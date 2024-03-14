=== WP Dash Message ===
Contributors: alekarsovski, enej, ubcdev, ctlt-dev
Tags: dash, dashboard, widget, dashboard widget, welcome, message, welcome message, dashboard welcome message
Requires at least: 3.1.3
Tested up to: 3.3
Stable tag: 1.1.2

Add a welcome message dashboard widget and remove any WordPress dashboard widgets with this plugin.

== Description ==

The plugin allows the user to write a message for a new widget box that appears at the top of the dashboard.
This can be accomplished on both a site and network level. The site level options are available on the site's
dashboard settings tab called "Dashboard Message", while the network level options are found on the Network
Settings tab on the Network Admin dashboard. Network level messages are displayed after site level messages
in the message box.

The header of the dashboard box/widget is "Welcome, (user's display name goes here)" by default.

1.0.2 UPDATE: The dashboard message now appears on the global dashboard as well. Pushed out network level entry field bug fixes as well.

1.1.0 UPDATE: The plugin now also enables you to REMOVE any dashboard widgets you wish to from any of the dashboards. This means that you can disable network, site, and global dashboard widgets.

Network and global (the dashboard new users without a blog/site typically see) dashboard widgets can be removed through the "Network Settings" page (Network Dashboard -> Settings -> Network Settings).

Site dashboard widgets can be removed from both the "Network Settings" page and from the "Dashboard Message" settings page (Dashboard -> Settings -> Dashboard Message). PLEASE NOTE that if a site dashboard widget is disabled through the network settings, site admins will not be able to re-enable it through the "Dashboard Message" settings page.

1.1.1 UPDATE: Optimization.

1.1.2 UPDATE: Small bug fix.

GOALS FOR FUTURE UPDATES:

Colour options would be great to add for a customized look.

Please NOTE: Some users may not have the Welcome box appear at the top of the Dashboard. This usually happens
if the user has rearranged the boxes on the Dashboard previously.

--This widget was developed with the help of the WordPress Codex, Tutorials, and Help Forums--

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the "wp-dash-message" folder to the "/wp-content/plugins/" directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. To enter text in your widget on a network level go to the "Network Settings" tab on the "Network Admin" dashboard
1. To enter text in your widget on a site level go to the site's dashboard settings tab called "Dashboard Message"

== Frequently Asked Questions ==

= Do I have to have the current background colour on the widget? =

It depends on how much work you are willing to do. The colour is set by CSS code in the php file (which can be edited, but keep in mind that any changes made may be wiped by an update that we may create. We are looking at enabling the user to have a choice of background colours for the next update.

= Why is my dashboard not displaying properly? =

This issue is caused by some HTML syntax errors. Check your site level or network entry and ensure that your HTML code is clean. We are hoping to introduce a filter for the most common syntax errors in our next patch; however, clear and clean text and HTML code is recommended nonetheless.

== Screenshots ==

1. This is how the dashboard widget looks with some random text.
2. The tab for the plugin's site level settings found on the site dashboard.
3. The site level settings entry field and site dashboard widget removal checkboxes.
4. The tab for the plugin's network level settings found on the Network Admin dashboard.
5. The network level settings entry field and dashboard widget removal checkboxes.

== Changelog ==

= 1.1.2 =

Small bug fix.

= 1.1.1 =

Mostly code optimization and elimination of all debug notices.

= 1.1.0 =
The plugin now also enables you to REMOVE any dashboard widgets you wish to from any of the dashboards. This means that you can disable network, site, and global dashboard widgets! Additionally, fixed bug which occurred during dashboard message widget generation in the global dashboard.

= 1.0.2 =
The dashboard message now appears on the global dashboard as well. Network level entry field filter has been fixed along with a number of other errors regarding network level entries (most notably single and double quotes being passed through improperly).

= 1.0.1 =
The message box title has been changed to display the user's display name instead of first and last name as not all users enter their first and last names and this led to some users having a lonesome "Welcome, ".

== Upgrade Notice ==

= 1.1.2 =

Small bug fix.

= 1.1.1 =

Mostly code optimization and elimination of all debug notices.

= 1.1.0 =
The plugin now also enables you to REMOVE any dashboard widgets you wish to from any of the dashboards. This means that you can disable network, site, and global dashboard widgets! Additionally, fixed bug which occurred during dashboard message widget generation in the global dashboard.

= 1.0.2 =
The dashboard message now appears on the global dashboard as well. Network level entry field filter has been fixed along with a number of other errors regarding network level entries (most notably single and double quotes being passed through improperly).

= 1.0.1 =
Dashboard message header will now display the user's display name as users that did not enter a first and last name would only get an empty "Welcome, ".