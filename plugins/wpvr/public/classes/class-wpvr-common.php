<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing Helper for WPVR Public
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */
Class WPVR_Helper {

    /**
     * Generates an array of social media share links for the given page URL.
     *
     * @param string $page_url The URL of the page to be shared on social media.
     *
     * @return array Associative array containing social media platforms with their respective details.
     *               Each platform has an 'icon' (image URL), 'url' (share link URL), and 'class' (CSS class).
     *
     * @filter wpvr_social_media_link Allows filtering of the social media array before returning.
     */
    public static function social_media_share_links($page_url)
    {
        $social_media = array(
            'facebook' => array(
                'icon' => WPVR_PLUGIN_PUBLIC_DIR_URL.'image/facebook-icon.png',
                'url' => 'https://www.facebook.com/sharer/sharer.php?u='.$page_url,
                'class' => 'wpvr-facebook-icon',
            ),
            'linkedin' => array(
                'icon' => WPVR_PLUGIN_PUBLIC_DIR_URL.'image/linkedin-icon.png',
                'url' => 'https://www.linkedin.com/shareArticle?url='.$page_url,
                'class' => 'wpvr-linkedin-icon',
            ),
            'twitter' => array(
//                'icon' => WPVR_PLUGIN_PUBLIC_DIR_URL.'image/twitter-icon.png',
                'icon' => WPVR_PLUGIN_PUBLIC_DIR_URL.'image/x.png',
                'url' => 'https://twitter.com/intent/tweet?url='.$page_url,
                'class' => 'wpvr-twitter-icon',
            ),
            'email' => array(
                'icon' => WPVR_PLUGIN_PUBLIC_DIR_URL.'image/email.png',
                'url' => 'mailto:?body='.$page_url,
                'class' => 'wpvr-email-icon',
            ),
            'reddit' => array(
                'icon' => WPVR_PLUGIN_PUBLIC_DIR_URL.'image/reddit-icon.png',
                'url' => 'https://www.reddit.com/submit?url='.$page_url,
                'class' => 'wpvr-reddit-icon',
            ),
        );

        /**
         * Allows filtering of the social media array before returning.
         *
         * @param array $social_media Associative array containing social media platforms with their respective details.
         *                            Each platform has an 'icon' (image URL), 'url' (share link URL), and 'class' (CSS class).
         * @return array Modified social media array.
         */

        return apply_filters('wpvr_social_media_link',$social_media);
    }

    /**
     * Displays social media share links for the given page URL.
     *
     * @param string $page_url The URL of the page to be shared on social media.
     *
     * @return void Outputs HTML for social media share links.
     */
    public static function social_media_share_links_display($page_url)
    {
        $social_media = self::social_media_share_links($page_url);
        foreach ($social_media as $platform => $data) {
            /**
             * Outputs HTML for a social media share link.
             *
             * @param string $platform The social media platform (e.g., 'facebook', 'linkedin').
             * @param string $icon     The URL of the social media icon image.
             * @param string $url      The share link URL for the platform.
             * @param string $class    The CSS class for styling the social media icon.
             */
            echo self::generateSocialMediaLink($platform, $data['icon'], $data['url'],$data['class']);
        }
    }

    /**
     * Generates HTML for a social media share link.
     *
     * @param string $platform   The social media platform (e.g., 'facebook', 'linkedin').
     * @param string $iconPath   The URL of the social media icon image.
     * @param string $profileUrl The share link URL for the platform.
     * @param string $class      The CSS class for styling the social media icon.
     *
     * @return string HTML code for the social media share link.
     */
    public static function generateSocialMediaLink($platform, $iconPath, $profileUrl,$class) {
        return sprintf(
            '<a href="%s" class="%s" target="_blank"><img src="%s" alt="%s"></a>',
            $profileUrl,
            $class,
            $iconPath,
            ucfirst($platform)
        );
    }

    /**
     * Checks if social sharing is enabled for a given tour data.
     *
     * @param array $tour_data The data associated with the tour.
     *
     * @return string Returns 'on' if social sharing is enabled, 'off' otherwise.
     */
    public static function is_enable_social_share($tour_data)
    {
        /**
         * Check if social sharing is enabled for a given tour.
         *
         * @param array $tour_data The data associated with the tour.
         *
         * @return string Returns 'on' if social sharing is enabled, 'off' otherwise.
         */
        return isset($tour_data['wpvr_social_share'])? $tour_data['wpvr_social_share'] : 'off';
    }
    /**
     * Generates HTML for social media share links suitable for embedding in a page.
     *
     * @param string $page_url The URL of the page to be shared on social media.
     *
     * @return string HTML code for social media share links.
     */
    public static function social_media_share_links_display_in_embed($page_url)
    {
        $html = '';
        $social_media = self::social_media_share_links($page_url);
        foreach ($social_media as $platform => $data) {
            /**
             * Generates HTML for a social media share link suitable for embedding in a page.
             *
             * @param string $platform The social media platform (e.g., 'facebook', 'linkedin').
             * @param string $icon     The URL of the social media icon image.
             * @param string $url      The share link URL for the platform.
             * @param string $class    The CSS class for styling the social media icon.
             *
             * @return string HTML code for the social media share link.
             */
            $html .= self::generateSocialMediaLink($platform, $data['icon'], $data['url'],$data['class']);
        }
        return $html;
    }
}