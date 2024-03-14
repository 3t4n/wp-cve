<?php
/**
 * Class to load all the FDP addons.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


class FDP_Load_Addons {
	
	/**
	 * FDP Custom Addons.
	 *
	 * @var array $addons
	 * @since  2.8.0
	 */		
	private $addons;

    /**
	 * Settings Titles.
	 *
	 * @var array $addons
	 * @since  2.8.0
	 */		
	private $titles;

    /**
	 * User capabiity.
	 *
	 * @var array $capability
	 * @since  2.8.0
	 */		
	private $capability;

    /**
	 * Array of FDP json.
	 *
	 * @var array $fdp_jsones
	 * @since  2.8.0
	 */		
	private $fdp_jsones;

    /**
     * Default construct function.
     * 
     * @since 2.8.0
     *
     */
	public function __construct() {
        $this->addons = eos_dp_get_option( 'fdp_addons', array() );
        $this->fdp_jsones = array();
        add_filter( 'fdp_pages', array( $this, 'fdp_pages' ) );
        add_filter( 'eos_dp_deactivation_pages', array( $this, 'fdp_pages' ) );
        $this->titles = array();
        add_action( 'admin_menu', array( $this, 'add_fdp_menu' ) );
        add_action( 'admin_menu', array( $this, 'add_wp_menu' ), 9999 );
        if( eos_dp_is_fdp_page() ) {
            add_action( 'admin_enqueue_scripts', 'eos_dp_scripts', 999999 );
        }
    }

    /**
     * Load FDP addons.
     * 
     * @since 2.8.0
     *
     */    
    public function add_fdp_menu() {
        $capability = current_user_can( 'fdp_plugins_viewer' ) ? 'read' : 'activate_plugins';
        $this->capability = apply_filters( 'eos_dp_settings_capability', $capability );
        if( ! empty ( $this->addons ) ) {
            foreach( $this->addons as $addon ) {
                $fdp_json =  WP_PLUGIN_DIR . '/' . dirname( sanitize_text_field( $addon ) ) . '/fdp.json';
                if( file_exists( $fdp_json ) ) {
                    $args = json_decode( stripslashes( sanitize_text_field( file_get_contents( $fdp_json ) ) ), true );
                    $this->fdp_jsones[ dirname( sanitize_text_field( $addon ) ) ] = $args;
                    if( isset( $args['parent_menu'] ) && isset( $args['submenu_name'] ) ) {
                        $this->titles[ dirname( sanitize_text_field( $addon ) ) ] = sanitize_text_field( $args['submenu_name'] );
                        fdp_add_submenu_page( 
                            sanitize_text_field( $args['parent_menu'] ),
                            'eos_dp_' . sanitize_key( dirname( sanitize_text_field( $addon ) ) ),
                            esc_html( $args['submenu_name'] ),
                            sanitize_key( $this->capability ),
                            array( $this, 'do_settings_page' ),
                            10
                        );
                    }
                }
            }
        }
    }

    /**
     * Load FDP addons.
     * 
     * @since 2.8.0
     *
     */    
    public function add_wp_menu() {
        if( ! empty ( $this->addons ) ) {
            foreach( $this->addons as $addon ) {
                    if( isset( $this->fdp_jsones[ dirname( sanitize_text_field( $addon ) ) ] ) ) {
                        $args = $this->fdp_jsones[ dirname( sanitize_text_field( $addon ) ) ];
                        if( isset( $args['description'] ) ) {
                            add_action( 'fdp_addon_description', array( $this, 'add_description' ) );
                        }
                        if( isset( $args['submenu_name'] ) ) {           
                            add_submenu_page( 
                                'fdp_hidden_menu',
                                esc_html( $args['submenu_name'] ),
                                esc_html( $args['submenu_name'] ),
                                sanitize_key( $this->capability ),
                                'eos_dp_' . sanitize_key( dirname( sanitize_text_field( $addon ) ) ),
                                array( $this, 'do_settings_page' ),
                                220
                            );
                        }
                    }
                
            }
        }
    }
 
    /**
     * Output the add-on settings page.
     * 
     * @since 2.8.0
     *
     */ 
    public function do_settings_page() {
        if( ! isset( $_GET['page'] ) || false === strpos( sanitize_text_field( $_GET['page'] ), 'eos_dp_' ) ) {
            return;
        }
        $addon_slug = ltrim( sanitize_text_field ( $_GET['page'] ), 'eos_dp_' );
        fdp_add_plugins_settings_page( sanitize_key( sanitize_text_field( $addon_slug ) ), $this->titles[ esc_attr( $addon_slug ) ] );
    }

    /**
     * Add the description.
     * 
     * @param string $page_slug
     * @since 2.8.0
     *
     */ 
    public function add_description( $page_slug ) {
        if( isset( $this->fdp_jsones[ str_replace( 'eos_dp_', '', sanitize_text_field( $page_slug ) ) ] ) ) {
            $args = $this->fdp_jsones[ str_replace( 'eos_dp_', '', sanitize_text_field( $page_slug ) ) ];
            echo '<p>' . esc_html( $args['description'] ) . '</p>';
        }
    }

    /**
     * Add the add-on page to the FDP pages.
     * 
     * @since 2.8.0
     *
     */ 
    public function fdp_pages( $fdp_pages ) {
        if( ! empty ( $this->addons ) ) {
            foreach( $this->addons as $addon ) {
                $fdp_pages[] = 'eos_dp_' . sanitize_key( sanitize_text_field( dirname( $addon ) ) );
            }
        }
        return $fdp_pages;
    }
}