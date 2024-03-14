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
 * VikAppointments - Employee E-Mail Template.
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
			
			<!-- HEADING TITLE -->

			<tr>
				<td style="padding: 0;">
					<h3 style="display: inline-block;"><?php echo JText::translate('VAPADMINEMAILHEADTITLE'); ?></h3>
				</td>
			</tr>

			<!-- CUSTOM POSITION TOP -->

			<tr>
				<td style="padding: 0;">
					{custom_position_top}
				</td>
			</tr>

			<!-- STATUS AND TOTAL COST -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 20px 10px; line-height: 1.4em; text-align: left;">
								<div style="float:left; display:inline-block;">
									<span style="text-transform:uppercase; font-weight:bold;">
										{order_status}
									</span>
								</div>

								<?php
								if ($order->totals->gross > 0)
								{
									?>
									<div style="float:right; display:inline-block;">{order_total_cost}</div>
									<?php
								}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- COUPON CODE -->

			<?php
			if ($order->coupon)
			{
				?>
				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
							<tr>
								<td style="padding: 20px 10px; line-height: 1.4em; text-align: left;">
									<div style="float:left; display:inline-block;">
										<?php echo JText::translate('VAPORDERCOUPON'); ?>
									</div>
									<div style="float:right; display:inline-block;">
										{order_coupon_code}
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- CUSTOM POSITION MIDDLE -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 0 auto">
						<tr>
							<td>
								{custom_position_middle}
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- APPOINTMENTS BOX -->

			<?php
			foreach ($order->appointments as $row)
			{	
				?>
				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
							
							<tr>
								<td style="padding: 0; line-height: 1.4em; text-align: left;">
									<div>

										<div style="padding: 10px; border-bottom: 0; background-color: #f8f8f8;">
											<?php echo $row->id . ' - ' . $row->sid; ?>

											<br />

											<?php
											echo $row->service->name;

											/**
											 * Display number of participants if higher than 1.
											 *
											 * @since 1.6.3
											 */
											if ($row->people > 1)
											{
												echo ' x' . $row->people . ' ';
											}
											?>

											<br />

											<?php
											// display check-in
											echo $row->employee->checkin->lc;

											if ($config->getBool('multitimezone'))
											{
												// in case of multi-timezone, append offset
												echo ' (' . $row->employee->checkin->timezone . ')';
											}

											// add duration
											echo ' - ' . VikAppointments::formatMinutesToTime($row->duration);

											if ($row->location)
											{
												?>
												<br />
												<?php

												echo $row->location->text;
											}
											?>
										</div>

										<?php
										if ($row->options)
										{
											?>
											<div style="border-top: 1px solid #ddd; padding: 10px; background-color: #eee;">
												<?php
												foreach ($row->options as $opt)
												{
													?>
													<div style="padding: 2px 0; width: 100%; display: inline-block;">
														<div style="float: left; display: inline-block; text-align: left;">
															<?php
															echo $opt->fullName;

															if ($opt->multiple || $opt->quantity > 1)
															{
																?>
																<span style="display: inline-block; margin-left: 10px;">
																	x<?php echo $opt->quantity; ?>
																</span>
																<?php
															}
															?>
														</div>
														<?php
														if ($opt->totals->gross)
														{
															?>
															<div style="float: right; display: inline-block; text-align: right;">
																<?php echo $currency->format($opt->totals->gross); ?>
															</div>
															<?php
														}
														?>
													</div>
													<?php
												}
												?>
											</div>
											<?php
										}

										if ($row->totals->grossOpt > 0)
										{
											?>
											<div style="border-top: 1px solid #ddd; padding: 5px 10px; text-align: right;">
												<span><?php echo $currency->format($row->totals->grossOpt); ?></span>
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
				<?php
				// end for
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
										<div style="display: inline-block; width: 180px;"><?php echo $label; ?>:</div>
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

			<!-- ATTENDEES -->

			<?php
			if ($order->attendees)
			{
				?>
				<tr>
					<td style="padding: 0; text-align: center;">
						<?php
						foreach ($order->attendees as $i => $attendee)
						{
							?>
							<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
								<tr>
									<td style="padding: 0; line-height: 1.4em; text-align: left;">
										<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::sprintf('VAP_N_ATTENDEE', $i + 2); ?></div>
										<div style="padding: 10px; background-color: #f2f3f7;">
											<?php
											foreach ($attendee['display'] as $label => $value)
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
							<?php
						}
						?>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- CUSTOM POSITION BOTTOM -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 0 auto;">
						<tr>
							<td>
								{custom_position_bottom}
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- ORDER LINK -->

			<?php
			if ($order->sameEmp)
			{
				?>
				<tr class="no-printable">
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
							<tr>
								<td style="padding: 0; line-height: 1.4em; text-align: left;">
									<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::translate('VAPORDERLINK'); ?></div>
									<div style="padding: 10px;">
										<a href="{order_link}" target="_blank" style="word-break: break-word;">{order_link}</a>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- CONFIRMATION LINK -->

			<?php
			if ($order->statusRole == 'PENDING' && $order->sameEmp)
			{
				?>
				<tr class="no-printable">
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
							<tr>
								<td style="padding: 0; line-height: 1.4em; text-align: left;">
									<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::translate('VAPCONFIRMATIONLINK'); ?></div>
									<div style="padding: 10px;">
										<a href="{confirmation_link}" target="_blank" style="word-break: break-word;">{confirmation_link}</a>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>

			<!-- CUSTOM POSITION FOOTER -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 0 auto;">
						<tr>
							<td style="">
								{custom_position_footer}
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
 * @var string|null	 {logo}					    The logo image of your company. Null if not specified.
 * @var string 		 {order_status}			    The status of the order.
 * @var string 		 {order_payment}		    The name of the payment processor selected. Returns "None" if empty.
 * @var float 		 {order_total_cost}		    The total cost of the appointments.
 * @var float 		 {order_total_net}			The total net of the appointments.
 * @var float 		 {order_total_tax}			The total taxes of the appointments.
 * @var float 		 {order_total_discount}		The total discount of the appointments.
 * @var string 		 {order_coupon_code}	    The coupon code used for the order. Returns "None" if empty.
 * @var string		 {order_link}			    The direct url to the page of the order.
 * @var string|null	 {confirmation_link}	    The direct url to confirm the order. Null when the status of the order is not PENDING.
 * @var string|null  {company_name}			    The name of the company.
 * @var string|null  {user_name}                The name of the user account.
 * @var string|null  {user_username}            The username of the user account.
 * @var string|null  {user_email}               The e-mail address of the user account.
 * @var string|null  {custom_position_top}		This tag will be replaced with all the Custom Text Contents assigned to the top position.
 * @var string|null  {custom_position_middle}	This tag will be replaced with all the Custom Text Contents assigned to the middle position.
 * @var string|null  {custom_position_bottom}	This tag will be replaced with all the Custom Text Contents assigned to the bottom position.
 * @var string|null  {custom_position_footer}	This tag will be replaced with all the Custom Text Contents assigned to the footer position.
 */
