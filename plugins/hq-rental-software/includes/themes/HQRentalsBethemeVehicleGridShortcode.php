<?php

namespace HQRentalsPlugin\HQRentalsThemes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsBethemeShortcodes;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesVehicleClasses;

class HQRentalsBethemeVehicleGridShortcode
{
    public function __construct()
    {
        $this->queryVehicles = new HQRentalsQueriesVehicleClasses();
        $this->assets = new HQRentalsAssetsBethemeShortcodes();
        add_shortcode('hq_rentals_betheme_vehicles_grid', array($this, 'renderShortcode'));
    }

    public function renderShortcode($atts = [])
    {
        $this->assets->loadVehicleGridAssets();
        $atts = shortcode_atts(
            array(
                'rent_button_url' => '',
            ),
            $atts,
            'hq_rentals_betheme_vehicles_grid'
        );
        ?>
        <div class="column mcb-column one column_fancy_heading ">
            <div class="fancy_heading fancy_heading_icon">
                <h2 class="title">Car List</h2>
                <div class="inside">
                    <?php foreach ($this->queryVehicles->allVehicleClasses() as $vehicle) : ?>
                        <div class="car-rental-wrapper car-rental-items-list">
                            <div class="item-type-label"><?php echo $vehicle->name; ?></div>
                            <div class="car-rental-list-item">
                                <div class="item-image">
                                    <a data-fancybox="gallery" href="<?php echo $vehicle->getFeatureImage(); ?>"
                                       title="<?php echo $vehicle->name; ?>">
                                        <img src="<?php echo $vehicle->getFeatureImage(); ?>"
                                             alt="Mitsubishi Montero Sport or Similar ">
                                    </a>
                                </div>
                                <div class="item-description">
                                    <a href="https://www.axis-cr.com/car/mitsubishi-montero-sport/"
                                       title="Show car description"><span
                                                class="item-name"><?php echo $vehicle->name; ?></span></a>
                                    <br>
                                    <hr>
                                </div>
                                <div class="item-more">
                                    <div class="item-features-title">Features</div>
                                    <hr>
                                    <ul class="car-rental-item-features-list">
                                        <?php foreach ($vehicle->features() as $feature) : ?>
                                            <li><?php echo $feature->label; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="car-rental-buttons">
                                        <div class="car-rental-single-button">
                                            <a href="<?php echo $atts['rent_button_url']; ?>" title="Rent">Rent</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <style>
            .car-rental-items-list .car-rental-list-item .item-more {
                width: 40% !important;
            }
        </style>
        <?php
    }
}
