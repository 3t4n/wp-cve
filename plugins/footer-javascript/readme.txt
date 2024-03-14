=== JavaScript to Footer ===
Contributors: freediver
Donate link: https://www.networkforgood.org/donation/MakeDonation.aspx?ORGID2=920155875
Tags: footer, javascript, optimization, 
Requires at least: 2.3
Tested up to: 4.2
Stable tag: trunk

This plugin automatically moves JavaScript code to page footer, speeding up page loading time.

== Description ==

The Yahoo! Exceptional Performance team recommends <a href="http://developer.yahoo.com/performance/rules.html#js_bottom">placing scripts at the bottom of your page</a> because of the way browsers download components.

"The problem caused by scripts is that they block parallel downloads. The HTTP/1.1 specification suggests that browsers download no more than two components in parallel per hostname. If you serve your images from multiple hostnames, you can get more than two downloads to occur in parallel. While a script is downloading, however, the browser won't start any other downloads, even on different hostnames.

In some situations it's not easy to move scripts to the bottom. If, for example, the script uses document.write to insert part of the page's content, it can't be moved lower in the page. There might also be scoping issues. In many cases, there are ways to workaround these situations."

WordPress default behavior is to load javascript in the <head> page.

This plugin will move all javascript code to the footer if the plugins have <a href="http://www.prelovac.com/vladimir/best-practice-for-adding-javascript-code-to-wordpress-plugin">declared javascript properly</a>. 

Plugin by <a href="http://www.prelovac.com/vladimir">Vladimir Prelovac</a>. Looking for <a href="http://www.prelovac.com/vladimir/services">WordPress Services</a>?

== Installation ==

1. Upload the whole plugin folder to your /wp-content/plugins/ folder.

2. Go to the Plugins page and activate the plugin.

3. Try your blog and check if all plugins work properly. Footer JavaScript may break some plugins so check everything first!

== License ==

This file is part of Footer JavaScript.

Footer JavaScript is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Footer JavaScript is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Footer JavaScript. If not, see <http://www.gnu.org/licenses/>.


== Frequently Asked Questions ==

= How do I correctly use this plugin? =

Just enable the plugin and check your blog pages. If all plugins appear to be working correctly you are set!

Additionally you may install YSlow enchancment for Firebug, a popular addon for Firefox browser. YSlow will help you determine your site choke points.

= I love your work, are you available for hire? =

Yes I am, visit my <a href="http://www.prelovac.com/vladimir/services">WordPress Services</a> page to find out more.