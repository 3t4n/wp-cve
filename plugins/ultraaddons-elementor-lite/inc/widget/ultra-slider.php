<?php
namespace UltraAddons\Widget;

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Utils;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ultra_Slider extends Base{
        use \UltraAddons\Traits\Animate_Style;
        
        
        /**
         * mainly to call specific depends
         * we have called this __construct() method
         * 
         * @param Array $data
         * @param Array $args
         * 
         * @by Saiful Islam
         */
        public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);

            //Naming of Args for owlCarousel
            $name           = 'owlCarousel';
            $js_file_url    = ULTRA_ADDONS_ASSETS . 'vendor/js/owl.carousel.min.js';
            $dependency     =  ['jquery'];//['jquery'];
            $version        = ULTRA_ADDONS_VERSION;
            $in_footer  = true;

            wp_register_script( $name, $js_file_url, $dependency, $version, $in_footer );
            wp_enqueue_script( $name );


            //CSS file for Slider Script Owl Carousel Slider
            wp_register_style('owlCarousel', ULTRA_ADDONS_ASSETS . 'vendor/css/owl.carousel.css' );
            wp_enqueue_style('owlCarousel' );

            wp_register_style('owlCarousel-theme', ULTRA_ADDONS_ASSETS . 'vendor/css/owl/owl.theme.default.css' );
            wp_enqueue_style( 'owlCarousel-theme' );

        }

        /**
         * By Saiful Islam
         * depend css for this widget
         * 
         * @return Array
         */
        public function get_style_depends() {
            return ['owlCarousel','owlCarousel-theme'];
        }

        /**
         * Retrieve the list of scripts the skill bar widget depended on.
         *
         * Used to set scripts dependencies required to run the widget.
         *
         * @since 1.0.9.2
         * @access public
         *
         * @return array Widget scripts dependencies.
         * @by Saiful
         */
        public function get_script_depends() {
                return [ 'jquery','owlCarousel' ];
        }
    
        
        /**
         * Find by search
         *
         * Retrieve widget title.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string keywords
         */
        public function get_keywords() {
            return [ 'ultraaddons', 'slider','ua','carousel', 'slide' ];
        }
        
        
        
        
        /**
         * Register oEmbed widget controls.
         *
         * Adds different input fields to allow the user to change and customize the widget settings.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function register_controls() {

            //For General Section
            $this->content_general_controls();

            //For Design Section Style Tab
            $this->style_general_controls();
            $this->slider_controls();
            
            //For Typically Background Effect
            $this->style_background_control();
            
            //Control Number Pagination
            //$this->style_number_pagination();
            //$this->style_next_prev_pagination();
            //$this->style_bullet_pagination();

        }
    
        /**
         * Render oEmbed widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render() {

                $settings       = $this->get_settings_for_display();
                $number         = ('yes'!= $settings['display_underline'] )  ? 'no-line' : ""  ;
        //
                $this->add_render_attribute( 'wrapper', [
                        'class' => [
                            'ua-slider-wrapper',
                            'nav-type-' . $settings['navigation_type'],
                            $number
                        ],
                ] );


                //navigation
                if( $settings['navigation_type'] == 'number' ){
                    $this->add_render_attribute( 'wrapper', 'class', 'ua-number-slider-wrapper' );
                }


                ?>
            <div class="ua-slider-main-wrapper">
                <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
                    
                    <?php
                    foreach( $settings['slides'] as $key => $slide ){
                        $repeater_key = $this->get_repeater_setting_key( 'title', 'slides', $key );
                        
                        $_id = !empty( $slide['_id'] ) ? $slide['_id'] : false;
                        $image = isset( $slide['image']['url'] ) && ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : false;
                        $title = !empty( $slide['title'] ) ? $slide['title'] : false;
                        $content = !empty( $slide['content'] ) ? $slide['content'] : false;
                        $button = !empty( $slide['button'] ) ? $slide['button'] : false;
                        
                        
                        $link = false;
                        if ( ! empty( $slide['button_link']['url'] ) ) {
                            $this->add_link_attributes( $repeater_key . '.button', $slide['button_link'] );
                            $this->add_render_attribute( $repeater_key . '.button', 'class', 'slider-button' );
                            $link = true;
                        }
                        
                        $slug = $slide['slide_template'];

                        if( ! empty( $slug ) ){
                             
                            $queried_post = get_page_by_path($slug, OBJECT, 'elementor_library');

                            (int) $select_post_id = $queried_post->ID;
                            
                            if( Plugin::$instance->documents->get( $select_post_id )->is_built_with_elementor() ){
                                echo wp_kses_post( '<div class="ua-slider-item">' );
                                echo Plugin::instance()->frontend->get_builder_content_for_display( $select_post_id );
                                echo wp_kses_post( '</div>' );
                            }
                        }else{
                    ?>
                        <div class="ua-slider-item" 
                             <?php if( $image ){ ?>
                             style="background-image: url(<?php echo esc_url( $image ); ?>);"
                             <?php } ?>
                             >
                            <div class="ultraaddons-slider-container-wrapper">
                                <div class="ultraaddons-slider-container">
                                    <div class="row">
                                        <div class="ua-slide-content-wrap">
                                            <div class="ua-hero-text">
                                                <h1><?php echo wp_kses_post( $title ); ?></h1>
                                                <p class="para"><?php echo wp_kses_post( $content ); ?></p>
                                                <div class="hero-btn">
                                                    <a <?php echo $this->get_render_attribute_string( $repeater_key . '.button' ); ?>><?php echo esc_html( $button ); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                        
                    <?php
                        }
                        
                    }                
                    ?>
                    
                </div> <!-- end of inside wrapper -->
            </div>
                <?php
        
        }
    
        /**
         * General Section for Content Controls
         * 
         * @since 1.0.0.9
         */
        protected function content_general_controls() {
        
                $this->start_controls_section(
                        'general',
			[
				'label' => __( 'General', 'ultraaddons' ),
			]
                );
                
                $repeater = new Repeater();
                $repeater->add_control(
                            'slide_type',
                            [
                                    'label' => __( 'Slide Type', 'ultraaddons' ),
                                    'type' => Controls_Manager::SELECT,
                                    'options' => [
                                        'template' => __( 'Elementor Template', 'ultraaddons' ),
                                        'default' => __( 'Default', 'ultraaddons' ),
                                    ],
                                    'default' => 'default',
                                    //'prefix_class' => 'slider-type-'
                            ]
                    );
                
                /**
                 * 'condition' => [
                                        'autoplay' => 'yes',
                                ]
                 * 
                 */
                $args = array(
                    'post_type'     =>  'elementor_library',
                    'post_status'   =>  'publish',
                    'posts_per_page' => -1,
                );
                $query = get_posts( $args );
                $template_choices = array();

                //If found post, then itarate
                if( is_array( $query ) && count( $query ) > 0 ){
                    foreach( $query as $q_post ){
                        //var_dump($q_post->post_name);
                        $id = (int) $q_post->ID;
                        $slug = $q_post->post_name;
                        $template_choices[$slug] = $q_post->post_title; //$id
                    }
                    
                    
                    $repeater->add_control(
                            'slide_template',
                            [
                                    'label' => __( 'Template as Slide', 'ultraaddons' ),
                                    'type' => Controls_Manager::SELECT,
                                    'options' => $template_choices,
                                    'condition' => [
                                            'slide_type' => 'template',
                                    ]
                            ]
                    );
                }else{
                    $repeater->add_control(
                            'no_slide_template',
                            [
                                    'label' => __( 'Template Not founded', 'ultraaddons' ),
                                    'type' => Controls_Manager::RAW_HTML,
                                    'description' => esc_html__( 'There is no existing Elementor Template. Please create first.' ),
                                    'condition' => [
                                                'slide_type' => 'template',
                                        ]
                            ]
                    );
                }
                
                $repeater->add_control(
                        'image',
                        [
                                'label' => __( 'Background Image', 'ultraaddons' ),
                                'type' => Controls_Manager::MEDIA,
                                'default' => [
                                        'url' => Utils::get_placeholder_image_src(),
                                ],
                                'dynamic' => [
                                        'active' => true,
                                ],
                                'condition' => [
                                            'slide_type' => 'default',
                                ],

                        ]
                );
                $repeater->add_control(
                        'title',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'ultraaddons' ),
				'default' => __( 'Slider Title Text', 'ultraaddons' ),
				'label_block' => true,
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
			]
                );
                
                $repeater->add_control(
                        'content',
			[
				'label' => __( 'Content', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your content...', 'ultraaddons' ),
				'default' => __( "Lorem Ipsum is simply dumy text of the printing & typesetting industry Lorem Ipsum has been the industry's standard dummy text ever since the 1975, when an unknown printer.", 'ultraaddons' ),
				'label_block' => true,
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
			]
                );
                
                $repeater->add_control(
                        'button',
                        [
                                'label' => __( 'Text', 'ultraaddons' ),
                                'type' => Controls_Manager::TEXT,
                                'dynamic' => [
                                        'active' => true,
                                ],
                                'default' => __( 'Click here', 'ultraaddons' ),
                                'placeholder' => __( 'Click here', 'ultraaddons' ),
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
                        ]
                );

                $repeater->add_control(
                        'button_link',
                        [
                                'label' => __( 'Link', 'ultraaddons' ),
                                'type' => Controls_Manager::URL,
                                'dynamic' => [
                                        'active' => true,
                                ],
                                'placeholder' => __( 'https://your-link.com', 'ultraaddons' ),
                                'default' => [
                                        'url' => '#',
                                ],
                                'condition' => [
                                            'slide_type' => 'default',
                                ],
                        ]
                );
                
//                
//		$repeater->add_group_control(
//			Group_Control_Typography::get_type(),
//			[
//				'name' => 'repeater_typography',
//				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .heading',
//				'global' => [
//					'default' => Global_Typography::TYPOGRAPHY_TEXT,
//				],
//			]
//		);
//                
                $this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'image' => Utils::get_placeholder_image_src(),
						'title' => __( 'Slide One', 'ultraaddons' ),
						'content' => __( 'This is Sample Content ...', 'ultraaddons' ),
						'button' => __( 'Click Here', 'ultraaddons' ),
						'button_link' => '#',
                                                
					],
                                    
					[
						'image' => Utils::get_placeholder_image_src(),
						'title' => __( 'Slide Two Text', 'ultraaddons' ),
						'content' => __( 'This is Sample Content ...', 'ultraaddons' ),
						'button' => __( 'Click Here', 'ultraaddons' ),
						'button_link' => '#',
                                                
					],
                                    
					[
						'image' => Utils::get_placeholder_image_src(),
						'title' => __( 'Slide Three', 'ultraaddons' ),
						'content' => __( 'This is Sample Content ...', 'ultraaddons' ),
						'button' => __( 'Click Here', 'ultraaddons' ),
						'button_link' => '#',
                                                
					],
                                    
					
				],
				'title_field' => '{{{ title }}}',
			]
		);
                
