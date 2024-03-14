<?php
/**
 * Add Builder layouts bundled with the Popup plugin to Builder
 *
 * @since 1.0.0
 */
class Themify_Builder_Layouts_Provider_Themify_Popup extends Themify_Builder_Layouts_Provider {

	public function get_id() {
		return 'themify-popup';
	}

	public function get_label() {
		return __( 'Themify Popup', 'themify' );
	}

	/**
	 * Get a list of layouts from /builder-layouts/layouts.php file inside the theme
	 *
	 * @return array
	 */
	public function get_layouts() {
		return $this->get_layouts_from_file( THEMIFY_POPUP_DIR . 'sample/layouts.php' );
	}

	/**
	 * Get the Builder data from a file in /builder-layouts directory in the theme
	 *
	 * @return array|WP_Error
	 */
	public function get_builder_data( $slug ) {
		return $this->get_builder_data_from_file( THEMIFY_POPUP_DIR . 'sample/' . $slug );
	}
}