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

$vik = VAPApplication::getInstance();

$properties = $this->properties;

?>

<!-- RESIZE - Checkbox -->
				
<?php
$yes = $vik->initRadioElement('', '', $properties['isresize'] == 1, 'onClick="resizeStatusChanged(1);"');
$no  = $vik->initRadioElement('', '', $properties['isresize'] == 0, 'onClick="resizeStatusChanged(0);"');

echo $vik->openControl(JText::translate('VAPMANAGEMEDIA8'));
echo $vik->radioYesNo('isresize', $yes, $no, false);
echo $vik->closeControl();
?>

<?php
$control = array();
$control['style'] = $properties['isresize'] ? '' : 'display:none;';
?>

<hr class="original-size" style="<?php echo $control['style']; ?>" />

<!-- ORIGINAL WIDTH - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA6'), 'original-size', $control); ?>
	<div class="input-append">
		<input type="number" name="oriwres" value="<?php echo $properties['oriwres']; ?>" size="4" min="64" max="9999" step="1" <?php echo ($properties['isresize'] ? '' : 'readonly'); ?> />
		
		<span class="btn">px</span>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- ORIGINAL HEIGHT - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA15'), 'original-size', $control); ?>
	<div class="input-append">
		<input type="number" name="orihres" value="<?php echo $properties['orihres']; ?>" size="4" min="64" max="9999" step="1" <?php echo ($properties['isresize'] ? '' : 'readonly'); ?> />
		
		<span class="btn">px</span>
	</div>
<?php echo $vik->closeControl(); ?>

<hr />

<!-- THUMBNAIL WIDTH - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA7')); ?>
	<div class="input-append">
		<input type="number" name="smallwres" value="<?php echo $properties['smallwres']; ?>" size="4" min="16" max="1024" step="1" />
		
		<span class="btn">px</span>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- THUMBNAIL HEIGHT - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEMEDIA16')); ?>
	<div class="input-append">
		<input type="number" name="smallhres" value="<?php echo $properties['smallhres']; ?>" size="4" min="16" max="1024" step="1" />
		
		<span class="btn">px</span>
	</div>
<?php echo $vik->closeControl(); ?>

<script>
	
	function resizeStatusChanged(is) {
		jQuery('input[name="oriwres"],input[name="orihres"]').prop('readonly', is ? false : true);

		if (is) {
			jQuery('.original-size').show();
		} else {
			jQuery('.original-size').hide();
		}
	}

</script>
