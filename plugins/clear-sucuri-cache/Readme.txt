=== Clear Sucuri Cache ===
Contributors: webrangers
Donate link: https://www.paypal.me/WebRangers
Tags: Sucuri, Clear, cache, cdn, lightweight, firewall, free, performance, speed, cache clear, Sucuri API
Requires at least: 3.1
Tested up to: 4.8.2
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author URI: http://webrangers.agency/

Clear Sucuri cache in one click.

== Description ==

It clears whole Sucuri cache for desired domain with one click.
Clear is done from WordPress admin panel or plugin's page. Current version contains functionality which purges individual files by URL and clears Sucuri cache after Save Post action.

THINGS YOU NEED TO KNOW:

* To make this plugin work you have to get Sucuri account first. Obviously :)

== Installation ==

1. Log into your WordPress Administrator panel (i.e., example.com/wp-admin). Click on the Plugins menu option, and select Add New.
2. Type Clear Sucuri Cache into the search input box. You are looking for the plugin that reads: Clear Sucuri Cache by WebRangers. Click Install Now.
3. The next page, if successful, will ask you if you want to proceed. Click the Activate plugin option.
3. Go to Dashboard->Clear Sucuri Cache (or directly to this URL: "http://your-wordpress-site.com/wp-admin/admin.php?page=sucuri-clear") and enter following fields:
    + Sucuri Key
    + Sucuri Secret
   You can find them in https://waf.sucuri.net/?settings&site=YOUR_DOMAIN.COM&panel=api
4. Press "Save Changes" button
5. Be happy! :-)

== Frequently Asked Questions ==

= How can I clear particular files? =

Go to your wp admin bar -> hover on Clear button -> press "Specific Files" dropdown -> deal with the modal.

== Screenshots ==

1. Plugin blank settings
2. Plugin settings filled with clear button
3. Error notification popup
4. Purge individual files modal

== Changelog ==

= 1.4 =
* Readme updated. Plugin tested with  Wordpress 3.8.2 version

= 1.3 =
* Single file/url method is improved with current sucuri API requirements. Empty url protection is added.

= 1.2 =
* bugfix with ajaxurls when your blog url is different from your domain

= 1.1.1 =
* bugfix when adding a new menu and atoclear cache option is enabled

= 1.1 =
* Single file purge modal is added
* Autopurge option is added. You now can clear whole CloudFlare cache after post is updated/saved/created

= 1.0 =
* Initial plugin release

== Upgrade Notice ==

= 1.4 =
* Readme updated. Plugin tested with  Wordpress 3.8.2 version

= 1.3 =
* Single file/url method is improved with current sucuri API requirements. Empty url protection is added.

= 1.2 =
* bugfix with ajaxurls when your blog url is different from your domain

= 1.1.1 =
* bugfix when adding a new menu and atoclear cache option is enabled

= 1.1 =
* Single file purge modal is added
* Autopurge option is added. You now can clear whole CloudFlare cache after post is updated/saved/created

= 1.0 =
Initial plugin release
