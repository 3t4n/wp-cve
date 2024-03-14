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
 * VikAppointments - Packages E-Mail Template.
 *
 * @var object  $order  It is possible to use this variable to 
 * 						access the details of the order.
 *
 * @see the bottom of the page to check the available TAGS to use.
 */

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

?>

<style>
	@media print {
		.no-printable {
			display: none;
		}
	}
</style>

<div style="background:#fff; color: #666; width: 100%; table-layout: fixed;">
	<div style="max-width: 600px; margin:0 auto;">

		<!--[if (gte mso 9)|(IE)]>
		<table width="800" align="center">
		<tr>
		<td>
		<![endif]-->

		<table align="center" style="margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; font-family: sans-serif;">
			
			<!-- TOP BOX [company logo and name] -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<div style="display: inline-block; float: left;">{logo}</div>
					<h3 style="display: inline-block; float: right;">{company_name}</h3>
				</td>
			</tr>

			<!-- ORDER NUMBER AND ORDER KEY -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 20px 10px; line-height: 1.4em; text-align: left;">
								<?php echo JText::translate('VAPORDERNUMBER'); ?>: {order_number}
							</td>
							<td style="padding: 20px 10px; line-height: 1.4em; text-align: right;">
								{order_key}
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- ORDER STATUS -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 20px 10px; line-height: 1.4em; text-align: left;">
								<span style="text-transform:uppercase; font-weight:bold;">
									{order_status}
								</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- TOTAL COST AND PAYMENT GATEWAY -->

			<?php
			if ($order->totals->gross > 0)
			{
				?>
				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
							<tr>
								<td style="padding: 20px 10px; line-height: 1.4em; text-align: left;">
									<div style="float:left; display:inline-block;">
										<?php
										if ($order->payment)
										{
											// show payment name
											?>
											{order_payment}
											<?php
										}
										else
										{
											// show grand total label
											echo JText::translate('VAPORDERDEPOSIT');
										}
										?>
									</div>

									<div style="float:right; display:inline-block;">{order_total_cost}</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- ITEMS LIST -->

			<?php
			foreach ($order->packages as $p)
			{
				?>
				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
							<tr>
								<td style="line-height: 1.4em; text-align: left; display: flex; flex-wrap: wrap;">
									<div style="display: inline-block; width: 100%; padding: 10px; box-sizing: border-box; background: #f8f8f8; flex: 100%;">
										<div style="float:left; display: inline-block; width: 100%;">
											<?php echo $p->name; ?> - <?php echo JText::sprintf('VAPPACKAGESMAILAPP', $p->totalApp); ?>
											<span style="margin-left: 10px; float: right;">x<?php echo $p->quantity; ?></span>
										</div>
									</div>

									<?php
									if ($p->totals->gross > 0)
									{
										?>
										<div style="background: #eee; border-top: 1px solid #ddd; padding: 10px; text-align: right; flex: 100%;">
											<?php echo $currency->format($p->totals->gross); ?>
										</div>
									<?php
									}
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- CUSTOMER DETAILS -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 0; line-height: 1.4em; text-align: left;">
								<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::translate('VAPPERSONALDETAILS'); ?></div>
								<div style="padding: 10px; background-color: #f2f3f7;">
								<?php
								foreach ($order->displayFields as $label => $value)
								{
									?>
									<div style="padding: 2px 0;">
										<div style="display: inline-block; width: 180px;"><?php echo $label; ?></div>
										<div style="display: inline-block;"><?php echo $value; ?></div>
									</div>
									<?php
								}
								?>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- ORDER LINK -->

			<tr class="no-printable">
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 0; line-height: 1.4em; text-align: left;">
								<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::translate('VAPORDERLINK'); ?></div>
								<div style="padding: 10px; background-color: #f2f3f7;">
									<a href="{order_link}" target="_blank" style="word-break: break-word;">{order_link}</a>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>

		</table>

		<!--[if (gte mso 9)|(IE)]>
		</td>
		</tr>
		</table>
		<![endif]-->

	</div>
</div>

<?php
/**
 * @var string|null	 {logo}					The logo image of your company. Null if not specified.
 * @var int 		 {order_number}			The unique ID of the reservation.
 * @var string 		 {order_key}			The serial key of the reservation.
 * @var string 		 {order_status}			The status of the order.
 * @var string|null	 {order_payment}		The name of the payment processor selected, otherwise NULL.
 * @var string|null  {order_payment_notes}	The notes of the payment processor selected, otherwise NULL.
 * @var float 		 {order_total_cost}		The total cost of the order.
 * @var string		 {order_link}			The direct url to the page of the order.
 * @var string|null  {company_name}			The name of the company.
 */
