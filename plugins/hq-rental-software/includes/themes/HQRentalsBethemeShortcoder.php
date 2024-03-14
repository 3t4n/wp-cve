<?php

namespace HQRentalsPlugin\HQRentalsThemes;

class HQRentalsBethemeShortcoder
{
    public function __construct()
    {
        $this->vehicleGrid = new HQRentalsBethemeVehicleGridShortcode();
        $this->vehicleCarousel = new HQRentalsBethemeVehicleCarouselShortcode();
    }
}
