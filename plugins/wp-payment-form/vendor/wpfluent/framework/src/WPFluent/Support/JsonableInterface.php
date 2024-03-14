<?php

namespace WPPayForm\Framework\Support;

interface JsonableInterface {

	/**
	 * Convert the object to its JSON representation.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0);

}
