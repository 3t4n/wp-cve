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

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$groupConditions = json_decode(wp_json_encode($this->data->get('groupConditions', [])), true);
$condition_items_parsed = $this->data->get('condition_items_parsed', null);
$plugin = $this->data->get('plugin');
$name = $this->data->get('name');
$groupKey = $this->data->get('groupKey');
$include_rules = $this->data->get('include_rules', []);
$exclude_rules = $this->data->get('exclude_rules', []);
$exclude_rules_pro = $this->data->get('exclude_rules_pro');
?>
<div class="fpf-conditionbuilder-group" data-key="<?php esc_attr_e($groupKey); ?>">
    <div class="fpf-conditionbuilder-item-toolbar group">
        <div class="show">
            <?php esc_html_e(fpframework()->_('FPF_CB_SHOW_WHEN')); ?>
            <select name="<?php esc_attr_e($name); ?>[<?php esc_attr_e($groupKey); ?>][matching_method]">
                <option <?php echo (isset($groupConditions['matching_method']) && $groupConditions['matching_method'] == 'all') ? 'selected ' : ''; ?>value="all"><?php esc_html_e(strtolower(fpframework()->_('FPF_ALL'))); ?></option>
                <option <?php echo (isset($groupConditions['matching_method']) && $groupConditions['matching_method'] == 'any') ? 'selected ' : ''; ?>value="any"><?php esc_html_e(strtolower(fpframework()->_('FPF_ANY'))); ?></option>
            </select>
            <?php esc_html_e(fpframework()->_('FPF_CB_OF_THE_CONDITIONS_MATCH')); ?>
        </div>
        <div class="fpf-conditionbuilder-item-buttons">
            <div class="links">
                <a class="fpf-button only-icon transparent remove removeGroupCondition" href="#" title="<?php esc_attr_e(fpframework()->_('FPF_CB_TRASH_CONDITION_GROUP')); ?>"><span class="dashicons dashicons-trash"></span></a>
            </div>
            <div class="toggle-status" title="<?php esc_attr_e(fpframework()->_('FPF_CB_TOGGLE_RULE_GROUP_STATUS')); ?>">
                <?php
				$checked = isset($groupConditions['enabled']) && (string) $groupConditions['enabled'] == '1';
				echo \FPFramework\Helpers\HTML::renderFPToggle([
					'input_class' => ['size-small'],
					'name' => $name . '[' . $groupKey . '][enabled]',
					'value' => $checked
				]);
                ?>
            </div>
        </div>
    </div>
    <div class="fpf-conditionbuilder-items">
        <?php
        // Array of conditions items in HTML format
        if (isset($condition_items_parsed) && is_array($condition_items_parsed) && !empty($condition_items_parsed))
        {
            foreach ($condition_items_parsed as $html)
            {
                echo $html;
            }
        }
        // Render conditions items in raw format
        else if (isset($groupConditions['rules']))
        {
            foreach ($groupConditions['rules'] as $conditionKey => $condition)
            {
                echo \FPFramework\Base\Conditions\ConditionBuilder::add($name, $groupKey, $conditionKey, (array) $condition, $include_rules, $exclude_rules, $exclude_rules_pro, $plugin);
            }
        }
        ?>
    </div>
    <div class="item-group-footer text-right">
        <a class="fpf-button fpf-cb-add-new-group" href="#" title="<?php esc_attr_e(fpframework()->_('FPF_CB_ADD_CONDITION')); ?>">
            <span class="dashicons dashicons-plus"></span>
            <span class="text"><?php esc_html_e(fpframework()->_('FPF_CB_ADD_CONDITION')); ?></span>
            <svg class="loading" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="14px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <circle cx="50" cy="50" fill="none" stroke="#333" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                </circle>
            </svg>
        </a>
    </div>
</div>