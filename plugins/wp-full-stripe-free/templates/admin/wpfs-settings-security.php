<?php
/** @var $backLinkUrl */
/** @var $view MM_WPFS_Admin_SecurityView */
/** @var $securityData */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-security">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <form <?php $view->formAttributes(); ?>>
            <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <div class="wpfs-form-block__title"><?php esc_html_e( 'Google reCAPTCHA', 'wp-full-stripe-admin' ); ?></div>
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php esc_html_e( 'Secure with reCAPTCHA', 'wp-full-stripe-admin' ); ?></label>
                            <div class="wpfs-form-check-list">
                                <div class="wpfs-form-check">
                                    <input id="<?php $view->secureInlineForms()->id(); ?>" name="<?php $view->secureInlineForms()->name(); ?>" value="<?php $view->secureInlineForms()->value(); ?>" <?php $view->secureInlineForms()->attributes(); ?> <?php echo $securityData->secureInlineForms == $view->secureInlineForms()->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $view->secureInlineForms()->id(); ?>"><?php $view->secureInlineForms()->label(); ?></label>
                                </div>
                                <div class="wpfs-form-check">
                                    <input id="<?php $view->secureCheckoutForms()->id(); ?>" name="<?php $view->secureCheckoutForms()->name(); ?>" value="<?php $view->secureCheckoutForms()->value(); ?>" <?php $view->secureCheckoutForms()->attributes(); ?> <?php echo $securityData->secureCheckoutForms == $view->secureCheckoutForms()->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $view->secureCheckoutForms()->id(); ?>"><?php $view->secureCheckoutForms()->label(); ?></label>
                                </div>
                                <div class="wpfs-form-check">
                                    <input id="<?php $view->secureCustomerPortal()->id(); ?>" name="<?php $view->secureCustomerPortal()->name(); ?>" value="<?php $view->secureCustomerPortal()->value(); ?>" <?php $view->secureCustomerPortal()->attributes(); ?> <?php echo $securityData->secureCustomerPortal == $view->secureCustomerPortal()->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $view->secureCustomerPortal()->id(); ?>"><?php $view->secureCustomerPortal()->label(); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        $showReCaptchaKeys = $securityData->secureInlineForms == $view->secureInlineForms()->value(false) ||
                                             $securityData->secureCheckoutForms == $view->secureCheckoutForms()->value(false) ||
                                             $securityData->secureCustomerPortal == $view->secureCustomerPortal()->value(false);
                    ?>
                    <div class="wpfs-form-block" id="google-recaptcha-api-keys" style="<?php echo $showReCaptchaKeys ? '' : 'display: none;' ?>">
                        <div class="wpfs-form-group">
                            <label for="<?php $view->reCaptchaSiteKey()->id(); ?>" class="wpfs-form-label"><?php $view->reCaptchaSiteKey()->label(); ?></label>
                            <input id="<?php $view->reCaptchaSiteKey()->id(); ?>" name="<?php $view->reCaptchaSiteKey()->name(); ?>" type="text" value="<?php echo esc_html( $securityData->recaptchaSiteKey ); ?>" class="wpfs-form-control">
                        </div>
                        <div class="wpfs-form-group">
                            <label for="<?php $view->reCaptchaSecretKey()->id(); ?>" class="wpfs-form-label"><?php $view->reCaptchaSecretKey()->label(); ?></label>
                            <input id="<?php $view->reCaptchaSecretKey()->id(); ?>" name="<?php $view->reCaptchaSecretKey()->name(); ?>"  type="text" value="<?php echo esc_html( $securityData->recaptchaSecretKey ); ?>" class="wpfs-form-control">
                        </div>
                    </div>
                    <div class="wpfs-form-actions">
                        <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
                        <a href="<?php echo $backLinkUrl; ?>" class="wpfs-btn wpfs-btn-text"><?php esc_html_e( 'Cancel', 'wp-full-stripe-admin' ); ?></a>
                    </div>
                </div>
                <div class="wpfs-form__col">
                    <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                        <div class="wpfs-inline-message__inner">
                            <div class="wpfs-inline-message__title"><?php esc_html_e( 'How to protect forms with Google reCaptcha?', 'wp-full-stripe-admin' ); ?></div>
                            <p><?php esc_html_e( 'You can find more info about which version you should choose and how to obtain the API keys in our Knowledge base.', 'wp-full-stripe-admin' ); ?></p>
                            <p>
                                <a class="wpfs-btn wpfs-btn-link" href="https://support.paymentsplugin.com/article/18-registering-your-website-for-google-recaptcha" target="_blank"><?php esc_html_e( 'Learn more about Google reCAPTCHA', 'wp-full-stripe-admin' ); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div id="wpfs-success-message-container"></div>
    </div>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>

<script type="text/template" id="wpfs-success-message">
    <div class="wpfs-floating-message__inner">
        <div class="wpfs-floating-message__message"><%- successMessage %></div>
        <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
            <span class="wpfs-icon-close"></span>
        </button>
    </div>
</script>
