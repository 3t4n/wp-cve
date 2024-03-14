=== Post to Social Media - WordPress to Hootsuite ===
Contributors: n7studios,wpzinc
Donate link: https://www.wpzinc.com/plugins/wordpress-to-hootsuite-pro
Tags: auto publish, auto post, social media automation, social media scheduling, hootsuite, promote old posts, promote posts, promote custom posts, promote selected posts, share posts, bulk share posts, share old posts, social, media, sharing, social media, social sharing, schedule, auto post, auto publish, publish, facebook, facebook post, facebook selected posts, facebook plugin, auto facebook post, post facebook, post to facebook, twitter, twitter post, tweet post twitter selected posts, tweet selected posts twitter plugin, auto twitter post, auto tweet post post twitter, post to twitter, linkedin, linkedin post, linkedin selected posts, linkedin plugin, auto linkedin post, post linkedin, post to linkedin, google, google post, google selected posts, google plugin, auto google post, post google, post to google, pinterest, pinterest post, pinterest selected posts, pinterest plugin, auto pinterest post, post pinterest, post to pinterest, best wordpress social plugin, best wordpress social sharing plugin, best social plugin, best social sharing plugin, best facebook social plugin, best twitter social plugin, best linkedin social plugin, best pinterest social plugin, best google+ social plugin, instagram, pinterest
Requires at least: 5.0
Tested up to: 6.4.1
Requires PHP: 7.4
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically share WordPress Pages, Posts or Custom Post Types to Facebook, Twitter and LinkedIn using your Hootsuite (hootsuite.com) account.

== Description ==

WordPress to Hootsuite is a plugin for WordPress that auto posts your Posts, Pages and/or Custom Post Types to your Hootsuite (hootsuite.com) account for scheduled publishing to Facebook, Twitter and LinkedIn.

Don't have a Hootsuite account?  [Sign up for free](https://hootsuite.com)

