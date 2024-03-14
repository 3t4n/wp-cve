<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wc_Cart_Aware_Interface;
use WC_Cart;
class Wc_Before_Checkout_Form extends Abstract_Event implements Wc_Cart_Aware_Interface
{
    /**
     * @var WC_Cart
     */
    private $cart;
    public function create()
    {
        add_action('woocommerce_before_checkout_form', function () {
            $this->cart = WC()->cart;
            $this->callback();
        }, 100, 3);
    }
    public function get_cart() : WC_Cart
    {
        return $this->cart;
    }
}
