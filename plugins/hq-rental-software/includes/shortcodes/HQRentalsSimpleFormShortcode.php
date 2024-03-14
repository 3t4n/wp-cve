<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsThemes\HQRentalsThemeCustomizer;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesCarRentalSetting;

class HQRentalsSimpleFormShortcode
{
    private $primaryColor;

    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
        $this->primaryColor = HQRentalsThemeCustomizer::getThemeColor();
        $this->carRentalSetting = new HQRentalsDBQueriesCarRentalSetting();
        add_shortcode('hq_rentals_simple_reservation_form', array($this, 'render'));
    }


    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    public function render($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'reservation_url' => '',
                'my_reservation_url' => '',
                'button_text' => 'Check Availability',
                'title' =>  HQRentalsLocaleHelper::getTranslation('Reserve a Vehicle'),
                'target_step' => '3'),
            $atts
        );
        $reservation_url = $atts['reservation_url'];
        $my_reservation_url = $atts['my_reservation_url'];
        $button_text = $atts['button_text'];
        $title = $atts['title'];
        $target_step = $atts['target_step'];
        $this->assets->loadSimpleFormAssets();
        $baseURL = home_url();
        $locale = get_locale();
        $html =
            HQRentalsAssetsHandler::getHQFontAwesome() .
            "
            <link rel='preconnect' href='https://fonts.googleapis.com'>
            <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
            <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' rel='stylesheet'>
            <script>
                var formTimes = {$this->helper->getTimesForDropdownAsJSForSelect2('00:00','23:50','12:00', '+15 minutes')};
                var websiteURL = '{$baseURL}';
                var hqLocale = '{$locale}';
            </script>
            <style>
                #hq-simple-form .submit-button{
                    background-color:{$this->getPrimaryColor()};
                }
                
                #hq-simple-form a{
                    color: {$this->getPrimaryColor()};
                }
                .hq-checkbox-input-widget{
                    border: 2px solid {$this->primaryColor};
                }
                #hq-simple-form i{
                    color: {$this->primaryColor};
                }
                .hq-date-big-font{
                    color: {$this->primaryColor};
                }
                .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
                    color: {$this->getPrimaryColor()}!important;
                }
                #hq-alert-message,.select2-results__option--selectable{
                    color: {$this->getPrimaryColor()}!important;
                }
                .flatpickr-day.startRange,.flatpickr-day.endRange{
                    background-color: {$this->primaryColor} !important;
                    border-color: {$this->primaryColor} !important;
                }
                .flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)), 
                .flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)), 
                .flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)){
                    -webkit-box-shadow: -10px 0 0 {$this->primaryColor} !important;
                    box-shadow: -10px 0 0 {$this->primaryColor} !important;
                }
                {$this->resolveStylesForVehicleFieldChanges($target_step)}
            </style>
            <div id='hq-simple-form' data-cy='hq-simple-form'>
                {$this->resolveForm($reservation_url,$my_reservation_url,$title,$button_text,$target_step)}
            <div>
        ";
        return $html;
    }

    private function resolveForm($reservation_url, $my_reservation_url, $title, $button_text, $target_step)
    {
        $setting = new HQRentalsSettings();
        $html = "";
        if (empty($setting->getGoogleAPIKey())) {
            return "<h5 class='warning'>Please, enter a valid Google API Key.</h5>";
        } else {
            $or = HQRentalsLocaleHelper::getTranslation('Or');
            $view = HQRentalsLocaleHelper::getTranslation('View');
            $modify = HQRentalsLocaleHelper::getTranslation('Modify');
            $cancelReservation = HQRentalsLocaleHelper::getTranslation('Cancel Reservation');
            $requireFiled = HQRentalsLocaleHelper::getTranslation('Required Field');
            $returnToDiffLocation = HQRentalsLocaleHelper::getTranslation('Return to Different Location');
            $returnLocation = HQRentalsLocaleHelper::getTranslation('Return Location');
            $pickup = HQRentalsLocaleHelper::getTranslation('Pick Up');
            $return = HQRentalsLocaleHelper::getTranslation('Return');
            $select2Placeholder = HQRentalsLocaleHelper::getTranslation('City or Airport');
            $pickupLocation = HQRentalsLocaleHelper::getTranslation('Pick Up Location');
            $label = $pickup . ' & ' .  $returnLocation;
            $html .= HQRentalsAssetsHandler::getHQFontAwesomeForHTML() .
                "<form id='hq-simple-form-inner-wrapper' 
                    class='hq-simple-inner-form' action='{$reservation_url}' method='GET'>
                    <script>
                        var selectPlaceholder = '${select2Placeholder}';
                        var pickUpLocationLabel = '${pickupLocation}';
                        var pickupReturnLocation = '${label}';
                    </script>
                    <div class='hq-simple-header-wrapper'>
                        <div class='blank'></div>
                        <div class='hq-simple-header-wrapper'>
                            <span class='title-text'><h3>{$title}</h3></span>
                            <span class='middle-text'><p>{$or}</p></span>
                            <span class='anchor-text'>
                                <a href='{$my_reservation_url}'>{$view} / {$modify} / {$cancelReservation}</a>
                            </span>
                        </div>    
                    </div>
                    <div class='hq-field-wrapper'>
                        <div class='hq-number'>
                            <h3>1</h3>
                        </div>
                        <div class='hq-simple-outer-input-wrapper'>
                            <div class='hq-label-wrapper hq-required-field'>
                                <label for='pick_up_location' id='pick_up_location_label'>
                                    ${label}*
                                </label>
                                <p class='hq-required'>* ${requireFiled}</p>   
                            </div>
                            <div class='hq-input-wrapper hq-locations hq-location-pickup'>
                                <select name='pick_up_location_custom' 
                                    id='pick_up_location' data-cy='pick_up_location'></select>
                            </div>
                            {$this->resolveDiffLocationCheckbox($returnToDiffLocation)}
                            
                            <div id='hq-return-location-wrapper'>
                                <div class='hq-label-wrapper'>
                                    <label for='return_location'>
                                        ${returnLocation}*
                                    </label>
                                </div>
                                <div class='hq-input-wrapper hq-locations hq-location-pickup'>
                                    <select name='return_location_custom' 
                                        id='return_location' data-cy='return_location'></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id='hq-dates-section' data-cy='hq-dates-section'>
                        <div class='hq-field-wrapper-dates'>
                        <div class='hq-number'>
                            <h3>2</h3>
                        </div>
                        <div class='hq-field-wrapper-dates-inner'>
                            <div class='hq-outer-dates-group'>
                            <div class='hq-simple-outer-dates-wrapper pick-up-dates'>
                            <div class='hq-label-wrapper'>
                                <label for='pick_up_date'>
                                    ${pickup} *
                                </label>
                            </div>
                            <div class='hq-input-wrapper-date'>
                                <button
                                    class='hq-date-input-button'
                                    id='hq-pick-up-date-button'
                                    data-cy='hq-pick-up-date-button'
                                >
                                    <div class='hq-date-input-button-inner-wrapper'>
                                        <div class='hq-date-input-day'>
                                            <p id='pick_up_date_day' class='hq-date-big-font'>09</p>
                                        </div>
                                        <div class='hq-date-input-month-outer-wraper'>
                                            <div>
                                                <p id='pick_up_date_month' class='hq-date-small-font'>Jul</p>
                                            </div>
                                            <div>
                                                <p id='pick_up_date_year' class='hq-date-small-font'>2022</p>
                                            </div>
                                        </div>
                                        <div class='hq-date-icon-wrapper'>
                                            <i class='fas fa-chevron-down'></i>
                                        <div>
                                    </div>
                                </button>
                                <button
                                    id='hq-pick-up-time-button'
                                    class='hq-date-input-button'
                                >
                                    <div id='hq-pick-up-time-inner-wrapper' 
                                        class='hq-date-input-button-inner-wrapper'>  
                                        <div class='hq-date-input-day'>
                                            <p id='pick_up_date_hour' class='hq-date-big-font'>12</p>
                                        </div>
                                        <div class='hq-date-input-month-outer-wraper'>
                                            <div>
                                                <p id='pick_up_date_minutes' class='hq-date-small-font'>:00</p>
                                            </div>
                                            <div>
                                                <p id='pick_up_date_meridian' class='hq-date-small-font'>PM</p>
                                            </div>
                                        </div>
                                        <div class='hq-date-icon-wrapper'>
                                            <i class='fas fa-chevron-down'></i>
                                        <div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class='hq-simple-arrow-wrapper'>
                                    <i class='fal fa-arrow-right'></i>
                                </div>
                        <div class='hq-simple-outer-dates-wrapper return-dates'>
                            <div class='hq-label-wrapper'>
                                <label for='return_date'>
                                    ${return} *
                                </label>
                            </div>
                            <div class='hq-input-wrapper-date'>
                                <button
                                    id='hq-return-date-button'
                                    class='hq-date-input-button'
                                >
                                    <div class='hq-date-input-button-inner-wrapper'>
                                        <div class='hq-date-input-day'>
                                            <p id='return_date_day' class='hq-date-big-font'>12</p>
                                        </div>
                                        <div class='hq-date-input-month-outer-wraper'>
                                            <div>
                                                <p id='return_date_month' class='hq-date-small-font'>Aug</p>
                                            </div>
                                            <div>
                                                <p id='return_date_year' class='hq-date-small-font'>2022</p>
                                            </div>
                                            <div class='hq-date-icon-wrapper'>
                                                <i class='fas fa-chevron-down'></i>
                                            <div>
                                        </div>
                                    </div>
                                </button>
                                <button
                                    id='hq-return-time-button'
                                    class='hq-date-input-button'
                                >
                                    <div id='hq-return-time-inner-wrapper' class='hq-date-input-button-inner-wrapper'>  
                                        <div class='hq-date-input-day'>
                                            <p id='return_date_hour' class='hq-date-big-font'>12</p>
                                        </div>
                                        <div class='hq-date-input-month-outer-wraper'>
                                            <div>
                                                <p id='return_date_minutes' class='hq-date-small-font'>:00</p>
                                            </div>
                                            <div>
                                                <p id='return_date_meridian' class='hq-date-small-font'>PM</p>
                                            </div>
                                            <div class='hq-date-icon-wrapper'>
                                                <i class='fas fa-chevron-down'></i>
                                            <div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        </div>
                        {$this->resolveVehicleClassField($target_step)}
                        {$this->resolveSubmitButton($target_step, $button_text)}
                        </div>
                    </div>
                    </div>
                    <div id='hq-alert-message'> 
                        <h6 class='hq-alert-text'>All fields are required</h6>     
                    </div>
                    {$this->resolveSubmitButtonOnBottom($target_step, $button_text)}
                    <input id='target_step' name='target_step' value='{$target_step}' type='hidden' />
                    <input id='pick_up_date' data-cy='pick_up_date' name='pick_up_date' value='' type='hidden' />
                    <input id='return_date' data-cy='return_date' name='return_date' value='' type='hidden' />
                    <input id='pick_up_time' data-cy='pick_up_time' name='pick_up_time' value='' type='hidden' />
                    <input id='return_time' data-cy='return_time' name='return_time' value='' type='hidden' />
                    <input id='form_init' data-cy='form_init' name='form_init' value='' type='hidden' />
                </form>";
        }
        return $html;
    }
    private function resolveVehicleClassField($target_step): string
    {
        $html = "";
        if ($target_step == '3') {
            $vehicle = HQRentalsLocaleHelper::getTranslation('Vehicle Class');
            $all = HQRentalsLocaleHelper::getTranslation('All Vehicles');
            $html = "
                <div class='hq-simple-outer-dates-wrapper vehicle-class-wrapper'>
                    <div class='hq-label-wrapper'>
                        <label for='vehicle_class_id'>
                            ${vehicle}*
                        </label>
                    </div>
                    <div id='hq-vehicle-class-id' class='hq-input-wrapper-date'>
                        <select name='vehicle_class_id' id='vehicle_class_id'>
                            <option value='0'>${all}</option>
                            {$this->resolveVehicleClassesOptions()}
                        </select>
                    </div>                
                </div>
        ";
        }
        return $html;
    }

    private function resolveVehicleClassesOptions(): string
    {

        $query = new HQRentalsDBQueriesVehicleClasses();
        $html = "";
        foreach ($query->allVehicleClasses(true) as $vehicle) {
            $html .= "<option value='{$vehicle->getId()}'>{$vehicle->getLabelForWebsite()}</option>";
        }
        return $html;
    }
    private function resolveStylesForVehicleFieldChanges($target_step): string
    {
        $html = '';
        if ($target_step != '3') {
            $html = "
                .hq-outer-dates-group{
                    width:75%;
                }
                .return-dates{
                    margin-right: 0 !important;
                }
            ";
        }
        return $html;
    }
    private function resolveSubmitButton($target_step, $button_text): string
    {
        $html = "";
        if ($target_step != '3') {
            $html = "
                <div class='hq-simple-outer-dates-wrapper vehicle-class-wrapper vehicle-class-wrapper-bottom'>
                    <div class='hq-simple-submit-wrapper-large'>
                        <button type='submit' class='submit-button'>{$button_text}</button>        
                    </div>
                </div>
        ";
        }
        return $html;
    }
    private function resolveSubmitButtonOnBottom($target_step, $button_text): string
    {
        $html = "";
        if ($target_step == '3') {
            $html = "
                <div class='hq-simple-submit-wrapper'>
                    <div class='hq-simple-submit-inner-wrapper'>
                        <button type='submit' class='submit-button'>{$button_text}</button>
                    </div>
                </div>
        ";
        }
        return $html;
    }
    private function resolveDiffLocationCheckbox($returnToDiffLocation): string
    {
        if ($this->carRentalSetting->getCarRentalSetting('allow_return_outside_office')->getSetting() == 1) {
            return "
                <div class='hq-input-wrapper-checkbox' data-cy='diff-location-checkbox'>
                    <div class='hq-checkbox-input-widget'>
                        <div class='hq-checkbox-container'>
                            <i class='fas'></i>
                        </div>
                    </div>
                    <input type='hidden' name='same_locations' id='same_locations' value='' />
                    <label class='hq-checkbox-label' for=''>${returnToDiffLocation}</label>
                </div>
            ";
        }
        return "";
    }
}
