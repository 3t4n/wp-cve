<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;

class HQRentalsAvailabilityGridShortcode
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        add_shortcode('hq_rentals_availability_grid', array($this, 'renderShortcode'));
    }

    public function renderShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'title' => ''
            ),
            $atts
        );
        ?>
        <script>
            var baseUrl = "<?php echo get_site_url() . '/'; ?>";
            var availabilityGridTitle = "<?php echo $atts['title']; ?>";
            var availabilityGridIntegrationPage = "<?php echo $atts['integration-page']; ?>";
        </script>
        <div id="hq-availability-grid"></div>
        <?php
        $this->assets->loadAssetsForAvailabilityGrid();
    }
}
