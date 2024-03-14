<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}


use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Repeater;

class MEAFE_Image_Card  extends Widget_Base
{
    public function get_name() {
        return 'meafe-image-card';
    }

    public function get_title() {
        return esc_html__( 'Image Card', 'mega-elements-addons-for-elementor' );
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_icon() {
        return ['meafe-image-card'];
    }

    public function get_style_depends() {
        return ['meafe-image-card'];
    }

    protected function register_controls()
    {
        /**
         * Timeline General Settings
        */
        $this->start_controls_section(
            'meafe_image_card_content_general_settings',
            [
                'label'     => __( 'General Settings', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'IC_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'one',
                'label_block'   => false,
                'options'       => [
                    'one'   => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    'two'   => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                ],
                'frontend_available' => true,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'IC_image',
            [
                'label'       => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'IC_title',
            [
                'label'       => esc_html__( 'Image Card Title', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'IC_content',
            [
                'label'       => esc_html__( 'Timeline Content', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'IC_show_load_more',
            [
                'label'     => esc_html__( 'Show Learn More', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );

        $repeater->add_control(
            'IC_show_load_more_text',
            [
                'label'     => esc_html__( 'Learn More Text', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => false,
                'default'   => esc_html__( 'Learn More', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'IC_show_load_more' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'IC_show_load_more_url',
            [
                'label'     => esc_html__( 'Learn More URL', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => false,
                'default'   => esc_html__( '#', 'mega-elements-addons-for-elementor' ),
                'condition' => [
                    'IC_show_load_more' => 'yes',
                    'IC_show_load_more_text!' => '',
                ],
            ]
        );

        $this->add_control( 
            'IC_items', 
            array(
                'label'       => esc_html__( 'Image Cards', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array( 
                    array(
                        'IC_image'         => ['url' => Utils::get_placeholder_image_src() ],
                        'IC_title'         => esc_html__( 'Title One', 'mega-elements-addons-for-elementor' ),
                        'IC_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
                    ),
                    array(
                        'IC_image'         => ['url' => Utils::get_placeholder_image_src() ],
                        'IC_title'         => esc_html__( 'Title Two', 'mega-elements-addons-for-elementor' ),
                        'IC_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
                    ),
                    array(
                        'IC_image'         => ['url' => Utils::get_placeholder_image_src() ],
                        'IC_title'         => esc_html__( 'Title Three', 'mega-elements-addons-for-elementor' ),
                        'IC_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor' ),
                    ), 
                ),                
                'title_field' => '{{{ IC_title }}}',
            ) 
        );

        $this->end_controls_section();

        /**
         * Timeline General Style
        */
        $this->start_controls_section(
            'meafe_image_card_style_general_style',
            [
                'label'     => __( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'IC_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Image Card Title', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'IC_title_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'IC_title_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'IC_title_color',
            [
                'label'     => __( 'Title Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'IC_title_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-title' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-image-card-title:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'IC_title_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-image-card-title',
            ]
        );

        $this->add_control(
            'IC_content_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__( 'Image Card Content', 'mega-elements-addons-for-elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'IC_content_padding',
            [
                'label'     => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'IC_content_spacing',
            [
                'label'     => __( 'Bottom Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'IC_content_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'IC_content_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-image-card-content:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'IC_content_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-image-card-content',
            ]
        );

		$this->end_controls_section();

        /**
         * Learn More Button Style
         */
        $this->load_more_button_style();
    }         
    
    protected function load_more_button_style()
    {
        /**
         * Blog Learn More Button Style
        */ 
        $this->start_controls_section(
            'meafe_image_card_style_load_more_style',
            [
                'label'     => esc_html__( 'Learn More Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'IC_load_more_btn_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'IC_load_more_btn_margin',
            [
                'label'     => esc_html__('Margin', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'IC_load_more_btn_typography',
                'selector'  => '{{WRAPPER}} .meafe-image-card-learn-more',
            ]
        );

        $this->start_controls_tabs( 'IC_load_more_btn_tabs' );

        // Normal State Tab
        $this->start_controls_tab(
            'IC_load_more_btn_normal', 
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'IC_load_more_btn_normal_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'IC_load_more_btn_normal_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'IC_load_more_btn_normal_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-image-card-learn-more',
            ]
        );

        $this->add_control(
            'IC_load_more_btn_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more' => 'border-radius: {{SIZE}}px',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'IC_load_more_btn_shadow',
                'selector'  => '{{WRAPPER}} .meafe-image-card-learn-more',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab(
            'IC_load_more_btn_hover', 
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ) 
            ] 
        );

        $this->add_control(
            'IC_load_more_btn_hover_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'IC_load_more_btn_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more:hover' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'IC_load_more_btn_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more:hover' => 'border-color: {{VALUE}}',
                ],
            ]

        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'IC_load_more_btn_hover_shadow',
                'selector'  => '{{WRAPPER}} .meafe-image-card-learn-more:hover',
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'IC_loadmore_button_alignment',
            [
                'label'     => esc_html__( 'Button Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title'     => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-left',
                    ],
                    'center'        => [
                        'title'     => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-center',
                    ],
                    'right'      => [
                        'title'     => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'      => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .meafe-image-card-learn-more-wrap' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render() {
        $settings  = $this->get_settings_for_display();
        $layout    = $settings['IC_layouts'];

        $this->add_render_attribute( 'icon', 'class', 'meafe-image-card-icon' );
        $this->add_render_attribute( 'IC_title', 'class', 'meafe-image-card-title' );
        $this->add_render_attribute( 'IC_content', 'class', 'meafe-image-card-content' );
        ?>
        <div id="<?php echo esc_attr( $this->get_id() ); ?>" class="meafe-image-card-main layout-<?php echo esc_attr($layout); ?> center-aligned-content">
            <div class="meafe-image-card-wrap">
                <?php 
                    foreach ( $settings[ 'IC_items'] as $index => $imagecard ) { ?>
                        <div class="meafe-image-card-inner-wrap">
                            <div class="meafe-image-card-image-wrap">
                                <?php if ( ( $imagecard['IC_image']['id'] || $imagecard['IC_image']['url']) ){ ?>
                                    <div class="meafe-image-card-reviewer-thumb">
                                    <?php echo Group_Control_Image_Size::get_attachment_image_html( $imagecard, 'full', 'IC_image' ); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="meafe-image-card-content-wrapper">
                                <div class="meafe-image-card-meta-wrap">
                                    <?php 
                                    if( $imagecard['IC_title'] ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'IC_title' ); ?>>
                                            <?php echo esc_html($imagecard['IC_title']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="meafe-image-card-meta-wrap-hover">
                                    <?php 
                                    if( $imagecard['IC_title'] ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'IC_title' ); ?>>
                                            <?php echo esc_html($imagecard['IC_title']); ?>
                                        </div>
                                    <?php endif; 
                                    
                                    if( $imagecard['IC_content'] ) : ?>
                                        <div <?php $this->print_render_attribute_string( 'IC_content' ); ?>>
                                            <?php echo wp_kses_post($imagecard['IC_content']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ( 'yes' == $imagecard['IC_show_load_more'] && $imagecard['IC_show_load_more_text'] && $imagecard['IC_show_load_more_url']  ) {
                                    echo '<div class="meafe-image-card-learn-more-wrap">
                                        <a href="' . esc_url( $imagecard['IC_show_load_more_url'] ) . '" class="meafe-image-card-learn-more">' . esc_html($imagecard['IC_show_load_more_text']) . '</a>
                                    </div>';
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
            </div>
        </div>
    <?php }

    protected function content_template(){

    }

}