<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.mydirtyhobby.com/registrationplugin
 * @since      1.0.0
 *
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/admin
 * @author     Mg <info@mindgeek.com>
 */
class Mdh_Promote_Admin
{

    private $option_name = 'mdh-promo';

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $profile_link;

    private $profile_pic_link;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version     The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

        $this->load_settings();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mdh_Promote_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mdh_Promote_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/mdh-promote-admin.css', [], $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mdh_Promote_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mdh_Promote_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/mdh-promote-admin.js', ['jquery'], $this->version, false);
        if (!wp_script_is('jquery', 'enqueued')) {

            //Enqueue
            wp_enqueue_script('jquery');

        }

    }

    public function add_options_page()
    {
        add_menu_page(
            "MDH Promote",
            "MDH Promote",
            'manage_options',
            $this->plugin_name,
            [$this, 'display_options_page']
        );
    }

    public function display_options_page()
    {

        include_once 'partials/mdh-promote-admin-display.php';
    }

    public function register_setting()
    {

        add_settings_section(
            $this->option_name . '_general',
            "Settings",
            [$this, 'setting_section_cb'],
            $this->plugin_name
        );

        $settings =
            [
                'code_type'             => [
                    'suffix'      => '_promo_code_type',
                    'description' => 'Promo Code Type'
                ],
                'code'                  => [
                    'suffix'      => '_code',
                    'description' => 'Promo Code'
                ],
                'profile_link'          => [
                    'suffix'      => '_profile_link',
                    'description' => 'MDH Profile Link'
                ],
                'nav_display'           => [
                    'suffix'      => '_nav_display',
                    'description' => 'Display in nav menu'
                ],
                'profile_pic_link'      => [
                    'suffix'      => '_profile_pic_link',
                    'description' => 'Profile picture link'
                ],
                'register_popup_lang'   => [
                    'suffix'      => '_register_popup_lang',
                    'description' => 'Register Popup Language'
                ],
                'nav_register_btn_txt'  => [
                    'suffix'      => '_nav_register_btn_txt',
                    'description' => 'Navigation Button Text'
                ],
                'sc_register_btn_class' => [
                    'suffix'      => '_sc_register_btn_class',
                    'description' => 'Shortcode button class'
                ],
            ];

        foreach ($settings as $setting => $details) {

            $setting_name = $this->option_name . $details['suffix'];
            $setting_cb   = 'setting_set' . $details['suffix'] . '_cb';

            add_settings_field(
                $setting_name,
                $details['description'],
                [$this, $setting_cb],
                $this->plugin_name,
                $this->option_name . '_general'
            );
            register_setting($this->plugin_name, $this->option_name . $details['suffix'], 'string');
        }
    }

    function setting_section_cb()
    {

    }

    function setting_set_profile_link_cb()
    {
        echo '<input type="text" name="' . $this->option_name . '_profile_link" value="' . get_option($this->option_name . '_profile_link') . '">';
    }

    function setting_set_nav_display_cb()
    {
        $checked = (get_option($this->option_name . '_nav_display') === "on") ? 'checked' : '';

        echo '<input type="checkbox" name="' . $this->option_name . '_nav_display"' . $checked . '>';
    }

    function setting_set_sc_register_btn_class_cb()
    {
        echo '<input type="text" name="' . $this->option_name . '_sc_register_btn_class" value="' . get_option($this->option_name . '_sc_register_btn_class') . '">';
    }

    function setting_set_code_cb()
    {
        echo '<input type="text" name="' . $this->option_name . '_code" value="' . get_option($this->option_name . '_code') . '">';
    }

    function setting_set_promo_code_type_cb()
    {
        $code_types = ['naff', 'ats'];

        foreach ($code_types as $code) {
            echo '<label>' . strtoupper($code) . '</label><input type="radio" name="mdh_code_type" class="mdh-promo-code-type-radio" value="' . $code . '"' . ((get_option($this->option_name . '_promo_code_type') === $code) ? 'checked' : '') . '>';
        }
        echo '<input type="hidden" name="' . $this->option_name . '_promo_code_type" value="' . get_option($this->option_name . '_promo_code_type') . '">';
        echo "<br>";
    }

    function setting_set_profile_pic_link_cb()
    {
        echo '<input type="text" name="' . $this->option_name . '_profile_pic_link" value="' . get_option($this->option_name . '_profile_pic_link') . '">';
    }

    function setting_set_nav_register_btn_txt_cb()
    {
        echo '<input type="text" name="' . $this->option_name . '_nav_register_btn_txt" value="' . get_option($this->option_name . '_nav_register_btn_txt') . '">';
    }

    function setting_set_register_popup_lang_cb()
    {
        $languages = ['en', 'de'];
        foreach ($languages as $lang) {
            echo '<label>' . strtoupper($lang) . '</label><input type="radio" name="register_popup_lang" class="register_popup_lang-radio" value="' . $lang . '" ' . ((get_option($this->option_name . '_register_popup_lang') === $lang) ? 'checked' : '') . ' >';
        }
        echo '<input type="hidden" name="' . $this->option_name . '_register_popup_lang" value="' . get_option($this->option_name . '_register_popup_lang') . '">';
        echo "<br>";
    }

    function load_settings()
    {
        $this->profile_pic_link = get_option('mdh-promo_profile_pic_link');
        $this->profile_link     = get_option('mdh-promo_profile_link');
    }
}
