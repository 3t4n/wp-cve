<?php


namespace rnpdfimporter\PDFLib\utils;


class arrays
{
    public static function toUnit8Array($input)
    {
        if (\is_string($input))
        {
            return base64::decodeFromBase64DataUri(unpack('N', $input));
        }

        return [];
    }

    public static function arrayAsString($array)
    {
        $str = '';
        for ($idx = 0, $len = count($array); $idx < $len; $idx++)
        {
            $str .= strings::charFromCode($array[$idx]);
        }
        return $str;
    }

    public static function typedArrayFor ($value) {
      if (\is_array($value)) return $value;
      $length = \strlen($value);
      $typedArray = \array_fill(0,$length,0);
      for ($idx = 0; $idx < $length; $idx++) {
        $typedArray[$idx] = strings::charCode($value[$idx]);
      }
      return $typedArray;
    }
}