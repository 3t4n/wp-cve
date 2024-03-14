<?php /* Hide template from public access ... Next line is email subject - Following lines are email body
[organisation] Booking Confirmation
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>
#emailblock
{
	font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
	width: 750px;
	padding: 5px;
	border: 1px black solid;
}

p
{
	margin-top: 0px;
}

<!-- 
	In The Styles That Follow lines starting with a '.' have a space 
	added before it to prevent problems with SMTP dot-stuffing.
-->
.ticket_table
{
	border: solid black 1px;
	margin: 0px 0px 0px 20px;
}

.ticket_td
{
	 text-align: center;
	 margin: 0px 20px;
}

.ticket_head
{
	height: 10px;
}

.ticket_show
{
	font-size: 90px;
}

.ticket_location
{
	height: 30px;
	font-size: 18px;
	width: 700px;
}

.ticket_detail
{
	font-size: 32px;
}

.ticket_txnid
{
	 text-align: right !important;
	font-size: 12px;
}
</style>

<!-- Add a style to throw a page, after a certain number of tickets, when printing the ticket -->
<style>
@media print 
{
	.page-break1,  .page-break4,  .page-break7,  .page-break10, .page-break13, .page-break16, 
	.page-break19, .page-break22, .page-break25, .page-break28, .page-break31, .page-break34,
	.page-break-end
	{
		page-break-after: always;
	}
} 
</style>
</head>
<body text="#000000" bgcolor="#FFFFFF">
<div id="emailblock"><p><a href="[url]"><img src="[logoimg]" alt="[organisation]" /></a><br />
<br />
Thank you for your booking online with [organisation]<br />
<br />
 The booking below is confirmed. Please bring a copy of this email with you to the event.</p>
<h3>Order Details:</h3>
<table>
<tbody>
<tr>
<td>Purchaser:</td>
<td>[saleName]</td>
</tr>
<tr>
<td>EMail:</td>
<td>[saleEMail]</td>
</tr>
<tr>
<td>Payment Method: &nbsp;</td>
<td>[saleMethod]</td>
</tr>
<tr>
<td>Sale Reference: &nbsp;</td>
<td>[saleTxnId]</td>
</tr>
<tr>
<td>Address: </td>
<td>[salePPStreet] </td>
</tr>
<tr>
<td>&nbsp;</td>
<td>[salePPCity] </td>
</tr>
<tr>
<td>&nbsp;</td>
<td>[salePPState]</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>[salePPZip]</td>
</tr>
<tr>
<td colspan="2">Note:</td>
</tr>
</tbody>
</table>
<p>[saleNoteToSeller]</p>
<h3>Purchased:</h3>
<p>[startloop] [ticketName]  [ticketQty] @ [ticketPaid] <br />
 [endloop] Booking Fee: [saleTransactionFee]<br />
 Postage: [salePostage]<br />
 Donation: [saleDonation]</p>
<h3>Payment:</h3>
<p>Total: [salePaid]</p>
<h3>Tickets:</h3>
<p>[ticketsloop]
<div class="page-break[ticketNo]">&nbsp;</div>
<table class="ticket_table">
<tr>
	<td rowspan=3>
	<img src="[logoimg]" alt="[organisation]" />
	</td>
	<td class="ticket_td ticket_head">[organisation]</td>
</tr>
<tr>
	<td class="ticket_td ticket_head">Presents</td>
</tr>
<tr>
	<td class="ticket_td ticket_show">[showName]</td>
</tr>
<tr>
	<td class="ticket_td ticket_location" colspan=2>At [seatingVenue]</td>
</tr>
<tr>
	<td class="ticket_td ticket_detail" colspan=2>[ticketName]</td>
</tr>
<tr>
	<td class="ticket_td ticket_detail" colspan=2>[ticketBarcode]</td>
</tr>
<tr>
	<td class="ticket_td" colspan=2>Please present this ticket at the door</td>
</tr>
<tr>
	<td class="ticket_td ticket_txnid" colspan=2>[saleTxnId]</td>
</tr>
</table>
<br />
<br />
 [endloop] 
<div class="page-break-end">&nbsp;</div>
Any queries relating to this booking should be emailed to <a href="mailto:[salesEMail]">[salesEMail]</a><br />
 <br />
 For further information on shows please visit our web site on <a href="[url]">[url]</a></p>
</div>
</body>
</html>
*/ ?>