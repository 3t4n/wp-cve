<?php
/*
 * Plugin Name: ACF Recent Posts Widget
 * Plugin URI: http://wp-doin.com/portfolio/acfrpw/
 * Description: Allow ACF and meta fields in the recent posts widget, giving control on the way recent posts are displayed.
 * Author: Magicoders
 * Version: 5.9.3
 * Requires at least: 4.6
 * Author URI: https://magicoders.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: acf-recent-posts-widget
 * Domain Path: /lang
 */
DEFINE( 'ACF_RWP_CLASS_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
DEFINE( 'ACF_RWP_INC_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR );
DEFINE( 'ACF_RWP_PATH', __DIR__ );

class ACF_Recent_Posts_Widget {

	/**
	 * Instance variable to store plugin's object
	 * 
	 * @var self::object 
	 */
	public static $instance = null;

	/**
	 *  Private constructor to follow the Singleton pattern
	 */
	public function __construct() {

		// include the required function to check if plugin is active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// enqueue the plugin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_enqueue_scripts' ) );

		// require helper class
		require( ACF_RWP_CLASS_PATH . 'helpers.php');

		// require the widget files 
		require( ACF_RWP_CLASS_PATH . 'widget-base.php');
		require( ACF_RWP_CLASS_PATH . 'acf-widget-widget.php');
		require( ACF_RWP_CLASS_PATH . 'resizer.php');

		// register widget activation hook
		add_action( 'widgets_init', array( $this, 'register_the_widget' ) );

		// apply custom filtering functions to the before and after filter
		add_action( 'acp_rwp_before', array( 'ACF_Helper', 'af_bf_content_filter' ) );
		add_action( 'acp_rwp_after', array( 'ACF_Helper', 'af_bf_content_filter' ) );

		// apply custom filtering functions to the acf meta value
		add_filter( 'acf_meta_value', array( 'ACF_Helper', 'date_filter' ) );

		// verify if the ACF is active
		if ( !is_plugin_active( 'advanced-custom-fields/acf.php' ) and ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) and !get_option( 'hide_acf_notice' ) ) {
			// ACF absent display admin notices
			add_action( 'admin_notices', array( $this, 'admin_notify_no_acf' ) );
		}

		// add the plugin options page
		add_action( 'admin_menu', array( $this, 'acf_rpw_plugin_menu' ) );

		// add the plugin pointers
		add_action( 'admin_enqueue_scripts', array( $this, 'acf_rpw_admin_pointers_header' ) );

		// Load plugin text domain
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// require the shortcodes plugin
		require_once(ACF_RWP_INC_PATH . '/shortcodes/shortcodes-generator.php');
	}

	/**
	 * @hook admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {

		if ( is_admin() ) {
			wp_register_script( 'acf-widget-admin', plugins_url( 'js/acf-widget-admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ) );
			wp_enqueue_script( 'acf-widget-admin' );

			wp_register_style( 'acf-widget-admin', plugins_url( 'css/acf-widget-admin.css', __FILE__ ) );
			wp_enqueue_style( 'acf-widget-admin' );

			wp_register_style( 'jquery-ui', plugins_url( 'css/jquery-ui.css', __FILE__ ) );
			wp_enqueue_style( 'jquery-ui' );
		}
	}

	/**
	 * @hook wp_enqueue_scripts
	 */
	public function front_enqueue_scripts() {
		wp_register_style( 'acf-rpw-main', plugins_url( 'css/acf-widget-front.css', __FILE__ ) );
		wp_enqueue_style( 'acf-rpw-main' );
	}

	/**
	 * Notify the admin on ACF dependency
	 * @hook admin_notices
	 */
	public function admin_notify_no_acf() {
		?>
		<div class="error acf-rpw-notice">
			<p><?php _e( '<strong>ACF Recent Posts Widget</strong>: You seem to have ACF disabled, some plugin functionalities are disabled.', 'acf-recent-posts-widget' ); ?> <!--<span class="hide"><a href="#" alt="Click to hide.">&#10008;</a></span>--></p>
		</div>
		<?php
	}

	/**
	 * Register the widget
	 * @hook widgets_init
	 */
	public function register_the_widget() {
		register_widget( 'ACF_Rpw_Widget' );
	}

	/**
	 * Add ACF Recent Posts Widget admin menu
	 * @hook admin_menu
	 */
	public function acf_rpw_plugin_menu() {
		add_menu_page( 'ACF RPW', 'ACF RPW', 'read', 'acf-rpw-settings', array( $this, 'acf_rpw_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'register_acf_rpw_plugin_settings' ) );
	}

	/**
	 * Add ACF Recent Posts Widget plugin page
	 */
	public function acf_rpw_plugin_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'ACF RPW: Settings', 'acf-recent-posts-widget' ); ?></h2>
			<hr />
			<form method="post" action="options.php"> 
				<?php settings_fields( 'acf-rpw-settings-group' ); ?>
				<?php do_settings_sections( 'acf-rpw-settings-group' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e( 'Disable missing ACF notice from the plugins page.', 'acf-recent-posts-widget' ); ?></th>
						<td><input type="checkbox" name="hide_acf_notice" value="1" <?php checked( get_option( 'hide_acf_notice' ) ); ?> /></td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Disable the shortcode button.', 'acf-recent-posts-widget' ); ?></th>
						<td><input type="checkbox" name="disable_shortcode" value="1" <?php checked( get_option( 'disable_shortcode' ) ); ?> /></td>
					</tr>

				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
			</form>

		</div>
		<?php
	}

	/**
	 * Register plugin settings
	 * 
	 * @hook admin_init 
	 */
	public function register_acf_rpw_plugin_settings() {
		register_setting( 'acf-rpw-settings-group', 'hide_acf_notice' );
		register_setting( 'acf-rpw-settings-group', 'disable_shortcode' );
	}

	/**
	 * Add plugin pointers
	 */
	public function acf_rpw_admin_pointers_header() {
		if ( $this->admin_pointers_check() ) {
			add_action( 'admin_print_footer_scripts', array( $this, 'acf_rpw_admin_pointers_footer' ) );

			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_style( 'wp-pointer' );
		}
	}

	/**
	 * Add admin ACF RPW pointers
	 * @return boolean
	 */
	public function admin_pointers_check() {
		$admin_pointers = $this->acf_rpw_custom_admin_pointers();
		foreach ( $admin_pointers as $pointer => $array ) {
			if ( $array['active'] )
				return true;
		}
	}

	/**
	 * Add ACF RPW pointers scripts to the footer
	 */
	function acf_rpw_admin_pointers_footer() {
		$admin_pointers = $this->acf_rpw_custom_admin_pointers();
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			(function ($) {
		<?php
		foreach ( $admin_pointers as $pointer => $array ) {
			if ( $array['active'] ) {
				?>
						$('<?php echo $array['anchor_id']; ?>').pointer({
							content: '<?php echo $array['content']; ?>',
							position: {
								edge: '<?php echo $array['edge']; ?>',
								align: '<?php echo $array['align']; ?>'
							},
							close: function () {
								$.post(ajaxurl, {
									pointer: '<?php echo $pointer; ?>',
									action: 'dismiss-wp-pointer'
								});
							}
						}).pointer('open');
				<?php
			}
		}
		?>
			})(jQuery);
			/* ]]> */
		</script>
		<?php
	}

	/**
	 * Add custom ACF RPW pointer
	 * @return array
	 */
	public function acf_rpw_custom_admin_pointers() {
		$dismissed = explode( ',', ( string ) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$version = '1_1';
		$prefix = 'acf_rpw_pointers_' . $version . '_';

		$new_pointer_content = '<h3>' . __( 'ACF RPW settings' ) . '</h3>';
		$new_pointer_content .= '<p>' . __( 'Alter plugin settings here.' ) . '</p>';

		return array(
			$prefix . 'new_items' => array(
				'content' => $new_pointer_content,
				'anchor_id' => '#toplevel_page_acf-rpw-settings',
				'edge' => 'top',
				'align' => 'top',
				'active' => (!in_array( $prefix . 'new_items', $dismissed ) )
			),
		);
	}

	/**
	 * @hook init
	 */
	public function load_plugin_textdomain() {
		$domain = 'acf-recent-posts-widget';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
	}

}

// instantiate the plugin
$acf_rpw = new ACF_Recent_Posts_Widget();

