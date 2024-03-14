<?php
/* 
Filename: stageshow_wp-config_sample.php

Description: Customisation Constants Sample Definitions
 
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


/*
	This file lists examples of definitions of Advanced Customisation constants.
	It never loaded when running StageShow.
	
	These constants should be defined in the stageshow site config file (stageshow-wp-config.php) 
	which is located in the  wp-contents/uploads/{plugin}  folder.  Before  version  5.3.5  these 
	constants were located in the wp-config.php  file.
*/

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_MAX_TICKETSEATS
	
	Define the maximum number of tickets in the Prices and Price Plans admin pages.
------------------------------------------------------------------------------------------------
*/
define('STAGESHOW_MAX_TICKETSEATS', 8);

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_STYLESHEET_URL
	
	Define the URL of the stylesheet loaded by StageShow

	If  specified  all  formatting  of  StageShow  output  will  be  determined  by  the 
	replacement stylesheet.
------------------------------------------------------------------------------------------------
*/
define('STAGESHOW_STYLESHEET_URL', STAGESHOW_URL.'/css/stageshow.css');

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_*******BUTTON_URL
	
	Constants to define image URLs for buttons on the Box-Office Page
	
	These sample definitions use images that are included with StageShow distributions. For Custom
	images, the image file should be stored outside of the plugins folder (for example in a 
	folder within the uploads folder) and the URL set accordingly. 
	
	Note: 
	For the uploads folder the STAGESHOW_UPLOADS_PATH constant can be used.
	
	If the STAGESHOW_REMOVEBUTTON_URL define is set to '' the remove link will be replaced
	by a <div> "button"
------------------------------------------------------------------------------------------------
*/
define('STAGESHOW_ADDBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_Add.gif');
define('STAGESHOW_CHECKOUTBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_Checkout.gif');
define('STAGESHOW_CLOSEBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_Close.gif');
define('STAGESHOW_REMOVEBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_Remove.gif');
define('STAGESHOW_RESERVEBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_Reserve.gif');
define('STAGESHOW_SEATSSELECTEDBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_Continue.gif');
define('STAGESHOW_SELECTSEATSBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_SelectSeats.gif');
define('STAGESHOW_SHOWAVAILABLEBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_ShowAvailable.gif');
define('STAGESHOW_CONFIRMANDPAYBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_CommandAndPay.gif');
define('STAGESHOW_SELECTSHOWBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_SelectShow.gif');
define('STAGESHOW_SELECTPERFBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_SelectPerformance.gif');
define('STAGESHOW_UPDATEPRICESBUTTON_URL', STAGESHOW_IMAGES_URL.'stageshow_UpdatePrices.gif');

