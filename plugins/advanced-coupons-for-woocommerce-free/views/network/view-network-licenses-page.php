<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
exit;} ?>

<div id="advanced-coupons-network-admin" class="wrap woocommerce">

    <div class="acfw-overview" style="margin: 1.5em 0;">
        <img src="<?php echo esc_url( $acfw_logo ); ?>" alt="<?php esc_attr_e( 'Advanced Coupons logo', 'advanced-coupons-for-woocommerce-free' ); ?>">
        <h1><strong><?php esc_html_e( 'Advanced Coupons Licenses', 'advanced-coupons-for-woocommerce-free' ); ?></strong></h1>
    </div>

    <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
        <?php foreach ( $license_plugins as $license_plugin ) : ?>
            <a 
                class="nav-tab <?php echo $current_tab === $license_plugin['key'] ? 'nav-tab-active' : ''; ?>"
                href="<?php echo esc_url( $license_plugin['url'] ); ?>" 
                data-tab="<?php echo esc_attr( $license_plugin['key'] ); ?>"
            >
                <?php echo esc_html( $license_plugin['name'] ); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="license-form-wrap">
        <?php do_action( 'acfw_network_menu_' . $current_tab . '_content' ); ?>
        <?php do_action( 'acfw_network_menu_license_content', $current_tab ); ?>
    </div>

</div>
