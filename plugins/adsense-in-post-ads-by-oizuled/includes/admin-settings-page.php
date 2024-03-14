<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

/* Settings Page */

// Hook for adding admin menus
add_action('admin_menu', 'adsense_inpost_ads_add_pages');

// action function for above hook
function adsense_inpost_ads_add_pages() {
	// Add a new submenu under Settings:
	global $aipa_settings_page;
	$aipa_settings_page = add_options_page('In-Post Ads Options','In-Post Ads', 'manage_options', 'inpost-ads', 'adsense_inpost_ads_settings_page');
}

function adsense_inpost_ads_enqueues() {
	// Add the admin style if on the settings page
	global $aipa_settings_page;
	$current_screen = get_current_screen();
	if ( $current_screen->id == $aipa_settings_page ) {
		wp_enqueue_style( 'adsense_inpost_ads_style', ADSENSE_INPOST_ADS_PLUGIN_URL . '/includes/css/styles.css', '', ADSENSE_INPOST_ADS_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'adsense_inpost_ads_enqueues' );

// Display the page content for the AdSense submenu
function adsense_inpost_ads_settings_page() {
	global $aip_options; ?>
	<div class="wrap">
		<h2><?php _e( 'In-Post Ads Options', 'adsense-inpost-ads' ); ?></h2>
		<div class="aipa-settings-container">
			<p><?php _e( 'You can enter up to three different sets of ad codes to use in your pages and posts.<br />You can create a number of different styled image or text based ads.', 'adsense-inpost-ads' ); ?></strong></p>
			<form method='post' action='options.php'>
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'adsense_inpost_ads_settings_group' ); ?>
				<table class="form-table">
					<tbody>
						<?php do_action( 'aip_settings' ); ?>

						<tr valign="top">
							<td colspan="2"><input type="hidden" name="action" value="update" /><?php submit_button(); ?></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
	<?php
}

add_action( 'aip_settings', 'adsense_ad_blocks' );
function adsense_ad_blocks(){
	global $aip_options; ?>
	<tr valign="top">
		<th scope="row"><?php _e( 'Enter the ad code provided to you from your <a href="https://www.google.com/adsense" target="_blank">Google AdSense account</a> or other ad network.', 'adsense-inpost-ads' ); ?></th>
		<td>
			<textarea rows="5" cols="36" name="aip_settings[unit_a]"><?php echo $aip_options[ 'unit_a' ]; ?></textarea><br /><?php _e( 'Use <strong>[AdSense-A]</strong> to display this ad.', 'adsense-inpost-ads' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Enter the ad code provided to you from your <a href="https://www.google.com/adsense" target="_blank">Google AdSense account</a> or other ad network.', 'adsense-inpost-ads' ); ?></th>
		<td>
			<textarea rows="5" cols="36" name="aip_settings[unit_b]"><?php echo $aip_options[ 'unit_b' ]; ?></textarea><br /><?php _e( 'Use <strong>[AdSense-B]</strong> to display this ad.', 'adsense-inpost-ads' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Enter the ad code provided to you from your <a href="https://www.google.com/adsense" target="_blank">Google AdSense account</a> or other ad network.', 'adsense-inpost-ads' ); ?></th>
		<td>
			<textarea rows="5" cols="36" name="aip_settings[unit_c]"><?php echo $aip_options[ 'unit_c' ]; ?></textarea><br /><?php _e( 'Use <strong>[AdSense-C]</strong> to display this ad.', 'adsense-inpost-ads' ); ?>
		</td>
	</tr>
	<?php
}

function adsense_inpost_ads_register_settings() {
	// creates our settings in the options table
	register_setting( 'adsense_inpost_ads_settings_group', 'aip_settings' );
}
add_action( 'admin_init', 'adsense_inpost_ads_register_settings' );