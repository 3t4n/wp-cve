=== Bot Block - Stop Spam Referrals in Google Analytics ===
Contributors: ThisWebGuy
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: seo, SEO, google, google analytics, google analytics spam, spam, bot block, bot blocker, bot blocking, block bots, semalt, 100dollarsseo, analytics spam, spam crawler, crawler spam
Requires at least: 3.9
Tested up to: 4.4.2
Stable tag: 2.6

Block spam referrals showing in Google Analytics and save bandwidth. Central database of sites, ability to add custom URL's and stats.

== Description ==

This plugin has two main functions:

1. To stop spam traffic before it reaches your site (which stops spam visits showing in Analytics).
2. Save bandwidth, bots use bandwidth when they visit your site, this plugin stops them before they get the chance to download anything.

Spam traffic is an increasing problem, companies like **semalt** are spamming sites in order to get their website shown within Google Analytics as a referrer (also known as referral spam). More spammers are beginning to take this approach and this causes issues for any Analytics user that relies on the data collected since pageviews and visitors are inflated.

Spammers have even taken this a step further by triggering events, which of course can effect conversion data.

This plugin is built to stop this, it blocks spam bots before your website loads which not only stops the traffic appearing within Google Analytics but it also saves you Bandwidth.
The spam bots blocked are pulled from a central database of known bots, this database is updated as new spam bots are found, you also have the option to add your own referrer URL's to block.

### Features Include:
> * Central database containing tons of known spam bots 
> * Ability to add your own custom referrer domains to block 
> * You can either 301 the spam traffic to another site OR show a 403 error message to spammers 
> * Full statistics, detailing most blocked bots, total blocks and number of sites in block list 
> * Ability to block all subdomains of a domain e.g. spam.semalt.com 
> * Video showing you how to block 'ghost referrers' (the spam that cannot be blocked since they do not visit your site) 
> * The ability to contribute to our growing list of spam bots 


= Tags =
seo, SEO, google, google analytics, google analytics spam, spam, bot block, bot blocker, bot blocking, block bots, semalt, 100dollarsseo, analytics spam blocker

== Installation ==

1. Upload the `bot block` folder to the `/wp-content/plugins/` directory
2. Activate the Bot Block plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `Bot Block` menu that appears in your admin menu under 'Settings'

== Frequently Asked Questions ==
= I am still seeing spam traffic showing in Analytics =
Watch the video within the plugins settings page - to block 100% of the spam traffic it is a 2 step process

= Can I see the full list of blocked sites? =
Yes go here: http://botblock.rickydawn.com/block_list.php

= Something has gone wrong and my site isn't working =
Disable the plugin, if you cannot get into your admin area delete the plugin files via FTP. Then drop us a message on the support page.

== Screenshots ==

1. Plugin Settings Page.
2. Stats found within plugin.

== Changelog ==

= 2.6 =

- Added screenshots to the plugin page on WordPress.org

= 2.5 =

- Added compatability for WordPress 4.4.2
- Hopefully fixed the empty needle error

= 2.4 =

- Added compatability for WordPress 4.4

= 2.3 =

- Added compatability for WordPress 4.3.1

= 2.2 =

- Data now gets sent and received daily instead of hourly.

= 2.0 =

- Fixed conflict with loading page plugin.

= 1.9 =

- Minor updates.

= 1.8 =

- Added notice to direct people to the second step to block 100% of the referral spam.

= 1.2 =

- Minor update to fix image in plugin options.

= 1.1 =

- Updated copy on plugin settings page and edited readme file.