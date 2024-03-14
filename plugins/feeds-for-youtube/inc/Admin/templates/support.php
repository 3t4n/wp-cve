<h3><?php use SmashBalloon\YouTubeFeed\Feed_Locator;

	_e( 'Need help?', $text_domain ); ?></h3>

<p><?php echo sby_admin_icon( 'life-ring', 'sbspf_small_svg' ); ?>&nbsp; <?php _e( 'Check out our ', $text_domain ); ?><a href="<?php echo esc_url( $setup_url ); ?>" target="_blank"><?php _e( 'setup directions', $text_domain ); ?></a> <?php _e( 'for a step-by-step guide on how to setup and use the plugin', $text_domain ); ?>.</p>

<p><?php echo sby_admin_icon( 'envelope', 'sbspf_small_svg' ); ?>&nbsp; <?php _e( 'Have a problem? Submit a ', $text_domain ); ?><a href="<?php echo esc_url( $setup_url ); ?>" target="_blank"><?php _e( 'support ticket', $text_domain ); ?></a> <?php _e( 'on our website', $text_domain ); ?>.  <?php _e( 'Please include your <b>System Info</b> below with all support requests.', $text_domain  ); ?></p>

<br />
<h3><?php _e('System Info', $text_domain ); ?> &nbsp; <span style="color: #666; font-size: 11px; font-weight: normal;"><?php _e( 'Click the text below to select all', $text_domain ); ?></span></h3>

<textarea readonly="readonly" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)." style="width: 70%; height: 500px; white-space: pre; font-family: Menlo,Monaco,monospace;">
## SITE/SERVER INFO: ##
Plugin Version:           <?php echo $plugin_name . ' v' . $plugin_version. "\n"; ?>
Site URL:                 <?php echo site_url() . "\n"; ?>
Home URL:                 <?php echo home_url() . "\n"; ?>
WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
PHP Version:              <?php echo PHP_VERSION . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>
PHP allow_url_fopen:      <?php echo ini_get( 'allow_url_fopen' ) ? "Yes" . "\n" : "No" . "\n"; ?>
PHP cURL:                 <?php echo is_callable('curl_init') ? "Yes" . "\n" : "No" . "\n"; ?>
JSON:                     <?php echo function_exists("json_decode") ? "Yes" . "\n" : "No" . "\n" ?>
SSL Stream:               <?php echo in_array('https', stream_get_wrappers()) ? "Yes" . "\n" : "No" . "\n" //extension=php_openssl.dll in php.ini ?>

## ACTIVE PLUGINS: ##
<?php
$plugins = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );

foreach ( $plugins as $plugin_path => $plugin ) {
	// If the plugin isn't active, don't show it.
	if ( in_array( $plugin_path, $active_plugins ) ) {
		echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
	}
}
?>

## OPTIONS: ##
<?php
$options = get_option( $this->get_option_name(), array() );
foreach ( $options as $key => $val ) {
	if ( is_array( $val ) ) {
		foreach ( $val as $key2 => $val2 ) {
			if ( is_array( $val2 ) ) {
				foreach ( $val2 as $key3 => $val3 ) {
					$label = $key3 . ':';
					$value = isset( $val3 ) ? esc_attr( $val3 ) : 'unset';
					echo str_pad( $label, 24 ) . $value ."\n";
				}
			} else {
				$label = $key2 . ':';
				$value = isset( $val2 ) ? esc_attr( $val2 ) : 'unset';
				echo str_pad( $label, 24 ) . $value ."\n";
			}
		}
	} else {
		$label = $key . ':';
		$value = isset( $val ) ? esc_attr( $val ) : 'unset';
		echo str_pad( $label, 24 ) . $value ."\n";
	}

}
?>

## Cron Events: ##
<?php
$cron = _get_cron_array();
foreach ( $cron as $key => $data ) {
	$is_target = false;
	foreach ( $data as $key2 => $val ) {
		if ( strpos( $key2, 'sby' ) !== false ) {
			$is_target = true;
			echo $key2;
			echo "\n";
		}
	}
	if ( $is_target) {
		echo date( "Y-m-d H:i:s", $key );
		echo "\n";
		echo 'Next Scheduled: ' . ((int)$key - time())/60 . ' minutes';
		echo "\n\n";
	}
}
?>
## Cron Cache Report: ##
<?php $cron_report = get_option( 'sby_cron_report', array() );
if ( ! empty( $cron_report ) ) {
	var_export( $cron_report );
}
echo "\n";
?>

## Location Summary: ##
<?php
$locator_summary = Feed_Locator::summary();
$condensed_shortcode_atts = array( 'type', 'channel', 'id', 'live', 'search', 'playlist', 'layout', 'whitelist', 'includewords' );

if ( ! empty( $locator_summary) ) {

	foreach ( $locator_summary as $locator_section ) {
		if ( ! empty( $locator_section['results'] ) ) {
			$first_five = array_slice( $locator_section['results'], 0, 5 );
			foreach ( $first_five as $result ) {
				$condensed_shortcode_string = '[youtube-feeds';
				$shortcode_atts             = json_decode( $result['shortcode_atts'], true );
				$shortcode_atts             = is_array( $shortcode_atts ) ? $shortcode_atts : array();
				foreach ( $shortcode_atts as $key => $value ) {
					if ( in_array( $key, $condensed_shortcode_atts, true ) ) {
						$condensed_shortcode_string .= ' ' . esc_html( $key ). '="' . esc_html( $value ) . '"';
					}
				}
				$condensed_shortcode_string .= ']';
				echo esc_url( get_the_permalink( $result['post_id'] ) ) . ' ' . $condensed_shortcode_string . "\n";
			}

		}
	}
}?>

## Error Log: ##
<?php
global $sby_posts_manager;
$errors = $sby_posts_manager->get_errors();
if ( ! empty( $errors ) ) :
	foreach ( $errors as $type => $error ) :
		echo $type . ': ' . str_replace( array( '<p>', '<b>', '</p>', '</b>' ), ' ', $error[1] . ' ' . $error[2] ) . "\n";
	endforeach;
endif;
?>

</textarea>