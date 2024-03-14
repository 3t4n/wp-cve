<?php

namespace HQRentalsPlugin\HQRentalsActions\elementor;

use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;
use ElementorPro\Modules\Forms\Widgets\Form;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesLocations;

class HQElementorActions
{
    public function __construct()
    {
        add_action('elementor/frontend/widget/before_render', array($this, 'handleDropdownForLocations'));
        add_action('elementor_pro/forms/validation', array($this, 'handleRedirectOutside'), 10, 2);
        add_action('elementor/elements/categories_registered', array($this, 'addingCustomCategories'));
    }

    public function handleDropdownForLocations($form)
    {
        if ($form instanceof Form) {
            $settings = $form->get_settings('form_fields');
            $dirty = false;
            foreach ($settings as $key => $setting) {
                if (isset($setting['field_options']) && $setting['field_options'] === 'hqwp_locations') {
                    $dirty = true;
                    $locations = new HQRentalsQueriesLocations();
                    $settings[$key]['field_options'] = implode(PHP_EOL, array_map(function ($location) {
                        return $location->name . '|' . $location->id;
                    }, $locations->allLocations()));
                }
            }
            if ($dirty) {
                $form->set_settings('form_fields', $settings);
            }
        }
    }

    /**
     * @param Form_Record $record
     * @param Ajax_Handler $ajax_handler
     */
    public function handleRedirectOutside($record, $ajax_handler)
    {
        $parameters = array_filter([
            'pick_up_location' => $this->getValueFromRecord($record, 'pick_up_location'),
            'return_location' => $this->getValueFromRecord($record, 'return_location'),
            'pick_up_date' => $this->getValueFromRecord($record, 'pick_up_date'),
            'pick_up_time' => $this->getValueFromRecord($record, 'pick_up_time'),
            'return_date' => $this->getValueFromRecord($record, 'return_date'),
            'return_time' => $this->getValueFromRecord($record, 'return_time'),
            'target_step' => $this->getValueFromRecord($record, 'target_step', 2),
        ]);
        $redirect = get_site_url(null, $this->getValueFromRecord($record, 'booking_page'));
        $ajax_handler->is_success = true;
        $ajax_handler->data = [
            'redirect_url' => $redirect . '?' . http_build_query($parameters),
        ];
    }

    protected function getValueFromRecord($record, $field, $default = null)
    {
        $value = $record->get_field([
            'id' => $field,
        ]);

        return isset($value[$field]['value']) ? $value[$field]['value'] : $default;
    }
    public function addingCustomCategories($elements_manager)
    {

        $elements_manager->add_category(
            'hq-rental-software',
            [
                'title' => __('HQ Rental Software', 'hq-rental-software'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
}
