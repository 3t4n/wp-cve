<?php require_once 'parts/tabs.php'; ?>
<h1 class="screen-reader-text"><?php esc_html_e( 'Advanced', 'woocommerce-checkout-manager' ); ?></h1>
<h2><?php esc_html_e( 'Advanced settings', 'woocommerce-checkout-manager' ); ?></h2>
<?php woocommerce_admin_fields( $settings ); ?>
