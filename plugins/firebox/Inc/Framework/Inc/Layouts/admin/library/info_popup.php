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
?>
<div class="fpf-template-library-item-info">
	<div class="item-description"></div>
	<div class="template-details">
		<div class="items grid-x grid-margin-x grid-margin-y">
			<div class="cell small-4 category">
				<div class="title is-grey"><?php esc_html_e($this->data->get('category_label')); ?>:</div>
				<div class="content"></div>
			</div>
			<div class="cell small-4 solution">
				<div class="title is-grey"><?php esc_html_e(fpframework()->_('FPF_SOLUTIONS')); ?>:</div>
				<div class="content"></div>
			</div>
			<div class="cell small-4 event">
				<div class="title is-grey"><?php esc_html_e(fpframework()->_('FPF_EVENTS')); ?>:</div>
				<div class="content"></div>
			</div>
		</div>
	</div>
	<div class="template-details compatibility-details">
		<div class="header-items">
			<div class="grid-x grid-margin-x grid-margin-y">
				<div class="cell small-4"><?php esc_html_e(fpframework()->_('FPF_REQUIREMENTS')); ?></div>
				<div class="cell small-4"><?php esc_html_e(fpframework()->_('FPF_DETECTED')); ?></div>
				<div class="cell small-4"><?php esc_html_e(fpframework()->_('FPF_CHECK')); ?></div>
			</div>
		</div>
		<div class="dependency-items"></div>
		<div class="template dependency-item grid-x grid-margin-x grid-margin-y">
			<div class="cell small-4 requirement"></div>
			<div class="cell small-4 detected"></div>
			<div class="cell small-4 value">
				<svg class="checkmark is-hidden" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 8.65556L6.89552 14.5L19 1.5" stroke="#82DE78" stroke-width="2" stroke-linecap="round"/></svg>
			</div>
		</div>
	</div>
</div>