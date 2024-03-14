<?php

namespace Avecdo\SDK\POPO;

use Avecdo\SDK\POPO\Shop\ShopExtras;
use Avecdo\SDK\Constants;

/**
 * Class Shop
 * @package AvecdoSDK\POPO
 */
class Shop
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $url = null;

    /**
     * @var string
     */
    protected $image = null;

    /**
     * @var string
     */
    protected $owner = null;

    /**
     * @var string
     */
    protected $address = null;

    /**
     * @var string
     */
    protected $email = null;

    /**
     * @var string
     */
    protected $phone = null;

    /**
     * @var string
     */
    protected $country = null;

    /**
     * @var string
     */
    protected $primaryLanguage = null;

    /**
     * @var string
     */
    protected $primaryCurrency = null;

    /**
     * Contains the current SDK version installed on the shop
     *
     * @var string
     */
    protected $sdkVersion = null;

    /**
     * @var string
     */
    protected $shopSystem = null;

    /**
     * @var string
     */
    protected $shopSystemVersion = null;

    /**
     * @var string
     */
    protected $pluginVersion = null;

    /**
     * @var array
     */
    protected $extras = array();


    public function __construct()
    {
        $this->sdkVersion = Constants::SDK_VERSION;

        $phpVersion = phpversion();

        if(!empty($phpVersion)) {
            $this->addToExtras(ShopExtras::PHP_VERSION, $phpVersion);
        }

        if(isset($_SERVER['SERVER_SOFTWARE'])) {
            $this->addToExtras(ShopExtras::SERVER_SOFTWARE, $_SERVER['SERVER_SOFTWARE']);
        }
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return get_object_vars($this);
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $image
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param $owner
     * @return $this
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @param $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param $primaryLanguage
     * @return $this
     */
    public function setPrimaryLanguage($primaryLanguage)
    {
        $this->primaryLanguage = $primaryLanguage;

        return $this;
    }

    /**
     * @param $primaryCurrency
     * @return $this
     */
    public function setPrimaryCurrency($primaryCurrency)
    {
        $this->primaryCurrency = $primaryCurrency;

        return $this;
    }

    /**
     * Contains the current plugin version
     *
     * @param $pluginVersion
     * @return $this
     */
    public function setPluginVersion($pluginVersion)
    {
        $this->pluginVersion = $pluginVersion;

        return $this;
    }

    /**
     * Contains the webshop system, e.g. WooCommerce
     * Should be of Shop\ShopSystem type
     *
     * @param $shopSystem
     * @return $this
     */
    public function setShopSystem($shopSystem)
    {
        $this->shopSystem = $shopSystem;

        return $this;
    }

    /**
     * Contains the version of the installed webshop system
     *
     * @param $shopSystemVersion
     * @return $this
     */
    public function setShopSystemVersion($shopSystemVersion)
    {
        $this->shopSystemVersion = $shopSystemVersion;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addToExtras($key, $value)
    {
        array_push($this->extras, array(
            'key'   => $key,
            'value' => $value
        ));

        return $this;
    }
}
