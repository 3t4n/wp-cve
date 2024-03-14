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

?>

<a name="payment" style="display: none;"></a>

<div id="vap-pay-box" class="<?php echo $payment->position; ?>">
	
	<?php
	// display notes after purchase
	if (!empty($order->payment->notes->afterPurchase))
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
				echo VikAppointments::renderHtmlDescription($order->payment->notes->afterPurchase, 'paymentorder');
				?>
			</div>
		</div>
		<?php
	}
	?>

</div>