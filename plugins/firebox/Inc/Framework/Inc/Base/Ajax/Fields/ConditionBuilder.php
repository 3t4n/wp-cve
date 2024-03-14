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

namespace FPFramework\Base\Ajax\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;

class ConditionBuilder
{
	public function __construct()
	{
		add_action('wp_ajax_fpf_conditionbuilder_init_load', [$this, 'init_load']);
		
		add_action('wp_ajax_fpf_conditionbuilder_options', [$this, 'options']);
		
		add_action('wp_ajax_fpf_conditionbuilder_add', [$this, 'add']);
	}
	
	/**
	 * On initial page load.
	 * 
	 * @return  void
	 */
	public function init_load()
	{
		if (!current_user_can('manage_options'))
		{
			return;
		}
		
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : null;

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return;
        }

		if (!isset($_POST['data']))
		{
			return;
		}

		if (!$data = json_decode(stripslashes($_POST['data']), true))
		{
			return;
		}

		$payload = [
			'plugin' => isset($data['plugin']) ? sanitize_text_field($data['plugin']) : null,
			'data' => isset($data['data']) ? sanitize_text_field($data['data']) : [],
			'name' => isset($data['name']) ? sanitize_text_field($data['name']) : [],
			'include_rules' => isset($data['include_rules']) ? json_decode(sanitize_text_field($data['include_rules']), true) : null,
			'exclude_rules' => isset($data['exclude_rules']) ? json_decode(sanitize_text_field($data['exclude_rules']), true) : null,
			'exclude_rules_pro' => isset($data['exclude_rules_pro']) ? sanitize_text_field($data['exclude_rules_pro']) === '1' : false
		];

		echo \FPFramework\Base\Conditions\ConditionBuilder::initLoad($payload);
		wp_die();
	}
	
	/**
	 * When we select a condition, retrieve its HTML.
	 * 
	 * @return  void
	 */
	public function options()
	{
		if (!current_user_can('manage_options'))
		{
			return;
		}

		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : null;

        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return;
        }

		if (!isset($_POST['data']))
		{
			return;
		}

		if (!$data = json_decode(stripslashes($_POST['data']), true))
		{
			return;
		}

		$exclude_rules = isset($data['exclude_rules']) ? json_decode(sanitize_text_field($data['exclude_rules']), true) : [];

		$name = isset($data['name']) ? sanitize_text_field($data['name']) : null;
		
		// Exclude rules that shouldn't be used
		if (is_array($exclude_rules) && in_array($name, $exclude_rules))
		{
			wp_die();
			return;
		}

		$conditionItemGroup = isset($data['conditionItemGroup']) ? sanitize_text_field($data['conditionItemGroup']) : null;
		$plugin = isset($data['plugin']) ? sanitize_text_field($data['plugin']) : null;

		echo \FPFramework\Base\Conditions\ConditionBuilder::renderOptions($name, $conditionItemGroup, null, $plugin);
		wp_die();
	}
	
	/**
	 * When we add a new condition item or group, retrieve its HTML.
	 * 
	 * @return  void
	 */
	public function add()
	{
		if (!current_user_can('manage_options'))
		{
			return;
		}
		
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : null;
		
        // verify nonce
        if (!$verify = wp_verify_nonce($nonce, 'fpf_js_nonce'))
        {
            return;
        }

		if (!isset($_POST['data']))
		{
			return;
		}

		if (!$data = json_decode(stripslashes($_POST['data']), true))
		{
			return;
		}

		$conditionItemGroup = isset($data['conditionItemGroup']) ? sanitize_text_field($data['conditionItemGroup']) : null;
		$plugin = isset($data['plugin']) ? sanitize_text_field($data['plugin']) : null;
		$groupKey = isset($data['groupKey']) ? intval($data['groupKey']) : 0;
		$conditionKey = isset($data['conditionKey']) ? intval($data['conditionKey']) : 0;
		$include_rules = isset($data['include_rules']) ? json_decode(sanitize_text_field($data['include_rules']), true) : [];
		$exclude_rules = isset($data['exclude_rules']) ? json_decode(sanitize_text_field($data['exclude_rules']), true) : [];
		$exclude_rules_pro = isset($data['exclude_rules_pro']) ? sanitize_text_field($data['exclude_rules_pro']) === '1' : false;

		$conditionItem = \FPFramework\Base\Conditions\ConditionBuilder::add($conditionItemGroup, $groupKey, $conditionKey, null, $include_rules, $exclude_rules, $exclude_rules_pro, $plugin);
		
		// Adding a single condition item
		$addingNewGroup = isset($data['addingNewGroup']) ? (bool) $data['addingNewGroup'] : false;
		if (!$addingNewGroup)
		{
			echo $conditionItem;
			wp_die();
		}

		$payload = [
			'name' => $conditionItemGroup,
			'plugin' => $plugin,
			'groupKey' => $groupKey,
			'groupConditions' => ['enabled' => 1],
			'include_rules' => $include_rules,
			'exclude_rules' => $exclude_rules,
			'exclude_rules_pro' => $exclude_rules_pro,
			'condition_items_parsed' => [$conditionItem],
		];

		// Adding a condition group
		echo fpframework()->renderer->fields->render('conditionbuilder/group', $payload, true);
		wp_die();
	}
}