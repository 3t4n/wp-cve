<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme;

use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;
// TODO review this class and optimize it
/** @internal */
class RegisterWordpressThemeSecurityProvider extends ServiceProvider
{
    /**
     * Clean and optimize template
     *
     * @link http://cubiq.org/clean-up-and-optimize-wordpress-for-your-next-theme
     * @link https://desarrollowp.com/blog/tutoriales/eliminar-codigo-innecesario-del-wp_head/
     * @return void
     */
    public function cleanHead()
    {
        // WP Version
        \remove_action('wp_head', 'wp_generator');
        \add_filter('the_generator', '__return_false');
        // WPML Generator
        global $sitepress;
        if (!empty($sitepress)) {
            \remove_action('wp_head', [$sitepress, 'meta_generator_tag']);
        }
        // API
        \remove_action('wp_head', 'rest_output_link_wp_head', 10);
        \remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        \remove_action('wp_head', 'rest_output_link_header', 11, 0);
        // WLManifest
        \remove_action('wp_head', 'feed_links', 2);
        \remove_action('wp_head', 'feed_links_extra', 3);
        \remove_action('wp_head', 'rsd_link');
        \remove_action('wp_head', 'wlwmanifest_link');
        \remove_action('wp_head', 'start_post_rel_link');
        \remove_action('wp_head', 'index_rel_link');
        \remove_action('wp_head', 'wp_shortlink_wp_head');
        \remove_action('wp_head', 'adjacent_posts_rel_link');
        \remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        \remove_action('wp_head', 'parent_post_rel_link');
        // Remove emojis
        \remove_action('wp_head', 'print_emoji_detection_script', 7);
        \remove_action('wp_print_styles', 'print_emoji_styles');
        \remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        \remove_action('admin_print_scripts', 'print_emoji_detection_script', 7);
        \remove_action('admin_print_styles', 'print_emoji_styles');
        \remove_filter('the_content_feed', 'wp_staticize_emoji');
        \remove_filter('comment_text_rss', 'wp_staticize_emoji');
        // Remove security
        \add_action('wp_head', function () {
            \ob_start(function ($o) {
                return \preg_replace('/^\\n?<!--.*?[Y]oast.*?-->\\n?$/mi', '', $o);
            });
        }, ~\PHP_INT_MAX);
        //XML-RPC
        \add_filter('xmlrpc_enabled', function () {
            return \false;
        });
        /**
         * Delete html lines from plugin LiteSpeedCache "log"
         */
        \define('Modular\\ConnectorDependencies\\LSCACHE_ESI_SILENCE', \true);
        /**
         * Delete html lines from plugin YoastSEO "log"
         */
        \add_filter('wpseo_debug_markers', function () {
            return \false;
        });
        //
        \remove_action('presscore_body_top', 'the7_version_comment', 0);
    }
    /**
     * Remove Visual Composer meta generator
     */
    public function cleanVCMetaGenerator()
    {
        if (\class_exists('Modular\\ConnectorDependencies\\Vc_Manager')) {
            \remove_action('wp_head', [visual_composer(), 'addMetaData']);
        }
    }
    /**
     * Remove pingbacks
     *
     * @param $links
     */
    public function removePingback(&$links)
    {
        foreach ($links as $l => $link) {
            if (0 === \strpos($link, \get_option('home'))) {
                unset($links[$l]);
            }
        }
    }
    /**
     * Remove query strings
     *
     * @param $src
     * @return string
     */
    public function removeQueryStrings($src)
    {
        if (\strpos($src, 'ver=')) {
            $src = \remove_query_arg('ver', $src);
        }
        return $src;
    }
    /**
     * Clean JS of front-end
     *
     * @link https://codex.wordpress.org/Function_Reference/wp_deregister_script
     * @return void
     */
    public function cleanJS()
    {
        if (!\is_admin()) {
            //            wp_deregister_script('jquery');
            //            wp_register_script('jquery', null);
        }
    }
    /**
     * Clean CSS of front-end
     *
     * @link https://codex.wordpress.org/Function_Reference/wp_deregister_script
     * @return void
     */
    public function cleanCSS()
    {
        if (!\current_user_can('update_core')) {
            \wp_deregister_style('dashicons');
        }
    }
    /**
     * Set add_actions wordpress
     *
     * @link https://developer.wordpress.org/reference/functions/add_action/
     * @return void
     */
    public function register() : void
    {
        if (\function_exists('add_action') && \function_exists('add_filter')) {
            \add_action('after_setup_theme', [$this, 'cleanHead'], \PHP_INT_MAX);
            \add_action('wp_enqueue_scripts', [$this, 'cleanJS'], \PHP_INT_MAX);
            \add_action('wp_enqueue_scripts', [$this, 'cleanCSS'], \PHP_INT_MAX);
            \add_filter('style_loader_src', [$this, 'removeQueryStrings'], \PHP_INT_MAX, 2);
            \add_filter('script_loader_src', [$this, 'removeQueryStrings'], \PHP_INT_MAX, 2);
            \add_action('pre_ping', [$this, 'removePingback']);
            \add_action('wp_head', [$this, 'cleanVCMetaGenerator'], 1);
            \add_action('wp_footer', function () {
                \ob_end_flush();
            }, 100);
        }
    }
}
