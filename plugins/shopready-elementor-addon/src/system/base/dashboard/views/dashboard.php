<!-- WooCommerce Builder Dashboard -->
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woo_ready_admin_message' ); ?>

<div
    class="woo-ready-admin-dashboard-container <?php echo esc_attr( apply_filters( 'woo_ready_dashboard_container_class', 'woo-ready-admin-dashboard-container' ) ); ?>">
    <!--Wrapper Container -->
    <div
        class="woo-ready-admin-dashboard-inner-content <?php echo esc_attr( apply_filters( 'woo_ready_dashboard_inner_class', 'woo-ready-admin-dashboard-inner-content' ) ); ?>">
        <div class="shop-ready-admin-topbar">
            <div class="main-logo">
                <a href="<?php echo esc_url( SHOP_READY_DEMO_URL ); ?>"><img
                        src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . '/main-logo.svg' ); ?>" alt=""></a>
            </div>
            <?php

			if ( ! defined( 'SHOP_READY_PRO' ) ) {
				?>
            <div class="buy-button">
                <a href="<?php echo esc_url( SHOP_READY_DEMO_URL ); ?>"><img
                        src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . '/shopping-cart-solid.svg' ); ?>" alt="">
                    <?php echo esc_html__( 'Buy Pro', 'shopready-elementor-addon' ); ?> </a>
            </div>
            <?php } ?>
        </div>
        <div
            class="woo-ready-menu-tab <?php echo esc_attr( apply_filters( 'woo_ready_dashboard_tab_menu_class', 'woo-ready-menu-tab' ) ); ?>">
            <?php do_action( 'woo_ready_tab_item' ); ?>
        </div>

        <div class="woo-ready-tab-content-container">

            <?php do_action( 'woo_ready_tab_content' ); ?>
        </div>
    </div> <!-- dashboard-inner-content end -->
</div>
<!--Wrapper Container end -->
<!-- Notification -->
<div id="woo-ready-admin-notification"></div>