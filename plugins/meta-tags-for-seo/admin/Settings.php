<?php
namespace Pagup\MetaTags;
use Pagup\MetaTags\Core\Asset;

class Settings {

    public function __construct()
    {

        $settings = new \Pagup\MetaTags\Controllers\SettingsController;
        $metabox = new \Pagup\MetaTags\Controllers\MetaboxController;

        // Add settings page
        add_action( 'admin_menu', array( &$settings, 'add_settings' ) );

        // Add metabox to post-types
        add_action( 'add_meta_boxes', array(&$metabox, 'add_metabox') );

        // Save meta data
        add_action( 'save_post', array(&$metabox, 'metadata'));

        // Add setting link to plugin page
        $plugin_base = PMT_PLUGIN_BASE;
        add_filter( "plugin_action_links_{$plugin_base}", array( &$this, 'setting_link' ) );
        
        // Add styles and scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'assets') );

    }

    public function setting_link( $links ) {

        array_unshift( $links, '<a href="admin.php?page=meta-tags-for-seo">Settings</a>' );

        return $links;
    }

    public function assets() {
        if ( isset($_GET['page']) && !empty($_GET['page']) && ( $_GET['page'] === "meta-tags-for-seo" ) ) {
            Asset::style_remote('pmt__font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
            Asset::style('pmt__flexboxgrid', 'admin/assets/flexboxgrid.min.css');
            Asset::style('pmt__styles', 'admin/assets/app.css');
            Asset::script('pmt__vuejs', 'vendor/vue.min.js', array(), true);
            Asset::script('pmt__script', 'admin/assets/app.js', array(), true);
        }
    
    }
}

$settings = new Settings;