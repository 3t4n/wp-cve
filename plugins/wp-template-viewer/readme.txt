=== WP Template Viewer ===
Contributors: keesiemeijer
Tags: template,theme template,plugin template,template files,included files,file content,files,content
Requires at least: 3.9
Tested up to: 5.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to see the content of all theme templates files that were used to display the current page. 

== Description ==

Ever wanted to take a quick look at a theme template file without opening an editor? Or wondered what template files were used to display the current page? 

This plugin displays all theme template file names in use for the current page in a [toolbar menu](http://codex.wordpress.org/Toolbar). File names are shown in the order they were included. File content is displayed in the footer of your site by clicking a file name.

Only **admins** and **super admins** have access to the toolbar menu and file content.

For more information see the [plugin documentation](https://keesiemeijer.wordpress.com/wp-template-viewer)

Note: Display of file content only works if the current theme follows the recommended practice of calling the [wp_footer()](http://codex.wordpress.org/Function_Reference/wp_footer) template tag (most theme's do).

Filters can be used to change (override) the default settings or behavior of the plugin. 

For example,

* Allow specific users access to the menu and content. (convenient for troubleshooting with others)
* Also include plugin files.
* Show the menu in the footer instead of the toolbar.

For more information see the [filter documentation](https://keesiemeijer.wordpress.com/wp-template-viewer/filters).

**Roadmap**

* Show content with popular syntax highlighter plugins.

== Installation ==
* Unzip the <code>wp-template-viewer.zip</code> folder.
* Upload the <code>wp-template-viewer</code> folder to your <code>/wp-content/plugins</code> directory.
* Activate *wp-template-viewer*.
* That's it, now you are ready to use the plugin.

Note: There's no settings page for this plugin.

== Screenshots ==

1. The WP Template Viewer toolbar menu.
2. File menu in the footer with part of the content of single.php.
3. Content of index.php.

== Changelog ==

= 1.0.0 =
* Add more specific css to display viewer on top.
* Add the current template to the toolbar

= 0.1.2 =
* Added links to select text an hide or show files in the footer.
* Added custom capability 'view_wp_template_viewer' for users.

= 0.1.1 =
* Added a menu item to see the menu in the footer.
* Better color coding for menu items

= 0.1 =
* Initial commit.

== Upgrade Notice ==

= 1.0.0 =
This update will add new styles to have the viewer display on top of the theme.
