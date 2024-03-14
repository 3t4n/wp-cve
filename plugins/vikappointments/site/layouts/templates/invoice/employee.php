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

$order = isset($displayData['order']) ? $displayData['order'] : array();

$rowspan = 3;

if ($order->totals->payCharge > 0)
{
	$rowspan++;
}

if ($order->totals->discount > 0)
{
	$rowspan++;
}

$currency = VAPFactory::getCurrency();

?>

<table width="100%"  border="0">

	<tr>
		<td>
			<table width="100%"  border="0" cellspacing="5" cellpadding="5">
				<tr>
					<td width="70%">{company_logo}<br/>{company_info}</td>
					<td width="30%"align="right" valign="bottom">
						<table width="100%" border="0" cellpadding="1" cellspacing="1">
							<tr>
								<td align="right" bgcolor="#FFFFFF"><strong><?php echo JText::translate('VAPINVNUM'); ?> {invoice_number}{invoice_suffix}</strong></td>
							</tr>
							<tr>
								<td align="right" bgcolor="#FFFFFF"><strong><?php echo JText::translate('VAPINVDATE'); ?> {invoice_date}</strong></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td>
			<table width="100%"  border="0" cellspacing="1" cellpadding="2">
				<tr bgcolor="#E1E1E1" style="background-color: #E1E1E1;">
					<td width="64%"><strong><?php echo JText::translate('VAPINVITEMDESC'); ?></strong></td>
					<td width="12%" align="right"><strong><?php echo JText::translate('VAPINVTOTAL'); ?></strong></td>
					<td width="12%" align="right"><strong><?php echo JText::translate('VAPINVTAXES'); ?></strong></td>
					<td width="12%" align="right"><strong><?php echo JText::translate('VAPINVITEMPRICE'); ?></strong></td>
				</tr>
				
				<tr>
					<td width="64%"><strong><?php echo $order->subscription->name; ?></strong></td>
					<td width="12%" align="right"><?php echo $currency->format($order->totals->net); ?></td>
					<td width="12%" align="right"><?php echo $currency->format($order->totals->tax); ?></td>
					<td width="12%" align="right"><?php echo $currency->format($order->totals->gross); ?></td>
				</tr>

				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2">
				<tr bgcolor="#E1E1E1">
					<td width="70%" colspan="2" rowspan="<?php echo $rowspan; ?>" valign="top">
						<strong><?php echo JText::translate('VAPINVCUSTINFO'); ?></strong><br/>{billing_info}
					</td>
					<td width="30%" align="left">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
							<td align="left"><strong><?php echo JText::translate('VAPINVTOTAL'); ?></strong></td>
							<td align="right">{invoice_totalnet}</td>
						</tr></table>
					</td>
				</tr>
				<?php
				if ($order->totals->discount > 0)
				{
					?>
					<tr bgcolor="#E1E1E1">
						<td width="30%" align="left">
							<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
								<td align="left"><strong><?php echo JText::translate('VAPSUMMARYDISCOUNT'); ?></strong></td>
								<td align="right">{invoice_discount}</td>
							</tr></table>
						</td>
					</tr>
					<?php
				}
				
				if ($order->totals->payCharge > 0)
				{
					?>
					<tr bgcolor="#E1E1E1">
						<td width="30%" align="left">
							<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
								<td align="left"><strong><?php echo JText::sprintf('VAPINVPAYCHARGE', $order->payment->name); ?></strong></td>
								<td align="right">{invoice_paycharge}</td>
							</tr></table>
						</td>
					</tr>
					<?php
				}
				?>
				<tr bgcolor="#E1E1E1">
					<td width="30%" align="left">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
							<td align="left"><strong><?php echo JText::translate('VAPINVTAXES'); ?></strong></td>
							<td align="right">{invoice_totaltax}</td>
						</tr></table>
					</td>
				</tr>
				<tr bgcolor="#E1E1E1">
					<td width="30%" align="left" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
							<td align="left"><strong><?php echo JText::translate('VAPINVGRANDTOTAL'); ?></strong></td>
							<td align="right">{invoice_grandtotal}</td>
						</tr></table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

</table>