<?php

class EIC_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = EasyImageCollage::get()->coreDir . $this->addonPath;
        $this->addonUrl = EasyImageCollage::get()->coreUrl . $this->addonPath;
        $this->addonName = $name;
    }
}