<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * String helper class
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) ifeelweb.de
 * @version   $Id: String.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   IfwPsn_Util
 */ 
class IfwPsn_Util_String
{
    const COMPRESSION_TYPE_NONE = 'none';
    const COMPRESSION_TYPE_GZ = 'gz';

    public static $compressionTypeUsed;

    /**
     * @param $string
     * @return string
     */
    public static function compress($string)
    {
        self::$compressionTypeUsed = self::COMPRESSION_TYPE_NONE;

        if (function_exists('gzcompress')) {
            self::$compressionTypeUsed = self::COMPRESSION_TYPE_GZ;
            $string = gzcompress($string, 9);
        }

        return $string;
    }

    /**
     * @param $string
     * @param null $forceCompressionType
     * @return string
     */
    public static function uncompress($string, $forceCompressionType = null)
    {
        if (function_exists('gzuncompress') && ($forceCompressionType === null || $forceCompressionType === self::COMPRESSION_TYPE_GZ)) {
            $uncompressedString = gzuncompress($string);
            if ($uncompressedString !== false) {
                $string = $uncompressedString;
            }
        }

        return $string;
    }

    /**
     * @param $json
     * @return string
     */
    public static function jsonIndent($json) {

        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '  ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;

        for ($i=0; $i<=$strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }
}
