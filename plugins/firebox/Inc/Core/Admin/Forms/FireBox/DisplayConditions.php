<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin\Forms\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FireBox\Core\Helpers\BoxHelper;

class DisplayConditions
{
	/**
	 * Holds the Display Conditions Settings
	 * 
	 * @return  array
	 */
	public function getSettings()
	{
		$settings = [
			'title' => firebox()->_('FB_DISPLAY_CONDITIONS'),
			'content' => [
				'general' => [
					'fields' => [
						[
							'name' => 'display_conditions_type',
							'type' => 'Toggle',
							'description' => firebox()->_('FB_DISPLAY_CAMPAIGN_DESC'),
							'default' => 'all',
							'choices' => [
								'all' => firebox()->_('FB_DISPLAY_CONDITIONS_TYPE_ALL_PAGES'),
								'mirror' => firebox()->_('FB_DISPLAY_CONDITIONS_TYPE_MIRROR'),
								'custom' => firebox()->_('FB_DISPLAY_CONDITIONS_TYPE_CUSTOM')
							]
						],
						[
							'name' => 'mirror_box',
							'type' => 'Dropdown',
							'label' => firebox()->_('FB_CAMPAIGN'),
							'description' => firebox()->_('FB_MIRROR_CAMPAIGN_SELECT_DESC'),
							'default' => 1,
							'class' => ['fpf-flex-row-fields'],
							'description_class' => ['bottom'],
							'input_class' => ['fullwidth'],
							'choices' => BoxHelper::getAllMirrorBoxesExceptID(get_the_ID()),
							'showon' => '[display_conditions_type]:mirror'
						],
						[
							'name' => 'rules',
							'type' => 'ConditionBuilder',
							'showon' => '[display_conditions_type]:custom',
							'plugin' => 'FPF_FIREBOX',
							
							'exclude_rules' => [
								'Date\Date',
								'Date\Day',
								'Date\Month',
								'Date\Time',
								'WP\UserID',
								'WP\UserGroup',
								'WP\Tags',
								'WP\Categories',
								'WP\CustomPostTypes',
								'WP\Language',
								'Device',
								'Browser',
								'OS',
								'Geo\City',
								'Geo\Country',
								'Geo\Region',
								'Geo\Continent',
								'FireBox\Popup',
								'FireBox\Form',
								'Referrer',
								'IP',
								'Pageviews',
								'Cookie',
								'PHP',
								'TimeOnSite',
								'NewReturningVisitor',
								'WooCommerce\CartContainsProducts',
								'WooCommerce\CartContainsXProducts',
								'WooCommerce\CartValue',
								'WooCommerce\Product',
								'WooCommerce\PurchasedProduct',
								'WooCommerce\LastPurchasedDate',
								'WooCommerce\CurrentProductPrice',
								'WooCommerce\TotalSpend',
								'WooCommerce\CurrentProductStock',
								'WooCommerce\Category',
								'WooCommerce\CategoryView',
								'EDD\CartContainsProducts',
								'EDD\CartContainsXProducts',
								'EDD\CartValue',
								'EDD\Product',
								'EDD\PurchasedProduct',
								'EDD\LastPurchasedDate',
								'EDD\CurrentProductPrice',
								'EDD\TotalSpend',
								'EDD\CurrentProductStock',
								'EDD\Category',
								'EDD\CategoryView',
							],
							'exclude_rules_pro' => true,
							
							
						],
					]
				]
			]
		];

		return apply_filters('firebox/box/settings/display_conditions/edit', $settings);
	}
}