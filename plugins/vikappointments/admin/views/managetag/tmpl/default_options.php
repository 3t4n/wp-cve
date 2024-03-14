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

$tag = $this->tag;

$vik = VAPApplication::getInstance();

// use specified color or generate a random one
$tag->color = $tag->color ? '#' . $tag->color : JHtml::fetch('vaphtml.color.preset');

?>

<!-- COLOR - Text -->

<?php echo $vik->openControl(JText::translate('VAPCOLOR')); ?>
	<div class="input-append">
		<input type="text" name="color" value="<?php echo $this->escape($tag->color); ?>" />

		<button type="button" class="btn" id="vapcolorpicker">
			<i class="fas fa-eye-dropper"></i>
		</button>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- ICON - Select -->

<?php
$icons = array(
	'fas fa-bookmark',
	'fas fa-flag',
	'fas fa-thumbtack',
	'fas fa-tag',
	'fas fa-circle',
	'fas fa-info',
	'fas fa-question',
	'fas fa-exclamation',
);

$options = array();

foreach ($icons as $icon)
{
	$options[] = JHtml::fetch('select.option', $icon, preg_replace("/^fa[a-z]*\sfa-/i", '', $icon));
}

// add option to choose a custom icon
$options[] = JHtml::fetch('select.option', '', JText::translate('VAP_CUSTOM_FIELDSET'));

$is_custom = in_array($tag->icon, $icons) === false;

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT15')); ?>
	<select id="vap-icon-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $is_custom ? '' : $tag->icon); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- ICON - Text -->

<?php echo $vik->openControl(''); ?>
	<div class="input-prepend input-append">
		<span class="btn">
			<i class="<?php echo $this->escape($tag->icon); ?>" id="vap-icon-preview" style="color: <?php echo $this->escape($tag->color); ?>;"></i>
		</span>

		<input type="text" name="icon" value="<?php echo $this->escape($tag->icon); ?>" <?php echo $is_custom ? '' : 'readonly'; ?> />

		<a class="btn" href="https://fontawesome.com" target="_blank">
			<i class="fas fa-external-link-square-alt"></i>
		</a>
	</div>
<?php echo $vik->closeControl(); ?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vap-icon-sel').select2({
				allowClear: false,
				width: '90%',
			});

			$('#vap-icon-sel').on('change', function() {
				let val = $(this).val();
				
				const input = $('input[name="icon"]')
					.val(val)
					.prop('readonly', val.length ? true : false)
					.trigger('change');

				if (val.length == 0) {
					input.focus();
				}
			});

			$('input[name="icon"]').on('change', function() {
				let val = $(this).val();
				$('#vap-icon-preview').attr('class', val ? val : 'fas fa-spinner fa-spin');
			})

			$('input[name="color"]').on('change blur', function() {
				// refresh colorpicker on value change
				$('#vapcolorpicker').ColorPickerSetColor($(this).val());
			});
			
			$('#vapcolorpicker').ColorPicker({
				color: $('input[name="color"]').val(),
				onChange: (hsb, hex, rgb) => {
					$('input[name="color"]').val('#' + hex.toUpperCase());
					$('#vap-icon-preview').css('color', '#' + hex.toUpperCase());
				},
			});
		});
	})(jQuery);

</script>
