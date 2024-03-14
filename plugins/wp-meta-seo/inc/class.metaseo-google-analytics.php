<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Class MetaSeoGoogleAnalytics
 * Base class for displaying your google analytics.
 */
class MetaSeoGoogleAnalytics
{
    /**
     * Ajax display google analytics
     *
     * @return void
     */
    public static function itemsReport()
    {
        include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php');
        include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmsgapi.php');
        $google_alanytics = get_option('wpms_google_alanytics');

        if (!isset($_POST['wpms_security_backend_item_reports'])
            || !wp_verify_nonce($_POST['wpms_security_backend_item_reports'], 'wpms_backend_item_reports')) {
            wp_die(- 30);
        }

        if (isset($_POST['projectId']) && $_POST['projectId'] !== 'false') {
            $projectId = $_POST['projectId'];
        } else {
            $projectId = false;
        }
        $from  = $_POST['from'];
        $to    = $_POST['to'];
        $query = $_POST['query'];
        if (isset($_POST['filter'])) {
            $filter_id = $_POST['filter'];
        } else {
            $filter_id = false;
        }
        if (ob_get_length()) {
            ob_clean();
        }
        if (!empty($google_alanytics['tableid_jail'])) {
            if (empty($controller)) {
                $controller = new WpmsGapiController();
            }
        } else {
            wp_die(- 99);
        }

        if (!empty($google_alanytics['googleCredentials']) && !empty($google_alanytics['tableid_jail'])
            && isset($from) && isset($to)) {
            if (empty($controller)) {
                $controller = new WpmsGapiController();
            }
        } else {
            wp_die(- 24);
        }

        if (!$projectId) {
            $projectId = $google_alanytics['tableid_jail']; // View ID of Google Analytics universal
            $property_type = 'UA';
        }
        $profile_info = WpmsGaTools::getSelectedProfile($google_alanytics['profile_list'], $projectId);
        if (isset($profile_info[4]) && $profile_info[4] === 'GA4') {
            $projectId = $profile_info[2]; // Property ID of GA4
            $property_type = 'GA4';
        }
        if (isset($profile_info[5])) {
            $controller->timeshift = $profile_info[5];
        } else {
            $controller->timeshift = (int) current_time('timestamp') - time();
        }

        $filter = false;
        if ($filter_id) {
            $uri_parts = explode('/', get_permalink($filter_id), 4);

            if (isset($uri_parts[3])) {
                $uri = '/' . $uri_parts[3];
                /**
                 * Allow URL correction before sending an API request
                 *
                 * @param string URL
                 */
                $filter   = apply_filters('wpmsga_backenditem_uri', $uri);
                $lastchar = substr($filter, - 1);

                if (isset($profile_info[7]) && $profile_info[7] && $lastchar === '/') {
                    $filter = $filter . $profile_info[7];
                }

                // Encode URL
                $filter = rawurlencode(rawurldecode($filter));
            } else {
                wp_die(- 25);
            }
        }
        $queries = explode(',', $query);
        $results = array();
        foreach ($queries as $value) {
            $results[] = $controller->get($projectId, $value, $from, $to, $filter, $property_type);
        }

        wp_send_json($results);
    }

    /**
     * Update analytics option
     *
     * @return void
     */
    public static function updateOption()
    {
        if (empty($_POST['wpms_nonce'])
            || !wp_verify_nonce($_POST['wpms_nonce'], 'wpms_nonce')) {
            die();
        }

        $options = get_option('wpms_google_alanytics');
        if (isset($_POST['userapi'])) {
            $options['wpmsga_dash_userapi'] = $_POST['userapi'];
            update_option('wpms_google_alanytics', $options);
        }
        wp_send_json(true);
    }

    /**
     * Ajax clear author
     *
     * @return void
     */
    public static function clearAuthor()
    {
        delete_option('wpms_google_alanytics');
        wp_send_json(true);
    }

    /**
     * Get map
     *
     * @param string $map Map
     *
     * @return mixed|string
     */
    public static function map($map)
    {
        $map = explode('.', $map);
        if (isset($map[1])) {
            $map[0] += ord('map');
            return implode('.', $map);
        } else {
            return str_ireplace('map', chr(112), $map[0]);
        }
    }

    /**
     * Get dashboard widget Google Analytics data
     *
     * @return mixed
     */
    public static function analyticsWidgetsData()
    {
        if (!isset($_POST['wpms_security'])
            || !wp_verify_nonce($_POST['wpms_security'], 'dashboard_analytics_widgets_security')) {
            wp_die(- 30);
        }
        $googleAnalytics = get_option('wpms_google_alanytics');
        if (empty($googleAnalytics)) {
            wp_die('Empty settings');
        }

        $requestDate = isset($_POST['requestDate']) ? trim($_POST['requestDate']) : 'today';
        $requestQuery = isset($_POST['requestQuery']) ? trim($_POST['requestQuery']) : 'sessions';
        // Save change selection
        if ((isset($_POST['saveChange']) && $_POST['saveChange']) || !isset($googleAnalytics['dashboard_analytics_widgets'])) {
            $googleAnalytics['dashboard_analytics_widgets'] = array(
                'requestDate' => $requestDate,
                'requestQuery' => $requestQuery,
            );
            update_option('wpms_google_alanytics', $googleAnalytics);
        }

        $from  =  isset($_POST['from']) ? trim($_POST['from']) : '30daysAgo';
        $to    = isset($_POST['to']) ? trim($_POST['to']) : 'yesterday';
        include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php');
        include_once(WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmsgapi.php');
        if (ob_get_length()) {
            ob_clean();
        }
        if (!empty($googleAnalytics['tableid_jail'])) {
            $controller = new WpmsGapiController();
        } else {
            wp_die(- 99);
        }

        if (!empty($googleAnalytics['googleCredentials']) && !empty($googleAnalytics['tableid_jail'])
            && isset($from) && isset($to)) {
            if (empty($controller)) {
                $controller = new WpmsGapiController();
            }
        } else {
            wp_die(- 24);
        }

        $projectId = $googleAnalytics['tableid_jail']; // View ID of Google Analytics Universal
        $property_type = 'UA';

        $profile_info = WpmsGaTools::getSelectedProfile($googleAnalytics['profile_list'], $projectId);
        if (isset($profile_info[4]) && $profile_info[4] === 'GA4') {
            $projectId = $profile_info[2]; // Property ID of GA4
            $property_type = 'GA4';
        }
        if (isset($profile_info[5])) {
            $controller->timeshift = $profile_info[5];
        } else {
            $controller->timeshift = (int) current_time('timestamp') - time();
        }

        $results = array();
        $results['data'] = $controller->get($projectId, $requestQuery, $from, $to, false, $property_type);
        $results['requestQuery'] = $requestQuery;

        wp_send_json($results);
    }
}
