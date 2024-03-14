<?php
/**
 * Code that runs if is_admin or doing wp_cli.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

define( 'EOS_DP_NEED_UPDATE_MU', true );

if ( is_admin() && defined( 'FDP_EXCLUDE_BACK' ) && FDP_EXCLUDE_BACK && isset( $_GET[ FDP_EXCLUDE_BACK ] ) && 'true' === $_GET[ FDP_EXCLUDE_BACK ] ) {
	// Don't run if URL includes query argument defined in wp-config.php.
    return;
}

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return; // Return if WordPress is upgrading.
}

/**
 * Allowed backend scripts.
 *
 * @since 1.9.0
 *
 */
function eos_dp_allowed_backend_scripts() {
	return apply_filters(
		'fdp_allowed_backend_scripts',
		array(
			'jquery-core',
			'jquery',
			'jquery-ui',
			'jquery-ui-core',
			'jquery-ui-mouse',
			'jquery-ui-sortable',
			'jquery-ui-draggable',
			'eos-dp-backend',
			'eos-dp-pro-backend',
			'eos-dp-pro-logged',
			'fdp-whois',
			'fdp-hooks-recorder',
			'fdp-pro-migration',
			'fdp-main-js',
			'fdp-assets-js',
			'fdp-settings',
			'fdp-one-place',
			'wp-theme-plugin-editor',
			'fdp-code-browser',
			'fdp-favorite-plugins',
			'fdp-pro-settings',
			'fdp-pro-bulk-actions',
			'fdp-pro-plugins',
			'fdp-pro-roles-manager',
			'fdp-translation-urls'
		)
	);
}

/**
 * It loads plugin translation files.
 *
 * @since 1.9.0
 *
 */
function eos_load_dp_plugin_textdomain() {
	load_plugin_textdomain( 'freesoul-deactivate-plugins', false, EOS_DP_PLUGIN_DIR . '/languages/' );
}
add_action( 'admin_init', 'eos_load_dp_plugin_textdomain' );

/**
 * Filter function to read plugin translation files.
 *
 * @since 1.9.0
 *
 */
function eos_dp_load_translation_file( $mofile, $domain ) {
	if ( 'freesoul-deactivate-plugins' === $domain ) {
		$loc    = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$mofile = EOS_DP_PLUGIN_DIR . '/languages/freesoul-deactivate-plugins-' . $loc . '.mo';
	}
	return $mofile;
}

add_filter( 'load_textdomain_mofile', 'eos_dp_load_translation_file', 99, 2 ); // loads plugin translation files.


add_action( 'admin_head', 'eos_dp_add_admin_inline_style' );

/**
 * Add admin inline style.
 *
 * @since 1.9.0
 *
 */
function eos_dp_add_admin_inline_style() {
	$user            = wp_get_current_user();
	$fdp_preferences = get_user_meta( $user->ID, 'fdp_toplevel_admin_menu', true );
	$toplevel_menu   = ! $fdp_preferences ? true : 'true' === $fdp_preferences;
	$menu_item       = $toplevel_menu ? '#toplevel_page_fdp_hidden_menu{display:none !important}' : '#toplevel_page_fdp_hidden_menu,#toplevel_page_eos_dp_menu{display:none !important}';
	?>
	<style id="fdp-clean-screen-css" type="text/css">
	<?php echo $menu_item; //phpcs:ignore WordPress.Security.EscapeOutput -- No need to escape an hardcoded value. ?>
	<?php if( isset( $_GET['fdp-iframe'] ) && 'true' === sanitize_text_field( $_GET['fdp-iframe'] ) ) { ?>
	div#templateside,#wpadminbar,div#wpwrap> div:not(#wpcontent),div#wpbody-content>div:not(.wrap),.fileedit-sub,h1,#dolly,label#theme-plugin-editor-label{display:none !important}
	#wpcontent{margin-left:0 !important;margin-right:0 !important}body{margin-top:-30px !important}
	#template .CodeMirror, #template textarea{min-height:500px !important}
	#template>div{margin-right:-20px}
	#wpcontent:not(.js #wpcontent){visibility:hidden !important}
	<?php } ?>
	</style>
	<?php
}
