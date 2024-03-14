<?php

namespace HQRentalsPlugin\HQRentalsCustomPosts;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsActiveRate;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsAdditionalCharge;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsFeature;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsLocation;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsPriceInterval;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClass;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleClassImage;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsVehicleType;

class HQRentalsCustomPostsHandler
{
    public function __construct()
    {
        $this->currentWebsite = get_site_url();
        $this->brands = new HQRentalsModelsBrand();
        $this->vehicleClasses = new HQRentalsModelsVehicleClass();
        $this->locations = new HQRentalsModelsLocation();
        $this->vehicleTypes = new HQRentalsModelsVehicleType();
        $this->featureClasses = new HQRentalsModelsFeature();
        $this->vehicleImage = new HQRentalsModelsVehicleClassImage();
        $this->priceInterval = new HQRentalsModelsPriceInterval();
        $this->activeRate = new HQRentalsModelsActiveRate();
        $this->charge = new HQRentalsModelsAdditionalCharge();
        add_action('init', array($this, 'registerAllHQRentalsCustomPosts'));
    }

    /*
     * Register all Custom Posts
     */
    public function registerAllHQRentalsCustomPosts()
    {
        register_post_type($this->brands->brandsCustomPostName, $this->brands->customPostArgs);
        register_post_type($this->locations->locationsCustomPostName, $this->locations->customPostArgs);
        register_post_type($this->vehicleClasses->vehicleClassesCustomPostName, $this->vehicleClasses->customPostArgs);
        register_post_type($this->vehicleTypes->vehicleTypeCustomPostName, $this->vehicleTypes->customPostArgs);
        register_post_type($this->featureClasses->featureVehicleClassPostName, $this->featureClasses->customPostArgs);
        register_post_type($this->vehicleImage->vehicleClassImageCustomPostName, $this->vehicleImage->customPostArgs);
        register_post_type($this->priceInterval->priceIntervalCustomPostName, $this->priceInterval->customPostArgs);
        register_post_type($this->activeRate->activeRateCustomPostName, $this->activeRate->customPostArgs);
        register_post_type($this->charge->additionalChargesCustomPostName, $this->charge->customPostArgs);
    }
}
