=== SmugMug Embed ===
Contributors: twicklund
Tags: SmugMug, Smug Mug, images, embed, integration
Requires at least: 4.8
Tested up to: 5.5.1
Stable tag: 3.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows users to search and embed images into posts or pages directly from their SmugMug accounts.

== Description ==

Allows users to search and embed images into posts or pages directly from their SmugMug accounts.

This plugin adds a custom block into the post/page editor called "Embed From SmugMug". Post and page authors can navigate available galleries from their SmugMug account and choose images to add. 

The plugin first authorizes the user to a smug mug account.  In the settings screen, the administrator decides which galleries will be available to the author, the sizes available, and several other options.
 

== Installation ==

1. Unzip the file content into the /wp-content/plugings/smugmugembed directory.
2. Activate the SmugMug Embed plugin in the Plugins menu. 
3. After the plugin is activated, you will find SmugMug Embed in the Plugins menu of the Admin Panel.

== Frequently Asked Questions ==

= How does an admin decide which galleries to display?
In the "Gallery Options" tab on the settings page of SmugMug Embed, administrators choose which galleries they want to display on the embed form by navigating their SmugMug folder structure and selecting galleries.

= No galleries are being displayed on the settings page...What is happening?
The plugin must first authenticate with smugmug.  If this has not been done, please click the "Start Activation With SmugMug" button.
If the plugin has been authenticated in the past and something is still wrong, click the "Delete SmugMug Authentication" button on the setting page for SmugMug Embed.

= What is the process to authenticate with SmugMug?
1.  Click the "Start Authentication With SmugMug" button.
2.  Click the "Click here to log into SmugMug to approve access".  This will open a new window on SmugMug.com which will ask the user to verify credentials.
3.  Enter the username and password when prompted (if not already logged into SmugMug).  Once complete, the screen will display "SmugMugEmbed was added to your Authorized Applications" and will take you back to the settings page on your website.

= Will the Demo version expire?
There is no expiration date for the demo version. However, the demo version only allows one image to be embedded with the plugin per page or post.  Also, there is a watermark placed on all embeded images.  The watermark will be be removed automatically when you upgrade to the full version. Upgrade to the full version (including 1 year of support) to unlock all the features of SmugMug Embed.
= How can I get the full version of the SmugMug Embed plugin?
Simply follow the upgrade link on the settings page to purchase a support license. An email will be sent with your password. Plug that password along with your email address into the SmugMug Embed settings page.  The plugin update will be available through the standard plugin mechanism within 12 hours of purchase.
== Screenshots ==



== Changelog ==
= 3.13 =
* Fixed an issue that would not let certain users register the full version
* Fixed an issue that caused a conflict with the Appearence -> Customizer when using Elementor

= 3.12 =
* Fixed an issue that prevented the images from being displayed in the public view if no Link Target was chosen.
* Fixed an issue that prevented SmugMug Authorization if a different Site Address was used from the Wordpress Address

= 3.10 =
* NEW FEATURE - We've added in Link Targets. Now it is possible to direct your audience to one of 4 configured targets when they click on an image on a post or page. Simply click the link button on the block toolbar for the SmugMug Embed Block and select the target when embeding an image from SmugMug. Plus, administrators can select the default Link Target and whether or not the link opens in a new tab or replaces the current content.

= 3.01 =
* Fixed an issue in the demo version that would not authenticate with license server on multisite installs
* Fixed an issue in the demo version where an image would not show up on a published page or post unless the alignment button was pressed in the block on the editor page

= 3.0 =
* Complete rewrite using SmugMug's latest api (2.0)
* Added support for the new Wordpress block editor
* Some features have been removed as they don't make sense with modern Wordpress versions

= 2.0 =
* Added support for XL, X2Large, and X3Large
* Added functionality to reload images and galleries on demand.  
  These are cached once hit and the cache is held hourly to increase 
  performance on large galleries.  Users have requested the ability 
  to reload this cache on demand
* Added functionality to uncheck images once inserted into a post.  
  Before, the same images just inserted would still be checked 
  if a user selected to add more images.  However, the settings will still remain.
* Added the ability to set the default image alignment in the settings tab 
* Fixed an issue which occurred when a user selected a gallery from the 
  drop down in the media browser, but then chose "Select Gallery".  
  The system would throw an error before
* Added ability to select all images from the media chooser SmugMug Embed form
* If a gallery had a hidden image, then the plugin would not embed any 
  image after the hidden image.  This is resolved
* SME now respects the SmugMug hidden flag on photos
* SME now has the ablitity to create shortcode that will use Wordpress's out of the box galleries style
* Added a progress indicator on several events in the Embed from SmugMug form

= 1.0 =
* Added ability to link to Cart in SmugMug.  The image is automatically added to the cart!
* Added ability to open the link in a new window
* Fixed several labels in both the admin panel and the embed form to be more user friendly

= 0.93 =
* Fixed an issue causing the white screen of death at plugin start (thanks Lord Laughter)
* Fixed an issue with pathing which threw errors on WP installs not at the root directory

= 0.92 =
* adding uninstall.php to clean up options

= 0.9 =
* SmugMug Embed initial release


== More Information ==
For more information, please visit https://www.wicklundphotography.com/smugmug-embed-wordpress-plugin/

