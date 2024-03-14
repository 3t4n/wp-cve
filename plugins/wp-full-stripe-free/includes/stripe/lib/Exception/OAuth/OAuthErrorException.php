<?php

namespace StripeWPFS\Exception\OAuth;

/**
 * Implements properties and methods common to all (non-SPL) Stripe OAuth
 * exceptions.
 */
abstract class OAuthErrorException extends \StripeWPFS\Exception\ApiErrorException
{
    protected function constructErrorObject()
    {
        if (null === $this->jsonBody) {
            return null;
        }

        return \StripeWPFS\OAuthErrorObject::constructFrom($this->jsonBody);
    }
}
