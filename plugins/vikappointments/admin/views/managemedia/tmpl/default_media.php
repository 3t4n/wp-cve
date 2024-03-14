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

$media = $this->media;

$vik = VAPApplication::getInstance();

$properties = VikAppointments::getMediaProperties();

?>

<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA2') . '*'); ?>
	<div class="input-append full-width">
		<input type="text" name="name" value="<?php echo $this->escape($media['name_no_ext']); ?>" class="required" size="64" />
	
		<span class="btn"><?php echo $media['file_ext']; ?></span>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- ACTION - Dropdown -->

<?php
$elements = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 1, JText::translate('VAPMEDIAACTION1')),
	JHtml::fetch('select.option', 2, JText::translate('VAPMEDIAACTION2')),
	JHtml::fetch('select.option', 3, JText::translate('VAPMEDIAACTION3')),
);

echo $vik->openControl(JText::translate('VAPMANAGEMEDIA17')); ?>
	<select name="action" id="vap-media-action">
		<?php echo JHtml::fetch('select.options', $elements); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- MEDIA - File -->

<?php
$control = array(
	'style' => 'display:none;',
);

echo $vik->openControl(JText::translate('VAPMANAGEMEDIA3') . '*', 'vap-action-child', $control); ?>
	<input type="file" name="file" class="vap-action-child-field" size="32" />
<?php echo $vik->closeControl(); ?>

<!-- RESIZE - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $properties['isresize'] == 1, 'onClick="resizeStatusChanged(1);"');
$no  = $vik->initRadioElement('', '', $properties['isresize'] == 0, 'onClick="resizeStatusChanged(0);"');

echo $vik->openControl(JText::translate('VAPMANAGEMEDIA8'), 'vap-replace-child', $control);
echo $vik->radioYesNo('isresize', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- ORIGINAL SIZE - Number -->

<?php echo $vik->openControl(JText::translate('VAPORIGINAL'), 'vap-replace-child', $control); ?>
	<div class="inline-fields">
		<div class="input-append">
			<input type="number" name="oriwres" value="<?php echo $properties['oriwres']; ?>" size="4" min="64" max="9999" step="1" <?php echo ($properties['isresize'] ? '' : 'readonly'); ?> />
			
			<span class="btn">px</span>
		</div>

		<div class="input-append">
			<input type="number" name="orihres" value="<?php echo $properties['orihres']; ?>" size="4" min="64" max="9999" step="1" <?php echo ($properties['isresize'] ? '' : 'readonly'); ?> />
			
			<span class="btn">px</span>
		</div>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- THUMBNAIL SIZE - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIATITLE4'), 'vap-replace-child', $control); ?>
	<div class="inline-fields">
		<div class="input-append">
			<input type="number" name="smallwres" value="<?php echo $properties['smallwres']; ?>" size="4" min="16" max="1024" step="1" />
			
			<span class="btn">px</span>
		</div>

		<div class="input-append">
			<input type="number" name="smallhres" value="<?php echo $properties['smallhres']; ?>" size="4" min="16" max="1024" step="1" />
			
			<span class="btn">px</span>
		</div>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- SEPARATOR -->

<hr class="vap-replace-child" style="display: none;" />

<!-- ALTERNATIVE TEXT - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA20')); ?>
	<input type="text" name="alt" value="<?php echo $this->escape($media['alt']); ?>" size="48" placeholder="<?php echo $this->escape(JText::translate('VAP_SELECT_USE_DEFAULT')); ?>" />
<?php echo $vik->closeControl(); ?>

<!-- TITLE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA21')); ?>
	<input type="text" name="title" value="<?php echo $this->escape($media['title']); ?>" size="48" />
<?php echo $vik->closeControl(); ?>

<!-- CAPTION - Textarea -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA22')); ?>
	<textarea name="caption" class="full-width" style="height: 120px; resize: vertical;"><?php echo htmlentities((string) $media['caption']); ?></textarea>
<?php echo $vik->closeControl(); ?>

<?php
JText::script('VAPMEDIAACTION0');
?>

<script>

	jQuery(function($) {

		$('#vap-media-action').select2({
			minimumResultsForSearch: -1,
			placeholder: Joomla.JText._('VAPMEDIAACTION0'),
			allowClear: true,
			width: 300,
		});

		$('#vap-media-action').on('change', function() {
			var val = $(this).val();

			if (val.length) {
				$('.vap-action-child').show();
				$('.vap-action-child-field').addClass('required');
				validator.registerFields('.vap-action-child-field');

				if (val == '3') {
					$('.vap-replace-child').show();
				} else {
					$('.vap-replace-child').hide();
				}
			} else {
				$('.vap-action-child, .vap-replace-child').hide();
				validator.unregisterFields('.vap-action-child-field');
			}
		});

	});

	function resizeStatusChanged(is) {
		jQuery('input[name="oriwres"],input[name="orihres"]').prop('readonly', is ? false : true);
	}

</script>
