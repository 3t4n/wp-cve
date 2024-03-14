<?php

namespace Element_Ready\Widgets\user;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Element_Ready\Widget_Controls\User_Style;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );

if ( ! defined( 'ABSPATH' ) ) exit;

class Signin extends Widget_Base {
    use \Elementor\Element_Ready_Box_Style;
    use \Elementor\Element_Ready_Common_Style;
    use User_Style;
    public $base;

    public function get_name() {
        return 'element-ready-user-sign';
    }

    public function get_keywords() {
		return ['element ready','signin','login' ];
	}

    public function get_title() {
        return esc_html__( 'ER Login', 'element-ready-lite' );
    }

    public function get_icon() { 
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }
   public function layout(){
        return[
            
            'style1'   => esc_html__( 'style1', 'element-ready-lite' ),
         ];
    }
 
    protected function register_controls() {

        $this->start_controls_section(
			'menu_layout',
			[
				'label' => esc_html__( 'Layout', 'element-ready-lite' ),
			]
        );

            $this->add_control(
                '_style',
                [
                    'label' => esc_html__( 'Style', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'style1',
                    'options' => $this->layout()
                ]
            );

            $this->add_control(
                'signup_',
                [
                    'label'        => esc_html__( 'SignUp Button', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                ]
            );

            $this->add_control(
                'signup_url', [
                    'label'			  => esc_html__( 'SignUp Path', 'element-ready-lite' ),
                    'type'			  => Controls_Manager::TEXT,
                    'label_block'	  => true,
                    'default'	     => '#',
                    'condition' => [
                        'signup_' => ['yes']
                    ],
                    
                ]
            );

            $this->add_control(
                'signup_text', [
                    'label'			  => esc_html__( 'SignUp Text', 'element-ready-lite' ),
                    'type'			  => Controls_Manager::TEXT,
                    'label_block'	  => true,
                    'default'	     => 'SignUp',
                    'condition' => [
                        'signup_' => ['yes']
                    ],
                    
                ]
            );

            $this->add_control(
                'custom_redirect',
                [
                    'label'        => esc_html__( 'Custom Redirect', 'element-ready-lite' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                    'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                    'return_value' => 'yes',
                    'default'      => 'no',
                ]
            );
                
            $this->add_control(
                'login_redirect_url', [
                    'label'			  => esc_html__( 'Success Redirect Path', 'element-ready-lite' ),
                    'type'			  => Controls_Manager::TEXT,
                    'label_block'	  => true,
                    'default'	     => '#',
                    'condition' => [
                        'custom_redirect' => ['yes']
                    ],
                    
                ]
            );

        $this->end_controls_section();
    
        $this->registration_button_css(esc_html__('SignUp Button','element-ready-lite'),'sign_up_button_cont','signup__btn_element');
        $this->login_button_css(esc_html__('Login Button','element-ready-lite'),'sign__button_cont','signin__btn_element');
   
        $this->lost_pass_button_css(esc_html__('Lost Password','element-ready-lite'),'slost_pass_cont','slost_password_element');
      
        $this->start_controls_section(
            'section_fields',
            [
                'label' => esc_html__('Login Fields', 'element-ready-lite'),
            ]
        );
  
        $this->add_control(
            'custom_fld_icon',
            [
                'label'        => esc_html__( 'Field Icon', 'element-ready-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'custom_lebel',
            [
                'label'        => esc_html__( 'Lebel ?', 'element-ready-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );
      
        $this->add_control(
            'login_username_placeholder', [
                'label'			  => esc_html__( 'Username placeholder', 'element-ready-lite' ),
                'type'			  => Controls_Manager::TEXT,
                'label_block'	  => true,
                'placeholder'    => esc_html__( 'username ', 'element-ready-lite' ),
                'default'	     => esc_html__( 'Username ', 'element-ready-lite' ),
            
                
            ]
        );

        $this->add_control(
            'login_username_label', [
                'label'			  => esc_html__( 'Username Label', 'element-ready-lite' ),
                'type'			  => Controls_Manager::TEXT,
                'label_block'	  => true,
                'placeholder'    => esc_html__( 'username ', 'element-ready-lite' ),
                'default'	     => esc_html__( 'Username ', 'element-ready-lite' ),
                'condition' => [
                    'custom_lebel' => ['yes']
                ],
                
            ]
        );

        $this->add_control(
			'login_username_icon',
			[
				'label' => __( 'User Icon', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-user',
					'library' => 'solid',
				],
                'condition' => [
                    'custom_fld_icon' => ['yes']
                ],
			]
		);
      

        $this->add_control(
            'login_password_placeholder', [
                'label'			  => esc_html__( 'Password placeholder', 'element-ready-lite' ),
                'type'			  => Controls_Manager::TEXT,
                'label_block'	  => true,
                'placeholder'    => esc_html__( 'password ', 'element-ready-lite' ),
                'default'	     => esc_html__( 'password ', 'element-ready-lite' ),
            
                
            ]
        );

        
        $this->add_control(
            'login_password_label', [
                'label'			  => esc_html__( 'Password Label', 'element-ready-lite' ),
                'type'			  => Controls_Manager::TEXT,
                'label_block'	  => true,
                'placeholder'    => esc_html__( 'password ', 'element-ready-lite' ),
                'default'	     => esc_html__( 'password ', 'element-ready-lite' ),
                'condition' => [
                    'custom_lebel' => ['yes']
                ],
                
            ]
        );

        $this->add_control(
			'login_password_icon',
			[
				'label' => __( 'Password Icon', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-user',
					'library' => 'solid',
				],
                'condition' => [
                    'custom_fld_icon' => ['yes']
                ],
			]
		);
       
        $this->add_control(
            'login_submit_text', [
                'label'			  => esc_html__( 'Submit text', 'element-ready-lite' ),
                'type'			  => Controls_Manager::TEXT,
                'label_block'	  => true,
                'placeholder'    => esc_html__( 'Submit ', 'element-ready-lite' ),
                'default'	     => esc_html__( 'Login', 'element-ready-lite' ),
            
                
            ]
        );

        $this->add_control(
            'signup_submit_icon',
            [
                'label' => __( 'Submit Icon', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'solid',
                ],
                'condition' => [
                    'custom_fld_icon' => ['yes'],
                  
                ],
            ]
        );
     
        $this->end_controls_section();

        $this->start_controls_section(
            'section_remenber_content',
            [
                'label' => esc_html__('Login Remember ', 'element-ready-lite'),
            ]
        );

                $this->add_control(
                    'remember_show',
                    [
                        'label'        => esc_html__( 'show', 'element-ready-lite' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                        'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default'      => 'yes',
                    ]
                );
            
                $this->add_control(
                    'remember_text',
                    [
                        'label'       => esc_html__( 'Title', 'element-ready-lite' ),
                        'type'        => \Elementor\Controls_Manager::TEXTAREA,
                        'default'     => esc_html__( 'Remember Me', 'element-ready-lite' ),
                        'placeholder' => esc_html__( 'Type your title here', 'element-ready-lite' ),
                    ]
                );
        
         $this->end_controls_section();

         
        $this->start_controls_section(
            'section_lost_password__content',
            [
                'label' => esc_html__('Login Lost Password ', 'element-ready-lite'),
            ]
        );

                $this->add_control(
                    'lost_password_show',
                    [
                        'label'        => esc_html__( 'show', 'element-ready-lite' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                        'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default'      => 'yes',
                    ]
                );
            
                $this->add_control(
                    'lost_password_text',
                    [
                        'label'       => esc_html__( 'Title', 'element-ready-lite' ),
                        'type'        => \Elementor\Controls_Manager::TEXT,
                        'default'     => esc_html__( 'Lost Password', 'element-ready-lite' ),
                        'placeholder' => esc_html__( 'Type your title here', 'element-ready-lite' ),
                    ]
                );

                $this->add_control(
                    'lost_password_url',
                    [
                        'label'       => esc_html__( 'Link', 'element-ready-lite' ),
                        'type'        => \Elementor\Controls_Manager::URL,
                    ]
                );
        
         $this->end_controls_section();

         $this->box_css(
            array(
                'title' => esc_html__('Remember Wrapper','element-ready-lite'),
                'slug' => 'wrapper_tems_box_style',
                'element_name' => 'wrapper_terms_element_ready_',
                'selector' => '{{WRAPPER}} .form-checkbox.element-ready-modal-checkbox label',
                
            )
        );
        
       
        $this->start_controls_section(
            '_remember_style_section',
            [
                'label' => esc_html__( 'Remember', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'remember_btn_margin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 
                        'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .form-checkbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .input-checkbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'remember_btn_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 
                        'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .form-checkbox span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .element-ready-modal-checkbox span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'remember_box_typography',
                 
                    'selector' => '{{WRAPPER}} .form-checkbox span,{{WRAPPER}} .element-ready-modal-checkbox span',
                    
                ]
            );

            $this->add_control(
                'remember_box_text_color',
                [
                    'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .form-checkbox span'  => 'color:{{VALUE}};',
                        '{{WRAPPER}} .element-ready-modal-checkbox span'  => 'color:{{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'remember_box_special_text_color',
                [
                    'label'     => esc_html__( 'Spacial Text Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .form-checkbox span span'  => 'color:{{VALUE}};',
                        '{{WRAPPER}} .element-ready-modal-checkbox span span'  => 'color:{{VALUE}};',
                        '{{WRAPPER}} .element-ready-modal-checkbox span a'  => 'color:{{VALUE}};',
                    ],
                ]
            );
           

            $this->add_control(
                'remember_box_check_color',
                [
                    'label'     => esc_html__( 'Checkbox Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .form-checkbox span::before'  => 'border-color:{{VALUE}};',
                        '{{WRAPPER}} .element-ready-modal-checkbox .input-checkbox'  => 'border-color:{{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'remember_box_check_bgcolor',
                [
                    'label'     => esc_html__( 'Check box bgColor', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .form-checkbox span::before'  => 'background:{{VALUE}};',
                        '{{WRAPPER}} .element-ready-modal-checkbox .input-checkbox'  => 'background:{{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Border:: get_type(),
                [
                    'name'     => 'remember_box_check_border',
                    'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .form-checkbox span::before,{{WRAPPER}} .element-ready-modal-checkbox .input-checkbox',
                     
                    
                ]
            );
        $this->end_controls_section();
        //
     
        
        /*---------------------------
            INPUT FIELD STYLE TAB START
        ----------------------------*/
        $this->start_controls_section(
            '_tform_input_style_section',
            [
                'label' => esc_html__( 'Input', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'input_box_tabs' );
                $this->start_controls_tab(
                    'input_box_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_responsive_control(
                        'input_box_height',
                        [
                            'label'      => esc_html__( 'Height', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'max' => 150,
                                ],
                            ],
                            'default' => [
                                'size' => 55,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .input-text'   => 'height:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .input-box .input-text'   => 'height:{{SIZE}}{{UNIT}};',
                           
                               
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_width',
                        [
                            'label'      => esc_html__( 'Width', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                                'size' => 100,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .input-text'=> 'width:{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .input-box .input-text'=> 'width:{{SIZE}}{{UNIT}};',
                         
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'     => 'input_box_typography',
                          
                            'selector' => '{{WRAPPER}} .input-text,{{WRAPPER}} .input-box',
                              
                        ]
                    );

                    $this->add_control(
                        'input_box_text_color',
                        [
                            'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .input-text'  => 'color:{{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text'  => 'color:{{VALUE}};',
                              
                        
                            ],
                        ]
                    );

                    $this->add_control(
                        'input_box_bgtext_color',
                        [
                            'label'     => esc_html__( 'Background Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .input-text'  => 'Background:{{VALUE}} !important;',
                                '{{WRAPPER}} .input-box .input-text'  => 'Background:{{VALUE}} !important;',
                              
                        
                            ],
                        ]
                    );
                   
                    $this->add_control(
                        'input_box_placeholder_color',
                        [
                            'label'     => esc_html__( 'Placeholder Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .input-text::-webkit-input-placeholder'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text::-moz-placeholder'            => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text:-ms-input-placeholder'        => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text::-moz-placeholder'           => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text:-ms-input-placeholder'       => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text::-webkit-input-placeholder'    => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text::-moz-placeholder'             => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-text:-ms-input-placeholder'         => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text::-webkit-input-placeholder'   => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text::-moz-placeholder'            => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text:-ms-input-placeholder'        => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text::-webkit-input-placeholder'  => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text::-moz-placeholder'           => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text:-ms-input-placeholder'       => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text::-webkit-input-placeholder'    => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text::-moz-placeholder'             => 'color: {{VALUE}};',
                                '{{WRAPPER}} .input-box .input-text:-ms-input-placeholder'         => 'color: {{VALUE}};',
                                
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'input_box_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => ' {{WRAPPER}} .input-text, {{WRAPPER}} .input-box .input-text',
                             
                            
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .input-text' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                                '{{WRAPPER}} .input-box .input-text' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                             ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'input_box_shadow',
                            'selector' => '{{WRAPPER}} .input-text',   
                            
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}}  .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .input-box .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                              
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'input_box_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .input-box .input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_control(
                        'input_box_transition',
                        [
                            'label'      => esc_html__( 'Transition', 'element-ready-lite' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0.1,
                                    'max'  => 3,
                                    'step' => 0.1,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 0.3,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .input-text'   => 'transition: {{SIZE}}s;',
                                '{{WRAPPER}} .input-box .input-text'   => 'transition: {{SIZE}}s;',
                           

                            ],
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'input_box_hover_tabs',
                    [
                        'label' => esc_html__( 'Focus', 'element-ready-lite' ),
                    ]
                );
                $this->add_control(
                    'input_box_hover_color',
                    [
                        'label'     => esc_html__( 'Text Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .input-text:focus'  => 'color:{{VALUE}};',
                            '{{WRAPPER}} .input-box .input-text:focus'  => 'color:{{VALUE}};',
                         
                         
                        ],
                    ]
                );
              
                $this->add_control(
                    'input_box_hover_border_color',
                    [
                        'label'     => esc_html__( 'Border Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .input-text:focus'   => 'border-color:{{VALUE}};',
                            '{{WRAPPER}} .input-box .input-text:focus'   => 'border-color:{{VALUE}};',
                         ],
                    ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow:: get_type(),
                    [
                        'name'     => 'input_box_hover_shadow',
                        'selector' => '{WRAPPER}}  .input-text:focus, {WRAPPER}} .input-box .input-text:focus',
                          
                    ]
                );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            '_label_cin_style_section',
            [
                'label' => esc_html__( 'Label & Icon', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_box_text_color',
            [
                'label'     => esc_html__( 'Label Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .input-box label'  => 'color:{{VALUE}};',
                   
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_box_typography',
                'label'     => esc_html__( 'Label font', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} .input-box label',
                
            ]
        );

        $this->add_control(
            'icon_box_text_color',
            [
                'label'     => esc_html__( 'Icon Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .input-box i'  => 'color:{{VALUE}};',
                    '{{WRAPPER}} .input-box svg'  => 'color:{{VALUE}};',
                   
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'icon_box_typography',
                'label'     => esc_html__( 'Label font', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} .input-box i,{{WRAPPER}} .input-box svg',
                
            ]
        );

      

        $this->end_controls_section();
        $this->box_css(
            array(
                'title' => esc_html__('User Name Field','element-ready-lite'),
                'slug' => 'wrapper_username_box_style',
                'element_name' => 'wrapper_user_name_element_ready_',
                'selector' => '{{WRAPPER}} .input-box.er-username',
                
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('User Name Lebel','element-ready-lite'),
                'slug' => 'wrapper_username_lebel_style',
                'element_name' => 'wrapper_user_lebel_element_ready_',
                'selector' => '{{WRAPPER}} .input-box.er-username label',
                
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('User Name Icon','element-ready-lite'),
                'slug' => 'wrapper_username_icon_style',
                'element_name' => 'wrapper_user_icn_element_ready_',
                'selector' => '{{WRAPPER}} .input-box.er-username i, {{WRAPPER}} .input-box.er-username svg',
                
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Password Field','element-ready-lite'),
                'slug' => 'wrapper_pass_box_style',
                'element_name' => 'wrapper_pass_element_ready_',
                'selector' => '{{WRAPPER}} .input-box.er-pass',
                
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Password Lebel','element-ready-lite'),
                'slug' => 'wrapper_pass_lebel_style',
                'element_name' => 'wrapper_pass_lebel_element_ready_',
                'selector' => '{{WRAPPER}} .input-box.er-pass label',
                
            )
        );

        $this->box_css(
            array(
                'title' => esc_html__('Password Icon','element-ready-lite'),
                'slug' => 'wrapper_passworde_icon_style',
                'element_name' => 'wrapper_passwn_element_ready_',
                'selector' => '{{WRAPPER}} .input-box.er-pass i, {{WRAPPER}} .input-box.er-pass svg',
                
            )
        );

        $this->text_wrapper_css(
            array(
                'title' => esc_html__('Submit Icon','element-ready-lite'),
                'slug' => 'wrapper_submit_icon_style',
                'element_name' => 'wrapper_submit_icn_element_ready_',
                'selector' => '{{WRAPPER}} .element-ready-user-login-btn i',
                
            )
        );

        $this->start_controls_section(
            'alignment_success_msg_section',
            [
                'label' => esc_html__( 'Success Message', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'success_msg_align', [
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
				
				],
               'default' => 'left',
            
                'selectors' => [
                     '{{WRAPPER}} .success' => 'text-align: {{VALUE}};',

				],
			]
        );//Responsive control end
        $this->add_control(
            'tsuccess__text_color',
            [
                'label'     => esc_html__( 'Message Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .success'  => 'color:{{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'tsucces_text_typography',
                
                'label'     => esc_html__( 'Message', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} .success',
                   
            ]
        );

        $this->add_control(
            'tsuccess_link_text_color',
            [
                'label'     => esc_html__( 'Link Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .success a'  => 'color:{{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'tsuccess_typography',
                
                'label'     => esc_html__( 'Link', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} .success a',
                   
            ]
        );
        $this->add_responsive_control(
			'success_margin',
			[
				'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .success' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'error__msg_section',
            [
                'label' => esc_html__( 'Error Message', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
			'error_msg_align', [
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
				
				],
               'default' => 'left',
            
                'selectors' => [
                     '{{WRAPPER}} .errors' => 'text-align: {{VALUE}};',

				],
			]
        );//Responsive control end
        $this->add_control(
            'error__text_color',
            [
                'label'     => esc_html__( 'Message Color', 'element-ready-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .errors li'  => 'color:{{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'eror_text_typography',
                
                'label'     => esc_html__( 'Message', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} .errors li',
                   
            ]
        );

       
        $this->add_responsive_control(
			'error_msg_margin',
			[
				'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .errors' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        $this->add_responsive_control(
			'error_msg_padding',
			[
				'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .errors li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->end_controls_section();
    } //Register control end


    protected function render( ) { 

        $settings     = $this->get_settings();
        $widget_id    = 'element-ready-'.$this->get_id().'-';
        
    ?>
     
    <?php if($settings['_style'] == 'style1'): ?>
        <?php  include('layout/signin/style1.php'); ?>   
    <?php endif; ?>  
    <?php  
    }
    protected function content_template(){}
}