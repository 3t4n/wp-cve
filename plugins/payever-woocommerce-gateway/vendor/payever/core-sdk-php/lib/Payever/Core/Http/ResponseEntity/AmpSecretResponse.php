<?php

namespace Payever\Sdk\Core\Http\ResponseEntity;

use Payever\Sdk\Core\Http\ResponseEntity;

class AmpSecretResponse extends ResponseEntity
{
    /** @var null|string */
    protected $apmSecret;

    /**
     * @return string|null
     */
    public function getApmSecret()
    {
        return $this->apmSecret;
    }

    /**
     * @param $secret
     * @return $this
     */
    public function setApmSecret($secret)
    {
        $this->apmSecret = $secret;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRequired()
    {
        return [
            'apmSecret'
        ];
    }
}