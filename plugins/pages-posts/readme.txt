=== Pages Posts ===
Contributors: rgubby
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=rgubby%40googlemail%2ecom&lc=GB&item_name=Richard%20Gubby%20%2d%20WordPress%20plugins&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: pages, posts, posts in pages
Requires at least: 2.9.1
Tested up to: 3.0.3
Stable tag: 2.1

Amend pages and put posts inside them - either by category or tag

== Description ==

Ever wanted different pages on your blog to display different types of post? You might have news and changelog pages and want to display categorized posts in each one.

With Pages Posts you can! You can configure a page to display posts only from a specific category or tag.

== Installation ==

1. To install through WordPress Control Panel:
	* Click "Plugins", then "Add New"
	* Enter "Pages Posts" as search term and click "Search Plugins"
	* Click the "Install" link on the right hand side against "Pages Posts"
	* Click the red "Install Now" button
	* Click the "Activate Plugin" link
1. To download and install manually:
	* Upload the entire `pages-posts` folder to the `/wp-content/plugins/` directory.
	* Activate the plugin through the `Plugins` menu in WordPress.

The control panel of Pages Posts is in `Settings` (on WordPress 2.3.3 and under, `Options`).

== Frequently Asked Questions ==

= How do I configure a page to display posts? =

Head over to the settings page and the top of the page gives you the ability to add a page. Select which page you want, whether it should only display posts from either a category or a tag, select which category or tag and click "Add Page"

== Screenshots ==

1. Control Panel

== Changelog ==

= 2.1 =
* Added option to turn home page view on or off

= 2.0 =
* Added compatibility with WordPress 3.0.3
* Added number of posts option
* Set a global var to specify when the post is being used (for theme mods): $IS_PAGES_POSTS
* Now uses home page template rather than either archive.php or category.php

= 1.6 =
* Fixed style issue with pagesposts.css

= 1.5 =
* Tested compatibility with WordPress 3.0.1
* Fixed bug with current_page_item class in menu - now shows as current_page_parent

= 1.4 =
* Further fix to bring back all pages -1 rather than false in the query

= 1.3 =
* Added ability to display the original post above your categorized/tagorized posts

= 1.2 =
* Fixed bug with not being able to select all pages - was only bringing back the last 5

= 1.1 =
* Added ability to have posts displaying as excerpts or full posts

= 1.0 =
* Added category and tag display capability