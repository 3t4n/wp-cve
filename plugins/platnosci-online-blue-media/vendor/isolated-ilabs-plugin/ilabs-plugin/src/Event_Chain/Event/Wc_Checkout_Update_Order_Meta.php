<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wc_Order_Aware_Interface;
use WC_Order;
class Wc_Checkout_Update_Order_Meta extends Abstract_Event implements Wc_Order_Aware_Interface
{
    /**
     * @var WC_Order
     */
    private $order;
    public function create()
    {
        add_action('woocommerce_checkout_update_order_meta', function ($order_id, $posted) {
            $this->order = wc_get_order($order_id);
            $this->callback();
        }, 100, 2);
    }
    public function get_order() : WC_Order
    {
        return $this->order;
    }
}
