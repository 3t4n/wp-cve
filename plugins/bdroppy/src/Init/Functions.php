<?php

if ( ! function_exists( 'ddd' ) ) :
    function ddd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit();
    }
endif;

if ( ! function_exists( 'bdroppy_is_multi_array' ) ) :
    function bdroppy_is_multi_array($array)
    {
        foreach ($array as $v) {
            if (is_array($v)) return true;
        }
        return false;
    }
endif;

if ( ! function_exists( 'bdroppy_ago_time' ) ) :
    function bdroppy_ago_time($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
endif;
