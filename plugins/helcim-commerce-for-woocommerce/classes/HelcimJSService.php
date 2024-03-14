<?php


class HelcimJSService
{

    private $error;
    private $helcimCurl;

    public function __construct()
    {
        $this->error = '';
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): HelcimJSService
    {
        $this->error = $error;
        return $this;
    }

    public function getHelcimCurl(): ?HelcimCurl
    {
        return $this->helcimCurl;
    }

    public function setHelcimCurl(HelcimCurl $helcimCurl): HelcimJSService
    {
        $this->helcimCurl = $helcimCurl;
        return $this;
    }

    public function updateOrderNumber(string $orderNumber, WCHelcimGateway $helcimGateway, $order): bool
    {
        if (!$this->getHelcimCURL() instanceof HelcimCurl) {
            $this->setHelcimCURL(new HelcimCurl());
        }
        if (!$this->getHelcimCURL() instanceof HelcimCurl) {
            $this->setHelcimCURL(new HelcimCurl());
        }
        $post = $this->getHelcimCURL()->buildGenericPostData($helcimGateway);
        $post["action"] = "orderEdit";
        $post["orderNumber"] = $orderNumber;
        $post["newOrderNumber"] = $helcimGateway->setOrderNumber((string)$order->get_order_number());
        $response = $this->getHelcimCURL()->curl($post, WCHelcimGateway::API_ENDPOINT);
        if (!is_string($response)) {
            $this->setError($this->getHelcimCURL()->getError());
            return false;
        }
        $objectXML = $this->getHelcimCURL()->convertToXML($response);
        if (!$objectXML instanceof SimpleXMLElement) {
            $this->setError($this->getHelcimCURL()->getError());
            return false;
        }
        if (!$this->getHelcimCURL()->validateXML($objectXML)) {
            $this->setError($this->getHelcimCURL()->getError());
            return false;
        }
        return true;
    }

    public function htmlLineItems(array $helcimLineItems): string
    {
        $html = '';
        $orderItemCounter = 0;
        foreach ($helcimLineItems as $helcimLineItem) {
            if (!$helcimLineItem instanceof HelcimLineItem) {
                continue;
            }
            $orderItemCounter++;
            $html .= '<input type="hidden" id="itemSKU' . $orderItemCounter . '" value="' . $helcimLineItem->getSku(
                ) . '">';
            $html .= '<input type="hidden" id="itemDescription' . $orderItemCounter . '" value="' . $helcimLineItem->getDescription(
                ) . '">';
            $html .= '<input type="hidden" id="itemQuantity' . $orderItemCounter . '" value="' . $helcimLineItem->getQuantity(
                ) . '">';
            $html .= '<input type="hidden" id="itemPrice' . $orderItemCounter . '" value="' . $helcimLineItem->getPrice(
                ) . '">';
            $html .= '<input type="hidden" id="itemTotal' . $orderItemCounter . '" value="' . $helcimLineItem->getTotal(
                ) . '">';
        }
        return $html;
    }

    public function parseXML(string $xml, string $xmlHash, WCHelcimGateway $helcimGateway): ?SimpleXMLElement
    {
        if (!$this->isValidXML($xml, $xmlHash, $helcimGateway)) {
            $this->setError("Error - {$this->getError()}");
            return null;
        }
        $xmlObject = simplexml_load_string($xml);
        if (!$xmlObject instanceof SimpleXMLElement) {
            $this->setError("Error - Invalid XML");
            return null;
        }
        if (!isset($xmlObject->date, $xmlObject->time)) {
            $this->setError("Error - Missing Transaction Processing Time in XML");
            return null;
        }
        if ($this->isTransactionExpired($xmlObject)) {
            return null;
        }
        return $xmlObject;
    }

    private function isTransactionExpired(SimpleXMLElement $xmlObject): bool
    {
        try {
            $transactionTimeObject = new DateTime(
                "{$xmlObject->date} {$xmlObject->time}",
                new DateTimeZone(WCHelcimGateway::HELCIM_SERVER_TIMEZONE)
            );
        } catch (Exception $e) {
            $this->setError("Error - Invalid Transaction Processing Time in XML");
            return true;
        }
        $transactionTime = $transactionTimeObject->getTimestamp();
        $now = time();
        if (($now - $transactionTime) < 0) {
            $this->setError("Error - Transaction Cannot Happen in the Future");
            return true;
        }
        if (($now - $transactionTime) > 120) {
            $this->setError(
                "Error - Transaction #{$xmlObject->transactionId} Expired({$transactionTimeObject->format('c')})"
            );
            return true;
        }
        return false;
    }

