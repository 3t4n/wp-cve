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
class Trigger_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([
            'title' => esc_html__('TRIGGER SETTINGS', 'darklup-lite'),
            'class' => 'darkluplite-trigger-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/trigger.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/trigger-white.svg'),
            'id' => 'darkluplite_trigger_settings'
        ]);

        $this->Multiple_select_box([
            'title' => esc_html__('Exclude Pages', 'darklup-lite'),
            'sub_title' => esc_html__('Select the pages where you don\'t want to show the dark mode switch', 'darklup-lite'),
            'name' => 'exclude_pages',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getPages()
        ]);
        $this->Multiple_select_box([
            'title' => esc_html__('Include Pages', 'darklup'),
            'sub_title' => esc_html__('Select the pages where you want to show the dark mode switch except all other pages', 'darklup'),
            'name' => 'include_pages',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getPages()
        ]);

        $this->Multiple_select_box([
            'title' => esc_html__('Exclude Posts', 'darklup-lite'),
            'sub_title' => esc_html__('Select the posts where you don\'t want to show the dark mode switch', 'darklup-lite'),
            'name' => 'exclude_posts',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getPosts()
        ]);
        $this->Multiple_select_box([
            'title' => esc_html__('Include Posts', 'darklup'),
            'sub_title' => esc_html__('Select the posts where you want to show the dark mode switch except all other posts', 'darklup'),
            'name' => 'include_posts',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getPosts()
        ]);

        $this->Multiple_select_box([
            'title' => esc_html__('Exclude Categories', 'darklup-lite'),
            'sub_title' => esc_html__('Select the categories to exclude dark mode switch on the selected category posts', 'darklup-lite'),
            'name' => 'exclude_categories',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getCategories()
        ]);
        $this->Multiple_select_box([
            'title' => esc_html__('Include Categories', 'darklup'),
            'sub_title' => esc_html__('Select the categories where you want to show the dark mode switch except all other category posts', 'darklup'),
            'name' => 'include_categories',
            'wrapper_class' => 'pro-feature',
            'is_pro' => 'yes',
            'options' => \DarklupLite\Helper::getCategories()
        ]);

        $this->end_fields_section(); // End fields section

    }


}

new Trigger_Settings_Tab();