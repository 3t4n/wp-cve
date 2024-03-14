<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsReservationsSnippetShortcode extends HQBaseShortcode implements HQShortcodeInterface
{
    private static $shortcodeTag = 'hq_rentals_reservations_snippet';
    private $brand_id;

    public function __construct($params = null)
    {
        add_shortcode(HQRentalsReservationsSnippetShortcode::$shortcodeTag, array($this, 'renderShortcode'));
        if (!empty($params['brand_id_reservation_engine'])) {
            $this->brand_id = $params['brand_id_reservation_engine'];
        }
    }

    public function renderShortcode($atts = null)
    {
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => HQRentalsFrontHelper::getLocaleForSnippet(),
            ),
            $atts
        );
        ob_start();
        $brand = new HQRentalsModelsBrand();
        $brand->findBySystemId((int) (!empty($this->brand_id) ? $this->brand_id : $atts['id']));
        return $this->filledSnippetData($brand->getReservationSnippet(), $atts);
    }
}
