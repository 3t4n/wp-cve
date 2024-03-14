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

$auth 	 = isset($displayData['auth'])         ? $displayData['auth']         : VAPEmployeeAuth::getInstance();
$has_cap = isset($displayData['has_capacity']) ? $displayData['has_capacity'] : false;
$orders  = isset($displayData['orders'])       ? $displayData['orders']       : array();
$itemid  = isset($displayData['itemid'])       ? $displayData['itemid']       : null;

if (is_null($itemid))
{
	// extract menu item from request when not specified
	$itemid = JFactory::getApplication()->input->getUint('Itemid');
}

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

$canEdit   = $auth->manageReservation();
$canRemove = $auth->removeReservation();

?>

<div class="vapempserlistcont">

	<div class="vap-allorders-singlerow vap-allorders-row head" style="text-align: center;">

		<!-- ID -->
		
		<span class="vap-allorders-column" style="width: 5%;"><?php echo JText::translate('VAPMANAGERESERVATION0'); ?></span>

		<!-- START -->

		<span class="vap-allorders-column" style="width: 10%;"><?php echo JText::translate('VAPMANAGERESERVATION5'); ?></span>

		<!-- END -->

		<span class="vap-allorders-column" style="width: 10%;"><?php echo JText::translate('VAPMANAGERESERVATION6'); ?></span>

		<!-- SERVICE -->

		<span class="vap-allorders-column" style="width: 15%;"><?php echo JText::translate('VAPMANAGERESERVATION4'); ?></span>

		<!-- PEOPLE -->

		<?php
		if ($has_cap)
		{
			// the employee may be interested in knowing the number of guests
			?>
			<span class="vap-allorders-column" style="width: 7%;"><?php echo JText::translate('VAPMANAGERESERVATION25'); ?></span>
			<?php
		}
		?>

		<!-- CUSTOMER -->

		<span class="vap-allorders-column" style="width: 20%;"><?php echo JText::translate('VAPMANAGERESERVATION38'); ?></span>

		<!-- TOTAL -->

		<?php
		if (!$has_cap)
		{
			// no capacity, display a different info (such as the total cost)
			?>
			<span class="vap-allorders-column" style="width: 7%;"><?php echo JText::translate('VAPMANAGERESERVATION9'); ?></span>
			<?php
		}
		?>

		<!-- STATUS -->

		<span class="vap-allorders-column" style="width: 20%;"><?php echo JText::translate('VAPMANAGERESERVATION12'); ?></span>

		<!-- ACTIONS -->

		<?php
		if ($canEdit || $canRemove)
		{
			?>
			<span class="vap-allorders-column" style="width: 5%;">&nbsp;</span>
			<?php
		}
		?>

	</div>

	<?php
	foreach ($orders as $row)
	{
		$checkout = VikAppointments::getCheckout($row->checkin_ts, $row->duration);

		$edit_uri = JRoute::rewrite('index.php?option=com_vikappointments&task=empmanres.edit&cid[]=' . $row->id . ($itemid ? '&Itemid=' . $itemid : ''), false);
		$del_uri  = JRoute::rewrite('index.php?option=com_vikappointments&task=empmanres.delete&cid[]=' . $row->id . ($itemid ? '&Itemid=' . $itemid : ''), false);
		?>
		<div class="vap-allorders-singlerow vap-allorders-row vapemprestr" id="vaptabrow<?php echo $row->id; ?>" style="text-align: center;">

			<!-- ID -->

			<span class="vap-allorders-column order-id" style="width: 5%;">
				<?php echo $row->id; ?>
			</span>

			<!-- START -->

			<span class="vap-allorders-column order-checkin" style="width: 10%;">
				<?php echo JHtml::fetch('date', $row->checkin_ts, $config->get('timeformat'), $auth->timezone ? $auth->timezone : null); ?>
			</span>

			<!-- END -->

			<span class="vap-allorders-column order-checkout" style="width: 10%;">
				<?php echo JHtml::fetch('date', $checkout, $config->get('timeformat'), $auth->timezone ? $auth->timezone : null); ?>
			</span>

			<!-- SERVICE -->

			<span class="vap-allorders-column order-service" style="width: 15%;">
				<?php echo $row->service_name; ?>
			</span>

			<!-- PEOPLE -->

			<?php
			if ($has_cap)
			{
				// the employee may be interested in knowing the number of guests
				?>
				<span class="vap-allorders-column order-people" style="width: 7%;">
					<?php echo $row->people; ?>
				</span>
				<?php
			}
			?>

			<!-- CUSTOMER -->

			<span class="vap-allorders-column order-customer" style="width: 20%;">
				<?php echo $row->purchaser_nominative; ?>
			</span>

			<!-- TOTAL -->

			<?php
			if (!$has_cap)
			{
				// no capacity, display a different info (such as the total cost)
				?>
				<span class="vap-allorders-column order-total" style="width: 7%;">
					<?php echo VAPFactory::getCurrency()->format($row->total_cost); ?>
				</span>
				<?php
			}
			?>

			<!-- STATUS -->

			<span class="vap-allorders-column order-status" style="width: 20%;">
				<?php echo JHtml::fetch('vaphtml.status.display', $row->status); ?>
			</span>

			<!-- ACTIONS -->

			<?php
			if ($canEdit || $canRemove)
			{
				?>
				<span class="vap-allorders-column order-actions" style="width: 5%;">
					<?php
					if ($canEdit)
					{
						?>
						<a href="<?php echo $edit_uri; ?>" style="margin: 0 2px;">
							<i class="fas fa-edit big"></i>
						</a>
						<?php
					}

					if ($canRemove)
					{
						?>
						<a href="<?php echo $vik->addUrlCSRF($del_uri); ?>" onclick="return confirm('<?php echo addslashes(JText::translate('VAPCONFDIALOGMSG')); ?>');" style="margin: 0 2px;">
							<i class="fas fa-trash big"></i>
						</a>
						<?php
					}
					?>
				</span>
				<?php
			}
			?>

		</div>
		<?php
	}		
	?>

</div>
