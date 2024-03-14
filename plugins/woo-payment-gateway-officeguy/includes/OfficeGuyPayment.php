<?php
class OfficeGuyPayment
{
    private static function GetOrderRequest($Gateway, $Order, $ItemMethods, $PaymentsCount, $IsSubscriptionPayment)
    {
        $Request = array();
        $Request['Credentials'] = OfficeGuyPayment::GetCredentials($Gateway);
        $Request['Items'] = OfficeGuyPayment::GetPaymentOrderItems($Order);
        $Request['VATIncluded'] = 'true';
        $Request['VATRate'] = OfficeGuyPayment::GetOrderVatRate($Order);
        $Request['Customer'] = OfficeGuyPayment::GetOrderCustomer($Gateway, $Order);
        $Request['AuthoriseOnly'] = $Gateway->settings['testing'] != 'no' ? 'true' : 'false';

        if ($Gateway->settings['authorizeonly'] == 'yes')
        {
            $Request['AutoCapture'] = 'false';

            $OrderAmount = $Order->get_total();
            $AuthorizeAmount = $OrderAmount;
            if (!empty($Gateway->settings['authorizeaddedpercent']))
                $AuthorizeAmount = round($AuthorizeAmount * (1 + $Gateway->settings['authorizeaddedpercent'] / 100));
            if (!empty($Gateway->settings['authorizeminimumaddition']) && $AuthorizeAmount - $OrderAmount < $Gateway->settings['authorizeminimumaddition'])
                $AuthorizeAmount = round($OrderAmount + $Gateway->settings['authorizeminimumaddition']);

            $Request['AuthorizeAmount'] = round($AuthorizeAmount, 2);
        }
        $Request['DraftDocument'] = $Gateway->settings['draftdocument'] != 'no' ? 'true' : 'false';
        $Request['SendDocumentByEmail'] = $Gateway->settings['emaildocument'] == 'yes' && !in_array('subscription', $ItemMethods) ? 'true' : 'false';
        $Request['UpdateCustomerByEmail'] = $Gateway->settings['emaildocument'] == 'yes' && in_array('subscription', $ItemMethods) ? 'true' : 'false';
        if ($Gateway->settings['emaildocument'] == 'yes') // BeginRedirect
            $Request['SendUpdateByEmailAddress'] = $Order->get_billing_email();
        $Request['DocumentDescription'] = __('Order number', 'officeguy') . ': ' . $Order->get_id() . (empty($Order->get_customer_note()) ? '' : "\r\n" . $Order->get_customer_note());
        $Request['Payments_Count'] = $PaymentsCount;
        $Request['MaximumPayments'] = OfficeGuyPayment::GetMaximumPayments($Gateway, round($Order->get_total()));
        $Request['DocumentLanguage'] = OfficeGuyPayment::GetOrderLanguage($Gateway);
        if (OfficeGuyDonation::OrderContainsDonation($Order))
            $Request['DocumentType'] = 'DonationReceipt';
        if ($IsSubscriptionPayment)
            $Request['MerchantNumber'] = $Gateway->settings['subscriptionsmerchantnumber'];
        else
            $Request['MerchantNumber'] = $Gateway->settings['merchantnumber'];
        
        $Token = null;
        if (!$IsSubscriptionPayment && $Gateway->settings['pci'] == 'redirect') {
            if (strpos(home_url(), '?') !== false)
                $Request['RedirectURL'] = untrailingslashit(home_url()) . '&wc-api=WC_OfficeGuy&OG-OrderID=' . $Order->get_id();
            else
                $Request['RedirectURL'] = untrailingslashit(home_url()) . '?wc-api=WC_OfficeGuy&OG-OrderID=' . $Order->get_id();
        }
        else
        {
            if ($IsSubscriptionPayment)
            {
                $Subscriptions = array_merge(
                    wcs_get_subscriptions_for_renewal_order($Order->get_id()),
                    wcs_get_subscriptions_for_order($Order->get_id())
                );
                if (!empty($Subscriptions) && count($Subscriptions) > 0)
                {
                    OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' payment subscription ' . $Subscriptions[0]->get_id(), 'debug');
                    $PaymentTokens = $Subscriptions[0]->get_payment_tokens();
                    if (count($PaymentTokens) > 0)
                    {
                        OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' payment subscription token #' . $PaymentTokens[count($PaymentTokens) - 1], 'debug');
                        $Token = WC_Payment_Tokens::get($PaymentTokens[count($PaymentTokens) - 1]);
                        //$TokenID = get_post_meta($Subscriptions[0]->get_id(), '_og_token', true);
                    }
                    else
                        OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' no subscription token', 'debug');
                }
                else
                    OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' no subscriptions', 'debug');
                if ($Token == null)
                {
                    OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' using default customer token', 'debug');
                    $Token = WC_Payment_Tokens::get_customer_default_token($Order->get_customer_id());
                }

                //if ($PaymentTokens == null || count($PaymentTokens) == 0) // Get subscriptions payment token
                //$PaymentTokens = wc_get_order($Order->get_parent_id())->get_payment_tokens();
                //$Token = WC_Payment_Tokens::get($PaymentTokens[0]);

                //$Token = WC_Payment_Tokens::get_customer_default_token($Order->get_customer_id());
                $Request["PaymentMethod"] = OfficeGuyPayment::GetOrderPaymentMethodFromToken($Token);
            }
            else
            {
                $TokenID = OfficeGuyRequestHelpers::Post('wc-' . $Gateway->id . '-payment-token');
                if ($TokenID && $TokenID !== 'new')
                {
                    $TokenID = wc_clean($TokenID);
                    $Token = WC_Payment_Tokens::get($TokenID);
                }
                if ($Token != null)
                {
                    if ($Token->get_user_id() !== get_current_user_id())
                        return;

                    $Request["PaymentMethod"] = OfficeGuyPayment::GetOrderPaymentMethodFromToken($Token);
                }
                else
                {
                    if ($Gateway->settings['pci'] == 'yes')
                        $Request["PaymentMethod"] = OfficeGuyPayment::GetOrderPaymentMethodPCI();
                    else
                        $Request["SingleUseToken"] = OfficeGuyRequestHelpers::Post('og-token');
                }
            }
        }
        return array($Request, $Token);
    }

    public static function ValidateOrderFields($Gateway)
    {
        // Check for saving payment info without having or creating an account
        if (OfficeGuyRequestHelpers::Post('saveinfo') && !is_user_logged_in() && !OfficeGuyRequestHelpers::Post('createaccount'))
        {
            wc_add_notice(__('Sorry, you need to create an account in order for us to save your payment information.', 'officeguy'), $notice_type = 'error');
            return false;
        }

        if (OfficeGuyRequestHelpers::Post('wc-' . $Gateway->id . '-payment-token') && OfficeGuyRequestHelpers::Post('wc-' . $Gateway->id . '-payment-token') !== 'new')
        {
        }
        else
        {
            if ($Gateway->settings['pci'] == 'no')
            {
                $CardToken = OfficeGuyRequestHelpers::Post('og-token');
                if (empty($CardToken))
                {
                    wc_add_notice(__('Card number is invalid.', 'officeguy'), $notice_type = 'error');
                    return false;
                }
            }
            elseif ($Gateway->settings['pci'] == 'yes')
            {
                $CardNumber = OfficeGuyRequestHelpers::Post('og-ccnum');
                $CVV = OfficeGuyRequestHelpers::Post('og-cvv');
                $ExpirationMonth = OfficeGuyRequestHelpers::Post('og-expmonth');
                $ExpirationYear = OfficeGuyRequestHelpers::Post('og-expyear');

                // Check card number
                if (empty($CardNumber) || !ctype_digit($CardNumber))
                {
                    wc_add_notice(__('Card number is invalid.', 'officeguy'), $notice_type = 'error');
                    return false;
                }

                if ($Gateway->settings['cvv'] == 'yes' && !ctype_digit($CVV))
                { // Check security code
                    wc_add_notice(__('Card security code is invalid (only digits are allowed).', 'officeguy'), $notice_type = 'error');
                    return false;
                }

                // Check expiration data
                $CurrentYear = date('Y');

                if (
                    !ctype_digit($ExpirationMonth)
                    || !ctype_digit($ExpirationYear)
                    || $ExpirationMonth > 12
                    || $ExpirationMonth < 1
                    || $ExpirationYear < $CurrentYear
                    || $ExpirationYear > $CurrentYear + 20
                )
                {
                    wc_add_notice(__('Card expiration date is invalid.', 'officeguy'), $notice_type = 'error');
                    return false;
                }
            }
        }

        return true;
    }

    public static function CreateOrderDocument($Gateway, $Order, $Customer, $OriginalDocumentID)
    {
        $Request = array();
        $Request['Credentials'] = OfficeGuyPayment::GetCredentials($Gateway);
        $Request['Items'] = OfficeGuyPayment::GetDocumentOrderItems($Order);
        $Request['VATIncluded'] = 'true';
        $Request['VATRate'] = OfficeGuyPayment::GetOrderVatRate($Order);
        $Request['Details'] = array(
            'Customer' => $Customer,
            'IsDraft' => $Gateway->settings['draftdocument'] != 'no' ? 'true' : 'false',
            'Language' => OfficeGuyPayment::GetOrderLanguage($Gateway),
            'Currency' => $Order->get_currency(),
            'Type' => '8',
            'Description' => __('Order number', 'officeguy') . ': ' . $Order->get_id() . (empty($Order->get_customer_note()) ? '' : "\r\n" . $Order->get_customer_note())
        );
        $Request['OriginalDocumentID'] = $OriginalDocumentID;
        $Response = OfficeGuyAPI::Post($Request, '/accounting/documents/create/', $Gateway->settings['environment'], false);
        if ($Response['Status'] == 0)
        {
            // Success
            $Remark = __('SUMIT order completed. Document ID: %s.', 'officeguy');
            $Remark = sprintf($Remark, $Response['Data']['DocumentID']);
            $Order->add_order_note($Remark);
            $Order->add_meta_data('OfficeGuyOrderDocumentID', $Response['Data']['DocumentID']);
            $Order->save_meta_data();
            $Order->save();
            return null;
        }
        else
        {
            $Remark = __('Order creation failed.', 'officeguy') . ' - ' . $Response['UserErrorMessage'];
            $Order->add_order_note($Remark);
            return $Remark;
        }
    }

    public static function ProcessOrder($Gateway, $Order, $IsWooCommerceSubscriptionPayment)
    {
        $ItemMethods = OfficeGuyPayment::GetOrderItemMethods($Order);
        if ($Order->get_total() == 0)
        {
            if (function_exists('wcs_is_subscription') && (wcs_is_subscription($Order->get_id()) || wcs_order_contains_subscription($Order->get_id())))
            {
                $TokenID = OfficeGuyRequestHelpers::Post('wc-' . $Gateway->id . '-payment-token');
                if ($TokenID && $TokenID !== 'new')
                {
                    $TokenID = wc_clean($TokenID);
                    $Token = WC_Payment_Tokens::get($TokenID);
                }
                else
                {
                    $Request = OfficeGuyTokens::GetTokenRequest($Gateway);
                    $Response = OfficeGuyAPI::Post($Request, '/creditguy/gateway/transaction/', $Gateway->settings['environment'], !$IsWooCommerceSubscriptionPayment);
                    if ($Response['Status'] == 0 && $Response['Data']['Success'] == true)
                    {
                        $Token = OfficeGuyTokens::GetTokenFromResponse($Gateway, $Response);
                        if (!$Token->save())
                        {
                            wc_add_notice(__('Update payment method failed', 'officeguy') . ' - ' . $Response['UserErrorMessage'], $notice_type = 'error');
                            return;
                        }
                    }
                    else if ($Response['Status'] != 0)
                    {
                        // No response or unexpected response
                        wc_add_notice(__('Update payment method failed', 'officeguy') . ' - ' . serialize($Gateway->settings['pci']) . ' ' . $Response['UserErrorMessage'], $notice_type = 'error');
                        return;
                    }
                    else
                    {
                        // Decline
                        wc_add_notice(__('Update payment method failed', 'officeguy') . ' - ' . $Response['Data']['ResultDescription'], $notice_type = 'error');
                        return;
                    }
                }
              
                OfficeGuyTokens::SaveTokenToOrder($Order, $Token);
                $Order->payment_complete();

                return array(
                    'result' => 'success',
                    'redirect' => $Gateway->get_return_url($Order),
                );
            }
            else
            {
                $Remark = OfficeGuyPayment::CreateOrderDocument($Gateway, $Order, OfficeGuyPayment::GetOrderCustomer($Gateway, $Order), null);
                if ($Remark == null)
                {
                    // Return thank you redirect
                    if ($IsWooCommerceSubscriptionPayment)
                        return true;

                    return array(
                        'result' => 'success',
                        'redirect' => $Gateway->get_return_url($Order),
                    );
                }
                else
                    wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $Remark, $notice_type = 'error');
                return;
            }
        }

        $PaymentsCount = '1';
        if (!$IsWooCommerceSubscriptionPayment)
        {
            $PaymentsCount = OfficeGuyRequestHelpers::Post('og-paymentscount');
            if ($PaymentsCount == '' || in_array('subscription', $ItemMethods))
                $PaymentsCount = '1';
            if (round($PaymentsCount) > 1)
            {
                $OrderValue = round($Order->get_total());
                $MaximumPayments = OfficeGuyPayment::GetMaximumPayments($Gateway, $OrderValue);
                if ($PaymentsCount > $MaximumPayments)
                {
                    $Order->add_order_note(__('Payment failed', 'officeguy') . ' - ' . 'Invalid payments count');
                    if ($IsWooCommerceSubscriptionPayment)
                        return false;
                    wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . 'Invalid payments count', $notice_type = 'error');
                    return;
                }
            }
        }

        $HasVendorInCart = OfficeGuyMultiVendor::HasVendorInCart();
        list($Request, $Token) = OfficeGuyPayment::GetOrderRequest($Gateway, $Order, $ItemMethods, $PaymentsCount, $IsWooCommerceSubscriptionPayment);
        do_action('og_payment_request_handle', $Order, $Request);

        $Response = '';
        if ($HasVendorInCart)
            $Response = OfficeGuyAPI::Post($Request, '/billing/payments/multivendorcharge/', $Gateway->settings['environment'], !$IsWooCommerceSubscriptionPayment);
        else if (in_array('subscription', $ItemMethods))
            $Response = OfficeGuyAPI::Post($Request, '/billing/recurring/charge/', $Gateway->settings['environment'], !$IsWooCommerceSubscriptionPayment);
        else if ($Gateway->settings['pci'] == 'redirect')
            $Response = OfficeGuyAPI::Post($Request, '/billing/payments/beginredirect/', $Gateway->settings['environment'], !$IsWooCommerceSubscriptionPayment);
        else
            $Response = OfficeGuyAPI::Post($Request, '/billing/payments/charge/', $Gateway->settings['environment'], !$IsWooCommerceSubscriptionPayment);

        if (!$IsWooCommerceSubscriptionPayment && $Gateway->settings['pci'] == 'redirect')
        {
            if (isset($Response['Data']['RedirectURL']))
            {
                return array(
                    'result' => 'success',
                    'redirect' => $Response['Data']['RedirectURL'],
                );
            }
            wc_add_notice(__('Something went wrong.', 'officeguy'), $notice_type = 'error');
        }
        // if method is not redirect
        else
        {
            // Check response
            if ($Response['Status'] == 0 && ($HasVendorInCart || $Response['Data']['Payment']['ValidPayment'] == true))
            {
                // Success
                if ($HasVendorInCart)
                {
                    foreach ($Response['Data']['Vendors'] as $ResponseVendor)
                    {
                        $ResponsePayment = $ResponseVendor['Payment'];
                        $ResponsePaymentMethod = $ResponsePayment['PaymentMethod'];
                        $Remark = __('SUMIT payment completed. Auth Number: %s. Last digits: %s. Payment ID: %s. Document ID: %s. Customer ID: %s.', 'officeguy');
                        $Remark = sprintf($Remark, $ResponsePayment['AuthNumber'], $ResponsePaymentMethod['CreditCard_LastDigits'], $ResponsePayment['ID'], $Response['Data']['DocumentID'], $Response['Data']['CustomerID']);
                        $Order->add_order_note($Remark);
                        $Order->add_meta_data('OfficeGuyDocumentID', $ResponseVendor['DocumentID']);
                        $Order->add_meta_data('OfficeGuyCustomerID', $ResponseVendor['CustomerID']);
                        $Order->payment_complete();
                        $Order->save_meta_data();
                        $Order->save();
                    }
                }
                else
                {
                    $ResponsePayment = $Response['Data']['Payment'];
                    $ResponsePaymentMethod = $ResponsePayment['PaymentMethod'];
                    $Remark = __('SUMIT payment completed. Auth Number: %s. Last digits: %s. Payment ID: %s. Document ID: %s. Customer ID: %s.', 'officeguy');
                    $Remark = sprintf($Remark, $ResponsePayment['AuthNumber'], $ResponsePaymentMethod['CreditCard_LastDigits'], $ResponsePayment['ID'], $Response['Data']['DocumentID'], $Response['Data']['CustomerID']);
                    $Order->add_order_note($Remark);
                    $Order->add_meta_data('OfficeGuyDocumentID', $Response['Data']['DocumentID']);
                    $Order->add_meta_data('OfficeGuyCustomerID', $Response['Data']['CustomerID']);
                    $Order->add_meta_data('OfficeGuyAuthNumber', $ResponsePayment['AuthNumber']);
                    $Order->add_meta_data('OfficeGuyTotalPaymentAmount', $ResponsePayment['Amount']);
                    $Order->add_meta_data('OfficeGuyFirstPaymentAmount', $ResponsePayment['FirstPaymentAmount']);
                    $Order->add_meta_data('OfficeGuyNonFirstPaymentAmount', $ResponsePayment['NonFirstPaymentAmount']);
                    $Order->add_meta_data('OfficeGuyLastDigits', $ResponsePaymentMethod['CreditCard_LastDigits']);
                    $Order->add_meta_data('OfficeGuyExpirationMonth', $ResponsePaymentMethod['CreditCard_ExpirationMonth']);
                    $Order->add_meta_data('OfficeGuyExpirationYear', $ResponsePaymentMethod['CreditCard_ExpirationYear']);
                    $Order->payment_complete();
                    $Order->save_meta_data();
                    $Order->save();
                }

                if (!$HasVendorInCart && empty($Token))
                {
                    $TokenID = OfficeGuyRequestHelpers::Post('wc-' . $Gateway->id . '-payment-token');
                    if ($TokenID && $TokenID !== 'new')
                    {
                        $TokenID = wc_clean($TokenID);
                        $Token = WC_Payment_Tokens::get($TokenID);
                        if (!empty($Token) && $Token->get_user_id() != get_current_user_id())
                            $Token = null;
                    }
                    else
                    {
                        $Token = new WC_Payment_Token_CC();
                        $Token->set_token($ResponsePaymentMethod['CreditCard_Token']);
                        $Token->set_gateway_id($Gateway->id);
                        $Token->set_card_type('card'); // missing $Gateway->get_card_brand($Response['Data']['Brand']));
                        $Token->set_last4($ResponsePaymentMethod['CreditCard_LastDigits']);
                        $Token->add_meta_data('og-citizenid', $ResponsePaymentMethod['CreditCard_CitizenID']);
                        $Token->set_expiry_month($ResponsePaymentMethod['CreditCard_ExpirationMonth']);
                        $Token->set_expiry_year($ResponsePaymentMethod['CreditCard_ExpirationYear']);

                        if (
                            OfficeGuyPayment::ForceTokenStorage($Gateway)
                            || ($Gateway->settings['support_tokens'] == 'yes' && OfficeGuyRequestHelpers::Post('wc-' . $Gateway->id . '-new-payment-method') == 'on')
                        )
                        {
                            $Token->set_user_id(get_current_user_id());
                            $Token->save(); // Fails when there's no user
                        }
                    }
                }

                if (!empty($Token) && $Token->get_id() != 0)
                    OfficeGuyTokens::SaveTokenToOrder($Order, $Token);

                if ($Gateway->settings['createorderdocument'] == 'yes' && !$HasVendorInCart)
                {
                    $OrderCustomer = array(
                        'ID' => $Response['Data']['CustomerID']
                    );
                    OfficeGuyPayment::CreateOrderDocument($Gateway, $Order, $OrderCustomer, $Response['Data']['DocumentID']);
                }

                if ($IsWooCommerceSubscriptionPayment)
                    return true;

                // Return thank you redirect
                return array(
                    'result' => 'success',
                    'redirect' => $Gateway->get_return_url($Order),
                );
            }
            else if ($Response['Status'] != 0)
            {
                // No response or unexpected response
                $Order->add_order_note(__('Payment failed', 'officeguy') . ' - ' . $Response['UserErrorMessage']);
                if ($IsWooCommerceSubscriptionPayment)
                {
                    $Order->update_status('failed');
                    $Order->save();
                    return false;
                }
                wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $Response['UserErrorMessage'], $notice_type = 'error');
            }
            else
            { // if ($Response['Data']['Payment']['ValidPayment'] == false)
                // Decline
                $Order->add_order_note(__('Payment failed', 'officeguy') . ' - ' . $Response['Data']['Payment']['StatusDescription']);
                if ($IsWooCommerceSubscriptionPayment)
                {
                    $Order->update_status('failed');
                    $Order->save();
                    return false;
                }
                wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $Response['Data']['Payment']['StatusDescription'], $notice_type = 'error');
            }
        }
    }

    public static function ForceTokenStorage($Gateway)
    {
        if ($Gateway->settings['support_tokens'] != 'yes')
            return false;
        if (OfficeGuySubscriptions::CartContainsWooCommerceSubscription())
            return true;
        return OfficeGuyCartFlow::PluginIsActive();
    }

    public static function ProcessBitOrder($Gateway, $Order)
    {
        global $woocommerce;

        $OfficeGuyGateway = GetOfficeGuyGateway();
        $ItemMethods = OfficeGuyPayment::GetOrderItemMethods($Order);
        if ($Order->get_total() == 0)
        {
            $Remark = OfficeGuyPayment::CreateOrderDocument($OfficeGuyGateway, $Order, OfficeGuyPayment::GetOrderCustomer($OfficeGuyGateway, $Order), null);
            if ($Remark == null)
            {
                // Return thank you redirect
                if ($IsWooCommerceSubscriptionPayment)
                    return true;

                return array(
                    'result' => 'success',
                    'redirect' => $Gateway->get_return_url($Order),
                );
            }
            else
                wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $Remark, $notice_type = 'error');
            return;
        }

        list($Request, $Token) = OfficeGuyPayment::GetOrderRequest($OfficeGuyGateway, $Order, $ItemMethods, 1, false);
        $Request['RedirectURL'] = $Gateway->get_return_url($Order);
        $Request['CancelRedirectURL'] = WC()->cart->get_checkout_url();
        $Request['AutomaticallyRedirectToProviderPaymentPage'] = 'UpayBit';
        $Request['IPNURL'] = $woocommerce->api_request_url('officeguybit_woocommerce_gateway') . '?orderid=' . $Order->get_id() . '&orderkey=' . $Order->get_order_key();
        do_action('og_payment_request_handle', $Order, $Request);

        $Response = OfficeGuyAPI::Post($Request, '/billing/payments/beginredirect/', $OfficeGuyGateway->settings['environment'], true);
        if ($Response['Status'] == 0 && isset($Response['Data']['RedirectURL']))
        {
            return array(
                'result' => 'success',
                'redirect' => $Response['Data']['RedirectURL'],
            );
        }
        else if ($Response['Status'] != 0)
        {
            // No response or unexpected response
            $Order->add_order_note(__('Payment failed', 'officeguy') . ' - ' . $Response['UserErrorMessage']);
            wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $Response['UserErrorMessage'], $notice_type = 'error');
            return false;
        }
        else
        { // if ($Response['Data']['Payment']['ValidPayment'] == false)
            // Decline
            $Order->add_order_note(__('Payment failed', 'officeguy') . ' - ' . $Response['Data']['Payment']['StatusDescription']);
            wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $Response['Data']['Payment']['StatusDescription'], $notice_type = 'error');
            return false;
        }
    }

    public static function ProcessOrderRefund($Gateway, $Order, $Amount, $Description)
    {
        $PaymentsCount = '1'; //OfficeGuyRequestHelpers::Post('og-paymentscount');
        //if ($PaymentsCount == '' || in_array('subscription', $ItemMethods))
        //$PaymentsCount = '1';

        $Request = array();
        $Request['Credentials'] = OfficeGuyPayment::GetCredentials($Gateway);
        if ($Amount == $Order->get_total())
        {
            $Request['Items'] = OfficeGuyPayment::GetPaymentOrderItems($Order);
            foreach ($Request['Items'] as $Key => $OrderItem)
                $Request['Items'][$Key]['UnitPrice'] = -round($OrderItem['UnitPrice'], 2);
        }
        else
        {
            $Request['Items'] = array();
            array_push($Request['Items'], array(
                'Item' => array(
                    'Name' => __('General credit', 'officeguy'),
                    'SearchMode' => 'Automatic'
                ),
                'UnitPrice' => -round($Amount, 2),
                'Currency' => $Order->get_currency(),
            ));
        }
        $Request['SupportCredit'] = 'true';
        $Request['VATIncluded'] = 'true';
        $Request['VATRate'] = OfficeGuyPayment::GetOrderVatRate($Order);
        $Request['Customer'] = array();
        $Request['Customer']['ID'] = $Order->get_meta("OfficeGuyCustomerID");
        $Request['AuthoriseOnly'] = $Gateway->settings['testing'] != 'no' ? 'true' : 'false';
        $Request['DraftDocument'] = $Gateway->settings['draftdocument'] != 'no' ? 'true' : 'false';
        $Request['SendDocumentByEmail'] = $Gateway->settings['emaildocument'] == 'yes' ? 'true' : 'false';
        $Request['DocumentDescription'] = __('Order number', 'officeguy') . ': ' . $Order->get_id() . (empty($Order->get_customer_note()) ? '' : "\r\n" . $Order->get_customer_note());
        $Request['Payments_Count'] = $PaymentsCount;
        $Request['DocumentLanguage'] = OfficeGuyPayment::GetOrderLanguage($Gateway);
        $Request['MerchantNumber'] = $Gateway->settings['merchantnumber'];
        $Tokens = $Order->get_payment_tokens();
        $Token = WC_Payment_Tokens::get($Tokens[count($Tokens) - 1]);
        $Request["PaymentMethod"] = OfficeGuyPayment::GetOrderPaymentMethodFromToken($Token);

        $Response = OfficeGuyAPI::Post($Request, '/billing/payments/charge/', $Gateway->settings['environment'], false);

        // Check response
        if ($Response['Status'] == 0 && $Response['Data']['Payment']['ValidPayment'] == true)
        {
            // Success    
            $ResponsePayment = $Response['Data']['Payment'];
            $ResponsePaymentMethod = $ResponsePayment['PaymentMethod'];
            $Remark = __('SUMIT credit completed. Auth Number: %s. Last digits: %s. Payment ID: %s. Document ID: %s. Customer ID: %s.', 'officeguy');
            $Remark = sprintf($Remark, $ResponsePayment['AuthNumber'], $ResponsePaymentMethod['CreditCard_LastDigits'], $ResponsePayment['ID'], $Response['Data']['DocumentID'], $Response['Data']['CustomerID']);
            $Order->add_order_note($Remark);
            $Order->add_meta_data('OfficeGuyCreditDocumentID', $Response['Data']['DocumentID']);
            $Order->save_meta_data();
            $Order->save();

            // Return thank you redirect
            return true;
        }
        else if ($Response['Status'] != 0)
        {
            // No response or unexpected response
            $Order->add_order_note(__('Credit failed', 'officeguy') . ' - ' . $Response['UserErrorMessage']);
            wc_add_notice(__('Credit failed', 'officeguy') . ' - ' . $Response['UserErrorMessage'], $notice_type = 'error');
            return false;
        }
        else
        { // if ($Response['Data']['Payment']['ValidPayment'] == false)
            // Decline
            $Order->add_order_note(__('Credit failed', 'officeguy') . ' - ' . $Response['Data']['Payment']['StatusDescription']);
            wc_add_notice(__('Credit failed', 'officeguy') . ' - ' . $Response['Data']['Payment']['StatusDescription'], $notice_type = 'error');
            return false;
        }
    }


    public static function GetOrderItemMethods($Order)
    {
        $ItemMethods = array();
        foreach ($Order->get_items() as $OrderItem)
        {
            if (get_post_meta($OrderItem['product_id'], 'OfficeGuySubscription', true) === 'yes')
            {
                if (!in_array('subscription', $ItemMethods))
                    array_push($ItemMethods, 'subscription');
            }
            else if (!in_array('simple', $ItemMethods))
                array_push($ItemMethods, 'simple');
        }
        return $ItemMethods;
    }

    public static function GetOrderVatRate($Order)
    {
        if (!wc_tax_enabled())
            return '';

        foreach ($Order->get_taxes() as $item_id => $ItemTax)
        {
            $TaxData = $ItemTax->get_data();
            $TaxRateId = $TaxData['rate_id'];
            return str_replace("%", "", WC_Tax::get_rate_percent($TaxRateId));
        }
        return '0';
    }

    public static function GetPaymentOrderItem($VariationID, $ProductID, $UnitPrice, $Quantity, $Currency, $ItemVendorCredentials, $OrderItem, $Order)
    {
        $Product = wc_get_product($ProductID);
        $DurationMonths = '0';
        $Recurrence = '0';
        if ($Product->get_meta('OfficeGuySubscription') === 'yes')
        {
            $DurationMonths = $Product->get_meta('_duration_in_months');
            $Recurrence = $Product->get_meta('_recurrences');
        }

        if (empty($VariationID))
            $ExternalIdentifier = $ProductID;
        else
        {
            $ExternalIdentifier = $VariationID;
            $Product = wc_get_product($VariationID);
        }

        $Item = array(
            'Duration_Days' => null,
            'Duration_Months' => $DurationMonths,
            'ExternalIdentifier' => $ExternalIdentifier,
            'Name' => $Product->get_name(),
            'SKU' => $Product->get_sku(),
            'SearchMode' => 'Automatic',
        );
        $Item = apply_filters('sumit_item_fields', $Item, $Product, $UnitPrice, $OrderItem, $Order);
        $ToReturn = array(
            'Item' => $Item,
            'Quantity' => $Quantity,
            'UnitPrice' => $UnitPrice,
            'Currency' => $Currency,
            'Duration_Days' => '0',
            'Duration_Months' => $DurationMonths,
            'Recurrence' => $Recurrence,
        );
        if (!empty($ItemVendorCredentials))
        {
            $ToReturn['CompanyID'] = intval($ItemVendorCredentials['OfficeGuyCompanyID']);
            $ToReturn['APIKey'] = $ItemVendorCredentials['OfficeGuyAPIKey'];
        }

        return $ToReturn;
    }

    public static function GetPaymentOrderItems($Order)
    {
        $Items = array();
        $Total = 0;

        foreach ($Order->get_items() as $OrderItem)
        {
            $VariationID = $OrderItem['variation_id'];
            $ProductID = $OrderItem['product_id'];
            $Quantity = $OrderItem['qty'];
            $UnitPrice = round($Order->get_line_total($OrderItem, true, true) / $Quantity, 2);
            $Currency = $Order->get_currency();
            
            if (OfficeGuyMultiVendor::PluginIsActive())
            {
                $ItemVendorCredentials = OfficeGuyMultiVendor::GetProductVendorCredentials();
                $Item = OfficeGuyPayment::GetPaymentOrderItem($VariationID, $ProductID, $UnitPrice, $Quantity, $Currency, $ItemVendorCredentials[$ProductID], $OrderItem, $Order);
            }
            else
                $Item = OfficeGuyPayment::GetPaymentOrderItem($VariationID, $ProductID, $UnitPrice, $Quantity, $Currency, null, $OrderItem, $Order);
            if ($Item == null)
                continue;
                
            array_push($Items, $Item);
            $Total += $UnitPrice * $Quantity;
        }

        foreach ($Order->get_fees() as $OrderItem)
        {
            array_push($Items, array(
                'Item' => array(
                    'Name' => $OrderItem->get_name(),
                    'SearchMode' => 'Automatic',
                    'ExternalIdentifier' => $OrderItem->get_id(),
                ),
                'UnitPrice' => round($OrderItem->get_total(), 2),
                'Currency' => $Order->get_currency(),
            ));
            $Total += $OrderItem->get_total();
        }

        $ShippingMethod = $Order->get_shipping_method();
        if (!empty($ShippingMethod))
        {
            $Shipping = $Order->get_shipping_total() + $Order->get_shipping_tax();
            array_push($Items, array(
                'Item' => array(
                    'Name' => $ShippingMethod,
                    'SearchMode' => 'Automatic'
                ),
                'Quantity' => 1,
                'UnitPrice' => round($Shipping, 2),
                'Currency' => $Order->get_currency()
            ));
            $Total += round($Shipping, 2);
        }

        $MissingAmount = round($Order->get_total() - $Total, 2);
        if ($MissingAmount != 0)
        {
            $MissingAmountName = null;
            if ($MissingAmount < 0)
                $MissingAmountName = __('General credit', 'officeguy');
            else
                $MissingAmountName = __('General', 'officeguy');
            array_push($Items, array(
                'Item' => array(
                    'Name' => $MissingAmountName,
                    'SearchMode' => 'Automatic'
                ),
                'Quantity' => 1,
                'UnitPrice' => $MissingAmount,
                'Currency' => $Order->get_currency()
            ));
        }
        return $Items;
    }

    public static function GetDocumentOrderItems($Order)
    {
        $Items = array();
        $Total = 0;

        foreach ($Order->get_items() as $OrderItem)
        {
            $IsOfficeGuySubscription = get_post_meta($OrderItem['product_id'], 'OfficeGuySubscription', true);

            $ExternalIdentifier = $OrderItem['variation_id'];
            if ($ExternalIdentifier == null || $ExternalIdentifier == '' || $ExternalIdentifier == '0')
                $ExternalIdentifier = $OrderItem['product_id'];
            $Product = wc_get_product($ExternalIdentifier);

            $ItemDetails = array(
                'ExternalIdentifier' => $ExternalIdentifier,
                'Name' => $Product->get_name(),
                'SKU' => $Product->get_sku(),
                'SearchMode' => 'Automatic'
            );
            $UnitPrice = round($Order->get_line_total($OrderItem, true, true) / $OrderItem['qty'], 2);
            
            $ItemDetails = apply_filters('sumit_item_fields', $ItemDetails, $Product, $UnitPrice, $OrderItem, $Order);
            if ($ItemDetails == null)
                continue;

            $Item = array(
                'Item' => $ItemDetails,
                'Quantity' => $OrderItem['qty'],
                'DocumentCurrency_UnitPrice' => $UnitPrice
            );
            
            array_push($Items, $Item);
            $Total += $OrderItem['qty'] * round($Order->get_line_total($OrderItem, true, true) / $OrderItem['qty'], 2);
        }

        foreach ($Order->get_fees() as $OrderItem)
        {
            array_push($Items, array(
                'Item' => array(
                    'Name' => $OrderItem->get_name(),
                    'SearchMode' => 'Automatic',
                    'ExternalIdentifier' => $OrderItem->get_id(),
                ),
                'DocumentCurrency_UnitPrice' => round($OrderItem->get_total(), 2)
            ));
            $Total += $OrderItem->get_total();
        }

        $Shipping = $Order->get_shipping_total() + $Order->get_shipping_tax();
        if ($Shipping != 0)
        {
            array_push($Items, array(
                'Item' => array(
                    'Name' => $Order->get_shipping_method(),
                    'SearchMode' => 'Automatic'
                ),
                'DocumentCurrency_UnitPrice' => round($Shipping, 2)
            ));
            $Total += round($Shipping, 2);
        }

        $MissingAmount = round($Order->get_total() - $Total, 2);
        if ($MissingAmount != 0)
        {
            $MissingAmountName = null;
            if ($MissingAmount < 0)
                $MissingAmountName = __('General credit', 'officeguy');
            else
                $MissingAmountName = __('General', 'officeguy');
            array_push($Items, array(
                'Item' => array(
                    'Name' => $MissingAmountName,
                    'SearchMode' => 'Automatic'
                ),
                'Quantity' => 1,
                'DocumentCurrency_UnitPrice' => $MissingAmount,
                'Currency' => $Order->get_currency()
            ));
        }
        return $Items;
    }

    public static function GetOrderCustomer($Gateway, $Order)
    {
        $CustomerName = $Order->get_billing_first_name() . ' ' . $Order->get_billing_last_name();
        if (!empty($Order->get_billing_company()) && $Order->get_billing_company() != '')
            $CustomerName = $Order->get_billing_company() . ' - ' . $CustomerName;
        if (empty($CustomerName) || $CustomerName == ' ')
            $CustomerName = __('Guest', 'officeguy');

        $VatRate = OfficeGuyPayment::GetOrderVatRate($Order);

        $Customer = array();
        $Customer['Name'] = $CustomerName;
        $Customer['EmailAddress'] = $Order->get_billing_email();
        $Customer['City'] = $Order->get_billing_city();
        if (!empty($Order->get_billing_state()))
        {
            if (empty($Customer['City']))
                $Customer['City'] = $Order->get_billing_state();
            else
                $Customer['City'] = $Customer['City'] . ', ' . $Order->get_billing_state();
        }
        if (!empty($Order->get_billing_country()) && $Order->get_billing_country() != 'IL')
        {
            if (empty($Customer['City']))
                $Customer['City'] = $Order->get_billing_country();
            else
                $Customer['City'] = $Customer['City'] . ', ' . $Order->get_billing_country();
        }

        $Customer['Address'] = $Order->get_billing_address_1();
        if (!empty($Order->get_billing_address_2()))
        {
            if (empty($Customer['Address']))
                $Customer['Address'] = $Order->get_billing_address_2();
            else
                $Customer['Address'] = $Customer['Address'] . ', ' . $Order->get_billing_address_2();
        }

        $Customer['ZipCode'] = $Order->get_billing_postcode();

        $Customer['Phone'] = $Order->get_billing_phone();
        $Customer['ExternalIdentifier'] = $Order->get_customer_id() == '0' ? '' : $Order->get_customer_id();
        $Customer['SearchMode'] = $Gateway->settings['mergecustomers'] == 'yes' ? 'Automatic' : 'None';
        $Customer['CompanyNumber'] = OfficeGuyRequestHelpers::Post('og-citizenid');
        if ($VatRate == '0')
            $Customer['NoVAT'] = true;
        else if ($VatRate != '')
            $Customer['NoVAT'] = false;
        $Customer = apply_filters('sumit_customer_fields', $Customer, $Order);
        return $Customer;
    }

    public static function GetOrderLanguage($Gateway)
    {
        $Language = '';
        if ($Gateway->settings['automaticlanguages'] == 'yes')
        {
            $locale = get_locale();
            if ($locale == 'en_US' || $locale == 'en')
                $Language = 'English';
            else if ($locale == 'ar_AR' || $locale == 'ar')
                $Language = 'Arabic';
            else if ($locale == 'es_ES' || $locale == 'es')
                $Language = 'Spanish';
            else if ($locale == 'he_IL' || $locale == 'he')
                $Language = 'Hebrew';
        }
        return $Language;
    }

    public static function GetMaximumPayments($Gateway, $OrderValue)
    {
        $MaximumPayments = round($Gateway->settings['maxpayments']);
        if ($Gateway->settings['minamountperpayment'] != '0')
            $MaximumPayments = min($MaximumPayments, round($OrderValue / $Gateway->settings['minamountperpayment']));
        if ($Gateway->settings['minamountforpayments'] != '0' && round($OrderValue) < round($Gateway->settings['minamountforpayments']))
            $MaximumPayments = 1;
        if (OfficeGuyMultiVendor::HasMultipleVendorsInCart())
            $MaximumPayments = 1;
        $MaximumPayments = apply_filters('sumit_maximum_installments', $MaximumPayments, $OrderValue);
        
        return $MaximumPayments;
    }

    public static function GetOrderPaymentMethodPCI()
    {
        $PaymentMethod = array();
        $PaymentMethod['CreditCard_Number'] = OfficeGuyRequestHelpers::Post('og-ccnum');
        $PaymentMethod['CreditCard_CVV'] = OfficeGuyRequestHelpers::Post('og-cvv');
        $PaymentMethod['CreditCard_CitizenID'] = OfficeGuyRequestHelpers::Post('og-citizenid');
        $PaymentMethod['CreditCard_ExpirationMonth'] = (OfficeGuyRequestHelpers::Post('og-expmonth') < 10) ? '0' . OfficeGuyRequestHelpers::Post('og-expmonth') : OfficeGuyRequestHelpers::Post('og-expmonth');
        $PaymentMethod['CreditCard_ExpirationYear'] = OfficeGuyRequestHelpers::Post('og-expyear');
        $PaymentMethod['Type'] = 1;
        return $PaymentMethod;
    }

    public static function GetOrderPaymentMethodFromToken($Token)
    {
        if (empty($Token))
            return null;

        $PaymentMethod = array();
        $PaymentMethod['CreditCard_Token'] = $Token->get_token();
        $PaymentMethod['CreditCard_CVV'] = OfficeGuyRequestHelpers::Post('og-cvv');
        $PaymentMethod['CreditCard_CitizenID'] = $Token->get_meta('og-citizenid');
        $PaymentMethod['CreditCard_ExpirationMonth'] = $Token->get_expiry_month();
        $PaymentMethod['CreditCard_ExpirationYear'] = $Token->get_expiry_year();
        $PaymentMethod['Type'] = 1;
        return $PaymentMethod;
    }

    public static function CreateDocumentOnPaymentComplete($OrderID)
    {
        $Gateway = GetOfficeGuyGateway();
        $UseCron = false;
        if ($Gateway->settings['paypalreceipts'] == 'async')
        {
            $Order = wc_get_order($OrderID);
            $PaymentMethod = $Order->get_payment_method();
            if ($PaymentMethod == 'paypal' || $PaymentMethod == 'eh_paypal_express' || $PaymentMethod == 'ppec_paypal' || $PaymentMethod == 'ppcp-gateway')
                $UseCron = true;
        }

        if ($UseCron)
        {
            if (!wp_schedule_single_event(time() + 5, 'officeguy_documentonpayment', array($OrderID)))
                OfficeGuyAPI::WriteToLog('Order #' . $OrderID . ' CreateDocumentOnPaymentComplete queue failed', 'debug');
            else
                OfficeGuyAPI::WriteToLog('Order #' . $OrderID . ' CreateDocumentOnPaymentComplete queued', 'debug');
        }
        else
            OfficeGuyPayment::CreateDocumentOnPaymentCompleteInternal($OrderID, false);
    }

    public static function CreateDocumentOnPaymentCompleteInternalHook($OrderID)
    {
        OfficeGuyPayment::CreateDocumentOnPaymentCompleteInternal($OrderID, false);
    }

    public static function CreateDocumentOnPaymentCompleteInternal($OrderID, $SkipPaymentMethodValidation)
    {
        $Gateway = GetOfficeGuyGateway();
        $Order = wc_get_order($OrderID);
        OfficeGuyAPI::WriteToLog('Order #' . $OrderID . ' CreateDocumentOnPaymentComplete', 'debug');
        if (!empty($Order->get_meta('OfficeGuyDocumentCreation')))
        {
            OfficeGuyAPI::WriteToLog('Order #' . $OrderID . ' CreateDocumentOnPaymentComplete skipped (duplicate)', 'debug');
            return;
        }
        $Order->add_meta_data('OfficeGuyDocumentCreation', "1");
        $Order->save_meta_data();

        $PaymentMethod = $Order->get_payment_method();
        $PaymentDescription = 'WooCommerce';
        OfficeGuyAPI::WriteToLog('Order #' . $OrderID . ' CreateDocumentOnPaymentComplete: ' . $PaymentMethod, 'debug');
        if ($PaymentMethod == 'paypal' || $PaymentMethod == 'eh_paypal_express' || $PaymentMethod == 'ppec_paypal' || $PaymentMethod == 'ppcp-gateway')
        {
            if (!$SkipPaymentMethodValidation && $Gateway->settings['paypalreceipts'] != 'yes' && $Gateway->settings['paypalreceipts'] != 'async')
                return;
            $PaymentDescription = 'PayPal';
            $PaymentTransactionID = get_post_meta($Order->get_id(), '_transaction_id', true);
            if (!empty($PaymentTransactionID))
                $PaymentDescription = $PaymentDescription . ' - ' . $PaymentTransactionID;
        }
        elseif ($PaymentMethod == 'bluesnap')
        {
            if (!$SkipPaymentMethodValidation && $Gateway->settings['bluesnapreceipts'] != 'yes')
                return;
            $PaymentDescription = 'BlueSnap';
        }
        elseif (!$SkipPaymentMethodValidation && !empty($Gateway->settings['otherreceipts']) && $PaymentMethod == $Gateway->settings['otherreceipts'])
        {
            $PaymentDescription = $PaymentMethod;
        }
        elseif (!$SkipPaymentMethodValidation)
            return;

        $Request = array();
        $Request['Credentials'] = OfficeGuyPayment::GetCredentials($Gateway);
        $Request['Items'] = OfficeGuyPayment::GetDocumentOrderItems($Order);
        $Request['VATIncluded'] = 'true';
        $Request['VATRate'] = OfficeGuyPayment::GetOrderVatRate($Order);
        $Request['Details'] = array(
            'IsDraft' => $Gateway->settings['draftdocument'] != 'no' ? 'true' : 'false',
            'Customer' => OfficeGuyPayment::GetOrderCustomer($Gateway, $Order),
            'Language' => OfficeGuyPayment::GetOrderLanguage($Gateway),
            'Currency' => $Order->get_currency(),
            'Description' => __('Order number', 'officeguy') . ': ' . $OrderID . (empty($Order->get_customer_note()) ? '' : "\r\n" . $Order->get_customer_note()),
            'Type' => '1'
        );
        if (OfficeGuyDonation::OrderContainsDonation($Order))
            $Request['Details']['Type'] = 'DonationReceipt';
        if ($Gateway->settings['emaildocument'] == 'yes') 
        {
            $Request['Details']['SendByEmail'] = array(
                'Original' => 'true'
            );
        }
        $Request['Payments'] = array();
        array_push($Request['Payments'], array(
            'Details_Other' => array(
                'Type' => 'WooCommerce',
                'Description' => $PaymentDescription,
                'DueDate' => '' . date('Y-m-d\TH:i:s', strtotime($Order->get_date_paid())) . ''
            )
        ));

        $Response = OfficeGuyAPI::Post($Request, '/accounting/documents/create/', $Gateway->settings['environment'], false);

        // Check response
        if ($Response['Status'] == 0)
        {
            // Success
            $Remark = __('SUMIT document completed. Document ID: %s. Customer ID: %s.', 'officeguy');
            $Remark = sprintf($Remark, $Response['Data']['DocumentID'], $Response['Data']['CustomerID']);
            $Order->add_order_note($Remark);
            $Order->add_meta_data('OfficeGuyDocumentID', $Response['Data']['DocumentID']);
            $Order->add_meta_data('OfficeGuyCustomerID', $Response['Data']['CustomerID']);
            $Order->save_meta_data();
            $Order->save();
        }
        else
        {
            // No response or unexpected response
            $Order->add_order_note(__('Document creation failed', 'officeguy') . ' - ' . $Response['UserErrorMessage']);
            wc_add_notice(__('Document creation failed', 'officeguy') . ' - ' . serialize($Gateway->settings['pci']) . ' ' . $Response['UserErrorMessage'], $notice_type = 'error');
        }
    }

    public static function GetCredentials($Gateway)
    {
        $Credentials = array();
        $Credentials['CompanyID'] = $Gateway->settings['companyid'];
        $Credentials['APIKey'] = $Gateway->settings['privatekey'];
        return $Credentials;
    }

    public static function IsCurrencySupported()
    {
        return in_array(get_woocommerce_currency(), array('ILS', 'USD', 'EUR', 'CAD', 'GBP', 'CHF', 'AUD', 'JPY', 'SEK', 'NOK', 'DKK', 'ZAR', 'JOD', 'LBP', 'EGP', 'BGN', 'CZK', 'HUF', 'PLN', 'RON', 'ISK', 'HRK', 'RUB', 'TRY', 'BRL', 'CNY', 'HKD', 'IDR', 'INR', 'KRW', 'MXN', 'MYR', 'NZD', 'PHP', 'SGD', 'THB'));
    }

    public static function CatalogPageBuyNowButton()
    {
        $Gateway = GetOfficeGuyGateway();
        $Product = wc_get_product(get_the_ID());
        if (isset($Gateway->settings['buynowloop']) && $Gateway->settings['buynowloop'] == 'yes' && !$Product->is_type('variable'))
            echo '<a href="' . wc_get_checkout_url() . (strpos(wc_get_checkout_url(), '?') !== false ? '&' : '?') . 'add-to-cart=' . get_the_ID() . '&quantity=1" class="button button-buynow">' . __('Buy Now', 'officeguy') . '</a>';
    }

    public static function ProductPageBuyNowButton()
    {
        $Gateway = GetOfficeGuyGateway();
        if (isset($Gateway->settings['buynowitem']) && $Gateway->settings['buynowitem'] == 'yes')
        {
            wp_enqueue_script('officeguy-front', PLUGIN_DIR . 'includes/js/officeguy.js', array('jquery'));
            $Product = wc_get_product(get_the_ID());
            $IsVariable = $Product->is_type('variable') ? 'true' : 'false';
            echo '<a onclick="og_buy_now_url(' . $IsVariable . ',' . get_the_ID() . ',\'' . wc_get_checkout_url() . '\'); return false;" href="#" class="button button-buynow single_add_to_cart_button">' . __('Buy Now', 'officeguy') . '</a>';
        }
    }

    public static function ThankYou($OrderID)
    {
        $Order = wc_get_order($OrderID);
        if ($Order->get_payment_method() != 'officeguy' && $Order->get_payment_method() != 'officeguybit')
            return;
        if ($Order->get_status() != "pending")
            return;

        $Gateway = GetOfficeGuyGateway();
        if ($Gateway->settings['pci'] != 'redirect' && $Order->get_payment_method() != 'officeguybit')
            return;
        $OGPaymentID = OfficeGuyRequestHelpers::Get('OG-PaymentID');
        if (empty($OGPaymentID))
            return;

        $Request = array();
        $Request['Credentials'] = OfficeGuyPayment::GetCredentials($Gateway);
        $Request['PaymentID'] = $OGPaymentID;
        $Response = OfficeGuyAPI::Post($Request, '/billing/payments/get/', $Gateway->settings['environment'], false);
        if ($Response == null)
            return;

        $OGDocumentID = OfficeGuyRequestHelpers::Get('OG-DocumentID');

        $ResponsePayment = $Response['Data']['Payment'];
        if ($ResponsePayment['ValidPayment'] != true)
        {
            $Order->add_order_note(__('Payment failed', 'officeguy') . ' - ' . $ResponsePayment['StatusDescription']);
            wc_add_notice(__('Payment failed', 'officeguy') . ' - ' . $ResponsePayment['StatusDescription'], $notice_type = 'error');
            $Order->update_status('failed');
        }
        else
        {
            $ResponsePaymentMethod = $ResponsePayment['PaymentMethod'];
            $Remark = __('SUMIT payment completed. Auth Number: %s. Last digits: %s. Payment ID: %s. Document ID: %s. Customer ID: %s.', 'officeguy');
            $Remark = sprintf($Remark, $ResponsePayment['AuthNumber'], $ResponsePaymentMethod['CreditCard_LastDigits'], $ResponsePayment['ID'], $OGDocumentID, $ResponsePayment['CustomerID']);
            $Order->add_order_note($Remark);
            $Order->payment_complete();

            if ($Gateway->settings['createorderdocument'] == 'yes')
            {
                $OrderCustomer = array(
                    'ID' => $Response['Data']['CustomerID']
                );
                OfficeGuyPayment::CreateOrderDocument($Gateway, $Order, $OrderCustomer, $Response['Data']['DocumentID']);
            }
        }
    }

    public static function AdminPageCreateDocumentButton($Actions)
    {
        global $theorder;
        if ($theorder->get_meta("OfficeGuyDocumentID") == null)
            $Actions['officeguy_create_document'] = __('Create invoice/receipt', 'officeguy');
        return $Actions;
    }

    public static function AdminPageCreateDocument($Order)
    {
        OfficeGuyPayment::CreateDocumentOnPaymentCompleteInternal($Order->get_id(), true);
    }
}

add_action('woocommerce_after_shop_loop_item', 'OfficeGuyPayment::CatalogPageBuyNowButton');
add_action('woocommerce_after_add_to_cart_button', 'OfficeGuyPayment::ProductPageBuyNowButton');
//add_action('woocommerce_thankyou', 'OfficeGuyPayment::ThankYou', 10, 1);
add_action('officeguy_documentonpayment', 'OfficeGuyPayment::CreateDocumentOnPaymentCompleteInternalHook');
add_action('woocommerce_order_actions', 'OfficeGuyPayment::AdminPageCreateDocumentButton');
add_action('woocommerce_order_action_officeguy_create_document', 'OfficeGuyPayment::AdminPageCreateDocument');
