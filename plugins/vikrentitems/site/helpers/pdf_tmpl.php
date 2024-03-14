<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

/*
 * Some special tags between curly brackets can be used to display certain values such as:
 * {logo}, {company_name}, {order_id}, {order_status}, {order_date}, {customer_info},
 * {pickup_date}, {pickup_location}, {dropoff_date}, {dropoff_location}, {order_details}, {order_total},
 * {customfield 2} (will print the custom field with ID 2), {order_link}, {footer_emailtext}, {vri_add_pdf_page} (to break and add a page to the PDF)
 *
 * The record of the order can be accessed from the following global array in case you need any extra content or to perform queries for a deeper customization level:
 * $order_details (order array)
 * Example: the ID of the order is contained in $order_details['id'] - you can see the whole array content with the PHP code "print_r($order_details);"
 *
 * It is also possible to access the customer information array by using this code:
 * $customer = VikRentItems::getCPinIstance()->getCustomerFromBooking($order_details['id']);
 * The variable $customer will always be an array, even if no customers were found. In this case, the array will be empty.
 * Debug the content of the array with the code "print_r($customer)" by placing it on any part of the PHP content below.
*/

defined('ABSPATH') or die('No script kiddies please!');

defined('_VIKRENTITEMSEXEC') OR die('Restricted Area');

//Custom PDF Parameters
define('VRI_PAGE_PDF_PAGE_ORIENTATION', 'P'); //define a constant - P=portrait, L=landscape (P by default or if not specified)
define('VRI_PAGE_PDF_UNIT', 'mm'); //define a constant - [pt=point, mm=millimeter, cm=centimeter, in=inch] (mm by default or if not specified)
define('VRI_PAGE_PDF_PAGE_FORMAT', 'A4'); //define a constant - A4 by default or if not specified. Could be also a custom array of width and height but constants arrays are only supported in PHP7
define('VRI_PAGE_PDF_MARGIN_LEFT', 10); //define a constant - 15 by default or if not specified
define('VRI_PAGE_PDF_MARGIN_TOP', 10); //define a constant - 27 by default or if not specified
define('VRI_PAGE_PDF_MARGIN_RIGHT', 10); //define a constant - 15 by default or if not specified
define('VRI_PAGE_PDF_MARGIN_HEADER', 1); //define a constant - 5 by default or if not specified
define('VRI_PAGE_PDF_MARGIN_FOOTER', 5); //define a constant - 10 by default or if not specified
define('VRI_PAGE_PDF_MARGIN_BOTTOM', 5); //define a constant - 25 by default or if not specified
define('VRI_PAGE_PDF_IMAGE_SCALE_RATIO', 1.25); //define a constant - ratio used to adjust the conversion of pixels to user units (1.25 by default or if not specified)
$page_params = array(
	'show_header' => 0, //0 = false (do not show the header) - 1 = true (show the header)
	'header_data' => array(), //if empty array, no header will be displayed. The array structure is: array(logo_in_tcpdf_folder, logo_width_mm, title, text, rgb-text_color, rgb-line_color). Example: array('logo.png', 30, 'Items Rental xy', 'Versilia Coast, xyz street', array(0,0,0), array(0,0,0))
	'show_footer' => 1, //0 = false (do not show the footer) - 1 = true (show the footer)
	'pdf_page_orientation' => 'VRI_PAGE_PDF_PAGE_ORIENTATION', //must be a constant - P=portrait, L=landscape (P by default)
	'pdf_unit' => 'VRI_PAGE_PDF_UNIT', //must be a constant - [pt=point, mm=millimeter, cm=centimeter, in=inch] (mm by default)
	'pdf_page_format' => 'VRI_PAGE_PDF_PAGE_FORMAT', //must be a constant defined above or an array of custom values like: 'pdf_page_format' => array(400, 300)
	'pdf_margin_left' => 'VRI_PAGE_PDF_MARGIN_LEFT', //must be a constant - 15 by default
	'pdf_margin_top' => 'VRI_PAGE_PDF_MARGIN_TOP', //must be a constant - 27 by default
	'pdf_margin_right' => 'VRI_PAGE_PDF_MARGIN_RIGHT', //must be a constant - 15 by default
	'pdf_margin_header' => 'VRI_PAGE_PDF_MARGIN_HEADER', //must be a constant - 5 by default
	'pdf_margin_footer' => 'VRI_PAGE_PDF_MARGIN_FOOTER', //must be a constant - 10 by default
	'pdf_margin_bottom' => 'VRI_PAGE_PDF_MARGIN_BOTTOM', //must be a constant - 25 by default
	'pdf_image_scale_ratio' => 'VRI_PAGE_PDF_IMAGE_SCALE_RATIO', //must be a constant - ratio used to adjust the conversion of pixels to user units (1.25 by default)
	'header_font_size' => '10', //must be a number
	'body_font_size' => '10', //must be a number
	'footer_font_size' => '8' //must be a number
);
defined('_VIKRENTITEMS_PAGE_PARAMS') OR define('_VIKRENTITEMS_PAGE_PARAMS', '1');
?>

