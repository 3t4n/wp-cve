<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsMyReservationsSnippetShortcode extends HQBaseShortcode
{
    private static $shortcodeTag = 'hq_rentals_my_reservations_snippet';

    public function __construct()
    {
        $this->brand = new HQRentalsModelsBrand();
        $this->assetsHelper = new HQRentalsAssetsHandler();
        add_shortcode(HQRentalsMyReservationsSnippetShortcode::$shortcodeTag, array($this, 'packagesShortcode'));
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
        $this->assetsHelper->getIframeResizerAssets();
        if ($atts['autoscroll'] == 'true') {
            $this->assetsHelper->loadScrollScript();
        }
        $this->brand->findBySystemId($atts['id']);
        return $this->filledSnippetData($this->brand->getReservationSnippet(), $atts);
    }
}
