<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Abstract.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
abstract class IfwPsn_Util_Parser_Abstract
{
    /**
     * @param $string
     * @return mixed
     */
    public static function stripNullByte($string)
    {
        if (!empty($string)) {
            $string = str_replace(chr(0), '', $string);
        }

        return $string;
    }
}
