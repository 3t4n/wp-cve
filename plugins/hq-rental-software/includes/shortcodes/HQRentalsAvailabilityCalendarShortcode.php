<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsAvailabilityCalendarShortcode
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->brand = new HQRentalsModelsBrand();
        $this->assetsHelper = new HQRentalsAssetsHandler();
        add_shortcode('hq_rentals_vehicle_calendar', array($this, 'calendarShortcode'));
    }

    public function calendarShortcode($atts = [])
    {
        $this->assetsHelper->getIframeResizerAssets();
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => 'en',
                'vehicle_class_id' => '',
                'autoscroll' => 'true'
            ),
            $atts
        );
        if ($atts['autoscroll'] == 'true') {
            $this->assets->loadScrollScript();
        }
        $this->brand->findBySystemId($atts['id']);
        $url = $this->brand->publicCalendarLink;
        $lang = '&forced_locale=' . $atts['forced_locale'];
        $vehicle_class = (empty($atts['vehicle_class_id'])) ? '' : '&vehicle_class_id=' . $atts['vehicle_class_id'];
        return '<iframe 
                    id="hq-rental-iframe" 
                    src="' .
                    esc_url($url .
                        $lang . $vehicle_class .
                        '&' . 'forced_locale=' . $atts['forced_locale']) . '" scrolling="no">
                </iframe>';
    }
}
