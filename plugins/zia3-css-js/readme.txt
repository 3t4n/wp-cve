=== Zia3 CSS JS ===
Contributors: zia3wp
Donate link: http://plugins.zia3.com/donate/
Tags: per page CSS, per page JavaScript, admin, posts, page, style, stylesheet, stylesheets, personal style, html style, javascript, css,, inline, syntax highlighting, pretify
Requires at least: 3.3
Tested up to: 4.5
Stable tag: 1.0
License: GPL3

Define additional CSS and JavaScript (inline and/or by URL)
to be added to any page or post individually.

== Description ==

A WordPress Plugin for easily defining additional CSS and JavaScript (inline and/or by URL)
to be added to any page or post individually.

Two simple steps:

* Enter the directory location/s of your custom CSS and/or JavaScript files on the Zia3 JS
   CSS settings page
* Select the CSS/JS files you want to include on the page/post edit page using checkboxes

The plugin adds a css editor field when you are editing and creating a new post or page.
With the plugin each template page/post will have more possibilities with it's increased
editing power. This allows you to insert arbitrary JavaScript and CSS into any post or
page you like without having to resort to loading it on all pages or modifying your
template's CSS or JS files. This reduces page load time by finely tuning the CSS and
JavaScript files includes in each page/post.

Ever need to include specific CSS or JavaScript (JS) files on a per page/post basis ? Needed
a seperate CSS / JS file for your homepage and not anywhere else in your site ? Wanted
certain pages/posts to use another CSS/JS file and provide funtionality to those pages only
and not to your full site ?

Ever want to tweak the appearance of the WordPress pages/posts, by hiding stuff, moving
stuff around, changing fonts, colors, sizes, etc? Any modification you may want to do
with CSS / JS can easily be done via this plugin. Leverage the power of jQuery on your
website by injecting inline JavaScipt.

Using this plugin you'll easily be able to define additional CSS / JS (inline and/or files
by URL) to any page or post individually. You can define CSS / JS to appear inline in
the page/post head (within style tags), or reference CSS files to be linked (via "link
rel='stylesheet'" tags). The referenced CSS / JS files will appear in page/post head first,
listed in the order defined in the plugin's settings. Then any inline CSS / JS are added to
the page head. Both values can be filtered for advanced customization (see Advanced
section).

== Installation ==

Install automatically through the Plugins, Add New menu in WordPress, or upload the
zia3meta folder to the /wp-content/plugins/ directory.

Activate the plugin through the Plugins menu in WordPress. Look for the Settings link
Zia3-JS-CSS to configure the Options.

== Frequently Asked Questions ==

= Who can insert JavaScript or CSS ? =
By default users with the capability upload_files are allowed to insert JavaScript or CSS
into posts/pages. This seemed to be a logical choice as you need to have a certain level of trust
for users to upload files.

= Can I add CSS I defined via a file, or one that is hosted elsewhere ? =
Yes, via the "Zia3-JS-CSS" input fields on the plugin's settings page.

= Can I limit what pages the CSS / JS gets output on ? =
Yes. This is the main strengh of the plugin.

= Why don't I have  any  checkboxes to include CSS/JS files ? =
You either have no files in the selected CSS/JS directories you set up using the Zia3 CSS JS
options page or the directory doesn't exist or you don't have access to it. The configuration
page displays both the file path on the server and the URl as a link to the configured CSS/JS
directory. You can use the link to check if you can access the directory. Also make sure you
have the correct permissions for your CSS/JS directories and that access isn't also blocked
via .htaccess file configuration.

== Screenshots ==

1. Zia3 CSS JS Meta Boxes
2. Zia3 CSS JS Settings
3. Zia3 CSS JS Installation Upload
4. Zia3 CSS JS Installation Success
5. Zia3 CSS JS Setting Menu Link
6. Zia3 CSS JS Syntax Highligher

== Upgrade Notice ==

Just the usual, deactivate plugin, replace files, activate.

== Changelog ==

0.1 initial release.

0.2 Added CSS and JavaScript code highlighting and auto-complete to respective metaboxes.

