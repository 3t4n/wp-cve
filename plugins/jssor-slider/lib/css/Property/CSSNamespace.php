<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
* WjsslCSSNamespace represents an @namespace rule.
*/
class WjsslCSSNamespace implements WjsslCssAtRule {
	private $mUrl;
	private $sPrefix;
	private $iLineNo;
	protected $aComments;
	
	public function __construct($mUrl, $sPrefix = null, $iLineNo = 0) {
		$this->mUrl = $mUrl;
		$this->sPrefix = $sPrefix;
		$this->iLineNo = $iLineNo;
		$this->aComments = array();
	}

	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	public function __toString() {
		return $this->render(new WjsslCssOutputFormat());
	}

	public function render(WjsslCssOutputFormat $oOutputFormat = null) {
		return '@namespace '.($this->sPrefix === null ? '' : $this->sPrefix.' ').$this->mUrl->render($oOutputFormat).';';
	}
	
	public function getUrl() {
		return $this->mUrl;
	}

	public function getPrefix() {
		return $this->sPrefix;
	}

	public function setUrl($mUrl) {
		$this->mUrl = $mUrl;
	}

	public function setPrefix($sPrefix) {
		$this->sPrefix = $sPrefix;
	}

	public function atRuleName() {
		return 'namespace';
	}

	public function atRuleArgs() {
		$aResult = array($this->mUrl);
		if($this->sPrefix) {
			array_unshift($aResult, $this->sPrefix);
		}
		return $aResult;
	}

	public function addComments(array $aComments) {
		$this->aComments = array_merge($this->aComments, $aComments);
	}

	public function getComments() {
		return $this->aComments;
	}

	public function setComments(array $aComments) {
		$this->aComments = $aComments;
	}
}