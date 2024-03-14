=== 📷 Simple QR Code Generator Widget ===
Contributors: nemezis
Tags: simple qr code , qr, code, bar code generator, simple bar code generator
Requires at least: 2.0.2
Tested up to: 5.9
Stable tag: trunk


Simple QR Code Generator

== Description ==

Very Simple and intuitive QR Code Generator that allows you to place a QR Code widget on your sidebar.

You can set custom QR code image size as well as create QR code for specific urls or content. 

Tool is based on Google Chart Tools.

⚠️ Version 2.0 onwards only compatible with latest wordpress installations

== Installation ==

1. Upload `qr-code-generator-widget.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place QR Code Generator Widget in the widget area

== Frequently Asked Questions ==

Any questions?

== Screenshots ==

1. The widget settings
2. Widget on the front end

== Changelog ==

= 1.0 =
* Basic functionality

= 1.1 =
* Links update

= 1.2 = 
* Removing deprecated functions

= 1.3 =
* Wordpress version 3.9.1 support
* Renamed widget from widget_qrCode to widget_qr_code. This update will enforce css class update to '.widget_qrCode'

= 1.4 = 
* Wordpress version 4.3.1 support
* Renamed functions for cosistency
* Code formatting
* added sprintf function 
* added support for https sadly Google API is not availible on https ;)

= 1.5 =
* Remove closing tag from end of the plugin to prevent 'Warning: session_start()' error

= 1.6 =
* Wordpress version 4.6.1 support

= 1.7 =
* Wordpress version 4.7 support

= 1.8 =
* Add more validation to user provided values; 
* Add HTML5 range field for width and height; restrict values to integers only; max 450 is to avoid 404 image src errors;
* Update logic for generating default qr code - it will default to the blog title and Site Address (URL);

= 1.9 =
* Fix reported typo in check for empty url

= 1.10 =
* Wordpress version 4.9.1 support
* Change default url logic; When url is empty, widget will return current page url as qr code

= 2.0 =
* Not backward compatible 
* Wordpress version 5.4 support
* Complete refactor to support new Wordpress version

= 2.1 =
* Wordpress version 5.8 support

= 2.2 =
* Wordpress version 5.9 support

<?php code(); // goes in backticks ?>`
