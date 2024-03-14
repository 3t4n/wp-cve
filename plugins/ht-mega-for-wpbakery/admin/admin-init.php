<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class HTMegavc_Admin_Setting{

    public function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'htmegavc_enqueue_admin_scripts' ) );
        $this->HTMegavc_Admin_Settings_page();
    }

    /*
    *  Setting Page
    */
    public function HTMegavc_Admin_Settings_page() {
        require_once('include/class.settings-api.php');
        require_once('include/admin-setting.php');
    }

    /*
    *   Enqueue admin scripts
    */
    public function htmegavc_enqueue_admin_scripts(){
        wp_enqueue_style( 'htmegavc-admin', HTMEGAVC_URI . '/admin/assets/css/htmegavc_admin.css', false );
    }

}

new HTMegavc_Admin_Setting();