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

$config = VAPFactory::getConfig();

$vik = VAPApplication::getInstance();

// get current URI
$current_uri = base64_encode(JUri::getInstance());

if (empty($this->rows))
{
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
}
else
{
	?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">

		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="20%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION1'); ?>
				</th>
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="20%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION26'); ?>
				</th>

				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION4'); ?>
				</th>
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION3'); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION38'); ?>
				</th>
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION12'); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		foreach ($this->rows as $order)
		{
			foreach ($order->appointments as $i => $app)
			{
				?>
				<tr class="row<?php echo ($i % 2); ?>">

					<td>
						<a href="index.php?option=com_vikappointments&view=orderinfo&cid[]=<?php echo $app->id;?>&tmpl=component&back=<?php echo $current_uri; ?>">
							<?php echo $app->id . ' - ' . $order->sid; ?>
						</a>
					</td>

					<td>
						<?php echo $app->checkin->lc3; ?>
					</td>

					<td>
						<?php echo $app->service->name; ?>
					</td>

					<td>
						<?php echo $app->employee->name; ?>
					</td>

					<td>
						<?php echo $order->purchaser_nominative; ?>
					</td>

					<td>
						<?php echo JHtml::fetch('vaphtml.status.display', $app->status); ?>
					</td>

				</tr>
				<?php
			}
		}
		?>

	</table>
	<?php
}
?>

<script>

	/**
	 * Check whether the parent window provides the function
	 * to update the footer buttons (edit, delete) of the modal.
	 *
	 * @since 1.6.6
	 */
	if (window.parent.vapUpdateModalButtons) {
		window.parent.vapUpdateModalButtons(false);
	}

</script>
