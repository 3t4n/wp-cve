<?php

/**
 * Post Comments widget class
 *
 * @package Skt_Addons
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Skt_Addons_Elementor\Elementor\Controls\Select2;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined('ABSPATH') || die();

class Post_Comments extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Post Comments', 'skt-addons-elementor');
	}

	public function get_custom_help_url() {
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-comment-square';
	}

	public function get_keywords() {
		return ['comments', 'post', 'response', 'form'];
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {
		$this->__post_comments_controls();
	}

	protected function __post_comments_controls() {
		$this->start_controls_section(
			'_section_post_comments',
			[
				'label' => __('Post Comments', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => esc_html__('Source', 'skt-addons-elementor'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'current_post' => esc_html__('Current Post', 'skt-addons-elementor'),
					'custom' => esc_html__('Custom', 'skt-addons-elementor'),
				],
				'default' => 'current_post',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'source_custom',
			[
				'label' => esc_html__('Search & Select', 'skt-addons-elementor'),
				'type' => Select2::TYPE,
				'multiple' => false,
				'placeholder' => 'Search Post',
				'dynamic_params' => [
					'object_type' => 'post',
					'post_type'   => 'any',
				],
				'select2options' => [
					'minimumInputLength' => 2,
				],
				'label_block' => true,
				'condition' => [
					'source_type' => 'custom',
				],
			]
		);


		$this->end_controls_section();
	}
	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__post_comments_style_controls();
		$this->__post_comments_textarea_style_controls();
		$this->__post_comments_button_style_controls();
	}


	protected function __post_comments_style_controls() {

		$this->start_controls_section(
			'label_style',
			[
				'label' => esc_html__('Comments', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'skt_pc_title_style',
			[
				'label' => __('Title', 'skt-addons-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'pc_title_color',
			[
				'label' => esc_html__('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_title_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} h2',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_responsive_control(
			'pc_title_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_title_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'skt_pc_desc_style',
			[
				'label' => __('Description', 'skt-addons-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'pc_desc_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.logged-in-as' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_desc_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} p.logged-in-as',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_responsive_control(
			'pc_desc_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} p.logged-in-as' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_desc_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} p.logged-in-as' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'skt_pc_desc_link',
			[
				'label' => __('Description Link', 'skt-addons-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs(
			'pc_desc_link_tabs'
		);

		$this->start_controls_tab(
			'pc_desc_link_normal_tab',
			[
				'label'    => __('Normal', 'skt-addons-elementor')
			]
		);

		$this->add_control(
			'pc_desc_link_color',
			[
				'label' => __('Link Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.logged-in-as a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_desc_link_typography',
				'label' => __('Link Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} p.logged-in-as a',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'pc_desc_link_hover_tab',
			[
				'label'    => __('Hover', 'skt-addons-elementor')
			]
		);

		$this->add_control(
			'pc_desc_link_hover_color',
			[
				'label' => __('Link Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.logged-in-as a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_desc_link_hover_typography',
				'label' => __('Link Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} p.logged-in-as a:hover',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);
		$this->end_controls_tab();


		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __post_comments_textarea_style_controls() {
		$this->start_controls_section(
			'textarea_style',
			[
				'label' => esc_html__('Textarea', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'skt_pc_textarea_label_style',
			[
				'label' => __('Label', 'skt-addons-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'pc_textarea_label_color',
			[
				'label' => esc_html__('Label Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_textarea_label_typography',
				'label' => __('Label Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_control(
			'skt_pc_textarea_input_style',
			[
				'label' => __('Textarea Input', 'skt-addons-elementor'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'pc_textarea_input_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_textarea_input_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pc_textarea_text_color',
			[
				'label' => esc_html__('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} textarea' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_textarea_text_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} textarea',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pc_textarea_input_border',
				'selector' => '{{WRAPPER}} textarea',
			]
		);

		$this->add_control(
			'pc_textarea_input_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __post_comments_button_style_controls() {

		$this->start_controls_section(
			'pc_button_style',
			[
				'label' => esc_html__('Submit Button', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'pc_button_hover_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} p.form-submit .submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pc_button_hover_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} p.form-submit .submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pc_button_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} p.form-submit .submit',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pc_button_border',
				'selector' => '{{WRAPPER}} p.form-submit .submit',
			]
		);

		$this->add_control(
			'pc_button_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} p.form-submit .submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'pc_submit_button_tabs'
		);

		$this->start_controls_tab(
			'pc_button_normal_tab',
			[
				'label'    => __('Normal', 'skt-addons-elementor')
			]
		);

		$this->add_control(
			'pc_button_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.form-submit .submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'pc_button_background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} p.form-submit .submit',
			]
		);
		
		$this->end_controls_tab();


		$this->start_controls_tab(
			'pc_button_hover_tab',
			[
				'label'    => __('Hover', 'skt-addons-elementor')
			]
		);

		$this->add_control(
			'pc_button_hover_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p.form-submit .submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'pc_button_hover_background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} p.form-submit .submit:hover',
			]
		);

		$this->add_control(
			'pc_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} p.form-submit .submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		if ('custom' === $settings['source_type']) {
			$post_id = (int) $settings['source_custom'];
			skt_addons_elementor()->db->switch_to_post($post_id);
		}

		if (!comments_open() && (skt_addons_elementor()->preview->is_preview_mode() || skt_addons_elementor()->editor->is_edit_mode())) :
?>
			<section id="comments" class="comments-area">
				<div id="respond" class="comment-respond">
					<h2 id="reply-title" class="comment-reply-title">Leave a Reply <small><a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;">Cancel reply</a></small></h2>
					<form action="#" method="post" id="commentform" class="comment-form" novalidate>
						<p class="logged-in-as">Logged in as admin. <a href="#">Edit your profile</a>. <a href="#">Log out?</a> <span class="required-field-message">Required fields are marked <span class="required">*</span></span></p>
						<p class="comment-form-comment"><label for="comment">Comment <span class="required">*</span></label> <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea></p>
						<p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Post Comment"> <input type="hidden" name="comment_post_ID" value="4551" id="comment_post_ID">
							<input type="hidden" name="comment_parent" id="comment_parent" value="0">
						</p><input type="hidden" id="_wp_unfiltered_html_comment_disabled" name="_wp_unfiltered_html_comment" value="3eb31e94e8">
						
					</form>
				</div>
			</section>
<?php
		else :
			comments_template();
		endif;

		if ('custom' === $settings['source_type']) {
			skt_addons_elementor()->db->restore_current_post();
		}
	}
}
