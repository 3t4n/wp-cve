<?php
/**
 * This class is to add a review request system.
 *
 * Original Author: danieliser
 * Original Author URL: https://danieliser.com
 * Improved & Adapted By Zaytech Team
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Woocci_Modules_Reviews
 *
 * This class adds a review request system for your plugin or theme to the WP dashboard.
 */
class Woocci_Zaytech_Rev {

    /**
     * Tracking API Endpoint.
     *
     * @var string
     */
    public static $api_url = '';

    /**
     *
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'hooks' ) );
        add_action( 'wp_ajax_woocci_review_action', array( __CLASS__, 'ajax_handler' ) );
    }

    /**
     * Hook into relevant WP actions.
     */
    public static function hooks() {
        if ( is_admin() && current_user_can( 'edit_posts' ) ) {
            self::installed_on();
            add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
            add_action( 'network_admin_notices', array( __CLASS__, 'admin_notices' ) );
            add_action( 'user_admin_notices', array( __CLASS__, 'admin_notices' ) );
        }
    }

    /**
     * Get the install date for comparisons. Sets the date to now if none is found.
     *
     * @return false|string
     */
    public static function installed_on() {
        $installed_on   = get_option( 'woocci_reviews_installed_on', false );

        if ( ! $installed_on ) {
            $installed_on = current_time( 'mysql' );
            update_option( 'woocci_reviews_installed_on', $installed_on );
        }

        return $installed_on;
    }

