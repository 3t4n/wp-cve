<?php defined('ABSPATH') || exit;

return array(
    '/^(www\.)?google\.\w+/i'       => array('utm_medium' => 'organic', 'short_name' => 'google'),
    '/^(www\.)?bing\.\w+/i'         => array('utm_medium' => 'organic', 'short_name' => 'bing'),
    '/(search.yahoo\.\w+)$/i'       => array('utm_medium' => 'organic', 'short_name' => 'yahoo'),
    '/^(www\.)?yahoo\.\w+/i'        => array('utm_medium' => 'organic', 'short_name' => 'yahoo'),
    '/^(www\.)?baidu\.\w+/i'        => array('utm_medium' => 'organic', 'short_name' => 'baidu'),
    '/^(www\.)?yandex\.\w+/i'       => array('utm_medium' => 'organic', 'short_name' => 'yandex'),
    '/^(www\.)?aol\.\w+/i'          => array('utm_medium' => 'organic', 'short_name' => 'aol'),
    '/^(www\.)?ask\.\w+/i'          => array('utm_medium' => 'organic', 'short_name' => 'ask'),
    '/^(www\.)?duckduckgo\.\w+/i'   => array('utm_medium' => 'organic', 'short_name' => 'duckduckgo'),
    '/^(www\.)?ecosia\.\w+/i'       => array('utm_medium' => 'organic', 'short_name' => 'ecosia'),
    '/(search.brave\.\w+)$/i'       => array('utm_medium' => 'organic', 'short_name' => 'brave')
);
