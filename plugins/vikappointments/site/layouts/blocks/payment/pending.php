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

/**
 * Layout variables
 * -----------------
 * @var  array   $data   An associative array containing the transaction details.
 * @var  mixed   $order  An object containing the order details.
 * @var  string  $scope  The caller of this layout (e.g. appointments, packages).
 */
extract($displayData);

// get payment details
$payment = $data['payment_info'];

$vik = VAPApplication::getInstance();

?>

<a name="payment" style="display: none;"></a>

<?php
/**
 * Instantiate the payment using the platform handler.
 *
 * @since 1.6.3
 */
$obj = $vik->getPaymentInstance($payment->file, $data, $payment->params);
?>

<div id="vap-pay-box" class="<?php echo $payment->position; ?>">

	<?php
	// display notes before purchase
	if (!empty($order->payment->notes->beforePurchase))
	{
		?>
		<div class="vappaymentouternotes">
			<div class="vappaymentnotes">
				<?php
				/**
				 * Render HTML description to interpret attached plugins.
				 * 
				 * @since 1.6.3
				 */
				echo VikAppointments::renderHtmlDescription($order->payment->notes->beforePurchase, 'paymentorder');
				?>
			</div>
		</div>
		<?php
	}

	if (!empty($scope) && $scope == 'appointments')
	{
		// check whether the deposit to leave is optional
		if (VAPFactory::getConfig()->getUint('usedeposit') == 1 && ($data['leavedeposit'] || $data['payfull']))
		{
			$value = (int) $data['leavedeposit'];
			?>
			<div class="vap-deposit-choice">
				<input type="checkbox" value="<?php echo $value; ?>" id="deposit-checkbox" />
				<label for="deposit-checkbox"><?php echo JText::translate('VAPORDERPAYFULLDEPOSIT' . ($value ? '' : 'BACK')); ?></label>
			</div>

			<script>
				jQuery(function($) {
					$('#deposit-checkbox').on('change', function() {
						$('.vap-deposit-choice').hide();
						$('#orderform').append('<input type="hidden" name="payfull" value="' + $(this).val() + '" />');
						$('#orderform').submit();
					});
				});
			</script>
			<?php
		}
	}

	// display payment form
	$obj->showPayment();
	?>

</div>
