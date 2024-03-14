=== WP Post Nav ===
Contributors: jo4nny8
Tags: post navigation, navigation, product navigation, post nav, custom post navigation, previous nav, next nav
Requires at least: 6.0
Tested up to: 6.3
Stable tag: 2.0.3
Requires PHP: 8.0
Text Domain: wp-post-nav
Domain Path: /languages
License:            GPL-2.0+
License URI:        http://www.gnu.org/licenses/gpl-2.0.txt

Simple posts navigation plugin.  Easily navigate between posts, pages, products and custom post types in/out the same category.

== Description ==

WP Post Nav is a simple to use post navigation plugin which allows easy navigation between all types of posts and post types.

Upon activation, navigate to the settings page and choose the post types you wish the next / previous links to display on, you custom CSS styles and save to make your custom modifications.

When visiting the front end of your website, on each post type activated, handy navigation arrows will appear on the screen to navigate to the next / previous post.

Never use another navigation plugin as WP Post Nav does it all.  Navigates all post types (including custom). Works with ANY theme or page builder with the built in shortcode.

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'WP Post Nav'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard
5. Customise the settings by visiting 'settings' in the WordPress admin menu, then WP Post Nav

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `wp-post-nav.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `wp-post-nav.zip`
2. Extract the `wp-post-nav` directory to your computer
3. Upload the `wp-post-nav` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Frequently Asked Questions ==

= How To Use WP Post Nav =

Activate the plugin then go to "Settings / WP Post Nav" menu and just check the options you want and modify the styles to match your theme and click save.

= What Does WP Post Nav Do? =

Wp Post Nav gives your users the ability to navigate easily between your next / previous posts (in or out of the same category / taxonomy).

= How Does It Work? =

WP Post Nav fetches all the custom posts types from your database, then shows them all in a handy list on the settings screen.
Simply tick each post type you wish to display the next / previous navigation on and change the styles to match your theme.

= Does it work with 'my theme' / 'theme builder'? =

We have tested WP Post Nav on many different themes, page builders and plugins to eliminate as many issues as possible.
We have created default options for specific built in and major post types to ensure that it will work with as many options as possible.

= It doesn't work with my custom post type = 

When showing the next / previous posts from the 'same category' we detect the category the post is assigned to.  If this is empty (or for custom posts types that do not have taxonomies assigned) we turn off the ability to show the next / previous post from the same category and it defaults to showing the next / previous post based on post it, excluding the category selection.  The best way to make WP Post Nav work, is to ensure that all posts and custom posts types have categories assigned, OR by turning off the 'show posts from the same category' option.

= I have a suggestion / want a custom hook or modification =

Drop a question in the plugin support option above or email me @ contact@wppostnav.com.

== Screenshots ==

1. Admin Screen
2. Instruction Screen
3. Additional Options Shown
4. Front End Default Display

== Changelog ==

= 2.0.3 =
Compatability with WordPress 6.3

= 2.0.2 =
Compatability with PHP 8
Compatability with WordPress 6.1.1
Minor changes in the admin side of the plugin (textual interface changes and wording)

= 2.0.1 =
Fixed an issue with Yoast SEO compatibility
Confirmed WordPress 5.7 compatability

= 2.0.0 =
Warning, this is a major update. You should create a full backup of your site prior to upgrading.
Introduced the most requested features:
* Added the option to use the SEO Framework Primary Category
* Added an option to FORCE primary category display
* Added a NEW shortcode option to display the navigation where and however you want
Fixed some minor errors
Modified some of the code
Confirmed compatibility with WordPress 5.6.2

= 1.0.2 =
Fixed an error where the instructions page was creating a headers already sent error on some hosts

= 1.0.1 = 
Introduced translation compatibility.  You can now translate the Heading and Category into your own language
Update for WordPress 5.6

= 1.0.0 =
Warning, this is a major update.  You should create a full backup of your site prior to upgrading.

* Switched the display to tabbed navigation
* Added sidebar
* Added numerous options, including WooCommerce Out Of Stock Option (If installed), Yoast SEO Primary Category (If Installed)
* Upgraded the options array to multidimensional
* Created function to automatically import changes from previous versions
* Added 'Next / Previous' Post Wording to front end
* Added developer hooks / filters to make custom modifications
* Various bug fixes / modifications to core code

= 0.1.2 =
Added an option to switch the display sides of the nav buttons - Requested Feature.

= 0.1.1 =
Fixed and issue where WooCommerce products weren't working correctly when 'in same category' was checked.
Added an option to exclude sold products from navigation (not skips products out of stock) - Requested Feature.

= 0.1.0 =
Compatibility check with WordPress 5.3
PHP 7.3 Compatibility Check
Modified CSS inclusion to stop PHP execution from dynamic PHP file
Image File Optimisations (compressed fallback and removed background)

= 0.0.1 =
Initial Release

== Upgrade Notice ==

= 1.0.0 =
This is a major update and a full backup should be undertaken PRIOR to upgrading.

= 0.1.2 =
This update includes an option to alter the sides that next / previous navigation arrows are displayed.

= 0.1.1 =
This minor modification fixes a bug for WooCommerce products.  It also adds a requested feature to exclude sold products from the navigation array.

= 0.1.0 =
Minor code tweaks including CSS optimisations. Updated readme.txt and default_fallback image.

== Translations ==

* English - default