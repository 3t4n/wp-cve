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

?>

<div class="vap-allorders-userhead">
	<div class="vap-allorders-userleft">
		<h2><?php echo JText::sprintf('VAPALLORDERSTITLE', $this->user->name); ?></h2>
	</div>

	<div class="vap-allorders-userright">
		<?php
		if ($this->hasSubscriptions)
		{
			?>
			<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=subscrhistory' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" class="vap-btn blue">
				<?php echo JText::translate('VAPALLORDERSSUBSCRBUTTON'); ?>
			</a>
			<?php
		}

		if ($this->hasPackages)
		{
			?>
			<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=packorders' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" class="vap-btn blue">
				<?php echo JText::translate('VAPALLORDERSPACKBUTTON'); ?>
			</a>
			<?php
		}
		?>

		<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=userprofile' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" class="vap-btn blue">
			<?php echo JText::translate('VAPALLORDERSPROFILEBUTTON'); ?>
		</a>

		<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&task=userprofile.logout' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" class="vap-btn">
			<?php echo JText::translate('VAPLOGOUTTITLE'); ?>
		</a>
	</div>
</div>
	
<?php
if (!count($this->orders))
{
	?>
	<div class="vap-allorders-void"><?php echo JText::translate('VAPALLORDERSVOID'); ?></div>
	<?php
}
else
{
	$currency = VAPFactory::getCurrency();

	?>
	<div class="vap-allorders-tinylist">
		<?php 
		foreach ($this->orders as $order)
		{
			$appCount = count($order->appointments);

			if ($appCount > 1)
			{
				// display parent record
				?>
				<div class="list-order-bar">

					<div class="order-oid">
						<?php echo substr($order->sid, 0, 2) . '#' . substr($order->sid, -2, 2); ?>
					</div>

					<div class="order-summary">
						<div class="summary-status order-<?php echo strtolower($order->status); ?>">
							<?php echo JHtml::fetch('vaphtml.status.display', $order->status); ?>
						</div>

						<div class="summary-service">
							<a href="javascript:void(0)" class="parent-order-link" data-id="<?php echo (int) $order->id; ?>">
								<?php echo JText::sprintf('VAP_N_APP_EXT', $appCount); ?>
							</a>
						</div>
					</div>

					<div class="order-purchase">
						<div class="purchase-date">
							<?php echo JHtml::fetch('date', $order->createdon, JText::translate('DATE_FORMAT_LC2'), VikAppointments::getUserTimezone()->getName()); ?>
						</div>

						<div class="purchase-price">
							<?php
							if ($order->totals->gross > 0)
							{
								echo $currency->format($order->totals->gross);
							}
							?>
						</div>
					</div>

					<div class="order-view-button">
						<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=order&ordnum=' . $order->id . '&ordkey=' . $order->sid . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>">
							<?php echo JText::translate('VAPVIEWDETAILS'); ?>
						</a>
					</div>

				</div>
				<?php
			}

			foreach ($order->appointments as $app)
			{
				$child_class = $style = '';

				if ($appCount > 1)
				{
					$child_class = ' vap-allord-child child-of-order' . $order->id;
					$style = 'display:none;';
				}
				?>
				<div class="list-order-bar<?php echo $child_class; ?>" style="<?php echo $style; ?>">

					<div class="order-oid">
						<?php echo substr($order->sid, 0, 2) . '#' . substr($order->sid, -2, 2); ?>
					</div>

					<div class="order-summary">
						<div class="summary-status order-<?php echo strtolower($order->status); ?>">
							<?php echo JHtml::fetch('vaphtml.status.display', $order->status); ?>
						</div>

						<div class="summary-service">
							<?php
							echo $app->service->name;

							if ($app->viewEmp)
							{
								echo ', ' . $app->employee->name;
							}
							?>
						</div>
					</div>

					<div class="order-purchase">
						<div class="purchase-date">
							<?php echo $app->customerCheckin->lc2; ?>
						</div>

						<div class="purchase-price">
							<?php
							if ($app->totals->gross > 0)
							{
								echo $currency->format($app->totals->gross);
							}
							?>
						</div>
					</div>

					<div class="order-view-button">
						<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=order&ordnum=' . $order->id . '&ordkey=' . $order->sid . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>">
							<?php echo JText::translate('VAPVIEWDETAILS'); ?>
						</a>
					</div>

				</div>
				<?php
			}
		}
		?>
	</div>
	
	<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=allorders' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" method="post">
		<?php echo JHtml::fetch('form.token'); ?>
		<div class="vap-list-pagination"><?php echo $this->navbut; ?></div>
		<input type="hidden" name="option" value="com_vikappointments" />
		<input type="hidden" name="view" value="allorders" />
	</form>
	
	<script>

		(function($) {
			'use strict';

			$('.parent-order-link').on('click', function() {
				const id = $(this).data('id');

				if ($('.child-of-order' + id).first().is(':visible')) {
					$('.child-of-order' + id).slideUp();
				} else {
					$('.child-of-order' + id).slideDown();
				}
			})
		})(jQuery);
		
	</script>
	
	<?php
}
?>
