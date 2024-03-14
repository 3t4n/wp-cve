<?php namespace BDroppy\Services\Config;


class Config
{
    public $api;
    public $catalog;
    public $setting;

    public function __construct()
    {
        $this->api = new apiConfig();
        $this->catalog = new CatalogConfig();
        $this->setting = new SettingConfig();
    }

}