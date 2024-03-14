<?php

/**
 * Plugin Name:       Current Year Shortcodes
 * Plugin URI:        https://praxismarketing-agentur.com/wordpress-plugins/current-year-shortcodes/
 * Description:       Add shortcodes for the current year, month and day
 * Version:           0.3
 * Author:            Lukas Pugstaller
 * Author URI:        https://praxismarketing-agentur.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       current-year-shortcodes
 */

// If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

function CurrentYearShortcodes_year_long() {
    return date('Y');
}
add_shortcode( 'Y', 'CurrentYearShortcodes_year_long' );

function CurrentYearShortcodes_year_short() {
    return date('y');
}
add_shortcode( 'y', 'CurrentYearShortcodes_year_short' );

function CurrentYearShortcodes_month_numeric_long() {
    return date('m');
}
add_shortcode( 'm', 'CurrentYearShortcodes_month_numeric_long' );

function CurrentYearShortcodes_month_numeric_short() {
    return date('n');
}
add_shortcode( 'n', 'CurrentYearShortcodes_month_numeric_short' );

function CurrentYearShortcodes_month_textual_long() {
    return wp_date( __('F', 'current-year-shortcodes') );
}
add_shortcode( 'F', 'CurrentYearShortcodes_month_textual_long' );

function CurrentYearShortcodes_month_textual_short() {
    return wp_date( __('M', 'current-year-shortcodes') );
}
add_shortcode( 'M', 'CurrentYearShortcodes_month_textual_short' );

function CurrentYearShortcodes_day_numeric_long() {
    return date('d');
}
add_shortcode( 'd', 'CurrentYearShortcodes_day_numeric_long' );

function CurrentYearShortcodes_day_numeric_short() {
    return date('j');
}
add_shortcode( 'j', 'CurrentYearShortcodes_day_numeric_short' );

function CurrentYearShortcodes_day_textual_long() {
    return wp_date( __('l', 'current-year-shortcodes') );
}
add_shortcode( 'l', 'CurrentYearShortcodes_day_textual_long' );

function CurrentYearShortcodes_day_textual_short() {
    return wp_date( __('D', 'current-year-shortcodes') );
}
add_shortcode( 'D', 'CurrentYearShortcodes_day_textual_short' );