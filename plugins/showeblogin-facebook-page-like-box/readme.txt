=== Showeblogin Social Plugin ===
Contributors: SureshPrasad
Tags: Facebook, Facebook Page Plugin, Facebook Plugin, Facebook Social, Social, Showeblogin, Social Widget.
Requires at least: 4.1
Requires PHP: 5.6
Tested up to: 6.3.1
Stable tag: 6.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Brings the power of simplicity to display or embed Facebook Page Plugin (Like Box) widget into your WordPress website by using latest Graph API Version 11.0.

== Description ==
Facebook like box is one of the most important part of social optimization. You can't ignore the power of more than 3 billion monthly active users on Facebook. Therefore, it's time to promote your Websites/ Blogs on Facebook and increase your site users on social media.

The **Showeblogin Social Plugin** is a special version of Facebook Like box created with HTML5 Attribute and designed for *Facebook Pages*. It allows WordPress site owners to promote their Facebook Pages and embed a simple feed of content from a Page into websites by inserting widget in the posts, pages, sidebar or even footer. You may select your preferred language (Default is English) for Facebook Page Like box.

This plugin works with responsive, fluid and static layouts and lets you easily embed and promote any Facebook Page on your WordPress websites. With the help of this plugin, your live visitors can like, comment and share all your Facebook posts in addition to liking and sharing your Facebook Page to their friends without having to leave your website. Additionally, you may create call to action button next to like button in your Facebook page like box.

= Features =
Following are the unique features of **Showeblogin Social Plugin**:-

