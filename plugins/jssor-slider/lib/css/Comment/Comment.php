<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WjsslCssComment implements WjsslCssRenderable {
	protected $iLineNo;
	protected $sComment;

	public function __construct($sComment = '', $iLineNo = 0) {
		$this->sComment = $sComment;
		$this->iLineNo = $iLineNo;
	}

	/**
	 * @return string
	 */
	public function getComment() {
		return $this->sComment;
	}

	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	/**
	 * @return string
	 */
	public function setComment($sComment) {
		$this->sComment = $sComment;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->render(new WjsslCssOutputFormat());
	}

	/**
	 * @return string
	 */
    public function render(WjsslCssOutputFormat $oOutputFormat = null) {
		return '/*' . $this->sComment . '*/';
	}

}
