<?php

namespace Mnet\Admin;

use Arr;

class MnetUser
{
    protected static $instance = null;
    protected $data = [];
    protected $isNewPub = false;

    public function __construct($details)
    {
        $this->data = $details;
    }

    public static function getInstance($refresh = false)
    {
        if (is_null(static::$instance) || $refresh) {
            static::$instance = new MnetUser(MnetOptions::getUserDetails());
        }
        return static::$instance;
    }

    public function refresh($details)
    {
        if (Arr::get($details, 'crid') && Arr::get($this->data, 'crid') !== Arr::get($details, 'crid')) {
            $this->isNewPub = true;
        }
        $this->data = array_merge($this->data, $details);
    }

    public function data()
    {
        return $this->data;
    }

    public function invalidate()
    {
        $this->data = [];
    }

    public function isNewPub()
    {
        return $this->isNewPub;
    }

    public function __get($key)
    {
        return Arr::get($this->data, $key);
    }

    public function info()
    {
        $info = array();
        $info['id'] = Arr::get($this->data, 'crid');
        $info['email'] = Arr::get($this->data, 'email');
        $info['isEap'] = Arr::get($this->data, 'isEap');
        if (empty($info['email'])) {
            $info['wp_email'] = MnetPluginUtils::getUserEmailId();
        }
        return $info;
    }
}
