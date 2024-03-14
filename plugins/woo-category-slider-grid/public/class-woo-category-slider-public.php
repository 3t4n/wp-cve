<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/public
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Woo_Category_Slider_Public class
 */
class Woo_Category_Slider_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.1.0
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
	 * The style and script suffix.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $suffix    The style and script suffix of this plugin.
	 */
	private $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->suffix      = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Category_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Category_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		if ( $found_generator_id ) {
			/* Load dynamic style in the header based on found shortcode on the page. */
			$dynamic_style = self::load_dynamic_style( $found_generator_id );
			wp_enqueue_style( 'sp-wcs-swiper' );
			wp_enqueue_style( 'sp-wcs-font-awesome' );
			wp_enqueue_style( 'woo-category-slider-grid' );
			wp_add_inline_style( 'woo-category-slider-grid', wp_strip_all_tags( $dynamic_style['dynamic_css'] ) );
		}

	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		$setting_options      = get_option( 'sp_wcsp_settings' );
		$enqueue_swiper       = isset( $setting_options['wcsp_swiper_css'] ) ? $setting_options['wcsp_swiper_css'] : true;
		$enqueue_swiper_js    = isset( $setting_options['wcsp_swiper_js'] ) ? $setting_options['wcsp_swiper_js'] : true;
		$enqueue_font_awesome = isset( $setting_options['wcsp_fa_css'] ) ? $setting_options['wcsp_fa_css'] : true;
		/**
		 *  Register the All style for the public-facing side of the site.
		 */
		if ( $enqueue_swiper ) {
			wp_register_style( 'sp-wcs-swiper', SP_WCS_URL . 'public/css/swiper' . $this->suffix . '.css', array(), $this->version, 'all' );
		}
		if ( $enqueue_font_awesome ) {
			wp_register_style( 'sp-wcs-font-awesome', SP_WCS_URL . 'public/css/font-awesome.min.css', array(), $this->version, 'all' );
		}
		wp_register_style( 'woo-category-slider-grid', SP_WCS_URL . 'public/css/woo-category-slider-public' . $this->suffix . '.css', array(), $this->version, 'all' );
		// Style for admin facing side of the plugin.
		wp_register_style( 'woo-category-slider-grid-admin', SP_WCS_URL . 'admin/css/woo-category-slider-admin' . $this->suffix . '.css', array(), SP_WCS_VERSION, 'all' );

		/**
		 *  Register the All scripts for the public-facing side of the site.
		 */
		if ( $enqueue_swiper_js ) {
			wp_register_script( 'sp-wcs-swiper-js', SP_WCS_URL . 'public/js/swiper' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		}
		wp_register_script( 'sp-wcs-preloader', SP_WCS_URL . 'public/js/preloader' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'sp-wcs-swiper-config', SP_WCS_URL . 'public/js/swiper-config' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );

		// Scripts for admin notice.
		wp_register_script( 'woo-category-slider-grid-admin-js', SP_WCS_URL . 'admin/js/woo-category-slider-admin' . $this->suffix . '.js', array( 'jquery' ), SP_WCS_VERSION, true );

	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_wcs_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_category_slider_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( get_option( 'sp_category_slider_page_id' . $post_ID ) ) {
				delete_option( 'sp_category_slider_page_id' . $post_ID );
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
	 * @param  mixed $shortcode_meta to push all options.
	 * @return array dynamic style and typography use in the specific shortcode.
	 */
	public static function load_dynamic_style( $found_generator_id, $shortcode_meta = '' ) {
		$dynamic_style = '';
		$custom_css    = trim( html_entity_decode( get_option( 'sp_wcsp_settings' )['wcsp_custom_css'] ) );
		// If multiple shortcode found in the page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$shortcode_meta = get_post_meta( $post_id, 'sp_wcsp_shortcode_options', true );
					include SP_WCS_PATH . '/public/dynamic-style.php';
				}
			}
		} else {
			// If single shortcode found in the page.
			$post_id = $found_generator_id;
			include SP_WCS_PATH . '/public/dynamic-style.php';
		}
		// Custom css merge with dynamic style.
		if ( ! empty( $custom_css ) ) {
			$dynamic_style .= $custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => Woo_Category_Slider_Shortcode::minify_output( $dynamic_style ),
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
	public static function wcs_db_options_update( $post_id, $get_page_data ) {
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
}
