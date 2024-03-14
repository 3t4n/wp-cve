=== Standout Color Boxes and Buttons ===
Tags: CSS, shortcode, button, color box, color button, rounded, dropshadow
Requires at least: 3.5
Tested up to: 3.9
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin implements colored content boxes and buttons as described in a Studiopress blog post.

== Description ==

This plugin implements colored content boxes and buttons a la <a href="http://www.studiopress.com/design/colored-content-boxes-buttons.htm">this Studiopress blog post</a>. Using a simple shortcode or PHP function you can create colorful buttons and content boxes. CSS is only included on pages with shortcode. Write and include your own CSS to create your own color schemes.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, please go to <a href="http://wordpress.org/plugins/standout-color-boxes-and-buttons/#compatibility">Compatibility</a> and click "Broken" after verifying your WordPress version and the version of the plugin you are using.

Then, create a thread in the <a href="http://wordpress.org/support/plugin/standout-color-boxes-and-buttons">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/standout-color-boxes-and-buttons">Reviews</a> section to report issues or request new features.</strong>

= Features =

- Create unlimited number of handsome context boxes and color buttons
- Works with most browsers, but degrades nicely in older browsers
- CSS3 compliant
- CSS only loads on pages with shortcode or function call
- Use shortcodes inside shortcodes, i.e. [color-box][my_shortcode][/color-box]
- Create your own color schemes
- Custom CSS automatically busts caches when you update it. Change it as often as you want, it will display changes in real-time!

= Shortcode =

To display on any post or page, use this shortcode:

[color-box]Content goes here[/color-box]

or 

[color-button]Content goes here[/color-button]

Make sure you go to the plugin settings page after installing to set default options.

<strong>If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.</strong>

Code sources:
- http://codex.wordpress.org/Shortcode_API
- http://codex.wordpress.org/Function_Reference/wp_enqueue_style
- http://scribu.net/wordpress/conditional-script-loading-revisited.html

== Installation ==

1. Upload plugin through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings &raquo; 'Standout Color Boxes and Buttons' and configure the plugin.
4. Place shortcodes on posts or pages where you want styled content to appear. You can also use shortcodes in sidebar, footer, header, etc.

To remove this plugin, go to the 'Plugins' menu in WordPress, find the plugin in the listing and click "Deactivate". After the page refreshes, find the plugin again in the listing and click "Delete".

== Frequently Asked Questions ==

= How do I use the plugin? =

After installing, there are two new shortcodes:

- [color-box]
- [color-button]

To create color boxes, specify the color and content. Two buttons have been added to your post editor toolbar to make this easier.

`[color-box color="gray"]
This is an example of a Content Box.
[/color-box]`

Color is optional ("blue" is default), but it is recommended that you always specify the color you want instead of relying on the default, in case the default ever changes.

To create color buttons, specify the color, URL and content.

`[color-button color="purple" href="http://www.mysite.com/"]
Follow this link
[/color-button]`

Color is optional ("blue" is default), but it is recommended that you always specify the color you want instead of relying on the default, in case the default ever changes.

`[color-box]
This is an example of a Content Box.
[/color-box]
[color-button href="http://www.mysite.com/"]
Follow this link
[/color-button]`

Shortcodes inside content are allowed, i.e. 
`[my_shortcode]
Here is something I wrapped in [another_shortcode] shortcodes.
[/my_shortcode]`

Rounded corners and drop shadows for boxes and buttons are optional. For backward compatibility, rounded corners are turned on by default. To turn this off, add `rounded="0"` as a parameter in the shortcode. i.e.

`[color-box color="red" rounded="0"]
This is an example of a Content Box without rounded corners.
[/color-box]`

You may also call the shortcode in your PHP code. Example:

`echo do_shortcode('[color-button color="purple" 
href="http://www.google.com/"]Follow this link[/color-button]');`

You can also call the box and button functions from your PHP code. Example:

`if (function_exists('color_box_shortcode')) {
  color_box_shortcode(array('color' => 'purple', 
'rounded' => 0, 
'show' => 1), 
'Here is my content');
}`

`if (function_exists('color_button_shortcode')) {
  color_button_shortcode(array('color' => 'purple', 
'opennewwindow' => true, 
'href' => 'http://www.jimmyscode.com/', 
'show' => 1), 
'Visit my website');
}`

= What are the plugin defaults? =

The plugin arguments and default values may change over time. To get the latest list of arguments and defaults, look at the settings page after installing the plugin. That is where the latest list will always be located. You will see what parameters you can specify and which ones are required.

= What colors are available? =

The currently available colors are

- blue
- gray
- green
- purple
- red
- yellow
- black
- white
- orange
- pink
- bluebell

See the dropdown list on the plugin settings menu for the most updated list.

= How do I create my own color schemes? =

1. When you are entering the shortcode or calling the plugin function from PHP, instead of using one of the built-in color names ("orange", "red" etc), use the class name you want to use. Ex: "bluegreen"

This will be used to build the class name as follows:

- For color buttons, the class will be <strong>scbb-button-<em>whatever you typed</em></strong>
- For boxes, the class will be <strong>scbb-content-box-<em>whatever you typed</em></strong>

