<?php
/*
Plugin Name: HostFact bestelformulier integratie
Plugin URI: https://www.hostfact.nl/
Description: Eenvoudige manier om het bestelformulier van HostFact in de Wordpress website te integreren. Zie <a href="https://www.hostfact.nl/help/artikel/87/bestelformulier-integreren-in-een-wordpress-website/">https://www.hostfact.nl/help/artikel/87/bestelformulier-integreren-in-een-wordpress-website/</a> voor meer informatie.
Version: 1.1
Author: HostFact
Author URI: https://www.hostfact.nl
License: GPLv2 or later
*/
// Load scripts needed
add_action('wp_enqueue_scripts', array('HostFactBestelformulier', 'loadScripts'));

// Add shortcode
add_shortcode('bestelformulier', array('HostFactBestelformulier', 'shortcode'));


class HostFactBestelformulier
{
    /**
     * HostFactBestelformulier::loadScripts()
     *
     * @return void
     */
    public static function loadScripts()
    {
        // Load Javascript file
        wp_register_script('hf-orderform', plugins_url('hf-orderform.js', __FILE__));
        wp_enqueue_script('hf-orderform');
    }

    /**
     * HostFactBestelformulier::shortcode()
     *
     * @param mixed $attributes
     * @return HTML
     */
    public static function shortcode($attributes)
    {
        if (isset($attributes['url']) && $attributes['url']) {
            // Add extra GET-parameters? Currenly supported: domain, hosting, ssl and/or product
            if (isset($_GET['domain'])) {
                $attributes['url'] .= ((strpos($attributes['url'], '?') !== false) ? '&' : '?') . 'domain=' . htmlspecialchars($_GET['domain']);
            }
            if (isset($_GET['hosting'])) {
                $attributes['url'] .= ((strpos($attributes['url'], '?') !== false) ? '&' : '?') . 'hosting=' . htmlspecialchars($_GET['hosting']);
            }
            if (isset($_GET['ssl'])) {
                $attributes['url'] .= ((strpos($attributes['url'], '?') !== false) ? '&' : '?') . 'ssl=' . htmlspecialchars($_GET['ssl']);
            }
            if (isset($_GET['vps'])) {
                $attributes['url'] .= ((strpos($attributes['url'], '?') !== false) ? '&' : '?') . 'vps=' . htmlspecialchars($_GET['vps']);
            }
            if (isset($_GET['product'])) {
                $attributes['url'] .= ((strpos($attributes['url'], '?') !== false) ? '&' : '?') . 'product=' . htmlspecialchars($_GET['product']);
            }

            return '<iframe src="' . $attributes['url'] . '" scrolling="no" class="hf-orderform" style="width:100%;border:0;overflow-y:hidden;"></iframe>';
        }

        return '';
    }
}