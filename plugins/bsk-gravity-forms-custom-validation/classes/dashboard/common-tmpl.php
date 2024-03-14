<?php
	ob_start();
?>
<!DOCTYPE html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraph.org/schema/"> <head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>{BSK_BLCV_NOTIFICATION_TITLE}</title>

<style type="text/css">
#outlook a{
	padding:0;
}

body{
	margin:0;
	padding:0;
	background-color:#FAFAFA;
	width:100% !important;
	-webkit-text-size-adjust:none;
	padding-bottom:50px !important;
}

img{
	border:0;
	height:auto;
	line-height:100%;
	outline:none;
	text-decoration:none;
}
    
table td{
	border-collapse:collapse;
}

table.widefat {
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.widefat {
    border-spacing: 0;
    width: 100%;
    clear: both;
    margin: 0;
}
    
.widefat td {
    font-size: 13px;
    line-height: 1.5em;
}
.widefat td {
    vertical-align: top;
}
.widefat td, .widefat th {
    padding: 8px 10px;
}
.bsk-gfblcv-column-value {
    width: 35%;
}
.striped>tbody>:nth-child(odd), ul.striped>:nth-child(odd) {
    background-color: #f9f9f9;
}
.bsk-gfcv-delete-confirm-span,
.bsk-gfcv-entry-blocked-keyword{
    color: #ff5b00;
}
</style>        
</head>
    
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: none;margin: 0;padding: 0;background-color: #F7F7F7;width: 100% !important;">
<center>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="margin: 0;padding: 0;background-color: #F7F7F7;height: 100% !important;width: 100% !important;">
    <tr>
        <td align="center" valign="top" style="border-collapse: collapse;">
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #DDDDDD; background-color: #FFFFFF;">
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse;">
                        <table border="0" cellpadding="0" cellspacing="0" width="1000" style="border-bottom: 1px solid #DDDDDD; background-color: #FFFFFF;padding: 0px;">
                            <tr>
                                <td style="border-collapse: collapse;color: #202020;font-family: Arial;padding: 0;text-align: left;vertical-align: middle;">{BSK_BLCV_MAIL_CONTENT}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        	</table>
		</td>
	</tr>
</table>
</center>
</body>    
</html>
<?php
	$email_html_tmpl = ob_get_contents();
	ob_end_clean();
?>