<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://iqonic.design/
 * @since      1.7.2
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/elementor
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/elementor
 * @author     Iqonic Design <hello@iqonic.design>
 */
class Marvy_Animation_Addons_Elementor
{

    /**
     * The ID of this plugin.
     *
     * @since    1.7.2
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.7.2
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.7.2
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.7.2
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Marvy_Animation_Addons_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Marvy_Animation_Addons_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('marvy-custom', plugin_dir_url(__FILE__) . 'assets/css/marvy-custom.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @param string $id
     * @since    1.0.
     */
    public function enqueue_scripts($id = 0)
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Marvy_Animation_Addons_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Marvy_Animation_Addons_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if (marvy_is_preview_mode()) {
            if (empty($id)) {
                $id = get_the_ID();
            }
            $elements = marvy_filter_widgets($id);
            if (!empty($elements)) {
                $config = (function_exists('marvy_get_config')) ? marvy_get_config() : '';
                foreach ($elements as $item) {
                    if (isset($config[$item]['dependency']['js'])) {
                        foreach ($config[$item]['dependency']['js'] as $js) {
                            wp_enqueue_script($js['name'], plugin_dir_url(__FILE__) . $js['src'], array('jquery'), $this->version, false);
                        }
                    }
                }
            }
        } else {
            $elements = (function_exists('marvy_get_config')) ? marvy_get_config() : '';
            if ($elements != '') {
                foreach ($elements as $item) {
                    foreach ($item['dependency']['js'] as $js) {
                        wp_enqueue_script($js['name'], plugin_dir_url(__FILE__) . $js['src'], array('jquery'), $this->version, false);
                    }
                }
            }
        }
        if ($elements != '') {
			$default_post_id = get_option('elementor_active_kit');
			$color =  get_post_meta($default_post_id, '_elementor_page_settings', true);
            if(!defined('REST_REQUEST')){
            ?>
                <script>
                var marvyScript = <?php echo json_encode(array('pluginsUrl' => plugin_dir_url(__FILE__), 'color' => $color)); ?>
                </script>
            <?php
            }
		}
    }
}
