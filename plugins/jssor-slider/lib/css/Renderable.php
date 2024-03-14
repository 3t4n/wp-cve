<?php


// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

interface WjsslCssRenderable {
	public function __toString();
	public function render(WjsslCssOutputFormat $oOutputFormat = null);
	public function getLineNo();
}