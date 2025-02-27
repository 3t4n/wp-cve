<?php

namespace UpsFreeVendor\Ups;

use DateTime;
use DOMDocument;
use Exception;
use UpsFreeVendor\GuzzleHttp\Client as Guzzle;
use UpsFreeVendor\Psr\Log\LoggerAwareInterface;
use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\Psr\Log\NullLogger;
use SimpleXMLElement;
use UpsFreeVendor\Ups\Exception\InvalidResponseException;
use UpsFreeVendor\Ups\Exception\RequestException;
class Request implements \UpsFreeVendor\Ups\RequestInterface, \UpsFreeVendor\Psr\Log\LoggerAwareInterface
{
    /**
     * @var string
     */
    protected $access;
    /**
     * @var string
     */
    protected $request;
    /**
     * @var string
     */
    protected $endpointUrl;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Guzzle
     */
    protected $client;
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(\UpsFreeVendor\Psr\Log\LoggerInterface $logger = null)
    {
        if ($logger !== null) {
            $this->setLogger($logger);
        } else {
            $this->setLogger(new \UpsFreeVendor\Psr\Log\NullLogger());
        }
        $this->setClient();
    }
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(\UpsFreeVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Creates a single instance of the Guzzle client
     *
     * @return null
     */
    public function setClient()
    {
        $this->client = new \UpsFreeVendor\GuzzleHttp\Client();
    }
    /**
     * Send request to UPS.
     *
     * @param string $access The access request xml
     * @param string $request The request xml
     * @param string $endpointurl The UPS API Endpoint URL
     *
     * @throws Exception
     *                   todo: make access, request and endpointurl nullable to make the testable
     *
     * @return ResponseInterface
     */
    public function request($access, $request, $endpointurl)
    {
        $this->setAccess($access);
        $this->setRequest($request);
        $this->setEndpointUrl($endpointurl);
        // Log request
        $date = new \DateTime();
        $id = $date->format('YmdHisu');
        $this->logger->debug('Request to UPS API', ['content' => $this->getRequest(), 'id' => $id, 'endpointurl' => $this->getEndpointUrl()]);
        try {
            $response = $this->client->post($this->getEndpointUrl(), ['body' => $this->getAccess() . $this->getRequest(), 'headers' => ['Content-type' => 'application/x-www-form-urlencoded; charset=utf-8', 'Accept-Charset' => 'UTF-8'], 'http_errors' => \true]);
            $body = (string) $response->getBody();
            $content = $body;
            if ($response->getStatusCode() === 200 && !empty($content)) {
                $content = $this->formatXml($this->convertEncoding($content));
            }
            $this->logger->debug('Response from UPS API', ['content' => $content, 'id' => $id, 'endpointurl' => $this->getEndpointUrl()]);
            if ($response->getStatusCode() === 200) {
                $body = $this->convertEncoding($body);
                if ('' === \trim($body)) {
                    throw new \UpsFreeVendor\Ups\Exception\InvalidResponseException('Failure: response is an empty string.');
                } else {
                    $xml = new \SimpleXMLElement($body);
                    if (isset($xml->Response) && isset($xml->Response->ResponseStatusCode)) {
                        if ($xml->Response->ResponseStatusCode == 1) {
                            $responseInstance = new \UpsFreeVendor\Ups\Response();
                            return $responseInstance->setText($body)->setResponse($xml);
                        } elseif ($xml->Response->ResponseStatusCode == 0) {
                            $code = (int) $xml->Response->Error->ErrorCode;
                            throw new \UpsFreeVendor\Ups\Exception\InvalidResponseException('Failure: ' . $xml->Response->Error->ErrorDescription . ' (' . $xml->Response->Error->ErrorCode . ')', $code);
                        }
                    } else {
                        throw new \UpsFreeVendor\Ups\Exception\InvalidResponseException('Failure: response is in an unexpected format.');
                    }
                }
            }
        } catch (\UpsFreeVendor\GuzzleHttp\Exception\TransferException $e) {
            // Guzzle: All of the exceptions extend from GuzzleHttp\Exception\TransferException
            $this->logger->alert($e->getMessage(), ['id' => $id, 'endpointurl' => $this->getEndpointUrl()]);
            throw new \UpsFreeVendor\Ups\Exception\RequestException('Failure: ' . $e->getMessage());
        }
    }
    /**
     * Formats XML.
     *
     * @param string $xml .
     *
     * @return string
     */
    private function formatXml($xml)
    {
        $xmlDocument = new \DOMDocument('1.0');
        $xmlDocument->preserveWhiteSpace = \false;
        $xmlDocument->formatOutput = \true;
        $xmlDocument->loadXML($xml);
        return $xmlDocument->saveXML();
    }
    /**
     * @param $access
     *
     * @return $this
     */
    public function setAccess($access)
    {
        $this->access = $access;
        return $this;
    }
    /**
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }
    /**
     * @param $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }
    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * @param $endpointUrl
     *
     * @return $this
     */
    public function setEndpointUrl($endpointUrl)
    {
        $this->endpointUrl = $endpointUrl;
        return $this;
    }
    /**
     * @return string
     */
    public function getEndpointUrl()
    {
        return $this->endpointUrl;
    }
    /**
     * @param $body
     * @return string
     */
    protected function convertEncoding($body)
    {
        if (!\function_exists('mb_convert_encoding')) {
            return $body;
        }
        $encoding = \mb_detect_encoding($body);
        if ($encoding) {
            return \mb_convert_encoding($body, 'UTF-8', $encoding);
        }
        return \utf8_encode($body);
    }
}
