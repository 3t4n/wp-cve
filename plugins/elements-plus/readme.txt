=== Elements Plus! ===
Contributors: cssigniterteam, nvourva, tsiger, anastis, silencerius
Tags: elements plus, elementor, elementor widgets, custom widgets, custom elements, page builder
Requires at least: 6.1
Tested up to: 6.2.2
Requires PHP: 5.4
Stable tag: 2.16.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Elements Plus! provides awesome custom widgets for the Elementor page builder. AudioIgniter, Button, Caldera Forms, Call to Action, Contact Form 7, Content Toggle, Countdown, Dual Button, FlipClock, Gallery, Google Maps, Heading, Hotspots, Icon, Image Accordion, Image Comparison, Image Hover Effects, Inline SVG, Instagram Filters, Label, Preloader, Pricing List, Scheduled visibility, Search, Sticky Videos, Tables, Tilt Effect, Tooltip, WPForms and YouTube Slideshow widgets are available.

== Description ==
Elements Plus! is a plugin for the popular Elementor page builder. It gives the user thirty one extra 'elements' (widgets) to use.

Check out [the demo](https://www.cssigniter.com/preview/elements-plus/) now!

The plugin's documentation can be found [here](https://www.cssigniter.com/docs/elements-plus/).

**Custom Elements**

**AudioIgniter Plus**: An element that allows you to embed [AudioIgniter](https://wordpress.org/plugins/audioigniter/) playlists.

**Button Plus**: This is a custom button widget with two lines of text.

**Caldera Forms Plus**: Embed and style Caldera forms using Elementor.

**Call to Action Plus**: A CTA widget with two lines of text and a button.

**Contact Form 7 Plus**: Embed and style Contact Form 7 forms using Elementor.

**Content Toggle Plus**: Create interactive content with ease.

**Countdown Plus**: A simple but versatile countdown widget.

**Dual Button Plus**: Displays two buttons with separate options for each one.

**FlipClock Plus**: A versatile flipclock timer to add to your projects.

**Gallery Plus**: Gallery widget using the popular JustifiedGallery jQuery library to help you create beautiful justified galleries.

**Google Maps Plus**: Maps widget which allows you to use a curated list of custom styles from snazzymaps.

**Heading Plus**: Create flexible headings with different colors & typography per word.

**Hotspots Plus**: Add hotspots with optional tooltips on any image.

**Icon Plus**: An icon element similar to the one bundled with Elementor, with custom icon sets.

**Image Accordion Plus**: Easily create responsive image accordions.

**Image Comparison Plus**: An element which allows you to highlight the differences between two images.

**Image Hover Effects Plus**: An element which allows you to switch between two images on hover with beautiful effects.

**Inline SVG Plus**: Use SVGs with Elementor.

**Instagram Filters Plus**: Allows you to apply various filters to Elementor's default Image widget.

**Label Plus**: Use the label widget to add a label above any element.

**Preloader Plus**: Use this simple element to show a loading animation while your page loads.

**Pricing List Plus**: Create flexible pricing lists for menus, service pricing and more.

**Scheduled Plus**: Add-on properties to control any element's visibility based on date.

**Search Plus**: AJAX Powered search box.

**Sticky Videos Plus**: Add sticky video functionality to the default Elementor video widget.

**Tables Plus**: Create awesome tables in no time.

**Tilt Effect Plus**: Add tilt effect to any element or widget.

**Tooltip**: Enable this option to add a tooltip to the Heading, Button, Icon, and Icon Box default Elementor widgets.

**WPForms Plus**: Use and style your WPForms with Elementor.

**YouTube Slideshow**: Create a slideshow using your favorite YouTube videos.

**Notice**
The plugin requires the [Elementor](https://wordpress.org/plugins/elementor/) page builder to be active in order to work.

== Installation ==
From within WordPress' dashboard:

1. Go to Plugins -> Add New
2. Search for "Elements Plus"
3. Click "Install"
4. Click "Activate"
5. Navigate to Elementor > Elements Plus! and enable the elements you need. If you want to use the Maps element you will also need a Google Maps API Key.

Manually via FTP:

1. Upload the folder 'elements-plus/' into the '/wp-content/plugins/' directory
2. Activate the plugin through the *Plugins* tab in WordPress
3. Navigate to Elementor > Elements Plus! and enable the elements you need. If you want to use the Maps element you will also need a Google Maps API Key.

== Screenshots ==
1. The available widgets
2. The map widget
3. The justified gallery widget

== Changelog ==

= 2.16.2 =
* Fixed issues caused by namespace change of the SafeSVG plugin.

= 2.16.1 =
* Removed deprecated Instagram Plus! element.

= 2.16 =
* Replaced deprecated (since Elementor 3.5) action 'elementor/widgets/widgets_registered' and method register_widget_type()

= 2.15 =
* Elements now use the Elementor Icons control.
* Fixed issue on YouTube slider Plus due to removal of slick slider from Elementor.
* Preloader Plus now uses Elementor icons.
* Replaced deprecated method _register_controls()
* Fixed fatal errors due to Scheme_Typography and Scheme_Color class removals.

= 2.14.2 =
* Removed migration function which would cause issues in certain installations.

= 2.14.1 =
* Fixed Tilt Plus! conflict with Elementor PRO.
* Google Maps Plus! won't load maps API key URL if element is disabled.
* Fixed PHP notice for missing API key on new installations.
* Fixed icon position styling issues on Label Plus! and Dual Button Plus!

= 2.14.0 =
* Added Pricing List Plus!

= 2.13.1 =
* Added margin control for each heading section on Heading Plus!
* Fixed issue with Button Plus! caused by change in display of the elementor-button-content-wrapper element.

= 2.13.0 =
* Added Heading Plus!

= 2.12.1 =
* Fixed issue with multiple Content Toggle elements on a page.

= 2.12.0 =
* Added Content Toggle Plus!

= 2.11.0 =
* Added Caldera Forms Plus!

= 2.10.2 =
* Search Plus! fixed issue where an "invalid nonce" message would get displayed when full page caching was enabled.

= 2.10.1 =
* Image Accordion Plus! - Fixed issue with linked accordion images on mobile devices.
* Sticky Videos Plus! - Added close button for stuck videos.

= 2.10.0 =
* Added Image Accordion Plus!
* Search Plus! can optionally display post thumbnails.

= 2.9.0 =
* Added Hotspots Plus!
* Added Contact Form 7 Plus.

= 2.8.0 =
* Added Sticky Videos Plus!
* Added input form validation error color option on WPForms Plus.

= 2.7.0 =
* Added WPForms Plus!
* Added notice when saving options in the plugin's settings page.

= 2.6.0 =
* Added Tables Plus!

= 2.5.2 =
* Replaced TweenMax with jstween due to licensing issues.
* Added link option to Image Hover Effects Plus!

= 2.5.1 =
* Added Outdoors Icons to the Icon Plus! element.

= 2.5.0 =
* Added Tilt Effect Plus!

= 2.4.0 =
* Added Inline SVG Plus! element.

= 2.3.0 =
* Added Countdown Plus! element.

= 2.2.1 =
* Fixed issue where fatal errors would be thrown due to missing functions.
* Reinstated missing functions for backwards compatibility, and added deprecation information.

= 2.2.0 =
* Added Search Plus! element.
* Fixed some notices thrown by Tooltip Plus.
* Added version number to enqueued resources.

= 2.1.1 =
* Fixed an issue where the captions would not show in the justified gallery element.
* Added Energy Icons to the Icon Plus! element.

= 2.1.1 =
* Fixed an issue which prevented Tooltip Plus! from working properly in the latest Elementor versions.

= 2.1 =
* Added Instagram Filters Plus!

= 2.0.1 =
* Dual Button Plus! - Fixed button alignment issues on the horizontal layout.

= 2.0.0 =
* Added the Dual Button Plus! element.

= 1.9.3 =
* Added More User Interface Icons to the Icon Plus! element.

= 1.9.2 =
* Added User Interface Icons to the Icon Plus! element.

= 1.9.1 =
* Added Car Icons to the Icon Plus! element.

= 1.9.0 =
* Added the Image Hover Effects Plus! element.

= 1.8.1 =
* Added Christmas icons to the Icon Plus! element.

= 1.8.0 =
* Added the Image Comparison Plus! element.

= 1.7.7 =
* Added Transportation icons to the Icon Plus element.

= 1.7.6 =
* Added Fashion icons to the Icon Plus element.

= 1.7.5 =
* Fixed an issue where Scheduled Plus! would not work for sections and columns.

= 1.7.4 =
* Added Food icons to the Icon Plus element.

= 1.7.3 =
* Added Medical icons to the Icon Plus element.
* Added "For Rent" & "Sold" icons in the Real Estate set of the Icon Plus element.

= 1.7.2 =
* Added Real Estate icons to the Icon Plus element.

= 1.7.1 =
* Added Photography icons to the Icon Plus element.

= 1.7.0 =
* Added FlipClock Plus element.

= 1.6.3 =
* Added Baby icons to the Icon Plus element.

= 1.6.2 =
* Added Sports icons to the Icon Plus element.

= 1.6.1 =
* Added eCommerce icons to the Icon Plus element.

= 1.6.0 =
* Added Icon Plus element.

= 1.5.3 =
* Fixed default map marker icon issue.

= 1.5.2 =
* Added more map styles.
* Users can now paste in a custom SnazzyMaps style.
* Added custom marker option.
* Added map info window.
* Added preloader icon size slider.
* Users can now use custom FontAwesome icon for the preloader.
* Justified gallery maximum row height increased to 600px.

= 1.5.1 =
* Fixed issue where the default WordPress widgets wouldn't be available for use.

= 1.5.0 =
* Added Scheduled visibility.

= 1.4.1 =
* Added tooltip option for the Heading, Button, Icon & Icon Box default Elementor widgets

= 1.4.0 =
* Added new Instagram element
* Unavailable elements due to missing required plugins now display notice in the plugin's settings page

= 1.3.0 =
* Added new Preloader element
* Fixed not working nofollow option on Button Plus!

= 1.2.1 =
* Fixed minor bug with navigation slider height

= 1.2.0 =
* Added new YouTube Slideshow element
* Added lightbox navigation for Justified Gallery element
* Added text padding option on CTA element

= 1.1.1 =
* Added new editor icons for the custom elements.

= 1.1.0 =
* Added AudioIgniter element.

= 1.0.0 =
* Initial release.
