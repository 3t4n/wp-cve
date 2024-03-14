<?php
/**
 * Define the shortcode functionality
 *
 * Loads and defines the shortcode for this plugin
 * so that it is ready for shortcode base view system.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public
 */

/**
 * Define the shortcode functionality.
 */
class WP_Tabs_Shortcode {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_action( 'save_post', array( $this, 'delete_page_wp_tab_option_on_save' ) );
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_wp_tab_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_tab_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( get_option( 'sp_tab_page_id' . $post_ID ) ) {
				delete_option( 'sp_tab_page_id' . $post_ID );
			}
		}
	}

	/**
	 * Minify output
	 *
	 * @param  string $html output minifier.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * Full html show.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $sptpro_data_src get all layout options.
	 * @param array $sptpro_shortcode_options get all meta options.
	 * @param array $main_section_title shows section title.
	 */
	public static function sp_tabs_html_show( $post_id, $sptpro_data_src, $sptpro_shortcode_options, $main_section_title ) {
		$sptpro_data_src             = isset( $sptpro_data_src['sptpro_content_source'] ) ? $sptpro_data_src['sptpro_content_source'] : null;
		$sptpro_preloader            = isset( $sptpro_shortcode_options['sptpro_preloader'] ) ? $sptpro_shortcode_options['sptpro_preloader'] : false;
		$sptpro_tabs_activator_event = isset( $sptpro_shortcode_options['sptpro_tabs_activator_event'] ) ? $sptpro_shortcode_options['sptpro_tabs_activator_event'] : '';
		$sptpro_tab_opened           = 1;
		$sptpro_tabs_on_small_screen = isset( $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] ) ? $sptpro_shortcode_options['sptpro_tabs_on_small_screen'] : '';
		$sptpro_title_heading_tag    = isset( $sptpro_shortcode_options['sptpro_title_heading_tag'] ) ? $sptpro_shortcode_options['sptpro_title_heading_tag'] : '';
		$sptpro_section_title        = isset( $sptpro_shortcode_options['sptpro_section_title'] ) ? $sptpro_shortcode_options['sptpro_section_title'] : false;
		// Animation.
		$sptpro_tabs_animation      = isset( $sptpro_shortcode_options['sptpro_tabs_animation'] ) ? $sptpro_shortcode_options['sptpro_tabs_animation'] : false;
		$sptpro_tabs_animation_type = isset( $sptpro_shortcode_options['sptpro_tabs_animation_type'] ) ? $sptpro_shortcode_options['sptpro_tabs_animation_type'] : '';
		$animation_name             = $sptpro_tabs_animation ? 'animated ' . $sptpro_tabs_animation_type : '';

		$wrapper_class   = 'sp-tab__lay-default';
		$content_class   = 'sp-tab__lay-default';
		$title_data_attr = '';

		switch ( $sptpro_tabs_on_small_screen ) {
			case 'full_widht':
				$title_data_attr = 'aria-controls=%s aria-selected=true tabindex=0';
				break;
			case 'accordion_mode':
				wp_enqueue_script( 'sptpro-collapse' );
				$wrapper_class .= ' sp-tab__default-accordion';
				break;
		}

		wp_enqueue_script( 'sptpro-tab' );
		wp_enqueue_script( 'sptpro-script' );
		include WP_TABS_PATH . 'public/partials/section-title.php';
		?>
		<div id="sp-wp-tabs-wrapper_<?php echo esc_attr( $post_id ); ?>" class="<?php echo esc_html( $wrapper_class ); ?>" data-preloader="<?php echo esc_html( $sptpro_preloader ); ?>" data-activemode="<?php echo esc_html( $sptpro_tabs_activator_event ); ?>">
		<?php
		include WP_TABS_PATH . '/public/preloader.php';
		include WP_TABS_PATH . '/public/partials/tabs-navigation.php';
		include WP_TABS_PATH . '/public/partials/content.php';
		?>
		</div>
		<?php
	}

	/**
	 * Shortcode of the Plugin.
	 *
	 * @since 2.0.0
	 * @param array $attributes Attribute.
	 * @param null  $content Param.
	 */
	public function sptpro_shortcode_execute( $attributes, $content = null ) {
		if ( empty( $attributes['id'] ) || 'sp_wp_tabs' !== get_post_type( $attributes['id'] ) || ( get_post_status( $attributes['id'] ) === 'trash' ) ) {
			return;
		}
		$post_id                  = esc_attr( intval( $attributes['id'] ) );
		$sptpro_data_src          = get_post_meta( $post_id, 'sp_tab_source_options', true );
		$sptpro_shortcode_options = get_post_meta( $post_id, 'sp_tab_shortcode_options', true );
		$main_section_title       = get_the_title( $post_id );

		// Get the existing shortcode id from the current page.
		$get_page_data      = WP_Tabs_Public::get_page_data();
		$found_shortcode_id = $get_page_data['generator_id'];

		ob_start();
		// Check if shortcode and page ids are not exist in the current page then enqueue the stylesheet.
		if ( ! is_array( $found_shortcode_id ) || ! $found_shortcode_id || ! in_array( $post_id, $found_shortcode_id ) ) {

			// Load dynamic style for the existing shortcode.
			$dynamic_style  = WP_Tabs_Public::load_dynamic_style( $post_id, $sptpro_shortcode_options );
			$accordion_mode = $dynamic_style['accordion'];
			if ( $accordion_mode ) {
				wp_enqueue_style( 'sptpro-accordion-style' );
			}
			wp_enqueue_style( 'sptpro-style' );
			echo '<style id="sp_tab_dynamic_style' . $post_id . '">' . $dynamic_style['dynamic_css'] . '</style>'; // phpcs:ignore
		}
		// Update all option If the option does not exist in the current page.
		WP_Tabs_Public::tabs_update_options( $post_id, $get_page_data );
		// Render output.
		self::sp_tabs_html_show( $post_id, $sptpro_data_src, $sptpro_shortcode_options, $main_section_title );
		return ob_get_clean();
	}

}
