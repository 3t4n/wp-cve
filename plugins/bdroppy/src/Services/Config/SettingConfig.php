<?php namespace BDroppy\Services\Config;

class SettingConfig extends baseConfig {
    protected $base = 'setting';


    public function set($name, $value = false)
    {
        if(is_array($name))
        {
            $name['logger']    = isset($name['logger']);
        }

        return parent::set($name, $value);
    }

    public function getOrderErrorEmails()
    {
        return preg_split('/\r\n|[\r\n]/', $this->get('order_error_notification'));
    }
}