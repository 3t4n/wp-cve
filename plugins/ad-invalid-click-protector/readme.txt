=== Ad Invalid Click Protector (AICP) ===
Contributors: isaumya, acnam
Author URI: https://acnam.com/
Plugin URI: https://wordpress.org/plugins/ad-invalid-click-protector/
Donate link: https://rzp.io/l/m7EhiuU
Tags: adsense, invalid click, protect adsense account, adsense invalid click, save adsense account, google adsense, isaumya, aicp, aicp wordpress
Requires at least: 4.9
Requires PHP: 7.0
Tested up to: 6.2

Stable Tag: 1.2.8

License: GNU Version 2 or Any Later Version

One plugin to save your AdSense account from Click Bombings and Invalid Click Activities

== Description ==

Ad Invalid Click Protector a.k.a. AICP plugin will help you to save your Google Ad account from unusual invalid click activities and click bombings. As per the Google Ad terms, Google doesn't take any responsibility towards these invalid click activities or click bombings and always point the finger towards the Ad publisher, giving him/her all the blames. Now it is time to end this problem, once and for all.

> **Important Note About Touch Screen Support:** This plugin doesn't work on mobile devices such as smartphones and tablets, because this hardware uses a touchscreen instead of a mouse as click input. This design makes the boundary monitoring trick ineffective.

> **Important Note:** From v1.1.0 this plugin __will only work with__ AdSense ad code or any ad code that shows iframe based ads. Normal affiliate ad codes will be tracked anymore. If you want to track affiliate ad codes like `<a href="http://example.com"><img src="/some.jpg" /></a>`, please use v1.0.6 of this plugin. v1.1.0+ code will not work that way. To know why I had to do this, check the FAQ section.

