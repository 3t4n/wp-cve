<?php

// File generated from our OpenAPI spec

namespace StripeWPFS\Service;

class ShippingRateService extends \StripeWPFS\Service\AbstractService
{
    /**
     * Returns a list of your shipping rates.
     *
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\Collection<\StripeWPFS\ShippingRate>
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/shipping_rates', $params, $opts);
    }

    /**
     * Creates a new shipping rate object.
     *
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\ShippingRate
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/shipping_rates', $params, $opts);
    }

    /**
     * Returns the shipping rate object with the given ID.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\ShippingRate
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/shipping_rates/%s', $id), $params, $opts);
    }

    /**
     * Updates an existing shipping rate object.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\StripeWPFS\Util\RequestOptions $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\ShippingRate
     */
    public function update($id, $params = null, $opts = null)
    {
        return $this->request('post', $this->buildPath('/v1/shipping_rates/%s', $id), $params, $opts);
    }
}
