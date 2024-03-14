<?php

namespace Element_Ready\Modules\Menu_Builder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Element_Ready\Modules\Menu_Builder\Base\Offcanvas_Nav_Walker as Offcanvas_Nav_Walker;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/content_controls/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php' );

if ( ! defined( 'ABSPATH' ) ) exit;

class Restuarent_Menu extends Widget_Base {

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;

    public $base;

    public function get_name() {
        return 'element-ready-menu-restuarant';
    }
    public function get_keywords() {
		return ['element ready','menu','nav','navigation','restaurant'];
	}
    public function get_title() {
        return esc_html__( 'ER Round Menu', 'element-ready-lite' );
    }

    public function get_icon() { 
        return 'eicon-menu-toggle';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }
    public function get_style_depends() {
        return [
            'er-round-menu'
        ];
    }
    public function get_script_depends() {
        return [
            'er-round-menu'
        ];
    }
    public function layout(){
        return[
           
            'style9'   => esc_html__( 'Round Menu', 'element-ready-lite' ),
        ];
    }

    public function menu_list(){

        $return_menus = [];
        
        $menus = wp_get_nav_menus(); 
       
        if(is_array($menus)){
           foreach($menus as $menu) {
            $return_menus[$menu->term_id] = $menu->name;  
           }
        }
        return $return_menus;
    }
   
