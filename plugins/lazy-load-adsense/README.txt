=== Lazy Load AdSense ===
Contributors: jorcus
Donate link: https://jorcus.com/product/buy-me-a-coffee/
Tags: Google AdSense, AdSense, Lazy Load Google AdSense, Speed Up Google AdSense
Requires at least: 5.0.1
Tested up to: 6.4.2
Stable tag: 1.2.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lazy Load AdSense plugin allows you to place your Google AdSense script without affecting your website page speed.

== Description ==

Lazy Load AdSense plugin is a simple and lightweight plugin that allows you to place your Google AdSense script without affecting your website page speed.

== Installation ==

Go to https://wordpress.org/plugins/lazy-load-adsense/ to download our plugin and install it to your WordPress System. 

1. Upload `lazy-load-adsense.zip` to the `/wp-content/plugins/` directory. And extract it zip file
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After installing and activating the plugin, go to "settings -> Lazy Load AdSense" to place your AdSense ID.
4. Then, you will start see your AdSense ads displayed on your site! Enjoy.

== Frequently Asked Questions ==

= Is it free? =
Yes, this plugin is 100% free.

= What does the Lazy Load AdSense Plugin actually do? =
If there is no user activity, we will delay the AdSense script for 5 seconds. If the script detects the user scrolling or any mouse activity, the script will be placed immediately.

= Why you need this plugin? =
If you are using traditional AdSense plugin to install your AdSense script or place the AdSense script directly to your website. You may notice a sharp drop in your WordPress site page speed score especially for Core Web Vitals.

In this plugin, we use a combination technique of both delayed loading and lazy load of the Google AdSense script. When you use our plugin to place Google AdSense script, it will not affect your page speed score.

= Will this plugin speed up my website? =
Technically, this plugin doesn't speed up your website. If your website has a score of 80 (Core Web Vitals) before placing the AdSense script, after using our plugin. The score will remain the same (80).

But if you place the code directly copied from official website without using this plugin. You may notice, your website core web vitals score might drop from 80 to 50 or even lower.

= Does your plugin support manual ads? =
Yes, we support both manual ads and auto ads placement.

= How to add manual ads on my website? =
Imagine you want to create a display ads on your website.
1. Log into your "AdSense account" -> "Ads" from menu -> "By Ad Unit" -> Create new ad unit "display ads".
2. Once u created the display ad. From your "existing ad unit", click on a button like "< >" to get the manual ad code.
3. Then go to the page/block template(block theme). Create a custom HTML code block, and paste the code.
4. Now you need to remove this line of code `<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=YOUR_ADSEBSE_ID" crossorigin="anonymous"></script>`. Then, save the page/block template.
5. No worries the ads will works because our plugin already lazy load the adsbygoogle.js. If you don't remove the adsbygoogle.js, your website performance will still degrade (core web vitals).

To make sure u understand. Here is the example of before you remove the code in step 4.

Incorrect code example (Before step 4)
```
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=YOUR_ADSEBSE_ID" crossorigin="anonymous"></script>
<!-- Your Custom Display Ads Name -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="YOUR_ADSENSE_ID"
     data-ad-slot="YOUR_AD_ID"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```

Correct code example (After step 4)
```
<!-- Your Custom Display Ads Name -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="YOUR_ADSENSE_ID"
     data-ad-slot="YOUR_AD_ID"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```

Note: If you placing multiple manual ads, always remove the `adsbygoogle.js` script.

= Can I place ads for specific post types? =
Our plugin does not have settings to place ads for specific post types because we want to keep our plugin simple and lightweight.

But if you still need to place ads for specific post type only. You can either use manual ads or you can use <a target="_blank" href="https://wordpress.org/plugins/plugin-load-filter/">Plugin Load Filter</a>. (Disclaimer: We are not affiliated with them)

= More Questions? =
If you have questions about the plugin, please use the support tab of this page. If you need help other than this plugin, please visit our forum at <a target="_blank" href="https://jorcus.com/">Jorcus.com</a>. There is a dedicated forum for WordPress topic.

== Screenshots ==

1. Currently no screenshot available yet.

== Changelog ==
= 1.2.3 =
* Improvement: Manual Ads is supported. (Read Plugin Page FAQs)

= 1.2.2 =
* Fixed: Deprecation warning on PHP 8.2

= 1.2.1 =
* Improvement: Optimize and Reduce the code.

= 1.2 =
* Fixed: Error message no longer appears when activating the plugin

= 1.1 =
* Enhancement : It's now automatically placed the AdSense script once it detects any mouse activity.
* Tweak : Change to the latest version of Google AdSense script.

= 1.0 =
* First released of Lazy Load AdSense Plugin.

== Upgrade Notice ==

= 1.0 =
First released of Lazy Load AdSense Plugin. 
