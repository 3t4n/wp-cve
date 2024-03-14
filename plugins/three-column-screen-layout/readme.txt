=== Plugin Name ===
Contributors: Chad Hovell
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZH6R884G8K9WQ
Tags: admin, column, columns, edit, editor, layout, post, page, screen, screenoptions, sidebar, third, three, fourth, four, fifth, five
Requires at least: 3.4
Tested up to: 4.7
Stable tag: 4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Three, four and five column screen layouts for the post editor.

== Description ==

Select extra layouts from the post editor Screen Options menu.
There are now 24 variations to choose from!

== Installation ==

1. Install and Activate
1. Edit a post or page
1. Open the Screen Options menu
1. Select a layout

== Frequently Asked Questions ==

= How does it work? =
This plugin splices additional metaboxes into the editor output buffer, using stylesheets for new screen layouts.

= What do the icons mean? =
The light-grey area is the main text editor - this cannot be moved nor can panels be moved into it. The dark grey areas are drop targets that you can drop editor panels into.

= Why are there large gaps in my editor display? =
These are drop targets for the layout you have selected - drop an editor panel into them, or choose a different layout from the screen options menu.

= The extra columns are not appearing, or the layout is not as the icon suggests? =
Layouts will revert to two columns on narrower windows. For example: the "Main with three sidebars" layout needs a browser window at least 1422px wide to display all three sidebars. If your window is magnified it will need to be even wider.

= The plugin is active but there are no screen layout options? =
First make sure you are editing a post or page, then ensure your browser window is wide enough to show at least two columns, as wordpress automatically hides the screen layout options if the window is too narrow.

= There are no screen layout icons, just the default radio controls for two columns? =
This plugin relies on specific editor elements to be present for it splice in the extra columns. Other installed plugins may alter the editor output before its had a chance to locate them, in which case the extra columns will not be displayed.

= I can't drop panels in the extra columns, there is no drop target outline? =
Try dragging the panel to the top of the column, or over the last panel in that column. This is default wordpress behaviour - sometimes you just need to nudge it a little!

= My text editor buttons are hidden or cut off? =
Prior to WP 3.9 the wysiwyg buttons did not wrap to the next line, and will be cut-off on narrower displays. Try a different layout, widen your browser window, or update Wordpress.

= The radio options (from the format panel) become unselected after I drag it to a new column? =
This is default Wordpress behavior. The option has not changed, just reload the page after you've arranged the panels and it will display correctly.

= The panels are overlapping each other? =
If you have another plugin that modifies the menu size or other margins then the panels may overlap, as the stylesheet is configured to work at default sizes.

= What happens if I disable this plugin with editor panels still in the extra columns? =
They will reset to other editor columns. You will need to re-adjust the panels again if you re-activate the plugin.

= The extra columns have stopped working and I am missing the editor panels that were in them? =
There may be a conflict with another plugin you recently installed or updated. Deactivate this plugin and the panels will reset to other editor columns.

= My layouts changed when I upgraded this plugin? =
I inserted new layouts and your previous settings are probably pointing to a different one than before. Time to re-arrange your panels!

= I cannot drop panels under the text editor in the 5 column layout? =
There is no drop target there, just the main text editor.

= Extra features: maybe a sixth column? =
No.

== Screenshots ==

1. 24 screen layout options
2. Three column layout
3. Four column layout
4. Five column layout
4. Columns below the editor

== Changelog ==

= 4.2 =
* Fix for alternate database prefixes
* Minified CSS

= 4.1 =
* Fix for non-English WP installations
* Added RTL language layouts

= 4.0 =
* Updated for WP 4.5.3
* Screen options show icons without radio buttons
* Minimised files for even smaller footprint

= 3.0 =
* Even more layouts, including 4 and 5 column displays
* New methods no longer use the hidden advanced sortables

= 2.0 =
* Now works completely in PHP, requiring zero Javascript
* Additional layouts
* Icons for screen options
* Fixed wysiwyg overflow in older WP versions

= 1.0 =
* Checks WP version before applying
* Fixed problem where you couldn't drag metaboxes onto the sidebar
* Postbox is hidden until new column is ready

= 0.1 =
* First release

== Upgrade Notice ==

= 4.2 =
Fix for alternate database prefixes

= 4.1 =
Fix for non-English WP installations, added RTL language layouts

= 4.0 =
Updated for WP 4.5.3

= 3.0 =
Additional 4 and 5 column layouts

= 2.0 =
Additional layouts, new icons, no Javascript

= 1.0 =
Bugfix

= 0.1 =
First release