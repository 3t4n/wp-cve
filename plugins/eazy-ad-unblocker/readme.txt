=== Eazy Ad Unblocker===

Contributors: debp85
Tags: anti adblock, ad unblocker
Requires at least: 3.9
Tested up to: 6.3.2
Requires PHP: 7.2
Stable tag: 1.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Eazy Ad Unblocker notifies the user if ad blockers like AdBlock, AdGuard AdBlocker, AdBlock Plus are blocking advertisements which you 
paintstakingly put on your site to monetize it. Your users must deactivate their adblockers or whitelist your site in their adblocker
settings.
 
== Description ==

Eazy Ad Unblocker notifies the user about ad blockers like AdBlock, AdGuard AdBlocker, AdBlock Plus, Ghostery, etc. 
Eazy Ad Unblocker works on Microsoft Edge, FireFox, Google Chrome and Opera browsers. If users have their adblocker on while surfing 
the web in these browsers, they will be prompted to switch their adblocker off or whitelist the site they are currently viewing 
via a modal popup. Users will not be able to view the website content clearly, nor will they be able to view the source of the page 
they are on, unless they deactivate the adblocker or whitelist the site in their adblocker settings. There will be an option to close the 
popup so that they are not locked out of the site completely. It will be in the form of a button marked 'X'.

There is an option to completely black-out the content in the popup background through opacity settings. The admin of the site can 
also configure the text and heading of the modal popup. The popup close button is controllable from wp-admin. You can also 
set the width of the popup for larger devices like ipads, laptops, desktops if you don't like the popup to be totally spread out. 

You can now choose from among six themes to style the popup.

Thanks for the art work to Rochana Deb!


== Installation ==

There are two ways to install the plugin once you have obtained the zip archive of the plugin.

Option 1: Unzip the plugin to install it

1.	Unzip the archive with the name beginning with eazy-ad-unblock.
2.	Connect to your website via FTP.
3.	Browse to '/wp-content/plugins/' directory in FTP.
4.	Upload the entire 'eazy-ad-unblock' folder to FTP.
5.	Login to the Admin Dashboard '/wp-admin' of your website. 
6.	Browse the plugins page. You will see 'Eazy Ad Unblocker' plugin in the listing.
7.	Activate the plugin in step 6 above.

Option 2: Upload the plugin archive as is

1.	Login to your admin dashboard '/wp-admin'.
2.	Under 'Plugins' in the left sidebar, you will see an 'Add New' menu. Click it.
3.	You will be taken to the plugin install page. Click 'Upload Plugin' button on top of this page.
4.	A form will open up prompting to upload a Zip file. Upload your plugin archive and click the submit button.
5.	Click the 'Activate Plugin' button under the 'Plugin installed successfully.' message.
6.	You will see the message 'Plugin Activated' on the plugin listing page.

IMPORTANT: Either way, you need to configure the plugin for first-time use in your site.

1.	Click on the 'Eazy Ad Unblock' menu in the left sidebar of your admin dashboard.
2.	Configure your popup according to your needs on the page that opens up.
3.	You can enter the title, body text and opacity of the popup background. An opacity of 100% 
	blacks out the content behind the popup. Opacity of 0% will make the content behind the popup 
	completely visible or, in other words, the popup background will be completely transparent.
4.	Although you can add media in the body text, avoid using wallpapers thousands of pixels tall or wide.
	Likewise, do not add videos that are hundreds of megabytes in size.
5.	Don't forget to click 'Save' at the bottom.

== Testing == 

Browse to the site where you installed this plugin.
To test the popup, activate the ad blocker in your browser for the site.
Refresh your page if it does not auto-refresh. You should see a popup and verify that 
it cannot be dismissed in any way except disabling your adblocker or whitelisting your site in it. 
There is a button marked with a cross on the top right corner of the popup. Click it to close the popup.
You should also not be able to view source for the page you are on by pressing Ctrl+U.   
 

 
== Frequently Asked Questions ==
 
Q1.	I installed and activated this plugin. But I did not see a proper modal popup with any proper message.
	What's wrong?

A1. The plugin needs to be configured for first-time use after installation and activation: 

	1.	Click on the 'Eazy Ad Unblock' menu in the left sidebar of your admin dashboard.
	2.	Configure your popup according to your needs on the page that opens up.
	3.	You can enter the title, body text and opacity of the popup background. An opacity of 100% 
		blacks out the content behind the popup. Opacity of 0% will make the content behind the popup 
		completely visible or, in other words, the popup background will be completely transparent.
	4.	Although you can add media in the body text, avoid using wallpapers thousands of pixels tall or wide.
	5.	Don't forget to click 'Save' at the bottom.
	6.	Verify that a proper popup shows up by making your adblocker active for your site.
	
	
