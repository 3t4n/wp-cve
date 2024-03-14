<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WjsslCssRuleValueList extends WjsslCssValueList {
	public function __construct($sSeparator = ',', $iLineNo = 0) {
		parent::__construct(array(), $sSeparator, $iLineNo);
	}
}