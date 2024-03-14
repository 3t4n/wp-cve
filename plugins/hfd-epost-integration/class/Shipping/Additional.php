<?php
/**
 * Created by PhpStorm.
 * Date: 6/5/18
 * Time: 4:04 PM
 */
namespace Hfd\Woocommerce\Shipping;

use Hfd\Woocommerce\Container;
use Hfd\Woocommerce\Template;

class Additional extends Template
{
    /**
     * @return string
     */
    public function render()
    {
        $setting = Container::get('Hfd\Woocommerce\Setting');
        $cartPickup = Container::get('Hfd\Woocommerce\Cart\Pickup');
        $layout = $setting->get('betanet_epost_layout');
        if (!$layout) {
            $layout = 'map';
        }
        $variables = array(
            'spotInfo' => $cartPickup->getSpotInfo(),
            'layout' => $layout
        );


        return $this->fetchView('cart/epost-additional.php', $variables);
    }
}