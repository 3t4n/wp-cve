<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\XmlApi;

use DhlVendor\DHL\Client\Web;
use DhlVendor\DHL\Entity\AM\GetQuote;
use DhlVendor\DHL\Entity\AM\GetQuoteResponse;
use DhlVendor\Psr\Log\LoggerInterface;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\Sender;
/**
 * Send request to DHL Express API
 *
 * @package WPDesk\DhlExpressShippingService\DhlApi
 */
class XmlApiDhlSender implements \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\Sender
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     * Is testing?
     *
     * @var bool
     */
    private $is_testing;
    /**
     * DhlSender constructor.
     *
     * @param LoggerInterface $logger Logger.
     * @param bool $is_testing Is testing?.
     */
    public function __construct(\DhlVendor\Psr\Log\LoggerInterface $logger, $is_testing = \true)
    {
        $this->logger = $logger;
        $this->is_testing = $is_testing;
    }
    /**
     * Send request.
     *
     * @param GetQuote $request DHL request.
     *
     * @return GetQuoteResponse
     *
     * @throws \Exception
     */
    public function send($request)
    {
        $mode = 'production';
        if ($this->is_testing) {
            $mode = 'staging';
        }
        $this->logger->info('API request', ['content' => $request->toXML(), 'mode' => $mode]);
        $client = new \DhlVendor\DHL\Client\Web($mode);
        $xml_response = $client->call($request);
        $this->logger->info('API response', ['content' => $xml_response]);
        $response = new \DhlVendor\DHL\Entity\AM\GetQuoteResponse();
        $response->initFromXML($xml_response);
        return $response;
    }
}
