<?php

namespace TotalContest\Helpers;


class DateTime
{
    /**
     *
     *
     * @param string $buffer
     * @param array|string $pattern
     * @return string
     * @access private
     */
    public static function _strptime_match(&$buffer, $pattern)
    {
        if (is_array($pattern)) {
            $pattern = implode('|', $pattern);
        }
        $pattern = '/^(' . $pattern . ')/i';

        $ret = null;
        if (preg_match($pattern, $buffer, $matches)) {
            $ret = $matches[0];

            //Remove the match from the buffer
            $buffer = preg_replace($pattern, '', $buffer);
        }
        return $ret;
    }

    /**
     *
     *
     * @param int $n
     * @param int $min
     * @param int $max
     * @return int
     * @access private
     */
    public static function _strptime_clamp($n, $min, $max)
    {
        return max(min($n, $max), $min);
    }

    /**
     *
     *
     * @param string $p
     * @return array
     * @access private
     */
    public static function _strptime_wdays($p)
    {
        $locales = array();

        for ($i = 0; $i < 7; $i++) {
            $dayTime = strtotime('next Sunday +' . $i . ' days');
            $locales[$i] = strftime('%' . $p, $dayTime);
        }

        return $locales;
    }

    /**
     *
     *
     * @param string $p
     * @return array
     * @access private
     */
    public static function _strptime_months($p)
    {
        $locales = array();

        for ($i = 1; $i <= 12; $i++) {
            $locales[$i] = strftime('%' . $p, mktime(0, 0, 0, $i));
        }

        return $locales;
    }

    /**
     *
     *
     * @param string $date
     * @param string $format
     * @return array
     */
    public static function strptime($date, $format)
    {
        //Default return values
        $tmSec = 0;
        $tmMin = 0;
        $tmHour = 0;
        $tmMday = 1;
        $tmMon = 1;
        $tmYear = 1900;
        $tmWday = 0;
        $tmYday = 0;

        $buffer = $date;
        $length = strlen($format);
        $lastc = null;

        for ($i = 0; $i < $length; $i++) {
            $c = $format[$i];

            //Remove spaces
            $buffer = ltrim($buffer);

            if ($lastc === '%') {
                switch ($c) {
                    case 'A':
                    case 'a':
                        self::_strptime_match($buffer, self::_strptime_wdays($c));
                        break;

                    case 'B':
                    case 'b':
                    case 'h':
                        $months = self::_strptime_months($c);
                        $month = self::_strptime_match($buffer, $months);
                        $tmMon = array_search($month, $months);
                        break;

                    case 'D':
                        //Unsupported by strftime on Windows
                        self::_strptime_match($buffer, '\d{2}\/\d{2}\/\d{2}');
                        break;

                    //case 'e':
                    case 'd':
                        $tmMday = (int)self::_strptime_match($buffer, '\d{2}');
                        break;

                    case 'F':
                        //Unsupported by strftime on Windows
                        if ($ret = self::_strptime_match($buffer, '\d{4}-\d{2}-\d{2}')) {
                            $frags = explode('-', $ret);
                            $tmYear = (int)$frags[0];
                            $tmMon = (int)$frags[1];
                            $tmMday = (int)$frags[2];
                        }
                        break;

                    case 'H':
                        $tmHour = (int)self::_strptime_match($buffer, '\d{2}');
                        break;

                    case 'M':
                        $tmMin = (int)self::_strptime_match($buffer, '\d{2}');
                        break;

                    case 'm':
                        $tmMon = (int)self::_strptime_match($buffer, '\d{2}');
                        break;

                    case 'S':
                        $tmSec = (int)self::_strptime_match($buffer, '\d{2}');
                        break;

                    case 'Y':
                        $tmYear = (int)self::_strptime_match($buffer, '\d{4}');
                        break;

                    case 'y':
                        $year = (int)self::_strptime_match($buffer, '\d{2}');
                        if ($year < 69) {
                            $tmYear = 2000 + $year;
                        } else {
                            $tmYear = 1900 + $year;
                        }
                        break;

                }
            } else {
                $buffer = ltrim($buffer, $c);
            }

            $lastc = $c;
        }

        //Date must exists!
        if (!checkdate($tmMon, $tmMday, $tmYear)) {
            return false;
        }

        //Clamp hours values
        $tmHour = self::_strptime_clamp($tmHour, 0, 23);
        $tmMin = self::_strptime_clamp($tmMin, 0, 59);
        $tmSec = self::_strptime_clamp($tmSec, 0, 61); //>59 = Leap seconds

        //Compute wday and yday
        $timestamp = mktime($tmHour, $tmMin, $tmSec, $tmMon, $tmMday, $tmYear);
        $tmWday = date('w', $timestamp);
        $tmYday = date('z', $timestamp);

        //Return
        $time = array();
        $time['tm_sec'] = $tmSec;
        $time['tm_min'] = $tmMin;
        $time['tm_hour'] = $tmHour;
        $time['tm_mday'] = $tmMday;
        $time['tm_mon'] = ($tmMon - 1); //0-11
        $time['tm_year'] = ($tmYear - 1900);
        $time['tm_wday'] = $tmWday;
        $time['tm_yday'] = $tmYday;
        $time['unparsed'] = $buffer; //Unparsed buffer
        return $time;
    }
}
