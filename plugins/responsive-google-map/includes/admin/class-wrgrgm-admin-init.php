<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRGRGM_Admin_Init {

    private static $instance;

    public static function initialize() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {
        
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function enqueue_scripts( $hook ) {
        
        if ( in_array( $hook, array( 'post.php', 'post-new.php', 'edit.php' ) ) ) {
            $screen = get_current_screen();

            if ( is_object( $screen ) && 'wrg_rgm' == $screen->post_type ) {
                
                if ( ! did_action( 'wp_enqueue_media' ) ) {
                    wp_enqueue_media();
                }

                $map_styles_data = wrg_rgm()->map_styles->default_styles_data();

                wp_enqueue_script( 'wrg_rgm_admin_script', WRG_RGM_PLUGIN_URL . 'dist/app.bundle.js', array(), WRG_RGM_VERSION, true );
                wp_localize_script( 'wrg_rgm_admin_script', 'RGM', array(
                    'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                    'WRG_RGM_PLUGIN_URL'    => WRG_RGM_PLUGIN_URL,
                    'GMAP_API_KEY'          => RGM_Settings::get_key(),
                    'GMAP_STYLES_DATA'      => $map_styles_data
                ));
            }
        }
    }
}

WRGRGM_Admin_Init::initialize();