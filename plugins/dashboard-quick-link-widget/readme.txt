=== Dashboard quick links widget ===
Contributors:hemthapa
Donate link: http://www.hemthapa.com
Tags: dashboard, admin, links, link, widget, widgets, administration, user, client, dashboard widget, link widget, shortcut widget
Requires at least: 3.0
Requires PHP: 7.3
Tested up to: 6.4.3
Stable tag: 1.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A lightweight plugin to allows admins to create a admin dashboard widget with frequently accessed links for quick access.

== Description ==
A lightweight plugin to allows admins to create an admin dashboard widget with frequently accessed links for quick access.

I originally developed this plugin after spending hours creating client/user documentation for every WordPress project. Instead of writing step-by-step navigation documentation, I used this plugin to organise all necessary links on the single widget for non-technical users. As a developer, I also use this script myself to organise frequently accessed links for quick access.

= Links format =
Each link should be entered in a separate line in the following format
(the fourth parameter, i.e. font awesome icon class is optional)

**`Link text|Button link|Button text|font-awesome icon class`**


= Examples =
`Post blog|/wp-admin/post-new.php|Post blog`
`Post blog|/wp-admin/post-new.php|Post blog|fa fa-cog`
`Post blog|/wp-admin/post-new.php newtab|Post blog`|fa fa-cog`

If you have any feedback or queries please contact me at [hemthapa.com](http://hemthapa.com?ref=wp_dqlw"hemthapa.com")

== Installation ==

1. Install the plugin from within the Dashboard or upload the directory `dashboard-quick-link-widget` and all of its contents to the `/wp-content/plugins/` directory of your website

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Configure the widget options and links on Settings->Dashboard links widget.

4. Preview and confirm the dashboard widget.

4. That's all, Now users can simply click on the button in the dashboard widget to access the page.

== Frequently Asked Questions ==

= How to format the list of the links?  =

You need to list each link in a separate line in the following format
**`Link text|Button link|Button text|font-awesome icon class`**

(fourth parameter, i.e. Font Awesome icon class is optional)

Example:

`Post new blog|/wp-admin/post-new.php|Create new post|fa fa-cog `

= How to open links in new tab? =
By default, all links open in the same tab, if you want to open all the links on the widget in a new tab tick the 'Open all links in a new tab' box.

However, if you want to open only selected links in a new tab, add the keyword 'newtab' after the link.

Example:

`Post new blog|/wp-admin/post-new.php newtab|Post new blog`

= How to add a divider or additional info in between the links? =
If you need to add any text, divider or any plain or HTML content in between the links, please add the new line with '#' in the front. Any new lines starting with # are rendered as it is in the widget. You can add any HTML tags in this line.

Example:
`#&lt;strong&gt; Section title &lt;/strong&gt;`
`Post new blog|/wp-admin/post-new.php|Post new blog`
`#&lt;hr/&gt;`
`Post new blog|/wp-admin/post-new.php|Post new blog`

= Why Font Awesome icons is not working properly? =
Please make sure correct the Font Awesome version is selected.

== Changelog ==
= 1.6.0 =
*4 Sep 2023*

* Support for custom HTML block is added to add text, space or whatever you like in between the links to categorise and manage the links block
* Support for multiple version of Font Awesome icon is added
* Individual links can now be set to open in a new tab

== Upgrade Notice ==
Version 1.6.0 is backward compatible with previous version.

== Screenshots ==
1. Dashboard widget preview
1. plugin settings dialog
