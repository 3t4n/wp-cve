<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsHelpers\HQRentalsLocaleHelper;
use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsCarRentalSetting;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesCarRentalSetting;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsVendor\Carbon;

class HQRentalsVehicleGrid extends HQRentalsBaseShortcode implements HQShortcodeInterface
{

    protected $params = [
        'reservation_url_vehicle_grid' => '',
        'title_vehicle_grid' => '',
        'brand_id' => '',
        'disable_features_vehicle_grid' => 'no',
        'button_position_vehicle_grid' => '',
        'randomize_grid' => '',
        'number_of_vehicles' => '',
        'default_dates' => '',
        'force_vehicles_by_rate' => '',
        'step' => '3',
        'forced_locale' => '',
        'button_text' => 'RENT NOW'
    ];

    public function __construct($params = null)
    {
        if(!empty($params)){
            $this->setParams($params);
        }

        add_shortcode('hq_rentals_vehicle_grid', array($this, 'renderShortcode'));
    }
    public function renderShortcode($atts = []): string
    {
        $atts = shortcode_atts(
            array(
                'reservation_url_vehicle_grid'      => '',
                'title_vehicle_grid'                => '',
                'brand_id'                          => '',
                'disable_features_vehicle_grid'     => '',
                'button_position_vehicle_grid'      => '',
                'button_text'                       => 'RENT NOW',
                'randomize_grid'                    =>  'false',
                'number_of_vehicles'                =>  '',
                'default_dates'                     =>  'false',
                'force_vehicles_by_rate'            =>  'false',
                'forced_locale'                     => ''
            ),
            $atts
        );
        $this->setParams($atts);
        $vehiclesCode = $this->getVehiclesHTML();
        HQRentalsAssetsHandler::loadVehicleGridAssets();
        return '
    ' . HQRentalsAssetsHandler::getHQFontAwesome() . ' 
            <div class="elementor-widget-container hq-elementor-title">
                    <h2 class="elementor-heading-title elementor-size-default">' . $this->params['title_vehicle_grid'] . '</h2>		
            </div>
            <div class="elementor-element elementor-widget elementor-widget-html">
                <div class="elementor-widget-container">
                    <!-- Begin Loop -->
                    ' . $vehiclesCode . '
                    <!-- End Loop -->       
                </div>
            </div>
        ';
    }
    public function getVehiclesHTML()
    {
        $query = new HQRentalsDBQueriesVehicleClasses();
        if ($this->params['brand_id']) {
            $vehicles = $query->getVehiclesByBrand($this->params['brand_id']);
        } else {
            if ($this->params['force_vehicles_by_rate'] === 'true') {
                $vehicles = $query->allVehicleClasses(false);
            } else {
                $vehicles = $query->allVehicleClasses(true);
            }
        }
        if ($this->params['randomize_grid'] === 'true') {
            shuffle($vehicles);
        }

        $html = '';
        if (count($vehicles)) {
            $innerHTML = '';
            if (!empty($this->params['number_of_vehicles']) and is_numeric($this->params['number_of_vehicles'])) {
                foreach (array_chunk(array_slice($vehicles, 0, (int)$this->params['number_of_vehicles']), 3) as $vehiclesRow) {
                    $innerHTML .= $this->resolveVehicleRowHTML($vehiclesRow);
                }
            } else {
                foreach (array_chunk($vehicles, 3) as $vehiclesRow) {
                    $innerHTML .= $this->resolveVehicleRowHTML($vehiclesRow);
                }
            }

            $html .=
                '<div id="hq-smart-vehicle-grid">
                    ' . $innerHTML . '
                </div>';
            return $html;
        }
        return $html;
    }

    public function resolveVehicleRowHTML($vehiclesRow): string
    {
        $html = $this->resolveSingleRowHTML($vehiclesRow);
        return $html;
    }

    public function resolveSingleRowHTML($singleRow): string
    {
        $html = '';
        foreach (array_splice($singleRow, 0, 3) as $vehicle) {
            $html .= $this->resolveSingleVehicleHTML($vehicle);
        }
        return $html;
    }

    public function resolveSingleVehicleHTML($vehicle, $dataAttr = ''): string
    {
        $day = HQRentalsLocaleHelper::getTranslation('Day', $this->params['forced_locale']);
        if ($this->params['button_position_vehicle_grid'] === 'right') {
            $rateTag  = empty(
            $vehicle->getActiveRate()->daily_rate->amount_for_display
            ) ? "" :
                "<h3>{$vehicle->getActiveRate()->daily_rate->amount_for_display}/{$day}</h3>";
        } else {
            $rateTag  = "";
        }
        if ($this->params['button_position_vehicle_grid'] === 'left') {
            $rateTagLeft  = empty(
            $vehicle->getActiveRate()->daily_rate->amount_for_display
            ) ? "" :
                "<h3>{$vehicle->getActiveRate()->daily_rate->amount_for_display}/{$day}</h3>";
        } else {
            $rateTagLeft  = "";
        }
        $html = "
                <div id='hq-vehicle-class-{$vehicle->getId()}' class='vehicle-card' {$dataAttr}>
                    <div class='hq-list-image-wrapper'>
                        <img class='img-response' src='{$vehicle->getPublicImage()}'>
                    </div>
                    <div class='hq-vehicle-content-wrapper'>
                        <div class='hq-list-label-wrapper-inner'>
                            <h3>{$vehicle->getLabelForWebsite()}</h3>
                            {$rateTag}
                        </div>
                    </div>
                    <div style='width: 100%;'>
                        <div>
                            " . $this->resolveVehicleFeaturesHTML($vehicle) . "    
                        </div>
                        <div class='hq-grid-button-wrapper'>
                            <div 
                                class='bottom-info hq-grid-button-wrapper 
                                hq-grid-button-wrapper-{$this->params['button_position_vehicle_grid']}'>
                                <a class='hq-list-rent-button' 
                                    href='{$this->resolveGridLink($vehicle)}'>
                                    {$this->params['button_text']}
                                </a>
                                {$rateTagLeft}
                            </div>
                        </div>
                    </div>
                </div>
        ";
        return $html;
    }

    private function resolveGridLink($vehicle): string
    {
        return "{$this->params['reservation_url_vehicle_grid']}?target_step={$this->params['step']}&vehicle_class_id={$vehicle->id}{$this->resolveDefaultDates()}";
    }

    public function resolveVehicleFeaturesHTML($vehicle): string
    {
        $html = '';
        if (!($this->params['disable_features_vehicle_grid'] === 'yes')) {
            $features = $vehicle->getVehicleFeatures();
            if (is_array($features) and count($features)) {
                $html .= "<ul class='list-feature-listing'>";
                foreach ($features as $feature) {
                    $html .= $this->resolveFeatureHTML($feature);
                }
                $html .= "</ul>";
            }
        }
        return $html;
    }

    public function resolveFeatureHTML($feature): string
    {
        $html = "
                <li class='hq-elementor-li'>
                    <span class='icon-wrapper'><i aria-hidden='true' class='{$feature->icon}'></i> </span>
                    <span class='feature-label'>{$this->getLabelForFeature($feature)}</span>
                </li>";
        return $html;
    }
    public function resolveDefaultDates(): string
    {
        if ($this->params['default_dates'] === 'true') {
            $setting = new HQRentalsSettings();
            $format = $setting->getTenantDatetimeFormat();
            $carbon = new Carbon();
            $pick_up_date = Carbon::now()->addDay(1)->format($format);
            $return_date = Carbon::now()->addDay(2)->format($format);
            return '&pick_up_date=' . $pick_up_date . '&return_date=' . $return_date;
        }
        return '';
    }
}
