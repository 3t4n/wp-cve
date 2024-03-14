<?php
/**
 * Divider component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementDivider extends Element implements ElementInterface {
	/**
	 * ElementDivider constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Divider';
		$this->slug       = 'divider';
		$this->icon->type = 'mdi-format-vertical-align-center';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Style',
				'name'     => 'style_field',
				'type'     => self::FIELD_DROPDOWN,
				'value'    => [
					[
						'label'    => 'Solid',
						'value'    => 'solid',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Double',
						'value'    => 'double',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Dotted',
						'value'    => 'dotted',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Dashed',
						'value'    => 'dashed',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} .sq-divider-separator' => [
						'border-top-style: {{VALUE}}'
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Weight',
				'name'     => 'weight_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '1',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true,
					],
				],
				'selector' => [
					'{{SELECTOR}} .sq-divider-separator' => [
						'border-top-width: {{VALUE}}{{UNIT}}'
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'color_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(204,204,204)',
				'selector' => [
					'{{SELECTOR}} .sq-divider-separator' => [
						'border-top-color: {{VALUE}}'
					]
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Width',
				'name'     => 'width_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '100',
				'units'    => [
					[
						'type'   => '%',
						'active' => true,
					],
				],
				'selector' => [
					'{{SELECTOR}} .sq-divider-separator' => [
						'width: {{VALUE}}{{UNIT}}'
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Alignment',
				'name'     => 'alignment_field',
				'type'     => self::FIELD_BUTTON_GROUP,
				'value'    => [
					[
						'label'    => '<span class="mdi mdi-format-horizontal-align-left mdi-18px"></span>',
						'value'    => 'left',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-horizontal-align-center mdi-18px"></span>',
						'value'    => 'center',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-horizontal-align-right mdi-18px"></span>',
						'value'    => 'right',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} .sq-divider' => [
						'text-align: {{VALUE}}'
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Gap',
				'name'     => 'gap_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '0',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true,
					],
				],
				'selector' => [
					'{{SELECTOR}} .sq-divider' => [
						'padding: {{VALUE}}{{UNIT}}'
					],
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->setBoxDefaults( [
			self::ADVANCED_MARGIN => [
				'value' => [ 5, 0, 5, 0 ]
			]
		] );

	}
}
