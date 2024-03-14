<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsReservationFormByVehicleType implements HQShortcodeInterface
{
    public function __construct()
    {
        $this->front = new HQRentalsFrontHelper();
        $this->queryVehicle = new HQRentalsDBQueriesVehicleClasses();
        $this->locationQuery = new HQRentalsDBQueriesLocations();
        $this->assets = new HQRentalsAssetsHandler();
        $this->setting = new HQRentalsSettings();
        add_shortcode('hq_rentals_reservation_form_vehicle_types', array($this, 'renderShortcode'));
    }

    public function renderShortcode($atts): string
    {
        $this->assets->loadDatePickersReservationAssets(true);
        $field = str_replace("f", "", $this->setting->getVehicleClassTypeField());
        $atts = shortcode_atts(
            array(
                'reservation_url' => '',
                'render_warning' => 'false',
                'warning_message' => '',
                'submit_bottom_label' => 'Find a Car',
                'orientation' => 'vertical'
            ),
            $atts
        );
        //💸 No deposit required!
        HQRentalsAssetsHandler::loadVehicleTypesFormAssets();
        return HQRentalsAssetsHandler::getHQFontAwesomeForHTML() . "
        <link rel='preconnect' href='https://fonts.googleapis.com'>
        <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
        <link href='https://fonts.googleapis.com/css2?family=Poppins&display=swap' rel='stylesheet'>
        <div id='hq-reservation-vehicle-type'>
            {$this->resolveFormDueToOrientation($atts)}
        </div>
        ";
    }
    private function resolveLocations(): string
    {
        $locs = $this->locationQuery->allLocations();
        if (count($locs) == 1) {
            return "
            <input type='hidden' name='pick_up_location' value='{$locs[0]->getId()}' />
            <input type='hidden' name='return_location' value='{$locs[0]->getId()}' />
          ";
        } else {
            $options = "";
            foreach ($locs as $loc) {
                $options .= "<option value='{$loc->getId()}'>{$loc->getLabelForWebsite()}</option>";
            }
            return "
            <div class='hq-types-form-field-wrapper'>
                <div class='hq-types-form-label-wrapper'>
                    <label for='hq-pickup-location'>Pickup Location</label>
                </div>
                <div class='hq-types-form-input-wrapper'>
                    <select id='hq-pickup-location' name='pick_up_location'>
                        {$options}
                    </select>
                </div>
            </div>
            <div class='hq-types-form-field-wrapper'>
                <div class='hq-types-form-label-wrapper'>
                    <label for='hq-return-location'>Vehicle</label>
                </div>
                <div class='hq-types-form-input-wrapper'>
                    <select id='hq-return-location' name='return_location'>
                        {$options}
                    </select>
                </div>
            </div>
        ";
        }
    }
    private function getLocationOptions(): string
    {
        return $this->front->getLocationOptions();
    }
    private function resolveTypes(): string
    {
        $html = '';
        $fields = $this->queryVehicle->getAllCustomFieldsValues();
        foreach ($fields as $field) {
            $html .= "<option value='{$field}'>{$field}</option>";
        }
        return $html;
    }
    private function resolveWarning($renderWarning, $message): string
    {
        //💸 No deposit required!
        if ($renderWarning == 'true') {
            return "
                <div class='hq-types-warning-wrapper'>
                    <p>{$message}</p>
                </div>
                <style>
                    .hq-reservation-inner-wrapper{
                        border-top-left-radius: 0!important;
                        border-top-right-radius: 0!important;
                    }
                    .hq-types-orientation-horizontal form{
                        border-bottom-left-radius: 16px;
                        border-bottom-right-radius: 16px;
                    }
                </style>
            ";
        } else {
            return "
                <style>
                    .hq-types-orientation-horizontal form{
                        border-radius: 16px;
                    }
                </style>
            ";
        }
    }
    private function resolveFormDueToOrientation($atts): string
    {
        if ($atts['orientation'] === 'vertical') {
            return "
            {$this->resolveWarning($atts['render_warning'], $atts['warning_message'])}
            <div class='hq-reservation-inner-wrapper'>
                <form method='get' action='" . $atts['reservation_url'] . "'>
                    <div class='hq-types-form-inner-wrapper'> 
                        <div class='hq-types-form-field-wrapper'>
                            <div class='hq-types-form-label-wrapper'>
                                <label for='hq-type-vehicle-type'>Vehicle</label>
                            </div>
                            <div class='hq-types-form-input-wrapper hq-select-wrapper'>
                                <div class='hq-select-icon-wrapper'>
                                    <i class='fas fa-chevron-down'></i>
                                </div>
                                <select id='hq-type-vehicle-type' name='field_{$field}'>
                                    {$this->resolveTypes()}
                                </select>
                            </div>
                        </div>
                        {$this->resolveLocations()}
                        <div class='hq-types-dates'>
                            <div class='hq-types-form-field-wrapper-date'>
                                <div class='hq-types-form-label-wrapper'>
                                    <label for='hq-type-vehicle-type'>Pick-up Date</label>
                                </div>
                                <div class='hq-types-form-input-wrapper'>
                                    <input id='hq_pick_up_date' name='pick_up_date' />
                                </div>
                            </div>
                            <div class='hq-types-form-field-wrapper-time'>
                                <div class='hq-types-form-label-wrapper'>
                                    <label for='hq-type-vehicle-type'>Pick-up Time</label>
                                </div>
                                <div class='hq-types-form-input-wrapper hq-select-wrapper'>
                                    <div class='hq-select-icon-wrapper'>
                                        <i class='fas fa-chevron-down'></i>
                                    </div>
                                    <select id='hq-type-vehicle-type' name='pick_up_time'>
                                        {$this->front->getTimesForDropdowns('00:00','23:50','12:00','+15 minutes')}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='hq-types-dates'>
                            <div class='hq-types-form-field-wrapper-date'>
                                <div class='hq-types-form-label-wrapper'>
                                    <label for='hq-type-vehicle-type'>Return Date</label>
                                </div>
                                <div class='hq-types-form-input-wrapper'>
                                    <input id='hq_return_date' name='return_date' />
                                </div>
                            </div>
                            <div class='hq-types-form-field-wrapper-time'>
                                <div class='hq-types-form-label-wrapper'>
                                    <label for='hq-type-vehicle-type'>Return Time</label>
                                </div>
                                <div class='hq-types-form-input-wrapper hq-select-wrapper'>
                                    <div class='hq-select-icon-wrapper'>
                                        <i class='fas fa-chevron-down'></i>
                                    </div>
                                    <select id='hq-type-vehicle-type' name='return_time'>
                                        {$this->front->getTimesForDropdowns('00:00','23:50','12:00','+15 minutes')}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='hq-types-button-wrapper'>
                            <button type='submit'>{$atts['submit_bottom_label']}</button>
                            <input type='hidden' name='target_step' value='2' />
                            <input type='hidden' name='vehicle_class_custom_fields' value='{$field}'>
                        </div>
                    </div>
                </form>
            </div>
            ";
        } else {
            return "
            <div class='hq-reservation-inner-wrapper hq-types-orientation-horizontal'>
                {$this->resolveWarning($atts['render_warning'], $atts['warning_message'])}
                <form method='get' action='" . $atts['reservation_url'] . "'>
                    <div class='hq-types-form-inner-wrapper'> 
                        <div class='hq-types-form-field-wrapper hq-types-form-field-wrapper-horizontal'>
                            <div class='hq-types-form-label-wrapper'>
                                <label for='hq-type-vehicle-type'>Vehicle</label>
                            </div>
                            <div class='hq-types-form-input-wrapper hq-select-wrapper'>
                                <div class='hq-select-icon-wrapper'>
                                    <i class='fas fa-chevron-down'></i>
                                </div>
                                <select id='hq-type-vehicle-type' name='field_{$field}'>
                                    {$this->resolveTypes()}
                                </select>
                            </div>
                        </div>
                        {$this->resolveLocations()}
                        <div class='hq-types-form-field-wrapper-date'>
                            <div class='hq-types-form-label-wrapper'>
                                <label for='hq-type-vehicle-type'>Pick-up Date</label>
                            </div>
                            <div class='hq-types-form-input-wrapper'>
                                <input id='hq_pick_up_date' name='pick_up_date' />
                            </div>
                        </div>
                        <div class='hq-types-form-field-wrapper-time'>
                            <div class='hq-types-form-label-wrapper'>
                                <label for='hq-type-vehicle-type'>Pick-up Time</label>
                            </div>
                            <div class='hq-types-form-input-wrapper hq-select-wrapper'>
                                <div class='hq-select-icon-wrapper'>
                                    <i class='fas fa-chevron-down'></i>
                                </div>
                                <select id='hq-type-vehicle-type' name='pick_up_time'>
                                    {$this->front->getTimesForDropdowns('00:00','23:50','12:00','+15 minutes')}
                                </select>
                            </div>
                        </div>
                        <div class='hq-types-form-field-wrapper-date'>
                            <div class='hq-types-form-label-wrapper'>
                                <label for='hq-type-vehicle-type'>Return Date</label>
                            </div>
                            <div class='hq-types-form-input-wrapper'>
                                <input id='hq_return_date' name='return_date' />
                            </div>
                        </div>
                        <div class='hq-types-form-field-wrapper-time'>
                            <div class='hq-types-form-label-wrapper'>
                                <label for='hq-type-vehicle-type'>Return Time</label>
                            </div>
                            <div class='hq-types-form-input-wrapper hq-select-wrapper'>
                                <div class='hq-select-icon-wrapper'>
                                    <i class='fas fa-chevron-down'></i>
                                </div>
                                <select id='hq-type-vehicle-type' name='return_time'>
                                    {$this->front->getTimesForDropdowns('00:00','23:50','12:00','+15 minutes')}
                                </select>
                            </div>
                        </div>
                        <div class='hq-types-button-wrapper'>
                            <button type='submit'>{$atts['submit_bottom_label']}</button>
                            <input type='hidden' name='target_step' value='2' />
                            <input type='hidden' name='vehicle_class_custom_fields' value='{$field}'>
                        </div>
                    </div>
                </form>
            </div>
            ";
        }
    }
}
