=== Plugin Name ===
Plugin Name: EU Cookie Law Complience
Plugin URI:  http://timtrott.co.uk/europe-cookie-law-plugin/
Version: 2.05
Author: Tim Trott
Tags: cookie law, cookies, eu law
Requires at least: 2.7.0
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a message to the top of the page stating that cookies are used and if they continue viewing the site then that counts as implied consent.

== Description ==

The Cookie Law is a new piece of privacy legislation from Europe that requires websites to obtain consent from visitors to store or retrieve any information on a computer or any other web connected device, like a smartphone or tablet.

It has been designed to protect online privacy, by making consumers aware of how information about them is collected by websites, and enable them to choose whether or not they want to allow it to take place.

It started as an EU Directive that was adopted by all EU countries on May 26th 2011. At the same time the UK updated its Privacy and Electronic Communications Regulations, which brought the EU Directive it into UK law. 

Each EU member state has done or is doing the same thing. Although they all have their own approach and interpretation, the basic requirements of the directive remain the same.

This plug-in will create a small banner at the top of the guest's browser that greets them with the message:

`We use cookies to ensure that we give you the best experience on our website. If you continue without changing your settings, we'll assume that you are happy to receive all cookies from this website. If you would like to change your preferences you may do so by following the instructions <a href="http://www.aboutcookies.org/Default.aspx?page=1">here</a>`

You can change this text from within the plug-in settings page, and as of version 2.0 you can change the visual style, either by selecting a preset theme or by creating your own. You can also float the message over your website header, or have it push the header further down the page so that it is not obstructed.

This plugin relies on implied cookie consent and you should ensure that this method is correct for your website. If you are relying on implied consent you need to be satisfied that your users understand that their actions will result in cookies being set. Without this understanding you do not have their informed consent. - http://www.ico.gov.uk/news/blog/2012/updated-ico-advice-guidance-e-privacy-directive-eu-cookie-law.aspx

For more information on the Cookie Law please visit http://www.ico.gov.uk/for_organisations/privacy_and_electronic_communications/the_guide/cookies.aspx

DISCLAIMER

Whilst every effort has been taken to ensure that this code remains up to date and conforms to the EU Cookie Law, the authors cannot and will not be held responsible if it is found to be inadequate in any way. The authors are NOT legal experts and nothing contained within constitutes legal advice.

It is your responsibility to ensure that implied consent is suitable for your site before using this code or plugin.

CHANGE LOG

2.05	Updated support for IE11, added option to disable enqueing jQuery, fixed PHP errors, tested up to WP 4.4

2.02	Fixed compatibility with conflicting function names

2.01	Fixed bug hiding admin bar (test code remained)

2.00    Added responsive layout, new configuration manager, improved control over styling and more configuration options.

1.01	Fix for IE8 CSS
	Fixed broken PHP tag in code

1.00	Initial Release

== Installation ==

This section describes how to install the plug-in and get it working.

1. Upload all files to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the text through the 'Settings' menu under 'EU Cookie Message'

On the plugin homepage there are extended instructions for using the code on a non-Wordpress website.

== Frequently Asked Questions ==

= None yet =

Feel free to ask!