//                $this->add_control( 
//                        'display_percentage',
//                        [
//                                'label' => __( 'Display Percentage', 'ultraaddons' ),
//                                'type' => Controls_Manager::SWITCHER,
//                                'label_on' => __( 'Show', 'ultraaddons' ),
//				'label_off' => __( 'Hide', 'ultraaddons' ),
//                                'return_value' => 'yes',
//                                'default' => 'yes',
//                        ]
//                );
        
               
        
                $this->end_controls_section();
        }
    
        /**
         * Alignment Section for Style Tab
         * 
         * @since 1.0.0.9
         */
        protected function style_general_controls() {
                
                $this->start_controls_section(
			'section_progress_style',
			[
				'label' => __( 'General', 'ultraaddons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
                
                $this->add_control(
                        'text_align',
                        [
                                'label' => __( 'Alignment', 'ultraaddons' ),
                                'type' => Controls_Manager::CHOOSE,
                                'options' => [
                                        'left' => [
                                                'title' => __( 'Left', 'ultraaddons' ),
                                                'icon' => 'eicon-text-align-left',
                                        ],
                                        'center' => [
                                                'title' => __( 'Center', 'ultraaddons' ),
                                                'icon' => 'eicon-text-align-center',
                                        ],
                                        'right' => [
                                                'title' => __( 'Right', 'ultraaddons' ),
                                                'icon' => 'eicon-text-align-right',
                                        ],
                                ],
                                'toggle' => true,
                                'prefix_class' => 'elementor-align-',
                                'default' => 'center',
                        ]
                );
                
                $this->add_responsive_control(
			'slider-height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
//				'default' => [
//					'size' => 1,
//				],
				'range' => [
					'px' => [
						'max' => 1000,
                                                'min'   => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ua-slider-item .ultraaddons-slider-container' => 'height: {{SIZE}}{{UNIT}};',
				],
				
			]
		);
                
                $this->add_responsive_control(
                        'slider_wrapper_padding',
                        [
                                'label' => __( 'Slider Area Padding', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
//                                'default'   => [
//                                        'left'=> 50,
//                                        'right'=> 50,
//                                        'top' => 180,
//                                        'bottom' => 180,
//                                        'unit' => 'px',
//                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper .owl-stage-outer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                
                        ]
                );
                $this->add_responsive_control(
                        'slider_wrapper_margin',
                        [
                                'label' => __( 'Slider Area Margin', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
//                                'default'   => [
//                                        'left'=> 50,
//                                        'right'=> 50,
//                                        'top' => 180,
//                                        'bottom' => 180,
//                                        'unit' => 'px',
//                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper .owl-stage-outer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                
                        ]
                );
                
                
                
                $this->add_responsive_control(
                        'slider_item_padding',
                        [
                                'label' => __( 'Slider Inner Padding', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
//                                'default'   => [
//                                        'left'=> 50,
//                                        'right'=> 50,
//                                        'top' => 180,
//                                        'bottom' => 180,
//                                        'unit' => 'px',
//                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-item .ultraaddons-slider-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                
                        ]
                );
                $this->add_responsive_control(
                        'slider_item_margin',
                        [
                                'label' => __( 'Slider Inner Margin', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
//                                'default'   => [
//                                        'left'=> 50,
//                                        'right'=> 50,
//                                        'top' => 180,
//                                        'bottom' => 180,
//                                        'unit' => 'px',
//                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-item .ultraaddons-slider-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                
                        ]
                );
                
                
                $this->add_control(
			'title_options',
			[
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text h1',
			]
		);
                
                $this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#021429',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text h1' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'subtitle_options',
			[
				'label' => __( 'Sub-Title', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text p',
			]
		);
                
                $this->add_control(
			'sub_title_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#5C6B79',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-item .ua-slide-content-wrap .ua-hero-text p' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'button_options',
			[
				'label' => __( 'Button', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->start_controls_tabs( 'slider_btn' );
                
                $this->start_controls_tab(
                        'tab_button_content_normal',
                        [
                                'label'  => esc_html__( 'Normal', 'ultraaddons' )
                        ]
                );
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button',
			]
		);
                
                $this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#0FC392',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button' => 'background-color: {{VALUE}}',
				],
			]
		);
                 $this->add_responsive_control(
                        'button_padding',
                        [
                                'label' => __( 'Button Padding', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                
                        ]
                );
                $this->add_responsive_control(
                        'button_radius',
                        [
                                'label' => __( 'Button Radius', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                
                        ]
                );
                $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
                                'name' => 'button_shadow',
                                'label' => esc_html__( 'Button Shadow', 'ultraaddons' ),
                                'selector' => '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button',
			]
		);
                $this->end_controls_tab();
                
                $this->start_controls_tab(
                        'tab_button_content_hover',
                        [
                                'label'  => esc_html__( 'Hover', 'ultraaddons' )
                        ]
                );
                
                $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_hover_typography',
				'label' => __( 'Typography', 'ultraaddons' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button:hover',
			]
		);
                
                $this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0FC392',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button:hover' => 'color: {{VALUE}}',
				],
			]
		);
                
                $this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-wrapper .ua-slider-item .slider-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
                
                $this->end_controls_tab();
                
                
                $this->end_controls_tabs();
                
                $this->add_control(
			'pagination_options',
			[
				'label' => __( 'Pagination/Navigation', 'ultraaddons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
                
                $this->add_control(
                        'navigation_icon_color',
                        [
                                'label' => __( 'Navigation Icon Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'condition'=>[
                                        'navigation'=>['arrow','both']
                                ],
                                'selectors' => [   
                                        '{{WRAPPER}} .ua-slider-wrapper button.owl-prev i' => 'color: {{VALUE}}',
                                        '{{WRAPPER}} .ua-slider-wrapper button.owl-next i' => 'color: {{VALUE}}'
                                ],
                        ]
                );

                $this->add_control(
                        'navigation_bg_color',
                        [
                                'label' => __( 'Navigation BG Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}}.navigation-arrow-position-center .ua-slider-wrapper .owl-nav button' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
                                        '{{WRAPPER}} .ua-slider-wrapper .owl-nav button.owl-next' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
                                ],
                                 'condition'=>[
                                        'navigation'=>['arrow','both']
                                ],
                        ]
                );
                $this->add_responsive_control(
                        'navigation_radius',
                        [
                                'label' => __( 'Navigation Radius', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        '{{WRAPPER}} .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                 'condition'=>[
                                        'navigation'=>'arrow'
                                ],
                                
                        ]
                );
                 $this->add_responsive_control(
                        'navigation_padding',
                        [
                                'label' => __( 'Navigation Padding', 'ultraaddons' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        '{{WRAPPER}} .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                                 'condition'=>[
                                        'navigation'=>'arrow'
                                ],
                                
                        ]
                );
                 $this->add_control(
			'navigation_size',
			[
				'label' => __( 'Navigation Size', 'ultraaddons' ),
				'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px'],
                                'range' => [
                                        'px' => [
                                                'min' => 40,
                                                'max' => 100,
                                                'step' => 5,
                                        ],
				],
				'default' => [
					'size' => 50,
					'unit' => 'px',
				],
				 'condition'=>[
                                        'navigation'=>'arrow'
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper .owl-nav button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                                ],
			]
		);
                
                $this->add_control(
                        'dot_icon_color',
                        [
                                'label' => __( 'Dots Icon Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'default' => '#717171',
                                 'condition'=>[
                                        'navigation'=>['dots']
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-main-wrapper .nav-type-dots .owl-dots button.owl-dot' => 'background-color: {{VALUE}}',
//                                        '{{WRAPPER}} .ua-slider-wrapper .owl-dots button.owl-dot.active' => 'border-color: {{VALUE}}; background-color: #FFF',
                                ]
                        ]
                );

                $this->add_control(
                        'dots_bg_active_color',
                        [
                                'label' => __( 'Active Dots/Number Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper.ua-number-slider-wrapper .owl-dots button.owl-dot.active' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} .ua-slider-wrapper.ua-number-slider-wrapper .owl-dots button.owl-dot.active:after' => 'background: {{VALUE}};',
                                ],
                                'default' => '#E60B55',
                                'condition'=>[
                                        'navigation'=>'dots'
                                ],
                        ]
                );
                $this->add_control(
                        'dots_bg_color',
                        [
                                'label' => __( 'Dots/Number Color', 'ultraaddons' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .ua-slider-wrapper.ua-number-slider-wrapper .owl-dots button.owl-dot' => 'color: {{VALUE}};',
                                        '{{WRAPPER}} .ua-slider-wrapper.ua-number-slider-wrapper .owl-dots button.owl-dot:after' => 'background: {{VALUE}};',
                                ],
                                'default' => '#0FC392',
                                'condition'=>[
                                        'navigation'=>'dots'
                                ],
                        ]
                );
                $this->add_control( 
                       'display_underline',
                        [
                                'label' => __( 'Number Underline', 'ultraaddons' ),
                               'type' => Controls_Manager::SWITCHER,
                               'label_on' => __( 'Show', 'ultraaddons' ),
				'label_off' => __( 'Hide', 'ultraaddons' ),
                               'return_value' => 'yes',
                                'default' => 'yes',
                                'condition'=>[
                                        'navigation' => ['dots','both'],
                                        'navigation_type' => 'number',
                                ],
                        ]
                 );
                
                $this->end_controls_section();
                
                
        }
        
        protected function style_background_control(){
            $this->start_controls_section(
                    'slider-background-effect',
                    [
                        'label'     => esc_html__( 'Overlay Effect', 'ultraaddons' ),
                    ]
                );

                $this->add_control(
			'background_overlay',
			[
				'label' => __( 'Backgrund Color', 'ultraaddons' ),
				'type' => Controls_Manager::COLOR,
                                'default' => '#EEFFF100',
				'selectors' => [
					'{{WRAPPER}} .ua-slider-item .ultraaddons-slider-container-wrapper' => 'background-color: {{VALUE}}',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
                                'label' => __( 'CSS Filter', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-slider-item',
			]
		);
                 
            
            $this->end_controls_section();
        }

                /**
         * 
         * @since 1.0.0.15
         */
        protected function slider_controls(){
                $this->start_controls_section(
                    'slider-settings',
                    [
                        'label'     => esc_html__( 'Slider Settings', 'ultraaddons' ),
                    ]
                );

                $this->add_control(
                        'autoplay',
                        [
                                'label' => __( 'Autoplay?', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'Yes', 'ultraaddons' ),
                                'label_off' => __( 'No', 'ultraaddons' ),
                                'default' => 'yes',
                                'return_value' => 'yes',
                                'frontend_available' => true,
                        ]
                );

                $this->add_control(
                        'pause_on_hover',
                        [
                                'label' => __( 'Pause on Hover', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'Yes', 'ultraaddons' ),
                                'label_off' => __( 'No', 'ultraaddons' ),
                                'default' => 'yes',
                                'return_value' => 'yes',
                                'condition' => [
                                        'autoplay' => 'yes',
                                ],
                                'frontend_available' => true,
                        ]
                );

                $this->add_control(
                        'autoplay_speed',
                        [
                                'label' => __( 'Autoplay Speed', 'ultraaddons' ),
                                'type' => Controls_Manager::NUMBER,
                                'min' => 100,
                                'step' => 100,
                                'max' => 10000,
                                'default' => 3000,
                                'description' => __( 'Autoplay speed in milliseconds', 'ultraaddons' ),
                                'condition' => [
                                        'autoplay' => 'yes',
                                ],
                                'frontend_available' => true,
                        ]
                );

                // Loop requires a re-render so no 'render_type = none'
                $this->add_control(
                        'loop',
                        [
                                'label' => __( 'Infinite Loop?', 'ultraaddons' ),
                                'type' => Controls_Manager::SWITCHER,
                                'label_on' => __( 'Yes', 'ultraaddons' ),
                                'label_off' => __( 'No', 'ultraaddons' ),
                                'default' => 'yes',
                                'return_value' => 'yes',
                                'frontend_available' => true,
                        ]
                );

                $this->add_control(
                        'autoplayTimeout',
                        [
                                'label' => __( 'Autoplay Delay', 'ultraaddons' ),
                                'type' => Controls_Manager::NUMBER,
                                'min' => 1000,
                                'step' => 1000,
                                'max' => 10000,
                                'default' => 3000,
                                'description' => __( 'Autoplay delay in milliseconds', 'ultraaddons' ),
                                'frontend_available' => true,
                        ]
                );

                $this->add_control(
                        'navigation',
                        [
                                'label' => __( 'Navigation', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'none'      => __( 'None', 'ultraaddons' ),
                                        'arrow'     => __( 'Arrow', 'ultraaddons' ),
                                        'dots'      => __( 'Dots/Number', 'ultraaddons' ),
                                        'both'      => __( 'Arrow & Dots/Number', 'ultraaddons' ),
                                ],
                                'default' => 'dots',
                                'frontend_available' => true,
                                'style_transfer' => true,
                        ]
                );
                $this->add_control(
                        'navigation_type',
                        [
                                'label' => __( 'Navigation Type', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'dots'      => __( 'Dots', 'ultraaddons' ),
                                        'number'    => __( 'Number', 'ultraaddons' ),
                                ],
                                'condition' => [
                                        'navigation' => ['dots', 'both'],
                                ],
                                'default' => 'number',
                        ]
                );
                
                $this->add_control(
                        'navigation_arrow_position',
                        [
                                'label' => __( 'Arrow Position', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'top-right'      => __( 'Top Right', 'ultraaddons' ),
                                        'top-left'      => __( 'Top Left', 'ultraaddons' ),
                                        'center'    => __( 'Center', 'ultraaddons' ),
                                        'bottom-right'    => __( 'Bottom Right', 'ultraaddons' ),
                                        'bottom-left'    => __( 'Bottom Left', 'ultraaddons' ),
                                ],
                                'condition' => [
                                        'navigation' => ['arrow','both'],
                                        //'navigation_type' => ['arrow'],
                                ],
                                'default' => 'center',
                                'prefix_class' => 'navigation-arrow-position-',
                        ]
                );
                $this->add_control(
                        'next_prev_spacing',
                        [
                                'label' => __( 'Navigation Button Spacing', 'ultraaddons' ),
                                'type' => Controls_Manager::SLIDER,
                                'default' => [
                                        'size' => 50,
                                ],
                                'range' => [
                                        'px' => [
                                                'min' => 1,
                                                'max' => 250,
                                                'step' => 1,
                                        ],
                                ],
                                
                                'condition' => [
                                        'navigation_arrow_position' => ['center', 'top-right','top-left','bottom-right','bottom-left'],
                                        'navigation' => ['arrow','both'],
                                ],
                                'selectors' => [
                                        '{{WRAPPER}}.navigation-arrow-position-bottom-right .ua-slider-wrapper .owl-nav,{{WRAPPER}}.navigation-arrow-position-bottom-left .ua-slider-wrapper .owl-nav' => 'bottom: -{{SIZE}}{{UNIT}};',
                                        '{{WRAPPER}}.navigation-arrow-position-top-left .ua-slider-wrapper .owl-nav,{{WRAPPER}}.navigation-arrow-position-top-right .ua-slider-wrapper .owl-nav' => 'top: -{{SIZE}}{{UNIT}};',
                                        '{{WRAPPER}}.navigation-arrow-position-center .ua-slider-wrapper .owl-nav' => 'top: {{SIZE}}%;',
                                ],

                        ]
                );
                
                $this->add_control(
                        'next_prev_center_spacing',
                        [
                                'label' => __( 'Navigation Spacing', 'elementor' ),
                                'type' => Controls_Manager::SLIDER,
//                                'size_units' => [ 'px' ],
                                'default' => [
//                                        'unit' => '%',
                                        'size' => 96,
                                ],
                                'range' => [
                                        'px' => [
                                                'min' => 1,
                                                'max' => 100,
                                                'step' => 1,
                                        ],
                                ],
                                'condition' => [
                                        'navigation_arrow_position' => ['center'],

                                ],
                                'selectors' => [
//                                        '{{WRAPPER}}.navigation-arrow-position-center .ua-slider-wrapper .owl-nav' => 'width: {{SIZE}}{{UNIT}};',
                                        '{{WRAPPER}}.navigation-arrow-position-center .ua-slider-wrapper .owl-nav' => 'width: {{SIZE}}%;margin-left: calc((100% - {{SIZE}}%)/2);',
                                ],

                        ]
                );
                
                $this->add_control(
                        'navigation_number_position',
                        [
                                'label' => __( 'Number Position', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'left'      => __( 'Left', 'ultraaddons' ),
                                        'right'    => __( 'Right', 'ultraaddons' ),
                                ],
                                'condition' => [
                                        'navigation' => ['dots','both'],
                                        'navigation_type' => 'number',
                                ],
                                'default' => 'left',
                                'prefix_class' => 'navigation-number-position-',
                        ]
                );

                $this->add_responsive_control(
                        'slides_to_show',
                        [
                                'label' => __( 'Slides To Show', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        1 => __( '1 Slide', 'ultraaddons' ),
                                        2 => __( '2 Slides', 'ultraaddons' ),
                                        3 => __( '3 Slides', 'ultraaddons' ),
                                        4 => __( '4 Slides', 'ultraaddons' ),
                                        5 => __( '5 Slides', 'ultraaddons' ),
                                        6 => __( '6 Slides', 'ultraaddons' ),
                                ],
                                'desktop_default' => 1,
                                'tablet_default' => 1,
                                'mobile_default' => 1,
                                'frontend_available' => true,
                                'style_transfer' => true,
                        ]
                );

                
                
                $animation_options = $this->get_animations();
                $this->add_control(
                        'external_animation',
                        [
                                'label' => __( 'External Animation', 'ultraaddons' ),
//                                'description' => esc_html__( 'Not mandatory, But you can apply. Generate from Animate.style', 'ultraaddons' ),
                                'type' => Controls_Manager::SELECT2,
                                'options' => $animation_options,
                                'default' => '',
                                'frontend_available' => true,
//                                'style_transfer' => true,
                        ]
                );
                
                $this->end_controls_section();
        }
        
        protected function style_number_pagination(){
            $this->start_controls_section(
                    'slider-number-pagination',
                    [
                        'label'     => esc_html__( 'Pagination Number', 'ultraaddons' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                    ]
                );
            
            $this->end_controls_section();
        }
        
        //style_next_prev_pagination
        protected function style_next_prev_pagination(){
            $this->start_controls_section(
                    'slider-next-prev-pagination',
                    [
                        'label'     => esc_html__( 'Pagination Next Prev', 'ultraaddons' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                    ]
                );
            
            $this->end_controls_section();
        }
        
        //sstyle for bullet
        protected function style_bullet_pagination(){
            $this->start_controls_section(
                    'slider-bullet-pagination',
                    [
                        'label'     => esc_html__( 'Pagination Next Prev', 'ultraaddons' ),
                        'tab' => Controls_Manager::TAB_STYLE,
                    ]
                );
            
            $this->end_controls_section();
        }
        
}