<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class LiveCopyPasteControls extends Tab_Base {

    public function get_id() {
        return 'live-copy-paste';
    }

    public function get_title() {
        return __('Live Copy Paste', 'live-copy-paste');
    }

    public function get_icon() {
        return 'eicon-clone';
    }

    protected function register_tab_controls() {
        $this->start_controls_section(
            'live_copy_paste_settings',
            [
                'label' => esc_html__('Settings', 'live-copy-paste'),
                'tab'   => 'live-copy-paste',
            ]
        );
        $this->start_controls_tabs(
            'tabs_live_copy_page_settings'
        );
        $this->start_controls_tab(
            'tab_settings_editor',
            [
                'label' => __('EDITOR', 'live-copy-paste'),
            ]
        );
        $this->add_control(
            'enable_copy_paste_btn',
            [
                'label'     => __('Enable Copy Paste', 'live-copy-paste'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __('Yes', 'live-copy-paste'),
                'label_off' => __('No', 'live-copy-paste'),
                'default'   => 'yes',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_settings_frontend',
            [
                'label' => __('FRONTEND', 'live-copy-paste'),
            ]
        );
        $this->add_control(
            'enable_magic_copy',
            [
                'label'     => __('Enable Magic Copy', 'live-copy-paste'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __('Yes', 'live-copy-paste'),
                'label_off' => __('No', 'live-copy-paste'),
                'default'   => 'no',
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'magic_button_login_users',
            [
                'label'         => __('Only for logged-in Users', 'live-copy-paste'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'live-copy-paste'),
                'label_off'     => __('No', 'live-copy-paste'),
                'return_value'  => 'yes',
                'default'       => 'no',
                'condition' => [
                    'enable_magic_copy' => 'yes'
                ],
            ]
        );
        $this->add_control(
            'magic_button_specific_section',
            [
                'label'         => __('Only for Specific Section', 'live-copy-paste'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Yes', 'live-copy-paste'),
                'label_off'     => __('No', 'live-copy-paste'),
                'description'   => __('After turn on this switcher, you must need to reload this page then go to the advanced tab of elementor section/container then you found a section called live copy paste, here you can select where you want to show this magic button', 'live-copy-paste'),
                'return_value'  => 'yes',
                'default'       => 'no',
                'condition' => [
                    'enable_magic_copy' => 'yes'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }
}
