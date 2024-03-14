<?php

namespace Tussendoor\OpenRDW\Includes;

use WP_Widget;

/**
 * Our open rdw widget constructor, front-end, back-end and configuration manager
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 */

class VehiclePlateInformationWidget extends WP_Widget
{

    public $dot_config;

    /**
     * Constructor that sets our default settings.
     *
     * @since    2.0.0
     */
    public function __construct()
    {
        global $dot_config;
        $this->dot_config = $dot_config;

        parent::__construct(
            'open_rdw_widget', // Base ID
            'Open RDW Kenteken widget', // Name
            [
                'description' => esc_html__('Request data by means of license plate from the Open RDW.', 'open-rdw-kenteken-voertuiginformatie')
            ] // Arguments
        );
    }

    /**
     * Front-end of the widget
     *
     * @since    2.0.0
     * @param $args     All of the widget arguments.
     * @param $settings All of the saved settings.
     */
    public function widget($args, $settings)
    {
        $settings['allfields'] = VehiclePlateInformationFields::fields();

        if (isset($args['widget_id']) && isset($_POST[$args['widget_id']])) {
            $api = new VehiclePlateInformationAPI();

            $kenteken = $api->clean_license(sanitize_text_field($_POST[$args['widget_id']]));
            $kentekeninfo = $api->get_info(sanitize_text_field($_POST[$args['widget_id']]));
        } 
        if(!isset($args['widget_id'])){
            $args['widget_id'] = 'admin_view';
        }

        if (isset($settings['title'])) {
            $title = apply_filters('widget_title', $settings['title']);
        }

        include $this->dot_config['plugin.view'] . 'front/vehicle-plate-information-form.php';
    }

    /**
     * Back-end of the widget
     *
     * @since    2.0.0
     * @param array $settings The widget settings obviously.
     */
    public function form($settings)
    {
        $args['widget_id'] = 'admin_view';
        $settings['allfields'] = VehiclePlateInformationFields::fields();

        if (!isset($settings['title'])) {
            $settings['title'] = esc_html__('Request licence plate information', 'open-rdw-kenteken-voertuiginformatie');
        }
        if (!isset($settings['class'])) {
            $settings['class'] = 'open_rdw_class';
        }
        include $this->dot_config['plugin.view'] . 'admin/vehicle-plate-information-widget.php';
    }

    /**
     * This is responsible for saving the widget settings
     *
     * @since    2.0.0
     * @param  array $new_settings The new settings
     * @param  array $old_settings The old settings (which will be overwritten)
     * @return array Saves the new settings
     */
    public function update($new_settings, $old_settings)
    {
        return $new_settings;
    }
}
