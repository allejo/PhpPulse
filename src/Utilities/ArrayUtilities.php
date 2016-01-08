<?php

/**
 * This file contains the ArrayUtilities class
 *
 * @copyright 2015 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Utilities;

/**
 * This class contains static utilities used for manipulating arrays.
 *
 * The original authors for the respective functions are listed in the documentation.
 *
 * @package allejo\DaPulse\Utilities
 * @since   0.1.0
 */
class ArrayUtilities
{
    /**
     * Search a two-dimensional array based on a specific key located in one of the inner arrays.
     *
     * In the following example array, this function could search each 'first_name' key in each of the inner arrays.
     *
     * ```php
     * $records = array(
     *    array(
     *      'id' => 2135,
     *      'first_name' => 'John',
     *      'last_name' => 'Doe',
     *    ),
     *    array(
     *      'id' => 3245,
     *      'first_name' => 'Sally',
     *      'last_name' => 'Smith',
     *    )
     * );
     * ```
     *
     * @param  array  $array  The target two-dimensional array we will be searching
     * @param  string $column The name of the key that each inner array will have whose value will be checked
     * @param  string $search The value that will be searched for
     *
     * @since  0.1.0
     *
     * @return mixed  This function returns an int or string if the key is found. This function will return false if the
     *                key was not found; be sure to use "===" for comparison.
     */
    public static function array_search_column($array, $column, $search)
    {
        if (function_exists('array_column'))
        {
            return array_search($search, array_column($array, $column));
        }
        else
        {
            foreach ($array as $key => $val)
            {
                if ($val[$column] === $search)
                {
                    return $key;
                }
            }
        }

        return false;
    }

    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function array_merge_recursive_distinct (array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value)
        {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
            {
                $merged[$key] = self::array_merge_recursive_distinct($merged[$key], $value);
            }
            else
            {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}