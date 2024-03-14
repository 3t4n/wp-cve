<?php
get_header();

use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

$settings = new HQRentalsSettings();
$front = new HQRentalsFrontHelper();
$get_data = $_GET;
$url = $front->resolveUrlOnPayments($settings->getTenantLink(), $get_data['payment_id']);
?>
    <div class="hq-container">
        <div class="hq-iframe-wrapper">
            <?php echo do_shortcode('[hq_rentals_form_link url=' . $url . ']') ?>
        </div>
    </div>
    <style>
        .hq-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hq-iframe-wrapper {
            flex: 1;
        }
    </style>
<?php
get_footer();
