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
<div class="fpf-widget fpf-rating-wrapper half<?php echo esc_attr($this->data->get('css_class') . ' ' . $this->data->get('size')); ?>">
	<?php
	$counter = 0;
	$prev_counter = 0;

	// initial value value
	$rating_value = 0.5;

	for ($i = 0; $i < $this->data->get('max_rating'); $i++)
	{
		$label_class = '';

		// wrapper start - for half rating item (half and full star)
		if ($counter % 2 == 0)
		{
			$prev_counter = $counter;
			?><span class="rating_item_group"><?php
		}

		$rating_item_type = fmod($rating_value, 1) == 0.5 ? 'half' : 'full';
		$rating_id = $this->data->get('id') . '_' . $i . '_' . $rating_item_type;

		if ($value && $rating_value <= $value)
		{
			$label_class = ' iconFilled';
		}
		?>
		<input type="radio"
			class="<?php echo esc_attr($this->data->get('input_class')); ?>"
			id="<?php echo esc_attr($rating_id); ?>"
			name="<?php echo esc_attr($this->data->get('name')); ?>"
			value="<?php echo esc_attr($rating_value); ?>"
			<?php if ($value && $rating_value == $value): ?>
			checked
			<?php endif; ?>
			<?php if ($this->data->get('readonly') || $this->data->get('disabled')): ?>
			disabled
			<?php endif; ?>
		/>
		<label class="<?php echo esc_attr($rating_item_type . $label_class); ?>" for="<?php echo esc_attr($rating_id); ?>" title="<?php echo esc_attr($rating_value); ?> <?php echo esc_attr(sprintf(fpframework()->_('FPF_STAR')) . ($rating_value > 1 ? 's' : '')); ?>">
			<svg class="svg-item">
				<use xlink:href="<?php echo esc_url($this->data->get('icon_url', '')); ?>#fpf-ratings-<?php echo esc_attr($this->data->get('icon')); ?>" />
			</svg>
		</label>
		<?php
		
		// wrapper end - for half rating item
		if ($counter == $prev_counter + 1)
		{
			?></span><?php
		}
		$counter++;

		// increase value
		$rating_value += 0.5;
	}
	?>
</div>