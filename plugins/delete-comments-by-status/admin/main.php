<?php
if(!defined('WP_DEBUG')) { exit; }

require_once('form-handler.php');

class MSBD_DELCOM_Admin {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add admin menu items
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page(
            __( 'Delete Comments By Status', 'msbddelcom' ),
            __( 'Delete Comments', 'msbddelcom' ),
            'manage_options',
            'msbd-delete-comments',
            array( $this, 'plugin_pages' ),
            'dashicons-groups',
            26.91
        );
    }

    /**
     * View
     *
     * @return void
     */
    public function plugin_pages() {
        $page = isset($_GET['page']) ? msbd_sanitization($_GET['page']) : '';
        $id = isset($_GET['id']) ? intval( msbd_sanitization($_GET['id']) ) : 0;
        
        $action     = 'dashboard';
        if (isset($_POST['action'])) {
            $action = msbd_sanitization($_POST['action']);
        } elseif (isset($_GET['action'])) {
            $action = msbd_sanitization($_GET['action']);
        }

        $template = MSBDDELCOM_PATH . 'admin/views/' . $action . '.php';
        //echo $template;
        //exit;
        
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}


/*
 * 
 * */
new MSBD_DELCOM_Admin();
