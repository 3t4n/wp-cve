<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Creates a closing tag 
 *
 * @link			https://icopydoc.ru/
 * @since		1.0.0
 * @version		1.1
 */

class XFGMC_Get_Open_Tag extends XFGMC_Get_Closed_Tag {
	protected $attr_tag_arr;
	protected $closing_slash = '';

	public function __construct( $name_tag, array $attr_tag_arr = array(), $closing_slash = false ) {
		parent::__construct( $name_tag );

		if ( ! empty( $attr_tag_arr ) ) {
			$this->attr_tag_arr = $attr_tag_arr;
		}

		if ( $closing_slash === true ) {
			$this->closing_slash = '/';
		}
	}

	public function __toString() {
		if ( empty( $this->get_name_tag() ) ) {
			return '';
		} else {
			return sprintf( "<%1\$s%2\$s%3\$s>",
				$this->get_name_tag(),
				$this->get_attr_tag(),
				$this->get_closing_slash()
			) . PHP_EOL;
		}
	}

	public function get_attr_tag() {
		$res_string = '';
		if ( ! empty( $this->attr_tag_arr ) ) {
			foreach ( $this->attr_tag_arr as $key => $value ) {
				$res_string .= ' ' . $key . '="' . $value . '"';
			}
		}
		return $res_string;
	}

	private function get_closing_slash() {
		return $this->closing_slash;
	}
}