> **Important Note:** This plugin uses the free API version of [IP-API.com](http://ip-api.com/) which allows 150 req/min. If you have a high traffic website, this 150 requests per minute will not be enough for you and you may see `503 Error` on your site due to free API restrictions. If you have a high traffic website which may generate more than 150 requests per minute, I will recommend you to grab the [PRO Version of IP-API](https://signup.ip-api.com/) and enter your Pro API key in the General Settings page of this plugin.

**Video Demonstration About the Plugin Usage**

Before start using the plugin, I will highly recommend you to take a look at this video demonstration where I've explained everything about this plugin.

[youtube https://www.youtube.com/watch?v=XKSeT4MIBBg]

**For plugin support please post your your feedback and support questions to the [Plugin's Support Forum](https://wordpress.org/support/plugin/ad-invalid-click-protector).**

> It took 300+ hours to code, design, test and to do several bugfix to make this plugin a reality. If you enjoy this plugin and understand the huge effort I put into this, please consider **[donating some amount](https://rzp.io/l/m7EhiuU) (no matter how small)** for keeping alive the development of this plugin. Thank you again for using my plugin. Also if you love using this plugin, I would really appreciate if you take 2 minutes out of your busy schedule to **[share your review](https://wordpress.org/support/plugin/ad-invalid-click-protector/reviews/)** about this plugin.

Features of the plugin include:

* Set maximum ad click limit
* Block any visitor if he exceeds the mentioned click limit
* Ban some countries from seeing the ads on your site
* Ability to see the list of banned user details from the WordPress admin section
* Ability to delete any banned IP one by one or in bulk approach
* Ability to search any IP within the banned IP list
* Admin dashboard widget to show the total number of banned users

> **Now this plugin is also hosted in [Github](https://github.com/isaumya/adsense-invalid-click-protector). But the Github repo will be used for the development of the plugin. So, from now on you can also report the bugs in [Github Issue Tracker](https://github.com/isaumya/adsense-invalid-click-protector/issues) if you want.**

= Some FAQs =

**How to use this plugin with your site?**

I know there are many WordPress plugin where you basically paste your AdSense code and it shows your ad at various position of your website. But unfortunately it is not humanly possible for me to check every single plugin of such out there or contact each plugin developer. Here I'm showing you how to incorporate the **Ad Invalid Click Protector (AICP)** plugin with your website's ad code, so that both other plugin developers and normal users who use custom codes to show up their ads can take advantage of this.

To use the Ad Invalid Click Protector plugin with your ad code you basically have to do 2 simple things.

1. Put a `if( aicp_can_see_ads() ) { /* return your ad code here */ }` block before returning your ad code to the front end
2. Wrap your ad code within a simple `div` tag like this `<div class="aicp">...your ad code goes here...</div>`

Personally I create various WordPress shortcodes for various ad units that I use on my personal website. It is extremely easy to create shortcodes for your ad units while taking the advantage of Ad Invalid Click Protector Plugin. Let me show you how to create a WordPress shortcode very easily.

To create a shortcode the first thing you need to do is, go to the `functions.php` file of your theme or your child theme and at the end of your file put any of the following code.

If you are using a PHP version < 5.3, you can create a shortcode in the following way:

`add_shortcode( 'your_shortcode_name', 'your_shortcode_function_name' );
function your_shortcode_function_name() {
    if( aicp_can_see_ads() ) { // This part will show ads to your non-banned visitors
        $adCode = '<div class="aicp"><!-- Don\'t forget to add this div with aicp class -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Responsive Ad Code -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1234567890"
             data-ad-slot="0987654321"
             data-ad-format="auto"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div><!-- end of the aicp div -->';
        return $adCode;
    } else { // Anything within this part will be shown to your banned visitors or to the blocked country visitors
        return '<div class="error">You have been blocked from seeing ads.</div>';
    }
}`

If you are using PHP version >= 5.3, you don't need to give a function name, instead you can take advantage of of anonymous function like this way:

`add_shortcode( 'your_shortcode_name', function() {
    if( aicp_can_see_ads() ) { // This part will show ads to your non-banned visitors
        $adCode = '<div class="aicp"><!-- Don\'t forget to add this div with aicp class -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Responsive Ad Code -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1234567890"
             data-ad-slot="0987654321"
             data-ad-format="auto"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div><!-- end of the aicp div -->';
        return $adCode;
    } else { // Anything within this part will be shown to your banned visitors or to the blocked country visitors
        return '<div class="error">You have been blocked from seeing ads.</div>';
    }
} );`

**Please Note:** if you want, you can completely ignore the `else {}` block above in case you don't wanna show anything special to the blocked visitors.

**How can I know what PHP version I am using?**

You can install the [WP Server Stats](https://wordpress.org/plugins/wp-server-stats/) plugin in your website which will show you many important information about your hosting environment including what PHP version you are currently using.

**Can this plugin be used with other ad medias?**

No It is not. This plugin will only work with Ad ads or any ad media that shows iframe based ads. Recently Google has tweaked there ad code which makes merely impossible to support both iframe based ads like Ad and non-iframe based ads, like affiliate ads.
In case of affiliate ads, there is generally no click bombing issue, that is why from v1.1.0, AICP will only support iframe based ads and not general affiliate ads. Sorry guys! In case you want to use AICP just for affiliate ads, I will recommend you to download v1.0.6 of the plugin from the [old archive](https://wordpress.org/plugins/ad-invalid-click-protector/developers/).

**Will it help me from stop clicking on my own ads?**

No, you are not supposed to click on your own ads. If you want you can use adblock in your browser to save yourself from accidental clicking on your own ads. 

**Languages**

Ad Invalid Click Protector plugin is 100% translation ready. Right now it only has the English translation in it but over time with the community help I hope this plugin will have many language in it's language directory.

**Support the Plugin**

If you like this plugin please don't forget to write a review and if possible please [Donate some amount](https://rzp.io/l/m7EhiuU) to keep the plugin and it's development alive.

== Screenshots ==

1. Admin Dashboard Widget
2. General Settings Page
3. Banned User List Page


== Installation ==

1. Go to Plugins > Add New
2. Search for Ad Invalid Click Protector or AICP and Install it
3. Go to your admin dashboard and you will see the dashboard widget over there.
4. To change the settings of the Ad Invalid Click Protector, head over to **Ad Invalid Click Protector** > **General Settings** menu in you WordPress's left vertical menu

== Frequently Asked Questions ==

= How to use this plugin with your site? =

I know there are many WordPress plugin where you basically paste your AdSense code and it shows your ad at various position of your website. But unfortunately it is not humanly possible for me to check every single plugin of such out there or contact each plugin developer. Here I'm showing you how to incorporate the **Ad Invalid Click Protector (AICP)** plugin with your website's ad code, so that both other plugin developers and normal users who use custom codes to show up their ads can take advantage of this.

To use the Ad Invalid Click Protector plugin with your ad code you basically have to do 2 simple things.

1. Put a `if( aicp_can_see_ads() ) { /* return your ad code here */ }` block before returning your ad code to the front end
2. Wrap your ad code within a simple `div` tag like this `<div class="aicp">...your ad code goes here...</div>`

Personally I create various WordPress shortcodes for various ad units that I use on my personal website. It is extremely easity to create shortcodes for your ad units while taking the advantage of Ad Invalid Click Protector Plugin. Let me show you how to create a WordPress shortcode very easily.

To create a shortcode the first thing you need to do is, go to the `functions.php` file of your theme or your child theme and at the end of yoru file put any of the following code.

If you are using a PHP version < 5.3, you can create a shortcode in the following way:

`add_shortcode( 'your_shortcode_name', 'your_shortcode_function_name' );
function your_shortcode_function_name() {
    if( aicp_can_see_ads() ) { // This part will show ads to your non-banned visitors
        $adCode = '<div class="aicp"><!-- Don\'t forget to add this div with aicp class -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Responsive Ad Code -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1234567890"
             data-ad-slot="0987654321"
             data-ad-format="auto"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div><!-- end of the aicp div -->';
        return $adCode;
    } else { // Anything within this part will be shown to your banned visitors or to the blocked country visitors
        return '<div class="error">You have been blocked from seeing ads.</div>';
    }
}`

If you are using PHP version >= 5.3, you don't need to give a function name, instead you can take advantage of of anonymous function like this way:

`add_shortcode( 'your_shortcode_name', function() {
    if( aicp_can_see_ads() ) { // This part will show ads to your non-banned visitors
        $adCode = '<div class="aicp"><!-- Don\'t forget to add this div with aicp class -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Responsive Ad Code -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-1234567890"
             data-ad-slot="0987654321"
             data-ad-format="auto"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        </div><!-- end of the aicp div -->';
        return $adCode;
    } else { // Anything within this part will be shown to your banned visitors or to the blocked country visitors
        return '<div class="error">You have been blocked from seeing ads.</div>';
    }
} );`

**Please Note:** if you want, you can completely ignore the `else {}` block above in case you don't wanna show anything special to the blocked visitors.

= How can I know what PHP version I am using? =

You can install the [WP Server Stats](https://wordpress.org/plugins/wp-server-stats/) plugin in your website which will show you many important information about your hosting environment including what PHP version you are currently using.

= Can this plugin be used with other ad medias? =

No It is not. This plugin will only work with AdSense ads or any ad media that shows iframe based ads. Recently Google has tweaked there ad code which makes merely impossible to support both iframe based ads like AdSense and non-iframe based ads, like affiliate ads.
In case of affiliate ads, there is generally no click bombing issue, that is why from v1.1.0, AICP will only support iframe based ads and not general affiliate ads. Sorry guys! In case you want to use AICP just for affiliate ads, I will recommend you to download v1.0.6 of the plugin from the [old archive](https://wordpress.org/plugins/ad-invalid-click-protector/developers/).

= Will it help me from stop clicking on my own ads? =

No, you are not supposed to click on your own ads. If you want you can use adblock in your browser to save yourself from accidental clicking on your own ads. 

= Does this plugin tracks click on mobile devices? =

This plugin doesn't work on mobile devices such as smartphones and tablets, because this hardware uses a touchscreen instead of a mouse as click input. This design makes the boundary monitoring trick ineffective.

== Changelog ==

= 1.2.8, March 21, 2023 =

* Set the WP minimum requirement to v4.9 so that the plugin can be used with ClassicPress
* Updating the tested up to value of the plugin

= 1.2.7, April 04, 2022 =

* Fixed CSRF & XSS security issues

= 1.2.5.2, April 23, 2021 =

* Fixed Google trademark issues

= 1.2.5.1, August 31, 2020 =

* Updated the Donation Flow inside the plugin settings page

= 1.2.5, June 29, 2020 =

* Added new contributor - [Acnam Infotech](https://acnam.com)
* Updated the donation link
* Updated tested up to WP v5.5
* Added requires PHP to PHP 7.0

= 1.2.4, April 18, 2020 =

* Some minor improvements and bugfixes

= 1.2.3, May 30, 2019 =

* For some users who are using cloudflare on their server facing issue that the user IP is not getting reflected on the plugin. Instead the plugin was showing the server IP as the user IP. This issue is now fixed. Thanks to [@coolph](https://profiles.wordpress.org/coolph/) for providing a testing environment to test and fix this issue.

= 1.2.2, April 6, 2018 =

* Added new demonstration video in YouTube embed.

= 1.2.0 & 1.2.1, December 30, 2016 =

* Reducing dependency on IP-API.com. Now the API call will only be made when you are using country wide ban. Also in the Banned User Details table the Country Name and Country Code has been removed to ensure less dependency on 3rd Party APIs.

= 1.1.0 & 1.1.1, December 23, 2016 =

* Major release.
* Now AICP supports only iframe based ads like AdSense and not normal affiliate ad codes
* Fix: In some cases clicking on adsense ads are not being tracked
* Huge code quality improvement

= 1.0.6, December 15, 2016 =

* Making sure that the welcome notice never shows up once you dismiss it.

= 1.0.5, November 27, 2016 =

* Changed the donation link to the new donation page
* Fixed some minor bugs

= 1.0.3 & 1.0.4, November 24, 2016 =

* Added support to [IP-API Pro](https://signup.ip-api.com/) for high traffic websites
* Minor bug fix with uninstallation method
* Translation update and bugfix

= 1.0.2, November 16, 2016 =

* Minor bug fix in the admin settings page

= 1.0.1, November 16, 2016 =

* Minor fix to some issues with the readme file

= 1.0.0, November 16, 2016 =

* First official stable release!