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

$rates = isset($this->trace['rates']) ? $this->trace['rates'] : array();

$currency = VAPFactory::getCurrency();

?>

<div class="ratestest" style="padding: 10px;">
	<?php echo $vik->openEmptyFieldset(); ?>

		<table class="rates-table table" id="rates-table">
			<thead>
				<tr>
					<th><?php echo JText::translate('JGRID_HEADING_ID'); ?></th>
					<th><?php echo JText::translate('JDETAILS'); ?></th>
					<th style="text-align:center;"><?php echo JText::translate('VAPCHDISC'); ?></th>
				</tr>
			</thead>

			<tbody>

				<tr>
					<td class="rate-id"></td>
					<td class="rate-details"><?php echo JText::translate('VAPBASECOST'); ?></td>
					<td class="rate-price"><?php echo $currency->format($this->trace['basecost']); ?></td>
				</tr>

				<?php
				foreach ($rates as $rate)
				{
					?>
					<tr class="rate-child">
						<td class="rate-id"><?php echo $rate->id; ?></td>
						<td class="rate-details">
							<?php
							echo $rate->name;

							if ($rate->description)
							{
								?>
								<div><small><?php echo $rate->description; ?></small></div>
								<?php
							}
							?>
						</td>
						<td class="rate-price"><?php echo $currency->format($rate->charge); ?></td>
					</tr>
					<?php
				}
				?>

			</tbody>

			<tfoot>

				<?php
				if ($this->finalCost != $this->rate)
				{
					// the final cost has been multiplied by the number of guests
					?>
					<tr>
						<td class="rate-id"></td>
						<td class="rate-details"><?php echo JText::translate('VAPCOSTPP'); ?></td>
						<td class="rate-price"><?php echo $currency->format($this->rate); ?></td>
					</tr>
					<?php
				}
				?>

				<tr>
					<td class="rate-id"></td>
					<td class="rate-details"><?php echo JText::translate('VAPFINALCOST'); ?></td>
					<td class="rate-price"><?php echo $currency->format($this->finalCost); ?></td>
				</tr>

			</tfoot>
		</table>

	<?php echo $vik->closeEmptyFieldset(); ?>
</div>

<script>

	/**
	 * Register trace within the parent window so that the caller
	 * is able to access and use the fetched price.
	 *
	 * @since 1.7
	 */
	window.parent.vapRatesTestTrace = <?php echo json_encode($this->trace); ?>;
	
	// for debug purposes
	console.log(window.parent.vapRatesTestTrace); 

</script>
