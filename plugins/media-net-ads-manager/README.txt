=== Media.net Ads Manager ===
Contributors: Media.net
Tags: ads manager, media.net, media.net ads manager, mnet ads, beta
Requires at least: 4.8
Requires PHP: 5.4
Tested up to: 5.7
Stable tag: 2.10.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The media.net ads manager provides an ability to place your ads.

== Description ==

Media.net is a leading global advertising technology company that develops innovative products for
both publishers and advertisers. It provides the full spectrum of advertising and traffic monetization
solutions to its large, diversified client base worldwide, and has one of the most comprehensive ad tech
portfolios in the industry.

Media.net Ad Manager plugin for WordPress allows you to insert Media.net Ad Code in simple, easy to
follow process. It allows you to insert the Media.net Ad Code in different types of pages:
* Article pages
* Archive pages
* Category pages
* Search pages
* Static page
* Home page
 

The Media.net Ad Manager provides an ability to place your ad on any of the pages at the following
positions:
* Above post
* Below post
* In-content
* Above Sidebar
* Below Sidebar

Note: The Media.net Ads Manager is in beta development. You may encounter cases where it might not be compatible with certain plugins. For any bug reports or issues with the plugin, please contact pubsupport@media.net


== Installation ==

This section describes how to install the plugin and get it working.

1. Login to your WordPress site.
2. From the left pane, click Plugins. The Plugins page appears.
3. Click the Add New button. The Add Plugins page appears.
4. Click the Upload Plugin button.
5. Click the Choose File button and select the mnet-ads-manager.zip file.
6. Click Open. The file name appears.
7. Click Install Now. The plugin starts installing. After the plugin is successfully installed, the Activate
Plugin button appears on the screen.
8. Click Activate Plugin to activate the plugin. The plugin is successfully activated. The Media.net Ad
Manager plugin appears in the left pane.


== Frequently Asked Questions ==
 
= How do I authenticate the plugin? =
To log in to the Media.net Ad Manager, ensure that your domain is approved at Media.net and you have the Authentication Key. You can obtain the Authentication Key for the domain from your account manager at Media.net. After you successfully authenticate your domain with the Authentication Key, you can then start using the features available in the plugin.

 
= How do I quickly configure the ad slots? =
 The Basic tab under Configuration provides you the ability to quickly configure the ad slots on the different types of pages on your site. On each page, you can add the ad slots at various positions. For example, on the Home Page, you can add the slot Above the Post Listing or Below the Post Listing.The Basic tab provides Pages on the left and the slots for the corresponding page on the right. After you select the relevant slots for the pages you require, click the Enable Ads button. This will enable the ad slots on the positions and the pages you have configured.After enabling the ads, the number of slots configured for the page is indicated at the top-left of the page name. For example, if you configure two slots for the Article Page, then the Article Page under Configuration &gt; Basic will display 2 slots. A green tick mark appears on the slot that is configured.

 
= How can I use the Enable Test Mode feature? =
 The Enable Test Mode check box allows you to enable testing on the selected ad slot. The selected ad slots will not be available on the live URL. This feature is used for testing purpose, where you can append the “debug” parameter in the page URL and preview/check the ad slot. This helps in debugging any issues you may come across.The Enable Test Mode check box is available for Basic as well as Advanced configuration. Following is an example of using the debug parameter:http://example.com/article-name/?debugorhttp://example.com/article-name/?q=cars&amp;debug

 
= How do I specify the ad size and the ad unit for the ad slot? =
 The Advanced tab under Configuration provides different options to customize the ad slot and its appearance. When you click Configuration &gt; Advanced, the page provides various options for each page and slot. The Size and Ad Unit drop-down list allows you to specify the size of the ad slot and the ad unit, respectively.

 
= How do I differentiate between the slots that are configured and the slot that are not configured? =
 You can easily differentiate and identify the slots that are already configured. In Basic configuration, the configured ad slot appears with a green tick mark on the slot. However, in Advanced configuration, the Configured label appears at the top-left of the slot name.

 
= How do I modify the margins and padding of the ad slot? =
 Margins and padding play an important role in the look and feel of the ad slot. The Advanced tab under Configuration provides the Margins and Padding options where you can specify and configure the top, right, bottom and left margins and padding. In addition, you can also specify the alignments of the ad slot on the page.

 
