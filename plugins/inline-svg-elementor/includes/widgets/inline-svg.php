<?php
namespace ElementorInlineSvg\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Utils;
use DomDocument;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 0.0.1
 */
class Widget_Inline_Svg extends Widget_Base {

	public function get_name() {
		return 'inline-svg';
	}

	public function get_title() {
		return __( 'Inline SVG', 'elementor-inline-svg' );
	}

	public function get_icon() {
		return 'eicon-image-hotspot';
	}

	public function get_categories() {
		return [ 'general-elements' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/
	public function get_script_depends() {
		return [ 'elementor-inline-svg' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Graphic', 'elementor-inline-svg' ),
			]
		);

			$this->add_control(
				'svg',
				[
					'label' 	=> __( 'Choose file', 'elementor-inline-svg' ),
					'type' 		=> Controls_Manager::MEDIA,
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'link',
				[
					'label' 		=> __( 'Link', 'elementor-inline-svg' ),
					'description' 	=> __( 'Active only when tolltips\' Trigger is set to Hover', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::URL,
					'placeholder' 	=> 'http://your-link.com',
					'default' 		=> [
						'url' 		=> '',
					],
					'separator' 	=> 'after',
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Graphic', 'elementor-inline-svg' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'elementor-inline-svg' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left', 'elementor-inline-svg' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elementor-inline-svg' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'elementor-inline-svg' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'sizing',
				[
					'label' 		=> __( 'Sizing', 'elementor-inline-svg' ),
					'description'	=> __( 'Makes contents responsive and allows you to change maximum width and aspect ratio', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-inline-svg' ),
					'label_off' 	=> __( 'No', 'elementor-inline-svg' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'maintain_ratio',
				[
					'label' 		=> __( 'Keep aspect ratio', 'elementor-inline-svg' ),
					'description'	=> __( 'Maintains width / height ratio intact. Note: Use this feature carefully as it might distort elements inside the SVG.', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> __( 'Yes', 'elementor-inline-svg' ),
					'label_off' 	=> __( 'No', 'elementor-inline-svg' ),
					'return_value' 	=> 'yes',
					'condition'		=> [
						'sizing'	=> 'yes'
					],
					'frontend_available' => true,
				]
			);

			$this->add_responsive_control(
				'width',
				[
					'label' 		=> __( 'Width', 'elementor-inline-svg' ),
					'description'	=> __( 'Set the maximum width', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 		=> [
						'size' 		=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 1920,
							'step' 	=> 10,
						],
						'%' => [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .elementor-inline-svg' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-inline-svg > svg' => 'width: 100%; height: auto; min-width: auto;',
					],
					'condition'		=> [
						'sizing'	=> 'yes'
					],
				]
			);

			$this->add_responsive_control(
				'height',
				[
					'label' 		=> __( 'Height', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 		=> [
						'size' 		=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 1920,
							'step' 	=> 10,
						],
						'%' => [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .elementor-inline-svg > svg' => 'height: {{SIZE}}{{UNIT}};',
					],
					'condition'			 	=> [
						'sizing'		=> 'yes',
						'maintain_ratio!' 	=> 'yes'
					],
				]
			);

			$this->add_control(
				'override_colors',
				[
					'label' 		=> __( 'Override Color', 'elementor-inline-svg' ),
					'description'	=> __( 'Specify the color for all svg elements that have a fill or stroke color set.', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-inline-svg' ),
					'label_off' 	=> __( 'No', 'elementor-inline-svg' ),
					'return_value' 	=> 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'remove_inline_css',
				[
					'label' 		=> __( 'Remove Inline CSS', 'elementor-inline-svg' ),
					'description'	=> __( 'Sometimes a SVG might have inline styling preventing you to set a custom color. This removes those styles but might also remove non-color related CSS attributes.', 'elementor-inline-svg' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> '',
					'label_on' 		=> __( 'Yes', 'elementor-inline-svg' ),
					'label_off' 	=> __( 'No', 'elementor-inline-svg' ),
					'return_value' 	=> 'yes',
					'frontend_available' => true,
					'condition'	=> [
						'override_colors!' => '',
					],
				]
			);

			$this->add_control(
				'color',
				[
					'label' 	=> __( 'Color', 'elementor-inline-svg' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-inline-svg' => 'color: {{VALUE}} !important',
					],
					'condition'	=> [
						'override_colors!' => '',
					],
				]
			);

			$this->add_control(
				'color_hover',
				[
					'label' 	=> __( 'Hover Color', 'elementor-inline-svg' ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 	=> Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-inline-svg:hover' => 'color: {{VALUE}} !important',
					],
					'condition'	=> [
						'override_colors!' => '',
					]
				]
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		$tag 			= 'div';

		// Add main class to wrapper
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-inline-svg-wrapper' );
		$this->add_render_attribute( 'svg', 'class', 'elementor-inline-svg' );

		if ( ! empty( $settings['link']['url'] ) ) {

			$tag = 'a';

			$this->add_render_attribute( 'svg', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'svg', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'svg', 'rel', 'nofollow' );
			}
		}

		// Print opening tag
		printf( '<div %1$s>', $this->get_render_attribute_string( 'wrapper' ) );

		?>

			<?php if ( ! empty( $settings['svg']['url'] ) ) { ?>
			<<?php echo $tag ?> <?php echo $this->get_render_attribute_string( 'svg' ); ?>></<?php echo $tag; ?>>
			<?php } ?>

		</div>

		<?php
	}

	protected function _content_template() {}
}
