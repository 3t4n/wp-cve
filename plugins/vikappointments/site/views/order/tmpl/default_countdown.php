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

// calculate remaining minutes
$remaining = ceil(($this->order->locked_until - time()) / 60);
// format remaining seconds in a readable text
$remaining = VikAppointments::formatMinutesToTime($remaining, $apply = true);

?>

<div class="vap-order-countdown">

	<?php
	// display countdown message
	echo JText::sprintf('VAPORDERCOUNTDOWN', $remaining);
	?>

</div>

<script>

	(function($) {
		let COUNTDOWN_TIMER = setInterval(() => {
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=order.countdown'); ?>',
				{
					id: <?php echo $this->order->id; ?>,
					locked_until: <?php echo $this->order->locked_until; ?>,
				},
				(resp) => {
					if (resp.status) {
						// refresh time
						$('.vap-order-countdown').html(resp.text);
					} else {
						// expired time, remove countdown block
						$('.vap-order-countdown').remove();

						// stop timer
						clearInterval(COUNTDOWN_TIMER);

						// still offer other 60 seconds to complete the payment
						setTimeout(() => {
							document.orderform.submit();
						}, 60000);
					}
				},
				(err) => {
					// do nothing on error, we probably faced a downtime
				}
			);
		}, 30000);
	})(jQuery);

</script>
