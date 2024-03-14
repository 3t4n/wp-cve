<?php

// Simple Site Verify Settings

add_action( 'admin_menu', 'ssv_admin_menu' );
add_action( 'admin_init', 'ssv_settings_init' );

function ssv_admin_menu() {

	add_options_page(
		'Simple Site Verify', // Page Title
		'Site Verify', // Menu Title
		'manage_options', // Capability
		'simple_site_verify', // Menu Slug
		'ssv_options_page' // Function
	);

}

function ssv_settings_init() { 

	register_setting( 'simpleVerify', 'ssv_settings' );

	add_settings_section(
		'ssv_section', // ID
		__( 'Verification Sites', 'simple-site-verify' ), // Title
		'ssv_settings_section_callback', // Callback function
		'simpleVerify' // Menu page
	);

	// Pinterest
	add_settings_field( 
		'ssv_pinterest', // ID
		__( 'Pinterest', 'simple-site-verify' ), // Title
		'ssv_pinterest_render', // Callback function
		'simpleVerify', // Menu page
		'ssv_section' // Settings section
	);

	// Google
	add_settings_field( 
		'ssv_google', // ID
		__( 'Google', 'simple-site-verify' ), // Title
		'ssv_google_render', // Callback function
		'simpleVerify', // Menu page
		'ssv_section' // Settings section
	);

	// Google Analytics
	add_settings_field( 
		'ssv_google_analytics', // ID
		__( 'Google Analytics', 'simple-site-verify' ), // Title
		'ssv_google_analytics_render', // Callback function
		'simpleVerify', // Menu Page
		'ssv_section' // Settings Section
	);

	// Bing
	add_settings_field( 
		'ssv_bing', // ID
		__( 'Bing', 'simple-site-verify' ), // Title
		'ssv_bing_render', // Callback function
		'simpleVerify', // Menu page
		'ssv_section' // Settings section
	);

	// Yandex
	add_settings_field( 
		'ssv_yandex', // ID
		__( 'Yandex', 'simple-site-verify' ), // Title
		'ssv_yandex_render', // Callback function
		'simpleVerify', // Menu page
		'ssv_section' // Settings section
	);

}

/* Pinterest Option */
function ssv_pinterest_render() { 

	$options = get_option( 'ssv_settings' ); // Plugin Saved Settings
	$ssv_pinterest = sanitize_text_field( $options['ssv_pinterest'] );

	?>

	<input type='text' name='ssv_settings[ssv_pinterest]' <?php if (isset($options['ssv_pinterest'])) echo 'value="' . esc_html($ssv_pinterest) . '"'; ?> id="pinterest" /><br />
This is your website's unique 32-character code. It is the code shown within content="" inside the meta tag given to you.

	<?php

}

/* Google Option */
function ssv_google_render() { 

	$options = get_option( 'ssv_settings' ); // Plugin Saved Settings
	$ssv_google = sanitize_text_field( $options['ssv_google'] );

	?>

	<input type='text' name='ssv_settings[ssv_google]' <?php if (isset($options['ssv_google'])) echo 'value="' . htmlspecialchars($ssv_google) . '"'; ?> id="google" /><br />
	Google Search Console/Google Apps. This is your website's unique code. It is the code shown within content="" inside the meta tag given to you.

	<?php

}

/* Google Analytics Option */
function ssv_google_analytics_render() { 

	$options = get_option( 'ssv_settings' ); // Plugin Saved Settings
	$ssv_google_analytics = sanitize_text_field( $options['ssv_google_analytics'] );

	?>

	<input type='text' name='ssv_settings[ssv_google_analytics]' <?php if (isset($options['ssv_google_analytics'])) echo 'value="' . htmlspecialchars($ssv_google_analytics) . '"'; ?> id="google-analytics" /><br />This is your website's unique code. It is the code shown within content="" inside the meta tag given to you.

	<?php

}

/* Bing Option */
function ssv_bing_render() { 

	$options = get_option( 'ssv_settings' ); // Plugin Saved Settings
	$ssv_bing = sanitize_text_field( $options['ssv_bing'] );

	?>

	<input type='text' name='ssv_settings[ssv_bing]' <?php if (isset($options['ssv_bing'])) echo 'value="' . htmlspecialchars($ssv_bing) . '"'; ?> id="bing" /><br />This is your website's unique code. It is the code shown within content="" inside the meta tag given to you.

	<?php

}

/* Yandex Option */
function ssv_yandex_render() { 

	$options = get_option( 'ssv_settings' ); // Plugin Saved Settings
	$ssv_yandex = sanitize_text_field( $options['ssv_yandex'] );

	?>

	<input type='text' name='ssv_settings[ssv_yandex]' <?php if (isset($options['ssv_yandex'])) echo 'value="' . htmlspecialchars($ssv_yandex) . '"'; ?> id="yandex" /><br />This is your website's unique code. It is the code shown within content="" inside the meta tag given to you.


	<?php

}

function ssv_settings_section_callback() { 
	echo __( 'Enter site verifcation code. The plugin will do the rest.', 'simple-site-verify' );
}

// Show Option Page
function ssv_options_page() { 

	?>
	<form action="options.php" method="post">
	
		<h2>Simple Site Verify</h2>

		<div class="my-plugin-options section general">

		<?php

			settings_fields( 'simpleVerify' );
			do_settings_sections( 'simpleVerify' );

		?>

		</div>

		<?php submit_button(); ?>
		
	</form>
	<?php

}

?>
