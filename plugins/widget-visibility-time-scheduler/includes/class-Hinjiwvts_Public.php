<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/widget-visibility-time-scheduler
 * @since      1.0.0
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/public
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
class Hinjiwvts_Public {

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $hinjiwvts       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_slug, $version ) {

		$this->plugin_slug = $plugin_slug;
		$this->version = $version;

	}

    /**
     * Determine whether the widget should be displayed based on time set by the user.
     *
     * @param array $widget_settings The widget settings.
     *
     * @return array|bool Settings to display or bool false to hide.
     * @since  1.0.0
     */
	public static function filter_widget( array $widget_settings ) {

		$plugin_slug = 'hinjiwvts';

        // show widget if we do not have scheduler settings for this plugin
		if ( !isset( $widget_settings[ $plugin_slug ] ) ) {

		    return $widget_settings;

        }

        $mode = isset( $widget_settings[ $plugin_slug ][ 'mode' ] ) ? strtolower( $widget_settings[ $plugin_slug ][ 'mode' ] ): '';

        // bail if no mode is set
        if( empty( $mode )) {

            return $widget_settings;

        }

        // bail if now is out of time rule or no mode is set
        if( self::is_rule_in_time( $widget_settings[ $plugin_slug ] ) ) {
            if( 'hide' === $mode ) {

                return false;

            }
            else {

                return $widget_settings;

            }
        }
        else {

            if( 'hide' === $mode ) {

                return $widget_settings;

            }
            else {

                return false;

            }

        }

	}

    /**
     * Is now in the time rule of the scheduler
     *
     * @param $widget_settings
     *
     * @return bool
     *
     * @since 5.3.10
     */
	private static function is_rule_in_time( $widget_settings ): bool {

        $widget_start_time = isset( $widget_settings[ 'timestamps' ][ 'start' ] ) ? $widget_settings[ 'timestamps' ][ 'start' ] : '';
        $widget_end_time   = isset( $widget_settings[ 'timestamps' ][ 'end' ] )   ? $widget_settings[ 'timestamps' ][ 'end' ]   : '';
        $days_of_week      = isset( $widget_settings[ 'daysofweek' ] )            ? $widget_settings[ 'daysofweek' ]            : '';
        $current_timestamp = (int) current_time( 'timestamp' ); // get current local blog timestamp
        $current_day_num   = (int) date( 'N', $current_timestamp ); // get ISO-8601 numeric representation of the day of the week; 1 (for Monday) through 7 (for Sunday)

        /*
         * Check if widget start and end is in current time
         * and allowed weekdays are in today's weekday
         */
        if( $widget_start_time <= $current_timestamp &&
            $current_timestamp <= $widget_end_time &&
            in_array( $current_day_num, $days_of_week ) ) {
            return true;
        }

        return false;

    }

}
