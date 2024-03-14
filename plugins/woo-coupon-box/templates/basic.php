<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings = new VI_WOO_COUPON_BOX_DATA();

$wcb_show_coupon                 = $settings->get_params( 'wcb_show_coupon' );
$wcb_popup_type                  = $settings->get_params( 'wcb_popup_type' );
$wbs_title                       = $settings->get_params( 'wcb_title' );
$wcb_message                     = $settings->get_params( 'wcb_message' );
$wcb_follow_us                   = $settings->get_params( 'wcb_follow_us' );
$wcb_footer_text                 = $settings->get_params( 'wcb_footer_text' );
$wcb_footer_text_after_subscribe = $settings->get_params( 'wcb_footer_text_after_subscribe' );
$wcb_gdpr_checkbox               = $settings->get_params( 'wcb_gdpr_checkbox' );
$wcb_gdpr_checkbox_checked       = $settings->get_params( 'wcb_gdpr_checkbox_checked' );
$wcb_gdpr_message                = $settings->get_params( 'wcb_gdpr_message' );
$wcb_button_close                = $settings->get_params( 'wcb_button_close' );
$wcb_layout                      = $settings->get_params( 'wcb_layout' );

$wcb_recaptcha              = $settings->get_params( 'wcb_recaptcha' );
$wcb_no_thank_button_enable = $settings->get_params( 'wcb_no_thank_button_enable' );
$wcb_no_thank_button_title  = $settings->get_params( 'wcb_no_thank_button_title' );

?>
<div class="wcb-md-modal wcb-coupon-box wcb-coupon-box-<?php echo esc_attr( $wcb_layout ); ?> <?php echo esc_attr( $wcb_popup_type ); ?>" id="vi-md_wcb">
    <div class="wcb-content-wrap">
        <span class="wcb-md-close <?php echo esc_attr( $wcb_button_close ) ?>"> </span>
        <div class="wcb-md-content">
            <div class="wcb-modal-header">
                <span class="wcb-coupon-box-title"><?php echo esc_html($wbs_title); ?></span>
            </div>
            <div class="wcb-modal-body">
                <div class="wcb-coupon-message">
					<?php echo wp_kses_post($wcb_message); ?>
                </div>
                <div class="wcb-text-title wcb-text-follow-us"><?php echo wp_kses_post($wcb_follow_us); ?></div>
                <div class="wcb-sharing-container">
                    {socials}
                </div>
                <div class="wcb-coupon-content" style="<?php if ( ! $wcb_show_coupon ) {
					echo esc_attr( 'display:none;' );
				} ?>">
                </div>

                <div class="wcb-coupon-box-newsletter">
                    <div class="wcb-newsletter">
                        <div class="wcb-warning-message-wrap">
                            <span class="wcb-warning-message"></span>
                        </div>
                        <div class="wcb-newsletter-form">
                            <div class="wcb-input-group">
                                <input type="email"
                                       placeholder="<?php esc_html_e( 'Enter your email address(*)', 'woo-coupon-box' ) ?>"
                                       class="wcb-form-control wcb-email"
                                       name="wcb_email">

                                <div class="wcb-input-group-btn">
                                    <span class="wcb-btn wcb-btn-primary wcb-button"><?php echo esc_attr($settings->get_params( 'wcb_button_text' )) ?></span>
                                </div>
                            </div>
                        </div>
						<?php
						if ( $wcb_gdpr_checkbox ) {
							?>
                            <div class="wcb-gdpr-field">
                                <input type="checkbox" name="wcb_gdpr_checkbox"
                                       class="wcb-gdpr-checkbox" <?php if ( $wcb_gdpr_checkbox_checked ) {
									echo esc_attr( 'checked' );
								} ?>>
                                <span class="wcb-gdpr-message"><?php echo wp_kses_post($wcb_gdpr_message); ?></span>
                            </div>
							<?php
						}
						if ( $wcb_recaptcha ) {
							?>
                            <div class="wcb-recaptcha-field">
                                <div id="wcb-recaptcha" class="wcb-recaptcha"></div>
                                <input type="hidden" value="" id="wcb-g-validate-response">
                            </div>
							<?php
						}
						if ( $wcb_no_thank_button_enable ) {
							?>
                            <div class="wcb-md-close-never-reminder-field">
                            <div class="wcb-md-close-never-reminder">
								<?php echo esc_html($wcb_no_thank_button_title) ?>
                            </div>
                            </div>
							<?php
						}
						?>
                        <div class="wcb-footer-text"><?php echo wp_kses_post($wcb_footer_text); ?></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="wcb-md-overlay"></div>
