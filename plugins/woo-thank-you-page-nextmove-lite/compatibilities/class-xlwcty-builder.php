<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_remove_builder {
	private static $ins = null;
	private $post_type = 'xlwcty_thankyou';

	public function __construct() {
		add_filter( 'vc_check_post_type_validation', array( $this, 'vc_check_post_type_validation' ), 10, 2 );
		add_filter( 'xlwcty_redirect_preview_link', array( $this, 'change_preview_link_for_builder' ) );
		add_filter( 'et_builder_post_types', array( $this, 'divi_builder_post_types' ), 10, 1 );
	}

	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function fl_builder_post_types( $post_types ) {
		$index = array_search( $this->post_type, $post_types );
		if ( $index ) {
			unset( $post_types[ $index ] );
		}

		return $post_types;
	}


	public function vc_check_post_type_validation( $roles, $type ) {
		if ( $this->post_type === $type ) {
			return false;
		}

		return $roles;
	}

	public function change_preview_link_for_builder( $link ) {
		if ( isset( $_REQUEST['fl_builder'] ) ) {
			$link = add_query_arg( array( 'fl_builder' => '' ), $link );
		}

		return $link;
	}

	public function divi_builder_post_types( $post_types ) {
		$index = array_search( $this->post_type, $post_types );
		if ( $index ) {
			unset( $post_types[ $index ] );
		}

		return $post_types;
	}
}

XLWCTY_remove_builder::get_instance();
