<?php

namespace HQRentalsPlugin\HQRentalsThemes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsBethemeShortcodes;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;

class HQRentalsBethemeVehicleCarouselShortcode
{
    public function __construct()
    {
        $this->queryVehicles = new HQRentalsQueriesVehicleClasses();
        $this->assets = new HQRentalsAssetsBethemeShortcodes();
        add_shortcode('hq_rentals_betheme_vehicles_carousel', array($this, 'renderShortcode'));
    }

    public function renderShortcode($atts = [])
    {
        $this->assets->loadVehicleCarouselAssets();
        $atts = shortcode_atts(
            array(
                'rent_button_url' => '',
            ),
            $atts,
            'hq_rentals_betheme_vehicles_carousel'
        );
        ?>
        <div class="owl-carousel">
            <?php foreach ($this->queryVehicles->allVehicleClasses() as $vehicle) : ?>
                <div class="hq-slide">
                    <div class="hq-slide-inner">
                        <div class="car-rental-item-image">
                            <a href="<?php echo $atts['rent_button_url']; ?>" title="View car">
                                <img class="hq-slide-image" src="<?php echo $vehicle->getFeatureImage('500'); ?>"
                                     title="<?php echo $vehicle->name; ?>" alt="<?php echo $vehicle->name; ?>">
                            </a>
                        </div>
                        <div class="car-rental-item-details hq-slide-details-wrapper">
                            <div class="car-rental-item-title hq-slide-title">
                                <a href="<?php echo $atts['rent_button_url']; ?>"><?php echo $vehicle->name; ?></a>
                            </div>
                            <div class="hq-slide-prefix">Get a quote</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <style>
            .car-rental-item-image {
                min-height: 250px;
                min-width: 250px;
            }

            .hq-slide-image {
                min-height: 100px;
                min-width: 100px;
                max-height: 350px;
                max-width: 350px;
            }

            .hq-slide-prefix {
                text-transform: uppercase;
                display: inline-block;
                color: #4C8AB1;
                font-size: 18px;
                font-weight: bold;
                text-align: left;
                vertical-align: top;
                padding-top: 18px;
                height: 62px;
            }

            .hq-slide-title {
                text-transform: uppercase;
                color: black;
                font-size: 18px;
                font-weight: bold;
                margin-top: 8px;
                margin-bottom: 5px;
                display: flex;
                flex-direction: row;
                flex: 1;
                justify-content: center;
                align-items: center;
            }

            .hq-slide {
                display: flex;
                flex: 1;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                height: 100%;
                min-height: 1px;
                outline: none;
            }

            .hq-slide-inner {
                display: flex;
                flex: 1;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }

            .hq-slide-details-wrapper {
                display: flex;
                flex: 1;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }
        </style>
        <?php
    }
}
