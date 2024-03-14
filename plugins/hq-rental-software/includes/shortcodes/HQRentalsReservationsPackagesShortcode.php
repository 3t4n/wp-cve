<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsReservationsPackagesShortcode
{
    public function __construct()
    {
        $this->brand = new HQRentalsModelsBrand();
        $this->assetsHelper = new HQRentalsAssetsHandler();
        add_shortcode('hq_rentals_reservation_packages', array($this, 'packagesShortcode'));
    }

    public function packagesShortcode($atts = [])
    {
        global $is_safari;
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => 'en',
                'autoscroll' => 'true'
            ),
            $atts
        );
        $langParams = '?forced_locale=' . $atts['forced_locale'];
        $this->assetsHelper->getIframeResizerAssets();
        if ($atts['autoscroll'] == 'true') {
            $this->assetsHelper->loadScrollScript();
        }
        $this->brand->findBySystemId($atts['id']);
        return '<iframe id="hq-rentals-integration-wrapper" 
                src="' . $this->resolveIframeURL($langParams) . '" scrolling="no"></iframe>';
    }

    private function resolveIframeURL($langParams): string
    {
        return esc_url($this->brand->publicReservationPackagesFirstStepLink . $langParams);
    }
}
