<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsElementor\HQRentalsElementorAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsFrontHelper;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesLocations;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsThemes\HQRentalsThemeCustomizer;

class HQWheelsberrySliderShortcode
{
    private $title;
    private $button_text;
    private $reservation_url;

    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
        $this->assets = new HQRentalsAssetsHandler();
        $this->front = new HQRentalsFrontHelper();
        $this->queryVehicles = new HQRentalsDBQueriesVehicleClasses();
        $this->queryLocations = new HQRentalsDBQueriesLocations();
        add_shortcode('hq_wheelsberry_reservation_form', array($this, 'renderShortcode'));
    }
    public function renderShortcode($atts = [])
    {
        $atts = shortcode_atts(
            array(
                'title' => '',
                'sub_title' => '',
                'form_title' => '',
                'form_sub_title' => '',
                'button_text' => esc_html__('Continue Booking', 'hq-wordpress'),
                'reservation_url' => '/reservations/',
                'render_form'   => 'true',
                'target_step'   => '3',
                'render_vehicle_field' => 'true'
            ),
            $atts
        );
        $this->assets->loadOwlCarouselAssets();
        $this->assets->loadWheelsberryCSS();
        $this->assets->loadWheelsberrySliderAssets();
        $vehicle_classes = $this->queryVehicles->allVehicleClasses(true);
        $locations = $this->queryLocations->allLocations();
        $themeColor = HQRentalsThemeCustomizer::getThemeColor();
        $slider_title = $atts['title'];
        $slider_subtitle = $atts['sub_title'];
        $form_title = $atts['form_title'];
        $form_subtitle = $atts['form_sub_title'];
        $render_form = $atts['render_form'];
        $reservation_url = $atts['reservation_url'];
        $target_step = $atts['target_step'];
        $render_vehicle_field = $atts['render_vehicle_field'];
        $image = HQRentalsThemeCustomizer::getTenantLogoURL();
        $imageHTML = empty($image) ? "" : "
            <div class='branding-logo-w'>
                <img class='branding-img' src='{$image}' />
            </div>
        ";
        $html = HQRentalsAssetsHandler::getHQFontAwesome() . "
            <link rel='preconnect' href='https://fonts.googleapis.com'> 
            <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
            <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap' 
            rel='stylesheet'>
            <style>
                #hq-wheelsberry-slider{
                    background-color: {$themeColor};
                    font-family: Montserrat, Open Sans,serif !important;
                }
                .cars-slider:after {
                    border-top-color: {$themeColor} !important;
                }
            </style>
            <div id='hq-wheelsberry-slider' data-cy='hq-wheelsberry-slider' class='cars-slider' id='cars-slider'>
                <div class='car-slider__title-wrapper om-container'>
                    <div class='om-container__inner'>
                        <h2 class='cars-slider__title'>{$slider_title}</h2>
                        <div class='h-subtitle cars-slider__subtitle'>{$slider_subtitle}</div>
                    </div>
                </div>
                <div id='hq-carousel' class='om-container owl-carousel'>
                    {$this->renderVehiclesOnSlider($vehicle_classes, $render_form, $reservation_url)}
                </div>
            </div>
            {$this->resolveForm(
                $render_form, 
                $imageHTML, 
                $form_title, 
                $form_subtitle,
                $vehicle_classes,
                $locations,
                $reservation_url, 
                $target_step,$render_vehicle_field
            )}
        ";
        return $html;
    }
    private function resolveForm(
        $resolve_form,
        $imageHTML,
        $form_title,
        $form_subtitle,
        $vehicle_classes,
        $locations,
        $reservation_url,
        $target_step,
        $render_vehicle_field
    ): string {
        if ($resolve_form == 'true') {
            return "<!--Begin Form-->
            <div class='reservation reservation--full hq-reservation-form-wrapper' id='reservation'>
                <div class='reservation-form reservation-form-hq'>
                    {$imageHTML}
                    <div class='om-container'>
                        <div class='om-container__inner'>
                            <div class='reservation-form__inner'>
                                <div class='reservation-form__titles'>
                                    <h2 class='reservation-form__title'>{$form_title}</h2>
                                    <div class='h-subtitle reservation-form__subtitle'>{$form_subtitle}</div>
                                </div>
                                <form id='hq-wheelsberry-slider-form' 
                                    data-cy='hq-wheelsberry-slider-form' action='{$reservation_url}' method='get'>
                                    {$this->resolveVehicleClassId($render_vehicle_field, $vehicle_classes)}
                                    <div class='reservation-form__more'>
                                        <div class='reservation-form__line reservation-form__set reservation-form__pick-up'>
                                            <div class='reservation-form__pick-up-location reservation-form__location'>
                                                <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                    <label for='reservation-form__pick-up-location-select' 
                                                    class='reservation-form__label reservation-form__pick-up-location-label 
                                                    reservation-form__location-label'>Pick-up</label>
                                                    <select id='hq-pick-up-location' 
                                                    data-cy='hq-wheelsberry-slider-form-pick-up-location' 
                                                    name='pick_up_location' class='reservation-form__pick-up-time-select' 
                                                    data-placeholder='Choose a location' required='required'>
                                                        <option>Select Location</option>
                                                        {$this->resolveOptionsForLocations($locations)}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='reservation-form__pick-up-date reservation-form__date'>
                                                <div 
                                                    class='reservation-form__field-inner 
                                                    hq-reservation-item-inner-wrapper'>
                                                    <label for='reservation-form__pick-up-date-input' 
                                                    class='reservation-form__label reservation-form__pick-up-date-label 
                                                    reservation-form__date-label'>Pick-up</label>
                                                    <div class='reservation-form__date-wrapper'>
                                                        <input type='text' name='pick_up_date' readonly='readonly' 
                                                        placeholder='Choose a date' 
                                                        class='reservation-form__pick-up-date-input' 
                                                        id='reservation-form__pick-up-date-input' 
                                                        data-date-format='m/d/y' required='required' />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='reservation-form__pick-up-time reservation-form__time'>
                                                <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                    <select name='pick_up_time' 
                                                    class='reservation-form__pick-up-time-select' 
                                                    data-cy='hq-wheelsberry-slider-form-pick-up-time' 
                                                    id='reservation-form__pick-up-time-select' required='required'>
                                                        {$this->front->getTimesWithFormat('00:00','23:50')}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='reservation-form__line reservation-form__set reservation-form__drop-off'>
                                            <div class='reservation-form__drop-off-location reservation-form__location'>
                                                <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                    <label for='reservation-form__pick-up-location-select' 
                                                    class='reservation-form__label reservation-form__pick-up-location-label 
                                                    reservation-form__location-label'>Drop-off</label>
                                                    <select id='hq-return-location' 
                                                    data-cy='hq-wheelsberry-slider-form-return-location' 
                                                    name='return_location' class='reservation-form__pick-up-time-select' 
                                                    data-placeholder='Choose a location' required='required' >
                                                        <option>Select Location</option>
                                                        {$this->resolveOptionsForLocations($locations)}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='reservation-form__drop-off-date reservation-form__date'>
                                                <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                    <label for='reservation-form__drop-off-date-input' 
                                                    class='reservation-form__label reservation-form__drop-off-date-label 
                                                    reservation-form__date-label'>Drop-off</label>
                                                    <div class='reservation-form__date-wrapper'>
                                                        <input type='text' name='return_date' readonly='readonly' 
                                                        placeholder='Choose a date' class='reservation-form__drop-off-date-input' 
                                                        id='reservation-form__drop-off-date-input' data-date-format='m/d/y' />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='reservation-form__drop-off-time reservation-form__time'>
                                                <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                    <select name='return_time' 
                                                        class='reservation-form__drop-off-time-select' 
                                                        data-cy='hq-wheelsberry-slider-form-return-time' 
                                                        id='reservation-form__drop-off-time-select'>
                                                        {$this->front->getTimesWithFormat('00:00','23:50')}
                                                    </select>                                                
                                                </div>
                                            </div>
                                        </div>
                                        <div class='reservation-form__line reservation-form__required-notice'>
                                            <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                <div class='reservation-form__required-notice-box'>
                                                Please fill in all required fields
                                                </div>
                                            </div>
                                        </div>
                                        <div class='reservation-form__line reservation-form__submit'>
                                            <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                                                <input type='hidden' name='target_step' value='{$target_step}' />
                                                <input type='submit' class='reservation-form__submit-button' 
                                                    id='reservation-form__submit-button' value='Continue booking' />
                                                <circle class='path' cx='24' cy='24' 
                                                    r='20' fill='none' stroke='#fff' stroke-width='4'>
                                                <animate attributeName='stroke-dasharray' attributeType='XML' 
                                                    from='1,200' to='89,200' values='1,200; 89,200; 89,200' 
                                                    keyTimes='0; 0.5; 1' dur='1.5s' repeatCount='indefinite' />
                                                <animate attributeName='stroke-dashoffset' 
                                                    attributeType='XML' from='0' to='-124' values='0; -35; -124' 
                                                    keyTimes='0; 0.5; 1' dur='1.5s' repeatCount='indefinite' />
                                                <animateTransform attributeName='transform' attributeType='XML'
                                                    type='rotate' from='0 24 24' to='360 24 24' 
                                                    dur='3s' repeatCount='indefinite'/>
                                                </circle>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function setListeners(baseValue, changedValue){
                    var pickupLocation = document.getElementById(baseValue);
                    pickupLocation.addEventListener('change', function(){
                        var pickupLocation = document.getElementById(baseValue);
                        var returnLocation = document.getElementById(changedValue);
                        returnLocation.value = pickupLocation.value; 
                    });
                }
                setListeners('hq-pick-up-location','hq-return-location');
                setListeners('reservation-form__pick-up-time-select','reservation-form__drop-off-time-select');
            </script>
            <!--End Form-->";
        }
        return "";
    }
    private function resolveVehicleClassId($render_vehicle_field, $vehicle_classes): string
    {
        if ($render_vehicle_field == 'true') {
            return "
                <div class='reservation-form__line reservation-form__car'>
                    <div class='reservation-form__field-inner hq-reservation-item-inner-wrapper'>
                        <select class='reservation-form__car-select' 
                            id='reservation-form__car-select' name='vehicle_class_id' required='required'>
                            {$this->resolveOptionsForClasses($vehicle_classes)}
                        </select>
                    </div>
                </div>
            ";
        } else {
            return '';
        }
    }

    private function getTitle($classTitle)
    {
        return preg_replace('/\s/', '<span class="cars - slider__model - br"><br></span>', $classTitle);
    }

    private function renderVehiclesOnSlider($vehicles, $resolve_form, $reservation_url)
    {
        if (is_array($vehicles) and count($vehicles)) {
            $html = '';
            foreach ($vehicles as $index => $vehicle) {
                $priceHTML = "";
                $settings = new HQRentalsSettings();
                $dailyRate = '';
                if ($settings->getOverrideDailyRateWithCheapestPriceInterval() === 'true') {
                    $interval = $vehicle->getCheapestPriceIntervalForWebsite();
                    // need this for price symbol
                    $rate = $vehicle->getActiveRate()->daily_rate;
                    $dailyRate = !empty($interval->price) ?
                        ("<span class='cars-slider__item-price hq-upper-tag'>as low as</span>
                            <span class='omcr-price-currency hq-wheelsberry-daily-tag'>
                                {$rate->currency_icon}{$interval->price} daily
                            </span>
                        ") : "";
                } else {
                    $dailyRate = !empty($vehicle->getActiveRate()->daily_rate->amount_for_display) ?
                        ("<span class='cars-slider__item-price hq-upper-tag'> as low as </span>
                            <span class='omcr-price-currency hq-wheelsberry-daily-tag'>
                                {$vehicle->getActiveRate()->daily_rate->amount_for_display} daily
                            </span>"
                        ) : "";
                }
                $priceHTML = $dailyRate;
                $priceHTML .= !empty($vehicle->getActiveRate()->daily_rate->amount_for_display) ?
                    ("<span class='omcr-price-currency hq-wheelsberry-separator'> |</span> 
                        <span class='omcr-price-currency hq-wheelsberry-weekly-tag'>
                            {$vehicle->getActiveRate()->weekly_rate->amount_for_display} weekly
                        </span>"
                    ) : "";
                $html .= "
                            <div id='hq-vehicle-wheelsberry-{$vehicle->id}' 
                                data-cy='hq-vehicle-class' 
                                data-vehicle-class-id='{$vehicle->id}'
                                data-vehicle-class-index='{$index}'
                                class='cars-slider__item-inner om-container__inner'>
                                <div class='cars-slider__item-description'>
                                    <div class='cars-slider__item-category'>{$vehicle->name}</div>
                                    <h3 class='cars-slider__model'>
                                        <span class='cars-slider__model-inner'>
                                            {$this->getTitle($vehicle->getLabelForWebsite())}
                                        </span>
                                        </h3>
                                    <div class='cars-slider__item-description-sep'></div>
                                    <div class='short-des'><p>{$vehicle->getShortDescriptionForWebsite()}</p></div>
                                    <div class='cars-slider__item-price'>
                                        <span class='cars-slider__item-price-value'>{$priceHTML}</span>
                                    </div>
                                    <div class='cars-slider__item-reserve'>
                                        <a href='{$this->resolveURLOnSlider($resolve_form,$reservation_url)}'>
                                            <span class='cars-slider__item-reserve-button' 
                                                data-car-id='{$vehicle->getId()}'>
                                                Reserve Now
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class='cars-slider__item-image hq-wheelsberry-image-wrapper'>
                                    <img class='img-responsive' 
                                        src='{$vehicle->getPublicImage()}' 
                                        alt='{$vehicle->getLabelForWebsite()}' />
                                </div>
                                <div class='cars-slider__item-options'>
                                    <div class='cars-slider__item-options-inner'>
                                        <!-- Features -->
                                        {$this->resolveFeatures($vehicle->getVehicleFeatures())}
                                    </div>
                                </div>
                                <div class='cars-slider__item-reserve-mobile'>
                                    <a href='{$this->resolveURLOnSlider($resolve_form,$reservation_url)}'>
                                        <span class='cars-slider__item-reserve-button' 
                                            data-car-id='{$vehicle->getId()}'>Reserve Now</span>
                                    </a>
                                </div>
                            </div>
                ";
            }
            return $html;
        }
        return '';
    }
    private function resolveURLOnSlider($resolve_form, $reservation_url)
    {
        if ($resolve_form == 'true') {
            return '#reservation';
        } else {
            return $reservation_url;
        }
    }

    private function resolveFeatures($features)
    {
        if (is_array($features) and count($features)) {
            $html = '';
            foreach (array_slice($features, 0, 5) as $feature) {
                $html .= "
                    <div class='cars-slider__item-option car-option'>
                        <i class='cars-slider__hq-feature-icon {$feature->icon}'></i>
                        <span class='cars-slider__item-option-label'>{$feature->label}</span>
                    </div>       
                ";
            }
            return $html;
        }
        return '';
    }

    private function resolveOptionsForClasses($vehicles)
    {
        if (is_array($vehicles) and count($vehicles)) {
            $html = '';
            foreach ($vehicles as $vehicle) {
                $html .= "<option value='{$vehicle->getId()}'>{$vehicle->getLabelForWebsite()}</option>";
            }
            return $html;
        }
        return '';
    }

    private function resolveOptionsForLocations($locations)
    {
        if (is_array($locations) and count($locations)) {
            $html = '';
            foreach ($locations as $location) {
                $html .= "<option value='{$location->getId()}'>{$location->getName()}</option>";
            }
            return $html;
        }
        return '';
    }
}
