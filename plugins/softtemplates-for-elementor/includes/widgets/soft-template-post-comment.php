<?php
/**
 * Class: Soft_Template_Post_Comment
 * Name: Post Comment
 * Slug: soft-template-post-comment
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Post_Comment extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-post-comment';
	}

	public function get_title() {
		return esc_html__( 'Post Comment', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-comments';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}

    protected function register_controls() {
        // Widget main
        $this->widget_main_options();

        // Form Style
        $this->widget_comments_elements();       
        $this->post_comment_reply_btn();       
        $this->form_elements();       
        
        // Button Style
        $this->post_comment_btn_style();
        
    }

    

    public function widget_main_options() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Post Comment', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => __( 'Style', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''      => __( 'Select', 'soft-template-core' ),
					'theme' => __( 'Theme Default', 'soft-template-core' ),
				],
				'default' => 'theme',
			]
		);

		$this->end_controls_section();
    }

    public function widget_comments_elements() {
        $this->start_controls_section(
            'comment_items',
            [
                'label' => __( 'Comments Item', 'soft-template-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'comment_items_bg',
            [
                'label' => __( 'Item Background', 'soft-template-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #comments li.comment .comment-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'comment_items_color',
            [
                'label' => __( 'Item Color', 'soft-template-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #comments li.comment .comment-body' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'comment_items_border',
				'label' => __( 'Border', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} #comments li.comment .comment-body',
			]
		);

        $this->__add_responsive_control(
            'comment_items_padding',
            [
                'label' => __( 'Item Padding', 'soft-template-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'placeholder' => '1',
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} #comments li.comment .comment-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );     
        
        $this->__add_responsive_control(
            'comment_items_margin',
            [
                'label' => __( 'Item Margin', 'soft-template-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'placeholder' => '1',
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} #comments li.comment .comment-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'comments_item_meta_head',
            [
                'label' => __( 'Meta', 'soft-template-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_items_meta_color',
            [
                'label' => __( 'Meta Color', 'soft-template-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .comment-metadata' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .comment-metadata a' => 'color: inherit;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} #comments .comment-metadata',
            ]
        );

        $this->add_control(
            'comments_item_author_head',
            [
                'label' => __( 'Author', 'soft-template-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'comment_items_autor_color',
            [
                'label' => __( 'Meta Color', 'soft-template-core' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #comments .comment-author a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} #comments .comment-author a',
            ]
        );

        $this->__add_responsive_control(
			'comment_author_thum_pos',
			[
				'label' => __( 'Thumbnail Position', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units'    => ['px', 'em', '%'],
                'default'     	=> [ 'unit' => '%' ],
				'selectors' => [
                    '{{WRAPPER}} #comments .comment .avatar' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} #comments .pingback .avatar' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);	

        $this->end_controls_section();
    }

    public function post_comment_reply_btn() {
        $this->start_controls_section(
            'post_reply_btn',
            [
                'label' => __( 'Reply Button', 'soft-template-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'display_reply_btn',
			[
				'label'   => __( 'Style', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''      => __( 'Select', 'soft-template-core' ),
					'block' => __( 'Block', 'soft-template-core' ),
					'flex' => __( 'Flex', 'soft-template-core' ),
					'inline-flex' => __( 'Inline Flex', 'soft-template-core' ),
					'inline-block' => __( 'Inline Block', 'soft-template-core' ),
				],
				'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .comment-reply-link' => 'display: {{VALUE}};',
                ],
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reply_typography',
                'selector' => '{{WRAPPER}} .comment-reply-link',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->box_model_controls(
            $this,
            [
                'name'          => 'reply_button',
                'label'         => __( 'Button', 'soft-template-core' ),
                'border'        => true,
                'border-radius' => true,
                'margin'        => true,
                'padding'       => true,
                'box-shadow'    => true,
                'selector'      => '{{WRAPPER}} .comment-reply-link',
            ]
        );

        $this->start_controls_tabs( 'reply_button_style' );
            $this->start_controls_tab( 'reply_button_normal', [ 'label' => __( 'Normal', 'soft-template-core' ) ] );

                $this->add_control(
                    'reply_button_text_color',
                    [
                        'label'     => __( 'Color', 'soft-template-core' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .comment-reply-link' => 'color:{{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'reply_button_color',
                    [
                        'label'     => __( 'Background Color', 'soft-template-core' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .comment-reply-link' => 'background:{{VALUE}};',
                        ],
                    ]
                );             
            $this->end_controls_tab();

            $this->start_controls_tab( 'reply_button_hover', [ 'label' => __( 'Hover', 'soft-template-core' ) ] );

                $this->add_control(
                    'reply_button_text_color_hover',
                    [
                        'label'     => __( 'Color', 'soft-template-core' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .comment-reply-link:hover' => 'color:{{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'reply_button_color_hover',
                    [
                        'label'     => __( 'Background Color', 'soft-template-core' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .comment-reply-link:hover' => 'background:{{VALUE}};',
                        ],
                    ]
                );                
                
                $this->add_control(
                    'reply_button_border_color_hover',
                    [
                        'label'     => __( 'Border Color', 'soft-template-core' ),
                        'type'      => Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .comment-reply-link:hover' => 'border-color:{{VALUE}};',
                        ],
                    ]
                );

            $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }
    
    public function post_comment_btn_style() {
        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Submit Button', 'soft-template-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
    
            $this->add_control(
                'button_heading',
                [
                    'label'     => __( 'Button Styles', 'soft-template-core' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
    
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'content_typography',
                    'label'    => __( 'Content Typography', 'soft-template-core' ),
                    'global'   => [
                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .submit',
                ]
            );
    
            $this->start_controls_tabs( 'button_style' );
                $this->start_controls_tab( 'button_normal', [ 'label' => __( 'Normal', 'soft-template-core' ) ] );
        
                    $this->add_control(
                        'button_text_color',
                        [
                            'label'     => __( 'Color', 'soft-template-core' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .submit' => 'color:{{VALUE}};',
                            ],
                        ]
                    );
        
                    $this->add_control(
                        'button_color',
                        [
                            'label'     => __( 'Background Color', 'soft-template-core' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .submit' => 'background:{{VALUE}};',
                            ],
                        ]
                    );
        
                    $this->box_model_controls(
                        $this,
                        [
                            'name'          => 'button',
                            'label'         => __( 'Button', 'soft-template-core' ),
                            'border'        => true,
                            'border-radius' => true,
                            'margin'        => false,
                            'padding'       => true,
                            'box-shadow'    => true,
                            'selector'      => '{{WRAPPER}} .submit',
                        ]
                    );
        
                $this->end_controls_tab();
        
                $this->start_controls_tab( 'button_hover', [ 'label' => __( 'Hover', 'soft-template-core' ) ] );
        
                    $this->add_control(
                        'button_text_color_hover',
                        [
                            'label'     => __( 'Color', 'soft-template-core' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .submit:hover' => 'color:{{VALUE}};',
                            ],
                        ]
                    );
        
                    $this->add_control(
                        'button_color_hover',
                        [
                            'label'     => __( 'Background Color', 'soft-template-core' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .submit:hover' => 'background:{{VALUE}};',
                            ],
                        ]
                    );
        
                    $this->box_model_controls(
                        $this,
                        [
                            'name'          => 'button_hover',
                            'label'         => __( 'Button', 'soft-template-core' ),
                            'border'        => true,
                            'border-radius' => true,
                            'margin'        => false,
                            'padding'       => false,
                            'box-shadow'    => true,
                            'selector'      => '{{WRAPPER}} .submit:hover',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
    
        $this->end_controls_section();
    }

    public function form_elements() {
        $this->start_controls_section(
            'section_form',
            [
                'label' => __( 'Comment Form', 'soft-template-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'comments_forms_label',
                [
                    'label' => __( 'Form Wrapper', 'soft-template-core' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'form_wrapper_bg',
                [
                    'label' => __( 'Container Background', 'soft-template-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .comment-respond form' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->__add_responsive_control(
                'form_wrapper_padding',
                [
                    'label' => __( 'Container Padding', 'soft-template-core' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => [ 'px' ],
                    'selectors' => [
                        '{{WRAPPER}} .comment-respond form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );      
            
            $this->__add_responsive_control(
                'comment_reply_title_spacing',
                [
                    'label' => __( 'Reply Title Spacing', 'soft-template-core' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => [ 'px' ],
                    'selectors' => [
                        '{{WRAPPER}} .comment-reply-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'comment_reply_title_typo',
                    'selector' => '{{WRAPPER}} .comment-reply-title',
                ]
            );


            $this->add_control(
                'heading_label',
                [
                    'label' => __( 'Label', 'soft-template-core' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );


            $this->add_control(
                'label_spacing',
                [
                    'label' => __( 'Spacing', 'soft-template-core' ),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        'body.rtl {{WRAPPER}} .elementor-soft-template-post-comment label' => 'padding-left: {{SIZE}}{{UNIT}};',
                        // for the label position = inline option
                        'body:not(.rtl) {{WRAPPER}} .elementor-soft-template-post-comment label' => 'padding-right: {{SIZE}}{{UNIT}};',
                        // for the label position = inline option
                        'body {{WRAPPER}} .elementor-soft-template-post-comment label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                        // for the label position = above option
                    ],
                ]
            );

            $this->add_control(
                'label_color',
                [
                    'label' => __( 'Text Color', 'soft-template-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-soft-template-post-comment label' => 'color: {{VALUE}};',
                    ],
                    'global' => [
                        'default' => Global_Colors::COLOR_TEXT,
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'label_typography',
                    'selector' => '{{WRAPPER}} .elementor-soft-template-post-comment label',
                    'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT,
                    ],
                ]
            );

            $this->add_control(
                'field_label',
                [
                    'label' => __( 'Field', 'soft-template-core' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'field_text_color',
                [
                    'label' => __( 'Text Color', 'soft-template-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-soft-template-post-comment input:not([type="submit"]), {{WRAPPER}} .elementor-soft-template-post-comment textarea' => 'color: {{VALUE}};',
                    ],
                    'global' => [
                        'default' => Global_Colors::COLOR_TEXT,
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'field_typography',
                    'selector' => '{{WRAPPER}} .elementor-soft-template-post-comment input:not([type="submit"]), {{WRAPPER}} .elementor-soft-template-post-comment textarea',
                    'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT,
                    ],
                ]
            );

            $this->add_control(
                'field_background_color',
                [
                    'label' => __( 'Background Color', 'soft-template-core' ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .elementor-soft-template-post-comment input:not([type="submit"]), {{WRAPPER}} .elementor-soft-template-post-comment textarea' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'field_border_color',
                [
                    'label' => __( 'Border Color', 'soft-template-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-soft-template-post-comment input:not([type="submit"]), {{WRAPPER}} .elementor-soft-template-post-comment textarea' => 'border-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );
    
            $this->add_control(
                'field_border_width',
                [
                    'label' => __( 'Border Width', 'soft-template-core' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => [ 'px' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-soft-template-post-comment input:not([type="submit"]), {{WRAPPER}} .elementor-soft-template-post-comment textarea' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_control(
                'field_border_radius',
                [
                    'label' => __( 'Border Radius', 'soft-template-core' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-soft-template-post-comment input:not([type="submit"]), {{WRAPPER}} .elementor-soft-template-post-comment textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();
    }

    public function box_model_controls( $widget, $args ) {

		$defaults = [
			'border'        => true,
			'border-radius' => true,
			'margin'        => true,
			'padding'       => true,
			'box-shadow'    => true,
		];

		$args = wp_parse_args( $args, $defaults );

		if ( $args['border'] ) {
			$widget->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => $args['name'] . '_border',
					'label'    => __( $args['label'] . ' Border', 'soft-template-core' ),
					'selector' => $args['selector'],
				]
			);
		}

		if ( $args['border-radius'] ) {
			$widget->add_control(
				$args['name'] . '_border_radius',
				[
					'label'      => __( 'Border Radius', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						$args['selector'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		}

		if ( $args['box-shadow'] ) {
			$widget->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => $args['name'] . '_box_shadow',
					'label'    => __( 'Box Shadow', 'soft-template-core' ),
					'selector' => $args['selector'],
				]
			);
		}

		if ( $args['padding'] ) {
			$widget->add_control(
				$args['name'] . '_padding',
				[
					'label'      => __( 'Padding', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						$args['selector'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		}

		if ( $args['margin'] ) {
			$widget->add_control(
				$args['name'] . '_margin',
				[
					'label'      => __( 'Margin', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						$args['selector'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		}
	}

    protected function render() {
        $this->__context = 'render';

        $settings  = $this->get_settings();
		$post_data = \Soft_template_Core_Utils::get_demo_post_data();

        global $post;
		$post = $post_data;
        
        $this->__open_wrap();
        
            setup_postdata( $post );
                comments_template();
            wp_reset_postdata();

        $this->__close_wrap();
    }

}