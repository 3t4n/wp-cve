<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 *
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/admin
 * @author     TheRiteSites <contact@theritesites.com>
 */

namespace TRS\EAA2C;

use TRS\EAA2C\Single;

if ( ! class_exists( 'TRS\EAA2C\Admin' ) ) {
	class Admin {

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
		 */
		public function __construct() {

			$this->plugin_name = EAA2C_NAME;
			$this->version = ENHANCED_AJAX_ADD_TO_CART;

			add_shortcode( 'enh_ajax_add_to_cart_button', array( $this, 'enhanced_ajax_add_to_cart_shortcode' ) );
			add_shortcode( 'ajax_add_to_cart', array( $this, 'enhanced_ajax_add_to_cart_shortcode' ) );
			add_shortcode( 'a2c_button', array( $this, 'enhanced_ajax_add_to_cart_shortcode' ) );

		}

		public function register_scripts() {
			
		}

		/**
		 * Handle for variable product ajax add to cart shortcode. Calls the function to display the html.
		 * 
		 * @since 1.0.0
		 * 
		 * @param $atts	array	contains passed variables from shortcode
		 * @param $content	string	contains passed html between shortcode start and end
		 * 
		 * @return	$add_to_cart_html	html to display from shortcode logic
		 */
		public function enhanced_ajax_add_to_cart_shortcode( $atts, $content = null ) {
			$add_to_cart_html = '';
			$att_array = shortcode_atts(array(
				'product'		=> '',
				'variation'		=> '',
				'title'			=> '',
				'quantity'		=> '', // Added in version 1.1.0
				'show_quantity' => '', // Added in version 1.1.0
				'show_price'	=> '', // Added in version 1.3.0
				'button_text'	=> '', // Added in version 1.3.0
				'class'			=> '', // Added in version 2.0.0
				'order'			=> '', // Added in version 2.2.0
			), $atts);


			return $this->render_from_shortcode( $att_array, $content );
		}

		/**
		 * Generates html and sets up variables for javascript calls
		 * 
		 * @since 1.0.0
		 * 
		 * @param $att_array	array	attributes passed by shortcode
		 * 
		 * @return via writing and echoing html, returns all the html for buttons
		 */
		public function display_variable_product_add_to_cart( $att_array ) {
			_deprecated_function( __FUNCTION__, '2.0', 'render_from_shortcode' );

			return $this->render_from_shortcode( $att_array );
		}

		/**
		 * Converts shortcode attributes to block and renders
		 * 
		 * @since 2.0.0
		 * 
		 * @param $att_array	array	attributes passed by shortcode
		 * 
		 * @return via writing and echoing html, returns all the html for buttons.
		 */
		public function render_from_shortcode( $att_array = array(), $content = '' ) {
			if ( ! empty( $att_array ) ) {
				$block = new Single( $att_array );
				return $block->render();
			}
		}

		/**
		 * Blocks
		 */
		public function register_a2cp_single() {

			// Skip block registration if Gutenberg is not enabled/merged.
			if ( ! function_exists( 'register_block_type' ) || in_array( 'add-to-cart-pro/a2cp', get_dynamic_block_names() ) ) {
				return;
			}

			$dir = plugin_dir_path( dirname( __FILE__ ) ) . 'dist/blocks/';

			$index_js = 'a2cp.js';
			wp_register_script(
				'a2cp-block-editor',
				plugins_url( $index_js, $dir .'blocks/' ),
				array(
					'wp-blocks',
					'wp-i18n',
					'wp-element',
					'wp-components',
					'wp-block-editor',
					'wp-editor',
				),
				filemtime( "$dir/$index_js" )
			);

			$buttonText = get_option( 'a2cp_default_text' );
			$buttonText = empty( $buttonText ) || false == $buttonText ? __( 'Add to cart', 'woocommerce' ) : $buttonText;

			wp_localize_script( 'a2cp-block-editor', 'A2C', array(
				'ajax_url'			=> admin_url( 'admin-ajax.php' ),
				'debug'				=> EAA2C_DEBUG,
				'route'				=> get_site_url(),
				'baseURL'			=> get_rest_url() ,
				'nonce' 			=> wp_create_nonce( 'wp_rest' ),
				'customClass'		=> get_option( 'a2cp_custom_class' ),
				'buttonText'		=> $buttonText
			) );

			$dir = plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/a2cp/';
			$editor_css = 'editor.css';
			wp_register_style(
				'a2cp-block-editor-style',
				plugins_url( $editor_css, $dir . 'a2cp/' ),
				array(),
				filemtime( "$dir/$editor_css" )
			);

			$common_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/common/assets/css/';
			$style_css = 'style.css';
			wp_register_style(
				'a2cp-block',
				plugins_url( $style_css, $common_dir . 'css/' ),
				array(),
				filemtime( "$common_dir/$style_css" )
			);

			$attributes = array(
				'editMode' => array(
					'type' => 'boolean',
					'default' => true,
				),
				'isPreview' => array(
					'type' => 'boolean',
					'default' => false,
				),
				'buttonText' => array(
					'type' => 'string',
					'default' => $buttonText,
				),
				'contentOrder' => array(
					'type' => 'array',
					'default' => array(
						'title',
						'separator',
						'price',
						'quantity',
						'button',
					),
					'items' => array (
						'type'	=> 'string',
					),
				),
				'contentVisibility' => array(
					'type' => 'object',
					'default' => array(
						'title' => true,
						'price' => true,
						'quantity' => true,
						'button' => true,
						'separator' => true,
					),
					'items' => array (
						'type'	=> 'boolean',
					),
				),
				'order' => array(
					'type' => 'string',
					'default' => 'DESC',
				),
				'orderby' => array(
					'type' => 'string',
					'default' => 'name',
				),
				'products' => array(
					'type' => 'object',
					'default' => array(),
					'items' => array (
						'type'	=> 'array',
					),
				),
				'quantity' => array(
					'type' => 'object',
					'default' => array(
						'default' => 1,
						'min' => 1,
						'max' => -1,
					),
					'items' => array (
						'type'	=> 'int',
					),
				),
				'titleAction' => array(
					'type' => 'string',
					'default' => '',
				),
				'titleType' => array(
					'type' => 'string',
					'default' => 'full',
				),
				'variations' => array(
					'type' => 'object',
					'default' => array(),
					'items' => array (
						'type'	=> 'array',
					),
				)
			);

			register_block_type( 'add-to-cart-pro/a2cp', array(
				'editor_script' => 'a2cp-block-editor',
				'editor_style'  => 'a2cp-block-editor-style',
				'style'         => 'a2cp-block',
				'attributes' 	=> $attributes,
				'render_callback' => array( $this, 'render_from_block' ),
			) );

		}

		/**
		 * Converts shortcode attributes to block and renders
		 * 
		 * @since 2.0.0
		 * 
		 * @param $att_array	array	attributes passed by shortcode
		 * 
		 * @return via writing and echoing html, returns all the html for buttons.
		 */
		public function render_from_block( $raw_attributes = array(), $content = '' ) {
			if ( ! empty( $raw_attributes ) ) {
				$block = new Single( $raw_attributes );
				return $block->render();
			}
		}
	}
}