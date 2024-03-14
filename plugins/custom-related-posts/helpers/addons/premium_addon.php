<?php

class CRP_Premium_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = CustomRelatedPostsPremium::get()->premiumDir . $this->addonPath;
        $this->addonUrl = CustomRelatedPostsPremium::get()->premiumUrl . $this->addonPath;
        $this->addonName = $name;
    }
}