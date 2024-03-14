<?php

namespace XCurrency\WpMVC\Routing;

class Response
{
    public static function send(array $data, int $status_code = 200, array $headers = [])
    {
        static::set_headers($headers);
        return \compact('data', 'status_code');
    }
    public static function set_headers(array $headers, bool $default = \true)
    {
        if (\headers_sent()) {
            return;
        }
        if ($default) {
            $default_headers = ['Content-Type' => 'application/json', 'charset' => get_option('blog_charset')];
            $headers = \array_merge($default_headers, $headers);
        }
        foreach ($headers as $key => $value) {
            \header("{$key}: {$value}");
        }
    }
}
