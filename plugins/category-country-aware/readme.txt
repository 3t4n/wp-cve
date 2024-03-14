=== Category Country Aware Wordpress ===
Contributors: wrigs1
Donate link: http://means.us.com/
Tags: Category, Country, Category Widget, RSS Widget, GeoIp, Geo-Location, Advert, Advertisement, Adverts, News Feed, RSS
Requires at least: 3.3
Tested up to: 4.9.6
Requires PHP: 5.4 or later
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make both your post content and sidebar category and/or visitor location relevant.

== Description ==

DUE TO PERSONAL CIRCUMSTANCES I AM NO LONGER ABLE TO DEVELOP OR SUPPORT THIS PLUGIN. IF YOU ARE INTERESTED IN ADOPTING THIS PLUGIN SEE https://developer.wordpress.org/plugins/wordpress-org/take-over-an-existing-plugin/ 

Country Geolocation shortcodes for every need, plus the most flexible Text (and RSS) Widget available. Customize and personalize your posts and widget content for your visitor's locale (country) and/or the current category.


https://www.youtube.com/watch?v=EyT-WQh39E8

&nbsp;

Customize post content to suit the visitor's location (country).

Display category relevant and visitor country relevant adverts/content in posts and widget areas.

Make category and country aware CCA widgets (text/scripts/news feeds) relevant to the post's category and/or visitor's location.

Make your CCA widgets/adverts smart responsive (display in post or sidebar depending on screen width).

&nbsp;

