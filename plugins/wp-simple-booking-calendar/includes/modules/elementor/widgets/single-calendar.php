<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Single Calendar Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_WPSBC_Single_Calendar_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve Single Calendar widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'Single Calendar';
    }

    /**
     * Get widget title.
     *
     * Retrieve Single Calendar widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('WP Simple Booking Calendar - Single Calendar', 'wp-simple-booking-calendar');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Single Calendar widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-calendar';
    }

    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url()
    {
        return 'https://www.wpsimplebookingcalendar.com/';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Single Calendar widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['wp-simple-booking-calendar'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the Single Calendar widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['Single', 'Calendar', 'Booking', 'WP Simple Booking Calendar', 'wpsimplebookingcalendar'];
    }

    /**
     * Register Single Calendar widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'calendar',
            [
                'label' => esc_html__('Calendar', 'wp-simple-booking-calendar'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $calendars = wpsbc_get_calendars(array('status' => 'active'));
        $calendarDropdown = array('0' => '-');
        foreach ($calendars as $calendar) {
            $calendarDropdown[$calendar->get('id')] = $calendar->get('name');
        }

        $this->add_control(
            'calendar_id',
            [
                'label' => esc_html__('Calendar', 'wp-simple-booking-calendar'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $calendarDropdown,
                'default' => '0',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'calendar_options',
            [
                'label' => esc_html__('Calendar Options', 'wp-simple-booking-calendar'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Display Calendar Title', 'wp-simple-booking-calendar'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-simple-booking-calendar'),
                    'no' => __('No', 'wp-simple-booking-calendar'),
                ),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'legend',
            [
                'label' => esc_html__('Display Legend', 'wp-simple-booking-calendar'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'yes' => __('Yes', 'wp-simple-booking-calendar'),
                    'no' => __('No', 'wp-simple-booking-calendar'),
                ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'legend_position',
            [
                'label' => esc_html__('Legend Position', 'wp-simple-booking-calendar'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'side' => __('Side', 'wp-simple-booking-calendar'),
                    'top' => __('Top', 'wp-simple-booking-calendar'),
                    'bottom' => __('Bottom', 'wp-simple-booking-calendar'),
                ),
                'default' => 'side',
            ]
        );

      


        $settings = get_option('wpsbc_settings');
        $languages = wpsbc_get_languages();
        $languagesDropdown = array('auto' => 'Auto');

        if (!empty($settings['active_languages'])) {

            foreach ($settings['active_languages'] as $key => $code) {

                if (empty($languages[$code])) {
                    continue;
                }

                $languagesDropdown[$code] = $languages[$code];

            }

        }

        $this->add_control(
            'language',
            [
                'label' => esc_html__('Language', 'wp-simple-booking-calendar'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $languagesDropdown,
                'default' => 'auto',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render Single Calendar widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        if (empty($settings['calendar_id']) || $settings['calendar_id'] == '0') {
            echo __("Please select a calendar to display", 'wp-simple-booking-calendar');
            return;
        }

        $settings['id'] = $settings['calendar_id'];

        echo WPSBC_Shortcodes::single_calendar($settings);

    }

}
