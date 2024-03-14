<?php if (!defined('ABSPATH')) {exit;}
/**
* Creates a closing tag 
*
* @link			https://icopydoc.ru/
* @since		1.0.0
*/

class XFGMC_Get_Closed_Tag {
	protected $name_tag;

	public function __construct($name_tag) {
		$this->name_tag = $name_tag;
	}

	public function __toString() {
		if (empty($this->get_name_tag())) { 
			return '';
		} else {
			return sprintf("</%1\$s>",
				$this->get_name_tag()
			). PHP_EOL;
		}
	}

	public function get_name_tag() {
		return $this->name_tag;
	}
}