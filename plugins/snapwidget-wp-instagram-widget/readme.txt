=== SnapWidget Social Photo Feed Widget ===
Contributors: snapwidget
Tags: instagram, widget, photos, sidebar, widgets, simple, snapwidget
Requires at least: 4.4
Tested up to: 5.6.2
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SnapWidget Social Photo Feed Widget is an easy way to embed your Instagram photos and videos on your website or blog to display your photos.

== Description ==
SnapWidget Social Photo Feed Widget is an easy way to embed your Instagram photos and videos on your website or blog to display your photos. The widget offers the same functionality and customization available for widgets embedded from [SnapWidget](https://snapwidget.com "Instagram widget"). Supports the Instagram Basic Display and Graph API's.

**Features**

- Simple and easy to use
- Multiple layout options (Grid, Board, Scrolling, Slideshow and Map)
- Secure, supports HTTPS websites
- Refreshes with new photos every 15 minutes (5 minutes for Pro widgets)


**Pro Features**

- Hashtag Widgets
- Widget Analytics
- Shoppable features
- Add your own custom CSS
- Display photos in a lightbox
- Previous / Next buttons to display older content
- Creating widgets for other users


== Installation ==
To install this plugin:

- Upload the `snapwidget-wp-instagram-widget` folder to the `/wp-content/plugins/` directory
- Activate the plugin through the \'Plugins\' menu in WordPress
- Visit [SnapWidget](https://snapwidget.com "Instagram widget") to create your free (or Pro) Instagram widget and configure the layout options
- Copy the widget ID from the URL. For example: `https://snapwidget.com/widgets/557485` the widget ID is `557485`
- Use the shortcode [snapwidget-instagram-widget id=557485] in your page, post or widget to display your Instagram photos. Be sure to replace `557485` with your own widget ID.

Alternatively, you can search for the plugin from your WordPress dashboard and install from there.

**Shortcode Options**

- id - The widget ID configured on [SnapWidget](https://snapwidget.com "Instagram widget") - Example: [snapwidget-instagram-widget id=WIDGET_ID]
- width - The width of your Instagram widget. Any number with px or % - Example: [snapwidget-instagram-widget width=600px]
- height - The height of your Instagram widget. Any number with px or % - Example: [snapwidget-instagram-widget height=600px]
- lightbox - Should the photos be opened in a lightbox. Only available for Pro widgets. - Example: [snapwidget-instagram-widget lightbox=true]


== Screenshots ==
1. Instagram plugin widget editor
2. Instagram widget in the sidebar
3. Instagram widget in the footer

== Changelog ==
= 1.2.0 =
* Test and confirm working with new version of Wordpress 5.6.2

= 1.1.0 =
* Update to support the new Instagram Basic Display API and Instagram Graph API.

= 1.0.4 =
* Update readme and plugin name as per requirement from Facebook.

= 1.0.3 =
* Fix issue with responsive widgets not resizing correctly.

= 1.0 =
* Initial release