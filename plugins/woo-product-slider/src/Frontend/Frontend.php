<?php
/**
 * The Frontend class to manage all public-facing functionality of the plugin.
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Frontend;

use ShapedPlugin\WooProductSlider\Frontend\Helper;

/**
 * The Frontend class to manage all public facing stuffs.
 */
class Frontend {

	/**
	 * Class Construct
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'register_all_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_shortcode( 'woo_product_slider', array( $this, 'wps_shortcode' ) );
		add_action( 'save_post', array( $this, 'delete_page_product_option_on_save' ) );
	}

	/**
	 * Enqueue the All style for the frontend of the site.
	 */
	public function front_scripts() {
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		/**
		 * Enqueue style.
		 */
		if ( $found_generator_id ) {
			wp_enqueue_style( 'sp-wps-font-awesome' );
			wp_enqueue_style( 'sp-wps-swiper' );
			wp_enqueue_style( 'sp-wps-style' );
			/* Load dynamic style in the header based on found shortcode on the page. */
			$dynamic_style = self::load_dynamic_style( $found_generator_id );
			wp_add_inline_style( 'sp-wps-style', ( $dynamic_style['dynamic_css'] ) );
		}
	}

	/**
	 * Live preview Scripts and Styles
	 */
	public function admin_scripts() {
		$current_screen            = get_current_screen();
			$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wps_shortcodes' === $the_current_post_type ) {
			/* Enqueue style for backend preview */
			wp_enqueue_style( 'sp-wps-swiper' );
			wp_enqueue_style( 'sp-wps-font-awesome' );
			wp_enqueue_style( 'sp-wps-style' );
			add_thickbox();

			/* Enqueue script for backend preview */
			wp_enqueue_script( 'sp-wps-swiper-js' );
		}
	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		$setting_options      = get_option( 'sp_woo_product_slider_options' );
		$enqueue_swiper       = isset( $setting_options['enqueue_swiper_css'] ) ? $setting_options['enqueue_swiper_css'] : true;
		$enqueue_swiper_js    = isset( $setting_options['enqueue_swiper_js'] ) ? $setting_options['enqueue_swiper_js'] : true;
		$enqueue_font_awesome = isset( $setting_options['enqueue_font_awesome'] ) ? $setting_options['enqueue_font_awesome'] : true;
		/**
		 *  Register the All style for the public-facing side of the site.
		 */
		if ( $enqueue_swiper ) {
			wp_register_style( 'sp-wps-swiper', esc_url( SP_WPS_URL . 'Frontend/assets/css/swiper.min.css' ), array(), SP_WPS_VERSION );
		}
		if ( $enqueue_font_awesome ) {
			wp_register_style( 'sp-wps-font-awesome', esc_url( SP_WPS_URL . 'Frontend/assets/css/font-awesome.min.css' ), array(), SP_WPS_VERSION );
		}
		wp_register_style( 'sp-wps-style', esc_url( SP_WPS_URL . 'Frontend/assets/css/style.min.css' ), array(), SP_WPS_VERSION );

		/**
		 *  Register the All scripts for the public-facing side of the site.
		 */
		if ( $enqueue_swiper_js ) {
			wp_register_script( 'sp-wps-swiper-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/swiper.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );
		}
		wp_register_script( 'sp-wps-scripts', esc_url( SP_WPS_URL . 'Frontend/assets/js/scripts.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );

	}
	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_product_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_product_slider_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( get_option( 'sp_product_slider_page_id' . $post_ID ) ) {
				delete_option( 'sp_product_slider_page_id' . $post_ID );
			}
		}

	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_product_slider_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_product_slider_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $shortcode_data to push all options.
	 * @return array dynamic style and typography use in the specific shortcode.
	 */
	public static function load_dynamic_style( $found_generator_id, $shortcode_data = '' ) {
		$dynamic_style = '';
		$custom_css    = trim( html_entity_decode( get_option( 'sp_woo_product_slider_options' )['custom_css'] ) );
		// If multiple shortcode found in the page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$shortcode_data = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
					require SP_WPS_PATH . 'Frontend/views/partials/dynamic-style.php';
				}
			}
		} else {
			// If single shortcode found in the page.
			$post_id = $found_generator_id;
			require SP_WPS_PATH . 'Frontend/views/partials/dynamic-style.php';
		}
		// Custom css merge with dynamic style.
		if ( ! empty( $custom_css ) ) {
			$dynamic_style .= $custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => Helper::minify_output( $dynamic_style ),
		);
		return $dynamic_style;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the page.
	 * @return void
	 */
	public static function wps_db_options_update( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}

	/**
	 * Shortcode
	 *
	 * @param array $attributes shortcode attributes.
	 *
	 * @return string
	 */
	public function wps_shortcode( $attributes ) {
		if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			return '<div class="error"><p>You must install and activate <a target="_blank" href="https://wordpress.org/plugins/woocommerce/"><strong>WooCommerce</strong></a> plugin to make the <strong>Product Slider for WooCommerce</strong> work.</p></div>';
		}
		shortcode_atts(
			array(
				'id' => '',
			),
			$attributes,
			'woo_product_slider'
		);
		if ( empty( $attributes['id'] ) || 'sp_wps_shortcodes' !== get_post_type( $attributes['id'] ) || 'trash' === get_post_status( $attributes['id'] ) ) {
			return;
		}
		$post_id            = esc_attr( intval( $attributes['id'] ) );
		$shortcode_data     = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
		$main_section_title = get_the_title( $post_id );
		ob_start();
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) ) {
			wp_enqueue_style( 'sp-wps-swiper' );
			wp_enqueue_style( 'sp-wps-font-awesome' );
			wp_enqueue_style( 'sp-wps-style' );
			// Load dynamic style.
			$dynamic_style = self::load_dynamic_style( $post_id, $shortcode_data );
			echo '<style id="sp_product_slider_dynamic_css' . esc_attr( $post_id ) . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';  // phpcs:ignore
		}

		// Update options if the existing shortcode id option not found.
		self::wps_db_options_update( $post_id, $get_page_data );
		Helper::spwps_html_show( $post_id, $shortcode_data, $main_section_title );
		return ob_get_clean();
	}
}
