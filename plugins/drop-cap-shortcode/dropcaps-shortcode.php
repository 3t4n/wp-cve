<?php
/*
Plugin Name: Drop Cap Shortcode
Plugin URI: http://ekakurniawan.com/
Description: This plugin give you freedom to insert dropcap or not. Just change your first letters into a shortcode. Example: [T]his is my paragraph with a drop cap. The first letter 'T' will turn into a drop cap.
Author: Eka Kurniawan, BestWebLayout
Version: 1.3
Author URI: http://ekakurniawan.com/
License: GPLv2 or later
*/

/*
* We need some CSS to position the dropcap, change this section to fit with your design.
*/
if ( ! function_exists( 'dropcap_css' ) ) {
	function dropcap_css() {
		wp_enqueue_style( 'dropcap_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
	}
}

/*
* Create multiple drop caps function from A to Z and quote.
*/
if ( ! function_exists( 'A_dc_func' ) ) {
	function A_dc_func() {
		$text = '<span class="dropcap">A</span>';
		return $text;
	}
}

if ( ! function_exists( 'B_dc_func' ) ) {
	function B_dc_func() {
		$text = '<span class="dropcap">B</span>';
		return $text;
	}
}

if ( ! function_exists( 'C_dc_func' ) ) {
	function C_dc_func() {
		$text = '<span class="dropcap">C</span>';
		return $text;
	}
}

if ( ! function_exists( 'D_dc_func' ) ) {
	function D_dc_func() {
		$text = '<span class="dropcap">D</span>';
		return $text;
	}
}

if ( ! function_exists( 'E_dc_func' ) ) {	
	function E_dc_func() {
		$text = '<span class="dropcap">E</span>';
		return $text;
	}
}

if ( ! function_exists( 'F_dc_func' ) ) {	
	function F_dc_func() {
		$text = '<span class="dropcap">F</span>';
		return $text;
	}
}

if ( ! function_exists( 'G_dc_func' ) ) {	
	function G_dc_func() {
		$text = '<span class="dropcap">G</span>';
		return $text;
	}
}

if ( ! function_exists( 'H_dc_func' ) ) {	
	function H_dc_func() {
		$text = '<span class="dropcap">H</span>';
		return $text;
	}
}

if ( ! function_exists( 'I_dc_func' ) ) {	
	function I_dc_func() {
		$text = '<span class="dropcap">I</span>';
		return $text;
	}
}

if ( ! function_exists( 'J_dc_func' ) ) {
	function J_dc_func() {
		$text = '<span class="dropcap">J</span>';
		return $text;
	}
}

if ( ! function_exists( 'K_dc_func' ) ) {	
	function K_dc_func() {
		$text = '<span class="dropcap">K</span>';
		return $text;
	}
}

if ( ! function_exists( 'L_dc_func' ) ) {	
	function L_dc_func() {
		$text = '<span class="dropcap">L</span>';
		return $text;
	}
}

if ( ! function_exists( 'M_dc_func' ) ) {	
	function M_dc_func() {
		$text = '<span class="dropcap">M</span>';
		return $text;
	}
}

if ( ! function_exists( 'N_dc_func' ) ) {	
	function N_dc_func() {
		$text = '<span class="dropcap">N</span>';
		return $text;
	}
}

if ( ! function_exists( 'O_dc_func' ) ) {	
	function O_dc_func() {
		$text = '<span class="dropcap">O</span>';
		return $text;
	}
}

if ( ! function_exists( 'P_dc_func' ) ) {	
	function P_dc_func() {
		$text = '<span class="dropcap">P</span>';
		return $text;
	}
}

if ( ! function_exists( 'Q_dc_func' ) ) {	
	function Q_dc_func() {
		$text = '<span class="dropcap">Q</span>';
		return $text;
	}
}

if ( ! function_exists( 'R_dc_func' ) ) {	
	function R_dc_func() {
		$text = '<span class="dropcap">R</span>';
		return $text;
	}
}

if ( ! function_exists( 'S_dc_func' ) ) {	
	function S_dc_func() {
		$text = '<span class="dropcap">S</span>';
		return $text;
	}
}

if ( ! function_exists( 'T_dc_func' ) ) {	
	function T_dc_func() {
		$text = '<span class="dropcap">T</span>';
		return $text;
	}
}

if ( ! function_exists( 'U_dc_func' ) ) {	
	function U_dc_func() {
		$text = '<span class="dropcap">U</span>';
		return $text;
	}
}

if ( ! function_exists( 'V_dc_func' ) ) {	
	function V_dc_func() {
		$text = '<span class="dropcap">V</span>';
		return $text;
	}
}

if ( ! function_exists( 'W_dc_func' ) ) {	
	function W_dc_func() {
		$text = '<span class="dropcap">W</span>';
		return $text;
	}
}

if ( ! function_exists( 'X_dc_func' ) ) {	
	function X_dc_func() {
		$text = '<span class="dropcap">X</span>';
		return $text;
	}
}

if ( ! function_exists( 'Y_dc_func' ) ) {	
	function Y_dc_func() {
		$text = '<span class="dropcap">Y</span>';
		return $text;
	}
}

if ( ! function_exists( 'Z_dc_func' ) ) {	
	function Z_dc_func() {
		$text = '<span class="dropcap">Z</span>';
		return $text;
	}
}

if ( ! function_exists( 'quote_dc_func' ) ) {
	function quote_dc_func() {
		$text = '<span class="dropcap">&quot;</span>';
		return $text;
	}
}

/* Function to add styles to the front-end. */
add_action( 'wp_head', 'dropcap_css' );
/* Adding a plugin support shortcode. */
add_shortcode( 'A', 'A_dc_func' );
add_shortcode( 'B', 'B_dc_func' );
add_shortcode( 'C', 'C_dc_func' );
add_shortcode( 'D', 'D_dc_func' );
add_shortcode( 'E', 'E_dc_func' );
add_shortcode( 'F', 'F_dc_func' );
add_shortcode( 'G', 'G_dc_func' );
add_shortcode( 'H', 'H_dc_func' );
add_shortcode( 'I', 'I_dc_func' );
add_shortcode( 'J', 'J_dc_func' );
add_shortcode( 'K', 'K_dc_func' );
add_shortcode( 'L', 'L_dc_func' );
add_shortcode( 'M', 'M_dc_func' );
add_shortcode( 'N', 'N_dc_func' );
add_shortcode( 'O', 'O_dc_func' );
add_shortcode( 'P', 'P_dc_func' );
add_shortcode( 'Q', 'Q_dc_func' );
add_shortcode( 'R', 'R_dc_func' );
add_shortcode( 'S', 'S_dc_func' );
add_shortcode( 'T', 'T_dc_func' );
add_shortcode( 'U', 'U_dc_func' );
add_shortcode( 'V', 'V_dc_func' );
add_shortcode( 'W', 'W_dc_func' );
add_shortcode( 'X', 'X_dc_func' );
add_shortcode( 'Y', 'Y_dc_func' );
add_shortcode( 'Z', 'Z_dc_func' );
add_shortcode( 'quote', 'quote_dc_func' );
?>