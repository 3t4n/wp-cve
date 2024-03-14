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
$button_label = $this->data->get('button_label');
$button_classes = $this->data->get('button_classes');
$vertical = (bool) $this->data->get('vertical', false);
$actions_class = $vertical ? ' grid-x grid-margin-x' : '';
$actions_row_classes = $vertical ? ' large-10 large-offset-2' : '';
?>
<form
	enctype="multipart/form-data"
	action="<?php echo esc_attr($this->data->get('action')); ?>"
	method="<?php echo esc_attr($this->data->get('method')); ?>"
	class="fpf-content-wrapper fpf-form <?php echo esc_attr($this->data->get('class')); ?>">
	<?php
	wp_nonce_field('fpf_form_nonce_' . $this->data->get('section_name'), 'fpf_form_nonce_' . $this->data->get('section_name'));
	settings_fields($this->data->get('section_name'));
	do_settings_sections($this->data->get('section_name'));
	if (get_settings_errors($this->data->get('section_name')))
	{
		?><div class="messages"><?php settings_errors($this->data->get('section_name')); ?></div><?php	
	}
	?>
	<?php
	echo $this->data->get('content', ''); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
	<div class="actions padding-top-1<?php echo esc_attr($actions_class); ?>">
		<div class="<?php echo esc_attr($actions_row_classes); ?>">
			<?php submit_button(fpframework()->_($button_label), $button_classes, 'submit', false); ?>
		</div>
	</div>
</form>