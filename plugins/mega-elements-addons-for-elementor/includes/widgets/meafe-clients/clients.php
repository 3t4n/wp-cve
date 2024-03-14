<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;

class MEAFE_Clients extends Widget_Base
{

    public function get_name() {
        return 'meafe-clients';
    }

    public function get_title() {
        return esc_html__( 'Clients Logo', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-client';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-clients'];
    }
    
    public function get_script_depends() {
        return ['meafe-clients'];
    }

    public function get_grid_classes( $settings, $columns_field = 'bccgs_clients_per_line' ) {
        
        $grid_classes = ' meafe-grid-desktop-';
        $grid_classes .= $settings[$columns_field];
        $grid_classes .= ' meafe-grid-tablet-';
        $grid_classes .= $settings[$columns_field . '_tablet'];
        $grid_classes .= ' meafe-grid-mobile-';
        $grid_classes .= $settings[$columns_field . '_mobile'];

        return apply_filters( 'meafe_grid_classes', esc_attr($grid_classes), $settings, $columns_field );
    }

    protected function register_controls() 
    {
        /**
         * Clients General Settings
        */
        $this->start_controls_section(
            'meafe_clients_content_general_settings',
            [
                'label'     => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_responsive_control(
            'bccgs_clients_per_line',
            [
                'label'     => esc_html__( 'Columns per row', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_show_carousel',
            [
                'label'     => esc_html__( 'Enable Carousel', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_show_carousel_nav',
            [
                'label'     => esc_html__( 'Enable Carousel Navigation', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'condition' => [
                    'bccgs_clients_show_carousel' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_show_carousel_dots',
            [
                'label'     => esc_html__( 'Enable Carousel Dots', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'condition' => [
                    'bccgs_clients_show_carousel' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_show_carousel_auto',
            [
                'label'     => esc_html__( 'Enable Carousel AutoPlay', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'condition' => [
                    'bccgs_clients_show_carousel' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_carousel_autoplay_speed',
            [
                'label'     => __( 'Autoplay Speed', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 100,
                'step'      => 100,
                'max'       => 10000,
                'default'   => 3000,
                'description' => __( 'Autoplay speed in milliseconds', 'mega-elements-addons-for-elementor' ),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_show_carousel_loop',
            [
                'label'     => esc_html__( 'Enable Carousel Infinite Loop', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'condition' => [
                    'bccgs_clients_show_carousel' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'bccgs_clients_show_black_and_white',
            [
                'label'     => esc_html__( 'Show in Black/White', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
                'prefix_class'  => 'meafe-clients-bw'
            ]
        );

        $clients_repeater = new Repeater();

        $clients_repeater->add_control(
            'bccgs_client_image',
            [
                'label'         => esc_html__( 'Client Logo/Image', 'mega-elements-addons-for-elementor' ),
                'description'   => esc_html__( 'The logo image for the client/customer.', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url'           => Utils::get_placeholder_image_src(),
                ],
                'label_block'   => true,
                'dynamic'       => [
                    'active'        => true,
                ],
            ]
        );

        $clients_repeater->add_control(
            'bccgs_client_link',
            [
                'label'         => esc_html__( 'Client URL', 'mega-elements-addons-for-elementor' ),
                'description'   => esc_html__( 'The website of the client/customer.', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::URL,
                'label_block'   => true,
                'default'       => [
                    'url'           => '',
                    'is_external'   => 'true',
                ],
                'placeholder'   => esc_html__( 'http://', 'mega-elements-addons-for-elementor'),
                'dynamic'       => [
                    'active'        => true,
                ],
            ]
        );


        $this->add_control(
            'bccgs_clients_repeater',
            [
                'label'       => esc_html__( 'Clients Logo', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $clients_repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

        /**
         * Clients General Style
        */
        $this->start_controls_section(
            'meafe_clients_content_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'bccgs_client_heading_image',
            [
                'label'     => esc_html__( 'Client Images', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bccgs_client_max_width',
            [
                'label'         => esc_html__( 'Max Width', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-clients .meafe-client .meafe-image' => 'max-width: {{SIZE}}%'
                ]
            ]
        );

        $this->add_responsive_control(
            'bccgs_client_thumbnail_hover_opacity',
            [
                'label'     => esc_html__( 'Logo Hover Opacity (%)', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size'      => 0.7,
                ],
                'range'     => [
                    'px'        => [
                        'max'   => 1,
                        'min'   => 0.10,
                        'step'  => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-clients .meafe-client:hover .meafe-image' => 'opacity: {{SIZE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bccgs_client_padding',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'description'   => esc_html__( 'Padding for the Client Logo images.', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', '%', 'em' ],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-clients .meafe-client' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-clients .meafe-grid-container.no-swiper' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $nav       = $settings['bccgs_clients_show_carousel_nav'];
        $dots      = $settings['bccgs_clients_show_carousel_dots'];
        ?>
        <div id=<?php echo esc_attr( $widget_id ); ?> class="meafe-clients meafe-gapless-grid">
            <div class="meafe-grid-container<?php echo $this->get_grid_classes( $settings ); ?> <?php if ( $settings['bccgs_clients_show_carousel'] == false ) echo 'no-swiper'; ?>">
                <?php if ( $settings['bccgs_clients_show_carousel'] == true ) echo '<div class="swiper-container"><div class="swiper-wrapper">'; ?>
                    <?php 
                    foreach ( $settings['bccgs_clients_repeater'] as $client ) : ?>
                        <div class="meafe-grid-item meafe-client <?php if ( $settings['bccgs_clients_show_carousel'] == true ) echo esc_attr('swiper-slide'); ?>">
                            <?php
                            if ( !empty( $client['bccgs_client_link'] ) && !empty( $client['bccgs_client_link']['url'] ) ) :
                                $target = $client['bccgs_client_link']['is_external'] ? ' target="_blank"' : '';
                                echo '<a href="' . esc_url( $client['bccgs_client_link']['url'] ) . '"' . $target . '>';
                            endif;
                            if ( !empty( $client['bccgs_client_image'] ) ) :
                                echo wp_get_attachment_image( $client['bccgs_client_image']['id'], 'full', false, array( 'class' => 'meafe-image full' ) );
                            endif;
                            if( !empty( $client['bccgs_client_link'] ) && !empty( $client['bccgs_client_link']['url'] ) ) :
                                echo '</a>';
                            endif; ?>
                        </div><!-- .meafe-client -->
                    <?php endforeach; ?>
                <?php if ( $settings['bccgs_clients_show_carousel'] == true ) echo '</div></div>'; ?>

                <?php if($dots === 'yes') { ?>
                    <!-- If we need pagination -->
                    <div class="clients meafa-swiper-pagination"></div>
                <?php }
                
                if($nav === 'yes') { ?>
                    <!-- If we need navigation buttons -->
                    <div class="meafa-navigation-wrap">
                        <div class="clients meafa-navigation-prev nav"><?php echo esc_html('<'); ?></div>
                        <div class="clients meafa-navigation-next nav"><?php echo esc_html('>'); ?></div>
                    </div>
                <?php } ?>
            </div>
        </div><!-- .meafe-clients -->
        <?php
    }

    protected function content_template() { 
    }
}
