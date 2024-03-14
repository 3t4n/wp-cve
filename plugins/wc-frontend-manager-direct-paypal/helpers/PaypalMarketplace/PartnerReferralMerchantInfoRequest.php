<?php

namespace WCFM\PaypalMarketplace;

use PayPalHttp\HttpRequest;

class PartnerReferralMerchantInfoRequest extends HttpRequest {
    function __construct( $partner_id, $merchant_id ) {
        parent::__construct("/v1/customer/partners/{$partner_id}/merchant-integrations/{$merchant_id}", "GET");
        $this->headers["Content-Type"] = "application/json";
    }
}
