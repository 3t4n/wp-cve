<?php

/**
 * This class adds the options with the related callbacks and validations.
 */
class Daexthefu_Menu_Options {

    private $shared = null;

	public function __construct( $shared ) {

		//assign an instance of the plugin info
		$this->shared = $shared;

	}

	public function register_options() {

		//Section Content ----------------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_content_settings_section',
			null,
			null,
			'daexthefu_content_options'
		);

		add_settings_field(
			'title',
			esc_html__( 'Title', 'daext-helpful' ),
			array( $this, 'title_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_title',
			'sanitize_text_field'
		);

		add_settings_field(
			'layout',
			esc_html__( 'Layout', 'daext-helpful' ),
			array( $this, 'layout_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_layout',
			'sanitize_key'
		);

		add_settings_field(
			'alignment',
			esc_html__( 'Alignment', 'daext-helpful' ),
			array( $this, 'alignment_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_alignment',
			'sanitize_key'
		);

		add_settings_field(
			'button_type',
			esc_html__( 'Rating Button Type', 'daext-helpful' ),
			array( $this, 'button_type_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_button_type',
			'sanitize_key'
		);

		add_settings_field(
			'positive_feedback_icon',
			esc_html__( 'Positive Rating Icon', 'daext-helpful' ),
			array( $this, 'positive_feedback_icon_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_positive_feedback_icon',
			'sanitize_key'
		);

		add_settings_field(
			'positive_feedback_text',
			esc_html__( 'Positive Rating Text', 'daext-helpful' ),
			array( $this, 'positive_feedback_text_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_positive_feedback_text',
			'sanitize_text_field'
		);

		add_settings_field(
			'negative_feedback_icon',
			esc_html__( 'Negative Rating Icon', 'daext-helpful' ),
			array( $this, 'negative_feedback_icon_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_negative_feedback_icon',
			'sanitize_key'
		);

		add_settings_field(
			'negative_feedback_text',
			esc_html__( 'Negative Rating Text', 'daext-helpful' ),
			array( $this, 'negative_feedback_text_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_negative_feedback_text',
			'sanitize_text_field'
		);

		add_settings_field(
			'comment_form',
			esc_html__( 'Comment Form', 'daext-helpful' ),
			array( $this, 'comment_form_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_comment_form',
			'sanitize_key'
		);

		add_settings_field(
			'comment_form_textarea_label_positive_feedback',
			esc_html__( 'Textarea Label Positive Rating', 'daext-helpful' ),
			array( $this, 'comment_form_textarea_label_positive_feedback_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_comment_form_textarea_label_positive_feedback',
			'sanitize_text_field'
		);

		add_settings_field(
			'comment_form_textarea_label_negative_feedback',
			esc_html__( 'Textarea Label Negative Rating', 'daext-helpful' ),
			array( $this, 'comment_form_textarea_label_negative_feedback_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_comment_form_textarea_label_negative_feedback',
			'sanitize_text_field'
		);

		add_settings_field(
			'comment_form_textarea_placeholder',
			esc_html__( 'Textarea Placeholder', 'daext-helpful' ),
			array( $this, 'comment_form_textarea_placeholder_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_comment_form_textarea_placeholder',
			'sanitize_text_field'
		);

		add_settings_field(
			'comment_form_button_submit_text',
			esc_html__( 'Submit Button Text', 'daext-helpful' ),
			array( $this, 'comment_form_button_submit_text_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_comment_form_button_submit_text',
			'sanitize_text_field'
		);

		add_settings_field(
			'comment_form_button_cancel_text',
			esc_html__( 'Cancel Button Text', 'daext-helpful' ),
			array( $this, 'comment_form_button_cancel_text_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_comment_form_button_cancel_text',
			'sanitize_text_field'
		);

		add_settings_field(
			'successful_submission_text',
			esc_html__( 'Successful Submission Text', 'daext-helpful' ),
			array( $this, 'successful_submission_text_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_successful_submission_text',
			'sanitize_text_field'
		);

		add_settings_field(
			'background',
			esc_html__( 'Container Background', 'daext-helpful' ),
			array( $this, 'background_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_background',
			'sanitize_key'
		);

		add_settings_field(
			'border',
			esc_html__( 'Container Border', 'daext-helpful' ),
			array( $this, 'border_callback' ),
			'daexthefu_content_options',
			'daexthefu_content_settings_section'
		);

		register_setting(
			'daexthefu_content_options',
			'daexthefu_border',
			'sanitize_key'
		);

		//Section Fonts ------------------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_fonts_settings_section',
			null,
			null,
			'daexthefu_fonts_options'
		);

		add_settings_field(
			'title_font_family',
			esc_html__( 'Title Font Family', 'daext-helpful' ),
			array( $this, 'title_font_family_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_title_font_family',
			'sanitize_text_field'
		);

		add_settings_field(
			'title_font_size',
			esc_html__( 'Title Font Size', 'daext-helpful' ),
			array( $this, 'title_font_size_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_title_font_size',
			'sanitize_key'
		);

		add_settings_field(
			'title_font_style',
			esc_html__( 'Title Font Style', 'daext-helpful' ),
			array( $this, 'title_font_style_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_title_font_style',
			'sanitize_key'
		);

		add_settings_field(
			'title_font_weight',
			esc_html__( 'Title Font Weight', 'daext-helpful' ),
			array( $this, 'title_font_weight_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_title_font_weight',
			'sanitize_key'
		);

		add_settings_field(
			'title_line_height',
			esc_html__( 'Title Line Height', 'daext-helpful' ),
			array( $this, 'title_line_height_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_title_line_height',
			'sanitize_key'
		);

		add_settings_field(
			'rating_button_font_family',
			esc_html__( 'Rating Button Font Family', 'daext-helpful' ),
			array( $this, 'rating_button_font_family_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_rating_button_font_family'
		);

		add_settings_field(
			'rating_button_font_size',
			esc_html__( 'Rating Button Font Size', 'daext-helpful' ),
			array( $this, 'rating_button_font_size_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_rating_button_font_size'
		);

		add_settings_field(
			'rating_button_font_style',
			esc_html__( 'Rating Button Font Style', 'daext-helpful' ),
			array( $this, 'rating_button_font_style_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_rating_button_font_style'
		);

		add_settings_field(
			'rating_button_font_weight',
			esc_html__( 'Rating Button Font Weight', 'daext-helpful' ),
			array( $this, 'rating_button_font_weight_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_rating_button_font_weight'
		);

		add_settings_field(
			'rating_button_line_height',
			esc_html__( 'Rating Button Line Height', 'daext-helpful' ),
			array( $this, 'rating_button_line_height_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_rating_button_line_height'
		);

		add_settings_field(
			'base_font_family',
			esc_html__( 'Base Font Family', 'daext-helpful' ),
			array( $this, 'base_font_family_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_base_font_family'
		);

		add_settings_field(
			'base_font_size',
			esc_html__( 'Base Font Size', 'daext-helpful' ),
			array( $this, 'base_font_size_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_base_font_size'
		);

		add_settings_field(
			'base_font_style',
			esc_html__( 'Base Font Style', 'daext-helpful' ),
			array( $this, 'base_font_style_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_base_font_style'
		);

		add_settings_field(
			'base_font_weight',
			esc_html__( 'Base Font Weight', 'daext-helpful' ),
			array( $this, 'base_font_weight_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_base_font_weight'
		);

		add_settings_field(
			'base_line_height',
			esc_html__( 'Base Line Height', 'daext-helpful' ),
			array( $this, 'base_line_height_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_base_line_height'
		);

		add_settings_field(
			'comment_textarea_font_family',
			esc_html__( 'Textarea Font Family', 'daext-helpful' ),
			array( $this, 'comment_textarea_font_family_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_comment_textarea_font_family'
		);

		add_settings_field(
			'comment_textarea_font_size',
			esc_html__( 'Textarea Font Size', 'daext-helpful' ),
			array( $this, 'comment_textarea_font_size_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_comment_textarea_font_size'
		);

		add_settings_field(
			'comment_textarea_font_style',
			esc_html__( 'Textarea Font Style', 'daext-helpful' ),
			array( $this, 'comment_textarea_font_style_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_comment_textarea_font_style'
		);

		add_settings_field(
			'comment_textarea_font_weight',
			esc_html__( 'Textarea Font Weight', 'daext-helpful' ),
			array( $this, 'comment_textarea_font_weight_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_comment_textarea_font_weight'
		);

		add_settings_field(
			'comment_textarea_line_height',
			esc_html__( 'Textarea Line Height', 'daext-helpful' ),
			array( $this, 'comment_textarea_line_height_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_comment_textarea_line_height'
		);

		add_settings_field(
			'button_font_family',
			esc_html__( 'Standard Button Font Family', 'daext-helpful' ),
			array( $this, 'button_font_family_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_button_font_family'
		);

		add_settings_field(
			'button_font_size',
			esc_html__( 'Standard Button Font Size', 'daext-helpful' ),
			array( $this, 'button_font_size_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_button_font_size'
		);

		add_settings_field(
			'button_font_style',
			esc_html__( 'Standard Button Font Style', 'daext-helpful' ),
			array( $this, 'button_font_style_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_button_font_style'
		);

		add_settings_field(
			'button_font_weight',
			esc_html__( 'Standard Button Font Weight', 'daext-helpful' ),
			array( $this, 'button_font_weight_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_button_font_weight'
		);

		add_settings_field(
			'button_line_height',
			esc_html__( 'Standard Button Line Height', 'daext-helpful' ),
			array( $this, 'button_line_height_callback' ),
			'daexthefu_fonts_options',
			'daexthefu_fonts_settings_section'
		);

		register_setting(
			'daexthefu_fonts_options',
			'daexthefu_button_line_height'
		);

		//Section Colors -----------------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_colors_settings_section',
			null,
			null,
			'daexthefu_colors_options'
		);

		add_settings_field(
			'title_font_color',
			esc_html__( 'Title Font Color', 'daext-helpful' ),
			array( $this, 'title_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_title_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'rating_button_font_color',
			esc_html__( 'Rating Button Font Color', 'daext-helpful' ),
			array( $this, 'rating_button_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_rating_button_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'rating_button_background_color',
			esc_html__( 'Rating Button Background Color', 'daext-helpful' ),
			array( $this, 'rating_button_background_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_rating_button_background_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'button_icon_primary_color',
			esc_html__( 'Icon Primary Color', 'daext-helpful' ),
			array( $this, 'button_icon_primary_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icon_primary_color',
			'sanitize_hex_color'
		);


		add_settings_field(
			'button_icon_secondary_color',
			esc_html__( 'Icon Secondary Color', 'daext-helpful' ),
			array( $this, 'button_icon_secondary_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icon_secondary_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'button_icon_primary_color_positive_selected',
			esc_html__( 'Icon Primary Color Positive Selected', 'daext-helpful' ),
			array( $this, 'button_icon_primary_color_positive_selected_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icon_primary_color_positive_selected',
			'sanitize_hex_color'
		);

		add_settings_field(
			'button_icon_secondary_color_positive_selected',
			esc_html__( 'Icon Secondary Color Positive Selected', 'daext-helpful' ),
			array( $this, 'button_icon_secondary_color_positive_selected_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icon_secondary_color_positive_selected',
			'sanitize_hex_color'
		);

		add_settings_field(
			'button_icon_primary_color_negative_selected',
			esc_html__( 'Icon Primary Color Negative Selected', 'daext-helpful' ),
			array( $this, 'button_icon_primary_color_negative_selected_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icon_primary_color_negative_selected',
			'sanitize_hex_color'
		);

		add_settings_field(
			'button_icon_secondary_color_negative_selected',
			esc_html__( 'Icon Secondary Color Negative Selected', 'daext-helpful' ),
			array( $this, 'button_icon_secondary_color_negative_selected_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icon_secondary_color_negative_selected',
			'sanitize_hex_color'
		);

		add_settings_field(
			'button_icons_border_color',
			esc_html__( 'Icons Border Color', 'daext-helpful' ),
			array( $this, 'button_icons_border_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_button_icons_border_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'label_font_color',
			esc_html__( 'Label Font Color', 'daext-helpful' ),
			array( $this, 'label_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_label_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'character_counter_font_color',
			esc_html__( 'Character Counter Font Color', 'daext-helpful' ),
			array( $this, 'character_counter_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_character_counter_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'comment_textarea_font_color',
			esc_html__( 'Textarea Font Color', 'daext-helpful' ),
			array( $this, 'comment_textarea_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_comment_textarea_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'comment_textarea_background_color',
			esc_html__( 'Textarea Background Color', 'daext-helpful' ),
			array( $this, 'comment_textarea_background_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_comment_textarea_background_color'
		);

		add_settings_field(
			'comment_textarea_border_color',
			esc_html__( 'Textarea Border Color', 'daext-helpful' ),
			array( $this, 'comment_textarea_border_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_comment_textarea_border_color'
		);

		add_settings_field(
			'comment_textarea_border_color_selected',
			esc_html__( 'Textarea Border Color Selected', 'daext-helpful' ),
			array( $this, 'comment_textarea_border_color_selected_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_comment_textarea_border_color_selected',
			'sanitize_hex_color'
		);

		add_settings_field(
			'primary_button_background_color',
			esc_html__( 'Primary Button Background Color', 'daext-helpful' ),
			array( $this, 'primary_button_background_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_primary_button_background_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'primary_button_border_color',
			esc_html__( 'Primary Button Border Color', 'daext-helpful' ),
			array( $this, 'primary_button_border_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_primary_button_border_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'primary_button_font_color',
			esc_html__( 'Primary Button Font Color', 'daext-helpful' ),
			array( $this, 'primary_button_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_primary_button_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'secondary_button_background_color',
			esc_html__( 'Secondary Button Background Color', 'daext-helpful' ),
			array( $this, 'secondary_button_background_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_secondary_button_background_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'secondary_button_border_color',
			esc_html__( 'Secondary Button Border Color', 'daext-helpful' ),
			array( $this, 'secondary_button_border_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_secondary_button_border_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'secondary_button_font_color',
			esc_html__( 'Secondary Button Font Color', 'daext-helpful' ),
			array( $this, 'secondary_button_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_secondary_button_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'successful_submission_font_color',
			esc_html__( 'Successful Submission Font Color', 'daext-helpful' ),
			array( $this, 'successful_submission_font_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_successful_submission_font_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'background_color',
			esc_html__( 'Container Background Color', 'daext-helpful' ),
			array( $this, 'background_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_background_color',
			'sanitize_hex_color'
		);

		add_settings_field(
			'border_color',
			esc_html__( 'Container Border Color', 'daext-helpful' ),
			array( $this, 'border_color_callback' ),
			'daexthefu_colors_options',
			'daexthefu_colors_settings_section'
		);

		register_setting(
			'daexthefu_colors_options',
			'daexthefu_border_color',
			'sanitize_hex_color'
		);

		//Spacing ------------------------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_spacing_settings_section',
			null,
			null,
			'daexthefu_spacing_options'
		);

		add_settings_field(
			'container_horizontal_padding',
			esc_html__( 'Container Horizontal Padding', 'daext-helpful' ),
			array( $this, 'container_horizontal_padding_callback' ),
			'daexthefu_spacing_options',
			'daexthefu_spacing_settings_section'
		);

		register_setting(
			'daexthefu_spacing_options',
			'daexthefu_container_horizontal_padding',
			'sanitize_key'
		);

		add_settings_field(
			'container_vertical_padding',
			esc_html__( 'Container Vertical Padding', 'daext-helpful' ),
			array( $this, 'container_vertical_padding_callback' ),
			'daexthefu_spacing_options',
			'daexthefu_spacing_settings_section'
		);

		register_setting(
			'daexthefu_spacing_options',
			'daexthefu_container_vertical_padding',
			'sanitize_key'
		);

		add_settings_field(
			'container_horizontal_margin',
			esc_html__( 'Container Horizontal Margin', 'daext-helpful' ),
			array( $this, 'container_horizontal_margin_callback' ),
			'daexthefu_spacing_options',
			'daexthefu_spacing_settings_section'
		);

		register_setting(
			'daexthefu_spacing_options',
			'daexthefu_container_horizontal_margin',
			'sanitize_key'
		);

		add_settings_field(
			'container_vertical_margin',
			esc_html__( 'Container Vertical Margin', 'daext-helpful' ),
			array( $this, 'container_vertical_margin_callback' ),
			'daexthefu_spacing_options',
			'daexthefu_spacing_settings_section'
		);

		register_setting(
			'daexthefu_spacing_options',
			'daexthefu_container_vertical_margin',
			'sanitize_key'
		);

		add_settings_field(
			'border_radius',
			esc_html__( 'Border Radius', 'daext-helpful' ),
			array( $this, 'border_radius_callback' ),
			'daexthefu_spacing_options',
			'daexthefu_spacing_settings_section'
		);

		register_setting(
			'daexthefu_spacing_options',
			'daexthefu_border_radius',
			'sanitize_key'
		);

		//Section Analysis ---------------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_analysis_settings_section',
			null,
			null,
			'daexthefu_analysis_options'
		);

		add_settings_field(
			'set_max_execution_time',
			esc_html__( 'Set Max Execution Time', 'daext-helpful' ),
			array( $this, 'set_max_execution_time_callback' ),
			'daexthefu_analysis_options',
			'daexthefu_analysis_settings_section'
		);

		register_setting(
			'daexthefu_analysis_options',
			'daexthefu_set_max_execution_time',
			'sanitize_text_field'
		);

		add_settings_field(
			'max_execution_time_value',
			esc_html__( 'Max Execution Time Value', 'daext-helpful' ),
			array( $this, 'max_execution_time_value_callback' ),
			'daexthefu_analysis_options',
			'daexthefu_analysis_settings_section'
		);

		register_setting(
			'daexthefu_analysis_options',
			'daexthefu_max_execution_time_value',
			'sanitize_text_field'
		);

		add_settings_field(
			'set_memory_limit',
			esc_html__( 'Set Memory Limit', 'daext-helpful' ),
			array( $this, 'set_memory_limit_callback' ),
			'daexthefu_analysis_options',
			'daexthefu_analysis_settings_section'
		);

		register_setting(
			'daexthefu_analysis_options',
			'daexthefu_set_memory_limit',
			'sanitize_text_field'
		);

		add_settings_field(
			'memory_limit_value',
			esc_html__( 'Memory Limit Value', 'daext-helpful' ),
			array( $this, 'memory_limit_value_callback' ),
			'daexthefu_analysis_options',
			'daexthefu_analysis_settings_section'
		);

		register_setting(
			'daexthefu_analysis_options',
			'daexthefu_memory_limit_value',
			'sanitize_text_field'
		);

		add_settings_field(
			'limit_posts_analysis',
			esc_html__( 'Limit Posts Analysis', 'daext-helpful' ),
			array( $this, 'limit_posts_analysis_callback' ),
			'daexthefu_analysis_options',
			'daexthefu_analysis_settings_section'
		);

		register_setting(
			'daexthefu_analysis_options',
			'daexthefu_limit_posts_analysis',
			'sanitize_key'
		);

		add_settings_field(
			'analysis_post_types',
			esc_html__( 'Post Types', 'daext-helpful' ),
			array( $this, 'analysis_post_types_callback' ),
			'daexthefu_analysis_options',
			'daexthefu_analysis_settings_section'
		);

		register_setting(
			'daexthefu_analysis_options',
			'daexthefu_analysis_post_types',
			array( $this, 'analysis_post_types_validation' )
		);

		//Section Capabilities -----------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_capabilities_settings_section',
			null,
			null,
			'daexthefu_capabilities_options'
		);

		add_settings_field(
			'statistics_menu_capability',
			esc_html__( 'Statistics Menu', 'daext-helpful' ),
			array( $this, 'statistics_menu_capability_callback' ),
			'daexthefu_capabilities_options',
			'daexthefu_capabilities_settings_section'
		);

		register_setting(
			'daexthefu_capabilities_options',
			'daexthefu_statistics_menu_capability',
			'sanitize_key'
		);

		add_settings_field(
			'maintenance_menu_capability',
			esc_html__( 'Maintenance Menu', 'daext-helpful' ),
			array( $this, 'maintenance_menu_capability_callback' ),
			'daexthefu_capabilities_options',
			'daexthefu_capabilities_settings_section'
		);

		register_setting(
			'daexthefu_capabilities_options',
			'daexthefu_maintenance_menu_capability',
			'sanitize_key'
		);

		//Section Advanced ---------------------------------------------------------------------------------------------
		add_settings_section(
			'daexthefu_advanced_settings_section',
			null,
			null,
			'daexthefu_advanced_options'
		);

		add_settings_field(
			'test_mode',
			esc_html__( 'Test Mode', 'daext-helpful' ),
			array( $this, 'test_mode_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_test_mode',
			'sanitize_key'
		);

		add_settings_field(
			'assets_mode',
			esc_html__( 'Assets Mode', 'daext-helpful' ),
			array( $this, 'assets_mode_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_assets_mode',
			'sanitize_key'
		);

		add_settings_field(
			'post_types',
			esc_html__( 'Post Types', 'daext-helpful' ),
			array( $this, 'post_types_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_post_types',
			array( $this, 'post_types_validation' )
		);

		add_settings_field(
			'pagination_items',
			esc_html__( 'Pagination Items', 'daext-helpful' ),
			array( $this, 'pagination_items_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_pagination_items',
			'sanitize_key'
		);

		add_settings_field(
			'google_font_url',
			esc_html__( 'Google Font URL', 'daext-helpful' ),
			array( $this, 'google_font_url_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_google_font_url',
			'sanitize_text_field'
		);

		add_settings_field(
			'textarea_characters',
			esc_html__( 'Textarea Characters', 'daext-helpful' ),
			array( $this, 'textarea_characters_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_textarea_characters',
			'sanitize_text_field'
		);

		add_settings_field(
			'unique_submission',
			esc_html__( 'Unique Submission', 'daext-helpful' ),
			array( $this, 'unique_submission_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_unique_submission',
			'sanitize_text_field'
		);

		add_settings_field(
			'cookie_expiration',
			esc_html__( 'Cookie Expiration', 'daext-helpful' ),
			array( $this, 'cookie_expiration_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_cookie_expiration',
			'sanitize_text_field'
		);

		add_settings_field(
			'character_counter',
			esc_html__( 'Character Counter', 'daext-helpful' ),
			array( $this, 'character_counter_callback' ),
			'daexthefu_advanced_options',
			'daexthefu_advanced_settings_section'
		);

		register_setting(
			'daexthefu_advanced_options',
			'daexthefu_character_counter',
			'sanitize_text_field'
		);

	}

	//Section Content --------------------------------------------------------------------------------------------------
	public function title_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_title" name="daexthefu_title" class="regular-text" value="' . esc_attr( get_option( "daexthefu_title" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The title of the form.', 'daext-helpful' ) . '"></div>';

	}

	public function layout_callback( $args ) {

		$html = '<select id="daexthefu_layout" name="daexthefu_layout" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_layout" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Side by Side', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_layout" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Stacked', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The type of layout of the form. Select "Side by Side" to display the form title and the rating buttons in the same row. Select "Stacked" to display the main title and the rating buttons in separate rows.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function alignment_callback( $args ) {

		$html = '<select id="daexthefu_alignment" name="daexthefu_alignment" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_alignment" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Left', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_alignment" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Center', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_alignment" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Right', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The alignment of the elements displayed in the form.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function button_type_callback( $args ) {

		$html = '<select id="daexthefu_button_type" name="daexthefu_button_type" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_type" ) ), '0',
				false ) . ' value="0">' . esc_html__( 'Icon', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_type" ) ), '1',
				false ) . ' value="1">' . esc_html__( 'Icon & Text', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_type" ) ), '2',
				false ) . ' value="2">' . esc_html__( 'Text', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The type of rating buttons.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function positive_feedback_icon_callback( $args ) {

		$html = '<select id="daexthefu_positive_feedback_icon" name="daexthefu_positive_feedback_icon" class="daext-display-none">';
		$html .= '<option ' . selected( get_option( "daexthefu_positive_feedback_icon" ), 'thumb-up',
				false ) . ' value="thumb-up">' . esc_html__( 'Thumb Up', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( get_option( "daexthefu_positive_feedback_icon" ), 'happy-face',
				false ) . ' value="happy-face">' . esc_html__( 'Happy Face', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The icon displayed in the positive rating button.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function positive_feedback_text_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daexthefu_positive_feedback_text" name="daexthefu_positive_feedback_text" class="regular-text" value="' . esc_attr( get_option( "daexthefu_positive_feedback_text" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The text displayed in the positive rating button.',
				'daext-helpful' ) . '"></div>';

	}

	public function negative_feedback_icon_callback( $args ) {

		$html = '<select id="daexthefu_negative_feedback_icon" name="daexthefu_negative_feedback_icon" class="daext-display-none">';
		$html .= '<option ' . selected( get_option( "daexthefu_negative_feedback_icon" ), 'thumb-down',
				false ) . ' value="thumb-down">' . esc_html__( 'Thumb Down', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( get_option( "daexthefu_negative_feedback_icon" ), 'sad-face',
				false ) . ' value="sad-face">' . esc_html__( 'Sad Face', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The icon displayed in the negative rating button.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function negative_feedback_text_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daexthefu_negative_feedback_text" name="daexthefu_negative_feedback_text" class="regular-text" value="' . esc_attr( get_option( "daexthefu_negative_feedback_text" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The text displayed in the negative rating button.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_form_callback( $args ) {

		$html = '<select id="daexthefu_comment_form" name="daexthefu_comment_form" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_form" ) ), '0',
				false ) . ' value="0">' . esc_html__( 'Always', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_form" ) ), '1',
				false ) . ' value="1">' . esc_html__( 'After Positive Rating', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_form" ) ), '2',
				false ) . ' value="2">' . esc_html__( 'After Negative rating', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_form" ) ), '3',
				false ) . ' value="3">' . esc_html__( 'Never', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select when to display the textarea used to collect a comment from the user.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function comment_form_textarea_label_positive_feedback_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_comment_form_textarea_label_positive_feedback" name="daexthefu_comment_form_textarea_label_positive_feedback" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_form_textarea_label_positive_feedback" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The textarea label displayed when a positive rating is selected.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_form_textarea_label_negative_feedback_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_comment_form_textarea_label_negative_feedback" name="daexthefu_comment_form_textarea_label_negative_feedback" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_form_textarea_label_negative_feedback" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The textarea label displayed when a negative rating is selected.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_form_textarea_placeholder_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_comment_form_textarea_placeholder" name="daexthefu_comment_form_textarea_placeholder" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_form_textarea_placeholder" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The placeholder of the textarea used to submit a comment.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_form_button_submit_text_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daexthefu_comment_form_button_submit_text" name="daexthefu_comment_form_button_submit_text" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_form_button_submit_text" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The text of the button used to submit a comment.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_form_button_cancel_text_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daexthefu_comment_form_button_cancel_text" name="daexthefu_comment_form_button_cancel_text" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_form_button_cancel_text" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The text of the button used to cancel a comment.',
				'daext-helpful' ) . '"></div>';

	}

	public function successful_submission_text_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_successful_submission_text" name="daexthefu_successful_submission_text" class="regular-text" value="' . esc_attr( get_option( "daexthefu_successful_submission_text" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'A message displayed after a successful submission of the form.',
				'daext-helpful' ) . '"></div>';

	}

	public function background_callback( $args ) {


		$html = '<select id="daexthefu_background" name="daexthefu_background" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_background" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_background" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Whether to display or not a colored background for the form container.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}


	public function border_callback( $args ) {

		$html = '<select id="daexthefu_border" name="daexthefu_border" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_border" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'None', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_border" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Horizontal', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_border" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Vertical', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_border" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Complete', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The type of border of the form container.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	//Title ------------------------------------------------------------------------------------------------------------
	public function title_font_family_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_title_font_family" name="daexthefu_title_font_family" class="regular-text" value="' . esc_attr( get_option( "daexthefu_title_font_family" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font family of the form title.',
				'daext-helpful' ) . '"></div>';

	}

	public function title_font_size_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_title_font_size" name="daexthefu_title_font_size" class="regular-text" value="' . esc_attr( get_option( "daexthefu_title_font_size" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font size of the form title.',
				'daext-helpful' ) . '"></div>';

	}

	public function title_font_style_callback( $args ) {

		$html = '<select id="daexthefu_title_font_style" name="daexthefu_title_font_style" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_style" ) ), 'normal',
				false ) . ' value="normal">' . esc_html__( 'Normal', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_style" ) ), 'italic',
				false ) . ' value="italic">' . esc_html__( 'Italic', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_style" ) ), 'oblique',
				false ) . ' value="oblique">' . esc_html__( 'Oblique', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font style of the form title.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function title_font_weight_callback( $args ) {

		$html = '<select id="daexthefu_title_font_weight" name="daexthefu_title_font_weight" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '100',
				false ) . ' value="100">' . esc_html__( '100', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '200',
				false ) . ' value="200">' . esc_html__( '200', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '300',
				false ) . ' value="300">' . esc_html__( '300', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '400',
				false ) . ' value="400">' . esc_html__( '400', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '500',
				false ) . ' value="500">' . esc_html__( '500', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '600',
				false ) . ' value="600">' . esc_html__( '600', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '700',
				false ) . ' value="700">' . esc_html__( '700', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '800',
				false ) . ' value="800">' . esc_html__( '800', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_title_font_weight" ) ), '900',
				false ) . ' value="900">' . esc_html__( '900', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font weight of the form title.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function title_line_height_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_title_line_height" name="daexthefu_title_line_height" class="regular-text" value="' . esc_attr( get_option( "daexthefu_title_line_height" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The line height of the form title.',
				'daext-helpful' ) . '"></div>';

	}

	public function rating_button_font_family_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_rating_button_font_family" name="daexthefu_rating_button_font_family" class="regular-text" value="' . esc_attr( get_option( "daexthefu_rating_button_font_family" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font family of the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function rating_button_font_size_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_rating_button_font_size" name="daexthefu_rating_button_font_size" class="regular-text" value="' . esc_attr( get_option( "daexthefu_rating_button_font_size" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font size of the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function rating_button_font_style_callback( $args ) {

		$html = '<select id="daexthefu_rating_button_font_style" name="daexthefu_rating_button_font_style" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_style" ) ), 0,
				false ) . ' value="normal">' . esc_html__( 'Normal', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_style" ) ), 1,
				false ) . ' value="italic">' . esc_html__( 'Italic', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_style" ) ), 1,
				false ) . ' value="oblique">' . esc_html__( 'Oblique', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font style of the rating buttons.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function rating_button_font_weight_callback( $args ) {

		$html = '<select id="daexthefu_rating_button_font_weight" name="daexthefu_rating_button_font_weight" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 100,
				false ) . ' value="100">' . esc_html__( '100', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 200,
				false ) . ' value="200">' . esc_html__( '200', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 300,
				false ) . ' value="300">' . esc_html__( '300', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 400,
				false ) . ' value="400">' . esc_html__( '400', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 500,
				false ) . ' value="500">' . esc_html__( '500', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 600,
				false ) . ' value="600">' . esc_html__( '600', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 700,
				false ) . ' value="700">' . esc_html__( '700', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 800,
				false ) . ' value="800">' . esc_html__( '800', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_rating_button_font_weight" ) ), 900,
				false ) . ' value="900">' . esc_html__( '900', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font weight of the rating buttons.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function rating_button_line_height_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_rating_button_line_height" name="daexthefu_rating_button_line_height" class="regular-text" value="' . esc_attr( get_option( "daexthefu_rating_button_line_height" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The line height of the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function base_font_family_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_base_font_family" name="daexthefu_base_font_family" class="regular-text" value="' . esc_attr( get_option( "daexthefu_base_font_family" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font family of the textarea label, character counter, and successful submission message.',
				'daext-helpful' ) . '"></div>';

	}

	public function base_font_size_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_base_font_size" name="daexthefu_base_font_size" class="regular-text" value="' . esc_attr( get_option( "daexthefu_base_font_size" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font family of the textarea label, character counter, and successful submission message.',
				'daext-helpful' ) . '"></div>';

	}

	public function base_font_style_callback( $args ) {

		$html = '<select id="daexthefu_base_font_style" name="daexthefu_base_font_style" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_style" ) ), 0,
				false ) . ' value="normal">' . esc_html__( 'Normal', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_style" ) ), 1,
				false ) . ' value="italic">' . esc_html__( 'Italic', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_style" ) ), 1,
				false ) . ' value="oblique">' . esc_html__( 'Oblique', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font style of the textarea label, character counter, and successful submission message.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function base_font_weight_callback( $args ) {

		$html = '<select id="daexthefu_base_font_weight" name="daexthefu_base_font_weight" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 100,
				false ) . ' value="100">' . esc_html__( '100', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 200,
				false ) . ' value="200">' . esc_html__( '200', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 300,
				false ) . ' value="300">' . esc_html__( '300', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 400,
				false ) . ' value="400">' . esc_html__( '400', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 500,
				false ) . ' value="500">' . esc_html__( '500', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 600,
				false ) . ' value="600">' . esc_html__( '600', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 700,
				false ) . ' value="700">' . esc_html__( '700', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 800,
				false ) . ' value="800">' . esc_html__( '800', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_base_font_weight" ) ), 900,
				false ) . ' value="900">' . esc_html__( '900', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font weight of the textarea label, character counter, and successful submission message.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function base_line_height_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_base_line_height" name="daexthefu_base_line_height" class="regular-text" value="' . esc_attr( get_option( "daexthefu_base_line_height" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The line height of the textarea label, character counter, and successful submission message.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_font_family_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_comment_textarea_font_family" name="daexthefu_comment_textarea_font_family" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_font_family" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font family of the textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_font_size_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_comment_textarea_font_size" name="daexthefu_comment_textarea_font_size" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_font_size" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font size of the textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_font_style_callback( $args ) {

		$html = '<select id="daexthefu_comment_textarea_font_style" name="daexthefu_comment_textarea_font_style" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_style" ) ), 0,
				false ) . ' value="normal">' . esc_html__( 'Normal', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_style" ) ), 1,
				false ) . ' value="italic">' . esc_html__( 'Italic', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_style" ) ), 1,
				false ) . ' value="oblique">' . esc_html__( 'Oblique', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font style of the textarea.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function comment_textarea_font_weight_callback( $args ) {

		$html = '<select id="daexthefu_comment_textarea_font_weight" name="daexthefu_comment_textarea_font_weight" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 100,
				false ) . ' value="100">' . esc_html__( '100', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 200,
				false ) . ' value="200">' . esc_html__( '200', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 300,
				false ) . ' value="300">' . esc_html__( '300', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 400,
				false ) . ' value="400">' . esc_html__( '400', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 500,
				false ) . ' value="500">' . esc_html__( '500', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 600,
				false ) . ' value="600">' . esc_html__( '600', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 700,
				false ) . ' value="700">' . esc_html__( '700', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 800,
				false ) . ' value="800">' . esc_html__( '800', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_comment_textarea_font_weight" ) ), 900,
				false ) . ' value="900">' . esc_html__( '900', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font weight of the textarea.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function comment_textarea_line_height_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_comment_textarea_line_height" name="daexthefu_comment_textarea_line_height" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_line_height" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The line height of the textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_font_family_callback( $args ) {

		echo '<input maxlength="10000" type="text" id="daexthefu_button_font_family" name="daexthefu_button_font_family" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_font_family" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font family of the standard buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_font_size_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_button_font_size" name="daexthefu_button_font_size" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_font_size" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font size of the standard buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_font_style_callback( $args ) {

		$html = '<select id="daexthefu_button_font_style" name="daexthefu_button_font_style" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_style" ) ), 0,
				false ) . ' value="normal">' . esc_html__( 'Normal', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_style" ) ), 1,
				false ) . ' value="italic">' . esc_html__( 'Italic', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_style" ) ), 1,
				false ) . ' value="oblique">' . esc_html__( 'Oblique', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font style of the standard buttons.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function button_font_weight_callback( $args ) {

		$html = '<select id="daexthefu_button_font_weight" name="daexthefu_button_font_weight" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 100,
				false ) . ' value="100">' . esc_html__( '100', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 200,
				false ) . ' value="200">' . esc_html__( '200', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 300,
				false ) . ' value="300">' . esc_html__( '300', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 400,
				false ) . ' value="400">' . esc_html__( '400', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 500,
				false ) . ' value="500">' . esc_html__( '500', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 600,
				false ) . ' value="600">' . esc_html__( '600', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 700,
				false ) . ' value="700">' . esc_html__( '700', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 800,
				false ) . ' value="800">' . esc_html__( '800', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_button_font_weight" ) ), 900,
				false ) . ' value="900">' . esc_html__( '900', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The font weight of the standard buttons.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function button_line_height_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_button_line_height" name="daexthefu_button_line_height" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_line_height" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The line height of the standard buttons.',
				'daext-helpful' ) . '"></div>';

	}

	//Section Colors ---------------------------------------------------------------------------------------------------
	public function title_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_title_font_color" name="daexthefu_title_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_title_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the form title.',
				'daext-helpful' ) . '"></div>';

	}

	public function rating_button_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_rating_button_font_color" name="daexthefu_rating_button_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_rating_button_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function rating_button_background_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_rating_button_background_color" name="daexthefu_rating_button_background_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_rating_button_background_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The background color of the button used to submit a positive rating.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icon_primary_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icon_primary_color" name="daexthefu_button_icon_primary_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icon_primary_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The primary color of the icons displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icon_secondary_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icon_secondary_color" name="daexthefu_button_icon_secondary_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icon_secondary_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The secondary color of the icons displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icon_primary_color_positive_selected_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icon_primary_color_positive_selected" name="daexthefu_button_icon_primary_color_positive_selected" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icon_primary_color_positive_selected" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The primary color of the selected positive icon displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icon_secondary_color_positive_selected_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icon_secondary_color_positive_selected" name="daexthefu_button_icon_secondary_color_positive_selected" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icon_secondary_color_positive_selected" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The secondary color of the selected positive icon displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icon_primary_color_negative_selected_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icon_primary_color_negative_selected" name="daexthefu_button_icon_primary_color_negative_selected" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icon_primary_color_negative_selected" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The primary color of the selected negative icon displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icon_secondary_color_negative_selected_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icon_secondary_color_negative_selected" name="daexthefu_button_icon_secondary_color_negative_selected" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icon_secondary_color_negative_selected" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The secondary color of the selected negative icon displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function button_icons_border_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_button_icons_border_color" name="daexthefu_button_icons_border_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_button_icons_border_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The border color of the icons displayed in the rating buttons.',
				'daext-helpful' ) . '"></div>';

	}

	public function label_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_label_font_color" name="daexthefu_label_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_label_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the textarea label.',
				'daext-helpful' ) . '"></div>';

	}

	public function character_counter_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_character_counter_font_color" name="daexthefu_character_counter_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_character_counter_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the character counter displayed above the textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_comment_textarea_font_color" name="daexthefu_comment_textarea_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the textarea label.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_background_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_comment_textarea_background_color" name="daexthefu_comment_textarea_background_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_background_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The background color of the textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_border_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_comment_textarea_border_color" name="daexthefu_comment_textarea_border_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_border_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The border color of the textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function comment_textarea_border_color_selected_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_comment_textarea_border_color_selected" name="daexthefu_comment_textarea_border_color_selected" class="regular-text" value="' . esc_attr( get_option( "daexthefu_comment_textarea_border_color_selected" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The color used for the border of a selected textarea.',
				'daext-helpful' ) . '"></div>';

	}

	public function primary_button_background_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_primary_button_background_color" name="daexthefu_primary_button_background_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_primary_button_background_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The background color of the primary standard button.',
				'daext-helpful' ) . '"></div>';

	}

	public function primary_button_border_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_primary_button_border_color" name="daexthefu_primary_button_border_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_primary_button_border_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The border color of the primary standard button.',
				'daext-helpful' ) . '"></div>';

	}

	public function primary_button_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_primary_button_font_color" name="daexthefu_primary_button_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_primary_button_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the primary standard button.',
				'daext-helpful' ) . '"></div>';

	}

	public function secondary_button_background_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_secondary_button_background_color" name="daexthefu_secondary_button_background_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_secondary_button_background_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The background color of the secondary standard button.',
				'daext-helpful' ) . '"></div>';

	}

	public function secondary_button_border_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_secondary_button_border_color" name="daexthefu_secondary_button_border_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_secondary_button_border_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The border color of the secondary standard button.',
				'daext-helpful' ) . '"></div>';

	}

	public function secondary_button_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_secondary_button_font_color" name="daexthefu_secondary_button_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_secondary_button_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the secondary standard button.',
				'daext-helpful' ) . '"></div>';

	}

	public function successful_submission_font_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_successful_submission_font_color" name="daexthefu_successful_submission_font_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_successful_submission_font_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The font color of the message displayed after a successful submission of the form.',
				'daext-helpful' ) . '"></div>';

	}

	public function background_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_background_color" name="daexthefu_background_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_background_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The background color of the form container.',
				'daext-helpful' ) . '"></div>';

	}

	public function border_color_callback( $args ) {

		echo '<input class="wp-color-picker" maxlength="7" type="text" id="daexthefu_border_color" name="daexthefu_border_color" class="regular-text" value="' . esc_attr( get_option( "daexthefu_border_color" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The border color of the form container.',
				'daext-helpful' ) . '"></div>';

	}

	//Section Spacing --------------------------------------------------------------------------------------------------

	public function container_horizontal_padding_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_container_horizontal_padding" name="daexthefu_container_horizontal_padding" class="regular-text" value="' . esc_attr( get_option( "daexthefu_container_horizontal_padding" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The horizontal padding of the form container.',
				'daext-helpful' ) . '"></div>';

	}

	public function container_vertical_padding_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_container_vertical_padding" name="daexthefu_container_vertical_padding" class="regular-text" value="' . esc_attr( get_option( "daexthefu_container_vertical_padding" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The vertical padding of the form container.',
				'daext-helpful' ) . '"></div>';

	}

	public function container_horizontal_margin_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_container_horizontal_margin" name="daexthefu_container_horizontal_margin" class="regular-text" value="' . esc_attr( get_option( "daexthefu_container_horizontal_margin" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The horizontal margin of the form container.',
				'daext-helpful' ) . '"></div>';

	}

	public function container_vertical_margin_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_container_vertical_margin" name="daexthefu_container_vertical_margin" class="regular-text" value="' . esc_attr( get_option( "daexthefu_container_vertical_margin" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The vertical margin of the form container.',
				'daext-helpful' ) . '"></div>';

	}

	public function border_radius_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_border_radius" name="daexthefu_border_radius" class="regular-text" value="' . esc_attr( get_option( "daexthefu_border_radius" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The border radius used for buttons, input fields, and containers.',
				'daext-helpful' ) . '"></div>';

	}

	//Section Analysis -------------------------------------------------------------------------------------------------
	public function set_max_execution_time_callback( $args ) {

		$html = '<select id="daexthefu_set_max_execution_time" name="daexthefu_set_max_execution_time" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_set_max_execution_time" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_set_max_execution_time" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to enable your custom "Max Execution Time Value" on long running scripts.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function set_max_execution_time_validation( $input ) {

		$input = sanitize_text_field( $input );

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function max_execution_time_value_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daexthefu_max_execution_time_value" name="daexthefu_max_execution_time_value" class="regular-text" value="' . intval( get_option( "daexthefu_max_execution_time_value" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This value determines the maximum number of seconds allowed to execute long running scripts.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function max_execution_time_value_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or intval( $input,
				10 ) < 1 or intval( $input, 10 ) > 1000000 ) {
			add_settings_error( 'daexthefu_max_execution_time_value', 'daexthefu_max_execution_time_value',
				esc_html__( 'Please enter a number from 1 to 1000000 in the "Max Execution Time Value" option.',
					'daext-helpful' ) );
			$output = get_option( 'daexthefu_max_execution_time_value' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function set_memory_limit_callback( $args ) {

		$html = '<select id="daexthefu_set_memory_limit" name="daexthefu_set_memory_limit" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_set_memory_limit" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_set_memory_limit" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Select "Yes" to enable your custom "Memory Limit Value" on long running scripts.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function set_memory_limit_validation( $input ) {

		$input = sanitize_text_field( $input );

		return intval( $input, 10 ) == 1 ? '1' : '0';

	}

	public function memory_limit_value_callback( $args ) {

		$html = '<input maxlength="7" type="text" id="daexthefu_memory_limit_value" name="daexthefu_memory_limit_value" class="regular-text" value="' . intval( get_option( "daexthefu_memory_limit_value" ),
				10 ) . '" />';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This value determines the PHP memory limit in megabytes allowed to execute long running scripts.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function memory_limit_value_validation( $input ) {

		$input = sanitize_text_field( $input );

		if ( ! preg_match( $this->shared->regex_number_ten_digits, $input ) or intval( $input,
				10 ) < 1 or intval( $input, 10 ) > 1000000 ) {
			add_settings_error( 'daexthefu_memory_limit_value', 'daexthefu_memory_limit_value',
				esc_html__( 'Please enter a number from 1 to 1000000 in the "Memory Limit Value" option.',
					'daext-helpful' ) );
			$output = get_option( 'daexthefu_memory_limit_value' );
		} else {
			$output = $input;
		}

		return intval( $output, 10 );

	}

	public function limit_posts_analysis_callback( $args ) {

		echo '<input maxlength="7" maxlength="100" type="text" id="daexthefu_limit_posts_analysis" name="daexthefu_limit_posts_analysis" class="regular-text" value="' . esc_attr( get_option( "daexthefu_limit_posts_analysis" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'With this options you can determine the maximum number of posts analyzed to get information about the feedback. If you select for example "10000", the analysis performed by the plugin will use your latest "10000" posts.',
				'daext-helpful' ) . '"></div>';

	}

	public function analysis_post_types_callback( $args ) {

		$analysis_post_types_a = get_option( "daexthefu_analysis_post_types" );

		$available_analysis_post_types_a = get_post_types( array(
			'public'  => true,
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_analysis_post_types_a = array_diff( $available_analysis_post_types_a, array( 'attachment' ) );

		$html = '<select id="daexthefu_analysis_post_types" name="daexthefu_analysis_post_types[]" class="daext-display-none" multiple>';

		foreach ( $available_analysis_post_types_a as $single_post_type ) {
			if ( is_array( $analysis_post_types_a ) and in_array( $single_post_type, $analysis_post_types_a ) ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object( $single_post_type );
			$html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html( $post_type_obj->label ) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option you are able to determine the post types listed in the "Statistics" menu.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function analysis_post_types_validation( $input ) {

		if ( ! is_array( $input ) ) {
			add_settings_error( 'daexthefu_analysis_post_types', 'daexthefu_analysis_post_types',
				esc_html__( 'Please at least one post type in the "Post Types" option.', 'daext-helpful' ) );
			$output = get_option( 'daexthefu_analysis_post_types' );

		} else {
			$output = $input;
		}

		return $output;

	}

	//Section Capabilities ----------------------------------------------------------
	public function statistics_menu_capability_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daexthefu_statistics_menu_capability" name="daexthefu_statistics_menu_capability" class="regular-text" value="' . esc_attr( get_option( "daexthefu_statistics_menu_capability" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The capability required to get access on the "Statistics" menu.',
				'daext-helpful' ) . '"></div>';

	}

	public function maintenance_menu_capability_callback( $args ) {

		echo '<input maxlength="100" type="text" id="daexthefu_maintenance_menu_capability" name="daexthefu_maintenance_menu_capability" class="regular-text" value="' . esc_attr( get_option( "daexthefu_maintenance_menu_capability" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The capability required to get access on the "Maintenance" menu.',
				'daext-helpful' ) . '"></div>';

	}

	//Section Advanced ----------------------------------------------------------
	public function test_mode_callback( $args ) {

		$html = '<select id="daexthefu_test_mode" name="daexthefu_test_mode" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_test_mode" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_test_mode" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Yes', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With the test mode enabled, the feedback form will be applied in the front-end only if the user that is requesting the page is the website administrator.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function assets_mode_callback( $args ) {

		$html = '<select id="daexthefu_assets_mode" name="daexthefu_assets_mode" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_assets_mode" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Development', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_assets_mode" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Production', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With "Development" selected the development version of the JavaScript files used by the plugin will be loaded on the front-end. With "Production" selected the minified version of the JavaScript file used by the plugin will be loaded on the front-end.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function post_types_callback( $args ) {

		$post_types_a = get_option( "daexthefu_post_types" );

		$available_post_types_a = get_post_types( array(
			'public'  => true,
			'show_ui' => true
		) );

		//Remove the "attachment" post type
		$available_post_types_a = array_diff( $available_post_types_a, array( 'attachment' ) );

		$html = '<select id="daexthefu_post_types" name="daexthefu_post_types[]" class="daext-display-none" multiple>';

		foreach ( $available_post_types_a as $single_post_type ) {
			if ( is_array( $post_types_a ) and in_array( $single_post_type, $post_types_a ) ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$post_type_obj = get_post_type_object( $single_post_type );
			$html          .= '<option value="' . $single_post_type . '" ' . $selected . '>' . esc_html( $post_type_obj->label ) . '</option>';
		}

		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'With this option, you are able to determine to which post types the form will be applied.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function post_types_validation( $input ) {

		if (is_array($input)) {
			return $input;
		} else {
			return '';
		}

	}

	public function pagination_items_callback( $args ) {

		$html = '<select id="daexthefu_pagination_items" name="daexthefu_pagination_items" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 10,
				false ) . ' value="10">' . esc_html__( '10', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 20,
				false ) . ' value="20">' . esc_html__( '20', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 30,
				false ) . ' value="30">' . esc_html__( '30', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 40,
				false ) . ' value="40">' . esc_html__( '40', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 50,
				false ) . ' value="50">' . esc_html__( '50', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 60,
				false ) . ' value="60">' . esc_html__( '60', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 70,
				false ) . ' value="70">' . esc_html__( '70', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 80,
				false ) . ' value="80">' . esc_html__( '80', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 90,
				false ) . ' value="90">' . esc_html__( '90', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_pagination_items" ) ), 100,
				false ) . ' value="100">' . esc_html__( '100', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'This options determines the number of items per page displayed in the "Statistics" menu.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function google_font_url_callback( $args ) {

		echo '<input maxlength="2048" type="text" id="daexthefu_google_font_url" name="daexthefu_google_font_url" class="regular-text" value="' . esc_attr( get_option( "daexthefu_google_font_url" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'Load one or more Google Fonts in the front-end of your website by entering the embed code URL in this option.',
				'daext-helpful' ) . '"></div>';

	}

	public function textarea_characters_callback( $args ) {

		echo '<input maxlength="7" type="text" id="daexthefu_textarea_characters" name="daexthefu_textarea_characters" class="regular-text" value="' . esc_attr( get_option( "daexthefu_textarea_characters" ) ) . '" />';
		echo '<div class="help-icon" title="' . esc_attr__( 'The maximum number of characters allowed in the textarea used to submit a comment.',
				'daext-helpful' ) . '"></div>';

	}

	public function unique_submission_callback( $args ) {

		$html = '<select id="daexthefu_unique_submission" name="daexthefu_unique_submission" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_unique_submission" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'No check', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_unique_submission" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Check cookies', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_unique_submission" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'Check IP', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_unique_submission" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'Check cookies and IP',
				'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Use cookies and IP address to prevent multiple form submissions.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function cookie_expiration_callback( $args ) {

		$html = '<select id="daexthefu_cookie_expiration" name="daexthefu_cookie_expiration" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Unlimited', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'One Hour', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 2,
				false ) . ' value="2">' . esc_html__( 'One Day', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 3,
				false ) . ' value="3">' . esc_html__( 'One Week', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 4,
				false ) . ' value="4">' . esc_html__( 'One Month', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 5,
				false ) . ' value="5">' . esc_html__( 'Three Months', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 6,
				false ) . ' value="6">' . esc_html__( 'Six Months', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_cookie_expiration" ) ), 7,
				false ) . ' value="7">' . esc_html__( 'One Year', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'The amount of time the cookies used to prevent multiple form submissions should be stored.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

	public function character_counter_callback( $args ) {

		$html = '<select id="daexthefu_character_counter" name="daexthefu_character_counter" class="daext-display-none">';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_character_counter" ) ), 0,
				false ) . ' value="0">' . esc_html__( 'Disabled', 'daext-helpful' ) . '</option>';
		$html .= '<option ' . selected( intval( get_option( "daexthefu_character_counter" ) ), 1,
				false ) . ' value="1">' . esc_html__( 'Enabled', 'daext-helpful' ) . '</option>';
		$html .= '</select>';
		$html .= '<div class="help-icon" title="' . esc_attr__( 'Whether to enable or disable the character counter.',
				'daext-helpful' ) . '"></div>';

		echo $html;

	}

}