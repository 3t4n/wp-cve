<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
* Class representing an @import rule.
*/
class WjsslCssImport implements WjsslCssAtRule {
	private $oLocation;
	private $sMediaQuery;
	protected $iLineNo;
	protected $aComments;
	
	public function __construct(WjsslCssURL $oLocation, $sMediaQuery, $iLineNo = 0) {
		$this->oLocation = $oLocation;
		$this->sMediaQuery = $sMediaQuery;
		$this->iLineNo = $iLineNo;
		$this->aComments = array();
	}

	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	public function setLocation($oLocation) {
			$this->oLocation = $oLocation;
	}

	public function getLocation() {
			return $this->oLocation;
	}
	
	public function __toString() {
		return $this->render(new WjsslCssOutputFormat());
	}

	public function render(WjsslCssOutputFormat $oOutputFormat = null) {
		return "@import ".$this->oLocation->render($oOutputFormat).($this->sMediaQuery === null ? '' : ' '.$this->sMediaQuery).';';
	}

	public function atRuleName() {
		return 'import';
	}

	public function atRuleArgs() {
		$aResult = array($this->oLocation);
		if($this->sMediaQuery) {
			array_push($aResult, $this->sMediaQuery);
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