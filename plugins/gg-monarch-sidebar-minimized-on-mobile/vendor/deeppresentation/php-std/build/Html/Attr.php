<?php namespace MSMoMDP\Std\Html;


class Attr {

	private $data = array();
	public function __construct( ?array $attributes = null ) {
		$this->data = $attributes ?? array();
	}
	public function add_attr( string $key, string $val ) {
		$this->data[ $key ] = $val;
	}

	public function append_class( string $val ) {
		if ( key_exists( 'class', $this->data ) ) {
			$this->data['class'] .= ' ' . $val;
		} else {
			$this->data['class'] = $val;
		}
	}

	//@param string|array $attrVal
	public function apend_attr( array $attributes ) {
		foreach ( $attributes as $key => $val ) {
			$this->data = array_merge_recursive( $this->data, $attributes );
		}
	}

	public static function get_style_str( array $assocStyles ) {
		if ( count( $assocStyles ) > 0 ) {
			$res = '';
			foreach ( $assocStyles as $key => $val ) {
				$res .= $key . ':' . $val . ';';
			}
			return $res;
		}
		return '';
	}

	public function to_str() {
		return Html::get_attr_str( $this->data );
	}
}
