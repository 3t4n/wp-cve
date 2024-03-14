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

class ElementSeparator extends Element implements ElementInterface {
	/**
	 * ElementSeparator constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Separator';
		$this->slug       = 'separator';
		$this->icon->type = 'mdi-format-horizontal-align-center';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Height',
				'name'     => 'height_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '38',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'height: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Width',
				'name'     => 'width_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '1',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'bg_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(204,204,204)',
				'selector' => [
					'{{SELECTOR}}' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->setBoxDefaults( [
			self::ADVANCED_MARGIN => [
				'value' => [ 0, 5, 0, 5 ]
			]
		] );
	}
}
