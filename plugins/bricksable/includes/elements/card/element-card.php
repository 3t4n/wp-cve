<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Card extends \Bricks\Element {
	// Element properties.
	public $category     = 'bricksable';
	public $name         = 'ba-card';
	public $icon         = 'ti-layout-cta-left';
	public $css_selector = '';
	public $scripts      = array();
	public $nestable     = true; // true || @since 1.5.

	// Methods: Builder-specific.
	public function get_label() {
		return esc_html__( 'Card', 'bricksable' );
	}
	public function set_control_groups() {
		// Image.
		$this->control_groups['image'] = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		// Title.
		$this->control_groups['content'] = array(
			'title' => esc_html__( 'Content', 'bricksable' ),
			'tab'   => 'content',
		);
		// Button.
		$this->control_groups['button'] = array(
			'title' => esc_html__( 'Button', 'bricksable' ),
			'tab'   => 'content',
		);

		// Image Styling.
		$this->control_groups['image_styling'] = array(
			'title' => esc_html__( 'image', 'bricksable' ),
			'tab'   => 'style',
		);

		// Badge Styling.
		$this->control_groups['badge_styling'] = array(
			'title' => esc_html__( 'Badge', 'bricksable' ),
			'tab'   => 'style',
		);

		// Title Styling.
		$this->control_groups['title_styling'] = array(
			'title' => esc_html__( 'Title', 'bricksable' ),
			'tab'   => 'style',
		);

		// Subhead Styling.
		$this->control_groups['subhead_styling'] = array(
			'title' => esc_html__( 'Subhead', 'bricksable' ),
			'tab'   => 'style',
		);

		// Content Styling.
		$this->control_groups['content_styling'] = array(
			'title' => esc_html__( 'Content', 'bricksable' ),
			'tab'   => 'style',
		);

		// Content Wrapper.
		$this->control_groups['body_styling'] = array(
			'title' => esc_html__( 'Card Body', 'bricksable' ),
			'tab'   => 'style',
		);

		// Button Wrapper.
		$this->control_groups['button_styling'] = array(
			'title' => esc_html__( 'Button', 'bricksable' ),
			'tab'   => 'style',
		);
	}

	public function set_controls() {
		$this->controls['_width']['default']   = '100%';
		$this->controls['_display']['default'] = 'flex';

		$this->controls['nestable'] = array(
			'tab'     => 'content',
			'group'   => 'content',
			'label'   => esc_html__( 'Nestable?', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'small'   => true,
			'default' => false,
		);

		// Image.
		$this->controls['image'] = array(
			'tab'     => 'content',
			'group'   => 'image',
			'type'    => 'image',
			'default' => array(
				'url' => \Bricks\Builder::get_template_placeholder_image(),
			),
			'dynamic' => array(
				'active' => true,
			),
		);

		$this->controls['imageTag'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'label'       => esc_html__( 'Image HTML tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'figure'  => 'figure',
				'picture' => 'picture',
				'div'     => 'div',
			),
			'lowercase'   => true,
			'inline'      => true,
			'placeholder' => 'Figure',
			'default'     => 'figure',
		);

		$this->controls['_objectFit'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'label'       => esc_html__( 'Object fit', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'fill'       => esc_html__( 'Fill', 'bricksable' ),
				'contain'    => esc_html__( 'Contain', 'bricksable' ),
				'cover'      => esc_html__( 'Cover', 'bricksable' ),
				'none'       => esc_html__( 'None', 'bricksable' ),
				'scale-down' => esc_html__( 'Scale down', 'bricksable' ),
				'fill'       => esc_html__( 'Fill', 'bricksable' ),
			),
			'css'         => array(
				array(
					'property' => 'object-fit',
					'selector' => 'img.ba-card-image',
				),
			),
			'inline'      => true,
			'default'     => 'cover',
			'placeholder' => esc_html__( 'Cover', 'bricksable' ),
		);

		$this->controls['_objectPosition'] = array(
			'tab'            => 'content',
			'group'          => 'image',
			'label'          => esc_html__( 'Object position', 'bricksable' ),
			'type'           => 'text',
			'css'            => array(
				array(
					'property' => 'object-position',
					'selector' => 'img.ba-card-image',
				),
			),
			'inline'         => true,
			'hasDynamicData' => false,
		);

		$this->controls['imagePosition'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'label'       => esc_html__( 'Image Position', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'row'         => esc_html__( 'Left', 'bricksable' ),
				'column'      => esc_html__( 'Top', 'bricksable' ),
				'row-reverse' => esc_html__( 'Right', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'Top', 'bricksable' ),
			'default'     => 'column',
			'description' => esc_html__( 'Adjust the width and height of your card image from the Style tab.', 'bricksable' ),
			'breakpoints' => true,
			'css'         => array(
				array(
					'property' => 'flex-direction',
					'value'    => '%s',
				),
			),
		);

		// Alt text.
		$this->controls['altText'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Custom alt text', 'bricksable' ),
			'type'     => 'text',
			'inline'   => true,
			'rerender' => false,
			'required' => array( 'image', '!=', '' ),
		);

		$this->controls['showTitle'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Show title', 'bricksable' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'image', '!=', '' ),
		);

		// Badge.
		$this->controls['badgeSeparator'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'type'  => 'separator',
			'label' => esc_html__( 'Badge Text', 'bricksable' ),
		);

		$this->controls['badge'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'type'        => 'text',
			'default'     => esc_html__( 'Badge', 'bricksable' ),
			'placeholder' => esc_html__( 'Badge Text', 'bricksable' ),
		);

		$this->controls['badgePosition'] = array(
			'tab'         => 'style',
			'group'       => 'badge_styling',
			'label'       => esc_html__( 'Badge Position', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'top-right'     => esc_html__( 'Top Right', 'bricksable' ),
				'top-center'    => esc_html__( 'Top Center', 'bricksable' ),
				'top-left'      => esc_html__( 'Top Left', 'bricksable' ),
				'center-right'  => esc_html__( 'Center Right', 'bricksable' ),
				'center'        => esc_html__( 'Center', 'bricksable' ),
				'center-left'   => esc_html__( 'Center Left', 'bricksable' ),
				'bottom-right'  => esc_html__( 'Bottom Right', 'bricksable' ),
				'bottom-center' => esc_html__( 'Bottom Center', 'bricksable' ),
				'bottom-left'   => esc_html__( 'Bottom Left', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'Top Right', 'bricksable' ),
			'default'     => 'top-right',
			'breakpoints' => true,
			'required'    => array(
				array( 'badge', '!=', '' ),
				array( 'badgeCustomPosition', '=', false ),
			),
		);

		$this->controls['badgeCustomPosition'] = array(
			'tab'      => 'style',
			'group'    => 'badge_styling',
			'label'    => esc_html__( 'Custom Badge Position', 'bricksable' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'badge', '!=', '' ),
		);

		$this->controls['badgeCustomTopPosition'] = array(
			'tab'      => 'style',
			'group'    => 'badge_styling',
			'label'    => esc_html__( 'Top (%)', 'bricksable' ),
			'type'     => 'number',
			'unit'     => '%',
			'min'      => 0,
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'top',
					'selector' => '.ba-card-badge-custom-position',
				),
			),
			'required' => array(
				array( 'badge', '!=', '' ),
				array( 'badgeCustomPosition', '=', true ),
			),
		);

		$this->controls['badgeCustomLeftPosition'] = array(
			'tab'      => 'style',
			'group'    => 'badge_styling',
			'label'    => esc_html__( 'Left (%)', 'bricksable' ),
			'type'     => 'number',
			'unit'     => '%',
			'min'      => 0,
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'left',
					'selector' => '.ba-card-badge-custom-position',
				),
			),
			'required' => array(
				array( 'badge', '!=', '' ),
				array( 'badgeCustomPosition', '=', true ),
			),
		);

		$this->controls['badgeBackgroundColor'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Badge Background color', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-card-badge',
				),
			),
			'default'  => '#ffffff',
			'required' => array( 'badge', '!=', '' ),
		);

		$this->controls['badgeTypography'] = array(
			'tab'      => 'style',
			'group'    => 'badge_styling',
			'label'    => esc_html__( 'Badge Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-card-badge',
				),
			),
			'inline'   => true,
			'popup'    => true,
			'default'  => array(
				'font-size' => '12px',
			),
			'required' => array( 'badge', '!=', '' ),
		);

		$this->controls['badgeBorder'] = array(
			'tab'     => 'style',
			'group'   => 'badge_styling',
			'label'   => esc_html__( 'Border', 'bricksable' ),
			'type'    => 'border',
			'css'     => array(
				array(
					'property' => 'border',
					'selector' => '.ba-card-badge',
				),
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'radius' => array(
					'top'    => 50,
					'right'  => 50,
					'bottom' => 50,
					'left'   => 50,
				),
			),
		);

		$this->controls['badgeBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'badge_styling',
			'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-card-badge',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['badgeMargin'] = array(
			'tab'      => 'style',
			'group'    => 'badge_styling',
			'label'    => esc_html__( 'Margin', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-card-badge',
				),
			),
			'required' => array( 'badge', '!=', '' ),
		);

		$this->controls['badgePadding'] = array(
			'tab'      => 'style',
			'group'    => 'badge_styling',
			'label'    => esc_html__( 'Padding', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-card-badge',
				),
			),
			'default'  => array(
				'top'    => '7px',
				'right'  => '15px',
				'bottom' => '7px',
				'left'   => '15px',
			),
			'required' => array( 'badge', '!=', '' ),
		);

		// Image Styling.
		$this->controls['imageHeight'] = array(
			'tab'     => 'style',
			'group'   => 'image_styling',
			'label'   => esc_html__( 'Height in (px)', 'bricksable' ),
			'type'    => 'number',
			'unit'    => '',
			'min'     => 50,
			'inline'  => true,
			'css'     => array(
				array(
					'property' => 'height',
					'selector' => '.ba-card-image-wrapper',
				),
			),
			'default' => '240px',
		);

		$this->controls['imageWidth'] = array(
			'tab'      => 'style',
			'group'    => 'image_styling',
			'label'    => esc_html__( 'Width in (%)', 'bricksable' ),
			'type'     => 'number',
			'min'      => 0,
			'max'      => 70,
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'flex-basis',
					'selector' => '&:not(.ba-card-image-position-column) .ba-card-image-wrapper',
				),
				array(
					'property' => 'flex-basis',
					'selector' => '.ba-card-body-wrapper',
					'value'    => 'calc(100% - %s)',
				),
			),
			'default'  => '50%',
			'required' => array( 'imagePosition', '!=', 'column' ),
		);

		$this->controls['imageBorder'] = array(
			'tab'    => 'style',
			'group'  => 'image_styling',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-card-image-wrapper .ba-card-image',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['imageBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'image_styling',
			'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-card-image-wrapper .ba-card-image',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['imageFilters'] = array(
			'tab'    => 'style',
			'group'  => 'image_styling',
			'label'  => esc_html__( 'CSS filters', 'bricksable' ),
			'type'   => 'filters',
			'inline' => true,
			'css'    => array(
				array(
					'property' => 'filter',
					'selector' => '.css-filter',
				),
			),
		);

		$this->controls['imageMargin'] = array(
			'tab'   => 'style',
			'group' => 'image_styling',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-card-image-wrapper',
				),
			),
		);

		$this->controls['imagePadding'] = array(
			'tab'   => 'style',
			'group' => 'image_styling',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-card-image-wrapper',
				),
			),
		);

		$this->controls['titleSeparator'] = array(
			'tab'      => 'content',
			'group'    => 'content',
			'type'     => 'separator',
			'label'    => esc_html__( 'Title', 'bricksable' ),
			'required' => array( 'nestable', '=', '' ),
		);

		// Title.
		$this->controls['title'] = array(
			'tab'         => 'content',
			'group'       => 'content',
			'type'        => 'text',
			'default'     => esc_html__( 'I am a heading', 'bricksable' ),
			'placeholder' => esc_html__( 'Here goes my heading ..', 'bricksable' ),
			'required'    => array( 'nestable', '=', '' ),
		);

		$this->controls['tag'] = array(
			'tab'         => 'content',
			'group'       => 'content',
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
			'required'    => array( 'nestable', '=', '' ),
		);

		$this->controls['customTag'] = array(
			'tab'         => 'content',
			'group'       => 'content',
			'label'       => esc_html__( 'Custom tag', 'bricksable' ),
			'type'        => 'text',
			'inline'      => true,
			'placeholder' => 'div',
			'required'    => array( 'tag', '=', 'custom' ),
		);

		$this->controls['titleTypography'] = array(
			'tab'    => 'style',
			'group'  => 'title_styling',
			'label'  => esc_html__( 'Title Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-card-title',
				),
			),
			'inline' => false,
			'popup'  => false,
		);

		$this->controls['titleMargin'] = array(
			'tab'   => 'style',
			'group' => 'title_styling',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-card-title',
				),
			),
		);

		$this->controls['titlePadding'] = array(
			'tab'   => 'style',
			'group' => 'title_styling',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-card-title',
				),
			),
		);

		// Subhead.
		$this->controls['subheadSeparator'] = array(
			'tab'      => 'content',
			'group'    => 'content',
			'type'     => 'separator',
			'label'    => esc_html__( 'Subhead', 'bricksable' ),
			'required' => array( 'nestable', '=', '' ),
		);
		$this->controls['subhead']          = array(
			'tab'         => 'content',
			'group'       => 'content',
			'type'        => 'text',
			'default'     => esc_html__( 'Subhead', 'bricksable' ),
			'placeholder' => esc_html__( 'Here goes my subhead ..', 'bricksable' ),
			'required'    => array( 'nestable', '=', '' ),
		);

		$this->controls['subheadTypography'] = array(
			'tab'    => 'style',
			'group'  => 'subhead_styling',
			'label'  => esc_html__( 'Subhead Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-card-subhead',
				),
			),
			'inline' => false,
			'popup'  => false,
		);

		$this->controls['subheadMargin'] = array(
			'tab'   => 'style',
			'group' => 'subhead_styling',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-card-subhead',
				),
			),
		);

		$this->controls['subheadPadding'] = array(
			'tab'   => 'style',
			'group' => 'subhead_styling',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-card-subhead',
				),
			),
		);

		// Content Description.
		$this->controls['contentSeparator'] = array(
			'tab'      => 'content',
			'group'    => 'content',
			'type'     => 'separator',
			'label'    => esc_html__( 'Content', 'bricksable' ),
			'required' => array( 'nestable', '=', '' ),
		);

		$this->controls['content'] = array(
			'tab'      => 'content',
			'group'    => 'content',
			'type'     => 'editor',
			'default'  => '<p>' . esc_html__( 'Here goes your text for the card...', 'bricksable' ) . '</p>',
			'required' => array( 'nestable', '=', '' ),
		);

		$this->controls['bodyAlign'] = array(
			'tab'   => 'style',
			'group' => 'content_wrapper_styling',
			'label' => esc_html__( 'Align', 'bricksable' ),
			'type'  => 'align-items',
			'css'   => array(
				array(
					'property' => 'align-items',
					'selector' => '',
				),
			),
		);

		$this->controls['contentTypography'] = array(
			'tab'    => 'style',
			'group'  => 'content_styling',
			'label'  => esc_html__( 'Content Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-card-content',
				),
			),
			'inline' => false,
			'popup'  => false,
		);

		$this->controls['contentMargin'] = array(
			'tab'   => 'style',
			'group' => 'content_styling',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-card-content',
				),
			),
		);

		$this->controls['contentPadding'] = array(
			'tab'   => 'style',
			'group' => 'content_styling',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-card-content',
				),
			),
		);

		// Body.
		$this->controls['bodyMargin'] = array(
			'tab'   => 'style',
			'group' => 'body_styling',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-card-body-wrapper',
				),
			),
		);

		$this->controls['bodyPadding'] = array(
			'tab'     => 'style',
			'group'   => 'body_styling',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-card-body-wrapper',
				),
			),
			'default' => array(
				'top'    => '20px',
				'right'  => '20px',
				'bottom' => '20px',
				'left'   => '20px',
			),
		);

		$this->controls['bodyDirection'] = array(
			'tab'     => 'style',
			'group'   => 'body_styling',
			'label'   => esc_html__( 'Direction', 'bricksable' ),
			'type'    => 'justify-content',
			'css'     => array(
				array(
					'property' => 'justify-content',
					'selector' => '.ba-card-body-wrapper',
				),
			),
			'exclude' => array(
				'space-between',
				'space-around',
				'space-evenly',
			),
		);

		// Button.
		$this->controls['button_text'] = array(
			'type'        => 'text',
			'tab'         => 'content',
			'group'       => 'button',
			'default'     => esc_html__( 'I am a button', 'bricksable' ),
			'placeholder' => esc_html__( 'I am a button', 'bricksable' ),
		);

		$this->controls['button_tag'] = array(
			'tab'            => 'content',
			'group'          => 'button',
			'label'          => esc_html__( 'HTML tag', 'bricksable' ),
			'type'           => 'text',
			'hasDynamicData' => false,
			'inline'         => true,
			'placeholder'    => 'span',
			'required'       => array( 'link', '=', '' ),
		);

		$this->controls['buttonFullwidth'] = array(
			'tab'     => 'content',
			'group'   => 'button',
			'label'   => esc_html__( 'Fullwidth', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		// Link.
		$this->controls['linkSeparator'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'label' => esc_html__( 'Link', 'bricksable' ),
			'type'  => 'separator',
		);

		$this->controls['link'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'label' => esc_html__( 'Link type', 'bricksable' ),
			'type'  => 'link',
		);

		// Icon.
		$this->controls['iconSeparator'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'label' => esc_html__( 'Icon', 'bricksable' ),
			'type'  => 'separator',
		);

		$this->controls['icon'] = array(
			'tab'   => 'content',
			'group' => 'button',
			'label' => esc_html__( 'Icon', 'bricksable' ),
			'type'  => 'icon',
		);

		$this->controls['iconTypography'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => 'i',
				),
			),
			'required' => array( 'icon.icon', '!=', '' ),
		);

		$this->controls['iconPosition'] = array(
			'label'       => esc_html__( 'Position', 'bricksable' ),
			'type'        => 'select',
			'tab'         => 'content',
			'group'       => 'button',
			'options'     => $this->control_options['iconPosition'],
			'inline'      => true,
			'placeholder' => esc_html__( 'Right', 'bricksable' ),
			'required'    => array( 'icon', '!=', '' ),
		);

		$this->controls['iconGap'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Gap', 'bricksable' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'gap',
				),
			),
			'required' => array( 'icon', '!=', '' ),
		);

		$this->controls['iconSpace'] = array(
			'tab'      => 'content',
			'group'    => 'button',
			'label'    => esc_html__( 'Space between', 'bricksable' ),
			'type'     => 'checkbox',
			'css'      => array(
				array(
					'property' => 'justify-content',
					'value'    => 'space-between',
				),
			),
			'required' => array( 'icon', '!=', '' ),
		);

		$this->controls['buttonAlign'] = array(
			'tab'     => 'style',
			'group'   => 'button_styling',
			'label'   => esc_html__( 'Alignment', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => 'stretch',
			'css'     => array(
				array(
					'property' => 'align-self',
					'selector' => '.bricks-button',
				),
			),
			'inline'  => true,
		);

		$this->controls['buttonMargin'] = array(
			'tab'     => 'style',
			'group'   => 'button_styling',
			'label'   => esc_html__( 'Margin', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'margin',
					'selector' => '.bricks-button',
				),
			),
			'default' => array(
				'top' => '20px',
			),
		);

		$this->controls['buttonPadding'] = array(
			'tab'     => 'style',
			'group'   => 'button_styling',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.bricks-button',
				),
			),
			'default' => array(
				'top'    => '.5em',
				'right'  => '1em',
				'bottom' => '.5em',
				'left'   => '1em',
			),
		);

		$this->controls['buttonTypography'] = array(
			'tab'    => 'style',
			'group'  => 'button_styling',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'typography',
					'selector' => '.bricks-button',
				),
			),
			'inline' => true,
			'popup'  => true,
		);

		$this->controls['buttonBackground'] = array(
			'tab'     => 'style',
			'group'   => 'button_styling',
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
					'selector' => '.bricks-button',
				),
			),
		);

		$this->controls['buttonBorder'] = array(
			'tab'    => 'style',
			'group'  => 'button_styling',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.bricks-button',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['buttonBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'button_styling',
			'label'  => esc_html__( ' BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.bricks-button',
				),
			),
			'inline' => true,
			'small'  => true,
		);
	}
	// Methods: Frontend-specific.
	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-card' );
	}

	public function get_normalized_image_settings( $settings ) {
		if ( empty( $settings['image'] ) ) {
			return array(
				'id'   => 0,
				'url'  => false,
				'size' => BRICKS_DEFAULT_IMAGE_SIZE,
			);
		}

		$image = $settings['image'];

		// Size.
		$image['size'] = empty( $image['size'] ) ? BRICKS_DEFAULT_IMAGE_SIZE : $settings['image']['size'];

		// Image ID or URL from dynamic data.
		if ( ! empty( $image['useDynamicData'] ) ) {
			$images = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', array( 'size' => $image['size'] ) );

			if ( ! empty( $images[0] ) ) {
				if ( is_numeric( $images[0] ) ) {
					$image['id'] = $images[0];
				} else {
					$image['url'] = $images[0];
				}
			}
			// No dynamic data image found (@since 1.6).
			else {
				return;
			}
		}

		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// If External URL, $image['url'] is already set.
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		} else {
			// Parse dynamic data in the external URL.
			$image['url'] = $this->render_dynamic_data( $image['url'] );
		}

		return $image;
	}

	public function render() {
		$settings    = $this->settings;
		$element_id  = $this->get_element_attribute_id();
		$breakpoints = array();
		$nestable    = isset( $settings['nestable'] ) ? true : false;

		$image_position = isset( $settings['imagePosition'] ) ? 'ba-card-image-position-' . $settings['imagePosition'] : '';
		foreach ( \Bricks\Breakpoints::$breakpoints as $key => $breakpoint ) {
			$setting_key      = 'desktop' === $breakpoint['key'] ? 'imagePosition' : "imagePosition:{$breakpoint['key']}";
			$breakpoint_width = ! empty( $breakpoint['width'] ) ? $breakpoint['width'] : false;
			$setting_value    = ! empty( $settings[ $setting_key ] ) ? "ba-card-image-{$breakpoint['key']}-" . $settings[ $setting_key ] : false;
			$breakpoints[]    = $setting_value;
		}

		if ( ! empty( $breakpoints ) ) {
			foreach ( $breakpoints as $key => $value ) {
				if ( false === $value ) {
					unset( $breakpoints[ $key ] );
				}
			}
			$this->set_attribute( '_root', 'class', array( esc_attr( $image_position ), esc_attr( implode( ' ', $breakpoints ) ) ) );
		}

		$output = "<div {$this->render_attributes( '_root' )}>";

		// Image.
		$image      = $this->get_normalized_image_settings( $settings );
		$image_id   = isset( $image['id'] ) ? $image['id'] : '';
		$image_url  = isset( $image['url'] ) ? $image['url'] : '';
		$image_size = isset( $image['size'] ) ? $image['size'] : '';
		// STEP: Dynamic data image not found: Show placeholder text.
		if ( ! empty( $settings['image']['useDynamicData'] ) && ! $image ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'Dynamic data is empty.', 'bricksable' ),
				)
			);
		}

		$image_placeholder_url = \Bricks\Builder::get_template_placeholder_image();
		// Check: No image selected: No image ID provided && not a placeholder URL.
		if ( ! isset( $image['external'] ) && ! $image_id && ! $image_url && $image_url !== $image_placeholder_url ) {
			return $this->render_element_placeholder( array( 'title' => esc_html__( 'No image selected.', 'bricksable' ) ) );
		}
		// Check: Image with ID doesn't exist.
		if ( ! isset( $image['external'] ) && ! $image_url ) {
			return $this->render_element_placeholder( array( 'title' => sprintf( esc_html__( 'Image ID (%s) no longer exist. Please select another image.', 'bricksable' ), $image_id ) ) );
		}

		$has_html_tag = isset( $settings['imageTag'] );

		// Add _root attributes to outermost tag.
		if ( $has_html_tag ) {
			$this->set_attribute( 'image_wrapper', 'class', 'ba-card-image-wrapper' );

			$output .= "<{$settings['imageTag']} {$this->render_attributes( 'image_wrapper' )}>";
		}

		$this->set_attribute( 'img', 'class', array( 'css-filter', 'ba-card-image' ) );

		$this->set_attribute( 'img', 'class', "size-$image_size" );

		// Check for alternartive "Alt Text" setting.
		if ( ! empty( $settings['altText'] ) ) {
			$this->set_attribute( 'img', 'alt', esc_attr( $settings['altText'] ) );
		}

		// Show image 'title' attribute.
		if ( isset( $settings['showTitle'] ) ) {
			$image_title = $image_id ? get_the_title( $image_id ) : false;

			if ( $image_title ) {
				$this->set_attribute( 'img', 'title', esc_attr( $image_title ) );
			}
		}

		// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
		if ( $image_id ) {
			$image_attributes = array();

			if ( ! $has_html_tag ) {
				foreach ( $this->attributes['_root'] as $key => $value ) {
					$image_attributes[ $key ] = is_array( $value ) ? join( ' ', $value ) : $value;
				}
			}

			foreach ( $this->attributes['img'] as $key => $value ) {
				if ( isset( $image_attributes[ $key ] ) ) {
					$image_attributes[ $key ] .= ' ' . ( is_array( $value ) ? join( ' ', $value ) : $value );
				} else {
					$image_attributes[ $key ] = is_array( $value ) ? join( ' ', $value ) : $value;
				}
			}

			// Merge custom attributes with img attributes.
			$custom_attributes = $this->get_custom_attributes( $settings );
			$image_attributes  = array_merge( $image_attributes, $custom_attributes );

			$output .= wp_get_attachment_image( $image_id, $image_size, false, $image_attributes );
		} elseif ( $image_url ) {
			if ( ! $has_html_tag && ! $link ) {
				foreach ( $this->attributes['_root'] as $key => $value ) {
					$this->attributes['img'][ $key ] = $value;
				}
			}

			$this->set_attribute( 'img', 'src', $image_url );

			$output .= "<img {$this->render_attributes( 'img', true )}>";
		}
		// Image Tag.
		if ( $has_html_tag ) {
			// Badge.
			$badge_custom_position = isset( $settings['badgeCustomPosition'] ) ? $settings['badgeCustomPosition'] : false;

			$breakpoints     = array();
			$get_breakpoints = array();
			foreach ( \Bricks\Breakpoints::$breakpoints as $key => $breakpoint ) {
				$setting_key       = 'desktop' === $breakpoint['key'] ? 'badgePosition' : "badgePosition:{$breakpoint['key']}";
				$breakpoint_width  = ! empty( $breakpoint['width'] ) ? $breakpoint['width'] : false;
				$setting_value     = ! empty( $settings[ $setting_key ] ) ? "ba-card-badge-{$settings[ $setting_key ]}-" . $breakpoint['key'] : false;
				$breakpoints[]     = $setting_value;
				$get_breakpoints[] = array(
					$breakpoint['key'] => $breakpoint_width,
				);
			}

			if ( ! empty( $breakpoints ) ) {
				foreach ( $breakpoints as $key => $value ) {
					if ( false === $value ) {
						unset( $breakpoints[ $key ] );
					}
				}
				$this->set_attribute( 'badge', 'class', array( 'ba-card-badge', false === $badge_custom_position ? esc_attr( implode( ' ', $breakpoints ) ) : '', true === $badge_custom_position ? 'ba-card-badge-custom-position' : '' ) );
			}
			/*
			For future use.
			if ( ! empty( $get_breakpoints ) ) {
				foreach ( $get_breakpoints as $key => $value ) {
					foreach ( $get_breakpoints[ $key ] as $key => $value ) {
						$setting_value = ! empty( $key ) ? "data-ba-card-breakpoints-{$key}={$value}" : false;
					}
					$this->set_attribute( 'badge', $setting_value );
				}
			}*/
			if ( ! empty( $settings['badge'] ) ) {
				$output .= "<div {$this->render_attributes( 'badge' )}>";

				$output .= $settings['badge'];

				$output .= '</div>';
			}
			$output .= "</{$settings['imageTag']}>";
		}
		// Content Wrapper.
		$this->set_attribute( 'content_wrapper', 'class', 'ba-card-body-wrapper' );
		$output .= "<div {$this->render_attributes( 'content_wrapper' )}>";
		if ( ! $nestable ) {

			// Title & Content.
			$this->set_attribute( 'title', 'class', 'ba-card-title' );

			$output .= "<{$this->tag} {$this->render_attributes( 'title' )}>";

			if ( ! empty( $settings['title'] ) ) {
				$output .= $settings['title'];
			}
			$output .= "</{$this->tag}>";

			// Subhead.
			$this->set_attribute( 'subhead', 'class', 'ba-card-subhead' );

			$output .= "<div {$this->render_attributes( 'subhead' )}>";

			if ( ! empty( $settings['subhead'] ) ) {
				$output .= $settings['subhead'];
			}
			$output .= '</div>';

			$this->set_attribute( 'content', 'class', 'ba-card-content' );

			$content = ! empty( $settings['content'] ) ? $settings['content'] : false;
			$content = $this->render_dynamic_data( $content );
			$content = \Bricks\Helpers::parse_editor_content( $content );

			if ( $content ) {
				$output .= "<div {$this->render_attributes( 'content' )}>{$content}</div>";
			}

			// Button.
			$button_tag       = ! empty( $settings['button_tag'] ) ? $settings['button_tag'] : 'a';
			$button_fullwidth = isset( $settings['buttonFullwidth'] ) && true === $settings['buttonFullwidth'] ? 'ba-card-button-fullwidth' : '';
			$this->set_attribute( 'button', 'class', array( 'bricks-button', 'bricks-background-primary', $button_fullwidth ) );

			if ( ! empty( $settings['link'] ) ) {
				$this->set_link_attributes( 'button', $settings['link'] );
			}

			if ( ! empty( $settings['button_text'] ) ) {

				$output .= "<{$button_tag} {$this->render_attributes( 'button' )}>";

				$icon          = ! empty( $settings['icon'] ) ? self::render_icon( $settings['icon'] ) : false;
				$icon_position = ! empty( $settings['iconPosition'] ) ? $settings['iconPosition'] : 'right';

				if ( $icon && 'left' === $icon_position ) {
					$output .= $icon;
				}

				if ( ! empty( $settings['button_text'] ) ) {
					$output .= trim( $settings['button_text'] );
				}

				if ( $icon && 'right' === $icon_position ) {
					$output .= $icon;
				}

				$output .= "</{$button_tag}>";
			}
		} elseif ( method_exists( '\Bricks\Frontend', 'render_children' ) ) {
				$output .= \Bricks\Frontend::render_children( $this );
		}
		// Content Wrapper.
		$output .= '</div>';
		// Root.
		$output .= '</div>';

		//phpcs:ignore
		echo $output;
	}

	public function generate_inline_css( $settings = array(), $breakpoint = '' ) {
		$settings        = $this->settings;
		$element_id      = $this->get_element_attribute_id();
		$breakpoints     = array();
		$card_inline_css = '';

		foreach ( \Bricks\Breakpoints::$breakpoints as $key => $breakpoint ) {
			$setting_key      = 'desktop' === $breakpoint['key'] ? 'imagePosition' : "imagePosition:{$breakpoint['key']}";
			$breakpoint_width = ! empty( $breakpoint['width'] ) ? $breakpoint['width'] : false;
			$setting_value    = ! empty( $settings[ $setting_key ] ) ? "ba-card-image-{$breakpoint['key']}-" . $settings[ $setting_key ] : false;
			$breakpoints[]    = $setting_value;

			if ( ! empty( $breakpoints ) ) {
				$card_inline_css .= "@media (max-width: {$breakpoint_width}px) {\n";
				if ( ! empty( $setting_value ) ) {
					$card_inline_css .= "#{$element_id}.{$setting_value} {\n";
					switch ( $settings[ $setting_key ] ) {
						case 'left':
							$card_inline_css .= 'flex-direction: row;';
							break;
						case 'right':
							$card_inline_css .= 'flex-direction: row-reverse;';
							break;
						default:
							$card_inline_css .= 'flex-direction: column;';
					}
					$card_inline_css .= '}';
				}
				$card_inline_css .= '}';
			}
		}
		return $card_inline_css;
	}
}
