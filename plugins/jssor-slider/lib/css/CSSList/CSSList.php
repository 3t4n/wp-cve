<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * A WjsslCSSList is the most generic container available. Its contents include RuleSet as well as other WjsslCSSList objects.
 * Also, it may contain Import and Charset objects stemming from @-rules.
 */
abstract class WjsslCSSList implements WjsslCssRenderable, WjsslCssCommentable {

	protected $aComments;
	protected $aContents;
	protected $iLineNo;

	public function __construct($iLineNo = 0) {
		$this->aComments = array();
		$this->aContents = array();
		$this->iLineNo = $iLineNo;
	}

	/**
	 * @return int
	 */
	public function getLineNo() {
		return $this->iLineNo;
	}

	public function append($oItem) {
		$this->aContents[] = $oItem;
	}

	/**
	 * Removes an item from the CSS list.
	 * @param WjsslCssRuleSet|WjsslCssImport|WjsslCssCharset|WjsslCSSList $oItemToRemove May be a RuleSet (most likely a WjsslCssDeclarationBlock), a Import, a Charset or another WjsslCSSList (most likely a MediaQuery)
	 */
	public function remove($oItemToRemove) {
		$iKey = array_search($oItemToRemove, $this->aContents, true);
		if ($iKey !== false) {
			unset($this->aContents[$iKey]);
			return true;
		}
		return false;
	}

	/**
	 * Set the contents.
	 * @param array $aContents Objects to set as content.
	 */
	public function setContents(array $aContents) {
		$this->aContents = array();
		foreach ($aContents as $content) {
			$this->append($content);
		}
	}

	/**
	 * Removes a declaration block from the CSS list if it matches all given selectors.
	 * @param array|string $mSelector The selectors to match.
	 * @param boolean $bRemoveAll Whether to stop at the first declaration block found or remove all blocks
	 */
	public function removeDeclarationBlockBySelector($mSelector, $bRemoveAll = false) {
		if ($mSelector instanceof WjsslCssDeclarationBlock) {
			$mSelector = $mSelector->getSelectors();
		}
		if (!is_array($mSelector)) {
			$mSelector = explode(',', $mSelector);
		}
		foreach ($mSelector as $iKey => &$mSel) {
			if (!($mSel instanceof WjsslCssSelector)) {
				$mSel = new WjsslCssSelector($mSel);
			}
		}
		foreach ($this->aContents as $iKey => $mItem) {
			if (!($mItem instanceof WjsslCssDeclarationBlock)) {
				continue;
			}
			if ($mItem->getSelectors() == $mSelector) {
				unset($this->aContents[$iKey]);
				if (!$bRemoveAll) {
					return;
				}
			}
		}
	}

	public function __toString() {
		return $this->render(new WjsslCssOutputFormat());
	}

	public function callback_safely_func($oNextLevel, $oContent) {
		return $oContent->render($oNextLevel);
	}

	public function render(WjsslCssOutputFormat $oOutputFormat = null) {
		$sResult = '';
		$bIsFirst = true;
		$oNextLevel = $oOutputFormat;
		if(!$this->isRootList()) {
			$oNextLevel = $oOutputFormat->nextLevel();
		}
		foreach ($this->aContents as $oContent) {
			$sRendered = $oOutputFormat->safely(array($this, 'callback_safely_func'), array($oNextLevel, $oContent));
			if($sRendered === null) {
				continue;
			}
			if($bIsFirst) {
				$bIsFirst = false;
				$sResult .= $oNextLevel->spaceBeforeBlocks();
			} else {
				$sResult .= $oNextLevel->spaceBetweenBlocks();
			}
			$sResult .= $sRendered;
		}

		if(!$bIsFirst) {
			// Had some output
			$sResult .= $oOutputFormat->spaceAfterBlocks();
		}

		return $sResult;
	}
	
	/**
	* Return true if the list can not be further outdented. Only important when rendering.
	*/
	public abstract function isRootList();

	public function getContents() {
		return $this->aContents;
	}

	/**
	 * @param array $aComments Array of comments.
	 */
	public function addComments(array $aComments) {
		$this->aComments = array_merge($this->aComments, $aComments);
	}

	/**
	 * @return array
	 */
	public function getComments() {
		return $this->aComments;
	}

	/**
	 * @param array $aComments Array containing WjsslComment objects.
	 */
	public function setComments(array $aComments) {
		$this->aComments = $aComments;
	}

}
