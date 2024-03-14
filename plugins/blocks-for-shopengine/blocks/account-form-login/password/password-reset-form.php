<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

use ShopEngine\Utils\Helper;

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
    $wp_resetpass_cookie = !empty($_COOKIE[ 'wp-resetpass-' . COOKIEHASH ]) ? sanitize_text_field(wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] )) : "";
    list( $rp_id, $rp_key ) = array_map( 'wc_clean', explode( ':', $wp_resetpass_cookie, 2 ) );
    $userdata = get_userdata( absint( $rp_id ) );
    $rp_login = $userdata ? $userdata->user_login : '';

    $args = [
        'key'   => $rp_key,
        'login' => $rp_login,
    ];
?>
	<div class="shopengine-account-form-login">
	<form method="post" class="woocommerce-ResetPassword woocommerce-form shopengine-account-form-login lost_reset_password">

		<p>
            <?php echo wp_kses(apply_filters( 'woocommerce_reset_password_message', __('Enter a new password below.', 'shopengine-gutenberg-addon')), Helper::get_kses_array()); ?>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="username"><?php esc_html_e('New password', 'shopengine-gutenberg-addon'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1"
                   id="password_1" autocomplete="new-password" />
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="username"><?php esc_html_e('Re-enter new password', 'shopengine-gutenberg-addon'); ?>&nbsp;
                <span class="required">*</span>
            </label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
        </p>

		<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
		<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

		<div class="clear"></div>

		<?php do_action( 'woocommerce_resetpassword_form' ); ?>

		<p class="woocommerce-form-row form-row">
			<input type="hidden" name="wc_reset_password" value="true" />
			<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'shopengine-gutenberg-addon' ); ?>"><?php esc_html_e( 'Save', 'shopengine-gutenberg-addon' ); ?></button>
		</p>
        <p class="woocommerce-LostPassword lost_password">
            <a href="<?php echo esc_url(home_url('my-account')); ?>"><?php esc_html_e('Login now', 'shopengine-gutenberg-addon'); ?></a>
        </p>

		<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

	</form>
	</div>
<?php
do_action( 'woocommerce_after_reset_password_form' );