= Do I have an option to upload custom CSS? =
In cases where you have a custom CSS readily available, you can copy-paste the CSS code under Configuration &gt; Advanced in the Custom CSS Code text box.

 
= Can I preview the ad slot that I have configured? =
 Yes, you can preview the ad slot that you have customized and configured. In the Configuration &gt; Advanced tab, after you have selected and customized the ad slot, click the Preview button. The preview of the ad slot appears under the Preview section on the right.

 
= Why do we have the Refresh Ad Tags link? =
 The Refresh Ad Tags link refreshes the ad tags and fetches the new ad tags from the system. Ad tags that are removed will no longer be listed. In addition, slots configured with those ad tags will also be removed.

 
= Do I have an interface to view statistics of the ad slots? =
 Yes, the Dashboard provides graphs and tabular information that helps you in identifying the following:Number of active slotsRevenue for the current and previous monthDate-wise and slot-wise charts for RPM, Revenue, and ImpressionsEstimated revenueDate-wise report


== Changelog ==

= 2.10.13 =
* Bug fixes and improvements

= 2.10.12 =
* Bug fixes and improvements

= 2.10.11 =
* Bug fixes and improvements

= 2.10.10 =
* Bug fixes and improvements

= 2.10.9 =
* Bug fixes and improvements

= 2.10.8 =
* Bug fixes

= 2.10.7 =
* Bug fixes

= 2.10.6 =
* Bug fixes

= 2.10.5 =
* Bug fixes

= 2.10.4 =
* Bug fixes and date picker update.

= 2.10.3 =
* Bug fix

= 2.10.2 =
* Bug fixes

= 2.10.1 =
* Bug fix

= 2.10.0 =
* Added interface to create Ad Unit.
* Bug fixes.

= 2.9.2 =
* Bug fix

= 2.9.1 =
* Bug fix

= 2.9.0 =
* Added interface to manage ads.txt

= 2.8.6 =
* Fixed issues of tables not getting created for invalid charset.
* Bug fixes.

= 2.8.5 =
* Optimized asset chunks

= 2.8.4 =
* Asset load error bug fix

= 2.8.3 =
* Handle asset load failure
* Bug fixes

= 2.8.2 =
* Bug fixes

= 2.8.1 =
* Updated troubleshooting page

= 2.8.0 =
* Advance page: Allow directly selecting adtag if no size selected
* Advance page: Filter used ad sizes
* Inject ad code from db for placed adtags

= 2.7.16 =
* Bug fix

= 2.7.15 =
* Fix: Redirect to login page after successful login

= 2.7.12 =
* BugFix: Advance page: ref not set issue

= 2.7.11 =
* BugFix: Advance page: handle slow mount

= 2.7.10 =
* Allow configuring sidebar slots on static pages
* Bug fixes

= 2.7.9 =
* Fix session expired issue

= 2.7.8 =
* Fix script filename issue
* Fix report date-selector issue

= 2.7.7 =
* Fix chunk load failures

= 2.7.6 =
* Increase remote call timeout value
* Add IE11 support
* Add links to media.net for detailed views
* Add error fallback page

= 2.7.5 =
* Fix: Data not getting sent in remote api calls for php < 7

= 2.7.4 =
* Increase timeout for API calls

= 2.7.3 =
* Update Change log

= 2.7.2 =
* Fix syntax issue for php < 5.4 and add minimum php >= 5.4 requirement

= 2.7.1 =
* Fix: Hide login page on successful login

= 2.7.0 =
* Add admin notices feature

= 2.6.0 =
* Add show password feature
* Fix mysql 5.5 incompatibility issue
* Add hash to script files to fix caching issue on plugin update

= 2.5.0 =
* Ad blocker beacon image size reduced
* Remove styles and scripts of other plugins on mnet plugin page

= 2.4.0 =
* Move login page to client side and make scripts async to reduce initial load time
* Dynamically inject medianet script if not injected by wp_head hook
* Plugin file structure changed

= 2.3.0 =
* Added mail support for login troubles

= 2.2.2 =
* Show help for sidebar widgets

= 2.2.0 =
* Added support for external code placement

= 2.1.0 =
* Added blocking feature
* Added feedback mail option

= 2.0.0 =
* UI changed
* Added Basic / Advanced configuration options
* Added Dashboard and Reports Page

= 1.1.1 =
* Added live preview feature
* Added device visibility and styling options

= 1.1.0 =
* Ad unit placements added.

