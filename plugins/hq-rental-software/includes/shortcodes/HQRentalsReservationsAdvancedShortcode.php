<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsDatesHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueryStringHelper;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsReservationsAdvancedShortcode
{
    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->dateHelper = new HQRentalsDatesHelper();
        $this->queryHelper = new HQRentalsQueryStringHelper();
        $this->assets = new HQRentalsAssetsHandler();
        $this->frontHelper = new HQRentalsFrontHelper();
        add_shortcode('hq_rentals_reservations_advanced', array($this, 'reservationsAdvancedShortcode'));
    }

    public function reservationsAdvancedShortcode($atts = [])
    {
        global $is_safari;
        $atts = shortcode_atts(
            array(
                'id' => '1',
                'forced_locale' => 'en',
                'new' => 'true',
                'autoscroll' => 'true',
                'reservation_advanced_url' => ''
            ),
            $atts,
            'hq_rentals_reservations_advanced'
        );
        $post_data = $_POST;
        $post_data = $this->frontHelper->sanitizeTextInputs($post_data);
        ?>
        <iframe id="hq-rental-iframe"
                src="<?php echo esc_url($atts['reservation_advanced_url'] .
                    '&' . http_build_query($post_data) . '&' . 'forced_locale=' . $atts['forced_locale']); ?>"
                scrolling="no"></iframe>
        <?php
        $this->assets->getIframeResizerAssets();
        if ($atts['autoscroll'] == 'true') {
            $this->assets->loadScrollScript();
        }
    }
}
