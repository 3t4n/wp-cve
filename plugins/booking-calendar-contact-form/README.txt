=== Booking Calendar Contact Form ===
Contributors: codepeople
Donate link: https://bccf.dwbooster.com/download
Tags: booking,booking calendar,reservation,booking form,reservation form,calendar,payment form,paypal form,reservation calendar,calendar form,paypal booking,paypal calendar,paypal form
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Booking calendar form w/ start and end date or a single date. Booking for house, hotel, services, etc. PayPal payment integration included.

== Description ==

Booking Calendar Form main features:

	» Booking form connected to PayPal
	» Optional availability verification
    » Visual form builder
    » Responsive & mobile optimized
	» Season management
	» Weekly bookings supported
	» Fixed days bookings supported
	» Full-day bookings or partial-day bookings as used in hotels
	» Built-in captcha anti-spam
	» Configurable email texts
	» Configurable validation messages
	» Printable bookings list
	» iCal link to export bookings to external calendars (ex: for exporting to Google Calendar, Airbnb, ...)
	» Multiple colors for marking dates on the booking calendar	
	» ... and more features (see below)

With the **Booking Calendar Contact Form** you can create a **booking form with a reservation calendar** or a **payment form**  connected to a PayPal payment button. The reservation calendar lets the customer select the start (ex: check-in) and end (ex: checkout) dates.

The **reservation calendar** is an optional item, so it can be disabled to create a **general purpose payment/contact form**.

There are two types of bookings available in the calendar configuration: full day bookings or partial day bookings. With full day bookings the whole day is blocked / reserved while in partial day bookings the start and end dates are partially blocked as used for example in **room/hotel bookings**.

Features:

