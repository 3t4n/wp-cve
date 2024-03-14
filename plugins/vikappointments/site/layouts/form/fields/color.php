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

$name  = !empty($displayData['name']) ? $displayData['name']  : 'name';
$id    = !empty($displayData['id'])   ? $displayData['id']    : $name;
$value = isset($displayData['value']) ? $displayData['value'] : '';
$class = isset($displayData['class']) ? $displayData['class'] : '';

?>

<div class="input-append">
	<input
		type="text"
		name="<?php echo $this->escape($name); ?>"
		id="<?php echo $this->escape($id); ?>"
		value="<?php echo $this->escape($value); ?>"
		size="40"
		class="<?php echo $this->escape($class); ?>"
		aria-labelledby="<?php echo $id; ?>-label"
	/>

	<button type="button" class="btn">
		<i class="fas fa-eye-dropper"></i>
	</button>
</div>

<script>
	jQuery(function($) {
		const input  = $('#<?php echo $id; ?>');
		const button = input.next();
		
		button.ColorPicker({
			color: input.val(),
			onChange: (hsb, hex, rgb) => {
				input.val(hex.toUpperCase());
			},
		});
	});
</script>
