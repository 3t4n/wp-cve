=== linkPizza-Manager ===
Contributors: Arjan Pronk, Kizitos, gvenk
Tags: monetization, linkpizza, linkPizza, affiliate, affiliate links, automated affiliate links
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 5.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Monetize your blog - Automate your affiliate marketing, save time & earn more.

== Description ==

= What is LinkPizza? =

LinkPizza is a native advertising solution that helps bloggers and publishers monetize their content. It does this by redirecting normal links to monetizable links automatically, without any configuration needed.

= The plugin =
By signing up for LinkPizza and installing this plugin, your links are automatically monetized when linking to one of our implemented advertisers (30,000+). Our product is mainly aimed at the european market and already includes the major networks. Under your post you can see which links will be monetized and which links we can't monetize yet, disable the plugin for specific links, disable it for the entire page or maybe even category. We automatically make your links no-follow if we can change it to an affiliate link, so your days of tagging all the links one by one are over.

= Widgets =
This plugin ships with 2 link widgets for you to use, an automatic and a manual variant. You can use these link-widgets to create top lists of your advertisers or maybe your favorite shops, it's all possible. The automated widgets are lists with the best performing advertisers in our network, advertisers that offer a special incentive (click deals, guaranteed eCPC and more) or special deals. By using either of these widgets you qualify for extra benefits in our platform. You can find these in your widgets tab under appearance. Use them using shortcodes or in the visual editor

= Link Summary =
It's also possible to enable link summary, a neat feature that will automatically scan your articles and repeat the links used in a tag cloud or list at the bottom of your posts.

= Signing up =
If you want to give this plugin a try, sign up for an account over at https://linkpizza.com/signup, wait for approval and enter your WordPress-Token to get started.

= Reporting =
When you've registered your account and installed the plugin, sign in to LinkPizza.com to check your outgoing clicks and see if you installed the script correctly. The plugin doesn't include a dashboard in WordPress, but this will be implemented in a later stage.

= Commission =
You keep 70% of all the commissions, we charge 30% of the earned commission from the advertiser, in return we save you trouble of having to sign up for all the different networks and make sure your links keep working even when an advertiser switches networks or stops their affiliate program.

= Supported networks =
Currently LinkPizza support 12 networks with over 33,000 different advertisers
Some of the networks include: Awin, Daisycon, Rakuten, CJ and various independent merchants such as bol.com, eBay and Amazon

== Installation ==


1. Upload `linkPizza-Manager` directory to the `/wp-content/plugins/` directory
   or simply download it through your plugin browser
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your API token in the LinkPizza menu
4. Verify your links are changed by right clicking on an external link.

== Frequently Asked Questions ==

= How can I check if it works? =

When you've activated your account and enabled the plugin find an outgoing link ( internal links are ignored ) and right click, if you check again the url at the bottom should now look like http://pzz.to/someId

= How can I make a widget? =

You can make a widget from your widget screen, under appearance. Drag and drop LinkPizza links into one of your widget area's and enter your links. Links without a url will be ignored

= Where can I find my token? =

To get your token your account has to be approved first. After you've been approved you can find your token when you are logged in and on https://linkpizza.com/install/wordpress

= Where can I find my statistics =

Since version 4.9 you can find the statistics over the last week on your dashboard, for your full statistics see https://app.linkpizza.com/nl/affiliate/statistics

== Screenshots ==
1. See which links are monetizable while your writing your posts
2. Track your statistics per post, domain or outgoing link
3. Quickly see how much your earned over a period of time
4. Add a monetizable link widget to your website

== Changelog ==

