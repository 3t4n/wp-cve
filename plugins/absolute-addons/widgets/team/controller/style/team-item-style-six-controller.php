<?php
defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;


// Add Skill Profile Control
$this->start_controls_section(
	'team_member_skills',
	array(
		'label'      => __( 'Skills', 'absolute-addons' ),
		'tab'        => Controls_Manager::TAB_CONTENT,
		'conditions' => [
			'relation' => 'and',
			'terms'    => [
				[
					'name'     => 'team_style_variation',
					'operator' => '==',
					'value'    => 'six',
				],

			],
		],
	) );

$skills = new Repeater();

$skills->add_control(
	'team_member_skill_name',
	array(
		'label'       => esc_html__( 'Add Skill Title', 'absolute-addons' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'Example:Photoshop', 'absolute-addons' ),
		'default'     => __( 'PhotoShop', 'absolute-addons' ),
	)
);

$skills->add_control(
	'team_member_skill_number',
	array(
		'label'      => esc_html__( 'Add Skill Number', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ '%' ],
		'range'      => [
			'%' => [
				'min' => 0,
				'max' => 100,
			],
		],
		'default'    => [
			'unit' => '%',
			'size' => 50,
		],
//		'selectors' => [
//			'{{WRAPPER}} .absp-team.element-six .absp-team-item .skill-area ul > {{CURRENT_ITEM}} > .skills-wrapper .skill' => 'width: {{SIZE}}{{UNIT}};',
//		],
	)
);

$skills->start_controls_tabs(
	'team_social'
);

$skills->start_controls_tab(
	'team_social_normal_tab',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$skills->add_control(
	'team_member_skill_name_color',
	array(
		'label'     => esc_html__( 'Select Skill Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-team-item .skill-area ul > {{CURRENT_ITEM}} >  .skill-name' => 'color:{{VALUE}}',
		],
	)
);

$skills->add_control(
	'team_member_skill_number_color',
	array(
		'label'     => esc_html__( 'Select Skill Bar Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-team-item .skill-area ul > {{CURRENT_ITEM}} > .skills-wrapper .skill'                 => 'background:{{VALUE}}',
			'{{WRAPPER}} .absp-team-item .skill-area ul > {{CURRENT_ITEM}} >  .skills-wrapper > .skill_number >span' => 'color:{{VALUE}}',
		],
	)
);

$skills->end_controls_tab();

$skills->start_controls_tab(
	'team_social_hover_tab',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$skills->add_control(
	'team_member_skill_name_hover',
	array(
		'label'     => esc_html__( 'Select Skill Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-team-item .skill-area ul > {{CURRENT_ITEM}} >  .skill-name:hover' => 'color:{{VALUE}}',
		],

	)
);

$skills->add_control(
	'team_member_skill_number_hover',
	array(
		'label'     => esc_html__( 'Select Skill Bar Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-team-item .skill-area ul > {{CURRENT_ITEM}} > .skills-wrapper .skill:hover' => 'background:{{VALUE}}',
		],
	)
);

$skills->end_controls_tab();

$skills->end_controls_tabs();

$this->add_control(
	'team_member_skill_area',
	array(
		'label'       => esc_html__( 'Skills', 'absolute-addons' ),
		'type'        => Controls_Manager::REPEATER,
		'fields'      => $skills->get_controls(),
		'title_field' => '{{{team_member_skill_name}}}',
		'default'     => [
			[
				'team_member_skill_name'   => __( 'Adobe Photoshop', 'absolute-addons' ),
				'team_member_skill_number' => __( '50', 'absolute-addons' ),

			],

		],
	)
);

$this->end_controls_section();

