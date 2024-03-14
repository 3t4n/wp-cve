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
$label = $this->data->get('label', '');
$feature_label = $this->data->get('feature_label', '');
if ($feature_label)
{
	$label = $feature_label;
}
?>
<a href="#" data-fpf-modal-item="<?php esc_attr_e(fpframework()->_($label)); ?>" class="fpf-button upgrade fpf-modal-opener" data-fpf-modal="#fpfUpgradeToPro" data-fpf-plugin="<?php esc_attr_e($this->data->get('plugin')); ?>">
	<i class="dashicons dashicons-lock"></i>
	<span class="text"><?php esc_html_e(fpframework()->_('FPF_UPGRADE_TO_PRO')); ?></span>
</a>