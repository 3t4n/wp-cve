<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wc_Product_Aware_Interface;
use WC_Cart;
use WC_Product;
class Wc_Remove_Cart_Item extends Abstract_Event implements Wc_Product_Aware_Interface
{
    /**
     * @var WC_Product
     */
    private $product;
    /**
     * @var int
     */
    private $quantity;
    public function create()
    {
        add_action('woocommerce_remove_cart_item', function ($cart_item_key, WC_Cart $cart) {
            $product_id = $cart->cart_contents[$cart_item_key]['product_id'];
            $this->product = wc_get_product($product_id);
            $this->quantity = $cart->cart_contents[$cart_item_key]['quantity'];
            $this->callback();
        }, 100, 2);
    }
    /**
     * @return WC_Product
     */
    public function get_product() : WC_Product
    {
        return $this->product;
    }
    /**
     * @return int
     */
    public function get_quantity() : int
    {
        return $this->quantity;
    }
}
