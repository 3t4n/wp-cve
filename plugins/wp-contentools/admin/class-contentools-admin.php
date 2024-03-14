<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Contentools
 * @subpackage Contentools/admin
 *
 * @link  https://growthhackers.com/workflow
 * @since 1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contentools
 * @subpackage Contentools/admin
 * @author     Contentools <wordpress-plugin@contentools.com>
 */
class Contentools_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string    $plugin_name       The name of this plugin.
     * @param string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Contentools_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Contentools_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/contentools-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Contentools_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Contentools_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name . "-md5", plugin_dir_url(__FILE__) . 'js/md5.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/contentools-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since 1.0.0
     */
    public function add_plugin_admin_menu()
    {

        add_options_page(__('Contentools', $this->plugin_name), __('Contentools', $this->plugin_name), 'manage_options', $this->plugin_name, array($this, 'options_display_page'));

    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since 1.0.0
     */
    public function add_action_links($links)
    {

        $settings_link = array(
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge($links, $settings_link);

    }

    /**
     * Render the plugin options page.
     *
     * @since 1.0.0
     */
    public function options_display_page()
    {

        include_once 'partials/contentools-admin-display.php';

    }

    /**
     *  Save the plugin options.
     *
     * @since 1.0.0
     */
    public function options_update()
    {

        register_setting($this->plugin_name, $this->plugin_name, array($this, 'options_validate'));

    }

    /**
     * Validate all the plugin options fields.
     *
     * @since 1.0.0
     */
    public function options_validate($input)
    {

        $valid = array();

        $valid['token'] = esc_attr($input['token']);

        return $valid;

    }

    /**
     * Update the .htaccess to add the appropriate rewrite rules.
     *
     * @since 1.0.0
     */
    public function update_rewrite_rules($rules)
    {

        if (defined('ABSPATH')) {

            $file = file_get_contents(ABSPATH . '.htaccess');

            if (!stripos($file, '# Begin Contentools Settings')) {

                $update_rewrite_rules = "\n# Begin Contentools Settings\nSetEnvIf Authorization \"(.*)\" HTTP_AUTHORIZATION=$1\n# End Contentools Settings";

                $result = file_put_contents(ABSPATH . '.htaccess', $update_rewrite_rules, FILE_APPEND);

            }

        }

        return $rules;

    }

    /**
     * Force the .htaccess changes to be written to the file.
     *
     * @since 1.0.0
     */
    public function flush_rewrite_rules()
    {

        global $wp_rewrite;

        $wp_rewrite->flush_rules();

    }

}
