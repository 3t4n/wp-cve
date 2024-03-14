<?php
namespace Elementor;

use Thim_EL_Kit\Settings;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Thim_Ekit_Widget_Dark_Mode extends Widget_Base {

	public function get_name() {
		return 'thim-ekits-dark-mode';
	}

	public function get_title() {
		return esc_html__( 'Dark Mode Switch', 'thim-elementor-kit' );
	}


	public function get_icon() {
		return 'thim-eicon eicon-darkmode';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Dark Mode', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'icon_style', [
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Style', 'thim-elementor-kit' ),
				'default' => 'default',
				'options' => [
					'default'    	=> esc_html__( 'Default', 'thim-elementor-kit' ),
					'light-bulb' 	=> esc_html__( 'Lightbulb', 'thim-elementor-kit' ),
					'horizon' 		=> esc_html__( 'Horizon', 'thim-elementor-kit' ),
					'dark-side' 	=> esc_html__( 'Dark Side', 'thim-elementor-kit' ),
					'button-switch' => esc_html__( 'Button Switch', 'thim-elementor-kit' ),
 				],
			]
		);

		// Add note Please enable Dark Mode in Thim Elementor > Settings.
		$options = get_option( Settings::ADVANCED_OPTIONS, array() );
		if ( empty( $options['enableDarkMode'] ) ) {
			$text_note = 'Please Enable Dark Mode in here: <a href="' . admin_url( 'admin.php?page=thim_ekit_settings' ) . '" target="__blank">Thim Elementor Settings</a>.';
		} else {
			$text_note = 'Please go to <a href="' . admin_url( 'admin.php?page=thim_ekit_settings' ) . '" target="__blank">Thim Elementor Settings</a> and config color for Dark.';
		}
		$this->add_control(
			'note',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $text_note,
				'content_classes' => 'thim-ekit-note',
			]
		);

		$this->end_controls_section();
		/*
		 * style icon
		 */
		$this->register_style_icon();

	}

	protected function register_style_icon() {
		$this->start_controls_section(
			'icon_settings',
			array(
				'label' => esc_html__( 'Icon Style', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'max' => 500,
					),
				),
 				'selectors'  => array(
					'{{WRAPPER}} .dark-mode-toggle > svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .dark-mode-toggle__button-switch svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};'
				),
			)
		);
		$this->add_control(
			'width_input',
			array(
				'label'      => esc_html__( 'Width Button', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
 				'selectors'  => array(
					'{{WRAPPER}} .dark-mode-toggle__button-switch' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => [
					'icon_style' => 'button-switch',
				]
			)
		);
		$this->add_control(
			'height_input',
			array(
				'label'      => esc_html__( 'Height Button', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
				),
 				'selectors'  => array(
					'{{WRAPPER}} .dark-mode-toggle__button-switch' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => [
					'icon_style' => 'button-switch',
				]
			)
		);
		$this->add_control(
			'w',
			array(
				'label'      => esc_html__( 'Text Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
 				'selectors'  => array(
					'{{WRAPPER}} .dark-mode-toggle__button-switch > span' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => [
					'icon_style' => 'button-switch',
				]
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );
		$this->start_controls_tab(
			'tab_icon_light',
			[
				'label' => esc_html__( 'Light', 'tpebl' ),
			]
		);
		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .dark-mode-toggle  > svg, {{WRAPPER}} .dark-mode-toggle span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dark-mode-toggle__button-switch svg' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dark-mode-toggle:hover > svg, {{WRAPPER}} .dark-mode-toggle:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dark-mode-toggle__button-switch:hover svg' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000927',
				'selectors' => array(
					'{{WRAPPER}} .dark-mode-toggle__button-switch ' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .dark-mode-toggle__button-switch svg path' => 'stroke: {{VALUE}};',
				),
				'condition' => [
					'icon_style' => 'button-switch',
				]
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_icon_dark',
			[
				'label' => esc_html__( 'Dark', 'tpebl' ),
			]
		);
		$this->add_control(
			'icon_dark_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ccc',
				'selectors' => array(
					'.thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle > svg, .thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle span' => 'color: {{VALUE}};',
					'.thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle__button-switch svg' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'icon_dark_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle:hover > svg, .thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle:hover span' => 'color: {{VALUE}};',
					'.thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle__button-switch:hover svg' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'bg_dark_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000927',
				'selectors' => array(
					'.thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle__button-switch ' => 'background-color: {{VALUE}};',
					'.thim-ekit-dark-mode {{WRAPPER}} .dark-mode-toggle__button-switch svg path' => 'stroke: {{VALUE}};',
				),
				'condition' => [
					'icon_style' => 'button-switch',
				]
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'border_input_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .dark-mode-toggle__button-switch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon_style' => 'button-switch',
				]
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$options = get_option( Settings::ADVANCED_OPTIONS, array() );

		if ( empty( $options['enableDarkMode'] ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		echo '<div class="thim-dark-mode-wrapper">';
		echo '<div class="dark-mode-toggle">' . $this->render_icon_style( $settings ) . '</div>';
		echo '</div>';
	}

	protected function render_icon_style( $settings ) {
		if ( $settings['icon_style'] == 'default' ) {
			return '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" stroke-linecap="round" class="dark-mode-toggle__classic" viewBox="0 0 32 32" >
						<clipPath id="dark-mode-toggle__classic__cutout">
						  <path d="M0-5h30a1 1 0 0 0 9 13v24H0Z" />
						</clipPath>
						<g clip-path="url(#dark-mode-toggle__classic__cutout)">
						  <circle cx="16" cy="16" r="9.34" />
						  <g stroke="currentColor" stroke-width="1.5">
							<path d="M16 5.5v-4" />
							<path d="M16 30.5v-4" />
							<path d="M1.5 16h4" />
							<path d="M26.5 16h4" />
							<path d="m23.4 8.6 2.8-2.8" />
							<path d="m5.7 26.3 2.9-2.9" />
							<path d="m5.8 5.8 2.8 2.8" />
							<path d="m23.4 23.4 2.9 2.9" />
						  </g>
						</g>
					  </svg>';
		} elseif ( $settings['icon_style'] == 'light-bulb' ) {
			return '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="dark-mode-toggle__lightbulb" stroke-width="0.7" stroke="currentColor" fill="currentColor" stroke-linecap="round" viewBox="0 0 32 32">
						<path  stroke-width="0"  d="M9.4 9.9c1.8-1.8 4.1-2.7 6.6-2.7 5.1 0 9.3 4.2 9.3 9.3 0 2.3-.8 4.4-2.3 6.1-.7.8-2 2.8-2.5 4.4 0 .2-.2.4-.5.4-.2 0-.4-.2-.4-.5v-.1c.5-1.8 2-3.9 2.7-4.8 1.4-1.5 2.1-3.5 2.1-5.6 0-4.7-3.7-8.5-8.4-8.5-2.3 0-4.4.9-5.9 2.5-1.6 1.6-2.5 3.7-2.5 6 0 2.1.7 4 2.1 5.6.8.9 2.2 2.9 2.7 4.9 0 .2-.1.5-.4.5h-.1c-.2 0-.4-.1-.4-.4-.5-1.7-1.8-3.7-2.5-4.5-1.5-1.7-2.3-3.9-2.3-6.1 0-2.3 1-4.7 2.7-6.5z"/>
						<path d="M19.8 28.3h-7.6" />
						<path d="M19.8 29.5h-7.6" />
						<path d="M19.8 30.7h-7.6" />
						<path pathLength="1" class="dark-mode-toggle__lightbulb__coil" fill="none"  d="M14.6 27.1c0-3.4 0-6.8-.1-10.2-.2-1-1.1-1.7-2-1.7-1.2-.1-2.3 1-2.2 2.3.1 1 .9 1.9 2.1 2h7.2c1.1-.1 2-1 2.1-2 .1-1.2-1-2.3-2.2-2.3-.9 0-1.7.7-2 1.7 0 3.4 0 6.8-.1 10.2"/>
						<g class="dark-mode-toggle__lightbulb__rays">
						  <path pathLength="1" d="M16 6.4V1.3" />
						  <path pathLength="1" d="M26.3 15.8h5.1" />
						  <path pathLength="1" d="m22.6 9 3.7-3.6" />
						  <path pathLength="1" d="M9.4 9 5.7 5.4" />
						  <path pathLength="1" d="M5.7 15.8H.6" />
						</g>
					  </svg>';
		} elseif ( $settings['icon_style'] == 'horizon' ) {
			return '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" class="dark-mode-toggle__horizon" viewBox="0 0 32 32">
				<clipPath id="dark-mode-toggle__horizon__mask"><path d="M0 0h32v29h-32z" /></clipPath>
				<path d="M30.7 29.9H1.3c-.7 0-1.3.5-1.3 1.1 0 .6.6 1 1.3 1h29.3c.7 0 1.3-.5 1.3-1.1.1-.5-.5-1-1.2-1z" />
				<g clip-path="url(#dark-mode-toggle__horizon__mask)">
				  <path d="M16 8.8c-3.4 0-6.1 2.8-6.1 6.1s2.7 6.3 6.1 6.3 6.1-2.8 6.1-6.1-2.7-6.3-6.1-6.3zm13.3 11L26 15l3.3-4.8c.3-.5.1-1.1-.5-1.2l-5.7-1-1-5.7c-.1-.6-.8-.8-1.2-.5L16 5.1l-4.8-3.3c-.5-.4-1.2-.1-1.3.4L8.9 8 3.2 9c-.6.1-.8.8-.5 1.2L6 15l-3.3 4.8c-.3.5-.1 1.1.5 1.2l5.7 1 1 5.7c.1.6.8.8 1.2.5L16 25l4.8 3.3c.5.3 1.1.1 1.2-.5l1-5.7 5.7-1c.7-.1.9-.8.6-1.3zM16 22.5A7.6 7.6 0 0 1 8.3 15c0-4.2 3.5-7.5 7.7-7.5s7.7 3.4 7.7 7.5c0 4.2-3.4 7.5-7.7 7.5z" />
				</g>
			  </svg>';
		} elseif ( $settings['icon_style'] == 'dark-side' ) {
			return '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="dark-mode-toggle__dark-side"  fill="currentColor" viewBox="0 0 32 32">
    			<path d="M16 .5C7.4.5.5 7.4.5 16S7.4 31.5 16 31.5 31.5 24.6 31.5 16 24.6.5 16 .5zm0 28.1V3.4C23 3.4 28.6 9 28.6 16S23 28.6 16 28.6z" />
  			</svg>';
		} elseif ( $settings['icon_style'] == 'button-switch' ) {
			return '<div class="dark-mode-toggle__button-switch"><span class="dark">'. esc_html__( 'Off', 'thim-elementor-kit' ) .'</span><span class="light">'. esc_html__( 'On', 'thim-elementor-kit' ) .'</span><svg aria-hidden="true" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M14 8.52667C13.8951 9.66147 13.4692 10.7429 12.7722 11.6445C12.0751 12.5461 11.1357 13.2305 10.0638 13.6177C8.99194 14.0049 7.83199 14.0787 6.71966 13.8307C5.60734 13.5827 4.58866 13.023 3.78281 12.2172C2.97697 11.4113 2.41729 10.3927 2.16927 9.28033C1.92125 8.16801 1.99514 7.00806 2.3823 5.9362C2.76946 4.86434 3.45388 3.92491 4.35547 3.22784C5.25706 2.53076 6.33853 2.10487 7.47333 2C6.80894 2.89884 6.48923 4.0063 6.57235 5.12094C6.65547 6.23559 7.1359 7.28337 7.92626 8.07373C8.71662 8.86409 9.76441 9.34452 10.8791 9.42765C11.9937 9.51077 13.1012 9.19106 14 8.52667Z" stroke="#000927" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg></div>';
		}
	}

	protected function content_template() {

	}
}
