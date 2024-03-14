<?php

defined('ABSPATH') || exit;

if (\ShopEngine\Core\Template_Cpt::TYPE && $block->is_editor) {

    if ('no' === get_option('woocommerce_enable_checkout_login_reminder')) : ?>
        <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
            <?php esc_html_e('This option is turned off from settings', 'shopengine-gutenberg-addon'); ?>
        </div>
    <?php

        return;

    endif;

    if (!function_exists('wc_print_notice')) {
        require_once WC_ABSPATH . 'includes/wc-notice-functions.php';
    }

    ?>
    <div class="shopengine-checkout-form-login">
        <div class="woocommerce-form-login-toggle">
            <?php wc_print_notice(
                apply_filters(
                    $title = esc_html__("Login", "shopengine-gutenberg-addon"),
                    'woocommerce checkout login message ',
                    esc_html__('Returning customer?', 'shopengine-gutenberg-addon')
                ) . '<a title="' . $title . '" href="#" class="showlogin">' . esc_html__(' Click here to login', 'shopengine-gutenberg-addon') . '</a>',
                'notice'
            );  ?>
        </div>
        <?php
        $hidden = false;
        $redirect = wc_get_checkout_url();
        $message = esc_html__('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'shopengine-gutenberg-addon');
        ?>
        <form class="woocommerce-form woocommerce-form-login login" method="post" <?php echo $hidden ? 'style="' . esc_attr('display:none;') . '"' : ''; ?>>

            <?php do_action('woocommerce_login_form_start'); ?>

            <?php echo wp_kses_post($message ? wpautop(wptexturize($message)) : '');
            ?>

            <p class="form-row form-row-first">
                <label for="username"><?php esc_html_e('Username or email', 'shopengine-gutenberg-addon'); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="input-text" name="username" id="username" autocomplete="username" required />
            </p>
            <p class="form-row form-row-last">
                <label for="password"><?php esc_html_e('Password', 'shopengine-gutenberg-addon'); ?>&nbsp;<span class="required">*</span></label>
                <input class="input-text" type="password" name="password" id="password" autocomplete="current-password" required />
            </p>
            <div class="clear"></div>

            <?php do_action('woocommerce_login_form'); ?>

            <p class="form-row">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    <span><?php esc_html_e('Remember me', 'shopengine-gutenberg-addon'); ?></span>
                </label>
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>" />
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('Login', 'shopengine-gutenberg-addon'); ?>"><?php esc_html_e('Login', 'shopengine-gutenberg-addon'); ?></button>
            </p>
            <p class="lost_password">
                <a title="<?php esc_html_e('Lost Password', 'shopengine-gutenberg-addon') ?>" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'shopengine-gutenberg-addon'); ?></a>
            </p>

            <div class="clear"></div>

            <?php do_action('woocommerce_login_form_end'); ?>

        </form>

    </div>
<?php
} else {
?>
    <div class="shopengine-checkout-form-login">
        <?php woocommerce_checkout_login_form(); ?>
    </div>
    <?php if (!is_user_logged_in()) { ?>
        <p class="shopengine-multistep-active" style="display: none;">
            <?php 
            echo wp_kses( sprintf(
                __( '<strong> Note: </strong> Don\'t have an account? proceed to next step and checked the <strong>Create an account?</strong> checkbox.',  'shopengine-gutenberg-addon' ),
                array( 'strong' => array() )
            ), array(), array() );
            ?>
        </p>
<?php
    }
}
