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
 * VikAppointments - Cancellation E-Mail Template.
 *
 * @var object  $order  It is possible to use this variable to 
 * 						access the details of the order.
 * @var string  $who    The entity to check (admin or employee).
 *
 * @see the bottom of the page to check the available TAGS to use.
 */

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$vik = VAPApplication::getInstance();

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
			
			<!-- TOP BOX [logo and cancellation content] -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<div>{logo}</div>
					<div style="margin-top: 10px;">{cancellation_content}</div>
				</td>
			</tr>

			<!-- CUSTOM POSITION TOP -->

			<tr>
				<td style="padding: 0;">
					{custom_position_top}
				</td>
			</tr>

			<!-- ORDER LINK -->

			<tr class="no-printable">
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 20px 10px; line-height: 1.4em; text-align: left;">
								<div>
									<a href="{order_link}" target="_blank" style="word-break: break-word;">{order_link}</a>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>

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
				if ($who == 'admin')
				{
					$url = $vik->adminUrl('index.php?option=com_vikappointments&task=reservation.edit&cid[]=' . $row->id);
				}
				else
				{
					$url = $vik->routeForExternalUse('index.php?option=com_vikappointments&task=empreservation.edit&cid[]=' . $row->id);
				}
				?>

				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="100%" style="border-spacing: 0; margin: 10px auto 0; font-size: 14px; background: #f2f3f7;">
							<tr>
								<td style="line-height: 1.4em; text-align: left;">
									<div style="display: inline-block; width: 100%; background: #eee; border-bottom: 1px solid #ddd; padding: 10px; box-sizing: border-box;">
										<div style="float:left; display: inline-block;"><?php echo $row->id . ' - ' . $row->sid; ?></div>
										<div style="float:right; display: inline-block;">
											<?php echo JHtml::fetch('vaphtml.status.display', $this->order->status); ?>
										</div>
									</div>
									<div style="padding: 10px;">
										<?php
										echo $row->service->name;

										if ($who == 'admin')
										{
											echo ' - ' . $row->employee->name;
										}
										?>

										<br />

										<?php
										if ($who == 'admin')
										{
											// display check-in for admin
											echo $row->systemCheckin->lc;

											if ($config->getBool('multitimezone'))
											{
												// in case of multi-timezone, append offset
												echo ' (' . $row->systemCheckin->timezone . ')';
											}
										}
										else
										{
											// display check-in for employee
											echo $row->employee->checkin->lc;

											if ($config->getBool('multitimezone'))
											{
												// in case of multi-timezone, append offset
												echo ' (' . $row->employee->checkin->timezone . ')';
											}
										}

										// add duration
										echo ' - ' . VikAppointments::formatMinutesToTime($row->duration);
										?>
									</div>
									<div style="border-top: 1px solid #ddd; padding: 10px; background: #eee;" class="no-printable">
										<a href="<?php echo $url; ?>" target="_blank" style="word-break: break-word;"><?php echo $url; ?></a>
									</div>
								</td>
							</tr>
						</table>
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
 * @var string|null	 {logo}					    The logo image of your company.
 * @var string 		 {order_payment}		    The name of the payment processor selected. Returns "None" if empty.
 * @var float 		 {order_total_cost}		    The total cost of the order.
 * @var float 		 {order_total_net}			The total net of the order.
 * @var float 		 {order_total_tax}			The total taxes of the order.
 * @var float 		 {order_total_discount}		The total discount of the order.
 * @var string|null  {cancellation_content}	    The content specified in the language file at VAPORDERCANCELEDCONTENT for admin and VAPORDERCANCELEDCONTENTEMP for employee.
 * @var string		 {order_link}			    The direct url to the details page of the order.
 * @var string|null  {company_name}			    The name of the company.
 * @var string|null  {user_name}                The name of the user account.
 * @var string|null  {user_username}            The username of the user account.
 * @var string|null  {user_email}               The e-mail address of the user account.
 * @var string|null  {custom_position_top}		This tag will be replaced with all the Custom Text Contents assigned to the top position.
 * @var string|null  {custom_position_middle}	This tag will be replaced with all the Custom Text Contents assigned to the middle position.
 * @var string|null  {custom_position_bottom}	This tag will be replaced with all the Custom Text Contents assigned to the bottom position.
 * @var string|null  {custom_position_footer}	This tag will be replaced with all the Custom Text Contents assigned to the footer position.
 */
