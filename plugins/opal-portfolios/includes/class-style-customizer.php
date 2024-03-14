<?php
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalportfolios
 * @author     Opal  Team <opalwordpressl@gmail.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class Opalportfolios_Scripts{
	/**
	 * Init
	 */
	public static function init(){
	
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'loadScripts') );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load3rdScripts' ) );
	}

	/**
	 * load script file in backend
	 */
	public static function loadScripts(){

		wp_enqueue_style('font-awesome-icon', PE_PLUGIN_URI . 'assets/css/font-awesome.min.css');
		wp_enqueue_style( 'isotope-css', PE_PLUGIN_URI.( 'assets/css/isotype.min.css' ), array(), 1.0 );
		wp_enqueue_script( 'isotope-js', PE_PLUGIN_URI.( 'assets/js/isotype.min.js' ), array( 'jquery' )  , '3.0.6', true );
        wp_enqueue_style('opal-portfolio-style', PE_PLUGIN_URI. 'assets/css/style.css');
	}

	/**
	 * load script file in backend
	 */
	public static function load3rdScripts(){
		
		//lightbox
        wp_enqueue_style('lightgallery-css', PE_PLUGIN_URI. 'assets/3rd/lightgallery/css/lightgallery.min.css');

        wp_enqueue_style('lg-transitions', PE_PLUGIN_URI. 'assets/3rd/lightgallery/css/lg-transitions.min.css');

        wp_enqueue_script('lightgallery-js', PE_PLUGIN_URI.( 'assets/3rd/lightgallery/js/lightgallery-all.min.js' ), array( 'jquery' ) , '1.6.11', true  );

        // swiper Slider
        wp_enqueue_style('swiper-css', PE_PLUGIN_URI. 'assets/3rd/swiper/swiper.css');

        wp_enqueue_script('jquery-swiper', PE_PLUGIN_URI.( 'assets/3rd/swiper/swiper.min.js' ), array( 'jquery' ) , '4.4.2', true );

        wp_enqueue_script('opal-portfolio-frontendjs', PE_PLUGIN_URI.( 'assets/js/frontend.js' ), array( 'jquery' ) , '1.0', true  );

	}

}
Opalportfolios_Scripts::init();