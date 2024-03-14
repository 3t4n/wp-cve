=== Maestro Connector ===
Contributors: bluehost, earnjam, dryanpress, wpscholar
Tags: security, authentication, sso, site-management
Requires at least: 5.7
Tested up to: 6.1
Stable tag: 1.2.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Give trusted web professionals admin access to your WordPress account. Revoke anytime.

== Description ==

As a web professional, managing multiple clients and sites can become cumbersome as your business grows. Keeping track of usernames, passwords, themes, plugins, patches, and updates across a fleet of websites often requires a variety of software, tools, and dashboards. So many moving parts can quickly become time-consuming and deplete you of your efficiency and take precious time away from helping you expand your business.

Bluehost’s Maestro platform is designed to help the modern Web Pro organize their web development business on a single dashboard, so that they can focus on their clients and growing their business, without the administrative overheads.

With the Maestro account, you can organize your clients and their WordPress sites onto a single dashboard, and get one-click access to the WP Admin of all the sites you manage. You no longer need to log in separately into each of your clients sites - a secure one-click login to WP Admin allows you to quickly access multiple sites from a central hub, making it easier to track, develop, design and update.

Your Maestro account is free, and is separate from any existing account you might have with Bluehost.

== Installation ==

1. Install the plugin by clicking `Add New` on the Plugins screen in your WordPress admin, then searching for "Maestro".
1. Activate the plugin.
1. Enter the secret key for the Maestro platform provided to you by your Web Pro.

== Frequently Asked Questions ==

= Do I have to host my site with Bluehost to use Maestro? =

No! In fact, that is what this plugin is for. It allows you to connect your sites hosted anywhere to the Bluehost Maestro platform.

= What access am I giving to the Web Pro through this plugin? =

You will be giving your Web Pro Administrator privileges to your website. This means the Web Pro will have complete control over your WordPress Admin area including but not limited to themes, plugins, content, user list and user roles.

= How do I revoke access for a Web Pro after giving them access? =

You can always do this by going to the ‘Users’ section from the left hand navigation menu. Click on ‘All Users’. You will see a list of users who have access to your WordPress Admin. The Web Pros to whom you have granted Administrator access via the Maestro Connector plugin will appear with the Bluehost Maestro tag under the Maestro column. Hover over the tag to see the “Revoke Access” button. Clicking on this will demote the Web Pro to a Subscriber to your WordPress Admin and disconnect from the Maestro platform. The Web Pro will no longer be able to access your WordPress admin dashboard.

== Screenshots ==


== Changelog ==

= 1.2.0 =
* Move the maestro functionalities to wp-module-maestro
* Add theme management APIs
* Add plugin management APIs
* Add wordpress management APIs in conjunction with bluehost plugin for managing auto-updates etc.
* Add a WP-CLI command to associate a web pro with the association key.

= 1.1.1 =
* Add support for a bounce parameter on SSO logins
* Fix bug preventing reconnecting after revoking

= 1.0.2 =
* Fix bug preventing approval of Web Pro connections

= 1.0.1 =
* Fix bug in response for DELETE REST API endpoint

= 1.0 =
* Intial version
