<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

use WC_Product_Variation;
interface Wc_Variation_Aware_Interface
{
    public function get_variation() : WC_Product_Variation;
}
