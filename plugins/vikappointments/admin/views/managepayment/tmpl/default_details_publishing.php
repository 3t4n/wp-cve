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

$payment = $this->payment;

$vik = VAPApplication::getInstance();

?>

<!-- PUBLISHED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $payment->published == 1);
$no  = $vik->initRadioElement('', '', $payment->published == 0);

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT3'));
echo $vik->radioYesNo('published', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- TRUST - Number -->

<?php
$yes = $vik->initRadioElement('', '', $payment->trust >= 1, 'onClick="trustValueChanged(1)"');
$no  = $vik->initRadioElement('', '', $payment->trust == 0, 'onClick="trustValueChanged(0)"');

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGEPAYMENT16'),
	'content' => JText::translate('VAPMANAGEPAYMENT16_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT16') . $help); ?>
	<div class="inline-fields">
		<?php echo $vik->radioYesNo('trust_check', $yes, $no, false); ?>

		<input type="number" name="trust" value="<?php echo (int) $payment->trust; ?>" style="<?php echo $payment->trust ? '' : 'display:none;'; ?>" min="<?php echo $payment->trust ? 1 : 0; ?>" max="9999" step="1">
	</div>
<?php echo $vik->closeControl(); ?>

<!-- ALLOWED FOR - Select -->

<?php
$options = array();
$options[] = JHtml::fetch('select.option', 1, 'VAPMANAGEPAYALLOWEDFOROPT1');
$options[] = JHtml::fetch('select.option', 2, 'VAPMANAGEPAYALLOWEDFOROPT2');
$options[] = JHtml::fetch('select.option', 3, 'JALL');

if ($payment->appointments && $payment->subscr)
{
	$allowed_for = 3;
}
else if ($payment->subscr)
{
	$allowed_for = 2;
}
else
{
	$allowed_for = 1;
}

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT11')); ?>
	<select name="allowedfor" id="vap-allowedfor-sel" <?php echo $payment->id_employee > 0 ? 'disabled="disabled"' : ''; ?>>
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $allowed_for, true); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- ACCESS - Select -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('JFIELD_ACCESS_LABEL'),
	'content' => JText::translate('JFIELD_ACCESS_DESC'),
));

echo $vik->openControl(JText::translate('JFIELD_ACCESS_LABEL'));
echo JHtml::fetch('access.level', 'level', $payment->level, '', false, 'vap-level-select');
echo $vik->closeControl();
?>

<script>

	(function($, w) {
		'use strict';

		w['trustValueChanged'] = (is) => {
			if (is) {
				$('input[name="trust"]').attr('min', 1).val(1).show();
			} else {
				$('input[name="trust"]').attr('min', 0).val(0).hide();
			}
		}

		$(function() {
			$('#vap-allowedfor-sel').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 250,
			});

			$('#vap-level-select').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 250,
			});
		});
	})(jQuery, window);

</script>
