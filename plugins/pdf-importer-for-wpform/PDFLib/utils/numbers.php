<?php


namespace rnpdfimporter\PDFLib\utils;


class numbers
{
    public static function numberToString($num)
    {
        $numstr=\strval($num);
        if(abs($num)<1.0)
        {
            $numParts=\explode('e-', \strval($num));
            if(count($numParts)>1)
            {
                $e = intval($numParts[1]);
                if ($e)
                {
                    $negative = $num < 0;
                    if ($negative)
                        $num *= -1;

                    $num *= pow(10, $e - 1);
                    $numStr = '0.' . str_repeat('0', $e) . \substr($num, 0, 2);
                    if ($negative)
                        $numstr .= '-' . $numstr;
                }
            }
        }else{
            $numParts=\explode('+', \strval($num));
            if(count($numParts)>1)
            {
                $e = intval($numParts[1]);
                if ($e > 20)
                {
                    $e -= 20;
                    $num /= pow(10, $e);
                    $numstr = \strval($num) . \str_repeat('0', $e + 1);
                }
            }
        }

        return $numstr;
    }

    public static function sizeInBytes($n)
    {
        return mb_strlen($n, '8bit');

    }

    public static function bytesFor($n)
    {
        $bytes=\array_fill(0,numbers::sizeInBytes($n),0);
        for ($i = 1; $i <= count($bytes); $i++) {
            $bytes[$i - 1] = $n >> ((count($bytes) - $i) * 8);
        }
        return $bytes;
    }
}