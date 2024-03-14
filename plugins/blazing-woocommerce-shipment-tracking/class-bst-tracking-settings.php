<?php

/**
 * @package     blazing-shipment-tracking
 * @category    Admin
 * @since       1.0
 *
 * Handles BST-Tracking-Admin endpoint requests
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Required functions
 */
if (!class_exists('BS_Shipment_Tracking_Dependencies'))
    require_once 'class-bst-tracking-dependencies.php';

class BS_Shipment_Tracking_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    private $plugins;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->plugins[] = array(
            'value' => 'bs_ship_track',
            'label' => 'BS_Shipment_Tracking',
            'path' => 'blazing-woocommerce-shipment-tracking/bst-tracking.php'
        );
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        // add_action('admin_print_styles', array(&$this, 'admin_styles'));
        // add_action('admin_print_scripts', array(&$this, 'library_scripts'));
    }


    // public function admin_styles()
    // {
    //     wp_enqueue_style('bst_styles_chosen', plugins_url(basename(dirname(__FILE__))) . '/assets/plugin/chosen/chosen.min.css');
    //     wp_enqueue_style('bst_styles', plugins_url(basename(dirname(__FILE__))) . '/assets/css/admin.css');
    // }

    // public function library_scripts()
    // {
    //     wp_enqueue_script('bst_styles_chosen_jquery', plugins_url(basename(dirname(__FILE__))) . '/assets/plugin/chosen/chosen.jquery.min.js');
    //     wp_enqueue_script('bst_styles_chosen_proto', plugins_url(basename(dirname(__FILE__))) . '/assets/plugin/chosen/chosen.proto.min.js');
    //     wp_enqueue_script('bst_script_setting', plugins_url(basename(dirname(__FILE__))) . '/assets/js/setting.js');
    // }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'BS_Shipment_Tracking Settings Admin',
            'BLZ-Ship-Tracking',
            'manage_options',
            'bs_ship_track-setting-admin',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('bst_option_name');
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Settings of BLAZING Shipment Tracking</h2>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('bst_option_group');
                do_settings_sections('bs_ship_track-setting-admin');
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'bst_option_group', // Option group
            'bst_option_name', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'bst_setting_section_id', // ID
            '', // Title
            array($this, 'print_section_info'), // Callback
            'bs_ship_track-setting-admin' // Page
        );

        add_settings_field(
            'couriers',
            'Couriers',
            array($this, 'couriers_callback'),
            'bs_ship_track-setting-admin',
            'bst_setting_section_id'
        );

        add_settings_field(
            'use_track_button',
            'Display Track Button at Order History Page',
            array($this, 'track_button_callback'),
            'bs_ship_track-setting-admin',
            'bst_setting_section_id'
        );

        add_settings_field(
            'track_message',
            'Content',
            array($this, 'track_message_callback'),
            'bs_ship_track-setting-admin',
            'bst_setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['track_couriers'])) {
            $new_input['couriers'] = sanitize_textarea_field($input['track_couriers']);
            //$new_input['couriers'] = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $input['track_couriers'] ) ) );
        }

        if (isset($input['track_message_1'])) {
            $postfix = '';
            if (substr($input['track_message_1'], -1) == ' ') {
                $postfix = ' ';
            }
            $new_input['track_message_1'] = sanitize_text_field($input['track_message_1']) . $postfix;
        }

        if (isset($input['track_message_2'])) {
            $postfix = '';
            if (substr($input['track_message_2'], -1) == ' ') {
                $postfix = ' ';
            }
            $new_input['track_message_2'] = sanitize_text_field($input['track_message_2']) . $postfix;
        }

        if (isset($input['use_track_button'])) {
            $new_input['use_track_button'] = true;
        }

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Enter your settings below:';
    }

    public function couriers_callback()
    {
        // $couriers_text = '[&#10;  {&#10;    "slug": "first",&#10;    "name": "first",&#10;    "url": "",&#10;  },&#10;  {&#10;    "slug": "second",&#10;    "name": "Second",&#10;    "url": "",&#10;  }&#10;]';
        // if ( isset($this->options['couriers']) ){
        //     $couriers_text = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $this->options['couriers'] ) ) );
        // }
        $couriers_text = isset($this->options['couriers']) ?  esc_textarea($this->options['couriers']) :
         '[&#10;  {&#10;    "slug": "canada-post", &#10;    "name": "Canada Post",&#10;    "url": "https://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber={tracking_number}&LOCALE=en"&#10;  },&#10;  {&#10;    "slug": "fedex",&#10;    "name": "FedEx",&#10;    "url": "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber={tracking_number}"&#10;  },&#10;  {&#10;    "slug": "purolator",&#10;    "name": "Purolator",&#10;    "url": "https://www.purolator.com/purolator/ship-track/tracking-summary.page?pin={tracking_number}"&#10;  }&#10;]';
        echo "<textarea id='bs_ship_track_couriers_textarea' name='bst_option_name[track_couriers]' rows='20' cols='80' wrap='soft' style='width:100%' type='textarea'>{$couriers_text}</textarea>";
    }

    public function track_message_callback()
    {
        printf(
            '<input type="text" id="track_message_1" name="bst_option_name[track_message_1]" value="%s" style="width:100%%">',
            isset($this->options['track_message_1']) ? $this->options['track_message_1'] : 'Your order was shipped via '
        );
        printf('<br/>');
        printf(
            '<input type="text" id="track_message_2" name="bst_option_name[track_message_2]" value="%s" style="width:100%%">',
            isset($this->options['track_message_2']) ? $this->options['track_message_2'] : 'Tracking number is '
        );
        printf('<br/>');
        printf('<br/>');
        printf('<b>Demo:</b>');
        printf(
            '<div id="track_message_demo_1" style="width:100%%"></div>'
        );
    }

    public function track_button_callback()
    {
        printf(
            '<label><input type="checkbox" id="use_track_button" name="bst_option_name[use_track_button]" %s>Use Track Button</label>',
            (isset($this->options['use_track_button']) && $this->options['use_track_button'] === true) ? 'checked="checked"' : ''
        );
    }
}


if (is_admin())
    $bst_settings = new BS_Shipment_Tracking_Settings();
