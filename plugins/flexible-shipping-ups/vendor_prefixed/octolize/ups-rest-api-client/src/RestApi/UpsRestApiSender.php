<?php

namespace UpsFreeVendor\Octolize\Ups\RestApi;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\RateResponse;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateReplyInterpretation;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSender;
/**
 * Send request to UPS REST API
 */
class UpsRestApiSender extends \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSender
{
    /**
     * Token.
     *
     * @var RestApiClient
     */
    private $rest_api_client;
    /**
     * Is tax enabled.
     *
     * @var bool
     */
    private $is_tax_enabled;
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
     * UpsSender constructor.
     *
     * @param RestApiClient $client .
     * @param LoggerInterface $logger Logger.
     * @param bool $is_testing Is testing?.
     * @param bool $is_tax_enabled Is tax enabled?.
     */
    public function __construct($client, \UpsFreeVendor\Psr\Log\LoggerInterface $logger, $is_testing = \false, $is_tax_enabled = \true)
    {
        $this->rest_api_client = $client;
        $this->logger = $logger;
        $this->is_testing = $is_testing;
        $this->is_tax_enabled = $is_tax_enabled;
    }
    /**
     * Send request.
     *
     * @param RateRequest $request UPS request.
     *
     * @return RateResponse
     *
     * @throws \Exception .
     * @throws RateException .
     */
    public function send(\UpsFreeVendor\Ups\Entity\RateRequest $request)
    {
        $rate = $this->create_rate();
        try {
            $reply = $rate->shopRates($request);
        } catch (\RuntimeException $e) {
            throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException($e->getMessage(), ['exception' => $e->getMessage()]);
            //phpcs:ignore
        }
        $rate_interpretation = new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateReplyInterpretation($reply, $this->is_tax_enabled);
        if ($rate_interpretation->has_reply_error()) {
            throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException($rate_interpretation->get_reply_message(), ['response' => $reply]);
            //phpcs:ignore
        }
        return $reply;
    }
    /**
     * @return \Octolize\Ups\RestApi\Rate
     */
    protected function create_rate()
    {
        return new \UpsFreeVendor\Octolize\Ups\RestApi\Rate($this->rest_api_client, $this->logger, $this->is_testing, $this->is_tax_enabled);
    }
}
