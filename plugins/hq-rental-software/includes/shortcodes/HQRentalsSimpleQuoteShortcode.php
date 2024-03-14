<?php

/**
 * Created by PhpStorm.
 * User: Miguel Faggioni
 * Date: 12/8/2018
 * Time: 11:31 AM
 */

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsApi\HQRentalsApiConnector;
use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsSimpleQuoteShortcode extends HQBaseShortcode implements HQShortcodeInterface
{
    public function __construct()
    {
        $this->assets = new HQRentalsAssetsHandler();
        $this->helper = new HQRentalsFrontHelper();
        $this->front = new HQRentalsFrontHelper();
        $this->queryVehicles = new HQRentalsDBQueriesVehicleClasses();
        $this->settings = new HQRentalsSettings();
        add_shortcode('hq_rentals_simple_quote_form', array($this, 'renderShortcode'));
    }


    public function renderShortcode($atts): string
    {
        $atts = shortcode_atts(
            array(
                'brand_id' => '1',
                'minimum_rental_period' => '2',
                'step_time' => '15',
                'button_label' => 'Get a Quote!'
            ),
            $atts
        );
        $this->assets->loadQuoteFormAssets();
        $this->assets->resolveDatePickerAssetsFiles();
        $lang = $this->getTranslations();
        $key = $this->settings->getCaptchaKey();
        $html = empty($key) ?  "<h5>Google Recaptcha key is required. Please go the plugin settings and provide the required information.</h5>" : "
            <script>
                var minimumDayRentals = " . $atts['minimum_rental_period'] . ";
                var hqLocale = '" . get_locale() . "';
                var timeStep = " . $atts['step_time'] . ";
                var captchaKey = '" . $key . "';
            </script>
             <script src='https://www.google.com/recaptcha/api.js?render={$key}'></script>
            <div id='hq-place-form-desktop' data-cy='hq-place-form' class='hq-places-form hq-quote-form-component-wrapper'>
               <div id='hq-form-wrapper' class=''>
                  <form id='hq-quote-form' data-cy='hq-quote-form'>
                     <div class='hq-places-inner-wrapper'>
                        <div class='hq-quote-form-wrapper'>
                            <div class='hq-quote-single-item-wrapper'>
                                <div class='hq-places-input-inner-wrapper'>
                                    <div class='hq-places-label-wrapper'>
                                        <label class='hq-places-label'>{$lang['customer_email']}</label> 
                                    </div>
                                    <div>
                                        <input type='email' name='customer_email' 
                                        data-cy='customer_email'
                                        placeholder='user@domain.com'
                                        class='hq-places-auto-complete' id='hq-email' required='required' />
                                    </div>
                                </div>
                            </div>
                            <div class='hq-quote-single-item-wrapper'>
                                <div class='hq-places-input-inner-wrapper'>
                                    <div class='hq-places-label-wrapper'>
                                        <label class='hq-places-label'>{$lang['customer_name']}</label> 
                                    </div>
                                    <div>
                                        <input type='text' name='customer_name'
                                        data-cy='customer_name' 
                                        placeholder='Jhon Doe'
                                        class='hq-places-auto-complete' id='hq-name' required='required' />
                                    </div>
                                </div>
                            </div>
                            <div class='hq-quote-single-item-wrapper'>
                                <div class='hq-places-input-inner-wrapper'>
                                        <div class='hq-places-label-wrapper'>
                                            <label class='hq-places-label'>{$lang['pick_up_location']}</label> 
                                        </div>
                                        <div>
                                            <select name='pick_up_location' id='pick_up_location' 
                                                data-cy='pick_up_location' required='required'>
                                                {$this->front->getLocationOptions()}
                                            </select>
                                        </div>
                                </div>
                            </div>
                            <div class='hq-quote-single-item-wrapper'>
                                <div class='hq-places-input-inner-wrapper'>
                                        <div class='hq-places-label-wrapper'>
                                            <label class='hq-places-label'>{$lang['return_location']}</label> 
                                        </div>
                                        <div>
                                            <select data-cy='return_location' name='return_location' 
                                                id='return_location' required='required'>
                                                {$this->front->getLocationOptions()}
                                            </select>
                                        </div>
                                </div>
                            </div>
                            <div class='hq-quote-two-item-wrapper'>
                                <div class='hq-places-input-inner-wrapper'>
                                    <div class='hq-places-label-wrapper'>
                                        <label class='hq-places-label'>{$lang['from']}</label> 
                                    </div>
                                    <div>
                                        <input type='text' name='pick_up_date' data-cy='hq-pick-up-date'
                                        class='hq-places-auto-complete' id='hq-times-pick-up-date' required='required' />
                                    </div>
                                </div>
                                <div class='hq-places-input-inner-wrapper'>
                                    <div class='hq-places-label-wrapper'>
                                        <label class='hq-places-label'>{$lang['until']}</label> 
                                    </div>
                                    <div>
                                        <input type='text' name='return_date' data-cy='hq-return-date'
                                        class='hq-places-auto-complete' id='hq-times-return-date' required='required' />
                                    </div>
                                </div>
                            </div>
                            <div class='hq-quote-single-item-wrapper'>
                                <div class='hq-places-input-inner-wrapper'>
                                        <div class='hq-places-label-wrapper'>
                                            <label class='hq-places-label'>{$lang['vehicles']}</label> 
                                        </div>
                                        <div>
                                            <select data-cy='vehicle_class_id' name='vehicle_class_id' id='vehicle_class_id' required='required'>
                                                {$this->getVehiclesClassesOptions()}
                                            </select>
                                        </div>
                                </div>
                            </div>
                            <div id='hq-quote-button-wrapper' class='hq-quote-single-item-wrapper'>
                                <input type='hidden' name='brand_id' id='brand_id' value='{$atts['brand_id']}'>
                                <button type='submit'
                                    class='hq-places-submit-button'>{$atts['button_label']}</button>
                            </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
        ";
        return $html;
    }
    public function getVehiclesClassesOptions(): string
    {
        $html = "";
        foreach ($this->queryVehicles->allVehicleClasses(true) as $vehicle) {
            $html .= "<option value='{$vehicle->getId()}'>{$vehicle->getLabelForWebsite()}</option>";
        }
        return $html;
    }
    public function getTranslations(): array
    {
        return [
            'pick_up_location' => HQRentalsLocaleHelper::getTranslation('Pickup Location'),
            'return_location' => HQRentalsLocaleHelper::getTranslation('Return Location'),
            'customer_name' => HQRentalsLocaleHelper::getTranslation('Name'),
            'customer_email' => HQRentalsLocaleHelper::getTranslation('Email'),
            'from' => HQRentalsLocaleHelper::getTranslation('Pickup Date'),
            'until' => HQRentalsLocaleHelper::getTranslation('Return Date'),
            'vehicles' => HQRentalsLocaleHelper::getTranslation('Vehicle')
        ];
    }
}
