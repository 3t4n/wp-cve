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
<div class="fpf-library-sidebar">
	<!-- Top -->
	<div class="top">
		<div class="flex-container align-center align-middle">
			<div class="fpf-library-sidebar-toggle opener" title="<?php esc_attr_e(fpframework()->_('FPF_OPEN_SIDEBAR')); ?>">
				<svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
					<line x1="6.5" y1="2.5" x2="15.5" y2="2.5" stroke="currentColor" stroke-linecap="round"/>
					<line x1="0.5" y1="2.5" x2="2.5" y2="2.5" stroke="currentColor" stroke-linecap="round"/>
					<line x1="13.5" y1="7.5" x2="15.5" y2="7.5" stroke="currentColor" stroke-linecap="round"/>
					<line x1="0.5" y1="7.5" x2="9.5" y2="7.5" stroke="currentColor" stroke-linecap="round"/>
					<line x1="7.5" y1="12.5" x2="15.5" y2="12.5" stroke="currentColor" stroke-linecap="round"/>
					<line x1="0.5" y1="12.5" x2="3.5" y2="12.5" stroke="currentColor" stroke-linecap="round"/>
					<circle cx="4.5" cy="2.5" r="2" stroke="currentColor"/>
					<circle cx="11.5" cy="7.5" r="2" stroke="currentColor"/>
					<circle cx="5.5" cy="12.5" r="2" stroke="currentColor"/>
				</svg>
			</div>
			<div class="on-sidebar-open flex-container align-center align-middle fpf-library-sidebar-toggle" title="<?php esc_attr_e(fpframework()->_('FPF_CLOSE_SIDEBAR')); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M2.14645 7.64645C1.95118 7.84171 1.95118 8.15829 2.14645 8.35355L5.32843 11.5355C5.52369 11.7308 5.84027 11.7308 6.03553 11.5355C6.2308 11.3403 6.2308 11.0237 6.03553 10.8284L3.20711 8L6.03553 5.17157C6.2308 4.97631 6.2308 4.65973 6.03553 4.46447C5.84027 4.2692 5.52369 4.2692 5.32843 4.46447L2.14645 7.64645ZM14.5 8.5C14.7761 8.5 15 8.27614 15 8C15 7.72386 14.7761 7.5 14.5 7.5V8.5ZM2.5 8.5H14.5V7.5H2.5V8.5Z" fill="currentColor"/>
					<line x1="14.5" y1="3.5" x2="14.5" y2="12.5" stroke="currentColor" stroke-linecap="round"/>
				</svg>
				<span class="top-title"><?php esc_html_e(fpframework()->_('FPF_FILTERS')); ?></span>
			</div>
		</div>
		<a href="#" class="fpf-library-filters-clear-all"><?php esc_html_e(fpframework()->_('FPF_CLEAR_ALL')); ?></a>
	</div>
	<!-- /Top -->
	<!-- Filters -->
	<div class="fpf-library-sidebar-filters">
		<?php esc_html_e(fpframework()->_('FPF_LOADING_FILTERS')); ?>
	</div>
	<!-- /Filters -->
</div>