<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_widgets_sub_page_to_list' ) ) {
	/**
	 * Function that add additional sub page item into general page list
	 *
	 * @param array $sub_pages
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_widgets_sub_page_to_list( $sub_pages ) {
		$sub_pages[] = 'QiAddonsForElementor_Admin_Page_Widgets';

		return $sub_pages;
	}

	add_filter( 'qi_addons_for_elementor_filter_add_welcome_sub_page', 'qi_addons_for_elementor_add_widgets_sub_page_to_list' );
}

if ( class_exists( 'QiAddonsForElementor_Admin_Sub_Pages' ) ) {
	class QiAddonsForElementor_Admin_Page_Widgets extends QiAddonsForElementor_Admin_Sub_Pages {

		public function __construct() {

			parent::__construct();

			add_action(
				'wp_ajax_qi_addons_for_elementor_action_framework_save_options',
				array(
					$this,
					'save_widgets',
				)
			);

		}

		function get_sidebar() {
			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/sidebar' );
		}

		function add_sub_page() {
			$this->set_base( 'widgets' );
			$this->set_menu_slug( 'qi_addons_for_elementor_widgets' );
			$this->set_title( esc_html__( 'Widgets', 'qi-addons-for-elementor' ) );
			$this->set_position( 3 );
			$this->set_atts( $this->set_atributtes() );
		}

		function set_atributtes() {

			$shortcodes          = $this->sort_by_subcategory( $this->get_shortcodes() );
			$disabled            = $this->disabled_shortcodes();
			$enabled_subcategory = $this->complete_enabled_subcategory( $shortcodes, $disabled );

			$atts = array(
				'shortcodes'          => $shortcodes,
				'disabled'            => $this->disabled_shortcodes(),
				'enabled_subcategory' => $enabled_subcategory,
			);

			return $atts;
		}

		function get_shortcodes() {
			$shortcodes_array = array();
			$shortcodes       = qi_addons_for_elementor_framework_get_framework_root()->get_shortcodes();

			foreach ( $shortcodes->get_shortcodes() as $shortcode ) {
				$shortcodes_array[ $shortcode->get_base() ]['base']          = basename( $shortcode->get_shortcode_path() );
				$shortcodes_array[ $shortcode->get_base() ]['title']         = $shortcode->get_name();
				$shortcodes_array[ $shortcode->get_base() ]['subcategory']   = $shortcode->get_subcategory();
				$shortcodes_array[ $shortcode->get_base() ]['demo']          = $shortcode->get_demo();
				$shortcodes_array[ $shortcode->get_base() ]['video']         = $shortcode->get_video();
				$shortcodes_array[ $shortcode->get_base() ]['documentation'] = $shortcode->get_documentation();
				$shortcodes_array[ $shortcode->get_base() ]['premium']       = stripos( $shortcode->get_category(), 'premium' ) ? true : false;
				$shortcodes_array[ $shortcode->get_base() ]['active']        = true;
			}

			$promo_shortcodes = qi_addons_for_elementor_promotion_shortcodes_list();
			$shortcodes_array = array_merge( $shortcodes_array, $promo_shortcodes );

			if ( ! empty( $shortcodes_array ) ) {
				//sort all widgets by title
				foreach ( $shortcodes_array as $key => $value ) {
					$sort_data[ $key ] = $value['title'];
				}
				array_multisort( $sort_data, SORT_ASC, $shortcodes_array );
			}

			return $shortcodes_array;
		}

		function sort_by_subcategory( $shortcodes ) {
			$formatted = array();
			foreach ( $shortcodes as $key => $shortcode ) {

				$subcategory_key = strtolower( str_replace( ' ', '-', $shortcode['subcategory'] ) );

				$formatted[ $subcategory_key ][ $key ] = $shortcode;
			}

			return $formatted;
		}

		function disabled_shortcodes() {

			$disabled = get_option( QI_ADDONS_FOR_ELEMENTOR_DISABLED_WIDGETS );

			if ( ! $disabled || empty( $disabled ) ) {
				return array();
			}

			return $disabled;
		}

		function complete_enabled_subcategory( $subcategory_shortcodes, $disabled ) {

			$enabled_subcategories = array();

			foreach ( $subcategory_shortcodes as $subcategory_key => $shortcodes ) {

				foreach ( $shortcodes as $shortcode_key => $shortcode ) {
					if ( key_exists( $shortcode_key, $disabled ) ) {
						unset( $subcategory_shortcodes[ $subcategory_key ] );
						break;
					}
				}
			}

			$enabled_subcategories = array_keys( $subcategory_shortcodes );

			return $enabled_subcategories;

		}

		function save_widgets() {

			if ( current_user_can( 'edit_theme_options' ) ) {

				$_REQUEST = stripslashes_deep( $_REQUEST );
				unset( $_REQUEST['action'] );
				check_ajax_referer( 'qi_addons_for_elementor_widgets_ajax_save_nonce', 'qi_addons_for_elementor_widgets_ajax_save_nonce' );

				$disabled         = array();
				$enabled          = array();
				$shortcodes       = $this->get_shortcodes();
				$promo_shortcodes = qi_addons_for_elementor_promotion_shortcodes_list();

				$shortcodes = array_diff_key( $shortcodes, $promo_shortcodes );

				foreach ( $shortcodes as $shortcode_key => $shortcode ) {
					if ( ! isset( $_REQUEST[ $shortcode_key ] ) ) {
						$disabled[ $shortcode_key ] = $shortcode['base'];
					} else {
						$enabled[ $shortcode_key ] = $shortcode['base'];
					}
				}

				$results = update_option( QI_ADDONS_FOR_ELEMENTOR_DISABLED_WIDGETS, $disabled );
				$this->generate_widget_stylesheet( $enabled );

				do_action( 'qi_addons_for_elementor_action_saved_widgets', $enabled );

				if ( $results ) {
					esc_html_e( 'Saved', 'qi-addons-for-elementor' );
				}

				die();
			}
		}

		function generate_widget_stylesheet( $enabled_shortcodes ) {

			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$main_file  = QI_ADDONS_FOR_ELEMENTOR_ASSETS_PATH . '/css/main.min.css';
			$main_style = '';

			wp_delete_file( $main_file );

			if ( ! empty( $enabled_shortcodes ) ) {
				//this file needs to be added since it is used in several widgets
				$main_style = $wp_filesystem->get_contents( QI_ADDONS_FOR_ELEMENTOR_ASSETS_PATH . '/css/parts/pagination-default.min.css' );

				$woocommerce_global  = false;
				$woocommerce_widgets = array(
					'qi_addons_for_elementor_add_to_cart_button',
					'qi_addons_for_elementor_product_category_list',
					'qi_addons_for_elementor_product_list',
					'qi_addons_for_elementor_product_slider',
				);

				foreach ( $enabled_shortcodes as $slug => $file ) {

					$part = QI_ADDONS_FOR_ELEMENTOR_ASSETS_PATH . '/css/parts/' . $file . '-default.min.css';

					$main_style .= $wp_filesystem->get_contents( $part );

					if ( in_array( $slug, $woocommerce_widgets, true ) ) {
						$woocommerce_global = true;
					}
				}
				if ( $woocommerce_global ) {
					$main_style .= $wp_filesystem->get_contents( QI_ADDONS_FOR_ELEMENTOR_ASSETS_PATH . '/css/parts/woo-global-default.min.css' );
				}
			}

			$wp_filesystem->put_contents( $main_file, $main_style, FS_CHMOD_FILE );
		}
	}
}
