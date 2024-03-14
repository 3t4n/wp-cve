<?php
/**
 * Alert - global - partial page.
 *
 * @var array $notice_data Notice data.
 * @var string $notice_key Notice key.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$notice_type    = iub_array_get( $notice_data, 'type' );
$notice_title   = iub_array_get( $notice_data, 'title' );
$notice_message = iub_array_get( $notice_data, 'message' );
?>

<div class="alert alert--<?php echo( 'error' === $notice_type ? 'failure' : 'success' ); ?> is-dismissible m-4">
	<div class="alert__icon p-4" style="display: flex; align-items:center; justify-content: center;">
		<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/banner_<?php echo 'error' === $notice_type ? esc_attr( 'failure' ) : esc_attr( 'success' ); ?>.svg">
	</div>
	<p class="text-regular"> <?php echo wp_kses_post( $notice_message ); ?></p> </br>
	<button class="btn-close mr-3 notice-dismiss dismiss-notification-alert" data-dismiss-key="<?php echo esc_attr( $notice_key ); ?>"> ×</button>
</div>
