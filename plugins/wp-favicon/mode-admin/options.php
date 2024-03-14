<?php
/**
 * Generate the page options
 * @since 0.1
 */
function favicon_options() {
?>
<!-- WP Favicon Admin -->
<div class="wrap">
	<h2><?php _e( 'WP Favicon Options', WPF_DOMAIN ); ?></h2>
	<p><?php echo sprintf( __( 'Upload your <font color="#FF0000">favicon.ico</font> and <font color="#FF0000">favicon.gif</font> files to <strong>%s/</strong>', WPF_DOMAIN ), get_option( 'siteurl' ) ); ?></p>
	<p>
	<?php _e(
	'Due to the famous web browser from Redmond.' .
	' The best approach is to have 2 separates favicon files located at the site root directory.' . '<br>' .
	'1. The first  favicon file (favicon.ico) is either a <strong>16x16</strong> or a <strong>32x32</strong> icon. Nowadays, all modern browser supports both size.' . '<br>' .
	'2. The second favicon file (favicon.gif) is a (reasonable) free size, and can be an animated GIF too!' . '<br>' .
	'The .gif version have precedence over the .ico. So, if the browser support <strong>animated</strong> favicon,' . '<br>' .
	'the animated will be displayed, else the static .ico will be used instead.' . '<br>' .
	'<u>Note</u> that both <strong>favicon.ico</strong> and <strong>favicon.gif</strong> can be <strong>different</strong> pictures.'
	, WPF_DOMAIN ); ?>
	</p>
	<div class="wpf-images">
		<div class="wpf-image-caption">
			<div class="wpf-image"><img src="<?php get_option( 'siteurl' ); ?>/favicon.gif" alt="favicon.gif" title="Favicon GIF" width="64" height="64"></div>
			<div class="wpf-caption"><?php _e( 'Your Favicon.gif', WPF_DOMAIN ); ?></div>
		</div>
		<div class="wpf-image-caption">
			<div class="wpf-image"><img src="<?php get_option( 'siteurl' ); ?>/favicon.ico" alt="favicon.ico" title="Favicon ICO" width="64" height="64"></div>
			<div class="wpf-caption"><?php _e( 'Your Favicon.ico', WPF_DOMAIN ); ?></div>
		</div>
	</div>
</div>
<!-- /WP Favicon Admin -->
<?php
}

// Enqueue options CSS
define( 'WPF_PLUGIN_URL', WP_PLUGIN_URL . '/' . WPF_DOMAIN );
wp_enqueue_style ( 'wp-favicon-options', WPF_PLUGIN_URL . '/css/options.css', false, WPF_VER, 'screen' );
?>