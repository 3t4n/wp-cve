<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

/**
 * Class HQRentalsShortcoder
 * @package HQRentalsPlugin\HQRentalsShortcodes
 * This class its just for making the Shortcodes accesible to Wordpress
 */
class HQRentalsShortcoder
{
    public function __construct()
    {
        $this->reservationShortcode = new HQRentalsReservationsShortcode();
        $this->reservationFilteredShortcode = new HQRentalsReservationsFilteredShortcode();
        $this->packagesShortcode = new HQRentalsPackagesShortcode();
        $this->reservationPackages = new HQRentalsReservationsPackagesShortcode();
        $this->myReservations = new HQRentalsMyReservationsShortcode();
        $this->myPackagesReservations = new HQRentalsMyPackagesReservationsShortcode();
        $this->formLink = new HQRentalsFormLink();
        $this->reservationsAdvanced = new HQRentalsReservationsAdvancedShortcode();
        $this->systemAssetsShortcode = new HQRentalsSystemAssets();
        $this->calendarShortcode = new HQRentalsAvailabilityCalendarShortcode();
        $this->karzoomMapBookForm = new HQRentalsMapBookingForm();
        $this->availabilityGrid = new HQRentalsAvailabilityGridShortcode();
        $this->reservationSnippet = new HQRentalsReservationsSnippetShortcode();
        $this->reservationFormSnippet = new HQRentalsReservationFormSnippetShortcode();
        $this->quoteSnippet = new HQRentalsQuoteSnippetShortcode();
        $this->packageSnippet = new HQRentalsPackageSnippetShortcode();
        $this->paymentSnippet = new HQRentalsPaymentSnippetShortcode();
        $this->gCarVehicleFilterShortcode = new HQGCarVehicleFilterShortcode();
        $this->calendarSnippetShortcode = new HQRentalsAvailabilityCalendarSnippetShortcode();
        $this->vehicleGrid = new HQRentalsVehicleGrid();
        $this->myReservationsSnippet = new HQRentalsMyReservationsSnippetShortcode();
        $this->carRentalVehicleTabs = new HQRentalsCarRentalVehicleTabShortcode();
        $this->availabilityGridFilter = new HQRentalsAvailabilityFilterShortcode();
        $this->placesShortcode = new HQRentalsPlacesReservationForm();
        $this->simpleShortcode = new HQRentalsSimpleFormShortcode();
        $this->wheelsberryShortcode = new HQWheelsberrySliderShortcode();
        $this->wheelsberryLocationMapShortcode = new HQWheelsberryLocationMapShortcode();
        $this->vehicleTypeForm = new HQRentalsReservationFormByVehicleType();
        $this->vehicleTypeGrid = new HQRentalsVehicleTypesGrid();
        $this->quoteSimpleForm = new HQRentalsSimpleQuoteShortcode();
        $this->gCarThemeGrid = new HQGCarVehicleFilterFourColumnsShortcode();
    }
}
