<?php

namespace WPSocialReviews\App\Services\Platforms\Chats;

use WPSocialReviews\App\App;
use WPSocialReviews\App\Services\Platforms\Chats\Helper as chatHelper;
use WPSocialReviews\App\Services\Helper as GlobalHelper;
use WPSocialReviews\App\Services\Platforms\Chats\Config;
use WPSocialReviews\App\Services\Includes\CountryNames;

use WPSocialReviews\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

class SocialChat extends BaseChat
{
    /**
     *
     * Get Credential
     *
     * @return json response
     * @since 1.0.0
     */
    public function getSettings($postId = null)
    {
        global $wpdb;
        $charset = $wpdb->get_col_charset( $wpdb->posts, 'post_content' );
        $feed_meta       = get_post_meta($postId, '_wpsr_template_config', true);

        $feed_meta = 'utf8mb3' === $charset ? json_decode($feed_meta, true) : maybe_unserialize($feed_meta);

        $settings        = Config::formatConfig($feed_meta);
        $pages           = GlobalHelper::getPagesList();
        $postTypes       = GlobalHelper::getPostTypes();
        $languages       = (new CountryNames())->get();

        $templateDetails = get_post($postId);
        
        wp_send_json_success([
            'message'          => __('Success', 'wp-social-reviews'),
            'settings'         => $settings,
            'template_details' => $templateDetails,
            'pages'            => $pages,
            'languages'        => $languages,
            'post_types'       => $postTypes,
        ], 200);
    }

    /**
     *
     * Update Credential
     *
     * @return json response
     * @since 1.0.0
     */
    public function updateSettings($postId = null, $args = [])
    {
        global $wpdb;
        $charset = $wpdb->get_col_charset( $wpdb->posts, 'post_content' );
        if('utf8' === $charset || 'utf8mb3' === $charset) {
            $args[$args['template']]['chat_body']['greeting_msg'] = wp_encode_emoji($args[$args['template']]['chat_body']['greeting_msg']);
        }

        $args['settings']['start_chat_time'] = chatHelper::formatedLocalTimeToUTCTime($args['settings']['start_time']);
        $args['settings']['end_chat_time'] = chatHelper::formatedLocalTimeToUTCTime($args['settings']['end_time']);

        $settings = array(
            'chat_settings' => $args
        );

        if(isset($args['menu_order'])) {
            $menuOrder = $args['menu_order'];
            unset($args['menu_order']);
            $db = App::getInstance('db');
            $db->table('posts')->where('ID', $postId)
                ->update([
                    'menu_order' => absint($menuOrder)
                ]);
        }

        $settings = 'utf8mb3' === $charset ? json_encode($settings, JSON_UNESCAPED_UNICODE) : serialize($settings);
        update_post_meta($postId, '_wpsr_template_config', $settings);

        if(defined('LSCWP_V')) {
            do_action( 'litespeed_purge_post', $postId );
        }

        wp_send_json_success([
            'message'   => __('Successfully Updated', 'wp-social-reviews'),
        ], 200);
    }
}