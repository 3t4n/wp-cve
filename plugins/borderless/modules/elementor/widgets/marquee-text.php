<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Repeater;
use Elementor\Utils;

class Marquee_Text extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-marquee-text';
	}
	
	public function get_title() {
		return 'Marquee Text';
	}
	
	public function get_icon() {
		return 'borderless-icon-marquee-text';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}

	public function get_style_depends() {
		return [ 'borderless-elementor-style' ];
	}

	public function get_script_depends() {
		return [ 'borderless-elementor-marquee-script' ];
	}
	
	protected function _register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Marquee Text - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_marquee_text_content',
			[
				'label' => esc_html__( 'Content', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$repeater = new Repeater();

			$repeater->add_control(
				'borderless_elementor_marquee_item',
				[
					'label'			=> esc_html__( 'Content', 'borderless'),
					'type'			=> Controls_Manager::TEXT,
					'label_block'	=> true,
					'dynamic'		=> [ 'active' => true ]
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_item_strings',
				[
					'type'        => Controls_Manager::REPEATER,
					'show_label'  => true,
					'fields'      =>  $repeater->get_controls(),
					'title_field' => '{{ borderless_elementor_marquee_item }}',
					'default'     => [
						['borderless_elementor_marquee_item' => esc_html__('Item #1', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #2', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #3', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #4', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #5', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #6', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #7', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #8', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #9', 'borderless')],
						['borderless_elementor_marquee_item' => esc_html__('Item #10', 'borderless')],
					],
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Marquee Text/Settings - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_marquee_text_settings',
			[
				'label' => esc_html__( 'Settings', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'borderless_elementor_marquee_text_start_visible',
				[
					'label' => __( 'Start Visible', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_text_duplicated',
				[
					'label' => __( 'Duplicated', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_text_pause_on_hover',
				[
					'label' => __( 'Pause On Hover', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'default' => 'false',
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_text_direction',
				[
					'label' => __( 'Direction', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'left'  => __( 'Left', 'borderless' ),
						'right' => __( 'Right', 'borderless' ),
					],
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_text_duration',
				[
					'label' => __( 'Duration', 'borderless' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1000,
					'max' => 100000,
					'step' => 100,
					'default' => 5000,
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_marquee_text_gap',
				[
					'label' => __( 'Gap', 'borderless' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 99999,
					'step' => 1,
					'default' => 50,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee' => 'gap: {{VALUE}}px',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_text_delay_before_start',
				[
					'label' => __( 'Delay Before Start', 'borderless' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 99999,
					'step' => 1,
					'default' => 0,
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Marquee Text - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_marquee_text_style',
			[
				'label' => esc_html__( 'Marquee Text', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_marquee_text_typography',
					'label' => __('Typography', 'borderless'),
					'scheme' => Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .borderless-elementor-marquee-text *',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_marquee_text_shadow',
					'label' => __( 'Text Shadow', 'borderless' ),
					'selector' => '{{WRAPPER}} .borderless-elementor-marquee-text *',
				]
			);

			$this->add_control(
				'borderless_elementor_marquee_text_color',
				[
					'label' => __( 'Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-marquee-text *' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'borderless_elementor_marquee_text_background',
					'label' => __( 'Background', 'borderless' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee .borderless-elementor-marquee-text-item',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_marquee_text_padding',
				[
					'label' => esc_html__( 'Padding', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee .borderless-elementor-marquee-text-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_marquee_text_margin',
				[
					'label' => esc_html__( 'Margin', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee .borderless-elementor-marquee-text-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_marquee_text_border',
					'label' => esc_html__( 'Border', 'borderless'),
					'selector' => '{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee .borderless-elementor-marquee-text-item',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_marquee_text_radius',
				[
					'label' => esc_html__( 'Border Radius', 'borderless'),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee .borderless-elementor-marquee-text-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_marquee_text_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-marquee-text .js-marquee .borderless-elementor-marquee-text-item',
				]
			);

		$this->end_controls_section();
	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();	 

		$this->add_render_attribute( 'marquee-text', 'data-direction', $settings['borderless_elementor_marquee_text_direction'] );
		$this->add_render_attribute( 'marquee-text', 'data-duration', $settings['borderless_elementor_marquee_text_duration'] );
		$this->add_render_attribute( 'marquee-text', 'data-delayBeforeStart', $settings['borderless_elementor_marquee_text_delay_before_start'] );
		$this->add_render_attribute( 'marquee-text', 'data-gap', $settings['borderless_elementor_marquee_text_gap'] );
		$this->add_render_attribute( 'marquee-text', 'data-startVisible', $settings['borderless_elementor_marquee_text_start_visible'] );
		$this->add_render_attribute( 'marquee-text', 'data-duplicated', $settings['borderless_elementor_marquee_text_duplicated'] );
		$this->add_render_attribute( 'marquee-text', 'data-pauseOnHover', $settings['borderless_elementor_marquee_text_pause_on_hover'] );

		?>

			<div class="borderless-elementor-marquee-text-widget">
				<div class="borderless-elementor-marquee-text" <?php echo $this->get_render_attribute_string( 'marquee-text' ) ?>>
					<?php foreach (  $settings['borderless_elementor_marquee_item_strings'] as $marquee_string ) { echo '<span class="borderless-elementor-marquee-text-item">'.wp_kses( ( $marquee_string['borderless_elementor_marquee_item'] ), true ).'</span>'; } ?>
				</div>
			</div>

		<?php

	}
	
	protected function _content_template() {

    }
	
	
}