<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Filterable_String_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Wc_Product_Aware_Interface;
use WC_Product;
class Wc_Filter_Price_Html extends Abstract_Event implements Filterable_String_Interface, Wc_Product_Aware_Interface
{
    /**
     * @var string
     */
    private $filterable_value;
    /**
     * @var string
     */
    private $filtered_value;
    /**
     * @var WC_Product
     */
    private $product;
    /**
     * @return WC_Product
     */
    public function get_product() : WC_Product
    {
        return $this->product;
    }
    /**
     * @return string
     */
    public function get_filterable_value() : string
    {
        return $this->filterable_value;
    }
    public function filter(string $value)
    {
        $this->filtered_value = $value;
    }
    public function create()
    {
        add_filter('woocommerce_get_price_html', function (string $price, WC_Product $product) {
            $this->product = $product;
            $this->filterable_value = $price;
            $this->callback();
            return $this->filtered_value;
        }, 10, 2);
    }
}
