<?php

defined('ABSPATH') || exit;
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 *
 * woocommerce/templates/myaccount/form-login.php
 */

// show email sent message
 if(isset($_GET['reset-link-sent']) &&  sanitize_text_field(wp_unslash($_GET['reset-link-sent'])) ==  true){ //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- reset-link-sent set by woocommerce
	wc_add_notice( __("We have sent a mail to you with password reset link. Please check your email.", 'shopengine-gutenberg-addon'), 'success' );
}

if(WC()->session) {
	wc_print_notices();
}

if ( is_lost_password_page() ) {
	$password_reset_form = isset( $_GET['show-reset-form'] ) && sanitize_text_field(wp_unslash($_GET['show-reset-form'])) == true; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- show-reset-form set by woocommerce

	if ( $password_reset_form ) {
		include 'password/password-reset-form.php';
	} else {
		include 'password/password-reset-mail-form.php';
	}
} else {
	?>
   <div class="shopengine shopengine-widget">
        <div class="shopengine-account-form-login">

            <form class="woocommerce-form woocommerce-form-login login" method="post">

                <?php do_action('woocommerce_login_form_start'); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php esc_html_e('Username or email address', 'shopengine-gutenberg-addon'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                        id="username" autocomplete="username"
                        value="<?php echo (!empty($_POST['username']) && !empty($_POST['woocommerce-login-nonce']) && wp_verify_nonce(sanitize_text_field( wp_unslash($_POST['woocommerce-login-nonce']) ), "woocommerce-login")) ? esc_attr(sanitize_text_field(wp_unslash($_POST['username']))) : ''; ?>"/>
                </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"><?php esc_html_e('Password', 'shopengine-gutenberg-addon'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password"
                        id="password" autocomplete="current-password"/>
                </p>

                <?php do_action('woocommerce_login_form'); ?>

                <p class="form-row">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
                            type="checkbox" id="rememberme" value="forever"/>
                        <span><?php esc_html_e('Remember me', 'shopengine-gutenberg-addon'); ?></span>
                    </label>
                    <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login"
                            value="<?php esc_attr_e('Log in', 'shopengine-gutenberg-addon'); ?>"><?php esc_html_e('Log in', 'shopengine-gutenberg-addon'); ?></button>
                </p>

                <p class="woocommerce-LostPassword lost_password">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'shopengine-gutenberg-addon'); ?></a>
                </p>

                <?php do_action('woocommerce_login_form_end'); ?>

            </form>

        </div>
   </div>
<?php

}