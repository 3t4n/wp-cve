<?php
/**
 * Class: LaStudioKit_Nav_Menu
 * Name: Nav Menu
 * Slug: lakit-nav-menu
 */

namespace Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class LaStudioKit_Nav_Menu extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    $this->add_script_depends( 'hoverIntent' );
        wp_register_style( 'lakit-dlmenu', lastudio_kit()->plugin_url( 'assets/css/lastudio-dlmenu.min.css' ), [], lastudio_kit()->get_version() );
        wp_register_script( 'lakit-dlmenu', lastudio_kit()->plugin_url('assets/js/lib/lakitdlmenu.min.js'), [], lastudio_kit()->get_version(), true);
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-base' );
		    $this->add_script_depends( 'lastudio-kit-base' );
            if( !empty($_GET['elementor-preview']) ){
                $this->add_script_depends('lakit-dlmenu');
                $this->add_style_depends('lakit-dlmenu');
            }
	    }
    }

	public function get_name() {
		return 'lakit-nav-menu';
	}

	public function get_widget_title() {
		return esc_html__( 'Nav Menu', 'lastudio-kit' );
	}

	public function get_icon() {
		return 'lastudio-kit-icon-nav-menu';
	}

    public function get_categories() {
        return [ 'lastudiokit-builder' ];
    }

    protected function is_support_megamenu(){
	    return lastudio_kit()->get_theme_support('elementor::mega-menu');
    }

    protected function register_controls() {

        $this->_start_controls_section(
            'section_menu',
            array(
                'label' => esc_html__( 'Menu', 'lastudio-kit' ),
            )
        );

        $menus   = $this->get_available_menus();
        $default = '';

        if ( ! empty( $menus ) ) {
            $ids     = array_keys( $menus );
            $default = $ids[0];
        }

        $this->_add_control(
            'nav_menu',
            array(
                'label'   => esc_html__( 'Select Menu', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => $default,
                'options' => $menus,
            )
        );

        $this->_add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => array(
                    'horizontal' => esc_html__( 'Horizontal', 'lastudio-kit' ),
                    'vertical'   => esc_html__( 'Vertical', 'lastudio-kit' ),
                ),
            )
        );

        $this->_add_control(
            'dropdown_position',
            array(
                'label'   => esc_html__( 'Dropdown Placement', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right-side',
                'options' => array(
                    'left-side'  => esc_html__( 'Left Side', 'lastudio-kit' ),
                    'right-side' => esc_html__( 'Right Side', 'lastudio-kit' ),
                    'bottom'     => esc_html__( 'At the bottom', 'lastudio-kit' ),
                    'push'       => esc_html__( 'Push', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'layout' => 'vertical',
                )
            )
        );

        if($this->is_support_megamenu()){
	        $this->_add_control(
		        'show_megamenu',
		        array(
			        'label'   => esc_html__( 'Show MegaMenu', 'lastudio-kit' ),
			        'type'    => Controls_Manager::SWITCHER,
		        )
	        );
	        $this->_add_control(
		        'enable_ajax_megamenu',
		        array(
			        'label'   => esc_html__( 'Ajax load MegaMenu', 'lastudio-kit' ),
			        'type'    => Controls_Manager::SWITCHER,
			        'condition' => array(
				        'show_megamenu!' => '',
			        ),
		        )
	        );
            $this->_add_control(
                'menu_as_toggle',
                array(
                    'label'   => esc_html__( 'Menu As Toggle', 'lastudio-kit' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'condition' => array(
                        'layout' => 'vertical',
                    )
                )
            );
            $this->_add_control(
                'toggle_text',
                array(
                    'label'     => esc_html__( 'Toggle Text', 'lastudio-kit' ),
                    'type'      => Controls_Manager::TEXT,
                    'condition' => array(
                        'layout' => 'vertical',
                        'menu_as_toggle!' => '',
                    ),
                )
            );
            $this->_add_advanced_icon_control(
                'toggle_icon',
                array(
                    'label'       => esc_html__( 'Toggle Icon', 'lastudio-kit' ),
                    'label_block' => false,
                    'type'        => Controls_Manager::ICON,
                    'skin'        => 'inline',
                    'default'     => 'lastudioicon-menu-8-1',
                    'fa5_default' => array(
                        'value'   => 'lastudioicon-menu-8-1',
                        'library' => 'lastudioicon',
                    ),
                    'condition' => array(
                        'layout' => 'vertical',
                        'menu_as_toggle!' => '',
                    ),
                )
            );
        }

        $this->_add_control(
            'dropdown_icon',
            array(
                'label'   => esc_html__( 'Dropdown Icon', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lastudioicon-down-arrow',
                'options' => $this->dropdown_arrow_icons_list(),
            )
        );

        $this->_add_control(
            'show_items_desc',
            array(
                'label'   => esc_html__( 'Show Items Description', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER
            )
        );

	    $this->_add_control(
		    'line_animation',
		    array(
			    'label'   => esc_html__( 'Line Animation', 'lastudio-kit' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'none',
			    'options' => [
			    	'none'      => esc_html__( 'None', 'lastudio-kit' ),
			    	'left'      => esc_html__( 'From Left', 'lastudio-kit' ),
			    	'right'     => esc_html__( 'From Right', 'lastudio-kit' ),
			    	'center'    => esc_html__( 'From Center', 'lastudio-kit' ),
			    	'center2'   => esc_html__( 'From Center & Push', 'lastudio-kit' ),
                    'dot'       => esc_html__( 'Dot Left', 'lastudio-kit' ),
                    'dotr'       => esc_html__( 'Dot Right', 'lastudio-kit' ),
                    'dotl'       => esc_html__( 'Dot+Line Left', 'lastudio-kit' ),
                    'dotlr'       => esc_html__( 'Dot+Line Right', 'lastudio-kit' ),
			    ],
			    'prefix_class' => 'lakit-nav-line-animation-'
		    )
	    );

        $this->_add_responsive_control(
            'menu_alignment',
            array(
                'label'   => esc_html__( 'Menu Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                    'space-between' => array(
                        'title' => esc_html__( 'Justified', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-stretch',
                    ),
                ),
                'selectors_dictionary' => array(
                    'flex-start'    => 'justify-content: flex-start; text-align: left;--lakit-navmenu--item-flex-grow:0;--lakit-navmenu--item-margin: 0; --lakit-navmenu_mb-align: flex-start',
                    'center'        => 'justify-content: center; text-align: center;--lakit-navmenu--item-flex-grow:0;--lakit-navmenu--item-margin: 0; --lakit-navmenu_mb-align: center',
                    'flex-end'      => 'justify-content: flex-end; text-align: right;--lakit-navmenu--item-flex-grow:0;--lakit-navmenu--item-margin: 0; --lakit-navmenu_mb-align: flex-end',
                    'space-between' => 'justify-content: space-between; text-align: left;--lakit-navmenu--item-flex-grow:1;--lakit-navmenu--item-margin: auto; --lakit-navmenu_mb-align: stretch',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-{{ID}}.lakit-nav--horizontal' => '{{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-{{ID}}.lakit-nav--vertical .lakit-nav-id-{{ID}} > .menu-item-link-top' => '{{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-{{ID}}.lakit-nav--vertical-sub-bottom .lakit-nav-id-{{ID}} > .menu-item-link-sub' => '{{VALUE}}',
                    '{{WRAPPER}} .lakit-mobile-menu.lakit-active--mbmenu .lakit-nav-id-{{ID}} > .menu-item-link' => '{{VALUE}}',
                )
            )
        );

        $this->_add_control(
            'menu_alignment_style',
            array(
                'type'       => Controls_Manager::HIDDEN,
                'default'    => 'style',
                'selectors'  => array(
                    'body:not(.rtl) {{WRAPPER}} .lakit-nav--horizontal .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'text-align: left;',
                    'body.rtl {{WRAPPER}} .lakit-nav--horizontal .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'text-align: right;',
                ),
                'condition' => array(
                    'layout' => 'horizontal',
                ),
            )
        );

        $this->_add_control(
            'mobile_trigger_visible',
            array(
                'label'     => esc_html__( 'Enable Mobile Trigger', 'lastudio-kit' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes',
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'mobile_menu_breakpoint',
            array(
                'label' => esc_html__( 'Breakpoint', 'lastudio-kit' ),
                'type'  => Controls_Manager::SELECT,
                'default' => 'tablet',
                'options' => [
                    'all' => 'All'
                ] + lastudio_kit_helper()->get_active_breakpoints(false, true),
                'condition' => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_control(
            'mobile_trigger_alignment',
            array(
                'label'   => esc_html__( 'Mobile Trigger Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'condition' => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'mobile_trigger_icon',
            array(
                'label'       => esc_html__( 'Mobile Trigger Icon', 'lastudio-kit' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICON,
                'skin'        => 'inline',
                'default'     => 'lastudioicon-menu-8-1',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-menu-8-1',
                    'library' => 'lastudioicon',
                ),
                'condition'   => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_advanced_icon_control(
            'mobile_trigger_close_icon',
            array(
                'label'       => esc_html__( 'Mobile Trigger Close Icon', 'lastudio-kit' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICON,
                'skin'        => 'inline',
                'default'     => 'lastudioicon-e-remove',
                'fa5_default' => array(
                    'value'   => 'lastudioicon-e-remove',
                    'library' => 'lastudioicon',
                ),
                'condition'   => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );

        $this->_add_control(
            'mobile_menu_layout',
            array(
                'label' => esc_html__( 'Mobile Menu Layout', 'lastudio-kit' ),
                'type'  => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'    => esc_html__( 'Default', 'lastudio-kit' ),
                    'full-width' => esc_html__( 'Dropdown', 'lastudio-kit' ),
                    'left-side'  => esc_html__( 'Slide From The Left Side ', 'lastudio-kit' ),
                    'right-side' => esc_html__( 'Slide From The Right Side ', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'mobile_trigger_visible' => 'yes',
                ),
            )
        );
	    $this->add_control(
		    'mobile_after_template_id',
		    array(
			    'label'       => esc_html__( 'Extra mobile block', 'lastudio-kit' ),
			    'label_block' => 'true',
			    'type'        => 'lastudiokit-query',
			    'object_type' => \Elementor\TemplateLibrary\Source_Local::CPT,
			    'filter_type' => 'by_id',
			    'condition' => array(
				    'mobile_trigger_visible' => 'yes',
			    ),
		    )
	    );

        $this->_add_control(
            'menu_effect',
            array(
                'label' => esc_html__( 'Mobile Menu Effect', 'lastudio-kit' ),
                'type'  => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'       => esc_html__( 'Default', 'lastudio-kit' ),
                    'effect1'       => esc_html__( 'Effect 1', 'lastudio-kit' ),
                    'effect2'       => esc_html__( 'Effect 2', 'lastudio-kit' ),
                    'effect3'       => esc_html__( 'Effect 3', 'lastudio-kit' ),
                    'effect4'       => esc_html__( 'Effect 4', 'lastudio-kit' ),
                    'effect5'       => esc_html__( 'Effect 5', 'lastudio-kit' ),
                ),
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'mobile_trigger_visible',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'layout',
                                    'operator' => '==',
                                    'value' => 'vertical'
                                ],
                                [
                                    'name' => 'dropdown_position',
                                    'operator' => '==',
                                    'value' => 'push'
                                ],
                            ]
                        ]
                    ]
                ]
            )
        );
        $this->_add_control(
            'dlmenu_back_text',
            array(
                'label' => esc_html__( 'Back text', 'lastudio-kit' ),
                'type'  => Controls_Manager::TEXT,
                'default' => esc_html__( 'Back', 'lastudio-kit' ),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'mobile_trigger_visible',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ],
                                [
                                    'name' => 'menu_effect',
                                    'operator' => '!==',
                                    'value' => 'default'
                                ],
                            ]
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'layout',
                                    'operator' => '==',
                                    'value' => 'vertical'
                                ],
                                [
                                    'name' => 'dropdown_position',
                                    'operator' => '==',
                                    'value' => 'push'
                                ],
                            ]
                        ]
                    ]
                ]
            )
        );
        $this->_add_advanced_icon_control(
            'dlmenu_back_icon',
            array(
                'label'       => esc_html__( 'Back Icon', 'lastudio-kit' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICON,
                'skin'        => 'inline',
                'default'     => 'lastudioicon-arrow-left',
                'fa5_default' => [
                    'value'   => 'lastudioicon-arrow-left',
                    'library' => 'lastudioicon',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'mobile_trigger_visible',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ],
                                [
                                    'name' => 'menu_effect',
                                    'operator' => '!==',
                                    'value' => 'default'
                                ],
                            ]
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'layout',
                                    'operator' => '==',
                                    'value' => 'vertical'
                                ],
                                [
                                    'name' => 'dropdown_position',
                                    'operator' => '==',
                                    'value' => 'push'
                                ],
                            ]
                        ]
                    ]
                ]
            )
        );
        $this->_add_advanced_icon_control(
            'dlmenu_trigger_icon',
            array(
                'label'       => esc_html__( 'Trigger Icon', 'lastudio-kit' ),
                'label_block' => false,
                'type'        => Controls_Manager::ICON,
                'skin'        => 'inline',
                'default'     => 'lastudioicon-right-arrow',
                'fa5_default' => [
                    'value'   => 'lastudioicon-right-arrow',
                    'library' => 'lastudioicon',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'mobile_trigger_visible',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ],
                                [
                                    'name' => 'menu_effect',
                                    'operator' => '!==',
                                    'value' => 'default'
                                ],
                            ]
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'layout',
                                    'operator' => '==',
                                    'value' => 'vertical'
                                ],
                                [
                                    'name' => 'dropdown_position',
                                    'operator' => '==',
                                    'value' => 'push'
                                ],
                            ]
                        ]
                    ]
                ]
            )
        );

        $this->_add_control(
            'enable_logo',
            array(
                'label'     => esc_html__( 'Display Logo', 'lastudio-kit' ),
                'type'      => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'layout' => 'horizontal'
                ]
            )
        );
        $this->_add_control(
            'logo_position',
            [
                'label' => esc_html__( 'Logo Position', 'lastudio-kit' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes',
                ]
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Logo', 'lastudio-kit' ),
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'logo_type',
            array(
                'type'    => 'select',
                'label'   => esc_html__( 'Logo Type', 'lastudio-kit' ),
                'default' => 'text',
                'options' => array(
                    'text'  => esc_html__( 'Text', 'lastudio-kit' ),
                    'image' => esc_html__( 'Image', 'lastudio-kit' ),
                    'both'  => esc_html__( 'Both Text and Image', 'lastudio-kit' ),
                ),
            )
        );

        $this->_add_control(
            'logo_image',
            array(
                'label'     => esc_html__( 'Logo Image', 'lastudio-kit' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => array(
                    'logo_type!' => 'text',
                ),
            )
        );

        $this->_add_control(
            'logo_image_2x',
            array(
                'label'     => esc_html__( 'Transparency Logo Image', 'lastudio-kit' ),
                'type'      => Controls_Manager::MEDIA,
                'condition' => array(
                    'logo_type!' => 'text',
                ),
            )
        );

        $this->_add_control(
            'logo_text_from',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Logo Text From', 'lastudio-kit' ),
                'default'    => 'site_name',
                'options'    => array(
                    'site_name' => esc_html__( 'Site Name', 'lastudio-kit' ),
                    'custom'    => esc_html__( 'Custom', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'logo_type!' => 'image',
                ),
            )
        );

        $this->_add_control(
            'logo_text',
            array(
                'label'     => esc_html__( 'Custom Logo Text', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => array(
                    'logo_text_from' => 'custom',
                    'logo_type!'     => 'image',
                ),
            )
        );

        $this->_add_control(
            'logo_display',
            array(
                'type'        => 'select',
                'label'       => esc_html__( 'Display Logo Image and Text', 'lastudio-kit' ),
                'label_block' => true,
                'default'     => 'block',
                'options'     => array(
                    'inline' => esc_html__( 'Inline', 'lastudio-kit' ),
                    'block'  => esc_html__( 'Text Below Image', 'lastudio-kit' ),
                ),
                'condition' => array(
                    'logo_type' => 'both',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'section_toggle_style',
            array(
                'label'      => esc_html__( 'Toggle', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'layout' => 'vertical',
                    'menu_as_toggle!' => '',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'btn_toggle_typography',
                'selector' => '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger',
            ),
            50
        );
        $this->_add_responsive_control(
            'btn_toggle_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'btn_toggle_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'btn_toggle_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger',
            ),
            75
        );

        $this->_add_responsive_control(
            'btn_toggle_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_start_controls_tabs( 'tabs_toggle_style' );

        $this->_start_controls_tab(
            'tab_toggle_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'btn_toggle_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'btn_toggle_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'tab_toggle_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'btn_toggle_bgcolor_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}}.toggle--active > .lakit-nav__toggle-trigger' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'btn_toggle_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}}.toggle--active > .lakit-nav__toggle-trigger' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'btn_toggle_border_color_hover',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'btn_toggle_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}}.toggle--active > .lakit-nav__toggle-trigger' => 'border-color: {{VALUE}}',
                ),
            ),
            75
        );

        $this->_end_controls_tab();
        $this->_end_controls_tabs();

        $this->_add_control(
            'toggle__heading1',
            array(
                'label' => esc_html__( 'Toggle Icon', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            75
        );
        $this->_add_responsive_control(
            'btn_toggle_icon_size',
            array(
                'label' => esc_html__( 'Toggle Icon Size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger .nav-toggle-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );
        $this->_add_responsive_control(
            'btn_toggle_icon_margin',
            array(
                'label'      => esc_html__( 'Toggle Icon Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger .nav-toggle-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'toggle__heading2',
            array(
                'label' => esc_html__( 'Toggle Icon 2', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            75
        );

        $this->_add_responsive_control(
            'btn_toggle_icon_dd_size',
            array(
                'label' => esc_html__( 'Toggle Icon Size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger .nav-toggle-icondrop' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );
        $this->_add_responsive_control(
            'btn_toggle_icon_dd_margin',
            array(
                'label'      => esc_html__( 'Toggle Icon Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__toggle-trigger .nav-toggle-icondrop' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_control(
            'toggle__heading3',
            array(
                'label' => esc_html__( 'Dropdown', 'lastudio-kit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            75
        );

        $this->_add_control(
            'toggle_dropdown_bgcolor',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );
        $this->_add_responsive_control(
            'toggle_dropdown_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'toggle_dropdown_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

      $this->_add_group_control(
        Group_Control_Border::get_type(),
        array(
          'name'        => 'toggle_dropdown_border',
          'label'       => esc_html__( 'Border', 'lastudio-kit' ),
          'placeholder' => '1px',
          'selector'    => '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav',
        ),
        75
      );

        $this->_add_group_control(
          Group_Control_Box_Shadow::get_type(),
          array(
            'name'     => 'toggle_dropdown_shadow',
            'selector' => '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav'
          ),
          75
        );


        $this->_end_controls_section();

        $this->_start_controls_section(
            'nav_items_style',
            array(
                'label'      => esc_html__( 'Top Level Items', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_responsive_control(
            'nav_vertical_menu_width',
            array(
                'label' => esc_html__( 'Vertical Menu Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap.lakit-nav-wrap-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'layout' => 'vertical',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'nav_vertical_menu_align',
            array(
                'label'       => esc_html__( 'Vertical Menu Alignment', 'lastudio-kit' ),
                'label_block' => true,
                'type'        => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'selectors_dictionary' => array(
                    'left'   => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right'  => 'margin-left: auto; margin-right: 0;',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap.lakit-nav-wrap-{{ID}}' => '{{VALUE}}',
                ),
                'condition'  => array(
                    'layout' => 'vertical',
                ),
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_nav_items_style' );

        $this->_start_controls_tab(
            'nav_items_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'nav_items_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_n_bgcolor: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_n_tcolor: {{VALUE}}',
                ),
            ),
            25
        );

        if( $this->is_support_megamenu() ){
	        $this->_add_control(
		        'nav_items_icon_color',
		        array(
			        'label'  => esc_html__( 'Menu Icon Color', 'lastudio-kit' ),
			        'type'   => Controls_Manager::COLOR,
			        'selectors' => array(
                        '{{WRAPPER}}' => '--enav_n_icolor: {{VALUE}}',
			        ),
		        ),
		        25
	        );
        }

        $this->_add_control(
            'nav_items_text_icon_color',
            array(
                'label'  => esc_html__( 'Dropdown Icon Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_n_dcolor: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_items_typography',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-link-text',
            ),
            50
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'nav_items_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'nav_items_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_h_bgcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-top' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_h_tcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-top' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'nav_items_border_border!' => '',
                ),
                'selectors' => array(
//                    '{{WRAPPER}}' => '--enav_h_bdcolor: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-top' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );
	    if( $this->is_support_megamenu() ){
		    $this->_add_control(
			    'nav_items_icon_color_hover',
			    array(
				    'label'  => esc_html__( 'Menu Icon Color', 'lastudio-kit' ),
				    'type'   => Controls_Manager::COLOR,
				    'selectors' => array(
                        '{{WRAPPER}}' => '--enav_h_icolor: {{VALUE}}',
//					    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-top .lakit-nav-item-icon' => 'color: {{VALUE}}',
				    ),
			    ),
			    25
		    );
	    }
        $this->_add_control(
            'nav_items_text_icon_color_hover',
            array(
                'label'  => esc_html__( 'Dropdown Icon Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_h_dcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-top .lakit-nav-arrow' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_items_typography_hover',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-top .lakit-nav-link-text',
            ),
            50
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'nav_items_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'nav_items_bg_color_active',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_a_bgcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_color_active',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_a_tcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'nav_items_active_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'nav_items_border_border!' => '',
                ),
                'selectors' => array(
//                    '{{WRAPPER}}' => '--enav_a_bdcolor: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );
	    if( $this->is_support_megamenu() ){
		    $this->_add_control(
			    'nav_items_icon_color_active',
			    array(
				    'label'  => esc_html__( 'Menu Icon Color', 'lastudio-kit' ),
				    'type'   => Controls_Manager::COLOR,
				    'selectors' => array(
                        '{{WRAPPER}}' => '--enav_a_icolor: {{VALUE}}',
//					    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top .lakit-nav-item-icon' => 'color: {{VALUE}}',
				    ),
			    ),
			    25
		    );
	    }
        $this->_add_control(
            'nav_items_text_icon_color_active',
            array(
                'label'  => esc_html__( 'Dropdown Icon Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_a_dcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top .lakit-nav-arrow' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'nav_items_typography_active',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top .lakit-nav-link-text',
            ),
            50
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'nav_items_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'nav_items_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'nav_items_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top',
            ),
            75
        );

        $this->_add_responsive_control(
            'nav_items_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        if( $this->is_support_megamenu() ){
	        $this->_add_responsive_control(
		        'nav_items_micon_size',
		        array(
			        'label'      => esc_html__( 'Menu Icon Size', 'lastudio-kit' ),
			        'type'       => Controls_Manager::SLIDER,
			        'size_units' => array( 'px', 'em' ),
			        'selectors' => array(
				        '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			        ),
		        ),
		        50
	        );
        }

        $this->_add_responsive_control(
            'nav_items_icon_size',
            array(
                'label'      => esc_html__( 'Dropdown Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav_n_isize: {{SIZE}}{{UNIT}};',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .trigger-dlmenu' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'nav_items_icon_gap',
            array(
                'label'      => esc_html__( 'Gap Before Dropdown Icon', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav--vertical-sub-left-side .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
                    '{{WRAPPER}} .lakit-mobile-menu.lakit-active--mbmenu .lakit-nav--vertical-sub-left-side .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
                ),
            ),
            50
        );

        $this->_add_control(
            'nav_items_desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'nav_items_desc_typography',
                'selector'  => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-top .lakit-nav-item-desc',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'sub_items_style',
            array(
                'label'      => esc_html__( 'Dropdown', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'sub_items_container_style_heading',
            array(
                'label' => esc_html__( 'Container Styles', 'lastudio-kit' ),
                'type'  => Controls_Manager::HEADING,
            ),
            25
        );

        $this->_add_responsive_control(
            'sub_items_container_width',
            array(
                'label'      => esc_html__( 'Container Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'layout',
                            'operator' => '===',
                            'value'    => 'horizontal',
                        ),
                        array(
                            'relation' => 'and',
                            'terms' => array(
                                array(
                                    'name'     => 'layout',
                                    'operator' => '===',
                                    'value'    => 'vertical',
                                ),
                                array(
                                    'name'     => 'dropdown_position',
                                    'operator' => '!==',
                                    'value'    => 'bottom',
                                )
                            ),
                        ),
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_container_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'sub_items_container_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub',
            ),
            75
        );

        $this->_add_responsive_control(
            'sub_items_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub > .menu-item:first-child > .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub > .menu-item:last-child > .menu-item-link' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'sub_items_container_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub',
            ),
            75
        );


        $this->_add_responsive_control(
            'sub_items_container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'sub_items_container_top_gap',
            array(
                'label'      => esc_html__( 'Gap Before 1st Level Sub', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav--horizontal .lakit-nav-id-{{ID}} > .lakit-nav-depth-0' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav--vertical-sub-left-side .lakit-nav-id-{{ID}} > .lakit-nav-depth-0' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav--vertical-sub-right-side .lakit-nav-id-{{ID}} > .lakit-nav-depth-0' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'layout',
                            'operator' => '===',
                            'value'    => 'horizontal',
                        ),
                        array(
                            'relation' => 'and',
                            'terms' => array(
                                array(
                                    'name'     => 'layout',
                                    'operator' => '===',
                                    'value'    => 'vertical',
                                ),
                                array(
                                    'name'     => 'dropdown_position',
                                    'operator' => '!==',
                                    'value'    => 'bottom',
                                )
                            ),
                        ),
                    ),
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'sub_items_container_left_gap',
            array(
                'label'      => esc_html__( 'Gap Before 2nd Level Sub', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-depth-0 .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav--vertical-sub-left-side .lakit-nav-depth-0 .lakit-nav-id-{{ID}} > .lakit-nav__sub' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name'     => 'layout',
                            'operator' => '===',
                            'value'    => 'horizontal',
                        ),
                        array(
                            'relation' => 'and',
                            'terms' => array(
                                array(
                                    'name'     => 'layout',
                                    'operator' => '===',
                                    'value'    => 'vertical',
                                ),
                                array(
                                    'name'     => 'dropdown_position',
                                    'operator' => '!==',
                                    'value'    => 'bottom',
                                )
                            ),
                        ),
                    ),
                ),
            ),
            50
        );

        $this->_add_control(
            'sub_items_style_heading',
            array(
                'label'     => esc_html__( 'Items Styles', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'sub_items_typography',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-link-text',
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_sub_items_style' );

        $this->_start_controls_tab(
            'sub_items_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'sub_items_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_n_bgcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-{{ID}}' => '--mm-subitem-bg: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_n_tcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-{{ID}}' => '--mm-subitem-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'sub_items_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'sub_items_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_h_bgcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-sub' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-{{ID}}' => '--mm-subitem-bg-hover: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_h_tcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}:hover > .menu-item-link-sub' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-nav-{{ID}}' => '--mm-subitem-color-hover: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'sub_items_active',
            array(
                'label' => esc_html__( 'Active', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'sub_items_bg_color_active',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_a_bgcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-sub' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'sub_items_color_active',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_a_tcolor: {{VALUE}}',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-sub' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'sub_items_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );
        $this->_add_responsive_control(
            'sub_items_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );


	    if( $this->is_support_megamenu() ){
		    $this->_add_responsive_control(
			    'sub_items_micon_size',
			    array(
				    'label'      => esc_html__( 'Menu Icon Size', 'lastudio-kit' ),
				    'type'       => Controls_Manager::SLIDER,
				    'size_units' => array( 'px', 'em' ),
				    'selectors' => array(
					    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-item-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				    ),
			    ),
			    50
		    );
	    }

        $this->_add_responsive_control(
            'sub_items_icon_size',
            array(
                'label'      => esc_html__( 'Dropdown Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--enav2_n_isize: {{SIZE}}{{UNIT}};',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
//                    '{{WRAPPER}} .lakit-nav-id-{{ID}}.lakit-nav-item-sub > .trigger-dlmenu' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'sub_items_icon_gap',
            array(
                'label'      => esc_html__( 'Gap Before Dropdown Icon', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'condition' => array(
                    'dropdown_icon!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav--vertical-sub-left-side .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
                    '{{WRAPPER}} .lakit-mobile-menu.lakit-active--mbmenu .lakit-nav--vertical-sub-left-side .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
                ),
            ),
            50
        );

        $this->_add_control(
            'sub_items_divider_heading',
            array(
                'label'     => esc_html__( 'Divider', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'sub_items_divider',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub > .lakit-nav-item-sub:not(:last-child)',
                'exclude'  => array( 'width' ),
            ),
            75
        );

        $this->_add_responsive_control(
            'sub_items_divider_width',
            array(
                'label' => esc_html__( 'Border Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'size' => 1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} > .lakit-nav__sub > .lakit-nav-item-sub:not(:last-child)' => 'border-width: 0; border-bottom-width: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'sub_items_divider_border!' => '',
                ),
            ),
            75
        );

        $this->_add_control(
            'sub_items_desc_heading',
            array(
                'label'     => esc_html__( 'Description', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'sub_items_desc_typography',
                'selector'  => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link-sub .lakit-nav-item-desc',
                'condition' => array(
                    'show_items_desc' => 'yes',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        if( $this->is_support_megamenu() ){

	        $this->_start_controls_section(
		        'badge_section',
		        array(
			        'label'      => esc_html__( 'Badge', 'lastudio-kit' ),
			        'tab'        => Controls_Manager::TAB_STYLE,
			        'show_label' => false,
		        )
	        );
            $this->_add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'badge_typography',
                    'selector'  => '{{WRAPPER}} .lakit-nav-item-badge-inner',
                ),
                50
            );

	        $this->_add_control(
		        'badge_bg',
		        array(
			        'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
			        'type'   => Controls_Manager::COLOR,
			        'selectors' => array(
				        '{{WRAPPER}} .lakit-nav-{{ID}}' => '--mm-badge-bg: {{VALUE}}',
			        ),
		        ),
		        25
	        );
	        $this->_add_control(
		        'badge_color',
		        array(
			        'label'  => esc_html__( 'Color', 'lastudio-kit' ),
			        'type'   => Controls_Manager::COLOR,
			        'selectors' => array(
				        '{{WRAPPER}} .lakit-nav-{{ID}}' => '--mm-badge-color: {{VALUE}}',
			        ),
		        ),
		        25
	        );

	        $this->_add_control(
		        'badge_position',
		        array(
			        'label'   => esc_html__( 'Position', 'lastudio-kit' ),
			        'type'    => Controls_Manager::SELECT,
			        'default' => 'default',
			        'options' => array(
				        'default' => esc_html__( 'Default', 'lastudio-kit' ),
				        'left'   => esc_html__( 'Left', 'lastudio-kit' ),
				        'center'   => esc_html__( 'Center', 'lastudio-kit' ),
				        'right'   => esc_html__( 'Right', 'lastudio-kit' ),
				        'custom'   => esc_html__( 'Custom', 'lastudio-kit' ),
			        ),
			        'prefix_class' => 'lakit-nav--badge-pos-'
		        )
	        );

	        $this->_add_control(
		        'badge_position_custom',
		        array(
			        'label'      => esc_html__( 'Left', 'lastudio-kit' ),
			        'type'       => Controls_Manager::SLIDER,
			        'size_units' => array( 'px', 'em', '%', 'custom' ),
			        'condition' => array(
				        'badge_position' => 'custom',
			        ),
			        'selectors' => array(
				        '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link .lakit-nav-item-badge' => 'left: {{SIZE}}{{UNIT}};',
			        ),
		        ),
		        50
	        );


	        $this->_add_responsive_control(
		        'badge_padding',
		        array(
			        'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
			        'type'       => Controls_Manager::DIMENSIONS,
			        'size_units' => array( 'px', '%', 'em' ),
			        'selectors'  => array(
				        '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link .lakit-nav-item-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			        ),
		        ),
		        25
	        );

	        $this->_add_responsive_control(
		        'badge_margin',
		        array(
			        'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			        'type'       => Controls_Manager::DIMENSIONS,
			        'size_units' => array( 'px', '%', 'em' ),
			        'selectors'  => array(
				        '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link .lakit-nav-item-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			        ),
		        ),
		        25
	        );

	        $this->_add_control(
		        'badge_radius',
		        array(
			        'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
			        'type'       => Controls_Manager::DIMENSIONS,
			        'size_units' => array( 'px', '%' ),
			        'selectors'  => array(
				        '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link .lakit-nav-item-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			        ),
		        ),
		        75
	        );

	        $this->_add_group_control(
		        Group_Control_Border::get_type(),
		        array(
			        'name'        => 'badge_border',
			        'label'       => esc_html__( 'Border', 'lastudio-kit' ),
			        'placeholder' => '1px',
			        'selector'    => '{{WRAPPER}} .lakit-nav-id-{{ID}} > .menu-item-link .lakit-nav-item-badge',
		        ),
		        75
	        );

	        $this->_end_controls_section();
        }

        $this->_start_controls_section(
            'mobile_trigger_styles',
            array(
                'label'      => esc_html__( 'Mobile Trigger', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_start_controls_tabs( 'tabs_mobile_trigger_style' );

        $this->_start_controls_tab(
            'mobile_trigger_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'mobile_trigger_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_trigger_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'mobile_trigger_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'mobile_trigger_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger:hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_trigger_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_trigger_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'mobile_trigger_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'mobile_trigger_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger',
                'separator'   => 'before',
            ),
            75
        );

        $this->_add_control(
            'mobile_trigger_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_responsive_control(
            'mobile_trigger_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                    '%' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            50
        );

        $this->_add_responsive_control(
            'mobile_trigger_height',
            array(
                'label'      => esc_html__( 'Height', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger' => 'height: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'mobile_trigger_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-nav-wrap-{{ID}} > .lakit-nav__mobile-trigger' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'mobile_menu_styles',
            array(
                'label' => esc_html__( 'Mobile Menu', 'lastudio-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->_add_responsive_control(
            'mobile_menu_width',
            array(
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range' => array(
                    'px' => array(
                        'min' => 150,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => 30,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-mobile-menu--full-width .lakit-nav-{{ID}}' => 'min-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'mobile_menu_layout!' => 'default'
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'mobile_menu_max_height',
            array(
                'label' => esc_html__( 'Max Height', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'vh' ),
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 500,
                    ),
                    'vh' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-{{ID}}' => 'max-height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => 'full-width',
                ),
            ),
            25
        );

	    $this->_add_responsive_control(
		    'mobile_menu_alignment',
		    array(
			    'label'   => esc_html__( 'Menu Alignment', 'lastudio-kit' ),
			    'type'    => Controls_Manager::CHOOSE,
			    'options' => array(
				    'left' => array(
					    'title' => esc_html__( 'Left', 'lastudio-kit' ),
					    'icon'  => 'eicon-h-align-left',
				    ),
				    'center' => array(
					    'title' => esc_html__( 'Center', 'lastudio-kit' ),
					    'icon'  => 'eicon-h-align-center',
				    ),
				    'right' => array(
					    'title' => esc_html__( 'Right', 'lastudio-kit' ),
					    'icon'  => 'eicon-h-align-right',
				    ),
			    ),
			    'selectors_dictionary' => array(
				    'left'      => '--lakit-mbmfull_p-left:0;--lakit-mbmfull_p-right:auto;--lakit-mbmfull_transform:translate(0)',
				    'center'    => '--lakit-mbmfull_p-left:50%;--lakit-mbmfull_p-right:auto;--lakit-mbmfull_transform:translate(-50%)',
				    'right'     => '--lakit-mbmfull_p-left:auto;--lakit-mbmfull_p-right:0;--lakit-mbmfull_transform:translate(0)'
			    ),
			    'selectors' => array(
				    '{{WRAPPER}} .lakit-nav-{{ID}}' => '{{VALUE}}'
			    ),
			    'condition' => array(
				    'mobile_menu_layout' => 'full-width',
			    ),
		    )
	    );

        $this->_add_control(
            'mobile_menu_bg_color',
            array(
                'label' => esc_html__( 'Background color', 'lastudio-kit' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-{{ID}}' => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_menu_item_text_color',
            array(
                'label' => esc_html__( 'Menu Item Color', 'lastudio-kit' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-id-{{ID}} > .menu-item-link-top' => 'color: {{VALUE}};',
                ),
            ),
            25
        );
        $this->_add_control(
            'mobile_menu_item_bg_color',
            array(
                'label' => esc_html__( 'Menu Item Background Color', 'lastudio-kit' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-id-{{ID}} > .menu-item-link-top' => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_menu_item_text_color_hover',
            array(
                'label' => esc_html__( 'Menu Item Active Color', 'lastudio-kit' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-id-{{ID}}:hover > .menu-item-link-top' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top' => 'color: {{VALUE}};',
                ),
            ),
            25
        );
        $this->_add_control(
            'mobile_menu_item_bg_color_hover',
            array(
                'label' => esc_html__( 'Menu Item Active Background Color', 'lastudio-kit' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
	                '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-id-{{ID}}:hover > .menu-item-link-top' => 'background-color: {{VALUE}};',
	                '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-id-{{ID}}.current-menu-item > .menu-item-link-top' => 'background-color: {{VALUE}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'mobile_menu_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );
        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'mobile_menu_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'selector'    => '{{WRAPPER}} .lakit-active--mbmenu .lakit-nav-{{ID}}',
            ),
            75
        );
        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'mobile_menu_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-active--mbmenu.lakit-mobile-menu-active .lakit-nav-{{ID}}',
            ),
            75
        );

        $this->_add_control(
            'mobile_close_icon_heading',
            array(
                'label' => esc_html__( 'Close icon', 'lastudio-kit' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_close_icon_color',
            array(
                'label' => esc_html__( 'Color', 'lastudio-kit' ),
                'type'  => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-{{ID}} > .lakit-nav__mobile-close-btn' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            25
        );

        $this->_add_control(
            'mobile_close_icon_font_size',
            array(
                'label' => esc_html__( 'Font size', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-{{ID}} > .lakit-nav__mobile-close-btn' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'mobile_menu_layout' => array(
                        'left-side',
                        'right-side',
                    ),
                ),
            ),
            50
        );

	    $this->_add_control(
		    '__heading_02',
		    array(
			    'label' => esc_html__( 'Extra Block', 'lastudio-kit' ),
			    'type'  => Controls_Manager::HEADING,
			    'separator' => 'before',
			    'condition' => array(
				    'mobile_trigger_visible' => 'yes',
                    'mobile_after_template_id!' => ''
			    ),
		    ),
		    25
	    );
	    $this->_add_responsive_control(
		    'mb_block_margin',
		    array(
			    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => array( 'px', 'em', 'custom' ),
			    'selectors'  => array(
				    '{{WRAPPER}} .lakit-nav-{{ID}} > .lakit-nav-custom-block-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ),
			    'condition' => array(
				    'mobile_trigger_visible' => 'yes',
				    'mobile_after_template_id!' => ''
			    ),
		    ),
		    25
	    );
        $this->_add_control(
            '__heading_03',
            array(
                'label' => esc_html__( 'DLMenu', 'lastudio-kit' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'dl_backtext_typo',
                'label'    => esc_html__( 'Back text', 'lastudio-kit' ),
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakitdl-back button',
            ),
            50
        );
        $this->_add_responsive_control(
            'dl_backicon_size',
            [
                'label' => __( 'Back icon', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakitdl-back button svg' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakitdl-back button i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'logo_style',
            array(
                'label'      => esc_html__( 'Logo', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes',
                    'logo_type'   => ['image', 'both']
                ]
            )
        );
        $this->_add_responsive_control(
            'logo_padding',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-logo.lakit-nav-id-{{ID}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'logo_width',
            [
                'label' => __( 'Logo Width', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-logo.lakit-nav-id-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->_add_responsive_control(
            'logo_alignment',
            array(
                'label'   => esc_html__( 'Logo Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'flex-start',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Start', 'lastudio-kit' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'End', 'lastudio-kit' ),
                        'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-logo.lakit-nav-id-{{ID}}' => 'justify-content: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'vertical_logo_alignment',
            array(
                'label'       => esc_html__( 'Image and Text Vertical Alignment', 'lastudio-kit' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'label_block' => true,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Top', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Middle', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                    'baseline' => array(
                        'title' => esc_html__( 'Baseline', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakit-logo__link' => 'align-items: {{VALUE}}',
                ),
                'condition' => array(
                    'logo_type'    => 'both',
                    'logo_display' => 'inline',
                ),
            ),
            25
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'text_logo_style',
            array(
                'label'      => esc_html__( 'Logo Text', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'layout' => 'horizontal',
                    'enable_logo' => 'yes',
                    'logo_type'   => ['text', 'both']
                ]
            )
        );

        $this->_add_control(
            'text_logo_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakit-logo__text' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_logo_typography',
                'selector' => '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakit-logo__text',
            ),
            50
        );

        $this->_add_control(
            'text_logo_gap',
            array(
                'label'      => esc_html__( 'Gap', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'default'    => array(
                    'size' => 5,
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-logo-display-block.lakit-nav-id-{{ID}} .lakit-logo__img'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .lakit-logo-display-inline.lakit-nav-id-{{ID}} .lakit-logo__img' => 'margin-right: {{SIZE}}{{UNIT}}',
                ),
                'condition'  => array(
                    'logo_type' => 'both',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'text_logo_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-nav-id-{{ID}} .lakit-logo__text' => 'text-align: {{VALUE}}',
                ),
                'condition' => array(
                    'logo_type'    => 'both',
                    'logo_display' => 'block',
                ),
            ),
            50
        );

        $this->_end_controls_section();
    }

    /**
     * Returns available icons for dropdown list
     *
     * @return array
     */
    public function dropdown_arrow_icons_list() {

        return apply_filters( 'lastudio-kit/nav-menu/dropdown-icons', array(
            'lastudioicon-down-arrow'          => esc_html__( 'Angle', 'lastudio-kit' ),
            'lastudioicon-small-triangle-down' => esc_html__( 'Triangle', 'lastudio-kit' ),
            'lastudioicon-arrow-down'          => esc_html__( 'Arrow', 'lastudio-kit' ),
            'lastudioicon-i-add'               => esc_html__( 'Plus', 'lastudio-kit' ),
            'lastudioicon-i-add-2'             => esc_html__( 'Plus 2', 'lastudio-kit' ),
            'lastudioicon-e-add'               => esc_html__( 'Plus 3', 'lastudio-kit' ),
            ''                                 => esc_html__( 'None', 'lastudio-kit' ),
        ) );

    }

    /**
     * Get available menus list
     *
     * @return array
     */
    public function get_available_menus() {

        $raw_menus = wp_get_nav_menus();
        $menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );

        return $menus;
    }

    protected function render() {

        $nav_menu = $this->get_settings_for_display('nav_menu');

        if ( empty($nav_menu) ) {
            return;
        }

        $mobile_after_template_id = 0;
        $layout = $this->get_settings_for_display('layout');

        $is_toggle = filter_var( $this->get_settings_for_display('menu_as_toggle'), FILTER_VALIDATE_BOOLEAN );
        $toggle_text = $this->get_settings_for_display('toggle_text');

        $trigger_visible = filter_var( $this->get_settings_for_display('mobile_trigger_visible'), FILTER_VALIDATE_BOOLEAN );
        $trigger_align   = $this->get_settings_for_display('mobile_trigger_alignment');
        $mobile_menu_breakpoint = $this->get_settings_for_display('mobile_menu_breakpoint');
        if(empty($mobile_menu_breakpoint)){
            $mobile_menu_breakpoint = 'tablet';
        }
        $active_breakpoints = lastudio_kit_helper()->get_active_breakpoints();
        $breakpoint_value = 1024;
        if(isset($active_breakpoints[$mobile_menu_breakpoint])){
            $breakpoint_value = $active_breakpoints[$mobile_menu_breakpoint];
        }

        $mobile_menu_layout = $this->get_settings_for_display('mobile_menu_layout');
        $dropdown_icon = $this->get_settings_for_display('dropdown_icon');
        $dropdown_position = $this->get_settings_for_display('dropdown_position');
        $menu_effect = $this->get_settings_for_display('menu_effect');

        if($dropdown_position === 'push' && ($menu_effect === '' || $menu_effect === 'default')){
            $menu_effect = 'effect1';
        }

        if( !empty($menu_effect) && $menu_effect !== 'default'){
            $this->add_script_depends('lakit-dlmenu');
            $this->add_style_depends('lakit-dlmenu');
        }

        $dlmenu_back_text = $this->get_settings_for_display('dlmenu_back_text');
        $dlmenu_back_icon = $this->_get_icon('dlmenu_back_icon', '%1$s');
        $dlmenu_trigger_icon = $this->_get_icon('dlmenu_trigger_icon', '%1$s');
        if(empty($dlmenu_back_text)){
            $dlmenu_back_text = esc_html__( 'Back', 'lastudio-kit' );
        }
        if(empty($dlmenu_trigger_icon)){
            $dlmenu_trigger_icon = '<i class="lastudioicon-right-arrow"></i>';
        }

        if($mobile_menu_breakpoint == 'all'){
            $breakpoint_value = 'all';
            $this->add_render_attribute( 'nav-wrapper', 'class', 'lakit-active--mbmenu' );
        }

        require_once lastudio_kit()->plugin_path( 'includes/class-nav-walker.php' );

        $this->add_render_attribute( 'nav-wrapper', 'class', 'lakit-nav-wrap' );
        $this->add_render_attribute( 'nav-wrapper', 'class', 'lakit-nav-wrap-' . $this->get_id() );
        $this->add_render_attribute( 'nav-wrapper', 'data-effect', $menu_effect );
        $this->add_render_attribute( 'nav-wrapper', 'data-dlconfig', json_encode([
            'backtext' => sprintf('<span>%1$s</span>', $dlmenu_back_text),
            'backicon' => $dlmenu_back_icon,
            'triggericon' => $dlmenu_trigger_icon,
        ]));

        if ( $trigger_visible ) {
            $this->add_render_attribute( 'nav-wrapper', 'class', 'lakit-mobile-menu' );
            $this->add_render_attribute( 'nav-wrapper', 'data-mobile-breakpoint', esc_attr($breakpoint_value) );

            if ( !empty( $mobile_menu_layout ) ) {
                $this->add_render_attribute( 'nav-wrapper', 'class', sprintf( 'lakit-mobile-menu--%s', esc_attr( $mobile_menu_layout ) ) );
                $this->add_render_attribute( 'nav-wrapper', 'data-mobile-layout', esc_attr( $mobile_menu_layout ) );
            }
	        $mobile_after_template_id = $this->get_settings_for_display('mobile_after_template_id');
        }

	    $mobile_after_template_id = apply_filters('wpml_object_id', $mobile_after_template_id, 'elementor_library', true);

        $this->add_render_attribute( 'nav-menu', 'class', 'lakit-nav' );
        $this->add_render_attribute( 'nav-menu', 'class', 'lakit-nav-' . $this->get_id()  );

        if ( !empty($layout) ) {
            $this->add_render_attribute( 'nav-menu', 'class', 'lakit-nav--' . esc_attr( $layout ) );

            if ( 'vertical' === $layout && !empty( $dropdown_position ) ) {
                $this->add_render_attribute( 'nav-menu', 'class', 'lakit-nav--vertical-sub-' . esc_attr( $dropdown_position ) );
            }
        }

        $mobile_extra_block_html = '';

        if( !empty($mobile_after_template_id) ){
	        $is_elementor_preview = ( isset( $_GET['elementor-preview'] ) || (!empty($_GET['preview']) && !empty($_GET['preview_id'])) ) ? true : false;
	        if($mobile_after_template_id == get_queried_object_id()){
		        $mobile_extra_block_html = '<div class="lakit-nav-custom-block-after" data-template-id="'.esc_attr($mobile_after_template_id).'"><span class="lakit-css-loader"></span></div>';
	        }
            else{
	            if(Plugin::instance()->editor->is_edit_mode()){
		            ob_start();
		            $css_file = Post_CSS::create( $mobile_after_template_id );
		            echo sprintf('<link rel="stylesheet" id="elementor-post-%1$s-css" href="%2$s" type="text/css" media="all" />', $mobile_after_template_id, $css_file->get_url() );
		            echo Plugin::$instance->frontend->get_builder_content( $mobile_after_template_id, false );
		            $content_html = ob_get_clean();
		            $mobile_extra_block_html = '<div class="lakit-nav-custom-block-after">'.$content_html.'</div>';
	            }
                else{
	                $mobile_extra_block_html = '<div class="lakit-nav-custom-block-after" data-lakit_ajax_loadtemplate="true" data-template-id="'.esc_attr($mobile_after_template_id).'"><span class="lakit-css-loader"></span></div>';
                }
            }
        }
        $menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s{{lakit_mobile_extra_block_html_holder}}</div>';
	    $close_btn = '';
        if ( $trigger_visible && in_array( $mobile_menu_layout, array( 'left-side', 'right-side' ) ) ) {
            $close_btn = $this->_get_icon( 'mobile_trigger_close_icon', '<div class="lakit-nav__mobile-close-btn lakit-blocks-icon">%s</div>' );
            $menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s{{lakit_close_btn_holder}}{{lakit_mobile_extra_block_html_holder}}</div>';
        }

	    $show_megamenu = filter_var($this->get_settings_for_display('show_megamenu'), FILTER_VALIDATE_BOOLEAN);
        if($show_megamenu){
	        $this->add_render_attribute( 'nav-wrapper', 'class', 'lakit-nav--enable-megamenu' );
        }

        $args = array(
            'container_class' => 'lakit-nav-menuwrap lakit-nav-menuwrap-' . $this->get_id(),
            'menu'            => $nav_menu,
            'fallback_cb'     => '',
            'items_wrap'      => $menu_html,
            'walker'          => new \LaStudio_Kit_Nav_Walker,
            'widget_settings' => array(
                'dropdown_icon'         => $dropdown_icon,
                'show_items_desc'       => $this->get_settings_for_display('show_items_desc'),
                'show_megamenu'         => $show_megamenu,
                'enable_ajax_megamenu'  => $this->get_settings_for_display('enable_ajax_megamenu'),
                'widget_id'             => $this->get_id()
            ),
            'echo'            => false
        );

        if( filter_var( $this->get_settings_for_display('enable_logo') ) && ( $this->get_settings_for_display('layout') === 'horizontal' ) ){
            $args['widget_settings']['logo_html'] = $this->get_logo_html();
            $args['widget_settings']['logo_position'] = absint($this->get_settings_for_display('logo_position'));
        }

        if($is_toggle){
            $args['container'] = false;
            $this->add_render_attribute( 'nav-wrapper', 'class', 'lakit-nav--enable-toggle' );
        }

        echo '<div ' . $this->get_render_attribute_string( 'nav-wrapper' ) . '>';

        if( $is_toggle ){
            ?>
            <button class="main-color lakit-nav__toggle-trigger"><?php
                echo $this->_get_icon('toggle_icon', '<span class="nav-toggle-icon">%s</span>');
                echo $this->_get_html('toggle_text', '<span class="nav-toggle-text">%s</span>');
                if(!empty($dropdown_icon)){
                    echo sprintf('<span class="nav-toggle-icondrop"><i class="%1$s"></i></span>', $dropdown_icon);
                }
            ?></button>
            <?php
        }

        if ( $trigger_visible ) {
            include $this->_get_global_template( 'mobile-trigger' );
        }
        echo str_replace(['{{lakit_mobile_extra_block_html_holder}}', '{{lakit_close_btn_holder}}'], [$mobile_extra_block_html, $close_btn], wp_nav_menu( $args ));
        echo '</div>';

    }

    public function get_logo_html(){
        $output = sprintf(
            '<div class="%1$s"><a href="%2$s" class="lakit-logo__link">%3$s%4$s</a></div>',
            esc_attr( $this->_get_logo_classes() ),
            esc_url( home_url( '/' ) ),
            $this->_get_logo_image(),
            $this->_get_logo_text()
        );
        return $output;
    }

    /**
     * Returns logo text
     *
     * @return string Text logo HTML markup.
     */
    public function _get_logo_text() {

        $type        = $this->get_settings_for_display('logo_type');
        if(empty($type)){
            $type = 'text';
        }
        $text_from   = $this->get_settings_for_display('logo_text_from');
        if(empty($text_from)){
            $text_from = 'site_name';
        }
        $custom_text = $this->get_settings_for_display('logo_text');

        if ( 'image' === $type ) {
            return;
        }

        if ( 'site_name' === $text_from ) {
            $text = get_bloginfo( 'name' );
        } else {
            $text = $custom_text;
        }

        $format = apply_filters(
            'lastudio-kit/logo/text-foramt',
            '<div class="lakit-logo__text">%s</div>'
        );

        return sprintf( $format, $text );
    }

    /**
     * Returns logo classes string
     *
     * @return string
     */
    public function _get_logo_classes() {

        $classes = array(
            'lakit-logo',
            'lakit-logo-type-' . $this->get_settings_for_display('logo_type'),
            'lakit-logo-display-' . $this->get_settings_for_display('logo_display'),
            'lakit-nav-id-' . $this->get_id(),
        );

        return implode( ' ', $classes );
    }

    /**
     * Returns logo image
     *
     * @return string Image logo HTML markup.
     */
    public function _get_logo_image() {

        $type     = $this->get_settings_for_display('logo_type');
        if(empty($type)){
            $type = 'text';
        }
        $image    = $this->get_settings_for_display('logo_image');
        $image_2x = $this->get_settings_for_display('logo_image_2x');

        if ( 'text' === $type || ! $image ) {
            return;
        }

        $image_src    = $this->_get_logo_image_src( $image );
        $image_2x_src = $this->_get_logo_image_src( $image_2x );

        $image_src = apply_filters('lastudio-kit/logo/attr/src', $image_src);
        $image_2x_src = apply_filters('lastudio-kit/logo/attr/src2x', $image_2x_src);

        if ( empty( $image_src ) && empty( $image_2x_src ) ) {
            return;
        }

        if(empty($image_2x_src)){
            $image_2x_src = $image_src;
        }

        $format = apply_filters(
            'lastudio-kit/logo/image-format',
            '<img src="%1$s" class="lakit-logo__img lakit-logo__n" alt="%2$s"%3$s>'
        );
        $format2 = apply_filters(
            'lastudio-kit/logo/image-format2',
            '<img src="%1$s" class="lakit-logo__img lakit-logo__t" alt="%2$s"%3$s>'
        );

        $image_data = ! empty( $image['id'] ) ? wp_get_attachment_image_src( $image['id'], 'full' ) : array();
        $width      = isset( $image_data[1] ) ? $image_data[1] : false;
        $height     = isset( $image_data[2] ) ? $image_data[2] : false;

        $width      = apply_filters('lastudio-kit/logo/attr/width', $width);
        $height      = apply_filters('lastudio-kit/logo/attr/height', $height);

        $attrs = sprintf(
            '%1$s%2$s%3$s',
            $width ? ' width="' . $width . '"' : '',
            $height ? ' height="' . $height . '"' : '',
            ' data-no-lazy="true"'
        );

        $logo1 = sprintf( $format, esc_url( $image_src ), get_bloginfo( 'name' ), $attrs );
        $logo2 = sprintf( $format2, esc_url( $image_2x_src ), get_bloginfo( 'name' ), $attrs );

        return $logo1 . $logo2;
    }

    public function _get_logo_image_src( $args = array() ) {

        if ( ! empty( $args['id'] ) ) {
            $img_data = wp_get_attachment_image_src( $args['id'], 'full' );

            return ! empty( $img_data[0] ) ? $img_data[0] : false;
        }

        if ( ! empty( $args['url'] ) ) {
            return $args['url'];
        }

        return false;
    }

}
