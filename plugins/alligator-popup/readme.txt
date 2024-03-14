=== Alligator Popup ===
Contributors: numeeja
Donate link: https://cubecolour.co.uk/wp
Tags: popup, popups, popup window, jQuery, shortcode, simple, popup link, message, popup message,
Requires at least: 4.9
Tested up to: 6.4
Stable tag: 2.0.0
License: GPL

Add popups to your site. Add links to pages/posts via a shortcode which will be opened in a popup browser window.

== Description ==

This plugin allows you to enter a shortcode to add links to pages/posts which will be opened in a popup window. The only options in Alligator popup are entered in the shortcode, so there is no admin page for this plugin.

#### Shortcode:
Add a popup shortcode where you would like a link to appear within your post or page text. The shortcode has parameters for url, height and width and should be in the format:

`[popup url="https://cubecolour.co.uk/wp" height="300" width="300" scrollbars="yes" alt="popup"]Link Text[/popup]`

Include your own Link Text and values for the url the width & height of the popup, and the alt text fot the link.

If no values are entered for the alt text and the height and width, defaults of 400px are used for the width & height of the popup window.

Scrollbars are enabled by default and will show if the scrollbars parameter is not added to the shortcode. If you do not want scrollbars on your popup window, add the scrollbars parameter with the value "no" to the shortcode: `scrollbars="no"`

If no value is entered for the alt text, an empty alt tag will be used in the link.

#### HTML Link:
Instead of using the shortcode you can include your link in the format:
`
<a href="https://cubecolour.co.uk/wp" class="popup" data-height="300" data-width="300" data-scrollbars="0" alt="my link text">Link Text</a>
`

This might be useful in a text widget, or you can build the link in a template file of your theme.

#### Note:
If you are using any other plugin (or a theme) that uses a shortcode with the name 'popup', you will not be able to use this plugin. This is not because of any shortcoming in this plugin, but because shortcodes such as those to create popup links should always be implemented in a plugin not a theme.

On mobile devices such as iPads which don't use browser windows, the link will open in a new tab.

This plugin was written in response to a post by a WordPress.org forum user who promised to wrestle an alligator if his problem with creating popups was solved.


== Installation ==

1. Upload the plugin folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add a popup link to a page by including the shortcode to your content in the format:
`[popup url="http://cubecolour.co.uk/wp" height="300" width="300" scrollbars="0"]Link Text[/popup]`

== Frequently Asked Questions ==

= Where is the admin page? =

It doesn't have any options that need an admin page, so there isn't one.

= What is the syntax of the shortcode? =

`[popup url="http://cubecolour.co.uk/wp" height="300" width="300" scrollbars="0"]Link Text[/popup]`
The values for height and width must be positive integers and the value for scrollbars must be either 1 (to show scrollbars) or 0 (to not show scrollbars)

= Are the shortcode parameters all necessary? =

The url is a mandatory parameter but height, widths and scrollbars are optional parameters, Default values will be used for any not present.

= What is the syntax of a link if I'm not using the shortcode? =

`<a href="http://cubecolour.co.uk/wp" class="popup" data-height="300" data-width="300" data-scrollbars="0">Link Text</a>`

= Why doesn't it work? =

The plugin does work on the sites it has been tested on. If it is not working for you, you may have done something wrong or maybe your theme is not built to WordPress standards. Feel free to ask for help on the [Alligator Popup plugin support page on WordPress.org](http://wordpress.org/support/plugin/alligator-popup "Alligator Popup plugin support page on WordPress.org").

= What levels of support are available? =

I offer free forum support for free cubecolour plugins where all communication takes place on the WordPress.org forums and a link is provided to the page on your site where I can see the issue without needing a password. Non-free support via email is available if the conditions of obtaining free support on the public forum are not compatible with the level of support required. This paid email support can be requested at: [cubecolour.co.uk/premium-support](http://cubecolour.co.uk/premium-support "cubecolour.co.uk/premium-support")

= I am using the plugin and love it, how can I show my appreciation? =

You can donate via [my donation page](http://cubecolour.co.uk/wp/ "cubecolour donation page"). If you find the plugin useful I would also appreciate a glowing five star review on the [plugin review page](http://wordpress.org/support/view/plugin-reviews/alligator-popup "alligator popup plugin review page")

= How do I wrestle an alligator? =

This page has some handy tips:
http://www.artofmanliness.com/2010/10/19/how-to-wrestle-an-alligator/

== Screenshots ==

1. A popup

== Changelog ==

= 2.0.0 =
* Removed jQuery as dependency

= 1.2.1 =
* Removed function to get plugin version

= 1.1.3 =
* added alt parameter to shortcode for alt tag support in popup link
* reduced the size of the plugin page icons

= 1.1.2 =
* added 'resizable=yes' to enable maximise button on popped up window in IE

= 1.1.1 =
* Undefined variable fixed in plugin page links

= 1.1.0 =
* Dynamic Version number added to script enqueue
* Plugin Page Links
* Added scrollbars option to shortcode

= 1.0.1 =
* Small improvements to documentation

= 1.0.0 =
* Initial Version

== Upgrade Notice ==

= 2.0.0 =
* Removed jQuery as dependency

= 1.2.1 =
* Removed function to get plugin version

= 1.1.3 =
* added alt parameter to shortcode for alt tag support in popup link

= 1.1.2 =
* Maximise button on popped up window in IE is no longer greyed out

= 1.1.1 =
* Undefined variable fixed in plugin page links

= 1.1.0 =
* Small improvements including addition of arg to control whether scrollbars are shown on the popup

= 1.0.1 =
* Small improvements to documentation

= 1.0.0 =
* Initial Version