* **Booking form** connected to PayPal: After clicking the reservation / book button on the booking form, the user is redirected to PayPal and after completing the payment three emails will be sent: the automatic PayPal notification, the booking confirmation email to the user sent from the website and the notification email to the website administrator containing the booking details. At this point the booking will be confirmed and will appear in the bookings list and calendar. 
* **Reservation / booking calendar** with optional availability verification: The dates are blocked in the calendar only if the "bookings overlap" option is enabled (it's enabled by default).
* **Season management:** Configuration accepts different prices for different dates (ex: low / medium / high season prices, special date's prices, etc...)
* Supports **full-day bookings** or **partial day bookings** as used in hotels / room reservations (details mentioned above)
* Allows to disable/hide the booking calendar to convert the booking form in a **general purpose contact form**
* The calendar an form are fully responsive and mobile optimized 

Other features also present in this version:

* Built-in captcha anti-spam protection on the booking form
* Easy visual selection of the start and end dates
* Configurable email texts
* Configurable validation messages
* Lets to assign a user to the calendar, this way a user with editor access will access his/her own booking calendar
* List of bookings with print option
* Supports bookings of a fixed length, example weekly bookings
* Price structure can be defined for each number of days
* Calendar configurable settings: date format, min/max dates, block dates, mark holidays, select working weekdays, calendar pages

What isn't included in the free version described here?

* The Form Builder for customizing the form is present only in the commercial versions. The free version works with the classic predefined form included on it.
* Full iCal sync available in Platinum version
* Coupons/discount codes and other minor features are present only in the commercial versions. 

For information about the commercial versions check the plugin's page: https://bccf.dwbooster.com/download

Note: Payments processed through the plugin are SCA ready (Strong Customer Authentication), compatible with the new Payment services (PSD 2) - Directive (EU) that comes into full effect on 14 September, 2019.

= Language Support =

The Booking Calendar Contact Form plugin is compatible with all charsets. The troubleshoot area contains options to change the encoding of the plugin database tables if needed.

Translations are supported through PO/MO files located in the Booking Calendar Contact Form  plugin folder "languages".

The following translations are already included in the plugin:

* English
* Turkish
* Afrikaans (af)
* Albanian (sq)
* Arabic (ar)
* Armenian (hy_AM)
* Azerbaijani (az)
* Basque (eu)
* Belarusian (be_BY)
* Bosnian
* Bulgarian
* Catalan (ca)
* Central Kurdish   (ckb)
* Chinese (China zh_CN)
* Chinese (Taiwan zh_TW)
* Croatian (hr)
* Czech (cs_CZ)
* Danish (da_DK)
* Dutch (nl_NL)
* Esperanto (eo_EO)
* Estonian (et)
* Finnish (fi)
* French (fr_FR)
* Galician (gl_ES)
* Georgian (ka_GE)
* German (de_DE)
* Greek (el)
* Hebrew (he_IL)
* Hindi (hi_IN)
* Hungarian (hu_HU)
* Indonesian (id_ID)
* Italian (it_IT)
* Japanese (ja)
* Korean (ko_KR)
* Latvian (lv)
* Lithuanian (lt_LT)
* Macedonian (mk_MK)
* Malay (ms_MY)
* Malayalam (ml_IN)
* Norwegian (nb_NO)
* Persian (fa_IR)
* Polish (pl_PL)
* Portuguese Brazil(pt_BR)
* Portuguese (pt_PT)
* Russian (ru_RU)
* Romanian (ro_RO)
* Serbian (sr_RS)
* Slovak (sk_SK)
* Slovene (sl_SI)
* Spanish (es_ES)
* Swedish (sv_SE)
* Turkish (tr_TR)
* Tamil (ta)
* Thai (th)
* Ukrainian (uk)
* Vietnamese (vi)

== Installation ==

To install **Booking Calendar Contact Form**, follow these steps:

1.	Download and unzip the booking plugin
2.	Upload the entire booking-calendar-contact-form/ directory to the /wp-content/plugins/ directory
3.	Activate the Booking Calendar Contact Form plugin through the Plugins menu in WordPress
4.	Configure the booking form settings at the administration menu >> Settings >> Booking Calendar Contact Form. 
5.	To insert the booking form into some content or post use the icon that will appear when editing contents

== Frequently Asked Questions ==

= Q: What means each field in the settings area? =

A: The product's page contains detailed information about each field and customization:

https://bccf.dwbooster.com/documentation

= Q: Can I customize the booking calendar colors? =

A: This and other questions are already answered in the product's FAQ at this page:

https://bccf.dwbooster.com/faq

= Q: How to disable the accommodation availability verification? =

A: In the booking calendar administration area, set the field "Accept overlapped reservations?" to "No", this way the accommodation availability verification will be disabled. Note that the calendar will disappear from the admin area when you select this setting since its purpose is to define the available days for booking.

= Q: When is blocked the reservation and sent the email with the rental information? =

A: After clicking the submit / booking button the customer is redirected to a PayPal payment page to submit the payment to confirm it. After completed the payment the reservation is saved into the database and calendar, the dates become un-available it the booking availability verification is enabled and the emails are sent with the booking information and the information entered by the customer on the booking form. At that point the booking information will appear also in the printable bookings list.

= Q: Got this error message at PayPal after clicking the book button: "We cannot process this transaction...". Solution?

A: Into the Booking Calendar Contact Form settings >> PayPal payment form configuration >> PayPal email, be sure to put your own PayPal email address instead the email placeholder put there as default. 

= Q: How to translate the plugin texts? =

A: If you don't want to edit the MO/PO files then just edit the texts that are at the beginning of the file "dex_scheduler.inc.php" (the booking page). The booking form validation texts can be edited from the administration area.

= Q: Can I restrict the number of days to book? =

A: Yes, use the settings fields "Minimum number of nights to be booked" and "Maximum number of nights to be booked" for that purpose. You can also specify a fixed reservation length if you want to allow only bookings of a specific number of days.

= Q: The booking calendar form doesn't appear. Solution? =

A: If the booking calendar form doesn't appear in the public website (in some cases only the captcha appear) then change the script load method to direct, this is the solution in most cases.

That can be changed in the "troubleshoot area" located below the list of booking calendars/items.

= Q: Can the booking calendar notification emails be customized? =

A: In addition to the possibility of editing the email contents you can use the following tags:

* **&lt;%itemnumber%&gt;:** Request ID.
* **&lt;%startdate%&gt;:** Start date for the booking.
* **&lt;%enddate%%&gt;:** End date for the booking.
* **&lt;%totalcost%%&gt;:** Total cost.
* **&lt;%email%%&gt;, &lt;%subject%%&gt;, &lt;%message%%&gt;, &lt;%fieldname1%%&gt;, &lt;%fieldname2%&gt;, ...:** Data entered on each field.

== Other Notes ==

= Troubleshoot Area =

The "Troubleshoot Area" is located below the booking calendars list.

Important!: Use the "Troubleshoot Area" area only if you are experiencing conflicts with third party plugins, with the theme scripts or with the character encoding.

*Script load method:* Change the script load method if the booking form doesn't appear in the public website. In most cases changing the booking form load method to "direct" will solve the problem, however if possible leave the default setting "classic".

*Character encoding:* Update the charset if you are getting problems displaying special/non-latin characters in the booking form, reservation emails or booking list. After updated you need to edit/enter the special characters again. 

= Min and max number of nights to be booked =

There are two new settings in the booking calendar administration area:

*Minimum number of nights to be booked:* If set to a number greater than zero, the booking form won't accept bookings of less than the indicated nights.

*Maximum number of nights to be booked:* The booking form won't accept booking of more than the indicated nights in this field.

= Supplement for specific bookings = 

You can apply supplements for bookings under some specific number of nights, or over a specified number of nights, or both.

The field "*Supplement for bookings between X and Y nights*" makes the booking form add the specified supplement once for bookings between the indicated number of nights.

= Settings for both admin and public calendars =

* Calendar Pages: Number of calendar months to display at the same time
* Calendar Language: Language used for the calendar. The default is auto-detect that works in most cases. If the auto-detect doesn't get the expected language then select it manually.
* Start Weekday: Start weekday, usually Sunday or Monday.
* Date format: Select dd/mm/yyyy or mm/dd/yyyy
* Accept overlapped reservations: Indicate if more than one reservation will be allowed in the same days. Default is "no overlapped" for an active availability verification.
* Reservation mode: Select Complete day means that the first and the last days booked are charged as full days; Partial Day means that they are charged as half-days only.

= Settings for public calendar only =

* Minimum available date: The minimum selectable date in the calendar. Examples: 2012-10-25, today, today + 3 days
* Maximum available date: The maximum selectable date in the calendar. 
* Minimum number of nights to be booked: The booking form won't accept less than the indicated nights on this field.
* Maximum number of nights to be booked: The booking form won't accept more than the indicated nights on this field.
* Working dates: Working dates are the dates that accept bookings. Use this for example to disable the weekends or other specific weekdays.
* Disabled and special dates: Click a date to mark it as disabled, for example for disabling holidays or other dates where reservations aren't allowed.
* Enable Fixed Reservation Length?: Use this for allowing only bookings of a specific number of days. More details in the next section 

= Fixed Reservation Length = 

If you enable the option "Fixed Reservation Length" for the calendar that means that you want to accept only bookings of the specified length (number of days), for example for accepting only 7 days bookings.

The settings fields for this option are:

* Fixed reservation length (days): The number of days that must have the booking.
* Start Reservation Date: Use this for allowing specific weekdays as start of the reservation, for example if you want to indicate that all the bookings must start on a Monday.
* Disabled and special dates: When the "Fixed Reservation Length" is enabled you can use the calendar for indicating specific starting days for the bookings. This is useful if you are offering packages that start only on specific dates.

When this mode is enabled, the customer only has to select the start day for the booking and the end date is calculated automatically.


= Form Builder =

The form builder is fully available only in the commercial versions. If allows to fully customizing the form: add, edit and remove fields.


= Submit Button =

There is an area to indicate the label used for the submit button. The class="pbSubmit" can be used to modify the button styles. The styles can be added into the "Customization area >> Add Custom Styles" (at the bottom of the page that contains the list of calendars): For further modifications the submit button is located at the end of the file "dex_scheduler.inc.php". For general CSS styles modifications to the form and samples check this FAQ: https://bccf.dwbooster.com/faq#q82


= Validation Texts = 

Use this area for translating of setting custom validation messages for the form fields.


= Price Configuration =

This administration section allows setup most of the price structure for the bookings. The following settings fields are available:

* Currency: The currency used at PayPal. Example currency codes: USD, EUR, GBP, CAD, AUD, NZD, CHF, MXN, CZK, DKK, NOK, SEK, HKD, SGD, HUF, ILS, JPY, PLN
* Default request cost (per day): The default request cost for each day. This amount is the fee per day, for example if the value specified here is us$25 and the reservation is for 4 days then the payment amount will be us$100.
* Total request cost for specific # of days, # of days to setup: The "total" request cost for bookings of a specific number of days. This has precedence over the default cost.
* Supplement for bookings between X and Y nights: Supplement (or discount if negative) can be applied to bookings which length is into the specified range of days.
* Seasons configuration: Allows to apply different prices on different seasons (configured with start and end dates). The season prices will overwrite the default request cost and the total request cost for specific days if that option is used.

= PayPal Payment Configuration = 

Settings related to the PayPal payment processing. The settings fields are:

* Enable PayPal Payments?: The free version supports PayPal enabled or disabled. The commercial versions support other options like "Optional" to let the customer select PayPal or just submit the booking for a payment later. If "Optional" is selected (commercial versions), a radio-button field will be added to let the customer select "Pay with PayPal." or "Pay later".
* PayPal email: The email of the PayPal that will receive the payments.
* PayPal product name: The name that will appear to the customer at PayPal.
* URL to return after successful payment: After the PayPal payment the user may go back to a page into your website (usually a "thank you" page). Paste here the complete address of that page. Important note: This field is used as the "acknowledgment / thank you message" even if the PayPal feature isn't used (commercial versions).
* URL to return after an incomplete or cancelled payment: After a canceled/incomplete PayPal payment the user may go back to a page into your website, usually a page with more instructions or requesting feedback. Paste here the complete address of that page.
* PayPal language: The language that will be used for the PayPal payment. It's any PayPal supported language.
* Taxes (applied at PayPal): Specify a number (percent) for adding taxes at PayPal (example: enter 10 for a 10%, don't include the % symbol).
* Discount Codes: Available only in commercial versions. Adds a field for entering discount codes and apply them to the price.

= Optional Services/Items Field =

This feature is available only in the commercial versions. These area optional fields that appear only if some option is specified. Useful for selecting additional items with prices for the booking, example: optional services like "Internet" or "Parking" in a hotel booking.

= Notification Settings to Administrator(s) =

Setup area for the notifications sent to the administrator(s) after the booking is completed. Settings fields:

* Notification "from" email: The email used as from in the notifications.
* Send notification to email: The email address where the notification will be sent to (ex: your email address).
* Email subject notification to admin: Subject of the notification email that you will receive.
* Email notification to admin: Content of the notification email that you will receive. Keep the tag %INFORMATION% that will be replaced automatically by the booking information.

= Email Copy to User (auto-reply): =

Setup area for the auto-reply email sent to the customer after the completing the booking. Settings fields:

* Email field on the form: Select which of the form field will contain the user's email address to send the auto-reply.
* Email subject confirmation to user: Subject of the thank you/confirmation email sent to the user (customer) after completing the payment.
* Email confirmation to user: Content of the thank you/confirmation email sent to the user (customer) after completing the payment. Keep the tag %INFORMATION% that will be replaced automatically by the booking information.

= Catpcha Verification =

Setup area for the built-in antispam captcha verification. Settings fields:

* Use Captcha Verification?: Select if the captcha image will be used.
* Width: Width of the captcha image.
* Height: Height of the captcha image.
* Chars: How many characters will appear in the captcha image.
* Min font size: Minimum size used for the font (randomized).
* Max font size: Maximum size used for the font (randomized).
* Preview: Preview for checking how the captcha image will look.
* Noise: Amount of noise to make it stronger.
* Noise Length: Length of the noise to modify its look.
* Background: Background color.
* Border: Border color.
* Font: Base font used to render the text. Four options already included.



== Screenshots ==

1. Booking Calendar form / contact form.
2. Inserting booking calendar into a page.
3. Managing the booking calendar.
4. Editing the calendar and rental settings.
5. Booking Calendar settings.

== Changelog ==

= 1.0.1 =
* Interface modifications
* Fix for iCal format bug (the events weren't marked as all day events)
* Compatible the latest WP and jQuery versions
* Replaced YUI calendar with a jQuery calendar
* Added support for weekly bookings
* New language translations
* Several bug fixes and new features for deposit payments
* Fixed encoding issues at PayPal
* Fixed bug in the address of the corner images for partial days mode.
* CSS styles updates to avoid conflicts with theme styles
* New options for customizing the emails
* New feature for applying taxes


= 1.0.2 =
* Compatible with the latest WordPress versions
* New language translations
* Fixed bugs in the language traslations
* Interface updates
* Fixed warning that appeared with PHP safe mode restrictions
* Sanitized GET parameters used in queries 
* Fixed issue with the site home URL in WP with folders in non-default locations
* Fixed bug in the url generated for the IPN under HTTPS connections

= 1.0.3 =
* Fixed bug in the function that generates the url for the ipn notification
* Update for SQL issues - sanitized values
* Update to make the IPN work over SSL connections
* Fixed bug in email processing
* Updates to minimize conflicts with third party themes and plugins

= 1.0.4 =
* New feature to show/hide the calculated cost below the calendar
* CSS update to avoid conflicts with the new WP default theme
* Compatible with the latest WordPress 4.2.2 version

= 1.0.5 =
* Fixed notice warning in not active feature
* Fixed bug in date calculation
* Fixed IPN address under SSL connections
* CSS update to fix the floating dialog height
* Fixed conflict of captcha image with zLib library
* Fixed bug in the path calculated for the admin area
* Translations updated
* Improved security
* Compatible with the latest WordPress 4.2.3 

= 1.0.6 =
* Modification to prevent conflicts with ZLib
* Update to the heading tags for the new WP version
* Fixed bug in IE 11
* Update to translations
* Implemented better submission process
* Compatible with the latest WordPress 4.3 

= 1.0.7 =
* Value of POST parameter has been corrected
* Update to Dutch language file
* Code sanitization

= 1.0.8 =
* Fixed bug in admin area

= 1.0.9 =
* Added support for new charsets

= 1.0.10 =
* Added access for subscribers to manage their calendars

= 1.0.11 =
* Updated menu slug

= 1.0.12 =
* Interface updates

= 1.0.13 =
* Tested and compatible with WordPress 4.4

= 1.0.14 =
* Fixed style in the new WP 4.4 theme

= 1.0.15 =
* Fixed captcha issue

= 1.0.16 =
* Fixed null value issue in query

= 1.0.17 =
* CSS styles updated

= 1.0.18 =
* DB structure update

= 1.0.19 =
* Fixed captcha issue in Windows servers

= 1.0.20 =
* Improved captcha security

= 1.0.21 =
* Updates to admin interface

= 1.0.22 =
* Translation opdates
* Captcha code updates
* Fixed Polish language ID

= 1.0.23 =
* Fixed PHP session issues

= 1.0.24 =
* Fixed XSS and SQL injection issues

= 1.0.25 =
* Updated API URLs

= 1.0.26 =
* Added nonces to delete actions

= 1.0.27 =
* Added price formatting

= 1.0.28 =
* Fixed bug in admin settings

= 1.0.29 =
* Fixed POST parameter in booking form

= 1.0.30 =
* Multiple language updates

= 1.0.31 =
* Compatible with WP 4.5

= 1.0.32 =
* Fixed posted data encoding

= 1.0.33 =
* Script load updates for WP 4.5

= 1.0.34 =
* Multiple destination emails supported

= 1.0.35 =
* Moved old changelogs to external file

= 1.0.36 =
* Changed init hook to avoid plugin conflicts

= 1.0.37 =
* New reply-to feature for admins

= 1.0.38 =
* Removed old versions of Booking Calendar Contact Form

= 1.0.39 =
* Fix in throubleshoot area

= 1.0.40 =
* Fix in email delivery

= 1.0.41 =
* New plugin doc and website

= 1.0.42 =
* Updates and docs

= 1.0.43 =
* Params sanitization

= 1.0.44 =
* Modified from email format

= 1.0.45 =
* Update about new versions

= 1.0.46 =
* Settings link fixed

= 1.0.47 =
* Added fr_CA language

= 1.0.48 =
* Compatible with WP 4.6

= 1.0.49 =
* New custom mod form

= 1.0.50 =
* Changed placeholder from email to avoid PHPMailer errors

= 1.0.51 =
* Removed stripslashes function

= 1.0.52 =
* Added additional security verification

= 1.0.53 =
* Improved magic quotes detection

= 1.0.54 =
* Instructions update

= 1.0.55 =
* Language update

= 1.0.56 =
* Interface update

= 1.0.57 =
* Corrections to French language

= 1.0.58 =
* Fixed lang identifiers

= 1.0.59 =
* Support link tracking

= 1.0.60 =
* DB structure updates

= 1.0.61 =
* PayPal IPN update

= 1.0.62 =
* Improvements to support docs and service

= 1.0.63 =
* Compatible with WP 4.7

= 1.0.64 =
* PayPal button update

= 1.0.65 =
* Instructions update

= 1.0.66 =
* DB updated

= 1.0.67 =
* New support area

= 1.0.68 =
* Captcha update

= 1.0.69 =
* Database improvements

= 1.0.70 =
* Additional config field

= 1.0.71 =
* Fixed bug in bookings list

= 1.0.72 =
* Product name update

= 1.0.73 =
* Admin intf update

= 1.0.74 =
* Dashboard updates

= 1.0.75 =
* Old update queries removed

= 1.0.76 =
* Security improvement

= 1.0.77 =
* Code improvements

= 1.0.78 =
* Compatible with WP 4.7.3

= 1.0.79 =
* Update related to the parameters in the PayPal IPN notification

= 1.0.80 =
* Decreased params number in PayPal IPN

= 1.0.81 =
* Tags updated to max 12

= 1.0.82 =
* Added email validation in admin

= 1.0.83 =
* Type email for email input fields in booking form

= 1.0.84 =
* Added more field validation to settings form

= 1.0.85 =
* Better captcha code for loading speed and security verificatins
* Better SQL sanitization

= 1.0.86 =
* Optional review box

= 1.0.88 =
* Replaced link tags by wp_enqueue_style
* Removed notices from admin area when WP_DEBUG enabled

= 1.0.89 =
* PayPal email sanitization

= 1.0.90 =
* Removed multiple CSS style references replaced by wp_enqueue_style

= 1.0.91 =
* Tested and compatible with WordPress 4.8

= 1.0.92 =
* Removed deprecated PayPal parameters

= 1.0.93 =
* Cleanup for PayPal parameters

= 1.0.94 =
* Accessibility updates

= 1.0.95 =
* New feature for exporting bookings through iCal link/file

= 1.0.96 =
* Moved plugin website and links to SSL

= 1.0.97 =
* Removed use of deprecated MySQL functions

= 1.0.98 =
* Validation updates

= 1.0.99 =
* PayPal return address santized

= 1.1.05 =
* Fixed number validation issue in Google Chrome

= 1.1.06 =
* Improved print option for bookings list

= 1.1.07 =
* Fixes in admin area

= 1.1.08 =
* Better PayPal currency selection

= 1.1.09 =
* Price number formatting

= 1.1.10 =
* Fixed bug in validation translations

= 1.1.11 =
* Added general default values

= 1.1.12 =
* Admin settings validation improvements

= 1.1.15 =
* Security improvement for queries sanitization

= 1.1.16 =
* Improved file extension verification

= 1.1.17 =
* Encoding fix un public booking form

= 1.1.18 =
* Compatible with WordPress 4.9

= 1.1.19 =
* Optimized code. Removed blocks not longer needed.

= 1.1.21 =
* Improvements to the captcha settings area

= 1.1.22 =
* Fixed bug in review panel

= 1.1.23 =
* Added CSS and JavaScript customization panel

= 1.1.24 =
* Improved submission to prevent duplicated bookings

= 1.1.25 =
* Fixes to language settings

= 1.1.26 =
* Default script loading changed to classic

= 1.1.27 =
* Better currency auto-detection

= 1.1.28 =
* Fixes captcha settings issue

= 1.1.30 =
* Database structure update

= 1.1.31 =
* New feature for backup and restore forms

= 1.1.33 =
* Label updates to fix layout issues

= 1.1.34 =
* Improved CSS edition area

= 1.1.35 =
* Fixed conflict with autoptimize plugin

= 1.1.36 =
* Fixed captcha reloading issue

= 1.1.37 =
* Better captcha notification messages

= 1.1.38 =
* Improvements to bookings list to match GPDR requirements

= 1.1.39 =
* Added min and max date validations

= 1.1.40 =
* Code speed optimizations and POST data sanitization

= 1.1.41 =
* Easier activation process

= 1.1.42 =
* Fixed initialization issue and added opt-in feedback

= 1.1.43 =
* Fixed bug in activation process

= 1.1.44 =
* Added support for INR - Indian Rupee and ARS - Argentine peso currencies

= 1.1.45 =
* Database creating encoding fix

= 1.1.46 =
* New fix to activation issue

= 1.1.47 =
* Fixed conflict with bootstrap calendar

= 1.1.49 =
* Compatible with Gutenberg

= 1.1.50 =
* Fix to Gutenberg integration

= 1.1.51 =
* Fixed conflict with Gutenberg editor

= 1.1.52 =
* Fixed magic quotes issue

= 1.1.53 =
* Interface icons updated

= 1.1.54 =
* Improved custom CSS and JS edition area

= 1.1.55 =
* Improved CSS customization

= 1.1.56 =
* Fixed jQuery slim conflict

= 1.1.57 =
* Currency setting clarifications

= 1.1.58 =
* Fixed conflict with third party plugins

= 1.1.59 =
* Major update: new visual form builder, publishing wizard, improved interface

= 1.1.60 =
* Gutenberg copatibility updates

= 1.1.61 =
* Fixed conflict with loading page plugins

= 1.1.62 =
* Interface updates and jQuery calendar fixes

= 1.1.63 =
* New option for adding bookings in the admin

= 1.1.64 =
* Major update: Fully responsive mobile optimized calendar

= 1.1.65 =
* Fixed visualization issue.

= 1.1.66 =
* Improved calendar rendering

= 1.1.67 =
* Improved interface and admin calendar

= 1.1.68 =
* New settings for calendar width and height

= 1.1.69 =
* New Gutemberg block. Optimized plugin size.

= 1.1.70 =
* Fixed data processing issue

= 1.1.71 =
* Improved data management

= 1.1.72 =
* Removed use of CURL

= 1.1.73 =
* Fixed bug in dates selection

= 1.1.74 =
* Fixed bug in Gutemberg integration

= 1.1.75 =
* Improved language translations and interface styles

= 1.1.76 =
* Better email auto-config and compatible with latest WP version

= 1.1.77 =
* Fixed bug in email settings

= 1.1.78 =
* Fixed bug in saving data process

= 1.1.79 =
* Fixed conflict with lazy loading feature of Jetpack

= 1.1.80 =
* Fixed conflict with Yoast SEO

= 1.1.81 =
* Fixed calender CSS styles issue

= 1.1.82 =
* Review link update

= 1.1.83 =
*  Compatible with WordPress 5.2

= 1.1.84 =
*  Added onchange event to date fields

= 1.1.85 =
*  Compatible with Google Translate 

= 1.1.86 =
* Update for compatibility with WordPress 5.2

= 1.1.87 =
* Code improvements

= 1.1.88 =
* Misc code improvements

= 1.1.89 =
* Fix to database encoding

= 1.1.90 =
* Updates to publish section

= 1.1.91 =
* Excluded captcha image from smush optimization

= 1.1.92 =
* Better support for single day bookings

= 1.1.93 =
* PSD 2 complaint - SCA Ready

= 1.1.94 =
* Bug fixes and code improvements

= 1.1.95 =
* Compatible with WordPress 5.3

= 1.1.96 =
* Fixed bug in email delivery

= 1.1.97 =
* Fixed conflict with bootstrap date-picker

= 1.1.98 =
* Interface improvements

= 1.1.99 =
* Rendering speed improvements

= 1.2.07 =
* New hooks for conversion tracking

= 1.2.08 =
* Compatible with WordPress 5.4

= 1.2.09 =
* Fixed conflict with third party optimization plugin

= 1.2.10 =
* Update for WordPress Editor

= 1.2.11 =
* Fastest form load speed

= 1.2.12 =
* Minimized scripts file size

= 1.2.14 =
* Fixed availability rendering bug

= 1.2.15 =
* Improved import/export area

= 1.2.16 =
* Compatible with WordPress 5.5

= 1.2.17 =
* Replaces jQuery deprecated code

= 1.2.18 =
* Compatible with WordPress 5.6

= 1.2.19 =
* New feature to locate the calendar into the form builder

= 1.2.20 =
* Script updates
* Compatible with WordPress 5.7

= 1.2.21 =
* Added feature for processing bookings without payment process involved

= 1.2.22 =
* Add-ons update. Improvements.

= 1.2.23 =
* CSS updates

= 1.2.24 =
* Database structure update

= 1.2.25 =
* PHP 8 compatibility updates
* CSS improvements

= 1.2.26 =
* Language support improvements

= 1.2.27 =
* Compatible with WordPress 5.8

= 1.2.28 =
* Improved price calculation

= 1.2.29 =
* Compatible with WordPress 5.9

= 1.2.30 =
* jQuery compatibility update

= 1.2.31 =
* Compatible with WordPress 6.0

= 1.2.32 =
* Validation fix

= 1.2.33 =
* Price calculation fix

= 1.2.34 =
* New price calculation fix

= 1.2.35 =
* Feedback panel update

= 1.2.36 =
* Compatible with WP 6.1

= 1.2.37 =
* Translation fixes

= 1.2.38 =
* Script fix

= 1.2.39 =
* PHP 8 fixes

= 1.2.40 =
* Javascript and settings fixes

= 1.2.41 =
* Output encoding fix

= 1.2.42 =
* Output encoding fix

= 1.2.43 =
* PHP 8 update

= 1.2.44 =
* Compatible with WP 6.2

= 1.2.45 =
* WP 6.2 update

= 1.2.46 =
* Compatible with WP 6.3

= 1.2.47 =
* Duplicate email fix

= 1.2.48 =
* Compatible with WP 6.4

= 1.2.49 =
* iCal Export fix

== Upgrade Notice ==

= 1.2.49 =
* iCal Export fix