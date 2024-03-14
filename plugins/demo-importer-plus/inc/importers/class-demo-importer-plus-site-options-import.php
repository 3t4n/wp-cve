<?php
/**
 * Customizer Site options importer class.
 *
 * @since  1.0.0
 * @package Demo Importer Plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Site options importer class.
 *
 * @since  1.0.0
 */
class Demo_Importer_Plus_Site_Options_Import {

	/**
	 * Instance of Demo_Importer_Plus_Site_Options_Importer
	 *
	 * @since  1.0.0
	 * @var (Object) Demo_Importer_Plus_Site_Options_Importer
	 */
	private static $instance = null;

	/**
	 * Instanciate Demo_Importer_Plus_Site_Options_Importer
	 *
	 * @since  1.0.0
	 * @return (Object) Demo_Importer_Plus_Site_Options_Importer
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Site Options
	 */
	private static function site_options() {
		$default_options = array(
			'custom_logo',
			'nav_menu_locations',
			'show_on_front',
			'page_on_front',
			'page_for_posts',
			'permalink_structure',

			// Plugin: Elementor.
			'elementor_container_width',
			'elementor_cpt_support',
			'elementor_css_print_method',
			'elementor_default_generic_fonts',
			'elementor_disable_color_schemes',
			'elementor_disable_typography_schemes',
			'elementor_editor_break_lines',
			'elementor_exclude_user_roles',
			'elementor_global_image_lightbox',
			'elementor_page_title_selector',
			'elementor_scheme_color',
			'elementor_scheme_color-picker',
			'elementor_scheme_typography',
			'elementor_space_between_widgets',
			'elementor_stretched_section_container',
			'elementor_load_fa4_shim',
			'elementor_active_kit',

			// Plugin: WooCommerce.
			// Pages.
			'woocommerce_shop_page_title',
			'woocommerce_cart_page_title',
			'woocommerce_checkout_page_title',
			'woocommerce_myaccount_page_title',
			'woocommerce_edit_address_page_title',
			'woocommerce_view_order_page_title',
			'woocommerce_change_password_page_title',
			'woocommerce_logout_page_title',

			// Account & Privacy.
			'woocommerce_enable_guest_checkout',
			'woocommerce_enable_checkout_login_reminder',
			'woocommerce_enable_signup_and_login_from_checkout',
			'woocommerce_enable_myaccount_registration',
			'woocommerce_registration_generate_username',

			// Categories.
			'woocommerce_product_cat',

			// PostX Support.
			'ultp-widget',

			// WP Travel Engine
			'primary_pricing_category',
			'wp_travel_engine_settings',
			'wp_travel_engine_permalinks',
			'wptravelengine_trip_version',
			'wptravelengine_version',
			'wptravelengine_since',
		);

		return apply_filters( 'demo-importer-plus:importable-site-options', $default_options );
	}

	/**
	 * Import site options.
	 *
	 * @param  (Array) $options Array of site options to be imported from the demo.
	 */
	public function import_options( $options = array() ) {

		if ( ! isset( $options ) ) {
			return;
		}

		foreach ( $options as $option_name => $option_value ) {

			if ( ! empty( $option_value ) ) {

				if ( in_array( $option_name, self::site_options(), true ) ) {

					switch ( $option_name ) {

						case 'woocommerce_shop_page_title':
						case 'woocommerce_cart_page_title':
						case 'woocommerce_checkout_page_title':
						case 'woocommerce_myaccount_page_title':
						case 'woocommerce_edit_address_page_title':
						case 'woocommerce_view_order_page_title':
						case 'woocommerce_change_password_page_title':
						case 'woocommerce_logout_page_title':
								$this->update_woocommerce_page_id_by_option_value( $option_name, $option_value );
							break;

						case 'page_for_posts':
						case 'page_on_front':
								$this->update_page_id_by_option_value( $option_name, $option_value );
							break;

						case 'nav_menu_locations':
								$this->set_nav_menu_locations( $option_value );
							break;

						case 'woocommerce_product_cat':
								$this->set_woocommerce_product_cat( $option_value );
							break;

						case 'custom_logo':
								$this->insert_logo( $option_value );
							break;

						case 'elementor_active_kit':
							if ( '' !== $option_value ) {
								$this->set_elementor_kit();
							}
							break;

						default:
							update_option( $option_name, $option_value );
							break;
					}
				}
			}
		}
	}

	/**
	 * Update post option
	 */
	private function set_elementor_kit() {

		// Update Elementor Theme Kit Option.
		$args = array(
			'post_type'   => 'elementor_library',
			'post_status' => 'publish',
			'numberposts' => 1,
			'meta_query'  => array(
				array(
					'key'   => '_demo_importer_plus_sites_imported_post',
					'value' => '1',
				),
				array(
					'key'   => '_elementor_template_type',
					'value' => 'kit',
				),
			),
		);

		$query = get_posts( $args );
		if ( ! empty( $query ) && isset( $query[0] ) && isset( $query[0]->ID ) ) {
			update_option( 'elementor_active_kit', $query[0]->ID );
		}
	}

	/**
	 * Update post option
	 *
	 * @param  string $option_name  Option name.
	 * @param  mixed  $option_value Option value.
	 */
	private function update_page_id_by_option_value( $option_name, $option_value ) {
		$page = get_page_by_title( $option_value );
		if ( is_object( $page ) ) {
			update_option( $option_name, $page->ID );
		}
	}

	/**
	 * Update WooCommerce page ids.
	 *
	 * @param  string $option_name  Option name.
	 * @param  mixed  $option_value Option value.
	 */
	private function update_woocommerce_page_id_by_option_value( $option_name, $option_value ) {
		$option_name = str_replace( '_title', '_id', $option_name );
		$this->update_page_id_by_option_value( $option_name, $option_value );
	}

	/**
	 * In WP nav menu is stored as ( 'menu_location' => 'menu_id' );
	 * In export we send 'menu_slug' like ( 'menu_location' => 'menu_slug' );
	 * In import we set 'menu_id' from menu slug like ( 'menu_location' => 'menu_id' );
	 *
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
	 * @param array $cats Array of categories.
	 */
	private function set_woocommerce_product_cat( $cats = array() ) {

		$menu_locations = array();

		if ( isset( $cats ) ) {

			foreach ( $cats as $key => $cat ) {

				if ( ! empty( $cat['slug'] ) && ! empty( $cat['thumbnail_src'] ) ) {

					$image = (object) Demo_Importer_Plus::sideload_image( $cat['thumbnail_src'] );

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
	 * Insert Logo By URL
	 *
	 * @param  string $image_url Logo URL.
	 */
	private function insert_logo( $image_url = '' ) {
		$attachment_id = $this->download_image( $image_url );
		if ( $attachment_id ) {
			Demo_Importer_Plus_WXR_Importer::instance()->track_post( $attachment_id );
			set_theme_mod( 'custom_logo', $attachment_id );
		}
	}

	/**
	 * Download image by URL
	 *
	 * @param  string $image_url Logo URL.
	 */
	private function download_image( $image_url = '' ) {
		$data = (object) Demo_Importer_Plus::sideload_image( $image_url );

		if ( ! is_wp_error( $data ) ) {
			if ( isset( $data->attachment_id ) && ! empty( $data->attachment_id ) ) {
				return $data->attachment_id;
			}
		}

		return false;
	}

}
