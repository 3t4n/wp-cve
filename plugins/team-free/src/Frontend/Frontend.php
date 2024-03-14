<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    team-free
 * @subpackage team-free/public
 * @author     ShapedPlugin <info@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Frontend;

use ShapedPlugin\WPTeam\Frontend\Helper;

/**
 * Frontend class
 */
class Frontend {

	/**
	 * The name of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The name of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Generator
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      integer    $generator_id    Generator ID of team.
	 */
	private $generator_id;

	/**
	 * The Settings key.
	 *
	 * @since    2.0.0
	 * @access private
	 * @var mixed Settings
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_shortcode( 'wpteam', array( $this, 'sptp_shortcode_func' ) );
		define( 'SPT_TRANSIENT_EXPIRATION', apply_filters( 'spteam_free_transient_expiration', DAY_IN_SECONDS ) );
		add_action( 'save_post', array( $this, 'delete_page_team_free_option_on_save' ) );
		add_filter( 'single_template', array( $this, 'get_custom_post_type_template' ) );
		// Member single page css.
		add_action( 'wp_head', array( $this, 'sp_team_free_single_css' ), 99 );

	}


	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_team_free_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'wp_team_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( get_option( 'wp_team_page_id' . $post_ID ) ) {
				delete_option( 'wp_team_page_id' . $post_ID );
			}
		}
	}

	/**
	 * Get custom template for the team post type.
	 *
	 * @param string $single_template The template file.
	 *
	 * @return string
	 */
	public function get_custom_post_type_template( $single_template ) {
		global $post;
		if ( 'sptp_member' === $post->post_type ) {
			return Helper::sptp_locate_template( 'sptp-single.php' );
			wp_reset_postdata();
		}
		return $single_template;
	}
	/**
	 * Single page css.
	 *
	 * @return void
	 */
	public function sp_team_free_single_css() {
		global $post;
		if ( is_object( $post ) && 'sptp_member' === $post->post_type ) {
			ob_start();
			Helper::sp_team_free_single_css();
			echo ob_get_clean();
		}
	}

