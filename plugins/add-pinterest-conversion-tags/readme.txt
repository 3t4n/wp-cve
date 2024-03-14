=== Add Pinterest conversion tags for Pinterest Ads +  Site verification ===
Contributors: the-rock, pagup, freemius
Tags: Pinterest tag, Pinterest ads, Pinterest conversion tracking, Pinterest for business, Pinterest conversion tag
Requires at least: 4.1
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: 1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Pinterest conversion tags plugin allows to add strategically your Pinterest TAG ID on all your webpages (with the base code). No need to edit your theme files!

== Description ==

**EASILY INSERT YOUR PINTEREST BASE CODE (TAG ID) & CREATE EVENTS FOR CONVERSION TRACKING.**

The Pinterest conversion tags plugin allows to add strategically your Pinterest TAG ID on all your webpages (with the base code). No need to edit your theme files!

The simple interface of the Pinterest conversion Tags plugin gives you one place where you can insert your TAG ID.

Also, you can easily add your Pinterest meta tag verification code to claim your site with Pinterest.

**PREMIUM FEATURES**

The Pinterest conversion tags plugin allows you to create EVENTS on specific pages thanks to our **Event META BOX** feature available on each page.
 
You can now define a conversion event by a specific action someone takes on your website, like signing up for your newsletter or buying a product.
 
This plugin allows you to get conversion reporting for **8 types of activity** on your website:

*   **PageVisit:** Record views of primary pages, such as product pages and article pages
*   **iewCategory:** Record views of category pages
*   **Search:** Record searches on your website
*   **AddToCart:** Record when items are added to shopping carts
*   **Checkout:** Record completed transactions
*   **WatchVideo:** Record video views
*   **Signup:** Record sign ups for your products or services
*   **Lead:** Record interest in product or service

**Add-to-cart, Search & Checkout Events** are directly managed on settings page while all others are managed via our **Event META BOX**.

Once the codes are added, give it 5 minutes and then confirm in Pinterest Conversion Manager that the tags are properly implemented.

See the [full implementation guide](https://developers.pinterest.com/docs/ad-tools/conversion-tag/?) for more information.

**HOW TO CHECK CONVERSION?**

Click into your Pinterest Tag at https://ads.pinterest.com/conversion_tags/. On the left you’ll see a section for ‘Tag Event History’. Here you’ll see all event codes you’ve successfully added. Next to the event code you’ll see how many times we’ve seen the tag fire in the last 24 hours as well as the timestamp of the most recent event fire in UTC time. The data in the tag event history is updated every 5 minutes.

**What you should know :**

- Give your campaigns a little room. As with any conversion based campaign, they need time to learn. This means you might initially see CPCs and CTRs that are a little out of line with what you normally see.

- If you’re able to, for the initial learning phase, increase your bids slightly over where you would normally set them to help give Pinterest the space it needs to learn and optimize. You can always scale back later.

- Always test more than one pin, but preferably 3-5 pins per ad set.

- If/when you make changes, make small adjustments incrementally. Meaning, you can’t go crazy to try to scale efforts that are going well because you’ll throw off the algorithms and your results might not scale the way you want them too.

- Pinterest’s new [visual search](https://blog.pinterest.com/en/our-crazy-fun-new-visual-search-tool) tool is a great way to help Pinners find your brand and products. Be sure the images in any product-focused Pins are clear and concise, increasing your chances that they’ll  be featured in visual searches for similar items and photos.

- Triple check that your conversion tag is placed on the right page––if you are tracking purchases, the tag needs to be on your order confirmation page, not the product page.

- No matter what vertical your brand falls into, choosing a stylized “voice” for your photos and sticking with it will ensure that your pins are consistent and coordinated––which will improve your brand experience across desktop and mobile.

- While keeping current trends in mind, be sure that you are also mindful of which gender you are targeting. Whether you are trying to reach males or females (or both), Pinterest released [recent data](https://blog.pinterest.com/en/battle-sexes-pinterest-style) on what each gender has been searching for.


== Installation ==

= Installing manually =

1. Unzip all files to the `/wp-content/plugins/pinterest-conversion-tags` directory
2. Log into WordPress admin and activate the 'Pinterest Conversion Tags' plugin through the 'Plugins' menu
3. Go to "Settings > Pinterest Conversion Tags" in the left-hand menu to start work on it.

== Frequently Asked Questions ==

= How can I confirm my base code and event codes are firing after I implement them on my site? =
Your base code is correctly implemented if the tag shows a ‘verified’ status in the conversion manager. Click into your Pinterest Conversion Tag to check that your event codes are firing - you’ll see a dashboard on the right for Tag Event History. Here you can see all the events that have fired with your Pinterest Conversion Tag. You can check when Pinterest last saw the event fire, and how many raw pixels fired for the event in the past 24 hours.


== Screenshots ==

1. Pctags - Pinterest conversion tags Settings Page
2. Pctags - Pinterest conversion tags Settings Page

== Changelog ==

= 1.0.0 =
* Initial release.

= 1.0.1 =
* Fixed a bug
* Added PageVisit event after base code

= 1.0.2 =
* Security patched in freemius sdk

= 1.0.4 =
* Updated Freemius SDK v2.3.0, Fixed get_blog_list () fatal error
* Updated all translations
* Added security for checkbox $_POST requests
* Added Multiple Plugin Recommendations

= 1.0.5 =
* Added affiliate program

= 1.0.6 =
* Updated Freemius SDK to latest v2.4.1
* Fixed: Undefined varibaled errors

= 1.2.0 =
* 🔥 NEW: Completely refactored with better structure and organized code
* 🔥 NEW: Pinterest Tags on all post types
* 🔥 NEW: Clean & Better Design

= 1.2.1 =
* 🐛 FIX: Composer autoloader classname

= 1.2.2 =
* 👌 IMPROVE: Tested with WordPres v5.9
* 🔥 NEW: Updated Cart and Checkout Event for PRO Version

= 1.2.3 =
* 🐛 FIX: Security update

= 1.2.4 =
* 🐛 FIX: Security update. Verify nonce.
* 👌 IMPROVE: Updated Freemius to v2.5.3
* 🚀 BREAKING: New auto code settings for Metabox and Single Post events.

= 1.2.5 =
* 🐛 FIX: Security update.