In this example, it will be:

`scbb-button-bluegreen
scbb-content-box-bluegreen`

2. Go to the plugin settings page. There is a textarea there where you enter the CSS you want to use.

If you need help writing the CSS, look at the existing CSS file the plugin uses (filename: scbb.css in the /css/ subfolder) as a model for what CSS you need. Then just change the color values accordingly and paste it into the textarea. If you want to do something above and beyond what is already in the CSS, please search the web to find help. Please don't use the plugin support forum to ask for CSS help unless there is an issue with the existing CSS.

The custom CSS stylesheet will be enqueued on pages where custom CSS class names are used.

<strong>Note: you must include the FULL class name (ex: <em>scbb-content-box-mycustomcolor</em>) in the custom CSS textarea. However, when you actually call the class in your shortcode you only use the color name you created in step #1 above (ex: <em>[color-box color="mycustomcolor"]some text here[/color-box]</em>).</strong>

= I added the shortcode to a page but I don't see anything. =

Clear your browser cache and also clear your cache plugin (if any). If you still don't see anything, check your webpage source for the following:

`<!-- Standout Color Boxes and Buttons: plugin is disabled. Check Settings page. -->`

This means the "enabled" checkbox on the settings page is unchecked. For color buttons, you must provide the URL and the hyperlink text. If the "enabled" checkbox is checked, but you do not provide a URL or hyperlink text for a button, you will see nothing.

= I don't see rounded corners. =

Make sure you aren't using `rounded=false` in your shortcode. If you are not, make sure your browser is up to date and check if it supports the `border-radius` CSS attribute. You may have to view the page in another browser. You may also need to refresh your browser and clear your caching plugin. Also, check the plugin settings page to make sure the "rounded corner CSS" checkbox is checked.

= I cleared my cache and still don't see what I want. =

The CSS files include a `?ver` query parameter. This parameter is incremented with every upgrade in order to bust caches. <strong>Make sure none of your plugins or functions are stripping query parameters.</strong> Also, if you are using a CDN, flush it or send an invalidation request for the plugin CSS files so that the edge servers request a new copy of it.

= I cleared my browser cache and my caching plugin but the output still looks wrong. =

Are you using a plugin that minifies CSS? If so, try excluding the plugin CSS file from minification.

= I don't want to use the admin CSS. =

Add this to your functions.php:

`remove_action('admin_head', 'insert_scbb_admin_css');`

= I don't want to use the plugin CSS. =

Add this to your functions.php:

`add_action('wp_enqueue_scripts', 'remove_scbb_style');
function remove_scbb_style() {
  wp_deregister_style('scbb_style');
}`

= I don't want the post editor toolbar button(s). How do I remove it? =

Add this to your functions.php:

`remove_action('admin_enqueue_scripts', 'scbb_ed_buttons');`

= I don't see the plugin toolbar button(s). =

This plugin adds one or more toolbar buttons to the HTML editor. You will not see them on the Visual editor.

The labels on the toolbar buttons are "SC Box" and "SC Button".

= I am using the shortcode but the parameters aren't working. =

On the plugin settings page, go to the "Parameters" tab. There is a list of possible parameters there along with the default values. Make sure you are spelling the parameters correctly.

The Parameters tab also contains sample shortcode and PHP code.

== Screenshots ==

1. Plugin settings page
2. Color buttons
3. Color boxes

== Changelog ==

= 0.7.0 =
- updated .pot file and readme

= 0.6.9 =
- fixed validation issue

= 0.6.8 =
- minor CSS edit

= 0.6.7 =
- code fix
- admin CSS and page updates

= 0.6.6 =
- minor code fix
- updated support tab

= 0.6.5 =
- added jquery dependency for color picker, since it uses jquery
- if the plugin is temporarily disabled (via plugin settings page), skip some code to save some cycles

= 0.6.4 = 
- code fix

= 0.6.3 =
- code optimizations
- use 'url', 'href' or 'link' as the URL parameter name
- plugin settings page is now tabbed

= 0.6.2 =
- fix for wp_kses

= 0.6.1 =
- fix for wp_kses

= 0.6.0 =
- some minor code optimizations
- verified compatibility with 3.9

= 0.5.9 =
- OK, I am going to stop playing with the plugin now. Version check rolled back (again)

= 0.5.8 =
- prepare strings for internationalization
- plugin now requires WP 3.5 and PHP 5.0 and above
- minor code optimization

= 0.5.7 =
- minor plugin settings page update

= 0.5.6 =
- minor bug with parameter table on plugin settings page

= 0.5.5 = 
- Fix for color buttons not working

= 0.5.4 =
- Stopgap fix for preg_match issue

= 0.5.3 =
- minor workflow change when writing custom CSS, see readme.txt
- custom CSS is preserved across updates (I hope)
- now includes color picker
- added submit button to top of plugin settings form

= 0.5.2 =
- All CSS and JS files automatically bust cache
- removed screen_icon() (deprecated)
- updated to WP 3.8.1

= 0.5.1 =
- refactored admin CSS
- added helpful links on plugin settings page and plugins page

