<?php

/*
	Plugin Name: Ultimate PopUnder
	Plugin URI: #
	Description: Be able to create any popunder AD for your site, and track the activity in 3rd party systems as well.
	Version: 1.2.5
	Author: Cris Griffith
	Author URI: https://profiles.wordpress.org/webmasterjunkie
	License: GPLv2 or later
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'ULTIMATE_POPUNDER_VERSION', '1.2.6' );

require_once( plugin_dir_path( __FILE__ ) . "functions.php" );
require_once( plugin_dir_path( __FILE__ ) . "activate.php" );

/**
 * Show the settings page in admin
 */
function ultimate_popunder_options_page(  ) {

	?>
	<div class="wrap">
		<form action='options.php' method='post'>

			<h1>Ultimate PopUnder Settings</h1>

			<?php
			settings_fields( 'ultimatePopunder' );
			do_settings_sections( 'ultimatePopunder' );
			submit_button();
			?>

		</form>
	</div>
	<?php
}

function ultimate_popunder_add_admin_menu()
{
	add_options_page(
		'Ultimate PopUnder',
		'Ultimate PopUnder',
		'manage_options',
		'ultimate_popunder',
		'ultimate_popunder_options_page'
	);
}

function ultimate_popunder_settings_init()
{
	register_setting( 'ultimatePopunder', '_ultimate_popunder_settings' );

	add_settings_section(
		'ultimate_popunder_ultimatePopunder_section',
		__( 'Specify the settings for the PopUnder windows', 'ultimate_popunder' ),
		'ultimate_popunder_settings_section_callback',
		'ultimatePopunder'
	);

	add_settings_field(
		'ultimate_popunder_text_popwidth',
		__( 'PopUnder Width (Percent)', 'ultimate_popunder' ),
		'ultimate_popunder_text_popwidth_render',
		'ultimatePopunder',
		'ultimate_popunder_ultimatePopunder_section'
	);

	add_settings_field(
		'ultimate_popunder_text_popheight',
		__( 'PopUnder Height (Percent)', 'ultimate_popunder' ),
		'ultimate_popunder_text_popheight_render',
		'ultimatePopunder',
		'ultimate_popunder_ultimatePopunder_section'
	);

	add_settings_field(
		'ultimate_popunder_text_poplength',
		__( 'Cookie Length (Minutes)', 'ultimate_popunder' ),
		'ultimate_popunder_text_poplength_render',
		'ultimatePopunder',
		'ultimate_popunder_ultimatePopunder_section'
	);

	add_settings_field(
		'ultimate_popunder_text_popmax',
		__( 'Maximum PopUnders', 'ultimate_popunder' ),
		'ultimate_popunder_text_popmax_render',
		'ultimatePopunder',
		'ultimate_popunder_ultimatePopunder_section'
	);

	add_settings_field(
		'ultimate_popunder_select_tracker',
		__( 'Track Opened PopUnders', 'ultimate_popunder' ),
		'ultimate_popunder_select_tracker_render',
		'ultimatePopunder',
		'ultimate_popunder_ultimatePopunder_section'
	);
}

function ultimate_popunder_select_tracker_render()
{
	$options = get_option( '_ultimate_popunder_settings' );
	?>
	<select name='_ultimate_popunder_settings[ultimate_popunder_select_tracker]'>
		<option value="0" <?php selected( $options['ultimate_popunder_select_tracker'], 0 ); ?>>No</option>
		<option value="ga" <?php selected( $options['ultimate_popunder_select_tracker'], "gtm" ); ?>>Google Analytics</option>
		<option value="gtm" <?php selected( $options['ultimate_popunder_select_tracker'], "gtm" ); ?>>Google Tag Manager</option>
	</select>
	<?php
}
function ultimate_popunder_text_popwidth_render()
{
	$options = get_option( '_ultimate_popunder_settings' );
	?>
	<input type='number' name='_ultimate_popunder_settings[ultimate_popunder_text_popwidth]' value='<?php echo $options['ultimate_popunder_text_popwidth']; ?>' placeholder="80" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
	<?php
}
function ultimate_popunder_text_popheight_render()
{

	$options = get_option( '_ultimate_popunder_settings' );
	?>
	<input type='number' name='_ultimate_popunder_settings[ultimate_popunder_text_popheight]' value='<?php echo $options['ultimate_popunder_text_popheight']; ?>' placeholder="80" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
	<?php

}
function ultimate_popunder_text_poplength_render()
{
	$options = get_option( '_ultimate_popunder_settings' );
	?>
	<input type='number' name='_ultimate_popunder_settings[ultimate_popunder_text_poplength]' value='<?php echo $options['ultimate_popunder_text_poplength']; ?>' placeholder="30" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
	<?php
}
function ultimate_popunder_text_popmax_render()
{
	$options = get_option( '_ultimate_popunder_settings' );
	?>
	<input type='number' name='_ultimate_popunder_settings[ultimate_popunder_text_popmax]' value='<?php echo $options['ultimate_popunder_text_popmax']; ?>' placeholder="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
	<?php
}
function ultimate_popunder_settings_section_callback()
{
	echo __( 'Use these settings to control the PopUnder globally. Things like the maximum amount of PopUnders the website visitor can see.', 'ultimate_popunder' );
}

?>