<?php

namespace Payever\Sdk\Core\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * Class ApmSecretRequest
 */
class ApmSecretRequest extends RequestEntity
{
    /** @var null|string $clientId */
    protected $clientId;

    /** @var null|string $clientSecret*/
    protected $clientSecret;

    /**
     * @return string|null
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string|null
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param $clientId
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @param $clientSecret
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function toArray($object = null)
    {
        return [
            'clientId'     => $this->getClientId(),
            'clientSecret' => $this->getClientSecret(),
        ];
    }
}
