<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JHtml::fetch('vaphtml.assets.colorpicker');

$name    = !empty($displayData['name']) ? $displayData['name']  : 'name';
$id      = !empty($displayData['id'])   ? $displayData['id']    : preg_replace("/[^a-zA-Z0-9\-_]+/", '_', $name);
$value   = isset($displayData['value']) ? $displayData['value'] : '';
$class   = isset($displayData['class']) ? $displayData['class'] : '';
$preview = !empty($displayData['preview']) ? true : false;

$value = ltrim($value, '#');

?>

<div class="<?php echo $preview ? 'input-prepend ' : ''; ?>input-append">
	<?php
	if ($preview)
	{
		?>
		<span class="btn color-picker-preview" style="background-color: #<?php echo $value; ?>;">&nbsp;&nbsp;</span>
		<?php
	}
	?>

	<input
		type="text"
		name="<?php echo $this->escape($name); ?>"
		id="<?php echo $this->escape($id); ?>"
		value="<?php echo $this->escape($value); ?>"
		size="40"
		class="<?php echo $this->escape($class); ?>"
	/>

	<button type="button" class="btn">
		<i class="fas fa-eye-dropper"></i>
	</button>
</div>

<script>
	(function($) {
		'use strict';

		$(function() {
			const input   = $('#<?php echo $id; ?>');
			const button  = input.next('button');
			const preview = input.prev('.color-picker-preview');
			
			button.ColorPicker({
				color: input.val(),
				onChange: (hsb, hex, rgb) => {
					input.val(hex.toUpperCase());

					input.trigger('change');
				},
			});

			if (preview.length) {
				input.on('change', function() {
					// update preview too
					preview.css('background-color', '#' + $(this).val());
				});
			}
		});
	})(jQuery);
</script>
