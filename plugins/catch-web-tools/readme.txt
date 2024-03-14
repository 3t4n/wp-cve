=== Catch Web Tools ===
Contributors: catchplugins, catchthemes, sakinshrestha, pratikshrestha, maheshmaharjan
Donate link: https://catchplugins.com/plugins/catch-web-tools/
Tags: catch-ids, simple, admin, wp-admin, show, ids, post, page, category, media, links, tag, user, widget, seo, search engine optimization, google, alexa, bing, meta keywords, meta description, social icons, github, dribbble, twitter, facebook, wordpress, googleplus, linkedin, pinterest, flickr, vimeo, youtube, tumblr, instagram, codepen, polldaddy, path, css, open graphs, plugin, posts, sidebar, image, images, to-top, arrow, button, icon, link to top, scroll, back to top, scroll to top, scroll top, scroll up, simple scroll to top
Requires at least: 5.7
Tested up to: 6.4
License: GNU General Public License, version 3 (GPLv3)
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

A top-notch modular plugin that can greatly enhance the capabilities of a WordPress website with its powerful features.

== Description ==

Catch Web Tools is a modular plugin that powers up your WordPress site with simple and utilitarian features. It currently offers Webmaster Tool, Open Graph, Custom CSS, Social Icons, Security, Updator and Basic SEO optimization modules with more addition in updates to come.

= Here are some quick reasons why you should check these out! =

Let's begin with how easy the setup process is. It's just a matter of clicks.

One usual assumption people have is like when a plugin offers multiple features and facilities, it loads slow. However, Catch Web Tools offers modular plugins that you activate manually. Which simply means, that if there are 50 different facilities the plugin offers, your site won't take the load of those 50 features unless you activate them. You have the option of activating manually the exact feature's you need and not unnecessarily overload your site.

Catch Web Tools is available for free downloads at this point. Which means, you will get a lot of advanced features that would make your site interesting, efficient and professional - for free!

Catch Web Tools use clean coding that follows WordPress's standard guideline. Which means, zero hassles and perfect compatibility with your themes!


= Premium Support =

