=== Banhammer – Monitor Site Traffic, Block Bad Users and Bots ===

Plugin Name: Banhammer
Plugin URI: https://perishablepress.com/banhammer/
Description: Monitor traffic and ban unwanted visitors.
Tags: monitor, security, ban, block, bots
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.6
Tested up to: 6.5
Stable tag: 3.4.2
Version:    3.4.2
Requires PHP: 5.6.20
Text Domain: banhammer
Domain Path: /languages
License: GPL v2 or later

Monitor traffic and ban unwanted visitors. Block any user or IP address so they can't access your site.



== Description ==

> Banhammer: Protect your site against enemy hordes!

Banhammer gives you full control over who and what may access your site. Visit the Armory to monitor traffic and review suspicious visitors. If you find some user or bot that is causing problems, you can ban them with a click. Or, if you just want to keep an eye on someone, you can flag them with a warning. Any banned users will be denied access to your site, until you restore access via the Tower. Check out the video and screenshots to get a better idea of how it works.

https://youtu.be/0t4qBH0TuW0

> Important: Not yet compatible with WP Multisite!

**Core Features**

* Ban or Warn any WP user or IP address
* Restore access to any banned targets
* Monitor site traffic in the Armory
* Monitor logged users in the Admin Area
* Monitor all visitors on the front-end
* Manage banned targets in the Tower
* Complete Ajax-powered navigation
* Useful tools like jump, sort, search
* Complete documentation via Help tab
* Automatically clear logged data
* Sound effects for Ban, Warn, et al
* NEW: manually block any IP address

**Options Galore**

* Optionally ignore logged-in users
* Optionally protect Login Page and Admin Area
* Customize the banned response and status code
* Display banned message or redirect the request
* Choose the interval to clear logged data
* One-click restore plugin default options
* All collected data may be deleted easily

**More Features**

* Easy to use
* Clean code
* Fast and secure
* Built with WP API
* Lightweight and flexible
* Regularly updated and "future proof"
* Works great with any WordPress theme
* Comprehensive search of all logged data
* Works great with other WordPress plugins
* Works with or without Gutenberg Block Editor
* Focused on usability, performance, and security

Banhammer is perfect for site owners, admins, and developers who want to keep an eye on traffic and block any unwanted visitors. It is a simple, flexible, and powerful security solution. Perfect for the best WordPress sites.

For complete documentation, visit the Help tab on any Banhammer screen.

_+ [Banhammer Pro now available&nbsp;&raquo;](https://plugin-planet.com/banhammer-pro/)_


**Privacy**

__User Data:__ Banhammer collects user data to "do its thing". The collected data is temporary and automatically deleted every day, or at whatever time interval is specified in the plugin settings. The only time that any data is "remembered" is when you ban something. For each person/thing that you ban, the plugin stores either the IP address OR the username (never both). At any time, all saved data may be deleted permanently via the plugin settings and Armory Tools. 

__Cookies:__ Banhammer does not set any cookies for regular visitors, but does set a few simple cookies for admin-level users. These simple cookies enable dope effects and interactivity in the Armory and Tower UI. But no cookies are set or used for any other visitor/user or purpose. 

__Services:__ Banhammer uses a free lookup service for GeoIP information. This happens only for admin-level users when they are viewing data in the Armory or Tower. No other third-party services are used by this plugin.

Banhammer is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).


