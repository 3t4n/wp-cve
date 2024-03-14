<?php


// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();


class WjsslCssURL extends WjsslCssPrimitiveValue {

	private $oURL;

	public function __construct(WjsslCSSString $oURL, $iLineNo = 0) {
		parent::__construct($iLineNo);
		$this->oURL = $oURL;
	}

	public function setURL(WjsslCSSString $oURL) {
		$this->oURL = $oURL;
	}

	public function getURL() {
		return $this->oURL;
	}

	public function __toString() {
		return $this->render(new WjsslCssOutputFormat());
	}

	public function render(WjsslCssOutputFormat $oOutputFormat = null) {
		return "url({$this->oURL->render($oOutputFormat)})";
	}

}