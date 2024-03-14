<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Service;

class ExchangeRateService extends \StripeWPFS\Service\AbstractService
{
    /**
     * Returns a list of objects that contain the rates at which foreign currencies are
     * converted to one another. Only shows the currencies for which Stripe supports.
     *
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\Collection<\StripeWPFS\ExchangeRate>
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/exchange_rates', $params, $opts);
    }

    /**
     * Retrieves the exchange rates from the given currency to every supported
     * currency.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\ExchangeRate
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/exchange_rates/%s', $id), $params, $opts);
    }
}