/* --------------------------------------------------------------------------------
	STAGESHOW_PAYPALEXPRESSBUTTON_URL
	
	The URL of the "Checkout with PayPal" button
	
-------------------------------------------------------------------------------- */
define('STAGESHOW_PAYPALEXPRESSBUTTON_URL', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');		

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_BOXOFFICECOL_********
	
	Constants to redefine column titles on Box-Office pages
------------------------------------------------------------------------------------------------
*/
define('STAGESHOW_BOXOFFICECOL_NAME', 'Event');			// Default: Show
define('STAGESHOW_BOXOFFICECOL_DATETIME', 'Date');		// Default: Date & Time
define('STAGESHOW_BOXOFFICECOL_TICKET', 'Category');	// Default: Ticket Type
define('STAGESHOW_BOXOFFICECOL_PRICE', 'Value');		// Default:	Price
define('STAGESHOW_BOXOFFICECOL_QTY', 'Places');			// Default: Quantity
define('STAGESHOW_BOXOFFICECOL_CARTQTY', 'Seats');		// Default: Quantity/Seat

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_BOXOFFICE_ALLDATES  
	
	Define this constant to disable the hiding of the date/time output  on  the 
	Box-Office page lines where it is the same as the line above. 
	
------------------------------------------------------------------------------------------------
*/
define('STAGESHOW_BOXOFFICE_ALLDATES', 'true');

/* --------------------------------------------------------------------------------
	STAGESHOW_DATETIME_BOXOFFICE_FORMAT
	
	Box-Office Output Date & Time Format
	
	This value must comply with the specification for the "format" parameter
	of the PHP date function. Consult the PHP documentation for details.
	
	Some typical values and the corresponding output are as follows:
								
	  Format                      Output
    d-m-Y H:i                19-12-2017 20:00    (default)
      d-m-Y                     19-12-2017      (date only)
    jS F Y H:i          19th December 2017 20:00	
	l jS F Y H:i    Tuesday 19th December 2017 20:00
		
	See the following URL for a complete list of the date/time format specifiers:
	http://php.net/manual/en/function.date.php
	 
-------------------------------------------------------------------------------- */
define('STAGESHOW_DATETIME_BOXOFFICE_FORMAT', 'd-m-Y H:i');

/* --------------------------------------------------------------------------------
	STAGESHOWLIB_DATETIME_DRILLDOWN_FORMAT
	
	Drilldown Box-Office Selector Date & Time Format
	
	Defines the format of the date & time output for the performance selector when
	the "drilldown" format output of Box-Office is active.
	
	Format is the same as for STAGESHOW_DATETIME_BOXOFFICE_FORMAT
	
-------------------------------------------------------------------------------- */
define('STAGESHOWLIB_DATETIME_DRILLDOWN_FORMAT', 'd-m-Y H:i');

/* --------------------------------------------------------------------------------
	STAGESHOWLIB_IMAGESURL
	
	Base URL for images used by StageShow (i.e. EMail Header etc.)
	
-------------------------------------------------------------------------------- */
define('STAGESHOWLIB_IMAGESURL', 'http://asite.com/images/');

/* --------------------------------------------------------------------------------
	STAGESHOWLIB_NOTETOSELLER_ROWS
	
	This value specifies the number of Rows in the "Message to Sender" text entry
	box in the Shopping Trolley (default value 2)
	
-------------------------------------------------------------------------------- */
define('STAGESHOWLIB_NOTETOSELLER_ROWS', 5);

/* --------------------------------------------------------------------------------
	STAGESHOWLIB_TROLLEYTIMEOUT
	
	This value specifies the time, in seconds, that the shopping trolley can be 
	"inactive" for before it is automatically cleared (default value 1800)
	
-------------------------------------------------------------------------------- */
define('STAGESHOWLIB_TROLLEYTIMEOUT', 1800);

/* --------------------------------------------------------------------------------
	STAGESHOWLIB_GATEWAYAPI_ABANDON_TIMEOUT
	
	This value specifies the time, in minutes, that shopping trolley details are
	held on the database without payment being confirmed. Once the timeout expires
	incomplete checkouts are removed permanently. (default value 4320 - 3 days)
	
-------------------------------------------------------------------------------- */
define('STAGESHOWLIB_GATEWAYAPI_ABANDON_TIMEOUT', 4320);

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_VERIFYLOG_DUPLICATEACTION
	
	This value determines what action will be taken on attempting to verify a sale that has been
	previously verified.
	'ignore' - The previous verification is ignored
	'hide'   - Only the place & date/time of the first verification are shown
	default  - Both Ticket Details and Verification Details are shown
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_VERIFYLOG_DUPLICATEACTION', 'ignore');
define('STAGESHOW_VERIFYLOG_DUPLICATEACTION', 'hide');

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_VALIDATERESULT_TIMEOUT
	
	This value determines the time delay (in thousanths of a second) after a ticket validation
	before the focus is transferred to the Sale Reference input box.
	default  - 1000
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_VALIDATERESULT_TIMEOUT', 1000);

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_PAYMENT_METHODS
	
	This value determines the Payment Method values that can be specified for a manually added
	sale. The name of the Payment Gateway selected will also be added automatically.
	The list is separated by a unique character, which is specified by using it as the first 
	character in the list.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_PAYMENT_METHODS', __('/Cash/Cheque/Credit Card/Debit Card/Voucher'));

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_BOXOFFICE_SORTFIELD
	
	This value determines the database field that will be used to sort the Box-Office output.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_BOXOFFICE_SORTFIELD', 'priceValue');

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_CAPABILITY_BOOKLOCKOUT
	
	This value determines the specific capability that a user must have in their login settings
	to allow them to book "locked out" seats. 
	
	Default Value: StageShow_Sales
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_CAPABILITY_BOOKLOCKOUT', 'priceValue');

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_IDENTIFY_RESERVED
	
	Defininng this value causes reserved seats to be displayed in a different colour to booked 
	seats on the seat selection page, and the seats available page.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_IDENTIFY_RESERVED', true);

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_SCROLLTOANCHOR_OFFSET
	STAGESHOWLIB_SCROLLTOANCHOR_DURATION
	
	These values determine constants used when the Box-Office scrolls to an anchor on  the  page 
	after clicking the Add button. STAGESHOWLIB_SCROLLTOANCHOR_OFFSET is the offset  (in pixels)  from 
	the top of the anchor block that the page scrolls to, and  STAGESHOWLIB_SCROLLTOANCHOR_DURATION is
	the time (in ms) for the scroll to complete.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_SCROLLTOANCHOR_OFFSET', 0);
define('STAGESHOWLIB_SCROLLTOANCHOR_DURATION', 1000);

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_TROLLEYHTML_ABOVETOTAL
	STAGESHOWLIB_TROLLEYHTML_ABOVEBUTTONS
	STAGESHOWLIB_TROLLEYHTML_BELOWBUTTONS
	
	These values determine HTML code that is inserted into the shopping trolley output. This does 
	a simillar function to the "Checkout Note" option in the settings, but gives more flexability
	to the code that can be inserted.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_TROLLEYHTML_ABOVETOTAL', '<tr><td colspan="6">Just some text in the Trolley</td></tr>');
define('STAGESHOWLIB_TROLLEYHTML_ABOVEBUTTONS', '<tr><td colspan="6">Just some text in the Trolley</td></tr>');
define('STAGESHOWLIB_TROLLEYHTML_BELOWBUTTONS', '<tr><td colspan="6">Just some text in the Trolley</td></tr>');

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_LOADING_URL
	
	This value defines the URL of the image displayed while the seating layout is loaded.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_LOADING_URL', STAGESHOW_URL.'/images/loading-segments.gif');

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET
	
	This value defines the time (in seconds) that Performance expiry times are delayed by 
	when a user with StageShow_Sales capability is logged in.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET', 86400);

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_SHOWDBIDS
	
	Defining this constant causes database record IDs to be included in admin page listings.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_SHOWDBIDS', true);

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_MAXTICKETCOUNT
	
	Defining this constant changes the default value for the "Max Ticket Qty" setting, and the
	number of digits allowed for that setting.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_MAXTICKETCOUNT ', 999);

/*
------------------------------------------------------------------------------------------------
	STAGESHOWLIB_RESERVATION_******_MINLEN
	
	Defining one of these constants changes the default value for minimum length of entries in 
	the User Form when making a reservation.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOWLIB_RESERVATION_EMAIL_MINLEN', 3);				
define('STAGESHOWLIB_RESERVATION_PHONE_MINLEN', 8);				
define('STAGESHOWLIB_RESERVATION_COUNTRY_MINLEN', 2);				
define('STAGESHOWLIB_RESERVATION_STATE_MINLEN', 2);				
define('STAGESHOWLIB_RESERVATION_DEFAULT_MINLEN', 3);	

/*
------------------------------------------------------------------------------------------------
	STAGESHOW_BACKEND_DRILLDOWN
	
	Set this constant to true to disable the Drilldown option on the admin pages.
	
------------------------------------------------------------------------------------------------
*/

define('STAGESHOW_BACKEND_DRILLDOWN', false);		
		
?>