Our [API](https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/data/) connects your website to [Hootsuite](https://hootsuite.com). An account with Hootsuite is required.

> #### WordPress to Hootsuite Pro
> <a href="https://www.wpzinc.com/plugins/wordpress-to-hootsuite-pro/" rel="friend" title="WordPress to Hootsuite Pro - Publish to Facebook, Twitter, LinkedIn and Pinterest">WordPress to Hootsuite Pro</a> provides additional functionality:<br />
>
> - **Instagram and Pinterest Support**<br />Post to Instagram (Personal Profiles only, using Reminders) and Pinterest Boards<br />
> - **Multiple, Customisable Status Messages**<br />Each Post Type and Social Network can have multiple, unique status message and settings<br />
> - **Conditionally send Status Messages**<br />Only send status(es) to Hootsuite based on Post Author(s), Taxonomy Term(s) and/or Custom Field Values<br />
> - **More Scheduling Options**<br />Each status update can be added to the start/end of your Hootsuite queue, posted immediately or scheduled at a specific time<br />
> - **Dynamic Status Tags**<br />Dynamically build status updates with data from the Post Author and Custom Fields<br />
> - **Separate Statuses per Social Network**<br />Define different statuses for each Post Type and Social Network<br />
> - **Per-Post Settings**<br />Override Settings on Individual Posts: Each Post can have its own Hootsuite settings<br />
> - **Repost Old Posts**<br />Automatically Revive Old Posts that haven't been updated in a while, choosing the number of days, weeks or years to re-share content on social media.<br />
> - **Bulk Publish Old Posts**<br />Manually re-share evergreen WordPress content and revive old posts with the Bulk Publish option<br />
> - **The Events Calendar and Event Manager Integration**<br />Schedule Posts to Hootsuite based on your Event's Start or End date, and display Event-specific details in your status updates<br />
> - **SEO Integration**<br />Display SEO-specific information in your status updates from All-In-One SEO Pack, Rank Math, SEOPress and Yoast SEO<br />
> - **WooCommerce Integration**<br />Display Product-specific information in your status updates<br />
> - **Autoblogging and Frontend Post Submission Integration**<br />Pro supports autoblogging and frontend post submission Plugins, including User Submitted Posts, WP Property Feed, WPeMatico and WP Job Manager<br />
> - **Shortcode Support**<br />Use shortcodes in status updates<br />
> - **Full Image Control**<br />Choose to display the WordPress Featured Image with your status updates, or define up to 4 custom images for each Post.<br />
> - **WP-Cron and WP-CLI Compatible**<br />Optionally enable WP-Cron to send status updates via Cron, speeding up UI performance and/or choose to use WP-CLI for reposting old posts<br />
> - **Support, Documentation and Updates**<br />Access to one on one email support, plus detailed documentation on how to install and configure the plugin and one click update notifications, right within the WordPress Administration panel.<br />
>
> [Upgrade to WordPress to Hootsuite Pro](https://www.wpzinc.com/plugins/wordpress-to-hootsuite-pro/)

[youtube https://www.youtube.com/watch?v=9QOAxJONRYM]

= Support =

We will do our best to provide support through the WordPress forums. However, please understand that this is a free plugin, 
so support will be limited. Please read this article on <a href="http://www.wpbeginner.com/beginners-guide/how-to-properly-ask-for-wordpress-support-and-get-it/">how to properly ask for WordPress support and get it</a>.

If you require one to one email support, please consider <a href="http://www.wpzinc.com/plugins/wordpress-to-hootsuite-pro" rel="friend">upgrading to the Pro version</a>.

= Data =

We connect directly to your Hootsuite (hootsuite.com) account, via their API, to:
- Fetch your social media profile names and IDs, 
- Send your WordPress Posts to one or more of your social media profiles.  The profiles and content sent will depend on the plugin settings you have configured.

We connect to our own [API](https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/data/) to pass the following requests through to Hootsuite:
- Connect our Plugin to Hootsuite, when you click the Authorize button (this obtains an access token from Hootsuite, once you have approved authorization)
- Process image uploads to the ow.ly API, which is required by Hootsuite when sharing an image as part of a social media update.

Both of these are done via our own API, to ensure that no secret data (such as oAuth client secret keys) are included in this Plugin's code or made public.

We **never** store any information on our web site or API during this process.

= WP Zinc =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us at <a href="https://www.wpzinc.com" title="Premium WordPress Plugins">wpzinc.com</a>

== Installation ==

1. Upload the `wp-to-hootsuite` folder to the `/wp-content/plugins/` directory
2. Active the WordPress to Hootsuite plugin through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `WordPress to Hootsuite` menu that appears in your admin menu

== Frequently Asked Questions ==

== Screenshots ==

1. Settings Screen when Plugin is first installed.
2. Settings Screen when Hootsuite is authorized.
3. Settings Screen showing available options for Posts.
4. Post-level Logging.

== Changelog ==

= 1.5.4 (2023-11-17) =
* Fix: Hootsuite API Error: #400: 5000: Unknown error occurred when attempting to publish a status with an image

= 1.5.3 (2023-10-09) =
* Fix: Correctly detect and differentiate REST API requests from Gutenberg REST API requests, ensuring REST API requests trigger status(es)

= 1.5.2 (2023-09-07) =
* Fix: Updated dashboard submodule

= 1.5.1 (2023-08-23) =
* Fix: Updated WordPress Coding Standards to 3.0.0

= 1.5.0 (2023-08-03) =
* Added: Plugins: Link to settings screen
* Fix: Remove duplicate call to load_language_files()
* Fix: PHP Deprecated notices in PHP 8.2

= 1.4.9 (2023-05-16) =
* Fix: Post: Log: Export Log: Check user can edit posts to permit export log functionality

= 1.4.8 (2023-01-26) =
* Added: Log: Log errors when image operations (resizing, converting, uploading to Media Library) fails
* Fix: Use get_temp_dir() instead of assumed /tmp folder for writing temporary images when resizing, converting or generating text to image
* Fix: Status: Clear profiles cache when deauthorizing and authorizing with a different Hootsuite account
* Fix: Improved WordPress Coding Standards
* Fix: Removed clipboard.js, as WordPress provides this library

= 1.4.7 (2022-10-25) =
* Fix: Remove unused 1200x1200 registered image size

= 1.4.6 (2022-06-21) =
* Fix: Status: Correctly sanitize and escape status textarea field value to prevent possible XSS

= 1.4.5 (2022-06-09) =
* Added: Support for WordPress 6.0

= 1.4.4 (2022-05-12) =
* Fix: Multisite: Activation: Conditionally load required hook depending on WordPress version

= 1.4.3 (2022-04-24) =
* Fix: Upgrade link would incorrectly redirect to WordPress Admin dashboard

= 1.4.2 (2022-03-08) =
* Fix: Call to undefined function _disable_block_editor_for_navigation_post_type when creating/updating Post in Gutenberg or via the REST API in WordPress 5.9+
* Fix: Scheduled Posts: Publish action would not run when using Gutenberg
* Fix: Customizer: Don't load inline CSS for menu icon when loading WordPress Admin > Theme > Customize

= 1.4.1 (2022-03-03) =
* Added: Status: Insert Tags: Insert tag at textarea caret position, with leading/trailing space as applicable
* Fix: Multisite: Activation: Use wp_insert_site hook when available in WordPress 5.1 and higher

= 1.4.0 (2021-12-22) =
* Added: Support for images added to the Media Library by Plugins that don't store images locally e.g. External Media without Import
* Added: Status: Tags: {date} uses WordPress Admin > Settings > Site Language and Date Format options.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#available-tags
* Fix: Always include WordPress media functions when converting a WebP image to JPEG and storing it in the Media Library to avoid PHP errors

= 1.3.9 (2021-09-17) =
* Fix: Logs: Correctly escape search and form action

= 1.3.8 (2021-09-16) =
* Fix: PHP Deprecated notices in PHP 8

= 1.3.7 (2021-09-09) =
* Added: Status: Text: Convert HTML links to plain text with link in brackets, instead of just displaying the unlinked text
* Added: Status: Text: Convert HTML lists to plain text with hyphens, instead of just displaying plain text
* Added: Status: Image: Support for .webp images when Use Feat. Image enabled and .webp image used as Featured Image. See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/featured-image-settings/#webp-image-support
* Added: Status: Remove HTML from shortcodes included in status text

= 1.3.6 (2021-07-15) =
* Added: New Installations: Clearer workflow for connecting to Hootsuite and connecting social media profiles to Hootsuite account.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/authentication-settings/
* Added: Status: Tags: Character Limit, Sentence Limit, Word Limit, Date and URL Encoding transformations.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#applying-transformations
* Fix: Don't minify Plugin Javascript if a third party minification Plugin is active, which would prevent status settings from sometimes saving
* Fix: Status: PowerPress: Prevent PowerPress from appending podcast URL to Content and Excerpt tags. 

= 1.3.5 (2021-06-10) =
* Fix: Authorization: Changed oAuth Authorization URL to prevent 404 error
* Fix: Authorization: More detailed error message displayed when Hootsuite API fails

= 1.3.4 (2021-04-29) =
* Added: Status: Text: Autocomplete suggestions for Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#autocomplete-suggestions

= 1.3.3 (2021-04-15) =
* Added: Settings: Post Type: Show prompt if changes made but unsaved when navigating away from the status settings
* Fix: Log: Warning: `Edit the Post` link correctly loads the Edit Post screen

= 1.3.2 (2021-04-01) =
* Added: Settings: Post Type: Immediately show/hide green tick on Post Type tab after clicking Save, to confirm whether the Post Type is configured to send status(es) to Hootsuite
* Fix: Settings: Post Type: Profile: Text order and links were incorrect when displaying a Timezone warning

= 1.3.1 (2021-03-18) =
* Added: Log: Enable wp-content/debug.log only when WP_DEBUG=true, WP_DEBUG_LOG=true, WP_DEBUG_DISPLAY=false and Plugin Logging enabled.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/repost-settings/#testing
* Added: Localization support, with .pot file and translators comments
* Fix: Status: Retain paragraphs when using {content} tag
* Fix: Settings: Log Settings: Corrected link to Logs screen, and don't link "Plugin Logs" text when logging not enabled
* Fix: Log: Don't show Logs in Plugin Submenu if Logging is disabled

= 1.3.0 (2021-01-07) =
* Added: Status: If a Featured Image is required, attempt to fetch it from the Post Content when a Featured Image has not been specified

= 1.2.9 (2020-12-22) =
* Fix: Status: Removed debugging code

= 1.2.8 (2020-12-21) =
* Fix: Status: Include Featured Image with status when required

= 1.2.7 (2020-11-27) =
* Added: Display error notice if PHP cURL extension is not installed
* Added: Settings: Force Trailing Forwardslash: Updated description to clarify why this setting might need to be enabled i.e. for correct status image
* Fix: Settings: Force Trailing Forwardslash: Truly force a forwardslash if Permalink settings don't add one.

= 1.2.6 (2020-09-03) =
* Added: Logs: Screen Options: Choose table columns to display.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#define-table-columns-to-display
* Added: Logs: Screen Options: Choose number of logs per page to display.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#define-number-of-logs-per-page
* Fix: Status: Enabling/Disabling Publish or Update wouldn't update green tick in tab UI in WordPress 5.5+
* Fix: Status: Don't display "Post sucessfully added" admin notification if Test Mode is enabled
* Fix: Logs: Lighter success/error row background colors to make text easier to read
* Fix: Logs: When filtering by date, include results matching the date, not just results between the dates

= 1.2.5 (2020-08-20) =
* Added: Settings: General Settings: Enable Test Mode. See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#enable-test-mode
* Added: Settings: Logs: Option to choose specific Log Levels.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#log-level
* Added: Settings: Logs: Added Pending Log Level, for status(es) due to be sent when Use WP Cron enabled in Plugin's Settings.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#log-level
* Added: Logs: Confirmation when clicking Clear Log button
* Fix: Logs: Set Clear Log button to red
* Fix: Logs: Clear Log: Contextualized confirmation message based on whether the Log is being cleared at Post or Plugin level
* Fix: Fatal error when detecting current admin screen on some Page Builders 
* Fix: Some notifications weren't dismissible

= 1.2.4 =
* Fix: Prevent fatal error when upgrading to Pro when Free is still active 

= 1.2.3 =
* Added: Settings: General Settings: Use Proxy option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#use-proxy-
* Added: Settings: Log Settings: Log Level option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#log-level 
* Fix: Log: Honor Enabled Setting, ensuring logging does not take place if not enabled

= 1.2.2 =
* Added: Status: Option to specify Taxonomy Tags
* Fix: Status: Taxonomy Tags: Remove non-alphanumeric characters to avoid breaking tag links

= 1.2.1 =
* Fix: CSS: Renamed option class to wpzinc-option to avoid CSS conflicts with third party Plugins
* Fix: Log: Unknown column 'status' in 'where clause' for query when clearing pending status log entries
* Fix: Elementor: Removed unused tooltip classes to prevent Menu and Element Icons from not displaying

= 1.2.0 =
* Added: Forms: Accessibility: Replaced Titles with <label> elements that focus the given input element on click
* Added: General Settings: Option to force trailing forwardslash on {url}.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#force-trailing-forwardslash 
* Fix: Activation: Prevent DB character set / collation errors on table creation by using WordPress' native get_charset_collate()
* Fix: Log: Call to undefined method WP_To_Social_Pro_Log::clear_pending_log()
* Fix: Log: Display Status Text's breaklines
* Fix: Status: Don't send status(es) to Hootsuite for non-public Post Types containing Post-level Status Settings copied from a public Post.
* Fix: Status: More verbose error message when a status is too long for the target social network 
* Fix: Status: Use AJAX to save statuses to avoid settings not saving or changing when PHP's max_input_vars is exceeded due to e.g. several profiles and statuses defined
* Fix: Status: Better method to remove double/triple spaces in text whilst retaining newlines/breaklines and unicode/accented characters
* Fix: Status: Strip query parameters (added by e.g. Jetpack) from images before sending status to prevent errors
* Fix: Settings: Removed disabled CSS class on tabs, as not used and avoids potential conflicts with third party Plugins
* Fix: Settings: Display confirmation notice that settings have saved

= 1.1.9 =
* Added: Log: Option to filter Logs by Request Sent Date. See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#filtering-logs
* Added: Log: Provide solutions to common issues
* Added: Log: New Log screen with filters and searching to view Status Logs across all Posts for all actions (Publish, Update, Repost, Bulk Publish).  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/logs/
* Added: Log: Improved messages explaining why a Post is not sent to Hootsuite
* Added: Log: Use separate database table for storing Plugin Status Logs instead of Post Meta, for performance
* Added: Status: Image: No Image option
* Removed: Status: Image: Use OpenGraph settings.  Hootsuite have disabled the ability for third party applications (such as this Plugin) to request that status messages read OpenGraph data for a given URL.

= 1.1.8 =
* Added: Status: Tags: Content and Excerpt Tag options with Word or Character Limits
* Added: Gutenberg: Better detection to check if Gutenberg is enabled
* Added: Gutenberg: Better detection to check if Post Content contains Gutenberg Block Markup
* Fix: Status: Removed loading of unused tags.js dependency for performance
* Fix: Status: {content} would return blank on WordPress 5.1.x or older

= 1.1.7 =
* Added: Status: Textarea will automatically expand based on the length of the status text. Fixes issues for some iOS devices where textarea scrolling would not work
* Fix: Status: {content} and {excerpt} tags always return the full content / excerpt, which can then be limited using word / character limits
* Fix: Publish: Add checks to prevent duplicate statuses being sent when a Page Builder (Elementor) fires wp_update_post multiple times when publishing
* Fix: Status: Strip additional unwanted newlines produced by Gutenberg when using {content}
* Fix: Status: Convert <br> and <br /> in Post Content to newlines when using {content}
* Fix: Status: Trim Post Content when using {content}

= 1.1.6 =
* Added: Settings: Display notice if the Hootsuite account does not have any social media profiles attached to it
* Fix: Publish: Display errors and log if authentication fails, or profiles cannot be fetched

= 1.1.5 =
* Fix: Settings: Status: Display warning if a timezone in WordPress or Hootsuite is not a valid timezone, instead of throwing a fatal error

= 1.1.4 =
* Added: Status: Secondary level tabbed UI for Profile actions (Publish, Update)
* Added: Settings: Post Type: Profile: Display warning with instructions when the WordPress Timezone and Hootsuite Profile Timezone do not match
* Added: Settings: Warning if the max_input_vars PHP setting might be too low for the Plugin's settings to successfully be saved
* Fix: Status: Documentation Tab Link

= 1.1.3 =
* Added: New Installations: Automatically enable Publish and Update Statuses on Posts
* Added: Plugin Activation: Enable Logging by default
* Added: Status: Option to limit the number of characters output on a Template Tag
* Fix: Log: Output dates according to WordPress' installation date locale formatting
* Fix: Log: Split data into more table columns for easier reading
* Fix: Status: Don't attempt publishing to any existing linked Google+ Accounts, as Google+ no longer exists.
* Fix: Publish: Improved performance when sending several statuses for a single Post.
* Fix: Publish: Display errors on Post Edit screen if status(es) failed to send to Hootsuite

= 1.1.2 =
* Fix: Menu Icon size preserved when Gravity Forms no conflict mode is set to on
* Fix: Display White Menu Icon unless the User is using WordPress' Light Admin Color Scheme, in which case display the Dark Menu Icon

= 1.1.1 =
* Fix: Publish: Removed global $post reference, which caused some installations to fetch the wrong Post to send to Hootsuite

= 1.1.0 =
* Added: Status: Featured Image: Option to choose between using OpenGraph image (clicking image links to URL) and using image, not linked to URL.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/featured-image-settings/
* Fix: Compatibility when using multiple WP Zinc Plugins
* Fix: Minified all CSS and JS for performance

= 1.0.9 =
* Fix: Multisite: Network Activation: Ensure activation routines automatically run on all existing sites
* Fix: Multisite: Network Activation: Ensure activation routines automatically run created on new sites created after Network Activation of Plugin
* Fix: Multisite: Site Activation: Ensure activation routines automatically run

= 1.0.8 =
* Added: Settings: Header UI enhancements
* Fix: Settings: Added Pinterest Board URL option for Pinterest Statuses.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Fix: Settings: Display Twitter Usernames
* Fix: Settings: Status: When using Custom Time, ensure it is at least 5 minutes after Publish, Update or Repost (required by Hootsuite's API)
* Fix: PHP warning on count() when trying to fetch an excerpt for a Post
* Fix: Settings: Only load settings for the displayed screen, for better performance
* Fix: Settings: Save settings more efficiently, for better performance

= 1.0.7 =
* Fix: Settings: Changed Authentication Tab Icon
* Fix: Settings and Status Settings: UI Enhancements for mobile compatibility
* Fix: {title} would sometimes result in HTML encoded characters on Facebook

= 1.0.6 =
* Fix: Status: Apply WordPress default filters to Post Title, Excerpt and Content. Ensures third party Plugins e.g. qtranslate can process content and remove shortcodes

= 1.0.5 =
* Added: Gutenberg: Support for Custom Field Tags when Custom Fields / Meta are registered as a meta box outside of the Gutenberg editor.
* Added: REST API: Support for Custom Field Tags when Posts are created or updated via the REST API with Custom Field / Meta data.

= 1.0.4 =
* Added: Gutenberg Support
* Added: Settings and Status Settings: UI Enhancements to allow for a larger number of connected social media profiles
* Added: Status: Tag: Post ID option
* Fix: Removed unused datepicker dependency
* Fix: CRON Scheduled Posts: Don't rely on wp_get_current_user() for User Access settings, as it's not always available
* Added: Status: Support for Shortcode processing on Status Text

= 1.0.3 =
* Fix: Publish: Ensure Post has fully saved (including all Custom Fields / ACF / Yoast data etc) before sending status to Hootsuite
* Fix: Publish: Removed duplicate do_action() call on save_post to prevent some third party plugins running routines twice
* Fix: Log: Report 'Plugin: Request Sent' and 'Created At' datetime using WordPress configured date time zone.
* Fix: Profiles: Serve social media profile images over SSL to avoid mixed content warning messages
* Fix: Settings: Changed WordPress standard .nav-tab-active class to .wpzinc-nav-tab-active, to prevent third party plugins greedily trying to control our UI.

= 1.0.2 =
* Fix: Publish: Only consider publishing statuses to Hootsuite on supported Post Types (resolves issues with Advanced Custom Fields Free Version saving Fields).

= 1.0.1 =
* Fix: Call to member function get_error_message() on null when attempting to fetch Hootsuite User Profile.

= 1.0 =
* First release.

== Upgrade Notice ==