Catch Plugins team does not provide support for the Catch Web Tools plugin on the WordPress.org forums. Support is provided at [Catch Web Tools Support Forum](https://catchplugins.com/support-forum/forum/catch-web-tools/)

= Translation =

Catch Web Tools plugin is translation ready.
Added Translation for Brazilian Portuguese by Valdir Trombini
Added Translation for Serbo-Croatian by Andrijana Nikolic

== Installation ==

You can download and install Catch Web Tools plugin using the built in WordPress plugin installer. If you download Catch Web Tools manually, make sure it is uploaded to "/wp-content/plugins/catch-web-tool/".

Activate Catch Web Tools in the "Plugins" admin panel using the "Activate" link.

You'll then see Catch Web Tools dashboard from which you can enable or disable the modules as per your need.

= Webmaster Tools =

Webmaster Tools is a very popular solution for your website and it is highly recommended by webmasters. It will help you with the Search Engine Ranking Optimization of your website.

Click on the Activate button in the Catch Web Tools Dashboard or Check the Enable Webmaster module to enable webmaster tools.

Feed Redirect/Custom Feeds
	* This section allows you to add in custom feed URL which will redirect default WordPress Feed. If your custom feed(s) are not handled by Feedblitz or Feedburner, do not use the redirect options.

Header and Footer Scripts Section
	* This section has facilities provided so that any script from Google, Facebook, Google Analytics etc. can be placed, which will load on Header or Footer.

Site Verification Section
	* This section allows addition of verification ID from Google, Bing and Alexa here to validate site.

= Custom CSS =

Allows addition of Custom CSS in the head section of the site. If CSS is entered and saved, it will show up in the frontend head section. You can leave it blank if it is not needed.


= Catch IDS =

Click on the Activate button in the Catch Web Tools Dashboard.

Once the module is enabled, it will show the Post ID, Page ID, Media ID, Links ID, Category ID, Tag ID and User ID in the Admin Section Table.

= Social Icons =

Social Icons uses icons from genericons v. 3.4.1  [genericons.com](http://genericons.com).

Click on the Activate button in the Catch Web Tools Dashboard or Check the Enable Social Icons module to enable Social Icons provided by Catch Web Tools.

Once the module is enabled and fields are entered, social icons can be shown via three ways, shortcodes, widgets and templates.

Shortcodes
	* The shortcode [catchthemes_social_icons] (in the Post/Page content) will enable Social Icons into the Page/Post.

Widgets
	* Drag and drop Catch Web Tools' Social Icons Widget to any Sidebar for results.

WordPress Templates
	* If Catch Web Tools Social Icons is required in php template, the following code can be used:

	<?php
	if ( function_exists( 'catchwebtools_social_icons' ) )
		catchwebtools_social_icons();
	?>

	OR

	<?php
		echo do_shortcode( '[catchthemes_social_icons]' );
	?>

= OpenGraph ( Social Integration ) =

SEO and Social Media are heavily intertwined, that is why this plugin also comes with a Facebook OpenGraph implementation. Custom Meta boxes can be used to add OpenGraph tags for specific pages or posts. This section adds Open Graph meta data to site’s section.

Click on the Activate button in the Catch Web Tools Dashboard or Check the "Enable OpenGraph Module" to enable social integration provided my Catch Web Tools.

Homepage Setting Section
	* This section includes OpenGraph Settings for your home page.

Default Setting Section
	* This section includes a default image field which is used if the post/page being shared does not contain any images.

Custom Settings Section ( Only For Advanced Users )
	* This setting is only recommended for advanced users who understand detailed workings of OpenGraph tool.
	* This field is for any other type of OpenGraph tag that is not fulfilled by Catch Web Tools OpenGraph Basic Settings. E.g.:<meta property="og:audio" content="http://example.com/sound.mp3" />
	* OpenGraph tags for specific pages or posts, can be added via Catch Web Tools Custom Meta Box which shows up in pages’ and posts’ add/edit sections once this section is enabled.

= SEO (BETA Release) =

SEO is in beta version. SEO can be used to add SEO meta tags to Homepage, specific pages or posts and Categories pages. This section adds SEO meta data to site's <head> section.

Click on Activate button in the Catch Web Tools Dashboard or Check the "Enable SEO Module" to enable SEO provided by Catch Web Tools.

SEO Homepage Settings
	* This section includes SEO settings for Homepage

Catch Web Tools: SEO Settings
	* SEO for specific pages or posts, can be added via Catch Web Tools Custom Meta Box which shows up in pages’ and posts’ add/edit sections once this section is enabled.
	* Once enabled the settings will also show up in the categories add and edit page, below the main settings section.

= Catch Updater =

Click on the Activate button in the Catch Web Tools Dashboard.

Catch Updater is a simple and lightweight WordPress Theme Updater Module, which enables you to update your themes easily using WordPress Admin Panel. Now, you can simple upload your Pro/Premium theme new version zip file from Theme Installer "Appearance => Themes => Add New => Upload" or http://yoursite.com/wp-admin/theme-install.php?upload. You also get an option to backup your existing theme while updating to latest version. No more hassle of deleting the theme and uploading new one.

= To Top  =

To Top Module adds a floating box at the bottom right side (by default) of the page when scrolled down and, when clicked, rolls smoothly to the top. This is convenient when you have a long page, and you want to give your visitors an easy way to get back to the top.

When a page or post has heaps of content, visitors have to scroll down to read those content. As they scroll below, all the navigational links go up. When visitors are done with the reading, they need to scroll up to see what else is there on your website. This can be very tedious. To Top Module adds a button that quickly gets visitors to the top of the page in a matter of milliseconds. Also, the transition is smooth and improves user experience. The other great thing about this module is you don’t have to touch a single code of your template.

### Features
* Displays an icon when user scrolls down the page
* Live Preview via Customizer
* Scrolls the page back to top with animation
* Set icon/image opacity
* Set icon(dashicons) or image as to top button
* For icon, set background color, icon color, icon size and icon shape(from square to circle)
* Set any image you want
* Set image width
* Set the location of the icon
* Show/hide To Top button in admin pages.
* Auto hide
* Hide on small devices


== Screenshots ==

1. Catch Web Tools Dashboard
2. Webmaster Tools
3. Custom CSS
4. Social Icons
5. Open Graph
6. SEO
7. Open Graph and SEO Settings Meta Box
8. To Top


== Changelog ==

= 2.7.4 (Released: November 15, 2023) =
* Compatibility check up to version 6.4

= 2.7.3 (Released: November 19, 2022) =
* Compatibility check up to version 6.1

= 2.7.2 (Released: April 14, 2022) =
* Bug Fixed: Open graph custom tags/keywords not displayed correctly
* Sanitize catchwebtools_opengraph_custom tags

= 2.7.1 (Released: January 21, 2022) =
* Compatibility check up to version 5.9
* Bug Fixed: Nonce and User capabilities check in ajax calls (Reported by wpscan)

= 2.7 (Released: September 18, 2021) =
* Bug Fixed: Security issue on ajax calls

= 2.6.6 (Released: August 17, 2021) =
* Bug Fixed: Undefined variable issue $get_image in frontend/inc/opengraph-tools.php on line 25

= 2.6.5 (Released: August 05, 2021) =
* Compatibility check up to version 5.8

= 2.6.4 (Released: March 07, 2021) =
* Compatibility check up to version 5.7

= 2.6.3 (Released: September 24, 2020) =
* Added: Catch Updater module - Link added in info message

= 2.6.2 (Released: September 15, 2020) =
* Bug Fixed: Social Icons shortcode issue.

= 2.6.1 (Released: August 19, 2020) =
* Bug Fixed: Issue in add new theme page

= 2.6 (Released: August 11, 2020) =
* Added: Disable by default if WordPress version 5.5 or above is installed and show notice
* Compatibility check up to version 5.5

= 2.5 (Released: March 13, 2020) =
* Compatibility check up to version 5.4

= 2.4 (Released: November 20, 2019) =
* Added: Big image size threshold toggle option

= 2.3 (Released: November 12, 2019) =
* Compatibility check up to version 5.3

= 2.2 (Released: August 20, 2019) =
* Added: Option to turn off Catch Themes and Catch Plugins tabs
* Compatibility check up to version 5.2
* Updated: Catch Themes and Catch Plugins tabs displaying code

= 2.1.5 (Released: February 26, 2019) =
* Bug Fixed: Catch Updater module: plugin update issue when plugin name or text-domain does not match plugin directory name

= 2.1.4 (Released: February 25, 2019) =
* Bug Fixed: Catch Updater module: plugin update issue

= 2.1.3 (Released: February 21, 2019) =
* Compatibility check up to version 5.1

= 2.1.2 (Released: January 22, 2019) =
* Removed: equal_height.js as it is no longer used
* Removed: Customizer preview enqueue additional-javascript-preview.js, no file

= 2.1.1 (Released: December 27, 2018) =
* Added: VK social icon in Social Icons module

= 2.1 (Released: December 12, 2018) =
* Added: Catch Themes and Catch Plugins tabs in Add themes and Add plugins page respectively
* Added: Themes by Catch Themes section under Themes panel in customizer
* Bug Fixed: Customizer header/footer script field showing when loading customizer even if the enabled option in unchecked
* Compatibility check up to version 5.0

= 2.0 (Released: May 07, 2018) =
* Added: Auto Updater
* Bug Fixed: undefined index issue Webmaster module
* Compatibility check up to version 4.9.5
* Replaced: div with span (Reported by: jacktester)
* Update: Moved domain from catchthemes.com to catchplugins.com
* Update: Dashboard panel

= 1.9.8 =
* Fixed: Webmaster Tools module Header & Footer script
* Fixed: Set Catch IDs module's status to 'disabled' if Catch IDs plugin in active
* Fixed: Catch Updater module

= 1.9.7 =
* Enhanced Security: Replaced wp_filter_post_kses with wp_kses_post

= 1.9.6 =
* Added: Plugin updater in Catch updater module
* Added: Header & Footer script option in Customizer
* Added: Toggle ID column option to display in selected pages in Catch IDs module
* Fixed: Maintenance mode on frontend in Catch updater module
* Checked: Version compatibility WordPress 4.9.4
* Updated: Disable Catch IDs module if Catch IDs standalone plugin is active

= 1.9.5 =
* Checked: Version compatibility WordPress 4.9
* Updated: Custom CSS to Additional CSS( Core Support )

= 1.9.4 =
* Checked: Version compatibility WordPress 4.8

= 1.9.3 =
* Updated: Make image link protocol-less http:// or https:// to // ( Reported By: pianoworld )
* Updated: Image addition script on admin theme options

= 1.9.2 =
* Fixed: Display on hover without scrolling issue [To Top Module]

= 1.9.1 =
* Fixed: Nonce issue in metabox (Reported by: james)

= 1.9 =
* Checked: Version compatibility WordPress 4.7
* Code Optimization

= 1.8.1 =
* Fixed: Catch IDs: ID column display issue in mobile devices

= 1.8 =
* Checked: Version compatibility WordPress 4.6
* Fixed: Custom CSS and Social Icons CSS
* Fixed: Added catchwebtools_to_top_options in uninstall
* Updated: Catch Updater class constructor name changed to `__construct()`

= 1.7 =
* Added: Social Icons Now have features to show their own social colors on hover and both hover and static
* Fixed: IDs not showing in category and tags page
* Fixed: Enqueue catchwebtools-to-top-public.js and catchwebtools-to-top-public.css only if to-top module is active
* Changed: ID column width size to support upto 8 digit ids
* Changed: http to https in links
* Changed: #to_top_scrollup to #cwt_to_top_scrollup
* Changed: Moved all options (CWT Custom CSS and CWT To Top) for Catch Web Tools to Catch Web Tools Plugin panel in customizer

= 1.6.1 =
* Update: Made the ID column sortable
* Code Optimization for Catch IDs

= 1.6 =
* Added: To Top Module
* Added: Pinterest Site Verification in Webmaster Tools
* Added: Yandex Site Verification in Webmaster Tools
* Added: Security Section In CWT Dashboard
* Added: Catch Updater Module
* Fixed: Undefined index issues
* Optimized: Admin Styles and Scripts delivery
* Optimized: Codes

= 1.5.2 =
* Fixed: SEO Author meta error in singular view

= 1.5.1 =
* Fixed: Custom CSS not working

= 1.5 =
* Added: Social Icons for Email, Skype, Digg, Reddit, Stumbleupon, Pocket, DropBox, Spotify, Foursquare, Twitch TV, Website, Phone, Handset, Cart, Cloud and Link
* Updated: Genericons to 3.4.1
* Updated: Color Picker
* Optimized: Social Icons Content Delivery
* Optimized: Custom CSS Content Delivery

= 1.4.1 =
* Bug Fixed: Replaced text domain from catchwebtools to catch-web-tools

= 1.4 =
* Added: Feed redirect / custom feed options under webmaster tools
* Changed: Support URL

= 1.3 =
* Fixed: Escaped outputs for social icons
* Fixed: Escaped outputs for open graph tools
* Fixed: Escaped outputs for webmasters tools
* Fixed: Skype social icon esc_attr instead of esc_url
* Updated: Genericons
* Checked: Version compatibility WordPress 4.3

= 1.2 =
* Added: Serbo-Croatian translation sr_RS.po and sr_RS.mo
* Fixed: empty SEO title added when deactivated

= 1.1 =
* Fixed: SEO with title tags for version 4.1 or later

= 1.0 =
* Checked: Version compatibility WordPress 4.1
* Fixed: Admin css

= 0.4 =
* Checked: Version compatibility WordPress 3.9.2

= 0.3 =
* Added: Brazilian Portuguese translation pt_BR.po and pt_BR.mo
* Fixed: Textdomain issue with .pot files

= 0.2 =
* Checked: WordPress compatibility up to version 3.9.1

= 0.1 =
* Initial Release
