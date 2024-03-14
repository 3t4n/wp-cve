<?php
/* 
Filename: stageshow-filters-sample.php

Description: Customisation StageShow Wordpress Filter Sample Definitions
 
Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

exit;
?>

/*
------------------------------------------------------------------------------------------------

	stageshow_filter_trolley_extrarows - Add Shopping Trolley Rows HTML
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterTrolleyExtraRows($trolley)
{
//	$buttonsRowTag = '<tr class="stageshow-trolley-buttons"';
	$extraHTML = '
<tr><td>
	<select id="custom_checkout_element" name="custom_checkout_element">
		<option>Collect from Box Office</option>
		<option>Sent in the Post</option>
	</select>
</td></tr>
	';
	// Put the extra HTML in fromt of the buttons row
//	$trolley = str_replace($buttonsRowTag, $extraHTML.$buttonsRowTag, $trolley);
	return $extraHTML;
}
add_filter('stageshow_filter_trolley_extrarows', 'StageshowFilterTrolleyExtraRows', 10, 1);

/*
------------------------------------------------------------------------------------------------

	stageshow_filter_GatewayRedirectURL - Modify the redirect to Gateway URL
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterGatewayRedirectURL($url)
{
	$showNames   = array("The Wordpress Show", "StageShow ROCKS");
	$confirmURLs = array("http://yoursite.org/checkout-confirm-wps", "http://yoursite.org/checkout-confirm-ssr");
	$cancelURLs  = array("http://yoursite.org/checkout-cancel-wps", "http://yoursite.org/checkout-cancel-ssr");

	$rawURL = urldecode($url);

	foreach ($showNames as $index => $showName)
	{
	
		if (StageShowLibMigratePHPClass::Safe_strpos($rawURL, $showName))
		{
			if ($confirmURLs[$index] != "")
			{
				$url = remove_query_arg('return', $url);
				$url = add_query_arg('return', urlencode($confirmURLs[$index]), $url);
			}
			
			if ($cancelURLs[$index] != "")
			{
				$url = remove_query_arg('cancel_return', $url);
				$url = add_query_arg('cancel_return', urlencode($cancelURLs[$index]), $url);				
			}
			break;
		}		

	}

	return $url;
}
add_filter('stageshow_filter_GatewayRedirectURL', 'StageshowFilterGatewayRedirectURL', 10, 1);
	
/*
------------------------------------------------------------------------------------------------

	StageshowFilterDiscount - Calculate Discount
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterDiscount($unused, $cartContents)
{
	$discount = 0;

	/*
		This section can be used to calculate a special discount based on the contents of the trolley.
		The $cartContents variable contains a stdClass object with these trolley contents as elements.			
		The main fields that are available are in the sample code below.
		For a full list of fields available in $cartContents, uncomment the "print_r" line below
	*/

	//StageShowLibUtilsClass::print_r($cartContents, '$cartContents');
		
	$yourVar = $cartEntry->saleDonation;		// Donation value  
	$yourVar = $cartEntry->salePostTickets;		// A boolean value to show if Send By Post has been selected 

	// Loop round all Trolley Entries
	foreach ($cartContents->rows as $cartEntry)
	{
		/*
			This section will loop around once for each main row in the Shopping Trolley.
			The $cartEntry variable will contain a stdClass object with the parameters for the row.			
			The main fields that are available are in this object are as in the sample code below.
		*/
		
		$yourVar = $cartEntry->showName;		// Show Name
		$yourVar = $cartEntry->perfDateTime;	// Performance Date & Time 
		$yourVar = $cartEntry->perfRef;			// Performance Reference
		$yourVar = $cartEntry->priceType;		// Price of the ticket (after application of Discount Code) 
		$yourVar = $cartEntry->zoneRef;			// Seating Zone Ref from Seating Plan
		$yourVar = $cartEntry->seatingVenue;	// Seating Venue from Seating Plan
		$yourVar = $cartEntry->seatLocns;		// Internal refresentation of Seat Location 
		$yourVar = $cartEntry->seatNames;		// Decoded Seat Location 	
		$yourVar = $cartEntry->qty;				// Number of tickets
		
	}
	
	return $discount;
}

add_filter('stageshow_filter_discount', 'StageshowFilterDiscount', 10, 2);
	
/*
------------------------------------------------------------------------------------------------

	stageshow_filter_seattext - Define Text in Seating Plan "Cells"
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterSeatText($textParam, $xCell, $yCell, $xCount, $yCount)
{
	static $firstTime = true;
	
	if (($xCount == 18) && ($yCount == 9))
	{
		return 'Stage (Text from Filter)';
	}
	
	return $textParam;
}
add_filter('stageshow_filter_seattext', 'StageshowFilterSeatText', 10, 5);

/*
------------------------------------------------------------------------------------------------

	stageshow_filter_seattitle - Define Title of Seating Plan "Cells"
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterSeatTitle($textParam, $xCell, $yCell)
{
	return "Locn (From Filter): $textParam";
}
add_filter('stageshow_filter_seattitle', 'StageshowFilterSeatTitle', 10, 3);

/*
------------------------------------------------------------------------------------------------

	stageshow_filter_trolley - Modify Shopping Trolley HTML
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterTrolley($trolley)
{
	$trolley = StageShowLibMigratePHPClass::Safe_str_replace('Send tickets by post', 'Postage (£1.00)', $trolley);
	$trolley = StageShowLibMigratePHPClass::Safe_str_replace('Your Shopping Trolley', 'Your Selections', $trolley);
	$trolley = StageShowLibMigratePHPClass::Safe_str_replace('Message To Seller', 'Your Message', $trolley);
	return $trolley;
}
add_filter('stageshow_filter_trolley', 'StageshowFilterTrolley', 10, 1);

/*
------------------------------------------------------------------------------------------------

	stageshow_filter_boxoffice - Modify BoxOffice HTML
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterBoxOffice($boxoffice)
{
	$boxoffice = StageShowLibMigratePHPClass::Safe_str_replace('Ticket Type', 'Ref', $boxoffice);
	return $boxoffice;
}
add_filter('stageshow_filter_boxoffice', 'StageshowFilterBoxOffice', 10, 1);
	
/*
------------------------------------------------------------------------------------------------

	stageshow_filter_monthheader - Modify the calendar month header
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterMonthHeader($html)
{
	return $html;
}
add_filter('stageshow_filter_monthheader', 'StageshowFilterMonthHeader', 10, 1);

/*
------------------------------------------------------------------------------------------------

	stageshow_filter_emailbody - Modify EMail Body HTML
	
------------------------------------------------------------------------------------------------
*/

function StageshowFilterEMail($emailHTML)
{
	$bodyEndTag = '</body>';
	$footer = '
	<div>EMail generated by StageShow!</div>
	';
	$emailHTML = StageShowLibMigratePHPClass::Safe_str_replace($bodyEndTag, $footer.$bodyEndTag, $emailHTML);
	return $emailHTML;
}
add_filter('stageshow_filter_emailbody', 'StageshowFilterEMail', 10, 1);

