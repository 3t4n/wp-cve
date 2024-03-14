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
$options = $this->data->get('options');
?>
<div class="fpf-conditionbuilder-item" data-key="<?php esc_attr_e($this->data->get('conditionKey')); ?>">
    <div class="fpf-conditionbuilder-item-toolbar">
        <div class="fpf-conditionbuilder-dropdown"><?php echo $this->data->get('conditions'); ?></div>
        <div class="fpf-conditionbuilder-item-buttons">
            <svg class="loading" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <circle cx="50" cy="50" fill="none" stroke="#dddddd" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
                    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
                </circle>
            </svg>
            <div class="links">
                <a class="fpf-button only-icon transparent remove fpf-cb-remove-condition" href="#" title="<?php esc_html_e(fpframework()->_('FPF_CB_TRASH_CONDITION')); ?>"><span class="dashicons dashicons-trash"></span></a>
                <a class="fpf-button only-icon transparent fpf-cb-add-new-group" href="#" title="<?php esc_html_e(fpframework()->_('FPF_CB_ADD_CONDITION')); ?>"><span class="dashicons dashicons-plus-alt2"></span></a>
            </div>
            <div class="toggle-status" title="<?php esc_html_e(fpframework()->_('FPF_CB_TOGGLE_RULE_STATUS')); ?>">
                <?php
				echo \FPFramework\Helpers\HTML::renderFPToggle([
					'input_class' => ['size-small'],
					'name' => $this->data->get('name') . '[enabled]',
					'value' => $this->data->get('enabled')
				]);
                ?>
            </div>
        </div>
    </div>
    <div class="fpf-conditionbuilder-item-content">
        <?php echo $options ? $options : '<div class="select-condition-message">' . esc_html(fpframework()->_('FPF_CB_SELECT_CONDITION_GET_STARTED')) . '</div>'; ?>
    </div>
</div>