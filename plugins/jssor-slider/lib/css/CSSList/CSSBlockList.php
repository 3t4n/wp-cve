<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * A WjsslCSSBlockList is a WjsslCSSList whose WjsslCssDeclarationBlocks are guaranteed to contain valid declaration blocks or at-rules.
 * Most WjsslCSSLists conform to this category but some at-rules (such as @keyframes) do not.
 */
abstract class WjsslCSSBlockList extends WjsslCSSList {
	public function __construct($iLineNo = 0) {
		parent::__construct($iLineNo);
	}

	protected function allDeclarationBlocks(&$aResult) {
		foreach ($this->aContents as $mContent) {
			if ($mContent instanceof WjsslCssDeclarationBlock) {
				$aResult[] = $mContent;
			} else if ($mContent instanceof WjsslCSSBlockList) {
				$mContent->allDeclarationBlocks($aResult);
			}
		}
	}

	protected function allRuleSets(&$aResult) {
		foreach ($this->aContents as $mContent) {
			if ($mContent instanceof WjsslCssRuleSet) {
				$aResult[] = $mContent;
			} else if ($mContent instanceof WjsslCSSBlockList) {
				$mContent->allRuleSets($aResult);
			}
		}
	}

	protected function allValues($oElement, &$aResult, $sSearchString = null, $bSearchInFunctionArguments = false) {
		if ($oElement instanceof WjsslCSSBlockList) {
			foreach ($oElement->getContents() as $oContent) {
				$this->allValues($oContent, $aResult, $sSearchString, $bSearchInFunctionArguments);
			}
		} else if ($oElement instanceof WjsslCssRuleSet) {
			foreach ($oElement->getRules($sSearchString) as $oRule) {
				$this->allValues($oRule, $aResult, $sSearchString, $bSearchInFunctionArguments);
			}
		} else if ($oElement instanceof WjsslCssRule) {
			$this->allValues($oElement->getValue(), $aResult, $sSearchString, $bSearchInFunctionArguments);
		} else if ($oElement instanceof WjsslCssValueList) {
			if ($bSearchInFunctionArguments || !($oElement instanceof WjsslCSSFunction)) {
				foreach ($oElement->getListComponents() as $mComponent) {
					$this->allValues($mComponent, $aResult, $sSearchString, $bSearchInFunctionArguments);
				}
			}
		} else {
			//Non-List Value or WjsslCSSString (CSS identifier)
			$aResult[] = $oElement;
		}
	}

	protected function allSelectors(&$aResult, $sSpecificitySearch = null) {
		$aDeclarationBlocks = array();
		$this->allDeclarationBlocks($aDeclarationBlocks);
		foreach ($aDeclarationBlocks as $oBlock) {
			foreach ($oBlock->getSelectors() as $oSelector) {
				if ($sSpecificitySearch === null) {
					$aResult[] = $oSelector;
				} else {
					$sComparison = "\$bRes = {$oSelector->getSpecificity()} $sSpecificitySearch;";
					eval($sComparison);
					if ($bRes) {
						$aResult[] = $oSelector;
					}
				}
			}
		}
	}

}
