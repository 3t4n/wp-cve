<?php
/** LZW compression
 * @param string data to compress
 * @return string binary data
 */


// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class lzw.inc
 * @author Neil.zhou
 */
class WjsslLZW
{
    /**
     * undocumented function
     *
     * @return void
     */
    public function compress($data)
    {
        if (empty($data)) {
            return '';
        }
        $dict = array();
        $out = array();
        $currChar;
        $phrase = $data[0];
        $code = 256;
        $strlen = strlen($data);
        for ($i = 1; $i < $strlen; $i++) {
            $currChar = $data[$i];
            if (array_key_exists('_' . $phrase . $currChar, $dict)) {
                $phrase .= $currChar;
            }
            else {
                $out[] = strlen($phrase) > 1 ? $dict['_' . $phrase] : ord($phrase[0]);
                $dict['_' . $phrase . $currChar] = $code;
                $code ++;
                $phrase = $currChar;
            }
        }

        $out[] = strlen($phrase) > 1 ? $dict['_' . $phrase] : ord($phrase[0]);

        $out = $this->_charCodesArrayToUtf16Binary($out);
        $out = array_map('chr', $out);
        return implode('', $out);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function decompress($str, $is_utf16 = true)
    {
        if (empty($str)) {
            return '';
        }
        if (empty($is_utf16)) {
            $code_array = $this->_convertMbStrToCodeArray($str);
        } else {
            $code_array = $this->_convertRawStrToCodeArray($str);
        }

        $dict = array();
        $currChar = chr($code_array[0]);
        $oldPhrase = $currChar;
        $out = array($currChar);
        $code = 256;
        $phrase = "";
        $strlen = count($code_array);
        for ($i = 1; $i < $strlen; $i++) {
            $currCode = $code_array[$i];
            if ($currCode < 256) {
                $phrase = chr($code_array[$i]);
            }
            else {
                $phrase = array_key_exists('_' . $currCode, $dict) ? $dict['_' . $currCode] : ($oldPhrase . $currChar);
            }
            $out[] = $phrase;

            $currChar = $phrase[0];
            $dict['_' . $code] = $oldPhrase . $currChar;
            $code++;
            $oldPhrase = $phrase;
        }
        return implode('', $out);
    }

    private function _convertRawStrToCodeArray($str)
    {
        // convert UTF-16 to UTF-8 code array
        $strlen = strlen($str);
        $codes = array();
        for($i = 0; $i < $strlen; $i+=2) {
            $codes[] = ord($str[$i]) + (ord($str[$i+1]) << 8);
        }
        return $codes;
    }

    private function _charCodesArrayToUtf16Binary($arrayCharCodes)
    {

        $uint8Array = array();
        $length = count($arrayCharCodes);
        for ($i = 0; $i < $length; $i++) {
            $uint8Array[$i * 2] = $arrayCharCodes[$i] & 0x00FF;
            $uint8Array[$i * 2 + 1] = ($arrayCharCodes[$i] & 0xFF00) >> 8;
        }

        return $uint8Array;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    private function _convertMbStrToCodeArray($str)
    {
        $offset = 0;
        while($offset >= 0) {
            $codes[] = $this->_ordUTF8($str, $offset);
        }
        return $codes;
    }
    
    private function _ordUTF8($string, &$offset)
    {
        $code = ord(substr($string, $offset,1)); 
        if ($code >= 128) {        //otherwise 0xxxxxxx
            if ($code < 224) $bytesnumber = 2;                //110xxxxx
            else if ($code < 240) $bytesnumber = 3;        //1110xxxx
            else if ($code < 248) $bytesnumber = 4;    //11110xxx
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; $i++) {
                $offset ++;
                $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
                $codetemp = $codetemp*64 + $code2;
            }
            $code = $codetemp;
        }
        $offset += 1;
        if ($offset >= strlen($string)) $offset = -1;
        return $code;
    }
}
