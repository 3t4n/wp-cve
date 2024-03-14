=== RSS Just Better ===
Contributors: stefsoton
Donate link: http://www.stefonthenet.com/donations/
Tags: RSS, rss, Atom, feed, feeds, XML, syndication, syndicate, syndicating, news, widget, shortcode
Author URI: http://www.stefonthenet.com
Plugin URI: http://www.stefonthenet.com/rss-just-better-plugin-for-wordpress/
Requires at least: 2.8
Tested up to: 4.3.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Displays a list of RSS/Atom feed items given the feed URL and other parameters (optionals). Highly customizable.

== Description ==
It displays a list of feed items from a given feed URL. You can select cache recreation frequency, the number of displayable news and whether you want publication date, time (and in which format), an excerpt and reference to the plugin homepage displayed or not. You can also choose to have a ordered (numbered) or unordered (bullet-point) list and to open a new windows or not, when clicking on the linked items. You can truncate title and/or excerpt to a certain amount of chars and can sort the itmes by date/time or title.

== Installation ==
1. The easiest way is: from Plugins of your wordpress administration, select "Add new", search for "RSS just better" into the search text-box then click on 'Install' (on the right) when prompted with this plugin
                                    OR
you can download this plugin from [wordpress repository](http://wordpress.org/extend/plugins/RSS-just-better/), unzip it (a directory with 5 files will be extracted) and upload it to the '/wp-content/plugins/' directory of your wordpress administration.

2. Activate the plugin through the Plugins->Installed Plugins menu in your WordPress administration

3. Use it! (see Usage in Other Notes)

== Frequently Asked Questions ==
= Why the heck would I need/want it?
= RSS Just Better provides the latest <n> items of any rss or atom feed on the Internet: can you imagine what traffic boost for your blog or website? 
Do you want to know more about RSS or Atom feeds? 
Check wikipedia: http://en.wikipedia.org/wiki/Web_feed

= Why is Better to the RSS plugin provided by wordpress
= Hey, I cannot find any configuration page in 'Settings'! How do I use it?
= As a widget, you drag & drop the widget to any widget-ready area of your theme; open and complete the widget form and click on the 'Save' button. Close the widget if you want. Check your wp site now.

As a shortcode, choose the post or page you want your feed items to be displayed into, selecte HTML mode (IMPORTANT!) - and enter your shortcode as explained in Usage (in Other Notes).  

== Screenshots ==
1. Widget Form with default values
2. Shortcode with all the attributes in their default values

== Changelog ==
= 1.4
* Change: Change reference website which is now http://stefonthenet.com.

= 1.3
* New Feature: Possible to disable the reverse chronological order established by default. Normally, this results in a chronological order but not always. I.e. Google news will not be chronological.
* New Feature: Added "Serbia" to the list of Google News countries
* New Feature: Added "More Top News" to the list of Google News topics
* Change: Added more comments to code
* Change: Google News attributes for shortcode are now the same as for widget variables (to avoid confusion) 
* Bug Fix: Non-latin alphabeths and non-standard chars (Google news locations) are now displayed properly
* Bug Fix: Image URL for rss icon in shortcode is now correct
* Bug Fix: Standard Google News feed URL has been changed (by Google)
* Bug Fix: Location as null string does not deafult to usa (??) (Google news)
* Bug Fix: urlencoding text for Google News searches

= 1.2 (never released) =
* Change: If no feed item is returned, a message specifying how many items were requested is displayed
* Bug Fix: double quote works but do not show up
* Bug Fix: Local field/attribute (for Us and Ca only in English) did not work (var geo was local instead)
* Bug Fix: Feed linked to title did not work when feed was Google News feed. 

= 1.1 =
* Bug Fix: Readme file was empty
* Bug Fix: plugin version in footer was 0.9 instead of 1.0 (now is 1.1)

= 1.0 =
* New Feature/Bug Fix: Date (weekdays & months) are now "localized" (=displayed in the language your wordpress is installed)
* New Feature: Given the possibility of displaying only items published by a given amount of hours (for infrequently updated feeds, * New Feature: Given the possibility of displaying Google News feeds given the new param: localization, topic or search keyword(s), local (for Us & Ca news only) it avoids showing always the same old items for a long time).
* Change: the preview of the excerpt on mouse-over is disabled if the excerpt is ticked-off (widget)/set to TRUE (shortcode)
* Change: improved documentation about n. of items to be displayed: reminded that setting a value of 0 will return the total number of items in the feed.
* Bug Fix: fixed path to rss image from '../RSS-just-better/..' to '../rss-just-better/..'  (thanks Mike)

= 0.9 (UNRELEASED) =
* New Feature/Bug Fix: Date (weekdays & months) are now "localized" (=displayed in the language your wordpress is installed)

= 0.8 =
* New Feature: Being able to truncate/limit the title to <n> (given) chars
* New Feature: Being able to choose the date format
* New Feature: Being able to choose the time format
* New Feature: Being able to sort the feed items by title (instead of the default date/time)
* Change: "Link to author" in widget form becomes "Link to the Plugin Homepage"
* Change: the popup window showing on mouseover title is limited to 400 char
* Change: optimized function to retrieve feed and format list
* Change: Changed error message when feed URL is not right: its more likely a temp fault of the hosting server (especially if it has already worked before than not a malformed feed
* Bug Fix: fixed the plugin homepage URI in readme.txt file

= 0.7 = 
* Bug Fix: The [...] in the excerpt linked to the description and not to the URL of the linked item/article [Thx ]
* Bug Fix: "Num. of News" in widget form becomes "No. of Items"
* Bug Fix: Fixed the shortcode example in readme.txt as contained syntax errors [Thx Wayne] 
* Bug Fix: The strings for titles and excerpts were not properly formatted for display on screen [Thanks Mary Ann]
* Change: Improved documentation for page Other Notes

= 0.6 =
* New feature: the user is able to set the cache refresh time both in the widget form and the shortcode
* Change: Replaced old email address (mywizardwebs@) with new (stefonthenet@)
* Change: Added the plugin version number in the "Powered from" line at the botton
* Change: Author URI, Plugin URI added to readme.txt file
* Change: Improved description of sample shortcode
* Fixed bug: Boolean variables were not properly tested and caused some inconsistent views when the plugin was used as a shortcode

= 0.5 =
* Added attribute "filter" to include or exclude given words
* Changed footer's author link to the plugin homepage
* Set attribute Charex default value to 150 so that if excerpt is true, an excerpt is automatically displayed (for 150 chars).
* Changed attribute num default value to 5
* Enclosed shortcode in a class (it seems attribute feed collided with wordpress since 3.2)

= 0.4 =
* Made Date & Time formats (when selected) as those defined in wordpress settings page
* Made linkable title opening in new/same window according to widget selection
* Added attributed "title" and "link" to shortcodes too
* Made link for title as an image to an RSS cube 
* Used fetch_feed as feed farser function (the other was not compatible with all the PHP server installations)
* Made code DHTML compatible (hopefully)

= 0.3 =
* Made it fully compatible with Atom 1.0
* Added proper error messages for: empty, non-existing URLs and invalid/misformatted feeds content
* Added defaults for target and list (widget fields)
* Added instruction to hide Warnings from the xmlsimple_load_file function
* Fixed a bug which prevented proper display of description/summary with tags

= 0.2 =
* Fixed a bug which prevented the shortcode to show feeds from URLs containing & and =
* Made it fully compatible with RSS version 0.92, 2.0, 2.0.1 where link and title might be omitted
* Made the widget title linkable to the feed URL
* Used the native PHP class simpleXML instead of the Wordpress feed parser (Magpie)

= 0.1 =
* First release. Originating from google-news-widget plugin

== Upgrade Notice ==
= 0.5 =
* Added attributed "filter" to include or exclude the given words

= 0.4 =
* Made Date & Time formats (when selected) as those defined in wordpress settings page
* Made linkable title opening in new/same window according to widget selection
* Added attributed "title" and "link" to shortcodes too
* Made link for title as an image to an RSS cube 
* Used fetch_feed as feed farser function (the other was not compatible with all the PHP server installations)
* Made code DHTML compatible (hopefully)

= 0.3 =
* Made it fully compatible with Atom 1.0
* Added proper error messages for: empty, non-existing URLs and invalid/misformatted feeds content

= 0.2 =
* Fixed a bug which prevented the shortcode to show feeds from URLs containing & and =
* Made it fully compatible with RSS version 0.92, 2.0, 2.0.1 where link and title might be omitted
* Made the widget title linkable to the feed URL

= 0.1 =
* First release. Originating from google-news-widget plugin

== Usage ==
= Usage as a Shortcode =
* In Posts/Pages->Add New or Edit of your wp adminstration, select the HTML tab in the entry form;
* enter either the following (if you want to display a generic feed):
[RSSjb feed="replace-with-the-rss-or-atom-feed-URL-you-wish-to-display"] 
or the following (if you want to display a Google News feed):
[RSSjb location="replace-with-the-Google-s-location-code-of-the-country-language-you-want-news-from"] 
A list of the [Google localization codes](http://www.stefonthenet.com/2010/02/21/google-news-localization-codes/) is here.
Optional attributes for Google News feeds are the following:
* local: city, state/province or zipcode (of Usa or Canada news and in English only)
* gsearch: search-words according to google search syntax. Learn a few [Tips](http://www.stefonthenet.com/googles-search-operators/) about Google search (default: none); (See note 4)
* topic: any of Google's topic-codes (default: Top Stories). Here is a [list of topic codes](http://www.stefonthenet.com/2010/02/21/google-news-topic-codes/). (See also notes 3 and 4)
Other Optional attributes:
* filter: enter any keyword which needs to be present or avoided in the titles of the choosen feed's items. (See Note 1)
* num: max number of items to be displayed, when all present in the feed (default: 5. If you enter 0 all the items in the feed will be displayed) (See Note 2);
* ltime: max age (in hours from publication) of item as a condition for displaying it (default: none)
* list: either "ul" or "ol" to get unordered or ordered lists (default: "ul");
* target: either "_blank" or "_self" to get links opened in a new or the same window (default: "_blank");
* pubdate: either true or false to display the publication date/not (default: false);
* pubtime: either true or false to display the publication time/not (default: false);
* dformat: customized date format (default: none. None displays the wp standard date format if pubdate is true);
* tformat: customized time format (default: none. None displays the wp standard time format if pubtime is true);
* pubauthor: true/false whether you allow this plugin homepage to be displayed in the footer or not (please, say yes) (default: true);
* excerpt: either true or false to display the excerpt/not (default: false);
* charex: limit the number of the ecerpt chars to be displayed (default: none = all chars will be displayed);
* chartle: limit the number of the title chars to be displayed (default: none = all chars will be displayed);
* title: title for the items list (default: none);
* link: either true or false if the title is linked to the RSS/Atom feed URL/not (default: false);
* sort: either true to display the list in alphabetic order (by title) or false to display in reverse date/time order (default: false);
* cachefeed: cache refresh for the feed (in seconds) (default: 3600 => 1h);

= Usage As a widget =
* in Appearance -> Widgets of your wp administration, drag & drop the RSS Just Better widget to any widget-ready area of your wp;
* Now, complete the widget form: 
** Enter a title to be given to your items list;
** Choose whether you want the widget title linkable to the feed URL or not;
** Enter the RSS/Atom feed URL of the items you wish to display OR
** Enter the location of the Google News feed items you wish to display;
** Enter topic or search keys, local (all optionals) if you chose a Google News feed;
** Enter the frequency for the cache refresh (in seconds);
** Choose if you want your list sorted by title instead of the standard date/time;
** Enter any keyword which needs to be present or excluded in the titles of the choosen feed items. (See Note 1)
** Enter the max number of items you want to display (when available) (See Note 2). If you enter 0 all the items in the feed will be displayed;
** Enter the max age (in hours from publishing) an item needs to have to be displayed. Useful for less frequently updated feeds;
** Enter a certain amount of chars, if you want to truncate the titles;
** Choose whether you want a publication date/time (and what formats), excerpt (and how many chars of it) or not;
** Select whether you want a dotted list (default) or a numbered/ordered list;
** Select whether you want the linked items to open up in a new page (default) or in the same page;
** Choose whether you allow this plugin homepage to be displayed or not (please, say yes);
** Click on 'Save' (and 'Close' the widget form, if you want).
(1) Notes on 'filter' attribute/parameter:
1. You can enter one of more words to be able to select your feed's items by keyword. So if you write "foo bar" you will INCLUDE ONLY those titles where ANY of the two word(s) is present and if you write "-foo -bar" you will EXCLUDE ALL those titles where any of the two words is present instead.
2. You can also mix inclusive and exclusive terms as in "foo -bar" of course (it will include only titles with "foo" and without "bar".)
3. Search is case insensitive and searches for keywords in the titles only.
4. Wildshars, quotes and boolean are not enabled for this search.
(2) Note on 'num' attribute/parameter:
The maximum number of displayable articles/items depends on the number of articles stored into the XML page (RSS/Atom feed page) of the website you want to syndicate (i.e. if you wish to display the latest 15 items and the original feed contains 10 items only, then only 10 items will be displayed).
(3) Note on 'topic' attribute/parameter:
Not all topics are set for all countries/languages.  If you select a topic for a country where this is not provided (as yet?) then the "Top stories" (the default) will be displayed instead.
(4) Note on 'topic' & 'gsearch' attributes/parameters:
Google (not me!) allows to search by topic OR by search-word(s). The two "filters" do not work together: if topic AND search-keys are both entered by the user, then the search-keys will be ignored and no error message will be displayed.
(5) Note on 'Local' attribute/parameter:
This option is available for Google News in English limited to Usa and Canada. It allows to enter city, region or postcode for local news results only.
Example of shortcode with all attributes and their default values for a generic feed URL: 
[RSSjb feed="http://feeds.feedburner.com/StefaniasBlog" filter="" num="5" ltime="" list="ul" target="_blank" pubdate="false" pubtime="false" dformat="" tformat="" pubauthor="true" excerpt="false" charex="150" title="" link="false" sort="false" cachefeed="3600"]
Example of shortcode with all attributes and their default values for a Google News feed URL: 
[RSSjb location="us" local="" topic="" gsearch="" filter="" num="5" ltime="" list="ul" target="_blank" pubdate="false" pubtime="false" dformat="" tformat="" pubauthor="true" excerpt="false" charex="150" title="" link="false" sort="false" cachefeed="3600"]

== The Future ==
* being able to view images (and media files) too
* more feeds for more websites
* filter by keywords in description too (new attribute)

== Interaction ==
* Would you like to see a new feature in this plugin? Please write me here: stefonthenet@gmail.com;
* Would you like to see a broken/orphan plugin working again? Write me anyhow, I might (hey, MIGHT) find the time to give it a look.