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
$value = $this->data->get('value');
?>
<div class="fpf-widget fpf-rating-wrapper<?php echo esc_attr($this->data->get('css_class')); ?>">
	<?php
	for ($i = 1; $i <= $this->data->get('max_rating'); $i++)
	{
		$label_class = '';
		$rating_id = $this->data->get('id') . '_' . $i;
		
		if ($value && $i <= $value)
		{
			$label_class = 'iconFilled';
		}
		?>
		<input type="radio" class="<?php echo esc_attr($this->data->get('input_class')); ?>" id="<?php echo esc_attr($rating_id); ?>" name="<?php echo esc_attr($this->data->get('name')); ?>"
			value="<?php echo esc_attr($i); ?>"
			<?php if ($value && $i == $value): ?>
			checked
			<?php endif; ?>
			<?php if ($this->data->get('readonly') || $this->data->get('disabled')): ?>
			disabled
			<?php endif; ?>
		/>
		<label for="<?php echo esc_attr($rating_id); ?>" class="<?php echo esc_attr($label_class); ?>" title="<?php echo esc_attr($i); ?> <?php echo esc_attr(sprintf(fpframework()->_('FPF_STAR')) . ($i > 1 ? 's' : '')); ?>">
			<svg class="svg-item">
				<use xlink:href="<?php echo esc_url($this->data->get('icon_url', '')); ?>#fpf-ratings-<?php echo esc_attr($this->data->get('icon')); ?>" />
			</svg>
		</label>
		<?php
	}
	?>
</div>