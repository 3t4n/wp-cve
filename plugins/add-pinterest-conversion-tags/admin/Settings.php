<?php
namespace Pagup\pctag;
use Pagup\Pctag\Core\Asset;

class Settings {

    public function __construct()
    {

        $settings = new \Pagup\Pctag\Controllers\SettingsController;
        $metabox = new \Pagup\Pctag\Controllers\MetaboxController;

        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );

        // Add metabox to post-types
        add_action( 'add_meta_boxes', array(&$metabox, 'add_metabox') );

        // Save meta data
        add_action( 'save_post', array(&$metabox, 'save_meta'));

        // Add setting link to plugin page
        $plugin_base = PCTAG_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );
        
        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets') );

    }

    public function setting_link( $links ) {

        array_unshift( $links, '<a href="options-general.php?page=pctag">Settings</a>' );

        return $links;
    }

    public function assets() {

        Asset::style_remote('pctag_font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
        Asset::style('pctag_styles', 'app.css');
        Asset::script('pctag_repeater', 'admin/assets/petite-vue.js', array(), true);
        Asset::script('pctag_script', 'admin/assets/app.js', array(), true);
    
    }
}

$settings = new Settings;