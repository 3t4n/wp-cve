<?php 
namespace GSLOGO;

if ( ! defined( 'ABSPATH' ) ) exit;

class Plugin {

    private static $_instance;

    public static function get_instance() {

        if ( ! self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public $column;
    public $cpt;
    public $hooks;
    public $metabox;
    public $notices;
    public $scripts;
    public $shortcode;
    public $builder;
    public $integrations;
    public $template_loader;

    public function __construct() {

        $this->column          = new Column;
        $this->cpt             = new Cpt;
        $this->hooks           = new Hooks;
        $this->metabox         = new Metabox;
        $this->notices         = new Notices;
        $this->scripts         = new Scripts;
        $this->shortcode       = new Shortcode;
        $this->builder         = new Builder;
        $this->integrations    = new Integrations;
        $this->template_loader = new Template_Loader;

        require_once GSL_PLUGIN_DIR . 'includes/asset-generator/gs-load-assets-generator.php';
        require_once GSL_PLUGIN_DIR . 'includes/demo-data/dummy-data.php';
        require_once GSL_PLUGIN_DIR . 'includes/gs-common-pages/gs-logo-common-pages.php';

    }

}

function plugin() {
    return Plugin::get_instance();
}

add_action('plugins_loaded', function() {
    plugin();
}, 0 );