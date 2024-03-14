<?php

namespace HQRentalsPlugin\HQRentalsAssets;

class HQRentalsAssetsBethemeShortcodes
{
    public function loadVehicleGridAssets()
    {
        wp_enqueue_style('hq-betheme-sc-vehicle-grid-styles');
        wp_enqueue_style('hq-fancy-box-css');
        wp_enqueue_script('hq-fancy-box-js');
        wp_enqueue_script('hq-betheme-vehicle-grid-js');
    }

    public function loadVehicleCarouselAssets()
    {
        wp_enqueue_style('hq-owl-carousel-css');
        wp_enqueue_style('hq-owl-carousel-theme-css');
        wp_enqueue_script('hq-owl-carousel-js');
        wp_enqueue_script('hq-betheme-vehicle-carousel-js');
    }
}
