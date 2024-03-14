<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 */
class WP_Tabs_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
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
	 * Script and style suffix
	 *
	 * @access protected
	 * @var string
	 */
	protected $min;
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
		$this->min         = ( apply_filters( 'enqueue_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {
		/**
		* This function is provided for demonstration purposes only.
		*
		* An instance of this class should be passed to the run() function
		* defined in WP_Tabs_Loader as all of the hooks are defined
		* in that particular class.
		*
		* The WP_Tabs_Loader will then create the relationship
		* between the defined hooks and the functions defined in this
		* class.
		*/

		// Get the existing shortcode id from the current page.
		$get_page_data      = self::get_page_data();
		$found_shortcode_id = $get_page_data['generator_id'];

		if ( $found_shortcode_id ) {
			// Load dynamic style for the existing shordcodes.
			$dynamic_style = self::load_dynamic_style( $found_shortcode_id );

			$accordion_mode = $dynamic_style['accordion'];
			if ( $accordion_mode ) {
				wp_enqueue_style( 'sptpro-accordion-style' );
			}
			wp_enqueue_style( 'sptpro-style' );
			wp_enqueue_style( 'sptpro-animate-css' );
			wp_add_inline_style( 'sptpro-style', $dynamic_style['dynamic_css'] );
		}
	}

	/**
	 * Enqueue styles file for live preview.
	 *
	 * @since    2.0.15
	 */
	public function admin_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Tabs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Tabs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_tabs' === $the_current_post_type ) {
			wp_enqueue_style( 'sptpro-accordion-style' );
			wp_enqueue_style( 'admin-sptpro-style' );
			wp_enqueue_style( 'sptpro-animate-css' );
		}
	}

	/**
	 * Register all Styles and Scripts for the public-facing side of the site.
	 *
	 * @since   2.1.14
	 */
	public function register_all_scripts() {
		/**
		 *  Register all the styles for the public-facing side of the site.
		 */
		wp_register_style( 'sptpro-accordion-style', esc_url( WP_TABS_URL . 'public/css/sptpro-accordion' . $this->min . '.css' ), array(), $this->version, 'all' );
		wp_register_style( 'sptpro-style', esc_url( WP_TABS_URL . 'public/css/wp-tabs-public' . $this->min . '.css' ), array(), $this->version, 'all' );
		wp_register_style( 'admin-sptpro-style', esc_url( WP_TABS_URL . 'public/css/wp-tabs-public' . $this->min . '.css' ), array(), $this->version, 'all' );
		wp_register_style( 'sptpro-animate-css', WP_TABS_URL . 'public/css/animate' . $this->min . '.css', array(), $this->version, 'all' );

		/**
		 * Register all the Scripts for the public-facing side of the site.
		 */
		wp_register_script( 'sptpro-tab', esc_url( WP_TABS_URL . 'public/js/tab' . $this->min . '.js' ), array( 'jquery' ), $this->version, false );
		wp_register_script( 'sptpro-collapse', esc_url( WP_TABS_URL . 'public/js/collapse' . $this->min . '.js' ), array( 'jquery' ), $this->version, false );
		wp_register_script( 'sptpro-script', esc_url( WP_TABS_URL . 'public/js/wp-tabs-public' . $this->min . '.js' ), array( 'jquery' ), $this->version, true );
	}

	/**
	 * Load dynamic style for the existing shortcode ids.
	 *
	 * @param  mixed $found_shortcode_id to push id for getting how many shortcode in the current page.
	 * @param  mixed $shortcode_data to get all options from the existing shortcode id.
	 * @return array
	 */
	public static function load_dynamic_style( $found_shortcode_id, $shortcode_data = '' ) {
		$settings             = get_option( 'sp-tab__settings' );
		$sptpro_custom_css    = isset( $settings['sptpro_custom_css'] ) ? trim( html_entity_decode( $settings['sptpro_custom_css'] ) ) : '';
		$accordion_mode       = false;
		$sptpro_dynamic_style = '';
		if ( is_array( $found_shortcode_id ) ) {
			wp_enqueue_style( 'sptpro-animate-css' );

			foreach ( $found_shortcode_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$sptpro_shortcode_options = get_post_meta( $post_id, 'sp_tab_shortcode_options', true );
					include WP_TABS_PATH . 'public/dynamic_style.php';
					if ( ! $accordion_mode && 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
						$accordion_mode = true;
					}
				}
			}
		} else {
			$post_id                  = $found_shortcode_id;
			$sptpro_shortcode_options = $shortcode_data;
			include WP_TABS_PATH . 'public/dynamic_style.php';
			if ( ! $accordion_mode && 'accordion_mode' === $sptpro_tabs_on_small_screen ) {
				$accordion_mode = true;
			}
		}
		if ( ! empty( $sptpro_custom_css ) ) {
			$sptpro_dynamic_style .= $sptpro_custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => WP_Tabs_Shortcode::minify_output( $sptpro_dynamic_style ),
			'accordion'   => $accordion_mode,
		);
		return $dynamic_style;
	}

	/**
	 * Get the existing shortcode id, page id and option key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_tab_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_tab_page_id' . get_current_blog_id() . $current_page_id;
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
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id shortcode id.
	 * @param  array  $get_page_data Get the existing page-id, shortcode-id and option-key from the current page.
	 * @return void
	 */
	public static function tabs_update_options( $post_id, $get_page_data ) {
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
	 * Converts Markdown content to HTML using Parsedown library.
	 *
	 * This function converts the given Markdown content to HTML by utilizing the Parsedown library.
	 * If the 'sp_tab_content_markdown_to_html' filter is disabled, the original HTML content is returned unchanged.
	 *
	 * @param string $html The Markdown content to be converted.
	 * @return string The converted HTML content.
	 */
	public static function sp_wp_tabs_markdown_to_html( $html ) {
		if ( ! apply_filters( 'sp_tab_content_markdown_to_html', false ) ) {
			return $html;
		}
		require_once WP_TABS_PATH . '/includes/class-wp-tabs-parsedown.php';

		$markdown = new Parsedown();
		// Convert Markdown to HTML.
		$html = $markdown->text( $html );
		return $html;
	}
}
