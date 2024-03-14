<?php require_once 'parts/tabs.php'; ?>
<h1 class="screen-reader-text"><?php esc_html_e( 'Shipping', 'woocommerce-checkout-manager' ); ?></h1>
<h2><?php esc_html_e( 'Shipping fields', 'woocommerce-checkout-manager' ); ?></h2>
<div id="<?php printf( 'wooccm_%s_settings-description', esc_attr( $current_section ) ); ?>">
	<p><?php printf( esc_html__( 'Customize and manage the checkout %s fields.', 'woocommerce-checkout-manager' ), esc_attr( $current_section ) ); ?></p>
</div>
<?php require_once 'parts/actions.php'; ?>
<?php require_once 'parts/loop.php'; ?>
<?php require_once 'modals/field.php'; ?>
