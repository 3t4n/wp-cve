<?php
namespace LaStudioKitThemeBuilder\Modules\ThemeBuilder\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Any_Child_Of extends Child_Of {

	public function get_name() {
		return 'any_child_of';
	}

	public function get_label() {
		return __( 'Any Child Of', 'lastudio-kit' );
	}

	public function check( $args ) {
		if ( ! is_singular() ) {
			return false;
		}

		$id = (int) $args['id'];
		$parents = get_post_ancestors( get_the_ID() );

		return ( ( 0 === $id && ! empty( $parents ) ) || in_array( $id, $parents ) );
	}
}
