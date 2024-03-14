<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Log {

    public function __construct() {
        add_action( 'wp_ajax_tochatbe_click_log', array( 'TOCHATBE_Log', 'click_log' ) );
        add_action( 'wp_ajax_nopriv_tochatbe_click_log', array( 'TOCHATBE_Log', 'click_log' ) );
    }

    public static function click_log() {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . 'tochatbe_log',
            array(
                'ip'             => self::get_current_ip(),
                'message'        => sanitize_text_field( $_POST['message'] ),
                'contacted_to'   => sanitize_text_field( $_POST['contacted_to'] ),
                'user'           => sanitize_text_field( self::current_username() ),
                'referral'       => esc_url_raw( self::get_current_url() ),
                'device_type'    => ( wp_is_mobile() == true ? 'Mobile' : 'Desktop' ),
                'timestamp'      => current_time( 'M d, y - H:i:s' ),
                'unix_timestamp' => current_time( 'timestamp' ),
            )
        );


        wp_die();
    }

    public static function log( $args = array() ) {
        $a = wp_parse_args( $args, array(
            'ip'             => self::get_current_ip(),
            'message'        => '',
            'contacted_to'   => '',
            'user'           => self::current_username(),
            'referral'       => self::get_current_url(),
            'device_type'    => ( wp_is_mobile() == true ? 'Mobile' : 'Desktop' ),
            'timestamp'      => current_time( 'M d, y - H:i:s' ),
            'unix_timestamp' => current_time( 'timestamp' ),
        ) );

        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . 'tochatbe_log',
            array(
                'ip'             => $a['ip'],
                'message'        => sanitize_text_field( $a['message'] ),
                'contacted_to'   => sanitize_text_field( $a['contacted_to'] ),
                'user'           => sanitize_text_field( $a['user'] ),
                'referral'       => sanitize_text_field( $a['referral']
             ),
                'device_type'    => $a['device_type'],
                'timestamp'      => $a['timestamp'],
                'unix_timestamp' => $a['unix_timestamp'],
            )
        );
    }

    public static function get_current_url() {
        return wp_get_referer();
    }

    public static function get_current_ip() {
        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public static function getOS() {
        $os_platform = "Unknown OS Platform";
        $os_array    = array(
            '/windows nt 10/i'      => 'Windows 10',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'Windows XP',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile',
        );

        foreach ( $os_array as $regex => $value ) {
            if ( preg_match( $regex, $_SERVER['HTTP_USER_AGENT'] ) ) {
                $os_platform = $value;
            }
        }

        return $os_platform;
    }

    protected static function getBrowser() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if ( strpos( $user_agent, 'Maxthon' ) !== false ) {
            return "Maxthon";
        } elseif ( strpos( $user_agent, 'SeaMonkey' ) !== false ) {
            return "SeaMonkey";
        } elseif ( strpos( $user_agent, 'Vivaldi' ) !== false ) {
            return "Vivaldi";
        } elseif ( strpos( $user_agent, 'Arora' ) !== false ) {
            return "Arora";
        } elseif ( strpos( $user_agent, 'Avant Browser' ) !== false ) {
            return "Avant Browser";
        } elseif ( strpos( $user_agent, 'Beamrise' ) !== false ) {
            return "Beamrise";
        } elseif ( strpos( $user_agent, 'Epiphany' ) !== false ) {
            return 'Epiphany';
        } elseif ( strpos( $user_agent, 'Chromium' ) !== false ) {
            return 'Chromium';
        } elseif ( strpos( $user_agent, 'Iceweasel' ) !== false ) {
            return 'Iceweasel';
        } elseif ( strpos( $user_agent, 'Galeon' ) !== false ) {
            return 'Galeon';
        } elseif ( strpos( $user_agent, 'Edge' ) !== false ) {
            return 'Microsoft Edge';
        } elseif ( strpos( $user_agent, 'Trident' ) !== false ) {
            return 'Internet Explorer';
        } elseif ( strpos( $user_agent, 'MSIE' ) !== false ) {
            return 'Internet Explorer';
        } elseif ( strpos( $user_agent, 'Opera Mini' ) !== false ) {
            return "Opera Mini";
        } elseif ( strpos( $user_agent, 'Opera' ) || strpos( $user_agent, 'OPR' ) !== false ) {
            return "Opera";
        } elseif ( strpos( $user_agent, 'Firefox' ) !== false ) {
            return 'Mozilla Firefox';
        } elseif ( strpos( $user_agent, 'Chrome' ) !== false ) {
            return 'Google Chrome';
        } elseif ( strpos( $user_agent, 'Safari' ) !== false ) {
            return "Safari";
        } elseif ( strpos( $user_agent, 'iTunes' ) !== false ) {
            return 'iTunes';
        } elseif ( strpos( $user_agent, 'Konqueror' ) !== false ) {
            return 'Konqueror';
        } elseif ( strpos( $user_agent, 'Dillo' ) !== false ) {
            return 'Dillo';
        } elseif ( strpos( $user_agent, 'Netscape' ) !== false ) {
            return 'Netscape';
        } elseif ( strpos( $user_agent, 'Midori' ) !== false ) {
            return 'Midori';
        } elseif ( strpos( $user_agent, 'ELinks' ) !== false ) {
            return 'ELinks';
        } elseif ( strpos( $user_agent, 'Links' ) !== false ) {
            return 'Links';
        } elseif ( strpos( $user_agent, 'Lynx' ) !== false ) {
            return 'Lynx';
        } elseif ( strpos( $user_agent, 'w3m' ) !== false ) {
            return 'w3m';
        } else {
            return 'Unknown';
        }
    }

    public static function get_total_click() {
        global $wpdb;

        $query = $wpdb->get_results(
            "SELECT id
            FROM {$wpdb->prefix}tochatbe_log"
        );

        return count( $query );
    }

    public static function get_total_day_click() {
        global $wpdb;

        $today_start_unix_timestamp   = strtotime( 'today midnight' );
        $today_currnet_unix_timestamp = current_time( 'timestamp' );

        $query = $wpdb->get_results( $wpdb->prepare(
                    "SELECT id
                    FROM {$wpdb->prefix}tochatbe_log
                    WHERE unix_timestamp
                    BETWEEN %s AND %s", 
                    $today_start_unix_timestamp, 
                    $today_currnet_unix_timestamp
                ) );

        return count( $query );
    }

    public static function get_this_week_click() {
        global $wpdb;

        $this_week_start_unix_timestamp   = strtotime( '-1 week' );
        $this_week_currnet_unix_timestamp = current_time( 'timestamp' );

        $query = $wpdb->get_results( $wpdb->prepare(
                    "SELECT id
                    FROM {$wpdb->prefix}tochatbe_log
                    WHERE unix_timestamp
                    BETWEEN %s AND %s",
                    $this_week_start_unix_timestamp,
                    $this_week_currnet_unix_timestamp
                ) );

        return count( $query );
    }

    public static function get_this_month_click() {
        global $wpdb;

        $this_month_start_unix_timestamp   = strtotime( '-1 month' );
        $this_month_currnet_unix_timestamp = current_time( 'timestamp' );

        $query = $wpdb->get_results( $wpdb->prepare(
                    "SELECT id
                    FROM {$wpdb->prefix}tochatbe_log
                    WHERE unix_timestamp
                    BETWEEN %s AND %s",
                    $this_month_start_unix_timestamp,
                    $this_month_currnet_unix_timestamp
                ) );

        return count( $query );
    }

    protected static function current_username() {
        $current_user = wp_get_current_user();

        return ( $current_user->display_name ) 
            ? $current_user->display_name 
            : 'Guest';
    }
}

new TOCHATBE_Log;