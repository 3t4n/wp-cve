=== Locatoraid Store Locator ===
Plugin URI: https://www.locatoraid.com
Contributors: Plainware
Tags: google maps, store locator, business locations, dealer locator, geocoding, geolocation, dealer locater, zipcode locator, store locater, gmaps, google map plugin, mapping, mapper, shop locator, shop finder, shortcode, location finder, places, widget, stores, plugin, maps, coordinates, latitude, longitude, posts, geo, jquery, shops, page, zip code, zip code search, store finder, address map, address location map, map maker, map creator, mapping software, map tools, mapping tools, locator maps, map of addresses, map multiple locations, wordpress locator, store locator map
License: GPLv2 or later
Stable tag: 3.9.37
Requires at least: 3.3
Tested up to: 6.4
Requires PHP: 5.3

A lightweight, easy to use store locator plugin.

== Description ==

Locatoraid is a lightweight, easy-to-use store locator plugin.

Let your customers find your stores, dealers, hotels, restaurants, ATMs, products, or any other types of locations. Autolocate visitor position to offer your nearest options.

__Start In Minutes__
Just start adding your locations, then the automatic geocoding function finds the coordinates, and your locations are on the map waiting for your customers!

__Custom Map Styles__
Easily configurable map styles to match your website.

__Shortcode Parameters__
A number of [shortcode parameters](https://www.locatoraid.com/shortcode-parameters/) that can be used to configure the front end view, like default search text, search radius, view layout, limit to a state or a country, and more.

__Works Worldwide__
More than 200 countries covered. USA, Australia, Canada, Hong Kong, Italy, Japan, Mexico, Singapore, South Africa, Spain, UK and more. As long as it is on the Google Maps, it will be found.

__REST API__
The [REST API](https://www.locatoraid.com/restful/) lets you query and modify your locations through a number of REST endpoints.

__Mobile Friendly__
Responsive design that works perfectly well for iPhone, Android, Blackberry, Windows as well as for desktops, laptops and tablets.


###Pro Version Features###

__Bulk Upload and Export__
The [Bulk Upload and Export](https://www.locatoraid.com/upload-export/) module lets you import, export and update your locations with a CSV file.

__Custom Fields__
Add up to 20 [Custom Fields](https://www.locatoraid.com/custom-fields/) to store and display additional information about your locations.

__Products__
Assign [Products](https://www.locatoraid.com/products/) to categorize your locations.

__Custom Map Icons__
With the [Custom Map Icons](https://www.locatoraid.com/custom-map-icons/) you can set any custom icon for any of your locations.

[Get the Pro version of Locatoraid store locator plugin!](http://www.locatoraid.com/order/)

== Support ==
Please contact us at [http://www.locatoraid.com/](http://www.locatoraid.com/)
[Follow on Facebook](https://www.facebook.com/Locatoraid-165233107413846/)

Author: Plainware
Author URI: http://www.locatoraid.com

== Installation ==

1. Upload the `locatoraid` folder to the `/wp-content/plugins/` directory on your site.
1. Activate the plugin through the 'Plugins' menu in WordPress admin.
1. Create a [Google API Key](https://www.locatoraid.com/create-google-api-keys/) and set them in Locatoraid -> Configuration -> Google Maps.
1. Add your locations under Locatoraid -> Locations -> Add New.
1. Add the map to a page or a post with this shortcode: [locatoraid]. For more options, please refer to Locatoraid -> Publish.
1. Customize your map view in Locatoraid -> Configuration -> Google Maps.

== Frequently Asked Questions ==

= Why my search result comes up in the wrong country? =

Some location names may exist in several countries, and the Google maps will make its guess on which one you mean. Make use of the `search-bias-country` shortcode parameter, it will adjust the map service to prefer results in the given country.
For example, `[locatoraid search-bias-country="Finland"]`. If you need several countries, separate them by comman and we'll add a drop-down box with the country list to the search form. For example, `[locatoraid search-bias-country="Finland,Sweden,Norway"]`

= How to make the font size in search results bigger or smaller? =

Go to WordPress admin -> Appearance -> Customize, then click Additional CSS, and paste the following code (change the font-size value if needed):
`#locatoraid-map-list-container { font-size: 0.9em; }`

== Screenshots ==

1. Front-end of the plugin.
2. Back-end of the plugin.

== Upgrade Notice ==
The upgrade is simple - upload everything up again to your `/wp-content/plugins/` directory, then go to the Locatoraid menu item in the admin panel. It will automatically start the upgrade process if any needed.

== Changelog ==

= 3.9.37 =
* Bug: the "translate dynamic content" module didn't translate strings that contained spaces.
* Bug: if the "search-bias-country" parameter was used in the shortcode with multiple country options, they were not translated.

= 3.9.36 =
* Bug: it might have produced an Ajax error in the front end if an entered search string could not be geocoded.

= 3.9.35 =
* Added an option to translate dynamically generated content, such as country names, product labels, custom field labels etc.

= 3.9.34 =
* Updated the language template file locatoraid.pot to up-to-date strings used in the plugin.

= 3.9.33 =
* Bug: locations coordinates might be accidentally reset.

= 3.9.32 =
* Minor fixes.

= 3.9.31 =
* Bug: fixed a crutical error that appeared in version 3.9.30 if locations had products/categories in the Pro version with PHP 8.

= 3.9.30 =
* Minor fixes.

= 3.9.29 =
* Adjusted the map-start-zoom shortcode parameter so it also affects the start state of the map with results too, before it affected the empty map only.

= 3.9.28 =
* Bug: minor error notices with PHP 8.2 fixed.
* Added offline zip code database for Netherlands as Google maps might produce incorrect results for postcode search.

= 3.9.27 =
* Bug: "where-product" shortcode parameter was not taking effect since version 3.9.26.

= 3.9.26 =
* Minor fixes

= 3.9.25 =
* If search-bias-country contains multiple options separated by comma then we add a drop-down box with the country list.

= 3.9.24 =
* Bug: Configuration, Products, All Options Checked By Default setting didn't take effect.
* Minor fixes

= 3.9.23 =
* Minor fixes

= 3.9.22 =
* Added an option to turn on all product options by default on a front end page.
* Minor fixes

= 3.9.21 =
* Added Gutenberg blocks for the main map with results display and search form widget.
* Minor fixes

= 3.9.20 =
* BUG: filtering by product/category may be incorrect in the front-end.

= 3.9.19 =
* Code security issues fixed.

= 3.9.18 =
* Added {{website_url}} tag to present the raw URL of the location's website.
* BUG: minor PHP notice that might corrupt the wp-admin view in the Pro version.

= 3.9.17 =
* Added a shortcode parameter not to display the location title on mouse over the map marker.

= 3.9.16 =
* Minor updates.

= 3.9.15 =
* Minor fixes.

= 3.9.14 =
* BUG: It might result in Ajax error on front end with some data.

= 3.9.13 =
* If a custom field content starts with "+", it renders it as tel: link.
* Minor fixes.

= 3.9.12 =
* Minor fixes.

= 3.9.11 =
* Compatibility with PHP 8.2.
* Minor fixes.

= 3.9.10 =
* Added an option to skip automatic conversion to HTML for custom fields (for images, web and email addresses).
* Minor fixes.

= 3.9.9 =
* BUG: It wasn't possible to disable name and address display in the list or in the location map details by adjusting the respective settings.
* Minor fixes.

= 3.9.8 =
* BUG: When using Locate Me option, the search form resubmit reset the current customer position.
* Disable form submit ability untill our scripts are completely loaded that should prevent potential errors.
* Added options for variable page size in admin locations listing.

= 3.9.7 =
* BUG: Fatal error after adding a product on sites with PHP 8. (Pro Version)

= 3.9.6 =
* BUG: If marker clustering was enabled, old (now wrong) markers from the previous search were not removed properly.

= 3.9.5 =
* Minor fix to avoid possible geocoding freeze when there were too many locations with empty address.
* Minor fixes.

= 3.9.4 =
* For shortcode for a single location (with the id parameter) we added the location details display too.
* Minor fixes.

= 3.9.3 =
* Added 'form-after-map' shortcode option to display the search form below the map.
* Minor fixes.

= 3.9.2 =
* PHP 8.0 compatibility fix.

= 3.9.1 =
* Custom fields are also used for search. (Pro Version)

= 3.9.0 =
* Re-added an option to share the same database accross all sites of a multi-site network. (Pro Version)

= 3.8.9 =
* If the results list is grouped, we added an option to display a select list to quickly jump to a group (group-jump shortcode parameter).
* Misc JavaScript and CSS adjustments for better compatibility with other plugins.

= 3.8.8 =
* BUG: Selecting a default custom map icon didn't work properly in the free version.

= 3.8.7 =
* Modified the search algorith to prefer geocoding matches over database partial address or zip code match.

= 3.8.6 =
* Added an option to determine visitor current location.

= 3.8.5 =
* Minor modifications to front end JavaScript code.

= 3.8.4 =
* Added a setting to force map language.
* Increased number of custom fields to 20 (Pro Version).

= 3.8.3 =
* Added export option for search log.

= 3.8.2 =
* Moved REST API inside main plugin rather than a separate plugin.

= 3.8.1 =
* Locatoraid REST interface added.

= 3.8.0 =
* Plugin ownership has been changed to Plainware.

= 3.7.2 =
* BUG: PHP notice with PHP 7.4 in profiler.php file.
* BUG: "REST API" error in Wordpress SiteHealth.

= 3.7.1 =
* Added a shortcode parameter if to display the search radius drop down box.
* Added a setting if to open links in a new window.

= 3.7.0 =
* Added a setting to define a default map pin for locations.

= 3.6.9 =
* BUG: multiple products checkbox in the search form may have worked incorrectly.

= 3.6.8 =
* BUG: the "limit" shortcode parameter may have worked incorrectly.
* BUG: an attempt to fix an error when custom fields module was not properly installed itself.
* The upload file can now include id column (Pro Version).
* Added bulk actions for locations in the admin area.
* Multiple shortcodes can be used one page.

= 3.6.7 =
* Useful for multi language websites: front end text configuration is now translation ready. For example, enter "Search" in the settings form, then translate it in the language file.

= 3.6.6 =
* BUG: not yet geocoded entries may have appeared in search results.
* If search results are grouped by state, entries with no state defined now go at the bottom rather on top.

= 3.6.5 =
* Phone numbers now appear as links (tel:).
* Search now tries to find a partial match in location name or address.
* "No results" text is now configurable.

= 3.6.4 =
* If more than one product is filtered with where-product, show checkboxes to select them in the search form (Pro Version).

= 3.6.3 =
* BUG: Ajax error on sites with PHP 7.2 (Function create_function() is deprecated error).

= 3.6.2 =
* BUG: Location pins were not displayed on the map after search (since version 3.6.1).

= 3.6.1 =
* If several locations share the same address, the marker on the map now shows the number of those locations, and their details are displayed in the same info window.

= 3.6.0 =
* Added input for custom Google maps options.

= 3.5.9 =
* Added map icon clustering option.
* The search field is set required if "start" shortcode parameter is set to "no".

= 3.5.8 =
* BUG: when only one location matched the search, the map might not be zoomed properly.
* Added an option to change show order for products (Pro Version).

= 3.5.7 =
* BUG: "where-product" shortcode parameter wasn't taking effect if the search string couldn't be geocoded (Pro Version).

= 3.5.6 =
* Added map-start-address and map-start-zoom shortcode parameters to initialize the default map without search results.

= 3.5.5 =
* Minor bug fix in importing locations (Pro Version).

= 3.5.4 =
* Disabled possible errors in json output parts that might have caused "Ajax Error" alerts.

= 3.5.3 =
* Minor bug fixes.

= 3.5.2 =
* BUG: If there was only one match, the map didn't zoom close enough.
* Added the "Draft" priority that can be used to temporarily hide locations from the front end.

= 3.5.1 =
* Another fix for the zip code exact match (the previous update may not work sometimes).

= 3.5.0 =
* A small fix to check for an exact match for the zip code.
* Optimized a procedure to add new map icons to reuse already existing options (Pro version).

= 3.4.9 =
* Added a small fix so that the results list scrolls back to top on search results update.

= 3.4.8 =
* A few minor code fixes.

= 3.4.7 =
* Updated JavaScript files to help avoid conflicts with other plugins and themes.

= 3.4.6 =
* BUG: JavaScript error in the front end if the map or the list output was hidden by the "layout" shortcode parameter.

= 3.4.5 =
* BUG: "sort" shortcode parameter was not working.

= 3.4.4 =
* A fix for a possible JSON error when bulk geocoding locations.

= 3.4.3 =
* Exact name match, if a visitor searches for a location name, and there is a full match, it now sets it as a starting point of search.
* Shortcode parameter search-bias-country.

= 3.4.2 =
* Fixed a few bugs that might appear after upgrading to the Pro version saying that certain table fields are missing.
* Added a setting to hide the product selection in the front end search form.

= 3.4.1 =
* Fixed permissions settings for the search log module as it may be visible for backend users who were not allowed to access Locatoraid.
* Added the list of posts and pages with Locatoraid shortcode on the Publish admin page.
* The front end search form is automatically resubmitted if the product checkbox is clicked.
* Redesigned a bit the admin location fields configuration form (more convenient for custom HTML code if needed).

= 3.4.0 =
* Added the search log module.
* Added an option to override any shortcode parameter with GET parameters.
* In the admin aread adjusted the paging view so that it now displays the total number of locations.

= 3.3.2 =
* BUG: A fatal error appeared when the "where-product" shorcode parameter was used (Pro version).

= 3.3.1 =
* BUG: When searching for more than one product, it returned less results then it should (Pro version).
* BUG: The export file didn't properly link locations to products if any (Pro version).

= 3.3.0 =
* Added the id parameter for the shortcode, so it displays the map for this location only, with no search form.
* BUG: There might be fatal errors when both free and pro versions are enabled.

= 3.2.9 =
* BUG: An ajax error when using the limit="1" parameter for the shortcode.

= 3.2.8 =
* BUG: Another fix for the Ajax Error (JSON.parse: bad escaped character) message with certain configurations.

= 3.2.7 =
* BUG: The search form widget didn't work properly if only digits search string was posted, like zip (postal) code.
* BUG: Attempted to fix the Ajax Error (JSON.parse: bad escaped character) message with certain configurations.

= 3.2.6 =
* BUG: When uploading a CSV file, and it contained new products, the location to product relations were not built properly.
* Minor code updates.

= 3.2.5 =
* BUG: When it was configured not to start with default results, the map outline was still displayed.
* Minor code updates.

= 3.2.4 =
* BUG: Error saying "required field" that did not allow to update a location.

= 3.2.3 =
* BUG: Fatal error when upgrading from several older versions.

= 3.2.2 =
* BUG: The bulk geocoding process did not save the coordinates properly.

= 3.2.1 =
* Minor bug fixes.

= 3.2.0 =
* Front text (submit button, search field, more results link) can be edited.
* Bulk delete option for products (Pro version).
* Code refactoring improved speed and reduced size.

= 3.1.7 =
* BUG: The initial Google Maps API Key entry check didn't work correctly.

= 3.1.6 =
* BUG: The import upload did not associate locations with products (Pro version).
* Internal cleanup and optimization that now requires PHP version 5.3 or later.

= 3.1.5 =
* If you do not want to auto start with default results, you can use the start="no" shortcode parameter.

= 3.1.4 =
* Added a shortcode parameter to filter by product ("where-product") (Pro version).
* Added an option to supply custom map styles.
* Added a setting to enable/disable map scroll zoom.
* For the widget we have added an option to choose a target page to submit the search to, if you have multiple front end locator pages.
* BUG: Could not import from CSV files created on a Mac computer (Pro version).
* BUG: When updating an existing location and trying to add 0 to the start of the zip code, it got dropped.

= 3.1.3 =
* BUG: searching in the admin view produced a fatal error.
* BUG: products were shown as [object] text in the search results (Pro version).

= 3.1.2 =
* BUG: if a starting search setting consisted of digits only (a zip code for example), it did not recognize it.

= 3.1.1 =
* The search form widget is available again.

= 3.1.0 =
* A few code updates.

= 3.0.8 =
* Modified the front view output for a better fit with various themes.
* Moved the submenu links closer to the page header for a more prominent position.

= 3.0.7 =
* Added the map-style and list-style shortcode parameters to specify custom style HTML attribute for the map and the results list components.

= 3.0.6 =
* BUG: If only "map" or "list" options were given in the shortcode "layout" parameter, the front end did not work.

= 3.0.5 =
* Now it allows to enter "none" as the Google Maps API key if you don't need it for any reason.
* Minor code updates.

= 3.0.4 =
* Now it allows to use duplicated locations names.
* Minor code updates.

= 3.0.3 =
* BUG: the language translation files were not loaded correctly.

= 3.0.2 =
* Make the map and the results list start hidden if no default search is given.
* Minor code updates.

= 3.0.1 =
* BUG: fixed a fatal database error for new installs.
* Added the .pot language file.

= 3.0.0 =
* A new major update.

= 2.7.6 =
* Fixed the unnecessary slashes appearing.

= 2.7.5 =
* Added the reverse alphabetically sort option.
* A few code updates and fixes.

= 2.7.4 =
* Removed potentially vulnerable own copy of PHPMailer library.

= 2.7.3 =
* Added shortcode parameter for the default country option.
* Added a dependency on jQuery for our scripts as it may be required for some WordPress configurations.

= 2.7.2 =
* BUG: the admin Install menu produced an error.

= 2.7.1 =
* Minor code updates.

= 2.7.0 =
* Added a setting if the street address for locations is required. If not, then you can leave just the city.

= 2.6.9 =
* Minor fixes in locations upload (Pro) and location name display functions.

= 2.6.8 =
* Added a configuration field to enter the Google Maps API key following the change in the Google Maps usage conditions.

= 2.6.7 =
* BUG: 404 error after certain WordPress search results
* Switched database engine to mysqli if it's available for compatibility with PHP 7

= 2.6.6 =
* BUG: Google maps API infobox URL fix

= 2.6.5 =
* BUG: the location search may have failed after settings update

= 2.6.4 =
* BUG: featured locations were not visually highlighted in the front end
* Added an option to sort locations by misc10 field [Pro]
* Minor code fixes

= 2.6.3 =
* Modified a bit the front end search form for a nicer view both in desktop and mobile.

= 2.6.2 =
* Added an option to set number of locations per page in the admin area.

= 2.6.1 =
* Allow longer entries for the website field (up to 300 characters), it was limited to 100 characters.

= 2.6.0 =
* Small fix for the stats module to prevent SQL error under some configurations.
* Modified a bit the admin edit location form to allow a bit more space for text inputs.
* Modified a bit the front end search form for a nicer view.
* Added a setting to open directions in a new window.
* Added an option for the admin to manually enter geo coordinates for a location.

= 2.5.9 =
* A little tweak to possibly share Google maps API file with other plugins.

= 2.5.8 =
* BUG: the address field format configuration was reset after updating the core settings.

= 2.5.7 =
* Added address display format configuration.
* BUG: directions link not working from the map after the infobox appeared after clicking on the locations list.

= 2.5.6 =
* Added options for labels before the search field and the radius selection.

= 2.5.5 =
* Added an option to configure which fields to show in the search results list and on the map.

= 2.5.4 =
* BUG: "Always Shown" locations were not really always displayed.

= 2.5.3 =
* BUG: If the matched locations title was set to blank, it still showed in the frontend.
* BUG: The matched locations count was wrong if the output group by option was set.
* Added an option to translate the Directions link label.
* Moved all localization/customization options for the front end together in the settings form.

= 2.5.2 =
* Skip locations with empty name and street address in the locations import file.
* BUG: If the locations import file contained special characters like umlauts then they were skipped.

= 2.5.1 =
* Added a setting to show the matched locations count in the front end.
* Skip empty lines in the locations import file.

= 2.5.0 =
* Added a setting to disable the scroll wheel in the map, it is useful when you don't want to automatically zoom the map when scrolling the page.

= 2.4.9 =
* The Pro version now can have up to 10 misc fields.

= 2.4.8 =
* A small fix to allow just "//" URLs, without protocol.

= 2.4.7 =
* A new option to group locations output by zip/postal code.

= 2.4.5 =
* Added an option to share the same database accross all sites of a multi-site network.

= 2.4.5 =
* A fix for the error in the print view for some search strings.

= 2.4.4 =
* Modified JavaScript to avoid conflicts with some themes.

= 2.4.3 =
* Print view link in the front end.

= 2.4.2 =
* A new option to group locations output by country, by country then by city, and by country then by state. It becomes active when countries are entered for your locations.

= 2.4.1 =
* When using products, now it searches for the exact product name. Before it might give wrong results because it searched for ANY word from the product name. For example, if you had two products "Dark Beer" and "Lager Beer", and searched for "Dark Beer", it also returned records with "Lager Beer" only because it contained the word "Beer".
* Product names are sorted in alphabetical order

= 2.4.0 =
* Now it can recognize shortcode options. Currently there are 2: "search" for the search address, and "search2" for the product option if you have any.
For example: [locatoraid search2="Pizza"]

= 2.3.9 =
* Added options to configure all other labels in the front end search form so now it can be easily translated into any language.

= 2.3.8 =
* Added an option to configure the search form label: the "Address or zip code" text.

= 2.3.7 =
* Loading Google maps JavaScript libraries with "//" rather than "http://" that will fix the error on https websites

= 2.3.6 =
* Fixed the empty label for website address in the admin panel

= 2.3.5 =
* Fixed compatibility issue with AutoChimp plugin
* Modified the CSV import code that may have failed then loading UTF-8 encoded CSV files (applies to the Pro version).

= 2.3.4 =
* Added a dropdown input to choose a country if you have locations in several countries
* Added a configuration for the location website label. If no label is given then the location's website URL is displayed. Applies to the Pro version.
* BUG: fatal error when Locatoraid front end was appeared on a post in the blog posts list rather on a page of its own.

= 2.3.3 =
* A fix for front end view for sites that implement page caching for example WPEngine

= 2.3.2 =
* BUG: when submitting the search by hitting the Enter button rather than a click, the auto-detect location input was appearing.

= 2.3.1 =
* Added an option to hide the user autodetect button
* Added an option to view locations in alphabetical order (in Settings > Group Output)
* BUG: the admin area in multi site installation was redirecting to the default site
* Added the data-id attribute in the location wrapping div (.lpr-location) in the front-end for a possible developer use

= 2.3.0 =
* Admin panel restyled for a closer match to the latest WordPress version.
* Front end JavaScript placed in a separate file to optimize loading.
* Cleaned and optimized many files thus greatly reducing the package size.
* The Pro version now features automatic updates option too.

= 2.2.2 =
* Redesigned the front end search form.
* Minor updates and fixes.

= 2.2.1 =
* Fixed a bug if you are using several instances (like locatoraid2.php and [locatoraid2] shortcode), it was showing the first instance for all the shortcodes.
* Added a wrapping CSS classes for location view in front end like .lpr-location-distance, .lpr-location-address, .lpr-location-name

= 2.2.0 =
* Added an option to set a limit on the number of locations that are shown following a search. For example, even though there may be 10 locations near AB10 C78, the locator only shows 3.

= 2.1.9 =
* Added a search form widget

= 2.1.8 =
* Making the plugin admin area accessible by only Editors or higher

= 2.1.7 =
* a small fix in the front end view when both "append search" and "start with all locations listing" options were enabled

= 2.1.6 =
* jQuery live() deprecated calls replaced

= 2.1.5 =
* When using auto search (auto detecting the current location), and switching the distance or the product selection, the search results were reverted back to the default search rather than current location.
* Language file fix

= 2.1.4 =
* Failed setup procedure in some WP configurations

= 2.1.3 =
* Error in location count when prompting a next radius search
* Failed shortcode with some WP configurations

= 2.1.2 =
* Enabled native languages interface

= 2.1.1 =
* Cleared jQuery dependency, making use of the built-in WP version

= 2.1.0 =
* Initial plugin version release

Thank You.