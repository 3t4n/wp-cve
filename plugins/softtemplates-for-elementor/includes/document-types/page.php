<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Softtemplate_Page_Document extends Softtemplate_Document_Base {

	public function get_name() {
		return 'softtemplate_page';
	}

	public static function get_title() {
		return __( 'Page', 'soft-template-core' );
	}

	public function has_conditions() {
		return false;
	}

}