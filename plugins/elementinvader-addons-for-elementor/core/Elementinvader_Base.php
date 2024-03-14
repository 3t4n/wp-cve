<?php

namespace ElementinvaderAddonsForElementor\Core;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Elementinvader_Base extends Widget_Base {
        
        public $view_folder = 'categories';
	public  $inline_css = '';
	public  $inline_css_tablet = '';
	public  $inline_css_mobile = '';
        
        public $categories_select = array();

        public function __construct($data = array(), $args = null) {
                                            
                if ($this->is_edit_mode_load()) {
                        $this->enqueue_styles_scripts();
                }

                parent::__construct($data, $args);

        }
        
        
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'categories';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Widget Name', 'elementinvader-addons-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return '';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elementinvader' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
            /* TAB_STYLE */ 
            $this->start_controls_section(
                    'section_form_css',
                    [
                            'label' => esc_html__( 'Custom сss', 'elementinvader-addons-for-elementor' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );

            $this->add_control(
                    'custom_css_title',
                    [
                            'raw' => esc_html__( 'Add your own custom CSS here', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::RAW_HTML,
                    ]
            );

            $this->add_control(
                    'custom_css',
                    [
                            'type' => Controls_Manager::CODE,
                            'label' => esc_html__( 'Custom CSS', 'elementinvader-addons-for-elementor' ),
                            'language' => 'css',
                            'render_type' => 'ui',
                            'show_label' => false,
                            'separator' => 'none',
                    ]
            );

            $this->add_control(
                    'custom_css_description',
                    [
                            'raw' => esc_html__( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-descriptor',
                    ]
            );

            $this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
            $this->enqueue_styles_scripts();
            $this->add_page_settings_css();
	}

        
        public function view($view_file = '', $element = NULL, $print = false)
        {
            if(empty($view_file)) return false;
            $file = false;
            
            if(is_child_theme() && file_exists(get_stylesheet_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$view_file.'.php'))
            {
                $file = get_stylesheet_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$view_file.'.php';
            }
            elseif(file_exists(get_template_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$this->view_folder.'/'.$view_file.'.php'))
            {
                $file = get_template_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$this->view_folder.'/'.$view_file.'.php';
            }
            elseif(file_exists(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'views/'.$this->view_folder.'/'.$view_file.'.php'))
            {
                $file = ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'views/'.$this->view_folder.'/'.$view_file.'.php';
            }

            if($file)
            {
                extract($element);
                if($print) {
                    include $file;
                } else {
                    ob_start();
                    include $file;
                    return ob_get_clean();
                }
            }
            else
            {
                if($print) {
                    echo 'View file not found in: '.esc_html(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'views/'.$this->view_folder.'/'.$view_file.'.php');
                } else {
                    return 'View file not found in: '.esc_html(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'views/'.$this->view_folder.'/'.$view_file.'.php');
                } 
            }
        }
                
        public function generate_renders_tabs($object = [], $tab_prefix='', $enable_options = [], $disable_options = []) {

            /* margin */
            //$options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition','image_size_control','image_fit_control', 'height', 'width','font-size','css_filters','background_group','hover_animation'];
            $options = ['typo','color','background','border','border_radius','padding','shadow']; // default
            
            /* defined */
            
            if(is_string($enable_options)){
                switch($enable_options) {
                    case 'block': $enable_options = ['typo','color','background','border','border_radius','padding','shadoeli-','transition'];
                                    break;
                    case 'text-block': $enable_options = ['align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'text': $enable_options = ['align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'full': $enable_options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                 break;
                    default: $enable_options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                 break;
                }
            }
            
            /* enable options */
            if(!empty($enable_options)){
                $options = $enable_options;
            }
            $options_flip = array_flip($options);
            /* disable options */
            if(!empty($disable_options)){
                foreach ($disable_options as $value) {
                    if(isset($options_flip[$value]))
                        unset($options[$options_flip[$value]]);
                }
            }
            $tabs_enable = true;
            if($this->sw_count($object) == 1){
                $tabs_enable = false;
            }
            if($tabs_enable)
            $this->start_controls_tabs( $tab_prefix.'_style' );
            foreach($object as $key => $selector)
                $this->_generate_tabs($selector, $key, $tab_prefix, $options, $tabs_enable);
            if($tabs_enable)
            $this->end_controls_tabs();
            
        }
        
        public function _generate_tabs($selector='', $prefix = '', $type='', $options = [], $tabs_enable = true) {
                if(empty($selector)) return false;
                
                if(empty($prefix) || $prefix == 'normal'){
                    $selector = $selector;
                    $prefix = 'normal';
                }
                else 
                    $selector = sprintf($selector,':'.$prefix);
                
                if($tabs_enable)
                    $this->start_controls_tab(
                            $type.'_'.$prefix.'_style',
                            [
                                    'label' => ucfirst($prefix),
                            ]
                    );
                
                if(in_array('typo',$options))
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => $type.'_typo'.$prefix,
                                'selector' => $selector,
                        ]
                );
                  
                if(in_array('align',$options))
                $this->add_responsive_control(
                    $type.'_align'.$prefix,
                    [
                            'label' => esc_html__( 'Alignment', 'elementinvader-addons-for-elementor' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left' => [
                                            'title' => esc_html__( 'Left', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-left',
                                    ],
                                    'center' => [
                                            'title' => esc_html__( 'Center', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-center',
                                    ],
                                    'right' => [
                                            'title' => esc_html__( 'Right', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-right',
                                    ],
                                    'justify' => [
                                            'title' => esc_html__( 'Justified', 'elementinvader-addons-for-elementor' ),
                                            'icon' => 'eicon-text-align-justify',
                                    ],
                            ],
                            'render_type' => 'template',
                            'selectors' => [
                                $selector => 'text-align: {{VALUE}};',
                            ],
                    ]
                );
                
                if(in_array('color',$options))
                $this->add_responsive_control(
                        $type.'_color'.$prefix,
                        [
                                'label' => esc_html__( 'Color', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        $selector => 'color: {{VALUE}};',
                                ],
                        ]
                );
    
                if(in_array('background',$options))
                $this->add_responsive_control(
                        $type.'_background'.$prefix,
                        [
                                'label' => esc_html__( 'Background', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        $selector => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                
                if(in_array('border',$options))
                $this->add_group_control(
                        Group_Control_Border::get_type(), [
                                'name' => $type.'_border'.$prefix,
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('border_radius',$options))
                $this->add_responsive_control(
                        $type.'_border_radius'.$prefix,
                        [
                                'label' => esc_html__( 'Border Radius', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                if(in_array('padding',$options))
                $this->add_responsive_control(
                        $type.'_padding'.$prefix,
                        [
                                'label' => esc_html__( 'Padding', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                
                if(in_array('margin',$options))
                $this->add_responsive_control(
                        $type.'_margin'.$prefix,
                        [
                                'label' => esc_html__( 'Margin', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                if(in_array('shadow',$options))
                $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                                'name' => $type.'_box_shadow'.$prefix,
                                'exclude' => [
                                        'field_shadow_position',
                                ],
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('transition',$options))
                $this->add_responsive_control(
                        $type.'_transition'.$prefix,
                        [
                                'label' => esc_html__( 'Transition Duration', 'elementinvader-addons-for-elementor' ),
                                'type' => Controls_Manager::SLIDER,
                                'render_type' => 'template',
                                'range' => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 3000,
                                        ],
                                ],
                                'selectors' => [
                                    $selector => 'transition-duration: {{SIZE}}ms',
                                ],
                        ]
                );
                if(in_array('width',$options))
                $this->add_responsive_control(
                        $type.'_width'.$prefix,
                        [
                                'label' => esc_html__( 'Width', 'wpdirectorykit' ),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 3000,
                                        ],
                                ],
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                    $selector => 'width: {{SIZE}}{{UNIT}}',
                                ],
                        ]
                );
                if(in_array('height',$options))
                $this->add_responsive_control(
                        $type.'_height'.$prefix,
                        [
                                'label' => esc_html__( 'height', 'wpdirectorykit' ),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 3000,
                                        ],
                                ],
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                    $selector => 'height: {{SIZE}}{{UNIT}}',
                                ],
                        ]
                );
                if(in_array('font-size',$options))
                $this->add_responsive_control(
                        $type.'_font_size'.$prefix,
                        [
                                'label' => esc_html__( 'Font Size', 'wpdirectorykit' ),
                                'type' => Controls_Manager::SLIDER,
                                'size_units' => [ 'px', 'em', '%' ],
                                'range' => [
                                        'px' => [
                                            'min' => 0,
                                            'max' => 150,
                                        ],
                                        'vw' => [
                                            'min' => 0,
                                            'max' => 100,
                                        ],
                                        '%' => [
                                            'min' => 0,
                                            'max' => 100,
                                        ],
                                    ],
                                'selectors' => [
                                        $selector => 'font-size: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );
                
                if (in_array('image_fit_control', $options)) {


                    $this->add_control(
                            $type.'_image_fit_control'.$prefix,
                            [
                                    'label' => esc_html__( 'Fit', 'textdomain' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                            '' => esc_html__( 'Default', 'textdomain' ),
                                            'fill' => esc_html__( 'Fill', 'textdomain' ),
                                            'contain'  => esc_html__( 'Contain', 'textdomain' ),
                                            'cover' => esc_html__( 'Cover', 'textdomain' ),
                                            'none' => esc_html__( 'None', 'textdomain' ),
                                            'scale-down' => esc_html__( 'Scale down', 'textdomain' ),
                                    ],
                                    'selectors' => [
                                            $selector => 'object-fit: {{VALUE}};',
                                    ],
                            ]
                    );
                    $this->add_control(
                            $type.'_image_fit_control_position'.$prefix,
                            [
                                    'label' => esc_html__( 'Fit Position', 'textdomain' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                            '' => esc_html__( 'Default', 'textdomain' ),
                                            'top' => esc_html__( 'Top', 'textdomain' ),
                                            'bottom'  => esc_html__( 'Bottom', 'textdomain' ),
                                            'left' => esc_html__( 'Left', 'textdomain' ),
                                            'right' => esc_html__( 'Right', 'textdomain' ),
                                            'center' => esc_html__( 'Center', 'textdomain' ),
                                    ],
                                    'selectors' => [
                                            $selector => 'object-position: {{VALUE}};',
                                    ],
                            ]
                    );

            }
            
            if (in_array('css_filters', $options)) {
                $this->add_group_control(
                        \Elementor\Group_Control_Css_Filter::get_type(),
                        [
                                'name' => $type.'_css_filters'.$prefix,
                                'selector' => $selector,
                        ]
                );
            }

            if (in_array('background_group', $options)) {
                $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                                'name' => $type.'_background_group'.$prefix,
                                'label' => esc_html__( 'Background group', 'wpdirectorykit' ),
                                'types' => [ 'classic', 'gradient', 'video' ],
                                'selector' => $selector,
                        ]
                );
                $this->add_control(
                        $type.'_background_group_hr'.$prefix,
                        [
                                'type' => \Elementor\Controls_Manager::DIVIDER,
                        ]
                );
            }
                        
            if (in_array('hover_animation', $options) && $prefix == 'hover') {
                $this->add_control(
                        $type.'_hover_animation'.$prefix,
                        [
                                'label' => esc_html__( 'Hover Animation', 'wpdirectorykit' ),
                                'type' => \Elementor\Controls_Manager::SELECT2,
                                'multiple' => false,
                                'options' => [
                                        'grow'  => esc_html__( 'Grow', 'wpdirectorykit' ),
                                        'shrink'  => esc_html__( 'Shrink', 'wpdirectorykit' ),
                                        'pulse'  => esc_html__( 'Pulse', 'wpdirectorykit' ),
                                        'pulse-grow'  => esc_html__( 'Pulse Grow', 'wpdirectorykit' ),
                                        'pulse-shrink'  => esc_html__( 'Pulse Shrink', 'wpdirectorykit' ),
                                        'push'  => esc_html__( 'Push', 'wpdirectorykit' ),
                                        'pop'  => esc_html__( 'Pop', 'wpdirectorykit' ),
                                        'bounce-in'  => esc_html__( 'Bounce In', 'wpdirectorykit' ),
                                        'bounce-out'  => esc_html__( 'Bounce Out', 'wpdirectorykit' ),
                                        'rotate'  => esc_html__( 'Rotate', 'wpdirectorykit' ),
                                        'grow-rotate'  => esc_html__( 'Grow Rotate', 'wpdirectorykit' ),
                                        'float'  => esc_html__( 'Float', 'wpdirectorykit' ),
                                        'sink'  => esc_html__( 'Sink', 'wpdirectorykit' ),
                                        'bob'  => esc_html__( 'Bob', 'wpdirectorykit' ),
                                        'hang'  => esc_html__( 'Hang', 'wpdirectorykit' ),
                                        'skew'  => esc_html__( 'Skew', 'wpdirectorykit' ),
                                        'skew-forward'  => esc_html__( 'Skew Forward', 'wpdirectorykit' ),
                                        'skew-backward'  => esc_html__( 'Skew Backward', 'wpdirectorykit' ),
                                        'wobble-vertical'  => esc_html__( 'Wobble Vertical', 'wpdirectorykit' ),
                                        'wobble-horizontal'  => esc_html__( 'wobble Horizontal', 'wpdirectorykit' ),
                                        'wobble-to-bottom-right'  => esc_html__( 'Wobble To Bottom Right', 'wpdirectorykit' ),
                                        'wobble-to-top-right'  => esc_html__( 'Wobble To Top Eight', 'wpdirectorykit' ),
                                        'wobble-top'  => esc_html__( 'Wobble Top', 'wpdirectorykit' ),
                                        'wobble-bottom'  => esc_html__( 'Wobble Bottom', 'wpdirectorykit' ),
                                        'wobble-skew'  => esc_html__( 'Wobble Skew', 'wpdirectorykit' ),
                                        'buzz'  => esc_html__( 'Buzz', 'wpdirectorykit' ),
                                        'buzz-out'  => esc_html__( 'Buzz Out', 'wpdirectorykit' ),
                                ],
                                'selectors_dictionary' => [
                                        'grow'  => 'animation-name: eli-hover-animation-grow;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: 1;',
                                        'shrink'  => 'animation-name: eli-hover-animation-shrink;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: 1;',
                                        'pulse'  => 'animation-name: eli-hover-animation-pulse;animation-duration: 1s; animation-timing-function: linear;animation-iteration-count: infinite;',
                                        'pulse-grow'  => 'animation-name: eli-hover-animation-pulse-grow; animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: infinite;animation-direction: alternate;',
                                        'pulse-shrink'  => 'animation-name: eli-hover-animation-pulse-shrink;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: infinite;animation-direction: alternate;',
                                        'push'  => 'animation-name: eli-hover-animation-push;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: 1;',
                                        'pop'  => 'animation-name: eli-hover-animation-pop;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: 1;',
                                        'bounce-in'  => 'animation-name: eli-hover-animation-bounce-in;animation-duration: 0.5s;animation-timing-function:  cubic-bezier(0.47, 2.02, 0.31, -0.36);;animation-iteration-count: 1;',
                                        'bounce-out'  => 'animation-name: eli-hover-animation-bounce-out;animation-duration: 0.5s;animation-timing-function:  cubic-bezier(0.47, 2.02, 0.31, -0.36);animation-iteration-count: 1;',
                                        'rotate'  => 'animation-name: eli-hover-animation-rotate;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: 1;',
                                        'grow-rotate'  => 'animation-name: eli-hover-animation-grow;animation-duration: 0.3s;animation-timing-function: linear;animation-iteration-count: 1;',
                                        'float'  => 'animation-name: eli-hover-animation-float;animation-duration: 0.3s;animation-timing-function: ease-out;animation-iteration-count: 1;',
                                        'sink'  => 'animation-name: eli-hover-animation-sink;animation-duration: 0.3s;animation-timing-function: ease-out;animation-iteration-count: 1;',
                                        'bob'  => 'animation-name: eli-hover-animation-bob-float, eli-hover-animation-bob;animation-duration: .3s, 1.5s;animation-delay: 0s, .3s;animation-timing-function: ease-out, ease-in-out;animation-iteration-count: 1, infinite;animation-fill-mode: forwards;animation-direction: normal, alternate;',
                                        'hang'  => 'animation-name: eli-hover-animation-hang-sink, eli-hover-animation-hang;animation-duration: .3s, 1.5s;animation-delay: 0s, .3s;animation-timing-function: ease-out, ease-in-out;animation-iteration-count: 1, infinite;animation-fill-mode: forwards;animation-direction: normal, alternate;',
                                        'skew'  => 'animation-name: eli-hover-animation-skew;animation-duration: 0.3s;animation-timing-function: ease-out;animation-iteration-count: 1;',
                                        'skew-forward'  => 'animation-name: eli-hover-animation-skew-forward;animation-duration: 0.3s;animation-timing-function: ease-out;animation-iteration-count: 1;transform-origin: 0 100%;',
                                        'skew-backward'  => 'animation-name: eli-hover-animation-skew-backward;animation-duration: 0.3s;animation-timing-function: ease-out;animation-iteration-count: 1;transform-origin: 0 100%;',
                                        'wobble-vertical'  => 'animation-name: eli-hover-animation-wobble-vertical;animation-duration: 1s;animation-timing-function: ease-in-out; animation-iteration-count: 1;',
                                        'wobble-horizontal'  => 'animation-name: eli-hover-animation-wobble-horizontal; animation-duration: 1s;animation-timing-function: ease-in-out;animation-iteration-count: 1;',
                                        'wobble-to-bottom-right'  => 'animation-name: eli-hover-animation-wobble-to-bottom-right;animation-duration: 1s;animation-timing-function: ease-in-out;animation-iteration-count: 1;',
                                        'wobble-to-top-right'  => 'animation-name: eli-hover-animation-wobble-to-top-right;animation-duration: 1s;animation-timing-function: ease-in-out;animation-iteration-count: 1;',
                                        'wobble-top'  => 'transform-origin: 0 100%;animation-name: eli-hover-animation-wobble-top;animation-duration: 1s;animation-timing-function: ease-in-out;animation-iteration-count: 1;',
                                        'wobble-bottom'  => 'transform-origin: 100% 0;animation-name: eli-hover-animation-wobble-bottom;animation-duration: 1s;animation-timing-function: ease-in-out;animation-iteration-count: 1;',
                                        'wobble-skew'  => 'animation-name: eli-hover-animation-wobble-skew;animation-duration: 1s;animation-timing-function: ease-in-out;animation-iteration-count: 1;',
                                        'buzz'  => 'animation-name: eli-hover-animation-buzz;animation-duration: 0.15s;animation-timing-function: linear;animation-iteration-count: infinite;',
                                        'buzz-out'  => 'animation-name: eli-hover-animation-buzz-out;animation-duration: 0.75s;animation-timing-function: linear;animation-iteration-count: 1;',
                                ],
                                'default' => '',
                                'selectors' => [
                                        $selector => '{{VALUE}};',
                                ],
                                'render_type' => 'template'
                        ]
                );
            }

                if (in_array('image_size_control', $options)) {
                        $this->add_responsive_control(
                             $type.'_image_size_control_max_heigth'.$prefix,
                            [
                                'label' => esc_html__('Max Height', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'max-height: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                
                        $this->add_responsive_control(
                             $type.'_image_size_control_max_width'.$prefix,
                            [
                                'label' => esc_html__('Max Width', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'max-width: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                
                        $this->add_responsive_control(
                             $type.'_image_size_control_heigth'.$prefix,
                            [
                                'label' => esc_html__('Height', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'height: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                
                        $this->add_responsive_control(
                             $type.'_image_size_control_width'.$prefix,
                            [
                                'label' => esc_html__('Width', 'wpdirectorykit'),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                    'px' => [
                                        'min' => 10,
                                        'max' => 1500,
                                    ],
                                    'vw' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                    '%' => [
                                        'min' => 0,
                                        'max' => 100,
                                    ],
                                ],
                                'size_units' => [ 'px', 'vw','%' ],
                                'selectors' => [
                                    $selector => 'width: {{SIZE}}{{UNIT}}',
                                ],
                                
                            ]
                        );
                    }
                if($tabs_enable)
                    $this->end_controls_tab();
            }
            
        
        /**
	 * @param $post_css Post
	 */
	public function add_page_settings_css() {
                $settings = $this->get_settings();
		$custom_css = $settings['custom_css'];
		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}
                
		// Add a css comment
		$custom_css_file = '/* Start custom CSS for page-settings */' . 
                        str_replace( 'selector', '#elementinvader_addons_for_elementor_' . $this->get_id_int(), $custom_css ) . 
                        str_replace( 'selector', '#eli_' . $this->get_id_int(), $custom_css ).
                        str_replace( 'selector', '.elementor-element.elementor-element-' . $this->get_id(), $custom_css ).
                    '/* End custom CSS */';

        wp_enqueue_style('eli-custom-inline', plugins_url( '/assets/css/custom-inline.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
        wp_add_inline_style( 'eli-custom-inline',  $custom_css_file );
	}
        
        public function _ch(&$var, $empty = '')
        {
            if(empty($var))
                return $empty;

            return $var;
        }
        
        public function esc_viewe($content) {
            // @codingStandardsIgnoreStart
            if(function_exists('sw_win_viewe'))
                sw_win_viewe($content); // WPCS: XSS ok, sanitization ok.
            // @codingStandardsIgnoreEnd
        }
        
        private function break_css($css)
        {

            $results = array();

            preg_match_all('/(.+?)\s?\{\s?(.+?)\s?\}/', $css, $matches);
            foreach($matches[0] AS $i=>$original)
                foreach(explode(';', $matches[2][$i]) AS $attr)
                    if (strlen(trim($attr)) > 0) // for missing semicolon on last element, which is legal
                    {
                        list($name, $value) = explode(':', $attr);
                        $results[$matches[1][$i]][trim($name)] = trim($value);
                    }
            return $results;
        }

    function sw_count($mixed='') {
        $count = 0;

        if(!empty($mixed) && (is_array($mixed))) {
            $count = count($mixed);
        } else if(!empty($mixed) && function_exists('is_countable') && version_compare(PHP_VERSION, '7.3', '<') && is_countable($mixed)) {
            $count = count($mixed);
        }
        else if(!empty($mixed) && is_object($mixed)) {
            $count = 1;
        }
        return $count;
    }
    
    function _js($str='')
    {
        $str = str_replace("\\", "", trim($str));
        $str = str_replace("'", "\'", trim($str));
        $str = str_replace('"', '\"', $str);
        
        return $str;
    }

    
    public function generate_icon($icon, $attributes = [], $tag = 'i' ){
                if ( empty( $icon['library'] ) ) {
                        return false;
                }
                $output = '';
                // handler SVG Icon
                if ( 'svg' === $icon['library'] ) {
                        $output = \Elementor\Icons_Manager::render_uploaded_svg_icon( $icon['value'] );
                } else {
                        $output = $this->render_icon_html( $icon, $attributes, $tag );
                }

                return $output;
        }

        public function render_icon_html( $icon, $attributes = [], $tag = 'i' ) {
                $icon_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
                if ( isset( $icon_types[ $icon['library'] ]['render_callback'] ) && is_callable( $icon_types[ $icon['library'] ]['render_callback'] ) ) {
                        return call_user_func_array( $icon_types[ $icon['library'] ]['render_callback'], [ $icon, $attributes, $tag ] );
                }

                if ( empty( $attributes['class'] ) ) {
                        $attributes['class'] = $icon['value'];
                } else {
                        if ( is_array( $attributes['class'] ) ) {
                                $attributes['class'][] = $icon['value'];
                        } else {
                                $attributes['class'] .= ' ' . $icon['value'];
                        }
                }
                return '<' . $tag . ' ' . Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
        }

        public static function render_svg_icon( $value ) {
                if ( ! isset( $value['id'] ) ) {
                        return '';
                }

                return Svg_Handler::get_inline_svg( $value['id'] );
        }


        public function is_edit_mode_load() {

                if (isset($this->is_edit_mode) &&  null !== $this->is_edit_mode ) {
                        return $this->is_edit_mode;
                }

                // Ajax request as Editor mode
                $actions = array(
                        'elementor',
                        // Templates
                        'elementor_get_templates',
                        'elementor_save_template',
                        'elementor_get_template',
                        'elementor_delete_template',
                        'elementor_export_template',
                        'elementor_import_template',
                );

                if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $actions)) {
                        return true;
                }

                if (isset($_REQUEST['elementor-preview'])) {
                        return true;
                }
       
                return false;
        }

        public function enqueue_styles_scripts() {
    
        }

}
