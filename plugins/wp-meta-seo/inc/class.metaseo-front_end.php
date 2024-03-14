<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php');
include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmsgapi.php');

/**
 * Class MetaSeoFront
 * Class that holds most of the admin functionality for Meta SEO.
 */
class MetaSeoFront
{
    /**
     * Google analytics tracking params
     *
     * @var array
     */
    public $ga_tracking;
    /**
     * Google analytics disconnect
     *
     * @var array
     */
    public $gaDisconnect;

    /**
     * Google tag manager
     *
     * @var array
     */
    public $google_tagmanager;

    /**
     * MetaSeoFront constructor.
     */
    public function __construct()
    {
        $this->ga_tracking = array(
            'wpmsga_dash_tracking' => 1,
            'wpmsga_dash_tracking_type' => 'universal',
            'wpmsga_dash_anonim' => 0,
            'wpmsga_dash_remarketing' => 0,
            'wpmsga_event_tracking' => 0,
            'wpmsga_event_downloads' => 'zip|mp3*|mpe*g|pdf|docx*|pptx*|xlsx*|rar*',
            'wpmsga_aff_tracking' => 0,
            'wpmsga_event_affiliates' => '/out/',
            'wpmsga_hash_tracking' => 0,
            'wpmsga_author_dimindex' => 0,
            'wpmsga_pubyear_dimindex' => 0,
            'wpmsga_category_dimindex' => 0,
            'wpmsga_user_dimindex' => 0,
            'wpmsga_tag_dimindex' => 0,
            'wpmsga_speed_samplerate' => 1,
            'wpmsga_event_bouncerate' => 0,
            'wpmsga_enhanced_links' => 0,
            'wpmsga_dash_adsense' => 0,
            'wpmsga_crossdomain_tracking' => 0,
            'wpmsga_crossdomain_list' => '',
            'wpmsga_cookiedomain' => '',
            'wpmsga_cookiename' => '',
            'wpmsga_cookieexpires' => '',
            'wpmsga_track_exclude' => array(),
        );

        $ga_tracking = get_option('_metaseo_ggtracking_settings');
        if (is_array($ga_tracking)) {
            $this->ga_tracking = array_merge($this->ga_tracking, $ga_tracking);
        }

        $this->gaDisconnect = array(
            'wpms_gg_service_tracking_id' => '',
            'wpms_gg_service_tracking_type' => 'universal',
            'wpmsga_code_tracking' => '',
            'wpmstm_header_code_tracking' => '',
            'wpmstm_body_code_tracking' => ''
        );

        $this->google_tagmanager = array(
            'list_accounts' => array(),
            'list_containers' => array(),
            'selected_account' => '',
            'selected_container' => ''
        );

        $gg_tagmanager = get_option('wpms_tagmanager_setting');
        if (is_array($gg_tagmanager)) {
            $this->google_tagmanager = array_merge($this->google_tagmanager, $gg_tagmanager);
        }
        $gaDisconnect = get_option('_metaseo_ggtracking_disconnect_settings');
        if (is_array($gaDisconnect)) {
            $this->gaDisconnect = array_merge($this->gaDisconnect, $gaDisconnect);
        }

        add_action('wp_head', array($this, 'trackingCode'), 99);
        add_action('wp_head', array($this, 'wpms_gg_disconnect_tracking_header'), 99);
        add_action('wp_body_open', array($this, 'wpms_gg_disconnect_tracking_body'), 99);
    }

