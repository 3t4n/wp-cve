<?php

if ( !defined( 'ABSPATH' ) ) {

	exit;
} // Exit if accessed directly

if ( !class_exists( 'TX_WC_Compare_Wishlist_Install' ) ) {

	/**
	 * Install plugin table and create the wishlist page
	 *
	 * @since 1.0.0
	 */
	class TX_WC_Compare_Wishlist_Install {

		/**
		 * Single instance of the class
		 *
		 * @var \TX_WC_Compare_Wishlist_Install
		 * @since 2.0.0
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @var array
		 * @since 1.0.0
		 */
		public $options;

		/**
		 * Returns single instance of the class
		 *
		 * @return \TX_WC_Compare_Wishlist_Install
		 * @since 2.0.0
		 */
		public static function get_instance(){

			if( is_null( self::$instance ) ){

				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Initiator. Replace the constructor.
		 *
		 * @since 1.0.0
		 */
		public function init() {

			$this->_add_pages();

			require_once 'compare/settings.php';
			require_once 'wishlist/settings.php';

			$this->options = tx_woocompare_get_settings();

			$this->default_options( $this->options );

			$this->options = tx_woowishlist_get_settings();

			$this->default_options( $this->options );
		}

		/**
		 * Set options to their default value.
		 *
		 * @param array $options
		 * @return bool
		 * @since 1.0.0
		 */
		public function default_options( $options ) {

			foreach( $options as $value ) {

				if ( isset( $value['default'] ) && isset( $value['id'] ) && ! get_option( $value['id'] ) ) {

					add_option( $value['id'], $value['default'] );
				}
			}
		}

		/**
		 * Add a pages.
		 *
		 * @return void
		 * @since 1.0.2
		 */
		private function _add_pages() {

			$this->_add_page( 'compare', __( 'Compare', 'tx' ) );
			$this->_add_page( 'wishlist', __( 'Wishlist', 'tx' ) );
		}

		/**
		 * Add a page.
		 *
		 * @return void
		 * @since 1.0.2
		 */
		private function _add_page( $page, $title ) {

			global $wpdb;

			$option    = get_option( 'tx_woo' . $page . '_page', '' );
			$shortcode = '[tx_woo_' . $page . '_table';

			if ( empty ( $option ) ) {

				$option = $wpdb->get_var( "SELECT `ID` FROM `{$wpdb->posts}` WHERE `post_name` = '{$page}' AND `post_type` = 'page' LIMIT 1;" );
			}

			if ( ! ( $option && get_post( $option ) ) ) {

				$page_options = array(
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_title'   => $title,
					'post_content' => $shortcode . ']'
				);
				$option = wp_insert_post( $page_options );

			} else {

				$post = get_post( $option );

				if( false === strpos( $post->post_content, $shortcode ) ) {

					$update = array(
						'ID'           => $option,
						'post_content' => $shortcode . "]\n" . $post->post_content,
					);
					wp_update_post( $update );
				}
			}
			update_option( 'tx_woo' . $page . '_page', $option );
		}
	}
}

/**
 * Unique access to instance of TX_WC_Compare_Wishlist_Install class
 *
 * @return \TX_WC_Compare_Wishlist_Install
 * @since 2.0.0
 */
function TX_WC_Compare_Wishlist_Install(){
	return TX_WC_Compare_Wishlist_Install::get_instance();
}