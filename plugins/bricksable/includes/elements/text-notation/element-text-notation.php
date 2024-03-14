<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Text_Notation extends \Bricks\Element {
	public $category = 'bricksable';
	public $name     = 'ba-text-notation';
	public $icon     = 'ti-text';
	public $scripts  = array( 'bricksableTextNotation' );

	public function get_label() {
		return esc_html__( 'Text Notation', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['wrapper']           = array(
			'title' => esc_html__( 'Wrapper / Tag', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['text']              = array(
			'title' => esc_html__( 'Content', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['notation_settings'] = array(
			'title' => esc_html__( 'Notation Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['prefix_style']      = array(
			'title' => esc_html__( 'Prefix Style', 'bricksable' ),
			'tab'   => 'style',
		);
		$this->control_groups['notation_style']    = array(
			'title' => esc_html__( 'Notation Style', 'bricksable' ),
			'tab'   => 'style',
		);
		$this->control_groups['suffix_style']      = array(
			'title' => esc_html__( 'Suffix Style', 'bricksable' ),
			'tab'   => 'style',
		);
	}
	public function set_controls() {
		$this->controls['_typography']['css'][0]['selector'] = '.ba-text-notation-wrapper-tag';
		$this->controls['wrapper_tag']                       = array(
			'tab'         => 'content',
			'group'       => 'wrapper',
			'label'       => esc_html__( 'Wrapper Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1'   => esc_html__( 'Heading 1 (h1)', 'bricksable' ),
				'h2'   => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3'   => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4'   => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5'   => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6'   => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
				'div'  => esc_html__( 'Division (div)', 'bricksable' ),
				'p'    => esc_html__( 'Paragraph (p)', 'bricksable' ),
				'span' => esc_html__( 'Span (span)', 'bricksable' ),
			),
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'p',
			'description' => esc_html__( 'The main wrapper tag.', 'bricksable' ),
		);
		$this->controls['notation_tag']                      = array(
			'tab'         => 'content',
			'group'       => 'text',
			'label'       => esc_html__( 'Notation Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1'   => esc_html__( 'Heading 1 (h1)', 'bricksable' ),
				'h2'   => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3'   => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4'   => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5'   => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6'   => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
				'div'  => esc_html__( 'Division (div)', 'bricksable' ),
				'p'    => esc_html__( 'Paragraph (p)', 'bricksable' ),
				'span' => esc_html__( 'Span (span)', 'bricksable' ),
			),
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'span',
		);

		$this->controls['prefix'] = array(
			'tab'            => 'content',
			'group'          => 'text',
			'label'          => esc_html__( 'Prefix', 'bricksable' ),
			'type'           => 'textarea',
			'default'        => 'Hand drawn ',
			'hasDynamicData' => 'text',
		);

		$this->controls['suffix'] = array(
			'tab'            => 'content',
			'group'          => 'text',
			'label'          => esc_html__( 'Suffix', 'bricksable' ),
			'type'           => 'textarea',
			'default'        => ' look and feel!',
			'hasDynamicData' => 'text',
		);

		$this->controls['text'] = array(
			'tab'            => 'content',
			'group'          => 'text',
			'label'          => esc_html__( 'Notation Text', 'bricksable' ),
			'type'           => 'textarea',
			'default'        => esc_html__( 'Notation', 'bricksable' ),
			'placeholder'    => esc_html__( 'Here goes my notation ..', 'bricksable' ),
			'hasDynamicData' => 'text',
		);
		// Notation.
		$this->controls['notation_type']         = array(
			'tab'         => 'content',
			'group'       => 'notation_settings',
			'label'       => esc_html__( 'Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'underline'      => esc_html__( 'Underline', 'bricksable' ),
				'box'            => esc_html__( 'Box', 'bricksable' ),
				'circle'         => esc_html__( 'Circle', 'bricksable' ),
				'highlight'      => esc_html__( 'Highlight', 'bricksable' ),
				'strike-through' => esc_html__( 'Strike through', 'bricksable' ),
				'crossed-off'    => esc_html__( 'Crossed-off', 'bricksable' ),
				'bracket'        => esc_html__( 'Bracket', 'bricksable' ),
			),
			'default'     => 'highlight',
			'placeholder' => esc_html__( 'Highlight', 'bricksable' ),
		);
		$this->controls['notation_bracket']      = array(
			'tab'      => 'content',
			'group'    => 'notation_settings',
			'label'    => esc_html__( 'Bracket Type', 'bricksable' ),
			'type'     => 'select',
			'options'  => array(
				'right'      => esc_html__( 'Right', 'bricksable' ),
				'left'       => esc_html__( 'Left', 'bricksable' ),
				'left-right' => esc_html__( 'Left & Right', 'bricksable' ),
				'top'        => esc_html__( 'Top', 'bricksable' ),
				'bottom'     => esc_html__( 'Bottom', 'bricksable' ),
				'top-bottom' => esc_html__( 'Top & Bottom', 'bricksable' ),
			),
			'default'  => 'left-right',
			'required' => array( 'notation_type', '=', 'bracket' ),
		);
		$this->controls['notation_duration']     = array(
			'tab'         => 'content',
			'group'       => 'notation_settings',
			'label'       => esc_html__( 'Animation Duration', 'bricksable' ),
			'type'        => 'number',
			'units'       => array(
				'ms' => array(
					'min'  => 200,
					'max'  => 5000,
					'step' => 1,
				),
			),
			'default'     => '800ms',
			'placeholder' => esc_html__( '800', 'bricksable' ),
			'description' => esc_html__( 'Duration of the animation in milliseconds. Default is 800ms.', 'bricksable' ),
		);
		$this->controls['notation_color']        = array(
			'tab'         => 'content',
			'group'       => 'notation_settings',
			'label'       => esc_html__( 'Text color', 'bricksable' ),
			'type'        => 'color',
			'inline'      => true,
			'small'       => true,
			'default'     => array(
				'hex' => '#FFD54F',
			),
			'pasteStyles' => false,
			'description' => esc_html__( 'Define the annotation color.', 'bricksable' ),
		);
		$this->controls['notation_stroke_width'] = array(
			'tab'         => 'content',
			'group'       => 'notation_settings',
			'label'       => esc_html__( 'Stroke Width', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'min'         => 1,
			'max'         => 10,
			'step'        => '1',
			'default'     => '3',
			'placeholder' => esc_html__( '3', 'bricksable' ),
		);
		$this->controls['notation_iterations']   = array(
			'tab'         => 'content',
			'group'       => 'notation_settings',
			'label'       => esc_html__( 'Iterations', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'min'         => 1,
			'max'         => 10,
			'step'        => '1',
			'default'     => '2',
			'placeholder' => esc_html__( '2', 'bricksable' ),
			'description' => esc_html__(
				'By default annotations are drawn in two iterations, e.g. when underlining, drawing from left to right and then back from right to left. Setting this property can let you configure the number of iterations.',
				'bricksable'
			),
		);

		$this->controls['prefixTypography']  = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Prefix Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.prefix',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefixSeparator']   = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Styling', 'bricksable' ),
			'type'     => 'separator',
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefixMargin']      = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Margin', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'margin',
					'selector' => '.prefix',
				),
			),
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefixPadding']     = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Padding', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.prefix',
				),
			),
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefixDisplay']     = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Display', 'bricksable' ),
			'type'     => 'select',
			'options'  => array(
				'inline-block' => esc_html__( 'Inline block', 'bricksable' ),
				'block'        => esc_html__( 'Block', 'bricksable' ),
			),
			'css'      => array(
				array(
					'property' => 'display',
					'selector' => '.prefix',
				),
			),
			'default'  => 'inline-block',
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefix_Border']     = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Border', 'bricksable' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.prefix',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefix_BoxShadow']  = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Box shadow', 'bricksable' ),
			'type'     => 'box-shadow',
			'css'      => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.prefix',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'prefix', '!=', '' ),
		);
		$this->controls['prefix_background'] = array(
			'tab'      => 'style',
			'group'    => 'prefix_style',
			'label'    => esc_html__( 'Background Color', 'bricksable' ),
			'type'     => 'color',
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.prefix',
				),
			),
			'required' => array( 'prefix', '!=', '' ),
		);

		$this->controls['notationTypography'] = array(
			'tab'      => 'style',
			'group'    => 'notation_style',
			'label'    => esc_html__( 'Notation Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-text-notation-wrapper',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'text', '!=', '' ),
		);
		$this->controls['notationSeparator']  = array(
			'tab'      => 'style',
			'group'    => 'notation_style',
			'label'    => esc_html__( 'Styling', 'bricksable' ),
			'type'     => 'separator',
			'required' => array( 'text', '!=', '' ),
		);
		$this->controls['notationadding']     = array(
			'tab'      => 'style',
			'group'    => 'notation_style',
			'label'    => esc_html__( 'Padding', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-text-notation-inner',
				),
			),
			'required' => array( 'text', '!=', '' ),
		);
		$this->controls['notationDisplay']    = array(
			'tab'      => 'style',
			'group'    => 'notation_style',
			'label'    => esc_html__( 'Display', 'bricksable' ),
			'type'     => 'select',
			'options'  => array(
				'inline-block' => esc_html__( 'Inline block', 'bricksable' ),
				'block'        => esc_html__( 'Block', 'bricksable' ),
			),
			'css'      => array(
				array(
					'property' => 'display',
					'selector' => '.ba-text-notation-wrapper',
				),
			),
			'default'  => 'inline-block',
			'required' => array( 'text', '!=', '' ),
		);

		$this->controls['suffixTypography']  = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Suffix Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.suffix',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffixSeparator']   = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Styling', 'bricksable' ),
			'type'     => 'separator',
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffixMargin']      = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Margin', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'margin',
					'selector' => '.suffix',
				),
			),
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffixPadding']     = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Padding', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.suffix',
				),
			),
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffixDisplay']     = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Display', 'bricksable' ),
			'type'     => 'select',
			'options'  => array(
				'inline-block' => esc_html__( 'Inline block', 'bricksable' ),
				'block'        => esc_html__( 'Block', 'bricksable' ),
			),
			'css'      => array(
				array(
					'property' => 'display',
					'selector' => '.suffix',
				),
			),
			'default'  => 'inline-block',
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffix_Border']     = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Border', 'bricksable' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.suffix',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffix_BoxShadow']  = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Box shadow', 'bricksable' ),
			'type'     => 'box-shadow',
			'css'      => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.suffix',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'suffix', '!=', '' ),
		);
		$this->controls['suffix_background'] = array(
			'tab'      => 'style',
			'group'    => 'suffix_style',
			'label'    => esc_html__( 'Background Color', 'bricksable' ),
			'type'     => 'color',
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.suffix',
				),
			),
			'required' => array( 'suffix', '!=', '' ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-text-notation' );
		wp_enqueue_script( 'ba-text-notation' );
		wp_localize_script(
			'ba-text-notation',
			'BricksabletextNotationData',
			array(
				'textNotationInstances' => array(),
			)
		);
	}

	public function render() {
		$settings = $this->settings;

		$notation_tag = isset( $settings['notation_tag'] ) ? esc_html( $settings['notation_tag'] ) : 'h3';

		if ( ! empty( $settings['notation_color']['rgb'] ) ) {
			$notation_color = $settings['notation_color']['rgb'];
		} elseif ( ! empty( $settings['notation_color']['hex'] ) ) {
			$notation_color = $settings['notation_color']['hex'];
		} elseif ( ! empty( $settings['notation_color']['raw'] ) ) {
			$notation_color = $settings['notation_color']['raw'];
		} else {
			$notation_color = '';
		}

		if ( ! empty( $settings['notation_color']['raw'] ) && ( ! empty( $settings['notation_color']['hex'] ) || ! empty( $settings['notation_color']['rgb'] ) ) ) {
			$notation_color = $settings['notation_color']['raw'];
		}

		$notation_bracket = isset( $settings['notation_bracket'] ) ? $settings['notation_bracket'] : array( 'left', 'right' );

		switch ( $notation_bracket ) {
			case 'left':
				$notation_bracket = 'left';
				break;
			case 'right':
				$notation_bracket = 'right';
				break;
			case 'top':
				$notation_bracket = 'top';
				break;
			case 'bottom':
				$notation_bracket = 'bottom';
				break;
			case 'top-bottom':
				$notation_bracket = array( 'top', 'bottom' );
				break;
			default:
				$notation_bracket = array( 'left', 'right' );
		}

		$notation_options = array(
			'type'              => isset( $settings['notation_type'] ) ? esc_attr( $settings['notation_type'] ) : 'underline',
			'animationDuration' => isset( $settings['notation_duration'] ) ? intval( $settings['notation_duration'] ) : 800,
			'color'             => isset( $settings['notation_color'] ) ? $notation_color : '#ff5722',
			'strokeWidth'       => isset( $settings['notation_stroke_width'] ) ? intval( $settings['notation_stroke_width'] ) : 3,
			'iterations'        => isset( $settings['notation_iterations'] ) ? intval( $settings['notation_iterations'] ) : 1,
			'brackets'          => $notation_bracket,
		);

		$wrapper_tag = isset( $settings['wrapper_tag'] ) ? esc_html( $settings['wrapper_tag'] ) : 'p';
		$this->set_attribute( 'notation_wrapper', $wrapper_tag );
		$this->set_attribute( 'notation_wrapper', 'class', 'ba-text-notation-wrapper-tag' );
		$this->set_attribute( 'notation_text', $notation_tag );
		$this->set_attribute( 'notation_text', 'class', 'ba-text-notation-wrapper' );
		$this->set_attribute( 'notation_text', 'data-ba-bricks-text-notation-options', wp_json_encode( $notation_options ) );

		// Render.
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			//phpcs:ignore
			echo "<div {$this->render_attributes( '_root' )}>";
		}
		//phpcs:ignore
		echo '<' . $this->render_attributes( 'notation_wrapper' ) . '>';

		if ( isset( $settings['prefix'] ) ) {
			?>
			<span class="prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
			<?php
		}

		$output  = '<' . $this->render_attributes( 'notation_text' ) . '>';
		$output .= '<span class="ba-text-notation-inner">' . $settings['text'] . '</span>';
		$output .= '</' . $notation_tag . '>';

		//phpcs:ignore
		echo $output;

		if ( isset( $settings['suffix'] ) ) {
			?>
			<span class="suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
			<?php
		}
		echo '</' . esc_attr( $wrapper_tag ) . '>';
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			echo '</div>';
		}
	}
}
