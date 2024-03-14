<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:26 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

class Cart
{

    /**
     * Model code
     */
    public static $MODEL = 'cart';
    public static $MODELS = 'carts';

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $item_count;

    /**
     * @var float
     */
    public $subtotal_price;

    /**
     * @var float
     */
    public $total_price;

    /**
     * @var array
     */
    public $items = [];
}
