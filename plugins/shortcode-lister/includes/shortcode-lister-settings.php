<?php
/**
 * ShortCode Lister Settings
 *
 * @package Shortcode_Lister
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you are not allowed to access this page directly.' );
}
/**
 * Admin Settings Setup
 *
 * @return void
 */
function shortcode_lister_admin_settings_setup() {
	add_options_page( __( 'Shortcode Lister', 'shortcode-lister' ), __( 'Shortcode Lister', 'shortcode-lister' ), 'manage_options', 'shortcode-lister-settings', 'shortcode_lister_admin_settings_page' );
}
add_action( 'admin_menu', 'shortcode_lister_admin_settings_setup' );

add_filter( 'plugin_action_links_' . SHORTCODE_LISTER_PLUGIN_BASENAME, 'shortcode_lister_add_action_links' );

/**
 * Undocumented function
 *
 * @param array $links Array links to be modified.
 * @return strings[]
 */
function shortcode_lister_add_action_links( $links ) {
	$mylinks = array(
		'<a href="' . admin_url( 'options-general.php?page=shortcode-lister-settings' ) . '">Settings</a>',
	);
	return array_merge( $links, $mylinks );
}

/**
 * Admin Settings Page
 *
 * @return void
 */
function shortcode_lister_admin_settings_page() {
	?>

	<h2 class="nav-tab-wrapper">
	<?php
		do_action( 'shortcode_lister_settings_tab' );
	?>
	</h2>
	<?php
		do_action( 'shortcode_lister_settings_content' );
}

add_action( 'shortcode_lister_settings_tab', 'shortcode_lister_welcome_tab', 1 );

/**
 * Welcome Tab
 *
 * @return void
 */
function shortcode_lister_welcome_tab() {
	?>
	<a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( 'options-general.php?page=shortcode-lister-settings&tab=welcome' ) ); ?>"><?php _e( 'Shortcode Lister', 'shortcode-lister' ); ?> </a>
	<?php
}

add_action( 'shortcode_lister_settings_content', 'shortcode_lister_welcome_render_options_page' );

/**
 * Render Welcome Options Page
 *
 * @return void
 */
function shortcode_lister_welcome_render_options_page() {
	?>

	<h3><?php _e( 'Shortcode Lister Settings', 'shortcode-lister' ); ?></h3>
	<p><?php _e( 'Exclude shortcodes from the shortcode listing menu by checking the box next to each shortcode below. This is useful if there are shortcodes you only use once so your list of shortcodes does not become overloaded with unnecessary shortcodes.', 'shortcode-lister' ); ?></p>
	<form method="post" action="options.php">
		<?php settings_fields( 'shortcode_lister_settings_group' ); ?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Shortcode', 'shortcode-lister' ); ?>
					</th>
				</tr>
				<?php shortcode_lister_menu( 'settings' ); ?>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'shortcode-lister' ); ?>" />
		</p>
	</form>
	<?php
}

/**
 * Register Settings
 *
 * @return void
 */
function shortcode_lister_register_settings() {
	// creates our settings in the options table.
	register_setting( 'shortcode_lister_settings_group', 'shortcode_lister_settings' );
}
add_action( 'admin_init', 'shortcode_lister_register_settings' );
