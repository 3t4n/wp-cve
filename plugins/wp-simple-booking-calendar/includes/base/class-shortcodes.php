<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPSBC_Shortcodes
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {

        // Register the single calendar shortcode
        add_shortcode('wpsbc', array(__CLASS__, 'single_calendar'));

        // Register the old shortcode as well
        add_shortcode('sbc', array(__CLASS__, 'single_calendar'));

    }

    /**
     * The callback for the WPSBC single calendar shortcode
     *
     * @param array $atts
     *
     */
    public static function single_calendar($atts)
    {

        // Shortcode default attributes
        $default_atts = array(
            'id' => 1,
            'title' => 'yes',
            'legend' => 'no',
            'legend_position' => '',
            'language' => 'auto',
        );

        // Shortcode attributes
        $atts = shortcode_atts($default_atts, $atts);

        // Calendar outputter default arguments
        $default_args = wpsbc_get_calendar_output_default_args();

        // Translating values from the shortcode attributes to the calendar arguments
        $args = array(
            'show_title' => (!empty($atts['title']) && $atts['title'] == 'yes' ? 1 : 0),
            'show_legend' => (!empty($atts['legend']) && $atts['legend'] == 'yes' ? 1 : 0),
            'legend_position' => $atts['legend_position'],
            'current_month' => current_time('n'),
            'current_year' => current_time('Y'),
            'language' => ($atts['language'] == 'auto' ? wpsbc_get_locale() : $atts['language']),
        );

        // Remove legend_position if it's empty
        if (empty($args['legend_position'])) {
            unset($args['legend_position']);
        }

        // Calendar arguments
        $calendar_args = wp_parse_args($args, $default_args);

        // Calendar id
        $calendar_id = (!empty($atts['id']) ? (int) $atts['id'] : 0);

        // Calendar
        $calendar = wpsbc_get_calendar($calendar_id);

        if (is_null($calendar)) {

            $output = '<p>' . __('Calendar does not exist.', 'wp-simple-booking-calendar') . '</p>';

        } else {

            // Initialize the calendar outputter
            $calendar_outputter = new WPSBC_Calendar_Outputter($calendar, $calendar_args);

            $output = $calendar_outputter->get_display();

        }

        return $output;

    }

}

// Init shortcodes
new WPSBC_Shortcodes();
