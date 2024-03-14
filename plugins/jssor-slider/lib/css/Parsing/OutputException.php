<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
* Thrown if the CSS parsers attempts to print something invalid
*/
class WjsslCssOutputException extends WjsslCssSourceException {
	public function __construct($sMessage, $iLineNo = 0) {
		parent::__construct($sMessage, $iLineNo);
	}
}