**Features ( [more info in the CCA Plugin Guide](http://wptest.means.us.com/category-country-aware-wordpress/ ))**:

* location aware **Shortcodes** for use in posts and pages (see CCA documentation). You can even auto convert temperatures in your text to the scale used by your visitor.

* YOU control **widget** content based on category(s) and/or visitor's locale(s)

* add multiple widgets to the sidebar, each configurable to display content based on current Category/Country aware content

* select categories by name (not by unfreindly numeric id)

* YOU choose the number of characters to display for RSS News Item excerpts (unlike WP RSS widget)

* option to nofollow news feed links and to open RSS links in new tab (unlike WP RSS widget)

* the same widget can be used as "Text" or RSS widget, depending on category and visitor locale, saving you valuable sidebar space

* can be used with Cookie Notice plugin to limit display of notice to EU visitors only 

* can be used with Menu Item Visibility Control plugin to modify main navigation links to suit visitor's locale

* API for client side (browser) Ajax country/EU geolocation - for use by plugins and coders who need a javascript solution.

* extensions (developers see below) providing additional functionality (see plugin documentation) 

&nbsp;


**Travel Blog EXAMPLE**:

In one **CCA sidebar widget** (you can use more):

* display a hotel booking advert/form by default

* for posts in category "Equipment" display an *Amazon.COM* Travel Gadget advert;
<br>but if the visitor is located in the UK or Ireland display an *Amazon.CO.UK* equivalent;

* category "Transport": display a flight search advertisement

* category "Information": display UK Gov Travel Warnings News Feed (**RSS**) by default;
<br /> but if the visitor is from US or NZ show their Government's equivalent Feed instead

Use "**Ads within posts widget**" to display a gadget advert within posts in category "equipment".

* set widget to only display on small devices i.e. when your sidebar is not visible.

Use **shortcodes to customize your posts** for visitors from different countries e.g.

* [display only="US,GB"]some content[cca_display] will only display the enclosed content, advert etc to visitors from US and UK.

* [cca_display not="GB,AU"]some content[cca_display] will NOT display the enclosed content to visitors from the specified countries.

&nbsp;

**CCA Goodies Extension**
Currently free for a tweet [see this post for more info](http://wptest.means.us.com/cca-goodies-extension/ ).  More flexibility for sidebar widgets . More "Ads in Posts" widgets. 

Enable PHP.

Preview mode + view CCA content as if you are a visitor from specified country.  More Geolocation shortcodes. Give pages "categories" etc etc.

&nbsp;

**GeoIP Country Data:**

This product includes GeoLite2 data created by MaxMind, available from http://www.maxmind.com .

If you use Cloudflare and have "switched on" their GeoLocation option ( see [Cloudflare's instructions](https://support.cloudflare.com/hc/en-us/articles/200168236-What-does-CloudFlare-IP-Geolocation-do- ) ) then it will be used to identify visitor country.  If not, then the Maxmind GeoLite2 Country Database, installed by this plugin, will be used.

Experts: a "hook" is provided to allow you to use other GeoIP systems with this plugin.

&nbsp;

**Developers and coders:** edit functions.php or build your own CCA extension plugin using CCA functions, filters and actions.

Useful functions & methods are detailed in the CCA website's documentation. Actions, Filters & Hooks have yet to be documented; but view the RSS code within this plugin or download the "CCA Goodies Extension" from the CCA website to give you an idea of how to add additional functionality.

If you want to build your own extensions then contact me first as there is a chance that hooks will be renamed or removed.


== Installation ==

Requirements: PHP 5.3 or later. Maxmind Geolocation (if used) requires at least PHP 5.4.

Install in the usual way:

1.Plugins -> Add New -> do a search for "Category Country Aware" to find it -> click "install now"

2.Activate the plugin.

3.Use the *Dashboard->'Settings'->'CCA Goodies'* and the Dashboard->'Appearance'->'Widgets' menus to configure.


== Frequently Asked Questions ==

= Where can I find support/additional documentation =

Support questions should be posted on Wordpress.Org<br />
Additional documentation is provided at http://wptest.means.us.com/category-country-aware-wordpress/


= How does the widget decide which of my category/country entries to use? =

The most specific entry found is used. For less specific entries, categories have higher priority than visitor location. 
So for a Fench visitor viewing a Books post: content defined for "visitor anywhere"|Category "Books"  would win over content defined for "France"|Category "Any".

= Can the widget be made to execute PHP code? =

Short answer:yes [via the CCA goodies extension] (http://wptest.means.us.com/cca-goodies-extension/ ) where you positively opt to allow PHP.

Long answer: any plugin enabling input of arbitrary PHP has increased security risks, however I am aware there is high demand for this feature.
To protect normal non-PHP users, you will have to positively opt to enable PHP. For security opt-in is set by a separate plugin to the widget that executes it.

= Caching plugins/services have problems with dynamic content such as GeoIP. Will the country location part of the CCA plugin work with these? =

Short answer:
<br /> &nbsp; Yes for Cloudflare with their standard caching option
<br /> &nbsp; "Perfectly" for **Comet Cache** and **WP Supercache** when using the country caching plugin extension [for WPSC](https://wordpress.org/plugins/country-caching-extension-for-wp-super-cache/ ), or 
[for Comet/ZC/QC](https://wordpress.org/plugins/country-caching-extension/ ). See CCA documentation.
<br /> &nbsp; W3 Total Cache: DIY solutions (less than perfect).
<br /> &nbsp; Other caching plugins may or may not provide suitable settings.

Note: the plugin also includes an API to allow client side geolocation for use with your own javascript - this should work with any type of page caching.

You can also simply use the CCA widget to display relevant content by category (ignoring visitor country).

full answer:  see CCA documentation


== Screenshots ==

1. Same 3 sidebar widgets i. on "Crime Fiction" category post (US visitor); and ii. on "Travel Guides post" (British visitor)

2. Ad in Post for category Junior fiction (smart responsive option set so ad only displays on small devices)

3. Override theme's widget styles (border, padding etc) without writing any HTML or CSS

4. Adding default content for widget:

5. Same widget, show RSS news feed for category "Travel" when visitor is from USA

6. Set up of "Ads in Posts" for category Junior Fiction:



== Changelog ==

= 1.2.3 =
* Shortcodes can now be used in CCA widgets
* Cookie Noitce - improvements & fix to hide CN revoke button for non-EU visitors
* Geo Country/EU API added for AJAX calls by other plugin or your own javascript

= 1.2.2 =
* Bug fix to prevent issue with caching and Cookie Notice - infinite reload attempts.
* CLEAR CACHE AFTER THIS UPDATE 

= 1.2.1 =
* Compatibility with new GDPR version of Cookie Notice to make EU visitor only 
* [more info] (https://wptest.means.us.com/european-cookie-law-bar/ )

= 1.2.0 =
* On non Cloudflare sites, Maxmind Geolite2 replaces "Legacy" for country GeoIP  (Legacy no longer supported by Maxmind)
* Added [cca_is_EU] [/cca_is_EU] and [cca_not_EU] [/cca_not_EU] shortcodes
* View your CCA Settings "Countries" tab to confirm that "GeoLite2-Country.mmdb" has been succesfully instaled

= 1.0.1 = 
* 2 new boolean functions "cca_visitor_from()" and "cca_visitor_NOT_from()" e.g. "cca_visitor_from('CA,US')" will return TRUE if visitor from US or Canada otherwise FALSE.
* can be used in you own custom code or non-coders with some other plugins e.g. The Menu Items Visibility Control plugin adds a "Visibility" field to Dashboard's Menu Settings.
* If say, you only want a Nav Menu link to display for visitors from France or USA simply insert "cca_visitor_from('US,FR)" in the visibilty field and save.

= 0.9.2 =
* Added option to set the [Cookie Notice plugin](https://wordpress.org/plugins/cookie-notice/ ) to only display its cookie bar to European Union visitors ONLY. 
* Dashboard->Settings->Category Country Goodies->Countries  and check the "set Cookie Notice to only display its cookie bar for these countries" box


== Upgrade Notice ==

= 1.2.3 =
* Shortcodes can now be used in CCA widgets
* Cookie Noitce - improvements & fix to hide CN revoke button for non-EU visitors
* Geo Country/EU API added for AJAX calls by other plugin or your own javascript

== License ==

This program is free software licensed under the terms of the [GNU General Public License version 2](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by the Free Software Foundation.

In particular please note the following:

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.