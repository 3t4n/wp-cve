<?php
/**
 * Plugin Name: WordPress Stats View Counter
 * Plugin URI: http://adamcap.com/code/save-wordpress-com-pageviews-as-post_meta/
 * Description: Saves view counts from WordPress.com Stats Jetpack module as post meta data.
 * Version: 1.3
 * Author: Adam Capriola
 * Author URI: http://adamcap.com/
 * License: GPLv2
 */

class AC_View_Counter {

	var $instance;

	public function __construct() {

		$this->instance =& $this;
		add_action( 'init', array( $this, 'init' ) );

		if ( ! defined( 'HOUR_IN_SECONDS' ) ) {
			define( 'HOUR_IN_SECONDS', 60 * 60 );
		}

	}

	public function init() {

		// Translations
		load_plugin_textdomain( 'view-counter', false, basename( dirname( __FILE__ ) ) . '/lib/languages' );

		// Save views
		add_action( 'wp_footer', array( $this, 'save_views' ) );

		// Settings page
		add_action( 'admin_init', array( $this, 'settings_page_init' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );

		// Shortcode
		add_shortcode( 'view-count', array( $this, 'view_count_shortcode' ) );

	}

	/**
	 * Save WordPress.com views as post meta data
	 *
	 * Must have WordPress.com Stats Jetpack module activated or be using WordPress.com Stats plugin
	 *
	 * @link http://wpthemetutorial.com/2012/01/31/showing-post-views-with-wordpress-com-stats/
	 * @link https://github.com/justintadlock/hybrid-core/blob/master/extensions/entry-views.php
	 * @link http://www.binarymoon.co.uk/2010/03/ultimate-add-popular-posts-wordpress-blog-1-line-code/
	 * @link http://stats.wordpress.com/csv.php
	 * 
	 */
	public function save_views() {

		global $post;

		// Check if WordPress.com Stats is enabled
		if ( ! function_exists( 'stats_get_csv' ) ) {
			return;
		}

		// Active post types
		$settings = get_option( 'view_counter' );
		if ( isset( $settings['post_types'] ) ) {
			$post_types = $settings['post_types'];
		}
		else {
			$post_types = array();
		}

		// Return if no active post types or we aren't on active post type
		if ( empty( $post_types ) || ! is_singular( $post_types ) ) {
			return;
		}

		// Don't count previews
		if ( is_preview() ) {
			return;
		}

		// Transient name
		$transient = 'view_counter_' . $post->ID;

		// Make sure transient is expired or non-existent
		if ( ! get_transient( $transient ) ) {

			$random = mt_rand( 36500, 2147483647 ); // hack to break cache bug

			$args = array(
				'days'    => $random,
				'post_id' => $post->ID
			);

			// API call to get views
			$stats = stats_get_csv( 'postviews', $args );
			$new_views = (int) $stats['0']['views'];

			// Save views
			$key = apply_filters( 'view_counter_meta_key', 'views' );
			$old_views = get_post_meta( $post->ID, 'views', true );
			if ( $new_views > $old_views ) {
				update_post_meta( $post->ID, $key, $new_views );
			}

			// Set transient
			$expiration = absint( apply_filters( 'view_counter_transient_expiration', 3 ) );
			set_transient( $transient, 1, $expiration * HOUR_IN_SECONDS );

		}

	}

	/**
	 * Initialize plugin options
	 * 
	 * @link http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	 * 
	 */
	public function settings_page_init() {
		register_setting( 'view_counter_options', 'view_counter', array( $this, 'view_counter_validate' ) );
	}

	/**
	 * Add Settings Page
	 *
	 */
	public function add_settings_page() {
		add_options_page( __( 'View Counter Settings', 'view-counter' ), __( 'View Counter', 'view-counter' ), 'manage_options', 'wp_stats_view_counter', array( $this, 'settings_page' ) );
	}

	/**
	 * Build Settings Page 
	 *
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'View Counter Settings', 'view-counter' );?></h2>
			<?php if ( ! function_exists( 'stats_get_csv' ) ) { ?>
			<div class="error"><p><?php _e( 'This plugin doesn&#8217;t work unless you have the <a href="http://wordpress.org/extend/plugins/jetpack/">Jetpack</a> Site Stats module activated.', 'view-counter' );?></p></div>
			<?php } else { ?>
			<form method="post" action="options.php">
				<?php 
				settings_fields( 'view_counter_options' );
				$settings = get_option( 'view_counter' );
				if ( isset( $settings['post_types'] ) ) {
					$post_types = $settings['post_types'];
				}
				else {
					$post_types = array();
				}
				?>
				<table class="form-table">
					<tr valign="top"><th scope="row"><?php _e( 'Active for Selected Post Types', 'view-counter' );?></th>
						<td>
						<?php
						foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $cpt ) {

							$checked = checked( in_array( $cpt->name, $post_types ) ? true : false, true, false );
							$name = esc_attr( $cpt->name );
							$label = esc_html( $cpt->label );

							printf( '<label><input type="checkbox" %s name="view_counter[post_types][]" value="%s" /> %s </label><br />', $checked, $name, $label );

						}
						?>
						</td>
					</tr>
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'view-counter' ); ?>" />
				</p>
			</form>
			<?php } ?>
		</div>
		<?php	

	}

	/**
	 * Add Settings Link
	 *
	 */
	public function add_settings_link( $links, $file ) {

		static $this_plugin;

		if ( empty( $this_plugin ) ) $this_plugin = plugin_basename( __FILE__ );

		// Check to make sure we're on the right plugin
		if ( $file == $this_plugin ) {

			// Create link
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=wp_stats_view_counter' ) . '">' . __( 'Settings', 'view-counter' ) . '</a>';

			// Add link to list
			array_unshift( $links, $settings_link );

		}

		return $links;

	}

	/** 
	 * Validate settings
	 *
	 */
	function view_counter_validate( $input ) {
		return $input;
	}

	/**
	 * View count shortcode
	 *
	 * Example usage: [view-count before="Views: "] or [view-count after=" views"]
	 * 
	 */
	public function view_count_shortcode( $atts ) {

		$defaults = array(
			'after'  => '',
			'before' => '',
		);
		$atts = shortcode_atts( $defaults, $atts );

		global $post;

		$views = number_format_i18n( (double) get_post_meta( $post->ID, apply_filters( 'view_counter_meta_key', 'views' ), true ) );

		$output = sprintf( '<span class="view-count">%2$s%1$s%3$s</span>', $views, $atts['before'], $atts['after'] );

		return $output;

	}

}

global $ac_view_counter;
$ac_view_counter = new AC_View_Counter;