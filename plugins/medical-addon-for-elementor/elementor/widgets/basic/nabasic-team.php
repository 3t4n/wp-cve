<?php
/*
 * Elementor Medical Addon for Elementor Team Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Team extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_basic_team';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Team', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-basic-category'];
	}

	/**
	 * Register Medical Addon for Elementor Team widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_team',
			[
				'label' => __( 'Team Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'team_style',
			[
				'label' => __( 'Team Style', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'medical-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'medical-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three', 'medical-addon-for-elementor' ),
					'four' => esc_html__( 'Style Four', 'medical-addon-for-elementor' ),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'team_image',
			[
				'label' => esc_html__( 'Upload Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your icon image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_control(
			'image_link',
			[
				'label' => esc_html__( 'Image Link', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'team_style' => array('one', 'three'),
				],
			]
		);
		$this->add_control(
			'team_title',
			[
				'label' => esc_html__( 'Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'William Smith', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'team_title_link',
			[
				'label' => esc_html__( 'Title Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'team_subtitle',
			[
				'label' => esc_html__( 'Sub Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'CEO/ Founder', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-facebook-square',
			]
		);
		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'Social Iocns', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ social_icon }}}',
				'prevent_empty' => false,
			]
		);

		$repeaterOne = new Repeater();
		$repeaterOne->add_control(
			'contact_icon',
			[
				'label' => esc_html__( 'Contact Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-envelope',
			]
		);
		$repeaterOne->add_control(
			'contact_text',
			[
				'label' => esc_html__( 'Contact Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'wilmore@mail.com', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeaterOne->add_control(
			'contact_link',
			[
				'label' => esc_html__( 'Contact Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'contact_groups',
			[
				'label' => esc_html__( 'Contact Info', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeaterOne->get_controls(),
				'title_field' => '{{{ contact_text }}}',
				'prevent_empty' => false,
			]
		);

		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .namep-mate-info' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'team_style' => array('one', 'two'),
					],
				]
			);
			$this->add_control(
				'box_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'team_section_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'team_section_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-mate-info',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-mate-info',
				]
			);
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectnt_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'team_style' => array('three'),
					],
				]
			);
			$this->add_control(
				'boxt_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .team-style-three .namep-mate-item:before, {{WRAPPER}} .team-style-three .namep-mate-item .namep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'teamt_section_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .team-style-three .namep-mate-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secnt_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .team-style-three .namep-mate-item:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secnt_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .team-style-three .namep-mate-item:before',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secnt_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .team-style-three .namep-mate-item:before',
				]
			);
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'social_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'icon_style' );
				$this->start_controls_tab(
						'icon_normal',
						[
							'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
						]
					);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-social a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_bg',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-social a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'icon_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-social a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'icon_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'icon_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-social a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_bg_hov',
					[
						'label' => esc_html__( 'Background Hover Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-social a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'icon_hover_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-social a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->add_responsive_control(
				'icon_size',
				[
					'label' => esc_html__( 'Size', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .namep-social a' => 'font-size:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'icon_width',
				[
					'label' => esc_html__( 'Width', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .namep-social a' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-social a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .namep-mate-info h4',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-mate-info h4, {{WRAPPER}} .namep-mate-info h4 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-mate-info h4 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Sub Title
			$this->start_controls_section(
				'section_subtitle_style',
				[
					'label' => esc_html__( 'Sub Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'subtitle_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .namep-mate-info p',
				]
			);
			$this->add_control(
				'subtitle_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-mate-info p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$team_style = !empty( $settings['team_style'] ) ? $settings['team_style'] : '';
		$team_image = !empty( $settings['team_image']['id'] ) ? $settings['team_image']['id'] : '';
		$image_link = !empty( $settings['image_link']['url'] ) ? $settings['image_link']['url'] : '';
		$image_link_external = !empty( $settings['image_link']['is_external'] ) ? 'target="_blank"' : '';
		$image_link_nofollow = !empty( $settings['image_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$image_link_attr = !empty( $image_link ) ?  $image_link_external.' '.$image_link_nofollow : '';
		$team_title = !empty( $settings['team_title'] ) ? $settings['team_title'] : '';
		$team_title_link = !empty( $settings['team_title_link']['url'] ) ? $settings['team_title_link']['url'] : '';
		$team_title_link_external = !empty( $settings['team_title_link']['is_external'] ) ? 'target="_blank"' : '';
		$team_title_link_nofollow = !empty( $settings['team_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$team_title_link_attr = !empty( $team_title_link ) ?  $team_title_link_external.' '.$team_title_link_nofollow : '';
		$team_subtitle = !empty( $settings['team_subtitle'] ) ? $settings['team_subtitle'] : '';
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		$contact_groups = !empty( $settings['contact_groups'] ) ? $settings['contact_groups'] : '';

		// Image
		$image_url = wp_get_attachment_url( $team_image );
		$title_link = $team_title_link ? '<a href="'.esc_url($team_title_link).'" '.$team_title_link_attr.'>'.esc_html($team_title).'</a>' : esc_html($team_title);
		$title = $team_title ? '<h4 class="namep-mate-name">'.$title_link.'</h4>' : '';
		$subtitle = $team_subtitle ? '<p>'.esc_html($team_subtitle).'</p>' : '';

		if ($team_style === 'two') {
			$style_class = ' team-style-two';
		} elseif ($team_style === 'three') {
			$style_class = ' team-style-three';
		} elseif ($team_style === 'four') {
			$style_class = ' team-style-four';
		} else {
			$style_class = '';
		}
		if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
			$icon_cls = '';
		} else {
			$icon_cls = ' no-icon';
		}
		// Turn output buffer on
		ob_start(); ?>
		<div class="namep-team<?php echo esc_attr($style_class); ?>">
		<?php if ($team_style === 'two') { ?>
			<div class="namep-mate-item">
				<?php if ($team_image) { ?>
					<div class="namep-image">
						<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>">
						<div class="namep-mate-info-wrap">
		          <div class="namep-table-wrap">
		            <div class="namep-align-wrap">
		              <div class="namep-social rounded">
		                <ul>
		                	<?php
											// Group Param Output
											if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
											  foreach ( $listItems_groups as $each_list ) {
											  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
												$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
												$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
												$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
												$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
											  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
												$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';
									   		?>
											  <li><a href="<?php echo esc_url($link_url); ?>" <?php echo $link_attr; ?>><?php echo $icon; ?></a></li>
											<?php } } ?>
		                </ul>
		              </div>
		            </div>
		          </div>
		        </div>
					</div>
				<?php } ?>
	      <div class="namep-mate-info">
	        <?php echo $title.$subtitle; ?>
	      </div>
	    </div>
	  <?php } elseif ($team_style === 'three') { ?>
			<div class="namep-mate-item<?php echo esc_attr($icon_cls); ?>">
				<?php if ($team_image) { ?>
					<div class="namep-image">
						<?php if ($image_link) { ?>
							<a href="<?php echo esc_url($image_link); ?>" <?php echo esc_attr($image_link_attr); ?>><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>"></a>
						<?php } else { ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>">
						<?php } ?>						
					</div>
				<?php } ?>
	      <div class="namep-mate-info">
	        <?php echo $title.$subtitle; ?>
	        <div class="namep-social">
            <ul>
            	<?php
							// Group Param Output
							if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
							  foreach ( $listItems_groups as $each_list ) {
							  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
								$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
								$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
								$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
								$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
							  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
								$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';
					   		?>
							  <li><a href="<?php echo esc_url($link_url); ?>" <?php echo $link_attr; ?>><?php echo $icon; ?></a></li>
							<?php } } ?>
            </ul>
          </div>
	      </div>
	    </div>
	  <?php } elseif ($team_style === 'four') { ?>
			<div class="namep-mate-item">
				<?php if ($team_image) { ?>
					<div class="namep-image">
						<?php if ($image_link) { ?>
							<a href="<?php echo esc_url($image_link); ?>" <?php echo esc_attr($image_link_attr); ?>><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>"></a>
						<?php } else { ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>">
						<?php } ?>						
					</div>
				<?php } ?>
	      <div class="namep-mate-info">
		      <div class="mate-info-wrap">
		        <?php echo $title.$subtitle; ?>
	          <ul>
	          	<?php
							// Group Param Output
							if ( is_array( $contact_groups ) && !empty( $contact_groups ) ){
							  foreach ( $contact_groups as $each_list ) {
							  $contact_text = !empty( $each_list['contact_text'] ) ? $each_list['contact_text'] : '';
							  $contact_link = !empty( $each_list['contact_link'] ) ? $each_list['contact_link'] : '';
								$link_url = !empty( $contact_link['url'] ) ? esc_url($contact_link['url']) : '';
								$link_external = !empty( $contact_link['is_external'] ) ? 'target="_blank"' : '';
								$link_nofollow = !empty( $contact_link['nofollow'] ) ? 'rel="nofollow"' : '';
								$link_attr = !empty( $contact_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
							  $contact_icon = !empty( $each_list['contact_icon'] ) ? $each_list['contact_icon'] : '';
								$icon = $contact_icon ? '<i class="'.esc_attr($contact_icon).'" aria-hidden="true"></i>' : '';
								$text = $link_url ? '<a href="'. esc_url($link_url).'" '. $link_attr.'>'.$contact_text.'</a>' : $contact_text;
					   		?>
							  <li><?php echo $icon.' '.$text; ?></li>
							<?php } } ?>
	          </ul>
		      </div>
	      </div>
	    </div>
	  <?php } else { ?>
			<div class="namep-mate-item">
				<?php if ($team_image) { ?>
					<div class="namep-image">
						<?php if ($image_link) { ?>
							<a href="<?php echo esc_url($image_link); ?>" <?php echo esc_attr($image_link_attr); ?>><img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>"></a>
						<?php } else { ?>
							<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($team_title); ?>">
						<?php } ?>
					</div>
				<?php } ?>
	      <div class="namep-mate-info">
	        <?php echo $title.$subtitle; ?>
	        <div class="namep-social">
            <ul>
            	<?php
							// Group Param Output
							if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
							  foreach ( $listItems_groups as $each_list ) {
							  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
								$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
								$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
								$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
								$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
							  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
								$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'" aria-hidden="true"></i>' : '';
					   		?>
							  <li><a href="<?php echo esc_url($link_url); ?>" <?php echo $link_attr; ?>><?php echo $icon; ?></a></li>
							<?php } } ?>
            </ul>
          </div>
	      </div>
	    </div>   
	  <?php } ?>
    </div>   
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Team() );
