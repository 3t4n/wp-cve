<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 * @version 1.0
 */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    if( is_wc_endpoint_url( 'lost-password' ) ){
        return;
    }

?>

<form
    class="width:100% woocommerce-form-login login woo-ready-account-form <?php echo esc_attr($settings['preset']); ?>"
    method="post">
    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide woo-ready-username-wrapper">
        <?php if( $settings[ 'show_label' ] == 'yes' ): ?>
        <label for="username"><?php echo esc_html($settings['username_label']); ?>&nbsp;<span
                class="required">*</span></label>
        <?php endif; ?>
        <input placeholder="<?php echo esc_attr($settings['username_placeholder']); ?>" type="text"
            class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username"
            autocomplete="username"
            value="<?php echo esc_attr( ! empty( $_POST['username']) ) ? esc_attr( sanitize_user($_POST['username']) )  : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
    </div>
    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide woo-ready-password-wrapper">
        <?php if( $settings[ 'show_label' ] == 'yes' ): ?>
        <label for="password"><?php echo esc_html($settings['password_label']); ?>&nbsp;<span
                class="required">*</span></label>
        <?php endif; ?>
        <input placeholder="<?php echo esc_attr($settings['password_placeholder']); ?>"
            class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password"
            autocomplete="current-password" />
    </div>
    <?php if( $settings[ 'show_remember_checkbox' ] == 'yes' ): ?>
    <div class="form-row woo-ready-checkbox-wrapper">

        <label
            class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme woo-ready-form-checkbox">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox"
                id="rememberme" value="forever" /> <span><?php echo esc_html( $settings['remember_text'] ); ?></span>
        </label>

    </div>
    <?php endif; ?>
    <div class="form-row woo-ready-btn-wrapper">
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login"
            value="<?php esc_attr_e( 'Log in', 'shopready-elementor-addon' ); ?>">
            <?php if( $settings['icon_align'] == 'left' ): ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            <?php endif; ?>
            <?php echo esc_html($settings['button_text']); ?>
            <?php if( $settings['icon_align'] == 'right' ): ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            <?php endif; ?>

        </button>
    </div>
</form>