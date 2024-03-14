<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div id="poststuff" class="vl-license-settings <?php echo esc_attr( $tab ); ?>">
    <div class="vl-license-settings-container">
        <img class="logo" src="<?php echo esc_attr( plugins_url( '/templates/admin/images/logo.png', WOO_CE_RELPATH ) ); ?>" alt="<?php esc_attr_e( 'Visser Labs', 'woocommerce-exporter' ); ?>" />
        <h1 class="title"><?php esc_html_e( 'Licenses', 'woocommerce-exporter' ); ?></h1>
        <p class="desc"><?php esc_html_e( 'Enter your license keys below to enjoy full access, plugin updates, and support.', 'woocommerce-exporter' ); ?></p>
        <div class="postbox license-box" id="license-tabs">
            <ul class="license-nav-tabs">
                <?php foreach ( $vl_plugins_tabs as $vl_plugins_tab ) : ?>
                    <li class="<?php echo $vl_plugins_tab['active'] ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url( $vl_plugins_tab['url'] ); ?>"><?php echo esc_html( $vl_plugins_tab['title'] ); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab">
                <?php do_action( 'vl_license_settings_page_content', $vl_plugins_tabs ); ?>
            </div>
        </div>
    </div> 
</div>
<!-- #poststuff -->