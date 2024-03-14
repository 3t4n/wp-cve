<?php

class EIC_Premium_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = EasyImageCollagePremium::get()->premiumDir . $this->addonPath;
        $this->addonUrl = EasyImageCollagePremium::get()->premiumUrl . $this->addonPath;
        $this->addonName = $name;
    }
}