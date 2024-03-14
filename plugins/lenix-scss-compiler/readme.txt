=== Lenix scss compiler ===
Contributors: pshitik, yonifre
Tags: sass, css, scss, compiler,local compiler
Requires at least: 3.8
Tested up to: 5.9
Stable tag: 1.0.0
License: GPLv2‬‬
License URI: https://www.gnu.org/licenses/gpl-2.0.html‬‬

An excellent way to write Scss in wordpress

== Description ==
A useful plugin for developers writing SCSS.
The plugin allows you to write SCSS directly on the server (via FTP) without the need for a local compiler.

How It Works?

Choose a source folder for SCSS and a target folder for CSS.
Write the SCSS code in the file on the source folder, and it automatically creates a CSS file in the target folder.

What's included?

- Option for multiple source and destination folders.
- Allows you to set a folder in the entire wp-content space.
- Performance - only when one of the source files change - it re-compiling itself.
- After development  you can turn off / delete the plugin without fear, and all the files are stay where they were.
- Allows you to develop a theme and plugin at the same time.


Example:

source: themes/your-theme/assets/scss/style.scss
<pre>
body {
	color: black;
	.main {
		background: red;
	}
}
</pre>
target: themes/your-theme/assets/css/style.css
<pre>
body {
	color: black;
}

body .main {
	background: red;
}
</pre>
--- pay attention!
If the file already exists in the destination folder - it will be overwritten by the SCSS file




== Installation ==
‪‪1. Download the link.‬‬
‪‪2. Upload the zip file via the Plugin upload.‬‬
‪‪3. Activate the plugin.‬‬
‪‪4. Edit in the settings page.

== Frequently Asked Questions ==

= Can I use in main theme and child theme together =
Yes, you can add unlimited locations even in a plugin.

== Changelog ==

= 1.2 =
* Update scssphp library 

= 1.1.1 =
* Improved UI

= 1.1.0 =
* Fix: Reduce resource consumption
* Tweak! Adding disable compiler button
* Tweak! Adding "Compile Now" button

= 1.0.0 =
* Initial Public Beta Release