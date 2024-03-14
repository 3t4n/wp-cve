<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Conditions extends SearchDropdown
{
	/**
     * List of available conditions
     *
     * @var array
     */
    public static $conditions = [
		'FPF_DATETIME' => [
			'Date\Date' => 'FPF_DATE',
			'Date\Day' => 'FPF_DAY_OF_WEEK',
			'Date\Month' => 'FPF_MONTH',
			'Date\Time' => 'FPF_TIME'
		],
		'WordPress' => [
			'WP\UserID' => 'FPF_USER',
			'WP\Menu' => 'FPF_MENU',
			'WP\UserGroup' => 'FPF_USER_GROUP',
			'WP\Posts' => 'FPF_POST',
			'WP\Pages' => 'FPF_PAGE',
			'WP\Tags' => 'FPF_POST_TAG',
			'WP\Categories' => 'FPF_POST_CATEGORY',
			'WP\CustomPostTypes' => 'FPF_CPT',
			'WP\Homepage' => 'FPF_HOMEPAGE',
		],
		'FPF_TECHNOLOGY' => [
			'Device' => 'FPF_DEVICES',
			'Browser' => 'FPF_BROWSERS',
			'OS' => 'FPF_OS'
		],
		'FPF_GEOLOCATION' => [
			'Geo\City' => 'FPF_CITY',
			'Geo\Country' => 'FPF_COUNTRY',
			'Geo\Region' => 'FPF_REGION',
			'Geo\Continent' => 'FPF_CONTINENT'
		],
		'FPF_INTEGRATIONS' => [
			'sitepress-multilingual-cms/sitepress.php#WP\Language' => 'FPF_WPML_LANGUAGE'
		],
		'FPF_FIREBOX' => [
			'firebox/firebox.php#FireBox\Popup'=> 'FPF_FIREBOX_VIEWED_ANOTHER_CAMPAIGN',
			'firebox/firebox.php#FireBox\Form'=> 'FPF_FIREBOX_SUBMITTED_FORM',
		],
		'FPF_WOOCOMMERCE' => [
			'woocommerce/woocommerce.php#WooCommerce\CartContainsProducts'=> 'FPF_WOOCOMMERCE_PRODUCTS_IN_CART',
			'woocommerce/woocommerce.php#WooCommerce\CartContainsXProducts'=> 'FPF_WOOCOMMERCE_CART_ITEMS_COUNT',
			'woocommerce/woocommerce.php#WooCommerce\CartValue'=> 'FPF_WOOCOMMERCE_AMOUNT_IN_CART',
			'woocommerce/woocommerce.php#WooCommerce\Product'=> 'FPF_WOOCOMMERCE_CURRENT_PRODUCT',
			'woocommerce/woocommerce.php#WooCommerce\PurchasedProduct'=> 'FPF_WOOCOMMERCE_PURCHASED_PRODUCT',
			'woocommerce/woocommerce.php#WooCommerce\LastPurchasedDate'=> 'FPF_WOOCOMMERCE_LAST_PURCHASED_DATE',
			'woocommerce/woocommerce.php#WooCommerce\CurrentProductPrice'=> 'FPF_WOOCOMMERCE_CURRENT_PRODUCT_PRICE',
			'woocommerce/woocommerce.php#WooCommerce\CurrentProductStock'=> 'FPF_WOOCOMMERCE_CURRENT_PRODUCT_STOCK',
			'woocommerce/woocommerce.php#WooCommerce\TotalSpend'=> 'FPF_WOOCOMMERCE_TOTAL_SPEND',
			'woocommerce/woocommerce.php#WooCommerce\Category'=> 'FPF_WOOCOMMERCE_CURRENT_PRODUCT_CATEGORY',
			'woocommerce/woocommerce.php#WooCommerce\CategoryView'=> 'FPF_WOOCOMMERCE_CATEGORY',
		],
		'FPF_EDD' => [
			'easy-digital-downloads/easy-digital-downloads.php#EDD\CartContainsProducts|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\CartContainsProducts'=> 'FPF_EDD_PRODUCTS_IN_CART',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\CartContainsXProducts|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\CartContainsXProducts'=> 'FPF_EDD_CART_ITEMS_COUNT',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\CartValue|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\CartValue'=> 'FPF_EDD_AMOUNT_IN_CART',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\Product|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\Product'=> 'FPF_EDD_CURRENT_PRODUCT',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\PurchasedProduct|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\PurchasedProduct'=> 'FPF_EDD_PURCHASED_PRODUCT',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\LastPurchasedDate|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\LastPurchasedDate'=> 'FPF_EDD_LAST_PURCHASED_DATE',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\CurrentProductPrice|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\CurrentProductPrice'=> 'FPF_EDD_CURRENT_PRODUCT_PRICE',
			'edd-purchase-limit/edd-purchase-limit.php#EDD\CurrentProductStock'=> 'FPF_EDD_CURRENT_PRODUCT_STOCK',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\TotalSpend|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\TotalSpend'=> 'FPF_EDD_TOTAL_SPEND',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\Category|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\Category'=> 'FPF_EDD_CURRENT_PRODUCT_CATEGORY',
			'easy-digital-downloads/easy-digital-downloads.php#EDD\CategoryView|easy-digital-downloads-pro/easy-digital-downloads.php#EDD\CategoryView'=> 'FPF_EDD_CATEGORY',
		],
		'FPF_ADVANCED' => [
			'URL' => 'FPF_URL',
			'Referrer' => 'FPF_REFERRER',
			'IP' => 'FPF_IP_ADDRESS',
			'Pageviews' => 'FPF_PAGEVIEWS',
			'Cookie' => 'FPF_COOKIE',
			'PHP' => 'FPF_PHP',
			'TimeOnSite' => 'FPF_TIMEONSITE',
			'NewReturningVisitor' => 'FPF_NEW_RETURNING_VISITOR'
		]
	];

	/**
	 * Set specific field options
	 * 
	 * @param   array  $options
	 * 
	 * @return  void
	 */
	protected function setFieldOptions($options)
	{
		parent::setFieldOptions($options);

		$this->field_options = array_merge($this->field_options, [
			'placeholder' => fpframework()->_('FPF_SELECT_CONDITION'),
			'search_query_placeholder' => fpframework()->_('FPF_TYPE_A_CONDITION'),
			'control_inner_class' => ['fpf-min-width-320', 'fpf-conditions-field'],
			'include_rules' => isset($options['include_rules']) ? $options['include_rules'] : [],
			'exclude_rules' => isset($options['exclude_rules']) ? $options['exclude_rules'] : [],
			'exclude_rules_pro' => isset($options['exclude_rules_pro']) ? $options['exclude_rules_pro'] : false,
			'multiple' => false,
			'local_search' => true,
			'items' => $this->getChoices()
		]);
	}

	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 */
	protected function getChoices()
	{
		$include_rules = empty($this->options['include_rules']) ? [] : $this->options['include_rules'];
		$exclude_rules = empty($this->options['exclude_rules']) ? [] : $this->options['exclude_rules'];

		$groups = [];

		foreach (self::$conditions as $conditionGroup => $conditions)
		{
			$childs = [];

			foreach ($conditions as $conditionName => $condition)
			{
				$skip_condition = false;

				/**
				 * Checks conditions that have multiple components as dependency.
				 * Check for multiple given components for a particular condition, i.e. acymailing can be loaded via com_acymailing or com_acym
				 */
				$multiple_components = explode('|', $conditionName);
				if (count($multiple_components) >= 2)
				{
					$foundMultiple = false;

					foreach ($multiple_components as $component)
					{
						if (!$tempConditionName = $this->getConditionName($component))
						{
							continue;
						}

						$conditionName = $tempConditionName;
						$foundMultiple = true;
					}

					$skip_condition = !$foundMultiple;
				}

				// If the condition must be skipped, skip it
				if ($skip_condition)
				{
					continue;
				}

				// Checks for a single condition whether its component exists and can be used.
				if (!$conditionName = $this->getConditionName($conditionName))
				{
					continue;
				}

				// If its excluded, skip it
				if (!$this->options['exclude_rules_pro'] && !empty($exclude_rules) && in_array($conditionName, $exclude_rules))
				{
					continue;
				}

				// If its not included, skip it
				if (!empty($include_rules) && !in_array($conditionName, $include_rules))
				{
					continue;
				}

				// Add condition to the group
				$childs[$conditionName] = fpframework()->_($condition);
			}

			if (!empty($childs))
			{
				$groups[fpframework()->_($conditionGroup)] = $childs;
			}
		}

		return $groups;
	}

	/**
	 * Returns the parsed condition name.
	 * 
	 * i.e. $condition: firebox/firebox.php#Foo\FireBox
	 * will return: Foo\FireBox
	 * 
	 * @param   string  $condition
	 * 
	 * @return  mixed
	 */
	private function getConditionName($condition)
	{
		$conditionNameParts = explode('#', $condition);

		if (count($conditionNameParts) >= 2 && !\is_plugin_active($conditionNameParts[0]))
		{
			return false;
		}
		
		return isset($conditionNameParts[1]) ? $conditionNameParts[1] : $conditionNameParts[0];
	}
}