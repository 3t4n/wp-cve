=== a3 Responsive Slider ===
Contributors: a3rev, mrnugyencongtuan, a3rev Software
Tags: responsive slider, wordpress image slider, responsive image slider, image gallery
Requires at least: 6.0
Tested up to: 6.4.1
Stable tag: 2.3.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A robust and versatile responsive image slider for WordPress.


== Description ==

There are hundreds of image sliders for WordPress. We know because we have used a lot and none have ever completely been what we wanted. We built our own because we wanted a versatile and robust responsive slider that we could use in our client website work and more importantly that our clients could easily use on their site.

a3 Responsive Slider is inspired by and powered by the [malsup Cycle2](https://github.com/malsup/cycle2) JavaScript.


= FEATURES =
* Fully mobile responsive.

* Touch Swipe support in mobiles.

* Images of any size - scaled to show perfectly no matter what size is uploaded.

* WordPress taxonomy. Manage Sliders (like posts). Folders (like categories)

* Fully Customize the Slider Skins Sass #dynamic {stylesheets}. No coding required.

* Add sliders by Widget.

* Embed sliders by shortcode button on every post, custom post type, pages.

* Slider Shortcode alignment (just like WordPress images)

* Slider Shortcode Dimension settings (Over-Ride Skin Dimension setting)

* Shortcode tracking. See at a glance where each slider is embed by shortcode.

* Remove sliders embedded by shortcode from the Slider Embed tab (removes the shortcode)

* Slider images uploaded to WordPress Media Library.

* There are 8 different image transition effects.

* Transition effects set on each slider.

* Extensively tested on live client sites before release



= LAZY LOAD =



Install the [a3 Lazy Load plugin](https://wordpress.org/plugins/a3-lazy-load/) to apply Lazy Load to your sliders first load. a3 Lazy Load will allow you to apply Lazy Load to any or all elements of your site for PCs and Mobiles including your a3 Responsive Sliders embedded by shortcode or added by widgets.



= IMAGE TRANSITIONS =


Up to 8 different image effects. In addition to the normal Scroll horizontal and Vertical image transition the plugin includes the transition effects.



* Flip - [see demo](http://jquery.malsup.com/cycle2/demo/flip.php)

* Shuffle - [see demo](http://jquery.malsup.com/cycle2/demo/shuffle.php)

* Tile Slide and Blind - [see demo](http://jquery.malsup.com/cycle2/demo/shuffle.php)

= PREMIUM VERSION =


This plugin has an advanced [a3 Responsive Slider Premium Version](http://a3rev.com/shop/a3-responsive-slider/). The Premium version advanced features include:

* Youtube Slider feature


* Ken Burns Transition effect

* 4 Additional Slider Skins 
* Touch Mobile Skin 

= CONTRIBUTE =

When you download a3 Responsive Slider, you join our the a3rev Software community. Regardless of if you are a WordPress beginner or experienced developer if you are interested in contributing to the future development of this plugin head over to the a3 Responsive Slider [GitHub Repository](https://github.com/a3rev/a3-responsive-slider) to find out how you can contribute.

Want to add a new language to a3 Responsive Slider! You can contribute via [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/a3-responsive-slider)
	


== INSTALLATION ==

= Minimum Requirements =

* PHP version 7.4 or greater is recommended
* MySQL version 5.6 or greater is recommended

= Automatic installation =



Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of a3 Responsive Slider, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "a3 responsive slider" and click Search Plugins. Once you have found our plugin you can install it by simply clicking Install Now. After clicking that link you will be asked if you are sure you want to install the plugin. Click yes and WordPress will automatically complete the installation.



= Manual installation =



The manual installation method involves down loading our plugin and uploading it to your web server via your favourite FTP application.



1. Download the plugin file to your computer and unzip it

2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installations wp-content/plugins/ directory.

3. Activate the plugin from the Plugins menu within the WordPress admin.



== Screenshots ==


1. Go to Responsive Slider menu - Slider Skins and create your customize your skin style.

2. Go to Responsive Slider menu - Add New menu and add new slider.

3. View and manage all sliders from the All Sliders menu, just like posts.

4. Add sliders by the a3 Responsive slider widget.

5. Add sliders by shortcode from the Sliders button above the WordPress text editor.




== Usage ==


1. Install and activate the plugin


2. Go to a3 Responsive Slider menu on your wp-admin dashboard.
3. Go to Slider Skins and create your own slider skin style.


4. Add New Slider - create your first slider


5. Go to Widgets - find a3 responsive slider widget and apply.


6. Use the Sliders button on post and page text editor to add slider by shortcode.


7. Enjoy.


== Changelog ==

= 2.3.1 - 2023/11/23 =
* This maintenance release has plugin framework updates for compatibility with PHP 8.1 onwards, plus compatibility with WordPress 6.4.1
* Tweak - Test for compatibility with WordPress 6.4.1
* Framework - Set parameter number of preg_match function from null to 0 for compatibility with PHP 8.1 onwards
* Framework - Validate empty before call trim for option value

= 2.3.0 - 2023/01/03 =
* This feature release removes the fontawesome lib and replaces icons with SVGs plus adds Default Topography option to font controls.
* Feature - Convert icon from font awesome to SVG
* Feature - Update styling for new SVG icons
* Plugin Framework - Update typography control from plugin framework to add support for Default value
* Plugin Framework - Default value will get fonts set in the theme.
* Plugin Framework - Change generate typography style for change on typography control
* Plugin Framework - Remove fontawesome lib

= 2.2.2 - 2022/11/21 =
* This maintenance release has 1 bug fix
* Fix - Show the panel settings page if have combine of premium and free options on same page.

= 2.2.1 - 2022/11/01 =
* This maintenance release has a security vulnerability patch, plus compatibility with WordPress major version 6.1.0
* Tweak - Test for compatibility with WordPress 6.1
* Security - This release has a patch for a security vulnerability

= 2.2.0 - 2022/06/25 =
* This release has a security patch for a Stored XSS zero-day vulnerability in all previous versions. 
* Security - Patched Stored XSS zero-day vulnerability.
* Props - Synack Red Team member Kernelsndrs for finding and reporting the vulnerability.

= 2.1.0 - 22/05/24 =
* This release is for compatibility with WordPress major version 6.0 plus includes various tweaks to harden the plugins security. 
* Tweak - Test for compatibility with WordPress 6.0
* Framework – Upgrade Plugin Framework to version 2.6.0
* Security - Various code hardening tweaks.
* Security - Escape all $-variable
* Security - Sanitize all $_REQUEST, $_GET, $_POST
* Security - Apply wp_unslash before sanitize

= 2.0.13 - 2022/01/21 =
* This is a maintenance release for compatibility with WordPress major version 5.9
* Tweak - Test for compatibility with WordPress 5.9

= 2.0.12 - 2021/11/20 =
* This maintenance release has check for compatibility with PHP 8.x
* Tweak - Test for compatibility with PHP 8.x

= 2.0.11 - 2021/07/19 =
* This maintenance release has code tweaks for WordPress 5.8 compatibility plus Security hardening.
* Tweak - Test for compatibility with WordPress 5.8
* Tweak - Skip version 2.0.10 to prevent PHP misread of the version
* Security - Added escaping for the shortcode parameters
* Security - Add more variable, options and html escaping
* Security - Get variable via name instead of use extract

= 2.0.9 - 2021/07/12 =
* This small maintenance release contains a security patch.
* Security - Added escaping for the shortcode parameters

= 2.0.8 - 2021/03/17 =
* This maintenance release updates 23 deprecated jQuery functions for compatibility with the latest version of jQuery in WordPress 5.7
* Tweak - Update JavaScript on plugin framework for compatibility with latest version of jQuery and resolve PHP warning event shorthand is deprecated.
* Tweak - Replace deprecated .change( handler ) with .on( 'change', handler ) 
* Tweak - Replace deprecated .change() with .trigger('change') 
* Tweak - Replace deprecated .focus( handler ) with .on( 'focus', handler )
* Tweak - Replace deprecated .focus() with .trigger('focus')
* Tweak - Replace deprecated .click( handler ) with .on( 'click', handler )
* Tweak - Replace deprecated .click() with .trigger('click')
* Tweak - Replace deprecated .select( handler ) with .on( 'select', handler )
* Tweak - Replace deprecated .select() with .trigger('select') 
* Tweak - Replace deprecated .blur( handler ) with .on( 'blur', handler ) 
* Tweak - Replace deprecated .blur() with .trigger('blur') 
* Tweak - Replace deprecated .resize( handler ) with .on( 'resize', handler ) 
* Tweak - Replace deprecated .submit( handler ) with .on( 'submit', handler ) 
* Tweak - Replace deprecated .scroll( handler ) with .on( 'scroll', handler ) 
* Tweak - Replace deprecated .mousedown( handler ) with .on( 'mousedown', handler ) 
* Tweak - Replace deprecated .mouseover( handler ) with .on( 'mouseover', handler ) 
* Tweak - Replace deprecated .mouseout( handler ) with .on( 'mouseout', handler )
* Tweak - Replace deprecated .keydown( handler ) with .on( 'keydown', handler ) 
* Tweak - Replace deprecated .attr('disabled', 'disabled') with .prop('disabled', true) 
* Tweak - Replace deprecated .removeAttr('disabled') with .prop('disabled', false) 
* Tweak - Replace deprecated .attr('selected', 'selected') with .prop('selected', true) 
* Tweak - Replace deprecated .removeAttr('selected') with .prop('selected', false) 
* Tweak - Replace deprecated .attr('checked', 'checked') with .prop('checked', true) 
* Tweak - Replace deprecated .removeAttr('checked') with .prop('checked', false)

= 2.0.7 - 2021/03/09 =
* This maintenance release is for compatibility with WordPress 5.7
* Tweak - Test for compatibility with WordPress 5.7
* Tweak - Use new function wp_getimagesize of WP instead of getimagesize

= 2.0.6 - 2020/12/30 =
* This is an important maintenance release that updates our scripts for compatibility with the latest version of jQuery released in WordPress 5.6
* Tweak - Update JavaScript on plugin framework for work compatibility with latest version of jQuery
* Fix - Replace .bind( event, handler ) by .on( event, handler ) for compatibility with latest version of jQuery
* Fix - Replace :eq() Selector by .eq() for compatibility with latest version of jQuery
* Fix - Replace .error() by .on( “error” ) for compatibility with latest version of jQuery
* Fix - Replace :first Selector by .first() for compatibility with latest version of jQuery
* Fix - Replace :gt(0) Selector by .slice(1) for compatibility with latest version of jQuery
* Fix - Remove jQuery.browser for compatibility with latest version of jQuery
* Fix - Replace jQuery.isArray() by Array.isArray() for compatibility with latest version of jQuery
* Fix - Replace jQuery.isFunction(x) by typeof x === “function” for compatibility with latest version of jQuery
* Fix - Replace jQuery.isNumeric(x) by typeof x === “number” for compatibility with latest version of jQuery
* Fix - Replace jQuery.now() by Date.now() for compatibility with latest version of jQuery
* Fix - Replace jQuery.parseJSON() by JSON.parse() for compatibility with latest version of jQuery
* Fix - Remove jQuery.support for compatibility with latest version of jQuery
* Fix - Replace jQuery.trim(x) by x.trim() for compatibility with latest version of jQuery
* Fix - Replace jQuery.type(x) by typeof x for compatibility with latest version of jQuery
* Fix - Replace .load( handler ) by .on( “load”, handler ) for compatibility with latest version of jQuery
* Fix - Replace .size() by .length for compatibility with latest version of jQuery
* Fix - Replace .unbind( event ) by .off( event ) for compatibility with latest version of jQuery
* Fix - Replace .unload( handler ) by .on( “unload”, handler ) for compatibility with latest version of jQuery

= 2.0.5 - 2020/12/08 =
* This maintenance release has tweaks for compatibility with WordPress major version 5.6 and PHP version 7.4.8
* Tweak - Test and Tweak for compatibility with PHP 7.4.8
* Tweak - Test for compatibility with WordPress 5.6

= 2.0.4 - 2020/08/08 =
* This maintenance release is for compatibility with WordPress major version 5.5 and WooCommerce 4.3.1.
* Tweak - Test for compatibility with WordPress 5.5
* Tweak - Test for compatibility with WooCommerce 4.3.1

= 2.0.3 - 2020/04/08 =
* This maintenance release is a tweak to the mobile template.
* Tweak - Remove the cog bottom border from the mobile template

= 2.0.2 - 2020/04/03 =
* This maintenance release adds Travis Unit build tests and compatibility with PHP Code Checker
* Tweak - Run Travis CI unit build tests for PHP compatibility issues with PHP 7.0 to 7.4
* Dev - Add ignore line comment to the line code for passed via PHP Compatibility Checker

= 2.0.1 - 2020/04/01 =
* This maintenance release is for compatibility with WordPress 5.4 and PHP 7.4
* Tweak - Test for compatibility with WordPress 5.4
* Fix - Update global ${$this- to $GLOBALS[$this to resolve 7.0+ PHP warnings
* Fix - Update global ${$option to $GLOBALS[$option to resolve 7.0+ PHP warnings
* Fix - Update less PHP lib that use square brackets [] instead of curly braces {} for Array, depreciated in PHP 7.4
* Fix - Validate to not use get_magic_quotes_gpc function that are depreciated in PHP 7.4

= 2.0.0 - 2020/01/010 =
* This feature release completes the full refactor (frontend and backend) of the plugins PHP to Composer, 1 bug fix and compatibility with WordPress 5.3.2
* Feature - Plugin Framework fully refactored to Composer for cleaner code and faster PHP code on the admin panels
* Tweak - Update plugin for compatibility with new version of plugin Framework
* Tweak - Test for compatibility with WordPress 5.3.2
* Fix - Update javascript for include Alt option when adding new images to a slider

= 1.9.0 - 2019/12/13 =
* This feature release has a lot. PHP is upgraded to Composer PHP Dependency Manager, a full security review, new z-index option and compatibility with WordPress 5.3.1
* Feature - On settings tab add z-index setting option for each slider
* Feature - Plugin fully refactored to Composer for cleaner and faster PHP code
* Tweak - Remove the hard coded PHP error_reporting display errors false from compile sass to css
* Tweak - Test for compatibility with WordPress 5.3.1
* Dev - Replace file_get_contents with HTTP API wp_remote_get
* Dev - Ensure that all inputs are sanitized and all outputs are escaped

= 1.8.9 - 2019/08/01 =
* This maintenance upgrade is to fix a style conflict with fontawesome icons
* Fix - fontawesome icons not able to get correct style on frontend when the fontawesome script is loaded on the page by theme or another plugin.

= 1.8.8 - 2019/06/29 =
* This is a maintenance upgrade to fix a potentially fatal error conflict with sites running PHP 7.3
* Fix - PHP warning continue targeting switch is equivalent to break for compatibility on PHP 7.3

= 1.8.7 - 2019/05/21 =
* This maintenance update adds support for ALT text on images
* Tweak - Add Support For image ALT Text
* Dev - Update plugin database table with new alt field

= 1.8.6 - 2019/04/01 =
* This maintenance update resolves a PHP 7.2 issue and compatibility check for WordPress upcoming 5.2 release 
* Tweak - Test for compatibility with WordPress 5.2
* Fix - Validate variable is array to resolve warning in PHP 7.2

= 1.8.5 - 2019/01/04 =
* This maintenance update is for compatibility with WordPress 5.0.2 and PHP 7.3. It also includes performance updates to the plugin framework.
* Tweak - Test for compatibility with WordPress 5.0.2 and WordPress 4.9.9
* Tweak - Create new structure for future development of Gutenberg Blocks
* Framework - Performance improvement.  Replace wp_remote_fopen  with file_get_contents for get web fonts
* Framework - Performance improvement. Define new variable `is_load_google_fonts` if admin does not require to load google fonts
* Credit - Props to Derek for alerting us to the framework google fonts performance issue
* Framework - Register style name for dynamic style of plugin for use with Gutenberg block
* Framework - Update Modal script and style to version 4.1.1
* Framework - Update a3rev Plugin Framework to version 2.1.0
* Framework - Test and update for compatibility with PHP 7.3

= 1.8.4 - 2018/10/26 =
* This maintenance update fixes 1 bug and checks for compatibility with WordPress 4.9.8
* Tweak - Test for compatibility with WordPress 4.9.8
* Fix - parse correct image url to getimagesize

= 1.8.3 - 2018/05/26 =
* This maintenance update is for compatibility with WordPress 4.9.6 and the new GDPR compliance requirements for users in the EU 
* Tweak - Test for compatibility with WordPress 4.9.6
* Tweak - Check for any issues with GDPR compliance. None Found
* Framework - Update a3rev Plugin Framework to version 2.0.3

= 1.8.2 - 2018/02/13 =
* Maintenance Update. Under the bonnet tweaks to keep your plugin running smoothly and is the foundation for new features to be developed this year 
* Framework - Update a3rev Plugin Framework to version 2.0.2
* Framework - Add Framework version for all style and script files
* Tweak - Update for full compatibility with a3rev Dashboard plugin
* Tweak - Test for compatibility with WordPress 4.9.4

= 1.8.1 - 2017/11/27 =
* Tweak - Numerous plugin style and script tweaks
* Fix - Resolved Progressive load conflict with a3 Lazy Load, was not showing images after first image in slider.

= 1.8.0 - 2017/11/25 =
* Feature Upgrade. Introducing Progressive image loading, plus tweaks and 2 bug fixes for WordPress 4.9 compatibility  
* Feature - Add new Progressive loading feature to reduce the bandwidth and speed up page load.
* Tweak - Add Progressive option to Settings of Slider and has it default as ON.
* Tweak - Do not show Pager for slider on Desktop if Progressive is set as ON
* Tweak - Tested for compatibility with WordPress major version 4.9.0
* Fix - Set empty value for variables to resolve PHP Notices
* Fix - Correct false (boolean) value of variable

= 1.7.0 - 2017/06/09 =
* Feature - Launched a3 Responsive Slider public Repository
* Tweak - Change global $$variable to global ${$variable} for compatibility with PHP 7.0
* Tweak - Update a3 Revolution to a3rev Software on plugins description
* Tweak - Added Settings link to plugins description on plugins menu
* Tweak - Tested for compatibility with WordPress major version 4.8.0
* Tweak - Include bootstrap modal script into plugin framework
* Tweak - Update a3rev plugin framework to latest version

= 1.6.0 - 2016/11/02 =
* Feature - Added Font editor 'Line Height' option
* Feature - Upgrade Slider Skins Control icons to font awesome icons.
* Feature - Added full dynamic style options for the control icons
* Feature - Added option to set the Control icons position on the slider. Top, Centre, Bottom and Left, middle, right
* Feature - Update old media uploader to new WP media uploader when Add/Edit Slider
* Tweak - Update select type of plugin framework for support group options
* Tweak - Update Typography Preview script for apply 'Line Height' value to Preview box
* Tweak - Update the generate_font_css() function with new 'Line Height' option
* Tweak - Replace all hard code for line-height inside custom style by new dynamic 'Line Height' value
* Tweak - Register fontawesome in plugin framework with style name is 'font-awesome-styles'
* Tweak - Update dynamic style for new features
* Tweak - Update text domain for full support of translation with new name for translate file is 'a3-responsive-slider.po'
* Tweak - Define new 'Ajax Multi Submit' control type with Progress Bar showing and Statistic for plugin framework
* Tweak - Define new 'Ajax Submit' control type with Progress Bar showing for plugin framework
* Tweak - Update plugin framework styles and scripts support for new 'Ajax Submit' and 'Ajax Multi Submit' control type
* Tweak - Tested for full compatibility with WordPress version 4.6.1
* Fix - Headers already sent warning. Delete trailing spaces at bottom of php file

= 1.5.0 - 2016/04/13 =
* Feature - Define new 'Background Color' type on plugin framework with ON | OFF switch to disable background or enable it
* Feature - Define new function - hextorgb() - for convert hex color to rgb color on plugin framework
* Feature - Define new function - generate_background_color_css() - for export background style code on plugin framework that is used to make custom style
* Tweak - Change on load script so that they are loaded with correct order without jQuery is loaded after slider script
* Tweak - Change call action from 'wp_head' to 'wp_enqueue_scripts' and use 'wp_enqueue_style' function to load style for better compatibility with minify feature of caching plugins
* Tweak - Change call action from  'wp_head' to 'wp_enqueue_scripts' to load  google fonts
* Tweak - Define new 'strip_methods' argument for Uploader type, allow strip http/https or no
* Tweak - Register fontawesome in plugin framework with style name is 'font-awesome-styles'
* Tweak - Saved the time number into database for one time customize style and Save change on the Plugin Settings
* Tweak - Replace version number by time number for dynamic style file are generated by Sass to solve the issue get cache file on CDN server
* Tweak - Update core style and script of plugin framework for support Background Color type
* Tweak - Update plugin framework to latest version
* Tweak - Tested for full compatibility with WordPress major version 4.5
* Fix - Add 'a3-notlazy' class to slide images for way site have a3 Portfolio and a3 Lazy Load to fix slider can't show on frontend. If you have added 'a3-slider-image' class to Skip Images Classes option of a3 Lazy Load then should remove it

= 1.4.0 - 2015/12/10 =
* Feature - Added Option to set Google Fonts API key to directly access latest fonts and font updates from Google
* Feature - Added full support for Right to Left RTL layout on plugins admin dashboard.
* Feature - Change media uploader to New UI of WordPress media uploader with WordPress Backbone and Underscore
* Tweak - Update the uploader script to save the Attachment ID and work with New Uploader
* Tweak - Updated a3 Plugin Framework to the latest version
* Tweak - Tested for full compatibility with WordPress major version 4.4

= 1.3.0 - 2015/11/20 =
* Feature - Add new option [ ] Open in new tab for slider images with links
* Feature - When a skin Controls and Pager options are switched ON don't show Controls and Pagers if the slider just has one slide
* Tweak - Update table database for new Open in new tab option
* Tweak - Tested for full compatibility with WordPress major version 4.3.1
* Fix - Make slider show for compatibility with a3 Portfolio and a3 Lazy Load

= 1.2.0 - 2015/08/21 =
* Feature - Added Plugin Framework Customization settings. Control how the admin panel settings show when editing.
* Tweak - Tested for full compatibility with WordPress major version 4.3.0
* Tweak - include new CSSMin lib from https://github.com/tubalmartin/YUI-CSS-compressor-PHP-port into plugin framework instead of old CSSMin lib from http://code.google.com/p/cssmin/ , to avoid conflict with plugins or themes that have CSSMin lib
* Tweak - Make __construct() function for 'Compile_Less_Sass' class instead of using a method with the same name as the class for compatibility on WP 4.3 and is deprecated on PHP4
* Tweak - Change class name from 'lessc' to 'a3_lessc' so that it does not conflict with plugins or themes that have another Lessc lib
* Tweak - Tested for full compatibility with WordPress Version 4.3.0
* Fix - Make __construct() function for 'A3_Responsive_Slider_Shortcode' class instead of using a method with the same name as the class for compatibility on WP 4.3 and is deprecated on PHP4

= 1.1.10 - 2015/06/10 =
* Fix - Check 'request_filesystem_credentials' function, if it does not exists then require the core php lib file from WP where it is defined

= 1.1.9 - 2015/06/03 =
* Tweak - Security Hardening. Removed all php file_put_contents functions in the plugin framework and replace with the WP_Filesystem API
* Tweak - Security Hardening. Removed all php file_get_contents functions in the plugin framework and replace with the WP_Filesystem API
* Fix - Update dynamic stylesheet url in uploads folder to the format //domain.com/ so it's always is correct when loaded as http or https

= 1.1.8 - 2015/05/18 =
* Tweak - Change Slider Skins Control and Pager setting default to OFF
* Tweak - Control and pager CSS only loads from the footer when those settings are switched ON
* Credit - Thanks to Kent for raising the issue attention https://a3rev.com/forums/topic/prev-next-pause-appears-when-page-refreshes/

= 1.1.7 - 2015/05/14 =
* Tweak - Tested and Tweaked for full compatibility with WordPress Version 4.2.2
* Tweak - Update cycle2 script to latest version 2.1.6
* Fix - Change custom layout. When inserting a slider by Slider shortcode insert button the Align Center of content was not working.

= 1.1.6 - 2015/04/21 =
* Tweak - Tested and Tweaked for full compatibility with WordPress Version 4.2.0
* Tweak - Changed <code>dbDelta()</code> function to <code>$wpdb->query()</code> for creating plugin table database.
* Tweak - Update style of plugin framework. Removed the [data-icon] selector to prevent conflict with other plugins that have font awesome icons

= 1.1.5 - 2015/01/24 =
* Tweak - Added str_replace( array("\r\n", "\r", "\n"), '', $slider_output) to prevent Themes and plugins that process shortcodes from adding line break br and paragraph p tags to Slider shortcodes and breaking them.
* Tweak - Changed add_action( 'wp_enqueue_scripts' ) to add_action( 'wp_head' ) for fixed the video don't work if have some plugin filter video tag for comaptibility with Pro Version upgrade.
* Tweak - Updated a3-rslider-frontend.js and a3-rslider-frontend-mobile.js for compatibility with Pro Version upgrade.

= 1.1.4 - 2015/01/16 =
* Feature - When link URL is added to an image in slider, click or tap on the image opens the link.
* Tweak - Can now just add link URL to slider image without having to add caption text with Read More button to create the link.
* Tweak - Read More option now activated from the image settings on slider edit page.
* Tweak - Removed Read More ON | OFF option from the Read More tab on all skins.
* Tweak - Added help text to the top of Read More tab page on each skin.
* Tweak - Added Show read more button / text option for each slide in slider edit page.
* Tweak - Slider edit page image setting UI. Changed the Caption text box width that same as Title and Link URL input box.
* Tweak - Show read more settings display to the right of the Caption text input box with help text.
* Tweak - Added new show_readmore column for a3_rslider_images table database.
* Fix - Caption text not showing on slider images - updated .less file.
* Fix - Check $post before get post_content to fix PHP notice property of non-object on a3-rslider-hook-filter.php line 56.

= 1.1.3 - 2015/01/10 =
* Tweak - Fix Slider skins first load UI when Dynamic height is activated for a skin
* Tweak - Skin .css only load for the slider it is attached to and only load on urls where slider is embed or in widget - like js assets
* Tweak - Edit for full compatibility with a3 Lazy Load. Only load skin when it comes into the view port like content
* Tweak - Update Plugins description to include install a3 Lazy Load recommendation.
* Dev - Convert Sass Global .less to simplify compiling style sheet edits.
* Fix - Show the new plugin version on the Core Update page. Feature stopped working with WordPress version 4.1
* Fix - Sass compile path not saving on windows xampp.

= 1.1.2 - 2015/01/06 =
* Tweak - Only load plugin assets on post and page where slider is embedded by shortcode or as a widget.
* Tweak - Only load assets on page that are required for slider effects - not all plugin js assets.
* Tweak - Only load slider mobile assets when load in mobile screen.
* Tweak - Audit code for full compatibility with WordPress version 4.1.0
* Tweak - Updated plugins admin dashboard yellow border text. Removed reference to Free Trials.
* Tweak - Added links to new a3 Lazy Load and a3 Portfolio plugins on wordpress.org
* Fix - PHP Error notice undefined variable: slider_settings on slider edit admin page.

= 1.1.1 - 2014/12/18 =
* Tweak - added div tag to href= jQuery when click add image to slider gallery. Some plugins like MOJO Market Place plugin enforce div on image URLs site wide.
* Credit - thanks to Bill Passawe for reporting the issue and giving us access to his site to find the issue and make a patch.

= 1.1.0 - 2014/09/15 =
* Feature - Converted all front end CSS #dynamic {stylesheets} to Sass #dynamic {stylesheets} for faster loading.
* Feature - Convert all back end CSS to Sass.

= 1.0.0.4 - 2014/09/10 =
* Tweak - Updated google font face in plugin framework.
* Tweak - Tested 100% compatible with WordPress Version 4.0

= 1.0.0.3 - 2014/07/19 =
* Fix - Changed Mobile_Detect Lib class name to A3_RSlider_Mobile_Detect to prevent conflict with other plugins that use the global class name.
* Credit - Thanks to Flemming Andersen for the access to his site to find and fix the class name conflict.

= 1.0.0.2 - 2014/06/21 =
* Tweak - Updated chosen js script to latest version 1.1.0 on the a3rev Plugin Framework
* Tweak - Added support for placeholder feature for input, email , password , text area types
* Tweak - Updated plugins description text and admin panel yellow sidebar text.

= 1.0.0.1 - 2014/05/24 =
* Tweak - Changed add_filter( 'gettext', array( $this, 'change_button_text' ), null, 2 ); to add_filter( 'gettext', array( $this, 'change_button_text' ), null, 3 );
* Tweak - Update change_button_text() function from ( $original == 'Insert into Post' ) to ( is_admin() && $original === 'Insert into Post' )
* Tweak - Checked and updated for full compatibility with WordPress version 3.9.1
* Fix - Code tweaks to fix a3 Plugins Framework conflict with WP e-Commerce tax rates.

= 1.0.0 - 2014/05/05 =
* First Release of Lite Version.


== Upgrade Notification ==

= 2.3.1 =
This maintenance release has plugin framework updates for compatibility with PHP 8.1 onwards, plus compatibility with WordPress 6.4.1

= 2.3.0 =
This feature release removes the fontawesome lib and replaces icons with SVGs plus adds Default Topography option to font controls.

= 2.2.2 =
This maintenance release has 1 bug fix

= 2.2.1 =
This maintenance release has a security vulnerability patch, plus compatibility with WordPress major version 6.1.0

= 2.2.0 =
This release has a security patch for a Stored XSS zero-day vulnerability in all previous versions.

= 2.1.0 =
This release is for compatibility with WordPress major version 6.0 plus includes various tweaks to harden the plugins security

= 2.0.13 =
This is a maintenance release for compatibility with WordPress major version 5.9

= 2.0.12 =
This maintenance release has check for compatibility with PHP 8.x

= 2.0.11 =
This maintenance release has code tweaks for WordPress 5.8 compatibility plus Security hardening.

= 2.0.9 =
This small maintenance release contains a security patch

= 2.0.8 =
This maintenance release updates 23 deprecated jQuery functions for compatibility with the latest version of jQuery in WordPress 5.7

= 2.0.7 =
* This maintenance release is for compatibility with WordPress 5.7

= 2.0.6 =
This is an important maintenance release that updates our scripts for compatibility with the latest version of jQuery released in WordPress 5.6

= 2.0.5 =
This maintenance release has tweaks for compatibility with WordPress major version 5.6 and PHP version 7.4

= 2.0.4 =
This maintenance release is for compatibility with WordPress major version 5.5 and WooCommerce 4.3.1.

= 2.0.3 =
This maintenance release is a tweak to the mobile template.

= 2.0.2 =
This maintenance release adds Travis Unit build tests and compatibility with PHP Code Checker

= 2.0.1 =
This maintenance release is for compatibility with WordPress 5.4 and PHP 7.4

= 2.0.0 =
This feature release completes the full refactor (frontend and backend) of the plugins PHP to Composer, 1 bug fix and compatibility with WordPress 5.3.2

= 1.9.0 =
This feature release has a lot. PHP is upgraded to Composer PHP Dependency Manager, a full security review, new z-index option and compatibility with WordPress 5.3.1

= 1.8.9 =
This maintenance upgrade is to fix a style conflict with fontawesome icons

= 1.8.8 =
This is a maintenance upgrade to fix a potentially fatal error conflict with sites running PHP 7.3

= 1.8.7 =
This maintenance update adds support for ALT text on images.

= 1.8.6 =
This maintenance update resolves a PHP 7.2 issue and compatibility check for WordPress upcoming 5.2 release

= 1.8.5 =
This maintenance update is for compatibility with WordPress 5.0.2 and PHP 7.3. It also includes performance updates to the plugin framework.

= 1.8.4 =
This maintenance update fixes 1 bug and checks for compatibility with WordPress 4.9.8

= 1.8.3 =
Maintenance Update. Compatibility WordPress 4.9.6 and the new GDPR compliance requirements for users in the EU

= 1.8.2 =
Maintenance Update. This version updates the Plugin Framework to v 2.0.2, adds full compatibility with a3rev dashboard and WordPress v 4.9.4

= 1.8.1 =
Maintenance Update. Following the release of new feature version 1.7.0 this release includes 1 bug fixe from that upgrade plus numerous style and script tweaks

= 1.8.0 =
Feature Upgrade. Introducing Progressive image loading, plus tweaks and 2 bug fixes for WordPress 4.9 compatibility

= 1.7.0 =
Feature Upgrade. 3 code tweaks for compatibility with WordPress version 4.8.0 and launch of the plugins source code on public Github repo

= 1.6.0 =
Feature Upgraded. 5 new features, 10 code tweaks and 1 bug fix for full compatibility with WordPress version 4.6.1

= 1.5.0 =
Feature Upgrade. 3 new features plus 10 tweaks and 1 bug fix for full compatibility with WordPress major version 4.5

= 1.4.0 =
Feature Upgrade. 3 new features plus tweaks for full compatibility with WordPress major Version 4.4

= 1.3.0 =
Feature Upgrade. 2 new features, 1 bug fixes and tweaks for full compatibility with WordPress Version 4.3.1

= 1.2.0 =
Major Maintenance Upgrade. 1 new Feature and 5 Code Tweaks plus 1 bug fixes for full compatibility with WordPress v 4.3.0

= 1.1.10 =
Maintenance Upgrade. Fix for PHP Fatal Error when upgrading from older versions of the plugin to version 1.1.9 on some servers

= 1.1.9 =
Important Maintenance Upgrade. 2 x major a3rev Plugin Framework Security Hardening Tweaks plus 1 https bug fix

= 1.1.8 =
Maintenance Upgrade. 2 Code Tweaks for improved loading and display of Slider Skin Controls and Pager.

= 1.1.7 =
Maintenance Upgrade. 1 shortcode alignment bug fix, update of core script and full compatibility with WordPress version 4.2.2

= 1.1.6 =
Maintenance upgrade! Code tweaks for full compatibility with WordPress 4.2.0

= 1.1.5 =
Upgrade now for 3 code tweaks which could be important depending on your site configuration.

= 1.1.4 =
Upgrade now for 1 new feature, 8 Code and UI Tweaks plus 2 bug fixes.

= 1.1.3 =
Upgrade your plugin now for tweaks that greatly improved slider first load UI, plus full compatibility with a3 Lazy Load and 2 bug fixes.

= 1.1.2 =
Upgrade now for slider load assets performance upgrade plus full compatibility with WordPress Version 4.1

= 1.1.1 =
Upgrade now for an image insert tweak.

= 1.1.0 =
Major Version upgrade. Upgrade now for full front end conversion to Sass #dynamic {stylesheets} and admin panel to Sass.

= 1.0.0.4 =
Upgrade your plugin now for a Framework code tweak and full compatibility with WordPress Version 4.0

= 1.0.0.3 =
Update your plugin now for mobile detect class name conflict bug fix

= 1.0.0.2 =
Update now for 2 important framework code tweaks to keep you plugin in tip top running order.

= 1.0.0.1 =
Update now for full compatibility with WordPress 3.9.1 with some a3rev Plugin Framework code tweaks.

= 1.0.0 =
First release