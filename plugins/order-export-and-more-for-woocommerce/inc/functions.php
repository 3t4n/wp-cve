<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}

/**
 * Common functions 
 */

/**
 * Gets the settings, sets defaults etc
 * All settings are now held in one single option -
 */
function jemxp_get_settings()
{

    $settings = get_option(JEMEXP_DOMAIN);

    return $settings;
}

/**
 * Gets the relevent archive posts (if any)
 * Returns the posts or null for no posts
 */
function jemxp_get_archive_posts()
{
    $postType = 'attachment';

    $args = array(
        'post_type' => $postType,
        'post_mime_type' => array('text/csv'),
        'meta_value' => null,
        'post_status' => 'any',
        'posts_per_page' => -1
    );

    $files = get_posts($args);
    return $files;
}

function jemxp_remove_nulls(array &$array)
{
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            $value = jemxp_remove_nulls($value);
        }
        if (is_null($value)) {
            unset($array[$key]);
        }
    }
    return $array;
}

/**
 * Creates an html output for meta data
 * @param $meta_data - the meta data, assumes you have already got it
 * @return string - html data
 */
function jemxp_explode_meta_to_html($meta_data)
{
    $html = "";
    foreach ($meta_data as $meta_name => $val) {
        if (count(maybe_unserialize($val)) == 1) {
            $val = $val[0];
        }

        $val = maybe_unserialize($val);

        //is the val an array?
        if (is_array($val)) {
            $html .= "<TR><TD style='width: 20%;'>{$meta_name}</TD><TD></TD></TR>";

            foreach ($val as $child_name => $child_val) {
                $html .= "<TR><TD>{$child_name}</TD><TD></TD></TR>";
                //get it in a nice format
                if (is_array(maybe_unserialize($child_val)) && count(maybe_unserialize($child_val)) == 1) {
                    $child_val = $child_val[0];
                }

                maybe_unserialize($child_val);

                //possible for children to be arrays as well!!!
                if (is_array($child_val)) {
                    foreach ($child_val as $grandchild_name => $grandchild_val) {
                        $html .= "<TR><TD>---{$grandchild_name}</TD><TD>{$grandchild_val}</TD></TR>";
                    }
                } else {
                    $html .= "<TR><TD>---{$child_name}</TD><TD>{$child_val}</TD></TR>";
                }
            }
        } else {
            $html .= "<TR><TD style='width: 20%;'>{$meta_name}</TD><TD>{$val}</TD></TR>";
        }
    }

    return $html;
}

/**
 * Create array of predefined date ranges
 */
function jemx_predefined_date_ranges_data()
{

    $pre_ranges = array();

    // Today date ranges
    $today_date = date('Y-m-d', current_time('timestamp', 0));
    $pre_ranges['today']['start_date'] = $today_date;
    $pre_ranges['today']['end_date'] = $today_date;

    // Yesterday date ranges
    $yesterday_date = date('Y-m-d', current_time('timestamp', 0) - (60 * 60 * 24));
    $pre_ranges['yesterday']['start_date'] = $yesterday_date;
    $pre_ranges['yesterday']['end_date'] = $yesterday_date;

    // This week date ranges
    $last_sunday = strtotime("last sunday");
    $sunday = date('w', $last_sunday) == date('w') ? $last_sunday + 7 * 86400 : $last_sunday;
    $pre_ranges['thisweek']['start_date'] = date("Y-m-d", $sunday);
    $pre_ranges['thisweek']['end_date'] = $today_date;

    // Last week date ranges
    $lastweek_sun = $last_sunday - 7 * 86400;
    $lastweek_sat = strtotime(date("Y-m-d", $lastweek_sun) . " +6 days");
    $pre_ranges['lastweek']['start_date'] = date("Y-m-d", $lastweek_sun);
    $pre_ranges['lastweek']['end_date'] = date("Y-m-d", $lastweek_sat);

    // Month to date ranges
    $pre_ranges['monthtodate']['start_date'] = date('Y-m-01', current_time('timestamp', 0));
    $pre_ranges['monthtodate']['end_date'] = $today_date;

    // Last month date ranges
    $first_day_current_month = strtotime(date('Y-m-01', current_time('timestamp', 0)));
    $lastmonth_start_date = date('Y-m-01', strtotime('-1 DAY', $first_day_current_month));
    $lastmonth_end_date = date('Y-m-t', strtotime('-1 DAY', $first_day_current_month));
    $pre_ranges['lastmonth']['start_date'] = $lastmonth_start_date;
    $pre_ranges['lastmonth']['end_date'] = $lastmonth_end_date;

    // Year to date ranges
    $pre_ranges['yeartodate']['start_date'] = date('Y-01-01', current_time('timestamp', 0));
    $pre_ranges['yeartodate']['end_date'] = $today_date;

    return $pre_ranges;
}
