<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings = new VI_WOO_COUPON_BOX_DATA();

$wcb_view_mode                   = $settings->get_params( 'wcb_view_mode' );
$wcb_show_coupon                 = $settings->get_params( 'wcb_show_coupon' );
$wcb_popup_type                  = $settings->get_params( 'wcb_popup_type' );
$wbs_title                       = $settings->get_params( 'wcb_title' );
$wcb_message                     = $settings->get_params( 'wcb_message' );
$wcb_message_after_subscribe     = $settings->get_params( 'wcb_message_after_subscribe' );
$wcb_follow_us                   = $settings->get_params( 'wcb_follow_us' );
$wcb_footer_text                 = $settings->get_params( 'wcb_footer_text' );
$wcb_footer_text_after_subscribe = $settings->get_params( 'wcb_footer_text_after_subscribe' );
$wcb_gdpr_checkbox               = $settings->get_params( 'wcb_gdpr_checkbox' );
$wcb_gdpr_message                = $settings->get_params( 'wcb_gdpr_message' );
$wcb_button_close                = $settings->get_params( 'wcb_button_close' );
$wbs_title                       = $settings->get_params( 'wcb_title' );
$wcb_gdpr_checkbox_checked       = $settings->get_params( 'wcb_gdpr_checkbox_checked' );
$wcb_title_after_subscribing     = $settings->get_params( 'wcb_title_after_subscribing' );
$wcb_recaptcha                   = $settings->get_params( 'wcb_recaptcha' );
$wcb_no_thank_button_enable      = $settings->get_params( 'wcb_no_thank_button_enable' );
$wcb_no_thank_button_title       = $settings->get_params( 'wcb_no_thank_button_title' );
?>
<!--template 1-->
<div class="wcb-md-modal wcb-coupon-box wcb-coupon-box-1 wcb-current-layout <?php echo esc_attr( $wcb_popup_type ); ?>"
     id="vi-md_wcb">
    <div class="wcb-content-wrap">
        <span class="wcb-md-close <?php echo esc_attr( $wcb_button_close ) ?>"> </span>
        <div class="wcb-md-content">
            <div class="wcb-modal-header wcb-view-before-subscribe" style="<?php echo $wcb_view_mode == 2 ? 'display:none;' : ''; ?>">
                <span class="wcb-coupon-box-title"><?php echo esc_html( $wbs_title ); ?></span>
            </div>
            <div class="wcb-modal-header wcb-view-after-subscribe" style="<?php echo $wcb_view_mode == 1 ? 'display:none;' : ''; ?>">
                <span class="wcb-coupon-box-title"><?php echo wp_kses_post( $wcb_title_after_subscribing ); ?></span>
            </div>
            <div class="wcb-modal-body">
                <div class="wcb-coupon-message wcb-view-before-subscribe wcb-coupon-message-before-subscribe"
                     style="<?php if ( $wcb_view_mode == 2 )
					     echo esc_attr( 'display:none;' ) ?>">
					<?php echo wp_kses_post( $wcb_message ); ?>
                </div>
                <div class="wcb-coupon-message wcb-view-after-subscribe wcb-coupon-message-after-subscribe"
                     style="<?php if ( $wcb_view_mode == 1 )
					     echo esc_attr( 'display:none;' ) ?>">
					<?php echo wp_kses_post( $wcb_message_after_subscribe ); ?>
                </div>
                <div class="wcb-text-title wcb-text-follow-us"><?php echo wp_kses_post( $wcb_follow_us ); ?></div>
                <div class="wcb-sharing-container">
                    {socials}
                </div>
                <div class="wcb-coupon-content" style="<?php if ( ! $wcb_show_coupon ) {
					echo esc_attr( 'display:none;' );
				} ?>">
                    <div class="wcb-coupon-treasure-container wcb-view-after-subscribe"
                         style="<?php if ( $wcb_view_mode == 1 )
						     echo esc_attr( 'display:none;' ) ?>">
                        <input type="text" readonly="" value="{coupon}" class="wcb-coupon-treasure"/>
                    </div>
                    <span class="wcb-guide wcb-view-after-subscribe" style="<?php if ( $wcb_view_mode == 1 )
						echo esc_attr( 'display:none;' ) ?>">
                            <?php esc_html_e( 'Enter this promo code at checkout page.', 'woo-coupon-box' ) ?>
                        </span>
                </div>

                <div class="wcb-coupon-box-newsletter">
                    <div class="wcb-newsletter-success wcb-view-after-subscribe"
                         style="<?php if ( $wcb_view_mode == 1 )
						     echo esc_attr( 'display:none;' ) ?>">
                        <div class="wcb-footer-text-after-subscribe"><?php echo wp_kses_post( $wcb_footer_text_after_subscribe ); ?></div>
                    </div>

                    <div class="wcb-newsletter wcb-view-before-subscribe" style="<?php if ( $wcb_view_mode == 2 )
						echo esc_attr( 'display:none;' ) ?>">
                        <div class="wcb-warning-message"></div>
                        <div class="wcb-newsletter-form">
                            <div class="wcb-input-group">
                                <input type="email"
                                       placeholder="<?php esc_html_e( '*Enter your email address', 'woo-coupon-box' ) ?>"
                                       class="wcb-form-control wcb-email"
                                       name="wcb_email">

                                <div class="wcb-input-group-btn">
                                    <span class="wcb-btn wcb-btn-primary wcb-button"><?php echo esc_html( $settings->get_params( 'wcb_button_text' ) ) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="wcb-gdpr-field" style="<?php if ( ! $wcb_gdpr_checkbox ) {
							echo esc_attr( 'display:none;' );
						} ?>">
                            <input type="checkbox" name="wcb_gdpr_checkbox"
                                   class="wcb-gdpr-checkbox" <?php if ( $wcb_gdpr_checkbox_checked ) {
								echo esc_attr( 'checked' );
							} ?>>
                            <span class="wcb-gdpr-message"><?php echo wp_kses_post( $wcb_gdpr_message ); ?></span>
                        </div>
                        <div class="wcb-recaptcha-field" style="<?php echo $wcb_recaptcha ? '' : 'display:none;'; ?>">
                            <div id="wcb-recaptcha" class="wcb-recaptcha"></div>
                            <input type="hidden" value="" id="wcb-g-validate-response">
                        </div>
                        <div class="wcb-md-close-never-reminder-field" style="<?php echo $wcb_no_thank_button_enable ? '' : 'display:none;'; ?>">
                            <div class="wcb-md-close-never-reminder">
								<?php echo esc_html( $wcb_no_thank_button_title ) ?>
                            </div>
                        </div>
                        <div class="wcb-footer-text"><?php echo wp_kses_post( $wcb_footer_text ); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wcb-md-overlay"></div>
