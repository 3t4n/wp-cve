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
<div class="fpf-library-list">
    <div class="fpf-library-item blank_popup">
        <span class="fpf-library-item-wrap">
            <a class="parent" href="<?php echo esc_url($this->data->get('create_new_template_link', '')); ?>">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                    <line x1="12" y1="7.5" x2="12" y2="16.5" stroke="currentColor" stroke-linecap="round"/>
                    <line x1="16.5" y1="12" x2="7.5" y2="12" stroke="currentColor" stroke-linecap="round"/>
                </svg>
                <span class="title"><?php esc_html_e($this->data->get('blank_template_label')); ?></span>
                <span class="description"><?php esc_html_e(fpframework()->_('FPF_START_FROM_SCRATCH')); ?></span>
            </a>
        </span>
    </div>
    <?php
    // Skeleton
    for ($i = 0; $i < 15; $i++)
    {
        ?>
        <div class="fpf-library-item skeleton">
            <div class="fpf-library-item-wrap">
                <div></div>
                <div class="actions">
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>