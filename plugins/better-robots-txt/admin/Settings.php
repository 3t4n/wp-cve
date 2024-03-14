<?php
namespace Pagup\BetterRobots;
use Pagup\BetterRobots\Core\Asset;

class Settings {

    public function __construct()
    {

        $settings = new \Pagup\BetterRobots\Controllers\SettingsController;
        $metabox = new \Pagup\BetterRobots\Controllers\MetaboxController;

        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );

        add_action( 'wp_ajax_rt__options', array( &$settings, 'save_options' ) );
        add_action( 'wp_ajax_rt__onboarding', array( &$settings, 'onboarding' ) );

        add_filter( 'script_loader_tag', array( &$this, 'add_module_to_script' ), 10, 3 );

        // Add metabox to post-types
        add_action( 'add_meta_boxes', array(&$metabox, 'add_metabox') );

        // Save meta data
        add_action( 'save_post', array(&$metabox, 'metadata'));

        // Add setting link to plugin page
        $plugin_base = ROBOTS_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );
        
        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets') );

    }

    public function setting_link( $links ) {

        array_unshift( $links, '<a href="admin.php?page=better-robots-txt">Settings</a>' );

        return $links;
    }

    public function assets() {

        if ( isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] === "better-robots-txt" ) {

            if (ROBOTS_PLUGIN_MODE === "prod") {
            
                Asset::style('robots__styles', 'admin/ui/index.css');
                Asset::script('robots__main', 'admin/ui/index.js', array(), true);
            
            } else {
            
                Asset::script_remote('robots__client', 'http://localhost:3213/@vite/client', array(), true, true);
                Asset::script_remote('robots__main', 'http://localhost:3213/src/main.ts', array(), true, true);
            }

        }

        Asset::style('rt_styles', 'admin/assets/app.css');
        Asset::script('rt_script', 'admin/assets/app.js', array(), true);
    
    }

    function add_module_to_script( $tag, $handle, $src ) {

        if (ROBOTS_PLUGIN_MODE === "prod") {
            if ( 'robots__main' === $handle ) {
                $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
            }
        } else {
            if ( 'robots__client' === $handle || 'robots__main' === $handle ) {
                $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
            }
        }

        return $tag;
    }

}

$settings = new Settings;