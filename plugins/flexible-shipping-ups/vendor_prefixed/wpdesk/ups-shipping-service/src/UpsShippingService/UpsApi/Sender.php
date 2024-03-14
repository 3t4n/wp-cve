<?php

/**
 * UPS API: Sender interface.
 *
 * @package WPDesk\UpsShippingService\UpsApi
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService\UpsApi;

use UpsFreeVendor\Ups\Entity\RateRequest;
use UpsFreeVendor\Ups\Entity\RateResponse;
/**
 * Sender class interface.
 */
interface Sender
{
    /**
     * Send request.
     *
     * @param RateRequest $request $request Request.
     *
     * @return RateResponse
     */
    public function send(\UpsFreeVendor\Ups\Entity\RateRequest $request);
}
