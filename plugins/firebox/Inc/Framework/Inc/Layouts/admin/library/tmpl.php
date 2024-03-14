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
$items_payload = [
    'create_new_template_link' => $this->data->get('create_new_template_link'),
    'blank_template_label' => $this->data->get('blank_template_label'),
];
$footer_payload = [
    'plugin_name' => $this->data->get('plugin_name'),
    'create_new_template_link' => $this->data->get('create_new_template_link')
];
?>
<div class="fpf-library-page" data-preview-url="<?php esc_attr_e($this->data->get('preview_url')); ?>">
    <?php fpframework()->renderer->admin->render('library/sidebar'); ?>
    <div class="fpf-library-body">
        <?php
            fpframework()->renderer->admin->render('library/toolbar');
            fpframework()->renderer->admin->render('library/noresults');
            fpframework()->renderer->admin->render('library/items', $items_payload);
            fpframework()->renderer->admin->render('library/footer', $footer_payload);
        ?>
    </div>
</div>