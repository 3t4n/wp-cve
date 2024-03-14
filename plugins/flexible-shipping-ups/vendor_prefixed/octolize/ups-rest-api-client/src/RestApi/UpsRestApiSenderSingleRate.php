<?php

namespace UpsFreeVendor\Octolize\Ups\RestApi;

use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\RateResponse;
use UpsFreeVendor\Ups\Exception\InvalidResponseException;
use UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateReplyInterpretation;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSenderSingleRate;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsServices;
class UpsRestApiSenderSingleRate extends \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsSenderSingleRate
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
     * @var mixed
     */
    private $service_code;
    /**
     * UpsSender constructor.
     *
     * @param RestApiClient   $client .
     * @param LoggerInterface $logger Logger.
     * @param bool            $is_testing Is testing?.
     * @param bool            $is_tax_enabled Is tax enabled?.
     */
    public function __construct($client, $service_code, \UpsFreeVendor\Psr\Log\LoggerInterface $logger, $is_testing = \false, $is_tax_enabled = \true)
    {
        $this->rest_api_client = $client;
        $this->logger = $logger;
        $this->is_testing = $is_testing;
        $this->is_tax_enabled = $is_tax_enabled;
        $this->service_code = $service_code;
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
            $request->getShipment()->getService()->setCode($this->service_code);
            if (\UpsFreeVendor\WPDesk\UpsShippingService\UpsServices::SUREPOST_LESS_THAN_1_LB === $this->service_code) {
                $this->convert_weight_to_ozs($request);
            }
            $reply = $rate->getRate($request);
        } catch (\UpsFreeVendor\Ups\Exception\InvalidResponseException $e) {
            throw new \UpsFreeVendor\WPDesk\AbstractShipping\Exception\RateException($e->getMessage(), ['exception' => $e->getCode()]);
            //phpcs:ignore
        }
        $rate_interpretation = new \UpsFreeVendor\WPDesk\UpsShippingService\UpsApi\UpsRateReplyInterpretation($reply, $this->is_tax_enabled());
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
