<?php /* Hide template from public access ... Next line is email subject - Following lines are email body
[organisation] Client Database Records
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
Thank you for your personal information request to [organisation]<br />
<br />
<table>
<tbody>
<tr>
<td colspan=2><h3>Database Records:</h3></td>
</tr>
<tr>
<td colspan=2>This is a list of all database records we hold for you.</td>
</tr>
<tr>
<td>EMail:</td>
<td>[saleEMail]</td>
</tr>
[startloop][if saleNew]
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>Date Added:&nbsp;&nbsp;</td>
<td>[saleDateTime]</td>
</tr>
<tr>
<td>Name:</td>
<td>[saleName]</td>
</tr>
<tr>
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
[endif]
<tr>
<td colspan=2>[ticketName]  [ticketQty] @ [ticketPaid]</td>
</tr>
[endloop] 
</body>
</table>
<br />
<br />
 For further information on shows please visit our web site on <a href="[url]">[url]</a></p>
</div>
</body>
</html>
*/ ?>