	/**
	 * Function get layout from atts and create class depending on it.
	 *
	 * @param array $attributes Shortcode's all attributes/options.
	 * @since 2.0.0
	 */
	public function sptp_shortcode_func( $attributes ) {

		$generator_id = esc_attr( intval( $attributes['id'] ) );
		// Check the shortcode status and post type.
		if ( empty( $generator_id ) || 'sptp_generator' !== get_post_type( $generator_id ) || 'trash' === get_post_status( $generator_id ) ) {
			return;
		}
		// Preset Layouts.
		$layout = get_post_meta( $generator_id, '_sptp_generator_layout', true );
		// All the visible options for the Shortcode like – Global, Filter, Display, Popup, Typography etc.
		$settings = get_post_meta( $generator_id, '_sptp_generator', true );
		if ( ! is_array( $settings ) ) {
			return; // for auto draft, broken shortcode
		}
		$main_section_title = get_the_title( $generator_id );

		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		ob_start();
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $generator_id, $found_generator_id ) ) {
			wp_enqueue_style( 'team-free-swiper' );
			wp_enqueue_style( 'team-free-fontawesome' );
			wp_enqueue_style( SPT_PLUGIN_SLUG );
			// Dynamic style load.
			$dynamic_style = self::load_dynamic_style( $generator_id, $layout, $settings );
			echo '<style id="team_free_dynamic_css' . $generator_id . '">' . $dynamic_style['dynamic_css'] . '</style>';//phpcs:ignore
		}
		// Update options if the existing shortcode id option not found.
		self::db_options_update( $generator_id, $get_page_data );
		Helper::sptp_html_show( $generator_id, $layout, $settings, $main_section_title );
		return ob_get_clean();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];

		if ( $found_generator_id ) {
			wp_enqueue_style( 'team-free-swiper' );
			wp_enqueue_style( 'team-free-fontawesome' );
			wp_enqueue_style( SPT_PLUGIN_SLUG );
			// Load dynamic style based on the existing shortcode ids in the current page.
			$dynamic_style = self::load_dynamic_style( $found_generator_id );
			wp_add_inline_style( SPT_PLUGIN_SLUG, $dynamic_style['dynamic_css'] );
		}

		// Frontend rtl css code.
		if ( is_rtl() ) {
			wp_enqueue_style( 'public-rtl' );
		}
		global $post;
		if ( is_object( $post ) && 'sptp_member' === $post->post_type ) {
			$custom_style = trim( html_entity_decode( get_option( '_sptp_settings' )['custom_css'] ) );
			wp_enqueue_style( 'team-free-fontawesome' );
			wp_enqueue_style( SPT_PLUGIN_SLUG );
			wp_add_inline_style( SPT_PLUGIN_SLUG, $custom_style );
		}
	}

	/**
	 * Register all Styles and Scripts for the public-facing side of the site.
	 *
	 * @since    2.2.10
	 */
	public function register_all_scripts() {
		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Team_fre_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Team_fre_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$sptp_settings    = get_option( '_sptp_settings' );
		$sptp_fontawesome = isset( $sptp_settings['enqueue_fontawesome'] ) ? $sptp_settings['enqueue_fontawesome'] : true;
		$sptp_swiper_css  = isset( $sptp_settings['enqueue_swiper'] ) ? $sptp_settings['enqueue_swiper'] : true;
		$sptp_swiper_js   = isset( $sptp_settings['enqueue_swiper_js'] ) ? $sptp_settings['enqueue_swiper_js'] : true;
		/**
		 *  Register all the styles for the public-facing side of the site.
		 */
		if ( $sptp_fontawesome ) {
			wp_register_style( 'team-free-fontawesome', SPT_PLUGIN_ROOT . 'src/Frontend/css/font-awesome.min.css', array(), SPT_PLUGIN_VERSION, 'all' );
		}
		if ( $sptp_swiper_css ) {
			wp_register_style( 'team-free-swiper', SPT_PLUGIN_ROOT . 'src/Frontend/css/swiper.min.css', array(), SPT_PLUGIN_VERSION, 'all' );
		}
		wp_register_style( 'public-rtl', SPT_PLUGIN_ROOT . 'src/Frontend/css/public-rtl.min.css', array(), SPT_PLUGIN_VERSION, 'all' );
		wp_register_style( SPT_PLUGIN_SLUG, SPT_PLUGIN_ROOT . 'src/Frontend/css/public.min.css', array(), SPT_PLUGIN_VERSION, 'all' );

		/**
		 * Register all the Scripts for the public-facing side of the site.
		 */
		if ( $sptp_swiper_js ) {
			wp_register_script( 'team-free-swiper', SPT_PLUGIN_ROOT . 'src/Frontend/js/swiper.min.js', array(), SPT_PLUGIN_VERSION, true );
		}
		wp_register_script( SPT_PLUGIN_SLUG, SPT_PLUGIN_ROOT . 'src/Frontend/js/script.js', array( 'jquery' ), SPT_PLUGIN_VERSION, true );

		wp_localize_script(
			SPT_PLUGIN_SLUG,
			'sptp_vars',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'sptp-modal' ),
				'not_found' => __( ' No result found ', 'team-free' ),
			)
		);
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'wp_team_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'wp_team_page_id' . get_current_blog_id() . $current_page_id;
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
	 * @param  mixed $layout to push all options.
	 * @param  mixed $settings to push All the visible options for the Shortcode like – Global, Filter, Display, Popup, Typography etc.
	 * @return array get dynamic style use in the specific shortcode.
	 */
	public static function load_dynamic_style( $found_generator_id, $layout = '', $settings = '' ) {
		$final_css = '';
		// If multiple shortcode found in the page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id as $generator_id ) {
				if ( $generator_id && is_numeric( $generator_id ) && 'sptp_generator' === get_post_type( $generator_id ) && get_post_status( $generator_id ) !== 'trash' ) {
					$layout   = get_post_meta( $generator_id, '_sptp_generator_layout', true );
					$settings = get_post_meta( $generator_id, '_sptp_generator', true );
					if ( is_array( $settings ) ) {
						include 'partials/dynamic-style.php';
					}
				}
			}
		} else {
			// If single shortcode found in the page.
			$generator_id = $found_generator_id;
			include 'partials/dynamic-style.php';
		}
		$custom_style = trim( html_entity_decode( get_option( '_sptp_settings' )['custom_css'] ) );
		// Custom css merge with dynamic style.
		if ( ! empty( $custom_style ) ) {
			$final_css .= $custom_style;
		}
		$dynamic_style = array(
			'dynamic_css' => Helper::minify_output( $final_css ),
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
	public static function db_options_update( $post_id, $get_page_data ) {
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
