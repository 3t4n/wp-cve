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
use TRS\EAA2C\Abstract_Button;

if ( ! class_exists( '\TRS\EAA2C\Single' ) ) {
	class Single extends Abstract_Button {

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
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $args = array() ) {

			$this->version = ENHANCED_AJAX_ADD_TO_CART;

			if ( ! empty( $args ) && is_array( $args ) ) {
				if ( isset( $args['product'] ) ) {
					$this->meta = $this->parse_shortcode_args_to_block_args( $args );
				}
				elseif ( isset( $args['products'] ) ) {
					$this->meta = $this->parse_attributes( $args );
				}
			}
		}

		public function parse_shortcode_args_to_block_args( $attributes = array() ) {

			$defaults = $this->meta;

			$newContentOrder = array();

			if ( isset( $attributes['product'] ) ) {
				if ( is_int( $product = intval( sanitize_text_field( $attributes['product'] ) ) ) ) {
					$defaults['products'] = array( array( 'id' => $product ) );
				}
				// elseif ( is_array( $attributes['product'] ) ) {

				// }
			}
			if ( isset( $attributes['variation'] ) ) {
				if ( is_int( $variation = intval( sanitize_text_field( $attributes['variation'] ) ) ) ) {
				// if ( is_int( $attributes['variation'] ) ) {
					$defaults['variations'] = array( array( 'id' => $variation ) );
					// $defaults['variations'][0]['id'] = $variation;
				}
				// elseif ( is_array( $attributes['variation'] ) ) {

				// }
			}
			if ( isset( $attributes['title'] ) ) {
				if ( strpos( strtolower( $attributes['title'] ), 'n' ) !== false ) {
					$defaults['contentVisibility']['title'] = false;
				}
				elseif ( strpos( strtolower( $attributes['title'] ), 't' ) !== false ) {
					$defaults['titleType'] = 'att';
				}
				elseif ( strpos( strtolower( $attributes['title'] ), 'b' ) !== false ) {
					$defaults['titleType'] = 'base';
				}
			}
			if ( isset( $attributes['quantity'] ) ) {
				if (  ! empty( $sanitized = sanitize_text_field( $attributes['quantity'] ) ) && is_int( $quantity = intval( $sanitized ) ) ) {
					$defaults['quantity']['default'] = $quantity;
					$defaults['contentVisibility']['quantity'] = false;
				}
				// elseif ( is_array( $attributes['quantity'] ) ) {

				// }
			}
			if ( isset( $attributes['show_quantity'] ) ) {
				if ( strpos( strtolower( $attributes['show_quantity'] ), 'n' ) !== false ) {
					$defaults['contentVisibility']['quantity'] = false;
				}
				if ( strpos( strtolower( $attributes['show_quantity'] ), 'y' ) !== false ) {
					$defaults['contentVisibility']['quantity'] = true;
				}
			}
			if ( isset( $attributes['show_price'] ) && ( ! isset( $attributes['order'] ) || empty( $attributes['order'] ) ) ) {
				// error_log( $attributes['show_price'] );
				$newContentOrder = $this->create_content_order_from_shortcode( $attributes['show_price'] );
				// error_log( wc_print_r( $newContentOrder, true ) );
				if ( ! empty( $newContentOrder ) ) {
					$defaults['contentOrder'] = $newContentOrder;
					$defaults['contentVisibility']['separator'] = true;
					$defaults['contentVisibility']['price'] = true;
				}
			}
			if ( isset( $attributes['button_text'] ) ) {
				if ( ! empty( $attributes['button_text'] ) ) {
					$defaults['buttonText'] = sanitize_text_field( $attributes['button_text'] );
					if ( empty ( $defaults['buttonText'] ) ) {
						$defaults['buttonText'] = __( 'Add to cart', 'woocommerce' );
					}
				} else {
					$defaults['buttonText'] = __( 'Add to cart', 'woocommerce' );
				}
			} else {
				$defaults['buttonText'] = __( 'Add to cart', 'woocommerce' );
			}
			if ( isset( $attributes['class'] ) ) {
				if ( ! empty( $class = sanitize_text_field( $attributes['class'] ) ) ) {
					$defaults['className'] = $class;
				}
			}

			if ( isset( $attributes['order'] ) ) {
				if ( ! empty( $order = sanitize_text_field( $attributes['order'] ) ) ) {
					$returnedContent = $this->create_block_display_from_order( $attributes['order'] );
					$defaults['contentOrder'] = $returnedContent['contentOrder'];
					$defaults['contentVisibility'] = $returnedContent['contentVisibility'];
					// this should overwrite all others visibility. This makes show_prioe and show_quantity be ignored.
				}
			}
			// error_log( wc_print_r( $defaults, true ) );

			// $this->meta = $defaults;
			// return $this->meta;
			return $defaults;
		}
	}
}