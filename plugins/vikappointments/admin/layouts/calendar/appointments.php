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
 * @var  array  $appointments  A list of appointments.
 */
extract($displayData);

if (!$appointments)
{
	// no appointment, nothing to show...
	return '';
}

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$vik = VAPApplication::getInstance();

$hasCost = false;

// check whether we have at least an appointment with a cost,
// otherwise we can assume that the provider is offering free
// services and we can avoid displaying such column
foreach ($appointments as $row)
{
	if ($row->total_cost > 0)
	{
		$hasCost = true;
		break;
	}
}

?>

<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
	
	<?php echo $vik->openTableHead(); ?>
		<tr>

			<!-- ID -->

			<th class="<?php echo $vik->getAdminThClass('left nowrap hidden-phone'); ?>" width="1%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION0'); ?>
			</th>

			<!-- BEGIN -->
		
			<th class="<?php echo $vik->getAdminThClass('nowrap'); ?>" width="5%" style="text-align: center;">
				<?php echo JText::translate('VAPMANAGERESERVATION5'); ?>
			</th>

			<!-- END -->
		
			<th class="<?php echo $vik->getAdminThClass('nowrap hidden-phone'); ?>" width="5%" style="text-align: center;">
				<?php echo JText::translate('VAPMANAGERESERVATION6'); ?>
			</th>

			<!-- SERVICE -->
		
			<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION4'); ?>
			</th>

			<!-- CUSTOMER -->
		
			<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
				<?php echo JText::translate('VAPMANAGERESERVATION38'); ?>
			</th>

			<!-- PEOPLE -->
		
			<th class="<?php echo $vik->getAdminThClass('nowrap hidden-phone'); ?>" width="5%" style="text-align: center;">
				<?php echo JText::translate('VAPMANAGERESERVATION25'); ?>
			</th>

			<!-- TOTAL -->

			<?php
			if ($hasCost)
			{
				?>
				<th class="<?php echo $vik->getAdminThClass('nowrap hidden-phone'); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGERESERVATION9'); ?>
				</th>
				<?php
			}
			?>

			<!-- STATUS -->

			<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="8%" style="text-align: center;">
				<?php echo JText::translate('VAPMANAGERESERVATION12'); ?>
			</th>

			<!-- PREVIEW -->

			<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;">
				<?php echo JText::translate('VAPMANAGERESERVATION20'); ?>
			</th>

		</tr>
	<?php echo $vik->closeTableHead(); ?>

	<?php
	foreach ($appointments as $i => $row)
	{
		?>
		<tr class="row<?php echo ($i % 2); ?>" id="vaptabrow<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
			
			<!-- ID -->

			<td class="hidden-phone">
				<?php echo $row->id; ?>
			</td>

			<!-- BEGIN -->
			
			<td style="text-align: center;">
				<?php echo JHtml::fetch('date', $row->checkin_ts, $config->get('timeformat')); ?>
			</td>

			<!-- END -->

			<td style="text-align: center;" class="hidden-phone">
				<?php echo JHtml::fetch('date', VikAppointments::getCheckout($row->checkin_ts, $row->duration), $config->get('timeformat')); ?>
			</td>
			
			<!-- SERVICE -->
			
			<td>
				<?php echo $row->service_name; ?>
			</td>

			<!-- CUSTOMER -->

			<td>
				<?php
				if ($row->purchaser_nominative)
				{
					echo $row->purchaser_nominative;
				}
				else
				{
					echo $row->purchaser_mail;
				}
				?>
			</td>

			<!-- PEOPLE -->

			<td style="text-align: center;" class="hidden-phone">
				<?php echo $row->people; ?>
			</td>

			<!-- TOTAL COST -->

			<?php
			if ($hasCost)
			{
				?>
				<td style="text-align: center;" class="hidden-phone">
					<?php echo $currency->format($row->total_cost); ?>
				</td>
				<?php
			}
			?>

			<!-- STATUS -->

			<td style="text-align: center;" class="hidden-phone">
				<?php echo JHtml::fetch('vaphtml.status.display', $row->status); ?>
			</td>

			<!-- PREVIEW -->

			<td style="text-align: center;">
				<a href="javascript:void(0)" onclick="displayDetailsView([<?php echo $row->id; ?>]);">
					<i class="fas fa-eye"></i>
				</a>
			</td>

		</tr>
		<?php
	}
	?>

</table>
