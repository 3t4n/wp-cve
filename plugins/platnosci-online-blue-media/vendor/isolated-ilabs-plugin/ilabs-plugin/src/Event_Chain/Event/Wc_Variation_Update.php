<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wc_Variation_Aware_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wp_Post_Id_Aware_Interface;
use WC_Product_Variation;
class Wc_Variation_Update extends Abstract_Event implements Wc_Variation_Aware_Interface, Wp_Post_Id_Aware_Interface
{
    /**
     * @var WC_Product_Variation
     */
    private $variation;
    /**
     * @var int
     */
    private $post_id;
    /**
     * @return WC_Product_Variation
     */
    public function get_variation() : WC_Product_Variation
    {
        return $this->variation;
    }
    /**
     * @return int
     */
    public function get_post_id() : int
    {
        return $this->post_id;
    }
    public function create()
    {
        add_action('woocommerce_update_product_variation', function (int $post_id, WC_Product_Variation $variation) {
            $this->post_id = $post_id;
            $this->variation = $variation;
            $this->callback();
        }, 3, 10);
    }
}
