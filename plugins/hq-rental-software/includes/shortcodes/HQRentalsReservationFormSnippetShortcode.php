<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsReservationFormSnippetShortcode extends HQBaseShortcode
{
    private static $shortcodeTag = 'hq_rentals_reservation_form_snippet';
    private $brand_id;

    public function __construct($params = null)
    {
        add_shortcode(HQRentalsReservationFormSnippetShortcode::$shortcodeTag, array($this, 'renderShortcode'));
        if (!empty($params['brand_id_form_widget'])) {
            $this->brand_id = $params['brand_id_form_widget'];
        }
    }

    public function renderShortcode($atts = null)
    {
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => HQRentalsFrontHelper::getLocaleForSnippet(),
                'reservation_page' => '',
                'layout' => ''
            ),
            $atts
        );
        ob_start();
        $brand = new HQRentalsModelsBrand();
        $brand->findBySystemId((int) !empty($this->brand_id) ? $this->brand_id : $atts['id']);
        return $this->filledSnippetData($brand->getReservationFormSnippet(), $atts);
    }
}
