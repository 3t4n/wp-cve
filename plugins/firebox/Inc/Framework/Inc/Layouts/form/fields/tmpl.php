<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
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
<div id="field-<?php esc_attr_e($this->data->get('id')); ?>" class="fb-form-control-group field-<?php esc_attr_e($this->data->get('id')); ?><?php echo $this->data->get('css_class', []) ? ' ' . esc_attr(implode(' ', $this->data->get('css_class', []))) : ''; ?>" data-field-id="<?php esc_attr_e($this->data->get('id')); ?>">
	<?php if (!$this->data->get('hideLabel')): ?>
	<label class="fb-form-control-label" for="fb-form-input-<?php esc_attr_e($this->data->get('id')); ?>">
		<?php esc_html_e($this->data->get('label')); ?>
		
		<?php if ($this->data->get('required') && $this->data->get('requiredFieldIndication')): ?>
			<span class="fb-form-control-required">*</span>
		<?php endif; ?>
	</label>
	<?php endif; ?>

	<div class="fb-form-control-input">
		<?php echo $this->data->get('input'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<?php if ($this->data->get('description') !== ''): ?>
		<div class="fb-form-control-helptext"><?php esc_html_e($this->data->get('description')); ?></div>
	<?php endif; ?>
</div>