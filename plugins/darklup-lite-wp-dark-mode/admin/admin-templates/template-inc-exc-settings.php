<?php

namespace DarklupLite\Admin;
/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.1.2
 * @author
 * @Websites:
 *
 */
class IncExc_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([
            'title' => esc_html__('FILTER ELEMENTS', 'darklup-lite'),
            'class' => 'darkluplite-inc-exc-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/inc_exc.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/inc_exc-white.svg'),
            'id' => 'darkluplite_inc_exc_settings'
        ]);

        
        $this->text_field([
            'title' => esc_html__('Exclude Element', 'darklup-lite'),
            'sub_title' => esc_html__('Set the element like div, section, class, id which you want to ignore darkmode. Add comma separated CSS selectors (classes, ids)', 'darklup-lite'),
            'class' => 'settings-switch-position',
            'name' => 'exclude_element',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'placeholder' => esc_html__('e.g: .className,#id,div', 'darklup-lite')
        ]);
        $this->switch_field([
            'title' => esc_html__( 'Add Overlay to all Background Images', 'darklup' ),
            'sub_title' => esc_html__( 'Enable the overlay option to add a visually appealing effect to all background images, enhancing their appearance without the need for image replacement.', 'darklup' ),
            'name' => 'apply_bg_overlay'
          ]);
          
        $this->text_field([
            'title' => esc_html__('Exclude Background Image Overlay', 'darklup'),
            'sub_title' => esc_html__("When 'image overlay' enabled, all background images receive an overlay in dark mode. To remove the overlay from a specific element, please provide the class or ID of that element. Include comma-separated CSS selectors (classes, IDs) for multiple elements.", 'darklup'),
            'class' => 'settings-switch-position',
            'name' => 'exclude_bg_overlay',
            'condition' => ["key" => "apply_bg_overlay", "value" => "yes"],
            // 'condition' => ["key" => "time_based_darkmode", "value" => "yes"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'placeholder' => esc_html__('e.g: .className,#id,div', 'darklup')
        ]);
        $this->end_fields_section(); // End fields section

    }

}

new IncExc_Settings_Tab();