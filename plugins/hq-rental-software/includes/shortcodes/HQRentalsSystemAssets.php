<?php

/**
 * Created by PhpStorm.
 * User: Miguel Faggioni
 * Date: 12/8/2018
 * Time: 11:31 AM
 */

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector;

class HQRentalsSystemAssets
{
    public function __construct()
    {
        $this->connector = new HQRentalsApiConnector();
        add_shortcode('hq_rentals_assets', array($this, 'loadSystemAssets'));
    }

    public function loadSystemAssets()
    {
        $assets = $this->connector->getHQRentalsSystemAssets();
        $html = '';
        if ($assets->success) {
            foreach ($assets->data as $key => $asset) {
                $html .= "<link rel='stylesheet' id='hq-css-" . esc_attr($key) . "' href='" . esc_url($asset) . "' type='text/css' media='all' />";
            }
        }
        return $html;
    }
}
