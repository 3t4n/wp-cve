=== Auto Clean URL for SEO ===
Contributors: apasionados, netconsulting
Donate link: http://apasionados.es/
Tags: url, slug, stop words, seo stop words, bing, google, search engine optimization, seo, seo pack, wordpress seo, yahoo, automatic seo, automation, marketing strategy, seo content, seo correction, seo meta, seo optimization, seo plugin, seo title, title
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Auto Clean URL for SEO removes STOP WORDS from the WordPress Slugs (URLs) in ENGLISH, SPANISH, GERMAN and FRENCH.

== Description ==

This plugin removes STOP WORDS from the WordPress Slugs in ENGLISH, SPANISH, GERMAN and FRENCH.

For all languages it removes HTML entities and anything that is not a letter, digit, space or apostrophe.

Please note that the plugin should not be used together with QTRANSLATE, WPML or POLYLANG. In case you have a multi-language blog, this plugin doesn't work correctly.

**Yoast WordPress SEO has added a similar functionality to the one of this plugin in version 1.4.16** ("Add option to enable slug stop word cleaning") which is on by default but removed it in version 7.0.0 (Release Date: March 6th, 2018). If you use our plugin together with Yoast Wordpress SEO in versions older than 7.0.0, please turn the WordPress SEO functionality off (find it under SEO -> Permalinks).

>**Yoast WordPress SEO** acts on the WordPress filter "name_save_pre" which means that the Slug is modified when saved as draft the first time or when published (only if it hasn't been saved as draft before). This means that when the slug is first created from the Title of the post the Stop words and HTML entities are not removed at this moment.

>**Our plugin** acts on the WordPress filter "name_save_pre" and on the AJAX action "wp_ajax_sample-permalink" which fires when the Slug is created for the first time. **This means that with our plugin the slug is created without stop words in real time**. After that you can edit the slug and it will not be modified again by the plugin after it has been edited.

= What can I do with this plugin? =

This plugin automatically removes STOP WORDS from the WordPress Slugs in ENGLISH, SPANISH, GERMAN and FRENCH to enforce your SEO efforts.

= Where did you get the STOP WORD list from? =

The stop words were taken from the great resource of [Ranks.nl/StopWords](http://www.ranks.nl/stopwords/ "Ranks.nl/StopWords").

= Auto Clean URL for SEO Plugin in your Language! =
This first release is avaliable in English and Spanish. In the languages folder we have included the necessarry files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](http://apasionados.es/contacto/index.php?desde=wordpress-org-autocleanseo-home).

* Translation to Serbo-Croatian language sr_RS by [Borisa Djuraskovic](http://www.webhostinghub.com).


= Further Reading =
You can access the description of the plugin in Spanish at: [Auto Clean URL SEO en castellano](http://apasionados.es/blog/optimizar-urls-wordpress-seo-plugin-wordpress-1925/).


== Installation ==

1. Upload the `auto-clean-url-seo` folder to the `/wp-content/plugins/` directory (or to the directory where your WordPress plugins are located)
1. Activate the Auto Clean Url SEO plugin through the 'Plugins' menu in WordPress.
1. Puling doesn't need any configuration.

Please note that the plugin should not be used together with QTRANSLATE, WPML or POLYLANG. In case you have a multi-language blog, this plugin doesn't work correctly.


== Frequently Asked Questions ==

= What is AUTO CLEAN URL SEO good for? =
The main functionality is for Spanish, German, English and French websites, where it removes STOP WORDS from the WordPress Slugs (URLs). This means that all words which are not necesary for the search engines are removed, helping your SEO.

For all languages it removes HTML entities and anything that is not a letter, digit, space or apostrophe.

= Does AUTO CLEAN URL SEO make changes to the database? =
No.

= How can I check out if the plugin works for me? =
Install and activate. Create a new post and see the magic happen when WordPress makes the slug after writing the title.

= How can I remove AUTO CLEAN URL SEO? =
You can simply activate, deactivate or delete it in your plugin management section.

= Are there any known incompatibilities? =
In case you have a multi-language blog, this plugin doesn't work correctly. The plugin should not be used together with QTRANSLATE or WPML. Polylang conflicts with the change of the slug when the post is a draft.
Yoast WordPress SEO has added the functionality of this plugin in version 1.4.16 ("Add option to enable slug stop word cleaning") which is on by default but removed it in version 7.0.0 (Release Date: March 6th, 2018). If you use our plugin together with Yoast Wordpress SEO older than version 7.0.0, please turn the WordPress SEO functionality off (find it under SEO -> Permalinks).

= Are you planning to continue development ? =
Maybe. We are planning to include one configurable parameter to remove/hide the slug option, so that non administrators can't change the slug. But we are not sure if this is a feature we or our customers need. 

= Do you make use of AUTO CLEAN URL SEO yourself? = 
Of course we do. ;-)


== Screenshots ==

1. There is no configuration screen for "Auto Clean URL SEO". This is an example of a clean URL without Stop Words (in Spanish).


== Changelog ==

= 1.6 =
* Solved problem with saving of menus. It seems that when you save a menu the hook "name_save_pre" is also executed. As there is no defined "post_title" an error was shown when debug is active.

= 1.5 =
* Made some changes to add compatibility with PHP 7.0: Replaced deprecated split() function with explode().

= 1.4 =
* Added action to update slug directly when created automatically by Ajax so that the user doesn't have to save as draft or publish to clean up the URL.

= 1.3.1 =
* Added translation.

= 1.3 =
* Updated and corrected readme.txt.

= 1.2 =
* Added French stop words.

= 1.1 =
* Updated and corrected readme.txt.

= 1.0 =
* First stable release.

= 0.5 =
* Beta release.

== Upgrade Notice ==

= 1.5 =
* Solved problem with saving of menus.

== Contact ==

For further information please send us an [email](http://apasionados.es/contacto/index.php?desde=wordpress-org-autocleanseo-contact).


== Translating WordPress Plugins ==

The steps involved in translating a plugin are:

1. Run a tool over the code to produce a POT file (Portable Object Template), simply a list of all localizable text. Our plugins allready havae this POT file in the /languages/ folder.
1. Use a plain text editor or a special localization tool to generate a translation for each piece of text. This produces a PO file (Portable Object). The only difference between a POT and PO file is that the PO file contains translations.
1. Compile the PO file to produce a MO file (Machine Object), which can then be used in the theme or plugin.

In order to translate a plugin you will need a special software tool like [poEdit](http://www.poedit.net/), which is a cross-platform graphical tool that is available for Windows, Linux, and Mac OS X.

The naming of your PO and MO files is very important and must match the desired locale. The naming convention is: `language_COUNTRY.po` and plugins have an additional naming convention whereby the plugin name is added to the filename: `pluginname-fr_FR.po`

That is, the plugin name name must be the language code followed by an underscore, followed by a code for the country (in uppercase). If the encoding of the file is not UTF-8 then the encoding must be specified. 

For example:

* en_US for US English
* en_UK for UK English
* es_ES for Spanish from Spain
* fr_FR for French from France
* zh_CN for Simplified Chinese

A list of language codes can be found [here](http://en.wikipedia.org/wiki/ISO_639), and country codes can be found [here](http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2). A full list of encoding names can also be found at [IANA](http://www.iana.org/assignments/character-sets).
