<?php
namespace Bricks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Multi_Heading extends Element {
	public $category = 'bricksable';
	public $name     = 'ba-multi-heading';
	public $icon     = 'ti-uppercase';

	public function get_label() {
		return esc_html__( 'Multi Heading', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['content'] = array(
			'title' => esc_html__( 'Heading', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['style'] = array(
			'title' => esc_html__( 'Heading Styles', 'bricksable' ),
			'tab'   => 'content',
		);
	}
	public function set_controls() {
		$this->controls['multi_heading_item'] = array(
			'tab'           => 'content',
			'group'         => 'content',
			'label'         => esc_html__( 'Multi Heading Text', 'bricksable' ),
			'type'          => 'repeater',
			'titleProperty' => 'text',
			'default'       => array(
				array( 'text' => 'Multi ' ),
				array( 'text' => 'Heading' ),
			),
			'placeholder'   => esc_html__( 'Heading block', 'bricksable' ),
			'fields'        => array(
				'text'                       => array(
					'type'           => 'text',
					'hasDynamicData' => 'text',
				),
				'heading_link'               => array(
					'label'       => esc_html__( 'Link to', 'bricksable' ),
					'type'        => 'link',
					'pasteStyles' => false,
				),
				'heading_typography'         => array(
					'label'  => esc_html__( 'Typography', 'bricksable' ),
					'type'   => 'typography',
					'css'    => array(
						array(
							'property'         => 'typography',
							'repeaterSelector' => '.ba-multi-heading-text',
						),
					),
					'inline' => true,
					'small'  => true,
				),
				'heading_background'         => array(
					'label'   => esc_html__( 'Background', 'bricksable' ),
					'type'    => 'background',
					'css'     => array(
						array(
							'property'         => 'background',
							'repeaterSelector' => '.ba-multi-heading-text',
						),
					),
					'exclude' => array(
						'videoUrl',
						'videoScale',
					),
					'inline'  => true,
					'small'   => true,
				),
				'use_background_text_mask'   => array(
					'label'       => esc_html__( 'Use Background Text Mask', 'bricksable' ),
					'type'        => 'checkbox',
					'inline'      => true,
					'description' => esc_html__( 'Upload a background below', 'bricksable' ),
				),
				'background_text_mask_image' => array(
					'label'    => esc_html__( 'Text Mask Image', 'bricksable' ),
					'type'     => 'image',
					'css'      => array(
						array(
							'property' => 'background-image',
							'selector' => '.ba-multi-heading-text-mask',
						),
					),
					'required' => array( 'use_background_text_mask', '=', true ),
				),
				'use_gradient'               => array(
					'label'  => esc_html__( 'Use Gradient / Overlay', 'bricksable' ),
					'type'   => 'checkbox',
					'inline' => true,
				),
				'heading_gradient'           => array(
					'label'    => esc_html__( 'Gradient', 'bricksable' ),
					'type'     => 'gradient',
					'css'      => array(
						array(
							'property'         => 'background-image',
							'repeaterSelector' => '.ba-multi-heading-text',
						),
					),
					'required' => array( 'use_gradient', '=', true ),
				),
				'heading_border'             => array(
					'label'  => esc_html__( 'Border', 'bricksable' ),
					'type'   => 'border',
					'css'    => array(
						array(
							'property'         => 'border',
							'repeaterSelector' => '.ba-multi-heading-text',
						),
					),
					'inline' => true,
					'small'  => true,
				),
				'heading_box_shadow'         => array(
					'label' => esc_html__( 'Box Shadow', 'bricksable' ),
					'type'  => 'box-shadow',
					'css'   => array(
						array(
							'property' => 'box-shadow',
						),
					),
				),
				'heading_padding'            => array(
					'label' => esc_html__( 'Padding', 'bricksable' ),
					'type'  => 'dimensions',
					'css'   => array(
						array(
							'property'         => 'padding',
							'repeaterSelector' => '.ba-multi-heading-text',
						),
					),
				),
			),
		);

		$this->controls['tag'] = array(
			'tab'         => 'content',
			'group'       => 'content',
			'label'       => esc_html__( 'Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1'  => esc_html__( 'Heading 1 (h1)', 'bricksable' ),
				'h2'  => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3'  => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4'  => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5'  => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6'  => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
				'div' => esc_html__( 'Division (div)', 'bricksable' ),
			),
			'inline'      => true,
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'h3',
		);

		$this->controls['style'] = array(
			'tab'         => 'content',
			'group'       => 'content',
			'label'       => esc_html__( 'Style', 'bricksable' ),
			'type'        => 'select',
			'options'     => $this->control_options['styles'],
			'inline'      => true,
			'reset'       => true,
			'placeholder' => esc_html__( 'None', 'bricksable' ),
		);

		$this->controls['link'] = array(
			'tab'         => 'content',
			'group'       => 'content',
			'label'       => esc_html__( 'Link to', 'bricksable' ),
			'type'        => 'link',
			'pasteStyles' => false,
		);

		$this->controls['heading_justifyItems'] = array(
			'tab'      => 'style',
			'group'    => 'style',
			'label'    => esc_html__( 'Vertical', 'bricksable' ),
			'type'     => 'justify-content',
			'exclude'  => array(
				'space-between',
				'space-around',
				'space-evenly',
			),
			'css'      => array(
				array(
					'property' => 'justify-content',
					'selector' => '.bricks-heading',
				),
			),
			'inline'   => true,
			'required' => array( 'heading_display', '=', 'row' ),
		);
		$this->controls['heading_AlignItems']   = array(
			'tab'      => 'style',
			'group'    => 'style',
			'label'    => esc_html__( 'Horizontal', 'bricksable' ),
			'type'     => 'align-items',
			'exclude'  => array( 'stretch' ),
			'css'      => array(
				array(
					'property' => 'align-items',
					'selector' => '.bricks-heading',
				),
			),
			'inline'   => true,
			'required' => array( 'heading_display', '=', 'column' ),
		);

		$this->controls['gap'] = array(
			'tab'         => 'style',
			'group'       => 'style',
			'label'       => esc_html__( 'Gap', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'gap',
					'selector' => '.bricks-heading',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 80,
					'step' => 1,
				),
				'em' => array(
					'min'  => 0,
					'max'  => 10,
					'step' => 0.1,
				),
			),
			'description' => esc_html__( 'Adjust the gap between the headings.', 'bricksable' ),
		);

		$this->controls['heading_display'] = array(
			'tab'     => 'content',
			'group'   => 'style',
			'label'   => esc_html__( 'Display', 'bricksable' ),
			'type'    => 'select',
			'default' => 'row',
			'options' => array(
				'row'    => esc_html__( 'Row', 'bricksable' ),
				'column' => esc_html__( 'Column', 'bricksable' ),
			),
			'css'     => array(
				array(
					'property' => 'flex-direction',
					'selector' => '.bricks-heading',
				),
			),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-multi-heading' );
	}

	public function render() {
		$settings = $this->settings;

		$tag = isset( $settings['tag'] ) ? esc_html( $settings['tag'] ) : 'h3';

		$heading_classes = array(
			'bricks-heading',
			'bricks-heading-' . $tag,
		);

		if ( isset( $settings['style'] ) ) {
			$heading_classes[] = 'bricks-color-' . $settings['style'];
		}

		if ( isset( $settings['separator'] ) ) {
			$heading_classes[] = 'has-separator';
		}

		$this->set_attribute( 'heading', $tag );
		$this->set_attribute( 'heading', 'class', $heading_classes );

		// Link.
		if ( isset( $settings['link'] ) ) {
			$this->set_link_attributes( 'a', $settings['link'] );
		}

		// Render.
		//phpcs:ignore
		$output = '';

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$output .= "<div {$this->render_attributes( '_root' )}>";
		}
		$output .= '<' . $this->render_attributes( 'heading' ) . '>';

		if ( isset( $settings['link'] ) ) {
			$output .= '<a ' . $this->render_attributes( 'a' ) . '>';
		}

		foreach ( $settings['multi_heading_item'] as $index => $item ) {
			$multi_heading_item_classes = array(
				'ba-multi-heading-text',
				'repeater-item',
			);

			$this->set_attribute( "multi-heading-item-$index", 'span' );
			$this->set_attribute( "multi-heading-item-$index", 'class', $multi_heading_item_classes );
			$this->set_attribute( "multi-heading-item-text-mask-$index", 'span' );
			$this->set_attribute( "multi-heading-item-text-mask-$index", 'class', 'ba-multi-heading-text-mask' );
			// Link.
			if ( isset( $item['heading_link'] ) ) {
				$this->set_link_attributes( "multi-heading-link-$index", $item['heading_link'] );
				$output .= '<a ' . $this->render_attributes( "multi-heading-link-$index" ) . '>';
			}

			// Heading.
			if ( isset( $item['use_background_text_mask'] ) ) {
				$output .= '<' . $this->render_attributes( "multi-heading-item-$index" ) . '><' . $this->render_attributes( "multi-heading-item-text-mask-$index" ) . '>' . $item['text'] . '</span></span>';
			} else {
				$output .= '<' . $this->render_attributes( "multi-heading-item-$index" ) . '>' . $item['text'] . '</span>';
			}

			// Link.
			if ( isset( $item['heading_link'] ) ) {
				$output .= '</a>';
			}
		}

		if ( isset( $settings['link'] ) ) {
			$output .= '</a>';
		}

		$output .= '</' . $tag . '>';

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$output .= '</div>';
		}

		//phpcs:ignore
		echo $output;

	}
}
