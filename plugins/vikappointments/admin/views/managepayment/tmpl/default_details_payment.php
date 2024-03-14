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

<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT1') . '*'); ?>
	<input type="text" name="name" class="input-xxlarge input-large-text required" value="<?php echo $this->escape($payment->name); ?>" size="48" />
<?php echo $vik->closeControl(); ?>

<!-- File - Select -->

<?php
$drivers = array();
$drivers[] = JHtml::fetch('select.option', '', '');

$drivers = array_merge($drivers, JHtml::fetch('vaphtml.admin.paymentdrivers'));

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT2') . '*'); ?>
	<select name="file" class="required" id="vap-file-sel" <?php echo $this->isOwner ? '' : 'disabled'; ?>>
		<?php echo JHtml::fetch('select.options', $drivers, 'value', 'text', $payment->file); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- CHARGE - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT4')); ?>
	<div class="input-prepend currency-field">
		<span class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></span>

		<input type="number" name="charge" value="<?php echo $payment->charge; ?>" size="5" step="any" />
	</div>
<?php echo $vik->closeControl(); ?>

<!-- TAXES - Select -->

<?php
$control = array();
$control['style'] = $payment->charge > 0 ? '' : 'display:none;';

$taxes = JHtml::fetch('vaphtml.admin.taxes', $blank = '');

echo $vik->openControl(JText::translate('VAPTAXFIELDSET'), 'taxes-control', $control); ?>
	<select name="id_tax" id="vap-taxes-sel">
		<?php echo JHtml::fetch('select.options', $taxes, 'value', 'text', $payment->id_tax); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- SET AUTO CONFIRMED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $payment->setconfirmed == 1, 'onclick="setconfirmedValueChanged(1)"');
$no  = $vik->initRadioElement('', '', $payment->setconfirmed == 0, 'onclick="setconfirmedValueChanged(0)"');

$help = $vik->createPopover(array(
	'title'	  => JText::translate('VAPMANAGEPAYMENT5'),
	'content' => JText::translate('VAPMANAGEPAYMENT5_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGEPAYMENT5') . $help);
echo $vik->radioYesNo('setconfirmed', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- SELF CONFIRMATION - Checkbox -->

<?php
$control = array();
$control['style'] = $payment->setconfirmed ? '' : 'display:none;';

$yes = $vik->initRadioElement('', '', $payment->selfconfirm == 1);
$no  = $vik->initRadioElement('', '', $payment->selfconfirm == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECONFIG127'),
	'content' => JText::translate('VAPMANAGECONFIG127_DESC2'),
));

echo $vik->openControl(JText::translate('VAPMANAGECONFIG127') . $help, 'vap-confirm-field', $control);
echo $vik->radioYesNo('selfconfirm', $yes, $no, false);
echo $vik->closeControl();
?>

<?php
JText::script('VAPFILTERSELECTFILE');
JText::script('VAP_SELECT_USE_DEFAULT');
?>

<script>

	(function($) {
		'use strict';

		window['setconfirmedValueChanged'] = (is) => {
			if (is) {
				$('.vap-confirm-field').show();
			} else {
				$('.vap-confirm-field').hide();

				var input = $('input[name="selfconfirm"]');

				if (input.is(':checkbox')) {
					input.prop('checked', false);
				} else {
					input.val(0);
				}
			}
		}

		$(function() {
			$('#vap-file-sel').select2({
				placeholder: Joomla.JText._('VAPFILTERSELECTFILE'),
				allowClear: false,
				width: 300,
			});

			$('#vap-taxes-sel').select2({
				placeholder: Joomla.JText._('VAP_SELECT_USE_DEFAULT'),
				allowClear: true,
				width: 300,
			});

			$('input[name="charge"]').on('change', function() {
				const charge = parseFloat($(this).val());

				if (!isNaN(charge) && charge > 0) {
					$('.taxes-control').show();
				} else {
					$('.taxes-control').hide();
				}
			});

			<?php
			if ($this->isOwner)
			{
				?>
				$('#vap-file-sel').on('change', vapPaymentGatewayChanged);
				<?php
			}
			?>
		});
	})(jQuery);

</script>
