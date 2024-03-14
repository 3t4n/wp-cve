<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPSBC_Calendar_Editor_Outputter
{

    /**
     * The arguments for the calendar outputter
     *
     * @access protected
     * @var    array
     *
     */
    protected $args;

    /**
     * The WPSBC_Calendar
     *
     * @access protected
     * @var    WPSBC_Calendar
     *
     */
    protected $calendar = null;

    /**
     * The list of legend items associated with the calendar
     *
     * @access protected
     * @var    array
     *
     */
    protected $legend_items = array();

    /**
     * The default legend item of the calendar
     *
     * @access protected
     * @var    WPSBC_Legend_Item
     *
     */
    protected $default_legend_item = null;

    /**
     * The list of events for the calendar for the given displayed range
     *
     * @access protected
     * @var    array
     *
     */
    protected $events = array();

    /**
     * Custom calendar data arranged by date
     *
     * @access protected
     * @var    array
     *
     */
    protected $data = array();

    /**
     * Constructor
     *
     * @param int   $calendar    - the calendar for which to print the editable calendar fields
     * @param array $args          - arguments from which to build the calendar fields
     * @param array $data          - extra data to be populated in the calendar fields on top of the
     *                                already saved events
     *
     */
    public function __construct($calendar, $args, $data = array())
    {

        $defaults = array(
            'current_year' => (!empty($args['current_year']) ? $args['current_year'] : current_time('Y')),
            'current_month' => (!empty($args['current_month']) ? $args['current_month'] : current_time('n')),
        );

        /**
         * Set arguments
         *
         */
        $this->args = wp_parse_args($args, $defaults);

        /**
         * Set the calendar
         *
         */
        $this->calendar = $calendar;

        /**
         * Set the calendar legend items
         *
         */
        $this->legend_items = wpsbc_get_legend_items(array('calendar_id' => $calendar->get('id')));

        /**
         * Set the default legend item
         *
         */
        foreach ($this->legend_items as $legend_item) {

            if ($legend_item->get('is_default') == 1) {
                $this->default_legend_item = $legend_item;
            }

        }

        /**
         * Set the calendar events
         *
         */
        $this->events = wpsbc_get_events(array('calendar_id' => $calendar->get('id'), 'date_year' => $this->args['current_year'], 'date_month' => $this->args['current_month']));

        /**
         * Set the calendar data
         *
         */
        $this->data = $data;

    }

    /**
     * Constructs and returns the HTML for the entire calendar month editor
     *
     * @return string
     *
     */
    public function get_display()
    {

        $total_days = date('t', mktime(0, 0, 0, $this->args['current_month'], 1, $this->args['current_year']));

        $output = '<div id="wpsbc-calendar-editor">';

        $output .= $this->get_display_date_header();

        for ($i = 1; $i <= $total_days; $i++) {

            $output .= $this->get_display_date($this->args['current_year'], $this->args['current_month'], $i);

        }

        /**
         * Calendar Editor Custom CSS
         *
         */
        $output .= $this->get_custom_css();

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the HTML for the dates columns header
     *
     * @return string
     *
     */
    protected function get_display_date_header()
    {

        $output = '<div class="wpsbc-calendar-date">';

        $output .= '<div class="wpsbc-calendar-date-legend-item-header">' . __('Availability', 'wp-simple-booking-calendar') . '</div>';
        $output .= '<div class="wpsbc-calendar-date-description-header">' . __('Description', 'wp-simple-booking-calendar') . wpsbc_get_output_tooltip(__('You can use the description field of the date to add private information regarding your booking. This information will not be displayed anywhere else, but here.', 'wp-simple-booking-calendar')) . '</div>';

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the HTML for a single calendar given date
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return string
     *
     */
    protected function get_display_date($year, $month, $day)
    {

        /**
         * Prepare data
         *
         */
        $data = $this->get_data_by_date($year, $month, $day);
        $event = $this->get_event_by_date($year, $month, $day);

        /**
         * Prepare output
         *
         */
        $output = '<div class="wpsbc-calendar-date">';

        // Get the date legend item
        $output .= $this->get_display_date_legend_item($year, $month, $day, $event, $data);

        // Get the date description
        $output .= $this->get_display_date_description($year, $month, $day, $event, $data);

        // Set-up extra data that can be added by others
        $output .= '<div class="wpsbc-calendar-date-meta">';

        /**
         * Filter to add custom calendar date meta
         *
         * @param int $year
         * @param int $month
         * @param int $day
         * @param mixed WPSBC_Event|null
         * @param mixed array|null
         *
         */
        $output .= apply_filters('wpsbc_calendar_editor_display_date', '', $year, $month, $day, $event, $data);

        $output .= '</div>';

        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the HTML for the date legend item selector
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param mixed WPSBC_Event|null
     * @param mixed array|null
     *
     * @return string
     *
     */
    protected function get_display_date_legend_item($year, $month, $day, $event, $data)
    {

        /**
         * Set selected value
         *
         */
        $selected_id = '';
        $selected = null;

        if (!is_null($data)) {

            $selected_id = $data['legend_item_id'];

        } elseif (!is_null($event)) {

            $selected_id = $event->get('legend_item_id');

        }

        foreach ($this->legend_items as $legend_item) {

            if ($selected_id == $legend_item->get('id')) {
                $selected = $legend_item;
                break;
            }

        }

        // If none of the existing legend items is the selected one,
        // set the default legend item as the selected one
        if (is_null($selected)) {
            $selected = $this->default_legend_item;
            $selected_id = $selected->get('id');
        }

        /**
         * Prepare output
         *
         */
        $output = '<div class="wpsbc-calendar-date-legend-item wpsbc-calendar-date-legend-item-' . esc_attr($selected->get('id')) . '">';

        $output .= '<div class="wpsbc-legend-item-icon-wrapper">';
        $output .= '<div class="wpsbc-date-inner">' . esc_attr($day) . '</div>';
        $output .= wpsbc_get_legend_item_icon($selected->get('id'), $selected->get('type'));
        $output .= '</div>';

        $output .= '<select data-name="legend_item_id" data-year="' . esc_attr($year) . '" data-month="' . esc_attr($month) . '" data-day="' . esc_attr($day) . '" ' . (!is_null($event) && is_null($event->get('id')) ? 'disabled' : '') . '>';

        foreach ($this->legend_items as $legend_item) {

            $output .= '<option value="' . esc_attr($legend_item->get('id')) . '" ' . selected($selected_id, $legend_item->get('id'), false) . ' data-type="' . esc_attr($legend_item->get('type')) . '">' . $legend_item->get_name($this->args['language']) . '</option>';

        }

        $output .= '</select>';
        $output .= '</div>';

        return $output;

    }

    /**
     * Constructs and returns the HTML for the date description field
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param mixed WPSBC_Event|null
     * @param mixed array|null
     *
     * @return string
     *
     */
    protected function get_display_date_description($year, $month, $day, $event, $data)
    {

        /**
         * Set selected value
         *
         */
        $value = '';

        if (!is_null($data) && isset($data['description'])) {

            $value = $data['description'];

        } elseif (!is_null($event)) {

            $value = $event->get('description');

        }

        /**
         * Prepare output
         *
         */
        $output = '<div class="wpsbc-calendar-date-description">';

        $output .= '<span class="dashicons dashicons-edit"></span>';
        $output .= '<input type="text" value="' . esc_attr($value) . '" data-name="description" data-year="' . esc_attr($year) . '" data-month="' . esc_attr($month) . '" data-day="' . esc_attr($day) . '" />';

        $output .= '</div>';

        return $output;

    }

    /**
     * Passes through all stored events and searches for the event that matches the given date
     * If an event is found it is returned, else null is returned
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return mixed WPSBC_Event|null
     *
     */
    protected function get_event_by_date($year, $month, $day)
    {

        foreach ($this->events as $event) {

            if ($event->get('date_year') == $year && $event->get('date_month') == $month && $event->get('date_day') == $day) {
                return $event;
            }

        }

        return null;

    }

    /**
     * Passes through all stored calendar data and searches for the data that matches the given date
     * If data is found it is returned, else null is returned
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return mixed array|null
     *
     */
    protected function get_data_by_date($year, $month, $day)
    {

        if (isset($this->data[$year][$month][$day])) {
            return $this->data[$year][$month][$day];
        }

        return null;

    }

    /**
     * Constructs and returns the calendar's custom CSS
     *
     * @return string
     *
     */
    protected function get_custom_css()
    {

        $output = '<style>';

        // Set the parent calendar class
        $calendar_parent_class = '#wpsbc-calendar-editor';

        /**
         * Legend Items CSS
         *
         */
        foreach ($this->legend_items as $legend_item) {

            // Background colors
            $colors = $legend_item->get('color');

            $output .= $calendar_parent_class . ' .wpsbc-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type { background-color: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
            $output .= $calendar_parent_class . ' .wpsbc-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) { background-color: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

            // Text color
            $color_text = $legend_item->get('color_text');

            if (!empty($color_text)) {
                $output .= $calendar_parent_class . ' .wpsbc-calendar-date-legend-item-' . esc_attr($legend_item->get('id')) . ' .wpsbc-date-inner { color: ' . esc_attr($color_text) . '; }';
            }

        }

        $output .= '</style>';

        return $output;

    }

    /**
     * Helper function that prints the calendar editor
     *
     */
    public function display()
    {

        echo $this->get_display();

    }

}
