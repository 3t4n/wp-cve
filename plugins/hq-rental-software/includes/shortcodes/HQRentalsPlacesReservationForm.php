<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsElementor\HQRentalsElementorAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsBaseShortcode;

class HQRentalsPlacesReservationForm extends HQRentalsBaseShortcode implements HQShortcodeInterface
{
    protected $params = [
        'reservation_url_places_form' => '',
        'orientation_places_form' => '',
        'support_for_custom_location' => '',
        'custom_location_label' => '',
        'minimum_rental_period' => 0,
        'google_country' => '',
        'google_map_center' => '',
        'google_map_center_radius' => '',
        'submit_button_label' => 'Book Now',
        'time_step' => '15',
        'forced_locale' => null,
        'brand_id' => null
    ];

    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->assets = new HQRentalsAssetsHandler();
        $this->front = new HQRentalsFrontHelper();
        $this->queryLocations = new HQRentalsDBQueriesLocations();
        add_shortcode('hq_rentals_places_reservation_form', array($this, 'renderShortcode'));
    }

    public function renderShortcode($params = []): string
    {
        $this->setParams($params);
        $key = $this->settings->getGoogleAPIKey();
        $this->assets->loadPlacesReservationAssets();
        $html = "";
        $minimumRental = "
            <script>
                var minimumDayRentals = " . $this->params['minimum_rental_period'] . ";
                var googleCountry = '" . $this->params['google_country'] . "';
                var googleMapCenter = '" . $this->params['google_map_center'] . "';
                var googleMapAddressRadius = '" . $this->params['google_map_center_radius'] . "';
                var hqLocale = '" . get_locale() . "';
                var timeStep = " . $this->params['time_step'] . "
            </script>
        ";
        $pickupLabel = HQRentalsLocaleHelper::getTranslation('Pick Up Location', $this->params['forced_locale']);
        $from = HQRentalsLocaleHelper::getTranslation('From', $this->params['forced_locale']);
        $returnLocation = HQRentalsLocaleHelper::getTranslation('Return Location', $this->params['forced_locale']);
        $until = HQRentalsLocaleHelper::getTranslation('Until', $this->params['forced_locale']);
        if (empty($key)) {
            echo "<p>Add Google Key</p>";
        } else {
            if ($this->params['orientation_places_form'] == 'horizontal') {
                $html = HQRentalsAssetsHandler::getHQFontAwesomeForHTML() . $minimumRental . "
            <div id='hq-place-form-desktop' data-cy='hq-place-form' class='hq-places-form'>
               <div id='hq-form-wrapper' class=''>
                  <form id='hq-form' method='get' action='{$this->params['reservation_url_places_form']}'>
                     <div class='hq-places-inner-wrapper'>
                        <div class='hq-places-input-wrapper'>
                            <div class='hq-places-input-inner-wrapper'>
                                <div class='hq-places-label-wrapper'>
                                    <label class='hq-places-label'>{$pickupLabel}</label> 
                                </div>
                                <div>
                                    <select name='pick_up_location' id='hq-pick-up-location'>
                                        {$this->resolveLocations()}
                                        {$this->resolveCustomLocation()}
                                    </select>
                                </div>
                            </div>
                            <div class='hq-pickup-custom-location'>
                                <input type='text' name='pick_up_location_custom' 
                                class='hq-places-auto-complete' id='pick-up-location-custom' />
                            </div>
                        </div>
                        <div class='hq-places-input-wrapper'>
                            <div class='hq-places-label-wrapper'>
                                <label class='hq-places-label'>{$from}</label> 
                            </div>
                            <div class='hq-places-date-time-wrapper'>
                                <div class='hq-places-times-input-wrapper'>
                                    <input type='text' name='pick_up_date' 
                                    class='hq-places-auto-complete' 
                                    id='hq-times-pick-up-date' data-cy='hq-pick-up-date' />
                                    <span class='hq-select-icon-wrapper'><i class='fas fa-clock'></i></span>
                                </div>
                            </div>
                        </div>
                        <div class='hq-places-input-wrapper'>
                            <div class='hq-places-input-inner-wrapper'>
                                <div class='hq-places-label-wrapper'>
                                    <label class='hq-places-label'>{$returnLocation}</label> 
                                </div>
                                <div>
                                    <select name='return_location' id='hq-return-location'>
                                        {$this->resolveLocations()}
                                        {$this->resolveCustomLocation()}
                                    </select>
                                </div>
                            </div>
                            <div class='hq-return-custom-location'>
                                <input type='text' name='return_location_custom' 
                                class='hq-places-auto-complete' id='return-location-custom' />
                            </div>
                        </div>
                        <div class='hq-places-input-wrapper'>
                            <div class='hq-places-label-wrapper'>
                                <label class='hq-places-label'>{$until}</label> 
                            </div>
                            <div class='hq-places-date-time-wrapper'>
                                <div class='hq-places-times-input-wrapper'>
                                    <input type='text' name='return_date' 
                                    class='hq-places-auto-complete' 
                                    id='hq-times-return-date' data-cy='hq-return-date' />
                                    <span class='hq-select-icon-wrapper'><i class='fas fa-clock'></i></span>
                                </div>
                            </div>
                        </div>
                        <div class='hq-places-input-wrapper hq-button-wrapper'>
                            <input type='hidden' name='target_step' value='2'>
                            <button type='submit' 
                                class='hq-places-submit-button'>{$this->params['submit_button_label']}</button>    
                        </div>
                     </div>
                  </form>
               </div>
            </div>  
        ";
            } else {
                $html = "
            " . HQRentalsAssetsHandler::getHQFontAwesomeForHTML() . $minimumRental . "
                <div class='hq-places-vertical-form-wrapper' data-cy='hq-place-form'>
                    <form method='get' id='hq-form' name='Booking' action='{$this->params['reservation_url_places_form']}'>
                        <div class='hq-places-vertical-form-item-wrapper'>
                                <label for='form-field-location'
                                       class='hq-smart-label'>{$pickupLabel}</label>
                                <div class='hq-places-dates-wrapper-vertical'>
                                    <select name='pick_up_location' id='hq-pick-up-location'>
                                        {$this->front->getLocationOptions()}
                                        {$this->resolveCustomLocation()}
                                    </select>
                                    <span class='hq-select-icon-wrapper-vertical'>
                                        <i class='fas fa-map-marked-alt'></i>
                                    </span>
                                </div>
                            </div>
                            <div class='hq-pickup-custom-location'>
                                <input type='text' name='pick_up_location_custom' 
                                    class='hq-places-auto-complete' id='pick-up-location-custom' />
                            </div>
                            <div class='hq-places-vertical-form-item-wrapper'>
                                <label for='form-field-location'
                                       class='hq-smart-label'>{$returnLocation}</label>
                                <div class='hq-places-dates-wrapper-vertical'>
                                    <select name='return_location' id='hq-return-location'>
                                        {$this->front->getLocationOptions()}
                                        {$this->resolveCustomLocation()}
                                    </select>
                                    <span class='hq-select-icon-wrapper-vertical'>
                                        <i class='fas fa-map-marked-alt'></i>
                                        </span>
                                </div>
                                <div class='hq-return-custom-location'>
                                    <input type='text' name='return_location_custom' 
                                        class='hq-places-auto-complete' id='return-location-custom' />
                                </div>
                            </div>
                            <div class='hq-places-vertical-form-item-wrapper hq-places-vertical-form-dates-wrapper'>
                                  <div>
                                        <label class='hq-places-label'>{$from}</label> 
                                    </div>
                                    <div class='hq-places-date-time-wrapper-vertical'>
                                        <div class='hq-places-times-input-wrapper hq-places-dates-wrapper-vertical'>
                                            <input type='text' name='pick_up_date' 
                                                class='hq-places-auto-complete' placeholder='Today' 
                                                id='hq-times-pick-up-date' required='required' />
                                            <span class='hq-select-icon-wrapper-vertical'>
                                                <i class='fas fa-calendar-alt'></i>
                                            </span>
                                        </div>
                                    </div>
                            </div>
                            <div class='hq-places-vertical-form-item-wrapper hq-places-vertical-form-dates-wrapper'>
                                  <div>
                                        <label class='hq-places-label'>{$until}</label> 
                                    </div>
                                    <div class='hq-places-date-time-wrapper-vertical'>
                                        <div class='hq-places-times-input-wrapper hq-places-dates-wrapper-vertical'>
                                            <input type='text' name='return_date' 
                                                class='hq-places-auto-complete' placeholder='Tomorrow' 
                                                id='hq-times-return-date' required='required' />
                                            <span class='hq-select-icon-wrapper-vertical'>
                                                <i class='fas fa-calendar-alt'></i>
                                            </span>
                                        </div>
                                    </div>
                            </div>
                            <div class='hq-places-vertical-button-wrapper'>
                                <button type='submit'
                                        class='hq-submit-button'>
                                       <span>
                                       <span class='elementor-button-icon'></span>
                                       <span class='elementor-button-text'>{$this->params['submit_button_label']}</span>
                                       </span>
                                </button>
                                <input type='hidden' name='target_step' value='2'>
                            </div>
                    </form>
                </div>
            ";
            }
        }
        return $html;
    }
    public function resolveCustomLocation(): string
    {
        if ($this->params['support_for_custom_location'] === 'true') {
            return "<option value='custom'>{$this->params['custom_location_label']}</option>";
        }
        return '';
    }
    public function resolveLocations(): string
    {
        $locations = empty($this->params['brand_id']) ? null : $this->queryLocations->getLocationsByBrand($this->params['brand_id']);
        return $this->front->getLocationOptions($locations);
    }
}
