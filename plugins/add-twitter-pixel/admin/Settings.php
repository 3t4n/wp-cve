<?php
namespace Pagup\Twitter;
use Pagup\Twitter\Core\Asset;

class Settings {

    public function __construct()
    {

        $settings = new \Pagup\Twitter\Controllers\SettingsController;
        $metabox = new \Pagup\Twitter\Controllers\MetaboxController;

        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );

        // Add metabox to post-types
        add_action( 'add_meta_boxes', array(&$metabox, 'add_metabox') );

        // Save meta data
        add_action( 'save_post', array(&$metabox, 'metadata'));

        // Add setting link to plugin page
        $plugin_base = ATP_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );
        
        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets') );

    }

    public function setting_link( $links ) {

        array_unshift( $links, '<a href="options-general.php?page=add-twitter-pixel">Settings</a>' );

        return $links;
    }

    public function assets() {

        if ( isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] === "add-twitter-pixel" ) {

            Asset::style_remote('atp__font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
            Asset::style('atp__flexboxgrid', 'flexboxgrid.min.css');
            Asset::style('atp__styles', 'app.css');
            Asset::script('atp__script', 'app.js');

        }
    
    }
}

$settings = new Settings;