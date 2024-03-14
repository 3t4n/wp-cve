=== Browser Specific CSS ===
Contributors: adrian3
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=designer%40adrianhanft%2ecom&lc=US&item_name=Wordpress%20Plugin%20Development&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: css, browser, target, ie6, ie7, ie8, safari, chrome, firefox, custom css, browser specific, browser selector, operating system, detect browser
Requires at least: 2.0.2
Tested up to: 2.9
Stable tag: trunk

The Browser Specific CSS Plugin allows you to easily target specific browsers and operating systems from your theme's stylesheet using regular css selectors.

== Description ==
The Browser Specific CSS plugin is a tool that allows developers to easily target different browsers straight from the stylesheet. Browser Specific CSS adds a short javascript to the head of your page that enables you to use browser specific css selectors. For example, targeting Internet Explorer 7 from your stylesheet is just a matter of defining styles with a ".ie7" selector. Every major browser is supported including code for targeting Macs, PCs, and Linux operating systems.

This plugin uses the javascript written by <a href="http://rafael.adm.br/css_browser_selector/" title="Rafael Lima">Rafael Lima</a>. No changes have been made to his code, and all the credit goes to him. Thanks, Rafael!

Changelog:
Version 0.3  
- Update for compatibility with Wordpress 2.9 

Version 0.2  
- Corrected readme file. 

Version 0.1  
- Features of the first version of this plugin include the ability to turn the plugin on and off and to specify a separate stylesheet. 

== Installation ==

Installing Browser Specific CSS is very easy and do not require any template modification in most cases. Just follow these steps:

1. Upload the folder 'browser_specific_css' to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Under "settings" you will see a "Browser Specific CSS" link that you can click on to access the Browser Specific CSS Plugin settings. This screen will give you a list of browser specific css selectors that you can begin to use in your stylesheet(s)



== Frequently Asked Questions ==

= How does this plugin work? =

Browser Specific CSS links to a javascript that enables you to target specific different browser and operating systems using simple css selectors.

= What is a CSS Selector?" =

A CSS Selector is a basic stylesheet concept. If you don't understand CSS this plugin probably isn't for you.

= Does this plugin work if a visitor has javascript disabled?" =

Since this plugin uses a javascript, it won't target browsers that have javascript disabled. Depending on who you ask, this might affect 5% of your visitors. In this case, you can use the .no_js selector to target these users.
 


== Screenshots ==
1. This screenshot shows the "settings" panel for the Browser Specific CSS plugin.