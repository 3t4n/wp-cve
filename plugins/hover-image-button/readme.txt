=== Hover Image Button ===
Contributors: igpremuo
Tags: image, hover, on hover, image hover, image on hover, image hover effects, button, image button, special button, hover effect, shortcode, responsive, mobile friendly, columns
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=WPDUGJJARDZLY&lc=ES&item_name=Donative%20to%20Ignacio%20Perez&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 3.5.1
Tested up to: 4.3.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create mobile responsive image buttons with pure CSS3 animated on hover effects.

== Description ==

Create mobile responsive buttons with background images and pure CSS3 animated on hover effects. Include a button title and a subtitle that appears when the user move the mouse hover the button. Recommended to be used as a page or sections links.

Customize the color and the opacity of the background on hover effect, change the default buttons size and include your custom CSS code. 

Add the shortcodes to your page or post to create the buttons.

Button shortcut Options:

* *title*: title of the button, it is a `h3` element.
* *subtitle*: appears when the user move the mouse hover the button.
* *link*: button link.
* *image*: background image link.
* *width*: button width.
* *height*: button height.

= Examples =

Multiple reponsive buttons:

`
[hover-image-row]
    [hover-image-button title="Section 1" subtitle="It's and amazing section" link="http://my-section-link" image="http://my-image-link" ]
    [hover-image-button title="Section 2" subtitle="Enter here for more info" link="http://my-section-link" image="http://my-image-link" ]
    [hover-image-button title="Section 3" subtitle="This is the best section" link="http://my-section-link" image="http://my-image-link" ]
[/hover-image-row]
`

Single button example:

`[hover-image-button title="Section 1" subtitle="It's and amazing section" link="http://my-section-link" image="http://my-image-link" ]`

== Installation ==

1. Upload the `hover-image-button` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place the shortcode `[hover-image-button ...]` in your posts or pages.

== Frequently Asked Questions ==

= Can I only show the image without texts? =

If you dont want to include a title or subtitle just add the shortcode without the title and subtitle tags.

== Screenshots ==

1. Working example

== Changelog ==

= 1.0 =
* Initial relesase of the plugin

= 1.1.0 =
* Added new shortcode to create responsive columns
* Added bootstrap grid system

= 1.1.2 =
* Fixed theme changes after activate plugin

== Upgrade Notice ==

= 1.1.0 =
A new shortcode has been added to easily create responsive buttons columns.

= 1.1.2 =
Theme changes after activate the plugin has been solved.
