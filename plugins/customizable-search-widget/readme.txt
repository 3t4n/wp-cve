=== Customizable Search Widget ===
Author: Brian D. Goad (bbbco)
Author URI: http://www.briandgoad.com/blog
Plugin URI: http://www.briandgoad.com/blog/customizable-search-widget
Tags: widget, search, customize, customizable, image, text
Requires at least: 2.2
Tested up to: 2.7
Stable tag: 1.2.4

== Description ==
Adds a Customizable Search Widget to give you more control over how the search box looks on your sidebar.

== Installation ==
Extract the zipped file and copy the `customizable-search-widget.php` file to your plugins directory. Then activate the Customizable Search Widget from WordPress, and get busy making your sidebar look even more snazy with an awesome search bar!

== Frequently Asked Questions ==
=Can I Use a Graphic Instead of a Standard Button?=
* Yes, you can input the path to an image located in your current theme's folder. This is located in the Admin panel.
	
=Can I Change What the Button Says?=
* Yes, you can customize this in the Admin panel.

=Can I Modify How the Textbox and Button Look?=
* Yes, you can input a custom defined class, id, or style for both the textbox and button in the Admin panel.

[Ask a question] mailto: bdgoad (at) gmail (dot) com

== Future Plans ==
* Include options to not require a search button
* Hmmm

== Version History ==
= Version 1.2.5 =
* Naming typo (how did that get spelled "buttton"?) caused button not to save correctly

= Version 1.2.4 =
* Bug fixed to show correct button type

= Version 1.2.3 =
* Revert back to function (wp_specialchars_decode only in 2.7.1)

= Version 1.2.2 =
* Use different function because of WP 2.7.1 error (ticket #9090)

= Version 1.2.1 =
* Included corrected functionality for users of PHP4
 
= Version 1.2 =
* Slight change to detect "id=" and "style=" tags in text modifier and overide standard formating with custom user formating.
* Check to ensure path to images has beginning "/"

= Version 1.1 =

* Corrected Other Modifying Attributes to work correctly with both browser and widget backend

= Version 1.0 =
* Initial work onthe widget

== Donate ==
If you have found this widget useful, please consider donating to the poor post-college graduate that I am by clicking on the big yellow Donate button on my website, at http://www.briandgoad.com/blog
