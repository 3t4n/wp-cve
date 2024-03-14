<?php

class CRP_Settings {

    public $bvs;

    public function __construct()
    {
        require_once( CustomRelatedPosts::get()->coreDir . '/helpers/settings_structure.php');
        require_once( CustomRelatedPosts::get()->coreDir . '/vendor/bv-settings/bv-settings.php' );
        $this->bvs = new BV_Settings( array(
            'uid' => 'crp',
            'menu_title' => 'Custom Related Posts',
            'settings' => $settings_structure,
        ) );
    }

    public function get( $setting ) {
        return $this->bvs->get( $setting );
    }
}