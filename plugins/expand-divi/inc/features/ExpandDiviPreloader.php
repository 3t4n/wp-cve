<?php
/**
 * Expand Divi Pre-loader
 * hides the pages with a pre-loader until the page is fully loaded
 *
 * @package  ExpandDivi/ExpandDiviPreloader
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviPreloader {
	public $options;
	
	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
		add_action( 'wp_head', array( $this, 'expand_divi_preloader_output' ) );
	}

	/**
	 * outputs preloader code
	 *
	 * @return string
	 */
	function expand_divi_preloader_output() {
		$classes = get_body_class();
		if ( !in_array('et-bfb',$classes) && isset($_GET['et_fb']) != 1 ) {

			if ( isset( $this->options['preloader_img_url'] ) && $this->options['preloader_img_url'] !== '' ) {
				$img_url = $this->options['preloader_img_url'];
				echo '<style>div#overlay img {top: 50%;margin: auto;left: 0;right: 0;position: absolute;}#overlay{position:fixed;background:#fff;z-index:99999;height:100%;width:100%;}</style>
				<div id="overlay"><img src="' . esc_url( $img_url ) . '"/></div><script>(function($){$(window).on("load",function(){var de_overlay=$("#overlay");if(de_overlay.length){de_overlay.delay(200).fadeOut(500);}});})(jQuery);</script>';
			} else {
				echo '<style>.expand-divi-spinner{position:absolute;top:52%;left:0;right:0;margin:auto;width:40px;height:40px;border-top:5px double #000;border-radius:100%;animation:spin 2s infinite cubic-bezier(0.23, 0.3, 0.7, 0.4);}@keyframes spin {from{transform:rotate(0deg);}to{transform:rotate(360deg);}}#overlay{position:fixed;background:#fff;z-index:99999;height:100%;width:100%;}</style>
				<div id="overlay"><div class="expand-divi-spinner"></div></div>
				<script>(function($){$(window).on("load",function(){var de_overlay=$("#overlay");if(de_overlay.length){de_overlay.delay(200).fadeOut(500);}});})(jQuery);</script>';
			}
		} 
			
	}
}

new ExpandDiviPreloader();