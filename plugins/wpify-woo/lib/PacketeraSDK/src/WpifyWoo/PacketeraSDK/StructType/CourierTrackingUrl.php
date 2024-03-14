<?php

declare(strict_types=1);

namespace WpifyWoo\PacketeraSDK\StructType;

use InvalidArgumentException;
use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CourierTrackingUrl StructType
 * @subpackage Structs
 */
class CourierTrackingUrl extends AbstractStructBase
{
    /**
     * The lang
     * @var string|null
     */
    protected ?string $lang = null;
    /**
     * The url
     * @var string|null
     */
    protected ?string $url = null;
    /**
     * Constructor method for CourierTrackingUrl
     * @uses CourierTrackingUrl::setLang()
     * @uses CourierTrackingUrl::setUrl()
     * @param string $lang
     * @param string $url
     */
    public function __construct(?string $lang = null, ?string $url = null)
    {
        $this
            ->setLang($lang)
            ->setUrl($url);
    }
    /**
     * Get lang value
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->lang;
    }
    /**
     * Set lang value
     * @param string $lang
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl
     */
    public function setLang(?string $lang = null): self
    {
        // validation for constraint: string
        if (!is_null($lang) && !is_string($lang)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lang, true), gettype($lang)), __LINE__);
        }
        $this->lang = $lang;
        
        return $this;
    }
    /**
     * Get url value
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
    /**
     * Set url value
     * @param string $url
     * @return \WpifyWoo\PacketeraSDK\StructType\CourierTrackingUrl
     */
    public function setUrl(?string $url = null): self
    {
        // validation for constraint: string
        if (!is_null($url) && !is_string($url)) {
            throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($url, true), gettype($url)), __LINE__);
        }
        $this->url = $url;
        
        return $this;
    }
}
