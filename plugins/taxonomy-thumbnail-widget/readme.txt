=== Taxonomy Thumbnail and Widget ===
Contributors: sunilkumarthz
Tags: 1.5
Donate link: #
Requires at least: 5.8.0
Tested up to: 5.8.0
Stable tag: 1.5.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

TTW plugin is used for add thumbnail option for inbuilt and custom taxonomy terms  and access them with shortcode and widget.

== Description ==
Using Taxonomy Thumbnail and widget plugin users can make thumbnail options for inbuilt and custom taxonomy terms and access via widget (Taxonomy Term List) in the sidebar and also use in page/post using the shortcode.  

Use `<?php if (function_exists('ttw_thumbnail_url')) echo ttw_thumbnail_url(); ?>` to get the url and put it in any img tag or simply use `<?php if (function_exists('ttw_thumbnail_image')) echo ttw_thumbnail_image(); ?>` in (category or taxonomy) template.

Arguments in above functions :

	1. ttw_thumbnail_url($termid , $size);
	2. ttw_thumbnail_image($termid , $size);

= Plugin advantage =

* Easy to configuration.
* Unblockable.
* Plugin supports Chrome, Firefox, Safari and IE
* Woocommerce Compatible
* Shortcode

= Plugin configuration =

* Drag and drop the widget `(Taxonomy Term List)`
* Shortcodes : 
`[TTW_TERMS taxonomy='category' class='taxonomy-term-list']`
	
For Show current post/product taxonomies
	
`[TTW_POST_TERMS_ICON taxonomy="product_tag"  class=""  hide_empty="" post_id=""]`

Note :
post_id is not required
taxonomy is required
	

== Installation ==
You can install Categories Images directly from the WordPress admin panel:

	1. Go to wp-admin -> Plugins > Add New -> and search for 'Taxonomy Thumbnail and Widget'.
	2. Click to install.
	3. Once installed, activate.
	
OR

Manual Installation:

	1. Upload the entire `taxonomy-thumbnail-widget` folder to the `/wp-content/plugins/` directory.
	2. Activate the plugin through the 'Plugins' menu in WordPress.
	3. Go to `wp-admin -> Settings -> TTW Settings` and select taxonomies for which you want thumbnail
	4. And For access taxonomies list in sidebar go to `wp-admin -> Appearance -> Widgets -> Taxonomies List widget`

== Screenshots ==
1. Before Use this plugin shortcode and widget please update settings
2. Output Using shortcode and sidebar widget

== Changelog ==
For more information , any query and your suggestion for plugin  functionality improvement you can write us at sunilkumarthz@gmail.com .

= 1.0 =

First version

= 1.1 =
* Fix - Notice Errors
* Make Shortcode for access taxonomy terms
* Make plugin Woocommerce Compatible

= 1.2 =
* Fix - Notice Errors
* Remove Unused functions and make code smaller and better

= 1.3 =
* Fix - Notice Errors
* Fix JS Errors
* Add  new shortcode

== Upgrade Notice ==
= 1.0 =

First version

= 1.1 =
* Fix - Notice Errors
* Make Shortcode for access taxonomy terms
* Make plugin Woocommerce Compatible

= 1.2 =
* Fix - Notice Errors
* Remove Unused functions and make code smaller and better

= 1.3 =
* Fix - Notice Errors
* Fix JS Errors
* Add  new shortcode

= 1.4 =
* Stablity Test With wordpress 5.1



