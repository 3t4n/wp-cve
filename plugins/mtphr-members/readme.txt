=== Metaphor Members ===
Contributors: metaphorcreations
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5SY9RRTQQ4ABN
Tags: custom post type, members, team, team members, member info, info
Requires at least: 3.2
Tested up to: 4.7
Stable tag: /trunk/
License: GPL2

Creates a custom post type to display info about members of your group or organization.

== Description ==

**This is not a membership plugin.**

Create individual posts to display information about the members or your organization. Includes a shortcode to generate a grid based archive of your members.

The Member post type includes the following fields:

* Basic content editor
* Featured image
* Member title
* *Contact info - Unlimited title/desciption fields
* *Social links - Unlimited list of social site icons/links
* *Social links target
* *Twitter handle - Add the member's Twitter handle

*Use these fields in conjuction with **[Metaphor Widgets](http://wordpress.org/extend/plugins/mtphr-widgets/)** to display this info on each single Member post sidebar.

#### Member Archive Shortcode

**Attributes**
* **posts_per_age** - Set the number of members to display per page. *Default: 9*.
* **columns** - Set the number of columns in the grid. *Default: 3*.
* **excerpt_length** - The length of the post excerpt. This will max out at the set excerpt length of your theme. *Default: 80*.
* **excerpt_more** - The display of the 'more' link of the excerpt. Wrap text in curly brackets to create a permalink to the post. *Default: &hellip*.
* **assets** - Set the order of the archive post assets. Set as a string with assets separated by commas. Available assets are: **thumbnail** **name** **social** **title** **excerpt**. *Default: thumbnail,name,social,title,excerpt*.

**Shortcode Examples**

`[mtphr_members_archive]`

`[mtphr_members_archive posts_per_page="6" columns="4" excerpt_length="200" excerpt_more="{View info}" assets="thumbnail,name,excerpt"]`


== Installation ==

1. Upload `mtphr-members` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where is the documentation =

Documentation is coming soon!

== Screenshots ==

1. Coming soon.

== Changelog ==

= 1.1.9 =
* File organization
* Bug fix in metabox scripts
* STILL WORKING ON THIS - Bug fix for shortcode generator

= 1.1.8 =
* Updated shortcode generator scripts

= 1.1.7 =
* Fixed errors in Widget overrides

= 1.1.6 =
* Fixed add_query_arg() and remove_query_arg() usage

= 1.1.5 =
* Added Italian translation files

= 1.1.4 =
* Made sure page is singular before attempting to filter Metaphor Widget

= 1.1.3 =
* Added plugin to GitHub

= 1.1.2 =
* Removed post type restriction of data display output
* Now requires Metaphor Widgets to be installed & activated for some custom meta
* Admin CSS Updates

= 1.1.1 =
* Modified metabox code for easier manipulation
* Additional actions & filters in metabox code
* Added "mtphr_members_info_meta" filter
* Added "mtphr_members_info_metabox_middle" action
* Added "mtphr_members_widgets_metabox_middle" action

= 1.1.0 =
* Updated menu icon to dash-icon
* Updated settings page code
* Converted some member attributes to require Metaphor Widgets
* Added member attribute display functions
* New shortcodes
* Added shortcode generator capabilities
* Integrated WPML for multi-language sites

= 1.0.9 =
* Added setting for post type "public" attribute
* Added setting for post type "has_archive" attribute

= 1.0.8 =
* Added full content option for archive excerpt (use "excerpt_length = -1")

= 1.0.7 =
* Added contact info option to archive shortcode
* Added "disable permalinks" option to archive shortcode
* Broke down functions for member asset display

= 1.0.6 =
* Added clearfix class to article wrapper
* Modified the archive excerpt code
* Added taxonomy query args to archive permalinks
* Re-ordered social links alphabetically

= 1.0.5 =
* Replaced social icon sprites with custom social font.
* PHP & CSS adjustments made due to implementation of custom font.
* Fixed localization script.

= 1.0.4 =
* Bug fix for auto widget removal.

= 1.0.3 =
* Added member category taxonomy.
* Updated archive shortcode to filter by categories.
* Updated Metaphor Widget Overrides to remove unused widgets.

= 1.0.2 =
* Updated css classes for responsive and non-responsive site.
* Added filter to set responsiveness.

= 1.0.1 =
* Added respond.js to add media queries for older browsers.

= 1.0.0 =
* Initial upload of Metaphor Members.

== Upgrade Notice ==

File organization & bug fix in metabox scripts
