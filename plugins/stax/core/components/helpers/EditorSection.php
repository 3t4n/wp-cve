<?php
/**
 * EditorSection.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EditorSection extends ElementSpecs {

	public
		$name,
		$title,
		$visibility,
		$only,
		$type,
		$state,
		$fields,
		$icon,
		$free;

	/**
	 * EditorSection constructor.
	 *
	 * @param array $property
	 */
	public function __construct( array $property ) {

		$property = apply_filters( 'stax_section_properties', $property );

		$this->title      = isset( $property['title'] ) ? $property['title'] : 'Options';
		$this->name       = $property['name'];
		$this->only       = isset( $property['only'] ) ? $property['only'] : '';
		$this->type       = isset( $property['type'] ) ? $property['type'] : 'default';
		$this->visibility = isset( $property['visibility'] ) ? $property['visibility'] : true;
		$this->state      = isset( $property['state'] ) ? $property['state'] : false;
		$this->fields     = [];
		$this->icon       = isset( $property['icon'] ) ? $property['icon'] : '';
		$this->free       = isset( $property['free'] ) ? $property['free'] : true;
	}

	/**
	 * @param EditorSectionField $field
	 */
	public function addField( EditorSectionField $field ) {

		$field = apply_filters( 'stax_section_add_field', $field, $this );
		if ( null !== $field ) {
			$this->fields[] = $field;
		}

		do_action( 'stax_section_after_add_field', $field, $this );
	}
}