    private function isValidXML(string $xml, string $xmlHash, WCHelcimGateway $helcimGateway): bool
    {
        if ($xmlHash === '') {
            $this->setError('XML Hash is Empty');
            return false;
        }
        if ($xml === '') {
            $this->setError('XML is Empty');
            return false;
        }
        $xmlNoSpace = preg_replace('/\s+/', '', $xml);
        $generatedHash = $this->hash($xmlNoSpace, $helcimGateway->getJsSecretKey());
        if ($generatedHash !== $xmlHash) {
            $this->setError('Invalid Hash');
            return false;
        }
        return true;
    }

    public function hash(string $data, string $secret): string
    {
        if ($data === '') {
            return '';
        }
        return hash('sha256', $secret . $data);
    }

    public function isValidFields(array $post, WCHelcimGateway $helcimGateway): bool
    {
        if (WCHelcimGateway::FORCE_HELCIM_JS_TO_RUN_VERIFY) {
            if (!isset($_POST['response']) || (int)$_POST['response'] !== 1) {
                $this->setError(
                    isset($_POST['responseMessage']) ? (string)$_POST['responseMessage'] : 'Helcim JS Not Set'
                );
                return false;
            }
            if (!isset($_POST['cardToken'])) {
                $this->setError('Missing Card Token in Post');
                return false;
            }
            if (!isset($_POST['cardNumber'])) {
                $this->setError('Missing Card First-4 Last-4');
                return false;
            }
            if (!isset($_POST['approvalCode'])) {
                $this->setError('Missing Approval Code');
                return false;
            }
            if (!isset($_POST['transactionId'])) {
                $this->setError('Missing Transaction Id');
                return false;
            }
            return true;
        }
        if (!isset($post['xml'])) {
            $this->setError('Missing XML');
            return false;
        }
        if (!isset($post['xmlHash'])) {
            $this->setError('Missing XML Hash');
            return false;
        }
        $xmlObject = $this->parseXML($post['xml'], $post['xmlHash'], $helcimGateway);
        if (!$xmlObject instanceof SimpleXMLElement) {
            return false;
        }
        return true;
    }

    public function processPayment($order, SimpleXMLElement $xmlObject, WCHelcimGateway $helcimGateway): bool
    {
        if (!isset($xmlObject->response) || (int)$xmlObject->response !== 1) {
            $errorMessage = isset($xmlObject->responseMessage) ? (string)$xmlObject->responseMessage : 'Something went wrong please contact the Merchant';
            wc_add_notice("DECLINED - $errorMessage", 'error');
            WCHelcimGateway::log('Helcim JS - DECLINED - ' . print_r($xmlObject, true));
            $order->add_order_note("DECLINED - $errorMessage");
            return false;
        }
        if (!isset($xmlObject->cardToken)) {
            wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
            WCHelcimGateway::log('Helcim JS - Missing Card Token ' . print_r($xmlObject, true));
            $order->add_order_note("ERROR - Missing Card Token");
            return false;
        }
        if (!isset($xmlObject->cardNumber)) {
            wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
            WCHelcimGateway::log('Helcim JS - Missing Card First-4 Last-4 ' . print_r($xmlObject, true));
            $order->add_order_note("ERROR - Missing Card First-4 Last-4");
            return false;
        }
        if (!isset($xmlObject->approvalCode)) {
            wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
            WCHelcimGateway::log('Helcim JS - Missing Approval Code ' . print_r($xmlObject, true));
            $order->add_order_note("ERROR - Missing Approval Code");
            return false;
        }
        if (!isset($xmlObject->currency)) {
            wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
            WCHelcimGateway::log('Helcim JS - Missing Currency: ' . print_r($xmlObject, true));
            $order->add_order_note("ERROR - Missing Currency");
            return false;
        }

        if ((string)$xmlObject->currency !== $helcimGateway->woocommerceCurrencyAbbreviation()) {
            wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
            WCHelcimGateway::log(
                'Helcim JS - Order Currency(' . $helcimGateway->woocommerceCurrencyAbbreviation(
                ) . ') Does not match Transaction Currency(' . (string)$xmlObject->currency . ')'
            );
            $order->add_order_note(
                'ERROR - Order Currency(' . $helcimGateway->woocommerceCurrencyAbbreviation(
                ) . ') Does not match Transaction Currency(' . (string)$xmlObject->currency . ')'
            );
            return false;
        }
        $orderNumber = isset($xmlObject->orderNumber) ? (string)$xmlObject->orderNumber : '';
        if (!$this->updateOrderNumber($orderNumber, $helcimGateway, $order)) {
            WCHelcimGateway::log(
                "ORDER {$order->get_id()} Failed to Update Helcim Order Number - {$this->getError()}"
            );
            $order->add_order_note("Failed to update Helcim Order Number");
        }
        return true;
    }
}