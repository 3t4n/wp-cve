<?php
/**
 * @package Robots.txt rewriter
 * @version 1.6
 */
/*
Plugin Name: Robots.txt rewrite
Plugin URI: http://wordpress.org/plugins/robotstxt-rewrite/
Description: Manage your robots.txt form admin side. Plugin provide to help search engines to indexing site correctly. A simple plugin to manage your robots.txt. Plugin donn't create the file or edit it. This plugin edit WordPress output of robots.txt content. And get you a easy and usable interface to manage it.
Author: Eugen Bobrowski
Version: 1.6.1
Author URI: http://bobrowski.ru/
Text Domain: robotstxt-rewrite
*/


class RobotsTxtRewrite
{

    protected static $instance;

    private function __construct()
    {
        add_filter('robots_txt', array($this, 'robots_txt_edit'), 10, 2);
    }

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;

    }

    public function robots_txt_edit($output, $public)
    {
        //do_robots();
        if (('0' != $public) && ($options = get_option('robots_options'))) {
            $site_url = parse_url(site_url());
            $path = (!empty($site_url['path'])) ? $site_url['path'] : '';

            $user_agents = array('*' => "User-agent: *\n");
            $allows = '';

            foreach ($options['allows'] as $allow) {
                if ($allow['allowed']) {
                    $item = "Allow: {$path}{$allow['path']}\n";
                } else {
                    $item = "Disallow: {$path}{$allow['path']}\n";
                }
                if (empty($allow['bots'])) {
                    $user_agents['*'] .= $item;
                } else {
                    foreach ($allow['bots'] as $bot ) {
                        if (!isset($user_agents[$bot])) $user_agents[$bot] = "User-agent: {$bot}\n";
                        $user_agents[$bot] .= $item;
                    }
                }

                if ($allow['allowed']) {
                    $allows .= "Allow: {$path}{$allow['path']}\n";
                } else {
                    $allows .= "Disallow: {$path}{$allow['path']}\n";
                }
            }
            $output = '';

            $site_map = (!empty($options['site_map'])) ? "\nSitemap: " . $options['site_map'] : '';
            $crawl_delay = (!empty($options['crawl_delay'])) ? "\nCrawl-Delay: " . $options['crawl_delay'] : '';
            $host = "\nHost: " . get_site_url() . "\n";
            $custom = (!empty($options['custom_content'])) ? "\n" . $options['custom_content'] . "\n" : '';

            $output .= implode("\n\n" , $user_agents) . $site_map . $crawl_delay . $host . $custom . apply_filters('robots_txt_rewrite_footer', "\n\n\n\n# This robots.txt file was created by Robots.txt Rewrite plugin: https://wordpress.org/plugins/robotstxt-rewrite/\n");


        }

        return $output;
    }

}



RobotsTxtRewrite::get_instance();

if (is_admin()) include_once ('robotstxt-rewrite-admin.php');