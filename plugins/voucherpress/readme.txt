=== VoucherPress ===
Contributors: mrwiblog, Christian Serron (http://twitter.com/cserron)
Donate link: http://www.stillbreathing.co.uk/donate/
License: GPLv2 or later
Tags: buddypress, voucher, vouchers, pdf, print, download, offer, code, special, coupon, ticket, token, 
Requires at least: 2.8
Tested up to: 4.7.2
Stable tag: 1.5.8

VoucherPress is a Wordpress plugin that allows you to give downloadable, printable vouchers/coupons in PDF format away on your site.

== Description ==

**Thanks for using VoucherPress. It's great to see so many people creating vouchers for all kinds of business. From pizza joints to jewellers, cleaning companies to sports clubs - you've created thousands of vouchers, and I'm really proud to have created something which has been useful to so many people.**

**As I work on the next version of VoucherPress it would be really helpful if you could [fill in this short survey](http://www.stillbreathing.co.uk/projects/voucherpress-the-next-chapter). Many thanks for your time.**

Have you ever wanted to give away vouchers, tickets, coupons or tokens on your website? If so this plugin is for you. You can create a voucher with whatever text you want, choosing the layout and font from a range of templates (you can also add your own templates). Vouchers can then be viewed, downloaded and printed from a specified URL.

There are shortcodes to add a link to a particular voucher, to show an unordered list of all your vouchers, or to show the registration for to request a restricted voucher.

You can require visitors to provide their name and email address to get a voucher. If an email address is required an email is sent to the address with a link to the voucher URL. Each voucher has a unique code, and vouchers that have an email address associated with them can only be used once, so once a registration-required voucher is downloaded it can't be downloaded again.

=== Hooks ===

From version 1.1.2 the plugin also offers a selection of hooks which you can use to run your own custom code. The hooks are:

==== voucherpress_create ====

When a voucher is created, this hook returns the properties of the voucher. You can use it like this:

add_action( 'voucherpress_create', 'my_voucherpress_create_function' );
function my_voucherpress_create_function( $id, $name, $text, $description, $template, $require_email, $limit, $startdate, $expiry ) {
	// do something here...
}

==== voucherpress_edit ====

When a voucher is edited, this hook returns the properties of the voucher. You can use it like this:

add_action( 'voucherpress_edit', 'my_voucherpress_edit_function' );
function my_voucherpress_edit_function( $id, $name, $text, $description, $template, $require_email, $limit, $startdate, $expiry ) {
	// do something here...
}

==== voucherpress_register ====

When someone registers to download a voucher and an email is sent to them, this hook returns the voucher and the users details. You can use it like this:

add_action( 'voucherpress_register', 'my_voucherpress_register_function' );
function my_voucherpress_register_function( $voucher_id, $voucher_name, $user_email, $user_name ) {
	// do something here...
}

==== voucherpress_download ====

When someone downloads a voucher, this hook returns the voucher and the users details. You can use it like this:

add_action( 'voucherpress_download', 'my_voucherpress_download_function' );
function my_voucherpress_download_function( $voucher_id, $voucher_name, $code ) {
	// do something here...
}

The plugin also makes use of the __() function to allow for easy translation.

Thanks to Christian Serron (http://twitter.com/cserron) for the code to make the vouchers work in widgets (currently disabled, I'm working on this) and to Barry (http://www.betakeygiveaway.com/) for bug testing above and beyond the call of duty.

== Installation ==

The plugin should be placed in your /wp-content/plugins/ directory and activated in the plugin administration screen. The plugin is quite large as it includes the TCPDF class for creating the PDF file.

== Shortcodes ==

There are four shortcodes available. The first shows a link to a particular voucher, and is in the format:

[voucher id="123"]

The "id" parameter is the unique ID of the voucher. The correct ID to use is available in the screen where you edit the voucher.

You can also how the description after the link:

[voucher id="123" description="true"]

The second shows a link to a voucher, but with a preview of the voucher (just the background image, no text) and the voucher name as the image alternate text:

[voucher id="123" preview="true"]

And you can show the description after the preview as well:

[voucher id="123" preview="true" description="true"]

You can also show an unordered list of all your live vouchers using this shortcode:

[voucherlist]

And a list of all live vouchers with their descriptions:

[voucherlist description="true"]

And you can also show the form for people to enter their name and email address if they wish to register for a restricted voucher:

[voucherform id="123"]

The shortcodes for any voucher can be found on the edit screen for that voucher. Just click the 'Shortcodes' button.

== Frequently Asked Questions ==

= My voucher PDF files are corrupted. Why? =

This is normally because PHP isn't given enough memory to create print-resolution PDF files. If you open one of your corrupted PDF files in a text editor it will say something like this:

Fatal error:  Out of memory (allocated 31981568) (tried to allocate 456135 bytes) in
/some/thing/here/wp-content/plugins/voucherpress/tcpdf/tcpdf.php

Speak to your hosts or system administrator to give PHP more memory. I'm also working on a way for VoucherPress itself to work around this problem.

= Why is the plugin so big? It's over 20 MB! =

I know, and I'm sorry, but the TCPDF sytem which generates the PDF documents is pretty big. And there's 40+ default layout templates which aren't small either.

= Why did you write this plugin? =

I'm not sure. It seemed like a good idea, and gave me opportunity to learn a little bit about the TCPDF class.

= Does this plugin work with any e-commerce plugins? =

Not at the moment, but I'm sure it could if those e-commerce plugin developers want to get in touch.

= Can I add my own codes, for example if I want to give away numbered coupons? =

Yes, you can add your own codes for any voucher. When people download the voucher they will be given one of your codes (in the same order you entered them). Once one of your codes is used on a voucher it can't be used again.

== Screenshots ==

1. Creating or editing a voucher
2. Viewing the list of your vouchers, and the mot popular downloaded ones
3. A sample voucher
4. A Microsoft Window print dialog showing the voucher on the paper
5. All the default templates

== Changelog ==

= 1.5.8 (2017/01/29) =

Fixed deprecation error messages. Removed survey text. Fixed loading the JavaScript and CSS over HTTPS. Tested up to 4.7.2.

= 1.5.7 (2015/06/02) =

Added link to [a short survey](http://www.stillbreathing.co.uk/projects/voucherpress-the-next-chapter) about the future of VoucherPress.

= 1.5.6 (2015/02/01) =

Fixed bug with HTTP method check. Updated readme file.

= 1.5.5 (2015/01/31) =

Changed use of ABSPATH to plugin_dir_path(), as that is what good developers use.

= 1.5.4 (2014/11/22) =

Fixed error encountered with some browsers who send two requests to download a file (the first one is a HEAD request). Thanks to Susan Grant for reporting the error.

= 1.5.3 (2014/07/01) =

Added checking for whitespace output by other plugins stopping rendering of the voucher, which is one of the most regular "bugs" reported

= 1.5.2 (2014/03/06) =

Fixed bug with deleting template
Formatted code to WordPress coding standards

= 1.5.1 (2014/02/28) =

Fixed bug with previewing voucher

= 1.5 (2014/02/28) =

Added page to list all vouchers
Changed a bit of text to be clearer

= 1.4 (2011/07/14) =

Fixed bug with vouchers that require an email address
Added optional displaying of voucher descriptions in shortcodes
Style changes

= 1.3 (2011/06/03) =

Allowed the expiry date to be a number of days in the future. Also added a start date on which a voucher will become available.

= 1.2 (2010/12/20) =

Changed templates to work at 150dpi to overcome memory limit problem. Also added code to temporarily increase PHP memory limit to 64mb while a voucher is being rendered. Fixed activation bug caused by WordPress breaking the Plugin Register plugin. Upgraded to recent version of TCPDF. Allowed CSV download for all vouchers, not just ones requiring an email address. Added nonce fields for security.

= 1.1.2 (2010/11/20) =

Added shortcodes and description field

= 1.1.1 (2010/10/24) =

Changed check for writable template directory to fix bug

= 1.1 (2010/10/08) =

Added expiry date and registered name to voucher
Added ability to delete (non-sitewide) templates

= 1.0.2 (2010/09/13) =

Fixed bugs with CSV download

= 1.0.1 (2010/09/13) =

Fixed bugs with expiry date

= 1.0 (2010/07/04) =

Added different voucher code options. Added registration form shortcode. Moved JavaScript into separate file. Lots of bug fixes.

= 0.8.6 (2010/05/26) =

Added voucher name to CSV report

= 0.8.5 (2010/05/14) =

Updated plugin URI

= 0.8.4 (2010/04/20) =

Implemented new Plugin Register version.

= 0.8.3 (2010/04/17) =

Fixed bugs with expiry date, download limit and email registration. Changed Plugin Register to be opt-in.

= 0.8.2 (2010/04/12) =

Stopped failure of chmod() on custom templates directory causing warnings

= 0.8.1 (2010/04/01) =

Added voucher code to the CSV download. Added Plugin Register code.

= 0.8 (2010/03/23) =

Made activation even more robust. Changed expiry to separate year/month/day fields. Prepared for own voucher codes.

= 0.7 (2010/03/12) =

Made activation more robust. Fixed bug with non-writeable templates directory. Added expiry date.

= 0.6 (2010/03/09) =

Added shortcode with preview of voucher

= 0.5.3 (2010/03/01) =

Fixed bug with upgrades not creating tables

= 0.5.2 (2010/02/25) =

Fixed bug when no 404.php page found in template. Added link to voucher to voucher edit page. Clarified some sections of the voucher edit page.

= 0.5.1 (2010/02/17) =

Added a support link and donate button

= 0.5 (2010/02/15) =

Fixed bug with download counts occurring in older versions of MySQL

= 0.4 (2010/02/14) =

Fixed bug with email registration. Changed PDF to force download.

= 0.3 (2010/02/12) =

Added check for PHP5

= 0.2 (2010/02/12) =

Fixed bugs with SQL

= 0.1 (2010/02/11) =

Initial version