    /**
     * Create tracking code on front-end
     *
     * @return boolean
     */
    public function trackingCode()
    {
        if (WpmsGaTools::checkRoles($this->ga_tracking['wpmsga_track_exclude'], true)) {
            return false;
        }

        $google_alanytics = get_option('wpms_google_alanytics');
        $traking_mode = $this->ga_tracking['wpmsga_dash_tracking'];

        // If enabled tracking option
        if ($traking_mode > 0) {
            // If have tracking-id then WPMS tracking data
            if (!empty($google_alanytics['tableid_jail'])) {
                // Get selected profile
                $profile_info = WpmsGaTools::getSelectedProfile(
                    $google_alanytics['profile_list'],
                    $google_alanytics['tableid_jail']
                );

                // Add google analytics tag to website
                if (!isset($profile_info[4])) {
                    return false;
                }
                if ($profile_info[4] === 'UA') {
                    $traking_type = $this->ga_tracking['wpmsga_dash_tracking_type'];
                    if ($traking_type === 'classic') {
                        echo "\n<!-- Classic Tracking - https://wordpress.org/plugins/wp-meta-seo/ -->\n";
                        if ($this->ga_tracking['wpmsga_event_tracking']) {
                            require_once 'google_analytics/tracking/events-classic.php';
                        }
                        require_once 'google_analytics/tracking/code-classic.php';
                        echo "\n<!-- END WPMSGA Classic Tracking -->\n\n";
                    } elseif ($traking_type === 'universal') {
                        echo "\n<!-- Universal Tracking - https://wordpress.org/plugins/wp-meta-seo/ -->\n";
                        if ($this->ga_tracking['wpmsga_event_tracking']
                            || $this->ga_tracking['wpmsga_aff_tracking']
                            || $this->ga_tracking['wpmsga_hash_tracking']) {
                            require_once 'google_analytics/tracking/events-universal.php';
                        }
                        require_once 'google_analytics/tracking/code-universal.php';
                        echo "\n<!-- END WPMSGA Universal Tracking -->\n\n";
                    } else {
                        return false;
                    }
                } else {
                    echo "\n<!-- WPMSGA Google Analytics 4 Tracking - https://wordpress.org/plugins/wp-meta-seo/ -->\n";
                    if ($this->ga_tracking['wpmsga_event_tracking']
                        || $this->ga_tracking['wpmsga_aff_tracking'] || $this->ga_tracking['wpmsga_hash_tracking']) {
                        // Add tag for both Universal and Google analytics 4 property
                        echo "\n<!-- Events tracking -->\n\n";
                        require_once 'google_analytics/tracking/events-ga4.php';
                        echo "\n<!-- End events tracking -->\n\n";
                    }
                    require_once 'google_analytics/tracking/code-ga4.php';
                    echo "\n<!-- END WPMSGA Google Analytics 4 Tracking -->\n\n";
                }
            }
        }
        return true;
    }

    /**
     * Add Google service analytics and tag manager tracking code to header website
     *
     * @return void
     */
    public function wpms_gg_disconnect_tracking_header()
    {
        $tracking_type = $this->gaDisconnect['wpms_gg_service_tracking_type'];
        $tracking_id = $this->gaDisconnect['wpms_gg_service_tracking_id'];
        $wpmstm_header_code_tracking = $this->gaDisconnect['wpmstm_header_code_tracking'];
        $wpmsga_code_tracking = $this->gaDisconnect['wpmsga_code_tracking'];
        if ($tracking_type && $tracking_id) {
            if ($tracking_type === 'tagmanager') {
                // Add GTM tracking js code to header
                require_once 'google-tag-manager/tracking/gtm-header.php';
            } elseif ($tracking_type === 'analytics4') {
                // Add GA v4 tracking js code
                require_once 'google_analytics/tracking/ga4_disconnect.php';
            } elseif ($tracking_type === 'classic') {
                // Add classic GA tracking js code
                require_once 'google_analytics/tracking/classic_disconnect.php';
            } else {
                // Add universal GA tracking js code
                require_once 'google_analytics/tracking/universal_disconnect.php';
            }
        }
        // If user directly GA tracking js code
        if (!empty($wpmsga_code_tracking)) {
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content has saved by user when save Analytics JS code
            echo stripslashes($wpmsga_code_tracking);
        }
        // If user directly GTM header tracking js code
        if (!empty($wpmstm_header_code_tracking)) {
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content has saved by user when save GTM JS code
            echo stripslashes($wpmstm_header_code_tracking);
        }
    }

    /**
     * Add Google Tag Manager to body website
     *
     * @return void
     */
    public function wpms_gg_disconnect_tracking_body()
    {
        $tracking_type = $this->gaDisconnect['wpms_gg_service_tracking_type'];
        $tracking_id = $this->gaDisconnect['wpms_gg_service_tracking_id'];
        $wpmstm_body_code_tracking = $this->gaDisconnect['wpmstm_body_code_tracking'];
        if (isset($tracking_type) && $tracking_type === 'tagmanager' && $tracking_id) {
            // Add GTM tracking js code to body
            require_once 'google-tag-manager/tracking/gtm-body.php';
        }

        // If user directly GTM body tracking js code
        if (!empty($wpmstm_body_code_tracking)) {
            // phpcs:ignore WordPress.Security.EscapeOutput -- Content has saved by user when save GTM body JS code
            echo stripslashes($wpmstm_body_code_tracking);
        }
    }
}
