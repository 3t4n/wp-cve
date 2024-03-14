<?php

namespace Avecdo\SDK\POPO;

class KeySet
{
    private $publicKey;
    private $privateKey;

    public static function fromActivationKey($activationKey)
    {
        if (!strpos($activationKey, ';')) {
            return null;
        }

        list($publicKey, $privateKey) = explode(';', $activationKey);

        if(empty($privateKey)) {
            return null;
        }

        return new static($publicKey, $privateKey);
    }

    public function asString()
    {
        if(empty($this->publicKey) || empty($this->privateKey)) {
            return '';
        }

        return $this->publicKey . ';' . $this->privateKey;
    }

    public function __construct($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
