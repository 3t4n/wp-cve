<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wc_Product_Aware_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wp_Post_Id_Aware_Interface;
use WC_Product;
class Wc_Product_Update extends Abstract_Event implements Wc_Product_Aware_Interface, Wp_Post_Id_Aware_Interface
{
    /**
     * @var int
     */
    private $post_id;
    /**
     * @var WC_Product
     */
    private $product;
    public function get_post_id() : int
    {
        return $this->post_id;
    }
    public function get_product() : WC_Product
    {
        return $this->product;
    }
    public function create()
    {
        add_action('woocommerce_update_product', function (int $post_id, WC_Product $product) {
            $this->post_id = $post_id;
            $this->product = $product;
            $this->callback();
        }, 3, 1000);
    }
}
