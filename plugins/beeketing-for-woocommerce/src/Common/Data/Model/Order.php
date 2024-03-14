<?php
/**
 * Created by PhpStorm.
 * User: quaninte
 * Date: 8/14/18
 * Time: 11:54 AM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Order
{

    /**
     * Model code
     */
    public static $MODEL = 'order';
    public static $MODELS = 'orders';

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
    public $financial_status;

    /**
     * @var string
     */
    public $fulfillment_status;

    /**
     * @var array
     */
    public $line_items = [];

    /**
     * @var string
     */
    public $cart_token;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $total_tax;

    /**
     * @var float
     */
    public $total_discounts;

    /**
     * @var float
     */
    public $total_price;

    /**
     * @var float
     */
    public $subtotal_price;

    /**
     * @var float
     */
    public $total_line_items_price;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var string
     */
    public $processed_at;

    /**
     * @var string
     */
    public $cancelled_at;

    /**
     * @var array
     */
    public $note_attributes = [];

    /**
     * @var string
     */
    public $source_name;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var array
     */
    public $billing_address = [];
}
