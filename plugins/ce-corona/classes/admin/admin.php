<?php
/**
 * Admin Class
 * 
 * @package Corona
 */
namespace CoderExpert\Corona;

defined( 'ABSPATH' ) or exit;

class Admin {
    /**
     * Invoked when Admin is called.
     *
     * @return void
     */
    public static function init(){
        Loader::add_action( 'admin_menu', Settings::class, 'init' );
        Loader::add_action( 'admin_enqueue_scripts', Admin::class, 'enqueue_scripts' );
        Loader::add_action( 'admin_init', Admin::class, 'maybe_redirect' );
        Loader::add_action( 'widgets_init', Admin::class, 'register_widget' );
    }
    public static function register_widget(){
        \register_widget( 'CoderExpert\Corona\Corona_Widget' );
    }
    public static function maybe_redirect(){
        if ( ! get_transient( 'corona_activation_redirect' ) ) {
			return;
		}
		if ( wp_doing_ajax() ) {
			return;
		}

		delete_transient( 'corona_activation_redirect' );
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
        }
        // Safe Redirect to Templately Page
        wp_safe_redirect( admin_url( 'admin.php?page=ce-corona' ) );
        exit;
    }
    /**
     * All admin scripts and stylesheets included here.
     * 
     * @return void
     */
    public static function enqueue_scripts( $hook ){
        \wp_enqueue_style( 'ce-corona-global', 
            CE_CORONA_ASSETS . 'css/ce-corona-global.css', array(  ), 
            CE_CORONA_VERSION, 'all'
        );
        if( $hook !==  'toplevel_page_ce-corona' ) {
            return;
        }
        \wp_enqueue_style( 'ce-corona-admin', 
            CE_CORONA_ASSETS . 'css/ce-corona-admin.css', array(  ), 
            CE_CORONA_VERSION, 'all'
        );

        \wp_enqueue_style( 'ce-corona-fonts', 
            CE_CORONA_ASSETS . 'css/corona-fonts.css', array(  ), 
            CE_CORONA_VERSION, 'all'
        );
        \wp_enqueue_script( 'ce-corona-admin', 
            CE_CORONA_ASSETS . 'js/corona-admin.js', array( 'jquery' ), 
            CE_CORONA_VERSION, true 
        );
    }
}