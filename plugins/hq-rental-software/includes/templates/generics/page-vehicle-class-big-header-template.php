<?php

use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsVendor\Carbon;

$vehicleId = $_GET['id'];
if (empty($vehicleId)) {
    wp_redirect(home_url());
    exit;
}
$queryLocations = new HQRentalsDBQueriesLocations();
$query = new HQRentalsDBQueriesVehicleClasses();
$assets = new HQRentalsAssetsHandler();
$helper = new HQRentalsFrontHelper();
$vehicle = $query->getVehicleClassById($vehicleId);
$setting = new \HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings();
$optionsLocations = $helper->getLocationOptions();
$assets->loadAssetsForBigHeaderPageTemplate();
$site = get_site_url();
get_header();
?>
    <?php HQRentalsAssetsHandler::getHQFontAwesome(); ?>
    <div id="page_caption" class="hasbg lazy"
         style="background-image: url(<?php echo $vehicle->getCustomFieldForWebsite('f' . $setting->getVehicleClassBannerImageField())[0]->public_link; ?>);">
    </div>
    <div id="page_content_wrapper">
        <div id="vehicle-class-<?php echo $vehicle->getId(); ?>" class="hq-vehicle-content-wrapper inner">
            <!-- Begin main content -->
            <div class="inner_wrapper">
                <div class="sidebar_content">
                    <div class="hq-title-wrapper">
                        <div>
                            <h1 class="hq-class-title"><?php echo $vehicle->getLabelForWebsite(); ?></h1>
                        </div>
                        <div>
                            <h1 class="hq-class-title">
                                <!--CH$49.761-->
                            </h1>
                        </div>
                    </div>
                    <div class="hq-vehicle-single-page-feature-wrapper single_car_attribute_wrapper themeborder">
                        <?php foreach ($vehicle->getVehicleFeatures() as $feature) : ?>
                            <div class="one_fourth hq-feature-wrapper">
                                <p class="hq-feature-label"><?php echo $feature->label_for_website->es; ?></p>
                                <i class="<?php echo $feature->icon; ?>"></i>
                                <!--<div class="car_attribute_content">
                                    <?php //echo $feature->label; ?>
                                </div>-->
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <br class="clear">
                    <div class="single_car_content">
                        <div class="single_car_content hq-short-description">
                            <?php echo $vehicle->getShortDescriptionForWebsite(); ?>
                        </div>
                    </div>
                    <div class="single_car_departure_wrapper themeborder hq-description">
                        <?php echo $vehicle->getDescriptionForWebsite(); ?>
                    </div>
                </div>
            </div>
            <!-- End main content -->
        </div>
        <script>
            var baseURL = "<?php echo $site; ?>";
        </script>
        <div class="sidebar_wrapper">
            <div class="sidebar_top"></div>
            <div class="content">
                <div class="single_car_booking_wrapper themeborder book_instantly">
                    <div class="single_car_booking_woocommerce_wrapper">
                        <form action="<?php echo $setting->getPublicReservationWorkflowURL(); ?>" method="GET" autocomplete="off"
                              id="hq-widget-form">
                            <div class="hq-form-item-wrapper">
                                <label for=""><?php echo __('Pick Up Location', 'hq-wordpress'); ?></label>
                                <select id="hq-pick-up-location" name="pick_up_location" required="required"
                                        autocomplete="off">
                                    <?php echo $optionsLocations; ?>
                                </select>
                            </div>
                            <div class="hq-form-item-wrapper">
                                <label for=""><?php echo __('Return Location', 'hq-wordpress'); ?></label>
                                <select id="hq-return-location" name="return_location" required="required"
                                        autocomplete="off">
                                    <?php echo $optionsLocations; ?>
                                </select>
                            </div>
                            <div class="hq-form-item-wrapper">
                                <label for=""><?php echo __('Pick Up Date', 'hq-wordpress'); ?></label>
                                <input id="hq-times-pick-up-date" class="hq-inputs" type="text"
                                       autocomplete="off" name="pick_up_date" placeholder="Select Date" />
                            </div>
                            <div class="hq-form-item-wrapper">
                                <label for=""><?php echo __('Return Date', 'hq-wordpress'); ?></label>
                                <input id="hq-times-return-date" class="hq-inputs" type="text"
                                       autocomplete="off" name="return_date" placeholder="Select Date" />
                            </div>
                            <input type="hidden" name="vehicle_class_id" value="<?php echo $vehicle->getId(); ?>">
                            <input type="hidden" name="target_step" value="3">
                            <input class="hq-submit-button" type="submit" value="<?php echo __('Book Now', 'hq-wordpress'); ?>">
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
            <div class="sidebar_bottom"></div>
        </div>
    </div>
<?php get_footer(); ?>
