<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Customer
{

    /**
     * Model code
     */
    public static $MODEL = 'customer';
    public static $MODELS = 'customers';

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var boolean
     */
    public $accepts_marketing;

    /**
     * @var boolean
     */
    public $verified_email;

    /**
     * @var string
     */
    public $signed_up_at;

    /**
     * @var string
     */
    public $address1;

    /**
     * @var string
     */
    public $address2;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $company;

    /**
     * @var string
     */
    public $province;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $country_code;

    /**
     * @var int
     */
    public $orders_count;

    /**
     * @var float
     */
    public $total_spent;
}
