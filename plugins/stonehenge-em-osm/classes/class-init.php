<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists('Stonehenge_EM_OSM') ) :
Class Stonehenge_EM_OSM extends Stonehenge_EM_OSM_Maps {

	var $plugin;
	var $text;
	var $options;
	var $is_ready;
	var $default_tiles;
	var $default_marker;
	var $icon_defaults = array(
		'shape'		=> 'circle',
		'color' 	=> 'blue',
		'icon'		=> '',
		'iconColor' => 'white',
		'prefix' 	=> 'fas'
	);


	#===============================================
	public function __construct() {
		$plugin 				= self::plugin();
		$this->plugin 			= $plugin;
		$this->text 			= $plugin['text'];
		$this->options 			= $plugin['options'];
		$this->is_ready 		= is_array( $this->options ) ? true : false;
		$this->default_tiles	= $this->get_default_maptiles();
		$this->default_marker 	= $this->get_default_marker();

		add_filter("{$this->plugin['slug']}_options", array($this, 'define_options'));

		if( $this->is_ready && stonehenge()->is_valid ) {
			add_filter('em_location_save_meta', array($this, 'save_maps_per_location'), 10, 2);

			// Admin.
			add_action('wp_loaded', array($this, 'restore'), 15, 1);
			add_filter('em_locate_template', array($this, 'replace_em_templates'), 10, 4);
			add_action('admin_footer', array($this, 'disable_google'));
			add_action('admin_init', array($this, 'alter_locations_table'));

			// Ajax Search.
			add_action('wp_ajax_osm_search_location', array($this, 'replace_ajax_search'));
			add_action('wp_ajax_norpiv_osm_search_location', array($this, 'replace_ajax_search'));

			// Maps.
			add_filter('em_event_output_placeholder', array($this, 'replace_location_map_placeholder'), 1, 3);
			add_filter('em_location_output_placeholder', array($this, 'replace_location_map_placeholder'), 1, 3);

			// Customize.
			add_action('stonehenge_after_options', array($this, 'custom_markers'), 10);

			// Shortcodes.
			remove_shortcode('locations_map');
			add_shortcode('locations_map', array($this, 'locations_map'));

			remove_shortcode('events_map');
			add_shortcode('events_map', array($this, 'events_map'));

			// Deprecated, but here for backward compatibility with older versions of Events Manager.
			remove_shortcode('locations-map');
			add_shortcode('locations-map', array($this, 'locations_map'));
			remove_shortcode('events-map');
			add_shortcode('events-map', array($this, 'events_map'));
		}
	}


	#===============================================
	public static function plugin() {
		return stonehenge_em_osm();
	}


	#===============================================
	public static function dependency() {
		$dependency = array(
			'events-manager/events-manager.php' => 'Events Manager',
		);
		return $dependency;
	}


	#===============================================
	public static function add_defaults() {
		return;
	}


	#===============================================
	public static function plugin_updated() {
		return;
	}


	#===============================================
	public static function show_notices() {
		return;
	}


	#===============================================
	public static function register_assets() {
		$plugin 	= self::plugin();
		$version 	= $plugin['version'];
		$url 		= plugins_url('/assets/', __FILE__);

		// Admin assets.
		wp_register_style('admin-em-osm-css', plugins_url('/assets/admin-em-osm.min.css', __DIR__), '', $version, 'all');
		wp_register_script('admin-em-osm-js', plugins_url('/assets/admin-em-osm.min.js', __DIR__), array('jquery'), $version, true);
		wp_localize_script('admin-em-osm-js', 'OSM', self::localize_assets());

		// Public assets.
		wp_register_style('public-em-osm-css', plugins_url('/assets/public-em-osm.min.css', __DIR__), '', $version, 'all');
		wp_register_script('public-em-osm-js', plugins_url('/assets/public-em-osm.min.js', __DIR__), array('jquery'), $version, true);

		// FontAwesome.
		wp_register_style('FontAwesome', '//use.fontawesome.com/releases/v5.14.0/css/all.css', '', '5.14.0', 'screen');
	}


	#===============================================
	public static function load_admin_assets() {
		wp_enqueue_style('admin-em-osm-css' );
		wp_enqueue_script('admin-em-osm-js');
//		wp_enqueue_style( 'FontAwesome' );
	}


	#===============================================
	public static function load_public_assets() {
		wp_enqueue_style( array('public-em-osm-css', 'stonehenge-css'));
		wp_enqueue_script( array('public-em-osm-js', 'parsley-validation', 'parsley-locale', 'parsley-locale-extra'));
		wp_enqueue_style( 'FontAwesome' );
	}


	#===============================================
	public function load_fontawesome() {
		wp_enqueue_style( 'FontAwesome' );
	}


	#===============================================
	public static function localize_assets()  {
		global $EM_OSM;
		$plugin 	= self::plugin();
		$text 		= $plugin['text'];

		if( is_array($plugin['options']) ) {
			$localize	= array(
				'AutoComplete' 		=> admin_url('admin-ajax.php') .'?action=osm_search_location',
				'apiKey' 			=> esc_js($plugin['options']['api']),
				'locale' 			=> strtolower(substr( get_bloginfo ( 'language' ), 0, 2 )),
				'filterApplied' 	=> __('Filter Applied', $text),
				'defaultMarker'		=> $EM_OSM->get_default_marker(),
				'defaultMap' 		=> $EM_OSM->get_default_maptiles(),
			);
			return $localize;
		}
		return;
	}

} // End class.

global $EM_OSM;
$EM_OSM = new Stonehenge_EM_OSM();

endif;