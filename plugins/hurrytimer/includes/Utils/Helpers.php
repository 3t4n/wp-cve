<?php

namespace Hurrytimer\Utils;

use DateTime;

class Helpers
{
    public static function ip_address()
    {
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Get admin preferences' date format.
     *
     * @param string $date
     *
     * @return string
     */
    public static function format_date($date)
    {
        $date_format = get_option('date_format') ?: 'M d, Y';

        return date($date_format, strtotime($date));
    }

    /**
     * Check if WC's active.
     *
     * @return boolean
     */
    public static function isWcActive()
    {
        if (!is_admin()) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active('woocommerce/woocommerce.php');
    }

    /**
     * Check if WC's active.
     *
     * @return boolean
     */
    public static function isPluginInstalled()
    {
        if (!is_admin()) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active('hurrytimer/hurrytimer.php');
    }

    /**
     * Check if WC's active.
     *
     * @return boolean
     */
    public static function isPluginProInstalled()
    {
        if (!is_admin()) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active('hurrytimer-pro/hurrytimer.php');
    }

    public static function date_time($datetime)
    {
        return new DateTime($datetime);
    }

    public static function get_timezone_by_offset($offset)
    {
        list($hours, $minutes) = wp_parse_args([0, 0], explode(':', $offset));
        $seconds = $hours * 60 * 60 + $minutes * 60;

        return timezone_name_from_abbr(null, $seconds, true);
    }

    public static function date_later($seconds)
    {
        $now = current_time('mysql');

        return date('Y-m-d H:i:s', strtotime($now) + $seconds);
    }

    public static function isNewPost($post_id)
    {
        return get_post_status($post_id) === "auto-draft";
    }

    public static function isPostActive($post_id)
    {
        return get_post_status($post_id) === "publish"
            || get_post_status($post_id) === "future";
    }

    public static function activateUrl($post_id)
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'hurrytimer_action' => 'activate-compaign',
                    'postid' => $post_id,
                    'post' => $post_id,
                    'action' => 'edit',
                ),
                admin_url('post.php')
            ),
            'activate-compaign'
        );
    }

    public static function deactivateUrl($post_id)
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'hurrytimer_action' => 'deactivate-compaign',
                    'postid' => $post_id,
                    'post' => $post_id,
                    'action' => 'edit',
                ),
                admin_url('post.php')
            ),
            'deactivate-compaign'
        );
    }

    public static function camelToSnakeCase($str)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }

    public static function snakeToCamelCase($str)
    {
        $arr = explode('_', $str);
        $arr = array_map(function ($i, $word) {
            if ($i === 0) {
                return $word;
            }

            return ucfirst($word);
        }, array_keys($arr), $arr);

        return implode('', $arr);
    }

    public static function getCampaigns($args = [])
    {
        return get_posts(array_merge(
            [
                'post_type' => HURRYT_POST_TYPE,
                'numberposts' => -1,
            ],
            $args
        ));
    }

    public static function are_urls_match($url1, $url2)
    {
        $url1_path = untrailingslashit(parse_url($url1, PHP_URL_PATH)?: '');
        $url2_path = untrailingslashit(parse_url($url2, PHP_URL_PATH)?: '');

        return $url1_path === $url2_path;
    }
    public static function createResetAllEvergreenCampaignsUrl($scope = 'admin')
    {
        return wp_nonce_url(
            add_query_arg(
                array(
                    'hurryt-action' => 'reset-all-evergreen-compaigns',
                    'hurryt-scope' => $scope,
                    'hurryt-page' => 'hurrytimer_settings',
                ),
                admin_url('admin.php?page=hurrytimer_settings')
            ),
            'reset-all-evergreen-compaigns'
        );
    }
}
