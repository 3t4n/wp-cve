<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsCarRentalSetting;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsCurrencyHelper;

new HQRentalBakeryMotorsVehicleGridShortcode();

class HQRentalBakeryMotorsVehicleGridShortcode extends WPBakeryShortCode
{
    private $query;
    private $reservationURL;
    private $title;
    private $target_step;
    private $ramdomize_items;
    private $number_of_vehicles;

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_motors_vehicle_grid', array($this, 'content'));
        $this->query = new HQRentalsDBQueriesVehicleClasses();
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'reservation_page_url'              =>  '',
            'title'                             =>  '',
            'target_step'                       =>  '3',
            'randomize_grid'                    =>  'false',
            'number_of_vehicles'                =>  ''
        ), $atts));
        $this->reservationURL = $atts['reservation_page_url'];
        $this->title = $atts['title'];
        $this->target_step = $atts['target_step'];
        $this->ramdomize_items = $atts['randomize_grid'];
        $this->number_of_vehicles = $atts['number_of_vehicles'];
        echo $this->renderShortcode();
    }

    public function setParams()
    {
        vc_map(
            array(
                'name' => __('HQRS Motors Vehicles Classes Grid', 'hq-wordpress'),
                'base' => 'hq_bakery_motors_vehicle_grid',
                'content_element' => true,
                "category" => __('HQ Rental Software - Motors Theme'),
                'show_settings_on_create' => true,
                'description' => __('HQ Motors Vehicles Classes Grid', 'hq-wordpress'),
                'icon' => HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Title', 'hq-wordpress'),
                        'param_name' => 'title',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Target Step', 'hq-wordpress'),
                        'param_name' => 'target_step',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Reservation URL', 'hq-wordpress'),
                        'param_name' => 'reservation_page_url',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Number of Vehicles', 'hq-wordpress'),
                        'param_name' => 'number_of_vehicles',
                        'value' => ''
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Randomize Items', 'hq-wordpress'),
                        'param_name' => 'randomize_grid',
                        'value'      => array(
                            __('Yes', "hq-wordpress") => 'true',
                            __('No', "hq-wordpress") => 'false',
                        ),
                    )
                )
            )
        );
    }

    public function renderShortcode()
    {
        $html_loop = "";
        $vehicles = $this->query->allVehicleClasses();
        if ($this->ramdomize_items === 'true') {
            shuffle($vehicles);
        }
        if (!empty($this->number_of_vehicles) and is_numeric($this->number_of_vehicles)) {
            foreach (array_slice($vehicles, 0, (int)$this->number_of_vehicles) as $vehicle) {
                $html_loop .= $this->resolveSingleVehicleCode($vehicle);
            }
        } else {
            foreach ($vehicles as $vehicle) {
                $html_loop .= $this->resolveSingleVehicleCode($vehicle);
            }
        }

        return HQRentalsAssetsHandler::getHQFontAwesome() . "
            <div class='wpb_column vc_column_container vc_col-sm-12 hq-grid-wrapper'>
                <div class='wpb_column vc_column_container vc_col-sm-12 hq-grid-inner-wrapper'>
                    <div class='vc_column-inner'>
                        <div class='wpb_wrapper'>
                            <h1 style='font-size: 30px;color: #000000;line-height: 50px;text-align: center' class='vc_custom_heading'>{$this->title}</h1>
                                <div class='stm_products_grid_class'>
                                    " . $html_loop . "
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
            .hq-grid-wrapper{
                display: flex;
                flex: 1;
                justify-content: center;
                align-items: center;
                padding-top: 40px;
            }
            .hq-grid-inner-wrapper{
                max-width: 1200px;
            }
            @media only screen and (max-width: 767px) {
                .hq-grid-inner-wrapper{
                    margin-left: 5%;
                    margin-right: 5%;
                }   
            }
            </style>
        ";
    }

    public function resolveSingleVehicleCode($vehicle)
    {
        $setting = new HQRentalsSettings();
        $option = $setting->getOverrideDailyRateWithCheapestPriceInterval();
        $priceInterval = $vehicle->getCheapestPriceIntervalForWebsite()->formatPrice();
        $currency = HQRentalsCurrencyHelper::getCurrencySymbol();
        $rate = ($option == 'true') ? ($currency . ' '  . $priceInterval) : $vehicle->getActiveRate()->daily_rate->amount_for_display;

        return "
        <div class='stm_product_grid_single'>
            <a href='{$this->reservationURL}?target_step={$this->target_step}&vehicle_class_id={$vehicle->getId()}' class='inner'>
                <div class='stm_top clearfix'>
                    <div class='stm_left heading-font'>
                        <h3>{$vehicle->getLabelForWebsite()}</h3>
                        <div class='s_title'></div>
                        <div class='price'>
                            <mark>" . HQRentalsLocaleHelper::resolveTranslation('motors_vehicle_grid_from') . "</mark>
                            <span class='woocommerce-Price-amount amount'>
                                {$rate}
                            <span style='font-size: 12px;'>/" . HQRentalsLocaleHelper::resolveTranslation('motors_vehicle_grid_day') . "</span>
                            </span>
                        </div>
                    </div>
                    " . $this->renderFeatures($vehicle) . "
                </div>
                <div class='stm_image'>
                        <img width='798' height='466'
                        src='{$vehicle->getPublicImage()}' />
                    </div>
            </a>
        </div>
        ";
    }
    public function renderFeatures($vehicle)
    {
        $html = "";
        if (is_array($vehicle->getVehicleFeatures()) and count($vehicle->getVehicleFeatures())) {
            foreach ($vehicle->getVehicleFeatures() as $feature) {
                $featureIcon = empty($feature->icon) ?
                    "<img src='{$feature->image}' class='feature-image'  alt='{$feature->label}' />" :
                    "<i class='{$feature->icon}'></i>";
                $html .= "
                    <div class='single_info'>
                        {$featureIcon}
                        <span>{$feature->label}</span>
                    </div>
                ";
            }
            return "<div class='stm_right'>" . $html . "</div>";
        }
        return $html;
    }
}
