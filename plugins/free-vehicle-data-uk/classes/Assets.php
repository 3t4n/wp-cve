<?php
namespace FreeVehicleData;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Assets{

    public function __construct() 
    {
        add_action('wp_enqueue_scripts', [$this, 'IncludeAssets'], 9999);
        add_action('admin_enqueue_scripts', [$this, 'IncludeAdminAssets'], 9999);
    }

    public function IncludeAdminAssets()
    {
        wp_register_style('fvd_admin', FreeVehicleData()->sURL.'assets/css/admin.css', false, FreeVehicleData()->sVersion, 'all');
        wp_enqueue_style ('fvd_admin');
    }
    public function IncludeAssets()
    {
        wp_register_style('fvd-css', FreeVehicleData()->sURL.'assets/css/style.css', false, FreeVehicleData()->sVersion, 'all'); 
        wp_enqueue_style ('fvd-css');
       
       
    }
}
