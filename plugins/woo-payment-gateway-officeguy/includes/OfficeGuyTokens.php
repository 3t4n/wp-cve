<?php
class OfficeGuyTokens
{
    public static function GetTokenRequest($Gateway)
    {
        $Request = array(
            'ParamJ' => 5,
            'Amount' => 1,
            'Credentials' => OfficeGuyPayment::GetCredentials($Gateway)
        );
        if ($Gateway->settings['pci'] == 'yes')
        {
            $Request["CardNumber"] = OfficeGuyRequestHelpers::Post('og-ccnum');
            $Request["CVV"] = OfficeGuyRequestHelpers::Post('og-cvv');
            $Request["CitizenID"] = OfficeGuyRequestHelpers::Post('og-citizenid');
            $Request["ExpirationMonth"] = (OfficeGuyRequestHelpers::Post('og-expmonth') < 10) ? '0' . OfficeGuyRequestHelpers::Post('og-expmonth') : OfficeGuyRequestHelpers::Post('og-expmonth');
            $Request["ExpirationYear"] = OfficeGuyRequestHelpers::Post('og-expyear');
        }
        else
            $Request["SingleUseToken"] = OfficeGuyRequestHelpers::Post('og-token');
        return $Request;
    }

    public static function GetTokenFromResponse($Gateway, $Response)
    {
        $ResponseData = $Response['Data'];
        $Token = new WC_Payment_Token_CC();
        $Token->set_token($ResponseData['CardToken']);
        $Token->set_gateway_id($Gateway->id);
        $Token->set_card_type('card'); // $Token->set_card_type(OfficeGuyTokens::GetCardBrand($ResponseData['Brand']));
        $Token->set_last4(substr($ResponseData['CardPattern'], -4));
        $Token->add_meta_data('og-citizenid', $ResponseData['CitizenID']);
        $Token->set_expiry_month($ResponseData['ExpirationMonth']);
        $Token->set_expiry_year($ResponseData['ExpirationYear']);
        $Token->set_user_id(get_current_user_id());
        return $Token;
    }

    public static function ProcessToken($Gateway)
    {
        $Request = OfficeGuyTokens::GetTokenRequest($Gateway);
        $Response = OfficeGuyAPI::Post($Request, '/creditguy/gateway/transaction/', $Gateway->settings['environment'], false);

        // Check response
        if ($Response['Status'] == 0 && $Response['Data']['Success'] == true)
        {
            $Token = OfficeGuyTokens::GetTokenFromResponse($Gateway, $Response);
            if ($Token->save())
            {
                // Return thank you redirect
                return array(
                    'result' => 'success',
                    'redirect' => wc_get_account_endpoint_url('payment-methods')
                );
            }
            else
                wc_add_notice(__('Update payment method failed', 'officeguy') . ' - ' . $Response['UserErrorMessage'], $notice_type = 'error');
        }
        else if ($Response['Status'] != 0)
        {
            // No response or unexpected response
            wc_add_notice(__('Update payment method failed', 'officeguy') . ' - ' . serialize($Gateway->settings['pci']) . ' ' . $Response['UserErrorMessage'], $notice_type = 'error');
        }
        else
        {
            // Decline
            wc_add_notice(__('Update payment method failed', 'officeguy') . ' - ' . $Response['Data']['ResultDescription'], $notice_type = 'error');
        }
    }

    public static function SaveTokenToOrder($Order, $Token)
    {
        $Order->add_payment_token($Token);
        $Order->save();
        OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' added payment token #' . $Token->get_id(), 'debug');

        if (function_exists('wcs_get_subscriptions_for_renewal_order') && function_exists('wcs_get_subscriptions_for_order'))
        {
            $Subscriptions = array_merge(
                wcs_get_subscriptions_for_renewal_order($Order->get_id()),
                wcs_get_subscriptions_for_order($Order->get_id())
            );
            foreach ($Subscriptions as $Subscription)
            {
                $Subscription->add_payment_token($Token);
                $Subscription->save();
                OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' added payment token to subscription #' . $Subscription->get_id(), 'debug');
            }
        }
    }

    public static function AddCreditCardTypeLabel($Array)
    {
        $Array['card'] = __('Credit card', 'officeguy');
        return $Array;
    }
}

add_filter('woocommerce_credit_card_type_labels', 'OfficeGuyTokens::AddCreditCardTypeLabel', 10, 1);
