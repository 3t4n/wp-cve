<?php

namespace Mnet\Admin;

use Arr;

class MnetSite
{
    protected static $instance = null;
    protected $data;
    public function __construct($details)
    {
        $this->data = $details;
    }

    public static function getInstance($refresh = false)
    {
        if (is_null(static::$instance) || $refresh) {
            static::$instance = new MnetSite(MnetOptions::getSiteDetails());
        }
        return static::$instance;
    }

    public function data()
    {
        return $this->data;
    }

    public function invalidate()
    {
        $this->data = [];
    }

    public function refresh($details)
    {
        $this->data = array_merge($this->data, $details);
    }

    public function __get($key)
    {
        return Arr::get($this->data, $key);
    }
}
