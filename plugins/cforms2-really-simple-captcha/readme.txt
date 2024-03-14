=== Really Simple CAPTCHA for cformsII ===
Contributors: bgermann
Donate link: https://www.betterplace.org/projects/11633/donations/new
Tags: captcha, spam, protection, verification, cforms, cforms2, cformsII
Requires at least: 4.4
Tested up to: 5.6
Requires PHP: 5.4
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0

== Description ==

Beginning with version 14.9.1 [cformsII](https://wordpress.org/plugins/cforms2) has pluggable captcha support. This plugin makes use of that by providing an implementation for the [Really Simple CAPTCHA](https://wordpress.org/plugins/really-simple-captcha/).


== Installation ==

If there are missing dependencies, you should be notified on plugin activation.


== Frequently Asked Questions ==

= How can I configure the generated image's parameters? =

On the Dashboard, go to Settings submenu "Really Simple CAPTCHA for cformsII" and find settings for characters length, font size, image size, text color, background color and allowed characters.


== Changelog ==

= 1.3 =
* enhanced: make configuration independent from cformsII (breaks the settings)
* enhanced: correct Text Domain

= 1.2 =
* enhanced: make compatible with cforms version 14.12.2

= 1.1 =
* added:    gettext support

= 1.0 =
* added:    animation on CAPTCHA reset to indicate activity

= 0.3 =
* enhanced: adopt the cformsII 14.11 pluggable CAPTCHA API change
* enhanced: it is possible to show more than one form with CAPTCHA on one site

= 0.2 =
* added:    CAPTCHA reset feature
* bugfix:   check for classes existing to not cause a fatal error

= 0.1 =
* added:    Really Simple CAPTCHA implementation for the cformsII pluggable
            captcha API
