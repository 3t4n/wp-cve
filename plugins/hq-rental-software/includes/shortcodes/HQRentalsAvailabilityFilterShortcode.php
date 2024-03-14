<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;

class HQRentalsAvailabilityFilterShortcode
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        add_shortcode('hq_rentals_availability_filter_grid', array($this, 'renderShortcode'));
    }

    public function renderShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'title' => ''
            ),
            $atts
        );
        $queryVehicle = new HQRentalsDBQueriesVehicleClasses();
        $vehicles = $queryVehicle->allVehicleClasses(true);
        ob_start();
        ?>
        <?php HQRentalsAssetsHandler::getHQFontAwesome(); ?>
        <script>
            var baseUrl = "<?php echo get_site_url() . '/'; ?>";
            var availabilityGridTitle = "<?php echo $atts['title']; ?>";
            var availabilityGridIntegrationPage = "<?php echo $atts['integration-page']; ?>";
        </script>
        <style>
            .hq-vehicle-item-wrapper{
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                align-items: center;
                box-shadow: 0 22px 40px rgb(0 0 0 / 15%);
                border: 1px solid #dce0e0;
                border-radius: 5px;
                padding:30px;
                transition-duration: 0.2s;
            }
            .hq-vehicle-item-wrapper:hover{
                transform: translateY(-4px);
                box-shadow: 0 22px 40px rgb(0 0 0 / 15%);
            }
            #hq-availability-grid-wrapper{
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                column-gap: 15px;
                row-gap: 50px;
                margin-top: 50px;
            }
            .hq-vehicle-item-content-wrapper{
                width: 100%;
            }
            .hq-vehicle-item-content-inner-wrapper{
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                align-items: center;
            }
            .hq-vehicle-item-label-wrapper{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 100%;
                flex:1;
            }
            .hq-vehicle-features-wrapper{
                display: flex;
                flex-direction: row;
                justify-content: space-evenly;
                align-items: center;
                width: 100%;
            }
            #hq-availability-grid-filter{
                max-width: 1200px;
                margin:auto;
                padding: 0 5%;
            }
            .hq-vehicle-item-label{
                font-size: 20px;
                font-family: inherit;
                font-weight: bold;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .hq-vehicle-single-icon{
                font-size: 20px;
            }
            .hq-vehicle-single-button{
                color: #FFFFFF!important;
                border-radius: 0px;
                letter-spacing: 4px;
                font-size: 16px;
                font-weight: 700!important;
                text-transform: uppercase!important;
                background-color: #AADB24;
                border: 2px solid transparent;
                padding: 0.3em 2em;
            }
            .hq-vehicle-single-button:hover{
            }
            .hq-vehicle-button-wrapper{
                padding:15px 0;
            }
            .hq-vehicle-features-wrapper{
                padding: 10px 0;
            }
            @media (max-width: 992px) {
                #hq-availability-grid-wrapper{
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 576px) {
                #hq-availability-grid-wrapper{
                    grid-template-columns: repeat(1, 1fr);
                }
            }


        </style>
        <div id="hq-availability-grid-filter">
            <div id="hq-availability-form-wrapper">

            </div>
            <div id="hq-availability-grid-wrapper">
                <?php foreach ($vehicles as $vehicle) : ?>
                    <div class="hq-vehicle-item-wrapper">
                        <div class="hq-vehicle-item-image-wrapper">
                            <a href="<?php echo get_site_url(); ?>/vehicle-class/?id=<?php echo $vehicle->getId(); ?>">
                                <img src="<?php echo $vehicle->getPublicImage(); ?>" alt="">
                            </a>
                        </div>
                        <div class="hq-vehicle-item-content-wrapper">
                            <div class="hq-vehicle-item-content-inner-wrapper">
                                <div class="hq-vehicle-item-label-wrapper">
                                    <div class="hq-vehicle-item-label-inner-wrapper">
                                        <h4 class="hq-vehicle-item-label">
                                            <?php echo $vehicle->getLabelForWebsite(); ?>
                                        </h4>
                                    </div>
                                    <div class="hq-vehicle-item-rate-inner-wrapper">

                                    </div>
                                </div>
                                <div class="hq-vehicle-features-wrapper">
                                    <?php foreach ($vehicle->getVehicleFeatures() as $feature) : ?>
                                        <div class="hq-vehicle-single-feature">
                                            <i class="<?php echo $feature->icon; ?> hq-vehicle-single-icon"></i>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="hq-vehicle-button-wrapper">
                                    <a class="hq-vehicle-single-button"
                                       href="
                                        <?php echo get_site_url(); ?>
                                        /vehicle-class/?id=<?php echo $vehicle->getId(); ?>">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
