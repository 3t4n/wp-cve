<?php

/**
 * Report abuse.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( is_user_logged_in() ) : ?>                   
    <button type="button" class="acadp-button acadp-button-secondary acadp-button-report acadp-button-modal acadp-py-2" data-target="#acadp-modal-report">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
        </svg>
        <?php esc_html_e( 'Report', 'advanced-classifieds-and-directory-pro' ); ?>
    </button>

    <!-- Modal -->
    <div id="acadp-modal-report" class="acadp-modal">
        <!-- Dialog -->
        <div class="acadp-modal-dialog">
            <form id="acadp-report-abuse-form" class="acadp-modal-content" role="form" data-js-enabled="false">
                <!-- Header -->
                <div class="acadp-modal-header">
                    <div class="acadp-text-xl">
                        <?php esc_html_e( 'Report abuse', 'advanced-classifieds-and-directory-pro' ); ?>
                    </div>

                    <button type="button" class="acadp-button acadp-button-close acadp-border-0 acadp-p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="acadp-modal-body acadp-flex acadp-flex-col acadp-gap-4">  
                    <div class="acadp-form-group">
                        <label for="acadp-report-abuse-form-control-message" class="acadp-form-label">
                            <?php esc_html_e( 'Your Complaint', 'advanced-classifieds-and-directory-pro' ); ?>
                            <span class="acadp-form-required" aria-hidden="true">*</span>
                        </label>

                        <textarea name="message" id="acadp-report-abuse-form-control-message" class="acadp-form-control acadp-form-textarea acadp-form-validate" rows="5" placeholder="<?php esc_attr_e( 'Message', 'advanced-classifieds-and-directory-pro' ); ?>..." required aria-describedby="acadp-report-abuse-form-error-message"></textarea>

                        <div hidden id="acadp-report-abuse-form-error-message" class="acadp-form-error"></div>
                    </div>

                    <!-- Hook for developers to add new fields -->
                    <?php do_action( 'acadp_report_abuse_form_fields' ); ?>

                    <div class="acadp-recaptcha">
                        <div id="acadp-report-abuse-form-control-recaptcha"></div>
                        <div hidden id="acadp-report-abuse-form-error-recaptcha" class="acadp-form-error"></div>
                    </div>                    
                </div>

                <!-- Footer -->
                <div class="acadp-modal-footer">
                    <div class="acadp-form-status acadp-me-auto"></div>

                    <button type="submit" class="acadp-button acadp-button-primary acadp-button-submit acadp-py-2">
                        <?php esc_html_e( 'Send Report', 'advanced-classifieds-and-directory-pro' ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php else : ?>
    <button type="button" class="acadp-button acadp-button-secondary acadp-button-require-login acadp-py-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
        </svg>
        <?php esc_html_e( 'Report', 'advanced-classifieds-and-directory-pro' ); ?>
    </button>
<?php endif;