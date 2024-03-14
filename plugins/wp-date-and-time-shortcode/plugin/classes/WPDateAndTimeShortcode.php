<?php

/**
 * WPDateAndTimeShortcode Class
 *
 * The main class for the WP Date and Time Shortcode plugin
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.5.6
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

class WPDateAndTimeShortcode extends Plugin {
    
    protected array $items;
    
    protected array $attributes;
    
    protected array $days_of_week = [
        'mon' => 1,
        'tue' => 2,
        'wed' => 3,
        'thu' => 4,
        'fri' => 5,
        'sat' => 6,
        'sun' => 7
    ];
    
    public function __construct($id, $data = []) {
        // Set text_domain for the framework
        $this->text_domain = 'denra-wp-dt';
        
        // Set admin menus texts
        $this->admin_title_menu = __('WP Date and Time Shortcode', 'denra-wp-dt');
        
        parent::__construct($id, $data);
        
        $this->items = [
            'date-time',                // default WP date and time format (default value)
            'datetime',                 
            'date',                     // default WP date format
            'time',                     // default WP date format
            'custom',                   // custom format used by the built-in PHP date() function
            'year',                     // 4-digit year
            'years',                    
            'year-short',               // 2-digit year
            'years-short',              
            'month',                    // month as number (1-12)
            'months',                   
            'month-name',               // month as name (January-December)
            'month-name-short',         // month as 3-letter name (Jan-Dec),
            'day',                      // day of month
            'days',                     
            'hour',                     // hours
            'hours',                    
            'minute',                   // minutes
            'minutes',                  
            'second',                   // seconds
            'seconds',                  
            'timestamp',                // shows the UNIX timestamp
            'day-of-year',              // day of the year as number
            'days-in-month',            // number of days in the month
            'days-in-february',         // number of days in the month of February for the year
            'days-in-year',             // number of days in year - 365/366
            'day-of-week',              // day of the week as number (1-7)
            'day-of-week-name',         // day of the week as full name (Monday-Sunday)
            'day-of-week-name-short',   // day of the week as full name (Mon-Sun)
            'week-of-year',             // week of year, since first Monday of the year
            'am-pm',                    // shows am/pm or AM/PM according to the am_pm attribute ('L' or 'U')
            'time-zone',                // current shortcode time zone
            
            // Backward compatibility with version 2.5.3.
            'weekday',                  // deprecated - replaced by day-of-week
            'weekday-name',             // deprecated - replaced by day-of-week-name
            'weekday-name-short',       // deprecated - replaced by day-of-week-name-short
            'timezone',                 // deprecated - replaced by time-zone
        ];
        
        $this->attributes = [
            'item'           => 'custom',   // item to show
            'format'         => '',         // format when item is custom
            'start'          => 'now',      // start date (now, mysql format, or other special one)
            'next'           => '',         // move the start date and time to the next coming selected
            'time_zone'      => '',         // select the time zone for which to display the result
            'days_suffix'    => 0,          // use suffix st, nd, rd, th for days
            'hours_24'       => 1,          // use 24 or 12 hours format
            'am_pm'          => 'L',        // use when 12 hours format: 'L' for lowercase (am, pm) or 'U' for uppercase (AM, PM)
            'years'          => 0,          // change in years
            'months'         => 0,          // change in months
            'days'           => 0,          // change in days
            'hours'          => 0,          // change in hours
            'minutes'        => 0,          // change in minutes
            'seconds'        => 0,          // change in seconds
            'zero'           => 1,          // use leading zero for months, days, hours, minutes, seconds
            'i18n'           => 1,          // display days of week and months' names in the current locale language
            'post_id'        => 0,          // post ID from which to get post-created(-gmt) or post-modified(-gmt)
            
            // *_change for backward compatibility with version 1.1
            'years_change'   => 0,
            'months_change'  => 0,
            'days_change'    => 0,
            'hours_change'   => 0,
            'minutes_change' => 0,
            'seconds_change' => 0,
        ];
        
        $this->addShortcodes();   
    }
    
    public function addShortcodes() {
        // Add the main shortcodes.
        add_shortcode('wpdts', [&$this, 'getDateTime']);
        // Add the main shortcodes
        add_shortcode('wp-dt', [&$this, 'getDateTime']);
        add_shortcode('wp_dt', [&$this, 'getDateTime']);
        
        // Add additional shortcodes for each separate item
        foreach ($this->items as $item) {
            add_shortcode('wpdts-' . $item, [&$this, 'getDateTime']);
            // Backward compatibility with version 2.3.1 and earlier
            add_shortcode('wp-dt-'.$item, [&$this, 'getDateTime']);
        }
    }
    
    public function getDateTime($atts, $content, $tag) {
        $atts = shortcode_atts($this->attributes, $atts, $tag );
        
        // Sanitize attributes.
        if (is_array($atts)) {
            foreach ($atts as $key => $value) {
                if (in_array($value, ['yes', '1', 'on', 1, true], true)) {
                    $atts[$key] = 1;
                }
                elseif (in_array($value, ['no', '0', 'off', 0, false], true)) {
                    $atts[$key] = 0;
                }
                elseif (is_numeric($value)) {
                    $atts[$key] = (int) $value;
                }
                else {
                    $atts[$key] = trim(htmlspecialchars_decode($value), '" ');
                }
            }
        }
        
        $wp_dt = ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
        
        foreach($wp_dt as $wp_dt_part) {
            // Backward compatibility with version 1.1 - remove '*_change'.
            if (isset($atts[$wp_dt_part.'_change']) && $atts[$wp_dt_part.'_change']) {
                $atts[$wp_dt_part] = $atts[$wp_dt_part.'_change'];
                unset($atts[$wp_dt_part.'_change']);
            }
            // Backward compatibility with version 2.2.1 for the '*_zero' attributes.
            if (isset($atts[$wp_dt_part.'_zero']) && $atts[$wp_dt_part.'_zero']) {
                $atts['zero'] = $atts[$wp_dt_part.'_zero'];
                unset($atts[$wp_dt_part.'_zero']);
            }
        }
        
        // Backward compatibility with version 2.3.1 - changing 'init' attr to 'start'.
        if (isset($atts['init'])) {
            $atts['start'] = $atts['init'];
            unset($atts['init']);
        }
        
        // Get 'item' from shortcode tag.
        if (!in_array($tag, ['wp-dt', 'wp_dt', 'wpdts'])) {
            $atts['item'] = preg_replace('/^wpdts\-/', '', $tag);
            // Backward compatibility with version 2.3.1 - wp-dt and wp_dt.
            $atts['item'] = preg_replace('/^wp[\-_]dt\-/', '', $atts['item']);
        }
               
        // Backward compatibility with version 2.5.2 - 'weekday' changed to 'day-of-week'.
        switch($atts['item']) {
            case 'weekday':
                $atts['item'] = 'day-of-week';
                break;
            case 'weekday-name':
                $atts['item'] = 'day-of-week-name';
                break;
            case 'weekday-name-short':
                $atts['item'] = 'day-of-week-name-short';
                break;
        }
        
        // Backward compatibility with version 2.5.3 - changing 'timezone' attr to 'time-zone'.
        if ($atts['item'] == 'timezone') {
            $atts['item'] = 'time-zone';
        }
        
        if (!in_array($atts['item'], $this->items, true)) {
            $atts['item'] = 'custom';
        }
        
        $wp_timezone_string = wp_timezone_string();
        $start_time_zone_string = $wp_timezone_string;
        
        if (!$atts['start'] || 'now' == $atts['start']) {
            // set initial date and time if not set in the 'start' attribute
            $atts['start'] = 'now';
            $start_time_zone_string = $wp_timezone_string;
        }
        elseif (in_array($atts['start'], ['post-created', 'post-created-gmt', 'post-modified', 'post-modified-gmt'])) {
            if (!$atts['post_id']) {
                $atts['post_id'] = (int) get_the_ID();
            }
            if (!$atts['post_id']) {
                return __('The post ID is not accessible.', 'denra-wp-dt');
            }
            $post = get_post($atts['post_id']);
            if (isset($post->ID) && $post->ID) {
                switch($atts['start']) {
                    case 'post-created':
                        $atts['start'] = $post->post_date;
                        $start_time_zone_string = $wp_timezone_string;
                        break;

                    case 'post-modified':
                        $atts['start'] = $post->post_modified;
                        $start_time_zone_string = $wp_timezone_string;
                        break;

                    case 'post-created-gmt':
                        $atts['start'] = $post->post_date_gmt;
                        $start_time_zone_string = 'GMT';
                        break;

                    case 'post-modified-gmt':
                        $atts['start'] = $post->post_modified_gmt;
                        $start_time_zone_string = 'GMT';
                        break;
                }
            }
            else {
                return sprintf(__("The post with ID: %s does not exist.", 'denra-wp-dt'), $atts['post_id']);
            }
        }
        else {
            $timezones_arr = '(' . implode('|', \DateTimeZone::listIdentifiers()) . ')';
            $result = \preg_match($timezones_arr, $atts['start']);
            if (!$result) {
                $start_time_zone_string = $wp_timezone_string;
            }
        }
        
        $start_time_zone = new \DateTimeZone($start_time_zone_string);
        $datetime = new \DateTime($atts['start'], $start_time_zone);
        
        $tz_attr = $atts['time_zone'] ? new \DateTimeZone($atts['time_zone']) : $datetime->getTimezone();
        $tz_utc = new \DateTimeZone('UTC');
        
        if ($atts['next']) {
            $next_datetime_timestamps = [];
            $next_values_arr = explode(',', strtolower(preg_replace('/\s+/', ' ', $atts['next'])));
            foreach($next_values_arr as $next_value) {
                $next_datetime = clone $datetime;
                $next_found = false;
                $next_value_arr = explode(' ', trim($next_value));
                $next_day = trim($next_value_arr[0]);
                if (isset($next_value_arr[1]) && '' != $next_value_arr[1]) {
                    $next_time = $next_value_arr[1];
                }
                else {
                    $next_time = $next_datetime->format('H:i:s');
                }
                if (in_array($next_day, array_keys($this->days_of_week), true)) {
                    $this_day_of_week = (int) $datetime->format('N');
                    $next_day_of_week = $this->days_of_week[$next_day];
                    if ($next_day_of_week == $this_day_of_week) {
                        $next_datetime->modify("{$next_time}");
                        if ($next_datetime < $datetime) {
                            $next_datetime->modify("next {$next_day} {$next_time}");
                        }
                    }
                    else {
                        $next_datetime->modify("next {$next_day} {$next_time}");
                    }
                    $next_found = true;
                }
                elseif ($next_day == 'last-day-of-month') {
                    $next_datetime->modify("last day of this month {$next_time}");
                    if ($next_datetime < $datetime) {
                        $next_datetime->modify("last day of next month {$next_time}");
                    }
                    $next_found = true;
                }
                elseif (is_numeric($next_day)) {
                    if ($next_day >= 1 || $next_day <= 31) {
                        do {
                            $days_in_month = (int) $next_datetime->format('t');
                            if ($next_day > $days_in_month ) {
                                $next_datetime->modify('next month');
                                continue;
                            }
                            $next_datetime->setDate($next_datetime->format('Y'), $next_datetime->format('m'), $next_day);
                            $next_time_arr = explode(':', $next_time);
                            $hour = isset($next_time_arr[0]) ? $next_time_arr[0] : 0;
                            $minute = isset($next_time_arr[1]) ? $next_time_arr[1] : 0;
                            $second = isset($next_time_arr[2]) ? $next_time_arr[2] : 0;
                            $next_datetime->setTime((int) $hour, (int) $minute, (int) $second);
                            if ($next_datetime < $datetime) {
                                $next_datetime->modify('next month');
                            }
                            else {
                                $next_found = true;
                            }
                        } while (!$next_found);
                        
                    }
                }
                if ($next_found) {
                    if ($next_datetime >= $datetime) {
                        $next_datetime->setTimezone($tz_utc);
                        $next_datetime_timestamps[$next_datetime->format('Y-m-d H:i:s')] = $next_datetime->getTimestamp();
                    }
                }
            }
            
            if (count($next_datetime_timestamps)) {
                asort($next_datetime_timestamps);
                $datetime->setTimestamp(array_shift($next_datetime_timestamps));
            }
            unset($next_datetime_timestamps);
        }
        
        // Calculate date and time after change attributes.
        $dt_change = [];
        foreach ($wp_dt as $wp_dt_part) {
            $change_value = $atts[$wp_dt_part] ? (int) $atts[$wp_dt_part] : 0;
            if ($change_value) {
                $plus_minus = $change_value > 0 ? '+' : '-';
                $change_value = abs($change_value);
                $dt_change[$wp_dt_part] = $plus_minus . $change_value . ' ';
                if ($change_value == 1) {
                    $dt_change[$wp_dt_part] .= rtrim($wp_dt_part, 's');
                }
                else {
                    $dt_change[$wp_dt_part] .= $wp_dt_part;
                }
            }
        }
        if (count($dt_change)) {
            $datetime->modify(implode(' ', $dt_change));
        }
        
        $datetime->setTimezone($tz_attr);
        $timestamp = $datetime->getTimestamp();
        $offset = $datetime->getOffset();
        $timestamp_local =  $timestamp + $offset;
        
        switch ($atts['item']) {
            case 'date-time':
            case 'datetime':
                $atts['format'] = get_option('date_format') . ' ' . get_option('time_format');
                break;
            
            case 'date':
                $atts['format'] = get_option('date_format'); // default from WP
                break;
            
            case 'time':
                $atts['format'] = get_option('time_format'); // default from WP
                break;
            
            case 'month':
            case 'months':
                if ($atts['zero']) {
                    $atts['format'] = "m"; // 01-09, 10-12
                }
                else {
                    $atts['format'] = "n"; // 1-12
                }
                break;
            
            case 'month-name':
                $atts['format'] = "F"; // January-December
                break;
            
            case 'month-name-short':
                $atts['format'] = "M"; // Jan-Dec
                break;
            
            case 'days-in-month':
                $atts['format'] = "t"; // 1-31
                break;
            
            case 'day':
            case 'days':
                if ($atts['zero']) {
                    $atts['format'] = "d"; // 01-09, 10-31
                }
                else {
                    $atts['format'] = "j"; // 1-31
                }
                if ((int) $atts['days_suffix']) {
                    $atts['format'] .= "S"; // add st, nd, rd, th
                }
                break;
            
            case 'day-of-week':
                 $atts['format'] = "N"; // ISO ISO-8601 1 is Monday, 7 - Sunday
                break;
            
            case 'day-of-week-name':
                 $atts['format'] = "l"; // Monday-Sunday
                break;
            
            case 'day-of-week-name-short':
                $atts['format'] = "D"; // Mon-Sun
                break;
            
            case 'week-of-year':
                $atts['format'] = 'W'; // 1-52, since first Monday
                break;
            
            case 'hour':
            case 'hours':
                if ($atts['hours_24']) {
                    if ($atts['zero']) {
                        $atts['format'] = "H"; // 01-09, 10-24
                    }
                    else {
                        $atts['format'] = "G"; // 1-24
                    }
                }
                else {
                   if ($atts['zero']) {
                        $atts['format'] = "h"; // 01-09, 10-12
                    }
                    else {
                        $atts['format'] = "g"; // 1-12
                    }
                }
                break;
                
            case 'am-pm':
                if ($atts['am_pm'] == 'L') {
                    $atts['format'] = "a"; // AM/PM
                }
                else {
                    $atts['format'] = "A"; // am/pm
                }
                break;
            
            // do nothing with these here since they need custom processing
            /*
            case 'day-of-year':
            case 'days-in-month':
            case 'days-in-february':
            case 'days-in-year':
            case 'minutes':
            case 'seconds':
                break;
             * 
             * Format contstants are not added either.
            */
            
            default:
            case 'custom':
                if ($atts['format'] == '' || $atts['format'] == 'custom') {
                    $atts['format'] = get_option('date_format') . ' ' . get_option('time_format');
                }
                break;

        }
        
        // process all that need direct calculations or displays
        switch ($atts['item']) {
            
            case 'date-time':
            case 'datetime':
            default:
                if ($atts['i18n']) {
                    $result = date_i18n($atts['format'], $timestamp_local, false);
                }
                else {
                    $result = date($atts['format'], $timestamp_local);
                }
                break;
            
            case 'timestamp':
                $result = $timestamp;
                break;
            
            case 'time-zone':
                $result = $tz_attr->getName();
                break;
            
            case 'day-of-year':
                // calculate the current day of the year
                $result = (int) date('z', $timestamp_local) + 1;
                break;
            
            case 'days-in-february':
                // calculate the number of days in February this year
                $result = 28;
                if ((int) date('L', $timestamp_local)) {
                    $result++; // 29 days
                }
                break;
                
            case 'days-in-year':
                $result = 365;
                if ((int) date('L', $timestamp_local)) {
                    $result++; // 366 days
                }
                break;
                
            case 'year':
            case 'years':
                $result = date('Y', $timestamp_local); // 0000-9999
                if (!$atts['zero']) {
                    $result =  (int) $result; // remove the leading zeros
                }
                break;
            
            case 'year-short':
            case 'years-short':
                $result = date('y', $timestamp_local); // 01-99
                if (!$atts['zero']) {
                    $result =  (int) $result; // remove the leading zeros
                }
                break;
            
            case 'minute':
            case 'minutes':
                $result = date('i', $timestamp_local);
                if (!$atts['zero']) {
                    $result =  (int) $result; // remove the leading zero
                }
                break;
            
            case 'second':
            case 'seconds':
                $result = date('s', $timestamp_local);
                if (!$atts['zero']) {
                    $result = (int) $result; // remove the leading zero
                }
                break;       
        }
        
        // convert to string and return the display result
        return strval($result);
    }
    
}
