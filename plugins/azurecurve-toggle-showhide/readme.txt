=== azurecurve Toggle Show/Hide ===
Contributors: azurecurve
Donate link: http://development.azurecurve.co.uk/support-development/
Author URI: http://development.azurecurve.co.uk/
Plugin URI: http://development.azurecurve.co.uk/plugins/toggle-show-hide/
Tags: toggle, show/hide, index, WordPress, ClassicPress
Requires at least: 3.3
Tested up to: 6.0.99
Stable tag: 2.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Toggle to show/hide content (allows custom title).

== Description ==
Toggle to show/hide content (allows custom title).

[toggle]content[/toggle] to use toggle in basic format.

Set a title by using the title parameter: [toggle title='Click to show/hide spoiler']content[/toggle]

Override settings width using width parameter: [toggle width='75%']content[/toggle]

Set toggle to default open using the expand parameter: [toggle expand=1]content[/toggle]

Override CSS border using the border parameter: [toggle border='none']content[/toggle] or [toggle border='1px dashed #FF0000']content[/toggle]

Override settings title colour using the title_color parameter: [toggle title_color='#000']content[/toggle]

Override settings title font family using the title_font parameter: [toggle title_font='Arial, Calibri']content[/toggle]

Override settings title font size using the title_font_size parameter: [toggle title_font_size='14px']content[/toggle]

Override settings title font weight using the title_font_weight parameter: [toggle title_font_weight=600]content[/toggle]

Override settings title background colour using the bgtitle parameter: [toggle bgtitle='#007FFF']content[/toggle]

Override settings text colour using the text_color parameter: [toggle bgtext='#000']content[/toggle]

Override settings text background colour using the bgtext parameter: [toggle bgtext='#000']content[/toggle]

Override settings text font family using the title_font parameter: [toggle text_font='Arial, Calibri']content[/toggle]

Override settings text font size using the title_font_size parameter: [toggle text_font_size='14px']content[/toggle]

Override settings text font weight using the title_font_weight parameter: [toggle text_font_weight=600]content[/toggle]

Override settings disable title images using disable_image=1 or disable_image=0

Shortcodes can now be used inside the content or title of the toggle (tested with Contact Form 7 and azurecurve BBCode).

Select toggle image in options or network options; allows different sites in a network to use different images. Add extra images by dropping them into the plugins /images folder

This plugin supports language translations. If you want to translate this plugin please sent the .po and .mo files to wordpress.translations@azurecurve.co.uk for inclusion in the next version (full credit will be given). The .pot fie is in the languages folder of the plugin and can also be downloaded from the plugin page on http://wordpress.azurecurve.co.uk.

== Installation ==
To install the plugin copy the <em>azurcurve-toggle-showhide</em> folder into your plug-in directory and activate it.

Add extra images for the toggle by placing them in the /images folder.

== Changelog ==
Changes and feature additions for the Toggle Show/Hide plugin:
= 2.1.3 =
* Fix undefined array_key and unassigned variable errors.
= 2.1.2 =
* Move menu to includes folder for easier maintenance
= 2.1.1 =
* Fix undefined constant error in PHP 7.2
= 2.1.0 =
* Allow title tag of toggle to be changed; will default to h3 (as per previous functionality)
= 2.0.0 =
* Add azurecurve menu
= 1.6.2 =
* Fix bug causing title_font_weight error
= 1.6.1 =
* Fix bug causing undefined image indexes
= 1.6.0 =
* Add Image Open and Close to settings to allow image to easily be changed
= 1.5.2 =
* Fix bug with image index
= 1.5.1 =
* Fix bug with multi-language
= 1.5.0 =
* Added new option and override for: width.
= 1.4.2 =
* Fix bug with default title not displaying from settings
= 1.4.1 =
* Fix bug in toggle where background image was repeated
= 1.4.0 =
* Added new options and overrides for: title font family/size/weight
* Added new options and overrides for: text font family/size/weight
* Added new options and overrides for: disable title images.
= 1.3.0 =
* Added colour overrides for text (content) part of the toggle
= 1.2.0 =
* Added background colour overrides for both the title and text parts of the toggle
= 1.1.0 =
* Added options page to allow title, title color and border defaults to be set at either site or network level
* Allowing Shortcodes within toggle shortcode no longer the default; can be enabled via the Settings page
* Localisation available by translating the pot file
= 1.0.6 =
* Add ability to use shortcodes within toggle tags for both title and content
= 1.0.5 =
* Fix bug with expand_active variable
= 1.0.4 =
* Add parameter to override border
* Fix bug with expand parameter not working
* CSS tidy up
= 1.0.3 =
* Change height in style.css to em from px
= 1.0.2 =
* Add expand parameter
= 1.0.1 =
* WordPress 4 Compatible
= 1.0.0 =
* First version

== Frequently Asked Questions ==
= Is this plugin compatible with both WordPress and ClassicPress? =
* Yes, this plugin will work with both.