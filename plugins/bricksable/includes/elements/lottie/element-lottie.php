<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Lottie extends \Bricks\Element {
	public $category = 'bricksable';
	public $name     = 'ba-lottie';
	public $icon     = 'ti-wand';
	public $scripts  = array( 'bricksableLottie' );

	public function get_label() {
		return esc_html__( 'Lottie', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['lottie_file']     = array(
			'title' => esc_html__( 'Lottie File', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['link']            = array(
			'title' => esc_html__( 'Link', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['lottie_settings'] = array(
			'title' => esc_html__( 'Lottie Settings', 'bricksable' ),
			'tab'   => 'content',
		);

	}
	public function set_controls() {
		// File.
		$this->controls['source_separator'] = array(
			'tab'   => 'content',
			'group' => 'lottie_file',
			'label' => esc_html__( 'Source', 'bricksable' ),
			'type'  => 'separator',
		);
		$this->controls['source_type']      = array(
			'tab'         => 'content',
			'group'       => 'lottie_file',
			'label'       => esc_html__( 'Source Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'url'   => esc_html__( 'External URL', 'bricksable' ),
				'media' => esc_html__( 'Upload JSON File', 'bricksable' ),
			),
			'default'     => 'url',
			'placeholder' => esc_html__( 'External URL', 'bricksable' ),
		);
		$this->controls['url']              = array(
			'tab'         => 'content',
			'group'       => 'lottie_file',
			'label'       => esc_html__( 'Lottie URL', 'bricksable' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'https://assets5.lottiefiles.com/private_files/lf30_hhvn1H.json', 'bricksable' ),
			'required'    => array( 'source_type', '=', 'url' ),
		);
		$this->controls['lottie_json_file'] = array(
			'tab'         => 'content',
			'group'       => 'lottie_file',
			'label'       => esc_html__( 'Lottie JSON Media', 'bricksable' ),
			'type'        => 'file',
			'pasteStyles' => false,
			'required'    => array( 'source_type', '=', 'media' ),
		);
		// Link To.
		$this->controls['linkToSeparator'] = array(
			'tab'   => 'content',
			'group' => 'link',
			'type'  => 'separator',
			'label' => esc_html__( 'Link To', 'bricksable' ),
		);
		$this->controls['link']            = array(
			'tab'         => 'content',
			'group'       => 'link',
			'type'        => 'select',
			'options'     => array(
				'url' => esc_html__( 'Other (URL)', 'bricksable' ),
			),
			'placeholder' => esc_html__( 'None', 'bricksable' ),
		);
		$this->controls['link_url']        = array(
			'tab'      => 'content',
			'group'    => 'link',
			'label'    => esc_html__( 'Link type', 'bricksable' ),
			'type'     => 'link',
			'required' => array( 'link', '=', 'url' ),
		);
		// Settings.
		$this->controls['trigger']         = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Trigger', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'viewport' => esc_html__( 'Viewport', 'bricksable' ),
				'click'    => esc_html__( 'On Click', 'bricksable' ),
				'hover'    => esc_html__( 'On Hover', 'bricksable' ),
				'scroll'   => esc_html__( 'Scroll', 'bricksable' ),
				'none'     => esc_html__( 'None', 'bricksable' ),
			),
			'default'     => 'viewport',
			'placeholder' => esc_html__( 'Viewport', 'bricksable' ),
		);
		$this->controls['mouseout_action'] = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Mouseout Action', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'no_action' => esc_html__( 'No Action', 'bricksable' ),
				'stop'      => esc_html__( 'Stop', 'bricksable' ),
				'pause'     => esc_html__( 'Pause', 'bricksable' ),
				'reverse'   => esc_html__( 'Reverse', 'bricksable' ),
			),
			'default'     => 'no_action',
			'placeholder' => esc_html__( 'No Action', 'bricksable' ),
			'required'    => array( 'trigger', '=', 'hover' ),
		);
		$this->controls['scroll_trigger']  = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'On Page Scroll Animation', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'document' => esc_html__( 'Body', 'bricksable' ),
				'element'  => esc_html__( 'Relative to Another element', 'bricksable' ),
				// 'section' => esc_html__( 'Section', 'bricksable' ), .
			),
			'default'     => 'document',
			'placeholder' => esc_html__( 'Body', 'bricksable' ),
			'required'    => array( 'trigger', '=', 'scroll' ),
		);
		$this->controls['element_selector'] = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Element Selector', 'bricksable' ),
			'type'        => 'text',
			'placeholder' => esc_html__( '.class', 'bricksable' ),
			'required'    => array( 'scroll_trigger', '=', 'element' ),
		);
		$this->controls['offset_top']       = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Offset Top (%)', 'bricksable' ),
			'type'        => 'number',
			'placeholder' => esc_html__( '100', 'bricksable' ),
			'description' => esc_html__( 'Distance from top of viewport for animation to end. Must be greater than Offset Bottom', 'bricksable' ),
			'required'    => array( 'scroll_trigger', '=', 'element' ),
		);
		$this->controls['offset_bottom']    = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Offset Bottom (%)', 'bricksable' ),
			'type'        => 'number',
			'placeholder' => esc_html__( '0', 'bricksable' ),
			'description' => esc_html__( 'Distance from bottom of viewport for animation to start. Must be less than Offset Top', 'bricksable' ),
			'required'    => array( 'scroll_trigger', '=', 'element' ),
		);
		$this->controls['loop']             = array(
			'tab'      => 'content',
			'group'    => 'lottie_settings',
			'label'    => esc_html__( 'Loop', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'trigger', '!=', 'scroll' ),
		);
		$this->controls['use_number_loop']  = array(
			'tab'      => 'content',
			'group'    => 'lottie_settings',
			'label'    => esc_html__( 'Use Number of Loops', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'loop', '=', true ),

		);
		$this->controls['number_loop'] = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Number of Loop', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'min'         => 1,
			'max'         => 10,
			'step'        => '1',
			'default'     => '2',
			'placeholder' => esc_html__( '1', 'bricksable' ),
			'description' => esc_html__( 'This option is only available if True is selected for Loop. Enter the number of times you wish to have the animation loop before stopping.', 'bricksable' ),
			'required'    => array( 'use_number_loop', '=', true ),
		);
		$this->controls['direction']   = array(
			'tab'      => 'content',
			'group'    => 'lottie_settings',
			'label'    => esc_html__( 'Reverse', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'loop', '=', true ),
		);
		$this->controls['speed']       = array(
			'tab'         => 'content',
			'group'       => 'lottie_settings',
			'label'       => esc_html__( 'Animation Speed', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'min'         => 0.1,
			'max'         => 5,
			'step'        => '0.1',
			'default'     => '1',
			'placeholder' => esc_html__( '1', 'bricksable' ),
			'description' => esc_html__( 'Increase or decrease the animation speed.', 'bricksable' ),
			'required'    => array( 'trigger', '!=', 'scroll' ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-lottie' );
		wp_enqueue_script( 'ba-lottie' );
		wp_localize_script(
			'ba-lottie',
			'bricksableLottieData',
			array(
				'lottieInstances' => array(),
			)
		);
	}

	public function render() {
		$settings = $this->settings;

		$source_type     = isset( $settings['source_type'] ) ? $settings['source_type'] : false;
		$url             = isset( $settings['url'] ) ? $this->render_dynamic_data( $settings['url'] ) : 'https://assets5.lottiefiles.com/private_files/lf30_hhvn1H.json';
		$json_file       = isset( $settings['lottie_json_file'] ) ? esc_url( $settings['lottie_json_file']['url'] ) : '';
		$number_loop     = isset( $settings['use_number_loop'] ) ? true : false;
		$trigger         = isset( $settings['trigger'] ) ? $settings['trigger'] : 'viewport';
		$mouseout_action = isset( $settings['mouseout_action'] ) ? $settings['mouseout_action'] : 'no_action';
		$mouseout        = 'hover' === $trigger ? $mouseout_action : false;

		$lottie_options = array(
			'url'              => 'url' === $source_type ? $url : $json_file,
			'trigger'          => $trigger,
			'loop'             => isset( $settings['loop'] ) ? true : false,
			'usenumberLoop'    => isset( $settings['use_number_loop'] ) ? true : false,
			'numberLoop'       => isset( $settings['number_loop'] ) ? intval( $settings['number_loop'] ) : 2,
			'speed'            => isset( $settings['speed'] ) ? floatval( $settings['speed'] ) : 1,
			'direction'        => isset( $settings['direction'] ) ? -1 : 1,
			'mouseout'         => $mouseout,
			'scroll'           => isset( $settings['scroll_trigger'] ) ? $settings['scroll_trigger'] : 'document',
			'element_selector' => isset( $settings['element_selector'] ) ? $settings['element_selector'] : '',
			'offset_top'       => isset( $settings['offset_top'] ) ? $settings['offset_top'] : '',
			'offset_bottom'    => isset( $settings['offset_bottom'] ) ? $settings['offset_bottom'] : '',
		);
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			//phpcs:ignore
			echo "<div {$this->render_attributes( '_root' )}>";
		}
		$this->set_attribute( 'wrapper', 'id', 'ba-bricks-lottie-' . $this->id );
		$this->set_attribute( 'wrapper', 'class', 'ba-lottie-wrapper' );
		$this->set_attribute( 'wrapper', 'data-ba-bricks-lottie-options', wp_json_encode( $lottie_options ) );

		// Render.
		$close_a_tag = false;

		if ( isset( $settings['link_url'] ) && 'url' === $settings['link'] && isset( $settings['link_url'] ) ) {
			$close_a_tag = true;

			// Link.
			$this->set_link_attributes( 'a', $settings['link_url'] );

			echo '<a ' . $this->render_attributes( 'a' ) . '>'; //phpcs:ignore
		}

		//phpcs:ignore
		echo '<div ' . $this->render_attributes( 'wrapper' ) . '>';
		echo '</div>';
		if ( $close_a_tag ) {
			echo '</a>';
		}
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			echo '</div>';
		}
	}
}
