<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsPackagesShortcode
{
    public function __construct()
    {
        $this->brand = new HQRentalsModelsBrand();
        $this->assetsHelper = new HQRentalsAssetsHandler();
        add_shortcode('hq_rentals_packages', array($this, 'packagesShortcode'));
    }

    public function packagesShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => 'en',
                'autoscroll' => 'true'
            ),
            $atts
        );
        $langParams = '&forced_locale=' . $atts['forced_locale'];
        $this->assetsHelper->getIframeResizerAssets();
        if ($atts['autoscroll'] == 'true') {
            $this->assetsHelper->loadScrollScript();
        }
        $this->brand->findBySystemId($atts['id']);
        return '<iframe id="hq-rentals-integration-wrapper" src="' . esc_url($this->brand->publicPackagesLinkFull . $langParams) . '" scrolling="no"></iframe>';
    }
}