    /**
     *
     */
    public static function ajax_handler() {
        $args = wp_parse_args( $_REQUEST, array(
            'group'  => self::get_trigger_group(),
            'code'   => self::get_trigger_code(),
            'pri'    => self::get_current_trigger( 'pri' ),
            'reason' => 'maybe_later',
        ) );

        if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'woocci_review_action' ) ) {
            wp_send_json_error();
        }

        try {
            $user_id = get_current_user_id();

            $dismissed_triggers                   = self::dismissed_triggers();
            $dismissed_triggers[ $args['group'] ] = $args['pri'];
            update_user_meta( $user_id, '_woocci_reviews_dismissed_triggers', $dismissed_triggers );
            update_user_meta( $user_id, '_woocci_reviews_last_dismissed', current_time( 'mysql' ) );

            switch ( $args['reason'] ) {
                case 'maybe_later':
                    update_user_meta( $user_id, '_woocci_reviews_last_dismissed', current_time( 'mysql' ) );
                    break;
                case 'am_now':
                case 'already_did':
                    self::already_did( true );
                    break;
            }

            wp_send_json_success();

        } catch ( Exception $e ) {
            wp_send_json_error( $e );
        }
    }

    /**
     * @return int|string
     */
    public static function get_trigger_group() {
        static $selected;

        if ( ! isset( $selected ) ) {

            $dismissed_triggers = self::dismissed_triggers();

            $triggers = self::triggers();

            foreach ( $triggers as $g => $group ) {
                foreach ( $group['triggers'] as $t => $trigger ) {
                    if ( ! in_array( false, $trigger['conditions'] ) && ( empty( $dismissed_triggers[ $g ] ) || $dismissed_triggers[ $g ] < $trigger['pri'] ) ) {
                        $selected = $g;
                        break;
                    }
                }

                if ( isset( $selected ) ) {
                    break;
                }
            }
        }

        return $selected;
    }

    /**
     * @return int|string
     */
    public static function get_trigger_code() {
        static $selected;
        if ( ! isset( $selected ) ) {

            $dismissed_triggers = self::dismissed_triggers();

            foreach ( self::triggers() as $g => $group ) {
                foreach ( $group['triggers'] as $t => $trigger ) {

                    if ( ! in_array( false, $trigger['conditions'] ) && ( empty( $dismissed_triggers[ $g ] ) || $dismissed_triggers[ $g ] < $trigger['pri'] ) ) {
                        $selected = $t;
                        break;
                    }
                }

                if ( isset( $selected ) ) {
                    break;
                }
            }
        }

        return $selected;
    }

    /**
     * @param null $key
     *
     * @return bool|mixed|void
     */
    public static function get_current_trigger( $key = null ) {
        $group = self::get_trigger_group();
        $code  = self::get_trigger_code();

        if ( ! $group || ! $code ) {
            return false;
        }

        $trigger = self::triggers( $group, $code );

        if( empty( $key ) ) {
            $return = $trigger;
        } elseif ( isset( $trigger[ $key ] ) ){
            $return = $trigger[ $key ];
        } else {
            $return = false;
        }

        return $return;
    }

    /**
     * Returns an array of dismissed trigger groups.
     *
     * Array contains the group key and highest priority trigger that has been shown previously for each group.
     *
     * $return = array(
     *   'group1' => 20
     * );
     *
     * @return array|mixed
     */
    public static function dismissed_triggers() {
        $user_id = get_current_user_id();

        $dismissed_triggers = get_user_meta( $user_id, '_woocci_reviews_dismissed_triggers', true );

        if ( ! $dismissed_triggers ) {
            $dismissed_triggers = array();
        }

        return $dismissed_triggers;
    }

    /**
     * Returns true if the user has opted to never see this again. Or sets the option.
     *
     * @param bool $set If set this will mark the user as having opted to never see this again.
     *
     * @return bool
     */
    public static function already_did( $set = false ) {
        $user_id = get_current_user_id();

        if ( $set ) {
            update_user_meta( $user_id, '_woocci_reviews_already_did', true );

            return true;
        }

        return (bool) get_user_meta( $user_id, '_woocci_reviews_already_did', true );
    }
    public static function secret_not_exist() {
        $settings       = get_option( 'woocommerce_woocci_zaytech_settings' );

        if (isset($settings)){
            return !(isset($settings["secret_key"]) && !empty($settings["secret_key"]));
        }

        return true;
    }

    /**
     * Gets a list of triggers.
     *
     * @param null $group
     * @param null $code
     *
     * @return bool|mixed|void
     */
    public static function triggers( $group = null, $code = null ) {
        $link = 'https://wordpress.org/support/plugin/woo-clover-gateway-by-zaytech/reviews/?rate=5#rate-response';
        static $triggers;

        if ( ! isset( $triggers ) ) {

            $nbOrdersMessage = __( "Hey there! We noticed that you have successfully paid for more than %d orders, and that's fantastic news! If you have a moment, could you please consider giving us a 5-star rating on WordPress? Your positive feedback will greatly help us in spreading the word about our service. Thank you very much for your support!.", 'zaytech_woocci' );
            $time_message = __( "Hey there! We noticed that you've been using the WooCommerce Clover Payment Gateway for %s on your site, and we hope it has been helpful for you. If you have a moment, could you please consider giving it a 5-star rating on WordPress? Your positive feedback will greatly help us in spreading the word about our service. Thank you very much for your support!", 'zaytech_woocci' );

            $triggers = apply_filters( 'woocci_reviews_triggers', array(
                'time_installed' => array(
                    'triggers' => array(
                        'one_week'     => array(
                            'message'    => sprintf( $time_message, __( '1 week', 'zaytech_woocci' ) ),
                            'conditions' => array(
                                strtotime( self::installed_on() . ' +1 week' ) < time(),
                            ),
                            'link'       => $link,
                            'pri'        => 10,
                        ),
                        'one_month'    => array(
                            'message'    => sprintf( $time_message, __( '1 month', 'zaytech_woocci' ) ),
                            'conditions' => array(
                                strtotime( self::installed_on() . ' +1 month' ) < time(),
                            ),
                            'link'       => $link,
                            'pri'        => 20,
                        ),
                        'three_months' => array(
                            'message'    => sprintf( $time_message, __( '3 months', 'zaytech_woocci' ) ),
                            'conditions' => array(
                                strtotime( self::installed_on() . ' +3 months' ) < time(),
                            ),
                            'link'       => $link,
                            'pri'        => 30,
                        ),

                    ),
                    'pri'      => 10,
                ),
                'nbOrders'     => array(
                    'triggers' => array(
                        '10_orders'  => array(
                            'message'    => sprintf( $nbOrdersMessage, 10 ),
                            'conditions' => array(
                                get_option( 'woocci_total_success_orders', 0 ) > 10,
                            ),
                            'link'       => $link,
                            'pri'        => 10,
                        ),
                        '50_orders' => array(
                            'message'    => sprintf( $nbOrdersMessage, 50 ),
                            'conditions' => array(
                                get_option( 'woocci_total_success_orders', 0 ) > 50,

                            ),
                            'link'       => $link,
                            'pri'        => 20,
                        ),
                        '100_orders' => array(
                            'message'    => sprintf( $nbOrdersMessage, 100 ),
                            'conditions' => array(
                                get_option( 'woocci_total_success_orders', 0 ) > 100,
                            ),
                            'link'       => $link,
                            'pri'        => 30,
                        ),

                    ),
                    'pri'      => 50,
                ),
            ) );

            // Sort Groups
            uasort( $triggers, array( __CLASS__, 'rsort_by_priority' ) );

            // Sort each groups triggers.
            foreach ( $triggers as $k => $v ) {
                uasort( $triggers[ $k ]['triggers'], array( __CLASS__, 'rsort_by_priority' ) );
            }
        }

        if ( isset( $group ) ) {
            if ( ! isset( $triggers[ $group ] ) ) {
                return false;
            }

            if ( ! isset( $code ) ) {
                $return = $triggers[ $group ];
            } elseif ( isset( $triggers[ $group ]['triggers'][ $code ] ) ) {
                $return = $triggers[ $group ]['triggers'][ $code ];
            } else {
                $return = false;
            }

            return $return;
        }

        return $triggers;
    }

    /**
     * Render admin notices if available.
     */
    public static function admin_notices() {
        if ( self::hide_notices() ) {
            return;
        }

        $group  = self::get_trigger_group();
        $code   = self::get_trigger_code();
        $pri    = self::get_current_trigger( 'pri' );
        $trigger = self::get_current_trigger();

        // Used to anonymously distinguish unique site+user combinations in terms of effectiveness of each trigger.
        $uuid = wp_hash( home_url() . '-' . get_current_user_id() );

        ?>

        <script type="text/javascript">
            (function ($) {
                var trigger = {
                    group: '<?php echo $group; ?>',
                    code: '<?php echo $code; ?>',
                    pri: '<?php echo $pri; ?>'
                };

                function dismiss(reason) {
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        url: ajaxurl,
                        data: {
                            action: 'woocci_review_action',
                            nonce: '<?php echo wp_create_nonce( 'woocci_review_action' ); ?>',
                            group: trigger.group,
                            code: trigger.code,
                            pri: trigger.pri,
                            reason: reason
                        }
                    });

                    <?php if ( ! empty( self::$api_url ) ) : ?>
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        url: '<?php echo self::$api_url; ?>',
                        data: {
                            trigger_group: trigger.group,
                            trigger_code: trigger.code,
                            reason: reason,
                            uuid: '<?php echo $uuid; ?>'
                        }
                    });
                    <?php endif; ?>
                }

                $(document)
                    .on('click', '.woocci_notice .woocci_dismiss', function (event) {
                        var $this = $(this),
                            reason = $this.data('reason'),
                            notice = $this.parents('.woocci_notice');

                        notice.fadeTo(100, 0, function () {
                            notice.slideUp(100, function () {
                                notice.remove();
                            });
                        });

                        dismiss(reason);
                    })
                    .ready(function () {
                        setTimeout(function () {
                            $('.woocci_notice button.notice-dismiss').click(function (event) {
                                dismiss('maybe_later');
                            });
                        }, 1000);
                    });
            }(jQuery));
        </script>
        <style>
            .woocci_notice p {
                margin-bottom: 0;
            }

            .woocci_notice img.logo {
                float: right;
                margin-left: 10px;
                width: 75px;
                padding: 0.25em;
                border: 1px solid #ccc;
            }
        </style>


        <div class="notice notice-success is-dismissible woocci_notice">

            <p>
                <img class="logo" src="<?php echo WOOCCI_PLUGIN_URL; ?>/assets/images/icon-256x256.png" />
                <strong>
                    <?php echo $trigger['message']; ?>
                </strong>
            </p>
            <ul>
                <li>
                    <a class="woocci_dismiss" target="_blank" href="https://wordpress.org/support/plugin/popup-maker/reviews/?rate=5#rate-response" data-reason="am_now">
                        <strong><?php _e( 'Ok, you deserve it', 'zaytech_woocci' ); ?></strong>
                    </a>
                </li>
                <li>
                    <a href="#" class="woocci_dismiss" data-reason="maybe_later">
                        <?php _e( 'Nope, maybe later', 'zaytech_woocci' ); ?>
                    </a>
                </li>
                <li>
                    <a href="#" class="woocci_dismiss" data-reason="already_did">
                        <?php _e( 'I already did', 'zaytech_woocci' ); ?>
                    </a>
                </li>
            </ul>

        </div>

        <?php
    }

    /**
     * Checks if notices should be shown.
     *
     * @return bool
     */
    public static function hide_notices() {

        $conditions = array(
                self::secret_not_exist(),
                self::already_did(),
                self::last_dismissed() && strtotime( self::last_dismissed() . ' +2 weeks' ) > time(),
                empty( self::get_trigger_code() ),
        );

        return in_array( true, $conditions );
    }

    /**
     * Gets the last dismissed date.
     *
     * @return false|string
     */
    public static function last_dismissed() {
        $user_id = get_current_user_id();
        return get_user_meta( $user_id, '_woocci_reviews_last_dismissed', true );
    }

    /**
     * Sort array by priority value
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    public static function sort_by_priority( $a, $b ) {
        if ( ! isset( $a['pri'] ) || ! isset( $b['pri'] ) || $a['pri'] === $b['pri'] ) {
            return 0;
        }

        return ( $a['pri'] < $b['pri'] ) ? - 1 : 1;
    }

    /**
     * Sort array in reverse by priority value
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    public static function rsort_by_priority( $a, $b ) {
        if ( ! isset( $a['pri'] ) || ! isset( $b['pri'] ) || $a['pri'] === $b['pri'] ) {
            return 0;
        }

        return ( $a['pri'] < $b['pri'] ) ? 1 : - 1;
    }

}

Woocci_Zaytech_Rev::init();