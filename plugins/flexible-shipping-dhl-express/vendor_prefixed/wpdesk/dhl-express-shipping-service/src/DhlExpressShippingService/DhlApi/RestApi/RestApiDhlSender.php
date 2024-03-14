<?php

namespace DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\RestApi;

use DhlVendor\DHL\Client\Web;
use DhlVendor\DHL\Entity\AM\GetQuote;
use DhlVendor\DHL\Entity\AM\GetQuoteResponse;
use DhlVendor\Octolize\DhlExpress\RestApi\MyDHL;
use DhlVendor\Octolize\DhlExpress\RestApi\Services\RateService;
use DhlVendor\Psr\Log\LoggerInterface;
use DhlVendor\WPDesk\AbstractShipping\Exception\RateException;
use DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\Sender;
/**
 * Send request to DHL Express API
 *
 * @package WPDesk\DhlExpressShippingService\DhlApi
 */
class RestApiDhlSender implements \DhlVendor\WPDesk\DhlExpressShippingService\DhlApi\Sender
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
    private bool $is_testing;
    private string $api_key;
    private string $api_secret;
    /**
     * DhlSender constructor.
     *
     * @param LoggerInterface $logger Logger.
     * @param bool $is_testing Is testing?.
     */
    public function __construct(\DhlVendor\Psr\Log\LoggerInterface $logger, string $api_key, string $api_secret, bool $is_testing = \true)
    {
        $this->logger = $logger;
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->is_testing = $is_testing;
    }
    /**
     * Send request.
     *
     * @param RateService $request DHL request.
     *
     * @return array
     *
     * @throws \Exception
     */
    public function send($request)
    {
        $mydhl = new \DhlVendor\Octolize\DhlExpress\RestApi\MyDHL($this->api_key, $this->api_secret, $this->is_testing);
        $this->logger->info('API request', ['content' => \json_encode($request->prepareQuery(), \JSON_PRETTY_PRINT), 'is_testing' => \json_encode($this->is_testing)]);
        try {
            $request->setClient($mydhl->getClient());
            $rates = $request->getRates();
        } catch (\Exception $e) {
            throw new \DhlVendor\WPDesk\AbstractShipping\Exception\RateException($e->getMessage(), [], $e->getCode(), $e);
        }
        $this->logger->info('API response', ['content' => \json_encode($request->getLastRawResponse(), \JSON_PRETTY_PRINT)]);
        return $rates;
    }
}
