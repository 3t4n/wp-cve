<?php
/**
 * Auth form login
 */

defined( 'ABSPATH' ) || exit;

do_action( 'mypos_auth_page_header' ); ?>

<h1 class="wc-auth-title">
    MyPOS would like to connect to your store <strong>"<?= esc_html( $store_name ) ?>"</strong>?
</h1>

<?php wc_print_notices(); ?>

<p class="wc-auth-subtitle">
    <?php
    /* translators: %1$s: app name, %2$s: URL */
    echo wp_kses_post( sprintf( __( 'To connect to "%1$s" you need to be logged in. Log in to your store below.', 'woocommerce' ), esc_html( wc_clean( $store_name ) ), esc_url( $return_url ) ) );
    ?>
</p>

<!-- Not Logged in header -->
<div class="wc-auth-header">
    <div>
        <img src="<?php echo esc_url(plugins_url('/mypos-virtual-for-woocommerce/assets/images/mypos_logo.png')); ?>"
             alt="myPOS" class="mypos-logo"/>
        <span>+</span>
        <h1 id="wc-logo"><img src="<?php echo esc_url(WC()->plugin_url()); ?>/assets/images/woocommerce_logo.png"
                              alt="<?php esc_attr_e('WooCommerce', 'woocommerce'); ?>"/></h1>
    </div>
</div>

<form method="post" class="wc-auth-login">
    <p class="form-row form-row-wide">
        <label for="username"><?php esc_html_e( 'Username or Е-mail address', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" /><?php //@codingStandardsIgnoreLine ?>
    </p>
    <p class="form-row form-row-wide">
        <label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?></label>
        <input class="input-text" type="password" name="password" id="password" />
    </p>
    <?php

    /**
     * Fires following the 'Password' field in the login form.
     *
     * @since 2.1.0
     */
    do_action( 'login_form' );

    ?>
    <p class="wc-auth-actions">
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <button type="submit" class="button button-large button-primary wc-auth-login-button" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
        <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect_url ); ?>" />

        <?php
        /* translators: %1$s: URL */
        echo wp_kses_post( sprintf( __( '<a href="%1$s" class="wc-auth-cancel-link">Cancel and return</a>', 'woocommerce' ), esc_url($return_url)));
        ?>
    </p>

</form>

<?php do_action( 'mypos_auth_page_footer' ); ?>
