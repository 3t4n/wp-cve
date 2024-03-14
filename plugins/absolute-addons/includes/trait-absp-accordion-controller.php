<?php
/**
 * Accordion Controllers
 *
 * @package AbsoluteAddons
 * @author Name <email>
 * @version
 * @since
 * @license
 */

namespace AbsoluteAddons;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

trait Absp_Accordion_Controller {

	/**
	 * Render Accordion Icon Controls.
	 *
	 * @param array $args
	 */
	protected function render_accordion_control( $args = [] ) {

		$this->start_section( 'accordion_controller', __( 'Accordion Controller', 'absolute-addons' ) );

		$defaults = [
			'icons'        => [],
			'expend_first' => 'yes',
			'always_open'  => 'false',
			'animation'    => 'slide',
			'open_speed'   => 800,
			'close_speed'  => 800,
		];

		$args = wp_parse_args( $args, $defaults );

		if ( false !== $args['icons'] ) {
			$this->render_accordion_icon_control( $args['icons'] );
		}

		if ( false !== $args['expend_first'] ) {
			$this->add_control(
				'accordion_expend_first',
				[
					'label'       => esc_html__( 'Expend first item', 'absolute-addons' ),
					'type'        => Controls_Manager::SWITCHER,
					'description' => esc_html__( 'Keep first accordion item expended.', 'absolute-addons' ),
					'default'     => $args['expend_first'],
					'separator'   => 'before',
				]
			);
		}

		if ( false !== $args['always_open'] ) {
			// accordion_open_single
			$this->add_control(
				'accordion_always_open',
				[
					'label'       => esc_html__( 'Always open', 'absolute-addons' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'true'  => esc_html__( 'Yes', 'absolute-addons' ),
						'false' => esc_html__( 'No', 'absolute-addons' ),
					],
					'description' => esc_html__( 'Accordion items stay open when another item is opened.', 'absolute-addons' ),
					'default'     => $args['always_open'],
					'separator'   => 'before',
				]
			);
		}

		if ( false !== $args['animation'] ) {
			// accordion_animation
			$this->add_control(
				'accordion_animation',
				[
					'label'       => esc_html__( 'Animation', 'absolute-addons' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'fade'  => esc_html__( 'Fade', 'absolute-addons' ),
						'slide' => esc_html__( 'Slide', 'absolute-addons' ),
					],
					'description' => esc_html__( 'By default the accordion will slide down and up.', 'absolute-addons' ),
					'default'     => $args['animation'],
				]
			);
		}

		if ( false !== $args['open_speed'] ) {
			// accordion_open_speed
			$this->add_control(
				'accordion_open_speed',
				[
					'label'   => esc_html__( 'Animation Open Speed', 'absolute-addons' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => $args['open_speed'],
				]
			);
		}

		if ( false !== $args['close_speed'] ) {
			// accordion_close_speed
			$this->add_control(
				'accordion_close_speed',
				[
					'label'   => esc_html__( 'Animation Close Speed', 'absolute-addons' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => $args['close_speed'],
				]
			);
		}

		$this->end_controls_section();

	}

	protected function render_accordion_icon_control( $args = [] ) {

		/**
		 * @var Absp_Widget $this
		 */

		$defaults = [
			'visibility' => 'true',
			'collapsed'  => [
				'value'   => 'fas fa-plus',
				'library' => 'solid',
			],
			'active'     => [
				'value'   => 'fas fa-minus',
				'library' => 'solid',
			],
			'align'      => is_rtl() ? 'right' : 'left',
		];

		$args = wp_parse_args( $args, $defaults );

		// accordion_icon_select
		$this->add_control(
			'accordion_icon',
			[
				'label'     => esc_html__( 'Icons', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'true'  => esc_html__( 'Show', 'absolute-addons' ),
					'false' => esc_html__( 'Hide', 'absolute-addons' ),
				],
				'default'   => 'true',
				'separator' => 'before',
			]
		);

		// accordion_before_icon
		$this->add_control(
			'accordion_icon_collapsed',
			[
				'label'            => esc_html__( 'Collapsed Icon', 'absolute-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'absolute-addons',
				'default'          => $args['collapsed'],
				'recommended'      => [
					'fa-solid'   => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
						'fas fa-plus',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'skin'             => 'inline',
				'condition'        => [
					'accordion_icon' => 'true',
				],
			]
		);

		// accordion_after_icon
		$this->add_control(
			'accordion_icon_active',
			[
				'label'            => esc_html__( 'Active Icon', 'absolute-addons' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => 'absolute-addons',
				'default'          => $args['active'],
				'recommended'      => [
					'fa-solid'   => [
						'chevron-up',
						'angle-up',
						'angle-left',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
						'fas fa-minus',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin'             => 'inline',
				'condition'        => [
					'accordion_icon' => 'true',
				],
			]
		);

		$this->add_control(
			'accordion_icon_align',
			[
				'label'     => __( 'Alignment', 'absolute-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title' => __( 'Start', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'End', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => $args['align'],
				'toggle'    => false,
				'condition' => [
					'accordion_icon' => 'true',
				],
			]
		);
	}

	protected function get_accordion_attributes( $settings = [], $json = true ) {

		/**
		 * @var Absp_Widget $this
		 */

		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		$attributes = [
			'animation'  => $settings['accordion_animation'],
			'openSingle' => 'false' === $settings['accordion_always_open'], // beefup open single is opposite of always open
			'openSpeed'  => $settings['accordion_open_speed'],
			'closeSpeed' => $settings['accordion_close_speed'],
		];

		return false === $json ? $attributes : wp_json_encode( $attributes );
	}

	protected function get_icon_alignment( $settings ) {

		return $settings['accordion_icon_align'];
	}

	protected function maybe_render_icons( $settings ) {
		return ( isset( $settings['accordion_icon'] ) && $settings['accordion_icon'] );
	}

	protected function render_icon_collapsed( $settings ) {
		$this->render_icon( 'accordion_icon_collapsed', 'fas fa-plus', $settings );
	}

	protected function render_icon_active( $settings ) {
		$this->render_icon( 'accordion_icon_active', 'fas fa-minus', $settings );
	}

	protected function maybe_expend_first( $settings ) {
		return isset( $settings['accordion_expend_first'] ) && absp_string_to_bool( $settings['accordion_expend_first'] );
	}

	/**
	 * handle expend (is-open) class on repeater item.
	 *
	 * Reassign the output to the same variable of 3rd argument.
	 * E.g. $aria_expanded = $this->handle_expend_first( 'element', $settings, $aria_expanded );
	 *
	 * @param string $element
	 * @param array $settings
	 * @param bool $expend_first
	 *
	 * @return bool
	 */
	protected function handle_expend_first( $element, $settings, &$expend_first = null ) {
		if ( null === $expend_first ) {
			$expend_first = $this->maybe_expend_first( $settings );
		}

		if ( $expend_first ) {
			$this->add_render_attribute( $element, 'class', 'is-open' );
			$expend_first = false;
		} else {
			$this->remove_render_attribute( $element, 'class', 'is-open' );
		}

		return $expend_first;
	}
}

// End of file trait-absp-slider-controller.php.
