<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
include_once HMCABW_PATH . 'core/core.php';
include_once HMCABW_PATH . 'core/common.php';
include_once HMCABW_PATH . 'core/personal-settings.php';
include_once HMCABW_PATH . 'core/social-settings.php';
include_once HMCABW_PATH . 'core/template-settings.php';
include_once HMCABW_PATH . 'core/styles-post-settings.php';
/**
 * Our main plugin class
*/
class HMCABW_Master
{
    protected  $hmcabw_loader ;
    protected  $hmcabw_version ;
    public function __construct()
    {
        $this->hmcabw_version = HMCABW_VERSION;
        add_action( 'plugins_loaded', array( $this, 'hmcabw_load_plugin_textdomain' ) );
        $this->hmcabw_load_dependencies();
        $this->hmcabw_trigger_widget_hooks();
        $this->hmcabw_trigger_admin_hooks();
        $this->hmcabw_trigger_front_hooks();
    }
    
    function hmcabw_load_plugin_textdomain()
    {
        load_plugin_textdomain( HMCABW_TXT_DOMAIN, FALSE, HMCABW_TXT_DOMAIN . '/languages/' );
    }
    
    private function hmcabw_load_dependencies()
    {
        require_once HMCABW_PATH . 'widget/' . HMCABW_CLASSPREFIX . 'widget.php';
        require_once HMCABW_PATH . 'admin/' . HMCABW_CLASSPREFIX . 'admin.php';
        require_once HMCABW_PATH . 'front/' . HMCABW_CLASSPREFIX . 'front.php';
        require_once HMCABW_PATH . 'inc/' . HMCABW_CLASSPREFIX . 'loader.php';
        $this->hmcabw_loader = new HMCABW_Loader();
    }
    
    private function hmcabw_trigger_widget_hooks()
    {
        new Hmcab_Widget();
        add_action( 'widgets_init', function () {
            register_widget( 'Hmcab_Widget' );
        } );
    }
    
    private function hmcabw_trigger_admin_hooks()
    {
        $hmcabw_admin = new Hmcabw_Admin( $this->hmcabw_version() );
        $this->hmcabw_loader->add_action( 'admin_menu', $hmcabw_admin, HMCABW_PREFIX . 'admin_menu' );
        $this->hmcabw_loader->add_action( 'admin_enqueue_scripts', $hmcabw_admin, HMCABW_PREFIX . 'enqueue_assets' );
        $this->hmcabw_loader->add_action( 'wp_ajax_hmcabw_get_image', $hmcabw_admin, 'hmcabw_get_image' );
        $this->hmcabw_loader->add_action( 'wp_ajax_nopriv_hmcabw_get_image', $hmcabw_admin, 'hmcabw_get_image' );
        // User Profile Add
        $this->hmcabw_loader->add_action(
            'edit_user_profile',
            $hmcabw_admin,
            'cab_user_profile_new_fileds',
            0
        );
        $this->hmcabw_loader->add_action(
            'show_user_profile',
            $hmcabw_admin,
            'cab_user_profile_new_fileds',
            0
        );
        // User Profile Save
        $this->hmcabw_loader->add_action( 'personal_options_update', $hmcabw_admin, 'cab_user_profile_save_fileds' );
        $this->hmcabw_loader->add_action( 'edit_user_profile_update', $hmcabw_admin, 'cab_user_profile_save_fileds' );
    }
    
    private function hmcabw_trigger_front_hooks()
    {
        $hmcabw_front = new HMCABW_Front( $this->hmcabw_version() );
        $this->hmcabw_loader->add_action( 'wp_enqueue_scripts', $hmcabw_front, HMCABW_PREFIX . 'enqueue_assets' );
        $this->hmcabw_loader->add_filter( 'the_content', $hmcabw_front, 'hmcabw_author_info_display' );
    }
    
    public function hmcabw_run()
    {
        $this->hmcabw_loader->hmcabw_run();
    }
    
    public function hmcabw_version()
    {
        return $this->hmcabw_version;
    }

}