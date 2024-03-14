<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Softtemplate_Section_Document extends Softtemplate_Document_Base {

	public function get_name() {
		return 'softtemplate_page';
	}

	public static function get_title() {
		return __( 'Section', 'soft-template-core' );
	}

	public function has_conditions() {
		return false;
	}

}