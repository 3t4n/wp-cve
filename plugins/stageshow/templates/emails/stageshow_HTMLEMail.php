<?php /* Hide template from public access ... Next line is email subject - Following lines are email body
[organisation] Booking Confirmation
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>
#emailblock
{
	width: 750px;
	padding: 5px;
	border: 1px black solid;
}
</style>
</head>
<body text="#000000" bgcolor="#FFFFFF">
<div id="emailblock"><p><a href="[url]"><img src="[logoimg]" alt="[organisation]" /></a><br />
<br />
 Thank you for your booking online with [organisation]<br />
 <br />
 The booking below is confirmed. Please bring a copy of this email with you to the event.<br />
 </p>
<h3>Order Details:</h3>
<p>&nbsp;</p>
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
<p>Total: [salePaid]<br />
 <br />
 [saleBarcode]<br />
 Any queries relating to this booking should be emailed to <a href="mailto:[salesEMail]">[salesEMail]</a><br />
 <br />
 For further information on shows please visit our web site on <a href="[url]">[url]</a></p>
</div>
</body>
</html>
*/ ?>