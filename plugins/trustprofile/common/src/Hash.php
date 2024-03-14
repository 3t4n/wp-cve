<?php

namespace Valued\WordPress;

class Hash {
    private const ALGORITHM = 'sha512';

    private $webshopId;

    private $apiKey;

    private $data;

    public function __construct($webshopId, $apiKey, array $data) {
        $this->webshopId = $webshopId;
        $this->apiKey = $apiKey;
        $this->data = $data;
        $this->validateKeys();
    }

    public function getHash(): string {
        return hash_hmac(self::ALGORITHM, $this->getHashData(), $this->getKey());
    }

    private function getHashData(): string {
        return http_build_query($this->data);
    }

    private function getKey(): string {
        return "$this->webshopId:$this->apiKey";
    }

    private function validateKeys(): void {
        if (!$this->apiKey || !$this->webshopId) {
            throw new InvalidKeysException();
        }
    }
}
