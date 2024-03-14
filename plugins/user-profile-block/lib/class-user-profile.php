<?php
namespace HAMWORKS\User_Profile_Block;

/**
 * Class User_Profile
 *
 * @package HAMWORKS\User_Profile_Block
 */
class User_Profile extends Block {

	/**
	 * Callback for render.
	 *
	 * @param array $attributes block attributes.
	 *
	 * @return false|string
	 */
	public function render( $attributes ) {
		if ( empty( $attributes['userId'] ) ) {
			return '';
		}
		set_query_var( 'user_id', $attributes['userId'] );

		return $this->get_content_from_template( $attributes );
	}
}
