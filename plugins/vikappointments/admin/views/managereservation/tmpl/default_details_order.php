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

$reservation = $this->reservation;

$vik = VAPApplication::getInstance();

?>

<!-- STATUS - Dropdown -->

<?php
$statuses = JHtml::fetch('vaphtml.admin.statuscodes', 'appointments');

echo $vik->openControl(JText::translate('VAPMANAGERESERVATION19')); ?>
	<select name="status" class="vap-status-sel">
		<?php echo JHtml::fetch('select.options', $statuses, 'value', 'text', $reservation->status); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- PAYMENTS - Dropdown -->

<?php
if ($this->payments)
{
	echo $vik->openControl(JText::translate('VAPMANAGERESERVATION13'));

	$payments = array();

	// add blank option
	$payments[] = array(JHtml::fetch('select.option', '', ''));

	foreach ($this->payments as $payment)
	{
		$opt = JHtml::fetch('select.option', $payment['id'], $payment['name']);

		// include payment charge too
		$opt->charge     = $payment['charge'];
		$opt->dataCharge = 'data-charge="' . $payment['charge'] . '"';

		// create group key
		$key = JText::translate($payment['published'] ? 'JPUBLISHED' : 'JUNPUBLISHED');

		// create status group if not exists
		if (!isset($payments[$key]))
		{
			$payments[$key] = array();
		}

		// add within group
		$payments[$key][] = $opt;
	}

	// create dropdown attributes
	$params = array(
		'id'          => 'vap-payment-sel',
		'group.items' => null,
		'list.select' => $reservation->id_payment,
		'list.attr'   => '',
		'option.attr' => 'dataCharge',
	);
	
	// render select
	echo JHtml::fetch('select.groupedList', $payments, 'id_payment', $params);
		
	echo $vik->closeControl();
}
?>

<!-- NOTIFY CUSTOMER - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', false);
$no  = $vik->initRadioElement('', '', true);

echo $vik->openControl(JText::translate('VAPMANAGERESERVATION24'));
echo $vik->radioYesNo('notifycust', $yes, $no, false);
?>

<button type="button" class="btn" style="display:none;margin-left:5px;vertical-align:top;" id="custmail-handle">
	<i class="fas fa-envelope"></i>
</button>

<?php echo $vik->closeControl(); ?>

<!-- NOTIFY EMPLOYEE - Checkbox -->

<?php
if (!$this->multiOrder && $reservation->notify)
{
	$yes = $vik->initRadioElement('', '', false);
	$no  = $vik->initRadioElement('', '', true);

	echo $vik->openControl(JText::translate('VAPMANAGERESERVATION36'));
	echo $vik->radioYesNo('notifyemp', $yes, $no, false);
	echo $vik->closeControl();
}
?>

<!-- NOTIFY WAITING LIST - Hidden -->

<input type="hidden" name="notifywl" value="0" />

<?php
JText::script('VAPWLNOTIFYMODALCONTENT');
JText::script('JYES');
JText::script('JNO');
?>

<script>

	jQuery(function($) {
		$('#vap-payment-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: '90%',
		});

		$('.vap-status-sel').select2({
			allowClear: false,
			width: '90%',
		});

		<?php
		// DO NOT apply the payment charge to children of a multi-order.
		// Declare change callback only if we are creating a new record, if we are editing
		// a multi-order or if we are editing a single appointment.
		if (!$reservation->id || $reservation->id_parent <= 0 || $reservation->id == $reservation->id_parent)
		{
			?>
			$('#vap-payment-sel').on('change', function() {
				// extract charge from selected payment
				let charge = parseFloat($(this).find(':selected').data('charge'));
				// make sure the charge exists
				charge = isNaN(charge) ? 0 : charge;

				currency = Currency.getInstance();

				new Promise((resolve, reject) => {
					if (charge == 0) {
						// do not interrogate controller in case the
						// payment has no charge (highly probable)
						resolve({net: 0, tax: 0, gross: 0});
						return;
					}

					UIAjax.do(
						'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=tax.testajax'); ?>',
						{
							id_tax:  $(this).val(),
							amount:  charge,
							id_user: jQuery('#vap-users-select').val(),
							langtag: '<?php echo $reservation->langtag; ?>',
							subject: 'payment',
						},
						(pay) => {
							// resolve with received payments
							resolve(pay);
						},
						(err) => {
							// propagate error
							reject(err);
						}
					);
				}).then((pay) => {
					// get totals
					let totals = getInputTotals();

					// add new total tax
					totals.total_tax = currency.sum(totals.total_tax, pay.tax);
					// subtract previous one
					totals.total_tax = currency.diff(totals.total_tax, totals.payment_tax);

					// add new total gross
					totals.total_cost = currency.sum(totals.total_cost, pay.gross);
					// subtract previous one
					totals.total_cost = currency.diff(totals.total_cost, currency.sum(totals.payment_charge, totals.payment_tax));

					// refresh payment charge and tax
					totals.payment_tax    = pay.tax;
					totals.payment_charge = pay.net;

					// commit changes
					updateInputTotals(totals);
				}).catch((err) => {
					alert(err.responseText || Joomla.JText._('VAP_AJAX_GENERIC_ERROR'));
				});
			});
			<?php
		}
		?>

		$('.vap-status-sel').on('change', function() {
			let status = $(this).val();

			// update any other select too
			$('.vap-status-sel').not(this).select2('val', status);

			// get confirmed status
			const CONFIRMED = '<?php echo JHtml::fetch('vaphtml.status.confirmed', 'appointments', 'code'); ?>';
			// get cancelled status
			const CANCELLED = '<?php echo JHtml::fetch('vaphtml.status.cancelled', 'appointments', 'code'); ?>';
			// get refunded status
			const REFUNDED = '<?php echo JHtml::fetch('vaphtml.status.refunded', 'appointments', 'code', $strict = false); ?>';

			if (status == CONFIRMED) {
				// auto-toggle notify customer after selecting a "confirmed" status
				const input = $('input[name="notifycust"],input[name="notifyemp"]');
				input.prop('checked', true).trigger('change');
			} else if (status == CANCELLED || status == REFUNDED) {
				<?php if (VAPFactory::getConfig()->getBool('enablewaitlist')) { ?>
					// show dialog for waiting list notifications
					wlDialog.show();
				<?php } ?>
			}
		});

		$('input[name="notifycust"]').on('change', function() {
			if ($(this).is(':checked')) {
				$('#custmail-handle').show();
			} else {
				$('#custmail-handle').hide();
			}
		});

		$('#custmail-handle').on('click', () => {
			vapOpenJModal('custmail', null, true);
		});

		// create waiting list nofitications dialog
		const wlDialog = new VikConfirmDialog(Joomla.JText._('VAPWLNOTIFYMODALCONTENT'));

		wlDialog.addButton(Joomla.JText._('JYES'), () => {
			// action confirmed, register flag to notify the waiting list
			$('input[name="notifywl"]').val(1);
		});
			
		wlDialog.addButton(Joomla.JText._('JNO'), () => {
			// action denied, unregister
			$('input[name="notifywl"]').val(0);
		});
	});

</script>
