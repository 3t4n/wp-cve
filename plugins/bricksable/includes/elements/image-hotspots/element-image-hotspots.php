<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Image_HotSpots extends \Bricks\Element {
	public $category     = 'bricksable';
	public $name         = 'ba-image-hotspots';
	public $icon         = 'ti-eye';
	public $css_selector = '.ba-image-hotspots';
	public $scripts      = array( 'bricksableImageHotspots' );
	public $nestable     = true;

	public function get_label() {
		return esc_html__( 'Image Hotspots', 'bricksable' );
	}

	public function get_keywords() {
		return array(
			esc_html__( 'Bricksable', 'bricksable' ),
		);
	}
	public function set_control_groups() {
		// Image.
		$this->control_groups['image'] = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		// Hotspot.
		$this->control_groups['hotspot'] = array(
			'title' => esc_html__( 'Hotspot Items', 'bricksable' ),
			'tab'   => 'content',
		);
		// Hotspot.
		$this->control_groups['item'] = array(
			'title' => esc_html__( 'Hotspot Item', 'bricksable' ),
			'tab'   => 'content',
		);
		// Hotspot Settings.
		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		// Hotspot Settings.
		$this->control_groups['animation'] = array(
			'title' => esc_html__( 'Animation', 'bricksable' ),
			'tab'   => 'content',
		);
		// Tooltip Settings.
		$this->control_groups['tooltip'] = array(
			'title' => esc_html__( 'Tooltip Styling', 'bricksable' ),
			'tab'   => 'content',
		);
	}

	// Set builder controls.
	public function set_controls() {

		// Image.
		$this->controls['image'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'label' => esc_html__( 'Image', 'bricksable' ),
			'type'  => 'image',
		);

		$this->controls['isSvg'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Image in SVG format?', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'image', '!=', '' ),
		);

		$this->controls['height'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Height', 'bricksable' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'selector' => '.ba-image-hotspot-img-svg',
					'property' => 'height',
				),
			),
			'required' => array( 'isSvg', '!=', '' ),
		);

		$this->controls['width'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Width', 'bricksable' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'selector' => '.ba-image-hotspot-img-svg',
					'property' => 'width',
				),
			),
			'default'  => '100%',
			'required' => array( 'isSvg', '!=', '' ),
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

		// 'loading' attribute (@ssince 1.6.2)
		$this->controls['loading'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'label'       => esc_html__( 'Loading', 'bricksable' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => array(
				'eager' => 'eager',
				'lazy'  => 'lazy',
			),
			'placeholder' => 'lazy',
		);

		// 'title' attribute (@since 1.6.2)
		$this->controls['showTitle'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Show Title', 'bricksable' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'image', '!=', '' ),
		);

		$this->controls['stretch'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'label' => esc_html__( 'Stretch', 'bricksable' ),
			'type'  => 'checkbox',
			'css'   => array(
				array(
					'property' => 'width',
					'selector' => '',
					'value'    => '100%',
				),
				array(
					'property' => 'width',
					'selector' => '.ba-image-hotspot-img',
					'value'    => '100%',
				),
			),
		);

		// Hotspot Items.
		$this->controls['hotspot'] = array(
			'tab'           => 'content',
			'group'         => 'hotspot',
			'placeholder'   => esc_html__( 'Hotspot', 'bricksable' ),
			'type'          => 'repeater',
			'selector'      => '.ba-image-hotspot-item',
			'titleProperty' => 'title',
			'fields'        => array(
				// title.
				'titleSeparator'         => array(
					'label'  => esc_html__( 'Title', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'title'                  => array(
					'label'         => esc_html__( 'Title', 'bricksable' ),
					'type'          => 'text',
					'spellcheck'    => true,
					'inlineEditing' => true,
				),
				'titleTypography'        => array(
					'label'       => esc_html__( 'Typography', 'bricksable' ),
					'type'        => 'typography',
					'css'         => array(
						array(
							'property' => 'font',
							'selector' => '.ba-image-hotspot-title',
						),
					),
					'exclude'     => array(
						'text-align',
					),
					'placeholder' => array(
						'font-size' => 14,
					),
					'required'    => array( 'title', '!=', '' ),
				),
				'titleMargin'            => array(
					'tab'      => 'content',
					'label'    => esc_html__( 'Margin', 'bricksable' ),
					'type'     => 'dimensions',
					'css'      => array(
						array(
							'property' => 'margin',
							'selector' => '.ba-image-hotspot-title',
						),
					),
					'default'  => array(
						'top'    => 0,
						'right'  => 0,
						'bottom' => 0,
						'left'   => '7px',
					),
					'required' => array( 'title', '!=', '' ),
				),
				'titlePadding'           => array(
					'tab'      => 'content',
					'label'    => esc_html__( 'Padding', 'bricksable' ),
					'type'     => 'dimensions',
					'css'      => array(
						array(
							'property' => 'padding',
							'selector' => '.ba-image-hotspot-title',
						),
					),
					'required' => array( 'title', '!=', '' ),
				),
				'iconSeparator'          => array(
					'label'  => esc_html__( 'Icon', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'icon'                   => array(
					'label'    => esc_html__( 'Icon', 'bricksable' ),
					'type'     => 'icon',
					'required' => array( 'use_icon_image', '!=', true ),
				),
				'iconTypography'         => array(
					'label'    => esc_html__( 'Icon Typography', 'bricksable' ),
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
							'selector' => '.ba-image-hotspot-icon-wrapper i',
						),
					),
					'inline'   => true,
					'small'    => true,
					'required' => array( 'use_icon_image', '!=', true ),
				),

				'use_icon_image'         => array(
					'label'  => esc_html__( 'Use Icon Image', 'bricksable' ),
					'type'   => 'checkbox',
					'inline' => true,
				),

				'icon_image'             => array(
					'label'          => esc_html__( 'Icon Image', 'bricksable' ),
					'type'           => 'image',
					'hasDynamicData' => 'image',
					'required'       => array( 'use_icon_image', '=', true ),
				),
				'icon_imageWidth'        => array(
					'label'       => esc_html__( 'Icon Image Width', 'bricksable' ),
					'type'        => 'number',
					'css'         => array(
						array(
							'property' => 'width',
							'selector' => '.ba-image-hotspot-icon-image',
						),
					),
					'units'       => array(
						'%'  => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
						'px' => array(
							'min'  => 1,
							'max'  => 50,
							'step' => 1,
						),
					),
					'placeholder' => '30px',
					'required'    => array( 'use_icon_image', '=', true ),
				),
				'iconBorder'             => array(
					'label'    => esc_html__( 'Border', 'bricksable' ),
					'type'     => 'border',
					'css'      => array(
						array(
							'property' => 'border',
							'selector' => '.ba-image-hotspot-icon-image',
						),
						array(
							'property' => 'border',
							'selector' => '.ba-image-hotspot-icon-wrapper i',
						),
					),
					'inline'   => true,
					'small'    => true,
					'required' => array( 'use_icon_image', '=', true ),
				),
				'iconBoxShadow'          => array(
					'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
					'type'   => 'box-shadow',
					'css'    => array(
						array(
							'property' => 'box-shadow',
							'selector' => '.ba-image-hotspot-icon-image',
						),
						array(
							'property' => 'box-shadow',
							'selector' => '.ba-image-hotspot-icon-wrapper i',
						),
					),
					'inline' => true,
					'small'  => true,
				),
				'placementSeparator'     => array(
					'label'  => esc_html__( 'Placement', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'tooltipPlacement'       => array(
					'tab'     => 'content',
					'group'   => 'hotspot',
					'label'   => esc_html__( 'Tooltip Placement', 'bricksable' ),
					'type'    => 'select',
					'options' => array(
						'top'    => esc_html__( 'Top', 'bricksable' ),
						'left'   => esc_html__( 'Left', 'bricksable' ),
						'right'  => esc_html__( 'Right', 'bricksable' ),
						'bottom' => esc_html__( 'Bottom', 'bricksable' ),
						'auto'   => esc_html__( 'Auto', 'bricksable' ),
					),
					'default' => 'auto',
					'inline'  => true,
				),
				'positionSeparator'      => array(
					'label'  => esc_html__( 'Position', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'xPosition'              => array(
					'label'       => esc_html__( 'X Position', 'bricksable' ),
					'type'        => 'number',
					'css'         => array(
						array(
							'property' => 'left',
							// 'selector' => '.ba-image-hotspot-item',
						),
					),
					'units'       => '%',
					'description' => esc_html__( 'Set the horizontal position of this item here.', 'bricksable' ),
				),

				'yPosition'              => array(
					'label'       => esc_html__( 'Y Position', 'bricksable' ),
					'type'        => 'number',
					'units'       => '%',
					'css'         => array(
						array(
							'property' => 'top',
							// 'selector' => '.ba-image-hotspot-item',
						),
					),
					'description' => esc_html__( 'Set the vertical position of this item here.', 'bricksable' ),
				),
				'hotspotStyleSeparator'  => array(
					'tab'    => 'content',
					'group'  => 'item',
					'label'  => esc_html__( 'Styling', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'hotSpotBackground'      => array(
					'label'   => esc_html__( 'Background', 'bricksable' ),
					'type'    => 'background',
					'css'     => array(
						array(
							'property' => 'background',
							'selector' => '.ba-image-hotspot-item-wrapper',
						),
					),
					'exclude' => array(
						'parallax',
						'videoUrl',
						'videoScale',
					),
					'inline'  => true,
					'small'   => true,
				),
				'hotSpotBorder'          => array(
					'label'  => esc_html__( 'Border', 'bricksable' ),
					'type'   => 'border',
					'css'    => array(
						array(
							'property' => 'border',
							'selector' => '.ba-image-hotspot-item-wrapper',
						),
					),
					'inline' => true,
					'small'  => true,
				),
				'hotSpotBoxShadow'       => array(
					'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
					'type'   => 'box-shadow',
					'css'    => array(
						array(
							'property' => 'box-shadow',
							'selector' => '.ba-image-hotspot-item-wrapper',
						),
					),
					'inline' => true,
					'small'  => true,
				),
				'contentSeparator'       => array(
					'label'  => esc_html__( 'Content', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'content'                => array(
					'label'         => esc_html__( 'Tooltip Content', 'bricksable' ),
					'type'          => 'editor',
					'spellcheck'    => true,
					'inlineEditing' => true,
				),
				'linkSeparator'          => array(
					'label'  => esc_html__( 'Link', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'url'                    => array(
					'label'   => esc_html__( 'URL', 'bricksable' ),
					'type'    => 'link',
					'exclude' => array(
						'media',
						'dynamicData',
						'lightboxImage',
						'lightboxVideo',
					),
				),
				'pulseSeparator'         => array(
					'label'  => esc_html__( 'Pulse Animation', 'bricksable' ),
					'type'   => 'separator',
					'inline' => true,
					'small'  => true,
				),
				'usePulse'               => array(
					'label'  => esc_html__( 'Use Pulse Animation', 'bricksable' ),
					'type'   => 'checkbox',
					'inline' => true,
				),
				'pulseBackground'        => array(
					'label'    => esc_html__( 'Background', 'bricksable' ),
					'type'     => 'color',
					'css'      => array(
						array(
							'property' => 'background-color',
							'selector' => '.ba-image-hotspot-pulse::before',
						),
					),
					'inline'   => true,
					'small'    => true,
					'required' => array( 'usePulse', '!=', '' ),
				),
				'pulseBorder'            => array(
					'label'       => esc_html__( 'Border', 'bricksable' ),
					'type'        => 'border',
					'css'         => array(
						array(
							'property' => 'border',
							'selector' => '.ba-image-hotspot-pulse::before',
						),
					),
					'inline'      => true,
					'small'       => true,
					'placeholder' => array(
						'radius' => array(
							'top'    => 50,
							'right'  => 50,
							'bottom' => 50,
							'left'   => 50,
						),
					),
					'required'    => array( 'usePulse', '!=', '' ),
				),
				'pulseAnimationDuration' => array(
					'tab'         => 'content',
					'group'       => 'animation',
					'label'       => esc_html__( 'Animation Duration (ms)', 'bricksable' ),
					'type'        => 'number',
					'css'         => array(
						array(
							'property' => 'animation-duration',
							'selector' => '.ba-image-hotspot-pulse::before',
						),
					),
					'unit'        => 'ms',
					'inline'      => true,
					'placeholder' => '1500',
					'required'    => array( 'usePulse', '!=', '' ),
				),
				'css_id'                 => array(
					'label'         => esc_html__( 'CSS ID', 'bricksable' ),
					'type'          => 'text',
					'spellcheck'    => true,
					'inlineEditing' => true,
				),

				'css_class'              => array(
					'label'         => esc_html__( 'CSS Classes', 'bricksable' ),
					'type'          => 'text',
					'spellcheck'    => true,
					'inlineEditing' => true,
				),

			),
			'default'       => array(
				array(
					'titleTypography' => array(
						'font-size' => 14,
					),
					'titleMargin'     => array(
						'top'    => 0,
						'right'  => 0,
						'bottom' => 0,
						'left'   => '5px',
					),
					'xPosition'       => '40%',
					'yPosition'       => '20%',
					'content'         => esc_html__( 'Here goes your tooltip content', 'bricksable' ),
					'icon'            => array(
						'library' => 'themify',
						'icon'    => 'ti-control-record',
					),
					'iconColor'       => array(
						'hex' => '#363636',
					),
				),
				array(
					'titleTypography' => array(
						'font-size' => 14,
					),
					'titleMargin'     => array(
						'top'    => 0,
						'right'  => 0,
						'bottom' => 0,
						'left'   => '5px',
					),
					'usePulse'        => true,
					'xPosition'       => '80%',
					'yPosition'       => '50%',
					'content'         => esc_html__( 'Here goes your tooltip content', 'bricksable' ),
					'icon'            => array(
						'library' => 'themify',
						'icon'    => 'ti-control-record',
					),
					'iconColor'       => array(
						'hex' => '#363636',
					),
				),
			),
		);

		// Items.
		$this->controls['titleSeparator']  = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Title', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);
		$this->controls['titleTypography'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Typography', 'bricksable' ),
			'type'        => 'typography',
			'css'         => array(
				array(
					'property' => 'font',
					'selector' => '.ba-image-hotspot-title',
				),
			),
			'exclude'     => array(
				'text-align',
			),
			'placeholder' => array(
				'font-size' => 14,
			),
		);

		$this->controls['iconSeparator'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Icon', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);

		$this->controls['iconTypography'] = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Icon Typography', 'bricksable' ),
			'type'    => 'typography',
			'exclude' => array(
				'font-family',
				'font-style',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
				'text-decoration',
			),
			'css'     => array(
				array(
					'property' => 'font',
					'selector' => '.ba-image-hotspot-icon-wrapper i',
				),
			),
			'inline'  => true,
			'small'   => true,
		);

		$this->controls['icon_imageWidth'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Icon Image Width', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '.ba-image-hotspot-icon-image',
				),
			),
			'units'       => array(
				'%'  => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
				'px' => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				),
			),
			'placeholder' => '30px',
		);

		$this->controls['iconBorder'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-image-hotspot-icon-image',
				),
				array(
					'property' => 'border',
					'selector' => '.ba-image-hotspot-icon-wrapper i',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['iconBoxShadow'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-image-hotspot-icon-image',
				),
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-image-hotspot-icon-wrapper i',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['hotspotStyleSeparator'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Styling', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);
		$this->controls['hotSpotBackground']     = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'background',
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-image-hotspot-item-wrapper',
				),
			),
			'exclude' => array(
				'parallax',
				'videoUrl',
				'videoScale',
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'color' => array(
					'hex' => '#ffffff',
				),
			),
		);
		$this->controls['hotSpotBorder']         = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Border', 'bricksable' ),
			'type'    => 'border',
			'css'     => array(
				array(
					'property' => 'border',
					'selector' => '.ba-image-hotspot-item-wrapper',
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

		$this->controls['hotSpotBoxShadow'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-image-hotspot-item-wrapper',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['hotSpotalignItems'] = array(
			'tab'          => 'content',
			'group'        => 'item',
			'label'        => esc_html__( 'Align items', 'bricksable' ),
			'type'         => 'justify-content',
			'css'          => array(
				array(
					'property' => 'align-items',
					'selector' => '.ba-image-hotspot-icon-wrapper',
				),
			),
			'isHorizontal' => false,
			'default'      => 'center',
			'placeholder'  => 'center',
			'exclude'      => array(
				'space-between',
				'space-around',
				'space-evenly',
				'stretch',
			),
		);

		$this->controls['hotspotHeight'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Min Height in px', 'bricksable' ),
			'type'        => 'number',
			'unit'        => 'px',
			'inline'      => true,
			'css'         => array(
				array(
					'property' => 'min-height',
					'selector' => '.ba-image-hotspot-item-wrapper',
				),
			),
			'default'     => 14,
			'placeholder' => '14px',
		);

		$this->controls['hotspotWidth'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Min Width in px', 'bricksable' ),
			'type'        => 'number',
			'unit'        => 'px',
			'inline'      => true,
			'css'         => array(
				array(
					'property' => 'min-width',
					'selector' => '.ba-image-hotspot-item-wrapper',
				),
			),
			'default'     => 14,
			'placeholder' => '14px',
		);

		$this->controls['hotSpotPadding'] = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-image-hotspot-item-wrapper',
				),
			),
			'default' => array(
				'top'    => '8px',
				'right'  => '8px',
				'bottom' => '8px',
				'left'   => '8px',
			),
		);

		// Settings.
		$this->controls['globalToolTipPlacement'] = array(
			'tab'     => 'content',
			'group'   => 'settings',
			'label'   => esc_html__( 'Apply Placement to All Tooltip', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => false,
		);

		$this->controls['globalToolTipPlacementInfo'] = array(
			'tab'     => 'content',
			'group'   => 'settings',
			'content' => esc_html__( 'If enable, this will take priority over individual placement settings.', 'bricksable' ),
			'type'    => 'info',
		);

		$this->controls['tooltipPlacement'] = array(
			'tab'      => 'content',
			'group'    => 'settings',
			'label'    => esc_html__( 'Tooltip Placement', 'bricksable' ),
			'type'     => 'select',
			'options'  => array(
				'top'    => esc_html__( 'Top', 'bricksable' ),
				'left'   => esc_html__( 'Left', 'bricksable' ),
				'right'  => esc_html__( 'Right', 'bricksable' ),
				'bottom' => esc_html__( 'Bottom', 'bricksable' ),
				'auto'   => esc_html__( 'Auto', 'bricksable' ),
			),
			'default'  => 'auto',
			'inline'   => false,
			'required' => array( 'globalToolTipPlacement', '!=', '' ),
		);

		$this->controls['trigger'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Trigger Method', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'click'      => esc_html__( 'Click', 'bricksable' ),
				'mouseenter' => esc_html__( 'Mouse Enter', 'bricksable' ),
				// 'mouseenter_click' => esc_html__( 'Mouse Enter & Click', 'bricksable' ),
				// 'mouseenter_focus' => esc_html__( 'Mouse Enter & Focus', 'bricksable' ),
			),
			'inline'      => false,
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'mouseenter',
			'placeholder' => 'Mouse Enter',
		);

		$this->controls['showArrow'] = array(
			'tab'     => 'content',
			'group'   => 'settings',
			'label'   => esc_html__( 'Show Arrow', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'default' => true,
		);

		$this->controls['followCursor'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Follow Cursor', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( "Determines if the tooltip follows the user's mouse cursor.", 'bricksable' ),
		);

		$this->controls['maxWidth'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Max Width (px)', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'inline'      => true,
			'default'     => 320,
			'placeholder' => '320',
		);

		$this->controls['offsetSkidding'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Offset Skidding (px)', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'inline'      => true,
			'default'     => 0,
			'placeholder' => '0',
		);

		$this->controls['offsetDistance'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Offset Distance (px)', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'inline'      => true,
			'default'     => 15,
			'placeholder' => '15',
		);

		// Animation.
		$this->controls['showDelay'] = array(
			'tab'         => 'content',
			'group'       => 'animation',
			'label'       => esc_html__( 'Transition In Delay (ms)', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'inline'      => true,
			'default'     => 100,
			'placeholder' => '100',
			'description' => esc_html__( 'Delay in ms once a trigger event is fired before the tooltip shows.', 'bricksable' ),
		);

		$this->controls['hideDelay'] = array(
			'tab'         => 'content',
			'group'       => 'animation',
			'label'       => esc_html__( 'Transition Out Delay (ms)', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'inline'      => true,
			'default'     => 100,
			'placeholder' => '100',
			'description' => esc_html__( 'Delay in ms once a trigger event is fired before the tooltip hides.', 'bricksable' ),
		);

		$this->controls['duration'] = array(
			'tab'         => 'content',
			'group'       => 'animation',
			'label'       => esc_html__( 'Duration (ms)', 'bricksable' ),
			'type'        => 'number',
			'unitless'    => true,
			'inline'      => true,
			'default'     => 320,
			'placeholder' => '320',
			'description' => esc_html__( 'Duration in ms of the transition animation.', 'bricksable' ),
		);

		$this->controls['customAnimation'] = array(
			'tab'         => 'content',
			'group'       => 'animation',
			'label'       => esc_html__( 'Animation', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'fade'         => esc_html__( 'Fade', 'bricksable' ),
				'scale'        => esc_html__( 'Scale', 'bricksable' ),
				'shift-away'   => esc_html__( 'Shift Away', 'bricksable' ),
				'shift-toward' => esc_html__( 'Shift Toward', 'bricksable' ),
				'perspective'  => esc_html__( 'Perspective', 'bricksable' ),
			),
			'inline'      => true,
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'fade',
			'placeholder' => 'Fade',
		);

		$this->controls['PulseSeparator'] = array(
			'tab'    => 'content',
			'group'  => 'animation',
			'label'  => esc_html__( 'Pulse Animation', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);
		$this->controls['usePulse']       = array(
			'tab'    => 'content',
			'group'  => 'animation',
			'label'  => esc_html__( 'Apply Pulse to All Hotspots', 'bricksable' ),
			'type'   => 'checkbox',
			'inline' => true,
		);

		$this->controls['usePulseInfo'] = array(
			'tab'     => 'content',
			'group'   => 'animation',
			'content' => esc_html__( 'If enable, it will be applied to all hotspots. To customize individual hotspots, please go to the settings of each hotspot item.', 'bricksable' ),
			'type'    => 'info',
		);

		$this->controls['pulseAnimationDuration'] = array(
			'tab'         => 'content',
			'group'       => 'animation',
			'label'       => esc_html__( 'Animation Duration (ms)', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'animation-duration',
					'selector' => '.ba-image-hotspot-pulse::before',
				),
			),
			'unit'        => 'ms',
			'inline'      => true,
			'placeholder' => '1500',
			'required'    => array( 'usePulse', '!=', '' ),
		);

		$this->controls['pulseBackground'] = array(
			'tab'      => 'content',
			'group'    => 'animation',
			'label'    => esc_html__( 'Background', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-image-hotspot-pulse::before',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'usePulse', '!=', '' ),
		);

		$this->controls['pulseBorder'] = array(
			'tab'         => 'content',
			'group'       => 'animation',
			'label'       => esc_html__( 'Border', 'bricksable' ),
			'type'        => 'border',
			'css'         => array(
				array(
					'property' => 'border',
					'selector' => '.ba-image-hotspot-pulse::before',
				),
			),
			'inline'      => true,
			'small'       => true,
			'placeholder' => array(
				'radius' => array(
					'top'    => 50,
					'right'  => 50,
					'bottom' => 50,
					'left'   => 50,
				),
			),
			'required'    => array( 'usePulse', '!=', '' ),
		);

		// Tooltip.
		$this->controls['tooltipBackground'] = array(
			'tab'     => 'content',
			'group'   => 'tooltip',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'color',
			'css'     => array(
				array(
					'property' => 'background-color',
					'selector' => '&.tippy-box',
				),
				array(
					'property' => 'color',
					'selector' => '&.tippy-box .tippy-arrow',
				),
			),

			'inline'  => true,
			'small'   => true,
			'default' => array(
				'color' => array(
					'hex' => '#333',
				),
			),
		);

		$this->controls['tooltipTypography'] = array(
			'tab'         => 'content',
			'group'       => 'tooltip',
			'label'       => esc_html__( 'Typography', 'bricksable' ),
			'type'        => 'typography',
			'css'         => array(
				array(
					'property' => 'font',
					'selector' => '.tippy-content',
				),
			),
			'exclude'     => array(
				'text-align',
			),
			'placeholder' => array(
				'font-size' => 14,
			),
		);

		$this->controls['tooltipBorder'] = array(
			'tab'     => 'content',
			'group'   => 'tooltip',
			'label'   => esc_html__( 'Border', 'bricksable' ),
			'type'    => 'border',
			'css'     => array(
				array(
					'property' => 'border',
					'selector' => '&.tippy-box',
				),
			),
			'exclude' => array(
				'width',
				'style',
				'color',
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'radius' => array(
					'top'    => 4,
					'right'  => 4,
					'bottom' => 4,
					'left'   => 4,
				),
			),
		);

		$this->controls['tooltipBoxShadow'] = array(
			'tab'     => 'content',
			'group'   => 'tooltip',
			'label'   => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'    => 'box-shadow',
			'css'     => array(
				array(
					'property' => 'box-shadow',
					'selector' => '&.tippy-box',
				),
			),
			'exclude' => array(
				'inset',
			),
			'inline'  => true,
			'small'   => true,
		);

		$this->controls['tooltipPadding'] = array(
			'tab'     => 'content',
			'group'   => 'tooltip',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.tippy-content',
				),
			),
			'default' => array(
				'top'    => '5px',
				'right'  => '9px',
				'bottom' => '5px',
				'left'   => '9px',
			),
		);
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
			} else {
				// No dynamic data image found (@since 1.6).
				return;
			}
		}

		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// If External URL, $image['url'] is already set.
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		} else {
			// Parse dynamic data in the external URL (@since 1.5.7).
			$image['url'] = $this->render_dynamic_data( $image['url'] );
		}

		return $image;
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-image-hotspots' );
		wp_enqueue_script( 'ba-tippy' );
		wp_enqueue_script( 'ba-image-hotspots' );
		if ( isset( $this->settings['customAnimation'] ) ) {
			if ( 'scale' === $this->settings['customAnimation'] ) {
				wp_enqueue_style( 'ba-image-hotspot-scale' );
			} elseif ( 'shift-away' === $this->settings['customAnimation'] ) {
				wp_enqueue_style( 'ba-image-hotspot-shift-away' );
			} elseif ( 'shift-toward' === $this->settings['customAnimation'] ) {
				wp_enqueue_style( 'ba-image-hotspot-shift-toward' );
			} elseif ( 'perspective' === $this->settings['customAnimation'] ) {
				wp_enqueue_style( 'ba-image-hotspot-perspective' );
			}
		}

	}

	public function render() {
		$settings = $this->settings;

		$image      = $this->get_normalized_image_settings( $settings );
		$image_id   = isset( $image['id'] ) ? $image['id'] : '';
		$image_url  = isset( $image['url'] ) ? $image['url'] : '';
		$image_size = isset( $image['size'] ) ? $image['size'] : '';
		// Handle svg image behaviour.
		$image_pathinfo = pathinfo( $image_url );
		$is_image_svg   = isset( $image_pathinfo['extension'] ) ? 'svg' === $image_pathinfo['extension'] : false;

		$output = '';

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
			/* translators: image id */
			return $this->render_element_placeholder( array( 'title' => sprintf( esc_html__( 'Image ID (%s) no longer exist. Please select another image.', 'bricksable' ), $image_id ) ) );
		}

		// Image.
		$this->set_attribute( 'img', 'class', array( 'css-filter', 'ba-image-hotspot-img' ) );
		// Check for alternartive "Alt Text" setting.
		if ( ! empty( $settings['altText'] ) ) {
			$this->set_attribute( 'img', 'alt', esc_attr( $settings['altText'] ) );
		}

		// Set 'loading' attribute: eager or lazy .
		if ( ! empty( $settings['loading'] ) ) {
			$this->set_attribute( 'img', 'loading', esc_attr( $settings['loading'] ) );
		}

		// Show image 'title' attribute.
		if ( isset( $settings['showTitle'] ) ) {
			$image_title = $image_id ? get_the_title( $image_id ) : false;

			if ( $image_title ) {
				$this->set_attribute( 'img', 'title', esc_attr( $image_title ) );
			}
		}

		// SVG.
		if ( $is_image_svg || ( isset( $settings['isSvg'] ) && $settings['isSvg'] ) ) {
			$this->set_attribute( 'img', 'class', array( 'ba-image-hotspot-img-svg' ) );
		}

		// Hotspot Options.
		$hotspot_main_options = array(
			'globalPlacement'  => isset( $settings['globalToolTipPlacement'] ) ? true : false,
			'tooltipPlacement' => isset( $settings['globalToolTipPlacement'] ) ? esc_attr( $settings['tooltipPlacement'] ) : '',
			'arrow'            => isset( $settings['showArrow'] ) ? true : false,
			'followCursor'     => isset( $settings['followCursor'] ) ? true : false,
			'trigger'          => isset( $settings['trigger'] ) ? esc_attr( $settings['trigger'] ) : 'mouseenter',
			'maxWidth'         => isset( $settings['maxWidth'] ) ? esc_attr( $settings['maxWidth'] ) : '320',
			'offset'           => isset( $settings['offsetSkidding'] ) || isset( $settings['offsetDistance'] ) ? array( isset( $settings['offsetSkidding'] ) ? intval( $settings['offsetSkidding'] ) : 0, isset( $settings['offsetDistance'] ) ? intval( $settings['offsetDistance'] ) : 15 ) : '[0,15]',
			'delay'            => isset( $settings['showDelay'] ) || isset( $settings['hideDelay'] ) ? array( isset( $settings['showDelay'] ) ? intval( $settings['showDelay'] ) : 100, isset( $settings['hideDelay'] ) ? intval( $settings['hideDelay'] ) : 100 ) : '[100,100]',
			'duration'         => isset( $settings['duration'] ) ? intval( $settings['duration'] ) : 320,
			'animation'        => isset( $settings['customAnimation'] ) ? esc_attr( $settings['customAnimation'] ) : 'fade',
		);

		$this->set_attribute( '_root', 'data-ba-bricks-image-hot-spot-main-options', wp_json_encode( $hotspot_main_options ) );

		// Root.
		$output .= "<div {$this->render_attributes( '_root' )}>";

		// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
		if ( $image_id ) {
			$image_attributes = array();

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
			foreach ( $this->attributes['_root'] as $key => $value ) {
				$this->attributes['img'][ $key ] = $value;
			}

			$this->set_attribute( 'img', 'src', $image_url );

			$output .= "<img {$this->render_attributes( 'img', true )}>";
		}

		// Global Pulse.
		$use_pulse = isset( $settings['usePulse'] ) && true === $settings['usePulse'] ? true : false;

		$output .= '<div class="ba-image-hotspot-wrapper">';
		// Loop Hotspot.
		$hotspot_output = '';
		foreach ( $settings['hotspot'] as $index => $hotspot_item ) {

			$hotspot_item_classes = array( 'ba-image-hotspot-item' );

			if ( isset( $hotspot_item['css_class'] ) ) {
				$hotspot_item_classes[] = $list_item['css_class'];
			}
			if ( isset( $hotspot_item['css_id'] ) ) {
				$this->set_attribute( "ba-image-hotspot-item-$index", 'id', $hotspot_item['css_id'] );
			}

			$this->set_attribute( "ba-image-hotspot-item-$index", 'class', $hotspot_item_classes );

			// Hotspot Options.
			$hotspot_options = array(
				'placement' => isset( $hotspot_item['tooltipPlacement'] ) ? esc_attr( $hotspot_item['tooltipPlacement'] ) : 'auto',
			);

			$this->set_attribute( "ba-image-hotspot-item-$index", 'data-ba-bricks-image-hot-spot-options', wp_json_encode( $hotspot_options ) );

			// Link.
			$link        = ! empty( $hotspot_item['url'] ) ? $hotspot_item['url'] : false;
			$link_output = '';
			if ( $link ) {
				$link_output = 'a ';
				if ( isset( $hotspot_item['url'] ) ) {
					$this->set_link_attributes( "ba-image-hotspot-item-$index", $hotspot_item['url'] );
				}
			} else {
				$link_output = 'div ';
			}

			// $hotspot_output .= '<div class="repeater-item">';
			$hotspot_output .= '<' . $link_output . $this->render_attributes( "ba-image-hotspot-item-$index" ) . '>';

			if ( true === $use_pulse || isset( $hotspot_item['usePulse'] ) ) {
				$this->set_attribute( "ba-image-hotspot-pulse-$index", 'class', 'ba-image-hotspot-pulse' );
				$hotspot_output .= '<div ' . $this->render_attributes( "ba-image-hotspot-pulse-$index" ) . '></div>';
			}
			// Hotspot Wrapper.
			$this->set_attribute( "hotspot-wrapper-$index", 'class', 'ba-image-hotspot-item-wrapper' );

			$hotspot_output .= '<div ' . $this->render_attributes( "hotspot-wrapper-{$index}" ) . '>';
				// Icon.
				$this->set_attribute( "icon-wrapper-$index", 'class', 'ba-image-hotspot-icon-wrapper' );
				$hotspot_output .= '<div ' . $this->render_attributes( "icon-wrapper-{$index}" ) . '>';
				$icon            = ! empty( $hotspot_item['icon'] ) ? self::render_icon( $hotspot_item['icon'] ) : false;

			if ( $icon && ( ! isset( $hotspot_item['use_icon_image'] ) && ! isset( $hotspot_item['icon_image'] ) ) ) {
				$hotspot_output .= '<span class="icon">';
				$hotspot_output .= $icon; //phpcs:ignore
				$hotspot_output .= '</span>';
			}
			if ( isset( $hotspot_item['use_icon_image'] ) && isset( $hotspot_item['icon_image'] ) ) {
				$image_url  = isset( $hotspot_item['icon_image']['url'] ) ? $hotspot_item['icon_image']['url'] : '';
				$image_id   = isset( $hotspot_item['icon_image']['id'] ) ? $hotspot_item['icon_image']['id'] : '';
				$image_size = isset( $hotspot_item['icon_image']['size'] ) ? $hotspot_item['icon_image']['size'] : '';

				$image_atts           = array();
				$image_atts['id']     = 'image-' . $image_id;
				$item_image_classes   = array( 'ba-image-hotspot-icon-image', 'css-filter' );
				$item_image_classes[] = 'size-' . $image_size;
				$image_atts['class']  = join( ' ', $item_image_classes );

				$this->set_attribute( "img-{$index}", 'class', $item_image_classes );
				$this->set_attribute( "img-$index", 'src', esc_url( $image_url ) );

				if ( $image_id ) {
					$hotspot_output .= wp_get_attachment_image( $image_id, $image_size, false, $image_atts );
				} elseif ( ! empty( $image_url ) ) {
					$hotspot_output .= "<img {$this->render_attributes( "img-$index", true )}>";
				}
			}
			if ( isset( $hotspot_item['title'] ) ) {
				$this->set_attribute( "title-$index", 'class', 'ba-image-hotspot-title' );
				$hotspot_output .= '<span ' . $this->render_attributes( "title-{$index}" ) . '>' . $hotspot_item['title'] . '</span>';
			}
				$hotspot_output .= '</div>'; // End of icon wrapper.
			if ( isset( $hotspot_item['content'] ) && ! empty( $hotspot_item['content'] ) ) {
				$this->set_attribute( "content-$index", 'class', array( 'ba-image-hotspot-content-wrapper' ) );
				$hotspot_output .= '<div ' . $this->render_attributes( "content-{$index}" ) . '>';
				$hotspot_output .= $hotspot_item['content'];
				$hotspot_output .= '</div>';
			}
			// End of hotspot-wrapper.
			$hotspot_output .= '</div>';
			$hotspot_output .= '</' . $link_output . '>'; // End of ba-image-hotspot-item.
			// $hotspot_output .= '</div>';
			// End of item.
		}
		//phpcs:ignore
		
		$output .= $hotspot_output;
		// End of ba-image-hotspot-wrapper.
		$output .= '</div>';
		// End of Root.
		$output .= '</div>';

		//phpcs:ignore
		echo $output;

	}
}
