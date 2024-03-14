<?php

/**
 * @package    Dicode_Icons_Pack
 * @subpackage Dicode_Icons_Pack/public
 * @author     Designinvento <team@designinvento.net>
 */
class Dicode_Icons_Pack_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// CSS

        //  Brands icons css
        if ( dicode_icons_get_option( 'dicode_icomb_icons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-icomb-icons', 
                DICODE_ICONS_ASSETS_URL . 'icomoon_brands/dicode-icomb-icons.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }

        //  Devicons icons css
        if ( dicode_icons_get_option( 'dicode_devicons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-devicons', 
                DICODE_ICONS_ASSETS_URL . 'devicons/devicons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }     

        // elegant icon css
        if ( dicode_icons_get_option( 'dicode_elegant_icons', 'dicode_icons_activation', 'on' ) == 'on' ){
            wp_enqueue_style(
                'dicode-elegant-icons', 
                DICODE_ICONS_ASSETS_URL . 'elegant/elegant-icons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }   

        //  Elusive icons css
        if ( dicode_icons_get_option( 'dicode_elusive_icons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-elusive-icons', 
                DICODE_ICONS_ASSETS_URL . 'elusive/elusive-icons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }     
        
        //  Ico font icons css
        if ( dicode_icons_get_option( 'dicode_icofont_icons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-icofont-icons', 
                DICODE_ICONS_ASSETS_URL . 'icofont/icofont.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }

        //  Icomoon icons css
        if ( dicode_icons_get_option( 'dicode_icomoon_icons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-icomoon-icons', 
                DICODE_ICONS_ASSETS_URL . 'icomoon/icomoon.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }

        //  Iconic icons css
        if ( dicode_icons_get_option( 'dicode_iconic_icons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-iconic-icons', 
                DICODE_ICONS_ASSETS_URL . 'iconic/iconic.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }     
        
        //  ion icons css
        if ( dicode_icons_get_option( 'dicode_ionicons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-ionicons', 
                DICODE_ICONS_ASSETS_URL . 'ionicons/ionicons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }   

        // linearicons icon
        if ( dicode_icons_get_option( 'dicode_linearicons', 'dicode_icons_activation', 'on' ) == 'on' ){
            wp_enqueue_style(
                'dicode-linearicons', 
                DICODE_ICONS_ASSETS_URL . 'linearicons/linearicons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }   

        //  Line Awesome icons css
        if ( dicode_icons_get_option( 'dicode_lineawesome', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-lineawesome-icons', 
                DICODE_ICONS_ASSETS_URL . 'line-awesome/line-awesome.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }        

        //  line-icons css
        if ( dicode_icons_get_option( 'dicode_lineicons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-lineicons', 
                DICODE_ICONS_ASSETS_URL . 'lineicons/lineicons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }   

        //  material design icons css
        if ( dicode_icons_get_option( 'material_icon', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-material_icons', 
                DICODE_ICONS_ASSETS_URL . 'material/material-icons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }

        //  Open Iconic icons
        if ( dicode_icons_get_option( 'dicode_open_iconic', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-open-iconic-icons', 
                DICODE_ICONS_ASSETS_URL . 'open-iconic/open-iconic.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }

        //  simple-line-icons css
        if ( dicode_icons_get_option( 'dicode_simple_lineicons', 'dicode_icons_activation', 'off' ) == 'on' ){
            wp_enqueue_style(
                'dicode-simple-lineicons', 
                DICODE_ICONS_ASSETS_URL . 'simple-lineicons/simple-lineicons.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );     
        }    
        
        // themify icon
        if ( dicode_icons_get_option( 'dicode_themify_icons', 'dicode_icons_activation', 'on' ) == 'on' ){
            wp_enqueue_style(
                'dicode-themify-icons',
                DICODE_ICONS_ASSETS_URL . 'themify/themify.min.css',
                NULL,
                DICODE_ICONS_PACK_VERSION
            );
        }

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}

}
