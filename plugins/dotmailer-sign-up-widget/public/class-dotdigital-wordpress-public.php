<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Pub;

use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
use Dotdigital_WordPress\Includes\Widget\Dotdigital_WordPress_Sign_Up_Widget;
class Dotdigital_WordPress_Public
{
    /**
     * The ID of this plugin.
     *
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * Initialize the class and set its properties.
     *
     * @param      string $plugin_name       The name of the plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dotdigital-wordpress-public.css', array(), $this->version, 'all');
    }
    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function enqueue_scripts()
    {
        if (is_admin()) {
            return;
        }
        wp_enqueue_script('jquery');
        wp_enqueue_script('dotdigital_for_wordpress_public_js', plugin_dir_url(__FILE__) . 'js/dotdigital-for-wordpress-public.js', array('jquery'), $this->version, \false);
        wp_localize_script('dotdigital_for_wordpress_public_js', 'dotdigital_form_data', array('ajax_url' => get_rest_url(), 'nonce' => wp_create_nonce('wp_rest'), 'generic_failure_message' => Dotdigital_WordPress_Sign_Up_Widget::get_failure_message()));
    }
    /**
     * Register the public shortcodes.
     */
    public function add_plugin_public_shortcodes()
    {
        add_shortcode(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-lists', array($this, 'render_public_lists'));
        add_shortcode(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-datafields', array($this, 'render_public_datafields'));
        add_shortcode(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-signup-widget', array($this, 'render_signup_widget'));
        add_shortcode('dotmailer-signup', array($this, 'render_signup_widget'));
        // deprecated.
        add_shortcode('dotdigital-signup', array($this, 'render_signup_widget'));
    }
    /**
     * Register the public actions .
     */
    public function add_plugin_public_actions()
    {
        add_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-lists', array($this, 'render_public_lists'));
        add_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-datafields', array($this, 'render_public_datafields'));
    }
    /**
     * Render lists.
     */
    public function render_public_lists()
    {
        $identifier = apply_filters(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-lists-identifier', 'lists');
        $lists = get_option(Dotdigital_WordPress_Config::SETTING_LISTS_PATH, array());
        $has_visible_lists = \count(\array_filter($lists, function ($list) {
            return $list['isVisible'];
        })) > 0;
        require DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'public/view/partial/dotdigital-wordpress-public-lists.php';
    }
    /**
     * Render datafields.
     */
    public function render_public_datafields()
    {
        $identifier = apply_filters(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '-public-lists-identifier', 'datafields');
        $datafields = get_option(Dotdigital_WordPress_Config::SETTING_DATAFIELDS_PATH, array());
        require DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'public/view/partial/dotdigital-wordpress-public-datafields.php';
    }
    /**
     * Register render signup widget.
     *
     * @param array $atts
     * @param string $tag
     */
    public function render_signup_widget($atts = array(), $tag = '')
    {
        $attributes = shortcode_atts(array('showtitle' => $atts['showtitle'] ?? 1, 'showdesc' => $atts['showdesc'] ?? 1, 'redirecturl' => $atts['redirection'] ?? null), $atts, $tag);
        \ob_start();
        the_widget(\Dotdigital_WordPress\Includes\Widget\Dotdigital_WordPress_Sign_Up_Widget::class, array(), array('showtitle' => $attributes['showtitle'], 'showdesc' => $attributes['showdesc'], 'redirection' => $attributes['redirecturl']));
        $widget = \ob_get_contents();
        \ob_end_clean();
        return $widget;
    }
}
