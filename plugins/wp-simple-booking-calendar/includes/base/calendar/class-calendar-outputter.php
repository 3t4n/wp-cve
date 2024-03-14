<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class WPSBC_Calendar_Outputter
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
     * The plugin general settings
     *
     * @access protected
     * @var    array
     *
     */
    protected $plugin_settings = array();

    /**
     * A counter for the number of months being displayed in the calendar while
     * iterating through them
     *
     * @access private
     * @var    int
     *
     */
    private $_months_iterator;

    /**
     * Constructor
     *
     * @param WPSBC_Calendar $calendar
     * @param array          $args
     *
     */
    public function __construct($calendar, $args = array())
    {

        /**
         * Set arguments
         *
         */
        $this->args = wp_parse_args($args, wpsbc_get_calendar_output_default_args());

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
        $this->events = wpsbc_get_events(array('calendar_id' => $calendar->get('id')));

        /**
         * Set plugin settings
         *
         */
        $this->plugin_settings = get_option('wpsbc_settings', array());

   
    }

    /**
     * Constructs and returns the HTML for the entire calendar
     *
     * @return string
     *
     */
    public function get_display()
    {

        /**
         * Return nothing if calendar is in Trash
         *
         */
        if ($this->calendar->get('status') == 'trash') {
            return '';
        }

        /**
         * Prepare needed data
         *
         */
        $year_to_show = (int) $this->args['current_year'];
        $month_to_show = (int) $this->args['current_month'];

        $calendar_html_data = 'data-id="' . $this->calendar->get('id') . '" ';

        foreach ($this->args as $arg => $val) {
            $calendar_html_data .= 'data-' . $arg . '="' . esc_attr($val) . '" ';
        }

        /**
         * Handle output for existing calendar
         *
         */
        $output = '<div class="wpsbc-container wpsbc-calendar-' . (int) $this->calendar->get('id') . '" ' . $calendar_html_data . '>';

        /**
         * Calendar title
         *
         */
        if ($this->args['show_title']) {

            $calendar_title_wrapper_el = apply_filters('wpsbc_calendar_output_title_wrapper_element', 'h2', $this->calendar->get('id'));

            if(wpsbc_get_calendar_meta($this->calendar->get('id'), 'calendar_name_translation_' . $this->args['language'],true)){
                $calendar_name = wpsbc_get_calendar_meta($this->calendar->get('id'), 'calendar_name_translation_' . $this->args['language'],true);
            } else {
                $calendar_name = $this->calendar->get('name');
            }

            $output .= '<' . $calendar_title_wrapper_el . '>' . $calendar_name . '</' . $calendar_title_wrapper_el . '>';

        }

        $output .= '<div class="wpsbc-calendars-wrapper ' . ($this->args['show_legend'] ? 'wpsbc-legend-position-' . esc_attr($this->args['legend_position']) : '') . '">';

        /**
         * Calendar Legend Top
         *
         */
        if ($this->args['show_legend'] && $this->args['legend_position'] != 'bottom') {
            $output .= $this->get_display_legend();
        }

        /**
         * Iterate through the number of months to show and get the display
         * of the given month and year
         *
         */
        $output .= '<div class="wpsbc-calendars">';

        $excluded_months = apply_filters('wpsbc_calendar_output_exclude_months', array());


        // Set the months iterator
        $this->_months_iterator = 1;

        // Check if we need to exclude any months
        if (!empty($excluded_months)) {

            while (in_array($month_to_show, $excluded_months)) {
                // Increment month and year (if needed)
                $year_to_show = ($month_to_show + 1 > 12 ? ($year_to_show + 1) : $year_to_show);
                $month_to_show = ($month_to_show + 1 > 12 ? ($month_to_show + 1 - 12) : ($month_to_show + 1));

            }

        }

        // Get the month
        $output .= $this->get_display_month($year_to_show, $month_to_show);

        // Increment month and year (if needed)
        $year_to_show = ($month_to_show + 1 > 12 ? ($year_to_show + 1) : $year_to_show);
        $month_to_show = ($month_to_show + 1 > 12 ? ($month_to_show + 1 - 12) : ($month_to_show + 1));

        $output .= '</div>'; // end of .wpsbc-calendars

        /**
         * Calendar Legend Bottom
         *
         */
        if ($this->args['show_legend'] && $this->args['legend_position'] == 'bottom') {
            $output .= $this->get_display_legend();
        }

        $output .= '</div>'; // end of .wpsbc-calendars-wrapper

        /**
         * Calendar Custom CSS
         *
         */
        $output .= $this->get_custom_css();

        /**
         * Flag needed for Gutenberg block to properly display the calendar
         * in the editor after the block settings are changed
         *
         */
        $output .= '<div class="wpsbc-container-loaded" data-just-loaded="1"></div>';

        $output .= '</div>'; // end of .wpsbc-container

        return $output;

    }

    /**
     * Constructs and returns the HTML for the given month of the given year
     *
     * @param int $year
     * @param int $month
     *
     * @return string
     *
     */
    protected function get_display_month($year, $month)
    {

        $day_names = wpsbc_get_days_first_letters($this->args['language']);
        $start_weekday = wpsbc_get_start_day() - 1;
        $first_day = getdate(mktime(0, 0, 0, $month, 1, $year));
        $total_days = date('t', mktime(0, 0, 0, $month, 1, $year));

        $output = '<div class="wpsbc-calendar">';

        /**
         * Month header
         *
         */
        $output .= $this->get_display_month_header($year, $month);

        /**
         * Table with the actual calendar
         *
         */
        $output .= '<div class="wpsbc-calendar-wrapper">';
        $output .= '<table>';

        /**
         * Table head
         *
         * This will display the name of the week days
         *
         */
        $output .= '<thead>';
        $output .= '<tr>';

        

        // The name of each day
        for ($i = $start_weekday; $i < ($start_weekday + 7); $i++) {

            $index = ($i > 6 ? $i - 7 : $i);

            $output .= '<th>' . $day_names[$index] . '</th>';
        }

        $output .= '</tr>';
        $output .= '</thead>';

        /**
         * Table body
         *
         * This will display the actual dates for the current month
         *
         */
        $output .= '<tbody>';

        // The days array
        $days = array();

        // The empty days at the begining of the calendar
        $offset = $first_day['wday'] - $start_weekday;

        if ($offset < 1) {
            $offset += 7;
        }

        // Add first empty days
        for ($i = 1; $i < $offset; $i++) {
            $days[$i] = 0;
        }

        for ($j = $i; $j < $total_days + $i; $j++) {
            $days[$j] = $j - $i + 1;
        }

        // Add remaining empty days
        for ($i = 1; $i <= 7; $i++) {

            if (count($days) % 7 == 0) {
                break;
            }

            $days[] = 0;

        }

        $output .= '<tr>';

        foreach ($days as $key => $day) {


            // Get the output for the current day
            $output .= '<td>' . $this->get_display_day($year, $month, $day) . '</td>';

            if ($key % 7 == 0 && $key != count($days)) {
                $output .= '</tr><tr>';
            }

        }

        $output .= '</tr>';

        $output .= '</tbody>';

        $output .= '</table>';
        $output .= '</div>'; // end of .wpsbc-calendar-wrapper
        $output .= '</div>'; // end of .wpsbc-calendar

        return $output;

    }

    /**
     * Constructs and returns the HTML of the calendar (month) header
     *
     * @param int $year
     * @param int $month
     *
     * @return string
     *
     */
    protected function get_display_month_header($year, $month)
    {

        $output = '<div class="wpsbc-calendar-header wpsbc-heading">';
        $output .= '<div class="wpsbc-calendar-header-navigation">';

        // Add navigate previous
        if ($this->_months_iterator == 1) {
            $output .= '<a href="#" class="wpsbc-prev"><span class="wpsbc-arrow"></span></a>';
        }

        // Add month selector
        $output .= $this->get_display_month_selector($year, $month);


        // Add navigate next
        if ($this->_months_iterator == 1) {
            $output .= '<a href="#" class="wpsbc-next"><span class="wpsbc-arrow"></span></a>';
        }

        $output .= '</div>'; // end .wpsbc-calendar-header-navigation
        $output .= '</div>'; // end .wpsbc-calendar-header

        return $output;

    }

    /**
     * Constructs and returns the HTML of the calendar month selector from the header
     *
     * @param int $year
     * @param int $month
     *
     * @return string
     *
     */
    protected function get_display_month_selector($year, $month)
    {

        $output = '<div class="wpsbc-select-container">';

        /**
         * Hook to modify how many months are being displayed in the select dropdown
         * before the current given month of the year
         *
         * @param int $months_before
         * @param int $calendar_id
         * @param int $year
         * @param int $month
         *
         */
        $months_before = apply_filters('wpsbc_calendar_output_month_selector_months_before', 3, $this->calendar->get('id'), $year, $month);

        /**
         * Hook to modify how many months are being displayed in the select dropdown
         * after the current given month of the year
         *
         * @param int $months_after
         * @param int $calendar_id
         * @param int $year
         * @param int $month
         *
         */
        $months_after = apply_filters('wpsbc_calendar_output_month_selector_months_after', 12, $this->calendar->get('id'), $year, $month);

        /**
         * Hook to modify the maximum number of months to display before now()
         *
         * @param int $months_before_max
         * @param int $calendar_id
         * @param int $year
         * @param int $month
         *
         */
        $months_before_max = apply_filters('wpsbc_calendar_output_month_selector_months_before_max', -1, $this->calendar->get('id'), $year, $month);

        /**
         * Hook to modify the maximum number of months to display after now()
         *
         * @param int $months_after_max
         * @param int $calendar_id
         * @param int $year
         * @param int $month
         *
         */
        $months_after_max = apply_filters('wpsbc_calendar_output_month_selector_months_after_max', -1, $this->calendar->get('id'), $year, $month);

        /**
         * Hook to exclude months from the date selector
         *
         * @param array $months
         *
         */
        $excluded_months = apply_filters('wpsbc_calendar_output_month_selector_exclude_months', array());

        /**
         * Hook to exclude past months from the date selector
         *
         * @param bool 
         *
         */
        $show_past_months = apply_filters('wpsbc_calendar_output_month_selector_hide_past_months', true);

        /**
         * Build the months array
         *
         */

        // The array that will contain all options data
        $select_options = array();

        // Maximum before time
        $before_max_month = date('n') + (12 * ceil($months_before_max / 12)) - $months_before_max;
        $before_max_year = $year - ceil($months_before_max / 12);
        $time_before_max = mktime(0, 0, 0, $before_max_month, 1, $before_max_year);

        // Maximum after time
        $after_max_month = (date('n') + $months_after_max - (12 * floor((date('n') + $months_after_max) / 12)));
        $after_max_year = $year + floor((date('n') + $months_after_max) / 12);
        $time_after_max = mktime(0, 0, 0, $after_max_month, 1, $after_max_year);

        /**
         * Add past months
         *
         */
        $_year = $year;
        $_month = $month;

        for ($i = 1; $i <= $months_before; $i++) {

            // Exit loop if the max number of months has been reached
            if ($months_before_max != -1 && mktime(0, 0, 0, $_month, 1, $_year) <= $time_before_max) {
                break;
            }

            $_month -= 1;

            if ($_month < 1) {
                $_month += 12;
                $_year -= 1;
            }

            if (in_array($_month, $excluded_months)) {
                $months_before++;
                continue;
            }

            if($show_past_months === false && $_month < current_time('m') && $_year <= current_time('Y')){
                break;
            }

            $select_options[] = array(
                'value' => mktime(0, 0, 0, $_month, 15, $_year),
                'option' => apply_filters('wpsbc_calendar_output_month_selector_date_format', wpsbc_get_month_name($_month, $this->args['language']) . ' ' . $_year),
            );

        }

        $select_options = array_reverse($select_options);

        /**
         * Add given current month
         *
         */
        $select_options[] = array(
            'value' => mktime(0, 0, 0, $month, 15, $year),
            'option' => apply_filters('wpsbc_calendar_output_month_selector_date_format', wpsbc_get_month_name($month, $this->args['language']) . ' ' . $year),
        );

        /**
         * Add future months
         *
         */
        $_year = $year;
        $_month = $month;

        for ($i = 1; $i <= $months_after; $i++) {

            if ($months_after_max != -1 && mktime(0, 0, 0, $_month, 1, $_year) >= $time_after_max) {
                break;
            }

            $_month += 1;

            if ($_month > 12) {
                $_month -= 12;
                $_year += 1;
            }

            if (in_array($_month, $excluded_months)) {
                $months_after++;
                continue;
            }

            $select_options[] = array(
                'value' => mktime(0, 0, 0, $_month, 15, $_year),
                'option' => apply_filters('wpsbc_calendar_output_month_selector_date_format', wpsbc_get_month_name($_month, $this->args['language']) . ' ' . $_year),
            );

        }

        /**
         * Output select
         *
         */
        $output .= '<select>';

        foreach ($select_options as $select_option) {
            $output .= '<option value="' . esc_attr($select_option['value']) . '" ' . selected($select_option['value'], mktime(0, 0, 0, $month, 15, $year), false) . '>' . $select_option['option'] . '</option>';
        }

        $output .= '</select>';

        $output .= '</div>'; // end .wpsbc-select-container

        return $output;

    }

    /**
     * Constructs and returns the HTML for the given day of the given month of the given year
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return string
     *
     */
    protected function get_display_day($year, $month, $day)
    {

        $output = '';

        /**
         * Get the event for the current day
         *
         */
        $event = $this->get_event_by_date($year, $month, $day);


        /**
         * Get the legend item for the current day
         *
         */
        $legend_item = null;

        if (!is_null($event)) {

            foreach ($this->legend_items as $li) {

                if ($event->get('legend_item_id') == $li->get('id')) {
                    $legend_item = $li;
                }

            }

        }

        if (is_null($legend_item)) {
            $legend_item = $this->default_legend_item;
        }

        // Determine if the current day is in the past
        $is_past = $this->is_date_past($year, $month, $day);

        /**
         * Set the needed variables for the legend item output
         *
         */
        if (!empty($day)) {

            $legend_item_id_icon = $legend_item->get('id');
            $legend_item_type_icon = $legend_item->get('type');

        }

        /**
         * Putting the day output pieces together
         *
         */
        $output .= '<div class="wpsbc-date ' . (!empty($legend_item_id_icon) && is_numeric($legend_item_id_icon) ? 'wpsbc-legend-item-' . $legend_item->get('id') : '') . ' ' . (empty($day) ? 'wpsbc-gap' : '') . '" ' . (!empty($day) ? ('data-year="' . esc_attr($year) . '" data-month="' . esc_attr($month) . '" data-day="' . esc_attr($day) . '"') : '') . '>';

        if (!empty($day)) {

            /**
             * Legend item icon output
             *
             */
            $output .= wpsbc_get_legend_item_icon($legend_item_id_icon, $legend_item_type_icon);

        }

        $output .= '<div class="wpsbc-date-inner">' . (!empty($day) ? '<span class="wpsbc-date-number">' . $day . '</span>' : '') . '</div>';

        $output .= '</div>';

        return $output;

    }


    /**
     * Constructs and returns the HTML for the calendar's legend
     *
     * @return string
     *
     */
    protected function get_display_legend()
    {

        $output = '<div class="wpsbc-legend">';

        foreach ($this->legend_items as $legend_item) {

            if ($legend_item->get('is_visible') == 0) {
                continue;
            }

            $output .= '<div class="wpsbc-legend-item">';
            $output .= wpsbc_get_legend_item_icon($legend_item->get('id'), $legend_item->get('type'));
            $output .= '<span class=wpsbc-legend-item-name>' . $legend_item->get_name($this->args['language']) . '</span>';
            $output .= '</div>';

        }

        $output .= '</div>';

        return $output;

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
        $calendar_parent_class = '.wpsbc-container.wpsbc-calendar-' . (int) $this->calendar->get('id');

        /**
         * Legend Items CSS
         *
         */
        foreach ($this->legend_items as $legend_item) {

            // Background colors
            $colors = $legend_item->get('color');

            $output .= $calendar_parent_class . ' .wpsbc-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type { background-color: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
            $output .= $calendar_parent_class . ' .wpsbc-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) { background-color: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

            $output .= $calendar_parent_class . ' .wpsbc-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:first-of-type svg { fill: ' . (!empty($colors[0]) ? esc_attr($colors[0]) : 'transparent') . '; }';
            $output .= $calendar_parent_class . ' .wpsbc-legend-item-icon-' . esc_attr($legend_item->get('id')) . ' div:nth-of-type(2) svg { fill: ' . (!empty($colors[1]) ? esc_attr($colors[1]) : 'transparent') . '; }';

            // Text color
            $color_text = $legend_item->get('color_text');

            if (!empty($color_text)) {
                $output .= $calendar_parent_class . ' .wpsbc-legend-item-' . esc_attr($legend_item->get('id')) . ' .wpsbc-date-number { color: ' . esc_attr($color_text) . '; }';
            }

        }


        $output .= '</style>';

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
     * Determines whether the given date is in the past or not
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return bool
     *
     */
    protected function is_date_past($year, $month, $day)
    {

        $today = mktime(0, 0, 0, current_time('n'), current_time('j'), current_time('Y'));
        $date = mktime(0, 0, 0, $month, $day, $year);

        return ($today > $date);

    }


    /**
     * Helper function that prints the calendar
     *
     */
    public function display()
    {

        echo $this->get_display();

    }

}
