=== Get Use APIs - JSON Content Importer ===
Contributors: berkux
Tags: api, json, connect, endpoint, data
Requires at least: 5.3
Requires PHP: 7.0
Tested up to: 6.4.3
Stable tag: 1.5.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WordPress meets APIs: Get API-data and show it with a Shortcode or a JCI Block. Generate a template with the JCI Block. New: ContactForm7 Integration

== Description ==

The simplest method to link WordPress with external APIs

JCI simplifies the task of retrieving data from a third-party REST API. The returned data can be neatly arranged and showcased on your WordPress site via a shortcode or a JCI Block. Generate a template with the JCI Block.

20 seconds: Load API and display data

https://www.youtube.com/watch?v=RBlrAUVywAk

7 minutes: Basic intro to JCI free

https://www.youtube.com/watch?v=SDUj1teNG2s

### Examples, Preview

Check out the live demonstration to explore how we're interfacing with various APIs
[LIVE PREVIEW - WordPress and a external API](https://api.json-content-importer.com/free-jci-plugin-example/wetter/)

[Video: How to use the Wikipedia API](https://www.youtube.com/watch?v=GJGBPvaKZsk)

### Main Plugin Features

* Low code usage: Save time and avoid coding using an API
* Insert an API-URL into the JCI Block: Data is loaded, and a template can be generated to display the data.
* Easy to start: Check Installation - Is your WordPress ready for JCI? Most probably!
* Basic Settings: Check SSL, Cacher, Gutenberg and Authentication
* ContactForm7 Integration: Populate CF7 forms with JSON data and forward CF7-submitted data to an API.
* Use the JCI cacher to avoid many API requests
* Step 1: Use the simple JCI Block interface to get data! Query the API and check the response.
* Step 2: Utilize the simple template generator inside the JCI Block to use the data!
* Contact Form 7 Integration: Fill form and submit to API

### Documentation

There's a wealth of resources and support at your fingertips. Explore the articles listed below to begin your journey:

* [JCI Manual](https://doc.json-content-importer.com/)
* [Step 1: Access the Data](https://doc.json-content-importer.com/json-content-importer/step-1-data-access/)
* [Step 2: Using the Data](https://doc.json-content-importer.com/json-content-importer/step-2-data-usage/)
* [Shortcode – Basic structure](https://doc.json-content-importer.com/json-content-importer/shortcode-basic-structure/)
* [Contact Form 7 Integration](https://doc.json-content-importer.com/json-content-importer/pro-contactform7/)
* [Free JCI Plugin: Overview Videos](https://doc.json-content-importer.com/json-content-importer/free-overview-videos/)
* [Free JCI Plugin: Getting Started](https://doc.json-content-importer.com/json-content-importer/basic-start/)

### You need more?

= JSON Content Importer PRO =
Both the free and PRO JCI Plugins serve the same purpose: retrieving data, transforming it, and publishing the results.
However, while the free Plugin can only handle basic challenges, the PRO JCI Plugin offers nearly full control over WordPress, the database, and applications.
[Compare: Free vs. JCI PRO Plugin](https://json-content-importer.com/compare/)

PRO features:
* application building by creating a searchform and connect it to a JSON-API in the background: pass GET-Variables to use a dynamic JSON-Feed-URL ("talk to API / webservice")
* much better and more flexible templateengine: twig
* create and use Custom Post Types
* store Templates independent of pages
* more Shortcode-Parameters
* executing Shortcodes inside a template
* more features...

== Frequently Asked Questions ==

= Help! I need more information! =
[Check the JCI manual, please](https://doc.json-content-importer.com)

= Where to start?
Give it a try: The JCI plugin's block comes with a simple, easy-to-understand example. This lets you learn how JCI works without any risk.

= What does this plugin do? =
This plugin enables you to insert a WordPress shortcode or a Gutenberg Block within any page, post, or Custom Post Type (CPT). This facilitates the retrieval of data from an API, which you can then manipulate, such as converting it to HTML for display purposes.
Connecting an API to WordPress offers enhanced content management, extended functionality, personalization and scalability. It enables real-time data display, third-party service integration, and process automation, making your website dynamic and powerful.
The plugin parses almost any JSON-feed and allows you to display all data on your website: Import data from an API or Webservice to display it on your website.

= How can I make sure the plugin works? =
Select the 'JSON Content Importer' option from the Admin Menu. Upon doing so, you'll be presented with various tabs, one of which is labeled 'Check Installation.' Ensure all the tests conducted here are successfully passed for the optimal functioning of the plugin.
Then use the Shortcodes from Tab "Step 1: Get data" and if successful "Step 2: Use data". If that does not work, check Tab "Support".

= Where is this plugin from? =
This plugin is made in munich, bavaria, germany!
Famous for Oktoberfest, FC Bayern Munich, AllianzArena, TUM, BMW, Siemens, seas, mountains and much more...


### Basic structure of a JCI Shortcode (use the JCI Block to generate such a shortcode and a template):
= Shortcode =
'[jsoncontentimporter
* url="http://...json"
* numberofdisplayeditems="number: how many items of level 1 should be displayed? display all: leave empty or set -1"
* urlgettimeout="number: who many seconds for loading url till timeout?"
* basenode="starting point of datasets, the base-node in the JSON-Feed where the data is"
* oneofthesewordsmustbein="default empty, if not empty keywords spearated by ','. At least one of these keywords must be in the created text (here: text=code without html-tags)"
* oneofthesewordsmustbeindepth="default: 1, number: where in the JSON-tree oneofthesewordsmustbein must be?"
]
This is the template:
Any HTML-Code plus "basenode"-datafields wrapped in "{}"
{subloop:"basenode_subloop":"number of subloop-datasets to be displayed"}
Any HTML-Code plus "basenode_subloop"-datafields wrapped in "{}". If JSON-data is HTML add "html" flag like "{fieldname:html}"
{/subloop:"basenode_subloop"}
[/jsoncontentimporter]'

* templates like "{subloop-array:AAAA:10}{text}{subloop:AAAA.image:10}{id}{/subloop:AAAA.image}{/subloop-array:AAAA}" are possible:
one is the recursive usage of "subloop-array" and "subloop".
the other is "{subloop:AAAA.image:10}" where "AAAA.image" is the path to an object. This is fine for some JSON-data.

= Some special add-ons for datafields =
* "{street:purejsondata}": Default-display of a datafield is NOT HTML, but HTML-Tags are converted : use this to use really the pure data from the JSON-Feed
* "{street:html}": Default-display of a datafield is NOT HTML: "&lt;" etc. are converted to "&amp,lt;". Add "html" to display the HTML-Code as Code.
* "{street:htmlAndLinefeed2htmlLinefeed}": Same as "{street:html}" plus "\n"-Linefeeds are converted to HTML-Linebreak
* "{street:ifNotEmptyAddRight:,}": If datafield "street" is not empty, add "," right of datafield-value. allowed chars are: "a-zA-Z0-9,;_-:&lt;&gt;/ "
* "{street:html,ifNotEmptyAddRight:extratext}": you can combine "html" and "ifNotEmptyAdd..." like this
* "{street:purejsondata,ifNotEmptyAddLeftRight:LEFT##RIGHT##}": If datafield "street" is not empty, add text on the left and right
* "{street:ifNotEmptyAdd:,}": same as "ifNotEmptyAddRight"
* "{street:ifNotEmptyAddLeft:,}": If datafield "street" is not empty, add "," left of datafield-value. allowed chars are: "a-zA-Z0-9,;_-:&lt;&gt;/ "
* "{locationname:urlencode}": Insert the php-urlencoded value of the datafield "locationname". Needed when building URLs

== Screenshots ==  
1. Welcome to JCI! Thank you!
2. Check your JCI installation and its requirements.
3. Configure your JCI settings: SSL? Cache? Gutenberg?
4. Step 1: Retrieve data. Highly recommended: Use the JCI Block.
5. Step 2: Use data. Generate a template with the JCI Block and try it out.
6. Locate the JCI Block.
7. JCI Block: Welcome to the JCI Block. Familiarize yourself with the JSON example.
8. JCI Block: Turn debug mode on to see what is happening.
9. JCI Block: Generate a template from JSON.
	
== Changelog ==

= 1.5.6=
* Additional CSS class(es)" defined in the "Advanced" settings of the JCI free Block are now considered
* Enhanced CF7 Integration: Submitted data is now sent successfully even without defining all CF7 Additional Settings

= 1.5.5=
* Plugin ok with WordPress 6.4.3
* ContactForm7 Integration: Populate CF7 forms with JSON data and forward CF7-submitted data to an API.
* Set Shortcode Parameter 'execshortcode=y' if a Shortcode in the enclosed Content of a JCI-free Shortcode should be executed. To mask [ and ] use #BRO# / #BRC# in the enclosed content

= 1.5.4=
* Plugin ok with WordPress 6.4.1
* Improved Security in the Backend

= 1.5.3=
* Enhanced, see Tab "Basic Settings": Send Header "HEADER_KEY:HEADER_VALUE": Insert 'header HEADER_KEY1:HEADER_VALUE1#HEADER_KEY2:HEADER_VALUE2' in the following text field, and no 'Bearer' will be added. E.g.: 'header User-Agent:JCIfree'
* Enhanced: The internal communication within a WordPress block relies on APIs, typically accessed via GET requests. For larger datasets, POST requests are more suitable. This new JCIfree version now utilizes POST instead of GET.
* Changed: Some server have problems, using jcifree-block.php as JavaScript-Applicaton (strict mime policy). Solution: Renamed to jcifree-block.js
* Some Backend-Bugfixes for Block-React, PHP8...

= 1.5.2 =
* Bugfix: JCI Block wasn't showing up when a browser had the "strict mime type" setting enabled. With this bugfix, the issue has been resolved.

= 1.5.1 =
* Fixed: JCI Block - In some situations, unwanted content ('Welcometext') is displayed on the published page.
* Improved: Translation

= 1.5.0 =
* Versionproblems: Wordpress.org does not update from 1.4 to 1.4.1

= 1.4.1 =
* Bugfix: The default settings of the shortcode parameter basenode were incorrect. This has now been corrected.

= 1.4 =
* Recommendation: Please check your JCI cache! The plugin displays the number of files in the JCI cache and its size. You can delete the cached files by using the 'Clear Cache' function.
* Significantly improved admin interface: Tabs, Tests, Settings, Step 1, Step 2...
* Greatly enhanced JCI block. Better error handling, and as a highlight, the creation of templates.
* New: Improved uninstall process.
* Minor PHP 8 fixes
* New Screenshots and improved Plugin-Description

= 1.3.17 =
* Wordpress 6.2 changed the way Blocks are rendered. This Update fixes the crash of the JCI-Block with wordpress 6.2

= 1.3.16 =
* Fixed security issue: Rio D. discovered and reported a Cross Site Scripting (XSS) vulnerability to Patchstack. Thank you Rio! For utilize you need Wordpress-Backend-Access and the affected Page is in the Wordpress-Adminarea only. Nevertheless: Update your JCI-Plugin, please!
* PHP 8.1 fixes

= 1.3.15 =
* New! Use JCILISTITEM to show a JSON-List: {subloop-array:mylist:no_first_item,no_last_item}AA{JCILISTITEM}BB<<br>{/subloop-array:mylist}, see https://doc.json-content-importer.com/json-content-importer/free-show-the-data/ for more
* Enhanced! "purejsondata,ifNotEmptyAdd", "purejsondata,ifNotEmptyAddRight", "purejsondata,ifNotEmptyAddLeftRight" and "purejsondata,ifNotEmptyAddLeft", e. g. {jsondata:purejsondata,ifNotEmptyAddLeft:TEXT}, see https://doc.json-content-importer.com/json-content-importer/free-show-the-data/ for more
* Plugin Ok with WP 6.1.1
* PHP 8.1 fixes

= 1.3.14 =
* New Shortcode-Parameter: "trytorepairjson=16" in the Shortcode removes non ASCII characters from the JSON data
* Bugfix after Wordpress 6.0 changes: Load JSONcontentimporter-Quicktag in a slightly different way
* Bugfix: Handling of JSON-Nodes containing ( or ) 
* Plugin Ok with WP 6.0.2


== Upgrade Notice ==
= 1.5.6=
* Additional CSS class(es)" defined in the "Advanced" settings of the JCI free Block are now considered
* Enhanced CF7 Integration: Submitted data is now sent successfully even without defining all CF7 Additional Settings