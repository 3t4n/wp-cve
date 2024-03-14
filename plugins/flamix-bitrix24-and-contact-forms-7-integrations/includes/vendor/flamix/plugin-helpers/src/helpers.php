<?php

if (!function_exists('tap')) {
    /**
     * Tap, tap, tap....
     *
     * @param $value
     * @param callable $callback
     * @return mixed
     */
    function tap($value, callable $callback)
    {
        $callback($value);
        return $value;
    }
}

if (!function_exists('flamix_log')) {
    /**
     * flamix_log('Hello', [1,2,3], 'commerceml');
     *
     * @param string|bool|int $message Log message
     * @param array $context Data
     * @param string|null $chanel Folder
     * @return \Monolog\Logger
     * @throws Exception
     */
    function flamix_log($message, array $context = [], ?string $chanel = null): \Monolog\Logger
    {
        return \Flamix\Plugin\General\Helpers::log($message, $context, $chanel);
    }
}
