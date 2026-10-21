<?php
/**
 * Smarty shared plugin
 *
 * @package Smarty
 * @subpackage PluginsShared
 */

/**
 * Format a timestamp using strftime()-compatible format specifiers.
 *
 * strftime() is deprecated as of PHP 8.1. This helper maps the commonly used
 * specifiers to date() so Smarty plugins keep working on modern PHP versions.
 *
 * @param string $format    strftime format
 * @param int    $timestamp Unix timestamp
 * @return string
 */
function smarty_strftime($format, $timestamp)
{
    $aMap = array(
        'a' => 'D', 'A' => 'l', 'd' => 'd', 'e' => 'j', 'm' => 'm',
        'b' => 'M', 'h' => 'M', 'B' => 'F', 'y' => 'y', 'Y' => 'Y',
        'H' => 'H', 'I' => 'h', 'l' => 'g', 'M' => 'i', 'S' => 's',
        'p' => 'A', 'P' => 'a', 'w' => 'w', 'u' => 'N', 'U' => 'W',
        'W' => 'W', 'V' => 'W', 'G' => 'o', 'g' => 'y', 'z' => 'O',
        'Z' => 'T', 's' => 'U', 'n' => "\n", 't' => "\t",
    );
    $aSpecial = array(
        'F' => 'Y-m-d',
        'T' => 'H:i:s',
        'R' => 'H:i',
        'r' => 'h:i:s A',
        'D' => 'm/d/y',
        'c' => 'D M j H:i:s Y',
        'x' => 'm/d/y',
        'X' => 'H:i:s',
        'j' => 'z',
    );

    return preg_replace_callback('/%([a-zA-Z%])/', function ($aMatch) use ($aMap, $aSpecial, $timestamp) {
        $sSpecifier = $aMatch[1];
        if ($sSpecifier === '%') {
            return '%';
        }
        if ($sSpecifier === 'e') {
            return sprintf('%2d', date('j', $timestamp));
        }
        if ($sSpecifier === 'l') {
            return sprintf('%2d', date('g', $timestamp));
        }
        if ($sSpecifier === 'j') {
            return sprintf('%03d', date('z', $timestamp) + 1);
        }
        if (isset($aSpecial[$sSpecifier])) {
            return date($aSpecial[$sSpecifier], $timestamp);
        }
        if (isset($aMap[$sSpecifier])) {
            return date($aMap[$sSpecifier], $timestamp);
        }
        return $aMatch[0];
    }, $format);
}

/**
 * Function: smarty_make_timestamp<br>
 * Purpose:  used by other smarty functions to make a timestamp from a string.
 *
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param DateTime|int|string $string  date object, timestamp or string that can be converted using strtotime()
 * @return int
 */
function smarty_make_timestamp($string)
{
    if (empty($string)) {
        // use "now":
        return time();
    } elseif ($string instanceof DateTime) {
        return $string->getTimestamp();
    } elseif (strlen($string) == 14 && ctype_digit($string)) {
        // it is mysql timestamp format of YYYYMMDDHHMMSS?
        return mktime(substr($string, 8, 2),substr($string, 10, 2),substr($string, 12, 2),
                       substr($string, 4, 2),substr($string, 6, 2),substr($string, 0, 4));
    } elseif (is_numeric($string)) {
        // it is a numeric string, we handle it as timestamp
        return (int) $string;
    } else {
        // strtotime should handle it
        $time = strtotime($string);
        if ($time == -1 || $time === false) {
            // strtotime() was not able to parse $string, use "now":
            return time();
        }
        return $time;
    }
}