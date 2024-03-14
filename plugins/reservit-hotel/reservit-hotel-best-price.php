<?php
/*
 *      Reservit Hotel Best Price Class
 *      Version: 1.6
 *      By Reservit
 *
 *      Contact: http://www.reservit.com/hebergement
 *      Created: 2017
 *      Modified: 02/05/2019
 *
 *      Copyright (c) 2017, Reservit. All rights reserved.
 *
 *      Licensed under the GPLv2 license - https://www.gnu.org/licenses/gpl-2.0.html
 *
 */
include_once plugin_dir_path(__FILE__) . '/reservit-hotel-bestprice-widget.php';

class Reservit_Hotel_Bestprice {

    public function __construct() {

        function reservit_hotel_load_plugin_textdomain() {
            $domain = 'reservit-hotel';
            $plugin_dir = basename(dirname(__FILE__)) . '/languages';
            load_plugin_textdomain($domain, false, $plugin_dir);
        }

        add_action('widgets_init', function() {
            register_widget('Reservit_Hotel_Bestprice_Widget');
        });
        reservit_hotel_load_plugin_textdomain();

        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
        add_action('admin_init', array($this, 'register_settings'));


        // Register javascript
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_js_css'));
    }

    //Load javascript for Alpha Color Piker with dependency and style for options pages
    public function enqueue_admin_js_css() {
        // Css Color Picker
        wp_enqueue_style('wp-color-picker');

        //Load fontawsome css.min cdn
        wp_enqueue_style('rsvit_fontawsome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

        //Alpha channel for color picker
        wp_enqueue_script('wp-color-picker-alpha', plugins_url('wp-color-picker-alpha.min.js', __FILE__), array('wp-color-picker'), '1.2.2', true);
        //Style for option pages
        wp_enqueue_style('rsvit_option_css', plugins_url('reservit-hotel-option.css', __FILE__));
    }

    //Add menu pages
    public function add_admin_menu() {
        add_submenu_page('reservitHotel', esc_html__('Login', 'reservit-hotel'), esc_html__('Reservit ID', 'reservit-hotel'), 'manage_options', 'ReservitHotelBestprice_id', array($this, 'reservit_hotel_id_html'));
        add_submenu_page('reservitHotel', esc_html__('Content', 'reservit-hotel'), esc_html__('Content', 'reservit-hotel'), 'manage_options', 'RsvitHotelBestprice_content', array($this, 'rsvit_hotel_content_html'));
        add_submenu_page('reservitHotel', esc_html__('CSS', 'reservit-hotel'), esc_html__('CSS', 'reservit-hotel'), 'manage_options', 'RsvitHotelBestprice_style', array($this, 'rsvit_hotel_style_html'));
    }

    //page for reservit service identification
    public function reservit_hotel_id_html() {
        ?>
        <h1><?= get_admin_page_title(); ?></h1>
        <p><?= esc_html_e('Set your reservit codes', 'reservit-hotel'); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields('reservit_hotel_bestprice_id_settings') ?>
            <?php do_settings_sections('reservit_hotel_bestprice_id_settings') ?>

            <?php submit_button(); ?>
        </form>

        <?php
    }

    //Button's content page
    public function rsvit_hotel_content_html() {
        ?>
        <h1><?= get_admin_page_title(); ?></h1>
        <h2><?= esc_html_e('Widget content setting', 'reservit-hotel'); ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields('rsvit_hotel_bestprice_content_settings') ?>
            <?php do_settings_sections('rsvit_hotel_bestprice_content_settings') ?>

            <?php submit_button(); ?>
        </form>

        <?php
    }

    //Styling page
    public function rsvit_hotel_style_html() {
        ?>
        <h1><?= get_admin_page_title(); ?></h1>
        <h2><?= esc_html_e('Widget style setting', 'reservit-hotel'); ?></h2>
        <p><?= esc_html_e('You have the option to customize certain elements of the widget. We have deliberately limited the number of style parameters but this could change in the future ...', 'reservit-hotel') ?></p>
        <form method="post" action="options.php" id="styleForm">
        <?php settings_fields('rsvit_hotel_bestprice_style_settings') ?>
            <?php do_settings_sections('rsvit_hotel_bestprice_style_settings') ?>

            <?php submit_button(); ?>
        </form>

        <?php
    }

    /* Options settings */

    public function register_settings() {

        add_settings_section('rsvit_id_section', esc_html__('Reservit account parameters', 'reservit-hotel'), array($this, 'section_id_html'), 'reservit_hotel_bestprice_id_settings');
        add_settings_field('rsvit_chaine_id', esc_html__('Chain ID', 'reservit-hotel'), array($this, 'chaine_id_html'), 'reservit_hotel_bestprice_id_settings', 'rsvit_id_section');
        add_settings_field('rsvit_hotel_id', esc_html__('Hotel ID', 'reservit-hotel'), array($this, 'hotel_id_html'), 'reservit_hotel_bestprice_id_settings', 'rsvit_id_section');
        register_setting('reservit_hotel_bestprice_id_settings', 'rsvit_chaine_id', array($this, 'reservit_sanitize_integer_field'));
        register_setting('reservit_hotel_bestprice_id_settings', 'rsvit_hotel_id', array($this, 'reservit_sanitize_integer_field'));


        add_settings_section('rsvit_content_section', '', array($this, 'section_content_html'), 'rsvit_hotel_bestprice_content_settings');
        add_settings_field('rsvit_hotel_design_version', esc_html__('Design version', 'reservit-hotel'), array($this, 'hotel_design_version_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_max_adlut', esc_html__('Maximum adults number', 'reservit-hotel'), array($this, 'hotel_max_adlut_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_max_child', esc_html__('Maximum children number', 'reservit-hotel'), array($this, 'hotel_max_child_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_bestprice_display', esc_html__('Display the "partner best price" bloc', 'reservit-hotel'), array($this, 'hotel_bestprice_display_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_distributorpriceblock_display', esc_html__('Display the distributor price block', 'reservit-hotel'), array($this, 'hotel_distributorpriceblock_display_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_partner_id', esc_html__('Other booking partner ID', 'reservit-hotel'), array($this, 'hotel_partner_id_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_distributorpartner1_id', esc_html__('Distributor partner ID 1', 'reservit-hotel'), array($this, 'hotel_distributorpartner1_id_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_distributorpartner2_id', esc_html__('Distributor partner ID 2', 'reservit-hotel'), array($this, 'hotel_distributorpartner2_id_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_distributorpartner3_id', esc_html__('Distributor partner ID 3', 'reservit-hotel'), array($this, 'hotel_distributorpartner3_id_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        add_settings_field('rsvit_hotel_distributorprice_display', esc_html__("Display the distributor\'s price even if it is equal to the hotel\'s price", 'reservit-hotel'), array($this, 'hotel_distributorprice_display_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_content_section');
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_design_version', array($this, 'reservit_sanitize_integer_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_max_adlut', array($this, 'reservit_sanitize_integer_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_max_child', array($this, 'reservit_sanitize_integer_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_bestprice_display', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_distributorpriceblock_display', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_distributorprice_display', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_partner_id', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_distributorpartner1_id', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_distributorpartner2_id', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_hotel_distributorpartner3_id', array($this, 'reservit_sanitize_text_field'));


        add_settings_section('rsvit_btn_section', '', array($this, 'section_btn_html'), 'rsvit_hotel_bestprice_content_settings');
        add_settings_field('rsvit_btn_txt_fr', esc_html__('French', 'reservit-hotel'), array($this, 'hotel_btn_fr_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_btn_section');
        add_settings_field('rsvit_btn_txt_en', esc_html__('English', 'reservit-hotel'), array($this, 'hotel_btn_en_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_btn_section');
        add_settings_field('rsvit_btn_txt_pt', esc_html__('Portuguese', 'reservit-hotel'), array($this, 'hotel_btn_pt_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_btn_section');
        add_settings_field('rsvit_btn_txt_it', esc_html__('Italian', 'reservit-hotel'), array($this, 'hotel_btn_it_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_btn_section');
        add_settings_field('rsvit_btn_txt_de', esc_html__('German', 'reservit-hotel'), array($this, 'hotel_btn_de_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_btn_section');
        add_settings_field('rsvit_btn_txt_es', esc_html__('Spanish', 'reservit-hotel'), array($this, 'hotel_btn_es_html'), 'rsvit_hotel_bestprice_content_settings', 'rsvit_btn_section');
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_btn_txt_fr', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_btn_txt_en', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_btn_txt_pt', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_btn_txt_it', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_btn_txt_de', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_content_settings', 'rsvit_btn_txt_es', array($this, 'reservit_sanitize_text_field'));


        add_settings_section('rsvit_style_section', esc_html__('Button style', 'reservit-hotel'), array($this, 'section_style_html'), 'rsvit_hotel_bestprice_style_settings');
        add_settings_field('rsvit_btn_bgcolor', esc_html__('Button background color', 'reservit-hotel'), array($this, 'hotel_btn_bgcolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_color', esc_html__('Button text color', 'reservit-hotel'), array($this, 'hotel_btn_color_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_fontsize', esc_html__('Button font size', 'reservit-hotel'), array($this, 'hotel_btn_fontsize_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_fontunit', '', 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_fontweight', esc_html__('Button text font weight', 'reservit-hotel'), array($this, 'hotel_btn_fontweight_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_radius', esc_html__('Button border radius', 'reservit-hotel'), array($this, 'hotel_btn_radius_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_radiusunit', '', 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_bordercolor', esc_html__('Button border color', 'reservit-hotel'), array($this, 'hotel_btn_bordercolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_borderwidth', esc_html__('Button border width', 'reservit-hotel'), array($this, 'hotel_btn_borderwidth_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_borderunit', '', 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_hoverbgcolor', esc_html__('Button hover background color', 'reservit-hotel'), array($this, 'hotel_btn_hoverbgcolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_hovercolor', esc_html__('Button hover text color', 'reservit-hotel'), array($this, 'hotel_btn_hovercolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_hoverbordercolor', esc_html__('Button hover border color', 'reservit-hotel'), array($this, 'hotel_btn_hoverbordercolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_mobilebgcolor', esc_html__('Button background color on mobile device', 'reservit-hotel'), array($this, 'hotel_btn_mobilebgcolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_mobilecolor', esc_html__('Button text color on mobile device', 'reservit-hotel'), array($this, 'hotel_btn_mobilecolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_mobilebordercolor', esc_html__('Button border color on mobile device', 'reservit-hotel'), array($this, 'hotel_btn_mobilebordercolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_mobilehoverbgcolor', esc_html__('Button hover background color on mobile device', 'reservit-hotel'), array($this, 'hotel_btn_mobilehoverbgcolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_mobilehovercolor', esc_html__('Button hover text color on mobile device', 'reservit-hotel'), array($this, 'hotel_btn_mobilehovercolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_mobilehoverbordercolor', esc_html__('Button hover border color on mobile device', 'reservit-hotel'), array($this, 'hotel_btn_mobilehoverbordercolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');
        add_settings_field('rsvit_btn_ico', esc_html__('Display icon', 'reservit-hotel') . ' <i id="btn_bed_ico" class="fa fa-bed" aria-hidden="true"></i>' . esc_html('on button', 'reservit-hotel'), array($this, 'hotel_btn_ico_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_section');

        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_bgcolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_hoverbgcolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_color', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_hovercolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_mobilebgcolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_mobilehoverbgcolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_mobilecolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_mobilehovercolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_fontsize', array($this, 'reservit_sanitize_num_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_fontunit', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_fontweight', array($this, 'reservit_sanitize_integer_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_ico', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_radius', array($this, 'reservit_sanitize_num_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_radiusunit', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_bordercolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_borderwidth', array($this, 'reservit_sanitize_num_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_borderunit', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_hoverbordercolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_mobilebordercolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_btn_mobilehoverbordercolor', array($this, 'reservit_sanitize_text_field'));

        add_settings_section('rsvit_style_box_section', '', array($this, 'section_style_box_html'), 'rsvit_hotel_bestprice_style_settings');
        add_settings_field('rsvit_box_btn_color', esc_html__('Pop-up Window close button background color', 'reservit-hotel'), array($this, 'hotel_box_btn_color_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_box_section');
        add_settings_field('rsvit_box_btn_textcolor', esc_html__('Pop-up Window close button font color', 'reservit-hotel'), array($this, 'hotel_box_btn_textcolor_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_box_section');
        add_settings_field('rsvit_hotel_custom_css', esc_html__('Custom CSS', 'reservit-hotel'), array($this, 'hotel_custom_css_html'), 'rsvit_hotel_bestprice_style_settings', 'rsvit_style_box_section');
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_box_btn_color', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_box_btn_textcolor', array($this, 'reservit_sanitize_text_field'));
        register_setting('rsvit_hotel_bestprice_style_settings', 'rsvit_hotel_custom_css', array($this, 'reservit_sanitize_textarea'));
    }

    public function section_id_html() {
        
    }

    public function chaine_id_html() {
        ?>
        <input type="number" min="0" step="1" name="rsvit_chaine_id" value="<?php echo get_option('rsvit_chaine_id') ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the chain ID given by reservit', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_id_html() {
        ?>
        <input type="number" min="0" step="1" name="rsvit_hotel_id" value="<?php echo get_option('rsvit_hotel_id') ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the hotel ID given by reservit', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function section_content_html() {
        esc_html_e('Fill the display parameters', 'reservit-hotel');
    }

    public function hotel_max_adlut_html() {
        $rsvit_hotel_max_adlut = get_option('rsvit_hotel_max_adlut');
        ?>
        <input type="number" min="0" step="1" name="rsvit_hotel_max_adlut" min="0" value="<?php if (!empty($rsvit_hotel_max_adlut)) {
            echo $rsvit_hotel_max_adlut;
        } else {
            echo '4';
        } ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the maximum adults number admitted', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_max_child_html() {
        $rsvit_hotel_max_child = get_option('rsvit_hotel_max_child');
        ?>
        <input type="number" min="0" step="1" name="rsvit_hotel_max_child" min="0" value="<?php if (!empty($rsvit_hotel_max_child)) {
            echo $rsvit_hotel_max_child;
        } else {
            echo '2';
        } ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the maximum children number admitted', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_design_version_html() {
        $rsvit_design_version = get_option('rsvit_hotel_design_version');
        ?>
        <select name="rsvit_hotel_design_version">
            <option value="1" <?php if ($rsvit_design_version == '1') echo "selected"; ?>>Version 1</option>
            <option value="2" <?php if ($rsvit_design_version == '2') echo "selected"; ?>>Version 2</option>
        </select>

        <?php
    }

    public function hotel_bestprice_display_html() {
        ?>
        <input type="checkbox" name="rsvit_hotel_bestprice_display" value="true" <?php if (get_option('rsvit_hotel_bestprice_display') == "true") {
            echo "checked";
        }; ?>/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Check this option if you want to display a higher price from a partner', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_distributorprice_display_html() {
        ?>
        <input type="checkbox" name="rsvit_hotel_distributorprice_display" value="true" <?php if (get_option('rsvit_hotel_distributorprice_display') == "true") {
            echo "checked";
        }; ?>/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e("Display the distributor\'s price even if it is equal to the hotel\'s price", 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_distributorpriceblock_display_html() {
        ?>
        <input type="checkbox" name="rsvit_hotel_distributorpriceblock_display" value="true" <?php if (get_option('rsvit_hotel_distributorpriceblock_display') == "true") {
            echo "checked";
        }; ?>/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Check this option if you want to display a higher price from a partner', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_partner_id_html() {
        ?>
        <input type="text" name="rsvit_hotel_partner_id" value="<?php echo get_option('rsvit_hotel_partner_id'); ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the partner ID that you want to display', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_distributorpartner1_id_html() {
        ?>
        <input type="text" name="rsvit_hotel_distributorpartner1_id" value="<?php echo get_option('rsvit_hotel_distributorpartner1_id'); ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the distributorpartner1 ID that you want to display', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_distributorpartner2_id_html() {
        ?>
        <input type="text" name="rsvit_hotel_distributorpartner2_id" value="<?php echo get_option('rsvit_hotel_distributorpartner2_id'); ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the distributorpartner2 ID that you want to display', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_distributorpartner3_id_html() {
        ?>
        <input type="text" name="rsvit_hotel_distributorpartner3_id" value="<?php echo get_option('rsvit_hotel_distributorpartner3_id'); ?>"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Fill with the distributorpartner3 ID that you want to display', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function section_btn_html() {
        ?>
        <hr/>
        <h2><?php esc_html_e('Button text', 'reservit-hotel'); ?></h2>
        <?php esc_html_e('Customize the button text you want to display for each language', 'reservit-hotel'); ?>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('30 characters maximun', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_fr_html() {
        $rsvit_btn_txt_fr = get_option('rsvit_btn_txt_fr');
        ?>
        <input type="text" name="rsvit_btn_txt_fr" value="<?php if (!empty($rsvit_btn_txt_fr)) {
            echo $rsvit_btn_txt_fr;
        } else {
            echo 'Meilleur tarif';
        } ?>" placeholder="Meilleur tarif" maxlenght="30"/>
        <?php
    }

    public function hotel_btn_en_html() {
        $rsvit_btn_txt_en = get_option('rsvit_btn_txt_en');
        ?>
        <input type="text" name="rsvit_btn_txt_en" value="<?php if (!empty($rsvit_btn_txt_en)) {
            echo $rsvit_btn_txt_en;
        } else {
            echo 'Best price';
        } ?>" placeholder="Best price" maxlenght="30"/>
        <?php
    }

    public function hotel_btn_pt_html() {
        $rsvit_btn_txt_pt = get_option('rsvit_btn_txt_pt');
        ?>
        <input type="text" name="rsvit_btn_txt_pt" value="<?php if (!empty($rsvit_btn_txt_pt)) {
            echo $rsvit_btn_txt_pt;
        } else {
            echo 'Melhor preço';
        } ?>" placeholder="Melhor preço" maxlenght="30"/>
        <?php
    }

    public function hotel_btn_it_html() {
        $rsvit_btn_txt_it = get_option('rsvit_btn_txt_it');
        ?>
        <input type="text" name="rsvit_btn_txt_it" value="<?php if (!empty($rsvit_btn_txt_it)) {
            echo $rsvit_btn_txt_it;
        } else {
            echo 'Miglior prezzo';
        } ?>" placeholder="Miglior prezzo" maxlenght="30"/>
        <?php
    }

    public function hotel_btn_de_html() {
        $rsvit_btn_txt_de = get_option('rsvit_btn_txt_de');
        ?>
        <input type="text" name="rsvit_btn_txt_de" value="<?php if (!empty($rsvit_btn_txt_de)) {
            echo $rsvit_btn_txt_de;
        } else {
            echo 'Günstigsten Preis';
        } ?>" placeholder="Günstigsten Preis" maxlenght="30"/>
        <?php
    }

    public function hotel_btn_es_html() {
        $rsvit_btn_txt_es = get_option('rsvit_btn_txt_es');
        ?>
        <input type="text" name="rsvit_btn_txt_es" value="<?php if (!empty($rsvit_btn_txt_es)) {
            echo $rsvit_btn_txt_es;
        } else {
            echo 'Mejor tarifa';
        } ?>" placeholder="Mejor tarifa" maxlenght="30"/>
        <?php
    }

    public function section_style_html() {
        esc_html_e('Fill the button display parameters', 'reservit-hotel');
    }

    public function hotel_btn_bgcolor_html() {
        $rsvit_btn_bgcolor = get_option('rsvit_btn_bgcolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_bgcolor" value="<?php if (!empty($rsvit_btn_bgcolor)) {
            echo $rsvit_btn_bgcolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_hoverbgcolor_html() {
        $rsvit_btn_hoverbgcolor = get_option('rsvit_btn_hoverbgcolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_hoverbgcolor" value="<?php if (!empty($rsvit_btn_hoverbgcolor)) {
            echo $rsvit_btn_hoverbgcolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_color_html() {
        $rsvit_btn_color = get_option('rsvit_btn_color');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_color" value="<?php if (!empty($rsvit_btn_color)) {
            echo $rsvit_btn_color;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_hovercolor_html() {
        $rsvit_btn_hovercolor = get_option('rsvit_btn_hovercolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_hovercolor" value="<?php if (!empty($rsvit_btn_hovercolor)) {
            echo $rsvit_btn_hovercolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_mobilebgcolor_html() {
        $rsvit_btn_mobilebgcolor = get_option('rsvit_btn_mobilebgcolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_mobilebgcolor" value="<?php if (!empty($rsvit_btn_mobilebgcolor)) {
            echo $rsvit_btn_mobilebgcolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_mobilehoverbgcolor_html() {
        $rsvit_btn_mobilehoverbgcolor = get_option('rsvit_btn_mobilehoverbgcolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_mobilehoverbgcolor" value="<?php if (!empty($rsvit_btn_mobilehoverbgcolor)) {
            echo $rsvit_btn_mobilehoverbgcolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_mobilecolor_html() {
        $rsvit_btn_mobilecolor = get_option('rsvit_btn_mobilecolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_mobilecolor" value="<?php if (!empty($rsvit_btn_mobilecolor)) {
            echo $rsvit_btn_mobilecolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_mobilehovercolor_html() {
        $rsvit_btn_mobilehovercolor = get_option('rsvit_btn_mobilehovercolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_mobilehovercolor" value="<?php if (!empty($rsvit_btn_mobilehovercolor)) {
            echo $rsvit_btn_mobilehovercolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_radius_html() {
        ?>
        <input type="number" min="0" step="0.01" name="rsvit_btn_radius" value="<?php echo get_option('rsvit_btn_radius'); ?>"/>
        <select name="rsvit_btn_radiusunit">
            <option value="" <?php if ((get_option('rsvit_btn_radiusunit') == "")) {
            echo 'selected';
        }; ?>><?= esc_html_e('Default unit', 'reservit-hotel'); ?>  </option> 
            <option value="px" <?php if ((get_option('rsvit_btn_radiusunit') == "px")) {
            echo 'selected';
        }; ?>>px</option>
            <option value="em" <?php if ((get_option('rsvit_btn_radiusunit') == "em")) {
            echo 'selected';
        }; ?>>em</option>
            <option value="rem" <?php if ((get_option('rsvit_btn_radiusunit') == "rem")) {
            echo 'selected';
        }; ?>>rem</option>
            <option value="%" <?php if ((get_option('rsvit_btn_radiusunit') == "%")) {
            echo 'selected';
        }; ?>>%</option>
        </select>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty & default unit to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_ico_html() {
        ?>
        <input type="radio" name="rsvit_btn_ico" value="initial" <?php if (get_option('rsvit_btn_ico') == "initial") {
            echo 'checked';
        }; ?>>
        <label><?= esc_html_e('Yes', 'reservit-hotel'); ?></label>
        <input type="radio" name="rsvit_btn_ico" value="none" <?php if (get_option('rsvit_btn_ico') == "none") {
            echo 'checked';
        }; ?>>
        <label><?= esc_html_e('No', 'reservit-hotel'); ?></label>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('The icon is displayed before the text, you have got the choise to display it or not', 'reservit-hotel'); ?>">
            <i class="fa fa-circle fa-stack-2x"></i>
            <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
        </span>
        </a>
        <?php
    }

    public function hotel_btn_fontsize_html() {
        $rsvit_btn_fontsize = get_option('rsvit_btn_fontsize');
        ?>
        <input type="number" name="rsvit_btn_fontsize" min="0" step="0.01" value="<?php if (!empty($rsvit_btn_fontsize)) {
            echo $rsvit_btn_fontsize;
        } ?>"/>
        <select name="rsvit_btn_fontunit">
            <option value="" <?php if ((get_option('rsvit_btn_fontunit') == "")) {
            echo 'selected';
        }; ?>> <?= esc_html_e('Default unit', 'reservit-hotel'); ?>  </option> 
            <option value="px" <?php if ((get_option('rsvit_btn_fontunit') == "px")) {
            echo 'selected';
        }; ?>>px</option>
            <option value="em" <?php if ((get_option('rsvit_btn_fontunit') == "em")) {
            echo 'selected';
        }; ?>>em</option>
            <option value="rem" <?php if ((get_option('rsvit_btn_fontunit') == "rem")) {
            echo 'selected';
        }; ?>>rem</option>
            <option value="%" <?php if ((get_option('rsvit_btn_fontunit') == "%")) {
            echo 'selected';
        }; ?>>%</option>
        </select>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty & default unit to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_fontweight_html() {
        ?>
        <select name="rsvit_btn_fontweight">
            <option value="" <?php if ((get_option('rsvit_btn_fontweight') == "")) {
            echo 'selected';
        }; ?>> <?= esc_html_e('Default weight', 'reservit-hotel'); ?>  </option>
            <option value="100" <?php if ((get_option('rsvit_btn_fontweight') == "100")) {
            echo 'selected';
        }; ?>>100</option>
            <option value="200" <?php if ((get_option('rsvit_btn_fontweight') == "200")) {
            echo 'selected';
        }; ?>>200</option>
            <option value="300" <?php if ((get_option('rsvit_btn_fontweight') == "300")) {
            echo 'selected';
        }; ?>>300</option>
            <option value="400" <?php if ((get_option('rsvit_btn_fontweight') == "400")) {
            echo 'selected';
        }; ?>>normal</option>
            <option value="500" <?php if ((get_option('rsvit_btn_fontweight') == "500")) {
            echo 'selected';
        }; ?>>500</option>
            <option value="600" <?php if ((get_option('rsvit_btn_fontweight') == "600")) {
            echo 'selected';
        }; ?>>600</option>
            <option value="700" <?php if ((get_option('rsvit_btn_fontweight') == "700")) {
            echo 'selected';
        }; ?>>bold</option>
            <option value="800" <?php if ((get_option('rsvit_btn_fontweight') == "800")) {
            echo 'selected';
        }; ?>>800</option>
            <option value="900" <?php if ((get_option('rsvit_btn_fontweight') == "900")) {
            echo 'selected';
        }; ?>>900</option>
        </select>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep default weight to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_bordercolor_html() {
        $rsvit_btn_bordercolor = get_option('rsvit_btn_bordercolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_bordercolor" value="<?php if (!empty($rsvit_btn_bordercolor)) {
            echo $rsvit_btn_bordercolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep default weight to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_borderwidth_html() {
        ?>
        <input type="number" min="0" step="0.01" name="rsvit_btn_borderwidth" value="<?php echo get_option('rsvit_btn_borderwidth'); ?>"/>
        <select name="rsvit_btn_borderunit">
            <option value="" <?php if ((get_option('rsvit_btn_borderunit') == "")) {
            echo 'selected';
        }; ?>> <?= esc_html_e('Default unit', 'reservit-hotel'); ?>  </option> 
            <option value="px" <?php if ((get_option('rsvit_btn_borderunit') == "px")) {
            echo 'selected';
        }; ?>>px</option>
            <option value="em" <?php if ((get_option('rsvit_btn_borderunit') == "em")) {
            echo 'selected';
        }; ?>>em</option>
            <option value="rem" <?php if ((get_option('rsvit_btn_borderunit') == "rem")) {
            echo 'selected';
        }; ?>>rem</option>
            <option value="%" <?php if ((get_option('rsvit_btn_borderunit') == "%")) {
            echo 'selected';
        }; ?>>%</option>
        </select>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty & default unit to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_hoverbordercolor_html() {
        $rsvit_btn_hoverbordercolor = get_option('rsvit_btn_hoverbordercolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_hoverbordercolor" value="<?php if (!empty($rsvit_btn_hoverbordercolor)) {
            echo $rsvit_btn_hoverbordercolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_mobilehoverbordercolor_html() {
        $rsvit_btn_mobilehoverbordercolor = get_option('rsvit_btn_mobilehoverbordercolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_mobilehoverbordercolor" value="<?php if (!empty($rsvit_btn_mobilehoverbordercolor)) {
            echo $rsvit_btn_mobilehoverbordercolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_btn_mobilebordercolor_html() {
        $rsvit_btn_mobilebordercolor = get_option('rsvit_btn_mobilebordercolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_btn_mobilebordercolor" value="<?php if (!empty($rsvit_btn_mobilebordercolor)) {
            echo $rsvit_btn_mobilebordercolor;
        } else {
            echo '';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Keep empty to use the default value of your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function section_style_box_html() {
        ?>
        <hr/>
        <h2><?php esc_html_e('Pop-up window style', 'reservit-hotel'); ?></h2>
        <?php
    }

    public function hotel_box_btn_color_html() {
        $rsvit_box_btn_color = get_option('rsvit_box_btn_color');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_box_btn_color" value="<?php if (!empty($rsvit_box_btn_color)) {
            echo $rsvit_box_btn_color;
        } else {
            echo '#ffffff';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('We set white as default but you can change this value to match with your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_box_btn_textcolor_html() {
        $rsvit_box_btn_textcolor = get_option('rsvit_box_btn_textcolor');
        ?>
        <input type="text" class="color-picker" data-alpha="true" name="rsvit_box_btn_textcolor" value="<?php if (!empty($rsvit_box_btn_textcolor)) {
            echo $rsvit_box_btn_textcolor;
        } else {
            echo '#000000';
        } ?>" placeholder="rgba(244,158,76,0.82)"/>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('We set black as default but you can change this value to match with your theme', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    public function hotel_custom_css_html() {
        $rsvit_hotel_custom_css = get_option('rsvit_hotel_custom_css');
        ?>
        <p> <?= esc_html_e('Write here your own CSS', 'reservit-hotel'); ?></p>
        <textarea rows="6" cols="80" form="styleForm" name="rsvit_hotel_custom_css"><?php if (!empty($rsvit_hotel_custom_css)) {
            echo $rsvit_hotel_custom_css;
        } else {
            echo "";
        }; ?></textarea>
        <a class="picto-item" href="#" aria-label= "<?= esc_html_e('Use carefully. The content of this section could generate some conflicts', 'reservit-hotel'); ?>">
            <span class="fa-stack fa-xs">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-medkit fa-stack-1x fa-inverse"></i>
            </span>
        </a>
        <?php
    }

    function reservit_sanitize_text_field($rsrvitoption) {

        return sanitize_text_field($rsrvitoption);
    }

    public function reservit_sanitize_textarea($rsrvitoption) {

        return esc_textarea($rsrvitoption);
    }

    public function reservit_sanitize_num_field($rsrvitoption) {
        //sanitize
        $rsrvitsafeoption = stripslashes(strip_tags($rsrvitoption));
        if (is_numeric($rsrvitsafeoption)) {
            $rsrvitoption = $rsrvitsafeoption;
        } else {
            $rsrvitoption = '';
        }

        return $rsrvitoption;
    }

    public function reservit_sanitize_integer_field($rsrvitoption) {
        //sanitize
        $rsrvitsafeoption = stripslashes(strip_tags($rsrvitoption));
        if (is_numeric($rsrvitsafeoption)) {
            $rsrvitoption = intval($rsrvitsafeoption);
        } else {
            $rsrvitoption = '';
        }

        return $rsrvitoption;
    }

}
