<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

use WC_Product;
interface Wc_Product_Aware_Interface
{
    public function get_product() : WC_Product;
}
