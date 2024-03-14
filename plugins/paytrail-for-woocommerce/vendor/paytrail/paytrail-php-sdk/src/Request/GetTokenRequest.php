<?php

/**
 * Class GetTokenRequest
 */

namespace Paytrail\SDK\Request;

use Paytrail\SDK\Exception\ValidationException;
use Paytrail\SDK\Util\ObjectPropertyConverter;

/**
 * Class GetTokenRequest
 *
 * @package Paytrail\SDK\Request
 */
class GetTokenRequest implements \JsonSerializable
{
    use ObjectPropertyConverter;

    /** @var string $paytrailTokenizationId */
    protected $checkoutTokenizationId;

    /**
     * Validates properties and throws an exception for invalid values
     *
     * @throws ValidationException
     */
    public function validate()
    {
        $props = $this->convertObjectVarsToDashed();

        if (empty($props['checkout-tokenization-id'])) {
            throw new ValidationException('checkout-tokenization-id is empty');
        }

        return true;
    }

    public function setCheckoutTokenizationId(string $checkoutTokenizationId): GetTokenRequest
    {
        $this->checkoutTokenizationId = $checkoutTokenizationId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutTokenizationId(): string
    {
        return $this->checkoutTokenizationId;
    }

    /**
     * Implements the json serialize method and
     * return all object variables including
     * private/protected properties.
     */
    public function jsonSerialize(): array
    {
        return array_filter($this->convertObjectVarsToDashed(), function ($item) {
            return $item !== null;
        });
    }
}
