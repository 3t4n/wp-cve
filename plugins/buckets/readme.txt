=== Buckets ===
Contributors: matthewordie
Tags: widgets, buckets, acf, advanced custom fields, custom, field, widgets alternative, sidebar
Requires at least: 3.0
Tested up to: 4.8.2
Stable tag: 4.8.2

A widgets alternative that lets you place content anywhere easily.

== Description ==

This plugin is designed as a widgets alternative. However it's uses can be expanded beyond that. It works ok on it's own, but really flys when paired with the Advanced Custom Fields plugin.

I was tired of my widgets not migrating properly. On top of not having full control over simple things. So I built this.

Now you can make a reusable piece of content. Place it right in the middle of another content area. Or even inside another bucket. Additionally you can use the Advanced Custom Fields plugin to create your own sidebars and add new fields to really customize your Buckets.

You can even create a fully modular site using sidebar areas and just throwing buckets in everywhere!

This plugin is made for developers who like to make their client's lives easier (and in turn, their own).

Documentation is available on google docs: https://docs.google.com/document/d/1fDhqmtKWTy-0oxTP8GUg7wdhv-VULNoBFkJbLvKNdzo/edit?usp=sharing

Please feel free to let me know if you have any questions or feedback!


== Installation ==

1. Upload 'buckets' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Read the documentation to learn how to create buckets.

Read the docs for more info: https://docs.google.com/document/d/1fDhqmtKWTy-0oxTP8GUg7wdhv-VULNoBFkJbLvKNdzo/edit?usp=sharing


== Other Notes ==

You can view the documentation here: https://docs.google.com/document/d/1fDhqmtKWTy-0oxTP8GUg7wdhv-VULNoBFkJbLvKNdzo/edit?usp=sharing



== Changelog ==
= 0.3.9 =
[Fixed] - Fixed some styling issues on the bucket field.
[Fixed] - Had an issue where Bucket pages were showing up in google results. Especialy if using an SEO plugin. Changed how the custom post type is setup. Using publicly_queryable set to false. This hides it from plugins like Yoast. If you run into any issues with this please make a posts on the forums or contact me on my website. https://matthewrestorff.com

= 0.3.8 =
[Fixed] - Javascript issue pulling buckets in sidebar field after new ACF 5.5.2 update

= 0.3.7 =
[Fixed] - Conflict with ACF v4 Relationship Field. Now loading scripts for each version seperately.
[Fixed] - Cleaned up some styles on the Sidebar field.
[Fixed] - Styles and Javascript weren't loading properly on ACF Options page.
[Fixed] - Add/Edit on Page feature for ACF v4 to match the ACF v5 version with Front End Fields. Much more stable!
[Added] - Updated the buckets output to use the more up to date have_rows() function from ACF.


= 0.3.5 =
[Fixed] - Buckets errors out if ACF is installed but Flexible Content field wasn't. Buckets will fallback to it's WYSIWYG mode now.

= 0.3.4 =
[Fixed] - ACF function acf_force_type_array was changed to acf_get_array in Buckets Sidebar field

= 0.3.3 =
[Added] - Ability to use custom variables in your shortcodes. Thanks to user summatix! This allows shortcodes to be called like [bucket id="1234" title="My Bucket" myvar="myvalue"]. Inside buckets you can use %%myvar%% to access the custom variable.

= 0.3.2 =
[Fixed] - Fields weren't loading properly with ACF 4

= 0.3.1 =
[Fixed] - TinyMCE button CSS was loading incorrectly

= 0.3.0 =
[Added] - Updated Buckets for ACF Pro v5+
[Fixed] - Rebuilt the Add/Edit Bucket on page function. Now uses ACF Front End Fields and works much more reliably!
[Fixed] - Rebuilt the TinyMCE shortcode plugin
[Fixed] - Lots of minor updates, tweaks and cleanup.

= 0.2.5 =
[Fixed] - Shortcodes will now work when used inside of a bucket when you aren't using ACF.

= 0.2.4 =
[Fixed] - Added prefixes to functions to fix any incompatibility with other plugins.

= 0.2.3 =
[Fixed] - Buckets can be added/edited without leaving the page you're editing again. Now works with the newest ACF Relationship Field.
[Fixed] - Dashboard Icon to match the changes in Wordpress 3.8

= 0.2.2 =
[Fixed] - Various minor fixes and updates
[Added] - Contextual Help Tab that links to the Buckets Documentation on Google Docs
[Fixed] - Add/Edit on page feature is back. Went missing after the upgrade to ACF 4.0.

= 0.2.1 =
[Added] - Better display on Buckets page, now shows shortcode and pages that Buckets are on.
[Fixed] - Now loops through get_bucket function if more then one flex layout are added to a single bucket.


= 0.2 =
[Fixed] - Updated Buckets plugin to work with ACF 4.0.

= 0.1.9.3 =
[Fixed] - Removed ZeroClipboard function because of security exploit found.

= 0.1.9.2 =
[Fixed] - Error in Buckets Area field.

= 0.1.9.1 =
[Added] - Documentation

= 0.1.9 =
[Fixed] - Changed Shortcode "Copy to Clipboard" feature to just use a link. The shortcode text is now selectable (for none flash browsers).
[Added] - Bucket type displays on Bucket Area field.
[Fixed] - Improved Add and Edit functions of the Buckets Area field. No longer saves as draft.
[Fixed] - Cleaned up minor styles.

= 0.1.8 =
[Fixed] - TinyMCE shortcode was inserting incorrectly
[Fixed] - Uploaded deprecated wp_get_single_post function to get_post
[Fixed] - Adjusted Buckets Area field styles
[Fixed] - Bucket Area field output to be slightly more efficient.
[Fixed] - Added wpautop function to content output
[Added] - You can now add new Buckets from the Buckets Sidebar field! Now you can edit all your content from a single page.
[Fixed] - Fixed plugin initilization function to prevent compatibility issues.

= 0.1.7 =
[Added] - Added a TinyMCE button that will let you select a bucket and it will automatically insert the shortcode for you. Must faster for editing your content and easier on your clients.

= 0.1.6 =
[Added] - Automatically setups default fields for ACF when installed
[Fixed] - Minor Bug Fixes

= 0.1.5 =
[Added] - Buckets Area field - The beginning of an easier way for creating your "sidebars".
[Fixed] - Updated to include new ACF has_sub_field function
[Fixed] - Bug with template output
[Fixed] - Bucket icon not displaying

= 0.1.4 =
[Fixed] - Updated plugin and field to work with newest ACF changes.
[Fixed] - Minor Bug Fixes
