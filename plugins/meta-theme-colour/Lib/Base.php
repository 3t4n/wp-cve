<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 2016/04/18
 * Time: 7:07 PM
 */

namespace MetaThemeColour;

/**
 * Class Base
 *
 * @package MetaThemeColour
 *
 */
class Base
{

    /**
     * Holds the values to be used in the fields callbacks
     *
     * @var
     */
    private $options;

    /**
     *
     * @var array
     */
    private $settings
        = [
            'page' => 'meta_theme_colour'
        ];

    /**
     * Start up
     */
    function __construct ()
    {
        add_action('init', [$this, 'init_actions'], 1);
    }

    /**
     * Initialise the actions
     */
    public function init_actions ()
    {

        add_action('wp_head', [$this, 'meta_theme_colour_set_html']);

        if (is_admin()) {
            add_action('admin_menu', [$this, 'register_settings_page']);
            add_action(
                'admin_enqueue_scripts', [$this, 'enqueue_static_files']
            );
            add_action('admin_init', [$this, 'register_settings']);
        }

    }

    /**
     * Display the HTML on the front-end
     */
    public function meta_theme_colour_set_html ()
    {
        $this->options = get_option('meta-theme-colour-group-colour');
        $colour = $this->options['meta_theme_colour'];

        printf('<meta name="theme-color" content="%s">', $colour);
        printf('<meta name="msapplication-navbutton-color" content="%s">', $colour);
        print('<meta name="apple-mobile-web-app-capable" content="yes">');
        print('<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">');
    }

    /**
     * Register the settings page
     */
    public function register_settings_page ()
    {
        add_options_page(
            __('Meta Theme Colour', 'meta-theme-colour'),
            __('Meta Theme Colour', 'meta-theme-colour'),
            'manage_options',
            $this->settings['page'],
            array (
                $this,
                'settings_page'
            )
        );
    }

    /**
     * Set-up the settings page fields
     */
    public function settings_page ()
    {
        $this->options = get_option('meta-theme-colour-group-colour');

        add_settings_field(
            'colour', // ID
            'Select the colour', // Title
            array ($this, 'colour_callback'), // Callback
            $this->settings['page'], // Page
            'main' // Section
        );

        /**
         * Load the view page
         */
        require_once plugin_dir_path(__FILE__) . '../views/settings-page.php';
    }

    /**
     * Register the setting
     */
    public function register_settings ()
    {
        register_setting(
            'meta-theme-colour-group',
            'meta-theme-colour-group-colour'
        );
    }

    /**
     * Get the colour field
     */
    public function colour_callback ()
    {
        printf(
            '<input type="text" id="meta_theme_colour" name="meta-theme-colour-group-colour[meta_theme_colour]" value="%s" />',
            isset($this->options['meta_theme_colour'])
                ? esc_attr($this->options['meta_theme_colour'])
                : ''
        );
    }

    /**
     * Load up the static files for the plugin
     */
    public function enqueue_static_files ()
    {

        if (is_admin()) {

            /**
             * Add the color picker css file
             */
            wp_enqueue_style('wp-color-picker');

            /**
             * Add our JavaScript file
             */
            wp_enqueue_script(
                'meta_theme_colour_script',
                plugins_url('../static/js/meta_theme_colour.js', __FILE__),
                array ('wp-color-picker'), false, true
            );

            /**
             * Add our CSS file
             */
            wp_enqueue_style(
                'meta_theme_colour_style',
                plugins_url('../static/css/meta_theme_colour.css', __FILE__)
            );
        }
    }
}