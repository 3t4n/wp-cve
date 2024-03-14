<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Activatable_Interface;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\Date_Period_Range;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the Notices module logic.
 * Public Model.
 *
 * @since 1.1
 */
class Usage implements Model_Interface, Initializable_Interface, Activatable_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 1.1
     * @access private
     * @var Cart_Conditions
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 1.1
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 1.1
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /**
     * Property that houses all admin notices data.
     *
     * @since 4.3.3
     * @access private
     * @var array
     */
    private $_notices = array();

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 1.1
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 1.1
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Cart_Conditions
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /*
    |--------------------------------------------------------------------------
    | Prepare Data
    |--------------------------------------------------------------------------
     */

    /**
     * Gather the tracking data together
     *
     * @since 1.14
     * @access public
     */
    private function _get_data() {
        $data = array();

        // Plugin data.
        $this->_append_plugins_data( $data );

        // Settings data.
        $this->_append_settings_data( $data );

        // Server environment data.
        $this->_append_environment_data( $data );

        // Effectiveness data.
        $data['effectiveness'] = $this->_get_effectiveness_data();

        return $data;
    }

    /**
     * Append versions and license data for all ACFW related plugins.
     *
     * @since 4.5.1
     * @access private
     *
     * @param array $data Usage data.
     */
    private function _append_plugins_data( &$data ) {

        $data = wp_parse_args(
            $data,
            array(
				'acfwf_version'       => Plugin_Constants::VERSION,
				'acfwp_version'       => '',
				'lpfw_version'        => '',
				'agc_version'         => '',
                'acfwp'               => (int) $this->_helper_functions->is_plugin_active( Plugin_Constants::PREMIUM_PLUGIN ),
                'lpfw'                => (int) $this->_helper_functions->is_plugin_active( Plugin_Constants::LOYALTY_PLUGIN ),
                'agc'                 => (int) $this->_helper_functions->is_plugin_active( Plugin_Constants::GIFT_CARDS_PLUGIN ),
                'acfwp_license_email' => '',
                'acfwp_license_key'   => '',
                'lpfw_license_email'  => '',
                'lpfw_license_key'    => '',
                'agc_license_email'   => '',
                'agc_license_key'     => '',
            )
        );

        // ACFWP data.
        if ( 1 === $data['acfwp'] ) {
            $data['acfwp_version']       = \ACFWP()->Plugin_Constants->VERSION;
            $data['acfwp_license_email'] = get_option( \ACFWP()->Plugin_Constants->OPTION_ACTIVATION_EMAIL );
            $data['acfwp_license_key']   = get_option( \ACFWP()->Plugin_Constants->OPTION_LICENSE_KEY );
        }

        // LPFW data.
        if ( 1 === $data['lpfw'] ) {
            $data['lpfw_version']       = \LPFW()->Plugin_Constants->VERSION;
            $data['lpfw_license_email'] = get_option( \LPFW()->Plugin_Constants->OPTION_ACTIVATION_EMAIL );
            $data['lpfw_license_key']   = get_option( \LPFW()->Plugin_Constants->OPTION_LICENSE_KEY );
        }

        // AGC data.
        if ( 1 === $data['agc'] ) {
            $data['agc_version']       = \AGCFW()->Plugin_Constants->VERSION;
            $data['agc_license_email'] = get_option( \AGCFW()->Plugin_Constants->OPTION_ACTIVATION_EMAIL );
            $data['agc_license_key']   = get_option( \AGCFW()->Plugin_Constants->OPTION_LICENSE_KEY );
        }
    }

    /**
     * Append settings data.
     *
     * @since 4.5.1
     * @access private
     *
     * @param array $data Usage data.
     */
    private function _append_settings_data( &$data ) {
        global $wpdb;

        $data['settings'] = array();

        $results = $wpdb->get_results(
            "SELECT option_name, option_value FROM {$wpdb->options}
            WHERE (option_name LIKE 'acfw_%'
            OR option_name LIKE 'acfwf_%'
            OR option_name LIKE 'acfwp_%'
            OR option_name LIKE 'lpfw_%'
            OR option_name LIKE 'agcfw_%')
            AND (option_name NOT LIKE '%license_key%'
            OR option_name NOT LIKE '%activation_email%'
            OR option_name NOT LIKE '%settings_hash%'
            OR option_name NOT LIKE '%installed_version%')
            "
        );

        foreach ( $results as $row ) {
            $data['settings'][ $row->option_name ] = $row->option_value;
        }
    }

    /**
     * Append server environment data.
     *
     * @since 4.5.1
     * @access private
     *
     * @param array $data Usage data.
     */
    private function _append_environment_data( &$data ) {
        // Get current theme info.
        $theme_data = wp_get_theme();

        // Get multisite data.
        $count_blogs = 1;
        if ( is_multisite() ) {
            if ( function_exists( 'get_blog_count' ) ) {
                $count_blogs = get_blog_count();
            } else {
                $count_blogs = 'Not Set';
            }
        }

        $data['url']               = home_url();
        $data['php_version']       = phpversion();
        $data['wp_version']        = get_bloginfo( 'version' );
        $data['wc_version']        = \WC()->version;
        $data['server']            = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : ''; // phpcs:ignore
        $data['multisite']         = is_multisite();
        $data['sites']             = $count_blogs;
        $data['usercount']         = function_exists( 'count_users' ) ? count_users() : 'Not Set';
        $data['themename']         = $theme_data->Name;
        $data['themeversion']      = $theme_data->Version;
        $data['admin_email']       = get_bloginfo( 'admin_email' );
        $data['usagetracking']     = get_option( Plugin_Constants::USAGE_CRON_CONFIG, false );
        $data['timezoneoffset']    = wp_timezone_string();
        $data['locale']            = get_locale();
        $data['active_plugins']    = $this->_get_active_plugins_data();
        $data['is_hpos_enabled']   = 'yes' === get_option( 'woocommerce_feature_custom_order_tables_enabled' );
        $data['is_cart_block']     = has_block( 'woocommerce/cart', wc_get_page_id( 'cart' ) );
        $data['is_checkout_block'] = has_block( 'woocommerce/checkout', wc_get_page_id( 'checkout' ) );
    }

    /**
     * Get site's list of active plugins.
     *
     * @since 4.5.1
     * @access private
     *
     * @return array List of active plugins.
     */
    private function _get_active_plugins_data() {
        $active_plugins         = get_option( 'active_plugins', array() );
        $network_active_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );

        return array_unique( array_merge( $active_plugins, $network_active_plugins ) );
    }

    /**
     * Append effectiveness data.
     *
     * @since 4.5.1
     * @access private
     */
    private function _get_effectiveness_data() {
        $start_period  = gmdate( 'Y-m-d', strtotime( 'monday last week' ) - DAY_IN_SECONDS );
        $end_period    = gmdate( 'Y-m-d', strtotime( 'saturday last week' ) );
        $report_period = new Date_Period_Range( $start_period, $end_period, true );
        $data          = array( 'currency' => get_option( 'woocommerce_currency' ) );
        $report_keys   = array(
            'coupons_used',
            'amount_discounted',
            'orders_discounted',
            'discounted_order_revenue',
            'store_credits_added',
            'store_credits_used',
            'gift_cards_sold',
            'gift_cards_claimed',
            'loyalty_points_earned',
            'loyalty_points_used',
        );

        // fetch the report data for last week (monday to sunday).
        $raw_data = \ACFWF()->API_Reports->prepare_dashboard_report_data( $report_period );

        // Set sunday of current week at 00:00:00 (site timezone) converted into UTC timezone value as the date value of the entry.
        $report_period->use_utc_timezone();
        $data['date'] = gmdate( 'Y-m-d H:i:s', $report_period->end_period->getTimestamp() + 1 );

        // match key value pairs: [report key] => [report amount value].
        foreach ( $raw_data as $row ) {

            if ( ! isset( $row['key'] ) || ! in_array( $row['key'], $report_keys, true ) || ! isset( $row['raw_data'] ) ) {
                continue;
            }

            $data[ $row['key'] ] = $row['raw_data'];
        }

        // append coupon emails sent report data.
        $data = array_merge( $data, $this->_get_coupon_emails_sent_data( $report_period ) );

        return $data;
    }

    /**
     * Get the coupon emails sent report data.
     *
     * @since 4.5.3
     * @access private
     *
     * @param Date_Period_Range $report_period Report period object.
     * @return array Coupon emails sent report data.
     */
    private function _get_coupon_emails_sent_data( Date_Period_Range $report_period ) {
        global $wpdb;

        $start_period = $report_period->start_period->format( 'Y-m-d H:i:s' );
        $end_period   = $report_period->end_period->format( 'Y-m-d H:i:s' );

        $raw_data = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT args FROM {$wpdb->actionscheduler_actions}
                WHERE hook = 'acfwf_send_coupon_action_schedule'
                AND status = 'complete'
                AND  CONVERT(last_attempt_gmt, DATETIME) BETWEEN %s AND %s
                ",
                $start_period,
                $end_period
            )
        );

        // count email sent for both existing and new customers.
        $data = array_reduce(
            $raw_data,
            function ( $c, $r ) {
                list( $coupon_id, $customer ) = json_decode( $r, true );

                // skip when the customer data is not valid.
                if ( ! is_array( $customer ) || ! isset( $customer['id'] ) ) {
                    return $c;
                }

                if ( (int) $customer['id'] > 0 ) {
                    $c['coupons_sent_existing_customers']++;
                } else {
                    $c['coupons_sent_new_customers']++;
                }

                return $c;
            },
            array(
				'coupons_sent_existing_customers' => 0,
				'coupons_sent_new_customers'      => 0,
            )
        );

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Cron Schedule
    |--------------------------------------------------------------------------
     */

    /**
     * Schedule when we should send tracking data
     *
     * @since 4.5.1
     * @access public
     */
    public function schedule_send() {
        if ( ! wp_next_scheduled( Plugin_Constants::USAGE_CRON_ACTION ) ) {
            $tracking = array();
            // phpcs:disable
            $tracking['day']      = rand( 0, 6 );
            $tracking['hour']     = rand( 0, 23 );
            $tracking['minute']   = rand( 0, 59 );
            $tracking['second']   = rand( 0, 59 );
            // phpcs:enable
            $tracking['offset']   = ( $tracking['day'] * DAY_IN_SECONDS ) +
                ( $tracking['hour'] * HOUR_IN_SECONDS ) +
                ( $tracking['minute'] * MINUTE_IN_SECONDS ) +
                $tracking['second'];
            $tracking['initsend'] = strtotime( 'next sunday' ) + $tracking['offset'];

            wp_schedule_event( $tracking['initsend'], 'weekly', Plugin_Constants::USAGE_CRON_ACTION );
            update_option( Plugin_Constants::USAGE_CRON_CONFIG, $tracking );
        }
    }

    /**
     * Add the cron schedule
     *
     * @since 4.5.1
     * @access public
     * @param array $schedules The schedules array from the filter.
     */
    public function add_schedules( $schedules = array() ) {
        // Adds once weekly to the existing schedules.
        $schedules['weekly'] = array(
            'interval' => 604800,
            'display'  => __( 'Once Weekly', 'advanced-coupons-for-woocommerce-free' ),
        );
        return $schedules;
    }

    /**
     * Send the checkin.
     *
     * @since 4.5.1
     * @access public
     * @param bool $override            Flag to override if tracking is allowed or not.
     * @param bool $ignore_last_checkin Flag to ignore that last checkin time check.
     * @return bool Whether the checkin was sent successfully.
     */
    public function send_checkin( $override = false, $ignore_last_checkin = false ) {

        // Don't track anything from our domains.
        $home_url = trailingslashit( home_url() );
        if ( strpos( $home_url, 'wholesalesuiteplugin.com' ) !== false || strpos( $home_url, 'advancedcouponsplugin.com' ) !== false ) {
            return false;
        }

        // Check if tracking is allowed on this site.
        if ( ! $this->_is_tracking_allowed() && ! $override ) {
            return false;
        }

        // Send a maximum of once per week.
        $last_send = get_option( Plugin_Constants::USAGE_LAST_CHECKIN );
        if ( is_numeric( $last_send ) && $last_send > strtotime( '-1 week' ) && ! ( $ignore_last_checkin || defined( 'ACFW_TESTING_SITE' ) ) ) {
            return false;
        }

        $response = wp_remote_post(
            'https://usg.rymeraplugins.com/v1/acfwf-checkin/',
            array(
				'method'      => 'POST',
				'timeout'     => 5,
				'redirection' => 5,
				'httpversion' => '1.1',
				'blocking'    => false,
				'body'        => $this->_get_data(),
				'user-agent'  => 'ACFWF/' . Plugin_Constants::VERSION . '; ' . get_bloginfo( 'url' ),
            )
        );

        // If we have completed successfully, recheck in 1 week.
        update_option( Plugin_Constants::USAGE_LAST_CHECKIN, time() );
        return true;
    }

    /**
     * Check if tracking is allowed.
     *
     * @since 4.5.1
     * @access private
     *
     * @return bool True if allowed, false otherwise.
     */
    private function _is_tracking_allowed() {
        $allow_usage = get_option( Plugin_Constants::USAGE_ALLOW, 'no' );
        return ( 'yes' === $allow_usage ) || $this->_helper_functions->has_paid_plugin_active();
    }

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
     */

    /**
     * Register allow usage tracking field.
     *
     * @since 4.5.1
     * @access public
     *
     * @param array $settings Setting fields.
     * @return array Filtered setting fields.
     */
    public function register_allow_usage_tracking_field( $settings ) {

        $settings[] = array(
            'title' => __( 'Allow usage tracking', 'advanced-coupons-for-woocommerce-free' ),
            'type'  => 'checkbox',
            'desc'  => sprintf(
                /* Translators: %s: Link to allow usage documentation. */
                __( 'By allowing us to track usage data we can better help you because we know with which WordPress configurations, themes and plugins we should test. Complete documentation on usage tracking is available <a href="%s" target="_blank">here</a>.', 'advanced-coupons-for-woocommerce-free' ),
                'https://advancedcouponsplugin.com/knowledgebase/usage-tracking/?utm_source=acfwf&utm_medium=kb&utm_campaign=allowusagesetting'
            ),
            'id'    => Plugin_Constants::USAGE_ALLOW,
        );

        return $settings;
    }

    /*
    |--------------------------------------------------------------------------
    | Notices
    |--------------------------------------------------------------------------
     */

    /**
     * Register allow usage tracking notice.
     *
     * @since 4.5.1
     * @access public
     *
     * @param array $notice_options List of notice options.
     * @return array Filtered list of notice options.
     */
    public function register_allow_usage_tracking_notice( $notice_options ) {
        $notice_options['allow_usage'] = Plugin_Constants::SHOW_ALLOW_USAGE_NOTICE;
        return $notice_options;
    }

    /**
     * Register allow usage notice data.
     *
     * @since 4.5.1
     * @access public
     *
     * @param array|null $notice_data Notice data.
     * @param string     $notice_key  Notice key.
     * @return array|null Filtered notice data.
     */
    public function register_allow_usage_tracking_notice_data( $notice_data, $notice_key ) {
        if ( 'allow_usage' === $notice_key ) {
            $notice_data = array(
                'slug'           => 'allow_usage',
                'id'             => Plugin_Constants::SHOW_ALLOW_USAGE_NOTICE,
                'logo_img'       => $this->_constants->IMAGES_ROOT_URL . '/acfw-logo.png',
                'is_dismissable' => false,
                'type'           => 'success',
                'heading'        => __( 'USAGE TRACKING PERMISSION', 'advanced-coupons-for-woocommerce-free' ),
                'content'        => array(
                    __( 'Allow Advanced Coupon to track plugin usage? Opt-in to let us track usage data so we know with which WordPress configurations, themes and plugins we should test with.', 'advanced-coupons-for-woocommerce-free' ),
                    sprintf(
                        /* Translators: %s: Link to allow usage documentation. */
                        __( 'Complete documentation on usage tracking is available <a href="%s">here</a>.', 'advanced-coupons-for-woocommerce-free' ),
                        'https://advancedcouponsplugin.com/knowledgebase/usage-tracking/?utm_source=acfwf&utm_medium=kb&utm_campaign=allowusagenotice'
                    ),
                ),
                'actions'        => array(
                    array(
                        'key'      => 'primary with-response',
                        'link'     => '',
                        'response' => 'allow_usage',
                        'text'     => __( 'Allow tracking', 'advanced-coupons-for-woocommerce-free' ),
                    ),
                    array(
                        'key'      => 'gray with-response',
                        'link'     => '',
                        'response' => 'dismissed',
                        'text'     => __( 'Do not allow', 'advanced-coupons-for-woocommerce-free' ),
                    ),
                ),
            );
        }

        return $notice_data;
    }

    /**
     * Set allow notice setting to 'yes' when response clicked in notice is "allow".
     *
     * @since 4.5.1
     * @access public
     *
     * @param string $notice_key Notice key.
     * @param string $response   Notice response.
     */
    public function update_allow_usage_setting_on_notice_dismiss( $notice_key, $response ) {
        if ( 'allow_usage' === $notice_key && 'allow_usage' === $response ) {
            update_option( Plugin_Constants::USAGE_ALLOW, 'yes' );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute codes that needs to run plugin activation.
     *
     * @since 1.1
     * @access public
     * @implements ACFWF\Interfaces\Activatable_Interface
     */
    public function activate() {
        if ( get_option( Plugin_Constants::SHOW_ALLOW_USAGE_NOTICE ) !== 'dismissed' ) {
            update_option( Plugin_Constants::SHOW_ALLOW_USAGE_NOTICE, 'yes' );
        }
    }

    /**
     * Execute codes that needs to run plugin activation.
     *
     * @since 1.1
     * @access public
     * @implements ACFWF\Interfaces\Initializable_Interface
     */
    public function initialize() {
        $this->schedule_send();
    }

    /**
     * Execute Notices class.
     *
     * @since 1.1
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {

        if ( ! $this->_helper_functions->has_paid_plugin_active() ) {
            $is_allowed   = get_option( Plugin_Constants::USAGE_ALLOW, 'default' );
            $setting_hook = 'default' === $is_allowed ? 'acfw_setting_general_options' : 'acfw_setting_modules_section_options';
            add_filter( $setting_hook, array( $this, 'register_allow_usage_tracking_field' ) );

            if ( 'yes' !== $is_allowed ) {
                add_filter( 'acfw_admin_notice_option_names', array( $this, 'register_allow_usage_tracking_notice' ) );
                add_filter( 'acfw_get_admin_notice_data', array( $this, 'register_allow_usage_tracking_notice_data' ), 10, 2 );
                add_action( 'acfw_before_dismiss_admin_notice', array( $this, 'update_allow_usage_setting_on_notice_dismiss' ), 10, 2 );
            }
        }

        add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
        add_action( Plugin_Constants::USAGE_CRON_ACTION, array( $this, 'send_checkin' ) );
    }
}
