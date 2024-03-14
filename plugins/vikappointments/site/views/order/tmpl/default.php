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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.fontawesome');

$order = $this->order;

// create global cancellation URI
$this->cancelURI = JRoute::rewrite("index.php?option=com_vikappointments&task=order.cancel&id={$this->order->id}&sid={$this->order->sid}&Itemid={$this->itemid}", false);

// In case of logged-in user, display a button to access the profile page of the user,
// which contains all the appointments that have been booked.
// If you wish to avoid displaying that button, just comment the line below.
echo $this->loadTemplate('backbutton');

// Check whether to display the payment form within the top position of this view.
// The payment will be displayed here only in case the position match one of these:
// top-left, top-center, top-right.
echo $this->displayPayment('top');

?>

<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=order' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" name="orderform" id="orderform" method="get">

	<div class="vaporderpagediv">

		<?php
		// Display the block containing the order details, such the order number,
		// the order status, the customer details, etc...
		echo $this->loadTemplate('orderdetails');	

		if ($order->attendees)
		{
			// Display the block containing the custom fields of any other
			// attendee of the appointments.
			echo $this->loadTemplate('attendees');
		}

		// Counter used to track all the appointments that can be cancelled.
		$this->count = 0;

		// In case of multiple appointments booked within the same order, the system
		// groups by default the same services under them same block.
		foreach ($this->services as $id_service => $list)
		{
			// keep track of the current service
			$this->groupServices = $list;

			// display all the appointments that belong to this service
			echo $this->loadTemplate('service');
		}

		// Check whether there's at least a public note to display to the user.
		if ($order->getUserNotes($this->order->id))
		{
			// display all the notes assigned to this order
			echo $this->loadTemplate('usernote');
		}
		?>

	</div>

	<input type="hidden" name="ordnum" value="<?php echo $order->id; ?>" />
	<input type="hidden" name="ordkey" value="<?php echo $order->sid; ?>" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="view" value="order" />

</form>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayOrderSummary","type":"sitepage"} -->

<?php
$dispatcher = VAPFactory::getEventDispatcher();

/**
 * Trigger event to let the plugins add custom HTML contents below
 * the order summary. In case more than one plugin returned a string,
 * they will be displayed in different blocks.
 *
 * @param 	object 	$order  The object holding the order details (changed from array @since 1.7).
 *
 * @return 	string 	The HTML to display.
 *
 * @since 	1.6.6
 */
$blocks = array_filter($dispatcher->trigger('onDisplayOrderSummary', array($order)));

if ($blocks)
{
	?>
	<div class="vap-summary-plugins-container">
		<?php
		foreach ($blocks as $block)
		{
			?>
			<div class="vap-summary-plugin"><?php echo $block; ?></div>
			<?php
		}
		?>
	</div>
	<?php
}

// Check whether to display the payment form within the bottom position of this view.
// The payment will be displayed here only in case the position match one of these:
// bottom-left, bottom-center, bottom-right (or not specified).
echo $this->displayPayment('bottom');

// In case one or more appointments have been assigned to a location, display the map
// (provided by Google) containing all the venues.
if (!empty($this->locations))
{
	echo $this->loadTemplate('locations');
}

// load script translations
JText::script('VAPCANCELORDERMESSAGE');
?>
	
<script>

	// CANCELLATION SCRIPTS
	
	jQuery(function($) {
		if (window.location.hash === '#cancel') {
			// auto-prompt the cancellation with a short delay
			setTimeout(() => {
				vapCancelButtonPressed('<?php echo $this->cancelURI; ?>');	
			}, 500);
		} 
	});
	
	function vapCancelButtonPressed(uri) {
		// ask confirmation before to proceed with the cancellation
		if (confirm(Joomla.JText._('VAPCANCELORDERMESSAGE'))) {
			// confirmed, reach cancellation URL
			document.location.href = uri;
		}
	}
</script>
