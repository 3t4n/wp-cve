<?php

namespace TenWebPluginIO;

class Utils
{
    const BOOSTER_PlUGIN_FILE = 'tenweb-speed-optimizer/tenweb_speed_optimizer.php';

    /**
     * Check the plugin status.
     *
     * @return int 0-not installed, 1-not active, 2-active
     */
    public static function getBoosterStatus()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active(self::BOOSTER_PlUGIN_FILE)) {
            return 2;
        } else if (self::pluginInstalled(self::BOOSTER_PlUGIN_FILE)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Check if the plugin already installed.
     *
     * @param string $slug plugin's slug
     *
     * @return bool
     */
    private static function pluginInstalled($file)
    {
        if (!function_exists('get_plugins')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        $all_plugins = get_plugins();

        return !empty($all_plugins[$file]);
    }

    /**
     * Get Google page speed score.
     */
    public static function getPageScore()
    {
        $nonce = isset($_POST['speed_ajax_nonce']) ? sanitize_text_field($_POST['speed_ajax_nonce']) : '';
        if (!wp_verify_nonce($nonce, 'speed_ajax_nonce')) {
            die;
        }

        $url = isset($_POST['iowd_url']) ? sanitize_url($_POST['iowd_url']) : '';

        self::checkUrlValidation($url);

        $post_id = url_to_postid($url);
        $home_url = get_home_url();
        if ($post_id !== 0 && get_post_status($post_id) != 'publish' && rtrim($url, "/") != rtrim($home_url, "/")) {
            echo json_encode(array('error' => 1, 'msg' => esc_html__('This page is not public. Please publish the page to check the score.', 'tenweb-image-optimizer')));
            die;
        }

        $result = \TenWebSC\TWScoreChecker::twsc_google_check_score($url, 'desktop');
        if (!empty($result['error']) || empty($result)) {
            die;
        }
        $score['desktop'] = $result['desktop_score'];
        $score['desktop_loading_time'] = $result['desktop_tti'];

        $result = \TenWebSC\TWScoreChecker::twsc_google_check_score($url, 'mobile');
        if (!empty($result['error']) || empty($result)) {
            die;
        }
        $score['mobile'] = $result['mobile_score'];
        $score['mobile_loading_time'] = $result['mobile_tti'];

        $nowdate = current_time('mysql');
        $nowdate = date('d.m.Y h:i:s a', strtotime($nowdate));

        $data = get_option('iowd_speed_score');
        $data[$url] = array(
            'desktop_score'        => $score['desktop'],
            'desktop_loading_time' => $score['desktop_loading_time'],
            'mobile_score'         => $score['mobile'],
            'mobile_loading_time'  => $score['mobile_loading_time'],
            'last_analyzed_time'   => $nowdate,
            'error'                => 0
        );
        $data['last'] = array(
            'url' => $url
        );
        update_option('iowd_speed_score', $data, 1);

        $homepage_optimized = get_option('iowd_homepage_optimized');
        if (rtrim($url, "/") == rtrim($home_url, "/") && !empty($homepage_optimized) && $homepage_optimized == 1) {
            update_option('iowd_homepage_optimized', 2);
        }
        echo json_encode(array(
            'desktop_score'        => esc_html($score['desktop']),
            'desktop_loading_time' => esc_html($score['desktop_loading_time']),
            'mobile_score'         => esc_html($score['mobile']),
            'mobile_loading_time'  => esc_html($score['mobile_loading_time']),
            'last_analyzed_time'   => esc_html($nowdate),
        ));
        die;
    }

    private static function checkUrlValidation($url)
    {
        /* Check if url hasn't http or https add */
        if (strpos($url, 'http') !== 0) {
            if (isset($_SERVER['HTTPS'])) {
                $url = 'https://' . $url;
            } else {
                $url = 'http://' . $url;
            }
        }

        /* Check if the url is valid */
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            echo json_encode(array('error' => 1));
            die;
        }
    }

    /**
     * Install/activate the plugin.
     *
     * @param $status
     *
     * @return bool|int|true|WP_Error|null
     */
    public static function installBooster()
    {
        $speed_ajax_nonce = isset($_POST['speed_ajax_nonce']) ? sanitize_text_field($_POST['speed_ajax_nonce']) : '';
        $install_optimize = isset($_POST['install_optimize']) ? sanitize_text_field($_POST['install_optimize']) : '';
        if (!wp_verify_nonce($speed_ajax_nonce, 'speed_ajax_nonce')) {
            die;
        }
        $so_file = 'tenweb-speed-optimizer/tenweb_speed_optimizer.php';
        $so_zip = 'https://downloads.wordpress.org/plugin/tenweb-speed-optimizer.latest-stable.zip';
        $activated = false;
        if (self::getBoosterStatus() == 0) {
            include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
            wp_cache_flush();
            $upgrader = new \Plugin_Upgrader();
            $installed = $upgrader->install($so_zip);
        } else {
            $installed = true;
        }
        if (!is_wp_error($installed) && $installed) {
            $activated = activate_plugin($so_file);
        }

        // To change the plugin status on Dashboard.
        if (class_exists('\Tenweb_Authorization\Helper')
            && method_exists('\Tenweb_Authorization\Helper', "check_site_state")) {
            \Tenweb_Authorization\Helper::check_site_state(true);
        }

        if ($activated === null && $install_optimize) {
            $connect_link = \TenWebOptimizer\OptimizerUtils::get_tenweb_connection_link();
            wp_send_json_success(array(
                'status'              => 'success',
                'booster_connect_url' => esc_url($connect_link)
            ));
        }

        return $activated;
    }

    public static function getImagesData()
    {
        $dataIO = array();
        if (class_exists('\TenWebIO\CompressDataService')) {
            $compress_data_service = new \TenWebIO\CompressDataService();
            $dataIO = $compress_data_service->getCompressResults();
        }

        return $dataIO;
    }

    public static function isFreeUser()
    {
        $dataIO = self::getImagesData();

        return (!empty($dataIO) && !empty($dataIO['limitation']) &&
            isset($dataIO['limitation']['subscription_id']) && $dataIO['limitation']['subscription_id'] == TENWEB_SO_FREE_SUBSCRIPTION_ID);
    }

    public static function formatBytes($size)
    {
        $unit = ['Byte', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = 0;
        while ($size >= 1024 && $i < count($unit) - 1) {
            $size /= 1024;
            $i++;
        }

        return number_format((float)($size), 2, '.', '') . ' ' . $unit[$i];
    }

}