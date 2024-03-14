<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Service;

class AccountLinkService extends \StripeWPFS\Service\AbstractService
{
    /**
     * Creates an AccountLink object that includes a single-use Stripe URL that the
     * platform can redirect their user to in order to take them through the Connect
     * Onboarding flow.
     *
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\AccountLink
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/account_links', $params, $opts);
    }
}
