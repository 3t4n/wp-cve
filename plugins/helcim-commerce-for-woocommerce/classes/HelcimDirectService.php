<?php

class HelcimDirectService
{
    private HelcimCurl $helcimCURL;
    private string $error;

    public function __construct(HelcimCurl $helcimCurl)
    {
        $this->helcimCURL = $helcimCurl;
        $this->error = '';
    }

    /**
     * @deprecated please use self::processRefundV2
     * @param WC_Order $order
     * @param float $amount
     * @param WCHelcimGateway $helcimGateway
     * @return string|null
     */
    public function processRefund(WC_Order $order, float $amount, WCHelcimGateway $helcimGateway): ?string
    {

        if (!$this->checkOrderForRefund($order, $amount)) {
            $this->setError("Error - {$this->getError()}");
            return null;
        }
        $response = $this->helcimCURL->curl(
            $this->buildRefundPostData($helcimGateway, $order, $amount),
            WCHelcimGateway::API_ENDPOINT
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $objectXML = $this->helcimCURL->validXML($response);
        if (!$objectXML instanceof SimpleXMLElement) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return isset($objectXML->transaction->approvalCode) ? (string)$objectXML->transaction->approvalCode : '';
    }

    public function processRefundV2(WC_Order $order, float $amount, WCHelcimGateway $helcimGateway): ?string
    {

        if (!$this->checkOrderForRefund($order, $amount)) {
            $this->setError("Error - {$this->getError()}");
            return null;
        }
        $response = $this->helcimCURL->curl(
            HelcimApiFactory::buildRefund($order, $amount, $helcimGateway),
            WCHelcimGateway::API_V2_ENDPOINT.'payment/refund',
            array_merge(
                HelcimApiFactory::apiV2Headers($helcimGateway),
                ['idempotency-key: '.HelcimApiFactory::buildIdempotencyKey(25)]
            )
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $responsePayload = json_decode($response, true);
        if (!is_array($responsePayload)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return isset($responsePayload['approvalCode']) ? (string)$responsePayload['approvalCode'] : '';
    }

    private function buildRefundPostData(WCHelcimGateway $helcimGateway, $order, float $amount): array
    {

        $postData = $this->helcimCURL->buildGenericPostData($helcimGateway);
        $postData['transactionType'] = 'refund';
        $postData['transactionId'] = $order->get_transaction_id();
        $postData['amount'] = $amount;
        $postData['cardToken'] = $order->get_meta(WCHelcimGateway::HELCIM_CARD_TOKEN);
        $postData['cardF4L4Skip'] = '1';
        $postData['test'] = $helcimGateway->isTest();
        return $postData;
    }

    public function processVoid(int $transactionId, WCHelcimGateway $wcHelcimGateway): ?SimpleXMLElement
    {

        $response = $this->helcimCURL->curl(
            $this->buildVoidPostData($wcHelcimGateway, $transactionId),
            WCHelcimGateway::API_ENDPOINT
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $objectXML = $this->helcimCURL->validXML($response);
        if (!$objectXML instanceof SimpleXMLElement) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return $objectXML;
    }

    private function buildVoidPostData(WCHelcimGateway $wcHelcimGateway, int $transactionId): array
    {

        $postData = $this->helcimCURL->buildGenericPostData($wcHelcimGateway);
        $postData['transactionType'] = 'void';
        $postData['transactionId'] = $transactionId;
        $postData['test'] = $wcHelcimGateway->isTest();
        return $postData;
    }

    /**
     * @deprecated please use self::processPurchase or self::processPreauth
     */
    public function processPurchasePreauth($order, WCHelcimGateway $wcHelcimGateway): ?SimpleXMLElement
    {

        $response = $this->helcimCURL->curl(
            $this->buildPurchasePreauthPostData($order, $wcHelcimGateway),
            WCHelcimGateway::API_ENDPOINT
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $objectXML = $this->helcimCURL->validXML($response);
        if (!$objectXML instanceof SimpleXMLElement) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return $objectXML;
    }

    public function processPurchase(WC_Abstract_Order $order, WCHelcimGateway $wcHelcimGateway, string $customerCode): ?array
    {
        $response = $this->helcimCURL->curl(
            HelcimApiFactory::buildPurchasePreauth($wcHelcimGateway, $order, $customerCode),
            WCHelcimGateway::API_V2_ENDPOINT.'payment/purchase',
            array_merge(
                HelcimApiFactory::apiV2Headers($wcHelcimGateway),
                ['idempotency-key: '.HelcimApiFactory::buildIdempotencyKey(25)]
            )
        );
        //(string)openssl_random_pseudo_bytes(25)
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $responsePayload = json_decode($response, true);
        if (!is_array($responsePayload)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return $responsePayload;
    }

    public function processPreauth(WC_Abstract_Order $order, WCHelcimGateway $wcHelcimGateway, string $customerCode): ?array
    {

        $response = $this->helcimCURL->curl(
            HelcimApiFactory::buildPurchasePreauth($wcHelcimGateway, $order, $customerCode),
            WCHelcimGateway::API_V2_ENDPOINT.'payment/preauth',
            array_merge(
                HelcimApiFactory::apiV2Headers($wcHelcimGateway),
                ['idempotency-key: '.HelcimApiFactory::buildIdempotencyKey(25)]
            )
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $responsePayload = json_decode($response, true);
        if (!is_array($responsePayload)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return $responsePayload;
    }

    /**
     * @deprecated please use self::buildPurchasePreauthPayload
     */
    private function buildPurchasePreauthPostData($order, WCHelcimGateway $wcHelcimGateway): array
    {
        $shippingAmount = $order->get_shipping_total();
        $discountAmount = $order->get_total_discount();
        $refundAmount = 0;
        $orderItemCounter = 0;
        $taxed = false;
        if (is_array($order->get_refunds())) {
            foreach ($order->get_refunds() as $refund) {
                $refundAmount += $refund->get_amount();
            }
        }

        $helcimArgs = $this->helcimCURL->buildGenericPostData($wcHelcimGateway);
        $helcimArgs['transactionType'] = $wcHelcimGateway->getTransactionType();
        $helcimArgs['terminalId'] = $wcHelcimGateway->getTerminalId();
        $helcimArgs['cvvIndicator'] = 1;
        $helcimArgs['amount'] = number_format($order->get_total(), 2, '.', '');
        $helcimArgs['currency'] = $wcHelcimGateway->woocommerceCurrencyAbbreviation();
        $helcimArgs['amountShipping'] = number_format($shippingAmount, 2, '.', '');
        $helcimArgs['amountTax'] = number_format($order->get_total_tax(), 2, '.', '');
        $helcimArgs['amountDiscount'] = number_format('-' . ($discountAmount + $refundAmount), 2, '.', '');
        $helcimArgs['orderNumber'] = $wcHelcimGateway->setOrderNumber((string)$order->get_order_number());
        $helcimArgs['comments'] = $order->get_customer_note();
        if (get_current_user_id()) {
            $helcimArgs['customerCode'] = get_current_user_id();
        }
        if ($wcHelcimGateway->isJS()) {
            // look for generated customer code from verify transaction not matching user's id
            if (isset($_POST['customerCode'], $helcimArgs['customerCode'])
                && $_POST['customerCode'] !== $helcimArgs['customerCode']) {
                $helcimArgs['customerCodeOld'] = $_POST['customerCode'];
            }
            if (isset($_POST['cardToken'])) {
                $helcimArgs['cardToken'] = $_POST['cardToken'];
            }
            $helcimArgs['cardF4L4Skip'] = 1;
        } elseif ($wcHelcimGateway->isDirect()) {
            $helcimArgs['cardHolderName'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            if (isset($_POST['cardNumber'])) {
                $helcimArgs['cardNumber'] = $_POST['cardNumber'];
            }
            if (isset($_POST['cardExpiryMonth'], $_POST['cardExpiryYear'])) {
                $helcimArgs['cardExpiry'] = $_POST['cardExpiryMonth'] . substr($_POST['cardExpiryYear'], -2);
            }
            if (isset($_POST['cardCVV'])) {
                $helcimArgs['cardCVV'] = $_POST['cardCVV'];
            }
            $helcimArgs['cardHolderAddress'] = $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
            $helcimArgs['cardHolderPostalCode'] = $order->get_billing_postcode();
        }
        if (is_array($order->get_items())) {
            foreach ($order->get_items() as $item) {
                $orderItemCounter++;
                $product = $item->get_product();
                $sku = $product->get_sku() ?: 'NoSKU';
                $helcimArgs['itemSKU' . $orderItemCounter] = $sku;
                $helcimArgs['itemDescription' . $orderItemCounter] = $item['name'];
                $helcimArgs['itemQuantity' . $orderItemCounter] = $item['qty'];
                $helcimArgs['itemPrice' . $orderItemCounter] = $order->get_item_total($item, $taxed, false);
                $helcimArgs['itemTotal' . $orderItemCounter] = $order->get_line_total($item, $taxed, false);
            }
        }
        if (is_array($order->get_fees())) {
            foreach ($order->get_fees() as $fee) {
                $orderItemCounter++;
                $helcimArgs['itemSKU' . $orderItemCounter] = 'Fee' . $fee->get_id();
                $helcimArgs['itemDescription' . $orderItemCounter] = $fee->get_name();
                $helcimArgs['itemQuantity' . $orderItemCounter] = '1';
                $helcimArgs['itemPrice' . $orderItemCounter] = $fee->get_total();
                $helcimArgs['itemTotal' . $orderItemCounter] = $fee->get_total();
            }
        }
        $helcimArgs['billing_contactName'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $helcimArgs['billing_street1'] = $order->get_billing_address_1();
        $helcimArgs['billing_street2'] = $order->get_billing_address_2();
        $helcimArgs['billing_city'] = $order->get_billing_city();
        $helcimArgs['billing_province'] = $order->get_billing_state();
        $helcimArgs['billing_postalCode'] = $order->get_billing_postcode();
        $helcimArgs['billing_country'] = $order->get_billing_country();
        $helcimArgs['billing_phone'] = $order->get_billing_phone();
        $helcimArgs['billing_email'] = $order->get_billing_email();
        $helcimArgs['shipping_contactName'] = $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(
            );
        $helcimArgs['shipping_street1'] = $order->get_shipping_address_1();
        $helcimArgs['shipping_street2'] = $order->get_shipping_address_2();
        $helcimArgs['shipping_city'] = $order->get_shipping_city();
        $helcimArgs['shipping_province'] = $order->get_shipping_state();
        $helcimArgs['shipping_postalCode'] = $order->get_shipping_postcode();
        $helcimArgs['shipping_country'] = $order->get_shipping_country();
        foreach ($helcimArgs as $key => $value) {
            $helcimArgs[$key] = trim($value);
        }
        return $helcimArgs;
    }

    private function checkOrderForRefund(WC_Order $order, float $amount): bool
    {
        if (!$order->get_transaction_id()) {
            $this->setError('Missing Transaction Id');
            return false;
        }
        if (!$order->get_meta(WCHelcimGateway::HELCIM_CARD_TOKEN)) {
            $this->setError('Missing Card Token');
            return false;
        }
        if ($amount <= 0) {
            $this->setError('Amount to refund should be greater than 0');
            return false;
        }
        return true;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): HelcimDirectService
    {
        $this->error = $error;
        return $this;
    }

    public function getCustomerId(string $customerCode, WCHelcimGateway $helcimGateway): ?int
    {

        $response = $this->helcimCURL->curl(
            ['customerCode' => $customerCode],
            WCHelcimGateway::API_V2_ENDPOINT.'customers/',
            HelcimApiFactory::apiV2Headers($helcimGateway),
            'GET'
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return false;
        }
        $responsePayload = json_decode($response, true);
        if(!is_array($responsePayload) || count($responsePayload) === 0){
            return 0;
        }
        $customer = reset($responsePayload);
        return isset($customer['id']) ? (int)$customer['id'] : 0;
    }

    public function updateCustomer(int $customerId, string $customerCode, WC_Abstract_Order $order, WCHelcimGateway $helcimGateway): ?array
    {

        $response = $this->helcimCURL->curl(
            [
                'customerCode' => $customerCode,
                'contactName' => $customerCode,
                'businessName' => $customerCode
            ],
            WCHelcimGateway::API_V2_ENDPOINT."customers/$customerId",
            HelcimApiFactory::apiV2Headers($helcimGateway),
            'PUT'
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $responsePayload = json_decode($response, true);
        if (!is_array($responsePayload)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return $responsePayload;
    }

    public function createCustomer(string $customerCode, WC_Abstract_Order $order, WCHelcimGateway $wcHelcimGateway): ?array
    {

        $response = $this->helcimCURL->curl(
            HelcimApiFactory::buildCustomerCreate($customerCode, $order),
            WCHelcimGateway::API_V2_ENDPOINT."customers/",
            HelcimApiFactory::apiV2Headers($wcHelcimGateway)
        );
        if (!is_string($response)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        $responsePayload = json_decode($response, true);
        if (!is_array($responsePayload)) {
            $this->setError($this->helcimCURL->getError());
            return null;
        }
        return $responsePayload;
    }
}
