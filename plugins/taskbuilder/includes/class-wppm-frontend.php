<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPPM_Frontend' ) ) :
  
    final class WPPM_Frontend {
    
        // constructor
        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts') );
            add_shortcode( 'wppm_projects', array( $this, 'wppm_projects' ) );
            add_shortcode( 'wppm_tasks', array( $this, 'wppm_tasks' ) );
            add_action('wp_ajax_nopriv_wppm_sign_in',array($this,'wppm_sign_in'));
            add_action('wp_ajax_nopriv_wppm_set_user_login',array($this,'wppm_set_user_login'));    
        }

        // Load scripts
        public function loadScripts(){
            //bootstrap
            wp_register_style('wppm-bootstrap-css', WPPM_PLUGIN_URL.'asset/css/wppm_bootstrap.css?version='.WPPM_VERSION );
            //admin scripts
            wp_register_script('wppm-admin', WPPM_PLUGIN_URL.'asset/js/admin.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-public', WPPM_PLUGIN_URL.'asset/js/public.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-modal', WPPM_PLUGIN_URL.'asset/js/modal.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-flatpickr-js', WPPM_PLUGIN_URL.'asset/lib/flatpickr/flatpickr.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-select2-js', WPPM_PLUGIN_URL.'asset/lib/select2/select2.min.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-gpopover-js', WPPM_PLUGIN_URL.'asset/lib/gpopover/jquery.gpopover.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-dragula-js', WPPM_PLUGIN_URL.'asset/lib/dragula/dragula.min.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_script('wppm-datatable-js', WPPM_PLUGIN_URL.'asset/lib/DataTables/datatables.min.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_style('wppm-public-css', WPPM_PLUGIN_URL . 'asset/css/public.css?version='.WPPM_VERSION );
            wp_register_style('wppm-admin-css', WPPM_PLUGIN_URL . 'asset/css/admin.css?version='.WPPM_VERSION );
            wp_register_style('wppm-modal-css', WPPM_PLUGIN_URL . 'asset/css/modal.css?version='.WPPM_VERSION );
            wp_register_style('wppm-flatpickr-css', WPPM_PLUGIN_URL . 'asset/lib/flatpickr/flatpickr.min.css?version='.WPPM_VERSION );
            wp_register_style('wppm-select2-css', WPPM_PLUGIN_URL . 'asset/lib/select2/select2.min.css?version='.WPPM_VERSION );
            wp_register_style('wppm-gpopover-css', WPPM_PLUGIN_URL . 'asset/lib/gpopover/jquery.gpopover.css?version='.WPPM_VERSION );
            wp_register_style('wppm-dragula-css', WPPM_PLUGIN_URL . 'asset/lib/dragula/dragula.min.css?version='.WPPM_VERSION );
            wp_register_script('wppm-datatable-js',WPPM_PLUGIN_URL.'asset/lib/DataTables/datatables.min.js?version='.WPPM_VERSION, array('jquery'));
            wp_register_style('wppm-datatable-css',WPPM_PLUGIN_URL.'asset/lib/DataTables/datatables.min.css?version='.WPPM_VERSION );
            //localize script
            $loading_html = '<div class="wppm_loading_icon"><img src="'.WPPM_PLUGIN_URL.'asset/images/ajax-loading.gif"></div>';
            $localize_script_data = apply_filters( 'wppm_public_localize_script', array(
                'ajax_url'             => admin_url( 'admin-ajax.php' ),
                'loading_html'         => $loading_html,
                'please_wait'          =>__('Please wait ...','taskbuilder'),
                'confirm'              =>__('Are you sure?','taskbuilder')
            ));
            wp_localize_script( 'wppm-public', 'wppm_admin', $localize_script_data );
            do_action('wppm_after_enqueue_script',$localize_script_data);
        }
          /**
         * Main shortcode
         */

        function wppm_projects(){
            ob_start();
            include WPPM_ABSPATH.'includes/frontend/shortcode.php';
            return ob_get_clean();
        }

        function wppm_tasks(){
            ob_start();
            include WPPM_ABSPATH.'includes/frontend/wppm_tasks_shortcode.php';
            return ob_get_clean();
        }

        function wppm_sign_in(){
            include WPPM_ABSPATH.'includes/frontend/wppm_sign_in.php';
            die();
        }

        function wppm_set_user_login(){
            include WPPM_ABSPATH.'includes/frontend/wppm_set_user_login.php';
        }

    }
endif;

new WPPM_Frontend();
