=== MailChimp Campaigns ===
Contributors: matthieuscarset-1
Tags: mailchimp, mailchimp campaign, mailchimp stats, shortcode, shortcodes, newsletter
Requires at least: 4.0.0
Tested up to: 5.2.1
Stable tag: 3.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Import and display MailChimp Campaigns in WordPress, the easy way.

== Description ==

This plugin allows you to **import and display your Mailchimp campaigns in your WordPress site with simple [embed](https://codex.wordpress.org/Embeds) content**.

Import your campaigns in WordPress as custom posts and display them anywhere you want with just a copy/paste of the internal URL.
 
You can display your campaigns as **HTML** in any Post, Widget, Page or Custom Post Type. 

<h4>Plans</h4>

* Free (forever): simply requires a free MailChimp API key. 
* Premium (online): register for free [on our online platform](https://mailchimp-campaigns-manager.com/).

<h4>Features</h4>
* Import your MailChimp campaigns in WordPress
* List all your campaigns with their statistics  in WordPress
* Display your newsletter as HTML anywhere you want

== Installation ==

= Plugin installation =
1. Download this plugin and add it to '/wp-content/plugins/'
1. Activate it from WordPress &raquo; Plugins admin screen
1. Go to Settings and scroll down until 'MailChimp Campaigns' section
1. Save your username and your API Key

= Plugin usage =
1. Import your campaigns in WordPress from the settings screen
1. Copy/paste a new imported campaign post's url in a Post or a Page of your site

== Frequently Asked Questions ==

= How to find my MailChimp API Key?  =
1. Log into your [MailChimp](http://mailchimp.com/ "MailChimp") account
1. Go to your [Account](https://mailchimp.com/account/ "Account")
1. Click on the [Extra](https://mailchimp.com/account/api/ "Extra") tab and you'll find your API keys ;)

= How to embed a Campaign in a Post?  =
There are two ways to embed a campaign.
1. Copy/paste the campaign Post's url and insert it into another Post.
1. Insert shortcode as follow: `[campaign id=”642f031e96″ width=”800px” height=”3000px”]`.

Examples are provided by default in Admin > Newsletters list.

= "Page not found" after import =
When you display a Newsletter post on front end after import it gives you a 404 Page not found error, proceed as follow:
1. Go to Admin > Settings > Permalinks 
1. Select "postname"
1. Save 

This must have fix the issue.

= Iframe is cut-off / Campaign is not fully displayed =
This is a limitation due to WordPress's JS itself. 
It limits iframe height to 1000px maximum. 

Luckily, others have already found a solution:
1. Install [Bypass Iframe Height Limit](https://fr.wordpress.org/plugins/bypass-iframe-height-limit/) plugin

== Screenshots ==

1. This screenshot shows the Setting section where you can save you API key and import your data.
2. This is the WordPress admin screen where your can see all your imported MailChimp campaigns
3. Campaigns are rendered in a Responsive iframe on front.
4. Campaigns are rendered in a Responsive iframe on front.


== Changelog ==

= 3.2.0 "Chet Baker" =
* New major version release
* Improved code quality
* New PRO features

== Upgrade Notice ==

= 3.2.0 =
Major update. New PRO feature.

