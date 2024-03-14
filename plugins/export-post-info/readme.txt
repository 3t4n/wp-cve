=== Export Post Info ===
Contributors: apasionados
Donate link: https://apasionados.es/
Author URI: https://apasionados.es/
Tags: export title, export post titles, extract title, export urls, extract urls, export title and url, export category, export word count, csv export
Requires at least: 4.0.1
Tested up to: 6.3
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin exports posts Date published, Post title, Word Count, Status, URL and Category to a CSV file.

== Description ==

This plugin exports posts Date published, Post title, Word Count, Status, URL and Category to a CSV file, allowing to have an overview of the topics and titles published in the blog.

We created this plugin because we generate large amounts of content for blogs and we often need to lookup the topics that were already covered before in a blog. As we don't want to export the titles by hand we developed this plugin.

= What can I do with this plugin? =

This plugin exports posts Date published, Post title, Word Count, Status, URL and Category to a CSV file that can be imported into Excel.

= What ideas is this plugin based on? =

We were searching for a plugin that allowed us to export the post titles and found [Export All URLs](https://wordpress.org/plugins/export-all-urls/ "Export All URLs"). Unfortunately the plugin didn't export the publish date, status and word count, so we decided to create a new plugin with this information and the the ability to translate it.

= System requirements =

PHP version 5.5 or greater.

= Export Post Info to CSV Plugin in your Language! =
This first release is avaliable in English and Spanish. In the "languages" folder we have included the necessarry files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](http://apasionados.es/contacto/index.php?desde=wordpress-org-export-post-info-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [Export Post Info to CSV en espa&ntilde;ol](https://apasionados.es/blog/exportar-informacion-de-posts-wordpress-plugin-7777/).

== Screenshots ==

1. Plugin settings after install: No random string saved so no export CSV is generated.
2. Plugin settings after saving random string. String is set and export file is created.
3. Plugin settings when you access and the file is created.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
3. Go to SETTINGS / EXPORT POST INFO.
4. Save a random string to make it harder for somebody to access your exported data.
5. Download your CSV file.

== Frequently Asked Questions ==

= Why did you make this plugin?  =

We couldn't find a plugin that would give us the functionality we were looking for:
1) Export publish date.
2) Export word count.
3) Easy translation of the plugin into other languages. So far English and Spanish translations are included.

= Why do I have to setup a random string for the name of the export CSV file?  =
Other plugins always export the file with the same name and in the same folder so it's easy to access this file. We don't want that this file can be easily found, so we decided to let you setup a random string to make the file name harder to guess.

= Does Export Post Info make changes to the database? =
Yes. It created an entry in the options table where the random string is stored.

= How can I check out if the plugin works for me? =
Install and activate. Go to settings / Export Post Info. Save random string. And download CSV file.

= How can I remove Export Post Info to CSV? =
You can simply activate, deactivate or delete it in your plugin management section.

= Which PHP version do I need? =
This plugin has been tested and works with PHP versions 5.5 and greater. WordPress itself [recommends using PHP version 7.3 or greater](https://wordpress.org/about/requirements/). If you're using a PHP version lower than 5.5 please upgrade your PHP version or contact your Server administrator.

= Are there any known incompatibilities? =
Yes. On a multi-language site the export will not work correctly.

= Is this plugin compatible with WPML =
Yes, but only the posts of the main language are exported. Post in secondary languages are not exported at this moment. We are working on this because we would like to have this feature but for us it isn't easy to code.

= Are there any server requirements? =
Yes. The plugin requires a PHP version 5.5 or higher and we recommend using PHP version 7.3 or higher.

= Do you make use of Export Post Info to CSV yourself? = 
Of course we do. That's why we created it. ;-)

== Changelog ==

= 1.3.0 (19/Sep/2022) =
* FIX: Remove `screen_icon` function call which is deprecated.

= 1.2.1 (19/Sep/2022) =
* Security update: Please update your plugin. We escaped data to increase your security.

= 1.2.0 (06/Sep/2022) =
* Security update: Please update your plugin. We escaped data to increase your security.
* Corrected PHP notices when debug was active.

= 1.1.0 (25/Aug/2019) =
* Now posts with status published, future and private are exported. Until now only posts with status published were exported.
* Added post status column to the export file.
* PHP version must be 5.6 or higher. Recommended PHP version: 7.3.

= 1.0.4 (08/Abr/2017) =
* Cleaned up code + Changed functions name to be unique and avoid conflicts with other plugins or themes.

= 1.0.3 (07/Abr/2017) =
* Added check for PHP version when activating the plugin. PHP version must be 5.5 o greater.

= 1.0.2 (07/Abr/2017) =
* Solved error in Word Count with words containing UTF-8 characters.

= 1.0.1 (07/Abr/2017) =
* Word count would not count all words of a post if a <!--more--> tag was present.

= 1.0.0 (07/Abr/2017) =
* First official release.

== Upgrade Notice ==

= 1.3.0 =
UPDATE: Remove `screen_icon` function call which is deprecated.

== Contact ==

For further information please send us an [email](http://apasionados.es/contacto/index.php?desde=wordpress-org-export-post-info-contact).

== Translating WordPress Plugins ==

The steps involved in translating a plugin are:

1. Run a tool over the code to produce a POT file (Portable Object Template), simply a list of all localizable text. Our plugins allready havae this POT file in the /languages/ folder.
1. Use a plain text editor or a special localization tool to generate a translation for each piece of text. This produces a PO file (Portable Object). The only difference between a POT and PO file is that the PO file contains translations.
1. Compile the PO file to produce a MO file (Machine Object), which can then be used in the theme or plugin.

In order to translate a plugin you will need a special software tool like [poEdit](http://www.poedit.net/), which is a cross-platform graphical tool that is available for Windows, Linux, and Mac OS X.

The naming of your PO and MO files is very important and must match the desired locale. The naming convention is: `language_COUNTRY.po` and plugins have an additional naming convention whereby the plugin name is added to the filename: `pluginname-fr_FR.po`

That is, the plugin name name must be the language code followed by an underscore, followed by a code for the country (in uppercase). If the encoding of the file is not UTF-8 then the encoding must be specified. 

For example:

* en_US – US English
* en_UK – UK English
* es_ES – Spanish from Spain
* fr_FR – French from France
* zh_CN – Simplified Chinese

A list of language codes can be found [here](http://en.wikipedia.org/wiki/ISO_639), and country codes can be found [here](http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2). A full list of encoding names can also be found at [IANA](http://www.iana.org/assignments/character-sets).