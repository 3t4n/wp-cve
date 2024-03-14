<?php
/**
 * Show error messages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}

?>
<ul class="woocommerce-error" role="alert">
    <?php foreach ( $notices as $notice ) : ?>
    <li<?php echo wp_kses_post(wc_get_notice_data_attr( $notice )); ?>>
        <?php echo wp_kses_post( wc_kses_notice( $notice['notice'] ) ); ?>
        </li>
        <?php endforeach; ?>
</ul>