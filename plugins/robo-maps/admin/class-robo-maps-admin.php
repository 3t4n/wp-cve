<?php
/*  
 * Robo Maps            http://robosoft.co/wordpress-google-maps
 * Version:             1.0.6 - 19837
 * Author:              Robosoft
 * Author URI:          http://robosoft.co
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:                Thu, 18 May 2017 11:11:10 GMT
 */

class Robo_Maps_Admin {

	private $robo_maps;

	private $version;

	public function __construct( $robo_maps, $version ) {
		$this->robo_maps = $robo_maps;
		$this->version = $version;
	}

	public function enqueue_styles() {
		$screen = get_current_screen();
		if( $screen->base=='post' || $screen->base=='edit' ||  $screen->base=='toplevel_page_robo-maps' ){
				wp_enqueue_style("wp-jquery-ui-dialog");
				wp_enqueue_style( $this->robo_maps, 					ROBO_MAPS_URL.'admin/css/robo-maps-admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->robo_maps."_bootstrap", 		ROBO_MAPS_URL.'addons/bootstrap/css/bootstrap.wp.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->robo_maps."_bootstrap_theme", 	ROBO_MAPS_URL.'addons/bootstrap/css/bootstrap-theme.min.css', array(), $this->version, 'all' );	
			
			if($screen->base=='toplevel_page_robo-maps'){
				wp_enqueue_style( $this->robo_maps."_magnific", ROBO_MAPS_URL . 'addons/magnific/magnific-popup.css', array(), $this->version, 'all' );
			}
		}
	}

	public function setup_menu(){
		add_menu_page( 'Robo Maps', 'Robo Maps', 'manage_options', 'robo-maps', array( $this, 'setup_form'), 'dashicons-location-alt' );
	}

	public function setup_form(){ 
		include( ROBO_MAPS_PATH . 'admin/partials/robo-maps-admin-display.php');
	}

	public function setup_button(){ 
		echo '<a href="#robo-map-modal" class="button" id="robo-map-tag" >'
				.'<span class="bootstrap-wrapper"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>'
				.'</span>'.__('Add Robo Map').'</a>';
		include( ROBO_MAPS_PATH . 'admin/partials/robo-maps-admin-display-form.php');
	}

	
	public function enqueue_scripts() {

		$screen = get_current_screen();
		if( $screen->base=='post' || $screen->base=='edit' ||  $screen->base=='toplevel_page_robo-maps' ){

			wp_register_script( $this->robo_maps, ROBO_MAPS_URL . 'admin/js/robo-maps-admin.js', array( 'jquery' ), $this->version, false );
			$translation_array = array( 
				'inputAddress' 			=> __('Please check input address or Coordinates'), 
				'roboMapsTitle' 		=> __('Robo Maps'),
				'roboMapsTitleCorrect' 	=> __('Robo maps'),
				'closeButton' 			=> __('Close'),
			);
			wp_localize_script( $this->robo_maps, 'robo_maps_trans', $translation_array );

			wp_enqueue_script( $this->robo_maps."_bootstrap", ROBO_MAPS_URL . 'addons/bootstrap/js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
			

			$key = get_option('robo-map-key', '');
			if($key) $key = '?key='.$key;

			wp_enqueue_script( $this->robo_maps.'_google', 'https://maps.google.com/maps/api/js'.$key, array( 'jquery' ), false, false );
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script( $this->robo_maps."_map", ROBO_MAPS_URL . 'js/jquery.ui.map.js', array( 'jquery' ), $this->version, false );
			//wp_enqueue_script( $this->robo_maps, ROBO_MAPS_URL . 'admin/js/robo-maps-admin.js', array( 'jquery' ), $this->version, false );	
			wp_enqueue_script( $this->robo_maps );

			if($screen->base=='toplevel_page_robo-maps'){
				wp_enqueue_script( $this->robo_maps."_magnific", ROBO_MAPS_URL . 'addons/magnific/jquery.magnific-popup.min.js', array( 'jquery' ), $this->version, false );
			}
		}
	}

}