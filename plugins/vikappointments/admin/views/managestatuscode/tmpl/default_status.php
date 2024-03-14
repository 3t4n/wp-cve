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

$status = $this->status;

$vik = VAPApplication::getInstance();

?>

<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEOPTION2') . '*'); ?>
	<input type="text" name="name" class="input-xxlarge input-large-text required" value="<?php echo $this->escape($status->name); ?>" size="40" />
<?php echo $vik->closeControl(); ?> 

<!-- CODE - Text -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON2'),
	'content' => JText::translate('VAPSTATUSCODECODE_HELP'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON2') . '*' . $help); ?>
	<input type="text" name="code" class="required" value="<?php echo $status->code; ?>" size="16" />
<?php echo $vik->closeControl(); ?> 

<!-- COLOR - Colorpicker -->

<?php echo $vik->openControl(JText::translate('VAPCOLOR')); ?>
	<div class="input-append">
		<input type="text" name="color" value="#<?php echo $status->color; ?>" size="16" />

		<button type="button" class="btn" id="vapcolorpicker">
			<i class="fas fa-eye-dropper"></i>
		</button>
	</div>
<?php echo $vik->closeControl(); ?>

<script>

	jQuery(function($) {
		$('input[name="color"]').on('change blur', function() {
			// refresh colorpicker on value change
			$('#vapcolorpicker').ColorPickerSetColor($(this).val());
		});
		
		$('#vapcolorpicker').ColorPicker({
			color: $('input[name="color"]').val(),
			onChange: function (hsb, hex, rgb) {
				$('input[name="color"]').val('#' + hex.toUpperCase());
			},
		});
	});

</script>
