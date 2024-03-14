<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia;

use WPPayVendor\BlueMedia\Common\Enum\ClientEnum;
use InvalidArgumentException;
class Configuration
{
    /**
     * @var string
     */
    private $serviceId;
    /**
     * @var string
     */
    private $sharedKey;
    /**
     * @var string
     */
    private $hashAlgo;
    /**
     * @var string
     */
    private $hashSeparator;
    /**
     * @param string $serviceId
     * @param string $sharedKey
     * @param string $hashAlgo
     * @param string $hashSeparator
     */
    public function __construct(string $serviceId, string $sharedKey, string $hashAlgo, string $hashSeparator)
    {
        if (!\in_array($hashAlgo, \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::HASH_TYPES)) {
            throw new \InvalidArgumentException(\sprintf('Not supported hash algorithm "%s"', $hashAlgo));
        }
        if (\mb_strlen($serviceId) > 10) {
            throw new \InvalidArgumentException(\sprintf('Service ID is too long, should be max %s chars.', 10));
        }
        $this->serviceId = $serviceId;
        $this->sharedKey = $sharedKey;
        $this->hashAlgo = $hashAlgo;
        $this->hashSeparator = $hashSeparator;
    }
    /**
     * @return string
     */
    public function getServiceId() : string
    {
        return $this->serviceId;
    }
    /**
     * @return string
     */
    public function getSharedKey() : string
    {
        return $this->sharedKey;
    }
    /**
     * @return string
     */
    public function getHashAlgo() : string
    {
        return $this->hashAlgo;
    }
    /**
     * @return string
     */
    public function getHashSeparator() : string
    {
        return $this->hashSeparator;
    }
}
