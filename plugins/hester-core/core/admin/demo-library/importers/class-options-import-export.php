<?php
/**
 * Hester Demo Library. Install a copy of a Hester demo to your website.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Core Options Import/Export.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Options_Import_Export {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Hester_Options_Import_Export Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Options_Import_Export
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Options_Import_Export ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Import options.
	 *
	 * @since  1.0.0
	 * @param  object $data Options from the demo.
	 */
	public function import( $data ) {

		// Have valid data?
		// If no data or could not decode.
		if ( empty( $data ) || ! is_array( $data ) ) {
			return new WP_Error( esc_html__( 'Import data could not be read. Please try a different file.', 'hester-core' ) );
		}

		// Hook before import.
		do_action( 'hester_core_before_options_import' );

		$data = apply_filters( 'hester_core_options_import_data', $data );

		foreach ( $data as $option_name => $option_value ) {

			if ( null !== $option_value ) {

				// Is option exist in defined array site_options()?
				if ( in_array( $option_name, self::options(), true ) ) {

					switch ( $option_name ) {

						// Set WooCommerce page ID by page Title.
						case 'woocommerce_shop_page_id':
						case 'woocommerce_cart_page_id':
						case 'woocommerce_checkout_page_id':
						case 'woocommerce_pay_page_id':
						case 'woocommerce_myaccount_page_id':
						case 'woocommerce_thanks_page_id':
						case 'woocommerce_edit_address_page_id':
						case 'woocommerce_view_order_page_id':
						case 'woocommerce_change_password_page_id':
						case 'woocommerce_logout_page_id':
						case 'woocommerce_lost_password_page_id':
						case 'page_for_posts':
						case 'page_on_front':
							$this->update_page_id_by_option_value( $option_name, $option_value );
							break;

						// Nav menu locations.
						case 'nav_menu_locations':
							$this->set_nav_menu_locations( $option_value );
							break;

						// Import WooCommerce category images.
						case 'woocommerce_product_cat':
							$this->set_woocommerce_product_cat( $option_value );
							break;

						// insert logo.
						case 'custom_logo':
							$this->insert_logo( $option_value );
							break;

						default:
							update_option( $option_name, $option_value );
							break;
					}
				}
			}
		}

		// Hook after import.
		do_action( 'hester_core_after_options_import' );
	}

	/**
	 * Export options.
	 *
	 * @since 1.0.0
	 */
	public static function export() {

		// Export data.
		$data = array();

		// Logo.
		if ( has_custom_logo() ) {
			$data['custom_logo'] = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
		}

		// Homepage settings.
		$data['show_on_front'] = get_option( 'show_on_front' );

		// Static homepage.
		if ( 'page' === $data['show_on_front'] ) {

			// Front Page.
			$page_on_front = get_option( 'page_on_front' );

			if ( $page_on_front ) {
				$data['page_on_front'] = get_the_title( $page_on_front );
			}

			// Posts page.
			$page_for_posts = get_option( 'page_for_posts' );

			if ( $page_for_posts ) {
				$data['page_for_posts'] = get_the_title( $page_for_posts );
			}
		}

		// Posts per page.
		$data['posts_per_page'] = get_option( 'posts_per_page' ); // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page

		// WooCommerce.
		if ( class_exists( 'WooCommerce' ) ) {

			// WooCommerce pages.
			$woocommerce_pages = array(
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_pay_page_id',
				'woocommerce_thanks_page_id',
				'woocommerce_myaccount_page_id',
				'woocommerce_edit_address_page_id',
				'woocommerce_view_order_page_id',
				'woocommerce_change_password_page_id',
				'woocommerce_logout_page_id',
				'woocommerce_lost_password_page_id',
			);

			foreach ( $woocommerce_pages as $page_id ) {
				$data[ $page_id ] = get_the_title( get_option( $page_id ) );
			}

			// WooCommerce options.
			$woocommerce_options = array(
				'woocommerce_enable_guest_checkout',
				'woocommerce_enable_checkout_login_reminder',
				'woocommerce_enable_signup_and_login_from_checkout',
				'woocommerce_enable_myaccount_registration',
				'woocommerce_registration_generate_username',
				'woocommerce_single_image_width',
				'woocommerce_thumbnail_image_width',
				'woocommerce_thumbnail_cropping',
				'woocommerce_thumbnail_cropping_custom_width',
				'woocommerce_thumbnail_cropping_custom_height',
				'woocommerce_shop_page_display',
				'woocommerce_category_archive_display',
				'woocommerce_default_catalog_orderby',
				'woocommerce_catalog_columns',
				'woocommerce_catalog_rows',
			);

			foreach ( $woocommerce_options as $id ) {
				$data[ $id ] = get_option( $id );
			}
		}

		// WPForms.
		if ( class_exists( 'WPForms' ) ) {
			$data['wpforms_settings'] = get_option( 'wpforms_settings' );
		}

		// Navigation.
		$nav_menu_locations = get_theme_mod( 'nav_menu_locations' );

		if ( ! empty( $nav_menu_locations ) && is_array( $nav_menu_locations ) ) {
			foreach ( $nav_menu_locations as $location => $menu_id ) {
				$term = get_term_by( 'id', $menu_id, 'nav_menu' );
				if ( is_object( $term ) ) {
					$data['nav_menu_locations'][ $location ] = $term->slug;
				}
			}
		}

		// Menus.
		$menus = get_terms( 'nav_menu' );

		if ( ! empty( $menus ) && is_array( $menus ) ) {
			foreach ( $menus as $menu ) {
				$data['menus'][ $menu->slug ] = $menu->name;
			}
		}

		// @todo wpforms settings.
		$data = apply_filters( 'hester_customizer_export_data', $data );
		$data = wp_json_encode( $data );

		$filesize = strlen( $data );

		// Set the download headers.
		nocache_headers();
		header( 'Content-disposition: attachment; filename=options.json' );
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Expires: 0' );
		header( 'Content-Length: ' . $filesize );

		// Serialize the export data.
		echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Start the download.
		die();
	}

	/**
	 * Valid Options.
	 *
	 * @since 1.0.0
	 *
	 * @return array $options List of valid options.
	 */
	private static function options() {

		// Valid options.
		$options = array(

			// General.
			'custom_logo',
			'nav_menu_locations',
			'show_on_front',
			'page_on_front',
			'page_for_posts',
			'posts_per_page',

			// WooCommerce pages.
			'woocommerce_shop_page_id',
			'woocommerce_cart_page_id',
			'woocommerce_checkout_page_id',
			'woocommerce_pay_page_id',
			'woocommerce_thanks_page_id',
			'woocommerce_myaccount_page_id',
			'woocommerce_edit_address_page_id',
			'woocommerce_view_order_page_id',
			'woocommerce_change_password_page_id',
			'woocommerce_logout_page_id',
			'woocommerce_lost_password_page_id',

			// WooCommerce Account & Privacy.
			'woocommerce_enable_guest_checkout',
			'woocommerce_enable_checkout_login_reminder',
			'woocommerce_enable_signup_and_login_from_checkout',
			'woocommerce_enable_myaccount_registration',
			'woocommerce_registration_generate_username',

			// WooCommerce thumbs.
			'woocommerce_single_image_width',
			'woocommerce_thumbnail_image_width',
			'woocommerce_thumbnail_cropping',
			'woocommerce_thumbnail_cropping_custom_width',
			'woocommerce_thumbnail_cropping_custom_height',

			// WooCommerce categories.
			'woocommerce_product_cat',

			// WPForms.
			'wpforms_settings',
		);

		return apply_filters( 'hester_core_site_options', $options );
	}

	/**
	 * Update post option.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $option_name  Option name.
	 * @param  mixed  $option_value Option value.
	 * @return void
	 */
	private function update_page_id_by_option_value( $option_name, $option_value ) {

		$page = get_page_by_title( $option_value );

		if ( is_object( $page ) ) {
			update_option( $option_name, $page->ID );
		}
	}

	/**
	 * In WP nav menu is stored as ( 'menu_location' => 'menu_id' );
	 * In export we send 'menu_slug' like ( 'menu_location' => 'menu_slug' );
	 * In import we set 'menu_id' from menu slug like ( 'menu_location' => 'menu_id' );
	 *
	 * @since 1.0.0
	 * @param array $nav_menu_locations Array of nav menu locations.
	 */
	private function set_nav_menu_locations( $nav_menu_locations = array() ) {

		$menu_locations = array();

		// Update menu locations.
		if ( isset( $nav_menu_locations ) ) {

			foreach ( $nav_menu_locations as $menu => $value ) {

				$term = get_term_by( 'slug', $value, 'nav_menu' );

				if ( is_object( $term ) ) {
					$menu_locations[ $menu ] = $term->term_id;
				}
			}

			set_theme_mod( 'nav_menu_locations', $menu_locations );
		}
	}

	/**
	 * Set WooCommerce category images.
	 *
	 * @since 1.0.0
	 * @param array $cats Array of categories.
	 */
	private function set_woocommerce_product_cat( $cats = array() ) {

		$menu_locations = array();

		if ( isset( $cats ) ) {

			foreach ( $cats as $key => $cat ) {

				if ( ! empty( $cat['slug'] ) && ! empty( $cat['thumbnail_src'] ) ) {

					$image = (object) hester_demo_importer()->sideload_image( $cat['thumbnail_src'] );

					if ( ! is_wp_error( $image ) ) {

						if ( isset( $image->attachment_id ) && ! empty( $image->attachment_id ) ) {

							$term = get_term_by( 'slug', $cat['slug'], 'product_cat' );

							if ( is_object( $term ) ) {
								update_term_meta( $term->term_id, 'thumbnail_id', $image->attachment_id );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Insert Logo By URL.
	 *
	 * @since  1.0.0
	 * @param  string $image_url Logo URL.
	 * @return void
	 */
	private function insert_logo( $image_url = '' ) {

		$data = (object) hester_demo_importer()->sideload_image( $image_url );

		if ( ! is_wp_error( $data ) ) {

			if ( isset( $data->attachment_id ) && ! empty( $data->attachment_id ) ) {
				set_theme_mod( 'custom_logo', $data->attachment_id );
			}
		}
	}
}
