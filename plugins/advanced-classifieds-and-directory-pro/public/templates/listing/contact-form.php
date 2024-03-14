<?php

/**
 * Contact Form.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp-contact-form acadp-flex acadp-flex-col acadp-gap-3">
    <?php if ( ! empty( $general_settings['contact_form_require_login'] ) && ! is_user_logged_in() ) : ?> 
        <div class="acadp-text-muted">
            <?php 
            if ( 'acadp' == $registration_settings['engine'] ) {
                echo sprintf( 
                    __( 'Please, <a href="%s">login</a> to contact this listing owner.', 'advanced-classifieds-and-directory-pro' ), 
                    esc_url( $login_url ) 
                );
            } else {
                esc_html_e( 'Please, login to contact this listing owner.', 'advanced-classifieds-and-directory-pro' );
            }
            ?>
        </p>
    <?php else :
        $current_user = wp_get_current_user();
        ?>
        <form id="acadp-contact-form" class="acadp-form acadp-flex acadp-flex-col acadp-gap-4" role="form" data-js-enabled="false">
            <div class="acadp-form-group">
                <label for="acadp-contact-form-control-name" class="acadp-form-label">
                    <?php esc_html_e( 'Your Name', 'advanced-classifieds-and-directory-pro' ); ?>
                    <span class="acadp-form-required" aria-hidden="true">*</span>
                </label>

                <input type="text" name="name" id="acadp-contact-form-control-name" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php echo esc_attr( $current_user->display_name ); ?>" placeholder="<?php esc_attr_e( 'Name', 'advanced-classifieds-and-directory-pro' ); ?>" required aria-describedby="acadp-contact-form-error-name" />

                <div hidden id="acadp-contact-form-error-name" class="acadp-form-error"></div>
            </div>
            
            <div class="acadp-form-group">
                <label for="acadp-contact-form-control-email" class="acadp-form-label">
                    <?php esc_html_e( 'Your E-mail Address', 'advanced-classifieds-and-directory-pro' ); ?>
                    <span class="acadp-form-required" aria-hidden="true">*</span>
                </label>

                <input type="email" name="email" id="acadp-contact-form-control-email" class="acadp-form-control acadp-form-input acadp-form-validate" value="<?php echo esc_attr( $current_user->user_email ); ?>" placeholder="<?php esc_attr_e( 'Email', 'advanced-classifieds-and-directory-pro' ); ?>" required aria-describedby="acadp-contact-form-error-email" />

                <div hidden id="acadp-contact-form-error-email" class="acadp-form-error"></div>
            </div>
            
            <div class="acadp-form-group">
                <label for="acadp-contact-form-control-phone" class="acadp-form-label">
                    <?php esc_html_e( 'Your Phone Number', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>

                <input type="tel" name="phone" id="acadp-contact-form-control-phone" class="acadp-form-control acadp-form-input" placeholder="<?php esc_attr_e( 'Phone', 'advanced-classifieds-and-directory-pro' ); ?>" aria-describedby="acadp-contact-form-error-phone" />

                <div hidden id="acadp-contact-form-error-phone" class="acadp-form-error"></div>
            </div>
            
            <div class="acadp-form-group">
                <label for="acadp-contact-form-control-message" class="acadp-form-label">
                    <?php esc_html_e( 'Your Message', 'advanced-classifieds-and-directory-pro' ); ?>
                    <span class="acadp-form-required" aria-hidden="true">*</span>
                </label>

                <textarea name="message" id="acadp-contact-form-control-message" class="acadp-form-control acadp-form-textarea acadp-form-validate" rows="3" placeholder="<?php esc_attr_e( 'Message', 'advanced-classifieds-and-directory-pro' ); ?>..." required aria-describedby="acadp-contact-form-error-message"></textarea>

                <div hidden id="acadp-contact-form-error-message" class="acadp-form-error"></div>
            </div>

            <!-- Hook for developers to add new fields -->
            <?php do_action( 'acadp_contact_form_fields' ); ?>
            
            <?php if ( isset( $general_settings['contact_form_send_copy'] ) && ! empty( $general_settings['contact_form_send_copy'] ) ) : ?>
                <label class="acadp-flex acadp-gap-1.5 acadp-items-center">
                    <input type="checkbox" id="acadp-contact-form-control-send_copy" class="acadp-form-control acadp-form-checkbox" value="1" />
                    <?php esc_html_e( 'Send a copy to myself?', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>
            <?php endif; ?>
            
            <div class="acadp-recaptcha">
                <div id="acadp-contact-form-control-recaptcha"></div>
                <div hidden id="acadp-contact-form-error-recaptcha" class="acadp-form-error"></div>
            </div>

            <div class="acadp-form-status acadp-hide-if-empty"></div>

            <button type="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-self-start">
                <?php esc_html_e( 'Send Message', 'advanced-classifieds-and-directory-pro' ); ?>
            </button>
        </form> 
    <?php endif; ?>
</div>