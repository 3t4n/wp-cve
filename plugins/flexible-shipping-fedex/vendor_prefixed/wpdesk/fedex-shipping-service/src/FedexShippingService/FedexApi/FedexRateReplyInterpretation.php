<?php

namespace FedExVendor\WPDesk\FedexShippingService\FedexApi;

use FedExVendor\FedEx\RateService\ComplexType\RatedShipmentDetail;
use FedExVendor\FedEx\RateService\ComplexType\RateReply;
use FedExVendor\FedEx\RateService\ComplexType\RateReplyDetail;
use FedExVendor\FedEx\RateService\SimpleType\RateTypeBasisType;
use FedExVendor\WPDesk\AbstractShipping\Rate\Money;
use FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use FedExVendor\WPDesk\AbstractShipping\Rate\SingleRate;
/**
 * Get response from API
 *
 * @package WPDesk\FedexShippingService\FedexApi
 */
class FedexRateReplyInterpretation implements \FedExVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
{
    /**
     * Is tax enabled.
     *
     * @var bool
     */
    private $is_tax_enabled;
    /**
     * Reply.
     *
     * @var RateReply
     */
    private $reply;
    /**
     * Setting value of FedexSettingsDefinition::FIELD_REQUEST_TYPE
     *
     * @var string
     */
    private $rate_type;
    /**
     * FedexResponse constructor.
     *
     * @param RateReply $reply Rate reply.
     * @param bool $is_tax_enabled Is tax enabled.
     * @param string $rate_type Setting value of FedexSettingsDefinition::FIELD_REQUEST_TYPE
     */
    public function __construct(\FedExVendor\FedEx\RateService\ComplexType\RateReply $reply, $is_tax_enabled, $rate_type)
    {
        $this->reply = $reply;
        $this->is_tax_enabled = $is_tax_enabled;
        $this->rate_type = $rate_type;
    }
    /**
     * Has reply error.
     *
     * @param RateReply $reply Rate reply.
     *
     * @return bool
     */
    public static function has_reply_error(\FedExVendor\FedEx\RateService\ComplexType\RateReply $reply)
    {
        try {
            return 'ERROR' === $reply->HighestSeverity || 'FAILURE' === $reply->HighestSeverity;
        } catch (\Throwable $e) {
            return \true;
        } catch (\Exception $e) {
            // required fallback from Throwable in PHP 5.6
            return \true;
        }
    }
    /**
     * Get reply error message.
     *
     * @param RateReply $reply Rate reply.
     *
     * @return mixed|string
     */
    public static function get_reply_message(\FedExVendor\FedEx\RateService\ComplexType\RateReply $reply)
    {
        try {
            $notification = $reply->Notifications[0];
            if (\is_string($notification)) {
                return $notification;
            }
            return $notification->Message;
        } catch (\Throwable $e) {
            return '';
        } catch (\Exception $e) {
            // required fallback from Throwable in PHP 5.6
            return '';
        }
    }
    /**
     * Has reply warning.
     *
     * @param RateReply $reply Rate reply.
     *
     * @return bool
     */
    public static function has_reply_warning(\FedExVendor\FedEx\RateService\ComplexType\RateReply $reply)
    {
        return 'WARNING' === $reply->HighestSeverity;
    }
    /**
     * Get single rate.
     *
     * @param RatedShipmentDetail $rated_shipment_detail .
     * @param RateReplyDetail $reply_detail .
     *
     * @return SingleRate
     */
    protected function get_single_rate(\FedExVendor\FedEx\RateService\ComplexType\RatedShipmentDetail $rated_shipment_detail, \FedExVendor\FedEx\RateService\ComplexType\RateReplyDetail $reply_detail)
    {
        $rate = new \FedExVendor\WPDesk\AbstractShipping\Rate\SingleRate();
        $money = new \FedExVendor\WPDesk\AbstractShipping\Rate\Money();
        if ($this->is_tax_enabled) {
            $money->currency = \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRequestManipulation::convert_currency_from_fedex($rated_shipment_detail->ShipmentRateDetail->TotalNetFedExCharge->Currency);
            $money->amount = $rated_shipment_detail->ShipmentRateDetail->TotalNetFedExCharge->Amount;
        } else {
            $money->currency = \FedExVendor\WPDesk\FedexShippingService\FedexApi\FedexRequestManipulation::convert_currency_from_fedex($rated_shipment_detail->ShipmentRateDetail->TotalNetCharge->Currency);
            $money->amount = $rated_shipment_detail->ShipmentRateDetail->TotalNetCharge->Amount;
        }
        $rate->total_charge = $money;
        $rate->service_type = $reply_detail->ServiceType;
        $rate->service_name = $reply_detail->ServiceDescription->Description;
        return $rate;
    }
    /**
     * @return RateReply
     */
    public function get_reply()
    {
        return $this->reply;
    }
    /**
     * Get response from FedEx.
     *
     * @return SingleRate[]
     */
    public function get_ratings()
    {
        $rates = [];
        $reply = $this->get_reply();
        if (!empty($reply->RateReplyDetails)) {
            //phpcs:ignore
            foreach ($reply->RateReplyDetails as $reply_detail) {
                if (!empty($reply_detail->RatedShipmentDetails)) {
                    foreach ($reply_detail->RatedShipmentDetails as $rated_shipment_detail) {
                        if ($this->rate_type === \FedExVendor\FedEx\RateService\SimpleType\RateTypeBasisType::_LIST && \false === \strpos($rated_shipment_detail->ShipmentRateDetail->RateType, 'PAYOR_LIST')) {
                            continue;
                        }
                        if (!empty($reply_detail->ServiceType)) {
                            $rates[] = $this->get_single_rate($rated_shipment_detail, $reply_detail);
                        }
                    }
                }
            }
        }
        return $rates;
    }
}
