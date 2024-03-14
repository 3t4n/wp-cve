<?php
/**
 * Show messages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! $notices ) {
	return;
}

?>

<?php foreach ( $notices as $notice ) : ?>
<div class="woocommerce-info" <?php echo esc_attr( wc_get_notice_data_attr( $notice ) ); ?>>
	<?php echo wp_kses_post( wc_kses_notice( $notice['notice'] ) ); ?>
</div>
<?php endforeach; ?>
