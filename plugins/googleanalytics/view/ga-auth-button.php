<?php
/**
 * GA Auth Button view.
 *
 * @package GoogleAnalytics
 */

// Template partial fallback values.
$label       = isset( $label ) ? $label : '';
$manually_id = isset( $manually_id ) ? $manually_id : '';
$button_type = isset( $button_type ) ? $button_type : '';
$url         = isset( $url ) ? $url : '';
$classes     = array();

// Determine button classes.
if ( 'auth' === $button_type ) {
	$classes[] = 'button-primary';
} else {
	$classes[] = 'button-secondary';
}
?>
<a id="ga_authorize_with_google_button"
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	href="https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id=1068475218768-0id2em2grsa7fcb1la1t3ephea6l7lvf.apps.googleusercontent.com&redirect_uri=https%3A%2F%2Fsharethis.com%2Fgoogle-analytics-setup%2F&state&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fanalytics.readonly&prompt=consent"
	<?php
	echo disabled(
		false === empty( $manually_id )
		|| false === Ga_Helper::are_features_enabled()
		|| true === Ga_Helper::is_curl_disabled()
	);
	?>
><?php echo esc_html( $label ); ?>
</a>
