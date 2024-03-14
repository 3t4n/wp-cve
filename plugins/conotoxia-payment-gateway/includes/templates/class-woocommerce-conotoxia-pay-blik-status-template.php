<?php

class WC_Gateway_Conotoxia_Pay_Blik_Status_Template
{
    /**
     * @param string $payment_id
     * @param string $email
     * @return string
     */
    public static function get(string $payment_id, string $email): string
    {
        $payment_id = esc_html($payment_id);
        $email = esc_html($email);

        $conotoxia_pay_logo_url = esc_url(plugins_url('images/conotoxia_pay_logo_raw.svg', dirname(__FILE__, 2)));
        $blik_logo_url = esc_url(plugins_url('images/blik.svg', dirname(__FILE__, 2)));
        $waiting_icon_url = esc_url(plugins_url('images/waiting_icon.svg', dirname(__FILE__, 2)));
        $success_icon_url = esc_url(plugins_url('images/success_icon.svg', dirname(__FILE__, 2)));
        $error_icon_url = esc_url(plugins_url('images/error_icon.svg', dirname(__FILE__, 2)));
        $continue_shopping_url = esc_url(wc_get_page_permalink('shop'));

        $confirm_blik_payment_in_your_banking_app = esc_html(
            __('Confirm your BLIK payment in your banking app', CONOTOXIA_PAY)
        );
        $thank_you_for_your_payment = esc_html(__('Thank you for your payment', CONOTOXIA_PAY));
        $payment_failed = esc_html(__('Payment failed', CONOTOXIA_PAY));
        $reason = esc_html(__('Reason:', CONOTOXIA_PAY));
        $payment_confirmation_time_expired = esc_html(__('Payment confirmation time expired.', CONOTOXIA_PAY));
        $payment_number = esc_html(__('Payment number:', CONOTOXIA_PAY));
        $return_to_the_shop_to_renew_your_payment = esc_html(
            __('Return to the shop to renew your payment.', CONOTOXIA_PAY)
        );
        $if_you_experience_any_further_problems_please_contact_the_shop_support = esc_html(
            __('If you experience any further problems, please contact the shop support.', CONOTOXIA_PAY)
        );
        $we_sent_your_payment_confirmation_by_email_to = esc_html(
            __('We sent your payment confirmation by email to', CONOTOXIA_PAY)
        );
        $notification_of_the_payment_status_will_be_sent_to_your_email_address = esc_html(
            __(
                'A notification about your payment status will be sent to your email address:',
                CONOTOXIA_PAY
            )
        );
        $please_contact_the_shop_to_determine_the_reason = esc_html(
            __('Please contact the shop to determine the reason.', CONOTOXIA_PAY)
        );
        $payment_of_this_shop_is_processed_by_conotoxia = esc_html(
            __('Payment for this store is executed by', CONOTOXIA_PAY)
        ).' Conotoxia Sp. z o.o.';
        $continue_shopping = esc_html(__('Continue shopping', CONOTOXIA_PAY));

        return <<<HTML
            <div id='cx-blik-status-container'>
                <div id='cx-blik-status-header'>
                    <img id='cx-blik-status-cx-pay-logo'
                         src='$conotoxia_pay_logo_url'
                         alt='Conotoxia Pay logo'>
                    <img id='cx-blik-status-blik-logo'
                         src='$blik_logo_url'
                         alt='BLIK logo'>
                </div>
                
                <div class='cx-blik-status-icon js-cx-blik-status-waiting-element'>
                <img class='cx-blik-status-icon js-cx-blik-status-waiting-element'
                     src='$waiting_icon_url'
                     alt='Waiting icon'>
                </div>
                
                <div class='cx-blik-status-primary-text js-cx-blik-status-waiting-element'>
                    $confirm_blik_payment_in_your_banking_app
                </div>
                <div class='cx-blik-status-additional-info-row js-cx-blik-status-waiting-element'>
                    <div class='cx-blik-status-additional-info-key'>
                        $payment_number
                    </div>
                    <div class='cx-blik-status-additional-info-value'>
                        $payment_id
                    </div>
                </div>
                <div id='cx-blik-status-loader'
                     class='js-cx-blik-status-waiting-element'>
                </div>
                <div id='cx-blik-status-disclaimer'
                     class='js-cx-blik-status-waiting-element'>
                    $payment_of_this_shop_is_processed_by_conotoxia
                </div>

                <div class='cx-blik-status-icon js-cx-blik-status-success-element' style='display: none'>
                <img class='cx-blik-status-icon js-cx-blik-status-success-element'
                     src='$success_icon_url'
                     alt='Success icon'
                     style='display: none'>
                </div>
                
                <div class='cx-blik-status-primary-text js-cx-blik-status-success-element' style='display: none'
                     style='display: none'>
                    $thank_you_for_your_payment
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-success-element'
                     style='display: none'>
                    $we_sent_your_payment_confirmation_by_email_to <b>$email</b>
                </div>
                <div class='js-cx-blik-status-success-element'
                     style='display: none'>
                    <a href='$continue_shopping_url'>
                        <button class='cx-blik-status-button'>$continue_shopping</button>
                    </a>
                </div>

                <div class='cx-blik-status-icon js-cx-blik-status-time-exceeded-element' style='display: none'>
                <img class='cx-blik-status-icon js-cx-blik-status-time-exceeded-element'
                     src='$error_icon_url'
                     alt='Error icon'
                     style='display: none'>
                </div>
                
                <div class='cx-blik-status-primary-text js-cx-blik-status-time-exceeded-element'
                     style='display: none'>
                    $payment_failed
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-time-exceeded-element'
                     style='display: none'>
                    <div class='cx-blik-status-additional-info'>
                        <div class='cx-blik-status-additional-info-row'>
                            <div class='cx-blik-status-additional-info-key'>
                                $reason
                            </div>
                            <div class='cx-blik-status-additional-info-value'>
                                $payment_confirmation_time_expired
                            </div>
                        </div>
                        <div class='cx-blik-status-additional-info-row'>
                            <div class='cx-blik-status-additional-info-key'>
                                $payment_number
                            </div>
                            <div class='cx-blik-status-additional-info-value'>
                                $payment_id
                            </div>
                        </div>
                    </div>
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-time-exceeded-element'
                     style='display: none'>
                    <div>
                        $return_to_the_shop_to_renew_your_payment
                    </div>
                    <div>
                        $if_you_experience_any_further_problems_please_contact_the_shop_support
                    </div>
                </div>
                <div class='js-cx-blik-status-time-exceeded-element'
                     style='display: none'>
                    <a href='$continue_shopping_url'>
                        <button class='cx-blik-status-button'>$continue_shopping</button>
                    </a>
                </div>

                <div class='cx-blik-status-icon js-cx-blik-status-error-element' style='display: none'>
                <img class='cx-blik-status-icon js-cx-blik-status-error-element'
                     src='$error_icon_url'
                     alt='Error icon'
                     style='display: none'>
                </div>
                
                <div class='cx-blik-status-primary-text js-cx-blik-status-error-element'
                     style='display: none'>
                    $payment_failed
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-error-element'
                     style='display: none'>
                    <div class='cx-blik-status-additional-info'>
                        <div class='cx-blik-status-additional-info-row'>
                            <div class='cx-blik-status-additional-info-key'>
                                $payment_number
                            </div>
                            <div class='cx-blik-status-additional-info-value'>
                                $payment_id
                            </div>
                        </div>
                    </div>
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-error-element'
                     style='display: none'>
                    $please_contact_the_shop_to_determine_the_reason
                </div>
                <div class='js-cx-blik-status-error-element'
                     style='display: none'>
                    <a href='$continue_shopping_url'>
                        <button class='cx-blik-status-button'>$continue_shopping</button>
                    </a>
                </div>

                <div class='cx-blik-status-icon js-cx-blik-status-problem-element' style='display: none'>
                <img class='cx-blik-status-icon js-cx-blik-status-problem-element'
                     src='$waiting_icon_url'
                     alt='Warning icon'
                     style='display: none'>
                </div>
                
                <div class='cx-blik-status-primary-text js-cx-blik-status-problem-element'
                     style='display: none'>
                    $confirm_blik_payment_in_your_banking_app
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-problem-element'
                     style='display: none'>
                    <div class='cx-blik-status-additional-info'>
                        <div class='cx-blik-status-additional-info-row'>
                            <div class='cx-blik-status-additional-info-key'>
                                $payment_number
                            </div>
                            <div class='cx-blik-status-additional-info-value'>
                                $payment_id
                            </div>
                        </div>
                    </div>
                </div>
                <div class='cx-blik-status-text js-cx-blik-status-problem-element'
                     style='display: none'>
                    $notification_of_the_payment_status_will_be_sent_to_your_email_address <b>$email</b>
                </div>
                <div class='js-cx-blik-status-problem-element'
                     style='display: none'>
                    <a href='$continue_shopping_url'>
                        <button class='cx-blik-status-button'>$continue_shopping</button>
                    </a>
                </div>
            </div>
HTML;
    }
}