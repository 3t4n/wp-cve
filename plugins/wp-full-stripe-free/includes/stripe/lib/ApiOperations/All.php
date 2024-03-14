<?php

namespace StripeWPFS\ApiOperations;

/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait All
{
    /**
     * @param null|array $params
     * @param null|array|string $opts
     *
     * @throws \StripeWPFS\Exception\ApiErrorException if the request fails
     *
     * @return \StripeWPFS\Collection of ApiResources
     */
    public static function all($params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = \StripeWPFS\Util\Util::convertToStripeObject($response->json, $opts);
        if (!($obj instanceof \StripeWPFS\Collection)) {
            throw new \StripeWPFS\Exception\UnexpectedValueException(
                'Expected type ' . \StripeWPFS\Collection::class . ', got "' . \get_class($obj) . '" instead.'
            );
        }
        $obj->setLastResponse($response);
        $obj->setFilters($params);

        return $obj;
    }
}
