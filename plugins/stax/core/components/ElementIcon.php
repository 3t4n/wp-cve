<?php
/**
 * Link component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementIcon extends Element implements ElementInterface {
	/**
	 * ElementIcon constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Icon';
		$this->slug       = 'icon';
		$this->icon->type = 'mdi-star-circle';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Type',
				'name'  => 'icon_type_field',
				'type'  => self::FIELD_ICON,
				'value' => 'mdi-account-outline'
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Size',
				'name'  => 'icon_size_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
					[
						'label'    => '18 px',
						'value'    => 'mdi-18px',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '24 px',
						'value'    => 'mdi-24px',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '36 px',
						'value'    => 'mdi-36px',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '48 px',
						'value'    => 'mdi-48px',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Link',
				'name'  => 'url_field',
				'type'  => self::FIELD_INPUT_URL,
				'value' => '#',
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Link target',
				'name'  => 'url_target_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
					[
						'label'    => 'Self',
						'value'    => '_self',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Blank',
						'value'    => '_blank',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				]
			]
		);

		$fields_1 = [];

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Alignment',
				'name'     => 'align_field',
				'only'     => 'section',
				'type'     => self::FIELD_BUTTON_GROUP,
				'value'    => [
					[
						'label'    => '<span class="mdi mdi-format-align-left mdi-18px"></span>',
						'value'    => 'left',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-align-center mdi-18px"></span>',
						'value'    => 'center',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-align-right mdi-18px"></span>',
						'value'    => 'right',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'text-align: {{VALUE}}'
				],
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'icon_padding_field',
				'type'     => self::FIELD_SPACING,
				'value'    => [
					[
						'position' => 'Top',
						'value'    => '6'
					],
					[
						'position' => 'Right',
						'value'    => '6'
					],
					[
						'position' => 'Bottom',
						'value'    => '6'
					],
					[
						'position' => 'Left',
						'value'    => '6',
					]
				],
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					],
					[
						'type'   => '%',
						'active' => false
					]
				],
				'selector' => [
					'{{SELECTOR}} a span.mdi' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}'
					]
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Border radius',
				'name'     => 'icon_border_radius_field',
				'type'     => self::FIELD_BORDER_RADIUS,
				'value'    => [
					[
						'position' => 'Top left',
						'value'    => '3'
					],
					[
						'position' => 'Top right',
						'value'    => '3'
					],
					[
						'position' => 'Bottom right',
						'value'    => '3'
					],
					[
						'position' => 'Bottom left',
						'value'    => '3'
					]
				],
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					],
					[
						'type'   => '%',
						'active' => false
					]
				],
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'       => 'Style',
				'name'        => 'icon_normal_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'icon_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(0,0,0)',
				'selector' => [
					'{{SELECTOR}} > a span.mdi' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'icon_bg_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Border type',
				'name'     => 'icon_border_type_n_field',
				'type'     => self::FIELD_DROPDOWN,
				'value'    => [
					[
						'label'    => 'None',
						'value'    => '',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Solid',
						'value'    => 'solid',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_n_field',
								'icon_border_color_n_field'
							]
						]
					],
					[
						'label'    => 'Double',
						'value'    => 'double',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_n_field',
								'icon_border_color_n_field'
							]
						]
					],
					[
						'label'    => 'Dotted',
						'value'    => 'dotted',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_n_field',
								'icon_border_color_n_field'
							]
						]
					],
					[
						'label'    => 'Dashed',
						'value'    => 'dashed',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_n_field',
								'icon_border_color_n_field'
							]
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'border-style: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Border width',
				'name'     => 'icon_border_width_n_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '1',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'border-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Border color',
				'name'     => 'icon_border_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'border-color: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'       => 'Hover style',
				'name'        => 'icon_hover_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'icon_color_h_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}}:hover > a span.mdi' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'icon_bg_color_h_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}}:hover a span.mdi' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Border type',
				'name'     => 'icon_border_type_h_field',
				'type'     => self::FIELD_DROPDOWN,
				'value'    => [
					[
						'label'    => 'None',
						'value'    => '',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Solid',
						'value'    => 'solid',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_h_field',
								'icon_border_color_h_field'
							]
						]
					],
					[
						'label'    => 'Double',
						'value'    => 'double',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_h_field',
								'icon_border_color_h_field'
							]
						]
					],
					[
						'label'    => 'Dotted',
						'value'    => 'dotted',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_h_field',
								'icon_border_color_h_field'
							]
						]
					],
					[
						'label'    => 'Dashed',
						'value'    => 'dashed',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'icon_border_width_h_field',
								'icon_border_color_h_field'
							]
						]
					]
				],
				'selector' => [
					'{{SELECTOR}}:hover a span.mdi' => 'border-style: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'      => 'Border width',
				'name'       => 'icon_border_width_h_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '1',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector'   => [
					'{{SELECTOR}}:hover a span.mdi' => 'border-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'      => 'Border color',
				'name'       => 'icon_border_color_h_field',
				'visibility' => false,
				'type'       => self::FIELD_COLOR_PICKER,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}}:hover a span.mdi' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'style-icon'
		] ), $fields_1, 'tab_style_icon' );

	}
}
