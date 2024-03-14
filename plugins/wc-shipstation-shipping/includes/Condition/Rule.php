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

if (!class_exists(__NAMESPACE__ . '\\Rule')):

class Rule
{
	protected $conditions;

	public function __construct()
	{
		$this->conditions = array();
	}

	public function addCondition(AbstractCondition $condition)
	{
		$this->conditions[$condition->getType()] = $condition;
	}

	public function match(array $items, array $rules)
	{
		$matchResults = array();

		foreach ($rules as $ruleName => $params) {
			if (!empty($params) && count($params) >= 2) {
				$conditionType = $params[0];
				if (isset($this->conditions[$conditionType])) {
					$condition = &$this->conditions[$conditionType];
					$condition->reset();
					
					if (count($params) > 2) {
						$condition->setItemsOperator($params[2]);
					}
					if (count($params) > 3) {
						$condition->setOptionsOperator($params[3]);
					}
					
					$options = &$params[1];
					$matchResults[$ruleName] = $condition->match($items, $options);
				}
			}
		}

		return $matchResults;
	}
}

endif;