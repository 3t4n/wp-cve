<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Shop
{

    /**
     * Model code
     */
    public static $MODEL = 'shop';
    public static $MODELS = 'shops';

    /**
     * @var string
     */
    public $domain;

    /**
     * @var string
     */
    public $absolute_path;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $currency_format;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $plugin_version;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $country;
}
