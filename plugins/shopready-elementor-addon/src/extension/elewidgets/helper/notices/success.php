<?php

/**
 * Show messages
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

?>

<?php foreach ( $notices as $notice ) : ?>
<div class="woocommerce-message" <?php echo esc_attr( wc_get_notice_data_attr( $notice ) ); ?> role="alert">
	<?php echo wp_kses_post( wc_kses_notice( $notice['notice'] ) ); ?>
</div>
<?php endforeach; ?>
