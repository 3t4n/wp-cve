<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="row">
    <p class="text-large col-left mt-0"><?php esc_html_e( 'You are currently using WooCommerce Store Exporter free version.', 'woocommerce-exporter' ); ?></p>
    <p class="text-large col-right mt-0">
        <span class="text-bold"><?php esc_html_e( 'License Status: ', 'woocommerce-exporter' ); ?></span>
        <span class="text-color-green"><?php esc_html_e( 'Free Version', 'woocommerce-exporter' ); ?></span>
    </p>
</div>
<p class="text-large mt-0 mb-2">
    <span class="text-bold"><?php esc_html_e( 'Version: ', 'woocommerce-exporter' ); ?></span>
    <span><?php echo esc_html( WOO_CE_VERSION ); ?></span>
</p>
<div class="row">
    <div class="col-left">
        <h2><?php esc_html_e( 'Upgrade to WooCommerce Store Exporter Deluxe', 'woocommerce-exporter' ); ?></h2>
        <p><?php esc_html_e( 'The premium deluxe version of store exporter lets you manage store exports with ease. Including scheduling, additional file types, smart exporting filters, customer exporting, subscriptions support, vendors, bookings, and loads more.', 'woocommerce-exporter' ); ?></p>
        <ul class="features-list mb-2">
            <li><?php esc_html_e( 'Lots of new export types such as customers, subscriptions, and more', 'woocommerce-exporter' ); ?></li>
            <li><?php esc_html_e( 'Smart export filtering like order status, products, country, coupons, user role', 'woocommerce-exporter' ); ?></li>
            <li><?php esc_html_e( 'Native integrations with 125+ WooCommerce extensions', 'woocommerce-exporter' ); ?></li>
        </ul>
        <a href="https://visser.com.au/solutions/woocommerce-export/?utm_source=wse&utm_medium=license&utm_campaign=upgradelicensebutton" target="_blank" class="button button-orange button-hero mb-1"><?php esc_html_e( 'Get Store Exporter Deluxe and Unlock All Features', 'woocommerce-exporter' ); ?></a>
        <div class="learn-more">
            <a href="https://visser.com.au/solutions/woocommerce-export/?utm_source=wse&utm_medium=license&utm_campaign=learnmorelicenselink" target="_blank" ><?php esc_html_e( 'Learn more about Deluxe features', 'woocommerce-exporter' ); ?></a>
        </div>
    </div>
    <div class="col-right demo-image">
        <a href="<?php echo esc_attr( plugins_url( '/templates/admin/images/store-exporter-demo.png', WOO_CE_RELPATH ) ); ?>" data-lightbox="store-exporter-demo">
            <img class="img-responsive" src="<?php echo esc_attr( plugins_url( '/templates/admin/images/store-exporter-demo.png', WOO_CE_RELPATH ) ); ?>" alt="<?php esc_attr_e( 'Store exporter deluxe demo', 'woocommerce-exporter' ); ?>">
        </a>
    </div>
</div>