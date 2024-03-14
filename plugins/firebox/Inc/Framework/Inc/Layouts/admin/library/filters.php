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
if (!$filters = $this->data->get('filters', []))
{
    return;
}
?>
<div class="fpf-library-filters">
    <?php
    foreach ($filters as $key => $filter)
    {
        ?>
        <div class="fpf-library-filter-item open" data-type="<?php esc_attr_e($key); ?>">
            <div class="fpf-library-filter-item-label">
                <span><?php esc_html_e($filter->label); ?></span>
                <svg class="fpf-library-filter-item-toggle" width="10" height="7" viewBox="0 0 10 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" d="M9 1.5L5.70711 4.79289C5.31658 5.18342 4.68342 5.18342 4.29289 4.79289L1 1.5" stroke="currentColor" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="fpf-library-filter-choices">
            <?php
            foreach ($filter->items as $_key => $label)
            {
                $choice_item_key = 'fpf_library_filters' . $key . '_filter_' . $_key;
                ?>
                <div class="fpf-library-filter-choice-item">
                    <input type="checkbox" class="fpf-library-filter-choice-item-checkbox" id="<?php esc_attr_e($choice_item_key); ?>" value="<?php esc_attr_e($label); ?>" />
                    <label for="<?php esc_attr_e($choice_item_key); ?>"><?php esc_html_e($label); ?></label>
                </div>
                <?php
            }
            ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>