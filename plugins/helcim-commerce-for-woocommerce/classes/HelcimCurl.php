<?php

class HelcimCurl
{
    private string $error;
    private int $curlErrorNumber;
    private string $curlError;
    private string $request;
    private string $response;

    public function __construct()
    {
        $this->error = '';
        $this->curlErrorNumber = 0;
        $this->curlError = '';
        $this->request = '';
        $this->response = '';
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): HelcimCurl
    {
        $this->error = $error;
        return $this;
    }

    public function curl(array $request, string $url, array $headers = [], string $method = 'POST'): ?string
    {
        $curlOptions = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS => $this->getRequest(),
            CURLOPT_TIMEOUT => 60
        ];
        if ($method === 'GET') {
            $this->setRequest(http_build_query($request));
            $url .= "?{$this->getRequest()}";
            $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
        } else {
            $this->setRequest(json_encode($request));
            $curlOptions[CURLOPT_POSTFIELDS] = $this->getRequest();
            $curlOptions[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
            if ($method === 'POST') {
                $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
            } elseif ($method === 'PUT') {
                $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
            } else {
                WCHelcimGateway::log("Invalid Method: $method");
                return null;
            }
        }

        if (count($headers) > 0) {
            foreach ($headers as $h) {
                $curlOptions[CURLOPT_HTTPHEADER][] = $h;
            }
        }
        $curl = curl_init($url);
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        $this->setResponse('');
        $this->setCurlErrorNumber(curl_errno($curl));
        $this->setCurlError(curl_error($curl));
        curl_close($curl);
        if ($response === false) {
            $this->setError("Communication to Helcim Failed - ({$this->getCurlErrorNumber()}) {$this->getCurlError()}");
            WCHelcimGateway::log(
                "$method $url {$info['http_code']} curlError:({$this->getCurlErrorNumber()}) {$this->getCurlError()}"
            );
            return null;
        }
        if (!isset($info['http_code']) || (int)$info['http_code'] !== 200) {
            $responseArray = json_decode($response, true);
            if (is_array($responseArray) && isset($responseArray['errors'])) {
                $errors = is_array($responseArray['errors'])
                    ? implode(', ', $responseArray['errors']) : (string)$responseArray['errors'];
            } else {
                $errors = (string)$response;
            }
            WCHelcimGateway::log("$method $url {$info['http_code']} error:$errors");
            $this->setError("Communication to Helcim Failed - httpCode:{$info['http_code']} response:$errors");
            return null;
        }
        WCHelcimGateway::log("$method $url {$info['http_code']}");
        $this->setResponse($response);
        return $this->getResponse();
    }

    public function getRequest(): string
    {
        return $this->request;
    }

    public function setRequest(string $request): HelcimCurl
    {
        $this->request = $request;
        return $this;
    }

    public function getCurlErrorNumber(): int
    {
        return $this->curlErrorNumber;
    }

    public function setCurlErrorNumber(int $curlErrorNumber): HelcimCurl
    {
        $this->curlErrorNumber = $curlErrorNumber;
        return $this;
    }

    public function getCurlError(): string
    {
        return $this->curlError;
    }

    public function setCurlError(string $curlError): HelcimCurl
    {
        $this->curlError = $curlError;
        return $this;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): HelcimCurl
    {
        $this->response = $response;
        return $this;
    }

    public function convertToXML(string $xmlString): ?SimpleXMLElement
    {
        if (stripos($xmlString, '<?xml') === false) {
            $this->setError("Invalid XML String - $xmlString");
            return null;
        }
        $objectXML = simplexml_load_string($xmlString);
        if (!$objectXML instanceof SimpleXMLElement) {
            $this->setError("Failed to convert to XML - $xmlString");
            return null;
        }
        return $objectXML;
    }

    public function validateXML(SimpleXMLElement $xml): bool
    {
        if (!isset($xml->response) || (int)$xml->response !== 1) {
            $this->setError(isset($xml->responseMessage) ? (string)$xml->responseMessage : 'Invalid Response');
            return false;
        }
        return true;
    }

    public function validXML(string $response): ?SimpleXMLElement
    {
        $objectXML = $this->convertToXML($response);
        if (!$objectXML instanceof SimpleXMLElement) {
            return null;
        }
        if (!$this->validateXML($objectXML)) {
            return null;
        }
        return $objectXML;
    }

    /**
     * @deprecated please use HelcimApiFactory::genericPaymentPayload
     * @param WCHelcimGateway $wcHelcimGateway
     * @return array
     */
    public function buildGenericPostData(WCHelcimGateway $wcHelcimGateway): array
    {
        $ipAddress = class_exists('WC_Geolocation') ? WC_Geolocation::get_ip_address() : $_SERVER['REMOTE_ADDR'] ?? '';
        return [
            'accountId' => $wcHelcimGateway->getAccountId(),
            'apiToken' => $wcHelcimGateway->getAPIToken(),
            'test' => $wcHelcimGateway->isTest(),
            'ecommerce' => 1,
            'ipAddress' => $ipAddress,
            'thirdParty' => WCHelcimGateway::PLUGIN_NAME,
            'pluginVersion' => WCHelcimGateway::VERSION,
            'server_remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '',
        ];
    }
}