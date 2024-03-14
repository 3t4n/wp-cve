<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
use Elementor\Utils;

class MEAFE_Counter extends Widget_Base
{

    public function get_name() {
        return 'meafe-counter';
    }

    public function get_title() {
        return esc_html__( 'Counter', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-counter';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-counter'];
    }

    public function get_script_depends() {
        return [
            'jquery-numerator',
            'elementor-waypoints',
            'meafe-counter',
        ];
    }

    protected function register_controls() 
    {    
        /**
         * Counter General Settings
         */
        $this->start_controls_section(
            'meafe_counter_content_general_settings',
            [
                'label'         => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' )
            ]
        );
        
        $this->add_control(
            'bccgs_counter_title',
            [
                'label'         => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => esc_html__( 'Enter title for stats counter block', 'mega-elements-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'bccgs_counter_start_value',
            [
                'label'         => esc_html__( 'Starting Number', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 0
            ]
        );
        
        $this->add_control(
            'bccgs_counter_end_value',
            [
                'label'         => esc_html__( 'Ending Number', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 500
            ]
        );

        $this->add_control(
            'bccgs_counter_t_separator',
            [
                'label'         => esc_html__( 'Thousands Separator', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => esc_html__( 'Separator converts 100000 into 100,000', 'mega-elements-addons-for-elementor' ),
                'default'       => ','
            ]
        );

        $this->add_control(
            'bccgs_counter_d_after',
            [
                'label'         => esc_html__( 'Digits After Decimal Point', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 0
            ]
        );

        $this->add_control(
            'bccgs_counter_preffix',
            [
                'label'         => esc_html__( 'Add Prefix', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => esc_html__( 'Enter prefix for counter value', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bccgs_counter_suffix',
            [
                'label'         => esc_html__( 'Add suffix', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => esc_html__( 'Enter suffix for counter value', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bccgs_counter_speed',
            [
                'label'         => esc_html__( 'Rolling Time', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'description'   => esc_html__( 'How long should it take to complete the digit?', 'mega-elements-addons-for-elementor' ),
                'default'       => 3
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Counter Display Settings
         */
        $this->start_controls_section( 
            'meafe_counter_content_display_settings',
            [
                'label'         => esc_html__( 'Display Settings', 'mega-elements-addons-for-elementor' )
            ]
        );

        $this->add_control(
            'bccds_counter_icon_image',
            [
                'label'         => esc_html__( 'Icon Type', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'description'   => esc_html__('Use a font awesome icon or upload a custom image', 'mega-elements-addons-for-elementor'),
                'options'       => [
                    'icon'  => esc_html__( 'Font Awesome', 'mega-elements-addons-for-elementor' ),
                    'custom'=> esc_html__( 'Custom Image', 'mega-elements-addons-for-elementor' )
                ],
                'default'       => 'icon'
            ]
        );

        $this->add_control(
            'bccds_counter_icon_updated',
            [
                'label'         => esc_html__( 'Select an Icon', 'mega-elements-addons-for-elementor' ),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'bccds_counter_icon',
                'default' => [
                    'value'     => 'fas fa-clock',
                    'library'   => 'fa-solid',
                ],
                'condition'     => [
                    'bccds_counter_icon_image' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'bccds_counter_image_upload',
            [
                'label'         => esc_html__( 'Upload Image', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'condition'         => [
                    'bccds_counter_icon_image' => 'custom'
                ],
                'default'       => [
                    'url' => Utils::get_placeholder_image_src(),
                ]
            ]
        );
        
        $this->add_control(
            'bccds_counter_icon_position',
            [
                'label'         => esc_html__( 'Icon Position', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'description'   => esc_html__( 'Choose a position for your icon', 'mega-elements-addons-for-elementor'),
                'options'       => [
                    'left'  => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                    'right' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                    'top'   => esc_html__( 'Top', 'mega-elements-addons-for-elementor' ),
                    
                ],
                'default'       => 'top',
                'separator'     => 'after'
            ]
        );
        
        $this->add_control(
            'bccds_counter_icon_animation', 
            [
                'label'         => esc_html__( 'Animations', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::ANIMATION,
                'render_type'   => 'template'
            ]
            );
        
        
        $this->end_controls_section();
        
        /**
         * Counter Title Style
         */        
        $this->start_controls_section(
            'meafe_counter_style_title_style',
            [
                'label'         => esc_html__( 'Title Style' , 'mega-elements-addons-for-elementor' ),
                'tab'           => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'bcsts_counter_title_color',
            [
                'label'         => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-title' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'bcsts_counter_title_typho',
                'selector'      => '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .meafe-counter-title, {{WRAPPER}} .meafe-counter-area .meafe-counter-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'bcsts_counter_title_shadow',
                'selector'      => '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .meafe-counter-title, {{WRAPPER}} .meafe-counter-area .meafe-counter-title',
            ]
        );

        $this->add_responsive_control(
            'bcsts_counter_title_space',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .meafe-counter-title, {{WRAPPER}} .meafe-counter-area .meafe-counter-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Counter Value Style
         */
        $this->start_controls_section(
            'meafe_counter_style_value_style',
            [
                'label'         => esc_html__( 'Value Style', 'mega-elements-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );
        
        $this->add_control(
            'bcsvs_counter_value_color',
            [
                'label'         => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-init' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'bcsvs_counter_value_typho',
                'selector'      => '{{WRAPPER}} .meafe-counter-area .meafe-counter-init',
                'separator'     => 'after'
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Counter Icon Style
         */
        $this->start_controls_section(
            'meafe_counter_style_icon_style',
            [
                'label'         => esc_html__( 'Icon Style' , 'mega-elements-addons-for-elementor' ),
                'tab'           => Controls_Manager::TAB_STYLE
            ]
        );
        
        $this->add_control(
            'bcsis_counter_icon_color',
            [
                'label'         => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .icon' => 'color: {{VALUE}}; fill: {{VALUE}}'
                ],
                'condition'     => [
                    'bccds_counter_icon_image' => 'icon'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'bcsis_counter_icon_size',
            [
                'label'         => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 70,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .icon' => 'font-size: {{SIZE}}px'
                ],
                'condition'     => [
                    'bccds_counter_icon_image' => 'icon'
                ]
            ]
        );

        $this->add_responsive_control(
            'bcsis_counter_image_size',
            [
                'label'         => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 60,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon img.custom-image' => 'width: {{SIZE}}px'
                ],
                'condition'     => [
                    'bccds_counter_icon_image' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'bcsis_counter_icon_style',
            [
                'label'         => esc_html__( 'Style', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'description'   => esc_html__('We are giving you three quick preset if you are in a hurry. Otherwise, create your own with various options', 'mega-elements-addons-for-elementor'),
                'options'       => [
                    'simple'=> esc_html__( 'Simple', 'mega-elements-addons-for-elementor' ),
                    'circle'=> esc_html__( 'Circle Background', 'mega-elements-addons-for-elementor' ),
                    'square'=> esc_html__( 'Square Background', 'mega-elements-addons-for-elementor' ),
                    'design'=> esc_html__( 'Design Your Own', 'mega-elements-addons-for-elementor' )
                ],
                'default'       => 'simple'
            ]
        );

        $this->add_control(
            'bcsis_counter_icon_bg',
            [
                'label'         => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'condition'     => [
                    'bcsis_counter_icon_style!' => 'simple'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .icon-bg' => 'background: {{VALUE}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'bcsis_counter_icon_bg_size',
            [
                'label'         => esc_html__( 'Background size', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size' => 150,
                ],
                'range'         => [
                    'px' => [
                        'min' => 1,
                        'max' => 600,
                    ]
                ],
                'condition'     => [
                    'bcsis_counter_icon_style!' => 'simple'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon span.icon' => 'width: {{SIZE}}px; height: {{SIZE}}px'
                ]
            ]
        );

        $this->add_responsive_control(
            'bcsis_counter_icon_v_align',
            [
                'label'         => esc_html__( 'Vertical Alignment', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 150,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 600,
                    ]
                ],
                'condition'     => [
                    'bcsis_counter_icon_style!' => 'simple'
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon span.icon, {{WRAPPER}} .meafe-counter-area .meafe-counter-icon span.icon i' => 'line-height: {{SIZE}}px'
                ]
            ]
        );
        
        
        $this->add_group_control(
        Group_Control_Border::get_type(),
            [
                'name'          => 'bcsis_counter_icon_border',
                'selector'      => '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .design',
                'condition'     => [
                    'bcsis_counter_icon_style' => 'design'
                ]
            ]
        );

        $this->add_control(
            'bcsis_counter_icon_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> ['px', '%' ,'em'],
                'default'   => [
                    'unit'      => 'px',
                    'size'      => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon .design' => 'border-radius: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'bcsis_counter_icon_style' => 'design'
                ]
            ]
        );

        $this->add_responsive_control(
            'bcsis_counter_title_space',
            [
                'label' => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-counter-area .meafe-counter-icon' => 'margin-bottom: {{SIZE}}px',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Counter Prefix & Suffix Style
         */
        $this->start_controls_section(
            'meafe_counter_style_suffix_prefix_style',
            [
                'label'         => esc_html__( 'Prefix & Suffix', 'mega-elements-addons-for-elementor' ),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );
        
        $this->add_control(
            'bcssps_counter_prefix_color',
            [
                'label'         => esc_html__( 'Prefix Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-counter-area span#prefix' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'bcssps_counter_prefix_typo',
                'selector'      => '{{WRAPPER}} .meafe-counter-area span#prefix',
                'separator'     => 'after',
            ]
        );

        $this->add_control(
            'bcssps_counter_suffix_color',
            [
                'label'         => esc_html__( 'Suffix Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-counter-area span#suffix' => 'color: {{VALUE}}'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'bcssps_counter_suffix_typo',
                'selector'      => '{{WRAPPER}} .meafe-counter-area span#suffix',
                'separator'     => 'after',
            ]
        );

        $this->end_controls_section();

    }

    public function get_counter_content( $settings, $direction ) {

        $start_value = $settings['bccgs_counter_start_value']; ?>
    
        <div class="meafe-init-wrapper <?php echo esc_attr($direction); ?>">
            <div class="meafe-counter-wrapper">

                <?php if ( ! empty( $settings['bccgs_counter_preffix'] ) ) : ?>
                    <span id="prefix" class="counter-su-pre"><?php echo esc_html( $settings['bccgs_counter_preffix'] ); ?></span>
                <?php endif; ?>

                <span class="meafe-counter-init" id="counter-<?php echo esc_attr( $this->get_id() ); ?>"><?php echo esc_html( $start_value ); ?></span>

                <?php if ( ! empty( $settings['bccgs_counter_suffix'] ) ) : ?>
                    <span id="suffix" class="counter-su-pre"><?php echo esc_html( $settings['bccgs_counter_suffix'] ); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( ! empty( $settings['bccgs_counter_title'] ) && $direction == 'top' ) : ?>
                <h4 class="meafe-counter-title" <?php echo $this->get_render_attribute_string( 'bccgs_counter_title' ); ?>>
                    <?php echo esc_html( $settings['bccgs_counter_title'] ); ?>
                </h4>
            <?php endif; ?>
        </div>

    <?php   
    }
    
    public function get_counter_icon( $settings, $direction ) {
        
        $icon_style = $settings['bcsis_counter_icon_style'] != 'simple' ? ' icon-bg ' . $settings['bcsis_counter_icon_style'] : '';
        
        $animation = $settings['bccds_counter_icon_animation'];
        
        if ( $settings['bccds_counter_icon_image'] === 'icon' ) {
            if ( ! empty ( $settings['bccds_counter_icon'] )  ) {
                $this->add_render_attribute( 'icon', 'class', esc_attr($settings['bccds_counter_icon']) );
                $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
            }
            
            $migrated = isset( $settings['__fa4_migrated']['bccds_counter_icon_updated'] );
            $is_new = empty( $settings['bccds_counter_icon'] ) && Icons_Manager::is_migration_allowed();
        } else {
            $alt = esc_attr( Control_Media::get_image_alt( $settings['bccds_counter_image_upload'] ) );
            
            $this->add_render_attribute( 'image', 'class', 'custom-image' );
            $this->add_render_attribute( 'image', 'src', esc_url($settings['bccds_counter_image_upload']['url']) );
            $this->add_render_attribute( 'image', 'alt', esc_attr($alt) );
        }
        
        $flex_width = '';
        if( $settings['bccds_counter_icon_image'] == 'custom' && $settings['bcsis_counter_icon_style'] == 'simple' ) {
            $flex_width = ' flex-width ';
        } ?>
        <div class="meafe-counter-icon <?php echo $direction; ?>">
            <?php if ( ! empty( $settings['bccgs_counter_title'] ) && $direction != 'top' ) : ?>
                <h4 class="meafe-counter-title" <?php echo $this->get_render_attribute_string( 'bccgs_counter_title' ); ?>>
                    <?php echo esc_html( $settings['bccgs_counter_title'] );?>
                </h4>
            <?php endif; ?>
            <span class="icon<?php echo esc_attr( $flex_width ); ?><?php echo esc_attr( $icon_style ); ?>" data-animation="<?php echo esc_attr($animation); ?>">
            
                <?php if( $settings['bccds_counter_icon_image'] === 'icon' ) {
        
                    if ( $is_new || $migrated ) :
                        Icons_Manager::render_icon( $settings['bccds_counter_icon_updated'], [ 'aria-hidden' => 'true' ] );
                    else: ?>
                        <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                    <?php endif;
                } else { ?>
                    <img <?php echo $this->get_render_attribute_string('image'); ?>>
                <?php } ?>
            
            </span>
        </div>

    <?php
    }

    protected function render() {
        
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes( 'bccgs_counter_title' );
         
        $position = $settings['bccds_counter_icon_position'];
        
        $this->add_render_attribute( 'counter', [
                'class'             => [ 'meafe-counter', 'meafe-counter-area' ],
                'data-duration'     => $settings['bccgs_counter_speed'] * 1000,
                'data-from-value'   => $settings['bccgs_counter_start_value'],
                'data-to-value'     => $settings['bccgs_counter_end_value'],
                'data-delimiter'    => $settings['bccgs_counter_t_separator'],
                'data-rounding'     => empty ( $settings['bccgs_counter_d_after'] ) ? 0 : $settings['bccgs_counter_d_after']
            ]
        );

        ?>

        <div <?php echo $this->get_render_attribute_string( 'counter' ); ?>>
            <?php if( $position == 'right' ) {
                $this->get_counter_content( $settings, $position );
                if( ! empty( $settings['bccds_counter_icon_updated']['value'] ) || ! empty( $settings['bccds_counter_icon'] ) || ! empty( $settings['bccds_counter_image_upload']['url'] ) || ! empty( $settings['bccgs_counter_title'] ) ) {
                    $this->get_counter_icon( $settings, $position );
                }
            
            }elseif( $position == 'top' ) {
                if( ! empty( $settings['bccds_counter_icon_updated']['value'] ) || ! empty( $settings['bccds_counter_icon'] ) || ! empty( $settings['bccds_counter_image_upload']['url'] ) || ! empty( $settings['bccgs_counter_title'] ) ) {
                    $this->get_counter_icon( $settings, $position );
                }
                $this->get_counter_content( $settings, $position );

            
            } else { 
                $this->get_counter_content( $settings, $position );
                if( ! empty( $settings['bccds_counter_icon_updated']['value'] ) || ! empty( $settings['bccds_counter_icon'] ) || ! empty( $settings['bccds_counter_image_upload']['url'] ) || ! empty( $settings['bccgs_counter_title'] ) ) {
                    $this->get_counter_icon( $settings, $position );
                } 
            ?>

            <?php } ?>

        </div>

        <?php
    }

    protected function content_template() {
        ?>
        <#            
            var iconImage,
                position;
        
            view.addInlineEditingAttributes('bccgs_counter_title');
            
            position = settings.bccds_counter_icon_position;

            var delimiter = settings.bccgs_counter_t_separator,
                round     = '' === settings.bccgs_counter_d_after ? 0 : settings.bccgs_counter_d_after;
            
            view.addRenderAttribute( 'counter', 'class', [ 'meafe-counter', 'meafe-counter-area' ] );
            view.addRenderAttribute( 'counter', 'data-duration', settings.bccgs_counter_speed * 1000 );
            view.addRenderAttribute( 'counter', 'data-from-value', settings.bccgs_counter_start_value );
            view.addRenderAttribute( 'counter', 'data-to-value', settings.bccgs_counter_end_value );
            view.addRenderAttribute( 'counter', 'data-delimiter', delimiter );
            view.addRenderAttribute( 'counter', 'data-rounding', round );
            
            function getCounterContent( direction ) {
            
                var startValue = settings.bccgs_counter_start_value;
                
                view.addRenderAttribute( 'counter_wrap', 'class', [ 'meafe-init-wrapper', direction ] );
                
                view.addRenderAttribute( 'value', 'id', 'counter-' + view.getID() );
                
                view.addRenderAttribute( 'value', 'class', 'meafe-counter-init' );
                
            #>
            
                <div {{{ view.getRenderAttributeString('counter_wrap') }}}>
                    <div class="meafe-counter-wrapper">
                        <# if ( '' !== settings.bccgs_counter_preffix ) { #>
                            <span id="prefix" class="counter-su-pre">{{{ settings.bccgs_counter_preffix }}}</span>
                        <# } #>

                        <span {{{ view.getRenderAttributeString('value') }}}>{{{ startValue }}}</span>

                        <# if ( '' !== settings.bccgs_counter_suffix ) { #>
                            <span id="suffix" class="counter-su-pre">{{{ settings.bccgs_counter_suffix }}}</span>
                        <# } #>
                    </div>
                    <# if ( '' !== settings.bccgs_counter_title && position === 'top' ) { #>
                        <h4 class="meafe-counter-title" {{{ view.getRenderAttributeString('bccgs_counter_title') }}}>
                                {{{ settings.bccgs_counter_title }}}
                        </h4>
                    <# } #>
                </div>
            
            <#
            }
            
            function getCounterIcon( direction ) {
            
                var iconStyle = 'simple' !== settings.bcsis_counter_icon_style ? ' icon-bg ' + settings.bcsis_counter_icon_style : '',
                    animation = settings.bccds_counter_icon_animation,
                    flexWidth = '';
                
                var iconHTML = elementor.helpers.renderIcon( view, settings.bccds_counter_icon_updated, { 'aria-hidden': true }, 'i' , 'object' ),
                    migrated = elementor.helpers.isIconMigrated( settings, 'bccds_counter_icon_updated' );
                    
                if( 'custom' === settings.bccds_counter_icon_image && 'simple' ===  settings.bcsis_counter_icon_style ) {
                    flexWidth = ' flex-width ';
                }
                
                view.addRenderAttribute( 'icon_wrap', 'class', [ 'meafe-counter-icon', direction ] );
                
                var iconClass = 'icon' + flexWidth + iconStyle;
            
            #>

            <div {{{ view.getRenderAttributeString('icon_wrap') }}}>
                <# if ( '' !== settings.bccgs_counter_title && position !== 'top'  ) { #>
                    <h4 class="meafe-counter-title" {{{ view.getRenderAttributeString('bccgs_counter_title') }}}>
                            {{{ settings.bccgs_counter_title }}}
                    </h4>
                <# } #>
                <span data-animation="{{ animation }}" class="{{ iconClass }}">
                    <# if( 'icon' === settings.bccds_counter_icon_image ) {
                        if ( iconHTML && iconHTML.rendered && ( ! settings.bccds_counter_icon || migrated ) ) { #>
                            {{{ iconHTML.value }}}
                        <# } else { #>
                            <i class="{{ settings.bccds_counter_icon }}" aria-hidden="true"></i>
                        <# } #>
                    <# } else { #>
                        <img class="custom-image" src="{{ settings.bccds_counter_image_upload.url }}">
                    <# } #>
                </span>
            </div>
            
            <#
            }
           
        #>
        
        <div {{{ view.getRenderAttributeString('counter') }}}>
            <# if( 'right' === position  ) {
            
                getCounterContent( position );
                
                if(  '' !== settings.bccds_counter_icon_updated.value || '' !== settings.bccds_counter_icon || '' !== settings.bccds_counter_image_upload.url || '' !== settings.bccgs_counter_title ) {
                    getCounterIcon( position );
                }
            
            } else if( 'top' === position  ) {
            
                if(  '' !== settings.bccds_counter_icon_updated.value || '' !== settings.bccds_counter_icon || '' !== settings.bccds_counter_image_upload.url || '' !== settings.bccgs_counter_title ) {
                    getCounterIcon( position );
                }

                getCounterContent( position );
                
            } else {
            
                getCounterContent( position );
                
                if(  '' !== settings.bccds_counter_icon_updated.value || '' !== settings.bccds_counter_icon || '' !== settings.bccds_counter_image_upload.url || '' !== settings.bccgs_counter_title ) {
                    getCounterIcon( position );
                }
                
            
            } #>
        </div>
        
        <?php
    }
}
