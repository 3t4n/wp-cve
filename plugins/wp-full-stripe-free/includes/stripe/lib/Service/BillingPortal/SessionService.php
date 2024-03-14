<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Service\BillingPortal;

class SessionService extends \StripeWPFS\Service\AbstractService
{
    /**
     * Creates a session of the customer portal.
     *
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\BillingPortal\Session
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/billing_portal/sessions', $params, $opts);
    }
}
