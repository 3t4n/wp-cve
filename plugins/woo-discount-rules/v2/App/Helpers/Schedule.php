<?php

namespace Wdr\App\Helpers;

use Wdr\App\Controllers\Configuration;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Schedule
{
    /**
     * Add schedule event (wp_schedule_event)
     *
     * @param $timestamp
     * @param $recurrence
     * @param $hook
     * @param $args
     * @return bool|\WP_Error
     */
    public static function addEvent($timestamp, $recurrence, $hook, $args = array())
    {
        if (function_exists('wp_schedule_event')) {
            return wp_schedule_event($timestamp, $recurrence, $hook, $args);
        }
        return false;
    }

    /**
     * Get schedule event object (wp_get_scheduled_event)
     *
     * @param $hook
     * @param $args
     * @param $timestamp
     * @return false|object
     */
    public static function get($hook, $args = array(), $timestamp = null)
    {
        if (function_exists('wp_get_scheduled_event')) {
            return wp_get_scheduled_event($hook, $args, $timestamp);
        }
        return false;
    }

    /**
     * Check if the schedule event is exists or not
     *
     * @param $hook
     * @param $args
     * @param $timestamp
     * @return bool
     */
    public static function hasEvent($hook, $args = array(), $timestamp = null)
    {
        return self::get($hook, $args, $timestamp) !== false;
    }

    /**
     * Remove scheduled event (wp_clear_scheduled_hook)
     *
     * @param $hook
     * @param $args
     * @return false|int|\WP_Error
     */
    public static function removeEvent($hook, $args = array())
    {
        if (function_exists('wp_clear_scheduled_hook')) {
            return wp_clear_scheduled_hook($hook, $args);
        }
        return false;
    }

    /**
     * Check config then run Rebuild On Sale Page index daily
     */
    public static function mayRunRebuildOnSaleIndex()
    {
        $config = new Configuration();
        $rebuild_on_sale_rules = $config->getConfig('awdr_rebuild_on_sale_rules', array());
        $run_rebuild_on_sale_index_cron = $config->getConfig('run_rebuild_on_sale_index_cron', 0);
        if (!empty($rebuild_on_sale_rules) && $run_rebuild_on_sale_index_cron) {
            self::runRebuildOnSaleIndex();
        }
    }

    /**
     * To run Rebuild On Sale Page index daily
     */
    public static function runRebuildOnSaleIndex()
    {
        if (!self::hasEvent('advanced_woo_discount_rules_scheduled_rebuild_on_sale_index_event')) {
            $time = strtotime(get_gmt_from_date(date('Y-m-d 00:00:00')));
            $recurrence = apply_filters('advanced_woo_discount_rules_scheduled_rebuild_on_sale_index_event_recurrence', 'daily');
            return (bool) self::addEvent($time, $recurrence, 'advanced_woo_discount_rules_scheduled_rebuild_on_sale_index_event');
        }
        return true;
    }

    /**
     * Stop Rebuild On Sale Page indexing daily
     */
    public static function stopRebuildOnSaleIndex() {
        if (self::hasEvent('advanced_woo_discount_rules_scheduled_rebuild_on_sale_index_event')) {
            self::removeEvent('advanced_woo_discount_rules_scheduled_rebuild_on_sale_index_event');
        }
    }
}