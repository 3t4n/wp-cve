<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_IconList extends \Bricks\Element {

	public $category = 'bricksable';
	public $name     = 'ba-icon-list';
	public $icon     = 'ti-list';

	public function get_label() {
		return esc_html__( 'Icon List', 'bricksable' );
	}

	public function set_control_groups() {

		$this->control_groups['items'] = array(
			'title' => esc_html__( 'Items', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['item'] = array(
			'title' => esc_html__( 'List item', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['highlight'] = array(
			'title' => esc_html__( 'Highlight', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['title'] = array(
			'title' => esc_html__( 'Title', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['icon'] = array(
			'title' => esc_html__( 'Icon', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['meta'] = array(
			'title' => esc_html__( 'Meta', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['description'] = array(
			'title' => esc_html__( 'Description', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['separator'] = array(
			'title' => esc_html__( 'Separator', 'bricksable' ),
			'tab'   => 'content',
		);

	}

	public function set_controls() {

		/**
		 * Items
		 */

		$this->controls['items'] = array(
			'tab'           => 'content',
			'group'         => 'items',
			'placeholder'   => esc_html__( 'List items', 'bricksable' ),
			'type'          => 'repeater',
			'selector'      => 'li',
			'titleProperty' => 'title',
			'fields'        => array(
				'title'          => array(
					'label'          => esc_html__( 'Title', 'bricksable' ),
					'type'           => 'text',
					'hasDynamicData' => 'text',
					'inlineEditing'  => array(
						'selector' => '.title',
					),
				),

				'icon'           => array(
					'label'    => esc_html__( 'Icon', 'bricksable' ),
					'type'     => 'icon',
					'required' => array(
						array(
							'use_icon_text',
							'!=',
							true,
						),
						array(
							'use_icon_image',
							'!=',
							true,
						),
					),
				),

				'use_icon_text'  => array(
					'label'    => esc_html__( 'Use Icon Text', 'bricksable' ),
					'type'     => 'checkbox',
					'inline'   => true,
					'required' => array( 'use_icon_image', '!=', true ),
				),

				'icon_text'      => array(
					'label'          => esc_html__( 'Icon Text', 'bricksable' ),
					'type'           => 'text',
					'hasDynamicData' => 'text',
					'required'       => array( 'use_icon_text', '!=', '' ),
				),

				'use_icon_image' => array(
					'label'    => esc_html__( 'Use Icon Image', 'bricksable' ),
					'type'     => 'checkbox',
					'inline'   => true,
					'required' => array( 'use_icon_text', '!=', true ),
				),

				'icon_image'     => array(
					'label'          => esc_html__( 'Icon Image', 'bricksable' ),
					'type'           => 'image',
					'hasDynamicData' => 'image',
					'required'       => array( 'use_icon_image', '!=', '' ),
				),

				'link'           => array(
					'label' => esc_html__( 'Link title', 'bricksable' ),
					'type'  => 'link',
				),

				'meta'           => array(
					'label'          => esc_html__( 'Meta', 'bricksable' ),
					'type'           => 'text',
					'hasDynamicData' => 'text',
				),

				'description'    => array(
					'label'          => esc_html__( 'Description', 'bricksable' ),
					'type'           => 'textarea',
					'hasDynamicData' => 'text',
				),

				'highlight'      => array(
					'label' => esc_html__( 'Highlight', 'bricksable' ),
					'type'  => 'checkbox',
				),

				'highlightLabel' => array(
					'label'    => esc_html__( 'Highlight label', 'bricksable' ),
					'type'     => 'text',
					'inline'   => true,
					'required' => array( 'highlight', '!=', '' ),
				),
			),
			'default'       => array(
				array(
					'title' => esc_html__( 'Icon List item #1', 'bricksable' ),
					'icon'  => array(
						'library' => 'themify',
						'icon'    => 'ti-check-box',
					),
				),
				array(
					'title' => esc_html__( 'Icon List item #2', 'bricksable' ),
					'icon'  => array(
						'library' => 'themify',
						'icon'    => 'ti-check-box',
					),
				),
			),
		);

		/**
		 * List item
		 */

		$this->controls['itemMargin'] = array(
			'tab'   => 'content',
			'group' => 'item',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'spacing',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => 'li',
				),
			),
		);

		$this->controls['itemPadding'] = array(
			'tab'   => 'content',
			'group' => 'item',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'spacing',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.content-wrapper',
				),
			),
		);

		$this->controls['itemRowGap'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Row Gap', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'row-gap',
					'selector' => 'ul',
				),
			),
			'placeholder' => '',
			'description' => esc_html__( 'Set the gap distance between the list items.', 'bricksable' ),
		);

		$this->controls['item_verticalAlign'] = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Vertical align', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => array(
				'stretch',
			),
			'css'     => array(
				array(
					'property' => 'align-items',
					'selector' => '.title-wrapper',
				),
			),
			'inline'  => true,
			'default' => 'center',
		);

		$this->controls['itemJustifyContent'] = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Align', 'bricksable' ),
			'type'    => 'justify-content',
			'exclude' => array(
				'space-between',
				'space-around',
				'space-evenly',
			),
			'css'     => array(
				array(
					'property' => 'justify-content',
					'selector' => '.title-wrapper',
				),
			),
			'inline'  => true,
		);

		$this->controls['itemDirection'] = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Direction', 'bricksable' ),
			'title'   => 'flex-direction',
			'type'    => 'select',
			'options' => array(
				'row'         => esc_html__( 'Horizontal', 'bricksable' ),
				'row-reverse' => esc_html__( 'Horizontal reversed', 'bricksable' ),
			),
			'css'     => array(
				array(
					'property' => 'flex-direction',
					'selector' => '.title-wrapper',
				),
			),
			'default' => 'row',
			'inline'  => true,
		);

		$this->controls['item_indent'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Indent Gap', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'gap',
					'selector' => '.title-wrapper',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 1,
					'max'  => 40,
					'step' => 1,
				),
				'em' => array(
					'min'  => 1,
					'max'  => 20,
					'step' => 0.1,
				),
			),
			'default'     => '7px',
			'description' => esc_html__( 'Set the gap distance element within container.', 'bricksable' ),
		);

		$this->controls['itemOddBackground'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Odd background', 'bricksable' ),
			'type'   => 'color',
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => 'li:nth-child(odd)',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['itemEvenBackground'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Even background', 'bricksable' ),
			'type'   => 'color',
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => 'li:nth-child(even)',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['itemBorder'] = array(
			'tab'    => 'content',
			'group'  => 'settings',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => 'li',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['itemAutoWidth'] = array(
			'tab'   => 'content',
			'group' => 'item',
			'label' => esc_html__( 'Auto width', 'bricksable' ),
			'type'  => 'checkbox',
			'css'   => array(
				array(
					'property' => 'justify-content',
					'selector' => '.title-wrapper',
					'value'    => 'initial',
				),
				array(
					'property' => 'flex-grow',
					'selector' => '.separator',
					'value'    => '0',
				),
			),
		);

		/**
		 * Highlight
		 */

		$this->controls['highlightBlock'] = array(
			'tab'   => 'content',
			'group' => 'highlight',
			'label' => esc_html__( 'Block', 'bricksable' ),
			'type'  => 'checkbox',
			'css'   => array(
				array(
					'property' => 'display',
					'selector' => '.highlight',
					'value'    => 'block',
				),
			),
		);

		$this->controls['highlightLabelPadding'] = array(
			'tab'   => 'content',
			'group' => 'highlight',
			'label' => esc_html__( 'Label padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.highlight',
				),
			),
		);

		$this->controls['highlightLabelBackground'] = array(
			'tab'    => 'content',
			'group'  => 'highlight',
			'label'  => esc_html__( 'Label background', 'bricksable' ),
			'type'   => 'color',
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => '.highlight',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['highlightLabelBorder'] = array(
			'tab'    => 'content',
			'group'  => 'highlight',
			'label'  => esc_html__( 'Label border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.highlight',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['highlightLabelTypography'] = array(
			'tab'    => 'content',
			'group'  => 'highlight',
			'label'  => esc_html__( 'Label typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.highlight',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['highlightContentPadding'] = array(
			'tab'   => 'content',
			'group' => 'highlight',
			'label' => esc_html__( 'Content padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.highlighted .content-wrapper',
				),
			),
		);

		$this->controls['highlightContentBackground'] = array(
			'tab'    => 'content',
			'group'  => 'highlight',
			'label'  => esc_html__( 'Content background', 'bricksable' ),
			'type'   => 'color',
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => '.highlighted .content-wrapper',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['highlightContentBorder'] = array(
			'tab'    => 'content',
			'group'  => 'highlight',
			'label'  => esc_html__( 'Content border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.highlighted .content-wrapper',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['highlightContentColor'] = array(
			'tab'    => 'content',
			'group'  => 'highlight',
			'label'  => esc_html__( 'Content text color', 'bricksable' ),
			'type'   => 'color',
			'css'    => array(
				array(
					'property' => 'color',
					'selector' => '.highlighted .content-wrapper .title',
				),
				array(
					'property' => 'color',
					'selector' => '.highlighted .content-wrapper .meta',
				),
				array(
					'property' => 'color',
					'selector' => '.highlighted .content-wrapper .description',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Icon.

		$this->controls['icon_margin'] = array(
			'tab'   => 'content',
			'group' => 'icon',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.icon',
				),

			),
		);

		$this->controls['icon_padding'] = array(
			'tab'   => 'content',
			'group' => 'icon',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.icon',
				),

			),
		);

		/*
		Removed in 1.6.35
		$this->controls['icon_circle'] = array(
			'tab'   => 'content',
			'group' => 'icon',
			'label' => esc_html__( 'Circle', 'bricksable' ),
			'type'  => 'checkbox',
		);
		*/

		$this->controls['iconHeight'] = array(
			'tab'   => 'content',
			'group' => 'icon',
			'label' => esc_html__( 'Icon height', 'bricksable' ),
			'type'  => 'number',
			'units' => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
			),
			'css'   => array(
				array(
					'property' => 'height',
					'selector' => '.icon',
				),
				array(
					'property' => 'line-height',
					'selector' => '.icon',
				),
				array(
					'property' => 'height',
					'selector' => '.icon',
				),
				array(
					'property' => 'line-height',
					'selector' => '.icon',
				),
				array(
					'property' => 'height',
					'selector' => '.icon-wrapper svg',
				),
				array(
					'property' => 'width',
					'selector' => '.icon-wrapper svg',
				),
			),
		);

		$this->controls['iconWidth'] = array(
			'tab'   => 'content',
			'group' => 'icon',
			'label' => esc_html__( 'Icon width', 'bricksable' ),
			'type'  => 'number',
			'units' => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
			),
			'css'   => array(
				array(
					'property' => 'width',
					'selector' => '.icon',
				),
				array(
					'property' => 'width',
					'selector' => '.icon',
				),
			),
		);

		$this->controls['icon_image_width'] = array(
			'tab'         => 'content',
			'group'       => 'icon',
			'label'       => esc_html__( 'Icon Image Width', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'max-width',
					'selector' => '.ba-icon-list-icon-image',
				),
			),
			'units'       => array(
				'%' => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
			),
			'description' => esc_html__( 'Adjust the icon image width.', 'bricksable' ),
		);

		$this->controls['icon_background'] = array(
			'tab'    => 'content',
			'group'  => 'icon',
			'label'  => esc_html__( 'Icon background', 'bricksable' ),
			'type'   => 'color',
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => '.icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['icon_typography'] = array(
			'tab'     => 'content',
			'group'   => 'icon',
			'label'   => esc_html__( 'Icon typography', 'bricksable' ),
			'type'    => 'typography',
			'exclude' => array(
				'font-family',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
			),
			'css'     => array(
				array(
					'property' => 'font',
					'selector' => '.icon',
				),
			),
			'inline'  => true,
			'small'   => true,
		);

		$this->controls['icon_text_typography'] = array(
			'tab'    => 'content',
			'group'  => 'icon',
			'label'  => esc_html__( 'Icon Text typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.ba-icon-list-icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['icon_border'] = array(
			'tab'    => 'content',
			'group'  => 'icon',
			'label'  => esc_html__( 'Icon border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['icon_boxshadow'] = array(
			'tab'    => 'content',
			'group'  => 'icon',
			'label'  => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		/**
		 * Title
		 */

		$this->controls['titleMargin'] = array(
			'tab'   => 'content',
			'group' => 'title',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.title',
				),
			),
		);

		$this->controls['titleTag'] = array(
			'tab'         => 'content',
			'group'       => 'title',
			'label'       => esc_html__( 'Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h2' => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3' => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4' => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5' => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6' => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => 'span',
		);

		$this->controls['titleTypography'] = array(
			'tab'    => 'content',
			'group'  => 'title',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.title',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		/**
		 * Meta
		 */

		$this->controls['metaMargin'] = array(
			'tab'   => 'content',
			'group' => 'meta',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.meta',
				),
			),
		);

		$this->controls['metaTypography'] = array(
			'tab'    => 'content',
			'group'  => 'meta',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.meta',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		/**
		 * Description
		 */

		$this->controls['descriptionTypography'] = array(
			'tab'    => 'content',
			'group'  => 'description',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.description',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		/**
		 * Separator
		 */

		$this->controls['separatorDisable'] = array(
			'tab'     => 'content',
			'group'   => 'separator',
			'label'   => esc_html__( 'Disable', 'bricksable' ),
			'type'    => 'checkbox',
			'css'     => array(
				array(
					'property' => 'display',
					'selector' => '.separator',
					'value'    => 'none',
				),
			),
			'default' => true,
		);

		$this->controls['separatorStyle'] = array(
			'tab'      => 'content',
			'group'    => 'separator',
			'label'    => esc_html__( 'Style', 'bricksable' ),
			'type'     => 'select',
			'options'  => $this->control_options['borderStyle'],
			'css'      => array(
				array(
					'property' => 'border-top-style',
					'selector' => '.separator',
				),
			),
			'inline'   => true,
			'required' => array( 'separatorDisable', '=', '' ),
		);

		$this->controls['separatorWidth'] = array(
			'tab'      => 'content',
			'group'    => 'separator',
			'label'    => esc_html__( 'Width in px', 'bricksable' ),
			'type'     => 'number',
			'unit'     => 'px',
			'css'      => array(
				array(
					'property' => 'flex-basis',
					'selector' => '.separator',
				),
				array(
					'property' => 'flex-grow',
					'selector' => '.separator',
					'value'    => '0',
				),
			),
			'inline'   => true,
			'required' => array( 'separatorDisable', '=', '' ),
		);

		$this->controls['separatorHeight'] = array(
			'tab'      => 'content',
			'group'    => 'separator',
			'label'    => esc_html__( 'Height in px', 'bricksable' ),
			'type'     => 'number',
			'unit'     => 'px',
			'css'      => array(
				array(
					'property' => 'border-top-width',
					'selector' => '.separator',
				),
			),
			'inline'   => true,
			'required' => array( 'separatorDisable', '=', '' ),
		);

		$this->controls['separatorColor'] = array(
			'tab'      => 'content',
			'group'    => 'separator',
			'label'    => esc_html__( 'Color', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'border-top-color',
					'selector' => '.separator',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'separatorDisable', '=', '' ),
		);

	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-icon-list' );
	}

	public function render() {
		$settings = $this->settings;
		$icon     = ! empty( $settings['icon'] ) ? self::render_icon( $settings['icon'] ) : false;

		// Element placeholder.
		if ( ! isset( $settings['items'] ) || empty( $settings['items'] ) ) {
			return $this->render_element_placeholder( array( 'text' => esc_html__( 'No list items defined.', 'bricksable' ) ) );
		}

		$output = '';

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$output .= "<div {$this->render_attributes( '_root' )}>";
		}

		$output .= '<ul>';

		foreach ( $settings['items'] as $index => $list_item ) {
			$list_item_classes = array( 'list-item' );

			if ( isset( $list_item['highlight'] ) ) {
				$list_item_classes[] = 'highlighted';
			}

			$this->set_attribute( "list-item-$index", 'class', $list_item_classes );

			$output .= '<li ' . $this->render_attributes( "list-item-$index" ) . '>';

			if ( isset( $list_item['highlightLabel'] ) && ! empty( $list_item['highlightLabel'] ) ) {
				$output .= '<div class="highlight">' . esc_html( $list_item['highlightLabel'] ) . '</div>';
			}
			// Icon item precedes icon set under "Icon" control group for all items.
			$icon = ! empty( $list_item['icon'] ) ? self::render_icon( $list_item['icon'] ) : $icon;

			$output .= '<div class="content-wrapper">';

			$output .= '<div class="title-wrapper">';

			if ( isset( $list_item['title'] ) && ! empty( $list_item['title'] ) ) {
				$title_tag = isset( $settings['titleTag'] ) ? esc_attr( $settings['titleTag'] ) : 'span';

				$this->set_attribute( "title-$index", $title_tag );
				$this->set_attribute( "title-$index", 'class', array( 'title' ) );

				// Icon.
				$this->set_attribute(
					"icon-wrapper-$index",
					'class',
					array(
						'icon-wrapper',
						'icon',
					)
				);
				$this->set_attribute(
					"ba-icon-list-icon-$index",
					'class',
					array(
						'ba-icon-list-icon',
					)
				);

				if ( true === isset( $list_item['use_icon_text'] ) ) {
					if ( isset( $list_item['icon_text'] ) ) {
						$output .= '<span ' . $this->render_attributes( "icon-wrapper-{$index}" ) . '>'; //phpcs:ignore
						$output .= '<span ' . $this->render_attributes( "ba-icon-list-icon-{$index}" ) . '>' . esc_html( $list_item['icon_text'] ) . '</span>'; //phpcs:ignore
						$output .= '</span>';
					}
				} elseif ( true === isset( $list_item['use_icon_image'] ) ) {
					// Image.
					$image_atts           = array();
					$image_atts['id']     = 'image-' . $list_item['icon_image']['id'];
					$item_image_classes   = array( 'ba-icon-list-icon-image', 'css-filter' );
					$item_image_classes[] = 'size-' . $list_item['icon_image']['size'];
					$image_atts['class']  = join( ' ', $item_image_classes );
					$this->set_attribute( "icon-list-icon-image-{$index}", 'class', $item_image_classes );
					$this->set_attribute( "icon-list-icon-image-{$index}", 'src', esc_url( $list_item['icon_image']['url'] ) );
					$output .= '<span ' . $this->render_attributes( "icon-wrapper-{$index}" ) . '>'; //phpcs:ignore

					// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
					if ( isset( $list_item['icon_image']['id'] ) ) {
						$output .= wp_get_attachment_image( $list_item['icon_image']['id'], $list_item['icon_image']['size'], false, $image_atts );
					} elseif ( ! empty( $list_item['icon_image']['url'] ) ) {
						$output .= '<img ' . $this->render_attributes( "icon-list-icon-image-{$index}" ) . '>'; //phpcs:ignore
					}
					$output .= '</span>';

				} else {
					if ( isset( $icon ) ) {
						$output .= '<span ' . $this->render_attributes( "icon-wrapper-{$index}" ) . '>'; //phpcs:ignore
						$output .= $icon; //phpcs:ignore
						$output .= '</span>';
					}
				}
				if ( isset( $list_item['link'] ) ) {
					$this->set_link_attributes( "a-$index", $list_item['link'] );
					$output .= '<a ' . $this->render_attributes( "a-$index" ) . '><' . $this->render_attributes( "title-$index" ) . '>' . $list_item['title'] . '</' . $title_tag . '></a>';
				} else {
					$output .= '<' . $this->render_attributes( "title-$index" ) . '>' . $list_item['title'] . '</' . $title_tag . '>';

				}
			}

			if ( ! isset( $list_item['separatorDisable'] ) ) {
				$output .= '<span class="separator"></span>';
			}

			if ( isset( $list_item['meta'] ) && ! empty( $list_item['meta'] ) ) {
				$this->set_attribute( "meta-$index", 'class', array( 'meta' ) );

				$output .= '<span ' . $this->render_attributes( "meta-$index" ) . '>' . $list_item['meta'] . '</span>';
			}

			$output .= '</div>';

			if ( isset( $list_item['description'] ) && ! empty( $list_item['description'] ) ) {
				$this->set_attribute( "description-$index", 'class', array( 'description' ) );

				$output .= '<div ' . $this->render_attributes( "description-$index" ) . '>' . $list_item['description'] . '</div>';
			}

			$output .= '</div>'; // .content-wrapper

			$output .= '</li>';
		}

		$output .= '</ul>';

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$output .= '</div>';
		}

		echo $output; //phpcs:ignore
	}

}
