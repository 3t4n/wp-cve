<?php

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;

new HQRentalBakeryMotorsReservationFormShortcode();


class HQRentalBakeryMotorsReservationFormShortcode extends WPBakeryShortCode
{
    private $query;
    private $reservationURL;
    private $minimumRentalPeriod;

    public function __construct()
    {
        add_action('vc_before_init', array($this, 'setParams'));
        add_shortcode('hq_bakery_motors_reservation_form', array($this, 'content'));
        $this->query = new HQRentalsDBQueriesVehicleClasses();
        $this->queryLocations = new HQRentalsDBQueriesLocations();
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
    }

    public function content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'reservation_page_url'  =>  '',
            'minimum_rental_period' =>  1
        ), $atts));
        $this->reservationURL = $atts['reservation_page_url'];
        $this->minimumRentalPeriod = $atts['minimum_rental_period'];
        echo $this->renderShortcode();
    }

    public function setParams()
    {
        vc_map(
            array(
                'name' => __('HQRS Motors Reservation Form', 'hq-wordpress'),
                'base' => 'hq_bakery_motors_reservation_form',
                'content_element' => true,
                "category" => __('HQ Rental Software - Motors Theme'),
                'show_settings_on_create' => true,
                'description' => __('HQ Motors Reservation Form', 'hq-wordpress'),
                'icon' => HQRentalsAssetsHandler::getHQLogo(),
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Reservation URL', 'hq-wordpress'),
                        'param_name' => 'reservation_page_url',
                        'value' => ''
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Minimum Rental Period', 'hq-wordpress'),
                        'param_name' => 'minimum_rental_period',
                        'value' => ''
                    )
                )
            )
        );
    }

    public function renderShortcode()
    {
        $this->assets->loadDatePickersReservationAssets();
        $locations = $this->queryLocations->allLocations();
        $locations_options = $this->helper->getLocationOptions($locations);
        return HQRentalsAssetsHandler::getHQFontAwesome() . "
            <script>
                var hqMinimumDaysRental = " . $this->minimumRentalPeriod . ";
            </script>
            <div class='stm_rent_car_form_wrapper style_1 text-right'>
                <div class='stm_rent_car_form'>
                        <form action='{$this->reservationURL}' method='get'>
                        " . $this->renderLocations($locations_options, $locations) . "
                        " . $this->renderLocations($locations_options, $locations, true) . "
                        " . $this->renderMessageInCaseOfOneLocation($locations) . "
                        <div class='hq-motors-input-wrapper'>
                            <h4>" . HQRentalsLocaleHelper::resolveTranslation('motors_reservation_form_from') . "</h4>
                            <div class='stm_date_time_input'>
                                <div class='stm_date_input'>
                                    <input type='text' id='hq_pick_up_date' class=' active' name='pick_up_date' 
                                    placeholder='Today' readonly='readonly' required='required'>
                                    <i class='stm-icon-date'></i>
                                </div>
                            </div>
                        </div>
                        <div class='hq-motors-input-wrapper'> 
                            <h4>" . HQRentalsLocaleHelper::resolveTranslation('motors_reservation_form_until') . "</h4>
                            <div class='stm_date_time_input'>
                                <div class='stm_date_input'>
                                    <input type='text' id='hq_return_date' class=' active' name='return_date' 
                                    placeholder='Tomorrow' readonly='readonly' required='required'>
                                    <i class='stm-icon-date'></i>
                                </div>
                            </div>
                        </div>
                        <div class='hq-motors-input-wrapper'>
                            <button type='submit'>" . HQRentalsLocaleHelper::resolveTranslation('motors_reservation_form_book') .
                                "<i class='fa fa-arrow-right'></i></button>
                            <input type='hidden' name='target_step' value='2'>
                        </div>    
                        </form>
                </div>
            </div>
            <style>
                .stm-template-car_rental .stm_date_time_input,.stm-template-car_rental .stm_date_time_input{
                    margin-bottom: 0px !important;
                }
                .hq-motors-input-wrapper{
                    padding-top: 5px;
                    padding-bottom: 5px; 
                }
                .stm_pickup_location select{
                    padding-left: 37px;
                    height: 40px;
                    line-height: 40px;
                    background-color: #fff;
                    border: 0;    
                    z-index: 10;  
                    width: 100%;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                .stm-template-car_rental .stm_rent_car_form_wrapper .stm_pickup_location{
                    padding-right: 10px;
                }
                .stm-template-car_rental .stm_rent_car_form_wrapper .stm_pickup_location > i{
                    left: 16px !important;
                }
                
                .stm-template-car_rental .stm_rent_car_form_wrapper .stm_pickup_location{
                    background-color: #fff;
                }
            </style>
        ";
    }

    public function renderLocations($locationsOptions, $locations, $pickupReturnMode = false): string
    {
        $html = "";
        if (is_array($locations)) {
            if ($pickupReturnMode) {
                if (count($locations) > 1) {
                    $html .= "
                    <div class='hq-motors-input-wrapper'>
                            <h4>" . HQRentalsLocaleHelper::resolveTranslation('motors_reservation_form_return') . "</h4>
                            <div class='stm_rent_form_fields'>
                                <div class='stm_pickup_location'>
                                    <i class='stm-service-icon-pin'></i>
                                    <select id='hq-return-location' name='return_location' required='required'>
                                        <option value=''>" .
                                            HQRentalsLocaleHelper::resolveTranslation(
                                                'motors_reservation_form_location_placeholder'
                                            ) .
                                        "</option>
                                        " . $locationsOptions . "
                                    </select>
                                </div>
                            </div>
                        </div>
                ";
                } else {
                    if (count($locations) === 1) {
                        $html .= "<input type='hidden' name='pick_up_location' value='" . $locations[0]->getId() . "' />";
                    }
                }
            } else {
                if (count($locations) > 1) {
                    $html .= "
                        <div class='hq-motors-input-wrapper'>
                            <h4>" . HQRentalsLocaleHelper::resolveTranslation('motors_reservation_form_pickup') . "</h4>
                            <div class='stm_rent_form_fields'>
                                <div class='stm_pickup_location'>
                                    <i class='stm-service-icon-pin'></i>
                                    <select id='hq-pick-up-location' name='pick_up_location' required='required'>
                                        <option value=''>" .
                                            HQRentalsLocaleHelper::resolveTranslation(
                                                'motors_reservation_form_location_placeholder'
                                            ) .
                                        "</option>
                                        " . $locationsOptions . "
                                    </select>
                                </div>
                            </div>
                        </div>
               ";
                } else {
                    if (count($locations) === 1) {
                        $html .= "<input type='hidden' name='return_location' value='" . $locations[0]->getId() . "' />";
                    }
                }
            }
        }
        return $html;
    }

    public function renderMessageInCaseOfOneLocation($locations): string
    {
        $html = "";
        if (is_array($locations) and count($locations) === 1) {
            $html .= "
                    <div class='hq-motors-input-wrapper'>
                            <h4>" . HQRentalsLocaleHelper::resolveTranslation('motors_reservation_form_pick_return_label') . "</h4>
                            <div class='stm_rent_form_fields'>
                                <h5>" . $locations[0]->getName() . "</h5>
                            </div>
                        </div>
                ";
        }
        return $html;
    }
}