* Show/Hide Small Header.
* Enable lazy-loading mechanism.
* Adapt to plugin container width.
* Show FB Like box for any Facebook Page.
* Show/Hide Facebook page Header Cover Photo.
* Show/Hide latest posts from Facebook Page's timeline.
* Change width and height in accordance with your website.
* Show/Hide the custom call to action button (if available).
* It is **100% forever FREE plugin** i.e. you never have to pay for it.
* Use [Shortcode](https://wordpress.org/plugins/showeblogin-facebook-page-like-box/other_notes/ "Display Facebook LikeBox anywhere") to show Facebook Page Like box anywhere in your site.
* Show/Hide Facebook profile photos when someone like your facebook page.
* Add Facebook page Plugin to any widget area of sites with custom features.
* Select your preferred language (Default is English) from **143 different languages**.
* Add Multiple Tabs to render i.e. timeline, events, messages:
	* **Timeline Tab:** This feature will show the most recent posts of your Facebook Page timeline.
	* **Events Tab:** People can follow your page events and subscribe to events from the plugin.
	* **Messages Tab:** This feature enable your site visitors to message your page directly from your website.

= Advanced Features =
Once you activate this plugin, you may also be able to insert [multiple social plugins](https://wordpress.org/plugins/showeblogin-facebook-page-like-box/faq/ "Like, share and follow button") in your sites:

* Insert Like Buttons, Share Button, Send Button and follow button in any part of your site.
* Embed any post from Facebook Pages and profiles into the content of your web site or web page.
* Insert Facebook **Comment Box** and Lets people comment on content on your website using their Facebook account.

= Further Reading =
If you have any query, please ask us in [Support section](https://wordpress.org/support/plugin/showeblogin-facebook-page-like-box "Have you any Question?"). For more info, check out [this tutorial](http://www.superwebtricks.com/facebook-page-wordpress-plugin/ "Details about Showeblogin Social Plugin widget for WordPress sites").

= Ratings & Reviews =
It's my humble request that after using, please [rate this Facebook Page Plugin](https://wordpress.org/support/view/plugin-reviews/showeblogin-facebook-page-like-box?rate=5#postform "Please rate this plugin and submit a review") in accordance with your experince after using this plugin.

> #### Follow Us
> [Blog](https://www.superwebtricks.com/) | [Twitter](https://twitter.com/SuperWebTricks)  | [YouTube](https://www.youtube.com/user/Showeblogin) | [Pinterest](https://www.pinterest.com/sureshprasad/showeblogin/) | [FaceBook](https://www.facebook.com/SuperWebTricks)

== Installation ==
Installation of Facebook Page Plugin to a Website. You may Install **Showeblogin Like Share Box Plugin** either via WordPress.org plugin directory or by uploading files to your server.

= From your WordPress site Dashboard =

**Step-1:** Go to "Plugins" menu from your WordPress site left sidebar and click "Add New" link.

**Step-2:** Search for "Showeblogin Social Plugin".

**Step-3:** Click on 'Install Now' button.

**Step-4:** Now, click on 'Activate' link.

**Step-5:** Finally, from widget area of your WordPress theme add Facebook Page Plugin and Enjoy!


= By uploading files to Server =

**Step-1:** Download the latest version of Showeblogin Social Plugin zip file.

**Step-2:** Unzip or Extract the plugin file.

**Step-3:** Upload the entire folder named showeblogin-facebook-page-plugin into your server under plugin folder (/wp-content/plugins).

**Step-4:** Now, login to your WordPress site and activate the Showeblogin Social Plugin and Enjoy!

= Shortcode =
`[swt-fb-likebox 
	url="https://www.facebook.com/SuperWebTricks"
	width="340"
	height="500"
	tabs="timeline,events,messages"
	hide_cover="false"
	show_faces="true" 
	hide_call_action="true"
	small_header="false" 
	adapt_container_width="true"]`

See [Other Notes](https://wordpress.org/plugins/showeblogin-facebook-page-like-box/other_notes/ "Additional Notes for using Showeblogin Social Plugin") for detailed instructions on shortcode.

Learn more (and get detailed instructions) in our [Tutorial guide](https://www.superwebtricks.com/facebook-page-wordpress-plugin/ "Step by Step guide on how to install Showeblogin Social Plugin widget in WordPress").

== Frequently Asked Questions ==
= 1. How do You integrate multiple Social Plugins? =
Once you activate this plugin and add like box widget in the sidebar, you can easily integrate any Social Plugin. There is no need to insert the *Facebook SDK for JavaScript* again into your web page.

**a) Example of a Like Button**: 
You can paste these code anywhere (Say in sidebar/Footer widget or any Posts/Pages) in your site to show Facebook like button.
`<!-- Showeblogin FB like button START --><div class="fb-like"
		data-layout="standard" 
		data-action="like"
		data-share="false"
		data-width="300"		
		data-show-faces="true">
	</div><!-- Showeblogin FB like button END -->`
* You may set data-share to true to include a share button beside the Like button.
* You may replace *standard* with button_count, box_count or button.
* The width of like button is subject to the minimum:
	* **standard** Minimum width: 225 pixels.	
	* **button_count** Minimum width: 90 pixels.
	* **box_count** Minimum width: 55 pixels.
	* **button** Minimum width: 47 pixels.

**b) Example of a Follow Button**:
You can paste these code anywhere (Sidebar/Footer/Posts/Pages) to show Facebook follow button.
`<!-- Showeblogin FB follow button START --><div class="fb-follow" 
		data-href="https://www.facebook.com/CA.SureshPrasad" 
		data-colorscheme="light"
		data-layout="standard" 
		data-width="225"
		data-show-faces="true">
	</div><!-- Showeblogin FB follow button END -->`
* You may replace *standard* layout with button_count or box_count.
* The width of follow button is subject to the minimum:
	* **standard** Minimum width: 225 pixels.	
	* **button_count** Minimum width: 90 pixels.
	* **box_count** Minimum width: 55 pixels.
	
**c) Example of Facebook Comment Box**:
You can paste these codes in PHP files (Posts/Pages) or even sidebar to show Facebook comment box.	
`<!-- Showeblogin FB comment box START --><div class="fb-comments" 
		data-colorscheme="light"
		data-numposts="5"
		data-width="100%"
		data-order-by="social">
	</div><!-- Showeblogin FB comment box END -->`
* You may change color scheme to dark from the light.
* You may replace 100% width to any pixel value like 450px. However, the minimum width supported by the comments plugin is 320px.
* order data *social* can be replaced with "reverse_time", or "time". 

**Note**: You should assign your Facebook Account to be admin to moderate comments. To do that, simply include the following meta tag in the `<head>` section of your site.
`<meta property="fb:admins" content="{REPLACE_WITH_YOUR_FACEBOOK_USER_ID}"/>`

If you have any other query, you may ask your query in [Support section](https://wordpress.org/support/plugin/showeblogin-facebook-page-like-box "Have you any Question?") or [Tweet Showeblogin](https://twitter.com/SuperWebTricks "Feel free to ask now") and I will reply as soon as possible. 

== Screenshots ==
1. Activate Showeblogin Social Plugin
2. Ready to add Facebook Page Like Box
3. Customization of FB Page Like Box
4. Showeblogin FB Page Like Box with Small Header
5. Showeblogin FB Page Like Box without Header cover and stream
6. Showeblogin FB Page Like Box with Call to Action Button and timeline/Posts feed.
7. Showeblogin FB Page Like Box with Share Button and Message Tab.

== Use Shortcode ==
Users had requested to make available shortcode to use this plugin. Accordingly, I have made it possible to show your facebook page Social anywhere in your website through shortcode.

<strong>Default Shortcode: </strong>
Use this default shortcode to show facebook Social in posts, pages and any other place.
`[swt-fb-likebox]`

<strong>Customized Shortcode: </strong>
Following shortcode can be used to display your own facebook page with customized features to control on its size, header cover, call to action button, showing faces and post feeds etc.
`[swt-fb-likebox 
	url="https://www.facebook.com/AUBSP"
	width="340"	height="500"
	tabs="timeline,events,messages"
	hide_cover="false"
	show_faces="true"
	hide_call_action="true"
	small_header="false"
	data_lazy="true"
	adapt_container_width="true"]`

You may also use this shortcode directly in PHP file.
`<?php echo do_shortcode( '[swt-fb-likebox 
	url="https://www.facebook.com/GSTact2017"
	width="340" height="500" 
	tabs="timeline,messages" 
	hide_cover="false"
	show_faces="true"
	hide_call_action="false"
	data_lazy="true"
	small_header="false"
	adapt_container_width="true"]' ); ?>`

Thus, after successfully activation of this plugin you may use the above shortcode to your posts as well as pages by just replacing the URL with your own facebook page address.

== Changelog ==


= 6.7 =
* Tested up to WordPress 6.3.1
* Upgrade API Version 18.0
* some minor bug fixed.

= 6.6 =
* Tested up to WordPress 6.0
* Upgrade API Version 14.0
* Minnor bug fixed.

= 6.5 =
* Tested up to WordPress 5.8
* Enhanced for fast loading.
* Upgrade API Version 11.0

= 5.1 =
* Tested upto WordPress 5.5.1
* lazy-loading features enabled
* Minnor bug fixed.

= 5.0 =
* Tested upto wordpress 5.5
* Grapgh API updraged to 8.0
* minnor bug fixed.

= 4.0 =
* Tested upto wordpress 5.1.1
* Grapgh API updraged to 3.2
* Bug fixed for responsive sites.

= 3.5 =
* Various minnor bug fixed.

= 3.0 =
* Added new features to insert like share and send button along with comment box for your site.
* Added new features to support 142 languages.

= 2.5 =
* Added new feature to use shortcode.

= 2.0 =
* Added new feature to show Small Header.
* Added new feature to Adapt to plugin container width.
* Added new feature to Hide the custom call to action button (if available).

= 1.0 =
Initial release

== Upgrade Notice ==
You should use the latest Showeblogin Social Plugin.