<?php

/**
 * The file that defines the Dotdigital_WordPress_Interacts_With_Messages_Trait class
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Traits;

use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
trait Dotdigital_WordPress_Interacts_With_Redirection_Trait
{
    /**
     * Get redirection type.
     *
     * @return string
     */
    public function get_redirection() : string
    {
        $redirection_settings = Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_REDIRECTS_PATH);
        if (\is_array($redirection_settings) && \array_key_exists('page', $redirection_settings)) {
            return get_permalink($redirection_settings['page']);
        }
        if (\is_array($redirection_settings) && \array_key_exists('url', $redirection_settings)) {
            return $redirection_settings['url'];
        }
        return '';
    }
    /**
     * Get origin URL.
     *
     * The origin URL is always set in a hidden form input. It is used in regular non-AJAX form submissions to provide a URL for wp_redirect(), in the event that redirection is not set.
     *
     * @return string
     */
    public function get_origin_url() : string
    {
        global $wp;
        return add_query_arg($wp->query_vars, home_url($wp->request));
    }
}
