<?php
/**
 * Auth form grant access
 */

defined( 'ABSPATH' ) || exit;
?>

<?php do_action( 'mypos_auth_page_header' ); ?>

<h1 class="wc-logged-in-title">

    MyPOS would like to connect to your store <strong>"<?= esc_html( $store_name ) ?>"</strong>?
</h1>

<?php wc_print_notices(); ?>
<!-- Logged in header -->
<div class="wc-auth-header wc-auth-logged-in-header">
    <div>
        <img src="<?php echo esc_url(plugins_url('/mypos-virtual-for-woocommerce/assets/images/mypos_logo.png')); ?>"
             alt="myPOS" class="mypos-logo"/>
        <span>+</span>
        <h1 id="wc-logo"><img src="<?php echo esc_url(WC()->plugin_url()); ?>/assets/images/woocommerce_logo.png"
                              alt="<?php esc_attr_e('WooCommerce', 'woocommerce'); ?>"/></h1>
    </div>

    <div class="wc-auth-logged-in-as">
        <div>
            <p>
                <?php
                /* Translators: %s display name. */
                printf(esc_html__('Logged in as %s', 'woocommerce'), esc_html($user->display_name));
                ?>
            </p>
            <a href="<?php echo esc_url($logout_url); ?>"
               class="wc-auth-logout"><?php esc_html_e('Logout?', 'mypos'); ?></a>
        </div>
        <?php echo get_avatar($user->ID, 40); ?>
    </div>
</div>

<p class="wc-auth-actions">
    <a href="<?php echo esc_url( $return_url ); ?>" class="button button-ghost wc-auth-deny"><?php esc_html_e( 'Deny', 'mypos' ); ?></a>
    <a href="<?php echo esc_url( $granted_url ); ?>" class="button button-primary wc-auth-approve"><?php esc_html_e( 'Approve', 'mypos' ); ?></a>
</p>

<?php do_action( 'mypos_auth_page_footer' ); ?>
