=== LH Dashboard Notes ===
Contributors: shawfactor
Donate link: https://lhero.org/plugins/lh-dashboard-notes/
Tags: note, notes, dashboard notes, wordpress notes, admin note, private note, post it, notification, collaboration, workflow, to do list, note list, multisite
Requires at least: 3.6
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to create and edit notes that appear on the admin dashboard

== Description ==

LH Dashboard Notes allow you to insert noes on your wp-admin dashboard for your users using the post editor interface. If this plugin is network activated the insertion and editing of these dashboard notes is centralised on the main site of your multisite install. Allowing you to set Dashboard notes that are viewed throughout your multisite network.


== Installation ==

1. Upload the `lh-dashboard-notes` folder to the `/wp-content/plugins/` directory
2. Activate or network activate the plugin through the 'Plugins' menu in WordPress
3. If the plugin is activated normally a Dash Notes post type will appear on your website, if it is network activated this post type will only appear on the main site. 


== Frequently Asked Questions ==

= Does the plugin require a multisite installation? =

No it works on both a single install and multisite

= Does this plugin behave differently when networked activated? =

Yes when network activated the Dashboard Notes post editor is only available on the main site of your multisite install. However the notes created on the main site will appear on the dashboard of all site in your multisite install. 


== Changelog ==

**1.00 April 04, 2016** 
* Initial release

**1.01 April 06, 2016** 
* Minor bugfix

**1.03 August 06, 2017** 
* Minor improvements

**1.04 September 17, 2017** 
* Major bug fix props erinnbush

**1.05 October 20, 2017** 
* Capability change and wpautop

**1.06 January 17, 2018** 
* Use Singleton pattern

**1.07 February 10, 2018** 
* added do_shortcode

**1.08 March 04, 2018** 
* Capability bugfix

**1.09 May 08, 2018** 
* Additional capability bugfix