<?php

/**
 * Class ATI_Object_Groups
 */
class ATI_Object_Groups {

	/**
	 * Post, user or term.
	 *
	 * @var string
	 */
	private $type;
	/**
	 * WordPress object.
	 *
	 * @var WP_Post|WP_Term|WP_User
	 */
	private $object;

	/**
	 * ATI_Object_Groups constructor.
	 *
	 * @param object $object .
	 * @param string                  $type .
	 */
	public function __construct( $object, $type ) {
		$this->object = $object;
		$this->type   = $type;
	}

	/**
	 * Retuns an array of the field groups assigned to current object.
	 *
	 * @return array
	 */
	public function get_fields_groups() {
		if ( 'post' === $this->type ) {
			$groups = acf_get_field_groups( array( 'post_id' => $this->object->ID ) );
		}

		if ( 'term' === $this->type ) {
			$groups = acf_get_field_groups( array( 'page_type' => 'taxonomy', 'taxonomy' => $this->object->taxonomy, 'term_id' => $this->object->ID ) );
		}

		if ( 'user' === $this->type ) {
			$groups = acf_get_field_groups( array( 'page_type' => 'user', 'user_form' => 'edit', 'user_id' => $this->object->ID ) );
		}

		$field_groups = array();
		foreach ( $groups as $group ) {
			$group           = acf_get_field_group( $group['key'] );
			$group['fields'] = acf_get_fields( $group );
			$field_groups[]  = $group;
		}

		return $field_groups;
	}
}