<?php
/**
 * Spacer component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementSpacer extends Element implements ElementInterface {
	/**
	 * ElementSpacer constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Spacer';
		$this->slug       = 'spacer';
		$this->icon->type = 'mdi-arrow-expand-vertical';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Space',
				'name'     => 'spacer_space_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '38',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true,
					],
				],
				'selector' => [
					'{{SELECTOR}} .sq-spacer-inner' => [
						'height: {{VALUE}}{{UNIT}}'
					],
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

	}
}
