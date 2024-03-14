=== Pretty Pinterest Pins ===
Contributors: jowilki
Donate link: http://jodiwilkinson.com
Tags: pinterest, widget, sidebar, plugin
Requires at least: 2.8
Tested up to: 4.3.1
Stable tag: trunk

A plugin to show off images, captions, and links from your latest Pinterest activity.

== Description ==

This plugin allows you to display thumbnails and links to yours (or anyones!) latest Pins from Pinterest in your sidebar.  It is styled after Pinterest and offers a clean and modern look to show off your pins.

This widget is fully customizable, you can:

* pull the latest pins from any Pinterest user
* display only pins from specific boards
* choose the number of pins to display
* show or hide the image captions
* display a "Follow me on Pinterest" button under your pins

Features:

* Clean and Modern Look (like Pinterest)
* Looks great on light and dark backgrounds
* Captions scale and look nice with long or short text
* Semantic HTML
* Easy to Install: Simply add a Pinterest username and you're good to go!

More to come when Pinterest opens their API!  This project is maintained on github if you'd like to contribute : 
https://github.com/jowilki/pretty-pinterest-pins


== Installation ==

1. Copy the pretty-pinterest-pins folder contained in the zip file to your plugins folder, /wp-content/plugins/
2. Activate it through the 'Plugins' menu on the WordPress Administration page
3. Under Appearance > Widgets you will see the "Pretty Pinterest Pins" widget. Drag it to your sidebar and place it where you want it to appear.
4. Add a Pinterest username and change any of the default settings.
5. To show pins only from a specific board, you can enter the name of the board in the Specific Board input box. The correct format for board names is found in the URL after the username.  For example, the URL for my board called "Cool Stuff" is http://pinterest.com/jowilki/cool-stuff/. I would enter my username *Jowilki* in the username box and *cool-stuff* in the specific board box to show only pins from that board.

== Frequently Asked Questions ==

= What options can I configure? =

You can configure:

* The username the pins are pulled from
* The title that appears above the pins
* Number of pins to show
* Show / Hide the "Follow me on Pinterest" button

= How do I show pins from specific boards? =

To show pins only from a specific board, you can enter the name of the board in the Specific Board input box. The correct format for board names is found in the URL after the username.  For example, the URL for my board called "Cool Stuff" is http://pinterest.com/jowilki/cool-stuff/. I would enter my username *Jowilki* in the username box and *cool-stuff* in the specific board box to show only pins from that board.

== Screenshots ==

1. The widget showing 3 pins and all the options enabled
2. The settings interface is easy to use and straightforward

== Changelog ==

= 1.3.1 =
* Fixes RSS feed for specific boards

= 1.3 =
* Pinterest feed is now fetched with https

= 1.2 =
* Added ability to show pins from specific boards and increased the maximum number of pins you can show.

= 1.1 =
* Fixes text trim for older versions of Wordpress.

= 1.0 =
* Initial version

== Upgrade Notice ==

= 1.3.1 =
Fixes Pinterest url changes to specific boards

= 1.2 =