    protected function register_controls() {

        $this->start_controls_section(
			'menu_layout',
			[
				'label' => esc_html__( 'Layout', 'element-ready-lite' ),
			]
        );

            $this->add_control(
                'menu_style',
                [
                    'label' => esc_html__( 'Style', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'style9',
                    'options' => $this->layout()
                ]
            );

        $this->end_controls_section();
        $this->start_controls_section(
			'header_humber_section',
			[
                'label' => esc_html__( 'Header Position', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style8','style9']
                ],
			]
        );

            $this->add_responsive_control(
                'header_nav_position_type',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed'    => esc_html__('Fixed','element-ready-lite'),
                        'absolute' => esc_html__('Absolute','element-ready-lite'),
                        'relative' => esc_html__('Relative','element-ready-lite'),
                        'sticky'   => esc_html__('Sticky','element-ready-lite'),
                        'static'   => esc_html__('Static','element-ready-lite'),
                        'inherit'  => esc_html__('inherit','element-ready-lite'),
                        ''         => esc_html__('none','element-ready-lite'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .main-header-9' => 'position: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'header_nav_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .main-header-9' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'header_nav_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .main-header-9' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'header_nav_position_bottom',
                [
                    'label' => esc_html__( 'Position bottom', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .main-header-9' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'header_nav_position_right',
                [
                    'label' => esc_html__( 'Position right', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .main-header-9' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        $this->start_controls_section(
			'header_logo_section',
			[
                'label' => esc_html__( 'Header logo', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style1','style2','style3','style4','style9','style8']
                ],
			]
        );


                $this->add_control(
                    'header_logo_enable',
                    [
                        'label' => esc_html__( 'Enable', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                        'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default' => 'no',
                    ]
                );

                $this->add_control(
                    'header_logo_type',
                    [
                        'label' => esc_html__( 'Logo Type', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => 'logo',
                        'options' => [
                            'logo' => esc_html__('Image Logo','element-ready-lite'),
                            'text' => esc_html__('Text Logo','element-ready-lite'),
                            'svg' => esc_html__('SVG Logo','element-ready-lite'),
                        ]
                    ]
                );
                
                $this->add_control(
                    'header_logo',
                    [
                        'label' => esc_html__( 'Choose logo', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        'condition' => [
                            'header_logo_type' => ['logo']
                        ],
                    ]
                );

                $this->add_control(
                    'header_svg_logo',
                    [
                        'label' => esc_html__( 'Svg logo', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'condition' => [
                            'header_logo_type' => ['svg']
                        ],
                    ]
                );

                $this->add_control(
                    'header_text_logo',
                    [
                        'label' => esc_html__( 'Text Logo', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__( 'Logo', 'element-ready-lite' ),
                        'placeholder' => esc_html__( 'Type your title here', 'element-ready-lite' ),
                    ]
                );
        
                $this->add_control(
                    'header_website_link',
                    [
                        'label' => esc_html__( 'Link', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => esc_html__( 'https://your-link.com', 'element-ready-lite' ),
                        'default' => [
                            'url' => home_url('/'),
                        ],
                    ]
                );

        $this->end_controls_section();

        $this->start_controls_section(
			'header_sticky_logo_section',
			[
                'label' => esc_html__( 'Header Sticky', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style9','style8']
                ],
			]
        );


                $this->add_control(
                    'header_sticky_logo_enable',
                    [
                        'label' => esc_html__( 'Enable', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                        'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default' => 'yes',
                    ]
                );

                $this->add_control(
                    'header_sticky_logo_type',
                    [
                        'label' => esc_html__( 'Logo Type', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => 'logo',
                        'options' => [
                            'logo' => esc_html__('Image Logo','element-ready-lite'),
                            'text' => esc_html__('Text Logo','element-ready-lite'),
                            'svg' => esc_html__('SVG Logo','element-ready-lite'),
                        ]
                    ]
                );
                
                $this->add_control(
                    'header_sticky_logo',
                    [
                        'label' => esc_html__( 'Choose logo', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        'condition' => [
                            'header_sticky_logo_type' => ['logo']
                        ],
                    ]
                );

                $this->add_control(
                    'header_sticky_svg_logo',
                    [
                        'label' => esc_html__( 'Svg logo', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'condition' => [
                            'header_sticky_logo_type' => ['svg']
                        ],
                    ]
                );

                $this->add_control(
                    'header_sticky_text_logo',
                    [
                        'label' => esc_html__( 'Text Logo', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__( 'Logo', 'element-ready-lite' ),
                        'placeholder' => esc_html__( 'Type your title here', 'element-ready-lite' ),
                    ]
                );
        
               

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Settings', 'element-ready-lite'),
            ]
        );

        $this->start_controls_tabs(
			'menu_type_tabs'
		);

            $this->start_controls_tab(
                'style_main_menu_tab',
                [
                    'label' => esc_html__( 'Main Menu', 'element-ready-lite' ),
                ]
            );
                
                $this->add_control(
                    'enable_mega_menu_content',
                    [
                        'label' => esc_html__( 'Mega Menu Content', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                        'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default' => '',
                    ]
                );

                $this->add_control(
                    'enable_meta_content',
                    [
                        'label' => esc_html__( 'Meta Content', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                        'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default' => '',
                        'condition' => [
                            'enable_mega_menu_content' => [''],
                            'menu_style' => ['style1']
                        ]
                    ]
                );
               

                $this->add_control(
                    'menu_selected',
                    [
                        'label' => esc_html__( 'Menu', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => $this->menu_list()
                    ]
                );


                $this->add_control(
                    'menu_depth',
                    [
                        'label'   => esc_html__( 'Nested Depth', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::NUMBER,
                        'min'     => 1,
                        'max'     => 10,
                        'step'    => 1,
                        'default' => 3
                    ]
                );

                $this->add_control(
                    'menu_drop_icon',
                    [
                        'label'   => esc_html__( 'Drop Down Icons', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::ICON,
                        'default' => 'fa fa-angle-down'
                    ]
                );

                $this->add_control(
                    'submenu_indecator_icon',
                    [
                        'label'   => esc_html__( 'Submenu Icons', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::ICON,
                        'default' => 'eicon-text-align-right'
                    ]
                );

                $this->add_control(
                    'custom_continer_element_popover-toggle',
                    [
                        'label'        => esc_html__( 'Extra option', 'element-ready-lite' ),
                        'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                        'label_off'    => esc_html__( 'Default', 'element-ready-lite' ),
                        'label_on'     => esc_html__( 'Custom', 'element-ready-lite' ),
                        'return_value' => 'yes',
                    ]
                );

                $this->start_popover();

                $this->add_control(
                    'wrapper_tag_type',
                        [
                            'label'   => esc_html__( 'Container Tag', 'element-ready-lite' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'description' => esc_html__('Menu Main Wrapper container Html tag','element-ready-lite'),
                            'options' => [
                                ''     => esc_html__( 'none', 'element-ready-lite' ),
                                'div'  => esc_html__( 'div', 'element-ready-lite' ),
                                'p'    => esc_html__( 'p', 'element-ready-lite' ),
                                'span' => esc_html__( 'span', 'element-ready-lite' ),
                                'i'    => esc_html__( 'i', 'element-ready-lite' ),
                                's'    => esc_html__( 's', 'element-ready-lite' ),
                                'b'    => esc_html__( 'b', 'element-ready-lite' ),
                                'p'    => esc_html__( 'P', 'element-ready-lite' ),
                                'ul'   => esc_html__( 'ul', 'element-ready-lite' ),
                            ],
                        ]
                );

                $this->add_control(
                    'anchore_wrapper_tag_before_type',
                        [
                            'label'   => esc_html__( 'Link Wrapper Before', 'element-ready-lite' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'description' => esc_html__('Menu Link Html tag','element-ready-lite'),
                            'options' => [
                                ''  => esc_html__( 'none', 'element-ready-lite' ),
                                '<div>'  => esc_html__( 'div', 'element-ready-lite' ),
                                '<p>'    => esc_html__( 'p', 'element-ready-lite' ),
                                '<span>' => esc_html__( 'span', 'element-ready-lite' ),
                                '<i>'    => esc_html__( 'i', 'element-ready-lite' ),
                                '<s>'    => esc_html__( 's', 'element-ready-lite' ),
                                '<b>'    => esc_html__( 'b', 'element-ready-lite' ),
                                '<p>'    => esc_html__( 'P', 'element-ready-lite' ),
                            ],
                        ]
                );

                $this->add_control(
                    'anchore_wrapper_tag_after_type',
                        [
                            'label'   => esc_html__( 'Link Wrapper After', 'element-ready-lite' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'options' => [
                                ''  => esc_html__( 'none', 'element-ready-lite' ),
                                '</div>'  => esc_html__( 'div', 'element-ready-lite' ),
                                '</p>'    => esc_html__( 'p', 'element-ready-lite' ),
                                '</span>' => esc_html__( 'span', 'element-ready-lite' ),
                                '</i>'    => esc_html__( 'i', 'element-ready-lite' ),
                                '/<s>'    => esc_html__( 's', 'element-ready-lite' ),
                                '</b>'    => esc_html__( 'b', 'element-ready-lite' ),
                                '</p>'    => esc_html__( 'P', 'element-ready-lite' ),
                            ],
                        ]
                );

                $this->add_control(
                    'anchore_text_before_tag_type',
                        [
                            'label'   => esc_html__( ' Link Text before', 'element-ready-lite' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'options' => [
                                ''  => esc_html__( 'none', 'element-ready-lite' ),
                                '<div>'  => esc_html__( 'div', 'element-ready-lite' ),
                                '<p>'    => esc_html__( 'p', 'element-ready-lite' ),
                                '<span>' => esc_html__( 'span', 'element-ready-lite' ),
                                '<i>'    => esc_html__( 'i', 'element-ready-lite' ),
                                '<s>'    => esc_html__( 's', 'element-ready-lite' ),
                                '<b>'    => esc_html__( 'b', 'element-ready-lite' ),
                                '<p>'    => esc_html__( 'P', 'element-ready-lite' ),
                            ],
                        ]
                );

                $this->add_control(
                    'anchore_text_after_tag_type',
                        [
                            'label'   => esc_html__( 'Link Text after', 'element-ready-lite' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'options' => [
                                ''  => esc_html__( 'none', 'element-ready-lite' ),
                                '</div>'  => esc_html__( 'div', 'element-ready-lite' ),
                                '</p>'    => esc_html__( 'p', 'element-ready-lite' ),
                                '</span>' => esc_html__( 'span', 'element-ready-lite' ),
                                '</i>'    => esc_html__( 'i', 'element-ready-lite' ),
                                '</s>'    => esc_html__( 's', 'element-ready-lite' ),
                                '</b>'    => esc_html__( 'b', 'element-ready-lite' ),
                                '</p>'    => esc_html__( 'P', 'element-ready-lite' ),
                            ],
                        ]
                );

                $this->end_popover();

                $this->add_control(
                    'custom_element_popover-toggle',
                    [
                        'label' => esc_html__( 'Custom Element Attribute', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                        'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                        'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                        'return_value' => 'yes',
                    ]
                );

                $this->start_popover();

                    $this->add_control(
                        'menu_container_custom_class',
                        [
                            'label' => esc_html__( 'Container Class', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'placeholder' => esc_html__( '.custom-container custom-size', 'element-ready-lite' ),
                        ]
                    );

                    $this->add_control(
                        'menu_container_custom_id',
                        [
                            'label' => esc_html__( 'Container Id', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'placeholder' => esc_html__( 'custom-id', 'element-ready-lite' ),
                        ]
                    );

                    $this->add_control(
                        'menu_custom_class',
                        [
                            'label' => esc_html__( 'Menu Class', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'placeholder' => esc_html__( '.menu-custom .type', 'element-ready-lite' ),
                        ]
                    );

                    $this->add_control(
                        'menu_custom_id',
                        [
                            'label' => esc_html__( 'Menu Id', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'placeholder' => esc_html__( 'custom-id', 'element-ready-lite' ),
                        ]
                    );

                $this->end_popover();

                $this->add_responsive_control(
                    'main_menu_title_align', [
                        'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [

                    'left'		 => [
                        
                        'title' => esc_html__( 'Left', 'element-ready-lite' ),
                        'icon'  => 'fa fa-align-left',
                    
                    ],
                        'center'	     => [
                        
                        'title' => esc_html__( 'Center', 'element-ready-lite' ),
                        'icon'  => 'fa fa-align-center',
                    
                    ],
                    'right'	 => [

                        'title' => esc_html__( 'Right', 'element-ready-lite' ),
                        'icon'  => 'fa fa-align-right',
                        
                    ],
                    
                    'justify'	 => [

                    'title' => esc_html__( 'Justified', 'element-ready-lite' ),
                    'icon'  => 'fa fa-align-justify',
                    
                            ],
                    ],
                    
                    'selectors' => [
                            '{{WRAPPER}} .ab-content'   => 'text-align: {{VALUE}};',
                        ],
                    ]
                );//Responsive control end


        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'offcanvas_style_main_menu_tab',
            [
                'label' => esc_html__( 'OffCanvas', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style8','style9']
                ]
            ]
        );
            
            $this->add_control(
                'offcanvas_enable',
                [
                    'label' => esc_html__( 'Offcanvas Enable', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                    'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );
 

            $this->add_control(
                'offcanvas_template_id',
                [
                    'label'     => esc_html__( 'Select Content Template', 'appscred-essential' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => '0',
                    'options'   => element_ready_elementor_template(),
                    'description' => esc_html__( 'Please select elementor templete from here, if not create elementor template from menu', 'element-ready-lite' )
                   
                ]
            );
    
            
            $this->add_control(
                'offcanvas_menu_icon',
                [
                    'label' => esc_html__( 'Icon', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                ]
            );
     
        $this->end_controls_tab();
       
		$this->start_controls_tab(
			'style_main_menu2_menu_tab',
			[
                'label' => esc_html__( 'Menu 2', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style8']
                ]
			]
        );
        
        $this->add_control(
            'main_menu2_enable_mega_menu_content',
            [
                'label' => esc_html__( 'Mega Menu Content', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'main_menu2_selected',
            [
                'label' => esc_html__( 'Menu', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->menu_list()
            ]
        );
        


        $this->add_control(
            'main_menu2_depth',
            [
                'label' => esc_html__( 'Nested Depth', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->add_control(
            'main_menu2_drop_icon',
            [
                'label' => esc_html__( 'Drop Down Icons', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-angle-down',
            ]
        );

        $this->add_control(
            'main_menu2_custom_element_main_popover-toggle',
            [
                'label' => esc_html__( 'Extra option', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'main_menu2_wrapper_tag_type',
                [
                    'label'   => esc_html__( 'Container Tag', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        'div'  => esc_html__( 'div', 'element-ready-lite' ),
                        'p'    => esc_html__( 'p', 'element-ready-lite' ),
                        'span' => esc_html__( 'span', 'element-ready-lite' ),
                        'i'    => esc_html__( 'i', 'element-ready-lite' ),
                        's'    => esc_html__( 's', 'element-ready-lite' ),
                        'b'    => esc_html__( 'b', 'element-ready-lite' ),
                        'p'    => esc_html__( 'P', 'element-ready-lite' ),
                        'ul'    => esc_html__( 'ul', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'main_menu2_anchore_wrapper_tag_before_type',
                [
                    'label'   => esc_html__( 'Link Wrapper Before', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '<div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '<span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '<i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '<s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '<b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'main_menu2_anchore_wrapper_tag_after_type',
                [
                    'label'   => esc_html__( 'Link Wrapper After', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '</div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '</span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '</i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '/<s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '</b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'main_menu2_anchore_text_before_tag_type',
                [
                    'label'   => esc_html__( ' Link Text before', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '<div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '<span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '<i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '<s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '<b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'main_menu2_anchore_text_after_tag_type',
                [
                    'label'   => esc_html__( 'Link Text after', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '</div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '</span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '</i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '</s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '</b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->end_popover();

        $this->add_control(
            'main_menu2_custom_element_popover-toggle',
            [
                'label' => esc_html__( 'Extra Element', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

            $this->add_control(
                'main_menu2_container_custom_class',
                [
                    'label' => esc_html__( 'Container Class', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( '.prefix-custom-container', 'element-ready-lite' ),
                ]
            );

            $this->add_control(
                'main_menu2_container_custom_id',
                [
                    'label' => esc_html__( 'Container Id', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( 'custom-id', 'element-ready-lite' ),
                ]
            );

            $this->add_control(
                'main_menu2_custom_class',
                [
                    'label' => esc_html__( 'Menu Class', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( '.custom-menu-cls .type', 'element-ready-lite' ),
                ]
            );

            $this->add_control(
                'main_menu2_custom_id',
                [
                    'label' => esc_html__( 'Menu Id', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( 'custom-id', 'element-ready-lite' ),
                ]
            );

        $this->end_popover();

        $this->add_responsive_control(
            'main_menu2_menu_align', [
                'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [

            'left'		 => [
                
                'title' => esc_html__( 'Left', 'element-ready-lite' ),
                'icon'  => 'fa fa-align-left',
            
            ],
                'center'	     => [
                
                'title' => esc_html__( 'Center', 'element-ready-lite' ),
                'icon'  => 'fa fa-align-center',
            
            ],
            'right'	 => [

                'title' => esc_html__( 'Right', 'element-ready-lite' ),
                'icon'  => 'fa fa-align-right',
                
            ],
            
            'justify'	 => [

            'title' => esc_html__( 'Justified', 'element-ready-lite' ),
            'icon'  => 'fa fa-align-justify',
            
                    ],
            ],
            
            'selectors' => [
                    '{{WRAPPER}} ul li'   => 'text-align: {{VALUE}};',
                ],
            ]
        );//Responsive control end


		$this->end_controls_tab();

		$this->end_controls_tabs();


           
        $this->end_controls_section();
        $this->start_controls_section(
			'header_button_section',
			[
                'label' => esc_html__( 'Header button', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style3','style4']
                ],
			]
        );

        $this->add_control(
            'header_button_enable',
            [
                'label' => esc_html__( 'Enable', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'element-ready-lite' ),
                'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'header_button_text',
            [

                'label' => esc_html__( 'Text', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Get Strted', 'element-ready-lite' ),
                'default' => esc_html__('Get Started','element-ready-lite')
                
            ]
        );

        $this->add_control(
            'header_button_link',
            [
                'label' => esc_html__( 'Link', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'element-ready-lite' ),
                'default' => [
                    'url' => home_url('/'),
                ],
            ]
        );


        $this->end_controls_section();


        $this->box_css(
            array(
                'title' => esc_html__('Menu Wrapper','element-ready-lite'),
                'slug' => 'wrapper_menu__box_style',
                'element_name' => 'wrapper_menu_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9',
              
               
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Menu Item wrapper','element-ready-lite'),
                'slug' => 'menu_item__wrapper_select_style',
                'element_name' => 'menu_wrape_item_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list > li',
               
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Menu Item','element-ready-lite'),
                'slug' => 'menu_item_select_style',
                'element_name' => 'menu_item_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list > li > a',
                'hover_selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list > li:hover > a',
               
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Menu Dropdown Item','element-ready-lite'),
                'slug' => 'menu_dropdown_item_select_style',
                'element_name' => 'menu_dropdown_item_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list li ul li a',
                'hover_selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list li ul li:hover a',
               
            )
        );
      


        $this->box_css(
            array(
                'title' => esc_html__('Submenu Item Container','element-ready-lite'),
                'slug' => 'wrapper_submenu_li_menu_background_box_style',
                'element_name' => 'wrapper_submenu_li_body_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list li ul li',
              
               
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Submenu Wrapper','element-ready-lite'),
                'slug' => 'wrapper_submenu_menu_background_box_style',
                'element_name' => 'wrapper_submenu_body_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list li ul',
              
               
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Sub Menu Icon','element-ready-lite'),
                'slug' => 'menu_dropdown_icon_item_select_style',
                'element_name' => 'menu_dropdown_icon_item_element_ready_',
                'selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list li ul li a i',
                'hover_selector' => '{{WRAPPER}} .main-menu-9 .main-menu__list li ul li:hover a i',
               
            )
        );
        
        $this->box_css(
            array(
                'title' => esc_html__('Menu Background','element-ready-lite'),
                'slug' => 'wrapper_menu_background_box_style',
                'element_name' => 'wrapper_body_element_ready_',
                'selector' => '{{WRAPPER}} .main-header-9 .inner-container::before',
              
               
            )
        );

        
     
    } //Register control end

    function _get_menu_array($current_menu,$nested = true) {
 
        $array_menu = wp_get_nav_menu_items($current_menu);
        $menu = array();
        if(!is_array($array_menu)){
            return [];
        }

        foreach ($array_menu as $m) {

            if (empty($m->menu_item_parent)) {
                $menu[$m->ID] = array();
                $menu[$m->ID]['ID']      =   $m->ID;
                $menu[$m->ID]['title']       =   $m->title;
                $menu[$m->ID]['url']         =   $m->url;
                $menu[$m->ID]['children']    =   array();
            }

        }

        if($nested):
            $submenu = array();
            foreach ($array_menu as $m) {
                if ($m->menu_item_parent) {
                    $submenu[$m->ID] = array();
                    $submenu[$m->ID]['ID']       =   $m->ID;
                    $submenu[$m->ID]['title']    =   $m->title;
                    $submenu[$m->ID]['url']  =   $m->url;
                    $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
                }
            }
        endif;
        return $menu;
         
    }
     
    protected function render( ) { 

        $settings       = $this->get_settings();
        $menu_id        = $settings['menu_selected'];
        $main_menu2_id = $settings['main_menu2_selected'];
     
        if($main_menu2_id ==''){

            $main_menu2_id =  $menu_id;
        }

       
        $nav_walker_default = [

            'mega_menu_content' => $settings['enable_mega_menu_content'] ,
            'enable_meta_content' => $settings['enable_meta_content'] ,
        
        ]; 
        
        $nav_walker_default2 = [

            'mega_menu_content' => $settings['main_menu2_enable_mega_menu_content'] ,
            'enable_meta_content' => $settings['enable_meta_content'] ,
        
        ];
        
        if($settings['menu_drop_icon'] !=''){
            $nav_walker_default['menu_drop_icon'] = $settings['menu_drop_icon'];
        }
        
        if($settings['main_menu2_drop_icon'] !=''){
            $nav_walker_default2['menu_drop_icon'] = $settings['main_menu2_drop_icon'];
        }
        if($settings['submenu_indecator_icon'] !=''){
            $nav_walker_default['submenu_indicator_icon'] = $settings['submenu_indecator_icon'];
        }
          
        $args = [
            'menu'            => $menu_id,
            'container'       => $settings['wrapper_tag_type'] !=''?$settings['wrapper_tag_type']:false,
            'container_id'    => $settings['menu_container_custom_id'] !=''?$settings['menu_container_custom_id']:false,
            'container_class' => $settings['menu_container_custom_class'] !=''?$settings['menu_container_custom_class']:false,
            'menu_class'      => $settings['menu_custom_class']==''?'fsmenu--list':$settings['menu_custom_class'],
            'depth'           => $settings['menu_depth'],
            'walker'          => new Offcanvas_Nav_Walker( $nav_walker_default ),
            
        ]; 
        
        $args_2 = [
            'menu'            => $main_menu2_id,
            'container'       => $settings['main_menu2_wrapper_tag_type'] !=''?$settings['main_menu2_wrapper_tag_type']:false,
            'container_id'    => $settings['main_menu2_container_custom_id'] !=''?$settings['main_menu2_container_custom_id']:false,
            'container_class' => $settings['main_menu2_container_custom_class'] !=''?$settings['main_menu2_container_custom_class']:false,
            'menu_class'      => $settings['main_menu2_custom_class']==''?'fsmenu--list':$settings['main_menu2_custom_class'],
            'depth'           => $settings['main_menu2_depth'],
            'walker'          => new Offcanvas_Nav_Walker( $nav_walker_default2 ),
            
        ]; 
    
        if($settings['menu_custom_id'] !=''){
            $args['menu_id'] = $settings['menu_custom_id'];
        }
        
        if($settings['main_menu2_custom_id'] !=''){
            $args_2['main_menu2_id'] = $settings['main_menu2_custom_id'];
        }

        $link_before_after = element_ready_menu_html_tag_validate($settings['anchore_text_before_tag_type'],$settings['anchore_text_after_tag_type']);    
        
        if( $link_before_after){
            $args['link_before'] = $link_before_after['start'];
            $args['link_after'] = $link_before_after['end'];
        }
        
        $link_before_after_2 = element_ready_menu_html_tag_validate($settings['main_menu2_anchore_text_before_tag_type'],$settings['main_menu2_anchore_text_after_tag_type']);    
        
        if( $link_before_after_2){
            $args_2['link_before'] = $link_before_after_2['start'];
            $args_2['link_after'] = $link_before_after_2['end'];
        }
       $before_after = element_ready_menu_html_tag_validate($settings['anchore_wrapper_tag_before_type'],$settings['anchore_wrapper_tag_after_type']);    
       if($before_after){
            $args['before'] = $before_after['start'];
            $args['after'] = $before_after['end'];
        } 
       $before_after_2 = element_ready_menu_html_tag_validate($settings['main_menu2_anchore_wrapper_tag_before_type'],$settings['main_menu2_anchore_wrapper_tag_after_type']);    
       if($before_after_2){
            $args_2['before'] = $before_after_2['start'];
            $args_2['after'] = $before_after_2['end'];
        } 
       
    ?>    
         
       <!--====== Header START ======-->
        

        <?php if($settings['menu_style'] == 'style8'): ?>

            <?php $nav_walker_default[ 'menu_list_item_cls' ] = 'nav-item'; ?>
            <?php $args[ 'walker' ] = new Offcanvas_Nav_Walker( $nav_walker_default );?>
            <?php $args[ 'menu_class'] = $settings['menu_custom_class']==''?'navbar-nav m-auto':' navbar-nav m-auto '.$settings['menu_custom_class']; ?>
            
            <?php $nav_walker_default2[ 'menu_list_item_cls' ] = 'nav-item'; ?>
            <?php $args_2[ 'walker' ] = new Offcanvas_Nav_Walker( $nav_walker_default2 );?>
            <?php $args_2[ 'menu_class' ] = $settings['main_menu2_custom_class']==''?'navbar-nav m-auto':' navbar-nav m-auto '.$settings[ 'main_menu2_custom_class' ]; ?>
          
            <?php include('layout/menu/style8.php'); ?>   
        <?php endif; ?>

        <?php if($settings[ 'menu_style' ] == 'style9' ): ?>
            <?php $nav_walker_default[ 'menu_list_item_cls' ] = 'nav-item'; ?>
            <?php $args[ 'walker' ] = new Offcanvas_Nav_Walker( $nav_walker_default );?>
            <?php $args[ 'menu_class' ] = $settings[ 'menu_custom_class' ]==''?' main-menu__list ':' main-menu__list '.$settings[ 'menu_custom_class' ]; ?>
            <?php include('layout/menu/style9.php'); ?>   
        <?php endif; ?>
    <!--====== PART ENDS ======-->
    <?php  

    }
    protected function content_template() { }

}