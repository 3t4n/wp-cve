<?php

use FluentSupport\App\Services\Helper;

if(!function_exists('FluentSupportApi')) {
    function FluentSupportApi($key = null)
    {
        $api = Helper::FluentSupport('api');
        return is_null($key) ? $api : $api->{$key};
    }
}
