<?php
/**
 * Plugin Name: Hangeul Web Fonts
 * Description: Hangeul web fonts management plugin for WordPress.
 * Version: 0.4.2
 * Author: miracl2l22
 * License: GPL2
 *
 * Text Domain: hangeul-web-fonts
 * Domain Path: /languages/
 */

/*  Copyright 2016  miracl2l22  (email : miracl2l22@gmail.com)

    Hangeul Web Fonts is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    Hangeul Web Fonts is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Hangeul Web Fonts; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Exit if accessed directely
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Hangeul_Web_Font' ) ) {

	class Hangeul_Web_Fonts {

		private static $instance;

		private $admin_page;

		public function __construct() {
			add_action( 'plugins_loaded',			array( $this, 'add_plugin_textdomain') );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_fonts_css' ) );
			add_action( 'admin_menu',					array( $this, 'register_admin_fonts_css') );

			$this -> load_files();
		}

		public function add_plugin_textdomain() {
			load_plugin_textdomain( 'hangeul-web-fonts', false, basename( dirname(__FILE__) ) . '/languages/' );
		}

		public function load_files() {
			require_once ( 'includes/class-admin.php' );
		}

		public function register_fonts_css() {
			$admin_page = new stdClass();
			$admin_page->options = get_option( 'fonts_options' );

			// 미생체 - SDMiSaeng
			if ( isset($admin_page->options['is_sdmisaeng']) && $admin_page->options['is_sdmisaeng'] === '1' ) {
				wp_register_style('sdmisaeng', plugins_url( 'hangeul-web-fonts/css/SDMiSaeng.css' ) );
				wp_enqueue_style('sdmisaeng');
			}

			// 제주 서체 - Jeju Fonts
			$this->register_google_font('jejuhallasan');
			$this->register_google_font('jejugothic');
			$this->register_google_font('jejumyeongjo');

			// 나눔폰트 - Nanum Fonts
			$this->register_google_font('nanumbrushscript');
			$this->register_google_font('nanumpenscript');
			$this->register_google_font('nanumgothic');
			$this->register_google_font('nanummyeongjo');
		}

		public function register_admin_fonts_css() {
			// 미생체 - SDMiSaeng
			wp_register_style('fonts_admin', plugins_url( 'hangeul-web-fonts/css/admin.css' ) );
			wp_enqueue_style('fonts_admin');

			// 제주 서체 - Jeju Fonts
			$this->register_google_font_admin('jejuhallasan');
			$this->register_google_font_admin('jejugothic');
			$this->register_google_font_admin('jejumyeongjo');

			// 나눔폰트 - Nanum Fonts
			$this->register_google_font_admin('nanumbrushscript');
			$this->register_google_font_admin('nanumpenscript');
			$this->register_google_font_admin('nanumgothic');
			$this->register_google_font_admin('nanummyeongjo');
		}

		public function register_google_font( $font_name = '' ) {
			$admin_page = new stdClass();
			$admin_page->options = get_option( 'fonts_options' );

			if ( isset($admin_page->options['is_' . $font_name]) && $admin_page->options['is_' . $font_name] === '1' ) {
				wp_register_style( $font_name, 'http://fonts.googleapis.com/earlyaccess/' . $font_name . '.css' );
				wp_enqueue_style( $font_name );
			}
		}

		public function register_google_font_admin( $font_name = '' ) {
			wp_register_style( $font_name, 'http://fonts.googleapis.com/earlyaccess/' . $font_name . '.css' );
			wp_enqueue_style( $font_name );
		}

		public static function getInstance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance             = new Hangeul_Web_Fonts;
				self::$instance->admin_page = new Hangeul_Web_Fonts_admin;
			}
			return self::$instance;
		}
	}
}

function Hangeul_Web_Fonts(){
	return Hangeul_Web_Fonts::getInstance();
}

Hangeul_Web_Fonts();