<?php

/**
 * This file contains the StringUtilities class
 *
 * @copyright 2015 Vladimir Jimenez
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
}
