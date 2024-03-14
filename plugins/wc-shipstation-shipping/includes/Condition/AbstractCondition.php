<?php

/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Condition;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\AbstractCondition')):

abstract class AbstractCondition
{
	protected $optionsOperator;
	protected $itemsOperator;

	public function __construct()
	{
		$this->reset();
	}

	public function reset()
	{
		$this->optionsOperator = 'and';
		$this->itemsOperator = 'and';
	}

	public function setOptionsOperator($optionsOperator)
	{
		$this->optionsOperator = $this->parseOperator($optionsOperator);
	}

	public function setItemsOperator($itemsOperator)
	{
		$this->itemsOperator = $this->parseOperator($itemsOperator);
	}
	
	protected function isMatched($operator, $numberOfMatches, $numberOfItems)
	{
		$isMatched = false;
	
		if ($operator == 'or' && $numberOfMatches > 0) {
			$isMatched = true;
		} else if ($numberOfMatches == $numberOfItems) {
			$isMatched = true;
		}
			
		return $isMatched;
	}

	private function parseOperator($operator)
	{
		return strtolower($operator) == 'or' ? 'or' : 'and';
	}
	
	public abstract function getType();
	public abstract function getAvailableOptions();
	public abstract function match(array $items, array $options);
}

endif;