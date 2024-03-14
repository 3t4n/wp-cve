<?php /* Hide template from public access ... Next line is email subject - Following lines are email body
[organisation] Reservation Confirmation
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body text="#000000" bgcolor="#FFFFFF"><p><a href="[url]"><img src="[logoimg]" alt="[organisation]" border="0" /></a><br />
<br />
 Thank you for your booking online with [organisation]<br />
 <br />
 The reservation below is confirmed. Please bring a copy of this email with you to the event.<br />
 </p>
<h3>Order Details:</h3>
<p><br />
Purchaser:  [saleName]<br />
EMail:      [saleEMail]<br />
Reference:  [saleTxnId]<br />
Address:    [salePPStreet] <br />
            [salePPCity] <br />
            [salePPState]<br />
            [salePPZip]<br />
Note:<br />
 [saleNoteToSeller]</p>
<h3>Reserved:</h3>
<p>[startloop] [ticketName] - [ticketQty][ticketSeat] @ [priceValue] <br />
 [endloop] Donation: [saleDonation]</p>
<h3>Payment:</h3>
<p>Total Due: [soldValue]<br />
 <br />
[saleBarcode]<br />
 Any queries relating to this booking should be emailed to <a href="mailto:[salesEMail]">[salesEMail]</a><br />
 <br />
 For further information on shows please visit our web site on <a href="[url]">[url]</a></p>
</body>
</html>
*/ ?>