<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.tax.tax');

/**
 * Helper class used to pre-load the details of the supported taxes.
 *
 * @since 1.7
 */
abstract class VAPTaxLoader
{
	/**
	 * Cache the loaded taxes to avoid loading
	 * them more than once.
	 *
	 * @var array
	 */
	protected static $taxes = array();

	/**
	 * Loads the details of the specified tax by checking
	 * whether the same details have been already loaded.
	 *
	 * @param 	integer  $id   The tax identifier.
	 * @param 	string   $tag  The language tag to use for translations.
	 *
	 * @return 	object   The tax details.
	 *
	 * @throws 	Exception
	 */
	public static function load($id, $tag = null)
	{
		if (!$tag)
		{
			// get current language tag
			$tag = JFactory::getLanguage()->getTag();
		}

		if (!isset(static::$taxes[$id]))
		{
			// create initial pool
			static::$taxes[$id] = array();
		}

		// make sure the requested tax is not yet
		// contained within the cache pool in the
		// given language
		if (!isset(static::$taxes[$id][$tag]))
		{
			// load and cache result
			static::$taxes[$id][$tag] = static::_load($id, $tag);
		}

		return static::$taxes[$id][$tag];
	}

	/**
	 * Loads the details of the specified tax.
	 *
	 * @param 	integer  $id   The tax identifier.
	 * @param 	string   $tag  The language tag to use for translations.
	 *
	 * @return 	object   The tax details.
	 *
	 * @throws 	Exception
	 */
	protected static function _load($id, $tag)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		// load tax details
		$q->select($dbo->qn('t.id'));
		$q->select($dbo->qn('t.name'));
		$q->select($dbo->qn('t.description'));
		$q->from($dbo->qn('#__vikappointments_tax', 't'));

		// load linked tax rules
		$q->select($dbo->qn('r.id', 'rule_id'));
		$q->select($dbo->qn('r.name', 'rule_name'));
		$q->select($dbo->qn('r.operator', 'rule_operator'));
		$q->select($dbo->qn('r.amount', 'rule_amount'));
		$q->select($dbo->qn('r.cap', 'rule_cap'));
		$q->select($dbo->qn('r.apply', 'rule_apply'));
		$q->select($dbo->qn('r.breakdown', 'rule_breakdown'));
		$q->from($dbo->qn('#__vikappointments_tax_rule', 'r'));

		// filter by given tax
		$q->where($dbo->qn('t.id') . ' = ' . (int) $id);
		// apply strict relation because taxes must specify at least
		// a rule, otherwise there wouldn't be anything to calculate
		$q->where($dbo->qn('t.id') . ' = ' . $dbo->qn('r.id_tax'));

		// sort rules by the specified ordering to properly
		// calculate the resulting taxes
		$q->order($dbo->qn('r.ordering') . ' ASC');

		/**
		 * Trigger hook to allow external plugins to manipulate the query used
		 * to load the tax details through this helper class.
		 *
		 * TIP: any column with an alias that starts with "rule_" will be
		 * automatically injected within the rule instance.
		 *
		 * @param 	mixed  &$query  A query builder object.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onBeforeQueryTax', array(&$q));

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no matching tax
			throw new Exception(sprintf('Tax [%d] not found', (int) $id), 404);
		}

		$tax = array();

		// iterate properties of the first record to check
		// what should be injected within the tax object
		foreach (get_object_vars($rows[0]) as $k => $v)
		{
			// inject any property that DOES NOT start with "rule_"
			if (!preg_match("/^rule_/", $k))
			{
				$tax[$k] = $v;
			}
		}

		// get translator
		$translator = VAPFactory::getTranslator();

		// create tax instance
		$tax = new VAPTax($tax);

		// translate tax details
		$taxLang = $translator->translate('tax', $tax->get('id'), $tag);

		if ($taxLang)
		{
			// update name with translation found
			$tax->set('name', $taxLang->name);
		}

		$rule_ids = array();

		// iterate rules
		foreach ($rows as $row)
		{
			$rule = array();

			// iterate properties of the current record to check
			// what should be injected within the rule object
			foreach (get_object_vars($row) as $k => $v)
			{
				// inject any property that STARTS with "rule_"
				if (preg_match("/^rule_(.+?)$/", $k, $match))
				{
					// use property without "rule_"
					$rule[end($match)] = $v;
				}
			}

			// translate tax rule details
			$taxRuleLang = $translator->translate('taxrule', $rule['id'], $tag);

			if ($taxRuleLang)
			{
				// update name with translation found
				$rule['name'] = $taxRuleLang->name;

				// JSON decode breakdown translation
				$taxRuleLang->breakdown = $taxRuleLang->breakdown ? json_decode($taxRuleLang->breakdown, true) : array();

				if ($rule['breakdown'] && $taxRuleLang->breakdown)
				{
					// decode original breakdown
					$rule['breakdown'] = json_decode($rule['breakdown']);

					foreach ($rule['breakdown'] as $i => $bd)
					{
						// replace name with translation only if not empty and we didn't receive
						// an array, which would mean that we have the default BD
						if (!empty($taxRuleLang->breakdown[$bd->id]) && is_string($taxRuleLang->breakdown[$bd->id]))
						{
							$bd->name = $taxRuleLang->breakdown[$bd->id];
						}

						// update array
						$rule['breakdown'][$i] = $bd;
					}
				}
			}

			// attach rule to parent tax
			$tax->attachRule($rule);
		}

		// return created tax
		return $tax;
	}
}
