<?php

namespace WCFM\PaypalMarketplace;

use PayPalHttp\HttpRequest;

class PartnerReferralCreateRequest extends HttpRequest {
    function __construct() {
        parent::__construct("/v2/customer/partner-referrals/", "POST");
        $this->headers["Content-Type"] = "application/json";
    }

    public function payPalPartnerAttributionId($payPalPartnerAttributionId) {
        $this->headers["PayPal-Partner-Attribution-Id"] = $payPalPartnerAttributionId;
    }

    public function prefer($prefer) {
        $this->headers["Prefer"] = $prefer;
    }
}
