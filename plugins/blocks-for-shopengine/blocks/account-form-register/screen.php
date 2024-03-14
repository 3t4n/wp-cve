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
 *  * woocommerce/templates/myaccount/form-login.php
 */

if('yes' === get_option('woocommerce_enable_myaccount_registration')): ?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-account-form-register">

        <form method="post"
              class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> >
              <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
			<?php do_action('woocommerce_register_form_start'); ?>

			<?php if('no' === get_option('woocommerce_registration_generate_username')) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_username"><?php esc_html_e('Username', 'shopengine-gutenberg-addon'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                           id="reg_username" autocomplete="username"   value="<?php  if ( ! empty( $_POST['username'] ) && ! empty( $_POST['woocommerce-register-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woocommerce-register-nonce'] ) ), 'woocommerce-register' ) ) {
                            echo esc_attr( sanitize_email( wp_unslash( $_POST['username'] ) ) );
                        } 
                    ?>"/>
                </p>

			<?php endif; ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_email"><?php esc_html_e('Email address', 'shopengine-gutenberg-addon'); ?>&nbsp;<span
                            class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                    id="reg_email" autocomplete="email"  value="<?php if ( ! empty( $_POST['email'] ) && ! empty( $_POST['woocommerce-register-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woocommerce-register-nonce'] ) ), 'woocommerce-register' ) ) {
                        echo esc_attr( sanitize_email( wp_unslash( $_POST['email'] ) ) );
                    }
                ?>"/>
            </p>

			<?php if('no' === get_option('woocommerce_registration_generate_password')) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_password"><?php esc_html_e('Password', 'shopengine-gutenberg-addon'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
                           name="password"
                           id="reg_password" autocomplete="new-password"/>
                </p>

			<?php else : ?>

                <p class="woocommerce-pending-message"><?php esc_html_e('A password will be sent to your email address.', 'shopengine-gutenberg-addon'); ?></p>
                 <?php if($block->is_editor) :?>
                  <div class="woocommerce-privacy-policy-text">
                    <p> <?php esc_html_e('Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our', 'shopengine-gutenberg-addon') ;?>
                       <a href="javascript:void(0)" class="woocommerce-privacy-policy-link" target="_blank"><?php esc_html_e('privacy policy.','shopengine-gutenberg-addon' ) ;?>
                       </a>
                     </p>
                  </div>
                 <?php endif ;?>  
			<?php endif; ?>

			<?php do_action('woocommerce_register_form'); ?>

            <p class="woocommerce-form-row form-row">
				<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                <button type="submit"
                        class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit"
                        name="register"
                        value="<?php esc_attr_e('Register', 'shopengine-gutenberg-addon'); ?>"><?php esc_html_e('Register', 'shopengine-gutenberg-addon'); ?></button>
            </p>

			<?php do_action('woocommerce_register_form_end'); ?>

        </form>

    </div>
</div>
<?php

elseif(get_post_type() === \ShopEngine\Core\Template_Cpt::TYPE): ?>
    <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
		<?php esc_html_e('Register option is turned off from settings', 'shopengine-gutenberg-addon'); ?>
    </div>
<?php
endif;
