=== Houzez Property Feed ===
Contributors: PropertyHive
Tags: property import, property export, houzez, houzez import property, real estate
Requires at least: 3.8
Tested up to: 6.4.3
Stable tag: 2.1.6
Version: 2.1.6
Homepage: https://houzezpropertyfeed.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Automatically import properties to Houzez from estate agency CRMs and export to portals

== Description ==

This free plugin from the creators of [Property Hive](https://wordpress.org/plugins/propertyhive) makes it easy to import and export properties to Houzez from various CRMs, including XML and CSV files in any format, into the popular Houzez theme.

We can import properties from the following estate agency CRMs/formats:

* 10ninety
* Acquaint
* agentOS
* Alto by Vebra
* Apex27
* BDP
* BLM
* CSV (any CSV file hosted on a public URL)
* Dezrez Rezi
* Domus
* Expert Agent
* Gnomen
* Inmobalia
* Jupix
* Kyero
* Loop
* MRI
* MLS/IDX (assuming XML URL can be provided)
* myCRM from Property Finder
* Pixxi
* RE/MAX
* Rentman
* ReSales Online
* Rex
* Street (including sending enquiries back in Street)
* XML (any XML file hosted on a public URL)

We can export and upload feeds from Houzez to third party portals in the following formats:

* BLM
* Facebook
* Idealista
* Kyero v3 (including WPML support)
* Rightmove and OnTheMarket Real-Time Format (RTDF)
* Thribee / LIFULL Connect ( Trovit / Mitula / Nestoria / Nuroa )
* Zoopla Real-Time Format

Here's just a couple of reasons why you should choose the Houzez Property Feed plugin to import and export your property stock:

* 20+ years experience in working with property feeds
* New formats always being added
* Lots of settings and easy to configure
* In-depth [documentation](https://houzezpropertyfeed.com/documentation/)

= Free features =

* Automatic imports and export
* One active import and export
* Import and export up to 25 properties
* Logs stored for one day

= PRO features =

* All of the above, plus:
* Import and export unlimited properties
* Multiple simulateous active imports and exports
* Priority support
* Logs stored for seven days
* Import logs emailed to a specified email address
* Import media in a separate background queue

[Update to PRO here](https://houzezpropertyfeed.com/#pricing)

== Installation ==

= Requirements =

* Houzez theme installed and activated
* For formats that use XML the PHP SimpleXML library will need to installed
* That WP Cron is firing automatically or an alternative cron job in place
* For formats that send or receive files via FTP the PHP FTP functionality will need to be available

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Property Hive, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "Houzez Property Feed" and click Search Plugins. Once you've found our plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading the Houzez Property Feed plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

Once installed and activated, you can access the import tool by navigating to 'Houzez > Import Properties' from within Wordpress.

= Updating =

Updating should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Once activated you'll be presented with a new 'Import Properties' admin menu item where you can manage your imports
2. Existing automatic imports will be displayed along with scheduling information
3. Adding and editing imports is easy with our simple to use interface
4. Each time an import runs we'll store in-depth logs so you can see exactly what was imported and when, plus any errors that arose

== Changelog ==

= 2.1.6 - 2024-03-13 =
* Cater for really long image URLs in Rex format with no ext. Often the case when they send watermarked images
* Don't import media in the XML or CSV format if no media mapping has been configured 

= 2.1.5 - 2024-03-12 =
* Append disclaimer to descriptions imported from Rex
* Get paginated data in RE/MAX requests for properties
* Extend timeout limits on RE/MAX requests

= 2.1.4 - 2024-02-26 =
* Corrected postcode field name in Acquaint imports
* Corrected the post ID variable passed through in Zoopla and RTDF export filters
* Removed unnecessary logging from Facebook exports
* Updated pro features
* Uninstall process updated to reflect recent cron and DB updates
* Reduced README to 5 tags

= 2.1.3 - 2024-02-26 =
* Added preliminary support for importing properties from Rex
* Added preliminary support for importing properties from Pixxi
* Corrected virtual tours in RTDF and Zoopla export formats
* Remove checking of fave_floor_plans_enable meta when exporting floorplans

= 2.1.2 - 2024-02-21 =
* Added preliminary support for importing properties from myCRM by Property Finder

= 2.1.1 - 2024-02-19 =
* Don't use CDATA in Kyero exports for fields like descriptions and features. Instead run them through htmlspecialchars()
* Ensured channel is set in RTDF send requests
* Address tweaks in RTDF send requests; try and get house number and give 'town' more chance of being set by looking at more address fields
* Corrected status not getting sent in RTDF send requests
* Corrected RTDF export property removals
* Updated list of default property mappings in Kyero export settings

= 2.1.0 - 2024-02-15 =
* Added new PRO import setting allowing media to be downloaded in the background in a separate queue. Useful for large imports that are timing out
* Allow filtering of properties imported by office ID(s) in RE/MAX format

= 2.0.65 - 2024-02-09 =
* Add ability to map statuses for sales and lettings properties in Kyero imports
* Remove unused variable in Zoopla reconcilliation process causing PHP warning

= 2.0.64 - 2024-02-08 =
* Correct removal and reconcilliation of properties in Zoopla exports
* Recently added export filename filter also applied to main exports table

= 2.0.63 - 2024-02-07 =
* New filter 'houzez_property_feed_properties_loop_endpoints' to customise Loop API endpoints
* New filter 'houzez_property_feed_export_kyero_url_filename' to change Kyero export filename (mainly for Idealista as they use the Kyero format apparently, but need a specific filename)
* Add notice when Idealista format is selected about using Kyero instead
* Import sizes in Kyero imports
* Remove empty branch codes when reconciling Zoopla exports

= 2.0.62 - 2024-02-01 =
* Set property_country taxonomy in 10ninety imports when applicable
* First steps in reconciling Zoopla exports. Just logged for now whilst we trial this feature
* Declared compatibility with WordPress 6.4.3

= 2.0.61 - 2024-01-24 =
* If new status or type received that's not been mapped before add it to the 'Taxonomy' settings area as an option ready to be mapped
* Corrected floorplans importing in Apex27 format
* Corrected currency used in Facebook exports

= 2.0.60 - 2024-01-22 =
* Ensure license requests are cached and only done every 24 hours

= 2.0.59 - 2024-01-08 =
* Support for lettings properties in ReSales Online XML format
* Exclude off market properties from ReSales Online XML format
* Tweaks to how we check if XML xpath field mapping should be used

= 2.0.58 - 2023-12-20 =
* Cater for multiselect custom fields in import field mapping
* Ensure images imported from XML and CSV always have an extension
* Added ability to automatically match agent/agency based on name in import field mapping

= 2.0.57 - 2023-12-08 =
* Added support for exporting to Thribee/LIFULL Connect (Trovit/Mitula/Nestoria/Nuroa)

= 2.0.56 - 2023-12-07 =
* Corrected property type mapping in Resales Online XML imports
* Import ADDRESS_1 from BLM files as part of the address field in Houzez
* Default currency in Facebook exports to GBP
* Declared compatibility with WordPress 6.4.2

= 2.0.55 - 2023-12-02 =
* Added support for exporting to Facebook

= 2.0.54 - 2023-12-02 =
* Support for sending enquiries back into the Street CRM

= 2.0.53 - 2023-12-01 =
* PHP 7 compatibility when saving field mapping rules
* Corrected property submission through Houzez frontend of site not exporting in real-time
* Corrected various Zoopla issues: department not being set, don't send empty living rooms, correct rent frequency

= 2.0.52 - 2023-11-30 =
* Corrections to BLM overseas export format
* Add 'HOUSE_NAME_NUMBER' to list of BLM export fields for use in field mapping
* Add countries array and separate functions for use in overseas feeds

= 2.0.51 - 2023-11-29 =
* Include commercial properties in Vebra Alto imports
* Output whole response from Zoopla when error occurs
* Add ability for third party formats to be added

= 2.0.50 - 2023-11-22 =
* Better detection of name/number and street in Zoopla exports
* Increase chances of a mandatory town existing in Zoopla exports by looking at more location fields
* Corrected status not being sent in Zoopla exports
* Pass 'sslverify' as false in some calls to wp_remote_get() to prevent warnings about SSL certificates

= 2.0.49 - 2023-11-21 =
* Added ability to choose property selection on a per export basis should you want some exports to include all properties, and others to receive individual properties (requires pro subscription)
* Support for additional fields when importing virtual tours in Gnomen import
* Corrected fields used for descriptions in Gnomen import when a property is imported for the first time
* Reset filename if error when uploading file to export settings so it doesn't give the illusion it uploaded fine
* Added permissions check for creating export folder too and show warning if permissions not correct

= 2.0.48 - 2023-11-19 =
* Initial support for importing properties from Inmobalia

= 2.0.47 - 2023-11-17 =
* Attempt to set parent for location based taxonomies once an import has finished
* Added new action 'houzez_property_feed_post_import_properties' to end of import process
* Corrected 0 status not being imported in Alto import
* Corrected wrong properties getting removed in Alto format

= 2.0.46 - 2023-11-15 =
* Added ability to specify CSV delimiter character when setting up a CSV import. Defaults to comma
* Added ability to specify that property features are delimited when setting up import field mapping. Useful if all features are sent in a single field
* Added new filters to XML import media URLs: houzez_property_feed_xml_image_url, houzez_property_feed_xml_floorplan_url and houzez_property_feed_xml_document_url
* Added new filters to CSV import media URLs: houzez_property_feed_csv_image_url, houzez_property_feed_csv_floorplan_url and houzez_property_feed_csv_document_url

= 2.0.45 - 2023-11-15 =
* Update to last release to also extract virtual tour URL from iframe and send that as the virtual tour in Kyero XML exports
* Ensure video URLs are valid URL's before including in Kyero XML exports

= 2.0.44 - 2023-11-14 =
* Support added for video URL on a property being included in Kyero XML exports

= 2.0.43 - 2023-11-13 =
* Added support for exporting properties to Idealista
* Ensure post related fields (i.e. Post Title, Post Content etc) are replaced in export field mapping rules

= 2.0.42 - 2023-11-11 =
* Added support for importing properties in the ReSales Online XML format

= 2.0.41 - 2023-11-04 =
* Added support for WPML in Kyero XML exports where URLs and descriptions can be sent in different languages
* New HPF_EXPORT constant defined during exports

= 2.0.40 - 2023-11-01 =
* Corrected field mapping

= 2.0.39 - 2023-10-18 =
* Added ability to clone an import
* Added ability to clone import field mapping rules

= 2.0.38 - 2023-10-18 =
* Still send exports even if no properties selected to be sent. Trying to be too helpful before by stopping them

= 2.0.37 - 2023-10-16 =
* Added support for Rentman XML format
* Added ability to set 'Not Equal' rule in import setting field mapping
* Declared compatibility with WordPress 6.3.2

= 2.0.36 - 2023-10-12 =
* Creating a field mapping rule to set post_status to take effect in XML and CSV imports
* New field mapping rules layout also available in export settings

= 2.0.35 - 2023-10-11 =
* Added ability to set post status in import field mapping rules
* Corrected property ID variable used when writing various logs

= 2.0.34 - 2023-10-11 =
* Use local PHP class to sign AWS requests instead of including full AWS SDK to keep filesize of plugin down

= 2.0.33 - 2023-10-11 =
* Re-factored 'Field Mapping' screen in import settings to make it easier to use and navigate when lots of rules are in place.
* Initial support for RE/MAX import format

= 2.0.32 - 2023-10-10 =
* Ensure images imported via XML have an extension. Sometimes a third party URL would link to a PHP or ASP script that dynamically generates the image
* Correcteed issue with warning not showing when mapping fields in XML format alerting user to the fact title, content or excerpt must be mapped

= 2.0.31 - 2023-10-06 =
* Run field mapping rule input data through stripslashes(). Checks for XPath selectors like [@lang='en'] would get escaped and therefore not executed
* Trim media filenames in XML imports if filename over 100 characters. Long filenames caused attachment not be inserted into database

= 2.0.30 - 2023-10-06 =
* When mapping fields in import settings, change ones that have a list of finite values (i.e. 'Featured') to a dropdown instead of freetype text
* Add agent/agency contact information display settings to field mapping section of import settings
* Set unit of size to sq m when importing from Gnomen

= 2.0.29 - 2023-10-02 =
* Remove any namespace references from imported XML files. When the namespace wasn't a URL it was causing issues with xpath

= 2.0.28 - 2023-09-28 =
* Improvements to Gnomen format (import price qualifier, area, exclude withdrawn properties and more)
* Add filters 'houzez_property_feed_xml_request_args' and 'houzez_property_feed_csv_request_args' so XML/CSV request arguments can be customised to add headers etc

= 2.0.27 - 2023-09-25 =
* Remove non-numeric characters from lat/lng related fields during CSV import

= 2.0.26 - 2023-09-24 =
* Ensure fave_featured meta key always set as it's sometimes used for ordering and often resulted in no results showing if meta key didn't exist

= 2.0.25 - 2023-09-22 =
* Added ability to specify media field character delimiter when media all sent in one CSV column. Defaults to comma

= 2.0.24 - 2023-09-22 =
* Added support for media URLs being in one comma-delimited CSV column, as opposed to one per column
* Added more debugging to Gnomen format should it not be able to obtain the XML

= 2.0.23 - 2023-09-08 =
* Imported price qualifier into 'Price Text' field from MRI format

= 2.0.22 - 2023-09-08 =
* Added support for Gnomen format
* Remember the point an import gets to so if it times out we can continue from this property/image the next time an import runs. This will also be reflected in the logs
* Added documentation links to taxonomy settings pages
* Cast all types and statuses to integers across all import formats. Previously there would be cases where a new property type of '16' for example would be created
* Corrected wrong post being referenced in remove logs
* Corrected typo in variable name passed to action

= 2.0.21 - 2023-09-05 =
* Added surface_area node to Kyero exports when applicable
* Added new remove action of 'delete' property (PRO users only)
* Corrected 0 STATUS_ID not importing in 10ninety format
* Corrected message shown when export completed
* Declared compatibility with WordPress 6.3.1

= 2.0.20 - 2023-08-18 =
* Only show 'View BLM' option when export is active
* Still output file when previewing BLM and no properties
* Clear down old generated BLM files sitting in uploads folder
* Cater for wildcard in field mapping export rules

= 2.0.19 - 2023-08-17 =
* Corrected issue with field mapping filter returning false instead of property data if no field mapping setup

= 2.0.18 - 2023-08-17 =
* Added ability to map price qualifiers in exports. BLM exports only for now

= 2.0.17 - 2023-08-17 =
* Added support for importing properties in the MRI XML format

= 2.0.16 - 2023-08-16 =
* Added more Houzez fields to dropdown in field mappings section of import settings (featured, show map, map address and more)
* Ensure warning shows in CSV import settings if no title, excerpt or content set

= 2.0.15 - 2023-08-14 =
* Cater for mapping property type combined with property style in Street format (i.e. Detached House - Bungalow)
* Declared compatibility with WordPress 6.3

= 2.0.14 - 2023-07-27 =
* Cater for Kyero fields being sent through as empty spaces when building display address/post title

= 2.0.13 - 2023-07-24 =
* Correct issue that prevented plugin from being deleted by adding dedicated uninstall.php file

= 2.0.12 - 2023-07-21 =
* Corrected status not being imported in BLM when set to 0

= 2.0.11 - 2023-07-12 =
* Added support for new Loop V2 status 'soldSTC' in mapping options

= 2.0.10 - 2023-07-07 =
* Added agent ID and agent name as possible contact information fields for the Kyero import format

= 2.0.9 - 2023-07-03 =
* Added geocoding functionality to formats that don't provide a lat/lng, specifically BLM and AgentOS. This will pass the postcode to the Nominatim geocoding service and store the lat/lng returned

= 2.0.8 - 2023-06-30 =
* Correct featured image not getting set in BLM when files provided locally

= 2.0.7 - 2023-06-30 =
* Convert BLM data sent to UTF8 before importing. When the BLM was encoding using non-UTF8 encoding certain symbols would result in the post not being inserted

= 2.0.6 - 2023-06-29 =
* Added 'Base URL' option to BDP format as this might change based on a BDP account enviroment
* Added better debugging to BDP when invalid JSON is returned
* Capitalise frequency in import/export tables
* Corrected imports set to run every 15 minutes not respecting this

= 2.0.5 - 2023-06-29 =
* Corrected plugin not working if Houzez had been white labelled

= 2.0.4 - 2023-06-27 =
* Added field mapping rules to BLM and Kyero so exported data can be customised
* Added ability to preview the BLM
* Tidying up of unused variables and methods

= 2.0.3 - 2023-06-26 =
* Added support for automatic exports to Zoopla
* Corrections to RTDF format

= 2.0.2 - 2023-06-25 =
* Added support for automatic exports in the RTDF format for Rightmove and OnTheMarket

= 2.0.1 - 2023-06-23 =
* Added support for automatic exports in the Kyero v3 XML format

= 2.0.0 - 2023-06-23 =
* Added support for automatic exports. Only BLM format added for now but more to be rolled out soon
* Show admin notice if Houzez theme not active
* Only do redirect when plugin activated if Houzez is active. Previously it would show an error

= 1.0.21 - 2023-06-20 =
* Support for CSV format allowing any CSV file hosted on a public URL to be imported

= 1.0.20 - 2023-06-19 =
* Corrected issue with dragging of XML fields into field mapping rules when using XML format
* Added spacing between XML fields in field mapping section

= 1.0.19 - 2023-06-19 =
* Support for XML format allowing any XML file hosted on a public URL to be imported
* Show warning if trying to map a field that is already imported by default
* Make format dropdown searchable to make finding a format easier as the list grows

= 1.0.18 - 2023-06-15 =
* Field mapping feature in an import settings area updated to support groups of multiple rules

= 1.0.17 - 2023-06-14 =
* Added support for Kyero
* Catered for formats that don't have a taxonomy mapping set
* Prevent license check being done multiple times on the same page which should improve performance

= 1.0.16 - 2023-06-14 =
* Check for 'youtu' instead of 'youtube' to cater for short URLs when deciding where to import a video

= 1.0.15 - 2023-06-13 =
* Added new 'Field Mapping' section to import settings to allow complete control over fields imported, as well as catering for any fields added using the Houzez Field Builder feature

= 1.0.14 - 2023-06-13 =
* Corrected field name referenced for status in Street format

= 1.0.13 - 2023-06-13 =
* Added ability to map additional CRM values when configuring taxonomy mapping. Useful if property types, for example, have been customised in the CRM and isn't one of the standard ones

= 1.0.12 - 2023-06-12 =
* Added support for Jupix
* Cater for no display address/title in logs table

= 1.0.11 - 2023-06-11 =
* Added support for Expert Agent
* Added support for Domus
* Ensure features are cast to a string in Apex27
* Tweaks to open_ftp_connection() function so ftp_chdir() is only called when a directory is passed in

= 1.0.10 - 2023-06-09 =
* Added support for Dezrez Rezi format
* Corrected issues with email reports and remove action not saving

= 1.0.9 - 2023-06-08 =
* Added support for BLM format, specifically files sent via FTP to the server from the third party

= 1.0.8 - 2023-06-07 =
* Initial support for BDP format
* Added 'Import Data' meta box to property edit screen to see what data was sent by CRM

= 1.0.7 - 2023-06-07 =
* Initial support for Alto by Vebra format
* Show warnings when setting up an import if required libraries (cURL, SimpleXML etc) are missing

= 1.0.6 - 2023-06-07 =
* Initial support for agentOS format
* Added ability to add a warning message per format when setting up an import. Done for agentOS where we need to show a warning about throtlling
* Changed rent frequencies to lowercase
* Changed datatype of 'entry' field in logs table to store more text. Useful when wanting to see the full response

= 1.0.5 - 2023-06-06 =
* Initial support for Apex27 format

= 1.0.4 - 2023-06-06 =
* Initial support for Acquaint format

= 1.0.3 - 2023-06-06 =
* Initial support for 10ninety format
* Initial support for Street formats

= 1.0.2 - 2023-06-05 =
* Escaping and sanitization to meet WordPress plugin guidelines
* Don't set global PHP limits to meet WordPress plugin guidelines
* Remove use of ALLOW_UNFILTERED_UPLOADS to meet WordPress plugin guidelines

= 1.0.1 - 2023-05-26 =
* Taxonomy mapping
* Contact information mapping and rules
* License key integration
* Only show pro link in plugin list if pro not in place
* Corrected featured image not getting set when new property imported

= 1.0.0 - 2023-05-23 =
* First working release of the plugin