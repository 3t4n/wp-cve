=== Alphabetic Pagination ===
Contributors: fahadmahmood, sommepro
Tags: pagination, alphabetic, filtering, sorting, posts pagination, chameleon
Requires at least: 3.0
Tested up to: 6.4
Stable tag: 3.2.0
Requires PHP: 7.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Alphabetic pagination is a great plugin to filter your posts/pages and WooCommerce products with alphabets. It is simple to use and easy to understand for customization.


== Description ==

* Author: [Fahad Mahmood](https://www.androidbubbles.com/contact)

* Project URI: <http://androidbubble.com/blog/wordpress/plugins/alphabetic-pagination>

* WooCommerce Shop Page: <http://demo.androidbubble.com/shop>

* WooCommerce Product Category: <http://demo.androidbubble.com/product-category/food-items>

* License: GPL 3. See License below for copyright jots and titles.




Alphabetic Pagination allows you to enable pagination on pages, posts and categories. By default it works with categories to filter posts inside with the selection from the settings page. You can enable it for other sections as well. 
Options are availble to select auto/custom implementation, where to display post type/status selection and DOM position. You can activate another amazing plugin Chameleon to apply styles on pagination. Pagination can be enabled for selected pages/posts/taxonomies with additional shortcodes. Custom implementation, shortcodes and permissions are available in advanced version. Templates are avarialble with another WordPress plugin Chameleon to shape your page with different style. You can display pagination on archives, categories, shops, portfolio, or selected post/page/product/category etc.


Important!

Visit my blog and suggest good features which you wana see in this plugin.

[Blog][Wordpress][]: <http://androidbubble.com/blog/category/website-development/php-frameworks/wordpress>

= Tags =
listing, posts, pages, navigation, categories,taxonomies, custom, arabic, english, russian, korean, hungarian, greek, wpml, translation, CPT-onomies

###Basic Features
* Auto Implementation
* Display Empty Alphabets (ON/OFF)
* Alphabets Grouping
* Hide/Show pagination if only one post available (ON/OFF)
* Specific DOM & CSS selectors
* Language selection
* Styles and Templates (Using WordPress Plugin Chameleon)
* Custom DOM & CSS selectors
* Manage settings page with Android App (Google Play Store)

###Advanced Features
* Custom Implementation
* Shortcodes for Pagination
* Shortcodes for Listing/Results
* Shortcodes for Users List
* jQuery/JS based one page listing and pagination



== Frequently Asked Questions ==

= Does it work with WooCommerce? =

Yes, please watch this tutorial.

[youtube https://youtu.be/cZ8bBwgqhCw]

= Tutorial by Plugin Author =
[youtube http://www.youtube.com/watch?v=N-ewX28pLXs]

= Testimonial by Brryce Axelrad =
[youtube http://www.youtube.com/watch?v=KtjBkSxB1QI]

= Shortcodes =
[youtube http://www.youtube.com/watch?v=q6mUKDinrW8]


== Video Tutorials ==

= Elementor + Shortcodes =
[youtube http://www.youtube.com/watch?v=23DPJOrY2zY]

= Styles =
[youtube http://www.youtube.com/watch?v=I8IAnf8wFpw]

== Installation ==
To use Alphabetic Pagination, you will need:
* 	an installed and configured copy of [WordPress][] (version 3.0 or later).
*	FTP, SFTP or shell access to your web host


= New Installations =

Method-A:

1. Go to your wordpress admin "yoursite.com/wp-admin"
2. Login and then access "yoursite.com/wp-admin/plugin-install.php?tab=upload
3. Upload and activate this plugin
4. Now go to admin menu -> settings -> Alphabetic Pagination
5- Choose your layout and case settings
6- Make sure that its working fine for you and don't forget to give your feedback

Method-B:

1.	Download installation package and extract the files on your computer. 
2.	Create a new directory named same as extracted copy in the `wp-content/plugins`	directory of your WordPress installation. Use an FTP or SFTP client to upload the contents of your plugin archive to the new directory that you just created on your web host.
3.	Log in to the WordPress Dashboard and activate installed plugin.
4.	Once this plugin is activated, a new sub-menu will appear in your Wordpress admin -> settings menu.

[Quick Start]: <http://androidbubble.com/blog/wordpress/plugins/alphabetic-pagination>

== Changelog ==
= 3.2.0 =
* Updated for custom implementation with the category_ids and the taxonomy attribute through the shortcodes. [Thanks to Chandni Rasotra][11/03/2024]
= 3.1.9 =
* Fix: Kadence theme compatibility revised. [Thanks to Mian Noman][28/02/2024]
= 3.1.8 =
* Fix: Multiple post types within one taxonomy a heterogenous case resolved. [Thanks to Mian Noman][27/02/2024]
= 3.1.7 =
* New: Hungarian alphabets updated. [Thanks to Maróthy Szilvia][07/12/2023]
= 3.1.6 =
* New: Letter disabled doesn't work with numeric sign. [Thanks to @ollaluca][22/11/2023]
= 3.1.5 =
* Warning: Array to string conversion fixed. [Thanks to Mattias Baldi][25/06/2023]
= 3.1.4 =
* Updated for WordPress version. [24/05/2023]
= 3.1.3 =
* Fix: PHP notices appeared under functions.php. [Thanks to Hamza Hedi][21/11/2022]
= 3.1.2 =
* Fix: Uncaught TypeError: $.blockUI is not a function. [Thanks to Jean-Louis Gaudefroy][26/10/2022]
= 3.1.1 =
* New: All update_option() items changed to ap_update_option() for better security messures. [Thanks to sommepro][31/08/2022]
= 3.1.0 =
* thumb_list and thumb_strip attributes added to category based shortcodes. [Thanks to Stefan][25/08/2022]
= 3.0.9 =
* wp_add_inline_style and wp_add_inline_script used. [Thanks to the WordPress Plugin Review Team][24/08/2022]
= 3.0.8 =
* Sanitize early, Escape Late, Always Validate. [22/08/2022]
= 3.0.7 =
* Images for taxonomy category added as thumbnail_id for AP Templates in Chameleon. [18/08/2022]
= 3.0.6 =
* New style added "Bilquis Edhi" for Alphabetic Pagination, WordPress Plugin. [19/04/2022]
= 3.0.5 =
* Taxonomy related shortcode improved with the include parameter. [Thanks to Stefan][16/04/2022]
= 3.0.4 =
* Post type page & post under category pages related imrpovements. [Thanks to Mouna nb][11/04/2022]
= 3.0.3 =
* Action hooks provided for jQuery implementation. [Thanks to David McCullough][16/03/2022]
= 3.0.2 =
* Improved version of jQuery based alphabetic pagination with the editor and unexpected separator/divider element. [Thanks to David McCullough][15/03/2022]
= 3.0.1 =
* Updated main_query sort_order to default instead of post_title. [Thanks to TEK Host][15/02/2022]
= 3.0.0 =
* Revised jQuery based shortcode for Arabic language. [Thanks to Bashar][07/01/2022]
= 2.9.9 =
* Taxonomy related shortcodes introduced. [Thanks to Dimitris Habouris] 
= 2.9.8 =
* Default query number added under permissions page as a new feature for FREE version. [Thanks to lucasmang][03/11/2021]
= 2.9.7 =
* Improvement for Elementor and Alphabetic Pagination jQuery based implementation. [Thanks to Jean Louis] 
= 2.9.6 =
* Go Premium links escaped finally. [Thanks to Jean Louis / Créations Messagères] 
= 2.9.5 =
* Tested with WordPress Elementor and Alphabetic Pagination shortcodes. [Thanks to Jean Louis] 
= 2.9.4 =
* Added a new attribute for filter box.
= 2.9.3 =
* Added a new attribute for shortcode as theme="william-wordsworth". [Thanks to Thato Chai]
= 2.9.2 =
* get_children and sorting feature ensured. [Thanks to Tennifer Tynan]
= 2.9.1 =
* Chameleon compatibility revised. [Thanks to Team AndroidBubbles]
= 2.9.0 =
* UI updated. [Thanks to Team AndroidBubbles]
= 2.8.9 =
* Styles updated.
= 2.8.8 =
* Tags updated. [Thanks to Abu Usman]
= 2.8.7 =
* Fatal error: Uncaught Error: Call to undefined function mysql_real_escape_string() - Fixed.
= 2.8.6 =
* A few assets are updated.
= 2.8.5 =
* Not available characters will remain disabled with jQuery as well. [Thanks to Bashar Anjileh]
= 2.8.4 =
* Multiple characters are working jQuery shortcodes as well. [Thanks to Bashar Anjileh]
= 2.8.3 =
* Arabic language added. [Thanks to Bashar Anjileh]
= 2.8.2 =
* Update a broken link and improved UI.
* Android App released. [Thanks to Team AndroidBubbles]
= 2.8.1 =
* Added DOM positions for WooCommerce Shop and Category pages. [Thanks to th0ward & Rais Sufyan]
= 2.8.0 =
* Added another feature so now shortcodes will work with category_ids as well. [Thanks to Jevon Boyce]
= 2.7.9 =
* Added a nice feature so now post_meta_key can replace the default post_permalink. [Thanks to Jevon Boyce]
= 2.7.8 =
* Added another condition for taxonomies if post_type page selected. [Thanks to Enerico Nherziane Benting]
= 2.7.7 =
* Language reviewed. [Thanks to Rais Sufyan]
= 2.7.6 =
* Chameleon compatibility reviewed. [Thanks to senakoga/ushiblog]
= 2.7.5 =
* Added French Language. [Thanks to Rais Sufyan]
= 2.7.4 =
* Added Spanish Language. [Thanks to Rais Sufyan]
= 2.7.3 =
* Added German Language. [Thanks to Abu Usman]
= 2.7.2 =
* Chameleon compatibility refined. [Thanks to Brryce Axelrad]
= 2.7.1 =
* Language selection fixed on settings page. [Thanks to Faridgem & firstboy000]
= 2.7.0 =
* Language related enhancements introduced through languages.php. [Thanks to prokops]
= 2.6.9 =
* array_filter related issue fixed in index.php. [Thanks to prokops]
= 2.6.8 =
* Post Parent parameter added in ap_results shortcode. [Thanks to BOB KORDAS]
= 2.6.7 =
* Fixed a bug in pagination bar related to numeric sign link. [Thanks to mindfulcreative]
= 2.6.6 =
* WooCommerce Shortcodes related WP_Query filters handled conditionally. [Thanks to Ed Christiano]
= 2.6.5 =
* Automatic updates for premium version added.
= 2.6.4 =
* Russian language improved. [Thanks to Влад Юдкин]
= 2.6.3 =
* A complex taxonomy structure related issue resolved. [Thanks to Rebecca Markowitz]
= 2.6.2 =
* A few important improvements. [Thanks to Pameloga]
= 2.6.1 =
* Disable empty alphabets feature added finally. [Thanks to eugeneugene]
= 2.6.0 =
* Improved shortcodes to manage post_type and also tweaked chameleon part.
= 2.5.9 =
* Sanitized input and fixed direct file access issues.
= 2.5.8 =
* Sanitized input and fixed direct file access issues.
= 2.5.7 =
* Sanitized input and fixed direct file access issues.
= 2.5.6 =
* Issue fixed related to pagination was showing on all WooCommerce product categories.
* Another really important tweak found regarding suppress_filters to be false. [Thanks to Matt Lovejoy]
= 2.5.5 =
* Plugin is now translatable.
= 2.5.4 =
* MarketPress - WordPress eCommerce compatibility added. [Thanks to Beepana Pokharel]
= 2.5.3 =
* UTF-8 related JavaScript based fixes.
= 2.5.2 =
* Guidelines provided for shortcodes.
= 2.5.1 =
* Alphabetic Pagination results templates added through Chameleon Plugin.
= 2.5.0 =
* The concept of results templates added through Chameleon Plugin.
* Compatibility added for CPT-onomies. [Thanks to Brryce Axelrad]
= 2.4.9 =
* WPML compatibility added. [Thanks to Aleksandr Daletski]
= 2.4.8 =
* Reset icon related CSS fix. [Thanks to lionas]
* posts_orderby filter refined. [Thanks to mangeshkode87]
= 2.4.7 =
* Main query related bug fixed. [Thanks to valesilve & Josef Rau.]
= 2.4.6 =
* More styles added through WordPress Plugin Chameleon.
= 2.4.5 =
* Content listing added as a shortcode. Thanks to Imre Bernáth.
= 2.4.4 =
* Greek alphabets are added. Thanks to Marcelo Xavier.
= 2.4.3 =
* wp_title was adding "With" on every page, its fixed. Thanks to jmarx75 & emilysparkle.
= 2.4.2 =
* An important fix related to Allowed Pages. Thanks to jmarx75.
= 2.4.1 =
* An important fix related to _utf8 characters. Thanks to Kony Islam.
= 2.4.0 =
* Hungarian alphabets are added. Thanks to Zoltan.
* BINARY UTF8 support included in mysql query. Plugin can distinguish in special characters now.
* Disable empty alphabets bug fixed.
* Multiple main queries can be handled as well.
= 2.3.0 =
* Releasing with auto Pro update.
* Mobile responsiveness.
* Auto display on main category selection.
* View All/Refresh icon visibility.
* Admin panel > settings page layout improvements.
* Category to All and specific, fixed. Thanks to Yuriy Golovkio
* Meta keys can be selected for filtering. Thanks to Duncan Shaw
* Allowed static pages for custom shortcode feature. Thanks to Duncan Shaw
* Pro users can download latest copy by entering reciept number or sale id. Thanks to Kayzee
= 2.2.1 =
* Fixed an important issue. Thanks to Димон.
= 2.2.0 =
* Exclude categories/taxonomies option provided. Thanks to ilnur87.
= 2.1.1 =
* Grouping feature improved.
= 2.1 =
* Alphabets grouping feature added. Thanks to Adam Cullen for suggesting.
= 2.0 =
* All posts will be sorted by post_title ASC when you will use pagination. Thanks to Darrick Kouns.
= 1.9 =
* Single category selection was not working perfectly in heavily widgetized website. The issue is fixed with init hook. Thanks to Francisco.
= 1.8 =
* Korean alphabets are added. Thanks to JAEWOO JUNG.
= 1.7 =
* Now it will reset numeric pagination when you will switch the alphabet.
* Updated the free version according to the pro version. Now empty alphabets will be disabled automatically in pro version.
= 1.6 =
* Updated the free version according to the pro version. Now both are same except premium features.
= 1.5.1 =
* Deprecated function mysql_real_escape_string() is replaced with the recommended function esc_sql().
= 1.5 =
* Shortcodes are available (Premium Feature)
* Users list can be paginated with this plugin (Premium Feature)
* Numeric sign visibility can be managed from settings now.
= 1.4.2 =
* Shortcode is working for the archive pages now.
= 1.4.1 =
* Video tutorial help included
= 1.4.0 =
* Improved settings layout
* Video tutorial added
= 1.3.0 =
* Hide/Show pagination if only one post available? (Added)
* Posts which starts with numeric values, can be sorted now!
= 1.2.8 =
* is_main_query (Notice) in wp_debug - Fixed
= 1.2.7 =
* Specific taxonomy was not working as expected before. It is fixed.
= 1.2.6 =
* z-index problem with twenty fourteen is fixed. (Thanks pho3nyx)
* pagination will not appear on single posts or on irrelevant taxonomies which were conflicted because of same URI's. (Thanks Glenis Pino)
= 1.2.5 =
* An important update according to WordPress upgrades.
= 1.2.4 =
* An important update according to WordPress upgrades.
= 1.2.3 =
* Few warnings are managed.
= 1.2.2 =
* Multiple DOM elements bug fixed.
= 1.2.1 =
* Shortcode implementation introduced.
= 1.2 =
* Now you can use pagination on selective categories instead of all categories.
= 1.1.4 =
* New style added: AP Mahjong.
= 1.1.3 =
* Pagination styles and preview feature is added.
= 1.1.2 =
* CSS related bugs are fixed.
= 1.1.1 =
Few bugs are fixed.
= 1.1 =
* Multilinguage support for alphabets. (Thanks to Andrew from Russia)
* Improved user interface and helping text. (Thanks to Bart De Vuyst from Belgium)
= 1.0.4 =
* Custom DOM selection is improved.
= 1.0.3 =
* Display pagination on other lists too except categories (Fixed).
= 1.0.2 =
* Restrict to taxonomies selection. Now pagination will be displayed only on selective layouts. No need to display pagination on every page as else case.
= 1.0.1 =
* Taxonomy empty array fixed
= 1.0 =
* Taxonomies option is added. Now you can restrict alphabetic pagination to particular views instead of displaying to whole website.
= 0.3 =
* CSS selectors are given in dropdown with an input field to change the pagination position.
= 0.2 =
* Default settings are implemented for uppercase and horizontal layout.

== Upgrade Notice ==
= 3.2.0 =
Updated for custom implementation with the category_ids and the taxonomy attribute through the shortcodes.
= 3.1.9 =
Fix: Kadence theme compatibility revised.
= 3.1.8 =
Fix: Multiple post types within one taxonomy a heterogenous case resolved.
= 3.1.7 =
New: Hungarian alphabets updated.
= 3.1.6 =
New: Letter disabled doesn't work with numeric sign.
= 3.1.5 =
Warning: Array to string conversion fixed.
= 3.1.4 =
Updated for WordPress version.
= 3.1.3 =
Fix: PHP notices appeared under functions.php.
= 3.1.2 =
Fix: Uncaught TypeError: $.blockUI is not a function. 
= 3.1.1 =
New: All update_option() items changed to ap_update_option() for better security messures.
= 3.1.0 =
thumb_list and thumb_strip attributes added to category based shortcodes.
= 3.0.9 =
wp_add_inline_style and wp_add_inline_script used.
= 3.0.8 =
Sanitize early, Escape Late, Always Validate.
= 3.0.7 =
Images for taxonomy category added as thumbnail_id for AP Templates in Chameleon.
= 3.0.6 =
New style added "Bilquis Edhi" for Alphabetic Pagination, WordPress Plugin.
= 3.0.5 =
Taxonomy related shortcode improved with the include parameter.
= 3.0.4 =
Post type page & post under category pages related imrpovements.
= 3.0.3 =
Action hooks provided for jQuery implementation.
= 3.0.2 =
Improved version of jQuery based alphabetic pagination with the editor and unexpected separator/divider element.
= 3.0.1 =
Updated main_query sort_order to default instead of post_title.
= 3.0.0 =
Revised jQuery based shortcode for Arabic language.
= 2.9.9 =
Taxonomy related shortcodes introduced.
= 2.9.8 =
Default query number added under permissions page as a new feature for FREE version.
= 2.9.7 =
Improvement for Elementor and Alphabetic Pagination jQuery based implementation.
= 2.9.6 =
Go Premium links escaped finally.
= 2.9.5 =
Tested with WordPress Elementor and Alphabetic Pagination shortcodes.
= 2.9.4 =
Added a new attribute for filter box.
= 2.9.3 =
Added a new attribute for shortcode as theme="william-wordsworth".
= 2.9.2 =
get_children and sorting feature ensured.
= 2.9.1 =
Chameleon compatibility revised.
= 2.9.0 =
UI updated.
= 2.8.9 =
Styles updated.
= 2.8.8 =
Tags updated.
= 2.8.7 =
Fatal error: Uncaught Error: Call to undefined function mysql_real_escape_string() - Fixed.
= 2.8.6 =
A few assets are updated.
= 2.8.5 =
Not available characters will remain disabled with jQuery as well.
= 2.8.4 =
Multiple characters are working jQuery shortcodes as well.
= 2.8.3 =
Arabic language added.
= 2.8.2 =
Update a broken link and improved UI.
= 2.8.1 =
Added DOM positions for WooCommerce Shop and Category pages. 
= 2.8.0 =
Added another feature so now shortcodes will work with category_ids as well.
= 2.7.9 =
Added a nice feature so now post_meta_key can replace the default post_permalink.
= 2.7.8 =
Added another condition for taxonomies if post_type page selected.
= 2.7.7 =
Language reviewed.
= 2.7.6 =
Chameleon compatibility reviewed.
= 2.7.5 =
Added French Language.
= 2.7.4 =
Added Spanish Language.
= 2.7.3 =
Added German Language.
= 2.7.2 =
Chameleon compatibility refined.
= 2.7.1 =
Language selection fixed on settings page.
= 2.7.0 =
Language related enhancements introduced through languages.php.
= 2.6.9 =
array_filter related issue fixed in index.php.
= 2.6.8 =
Post Parent parameter added in ap_results shortcode.
= 2.6.7 =
Fixed a bug in pagination bar related to numeric sign link.
= 2.6.6 =
WooCommerce Shortcodes related WP_Query filters handled conditionally.
= 2.6.5 =
Automatic updates for premium version added.
= 2.6.4 =
Russian language improved.
= 2.6.3 =
A complex taxonomy structure related issue resolved.
= 2.6.2 =
A few important improvements.
= 2.6.1 =
Disable empty alphabets feature added finally.
= 2.6.0 =
Improved shortcodes to manage post_type and also tweaked chameleon part.
= 2.5.9 =
Sanitized input and fixed direct file access issues.
= 2.5.8 =
Sanitized input and fixed direct file access issues.
= 2.5.7 =
Sanitized input and fixed direct file access issues.
= 2.5.6 =
Issue fixed related to pagination was showing on all WooCommerce product categories
= 2.5.5 =
Plugin is now translatable.
= 2.5.4 =
MarketPress - WordPress eCommerce compatibility added.
= 2.5.3 =
UTF-8 related JavaScript based fixes.
= 2.5.2 =
Guidelines provided for shortcodes.
= 2.5.1 =
Alphabetic Pagination results templates added through Chameleon Plugin.
= 2.5.0 =
Compatibility added for CPT-onomies.
= 2.4.9 =
WPML compatibility added.
= 2.4.7 =
Main query related bug fixed.
= 2.4.6 =
More styles added through WordPress Plugin Chameleon.
= 2.4.5 =
Content listing added as a shortcode.
= 2.4.4 =
Greek alphabets are added.
= 2.4.3 =
wp_title was adding "With" on every page, its fixed.
= 2.4.2 =
An important issue is fixed.
= 2.4.1 =
An important fix related to _utf8 characters
= 2.4.0 =
Don't update until you are sure that you need the upgrades.
= 2.3.0 =
Releasing with auto Pro update.
= 2.2.1 =
Fixed an important issue. Thanks to Димон.
= 2.2.0 =
Exclude categories/taxonomies option provided.
= 2.1.1 =
Grouping feature debugged.
= 2.1 =
A new feature is added.
= 2.0 =
A new feature is added.
= 1.9 =
And important fix, please must update this version.
= 1.8 =
Korean alphabets are added.
= 1.7 =
Now it will reset numeric pagination when you will switch the alphabet. And empty alphabets will be disabled automatically in pro version now.
= 1.6 =
Updated the free version according to the pro version. Now both are same except premium features.
= 1.5.1 =
Deprecated function mysql_real_escape_string() is replaced with the recommended function esc_sql().
= 1.5 =
Numeric sign visibility can be managed from settings now.
= 1.4.2 =
Shortcode is working for the archive pages now.
= 1.4.1 =
Video tutorial help included
= 1.4.0 =
Improved settings layout is here!
= 1.3.0 =
Hide/Show pagination if only one post available? (Added)
Posts which starts with numeric values, can be sorted now!
= 1.2.8 =
is_main_query (Notice) in wp_debug - Fixed
= 1.2.7 =
Important if you are using it on specific taxonomy.
= 1.2.6 =
An important functional fix and a twenty fourteen update.
= 1.2.5 =
An important update according to WordPress upgrades.
= 1.2.4 =
An important update according to WordPress upgrades.
= 1.2.3 =
Few warnings are managed.
= 1.2.2 =
Multiple DOM elements bug fixed.
= 1.2.1 =
Shortcode introduced, don't upgrade if you are happy with existing build.
= 1.2 =
New Feature Added: Now you can use pagination on selective categories instead of all categories.
= 1.1.4 =
AP Mahjong added.
= 1.1.3 =
Enhanced release.
= 1.1.2 =
Must update this release.
= 1.1.1 =
If you WP_DEBUG is true, then must update this release.
= 1.1 =
If you need to translate your alphabets then must check the available translations before updgrade. But for better user interface, you should upgrade it.
= 1.0.4 =
If your pagination is working fine, no need to upgrade. We are in process of improvement, these fixes are as beta version.
= 1.0.3 =
If you want to display pagination on other lists too except categories so you should upgrade to this version.
= 1.0.2 =
Useless display of pagination in every layout is fixed.
= 1.0.1 =
Taxonomy related error fixed
= 1.0 =
It is recommended that you update this version, it will give you better control on alphabetic pagination. If you feel any ambiguity, must inform me.
= 0.3 =
Useful for those guys who want to use other CSS selectors to place pagiantion around. Default selecor was #content.
= 0.2 =
On activation, default settings will be automatically implemented so sudden layout disturbance will be handled.

= Upgrades =
To *upgrade* an existing installation of Alphabetic Pagination to the most recent release:
1.	Download plugin installation package and extract the files on your computer. 
2.	Upload the new PHP files to `wp-content/plugins/`, overwriting any existing plugin files that are there.
3.	Log in to your WordPress administrative interface immediately in order to see whether there are any further tasks that you need to perform to complete the upgrade.
4.	Enjoy your newer and hotter installation of this plugin

[Homepage]: <https://www.androidbubbles.com/extends/wordpress/plugins>


== Screenshots ==
1. Features at a Glance
2. General Settings > Implementation, where to display, post type/status & DOM position.
3. Styling >  Styles, templates and alphabets appearance related options.
4. Additional shortcodes.
5. Allowed pages and custom query numbers
6. Tutorial, open ticket on support forums or contact developer.



== License ==
This is a free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.