<style type="text/css">
<!--

body {
	font-size: 12px;
}
p {
	font-size: 12px;
}
h3 {
	font-size: 16px;
	font-weight: bold;
}
h4 {
	font-size: 14px;
	font-weight: bold;
}
span.confirmed {
	color: #009900;
}
span.standby {
	color: #ff0000;
}

-->
</style>

<body>

<table>
	<tr>
		<td>{logo}</td><td><h3>{company_name}</h3></td>
	</tr>
</table>

<table>
	<tr>
		<td><strong><?php echo JText::translate('VRIORDERNUMBER'); ?>:</strong> {order_id}</td>
		<td><strong><?php echo JText::translate('VRLIBSEVEN'); ?>:</strong> <span style="color: {order_status_class};">{order_status}</span></td>
		<td><strong><?php echo JText::translate('VRLIBEIGHT'); ?>:</strong> {order_date}</td>
	</tr>
</table>

<h4><?php echo JText::translate('VRLIBNINE'); ?>:</h4>
<p>{customer_info}</p>

<table>
	<tr>
		<td align="center"><strong><?php echo JText::translate('VRLIBELEVEN'); ?></strong></td>
		{if_pickup_location_label}<td align="center"><strong><?php echo JText::translate('VRRITIROITEM'); ?></strong></td>{/if_pickup_location_label}
		<td> </td>
		<td align="center"><strong><?php echo JText::translate('VRLIBTWELVE'); ?></strong></td>
		{if_dropoff_location_label}<td align="center"><strong><?php echo JText::translate('VRRETURNITEMORD'); ?></strong></td>{/if_dropoff_location_label}
	</tr>
	<tr>
		<td align="center">{pickup_date}</td>
		{if_pickup_location}<td align="center">{pickup_location}</td>{/if_pickup_location}
		<td> </td>
		<td align="center">{dropoff_date}</td>
		{if_dropoff_location}<td align="center">{dropoff_location}</td>{/if_dropoff_location}
	</tr>
</table>

<p> <br/><br/></p>

<h4><?php echo JText::translate('VRIORDERDETAILS'); ?>:</h4>
<br/>
<table width="100%" align="left" style="border: 1px solid #DDDDDD;">
<tr><td bgcolor="#C9E9FC" width="30%" style="border: 1px solid #DDDDDD;"></td><td bgcolor="#C9E9FC" width="10%" align="center" style="border: 1px solid #DDDDDD;"><?php echo JText::translate('VRIPDFDAYS'); ?></td><td bgcolor="#C9E9FC" width="20%" style="border: 1px solid #DDDDDD;"><?php echo JText::translate('VRIPDFNETPRICE'); ?></td><td bgcolor="#C9E9FC" width="20%" style="border: 1px solid #DDDDDD;"><?php echo JText::translate('VRIPDFTAX'); ?></td><td bgcolor="#C9E9FC" width="20%" style="border: 1px solid #DDDDDD;"><?php echo JText::translate('VRIPDFTOTALPRICE'); ?></td></tr>
{order_details}
{order_total}
</table>

<p> <br/><br/></p>

<p>
	<br/>
	<small>
		<strong>{customfield 2} {customfield 3}, <?php echo JText::translate('VRLIBTENTHREE'); ?>:</strong>
		<br/>
		{order_link}
	</small>
	<br/>
</p>
<small>{footer_emailtext}</small>

<?php
//BEGIN: Contract/Agreement Sample Code
?>
{vri_add_pdf_page}
<?php
//with the line above we add a new page to the PDF
?>
<h3><?php echo JText::translate('VRIAGREEMENTTITLE'); ?></h3>
<?php echo JText::sprintf('VRIAGREEMENTSAMPLETEXT', '{customfield 2}', '{customfield 3}', '{company_name}', '{order_date}', '{dropoff_date}'); ?>
<?php
//the line above will print the following sample text:
//"This agreement between %s %s and %s was made on the %s and is valid until the %s."
//The wildcards "%s" will be replaced with all the parameters of the function sprintf so in the example above, the text will become:
//"This agreement between {customfield 2} {customfield 3} and {company_name} was made on the {order_date} and is valid until the {dropoff_date}."
//The system will replace the values in {} with the real values
?>
<p> <br/><br/></p>
<?php echo JText::translate('VRIAGREEMENTSAMPLETEXTMORE'); ?>
<?php
//END: Contract/Agreement Sample Code
?>

</body>