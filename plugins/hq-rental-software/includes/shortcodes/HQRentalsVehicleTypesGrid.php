<?php

namespace HQRentalsPlugin\HQRentalsShortcodes;

use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesVehicleClasses;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsVehicleTypesGrid extends HQRentalsVehicleGrid implements HQShortcodeInterface
{
    private $settings;

    public function __construct($params = null)
    {
        $this->queryVehicles = new HQRentalsDBQueriesVehicleClasses();
        $this->settings = new HQRentalsSettings();
        $this->assets = new HQRentalsAssetsHandler();
        if (!empty($params)) {
            $this->setParams($params);
        }
        add_shortcode('hq_rentals_vehicle_types_grid', array($this, 'renderShortcode'));
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
                'force_vehicles_by_rate'            =>  'false'
            ),
            $atts
        );
        if (count($atts)) {
            $this->setParams($atts);
        }
        $vehicles = $this->queryVehicles->allVehicleClasses();
        $fields = $this->queryVehicles->getAllCustomFieldsValues();
        HQRentalsAssetsHandler::loadVehicleGridAssets();
        HQRentalsAssetsHandler::loadVehicleTypesStyles();
        wp_enqueue_script('hq-tabs-js');
        return HQRentalsAssetsHandler::getHQFontAwesome() . "
            <div id='hq-vehicle-grid-with-types' data-cy='hq-vehicle-grid-with-types' class='elementor-widget-container hq-elementor-title'>
                <h2 class='elementor-heading-title elementor-size-default'>{$this->params['title_vehicle_grid']}</h2>		
            </div>
            <div id='hq-tabs'>
                <ul class='hq-tabs-wrapper'>
                    {$this->resolveTabs($fields)}
                </ul>
                {$this->resolveTabsContent($fields,$vehicles)}
            </div>
        ";
    }
    private function resolveTabs($customFields): string
    {
        $html = "";
        if (is_array($customFields) and count($customFields)) {
            foreach ($customFields as $field) {
                $idField = str_replace(' ', '', $field);
                $html .= "<li><a data-cy='trigger-{$idField}' class='trigger' href='#{$idField}'>{$field}</a></li>";
            }
        }
        return $html;
    }
    private function resolveTabsContent($fields, $vehicles): string
    {
        $html = "";
        if (is_array($vehicles) and count($vehicles) and is_array($fields) and count($fields)) {
            foreach ($fields as $field) {
                $filteredVehicles = array_filter($vehicles, function ($vehicle) use ($field) {
                    return $field == $vehicle->getCustomFieldForWebsite($this->settings->getVehicleClassTypeField());
                });
                $idField = str_replace(' ', '', $field);
                $html .= "
                    <div id='{$idField}' class='elementor-element elementor-widget elementor-widget-html'>
                    <div class='elementor-widget-container'>
                        <!-- Begin Loop - Tabs -->
                        <div id='hq-smart-vehicle-grid' data-cy='hq-smart-vehicle-grid'>
                            {$this->resolveVehicles($filteredVehicles)}  
                        </div>
                        <!-- End Loop - Tabs-->
                    </div>
                </div>
                ";
            }
        }
        return $html;
    }
    private function resolveVehicles($vehicles): string
    {
        $html = "";
        foreach ($vehicles as $vehicle) {
            $html .= $this->resolveSingleVehicleHTML(
                $vehicle,
                'data-cy="' . $vehicle->getCustomFieldForWebsite($this->settings->getVehicleClassTypeField()) . '"'
                . 'data-custom_field="' . $vehicle->getCustomFieldForWebsite($this->settings->getVehicleClassTypeField()) . '"'
            );
        }
        return $html;
    }
}