**Support development**

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)
* [Wizard's SQL Recipes for WordPress](https://books.perishablepress.com/downloads/wizards-collection-sql-recipes-wordpress/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect WordPress to Google Analytics
* [Simple Ajax Chat Pro](https://plugin-planet.com/simple-ajax-chat-pro/) - Unlimited chat rooms
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thank you! :)



== Screenshots ==

1. Banhammer Settings (showing default options)
2. Banhammer Armory (showing basic view)
3. Banhammer Armory (showing advanced view)
4. Banhammer Armory (showing more tools)
5. Banhammer Tower
6. Default banned message (can customize via settings)
7. Help tabs! (available on all Banhammer screens)



== Installation ==

**Important: PHP Requirement**

Before installing, make sure your server has either `cURL` or `file_get_contents()` enabled. Banhammer requires at least one of these functions to do its thing.

**Install Banhammer**

1. Upload the plugin and activate
2. Configure the plugin settings as desired
3. Visit the Armory to monitor traffic and ban/warn any unwanted visitors
4. Visit the Tower to manage any banned/warned targets

[More info on installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)


**Caching Plugins**

Banhammer works with any type of caching plugin where "page caching" is not enabled.

There are many types of cache plugins. They provide all sorts of different caching mechanisms and features. All caching features work great with Banhammer except for “page caching”. With page caching, the required WP `init` hook may not be fired, which means that plugins like Banhammer are not able to log and ban requests dynamically. Fortunately, some of the most popular caching plugins provide settings that enable full compatibility with Banhammer. For a complete list, check out [this article](https://plugin-planet.com/blackhole-pro-cache-plugins/). Note: that article was written for [Blackhole Pro](https://plugin-planet.com/blackhole-pro/), but the compatibility list and general info apply also to Banhammer.


**Use Banhammer**

Banhammer enables you to monitor traffic and ban any user or bot. To view your site's traffic, visit the Armory. There you can ban or warn (flag) anything you wish. Once you have banned something, it will be locked in the Tower, where you can manage all banned users and bots.

Banhammer is designed to be as intuitive as possible, and provides complete documentation via the Help tab on any Banhammer screen. There are three plugin screens:

* Settings - configure options
* Armory   - monitor site traffic
* Tower    - manage banned visitors

So after configuring options, visit the Armory to monitor site traffic. If you see a visitor that should be banned, click the hammer button to ban them immediately. Or, if you just want to keep an eye on someone, click the horn button to issue a warning. After banning or warning your target, you can visit the Tower to manage as desired. There you can ban, warn, restore, or delete any target with a click.

For complete documentation, visit the Help tab of any Banhammer screen. If anything is unclear or if you find a bug, you can [drop a line](https://plugin-planet.com/support/#contact) via my contact form. 

_+ [Check out Banhammer Pro&nbsp;&raquo;](https://plugin-planet.com/banhammer-pro/)_


**With great power..**

Please be careful not to ban any important IP addresses. Before banning some target, verify the IP and host name. Verifying the IP address is important because you do not want to accidentally ban major search engines and services. A good way to verify any IP address is to do a reverse lookup. The result should match the host name. For an example of how to verify a bot, check out [this article](https://perishablepress.com/spoofed-search-engine-bot/) at Perishable Press.

_Pro Tip: In the Armory, you can click on the IP Address or Host Name to do a quick whois lookup._


**Important! Don’t ban yourself!**

Please be careful not to ban yourself when using Banhammer. The Basic Settings are powerful; use them wisely. Here are some things that can help mitigate any accidents:

* Be mindful when monitoring traffic; always know your own IP address and WP username.
* Disable the setting "Login Page", so you always have access to the Login Page.
* Enable the setting "Ignore Users", so you always can access the Tower, and your own visits will not be logged in the Armory.


**Whoops! How do I get back in?**

It’s almost inevitable. Worst-case scenario say you accidentally ban yourself. As site admin, it is easy to restore access. Follow these steps:

1. [Download the Banhammer Unlock plugin](https://plugin-planet.com/wp/addons/banhammer-unlock.zip)
2. Upload the Unlock plugin to your server at: `/wp-content/mu-plugins/`
3. If the mu-plugins directory does not exist, go ahead and create it
4. After uploading the plugin, Banhammer will be disabled, so you can log in and restore access via the Tower
5. Once you have restored access, delete the Banhammer Unlock plugin from the server
6. After deleting the Unlock plugin, Banhammer once again will be enabled

Alternately, if you banned yourself by IP address, you can bypass the ban by using a trustworthy proxy service to log in to your site.


**Testing**

How do you know if the plugin is working? Like if you want to customize the banned response? Well, there are several ways to go about it.

Method One (easiest): Configure the following Banhammer settings:

* Enable Plugin - enable
* Ignore Users  - disable
* Login Page    - disable
* Admin Area    - disable

After saving the changes, you will be able to ban your own visits to the front-end (non-admin) pages on your site, without actually banning yourself from the Admin Area or Login Page. Just remember to restore access via the Tower when you are finished testing.

Method Two (moderate): Create a new WordPress user and log in using a second browser. Then as you surf around the site, you can monitor and ban the user via the first browser.

Method Three (advanced): Open two browser tabs. Tab 1 is the Armory. Tab 2 is a good proxy service. With Banhammer enabled, visit your site's homepage via proxy. Then jump over to the Armory and ban the proxy IP address. Then retry the proxy visit to the homepage; it should be denied access. Remember to restore access or delete the banned IP via the Tower when finished testing.


**Manually Add IP Address**

If you want to ban some IP that has not yet visited your site, you can do so by entering the following URL in your browser's address bar:

`https://example.com/wp-admin/?banhammer-key=[KEY]&banhammer-ip=[IP]`

Replace the following:

* Replace [KEY] with your "Target Key"
* Replace [IP] with the IP you want to block
* Replace `example.com` with your own domain

Note: You can find your Target Key in Banhammer Advanced settings. 

For more info about adding targets, visit the Help tab on the Settings page. 

_Important! Never share your Target Key, always keep it secret._


**Auto-Clear Data**

To prevent collected data from filling up the database, Banhammer automatically clears all Armory data at regular intervals. By default, the interval is 24 hours. So every 24 hours, the Armory data will be flushed, and fresh data will be collected. Of course, any banned targets will remain banned and available in the Tower. To change the auto-clear interval, check out the "Reset Armory" setting. Visit the Armory Help tab for more details.


**Performance Tip!**

The first time a logged entry is displayed in the Armory, additional data are fetched behind the scenes. So as you navigate pages, you may notice that pages containing new entries take a bit more time to load. Subsequent views should be nice and speedy via Ajax. So with that in mind, it is optimal for performance to keep the number of items per page to a minimum. Try to keep it anywhere under 10 or so and you should be good. To change the number of entries displayed per page, click "Tools" and go to "Display [x] rows".


**Basic View vs. Advanced View**

Under the Tools menu you can toggle between "Basic view" and "Advanced view". Basic view gives you a streamlined summary. Advanced view gives you complete data for each entry. Note that you can toggle each entry individually between Basic and Advanced. So for example, you can monitor traffic in Basic view, and then toggle open (double-click) any entry that may need banning. The default is Advanced view.


**Sound Effects!**

Banhammer sound effects can be enabled by clicking "Tools" and then "Enable sound fx". When enabled, the sounds will be played whenever an action button is clicked. This includes the Ban, Warn, Restore, and Delete buttons. The Armory provides Ban and Warn buttons. The Tower provides all four. Note that enabling sound fx in the Armory applies to the Tower as well.

Note that the sound effects are a work in progress. Finding quality open source audio is challenging. If you are able to contribute better effects, please let me know. And of course, the sound effects can be disabled entirely by clicking "Disable sound fx".


**License for Sound Effects**

Audio used in plugin

* [Explosion sound by steveygos93](https://freesound.org/s/80401/):     [Attribution 3.0 Unported (CC BY 3.0)](https://creativecommons.org/licenses/by/3.0/)
* [Inception Horn sound by Kubatko](https://freesound.org/s/196584/):   [CC0 1.0 Universal (CC0 1.0)](https://creativecommons.org/publicdomain/zero/1.0/)
* [Shield Guard sound by nekoninja](https://freesound.org/s/370203/):   [CC0 1.0 Universal (CC0 1.0)](https://creativecommons.org/publicdomain/zero/1.0/)
* [Lettuce Chopping sound by danloss](https://freesound.org/s/412531/): [CC0 1.0 Universal (CC0 1.0)](https://creativecommons.org/publicdomain/zero/1.0/)

Audio used in promos

* [War Drum Loop by limetoe](https://freesound.org/s/274223/): [CC0 1.0 Universal (CC0 1.0)](https://creativecommons.org/publicdomain/zero/1.0/)
* [Warrior Drums by Sclolex](https://freesound.org/s/209546/): [CC0 1.0 Universal (CC0 1.0)](https://creativecommons.org/publicdomain/zero/1.0/)


**Uninstalling**

This plugin cleans up after itself. All plugin options and collected data will be removed from your database when the plugin is uninstalled via the Plugins screen.


**Like the plugin?**

If you like Banhammer, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/banhammer/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



== Upgrade Notice ==

To upgrade this plugin, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

Note: uninstalling the plugin from the WP Plugins screen results in the removal of all options and data from the WordPress database. 



== Frequently Asked Questions ==

**How is this different than the other "Ban Hammer" plugin?**

The plugin [Ban Hammer](https://wordpress.org/plugins/ban-hammer/) by Mika Epstein ([Ipstenu](https://profiles.wordpress.org/ipstenu)) is the original "ban-hammer" plugin. It is a great plugin that prevents unwanted users from registering with your site. My plugin [Banhammer](https://wordpress.org/plugins/banhammer/) monitors traffic and enables you to ban any unwanted WP users or IP addresses. They are similar in effect, but focus on different aspects of site access. Btw, huge Thank You to Mika for being so awesome with sharing the "ban hammer" space :)


**Will this plugin slow down my site?**

No, Banhammer is developed with a focus on performance, so your site will be as fast as possible. For example, Banhammer does all of its "heavy lifting" of data only when the admin is viewing the Armory, so normal site traffic remains as fast as possible. This is why the first pass thru the Armory data can take a second or two longer than subsequent passes; Banhammer is looking up Geo/IP information, hostname information, and also performing other important tasks only when the admin is viewing the Armory.


**I can't change the Row Limit setting?**

To change the Row Limit, enter a number and then press the Enter key on your keyboard. For more information about Row Limits, click the Help tab on the plugin settings page.


**What are Protect Login Page and Admin Area options?**

In the Banhammer settings, there are two options:

* Login Page - Protect WP Login Page
* Admin Area - Protect WP Admin Area

When enabled these options tell Banhammer to include the Login Page and Admin Area, respectively. It means that Banhammer will monitor requests made to the Login Page and Admin Area, and block anything that you've told it to block. Otherwise only the frontend pages are protected. Conversely, when these options are disabled, Banhammer will ignore the Login Page and/or Admin Area. 

Further explanation: with WordPress, there is the frontend (like homepage, posts and pages, etc.). Then there also are all the admin-related pages, commonly referred to as the Admin Area. This is what is covered by the "Admin Area" option. Likewise with the Login Page, located at `/wp-login.php`, that is what the "Login Page" option refers to.


**Got a question?**

Send any questions or feedback via my [contact form](https://plugin-planet.com/support/#contact)



== Changelog ==

If you like Banhammer, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/banhammer/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**3.4.2 (2024/03/06)**

* Updates plugin settings page
* Updates default translation template
* Improves plugin docs/readme.txt
* Tests on WordPress 6.5 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/banhammer.txt](https://plugin-planet.com/wp/changelog/banhammer.txt)
