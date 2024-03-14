<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Css.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
class IfwPsn_Util_Parser_Css extends IfwPsn_Util_Parser_Abstract
{
    /**
     * @param $css
     * @return mixed
     */
    public static function sanitize($css)
    {
        $css = self::stripNullByte($css);

        return $css;
    }

    /**
     * @param $css
     * @return mixed
     */
    public static function compress($css)
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove space after colons
        $css = str_replace(': ', ':', $css);
        // Remove whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

        return $css;
    }

    /**
     * @param $css
     * @return mixed
     */
    public static function prepareForAmp($css)
    {
        $remove = array(
            ' !important',
            '!important'
        );

        $css = str_replace($remove, '', $css);

        return $css;
    }

    /**
     * @param $selector
     * @return bool
     */
    public static function isClassSelector($selector)
    {
        return strpos($selector, '.') === 0;
    }

    /**
     * @param $selector
     * @return bool
     */
    public static function isIdSelector($selector)
    {
        return strpos($selector, '#') === 0;
    }
}
