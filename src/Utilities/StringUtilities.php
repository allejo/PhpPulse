<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Utilities;

/**
 * This class contains static utilities used for validating strings
 *
 * @package allejo\DaPulse\Utilities
 * @since   0.1.0
 */
class StringUtilities
{
    /**
     * Determine whether a string is null or empty
     *
     * @param  string $string The string to test
     *
     * @return bool True if string is null or empty
     */
    public static function isNullOrEmpty ($string)
    {
        return (!isset($string) || empty($string) || ctype_space($string));
    }

    /**
     * Get the literal representation of a boolean value
     *
     * @param  bool $value A boolean value
     *
     * @return string True if a boolean is true
     */
    public static function booleanLiteral ($value)
    {
        return ($value) ? "true" : "false";
    }
}
