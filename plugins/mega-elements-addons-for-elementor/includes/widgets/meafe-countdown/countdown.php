<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Box_Shadow;

class MEAFE_Countdown extends Widget_Base
{

    public function get_name() {
        return 'meafe-countdown';
    }

    public function get_title() {
        return esc_html__( 'Countdown Timer', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-countdown';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-countdown'];
    }

    public function get_script_depends() {
        return [ 
            'jquery-countdown',
            'meafe-countdown',
        ];
    }

    protected function register_controls() 
    {
        /**
         * Countdown General Settings
         */ 
        $this->start_controls_section(
            'meafe_countdown_content_general_settings',
            [
                'label'     => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bccgs_countdown_date_time',
            [
                'label'         => esc_html__( 'Due Date', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DATE_TIME,
                'picker_options'    => [
                    'format' => 'Ym/d H:m:s'
                ],
                'default'       => date( "Y/m/d H:m:s", strtotime("+ 1 Day") ),
                'description'   => esc_html__( 'Date format is (yyyy/mm/dd). Time format is (hh:mm:ss). Example: 2020-01-01 09:30.', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bccgs_countdown_s_u_time',
            [
                'label'         => esc_html__( 'Time Zone', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'wp-time'           => __('WordPress Default', 'mega-elements-addons-for-elementor' ),
                    'user-time'         => __('User Local Time', 'mega-elements-addons-for-elementor' )
                ],
                'default'       => 'wp-time',
                'description'   => __('This will set the current time of the option that you will choose.', 'mega-elements-addons-for-elementor')
            ]
        );

        $this->add_control(
            'bccgs_countdown_units',
            [
                'label'         => esc_html__( 'Time Units', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description' => __('Select the time units that you want to display in countdown timer.', 'mega-elements-addons-for-elementor' ),
                'options'       => [
                    'Y'     => esc_html__( 'Years', 'mega-elements-addons-for-elementor' ),
                    'O'     => esc_html__( 'Month', 'mega-elements-addons-for-elementor' ),
                    'W'     => esc_html__( 'Week', 'mega-elements-addons-for-elementor' ),
                    'D'     => esc_html__( 'Day', 'mega-elements-addons-for-elementor' ),
                    'H'     => esc_html__( 'Hours', 'mega-elements-addons-for-elementor' ),
                    'M'     => esc_html__( 'Minutes', 'mega-elements-addons-for-elementor' ),
                    'S'     => esc_html__( 'Second', 'mega-elements-addons-for-elementor' ),
                ],
                'default'       => [ 'D', 'H', 'M', 'S' ],
                'multiple'      => true,
                'separator'     => 'after'
            ]
        );
        
        $this->add_control(
            'bccgs_countdown_separator',
            [
                'label'         => __( 'Digits Separator', 'mega-elements-addons-for-elementor' ),
                'description'   => __( 'Enable or disable digits separator', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control(
            'bccgs_countdown_separator_text',
            [
                'label'         => __( 'Separator Text', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'condition'     => [
                    'bccgs_countdown_separator' => 'yes'
                ],
                'default'       => ':'
            ]
        );

        $this->end_controls_section();

        /**
         * Countdown Expire Settings
         */ 
        $this->start_controls_section(
            'meafe_countdown_content_expire_settings',
            [
                'label'     => esc_html__( 'Expire Settings', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bcces_countdown_expire_text_url',
            [
                'label'         => esc_html__( 'Action After Expire', 'mega-elements-addons-for-elementor' ),
                'label_block'   => false,
                'type'          => Controls_Manager::SELECT,
                'description'   => esc_html__( 'Choose whether if you want to set a message or not', 'mega-elements-addons-for-elementor' ),
                'options'       => [
                    'none'      => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                    'text'      => esc_html__( 'Message', 'mega-elements-addons-for-elementor' ),
                ],
                'default'       => 'none'
            ]
        );

        $this->add_control(
            'bcces_countdown_expiry_text_',
            [
                'label'         => esc_html__( 'On expiry Text', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
                'default'       => esc_html__( 'Countdown is finished!', 'mega-elements-addons-for-elementor' ),
                'condition'     => [
                    'bcces_countdown_expire_text_url' => 'text'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Countdown Text Settings
         */ 
        $this->start_controls_section(
            'meafe_countdown_content_text_settings',
            [
                'label'     => esc_html__( 'Text Settings', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bccts_countdown_day_text',
            [
                'label'         => esc_html__( 'Days', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Days'
            ]
        );

        $this->add_control(
            'bccts_countdown_week_text',
            [
                'label'         => esc_html__( 'Weeks', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Weeks'
            ]
        );

        $this->add_control(
            'bccts_countdown_month_text',
            [
                'label'         => esc_html__( 'Months', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Months'
            ]
        );

        $this->add_control(
            'bccts_countdown_year_text',
            [
                'label'         => esc_html__( 'Years', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Years'
            ]
        );

        $this->add_control(
            'bccts_countdown_hour_text',
            [
                'label'         => esc_html__( 'Hours', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Hours'
            ]
        );

        $this->add_control(
            'bccts_countdown_minute_text',
            [
                'label'         => esc_html__( 'Minutes', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Minutes'
            ]
        );

        $this->add_control(
            'bccts_countdown_second_text',
            [
                'label'         => esc_html__( 'Seconds', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Seconds'
            ]
        );

        $this->end_controls_section();

        /**
         * Countdown Block Style
         */ 
        $this->start_controls_section(
            'meafe_countdown_style_block_style',
            [
                'label'     => esc_html__( 'Block Style' , 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'bcsbs_countdown_block_backcolor',
            [
                'label'         => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'bcsbs_countdown_block_shadow',
                'selector'      => '{{WRAPPER}} .meafe-countdown .pre_countdown-section',
            ]
        );

        $this->add_control(
            'bcsbs_countdown_block_border_radius',
            [
                'label'         => __('Border Radius', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'bcsbs_countdown_block_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsbs_countdown_block_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Countdown Digits Style
         */ 
        $this->start_controls_section(
            'meafe_countdown_style_digits_style',
            [
                'label'     => esc_html__( 'Digits Style' , 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'bcsds_countdown_digit_color',
            [
                'label'         => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section .pre_countdown-amount' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcsds_countdown_digit_typo',
                'selector'  => '{{WRAPPER}} .meafe-countdown .pre_countdown-section .pre_countdown-amount',
                'separator' => 'after'
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Countdown Units Style
         */ 
        $this->start_controls_section(
            'meafe_countdown_style_units_style',
            [
                'label'     => esc_html__( 'Units Style' , 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'bcsus_countdown_unit_color',
            [
                'label'         => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section .pre_countdown-period' => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcsus_countdown_unit_typo',
                'selector'  => '{{WRAPPER}} .meafe-countdown .pre_countdown-section .pre_countdown-period',
            ]
        );

        $this->add_responsive_control(
            'bcsus_countdown_separator_width',
            [
                'label'         => esc_html__( 'Spacing in Between', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size' => 40,
                ],
                'range'         => [
                    'px'    => [
                        'min' => 0,
                        'max' => 200,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-countdown .pre_countdown-section' => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2 ); margin-left: calc( {{SIZE}}{{UNIT}} / 2 )'
                ],
                'condition'     => [
                    'bccgs_countdown_separator!' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();
        
        /**
         * Countdown Separator Style
         */ 
        $this->start_controls_section(
            'meafe_countdown_style_separator_style', 
            [
                'label'         => esc_html__( 'Separator', 'mega-elements-addons-for-elementor' ),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'bccgs_countdown_separator' => 'yes'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'bcsss_countdown_separator_size',
            [
                'label'         => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .pre-countdown_separator' => 'font-size: {{SIZE}}px'
                ]
            ]
        );

        $this->add_control(
            'bcsss_countdown_separator_color',
            [
                'label'         => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .pre-countdown_separator' => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'bcsss_countdown_separator_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .pre-countdown_separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->end_controls_section();
        
    }

    protected function render( ) {
        
        $settings = $this->get_settings_for_display();

        $target_date = str_replace('-', '/', $settings['bccgs_countdown_date_time'] );
        
        $formats = $settings['bccgs_countdown_units'];
        $format = implode('', $formats );
        $time = str_replace('-', '/', current_time('mysql') );
        
        if( $settings['bccgs_countdown_s_u_time'] == 'wp-time' ) : 
            $sent_time = $time;
        else:
            $sent_time = '';
        endif;

        // Plural labels set up
        $ys = !empty( $settings['bccts_countdown_year_text'] ) ? $settings['bccts_countdown_year_text'] : 'Years';
        $ms = !empty( $settings['bccts_countdown_month_text'] ) ? $settings['bccts_countdown_month_text'] : 'Months';
        $ws = !empty( $settings['bccts_countdown_week_text'] ) ? $settings['bccts_countdown_week_text'] : 'Weeks';
        $ds = !empty( $settings['bccts_countdown_day_text'] ) ? $settings['bccts_countdown_day_text'] : 'Days';
        $hs = !empty( $settings['bccts_countdown_hour_text'] ) ? $settings['bccts_countdown_hour_text'] : 'Hours';
        $mis = !empty( $settings['bccts_countdown_minute_text'] ) ? $settings['bccts_countdown_minute_text'] : 'Minutes';
        $ss = !empty( $settings['bccts_countdown_second_text'] ) ? $settings['bccts_countdown_second_text'] : 'Seconds';
        $labels1 = $ys."," . $ms ."," . $ws ."," . $ds ."," . $hs ."," . $mis ."," . $ss;
        
        $expire_text = $settings['bcces_countdown_expiry_text_'];
        
        if( $settings['bcces_countdown_expire_text_url'] == 'none' ){
            $event = 'None';
            $text = '';
        }

        if( $settings['bcces_countdown_expire_text_url'] == 'text' ){
            $event = 'onExpiry';
            $text = $expire_text;
        }
        
        $separator_text = ! empty ( $settings['bccgs_countdown_separator_text'] ) ? $settings['bccgs_countdown_separator_text'] : '';
        
        $countdown_settings = [
            'label'    => $labels1,
            'until'     => $target_date,
            'format'    => $format,
            'event'     => $event,
            'text'      => $text,
            'serverSync'=> $sent_time,
            'separator' => $separator_text
        ];

        $due_target_date    = date( strtotime( $target_date ) );
        $due_sent_date      = date( strtotime( $sent_time ) );
        
        if( $due_sent_date > $due_target_date ) return false; ?>
            
        <div id="countDownContiner-<?php echo esc_attr( $this->get_id() ); ?>" class="meafe-countdown-main meafe-countdown-separator-<?php echo esc_attr( $settings['bccgs_countdown_separator'] ); ?>" data-settings='<?php echo wp_json_encode( $countdown_settings ); ?>'>
            <div id="countdown-<?php echo esc_attr( $this->get_id() ); ?>" class="meafe-countdown-init meafe-countdown down"></div>
        </div>
        <?php
    }

    protected function content_template() {
    }
}