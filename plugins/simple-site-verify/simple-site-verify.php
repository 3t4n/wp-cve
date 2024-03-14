<?php

/**
 * Plugin Name: Simple Site Verify
 * Version: 1.0.8
 * Plugin URI: https://idoweb.work/resources/plugins-themes/
 * Description: Simple method of verifying your site via Pinterest, Google, Bing, & Yandex.
 * Author: Michael Mann
 * Author URI: https://idoweb.work
 * License: GPL v2

 * Copyright (C) 2017, Michael Mann - support@idoweb.work

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation version 2.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.

**/

class SimpleVerify {

     /* Setup the environment for the plugin */
     public function bootstrap() {
        register_activation_hook( __FILE__, array( $this, 'ssv_activate' ) ); // Activation Hook
		add_action( 'wp_head', array( $this, 'ssv_head' ), 0 ); // Hook into WP head function
		add_action( 'wp_head', array( $this, 'ssv_google_analytics' ), 99 ); // Hook into WP head function
    }

    //  Plugin Activation
    public function ssv_activate() {
		flush_rewrite_rules(); // Flush Rewrite Rules
    }

	// Add tracking code to header, if set
	function ssv_head() {

		$options = get_option( 'ssv_settings' ); // Plugin saved settings

		// If validation array empty, skip
		if ( empty( $options ) ) {

			return;

		} else {

			// Add validation code to header

			// Add Pinterest validation code if not empty
			if ( array_key_exists ( 'ssv_pinterest', $options ) && !empty( $options[ 'ssv_pinterest' ] ) )
				echo '<meta name="p:domain_verify" content="' . $options[ 'ssv_pinterest' ] . '" />
	';

			// Add Google validation code if not empty
			if ( array_key_exists ( 'ssv_google', $options ) && !empty( $options[ 'ssv_google' ] ) )
				echo '<meta name="google-site-verification" content="' . $options[ 'ssv_google' ] . '" />
	';

			// Add Bing validation code if not empty
			if ( array_key_exists ( 'ssv_bing', $options ) && !empty( $options[ 'ssv_bing' ] ) )
				echo '<meta name="msvalidate.01" content="' . $options[ 'ssv_bing' ] . '" />
	';

			// Add Yandex validation code if not empty
			if ( array_key_exists ( 'ssv_yandex', $options ) && !empty( $options[ 'ssv_yandex' ] ) )
				echo '<meta name="yandex-verification" content="' . $options[ 'ssv_yandex' ] . '" />
	';

		}
	}

		// Google Analytics Validation
	function ssv_google_analytics() {

		$options = get_option( 'ssv_settings' ); // Plugin Saved Settings

		// If Google Analytics value empty, skip
		if ( empty( $options[ 'ssv_google_analytics' ] ) ) {

			return;

		} else {

			// Add UA Google Analytics code if begins with UA-
			if ( array_key_exists ( 'ssv_google_analytics', $options ) && str_starts_with( $options[ 'ssv_google_analytics' ], 'UA-' ) ) {

?>
<!-- BEGIN Google Universal Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $options[ 'ssv_google_analytics' ]; ?>', 'auto');
ga('send', 'pageview');
</script>
<!-- END Google Universal Analytics -->
			<?php

			} // End Google Analytics Validation
			
						// Add Google Analytics 4 code if begins with G-
			if ( array_key_exists ( 'ssv_google_analytics', $options ) && str_starts_with( $options[ 'ssv_google_analytics' ], 'G-' ) ) {

?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $options[ 'ssv_google_analytics' ]; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){window.dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $options[ 'ssv_google_analytics' ]; ?>');
</script>
<!-- END Google Analytics 4 -->

			<?php

			} // End Google Analytics 4 Validation

		} // End Options Empty Check

	} // End ssv_google_analytics()

} //End Simple Verify Class

global $simpleverify;
$simpleverify = new SimpleVerify();
$simpleverify->bootstrap();

// Include plugin settings in admin
if ( is_admin() )
	include plugin_dir_path( __FILE__ ) . 'simple-site-verify-settings.php';

// Add Settings Link to Plugins Page
if( !function_exists( 'ssv_add_settings_link' ) ) {

	function ssv_add_settings_link( $links ) {

		$settings_link = '<a href="options-general.php?page=simple_site_verify">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link ); // Put Settings link in front
	  	return $links;

	}

	add_filter( 'plugin_action_links_'. plugin_basename( __FILE__ ), 'ssv_add_settings_link' );

}

?>
