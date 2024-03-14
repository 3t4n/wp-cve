<?php

class Cartflows_Pro_Gateway_OfficeGuy
{
    private static $instance;

    public static function get_instance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function __construct()
    {
    }

    public function process_offer_payment($Order, $Product)
    {
        $Gateway = GetOfficeGuyGateway();
        add_action('cartflows_offer_child_order_created_' . $Gateway->id, array($this, 'ChildOrderCreated'), 10, 3);

        $OrderItem = $Order->get_items()[0];

        $Request = $this->GetOfferPaymentRequest($Order, $OrderItem, $Product);
        $IsOfficeGuySubscription = get_post_meta($Product['id'], 'OfficeGuySubscription', true) === 'yes';
        if ($IsOfficeGuySubscription)
            $Response = OfficeGuyAPI::Post($Request, '/billing/recurring/charge/', $Gateway->settings['environment'], false);
        else
            $Response = OfficeGuyAPI::Post($Request, '/billing/payments/charge/', $Gateway->settings['environment'], false);

        // Check response
        if ($Response['Status'] == 0 && $Response['Data']['Payment']['ValidPayment'] == true)
        {
            // Success    
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
            $Order->save();

            if ($Gateway->settings['createorderdocument'] == 'yes')
            {
                $OrderCustomer = array(
                    'ID' => $Response['Data']['CustomerID']
                );
                OfficeGuyPayment::CreateOrderDocument($Gateway, $Order, $OrderCustomer, $Response['Data']['DocumentID']);
            }

            return true;
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

	public function is_api_refund() 
    {
		return false;
	}

    public function ChildOrderCreated($ParentOrder, $Order, $TransactionID) 
    {
        $Tokens = $ParentOrder->get_payment_tokens();
        $Token = WC_Payment_Tokens::get($Tokens[count($Tokens) - 1]);
        if ($Token == null) 
        {
            OfficeGuyAPI::WriteToLog('Order #' . $Order->get_id() . ' no Token found for parent order #' . $ParentOrder->get_id(), 'debug');
            return;
        }

        OfficeGuyTokens::SaveTokenToOrder($Order, $Token);
    }
    private function GetOfferPaymentRequest($Order, $OrderItem, $Product)
    {
        $Gateway = GetOfficeGuyGateway();
        $Request = array();
        $Request['Credentials'] = OfficeGuyPayment::GetCredentials($Gateway);
        $Request['Items'] = array();
        $Item = OfficeGuyPayment::GetPaymentOrderItem(null, $Product['id'], round($Product['price'], 2), 1, $Order->get_currency(), null, $OrderItem, $Order);
        array_push($Request['Items'], $Item);
        $Request['VATIncluded'] = 'true';
        $Request['VATRate'] = OfficeGuyPayment::GetOrderVatRate($Order);
        $Request['Customer'] = array();
        $Request['Customer']['ID'] = $Order->get_meta("OfficeGuyCustomerID");
        $Request['AuthoriseOnly'] = $Gateway->settings['testing'] != 'no' ? 'true' : 'false';
        $Request['DraftDocument'] = $Gateway->settings['draftdocument'] != 'no' ? 'true' : 'false';
        $Request['SendDocumentByEmail'] = $Gateway->settings['emaildocument'] == 'yes' ? 'true' : 'false';
        $Request['UpdateCustomerByEmail'] = 'false';
        $Request['DocumentDescription'] = __('Order number', 'officeguy') . ': ' . $Order->get_id() . (empty($Order->get_customer_note()) ? '' : "\r\n" . $Order->get_customer_note());
        $Request['DocumentLanguage'] = OfficeGuyPayment::GetOrderLanguage($Gateway);
        $Request['MerchantNumber'] = $Gateway->settings['merchantnumber'];

        $Tokens = $Order->get_payment_tokens();
        $Token = WC_Payment_Tokens::get($Tokens[count($Tokens) - 1]);
        $Request["PaymentMethod"] = OfficeGuyPayment::GetOrderPaymentMethodFromToken($Token);
        return $Request;
    }
}

/**
 *  Prepare if class 'Cartflows_Pro_Gateway_OfficeGuy' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Cartflows_Pro_Gateway_OfficeGuy::get_instance();

