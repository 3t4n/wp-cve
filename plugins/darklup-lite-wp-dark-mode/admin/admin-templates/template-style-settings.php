<?php

namespace DarklupLite\Admin;
/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.0.0
 * @author
 * @Websites:
 *
 */
class Style_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([
            'title' => esc_html__('SWITCH STYLES', 'darklup-lite'),
            'class' => 'darkluplite-style-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/style.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/style-white.svg'),
            'id' => 'darkluplite_style_settings'
        ]);



        $switch_cases = [
            'desktop_switch' => 'Floating Switch (Desktop)',
            'mobile_switch' => 'Floating Switch (Mobile)',
            'menu_switch' => 'Menu Switch',
            'advance_style' => 'Advance Settings',
        ];
        
        $this->button_radio_field([
            'class' => 'settings-color-preset',
            'name' => 'switch_cases',
            'options' => $switch_cases,
            'default' => 'desktop_switch',
        ]);



        /******************************** Desktop Settings **********************************************/
        $this->switch_field([
            'title' => esc_html__('Display Floating Switch in Desktop', 'darklup-lite'),
            'sub_title' => esc_html__('Enable the switch to show the dark mode switch button on the Desktop screen.', 'darklup-lite'),
            'name' => 'switch_in_desktop',
            'input_classes' => 'darklup_default_checked',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
        ]);

        $switch_styles = [
            '1' => DARKLUPLITE_DIR_URL . 'assets/img/switch-15.svg',
            '2' => DARKLUPLITE_DIR_URL . 'assets/img/switch-1.svg',
            '3' => DARKLUPLITE_DIR_URL . 'assets/img/switch-2.svg',
            '4' => DARKLUPLITE_DIR_URL . 'assets/img/switch-8.svg',
            '5' => DARKLUPLITE_DIR_URL . 'assets/img/switch-3.svg',
            '6' => DARKLUPLITE_DIR_URL . 'assets/img/switch-4.svg',
            '7' => DARKLUPLITE_DIR_URL . 'assets/img/switch-5.png',
            '8' => DARKLUPLITE_DIR_URL . 'assets/img/switch-6.svg',
            '9' => DARKLUPLITE_DIR_URL . 'assets/img/switch-7.svg',
            '10' => DARKLUPLITE_DIR_URL . 'assets/img/switch-9.svg',
            '11' => DARKLUPLITE_DIR_URL . 'assets/img/switch-10.svg',
            '12' => DARKLUPLITE_DIR_URL . 'assets/img/switch-11.png',
            '13' => DARKLUPLITE_DIR_URL . 'assets/img/switch-12.png',
            '14' => DARKLUPLITE_DIR_URL . 'assets/img/switch-13.svg',
            '15' => DARKLUPLITE_DIR_URL . 'assets/img/switch-14.svg'
        ];
        $this->image_radio_field([
            'title' => esc_html__('Switch Style', 'darklup-lite'),
            'sub_title' => esc_html__('Select the switcher button style for the frontend.', 'darklup-lite'),
            // 'class' => 'settings-color-preset',
            'class' => 'settings-switch-style desktop-switch-style',
            'name' => 'switch_style',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'options' => $switch_styles
        ]);



        $this->select_box([
            'title' => esc_html__('Switch Position', 'darklup-lite'),
            'sub_title' => esc_html__('Select the position of the floating dark mode switcher button on the frontend.', 'darklup-lite'),
            'class' => 'settings-switch-position',
            'name' => 'desktop_switch_position',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'options' => [
                'top_right' => esc_html__('Top Right', 'darklup-lite'),
                'top_left' => esc_html__('Top Left', 'darklup-lite'),
                'bottom_right' => esc_html__('Bottom Right ', 'darklup-lite'),
                'bottom_left' => esc_html__('Bottom Left', 'darklup-lite'),
            ]
        ]);
        
        $this->select_box([
            'title' => esc_html__('Switch Margin Unit', 'darklup-lite'),
            'sub_title' => esc_html__('Select the unit (pixel or percentage) to set Customized Switch Margin.', 'darklup-lite'),
            'class' => 'settings-switch-position',
            'name' => 'switch_margin_unit',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'options' => [
                'pixel' => esc_html__('Pixel (px)', 'darklup-lite'),
                'percent' => esc_html__('Percent (%)', 'darklup-lite')
            ]
        ]);
        $this->margin_field([
            'title' => esc_html__('Switch Margin', 'darklup-lite'),
            'sub_title' => esc_html__('Set floating switch margin in given unit.', 'darklup-lite'),
            'name' => array("switch_top_margin", "switch_bottom_margin", "switch_right_margin", "switch_left_margin"),
            'step' => '1',
            'max' => '5000',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'placeholder' => array("Top Margin", "Bottom Margin", "Right Margin", "Left Margin")
        ]);


        $this->number_field([
            'title' => esc_html__('Floating Switch Width (px)', 'darklup'),
            'sub_title' => esc_html__('Set the custom floating switch width.', 'darklup'),
            'class' => 'settings-switch-position',
            'name' => 'switch_size_base_width',
            'step' => '1',
            'max' => '500',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'placeholder' => esc_html__('Width (Default 100)', 'darklup')
        ]);
        $this->number_field([
            'title' => esc_html__('Floating Switch Height (px)', 'darklup'),
            'sub_title' => esc_html__('Set the custom floating switch height.', 'darklup'),
            'class' => 'settings-switch-position',
            'name' => 'switch_size_base_height',
            'step' => '1',
            'max' => '500',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'placeholder' => esc_html__('Height (Default 40)', 'darklup')
        ]);
        

        $this->number_field([
            'title' => esc_html__('Switch Icon Width (px)', 'darklup'),
            'sub_title' => esc_html__('Set the custom floating switch Icon Width.', 'darklup'),
            'class' => 'settings-switch-position',
            'name' => 'floating_switch_icon_width',
            'step' => '1',
            'max' => '50',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'placeholder' => esc_html__('e.g 14', 'darklup')
        ]);
        $this->number_field([
            'title' => esc_html__('Switch Icon Height (px)', 'darklup'),
            'sub_title' => esc_html__('Set the custom floating switch Icon Width.', 'darklup'),
            'class' => 'settings-switch-position',
            'name' => 'floating_switch_icon_height',
            'step' => '1',
            'max' => '50',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'placeholder' => esc_html__('e.g 14', 'darklup')
        ]);


        $this->number_field([
            'title' => esc_html__('Switch Border radious (px)', 'darklup'),
            'sub_title' => esc_html__('Set the custom floating switch width.', 'darklup'),
            'class' => 'settings-switch-position',
            'name' => 'floating_switch_border_radius',
            'step' => '1',
            'max' => '50',
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'placeholder' => esc_html__('e.g 14', 'darklup')
        ]);

        $this->color_field([
            'title' => esc_html__( 'Switch Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the switch background color', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Switch Icon Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the switch icon color', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_icon_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Switch Border Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the switch border color', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_border_color'
          ]);

          
          // Styles on Dark Mode
          $this->color_field([
            'title' => esc_html__( 'Switch Background Color on Dark Mode', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the base background color on dark mode', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_bg_color_on_dark'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Switch Icon Color on Dark Mode', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the icon plate color on dark mode', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_icon_color_on_dark'
          ]);

          $this->color_field([
            'title' => esc_html__( 'Switch Border Color on Dark Mode', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the switch border color on dark mode', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_border_color_on_dark'
          ]);

          $this->color_field([
            'title' => esc_html__( 'Switch Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the switch text color', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_text_color'
          ]);

          
          $this->color_field([
            'title' => esc_html__( 'Switch Text Color on Dark Mode', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set the switch text color on dark mode', 'darklup-lite' ),
            'condition' => ["key" => "switch_cases", "value" => "desktop_switch"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_switch_text_color_on_dark'
          ]);


            /******************************** Mobile Settings **********************************************/

            $this->switch_field([
                'title' => esc_html__('Display Switch on Mobile', 'darklup-lite'),
                'sub_title' => esc_html__('Turn on to show switch on mobile', 'darklup-lite'),
                'name' => 'switch_in_mobile',
                'input_classes' => 'darklup_default_checked',
                'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            ]);
            $this->image_radio_field([
                'title' => esc_html__('Switch Style', 'darklup-lite'),
                'sub_title' => esc_html__('Select the switcher button style for the frontend.', 'darklup-lite'),
                // 'class' => 'settings-color-preset',
                'class' => 'settings-switch-style mobile-switch-style',
                'name' => 'switch_style_mobile',
                'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
                'options' => $switch_styles
            ]);
            // $this->select_box([
            //     'title' => esc_html__('Switch Position', 'darklup-lite'),
            //     'sub_title' => esc_html__('Select the position of the floating dark mode switcher button on the frontend.', 'darklup-lite'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_position_mobile',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'options' => [
            //         'top_right' => esc_html__('Top Right', 'darklup-lite'),
            //         'top_left' => esc_html__('Top Left', 'darklup-lite'),
            //         'bottom_right' => esc_html__('Bottom Right ', 'darklup-lite'),
            //         'bottom_left' => esc_html__('Bottom Left', 'darklup-lite'),
            //     ]
            // ]);
            // $this->select_box([
            //     'title' => esc_html__('Switch Margin Unit', 'darklup-lite'),
            //     'sub_title' => esc_html__('Select the unit (pixel or percentage) to set Customized Switch Margin.', 'darklup-lite'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_margin_unit_mobile',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'options' => [
            //         'pixel' => esc_html__('Pixel (px)', 'darklup-lite'),
            //         'percent' => esc_html__('Percent (%)', 'darklup-lite')
            //     ]
            // ]);
            // $this->margin_field([
            //     'title' => esc_html__('Switch Margin', 'darklup-lite'),
            //     'sub_title' => esc_html__('Set floating switch margin in given unit.', 'darklup-lite'),
            //     'name' => array("switch_top_margin_mobile", "switch_bottom_margin_mobile", "switch_right_margin_mobile", "switch_left_margin_mobile"),
            //     'step' => '1',
            //     'max' => '5000',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'placeholder' => array("Top Margin", "Bottom Margin", "Right Margin", "Left Margin")
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Floating Switch Width (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_size_base_width_mobile',
            //     'step' => '1',
            //     'max' => '500',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'placeholder' => esc_html__('Width (Default 100)', 'darklup')
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Floating Switch Height (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch height.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_size_base_height_mobile',
            //     'step' => '1',
            //     'max' => '500',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'placeholder' => esc_html__('Height (Default 40)', 'darklup')
            // ]);
            
            // $this->number_field([
            //     'title' => esc_html__('Switch Border radious (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'floating_switch_border_radius_mobile',
            //     'step' => '1',
            //     'max' => '50',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'placeholder' => esc_html__('e.g 14', 'darklup')
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Switch Icon Width (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch Icon Width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'floating_switch_icon_width_mobile',
            //     'step' => '1',
            //     'max' => '50',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'placeholder' => esc_html__('e.g 14', 'darklup')
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Switch Icon Height (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch Icon Width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'floating_switch_icon_height_mobile',
            //     'step' => '1',
            //     'max' => '50',
            //     'condition' => ["key" => "switch_cases", "value" => "mobile_switch"],
            //     'placeholder' => esc_html__('e.g 14', 'darklup')
            // ]);



            /******************************** Menu Settings **********************************************/


            $this->switch_field([
                'title' => esc_html__('Display Switch on Menu', 'darklup-lite'),
                'sub_title' => esc_html__('Turn on to show switch on mobile', 'darklup-lite'),
                'name' => 'switch_in_menu',
                'input_classes' => 'darklup_default_checked',
                'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            ]);
            $this->image_radio_field([
                'title' => esc_html__('Switch Style', 'darklup-lite'),
                'sub_title' => esc_html__('Select the switcher button style for the frontend.', 'darklup-lite'),
                // 'class' => 'settings-color-preset',
                'class' => 'settings-switch-style menu-switch-style',
                'name' => 'switch_style_menu',
                'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
                'options' => $switch_styles
            ]);

            $this->Multiple_select_box([
                'title'     => esc_html__( 'Select Menu', 'darklup' ),
                'sub_title' => esc_html__( 'Set the menu location', 'darklup' ),
                'name'      => 'menu_location',
                'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
                // 'condition' => ["key" => "switch_in_menu", "value" => "yes"],
                // 'condition' => [["key" => "switch_in_menu", "value" => "yes"], ["key" => "switch_cases", "value" => "menu_switch"]],
                'options'   => \Darkluplite\Helper::getMenuLocations()
              ]);

              $this->margin_field([
                'title' => esc_html__('Switch Menu Margin (px)', 'darklup'),
                'sub_title' => esc_html__('Set switch menu margin in px.', 'darklup'),
                'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
                'name' => array("switch_menu_top_margin", "switch_menu_bottom_margin", "switch_menu_right_margin", "switch_menu_left_margin"),
                'step' => '1',
                'max' => '200',
                'is_pro' => 'yes',
                'wrapper_class' => 'pro-feature',    
                'placeholder' => array("Top Margin", "Bottom Margin", "Right Margin", "Left Margin")
            ]);

            // $this->select_box([
            //     'title' => esc_html__('Switch Position', 'darklup-lite'),
            //     'sub_title' => esc_html__('Select the position of the floating dark mode switcher button on the frontend.', 'darklup-lite'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_position_menu',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'options' => [
            //         'top_right' => esc_html__('Top Right', 'darklup-lite'),
            //         'top_left' => esc_html__('Top Left', 'darklup-lite'),
            //         'bottom_right' => esc_html__('Bottom Right ', 'darklup-lite'),
            //         'bottom_left' => esc_html__('Bottom Left', 'darklup-lite'),
            //     ]
            // ]);
            // $this->select_box([
            //     'title' => esc_html__('Switch Margin Unit', 'darklup-lite'),
            //     'sub_title' => esc_html__('Select the unit (pixel or percentage) to set Customized Switch Margin.', 'darklup-lite'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_margin_unit_menu',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'options' => [
            //         'pixel' => esc_html__('Pixel (px)', 'darklup-lite'),
            //         'percent' => esc_html__('Percent (%)', 'darklup-lite')
            //     ]
            // ]);
            // $this->margin_field([
            //     'title' => esc_html__('Switch Margin', 'darklup-lite'),
            //     'sub_title' => esc_html__('Set floating switch margin in given unit.', 'darklup-lite'),
            //     'name' => array("switch_top_margin_menu", "switch_bottom_margin_menu", "switch_right_margin_menu", "switch_left_margin_menu"),
            //     'step' => '1',
            //     'max' => '5000',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'placeholder' => array("Top Margin", "Bottom Margin", "Right Margin", "Left Margin")
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Floating Switch Width (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_size_base_width_menu',
            //     'step' => '1',
            //     'max' => '500',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'placeholder' => esc_html__('Width (Default 100)', 'darklup')
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Floating Switch Height (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch height.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'switch_size_base_height_menu',
            //     'step' => '1',
            //     'max' => '500',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'placeholder' => esc_html__('Height (Default 40)', 'darklup')
            // ]);
            
            // $this->number_field([
            //     'title' => esc_html__('Switch Border radious (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'floating_switch_border_radius_menu',
            //     'step' => '1',
            //     'max' => '50',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'placeholder' => esc_html__('e.g 14', 'darklup')
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Switch Icon Width (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch Icon Width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'floating_switch_icon_width_menu',
            //     'step' => '1',
            //     'max' => '50',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'placeholder' => esc_html__('e.g 14', 'darklup')
            // ]);
            // $this->number_field([
            //     'title' => esc_html__('Switch Icon Height (px)', 'darklup'),
            //     'sub_title' => esc_html__('Set the custom floating switch Icon Width.', 'darklup'),
            //     'class' => 'settings-switch-position',
            //     'name' => 'floating_switch_icon_height_menu',
            //     'step' => '1',
            //     'max' => '50',
            //     'condition' => ["key" => "switch_cases", "value" => "menu_switch"],
            //     'placeholder' => esc_html__('e.g 14', 'darklup')
            // ]);











        $this->switch_field([
            'title' => esc_html__('Show tooltip?', 'darklup-lite'),
            'sub_title' => esc_html__('Choose to display tooltip on switch hover.', 'darklup-lite'),
            'name' => 'darkluplite_show_tooltip',
            'condition' => ["key" => "switch_cases", "value" => "advance_style"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature'
        ]);

        $this->select_box([
            'title' => esc_html__('Switch Animation', 'darklup-lite'),
            'sub_title' => esc_html__('Select an animation effect for the switch.', 'darklup-lite'),
            'name' => 'darkluplite_switcher_animate',
            'options' => [
                'none' => esc_html__('None', 'darklup-lite'),
                'animate_vibrate'   => esc_html__('Vibrate', 'darklup-lite'),
                'animate_shake'     => esc_html__('Shake', 'darklup-lite'),
                'animate_heartbeat' => esc_html__('Heartbeat', 'darklup-lite'),
                'animate_rotate'    => esc_html__('Rotate', 'darklup-lite'),
                'animate_spring'    => esc_html__(' Spring', 'darklup-lite'),
            ],
            'condition' => ["key" => "switch_cases", "value" => "advance_style"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature'
        ]);
        $this->switch_field([
            'title' => esc_html__('Enable Draggable Floating Switch', 'darklup-lite'),
            'sub_title' => esc_html__('This feature allow users to drag the floating toggle switch to any position on the page.', 'darklup-lite'),
            'name' => 'enable_draggable_floating_switch',
            'condition' => ["key" => "switch_cases", "value" => "advance_style"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature'
        ]);        
        // $this->switch_field([
        //     'title' => esc_html__('Show Above Posts', 'darklup-lite'),
        //     'sub_title' => esc_html__('Show the dark mode switcher button above of all the post.', 'darklup-lite'),
        //     'name' => 'show_above_posts',
        //     'condition' => ["key" => "switch_cases", "value" => "advance_style"],
        //     'wrapper_class' => 'pro-feature',
        //     'is_pro' => 'yes'
        // ]);
        // $this->switch_field([
        //     'title' => esc_html__('Show Above Pages', 'darklup-lite'),
        //     'sub_title' => esc_html__('Show the dark mode switcher button above of all the pages.', 'darklup-lite'),
        //     'name' => 'show_above_pages',
        //     'condition' => ["key" => "switch_cases", "value" => "advance_style"],
        //     'wrapper_class' => 'pro-feature',
        //     'is_pro' => 'yes'
        // ]);




        // $this->switch_field([
        //     'title' => esc_html__('Want to Customize Switch Colors?', 'darklup-lite'),
        //     'sub_title' => esc_html__('Customize switch background, icon and text colors', 'darklup-lite'),
        //     'name' => 'label_custom_color_enabled',
        //     'is_pro' => 'yes',
        //     'wrapper_class' => 'pro-feature'
        // ]);
        // $this->switch_field([
        //     'title' => esc_html__('Want to Customize Switch Size?', 'darklup-lite'),
        //     'sub_title' => esc_html__('Customize switch width, height', 'darklup-lite'),
        //     'name' => 'label_custom_size_enabled',
        //     'is_pro' => 'yes',
        //     'wrapper_class' => 'pro-feature'
        // ]);



        // $this->select_box([
        //     'title' => esc_html__('Switch Position', 'darklup-lite'),
        //     'sub_title' => esc_html__('Select the position of the floating dark mode switcher button on the frontend.', 'darklup-lite'),
        //     'class' => 'settings-switch-position',
        //     'wrapper_class' => 'pro-feature',
        //     'is_pro' => 'yes',
        //     'name' => 'switch_position',
        //     'options' => [
        //         '1' => esc_html__('Top Right', 'darklup-lite'),
        //         '2' => esc_html__('Top Left', 'darklup-lite'),
        //         '3' => esc_html__('Bottom Right ', 'darklup-lite'),
        //         '4' => esc_html__('Bottom Left', 'darklup-lite'),
        //     ]
        // ]);

        // $this->margin_field([
        //     'title' => esc_html__('Switch Margin (px)', 'darklup-lite'),
        //     'sub_title' => esc_html__('Set floating switch margin in px.', 'darklup-lite'),
        //     'name' => array("switch_top_margin", "switch_bottom_margin", "switch_right_margin", "switch_left_margin"),
        //     'step' => '1',
        //     'max' => '200',
        //     'placeholder' => array("Top Margin", "Bottom Margin", "Right Margin", "Left Margin")
        // ]);

        // $this->number_field([
        //     'title' => esc_html__('Text Font Size', 'darklup-lite'),
        //     'sub_title' => esc_html__('Set dark mode text font size.', 'darklup-lite'),
        //     'class' => 'settings-switch-position',
        //     'wrapper_class' => 'pro-feature',
        //     'is_pro' => 'yes',
        //     'name' => 'body_font_size',
        //     'step' => '1',
        //     'max' => '50',
        //     'placeholder' => esc_html__('14', 'darklup-lite'),
        // ]);
        // $this->text_field([
        //     'title' => esc_html__('Switch Text (Light)', 'darklup-lite'),
        //     'sub_title' => esc_html__('Switch light text.', 'darklup-lite'),
        //     'class' => 'settings-switch-position',
        //     'name' => 'switch_text_light',
        //     'wrapper_class' => 'pro-feature',
        //     'is_pro' => 'yes',
        //     'placeholder' => esc_html__('e.g Light', 'darklup-lite')
        // ]);
        // $this->text_field([
        //     'title' => esc_html__('Switch Text (Dark)', 'darklup-lite'),
        //     'sub_title' => esc_html__('Switch dark text.', 'darklup-lite'),
        //     'class' => 'settings-switch-position',
        //     'name' => 'switch_text_dark',
        //     'wrapper_class' => 'pro-feature',
        //     'is_pro' => 'yes',
        //     'placeholder' => esc_html__('e.g Dark', 'darklup-lite')
        // ]);



        $this->end_fields_section(); // End fields section

    }


}

new Style_Settings_Tab();