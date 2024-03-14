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
<!-- Library Toolbar -->
<div class="fpf-library-toolbar">
    <!-- Left -->
    <div class="toolbar-left">
        <div class="fpf-library-search">
            <input type="search" id="fpf_search_template" data-search="true" placeholder="<?php esc_attr_e(fpframework()->_('FPF_SEARCH')); ?>..." name="fpf-library-search">
            <svg class="fpf-library-search-icon" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="7.5" cy="7.5" r="6.5" stroke="currentColor" stroke-width="2"/>
                <path d="M12.207 12.2075L15.9994 15.9999" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>
    <!-- /Left -->
    <!-- Right -->
    <div class="toolbar-right">
        <a href="#" class="item fpf-library-favorite-icon fpf-library-view-favorites">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.902 6.62124C14.3943 9.04222 11.0187 11.1197 7.99845 14C4.97819 11.1197 1.60265 9.04223 1.09492 6.62125C0.231957 2.50649 5.47086 -0.0322559 7.99845 4.12617C10.7204 -0.0322523 15.7649 2.50648 14.902 6.62124Z" fill="currentColor" stroke="currentColor" stroke-linejoin="round"/>
            </svg>
            <?php esc_html_e(fpframework()->_('FPF_MY_FAVORITES')); ?>
        </a>
        <div class="item sorting-selector-item">
            <div class="sort-wrapper">
                <div class="sorting-selected-label">
                    <span class="selected-label"><?php esc_html_e(fpframework()->_('FPF_TRENDING')); ?></span>
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 1L5.70711 4.29289C5.31658 4.68342 4.68342 4.68342 4.29289 4.29289L1 0.999999" stroke="currentColor" stroke-linecap="round"/></svg>
                </div>
                <ul class="sorting-selector-items">
                    <li data-value="featured"><?php esc_html_e(fpframework()->_('FPF_FEATURED')); ?></li>
                    <li data-value="popularity"><?php esc_html_e(fpframework()->_('FPF_POPULAR')); ?></li>
                    <li data-value="trending" class="selected"><?php esc_html_e(fpframework()->_('FPF_TRENDING')); ?></li>
                    <li data-value="date"><?php esc_html_e(fpframework()->_('FPF_NEWEST')); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Right -->
</div>
<!-- /Library Toolbar -->
<!-- Library Selected Filters -->
<div class="fpf-library-selected-filters-pills-wrapper">
    <div><?php echo wp_kses(fpframework()->_('FPF_SHOWING_RESULTS_FOR'), \FPFramework\Helpers\WPHelper::getAllowedHTMLTags()); ?></div>
    <div class="fpf-library-selected-filters-pills"></div>
    <div class="fpf-library-filter-template">
        <div class="filter" data-filter="">
            <span class="filter-label"></span>
            <svg class="fpf-library-filter-pill-item-remove" width="14" height="14" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="5" cy="5" r="4.5" stroke="currentColor"/>
                <rect x="7" y="6.5" width="0.707105" height="4.94973" transform="rotate(135 7 6.5)" fill="currentColor"/>
                <rect width="0.707105" height="4.94973" transform="matrix(-0.707109 -0.707105 0.707109 -0.707105 3.5 7)" fill="currentColor"/>
            </svg>
        </div>
    </div>
    <a href="#" class="fpf-library-filters-clear-all"><?php esc_html_e(fpframework()->_('FPF_CLEAR_ALL')); ?></a>
</div>
<!-- /Library Selected Filters -->
<!-- Library Messages -->
<div class="fpf-library-messages fpf-alert callout warning is-hidden">
    <span class="fpf-library-messages-text text"></span>
    <button class="fpf-library-messages-hide-btn close-button" aria-label="Dismiss alert" type="button" data-close>
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<!-- /Library Messages -->