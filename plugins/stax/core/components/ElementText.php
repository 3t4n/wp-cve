<?php
/**
 * Text component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementText extends Element implements ElementInterface {
	/**
	 * ElementText constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Text';
		$this->slug       = 'text';
		$this->icon->type = 'mdi-format-text';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Content',
				'name'  => 'text_field',
				'type'  => self::FIELD_TEXT_EDITOR,
				'value' => 'This is a demo text'
			]
		);

		$fields_1 = [];

		$fields_1[] = new EditorSectionField(
			[
				'label'       => 'Typography',
				'name'        => 'typo_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Font weight',
				'name'     => 'typo_font_weight_field',
				'type'     => self::FIELD_DROPDOWN,
				'value'    => [
					[
						'label'    => '100',
						'value'    => '100',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '200',
						'value'    => '200',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '300',
						'value'    => '300',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '400',
						'value'    => '400',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '500',
						'value'    => '500',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '600',
						'value'    => '600',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '700',
						'value'    => '700',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '800',
						'value'    => '800',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '900',
						'value'    => '900',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'font-weight: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Line height',
				'name'     => 'text_line_height_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '24',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'line-height: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Letter spacing',
				'name'     => 'text_letter_spacing_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '1',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'letter-spacing: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'style-text'
		] ), $fields_1, 'tab_style_text' );

		$this->setBoxDefaults( [
			self::ADVANCED_PADDING => [
				'value' => [ 7, 0, 7, 0 ]
			]
		] );
	}
}
