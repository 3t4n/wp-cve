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
 * VikAppointments - Waiting List E-Mail Template
 * @see the bottom of the page to check the available TAGS to use.
 */

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
			
			<!-- LOGO AND WAITING LIST MESSAGE -->

			<tr>
				<td style="padding: 0; text-align: center;">
					<div style="text-align: center;">{logo}</div>
					<h3 style="text-align: center; font-size: .9em;"><?php echo JText::translate('VAPWAITLISTMAILCONTENT'); ?></h3>
				</td>
			</tr>

			<!-- SERVICE DETAILS LINK -->

			<tr class="no-printable">
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 0; line-height: 1.4em; text-align: left;">
								<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::translate('VAPDETAILSLINK'); ?></div>
								<div style="padding: 10px; background-color: #f2f3f7;">
									<a href="{details_link}" target="_blank" style="word-break: break-word;">{details_link}</a>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- UNSUBSCRIBE LINK -->

			<tr class="no-printable">
				<td style="padding: 0; text-align: center;">
					<table width="100%" style="border-spacing: 0; margin: 10px auto 0; padding: 0; font-size: 14px; background: #f2f3f7;">
						<tr>
							<td style="padding: 0; line-height: 1.4em; text-align: left;">
								<div style="background: #eee; border-bottom: 1px solid #ddd; padding: 10px;"><?php echo JText::translate('VAPUNSUBSCRLINK'); ?></div>
								<div style="padding: 10px; background-color: #f2f3f7;">
									<a href="{unsubscribe_link}" target="_blank" style="word-break: break-word;">{unsubscribe_link}</a>
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
 * @var string|null  {company_name}		 The name of the company.
 * @var string|null	 {logo}				 The logo image of your company.
 * @var string		 {service}			 The name of the service requested.
 * @var string		 {checkin_day}		 The checkin day of the appointment.
 * @var string		 {checkin_time}		 The checkin time of the first free slot.
 * @var string		 {details_link}		 The direct url to the details page of the service requested.
 * @var string		 {unsubscribe_link}	 The direct url to remove the subscription from the waiting list.
 */
