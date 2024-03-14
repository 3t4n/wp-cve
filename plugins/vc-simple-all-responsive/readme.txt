=== WPBakery Page Builder Simple All Responsive ===
Plugin Name: VC Simple All Responsive
Contributors: autonomash
Tags: WPBakery, Page Builder, responsive
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=markovobren@gmail.com&lc=US&item_name=Donation+to+VC+Simple+All+Responsive+WordPress+Plugin&no_note=0&cn=&currency_code=EUR&bn=PP-DonationsBF:btn_donateCC_LG.gif:NonHosted
Requires at least: 4.0
Tested up to: 5.3.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.3

VC Simple All Responsive makes it easier to develop responsive websites when using WPBakery Page Builder. Works on elements within columns.

== Description ==
VC Simple All Responsive Plugin provides full responsiveness to sites created with Page Builder. So far, we have been able to determine the screens on which some Row and Columns will appear (or not appear). From now on, thanks to this plugin, this type of control is obtained over all Page Builder elements.

VC Simple All Responsive plugin creates a new element of the Page Builder that can be found in All and in the Content section. It works as a container that determines when the content that is placed in it will be visible (or not).

VC Simple All Responsive conatiners are placed inside the column. Within a single column we can place an arbitrary number of containers, and it is possible that they have different settings. Also, they can be arbitrarily combined with free elements (elements outside the container).

It is possible to set several elements within a single container. In this case, the same rules for displaying apply to all elements within a single container.

When you drag the element inside the container, it behaves commonly, in the way it would behave outside of the container. The container has only one type of setting, which determines the conditions for displaying the content.

The container can be left unfilled (without content), and then he will have no visible effect. Also, similar to the Page Builder rows, the container can be completely excluded from the view (regardless of the screen size).

**Benefits of Use:**
WPBakery Page Builder in the original version provides the option to set the visibility depending on the screen sizes of the device, but only at the level of Rows and Columns. In addition, it is not possible to set up two or more competitive columns (competitive, in the sense that they fill the same space). The interior elements do not have this possibility of visibility adjustments. This is sufficient for rough adjustment of the responsiveness, however, it is not practical in cases of finer tuning, when only a partially different content is displayed on different resolutions. Plugin VC Simple All Responsive allows complete freedom in this regard. The ability to choose when it will be displayed is enabled at the level of a single element or an arbitrary group of elements. The back-end display is of a competitive type (in the same column), which facilitates visibility.

Example: With the VC Simple All Responsive Plugin, it is possible to set up in the same column: element visible only on desktop screens (in the first All Responsive container), then a group of elements seen only on tablets and on mobile phones (in the 2nd All Responsive container) and finally (at any place, whatever) the elements that are visible on all screens (free elements placed in the usual way, outside of the container). Without the use of All Responsive Elements plugin, it would be necessary to form three separate rows, each with its own setting and copying repeating elements (in other columns, for example). Great advantages are also that the Websites Back End with using of the VC Simple All Responsive Plugin is much more readable and the memory and processor load of the site is reduced.

VC Simple All Responsive element can equally apply to additional elements of independent manufacturers, as well as to the basic elements of Page Builder. For this reason, the implemented solution is superior to some other that are applied in some WP themes.

------

Please, when evaluating this plugin, you understand that it is free and that customer support is adequate to that.
Also, in situations with WordPress ver 5 or newer WPBakery Page Builder do not behave completely correctly, it surely means that this plugin will not behave correctly, too. This is not a plugin shortage. This problem occurs because some WP themes have not adapted well to WordPress 5 and Gutenberg appears. Over time, these problems will be less.

**Note:**
For the proper functioning of this plugin, it is necessary to have an active installation of *WPBakery Page Builder* (js_composer) in version 5 or later.

== Installation ==
METHOD 1: FROM YOUR WORDPRESS DASHBOARD

1. Visit 'Plugins > Add New'
2. Search for 'vc simple all responsive'
3. Activate 'VC Simple All responsive' plugin from your Plugins page.

METHOD 2: FROM WORDPRESS.ORG

1. Search for 'vc simple all responsive' on WordPress.org
2. Download 'VC Simple All Responsive' plugin from WordPress.org.
3. Upload the 'vs-simple-all-responsive� directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
4. Activate 'VC Simple All Responsive' plugin from your Plugins page.

== Frequently Asked Questions ==
= The plugin does not work properly on my site. What's the problem? =
* Check compatibility with PHP version and WP version
* If the WP version is 5.0 or later: Some WP themes are not written well enough, so when WordPress is in version 5.0 or later, the WPBakery page builder works properly. If there are problems in the work of Page Builder, probably this plugin will not work properly. In this case, you should wait for the theme manufacturer to make the necessary changes.

== Screenshots == 
1. Appearance of the plugin icon in the 'All' group
2. Appearance of the plugin icon in the 'Content' group
3. Settings screen (upper part)
4. Settings screen (bottom part)
5. Visibility by width options
6. Additional options for mobile phone types
7. Appearance of elements without internal content
8. Usage example: Media grid and Woo cart are displayed only on screens below 1024 pixels, and Line Chart only on desktop screens of 1024 pixels or more
9. Usage example: Image is displayed on all screens, a price below only on tablets and phones

== Changelog ==
= 1.3 =
* removed a couple of bugs and a completely changed the way of elements hiding

= 1.2 =
* Reorganized code - removed issues with some topics on WP 5+.

= 1.1.1 =
* Corrected noticed bugs.

= 1.0 =
* Initial release