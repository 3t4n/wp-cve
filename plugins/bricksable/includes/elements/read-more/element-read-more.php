<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Read_More extends \Bricks\Element {
	// Element properties.
	public $category     = 'bricksable';
	public $name         = 'ba-read-more';
	public $icon         = 'ti-layout-list-post';
	public $css_selector = '';
	public $scripts      = array( 'bricksableReadMore' );
	public $nestable     = true; // true || @since 1.5.

	// Methods: Builder-specific.
	public function get_label() {
		return esc_html__( 'Read More (Expand)', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['button']   = array(
			'title' => esc_html__( 'Read More Button', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['title']    = array(
			'title' => esc_html__( 'Title Typography', 'bricksable' ),
			'tab'   => 'style',
		);
	}

	public function set_controls() {
		$this->controls['_typography']['css'][0]['selector'] = '.ba-readmore-content';

		$this->controls['readMoreType'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Content Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'editor'   => esc_html__( 'Editor', 'bricksable' ),
				'nestable' => esc_html__( 'Nestable', 'bricksable' ),
				// 'template' => esc_html__( 'Template', 'bricksable' ),.
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'Editor', 'bricksable' ),
			'clearable'   => false,
			'default'     => 'editor',
		);

		/*
		$this->controls['template'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Template', 'bricksable' ),
			'type'        => 'select',
			'options'     => bricks_is_builder() ? \Bricks\Templates::get_templates_list( array( 'section', 'content', 'popup' ), get_the_ID() ) : array(),
			'searchable'  => true,
			'placeholder' => esc_html__( 'Select template', 'bricksable' ),
			'required'    => array( 'readMoreType', '=', 'template' ),
		);*/

		$this->controls['titleSeparator'] = array(
			'tab'      => 'content',
			'type'     => 'separator',
			'label'    => esc_html__( 'Title', 'bricksable' ),
			'required' => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['title'] = array(
			'tab'         => 'content',
			'type'        => 'text',
			'default'     => esc_html__( 'I am a heading', 'bricksable' ),
			'placeholder' => esc_html__( 'Here goes my heading ..', 'bricksable' ),
			'required'    => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['tag'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'HTML Title tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1'     => 'h1',
				'h2'     => 'h2',
				'h3'     => 'h3',
				'h4'     => 'h4',
				'h5'     => 'h5',
				'h6'     => 'h6',
				'custom' => esc_html__( 'Custom', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'h3',
			'placeholder' => ! empty( $this->theme_styles['tag'] ) ? $this->theme_styles['tag'] : 'h3',
			'required'    => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['customTag'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Custom tag', 'bricksable' ),
			'type'        => 'text',
			'inline'      => true,
			'placeholder' => 'div',
			'required'    => array( 'tag', '=', 'custom' ),
		);

		$this->controls['titleTypography'] = array(
			'tab'      => 'style',
			'group'    => 'title',
			'label'    => esc_html__( 'Title Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-readmore-title',
				),
			),
			'inline'   => false,
			'popup'    => false,
			'required' => array( 'readMoreType', '!=', 'nestable' ),

		);

		$this->controls['titleMargin'] = array(
			'tab'      => 'style',
			'group'    => 'title',
			'label'    => esc_html__( 'Margin', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-readmore-title',
				),
			),
			'required' => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['titlePadding'] = array(
			'tab'      => 'style',
			'group'    => 'title',
			'label'    => esc_html__( 'Padding', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-readmore-title',
				),
			),
			'required' => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['contentSeparator'] = array(
			'tab'      => 'content',
			'type'     => 'separator',
			'label'    => esc_html__( 'Content', 'bricksable' ),
			'required' => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['content'] = array(
			'tab'      => 'content',
			'type'     => 'editor',
			'default'  => '<p>' . esc_html__( 'Here goes your text ... discover a convenient way to manage lengthy blocks of text with our new "Read more" and "Close" functionality. With a simple click on the "Read more" link, you can expand the text and dive into all the juicy details. No more endless scrolling! When you’re done, just click "Close" to bring it back to a neat and compact form. It’s a super convenient way to manage lengthy content without any hassle. Give it a try and enjoy a smoother reading experience with our user-friendly "Read more" and "Close" feature!', 'bricksable' ) . '</p>',
			'required' => array( 'readMoreType', '!=', 'nestable' ),
		);

		$this->controls['contentMargin'] = array(
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-readmore-content',
				),
			),
		);

		$this->controls['contentPadding'] = array(
			'tab'   => 'style',
			'group' => '_typography',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-readmore-content',
				),
			),
		);

		// Settings.
		$this->controls['startOpen'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Start on Open', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Expand on load.', 'bricksable' ),
		);
		$this->controls['speed']     = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Animation Speed (ms)', 'bricksable' ),
			'type'        => 'number',
			'units'       => array(
				'ms' => array(
					'min'  => 100,
					'max'  => 1000,
					'step' => 1,
				),
			),
			'default'     => '400',
			'placeholder' => esc_html__( '400', 'bricksable' ),
			'description' => esc_html__( 'Animation speed in milliseconds. Default is 400.', 'bricksable' ),
		);

		$this->controls['collapsedHeight'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Collapsed Height (px)', 'bricksable' ),
			'type'        => 'number',
			'default'     => '100',
			'placeholder' => esc_html__( '100', 'bricksable' ),
			'description' => esc_html__( 'The maximum height of the component in it’s collapsed state.', 'bricksable' ),
		);

		$this->controls['heightMargin'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Height Margin (px)', 'bricksable' ),
			'type'        => 'number',
			'default'     => '16',
			'placeholder' => esc_html__( '16', 'bricksable' ),
			'description' => esc_html__( 'Avoids collapsing blocks that are only slightly larger than collapsed height', 'bricksable' ),
		);

		$this->controls['showGradient'] = array(
			'tab'     => 'content',
			'group'   => 'settings',
			'label'   => esc_html__( 'Show Gradient', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => true,
		);

		// Button.
		$this->controls['moreText'] = array(
			'label'       => esc_html__( 'More Text', 'bricksable' ),
			'tab'         => 'content',
			'group'       => 'button',
			'type'        => 'text',
			'default'     => esc_html__( 'Read More', 'bricksable' ),
			'placeholder' => esc_html__( 'Read More', 'bricksable' ),
		);
		$this->controls['lessText'] = array(
			'label'       => esc_html__( 'Less Text', 'bricksable' ),
			'tab'         => 'content',
			'group'       => 'button',
			'type'        => 'text',
			'default'     => esc_html__( 'Close', 'bricksable' ),
			'placeholder' => esc_html__( 'Close', 'bricksable' ),
		);

		$this->controls['buttonTypographySeparator'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'type'  => 'separator',
			'label' => esc_html__( 'Style', 'bricksable' ),
		);
		$this->controls['buttonTypography']          = array(
			'tab'    => 'content',
			'group'  => 'button',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.ba-read-more-button',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['buttonBackground'] = array(
			'tab'     => 'content',
			'group'   => 'button',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'background',
			'inline'  => true,
			'small'   => true,
			'exclude' => array(
				'parallax',
				'videoUrl',
				'videoScale',
			),
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-read-more-button',
				),
			),
		);

		$this->controls['buttonBorder'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'type'  => 'border',
			'label' => esc_html__( 'Border', 'bricksable' ),
			'css'   => array(
				array(
					'property' => 'border',
					'selector' => '.ba-read-more-button',
				),
			),
		);

		$this->controls['buttonBoxShadow'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'label' => esc_html__( 'Box Shadow', 'bricksable' ),
			'type'  => 'box-shadow',
			'css'   => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-read-more-button',
				),
			),
		);

		$this->controls['buttonTextAlign'] = array(
			'tab'     => 'content',
			'group'   => 'button',
			'label'   => esc_html__( 'Text Align', 'bricksable' ),
			'type'    => 'justify-content',
			'css'     => array(
				array(
					'property' => 'justify-content',
					'selector' => '.ba-read-more-button',
				),
			),
			'exclude' => array(
				'space-between',
				'space-around',
				'space-evenly',
			),
		);

		$this->controls['buttonAlign'] = array(
			'tab'     => 'content',
			'group'   => 'button',
			'label'   => esc_html__( 'Align', 'bricksable' ),
			'type'    => 'align-items',
			'css'     => array(
				array(
					'property' => 'align-self',
					'selector' => '.ba-read-more-button',
				),
			),
			'default' => 'center',
		);

		$this->controls['buttonMargin'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-read-more-button',
				),
			),
		);

		$this->controls['buttonPadding'] = array(
			'tab'     => 'content',
			'group'   => 'button',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-read-more-button',
				),
			),
			'default' => array(
				'top'    => '0.5em',
				'right'  => '1em',
				'bottom' => '0.5em',
				'left'   => '1em',
			),
		);

		$this->controls['buttonIconSeparator'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'type'  => 'separator',
			'label' => esc_html__( 'Icon', 'bricksable' ),
		);

		$this->controls['useButtonIcon'] = array(
			'tab'     => 'content',
			'group'   => 'button',
			'label'   => esc_html__( 'Use Button Icon', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => true,
		);

		$this->controls['readMoreIcon'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Read More Icon', 'bricksable' ),
			'type'     => 'icon',
			'css'      => array(
				array(
					'selector' => '.ba-read-more-button .ba-read-more-icon',
				),
				array(
					'selector' => '.ba-read-more-button svg',
				),
			),
			'default'  => array(
				'library' => 'Ionicons',
				'icon'    => 'ion-ios-arrow-down',
			),
			'required' => array( 'useButtonIcon', '!=', '' ),
		);

		$this->controls['readMoreIconTypography'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Read More Icon Typography', 'bricksable' ),
			'type'     => 'typography',
			'exclude'  => array(
				'font-family',
				'font-style',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
				'text-decoration',
			),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-read-more-button .ba-read-more-icon i',
				),
			),
			'default'  => array(
				'font-size' => 18,
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'useButtonIcon', '!=', '' ),
		);

		$this->controls['lessIcon'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Less Icon', 'bricksable' ),
			'type'     => 'icon',
			'css'      => array(
				array(
					'selector' => '.ba-read-more-less-button .ba-read-more-less-icon',
				),
				array(
					'selector' => '.ba-read-more-less-button svg',
				),
			),
			'default'  => array(
				'library' => 'Ionicons',
				'icon'    => 'ion-ios-arrow-up',
			),
			'required' => array( 'useButtonIcon', '!=', '' ),
		);

		$this->controls['lessIconTypography'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Less Icon Typography', 'bricksable' ),
			'type'     => 'typography',
			'exclude'  => array(
				'font-family',
				'font-style',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
				'text-decoration',
			),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-read-more-button .ba-read-more-less-icon i',
				),
			),
			'default'  => array(
				'font-size' => 18,
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'useButtonIcon', '!=', '' ),
		);

		$this->controls['iconGap'] = array(
			'tab'         => 'content',
			'group'       => 'button',
			'label'       => esc_html__( 'Icon Gap', 'bricksable' ),
			'type'        => 'number',
			'units'       => array(
				'ms' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			),
			'css'         => array(
				array(
					'property' => 'gap',
					'selector' => '.ba-read-more-button',
				),
			),
			'default'     => '10',
			'placeholder' => esc_html__( '10', 'bricksable' ),
			'description' => esc_html__( 'Icon gap inbetween text.', 'bricksable' ),
			'required'    => array( 'useButtonIcon', '!=', '' ),
		);
	}

	// Methods: Frontend-specific.
	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-read-more' );
		wp_enqueue_script( 'ba-read-more' );
		wp_localize_script(
			'ba-read-more',
			'bricksableReadMoreData',
			array(
				'ReadMoreInstances' => array(),
			)
		);
	}
	public function render() {
		$settings       = $this->settings;
		$use_icon       = ! empty( $settings['useButtonIcon'] ) ? $settings['useButtonIcon'] : false;
		$show_gradient  = isset( $settings['showGradient'] ) ? boolval( $settings['showGradient'] ) : false;
		$read_more_icon = isset( $settings['readMoreIcon'] ) && isset( $settings['readMoreIcon']['icon'] ) ? true : false;
		$less_more_icon = isset( $settings['lessIcon'] ) && isset( $settings['lessIcon']['icon'] ) ? true : false;

		$readmore_options = array(
			'speed'           => isset( $settings['speed'] ) ? intval( $settings['speed'] ) : 100,
			'collapsedHeight' => isset( $settings['collapsedHeight'] ) ? intval( $settings['collapsedHeight'] ) : 100,
			'heightMargin'    => isset( $settings['heightMargin'] ) ? intval( $settings['heightMargin'] ) : 16,
			'moreLink'        => isset( $settings['moreText'] ) ? esc_attr( $settings['moreText'] ) : esc_attr( 'Read more' ),
			'lessText'        => isset( $settings['lessText'] ) ? esc_attr( $settings['lessText'] ) : esc_attr( 'Close' ),
			'startOpen'       => isset( $settings['startOpen'] ) ? boolval( $settings['startOpen'] ) : false,
			'readMoreIcon'    => isset( $settings['readMoreIcon'] ) && ( true === $use_icon && true === $read_more_icon ) ? esc_attr( $settings['readMoreIcon']['icon'] ) : '',
			'readMoreIconSVG' => isset( $settings['readMoreIcon'] ) && ( true === $use_icon && false === $read_more_icon ) ? self::render_icon( $settings['readMoreIcon'] ) : '',
			'lessIcon'        => isset( $settings['lessIcon'] ) && ( true === $use_icon && true === $less_more_icon ) ? esc_attr( $settings['lessIcon']['icon'] ) : '',
			'lessIconSVG'     => isset( $settings['lessIcon'] ) && ( true === $use_icon && false === $less_more_icon ) ? self::render_icon( $settings['lessIcon'], 'asdasd' ) : '',
		);

		$this->set_attribute( '_root', 'data-ba-bricks-read-more-options', wp_json_encode( $readmore_options ) );

		$output = "<div {$this->render_attributes( '_root' )}>";
		$this->set_attribute( 'wrapper', 'class', array( 'ba-read-more-wrapper', $show_gradient ? 'ba-read-me-gradient' : '' ) );
		$output .= "<div {$this->render_attributes( 'wrapper' )}>";

		if ( isset( $settings['readMoreType'] ) ) {
			if ( 'editor' === $settings['readMoreType'] ) {
				// Title.
				$title_classes = array(
					'ba-readmore-title',
				);

				$this->set_attribute( 'title', $this->tag );
				$this->set_attribute( 'title', 'class', $title_classes );

				if ( ! empty( $settings['title'] ) ) {
					$output .= '<' . $this->render_attributes( 'title' ) . '>' . $settings['title'] . '</' . $this->tag . '>';
				}

				// Content.
				$this->set_attribute( 'content', 'class', 'ba-readmore-content' );
				$content = ! empty( $settings['content'] ) ? $settings['content'] : false;
				$content = $this->render_dynamic_data( $content );
				$content = \Bricks\Helpers::parse_editor_content( $content );

				if ( $content ) {
					$output .= "<div {$this->render_attributes( 'content' )}>{$content}</div>";
				}
			} elseif ( method_exists( '\Bricks\Frontend', 'render_children' ) ) {
					$output .= \Bricks\Frontend::render_children( $this );
			}
		}

		// Wrapper.
		$output .= '</div>';
		// Root.
		$output .= '</div>';
		//phpcs:ignore
		echo $output;
	}
}