= 5.5.3 =
* Change the minimum required PHP version to PHP7.4.
* Fix the link to the statistics page.
* Fix PHP8.x deprecation messages (Thanks @https://www.timdehoog.nl/).


= 5.5.2 =
* Fix problem that accounts are logged out after 30 days. A background process will refresh the account before it expires, Crons must be enabled for this!
* Add notification in menu when user is not logged in.


= 5.5.1 =
* Fix missing image and stylesheet for settings page.

= 5.5 =
* Fixed bug when clicking on the 'Ony track statistics...' option in the metabox. This enabled/disabled the wrong option.
* Fixed showing the correct image for monetizable links.
* Clean up source code.
* Added sanitization and escaping.
* Added dashboard for the settings.
* Added logout option.
* Added information about the plugin on the dashboard page.
* Added register button for new users.

= 5.4.1 =
* Fix a bug during token refresh

= 5.4.0 =
* Introduce new way of loging in to the plugin
* Use new LinkPizza affiliate api
* Rework conversion of database from initial upfront conversion to case by case loop conversion to avoid "Out of memory" problems
* Remove dashboard widgets
* Remove Top Links widget
* Remove mediakit shortcode
* Remove faktor.io support

= 5.3.2 =
* Added error handling for unknown fatal errors when attempting api calls

= 5.3.1 =
* Added error handling for HTML 5 entities being used by the Gutenberg editor. The plugin has been tested with Gutenberg and seems compatible

= 5.3 =
* Added support for LinkPizza's privacy partner readyGDPR cookie solution

= 5.2.5 =
* Improved compatibility with SiteOrigin Page Builder plugin

= 5.2.3 =
* Added option to put post on tracking only before date

= 5.2.2 =
* Remove unnecessary request being done when not needed
* Removed LinkPizza automatic ads button from editor

= 5.2.0 =
* Fixed tracking views for logged-in users
* Added a bulk option for users running WordPress 4.7 and higher
* Added a column to pages and post overview showing if LinkPizza is running

= 5.1.0 =
* Added new shortcode that allows you to include your LinkPizza MediaKit
* Added a widget to link to your MediaKit
* Simplified disabling LinkPizza redirects

= 5.0.7 =
* Fixed bug that broke the plugin for PHP 5.4.x and earlier

= 5.0.7 =
* Added possibility to include an indexable version of the LinkPizza script
* Fixed a bug when first entering token would not retrieve statistics

= 5.0.6 =
* Clarified error messages, if a connection fails it now returns the error code

= 5.0.5 =
* Fixed loading the LinkPizza script

= 5.0.4 =
* Fixed a bug where using W3 Total Cache could mess up statistics

= 5.0.3 =
* Fixed bug where articles without links where not shown
* Fixed a bug where invalid HTML would break the link summary

= 5.0.2 =
* You can now choose to show the text of the link or the domain.
* Don't show links in this article if there are no links
* Don't take the top-links as a link in the summary
* You can now position tags as wel

= 5.0.1 =
* small fix for refreshing statistics and top links

= 5.0.0 =
* Added shortcodes
* Added button to visual editor which inserts a LinkPizza widget
* Added a configurable link summary that you can put at the bottom of your post

= 4.9.5 =
* Made the look and feel of the top links configurable
* You can now set the height and width of your automatic link widget
* Separated the menu into several tabs

= 4.9.4 =
* Fixed a bug where if a user never saved the options a div by zero could occur

= 4.3 =
* Initial Release

= 4.9.3 =
* Fixed a bug where older versions of PHP would break because of array construction

= 4.9.2 =
* made it configurable when the top links should be shown

= 4.9.1 =
* The statistics are now rounded

= 4.9 =
* Added statistics to the plugin
* You can now advertise your best performing links in between your posts

= 4.8.1 =
* Fixed a bug where windows servers could not reach our servers
* Switched the managed widget to use both http and https if available

= 4.8 =
* The plugin now checks if the needed libraries are available for this PHP install
* Added automated ads Widget

= 4.7.8 =
* New links in the widget open in a new tab by default

= 4.7.7 =
* Fixed problems with multiple languages

= 4.7.6 =
* Added support for Link Widgets

= 4.7.3 =
* Hotfix for unreachable admin page

= 4.6 =
* Internationalized the plugin
* You can now see what links are monetized
* Oauth2
* Disable LinkPizza by category

= 4.5 =
* Fixed a bug where no links in a post would give an error

= 4.4 =
* Allow users to disable LinkPizza on pages and posts
