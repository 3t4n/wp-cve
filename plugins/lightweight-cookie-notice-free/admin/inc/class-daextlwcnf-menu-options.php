<?php

/**
 * This class adds the options with the related callbacks and validations.
 */
class Daextlwcnf_Menu_Options {

	private $shared = null;

	public function __construct( $shared ) {

		//assign an instance of the plugin info
		$this->shared = $shared;

	}

	public function register_options() {

		//Section General ----------------------------------------------------------------------------------------------
		add_settings_section(
			'daextlwcnf_general_settings_section',
			null,
			null,
			'daextlwcnf_general_options'
		);

		add_settings_field(
			'headings_font_family',
			esc_html__( 'Headings Font Familly', 'daextlwcnf' ),
			array( $this, 'headings_font_family_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_headings_font_family',
			array( $this, 'headings_font_family_validation' )
		);

		add_settings_field(
			'headings_font_weight',
			esc_html__( 'Headings Font Weight', 'daextlwcnf' ),
			array( $this, 'headings_font_weight_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_headings_font_weight',
			array( $this, 'headings_font_weight_validation' )
		);

		add_settings_field(
			'paragraphs_font_family',
			esc_html__( 'Paragraphs Font Family', 'daextlwcnf' ),
			array( $this, 'paragraphs_font_family_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_paragraphs_font_family',
			array( $this, 'paragraphs_font_family_validation' )
		);

		add_settings_field(
			'paragraphs_font_weight',
			esc_html__( 'Paragraphs Font Weight', 'daextlwcnf' ),
			array( $this, 'paragraphs_font_weight_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_paragraphs_font_weight',
			array( $this, 'paragraphs_font_weight_validation' )
		);

		add_settings_field(
			'strong_tags_font_weight',
			esc_html__( 'Strong Tags Font Weight', 'daextlwcnf' ),
			array( $this, 'strong_tags_font_weight_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_strong_tags_font_weight',
			array( $this, 'strong_tags_font_weight_validation' )
		);

		add_settings_field(
			'buttons_font_family',
			esc_html__( 'Buttons Font Family', 'daextlwcnf' ),
			array( $this, 'buttons_font_family_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_buttons_font_family',
			array( $this, 'buttons_font_family_validation' )
		);

		add_settings_field(
			'buttons_font_weight',
			esc_html__( 'Buttons Font Weight', 'daextlwcnf' ),
			array( $this, 'buttons_font_weight_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_buttons_font_weight',
			array( $this, 'buttons_font_weight_validation' )
		);

		add_settings_field(
			'buttons_border_radius',
			esc_html__( 'Buttons Border Radius', 'daextlwcnf' ),
			array( $this, 'buttons_border_radius_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_buttons_border_radius',
			array( $this, 'buttons_border_radius_validation' )
		);

		add_settings_field(
			'containers_border_radius',
			esc_html__( 'Containers Border Radius', 'daextlwcnf' ),
			array( $this, 'containers_border_radius_callback' ),
			'daextlwcnf_general_options',
			'daextlwcnf_general_settings_section'
		);

		register_setting(
			'daextlwcnf_general_options',
			'daextlwcnf_containers_border_radius',
			array( $this, 'containers_border_radius_validation' )
		);

		//Section Cookie Notice ----------------------------------------------------------------------------------------
		add_settings_section(
			'daextlwcnf_cookie_notice_settings_section',
			null,
			null,
			'daextlwcnf_cookie_notice_options'
		);

		add_settings_field(
			'main_message_text',
			esc_html__( 'Message Text', 'daextlwcnf' ),
			array( $this, 'cookie_notice_main_message_text_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);


		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_main_message_text',
			array( $this, 'cookie_notice_main_message_text_validation' )
		);

		add_settings_field(
			'cookie_notice_main_message_font_color',
			esc_html__( 'Message Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_main_message_font_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_main_message_font_color',
			array( $this, 'cookie_notice_main_message_font_color_validation' )
		);

		add_settings_field(
			'cookie_notice_main_message_link_font_color',
			esc_html__( 'Message Link Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_main_message_link_font_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_main_message_link_font_color',
			array( $this, 'cookie_notice_main_message_link_font_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_text',
			esc_html__( 'Button 1 Text', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_text_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_text',
			array( $this, 'cookie_notice_button_1_text_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_action',
			esc_html__( 'Button 1 Action', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_action_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_action',
			array( $this, 'cookie_notice_button_1_action_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_url',
			esc_html__( 'Button 1 URL', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_url_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_url',
			array( $this, 'cookie_notice_button_1_url_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_background_color',
			esc_html__( 'Button 1 Background Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_background_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_background_color',
			array( $this, 'cookie_notice_button_1_background_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_background_color_hover',
			esc_html__( 'Button 1 Background Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_background_color_hover_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_background_color_hover',
			array( $this, 'cookie_notice_button_1_background_color_hover_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_border_color',
			esc_html__( 'Button 1 Border Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_border_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_border_color',
			array( $this, 'cookie_notice_button_1_border_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_border_color_hover',
			esc_html__( 'Button 1 Border Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_border_color_hover_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_border_color_hover',
			array( $this, 'cookie_notice_button_1_border_color_hover_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_font_color',
			esc_html__( 'Button 1 Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_font_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_font_color',
			array( $this, 'cookie_notice_button_1_font_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_1_font_color_hover',
			esc_html__( 'Button 1 Font Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_1_font_color_hover_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_1_font_color_hover',
			array( $this, 'cookie_notice_button_1_font_color_hover_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_text',
			esc_html__( 'Button 2 Text', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_text_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_text',
			array( $this, 'cookie_notice_button_2_text_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_action',
			esc_html__( 'Button 2 Action', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_action_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_action',
			array( $this, 'cookie_notice_button_2_action_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_url',
			esc_html__( 'Button 2 URL', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_url_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_url',
			array( $this, 'cookie_notice_button_2_url_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_background_color',
			esc_html__( 'Button 2 Background Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_background_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_background_color',
			array( $this, 'cookie_notice_button_2_background_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_background_color_hover',
			esc_html__( 'Button 2 Background Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_background_color_hover_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_background_color_hover',
			array( $this, 'cookie_notice_button_2_background_color_hover_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_border_color',
			esc_html__( 'Button 2 Border Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_border_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_border_color',
			array( $this, 'cookie_notice_button_2_border_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_border_color_hover',
			esc_html__( 'Button 2 Border Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_border_color_hover_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_border_color_hover',
			array( $this, 'cookie_notice_button_2_border_color_hover_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_font_color',
			esc_html__( 'Button 2 Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_font_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_font_color',
			array( $this, 'cookie_notice_button_2_font_color_validation' )
		);

		add_settings_field(
			'cookie_notice_button_2_font_color_hover',
			esc_html__( 'Button 2 Font Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_2_font_color_hover_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_2_font_color_hover',
			array( $this, 'cookie_notice_button_2_font_color_hover_validation' )
		);

		add_settings_field(
			'cookie_notice_button_dismiss_action',
			esc_html__( 'Button Dismiss Action', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_dismiss_action_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_dismiss_action',
			array( $this, 'cookie_notice_button_dismiss_action_validation' )
		);

		add_settings_field(
			'cookie_notice_button_dismiss_url',
			esc_html__( 'Button Dismiss URL', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_dismiss_url_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_dismiss_url',
			array( $this, 'cookie_notice_button_dismiss_url_validation' )
		);

		add_settings_field(
			'cookie_notice_button_dismiss_color',
			esc_html__( 'Dismiss Button Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_button_dismiss_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_button_dismiss_color',
			array( $this, 'cookie_notice_button_dismiss_color_validation' )
		);

		add_settings_field(
			'cookie_notice_container_position',
			esc_html__( 'Container Position', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_position_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_position',
			array( $this, 'cookie_notice_container_position_validation' )
		);

		add_settings_field(
			'cookie_notice_container_width',
			esc_html__( 'Wrapper Width', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_width_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_width',
			array( $this, 'cookie_notice_container_width_validation' )
		);

		add_settings_field(
			'cookie_notice_container_background_color',
			esc_html__( 'Container Background Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_background_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_background_color',
			array( $this, 'cookie_notice_container_background_color_validation' )
		);

		add_settings_field(
			'cookie_notice_container_opacity',
			esc_html__( 'Container Opacity', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_opacity_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_opacity',
			array( $this, 'cookie_notice_container_opacity_validation' )
		);

		add_settings_field(
			'cookie_notice_container_border_width',
			esc_html__( 'Container Border Width', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_border_width_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_border_width',
			array( $this, 'cookie_notice_container_border_width_validation' )
		);

		add_settings_field(
			'cookie_notice_container_border_color',
			esc_html__( 'Container Border Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_border_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_border_color',
			array( $this, 'cookie_notice_container_border_color_validation' )
		);

		add_settings_field(
			'cookie_notice_container_border_opacity',
			esc_html__( 'Container Border Opacity', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_border_opacity_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_border_opacity',
			array( $this, 'cookie_notice_container_border_opacity_validation' )
		);

		add_settings_field(
			'cookie_notice_container_drop_shadow',
			esc_html__( 'Container Drop Shadow', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_drop_shadow_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_drop_shadow',
			array( $this, 'cookie_notice_container_drop_shadow_validation' )
		);

		add_settings_field(
			'cookie_notice_container_drop_shadow_color',
			esc_html__( 'Container Drop Shadow Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_container_drop_shadow_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_container_drop_shadow_color',
			array( $this, 'cookie_notice_container_drop_shadow_color_validation' )
		);

		add_settings_field(
			'cookie_notice_mask',
			esc_html__( 'Mask', 'daextlwcnf' ),
			array( $this, 'cookie_notice_mask_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_mask',
			array( $this, 'cookie_notice_mask_validation' )
		);

		add_settings_field(
			'cookie_notice_mask_color',
			esc_html__( 'Mask Color', 'daextlwcnf' ),
			array( $this, 'cookie_notice_mask_color_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_mask_color',
			array( $this, 'cookie_notice_mask_color_validation' )
		);

		add_settings_field(
			'cookie_notice_mask_opacity',
			esc_html__( 'Mask Opacity', 'daextlwcnf' ),
			array( $this, 'cookie_notice_mask_opacity_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_mask_opacity',
			array( $this, 'cookie_notice_mask_opacity_validation' )
		);

		add_settings_field(
			'cookie_notice_shake_effect',
			esc_html__( 'Shake Effect', 'daextlwcnf' ),
			array( $this, 'cookie_notice_shake_effect_callback' ),
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_notice_options',
			'daextlwcnf_cookie_notice_shake_effect',
			array( $this, 'cookie_notice_shake_effect_validation' )
		);

		//Section Cookie Settings --------------------------------------------------------------------------------------
		add_settings_section(
			'daextlwcnf_cookie_settings_settings_section',
			null,
			null,
			'daextlwcnf_cookie_settings_options'
		);

		add_settings_field(
			'cookie_settings_logo_url',
			esc_html__( 'Logo URL', 'daextlwcnf' ),
			array( $this, 'cookie_settings_logo_url_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_logo_url',
			array( $this, 'cookie_settings_logo_url_validation' )
		);

		add_settings_field(
			'cookie_settings_title',
			esc_html__( 'Title', 'daextlwcnf' ),
			array( $this, 'cookie_settings_title_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_title',
			array( $this, 'cookie_settings_title_validation' )
		);

		add_settings_field(
			'cookie_settings_description',
			esc_html__( 'Description', 'daextlwcnf' ),
			array( $this, 'cookie_settings_description_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_description',
			array( $this, 'cookie_settings_description_validation' )
		);

		add_settings_field(
			'cookie_settings_cookie_settings_button_1_text',
			esc_html__( 'Button 1 Text', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_text_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_text',
			array( $this, 'cookie_settings_button_1_text_validation' )
		);

		add_settings_field(
			'cookie_settings_cookie_settings_button_1_action',
			esc_html__( 'Button 1 Action', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_action_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_action',
			array( $this, 'cookie_settings_button_1_action_validation' )
		);

		add_settings_field(
			'cookie_settings_cookie_settings_button_1_url',
			esc_html__( 'Button 1 Url', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_url_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_url',
			array( $this, 'cookie_settings_button_1_url_validation' )
		);

		add_settings_field(
			'cookie_settings_button_1_background_color',
			esc_html__( 'Button 1 Background Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_background_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_background_color',
			array( $this, 'cookie_settings_button_1_background_color_validation' )
		);

		add_settings_field(
			'cookie_settings_button_1_background_color_hover',
			esc_html__( 'Button 1 Background Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_background_color_hover_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_background_color_hover',
			array( $this, 'cookie_settings_button_1_background_color_hover_validation' )
		);

		add_settings_field(
			'cookie_settings_button_1_border_color',
			esc_html__( 'Button 1 Border Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_border_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_border_color',
			array( $this, 'cookie_settings_button_1_border_color_validation' )
		);

		add_settings_field(
			'cookie_settings_button_1_border_color_hover',
			esc_html__( 'Button 1 Border Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_border_color_hover_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_border_color_hover',
			array( $this, 'cookie_settings_button_1_border_color_hover_validation' )
		);

		add_settings_field(
			'cookie_settings_button_1_font_color',
			esc_html__( 'Button 1 Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_font_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_font_color',
			array( $this, 'cookie_settings_button_1_font_color_validation' )
		);

		add_settings_field(
			'cookie_settings_button_1_font_color_hover',
			esc_html__( 'Button 1 Font Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_1_font_color_hover_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_1_font_color_hover',
			array( $this, 'cookie_settings_button_1_font_color_hover_validation' )
		);

		add_settings_field(
			'cookie_settings_cookie_settings_button_2_text',
			esc_html__( 'Button 2 Text', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_text_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_text',
			array( $this, 'cookie_settings_button_2_text_validation' )
		);

		add_settings_field(
			'cookie_settings_cookie_settings_button_2_action',
			esc_html__( 'Button 2 Action', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_action_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_action',
			array( $this, 'cookie_settings_button_2_action_validation' )
		);

		add_settings_field(
			'cookie_settings_cookie_settings_button_2_url',
			esc_html__( 'Button 2 Url', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_url_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_url',
			array( $this, 'cookie_settings_button_2_url_validation' )
		);

		add_settings_field(
			'cookie_settings_button_2_background_color',
			esc_html__( 'Button 2 Background Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_background_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_background_color',
			array( $this, 'cookie_settings_button_2_background_color_validation' )
		);

		add_settings_field(
			'cookie_settings_button_2_background_color_hover',
			esc_html__( 'Button 2 Background Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_background_color_hover_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_background_color_hover',
			array( $this, 'cookie_settings_button_2_background_color_hover_validation' )
		);

		add_settings_field(
			'cookie_settings_button_2_border_color',
			esc_html__( 'Button 2 Border Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_border_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_border_color',
			array( $this, 'cookie_settings_button_2_border_color_validation' )
		);

		add_settings_field(
			'cookie_settings_button_2_border_color_hover',
			esc_html__( 'Button 2 Border Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_border_color_hover_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_border_color_hover',
			array( $this, 'cookie_settings_button_2_border_color_hover_validation' )
		);

		add_settings_field(
			'cookie_settings_button_2_font_color',
			esc_html__( 'Button 2 Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_font_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_font_color',
			array( $this, 'cookie_settings_button_2_font_color_validation' )
		);

		add_settings_field(
			'cookie_settings_button_2_font_color_hover',
			esc_html__( 'Button 2 Font Color Hover', 'daextlwcnf' ),
			array( $this, 'cookie_settings_button_2_font_color_hover_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_button_2_font_color_hover',
			array( $this, 'cookie_settings_button_2_font_color_hover_validation' )
		);

		add_settings_field(
			'cookie_settings_headings_font_color',
			esc_html__( 'Headings Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_headings_font_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_headings_font_color',
			array( $this, 'cookie_settings_headings_font_color_validation' )
		);

		add_settings_field(
			'cookie_settings_paragraphs_font_color',
			esc_html__( 'Paragraphs Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_paragraphs_font_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_paragraphs_font_color',
			array( $this, 'cookie_settings_paragraphs_font_color_validation' )
		);

		add_settings_field(
			'cookie_settings_links_font_color',
			esc_html__( 'Links Font Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_links_font_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_links_font_color',
			array( $this, 'cookie_settings_links_font_color_validation' )
		);

		add_settings_field(
			'cookie_settings_container_background_color',
			esc_html__( 'Container Background Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_background_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_background_color',
			array( $this, 'cookie_settings_container_background_color_validation' )
		);

		add_settings_field(
			'cookie_settings_container_opacity',
			esc_html__( 'Container Opacity', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_opacity_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_opacity',
			array( $this, 'cookie_settings_container_opacity_validation' )
		);

		add_settings_field(
			'cookie_settings_container_border_width',
			esc_html__( 'Container Border Width', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_border_width_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_border_width',
			array( $this, 'cookie_settings_container_border_width_validation' )
		);

		add_settings_field(
			'cookie_settings_container_border_color',
			esc_html__( 'Container Border Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_border_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_border_color',
			array( $this, 'cookie_settings_container_border_color_validation' )
		);

		add_settings_field(
			'cookie_settings_container_border_opacity',
			esc_html__( 'Container Border Opacity', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_border_opacity_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_border_opacity',
			array( $this, 'cookie_settings_container_border_opacity_validation' )
		);

		add_settings_field(
			'cookie_settings_container_drop_shadow',
			esc_html__( 'Container Drop Shadow', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_drop_shadow_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_drop_shadow',
			array( $this, 'cookie_settings_container_drop_shadow_validation' )
		);

		add_settings_field(
			'cookie_settings_container_drop_shadow_color',
			esc_html__( 'Container Drop Shadow Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_drop_shadow_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_drop_shadow_color',
			array( $this, 'cookie_settings_container_drop_shadow_color_validation' )
		);

		add_settings_field(
			'cookie_settings_container_highlight_color',
			esc_html__( 'Container Highlight Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_container_highlight_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_container_highlight_color',
			array( $this, 'cookie_settings_container_highlight_color_validation' )
		);

		add_settings_field(
			'cookie_settings_separator_color',
			esc_html__( 'Separator Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_separator_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_separator_color',
			array( $this, 'cookie_settings_separator_color_validation' )
		);

		add_settings_field(
			'cookie_settings_mask',
			esc_html__( 'Mask', 'daextlwcnf' ),
			array( $this, 'cookie_settings_mask_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_mask',
			array( $this, 'cookie_settings_mask_validation' )
		);

		add_settings_field(
			'cookie_settings_mask_color',
			esc_html__( 'Mask Color', 'daextlwcnf' ),
			array( $this, 'cookie_settings_mask_color_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_mask_color',
			array( $this, 'cookie_settings_mask_color_validation' )
		);

		add_settings_field(
			'cookie_settings_mask_opacity',
			esc_html__( 'Mask Opacity', 'daextlwcnf' ),
			array( $this, 'cookie_settings_mask_opacity_callback' ),
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_settings_section'
		);

		register_setting(
			'daextlwcnf_cookie_settings_options',
			'daextlwcnf_cookie_settings_mask_opacity',
			array( $this, 'cookie_settings_mask_opacity_validation' )
		);

        //Revisit Consent Button ---------------------------------------------------------------------------------------
        add_settings_section(
            'daextlwcnf_revisit_consent_button_settings_section',
            null,
            null,
            'daextlwcnf_revisit_consent_button_options'
        );

        add_settings_field(
            'revisit_consent_button_enable',
            esc_html__( 'Enable', 'daextlwcnf' ),
            array( $this, 'revisit_consent_button_enable_callback' ),
            'daextlwcnf_revisit_consent_button_options',
            'daextlwcnf_revisit_consent_button_settings_section'
        );

        register_setting(
            'daextlwcnf_revisit_consent_button_options',
            'daextlwcnf_revisit_consent_button_enable',
            array( $this, 'revisit_consent_button_enable_validation' )
        );

        add_settings_field(
            'revisit_consent_button_tooltip_text',
            esc_html__( 'Tooltip Text', 'daextlwcnf' ),
            array( $this, 'revisit_consent_button_tooltip_text_callback' ),
            'daextlwcnf_revisit_consent_button_options',
            'daextlwcnf_revisit_consent_button_settings_section'
        );

        register_setting(
            'daextlwcnf_revisit_consent_button_options',
            'daextlwcnf_revisit_consent_button_tooltip_text',
            array( $this, 'revisit_consent_button_tooltip_textenable_validation' )
        );

		add_settings_field(
			'revisit_consent_button_position',
			esc_html__( 'Position', 'daextlwcnf' ),
			array( $this, 'revisit_consent_button_position_callback' ),
			'daextlwcnf_revisit_consent_button_options',
			'daextlwcnf_revisit_consent_button_settings_section'
		);

		register_setting(
			'daextlwcnf_revisit_consent_button_options',
			'daextlwcnf_revisit_consent_button_position',
			array( $this, 'revisit_consent_button_position_validation' )
		);
		
		add_settings_field(
			'revisit_consent_button_background_color',
			esc_html__( 'Background Color', 'daextlwcnf' ),
			array( $this, 'revisit_consent_button_background_color_callback' ),
			'daextlwcnf_revisit_consent_button_options',
			'daextlwcnf_revisit_consent_button_settings_section'
		);

		register_setting(
			'daextlwcnf_revisit_consent_button_options',
			'daextlwcnf_revisit_consent_button_background_color',
			array( $this, 'revisit_consent_button_background_color_validation' )
		);

		add_settings_field(
			'revisit_consent_button_icon_color',
			esc_html__( 'Icon Color', 'daextlwcnf' ),
			array( $this, 'revisit_consent_button_icon_color_callback' ),
			'daextlwcnf_revisit_consent_button_options',
			'daextlwcnf_revisit_consent_button_settings_section'
		);

		register_setting(
			'daextlwcnf_revisit_consent_button_options',
			'daextlwcnf_revisit_consent_button_icon_color',
			array( $this, 'revisit_consent_button_icon_color_validation' )
		);
        
		//Section Geolocation ------------------------------------------------------------------------------------------
		add_settings_section(
			'daextlwcnf_geolocation_settings_section',
			null,
			null,
			'daextlwcnf_geolocation_options'
		);

		add_settings_field(
			'enable_geolocation',
			esc_html__( 'Geolocation', 'daextlwcnf' ),
			array( $this, 'enable_geolocation_callback' ),
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_settings_section'
		);

		register_setting(
			'daextlwcnf_geolocation_options',
			'daextlwcnf_enable_geolocation',
			array( $this, 'enable_geolocation_validation' )
		);

		add_settings_field(
			'geolocation_service',
			esc_html__( 'Geolocation Service', 'daextlwcnf' ),
			array( $this, 'geolocation_service_callback' ),
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_settings_section'
		);

		register_setting(
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_service',
			array( $this, 'geolocation_service_validation' )
		);

		add_settings_field(
			'geolocation_locale',
			esc_html__( 'Geolocation Locale', 'daextlwcnf' ),
			array( $this, 'geolocation_locale_callback' ),
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_settings_section'
		);

		register_setting(
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_locale',
			array( $this, 'geolocation_locale_validation' )
		);

		add_settings_field(
			'maxmind_license_key',
			esc_html__( 'MaxMind License Key', 'daextlwcnf' ),
			array( $this, 'maxmind_license_key_callback' ),
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_settings_section'
		);

		register_setting(
			'daextlwcnf_geolocation_options',
			'daextlwcnf_maxmind_license_key',
			array( $this, 'maxmind_license_key_validation' )
		);

		add_settings_field(
			'maxmind_database_file_path',
			esc_html__( 'MaxMind Database File Path', 'daextlwcnf' ),
			array( $this, 'maxmind_database_file_path_callback' ),
			'daextlwcnf_geolocation_options',
			'daextlwcnf_geolocation_settings_section'
		);

		register_setting(
			'daextlwcnf_geolocation_options',
			'daextlwcnf_maxmind_database_file_path',
			array( $this, 'maxmind_database_file_path_validation' )
		);

		//Section Advanced ---------------------------------------------------------------------------------------------
		add_settings_section(
			'daextlwcnf_advanced_settings_section',
			null,
			null,
			'daextlwcnf_advanced_options'
		);

		add_settings_field(
			'assets_mode',
			esc_html__( 'Assets Mode', 'daextlwcnf' ),
			array( $this, 'assets_mode_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_assets_mode',
			array( $this, 'assets_mode_validation' )
		);

		add_settings_field(
			'test_mode',
			esc_html__( 'Test Mode', 'daextlwcnf' ),
			array( $this, 'test_mode_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_test_mode',
			array( $this, 'test_mode_validation' )
		);

		add_settings_field(
			'cookie_expiration',
			esc_html__( 'Cookie Expiration', 'daextlwcnf' ),
			array( $this, 'cookie_expiration_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_cookie_expiration',
			array( $this, 'cookie_expiration_validation' )
		);

		add_settings_field(
			'reload_page',
			esc_html__( 'Reload Page', 'daextlwcnf' ),
			array( $this, 'reload_page_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_reload_page',
			array( $this, 'reload_page_validation' )
		);

		add_settings_field(
			'google_font_url',
			esc_html__( 'Google Font URL', 'daextlwcnf' ),
			array( $this, 'google_font_url_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_google_font_url',
			array( $this, 'google_font_url_validation' )
		);

		add_settings_field(
			'responsive_breakpoint',
			esc_html__( 'Responsive Breakpoint', 'daextlwcnf' ),
			array( $this, 'responsive_breakpoint_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_responsive_breakpoint',
			array( $this, 'responsive_breakpoint_validation' )
		);

		add_settings_field(
			'force_css_specificity',
			esc_html__( 'Force CSS Specificity', 'daextlwcnf' ),
			array( $this, 'force_css_specificity_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_force_css_specificity',
			array( $this, 'force_css_specificity_validation' )
		);

		add_settings_field(
			'compress_output',
			esc_html__( 'Compress Output', 'daextlwcnf' ),
			array( $this, 'compress_output_callback' ),
			'daextlwcnf_advanced_options',
			'daextlwcnf_advanced_settings_section'
		);

		register_setting(
			'daextlwcnf_advanced_options',
			'daextlwcnf_compress_output',
			array( $this, 'compress_output_validation' )
		);

	}

	//General options callbacks and validations ------------------------------------------------------------------------
	public function cookie_settings_logo_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_cookie_settings_logo_url" name="daextlwcnf_cookie_settings_logo_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_logo_url" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The URL of the logo.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_logo_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2048 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_logo_url', 'daextlwcnf_cookie_settings_logo_url',
				esc_html__( 'Please enter a valid value in the "Logo URL" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_logo_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_title_callback( $args ) {

		$html = '<input maxlength="1000" type="text" id="daextlwcnf_cookie_settings_title" name="daextlwcnf_cookie_settings_title" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_title" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The title of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_title_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_title', 'daextlwcnf_cookie_settings_title',
				esc_html__( 'Please enter a valid value in the "Title" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_title' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_description_callback( $args ) {

		$html = '<textarea id="daextlwcnf_cookie_settings_description" name="daextlwcnf_cookie_settings_description" maxlength="100000">' . esc_html( get_option( "daextlwcnf_cookie_settings_description" ) ) . '</textarea>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The description displayed in the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_description_validation( $input ) {

		$input = $this->shared->apply_custom_kses( $input );

		if ( strlen( $input ) > 100000 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_description',
				'daextlwcnf_cookie_settings_description',
				esc_html__( 'Please enter a valid value in the "Description" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_description' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_separator_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_separator_color" name="daextlwcnf_cookie_settings_separator_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_separator_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the separator color of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_separator_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_separator_color',
				'daextlwcnf_cookie_settings_separator_color',
				esc_attr__( 'Please enter a valid color in the "Separator Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_separator_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_text_callback( $args ) {

		$html = '<input maxlength="1000" type="text" id="daextlwcnf_cookie_settings_button_1_text" name="daextlwcnf_cookie_settings_button_1_text" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_text" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the text of the button 1 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_text_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_text', 'daextlwcnf_cookie_settings_button_1_text',
				esc_html__( 'Please enter a valid value in the "Button 1 Text" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_text' );
		} else {
			$output = $input;
		}

		return $output;

	}


	public function cookie_settings_button_1_action_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_settings_button_1_action" name="daextlwcnf_cookie_settings_button_1_action" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_1_action" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_1_action" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Accept Cookies', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_1_action" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Close Cookie Settings', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_1_action" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Redirect to URL', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The action performed after clicking the button 1 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_settings_button_1_action_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_settings_button_1_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_cookie_settings_button_1_url" name="daextlwcnf_cookie_settings_button_1_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_url" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The URL where the user will be redirected after clicking the button 1 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2048 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_url', 'daextlwcnf_cookie_settings_button_1_url',
				esc_html__( 'Please enter a valid value in the "Button 2 Text" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_text_callback( $args ) {

		$html = '<input maxlength="1000" type="text" id="daextlwcnf_cookie_settings_button_2_text" name="daextlwcnf_cookie_settings_button_2_text" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_text" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the text of the button 2 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_text_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_text', 'daextlwcnf_cookie_settings_button_2_text',
				esc_html__( 'Please enter a valid value in the "Button 2 Text" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_text' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_action_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_settings_button_2_action" name="daextlwcnf_cookie_settings_button_2_action" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_2_action" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_2_action" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Accept Cookies', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_2_action" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Close Cookie Settings', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_button_2_action" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Redirect to URL', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The action performed after clicking the button 2 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_settings_button_2_action_validation( $input ) {

		return intval( $input, 10 );

	}


	public function cookie_settings_button_2_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_cookie_settings_button_2_url" name="daextlwcnf_cookie_settings_button_2_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_url" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The URL where the user will be redirected after clicking the button 2 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2048 ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_url', 'daextlwcnf_cookie_settings_button_2_url',
				esc_html__( 'Please enter a valid value in the "Button 2 URL" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_main_message_text_callback( $args ) {

		$html = '<textarea id="daextlwcnf_cookie_notice_main_message_text" name="daextlwcnf_cookie_notice_main_message_text" maxlength="100000">' . esc_html( get_option( "daextlwcnf_cookie_notice_main_message_text" ) ) . '</textarea>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Enter the text of the message displayed in the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_main_message_text_validation( $input ) {

		$input = $this->shared->apply_custom_kses( $input );

		if ( strlen( $input ) > 100000 ) {
			add_settings_error( 'daextlwcnf_cookie_notice_main_message_text',
				'daextlwcnf_cookie_notice_main_message_text',
				esc_html__( 'Please enter a valid value in the "Button 1 Text" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_main_message_text' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_1_text_callback( $args ) {

		$html = '<input maxlength="1000" type="text" id="daextlwcnf_cookie_notice_button_1_text" name="daextlwcnf_cookie_notice_button_1_text" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_text" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the text of the button 1 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_text_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_text', 'daextlwcnf_cookie_notice_button_1_text',
				esc_html__( 'Please enter a valid value in the "Button 1 Text" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_text' );
		} else {
			$output = $input;
		}

		return $output;

	}


	public function cookie_notice_button_1_action_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_button_1_action" name="daextlwcnf_cookie_notice_button_1_action" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_1_action" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_1_action" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Cookie Settings', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_1_action" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Accept Cookies', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_1_action" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Close Cookie Notice', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_1_action" ) ), 4,
				false ) . ' value="4">' . esc_html__( 'Redirect to URL', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The action performed after clicking the button 1 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_button_1_action_validation( $input ) {

		return intval( $input, 10 );

	}


	public function cookie_notice_button_1_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_cookie_notice_button_1_url" name="daextlwcnf_cookie_notice_button_1_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_url" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The URL where the user will be redirected after clicking the button 1 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2048 ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_url', 'daextlwcnf_cookie_notice_button_1_url',
				esc_html__( 'Please enter a valid value in the "Button 1 URL" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_text_callback( $args ) {

		$html = '<input maxlength="1000" type="text" id="daextlwcnf_cookie_notice_button_2_text" name="daextlwcnf_cookie_notice_button_2_text" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_text" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the text of the button 2 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_text_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_text', 'daextlwcnf_cookie_notice_button_2_text',
				esc_html__( 'Please enter a valid value in the "Button 2 Text" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_text' );
		} else {
			$output = $input;
		}

		return $output;

	}


	public function cookie_notice_button_2_action_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_button_2_action" name="daextlwcnf_cookie_notice_button_2_action" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_2_action" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_2_action" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Cookie Settings', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_2_action" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Accept Cookies', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_2_action" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Close Cookie Notice', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_2_action" ) ), 4,
				false ) . ' value="4">' . esc_html__( 'Redirect to URL', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The action performed after clicking the button 2 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_button_2_action_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_notice_button_2_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_cookie_notice_button_2_url" name="daextlwcnf_cookie_notice_button_2_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_url" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The URL where the user will be redirected after clicking the button 2 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2048 ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_url', 'daextlwcnf_cookie_notice_button_2_url',
				esc_html__( 'Please enter a valid value in the "Button 2 URL" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_mask_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_mask" name="daextlwcnf_cookie_notice_mask" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_mask" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_mask" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Enabled" a mask will be generated behind the cookie notice to prevent user interactions with the website.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_mask_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function cookie_settings_mask_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_settings_mask" name="daextlwcnf_cookie_settings_mask" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_mask" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_mask" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Enabled" a mask will be generated behind the cookie settings modal window to prevent user interactions with the website.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_settings_mask_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	//Style callbacks and validations ----------------------------------------------------------------------------------
	public function cookie_notice_container_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_container_background_color" name="daextlwcnf_cookie_notice_container_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_background_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'cookie_notice_container_background_color', 'cookie_notice_container_background_color',
				esc_attr__( 'Please enter a valid color in the "Container Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'dale_cookie_notice_container_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_container_opacity_callback( $args ) {

		$html = '<input maxlength="3" type="text" id="daextlwcnf_cookie_notice_container_opacity" name="daextlwcnf_cookie_notice_container_opacity" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_opacity" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the background of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_opacity_validation( $input ) {

		return floatval( $input );

	}

	public function cookie_notice_container_border_width_callback( $args ) {

		$html = '<input maxlength="3" type="text" id="daextlwcnf_cookie_notice_container_border_width" name="daextlwcnf_cookie_notice_container_border_width" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_border_width" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the width of the border of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_border_width_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_notice_container_border_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_container_border_color" name="daextlwcnf_cookie_notice_container_border_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_border_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the border of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_border_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'cookie_notice_container_border_color', 'cookie_notice_container_border_color',
				esc_attr__( 'Please enter a valid color in the "Container Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'dale_cookie_notice_container_border_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_container_border_opacity_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_cookie_notice_container_border_opacity" name="daextlwcnf_cookie_notice_container_border_opacity" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_border_opacity" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the background of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_border_opacity_validation( $input ) {

		return floatval( $input );

	}

	public function cookie_notice_container_drop_shadow_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_container_drop_shadow" name="daextlwcnf_cookie_notice_container_drop_shadow" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_container_drop_shadow" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_container_drop_shadow" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Enabled" a drop shadow will be added to the cookie notice.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_container_drop_shadow_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_notice_container_drop_shadow_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_container_drop_shadow_color" name="daextlwcnf_cookie_notice_container_drop_shadow_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_drop_shadow_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the color of the mask displayed behind the cookie notice',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_drop_shadow_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_container_drop_shadow_color',
				'daextlwcnf_cookie_notice_container_drop_shadow_color',
				esc_attr__( 'Please enter a valid color in the "Container Drop Shadow Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_container_drop_shadow_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_container_position_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_container_position" name="daextlwcnf_cookie_notice_container_position" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_container_position" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Top', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_container_position" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Center', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_container_position" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Bottom', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The position of the cookie notice.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_container_position_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_notice_main_message_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_main_message_font_color" name="daextlwcnf_cookie_notice_main_message_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_main_message_font_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the message displayed in the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_main_message_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_main_message_font_color',
				'daextlwcnf_cookie_notice_main_message_font_color',
				esc_attr__( 'Please enter a valid color in the "Message Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_main_message_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_main_message_link_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_main_message_link_font_color" name="daextlwcnf_cookie_notice_main_message_link_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_main_message_link_font_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the links included in the message displayed in the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_main_message_link_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_main_message_link_font_color',
				'daextlwcnf_cookie_notice_main_message_link_font_color',
				esc_attr__( 'Please enter a valid color in the "Message Link Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_main_message_link_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function headings_font_family_callback( $args ) {

		$html = '<input maxlength="10000" type="text" id="daextlwcnf_headings_font_family" name="daextlwcnf_headings_font_family" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_headings_font_family" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font family of the headings.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function headings_font_family_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( ! preg_match( $this->shared->font_family_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_headings_font_family', 'daextlwcnf_headings_font_family',
				esc_html__( 'Please enter a valid value in the "Headings Font Family" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_headings_font_family' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function headings_font_weight_callback( $args ) {

		$html = '<input maxlength="4" type="text" id="daextlwcnf_headings_font_weight" name="daextlwcnf_headings_font_weight" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_headings_font_weight" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font weight of the headings.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function headings_font_weight_validation( $input ) {

		return intval( $input, 10 );

	}

	public function paragraphs_font_family_callback( $args ) {

		$html = '<input maxlength="10000" type="text" id="daextlwcnf_paragraphs_font_family" name="daextlwcnf_paragraphs_font_family" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_paragraphs_font_family" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font family of the paragraphs.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function paragraphs_font_family_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( ! preg_match( $this->shared->font_family_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_paragraphs_font_family', 'daextlwcnf_paragraphs_font_family',
				esc_html__( 'Please enter a valid value in the "Paragraphs Font Family" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_paragraphs_font_family' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function paragraphs_font_weight_callback( $args ) {

		$html = '<input maxlength="4" type="text" id="daextlwcnf_paragraphs_font_weight" name="daextlwcnf_paragraphs_font_weight" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_paragraphs_font_weight" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font weight of the paragraphs.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function paragraphs_font_weight_validation( $input ) {

		return intval( $input, 10 );

	}

	public function strong_tags_font_weight_callback( $args ) {

		$html = '<input maxlength="4" type="text" id="daextlwcnf_strong_tags_font_weight" name="daextlwcnf_strong_tags_font_weight" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_strong_tags_font_weight" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font weight of the strong tags.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function strong_tags_font_weight_validation( $input ) {

		return intval( $input, 10 );

	}


	public function buttons_font_family_callback( $args ) {

		$html = '<input maxlength="10000" type="text" id="daextlwcnf_buttons_font_family" name="daextlwcnf_buttons_font_family" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_buttons_font_family" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font family of the buttons.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function buttons_font_family_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( ! preg_match( $this->shared->font_family_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_buttons_font_family', 'daextlwcnf_buttons_font_family',
				esc_html__( 'Please enter a valid value in the "Buttons Font Family" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_buttons_font_family' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function buttons_font_weight_callback( $args ) {

		$html = '<input maxlength="4" type="text" id="daextlwcnf_buttons_font_weight" name="daextlwcnf_buttons_font_weight" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_buttons_font_weight" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font weight of the buttons.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function buttons_font_weight_validation( $input ) {

		return intval( $input, 10 );

	}

	public function buttons_border_radius_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_buttons_border_radius" name="daextlwcnf_buttons_border_radius" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_buttons_border_radius" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border radius of the buttons.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function buttons_border_radius_validation( $input ) {

		return intval( $input, 10 );

	}

	public function containers_border_radius_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_containers_border_radius" name="daextlwcnf_containers_border_radius" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_containers_border_radius" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border radius of the containers.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function containers_border_radius_validation( $input ) {

		return intval( $input, 10 );

	}


	public function cookie_notice_button_1_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_1_background_color" name="daextlwcnf_cookie_notice_button_1_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_background_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 1 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_background_color',
				'daextlwcnf_cookie_notice_button_1_background_color',
				esc_attr__( 'Please enter a valid color in the "Button 1 Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_daextlwcnf_cookie_notice_button_1_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_1_background_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_1_background_color_hover" name="daextlwcnf_cookie_notice_button_1_background_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_background_color_hover" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 1 in hover state of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_background_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_background_color_hover',
				'daextlwcnf_cookie_notice_button_1_background_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 1 Background Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_background_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_1_border_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_1_border_color" name="daextlwcnf_cookie_notice_button_1_border_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_border_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 1 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_border_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_border_color',
				'daextlwcnf_cookie_notice_button_1_border_color',
				esc_attr__( 'Please enter a valid color in the "Button 1 Border Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_border_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_1_border_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_1_border_color_hover" name="daextlwcnf_cookie_notice_button_1_border_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_border_color_hover" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 1 in hover state of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_border_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_border_color_hover',
				'daextlwcnf_cookie_notice_button_1_border_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 1 Border Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_border_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_1_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_1_font_color" name="daextlwcnf_cookie_notice_button_1_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_font_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 1 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_font_color',
				'daextlwcnf_cookie_notice_button_1_font_color',
				esc_attr__( 'Please enter a valid color in the "Button 1 Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_1_font_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_1_font_color_hover" name="daextlwcnf_cookie_notice_button_1_font_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_1_font_color_hover" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 1 in hover state of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_1_font_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_1_font_color_hover',
				'daextlwcnf_cookie_notice_button_1_font_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 1 Font Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_1_font_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_2_background_color" name="daextlwcnf_cookie_notice_button_2_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_background_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 2 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_background_color',
				'daextlwcnf_cookie_notice_button_2_background_color',
				esc_attr__( 'Please enter a valid color in the "Button 2 Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_background_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_2_background_color_hover" name="daextlwcnf_cookie_notice_button_2_background_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_background_color_hover" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 2 in hover state of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_background_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_background_color_hover',
				'daextlwcnf_cookie_notice_button_2_background_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 2 Background Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_background_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_border_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_2_border_color" name="daextlwcnf_cookie_notice_button_2_border_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_border_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 2 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_border_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_border_color',
				'daextlwcnf_cookie_notice_button_2_border_color',
				esc_attr__( 'Please enter a valid color in the "Button 2 Border Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_border_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_border_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_2_border_color_hover" name="daextlwcnf_cookie_notice_button_2_border_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_border_color_hover" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 2 in hover state of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_border_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_border_color_hover',
				'daextlwcnf_cookie_notice_button_2_border_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 2 Border Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_border_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_2_font_color" name="daextlwcnf_cookie_notice_button_2_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_font_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 2 of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_font_color',
				'daextlwcnf_cookie_notice_button_2_font_color',
				esc_attr__( 'Please enter a valid color in the "Button 2 Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_2_font_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_2_font_color_hover" name="daextlwcnf_cookie_notice_button_2_font_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_2_font_color_hover" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 2 in hover state of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_2_font_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_2_font_color_hover',
				'daextlwcnf_cookie_notice_button_2_font_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 2 Font Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_2_font_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_dismiss_action_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_button_dismiss_action" name="daextlwcnf_cookie_notice_button_dismiss_action" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_dismiss_action" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_dismiss_action" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Cookie Settings', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_dismiss_action" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Accept Cookies', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_dismiss_action" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Close Notice', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_button_dismiss_action" ) ), 4,
				false ) . ' value="4">' . esc_html__( 'Redirect to URL', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The action performed after clicking the dismiss button of the cookie notice.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_button_dismiss_action_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_notice_button_dismiss_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_cookie_notice_button_dismiss_url" name="daextlwcnf_cookie_notice_button_dismiss_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_dismiss_url" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The URL where the user will be redirected after clicking the dismiss button. Please note that this option will be used only if the "Button Dismiss Action" option is set to "Redirect to URL"',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_dismiss_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_dismiss_url',
				'daextlwcnf_cookie_notice_button_dismiss_url',
				esc_html__( 'Please enter a valid value in the "Button Dismiss URL" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_dismiss_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_button_dismiss_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_button_dismiss_color" name="daextlwcnf_cookie_notice_button_dismiss_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_button_dismiss_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the color of the dismiss button of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_button_dismiss_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_button_dismiss_color',
				'daextlwcnf_cookie_notice_button_dismiss_color',
				esc_attr__( 'Please enter a valid color in the "Button Dismiss Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_button_dismiss_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_container_width_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_cookie_notice_container_width" name="daextlwcnf_cookie_notice_container_width" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_container_width" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the width of the wrapper that includes the content of the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_container_width_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_notice_mask_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_notice_mask_color" name="daextlwcnf_cookie_notice_mask_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_mask_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the color of the mask displayed behind the cookie notice',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_mask_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_notice_mask_color', 'daextlwcnf_cookie_notice_mask_color',
				esc_attr__( 'Please enter a valid color in the "Mask Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_notice_mask_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_notice_mask_opacity_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_cookie_notice_mask_opacity" name="daextlwcnf_cookie_notice_mask_opacity" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_notice_mask_opacity" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the mask displayed behind the cookie notice.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_notice_mask_opacity_validation( $input ) {

		return floatval( $input );

	}

	public function cookie_notice_shake_effect_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_notice_shake_effect" name="daextlwcnf_cookie_notice_shake_effect" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_shake_effect" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_notice_shake_effect" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Enabled" a shake effect will be applied on the cookie notice when the user clicks on the mask.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_notice_shake_effect_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_settings_container_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_container_background_color" name="daextlwcnf_cookie_settings_container_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_background_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_container_background_color',
				'daextlwcnf_cookie_settings_container_background_color',
				esc_attr__( 'Please enter a valid color in the "Container Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_container_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_container_opacity_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_cookie_settings_container_opacity" name="daextlwcnf_cookie_settings_container_opacity" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_opacity" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the background of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_opacity_validation( $input ) {

		return floatval( $input );

	}

	public function cookie_settings_container_border_width_callback( $args ) {

		$html = '<input maxlength="3" type="text" id="daextlwcnf_cookie_settings_container_border_width" name="daextlwcnf_cookie_settings_container_border_width" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_border_width" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the width of the border of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_border_width_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_settings_container_border_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_container_border_color" name="daextlwcnf_cookie_settings_container_border_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_border_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_border_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_container_border_color',
				'daextlwcnf_cookie_settings_container_border_color',
				esc_attr__( 'Please enter a valid color in the "Container Border Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_container_border_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_container_border_opacity_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_cookie_settings_container_border_opacity" name="daextlwcnf_cookie_settings_container_border_opacity" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_border_opacity" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the cookie settings modal window container border.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_border_opacity_validation( $input ) {

		return floatval( $input );

	}

	public function cookie_settings_container_highlight_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_container_highlight_color" name="daextlwcnf_cookie_settings_container_highlight_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_highlight_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the highlight color of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_drop_shadow_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_settings_container_drop_shadow" name="daextlwcnf_cookie_settings_container_drop_shadow" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_container_drop_shadow" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_settings_container_drop_shadow" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Enabled" a drop shadow will be added to the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_settings_container_drop_shadow_validation( $input ) {

		return intval( $input, 10 );

	}

	public function cookie_settings_container_drop_shadow_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_container_drop_shadow_color" name="daextlwcnf_cookie_settings_container_drop_shadow_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_container_drop_shadow_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the drop shadow color of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_container_drop_shadow_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_container_drop_shadow_color',
				'daextlwcnf_cookie_settings_container_drop_shadow_color',
				esc_attr__( 'Please enter a valid color in the "Container Drop Shadow Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_container_drop_shadow_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_container_highlight_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_container_highlight_color',
				'daextlwcnf_cookie_settings_container_highlight_color',
				esc_attr__( 'Please enter a valid color in the "Container Highlight Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_container_highlight_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_mask_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_mask_color" name="daextlwcnf_cookie_settings_mask_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_mask_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the color of the mask displayed behind the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_mask_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_mask_color', 'daextlwcnf_cookie_settings_mask_color',
				esc_attr__( 'Please enter a valid color in the "Mask Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_mask_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_mask_opacity_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daextlwcnf_cookie_settings_mask_opacity" name="daextlwcnf_cookie_settings_mask_opacity" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_mask_opacity" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the opacity of the mask displayed behind the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_mask_opacity_validation( $input ) {

		return floatval( $input );

	}

    public function revisit_consent_button_enable_callback( $args ) {

        $html = '<select id="daextlwcnf_revisit_consent_button_enable" name="daextlwcnf_revisit_consent_button_enable" class="daext-display-none">';
        $html .= '<option ' . selected( intval( get_option( "daextlwcnf_revisit_consent_button_enable" ) ), 0,
                false ) . ' value="0">' . esc_html__( 'No', 'daextlwcnf' ) . '</option>';
        $html .= '<option ' . selected( intval( get_option( "daextlwcnf_revisit_consent_button_enable" ) ), 1,
                false ) . ' value="1">' . esc_html__( 'Yes', 'daextlwcnf' ) . '</option>';
        $html .= '</select>';
        $html .= '<div class="help-icon" title="' . esc_attr__( 'Whether to enable or not the revisit consent button. The revisit consent button allows visitors to revoke their prior preferences.',
                'daextlwcnf' ) . '"></div>';

        echo $html;

    }

    public function revisit_consent_button_enable_validation( $input ) {

        return intval( $input, 10 ) == 1 ? '1' : '0';

    }

    public function revisit_consent_button_tooltip_text_callback( $args ) {

        $html = '<input maxlength="1000" type="text" id="daextlwcnf_revisit_consent_button_tooltip_text" name="daextlwcnf_revisit_consent_button_tooltip_text" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_revisit_consent_button_tooltip_text" ),
                10 ) . '" />';
        $html .= '<div class="help-icon" title="' . esc_attr__( 'The tooltip of the revisit content button.',
                'daextlwcnf' ) . '"></div>';
        echo $html;

    }

    public function revisit_consent_button_tooltip_text_validation( $input ) {

        $input = sanitize_text_field( $input );

        if ( strlen( $input ) > 1000 ) {
            add_settings_error( 'daextlwcnf_revisit_consent_button_tooltip_text', 'daextlwcnf_revisit_consent_button_tooltip_text',
                esc_html__( 'Please enter a valid value in the "Tooltip Text" option.', 'daextlwcnf' ) );
            $output = get_option( 'daextlwcnf_revisit_consent_button_tooltip_text' );
        } else {
            $output = $input;
        }

        return $output;

    }

	public function revisit_consent_button_position_callback( $args ) {

		$html = '<select id="daextlwcnf_revisit_consent_button_position" name="daextlwcnf_revisit_consent_button_position" class="daext-display-none">';
		$html .= '<option ' . selected( get_option( "daextlwcnf_revisit_consent_button_position" ), 'left',
				false ) . ' value="left">' . esc_html__( 'Left', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( get_option( "daextlwcnf_revisit_consent_button_position" ), 'right',
				false ) . ' value="right">' . esc_html__( 'Right', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The position of the revisit consent button.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function revisit_consent_button_position_validation( $input ) {

		return $input == 'left' ? 'left' : 'right';

	}

	public function revisit_consent_button_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_revisit_consent_button_background_color" name="daextlwcnf_revisit_consent_button_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_revisit_consent_button_background_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The background color of the revisit consent button.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function revisit_consent_button_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_revisit_consent_button_background_color',
				'daextlwcnf_revisit_consent_button_background_color',
				esc_attr__( 'Please enter a valid color in the "Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_daextlwcnf_revisit_consent_button_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function revisit_consent_button_icon_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_revisit_consent_button_icon_color" name="daextlwcnf_revisit_consent_button_icon_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_revisit_consent_button_icon_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The icon color of the revisit consent button.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function revisit_consent_button_icon_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_revisit_consent_button_icon_color',
				'daextlwcnf_revisit_consent_button_icon_color',
				esc_attr__( 'Please enter a valid color in the "Icon Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_daextlwcnf_revisit_consent_button_icon_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_1_background_color" name="daextlwcnf_cookie_settings_button_1_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_background_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 1 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_background_color',
				'daextlwcnf_cookie_settings_button_1_background_color',
				esc_attr__( 'Please enter a valid color in the "Button 1 Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_background_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_1_background_color_hover" name="daextlwcnf_cookie_settings_button_1_background_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_background_color_hover" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 1 in hover status of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_background_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_background_color_hover',
				'daextlwcnf_cookie_settings_button_1_background_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 1 Background Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_background_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_border_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_1_border_color" name="daextlwcnf_cookie_settings_button_1_border_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_border_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 1 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_border_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_border_color',
				'daextlwcnf_cookie_settings_button_1_border_color',
				esc_attr__( 'Please enter a valid color in the "Button 1 Border Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_border_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_border_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_1_border_color_hover" name="daextlwcnf_cookie_settings_button_1_border_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_border_color_hover" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 1 in hover state of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_border_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_border_color_hover',
				'daextlwcnf_cookie_settings_button_1_border_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 1 Border Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_border_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_1_font_color" name="daextlwcnf_cookie_settings_button_1_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_font_color" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 1 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_font_color',
				'daextlwcnf_cookie_settings_button_1_font_color',
				esc_attr__( 'Please enter a valid color in the "Button 1 Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_1_font_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_1_font_color_hover" name="daextlwcnf_cookie_settings_button_1_font_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_1_font_color_hover" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 1 in hover state of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_1_font_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_1_font_color_hover',
				'daextlwcnf_cookie_settings_button_1_font_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 1 Font Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_1_font_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_background_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_2_background_color" name="daextlwcnf_cookie_settings_button_2_background_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_background_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 2 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_background_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_background_color',
				'daextlwcnf_cookie_settings_button_2_background_color',
				esc_attr__( 'Please enter a valid color in the "Button 2 Background Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_background_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_background_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_2_background_color_hover" name="daextlwcnf_cookie_settings_button_2_background_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_background_color_hover" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the background color of the button 2 in hover state of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_background_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_background_color_hover',
				'daextlwcnf_cookie_settings_button_2_background_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 2 Background Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_background_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_border_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_2_border_color" name="daextlwcnf_cookie_settings_button_2_border_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_border_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 2 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_border_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_border_color',
				'daextlwcnf_cookie_settings_button_2_border_color',
				esc_attr__( 'Please enter a valid color in the "Button 2 Border Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_border_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_border_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_2_border_color_hover" name="daextlwcnf_cookie_settings_button_2_border_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_border_color_hover" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the border color of the button 2 in hover state of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_border_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_border_color_hover',
				'daextlwcnf_cookie_settings_button_2_border_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 2 Border Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_border_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_headings_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_headings_font_color" name="daextlwcnf_cookie_settings_headings_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_headings_font_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the headings of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_headings_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_headings_font_color',
				'daextlwcnf_cookie_settings_headings_font_color',
				esc_attr__( 'Please enter a valid color in the "Headings Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_headings_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_paragraphs_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_paragraphs_font_color" name="daextlwcnf_cookie_settings_paragraphs_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_paragraphs_font_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the paragraphs of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_paragraphs_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_paragraphs_font_color',
				'daextlwcnf_cookie_settings_paragraphs_font_color',
				esc_attr__( 'Please enter a valid color in the "Paragraphs Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_paragraphs_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_links_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_links_font_color" name="daextlwcnf_cookie_settings_links_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_links_font_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the links of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_links_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_links_font_color',
				'daextlwcnf_cookie_settings_links_font_color',
				esc_attr__( 'Please enter a valid color in the "Links Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_links_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_font_color_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_2_font_color" name="daextlwcnf_cookie_settings_button_2_font_color" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_font_color" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 2 of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_font_color_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_font_color',
				'daextlwcnf_cookie_settings_button_2_font_color',
				esc_attr__( 'Please enter a valid color in the "Button 2 Font Color" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_font_color' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function cookie_settings_button_2_font_color_hover_callback( $args ) {

		$html = '<input class="wp-color-picker" maxlength="7" type="text" id="daextlwcnf_cookie_settings_button_2_font_color_hover" name="daextlwcnf_cookie_settings_button_2_font_color_hover" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_cookie_settings_button_2_font_color_hover" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines the font color of the button 2 in hover state of the cookie settings modal window.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function cookie_settings_button_2_font_color_hover_validation( $input ) {

		if ( ! preg_match( $this->shared->hex_rgb_regex, $input ) ) {
			add_settings_error( 'daextlwcnf_cookie_settings_button_2_font_color_hover',
				'daextlwcnf_cookie_settings_button_2_font_color_hover',
				esc_attr__( 'Please enter a valid color in the "Button 2 Font Color Hover" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_cookie_settings_button_2_font_color_hover' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function responsive_breakpoint_callback( $args ) {

		$html = '<input maxlength="6" type="text" id="daextlwcnf_responsive_breakpoint" name="daextlwcnf_responsive_breakpoint" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_responsive_breakpoint" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'When the browser viewport width goes below this value the mobile version of the cookie notice and cookie settings modal window will be used.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function responsive_breakpoint_validation( $input ) {

		return intval( $input, 10 );

	}

	//Geolocation callbacks and validations ----------------------------------------------------------------------------------
	public function enable_geolocation_callback( $args ) {

		$html = '<select id="daextlwcnf_enable_geolocation" name="daextlwcnf_enable_geolocation" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_enable_geolocation" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_enable_geolocation" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Enabled" the cookie notice will be displayed only to the users located in the countries defined with the "Geolocation Locale" option.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function enable_geolocation_validation( $input ) {

		return intval( $input, 10 );

	}

	public function geolocation_locale_callback( $args ) {

		$html               = '<select id="daextlwcnf_geolocation_locale" name="daextlwcnf_geolocation_locale[]" class="daext-display-none" multiple="multiple">';
		$array_locale       = get_option( 'daextlwcnf_locale' );
		$geolocation_locale = get_option( "daextlwcnf_geolocation_locale" );
		foreach ( $array_locale as $key => $value ) {
			$html .= '<option value="' . $value . '" ' . $this->shared->selected_array( $geolocation_locale,
					$value ) . '>' . esc_html( $key ) . '</option>';
		}
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The list of countries where the cookie notice should be displayed.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function geolocation_locale_validation( $input ) {

		if ( is_array( $input ) ) {
			return $input;
		} else {
			return '';
		}

	}

	public function geolocation_service_callback( $args ) {

		$html = '<select id="daextlwcnf_geolocation_service" name="daextlwcnf_geolocation_service" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_geolocation_service" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'HostIP.info', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_geolocation_service" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'MaxMind GeoLite2', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The geolocation service used to detect the user location.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function geolocation_service_validation( $input ) {

		return intval( $input, 10 );

	}

	public function maxmind_license_key_callback( $args ) {

		$html = '<input maxlength="100" type="text" id="daextlwcnf_maxmind_license_key" name="daextlwcnf_maxmind_license_key" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_maxmind_license_key" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The license key provided by MaxMind used by this plugin to automate the database download.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function maxmind_license_key_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 1000 ) {
			add_settings_error( 'daextlwcnf_maxmind_license_key', 'daextlwcnf_maxmind_license_key',
				esc_html__( 'Please enter a valid value in the "MaxMind License Key" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_maxmind_license_key' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function maxmind_database_file_path_callback( $args ) {

		$html = '<input maxlength="2000" type="text" id="daextlwcnf_maxmind_database_file_path" name="daextlwcnf_maxmind_database_file_path" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_maxmind_database_file_path" ),
				20 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The file path where the database provided by MaxMind should be stored.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function maxmind_database_file_path_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2000 ) {
			add_settings_error( 'daextlwcnf_maxmind_database_file_path', 'daextlwcnf_maxmind_database_file_path',
				esc_html__( 'Please enter a valid value in the "MaxMind Database File Path" option.', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_maxmind_database_file_path' );
		} else {
			$output = $input;
		}

		return $output;

	}

	//Advanced callbacks and validations -------------------------------------------------------------------------------
	public function assets_mode_callback( $args ) {

		$html = '<select id="daextlwcnf_assets_mode" name="daextlwcnf_assets_mode" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_assets_mode" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Development', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_assets_mode" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Production', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With "Development" selected the development version of the JavaScript files used by the plugin will be loaded on the front-end. With "Production" selected the minified version of the JavaScript file used by the plugin will be loaded on the front-end.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function assets_mode_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function test_mode_callback( $args ) {

		$html = '<select id="daextlwcnf_test_mode" name="daextlwcnf_test_mode" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_test_mode" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_test_mode" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With the test mode enabled the cookie notice will be applied in the front-end only if the user that is requesting the page is the website administrator.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function test_mode_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function cookie_expiration_callback( $args ) {

		$html = '<select id="daextlwcnf_cookie_expiration" name="daextlwcnf_cookie_expiration" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Unlimited', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'One Hour', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 1,
				false ) . ' value="2">' . esc_html__( 'One Day', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 2,
				false ) . ' value="3">' . esc_html__( 'One Week', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 3,
				false ) . ' value="4">' . esc_html__( 'One Month', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 4,
				false ) . ' value="5">' . esc_html__( 'Three Months', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 5,
				false ) . ' value="6">' . esc_html__( 'Six Months', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_cookie_expiration" ) ), 6,
				false ) . ' value="7">' . esc_html__( 'One Year', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The amount of time the cookies used to store the acceptance status should be stored.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function cookie_expiration_validation( $input ) {

		return intval( $input, 10 );

	}

	public function reload_page_callback( $args ) {

		$html = '<select id="daextlwcnf_reload_page" name="daextlwcnf_reload_page" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_reload_page" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_reload_page" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option enabled when the user accepts the cookies the page is reloaded.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function reload_page_validation( $input ) {

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function google_font_url_callback( $args ) {

		$html = '<input maxlength="2048" type="text" id="daextlwcnf_google_font_url" name="daextlwcnf_google_font_url" class="regular-text" value="' . esc_attr( get_option( "daextlwcnf_google_font_url" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Load one or more Google Fonts in the front-end of your website by entering the embed code in this option.',
				'daextlwcnf' ) . '"></div>';
		echo $html;

	}

	public function google_font_url_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( strlen( $input ) > 2048 ) {
			add_settings_error( 'daextlwcnf_google_font_url', 'daextlwcnf_google_font_url',
				esc_html__( 'Google Font URL', 'daextlwcnf' ) );
			$output = get_option( 'daextlwcnf_google_font_url' );
		} else {
			$output = $input;
		}

		return $output;

	}

	public function force_css_specificity_callback( $args ) {

		$html = '<select id="daextlwcnf_force_css_specificity" name="daextlwcnf_force_css_specificity" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_force_css_specificity" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_force_css_specificity" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'If you select "Yes" the CSS specificity will be forced with the "!important" rule.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function force_css_specificity_validation( $input ) {

		return intval( $input, 10 );

	}

	public function compress_output_callback( $args ) {

		$html = '<select id="daextlwcnf_compress_output" name="daextlwcnf_compress_output" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_compress_output" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daextlwcnf' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daextlwcnf_compress_output" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daextlwcnf' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This option determines if the JavaScript code used to initialize the plugin in the front-end should be compressed.',
				'daextlwcnf' ) . '"></div>';

		echo $html;

	}

	public function compress_output_validation( $input ) {

		return intval( $input, 10 );

	}

}