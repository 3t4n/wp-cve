<?php

class CRP_Addon {

    public $addonPath;
    public $addonDir;
    public $addonUrl;
    public $addonName;

    public function __construct( $name )
    {
        $this->addonPath = '/addons/' . $name;
        $this->addonDir = CustomRelatedPosts::get()->coreDir . $this->addonPath;
        $this->addonUrl = CustomRelatedPosts::get()->coreUrl . $this->addonPath;
        $this->addonName = $name;
    }
}