Q2.	I activated and configured the plugin. Then I activated my adblocker for my site. But I don't get any popup. Why?

A2. You need to refresh the page in the browser after activating the ad blocker. 


Q3. I set the popup width for large screens from admin section. Now I want to reverse the effect of setting width?

A3.	You need to set the popup width to 0 in popup admin and save the settings.


Q4. I am not being allowed to save my plugin options as it asks for a theme. I want to keep the earlier theme.

A4.	Just save your options with the blue theme which has blue preview image.
 
  
== Screenshots ==

1. The screenshot-1.png shows the demo modal popup that appears in the frontend when the plugin has been installed and activated.  

2. The screenshot-2.png shows the modal popup as it appears on the front-end after editing. The background is black because the opacity is 100%.
	The body of the popup shows that html content as well as media can be added to it. 

3. The screenshot-3.png shows the admin section of the plugin where you can configure the settings such as title, body text and opacity.

 
== Features ==
 
The following features exist in this plugin:
 
1.	It prevents users from using the site when adblockers are active for them.
2.	There is no way to dismiss the popup without deactivating the ad blockers.
3.	The users cannot view the source of the page they are on when the plugin is active.
4.	The popup background opacity can be adjusted.
5.	The user also cannot view or use web developer tools to bypass the popup or view the popup html.
6.	The popup auto-scales to the content visible in it.
7.	The plugin checks whether ad blockers are on or not, not if the page has ads.
8.	The popup's title, text and opacity are editable. You can also add media such as images, videos and 
	audio clips to the body text. Audio and video are HTML5 based.
9.	The admin can configure, from the backend, whether to show a close button in the popup or not. 
10. The popup dialog is responsive. 
11.	The popup can be disabled on individual pages and posts.
12. The popup width can be set for larger devices like ipads and desktop screens from the admin section.
13.	The popup style theme can be changed.
14.	CSS classes and id attributes are random for defence against ad blockers.
15. Deleting the plugin deletes its data as well.
16. Tested with PHP 8.1 and WordPress 6.3.2
	
	
== Changelog ==

= 1.2.3 2023/11/05 =
* Tested with PHP 8.1 and WordPress 6.3.2

= 1.2.2 2023/01/15 =
* Tested with PHP 8.0 and WordPress 6.1.1

= 1.2.1 2022/06/04 =
* Maintenance release.

= 1.2.0 2022/05/21 =
* Added support for more popular ad blockers across the supported browsers like Ghostery.

= 1.1.12 2021/10/26 =
* Bug fix for widget editor after core upgrade to Wordpress 5.8.1


== Upgrade Notice ==

= 1.2.3 =
Upgrade for PHP 8.1 and Wordpress 6.3.2.

= 1.2.2 =
Upgrade for PHP 8.0 and Wordpress 6.1.1.

= 1.2.1 =
Upgrade for maintenance fixes.

= 1.2.0 =
Upgrade for supporting more popular ad blockers like Ghostery.

= 1.1.12 =
Upgrade for bug fix for widget editor after core upgrade to Wordpress 5.8.1

= 1.1.11 =
Upgrade for bug fix for randomization of popup id and class attributes.

= 1.1.10 =
Upgrade for bug fix for plugins like Ad Inserter.

= 1.1.9 =
Upgrade for bug fix for PHP 8 warnings.

= 1.1.8 =
Upgrade for hiding plugin folder using non-English characters in new folder name.

= 1.1.7 =
Upgrade for fix of two issues  - session and REST API, in Wordpress Admin Site Health section. (WordPress 5.2 and above)

= 1.1.6 =
Upgrade for popup defence against ad blockers like Ad Guard through randomization of css class names and id attributes.
Please 'Restore' your plugin folder before upgrading. 'Hide' plugin again after upgrading is over.

= 1.1.5 =
Upgrade for popup defence against blanket blocking of plugin files by some ad blockers like Ad Guard.

= 1.1.4 =
Upgrade for popup enable/disable bug fixes in post archive pages. Introducing popup theming.

= 1.1.3 =
Upgrade for bug fixes.

= 1.1.2 =
Upgrade for setting popup width for large devices like iPads, laptops and desktops.

= 1.1.1 =
Upgrade for refresh button fix.

= 1.1.0 =
Upgrade to enjoy NEW feature to disable popup on individual pages and posts.

= 1.0.9 =
Upgrade to enjoy NEW responsive popup.

= 1.0.8 =
Upgrade to enjoy NEW refresh button in popup and some bug fixes.

= 1.0.7 =
Upgrade to enjoy third-party shortcodes in the text editor field.

= 1.0.6 =
Code cleanup

= 1.0.5 =
This is a maintenance upgrade to improve the functionality of the previous versions.

= 1.0.4 =
Upgrade to this version to enjoy admin control over the popup close button.
