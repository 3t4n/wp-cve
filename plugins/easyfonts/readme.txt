=== Local google fonts,  host google fonts locally by Easyfonts ===
Contributors: easywpstuff
Donate link: 
Tags: googlefonts, fonts, GDRP, dsgvo, google fonts local, fonts locally
Requires at least: 5.0
Tested up to: 6.4.2
Requires PHP: 5.6
Stable tag: 1.1.2
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically cache and host google fonts locally on your server to make site faster and GDPR compliant.

== Description ==
Looking for local google fonts? EasyFonts is a lightweight plugin that gives you the option to host and cache your existing Google fonts locally on your local server. Improve website speed and load fonts from your own URL instead of Google. With EasyFonts, you no longer need to rely on external servers to load Google Fonts on your website, which can slow down your website and negatively impact user experience. 

Use the options in the plugin settings to download and use Google fonts locally.

= Features =
* Cached and host google fonts loading with &lt;link&gt;
* local google fonts loading with @import statement inside inline &lt;style&gt;
* host google fonts locally that are loading with @font-face statements inside inline &lt;style&gt;
* Remove Resource Hints (preload, preconnect, dns-prefetch)
* Remove WebFontLoader (webfont.js).

= Plugin Compatibility =

This plugin will work with almost all the themes and page builders.

* host elementor google fonts on local server.
* support for wpbakery page builder
* host google fonts locally for divi or any other theme.

== Frequently Asked Questions ==
**Is it legal to host Google Fonts locally on my server?**
Yes, it is legal to host Google Fonts locally on your server because Google Fonts are published under a licenses that allow you to use them on any website, no matter if it’s commercial or personal.

**Why only the woff2 font format is loaded in my site when I host Google Fonts locally?**
The woff2 font format is the most efficient and lightweight font format and most widely supported format among modern browsers. By only loading the woff2 font format, your site will have faster load times and better performance.

**How can I make sure that my site is GDPR compliant when I host Google Fonts locally?**
By hosting Google Fonts locally on your server, you can ensure that the font data is not being sent to external servers, which can help to make your site GDPR compliant. 

**How can I check if my site is using Google Fonts?**
You can check if your site is using Google Fonts by looking at the source code of your site and searching for the "fonts.googleapis.com" or "fonts.gstatic.com" domains. You can also use browser developer tools to inspect the network requests and see if any requests are being made to these domains. 


== Installation ==
1. Unzip the plugin archive on your computer
2. Upload directory to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the options in the plugin settings to host existing Google Fonts as desired.
5. That's it! The plugin will do the rest.



== Screenshots ==
1. [Settings Page](assets/screenshot-1.png)

== Changelog ==
= 1.1.2 =
* Fixed issue with wocoomerce checkout
= 1.1.1 =
* Improvement
= 1.1.0 =
* minor issues fixed
* support for smart slider 3
* support for groovy menu
= 1.0.4 =
* fixed issue with google fonts url contains special characters.
= 1.0.3 =
* fixed issues with google fonts start with //.
= 1.0.2 =
* fixed http issues.
= 1.0.0 =
* Initial release.