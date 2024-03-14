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
$value = $this->data->get('value', '');
$value = is_array($value) || is_object($value) ? wp_json_encode($value) : $value;
?>
<div class="fpf-conditionbuilder-wrapper">
    <input type="hidden" name="<?php esc_attr_e($this->data->get('name')); ?>" value="<?php echo esc_attr($value); ?>" />
    <div class="fpf-conditionbuilder"
        data-plugin="<?php esc_attr_e($this->data->get('plugin')); ?>"
        data-exclude_rules_pro="<?php esc_attr_e($this->data->get('exclude_rules_pro')); ?>"
        data-include-rules="<?php esc_attr_e(wp_json_encode($this->data->get('include_rules'))); ?>"
        data-exclude-rules="<?php esc_attr_e(wp_json_encode($this->data->get('exclude_rules'))); ?>">
        <div class="fpf-conditionbuilder-initial-message">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="14px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <circle cx="50" cy="50" fill="none" stroke="#333" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                </circle>
            </svg>
            <?php esc_html_e(fpframework()->_('FPF_DISPLAY_CONDITIONS_LOADING')); ?>
        </div>
        <div class="fpf-conditionbuilder-groups"></div>
        <div class="actions">
            <a class="fpf-conditionbuilder-button fpf-cb-add-new-group" href="#" title="<?php esc_html_e(fpframework()->_('FPF_CB_NEW_CONDITION_GROUP')); ?>">
                <span class="dashicons dashicons-plus"></span>
                <span class="text"><?php esc_html_e(fpframework()->_('FPF_CB_NEW_CONDITION_GROUP')); ?></span>
                <svg class="loading" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="14px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                    <circle cx="50" cy="50" fill="none" stroke="#333" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                        <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                    </circle>
                </svg>
            </a>
        </div>
    </div>
</div>