= 0.5.0 =
- editor button now outputs required parameters when clicking it
- custom CSS textbox to enter your own CSS styling for custom classes
- code refactored for efficiency
- custom CSS file automatically busts caches whenever it is edited
- minified CSS (somewhat)
- updated FAQ/readme
- added color "bluebell"

= 0.4.4 =
fixed uninstall routine, actually deletes options now

= 0.4.3 =
- updated the plugin settings page list of parameters to indicate whether they are required or not
- updated FAQ section of readme.txt

= 0.4.2 =
some security hardening added

= 0.4.1 =
minor admin code update

= 0.4.0 =
- target="_blank" is deprecated, replaced with javascript fallback

= 0.3.9 =
- minor code refactoring

= 0.3.8 = 
- fixed code mixup with nofollow and target=_blank settings

= 0.3.7 =
- added donate link on admin page
- admin page CSS added
- various admin page tweaks
- minor code refactoring
- added shortcode defaults display on settings page

= 0.3.6 =
- minor code refactoring
- added option to open links in new window (color buttons only)
- if no hyperlink text is passed to button function, plugin does nothing
- css file refactoring

= 0.3.5 =
- updated readme.txt
- another minor admin page update

= 0.3.4 =
- moved quicktag script further down the page
- minor admin page update

= 0.3.3 =
- updated admin messages code
- updated readme

= 0.3.2 =
- added quicktag
- code refactoring
- added admin menu
- added drop shadows by request

= 0.3.1 =
Thanks to http://scribu.net/wordpress/conditional-script-loading-revisited.html, CSS is only included on pages that actually have the shortcode.

= 0.3 =
Added more colors, updated readme, rounded corners are optional (but turned on by default)

= 0.2 =
Fixed incorrect css file path, plugin actually works now

= 0.1 =
Version 0.1 completed September 17, 2012.

== Upgrade Notice ==

= 0.7.0 =
- updated .pot file and readme

= 0.6.9 =
- fixed validation issue

= 0.6.8 =
- minor CSS edit

= 0.6.7 =
- code fix; admin CSS and page updates

= 0.6.6 =
- minor code fix; updated support tab

= 0.6.5 =
- added jquery dependency for color picker; add enabled checks

= 0.6.4 = 
- code fix

= 0.6.3 =
- code optimizations; use 'url', 'href' or 'link' as the URL parameter name; plugin settings page is now tabbed

= 0.6.2 =
- fix for wp_kses

= 0.6.1 =
- fix for wp_kses

= 0.6.0 =
- some minor code optimizations, verified compatibility with 3.9

= 0.5.9 =
- OK, I am going to stop playing with the plugin now. Version check rolled back (again)

= 0.5.8 =
- prepare strings for internationalization, plugin now requires WP 3.5 and PHP 5.0 and above, minor code optimization

= 0.5.7 =
- minor plugin settings page update

= 0.5.6 =
- minor bug with parameter table on plugin settings page

= 0.5.5 = 
- Fix for color buttons not working

= 0.5.4 =
- Stopgap fix for preg_match issue

= 0.5.3 =
- minor workflow change when writing custom CSS; custom CSS is preserved across updates (I hope), now includes color picker; added submit button to top of plugin settings form

= 0.5.2 =
- All CSS and JS files automatically bust cache, 
- removed screen_icon() (deprecated), 
- updated to WP 3.8.1

= 0.5.1 =
- refactored admin CSS
- added helpful links on plugin settings page and plugins page

= 0.5.0 =
- editor button now outputs required parameters when clicking it
- custom CSS textbox to enter your own CSS styling for custom classes
- code refactored for efficiency
- custom CSS file automatically busts caches whenever it is edited
- minified CSS (somewhat)
- updated FAQ/readme
- added color "bluebell"

= 0.4.4 =
fixed uninstall routine, actually deletes options now

= 0.4.3 =
- updated the plugin settings page list of parameters to indicate whether they are required or not
- updated FAQ section of readme.txt

= 0.4.2 =
some security hardening added

= 0.4.1 =
minor admin code update

= 0.4.0 =
- target="_blank" is deprecated, replaced with javascript fallback

= 0.3.9 =
- minor code refactoring

= 0.3.8 = 
- fixed code mixup with nofollow and target=_blank settings

= 0.3.7 =
- added donate link on admin page
- admin page CSS added
- various admin page tweaks
- minor code refactoring
- added shortcode defaults display on settings page

= 0.3.6 =
- minor code refactoring
- added option to open links in new window (color buttons only)
- if no hyperlink text is passed to button function, plugin does nothing
- css file refactoring

= 0.3.5 =
- updated readme.txt
- another minor admin page update

= 0.3.4 =
- moved quicktag script further down the page
- minor admin page update

= 0.3.3 =
- updated admin messages code
- updated readme

= 0.3.2 =
* added quicktags for post editor toolbar
* code refactoring
* added admin menu

= 0.3.1 =
CSS is now conditional

= 0.3 =
This update adds more colors and updates the readme. Rounded corners are now optional.

= 0.2 =
This update fixes an incorrect css file path in version 0.1.