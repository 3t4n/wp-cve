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

if (!class_exists(__NAMESPACE__ . '\\AbstractItemCondition')):

abstract class AbstractItemCondition extends AbstractCondition
{
	protected $optionsOperator;
	protected $itemsOperator;

	public function match(array $items, array $options)
	{
		if (empty($items) || empty($options)) {
			return false;
		}
	
		$numberOfItems = count($items);
		$numberOfMatches = 0;
	
		foreach ($items as $item) {
			if (isset($item['data']) && is_object($item['data']) && $this->matchItem($item, $options)) {
				$numberOfMatches++;
	
				if ($this->itemsOperator == 'or') {
					break;
				}
			}
		}
	
		return $this->isMatched($this->itemsOperator, $numberOfMatches, $numberOfItems);
	}
	
	protected abstract function matchItem(array $item, array $options);
}

endif;