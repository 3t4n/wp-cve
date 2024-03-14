## Changelog

**Admin and Site Enhancements (ASE) v1.0.0** was released on October 17, 2022. Since then, there have been **69 _point_ releases** (e.g. 1.1.0 ) and **81 _patch_ releases** (e.g. 4.9.1). 

Each **_point release_** usually corresponds with the addition of one new module/feature. Each module/feature usually is the equivalent of one (or more) single-purpose plugin. Each **_patch release_** usually contain one or more bugfix or improvements to existing modules/features.

[**Upgrade to ASE Pro**](https://www.wpase.com/chnlg-to-web) now. Lifetime Deal (LTD) available.

### 6.9.3 (2024.03.12) - ASE Free and Pro

* **[IMPROVED in Pro] Utilities >> Maintenance Mode**: enable WYSIWYG editor for heading and description, which allows for links, some custom HTML and custom styling if you need it. Also added ability to add custom CSS along with addition of a page overlay div element, which you can style to overlay the background image, e.g. make it darker or brighter to increase contrast with the maintenance message.

* **[IMPROVED in Pro] Content Management >> Content Duplication**: added duplication link in the publishing section of post edit screen, both in the classic editor and block editor. Also added an option to choose on which locations to show the duplication link: list view post action row / admin bar / edit screen publish section.

* **[FIXED in Free and Pro] ASE Settings**: prevent JS error when iThemes Security Pro / Solid Security Pro plugin is active.

* **[FIXED in Pro] Content Management >> Custom Content Types >> Custom Field Groups**: fixed an issue where returning the value of a custom field inside a GenerateBlocks Query Loop returns an empty value after update to v6.9.1. Props to Arne O. for reporting the issue in details and patiently facilitating the troubleshooting process. This also fixed a similar issue with showing custom field values in Breakdance Post Loop Builder. Props to Philipp Z. for reporting it.

* **[CHANGED in Pro] Content Management >> AVIF Upload**: remove link to avif.io, which is no longer online. This module will likely be removed once WP v6.5 is released and is widely adopted, as built-in AVIF support will be [included in WP core](https://make.wordpress.org/core/2024/02/23/wordpress-6-5-adds-avif-support/).


### 6.9.2 (2024.03.07) - ASE Free and Pro

* **[CHANGED in Pro] Content Management >> Custom Content Types**: custom field groups and options pages creation is now enabled by default when Custom Content Types module is enabled. Also updated module description and settings to reflect this.

* **[FIXED in Free and Pro] Optimizations >> Image Upload Control**: added checks to prevent PHP fatal error and warnings when uploading non-image files and WebP conversion is enabled. Props to Peter J. and Gilang R. for reporting the fatal error issue.

* **[FIXED in Free and Pro] Security >> Limit Login Attemtps**: fixed an issue where under certain conditions, the login page would endlessly reload making it impossible to login. Props to [eangulus](https://wordpress.org/support/users/eangulus/), [andreawriessnegger](https://wordpress.org/support/users/andreawriessnegger/), [eangulus](https://wordpress.org/support/users/eangulus/), Max F. and Eric for reporting this patiently and in great detail in the wp.org [support forum](https://wordpress.org/support/topic/something-not-right-with-limit-login-attempts/) and via email.

### 6.9.1 (2024.02.29) - ASE Free and Pro

* **[IMPROVED in Pro] Content Management >> Custom Content Types**: added the ability to create Options Pages, which for example, you can use to allow clients to easily edit parts of the website, e.g. office address, organization mission statement, etc. This has also been integrated with Oxygen, Bricks, Breakdance and Elementor, i.e. you can see fields from options pages showing up as dynamic data source.

* **[IMPROVED in Pro] Optimizations >> Image Upload Control**: in previous version, BMP, PNG and JPG images will be converted first to WebP before being resized  to the specified. Now, uploads will be resized first before conversion to WebP. This will result in even smaller file size while maintaining image quality. Props to Adryan for the astute observation and detailed reporting of the issue.

* **[IMPROVED in Pro] Content Management >> Custom Content Types >> Custom Field Groups**: the WYSIWYG field now has a 'code' button to show the HTML version of content being added / copy-pasted. Useful for cleaning up from unwanted code, e.g. CSS classes, weird formatting, etc. Props to Michael S. for suggesting something similar.

* **[IMPROVED in Pro] Custom Code >> Code Snippets Manager**: snippet description editor now has the Visual-Text tabs. Useful for cleaning up description from unwanted code, e.g. CSS classes, weird formatting, etc, when the description is copy-pasted from elsewhere. Props to Michael S. for suggesting this.

* **[IMPROVED in Free and Pro] Log In | Log Out >> Change Login URL**: will now correctly output an error message on failed login: "Error: Invalid username/email or incorrect password.". This is a custom message that does not give away valuable info to potential hackers. e.g. does not indicate that the username is correct but password is wrong. Props to Sven K. for reporting the issue.

* **[FIXED in Pro] Custom Code >> Code Snippets Manager**: fixed missing "Snippet Categories" sub-menu item after the last release. Props to Herbert S. for reporting the issue.

* **[FIXED in Free] Utilities >> Maintenance Mode**: properly hide the 'Image' and 'Color' background options in the free version. Props to Michael I. for reporting the issue.

* **[FIXED in Pro] Content Management >> Custom Content Type**: custom taxonomy key / slug was limited to 20 characters despite the UI / description saying it's limited to 32 characters, per the hard limit set by [register_taxonomy()(https://developer.wordpress.org/reference/functions/register_taxonomy/). Props to Michael S. for reporting the issue.

### 6.9.0.1 (2024.02.26) - ASE Pro

* **[FIXED in Pro] Content Management >> Content Duplication**: fixed a bug that can cause PHP warning or fatal error. Props to Benjamin P., Elon R., and Yoshihiro T. for reporting the issue immediately after the release of v6.9.0.

### 6.9.0 (2024.02.26) - ASE Free and Pro

* **[NEW in Free and Pro] Admin Interface >> Custom Admin Footer Text**: Customize the text you see on the footer of wp-admin pages.

* **[IMPROVED in Pro] Content Management >> Custom Content Types >> Custom Field Groups**: added 4 **new field types: number, radio, checkbox and (image) gallery**. All of them works with the repeater field and have been integrated with Bricks, Breakdance, Oxygen and Elementor, and also fully supported in the Admin Columns Manager module.

* **[IMPROVED in Pro] Content Management >> Custom Content Types >> Custom Field Groups**: added option to choose preview size for file (image) field type. You can choose between thumbnail (cropped/square) or medium (uncropped). Some users prefer the cropped version as it's smaller file size, while some other users prefer the uncropped version which for example, shows brand logos nicely.

* **[IMPROVED in Pro] Admin Interface >> Admin Columns Manager**: list table is now responsive, i.e. looks and works nicely on mobile. Props to Pablo R. for reporting the issue when vieweing on mobile.

* **[IMPROVED in Pro] Log In | Log Out >> Change Login URL**: added option to choose which URL to redirect to when visitors try to access default WP login URLs/slugs (/wp-admin/, /admin/, /wp-login.php, /login/). Props to Igor P. for the suggestion.

* **[IMPROVED in Pro] Content Management >> Content Duplication**: added option to choose which user role(s) the duplication feature is available for. Props to Igor P. for the suggestion.

* **[IMPROVED in Pro] Content Management >> Custom Content Types >> Custom Post Types**: small usability tweak. Add a close icon in the icon picker of the custom post type creation / edit screen. Props to Steven Y. for the suggestion.

### 6.8.3.1 (2024.02.20) - ASE Pro

* **[FIXED in Pro] Admin Interface >> Admin Columns Manager**: fixed an issue where column widths are not properly applied.

### 6.8.3 (2024.02.20) - ASE Free and Pro

* **[NEW in Pro] Content Management >> Custom Content Types**: added **integration of ASE custom fields as Elementor builder's dynamic data provider** All field types, except for repeater fields, can be displayed using the Text Editor widget. It's also possible to use compatible field types as data sources for URLs, files/media, colors, numbers, image and video.

* **[IMPROVED in Free and Pro] Admin Interface >> Hide Admin Notices**: mouse cursor now changes to a pointer (hand icon pointing upwards) when hovering on the 'Notices' menu in the admin bar. Props to @cvladan for [suggesting this](https://github.com/qriouslad/admin-site-enhancements/issues/10).

* **[FIXED in Pro] Custom Code >> Code Snippets Manager**: fixed PHP warning on login page. Props to Sunny L., Linda L. and Francois G. for reporting the issue along with the full error entry that helped with troubleshooting.

* **[FIXED in Free and Pro] ASE Settings Page**: fixed incompatibility with US Weather Widget plugin (a plugin that's last updated 6 years ago) causing the settings page to load partially and become non-functional. Props to James B for reporting the issue.

### 6.8.2 (2024.02.16) - ASE Free and Pro

* **[IMPROVEMENT in Free and Pro] Content Management >> Content Duplication**: added an option to choose where to redirect after performing duplication of a post. Choices are the edit screen of the new/duplicate post, or the list view for the post type, e.v. View all posts. Props to [@americancreativeconsulting](https://wordpress.org/support/users/americancreativeconsulting/) for [suggesting](https://wordpress.org/support/topic/feature-request-duplicate-page-without-opening-to-it/) this improvement.

* **[FIXED in Free and Pro] Content Management >> Media Replacement**: fixed an issue where media replace is not working in non-English wp-admin. Also fixed an issue where the "Drop files to upload" blue overlay is not closing after drag-and-dropping a file in the media replacement modal window. Props to Andi P. for reporting these issues in details and with screencasts and also facilitating troubleshooting further.

* **[FIXED in Free and Pro] Log In | Log Out >> Last Login Column**: fixed PHP warning when logging out of temporary account created with [Temporary Login Without Password](https://wordpress.org/plugins/temporary-login-without-password/) plugin. Props to Alex S. for reporting the issue.

* **[FIXED in Pro] Terms Order**: fixed PHP notice and warning on certain scenarios. Props to Brian N. for reporting the issue with a copy of the error log entry, which helped with troubleshooting.

* **[FIXED in Pro] Custom Code >> Code Snippets Manager**: fixed an issue where users are not able to logout in certain scenarios when Code Snippets Manager is active. Props to HMDIA for reporting the issue and assisting with troubleshooting.


### 6.8.0 (2024.02.13) - ASE Free and Pro

* **[NEW in Free and Pro] Log In Log Out >> Login ID Type**: Restrict login ID to username or email address only.

* **[IMPROVED in Free and Pro] Custom Code >> Custom Admin CSS**: change the hook in use to print custom CSS on page from 'admin_enqueue_scripts' to 'admin_print_footer_scripts'. This increases the chance that the custom CSS will override previously declared CSS.

* **[IMPROVED in Free and Pro] Utilities >> Display System Summary**: added server IP address. Props to Koen A. for the suggestion.

* **[IMPROVED in Free and Pro] Security >> Limit Login Attempts**: improve detection of user's IP address, especially for cases where the user is behind a proxy server. Prevents locking out all users from that proxy server when one is being locked out. Props to Gunnar A. for detecting and reporting the issue and suggesting a solution to help fix that.

* **[FIXED in Pro] Admin Interface >> Admin Columns Manager**: fixed an issue where columns of certain post type listings are showing empty cells / values. This was originally reported in detail with GeoDirectory's Packages listing by Martin K., which also helped with the troubleshooting.

* **[FIXED in Free and Pro] ASE settings page**: fixed plugin conflict between ASE and WordPress Mentions Légales plugin causing ASE settings page to be blank and dysfunctional. Props to Gilbert G. for reporting and helping with troubleshooting.

* **[FIXED in Pro] Admin Interface >> Admin Columns Manager**: fixed PHP fatal error when there's a product that does not have an SKU and the Products column is shown in WooCommerce Orders page. Props to Jacob E. for reporting the issue and helping with troubleshooting.

* **[FIXED in Free and Pro] Content Management >> Content Duplication**: fixed PHP warnings as reported by [@webvizionph](https://wordpress.org/support/users/webvizionph/) in details [here](https://wordpress.org/support/topic/warning-attempt-to-read-property-post_type-on-null-in-home/).

* **[FIXED in Pro] Content Management >> Media Replacement**: fixed an issue where the "Select New Media File" button does not work in the grid view of the media library when viewing a media item and trying to perform media replacement. Props to Stefan P. for reporting the issue.

* **[FIXED in Pro] Content Management >> Media Replacement**: fixed an issue where the new image (for replacement) is not being shown in the frontend view of the site and in the block editor's Featured Image section. Props to David H. for their investigative work detailing the issues and the scenarios under which they appear, and also for helping with the troubleshooting process.

### 6.7.0 (2024.02.07) - ASE Free and Pro

* **[NEW in Free and Pro] Admin Interface >> Display Active Plugins First**: new module to display active / activated plugins at the top of the Installed Plugins list. Useful when your site has many deactivated plugins for testing or development purposes.

* **[IMPROVED in Pro] Content Management >> Custom Content Types**: added **integration of ASE custom fields as Breakdance builder's dynamic data provider** with full support for the repeater field. All field types can be output using a simple text element in Breakdance, and it's also possible to output compatible field types as URLs and be used in the image and video elements for further customization. Relationship field support in Post Loop Builder requires intervention from Breakdance team, and ASE has initiated the process towards that end.

* **[IMPROVED in Pro] Admin Interface >> Admin Columns Manager**: added a column containing list of products being ordered for WooCommerce Orders listing page. Props to Jacob E. for the suggestion.

* **[FIXED in Free and Pro] Admin Interface >> Hide Admin Notices**: will now only be active in wp-admin pages and not on the frontend. Changed the hook used to load inline CSS styles that was causing the PHP warning issue of "headers already sent" when viewing the Customizer, as reported by [@socialsparkmedia](https://wordpress.org/support/users/socialsparkmedia/) and [@elonreynolds](https://wordpress.org/support/users/elonreynolds/) [here](https://wordpress.org/support/topic/php-warning-using-divi-theme-with-ase-pro-6-5-0/) and [here](https://wordpress.org/support/topic/warning-message-when-opening-customizer/). Additionally, when Breakdance builder is actively managing a post/page, a similar error was also reported and has also been fixed. Props to Val J. for reporting this issue in detail and facilitating troubleshooting.

* **[FIXED in Free and Pro] Content Management >> Media Replacement**: fixed a bug that occurs when an image of a different (mime) type than the image to replace is being used for the replacement. A check has been added that will output a warning when that happens, and will disable the Perform Replacement button until an image of the same type is selected. Props to David H. and Andi P. for reporting issues related to this bug, and help with providing detailed information to help with troubleshooting.

* **[FIXED in Free and Pro] Utilities >> Display System Summary**: fixed PHP warning when there's an error getting database information via mysqli_connect(). Props to Val J. for reporting the issue and helping with troubleshooting.

### 6.6.0 (2024.01.30) - ASE Free and Pro

* **[NEW][Free][Pro] Utilities >> Search Engines Visibility Status**: new module to show admin bar status and admin notice when search engines are set to be discouraged from indexing the site, which is set through a "Search engine visibility" checkbox in Settings >> Reading. Props to David S. for suggesting the feature and providing the code snippet (generated with ChatGPT Plus) that this module was based on.

* **[FIXED][Free][Pro] Security >> Limit Login Attempts**: fixed an issue where lockout is not effecive after reaching the limit of failed login attempts and being shown the lockout screen. Reloading the lockout screen would simply show the login form again, even when lockout period is not over yet. This happens only when using default login URL at /wp-login.php. Props to [@dywoo02](https://wordpress.org/support/users/dywoo02/) for [reporting this](https://wordpress.org/support/topic/limit-login-attempts-limit-login-attempts-and-correct-access-data/) and doing a thorough investigative work. That gave a clue and help for looking at the right places in the code to fix the issue.

* **[FIXED][Free][Pro] Log In | Log Out >> Change Login URL**: undo a change in v6.5.1 that was returning the custom login URL for wp_login_url(). This caused common login URLs like /admin, /wp-admin and /login to redirect to the custom login URL, thus making it pointless to have a custom login URL. With this reversion, those common long URLs will redirect to /not_found/ 404 error page. Props to Hayato for reporting this issue after updating to v6.5.1.

* **[FIXED][Pro] Local User Avatar**: fixed PHP warning when opening the block editor and in other scenarios. Props to Rio M. and Brian W. for reporting the issue.

### 6.5.1 (2024.01.29) - ASE Free and Pro

* **[IMPROVED][Pro] Content Management >> Custom Content Types**: added **integration of ASE custom fields as Oxygen and Bricks builder's dynamic data provider**. The repeater and relationship fields are fully supported in Bricks builder's Query Loop. Repeater field suport in Oxygen builder's Repeater module requires implementation from within Oxygen builder's plugin, so, if you'd like to see that happen, please comment / vote / request it at [this Github issue](https://github.com/soflyy/oxygen-bugs-and-features/issues/3499), which is their official feature request channel.

* **[IMPROVED][Free][Pro] Admin Interface >> Clean Up Admin Bar**: when 'Howdy' is hidden and "Avatar Display" is disabled in Settings >> Discussion, the profile menu will also hide the default user avatar and the profile dropdown will no longer show empty space where the avatar was shown before. Props to Stijn V. for reporting the issue and providing the one-line code fix for it.

* **[IMPROVED][Free][Pro] Log In | Log Out >> Change Login URL**: after resetting password, the "Log in" link in "Your password has been reset. Log in" will now link to the custom login URL. More generally, wp_login_url() will now return the custom login URL. Props to [@timbre-design](https://wordpress.org/support/topic/change-login-url-disables-reset-password-function/) for reporting the issue.

* **[FIXED][Free][Pro] Log In | Log Out >> Change Login URL**: when a user is logged-in and attempt to visit the custom login URL, the user was being logged out and redirected to the login page. Now, the user be redirected to /wp-admin/ and will stay logged-in. Props to [@boah123](https://wordpress.org/support/users/boah123/) for [reporting the issue](https://wordpress.org/support/topic/change-login-url-disables-remember-me/) with detailed steps to reproduce it.

* **[FIXED][Pro] Utilities >> Local User Avatar**: fixed fatal error in certain scenario when $user object is not being returned.

* **[FIXED][Pro] Admin Interface >> Admin Menu Organizer**: fixed an issue where /wp-admin/ (Dashboard) is not accessible upon successful login when this module is active. Props to John M. for reporting the issue and help with narrowing down the probably cause.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: fixed an issue with SureMembers Access Groups listing page not showing the group title and action links when Admin Columns Manager is active. Props to Volker D. for reporting the issue and facilitating troubleshooting.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: nothing is listed in the Custom Fields section when organizing columns for WooCommerce Orders (shop_order), including custom fields from ACF. Props to Jacob E. for reporting the issue and providing additional information to help troubleshoot the issue.

### 6.5.0 (2024.01.22) - ASE Free and Pro

* **[NEW][Free][Pro] Content Management >> Media Library Infinite Scrolling**: Re-enable infinite scrolling in the grid view of the media library. Useful for scrolling through a large library. Props to [@benbaudart](https://wordpress.org/support/users/benbaudart/) for [suggesting this](https://wordpress.org/support/topic/feature-request-infinite-scroll-in-media-library/).

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: make WP core, ASE, ACF and Meta Box columns with values that are sortable to be automatically sortable, i.e. can be sorted ASC / DESC on clicking the column header. **To enable this, please open the admin columns manager for the post types you have, and click Save Changes**. Props to Julian S. and Uli L for suggesting a feature in this area.

* **[IMPROVED][Free][Pro] Admin Interface >> Hide Admin Notices**: will now properly handle hiding notices on GenerateBlocks settings page. Props to [@tpfoster](https://wordpress.org/support/users/tpfoster/) for [reporting this](https://wordpress.org/support/topic/hide-admin-notices-doesnt-work-on-every-page/) in great detail, which helped with troubleshooting.

* **[IMPROVED][Pro] Utilities >> Local User Avatar**: make sure local avatar is used for [get_avatar_url()](https://developer.wordpress.org/reference/functions/get_avatar_url/). Props to Sunny T. for reporting that Cwicly Image block does not load local avatar when "Dynamic Data >> WordPress >> Author Profile Picture" is selected as the data source, @ardyan for reporting gravatar image was still loaded on their site.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: fixed an issue where Screen Options toggle was not properly toggling RankMath's SEO Title and SEO Desc columns. Props to Sunny T. for reporting the issue.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: fixed missing column values from EDD Orders page. Props to Deborah S. for reporting the issue and helping with troubleshooting.

* **[FIXED][Free][Pro] Optimizations >> Heartbeat Control**: fixed PHP warnign. Props to Maek M. for reporting the issue along with the error log entry.

* **[FIXED][Free][Pro] Disable Components >> Disable Comments**: fixed PHP warning issue. Props to @ken0429ng for reporting the issue.

* **[FIXED][Free][Pro] Fatal error on site migration**: fixed a fatal error that occurred after migrating a site with ASE configured. Props to Bart van O. and JW for reporting the issue and helping with troubleshooting.

### 6.4.0 (2024.01.15) - ASE Free and Pro

* **[NEW][Pro] Content Management >> Terms Order**: Enable custom ordering of terms from various "hierarchical" taxonomies.

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: allow extra columns, e.g. from plugins like WPML and Yoast SEO, to use their original title, which sometimes are icons, e.g. language/country flag to signify language, or traffic light to signify SEO score. Props to Sebastian A. for reporting the issue with WPML language column needing to use the original title, which is the language/country flag, different for each enabled languages.

* **[IMPROVED[Pro] Admin Interface >> Admin Columns Manager**: added support for wp_block post type used for all user-created block patterns since WP v6.3. It will allow showing the sync status column added in the 'wp_pattern_sync_status' post meta. Props to Francois G. for suggesting this.

* **[FIXED][Free][Pro] Admin Interface >> Hde Admin Notices**: fixed another PHP warning.

* **[FIXED][Free][Pro] Settings page**: fixed plugin conflicts causing ASE settings page to go blank, i.e. does not work properly. This is usually caused by other plugins loading scripts on ASE settings page that causes some JS error. Two such plugins if active will no longer cause the issue.

* **[FIXED][Free][Pro] Utilities >> Image Sizes Panel**: fixed fatal error in a certain scenario when GamiPress plugin is enabled. Props to Peter J. for reporting the issue complete with the relevant error log entry, which helped with troubleshooting.

* **[FIXED][Free][Pro] Security >> Email Address Obfuscator**: fixed PHP warning on scenarios where user agent is not detectable.

* **[FIXED][Pro] Content Management >> Custom Content Types**: fixed PHP warning during sanitization of checkbox fields during CPT creation / editing. Also fixed PHP warning when trying to detect post type label for the admin column in the Custom Post Types listing page.

### 6.3.2 (2024.01.10) - ASE Free and Pro

* **[FIXED][Free][Pro] Admin Interface >> Hde Admin Notices**: fixed PHP warning. Props to [@cck23](https://wordpress.org/support/users/cck23/) and [@joeysander](https://wordpress.org/support/users/joeysander/) for [reporting this](https://wordpress.org/support/topic/warning-appears-when-updating-ase-to-6-3-1/).

* **[FIXED] Admin Interface >> Wider Admin Menu**: fixed layout issue in WooCommerce products listing page when viewed on mobile. Props to [@inboundbe](https://wordpress.org/support/users/inboundbe/) for [reporting this](https://wordpress.org/support/topic/wider-admin-menu-conflicts-on-mobile/), and with a screenshot.

### 6.3.1 (2024.01.10) - ASE Free and Pro

* **[IMPROVED][Free][Pro] Disable Components >> Disable Comments**: now will also block comment attempts via XML-RPC and REST API. Props to @jmentalist and Anders for reporting the issue and helping with troubleshooting.

* **[IMPROVED][Pro] Admin Interface >> Admin Menu Organizer**: add "Reset Menu" link at the bottom of the organizer to reset menu order, titles and hidden status. Props to Francois G. for suggesting this.

* **[IMPROVED][Pro] Custom Code >> Code Snippets Manager**: add snippet categories to help organize your code snippets. Props to George N. for the feature suggestion.

* **[IMPROVED][Free][Pro] Admin Interface >> Hde Admin Notices**: fixed an issue where notices are not moved inside the notices panel after the last release. Pro version adds an option to also hide admin notices for non-administrators.

* **[FIXED][Free][Pro] Security >> Limit Login Attempts**: fixed PHP warning errors that occurs in certain scenarios.

* **[FIXED][Free][Pro] Padding issue around "Media (attachment)"** checkbox in module options.

### 6.3.0 (2024.01.07) - ASE Free and Pro

* **[NEW][Free][Pro] Utilities >> Image Sizes Panel**: New module to display a panel showing and linking to all available sizes when viewing an image in the media library. Especially useful to quickly get the URL of a particular image size. Pro version adds a convenient button to copy the image URL on click.

* **[IMPROVED][Free][Pro] Content Management >> Content Duplication**: After clicking the duplicate link, redirects will now go to the edit screen of the duplicate content. Add admin bar link to duplicate content on the edit screen (wp-admin) and singular view (frontend).

* **[IMPROVED][Free][Pro] Admin Interface >> Hide Admin Notices**: Prevent hidden notices from being moved into the notices panel and be made visible. Useful, for example, when plugins add hidden notices for showing action progress or errors when needed.

* **[IMPROVED][Pro] Custom Code >> Code Snippets Manager**: added support for SCSS in CSS snippets. Props to Benjamin P. for the suggestion.

* **[IMPROVED][Pro] Content Management >> Media Categories**: make the categories tree scrollable when there are many categories / sub-categories to make drag-and-dropping onto the tree more convenient. Props to Alin T. for suggesting the improvement.

* **[IMPROVED][Pro] Content Management >> Media Categories**: when you assign a media item to a sub-category, it will automatically be assigned to the parent category and the counter on the parent category will be incremented as well. Props to Alin T. for suggesting the improvement.

* **[IMPROVED][Pro] Security >> Email Address Obfuscator**: fix for auto-obfuscation when email address is inside an HTML tag like &lt;div&gt; or &lt;span&gt;. Props to Julian S. for noticing the issue in Elementor Icon List widget, reporting in details with a screencast, testing further to ensure the_content filter applies there, and finally even suggesting code fixes including the improved regex pattern! Thank you!

### 6.2.7 (2024.01.01) - ASE Free and Pro

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: columns for post ID, menu order, publish date and last modified date are now sortable by default. Props to Hayato for suggesting the Last Modified Date column to be made sortable, which prompted the improvement.

* **[IMPROVED][Free][Pro] Disable Components >> Disable Comments:** Improve logic to ensure comment section is properly hidden, preventing empty gaps from appearing on the frontend. This was observed in GeneratePress theme. Props to Julian for reporting the issue with a detailed screencast / video recording.

* **[IMPROVED][Pro] Content Management >> Custom Content Types**: "Show in REST" is enabled by default during creation of new custom taxonomy. This help ensure that custom taxonomies being created will work in the block editor right away. Props to Arne O. for hinting about the issue in a support ticket.

* **[IMPROVED][Pro] Content Management >> Custom Content Types**: improve module description to emphasize that custom field groups are currently (only) supported for post types.

* **[CHANGED][Free][Pro] Optimizations >> Revisions Control**: exclude bricks_template CPT from the list of post types to enable revisions control for. Bricks handle this via a custom constant as explained at [this article](https://academy.bricksbuilder.io/article/revisions/). So, usage of a PHP code snippet via the Code Snippets Manager module is more appropriate to control revisions on Bricks templates. Props to Kenneth S. for reporting the issue in the first place.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: fix for column header not being hidden when the corresponding Screen Options toggle is unchecked on the post listing table page. Props to David M. and Sunny T. for reporting the issue.

* **[FIXED][Pro] Content Management >> Custom Content Types**: when there are multiple repeater fields for a custom field group, and in the post editing UI, fixed an issue with using the + sign on a repeater field row that may cause sub-fields from another repeater to be added instead. Props to Benjamin P. for reporting the issue in details (with screenshots and highlights added), which helped with replicating the issue and finding the cause and fix.

* **[FIXED][Free][Pro] Admin Interface >> Enhance List Tables >> Show additional filter(s)**: fixed PHP warning for undefined taxonomy-slug array key. Props to Christine M. for reporting the issue.

* **[FIXED][PRO] Content Management >> Custom Content Types >> Custom Field Groups**: fixed link to documentation on a field group edit screen's 'Tips' meta box.

* **[FIXED][Pro] Content Mangement >> Content Order**: fixed an issue where secondary queries in GenerateBlocks Query Loop block were not able to be sorted as intended. Props to Arne O. for reporting the issue in detail and helping with troubleshooting.

### 6.2.6.1 (2023.12.20) - ASE Pro

* **[FIXED][PRO] Admin Interface >> Admin Columns Manager**: fixed fatal error for sites where the NumberFormatter class is not present. Props to David M. for reporting.

### 6.2.6 (2023.12.20) - ASE Free and Pro

* **[ADDED][Pro] Admin Interface >> Admin Columns Manager**: added support for ACF and Meta Box custom fields. This includes ACF repeater and flexible content fields as well as Meta Box cloneable group field.

* **[FIXED][Free][Pro] Left-side footer area in wp-admin** will no longer be blank. Props to C.J. Ezell for reporting the issue.

* **[FIXED][Free] Fixed PHP warning errors** around the sponsorship nudge. Props to [@banijadev](https://wordpress.org/support/users/banijadev/) for [reporting](https://wordpress.org/support/topic/undefined-array-key-have_sponsored/) the issue.

### 6.2.5 (2023.12.12) - ASE Free and Pro

* **[ADDED][Free][Pro] Utilities >> Email Delivery**: added the option to bypass SSL certificate verification. While this would be insecure if mail is delivered across the internet, it could help in certain local and/or containerized WordPress scenarios. Props to Thijs E. for suggesting this feature and providing the code snippet for it.

* **[IMPROVED][Free][Pro] Log In | Log Out >> Change Login URL**: fixed account registration URL going to the /not_found/ 404 page. Improved overall handling of login, registration and password reset flows. Props to [@tedocweb](https://wordpress.org/support/users/tedocweb/) for [reporting the issue](https://wordpress.org/support/topic/change-login-url-15/) and prompting the improvement.

* **[FIXED][Free][Pro] Admin Interface >> Wider Admin Menu**: fix for when the admin menu is shown on a block editor screen (non-fullwidth), it would overlap part of the block editor. Props to Adrien R. for reporting the issue and pointing to the CSS fix.

* **[FIXED][Free][Pro] Admin Interface >> Enhance List Tables**: fixed PHP warning on the media library list view when "Show additional filter(s) for hierarchical, custom taxonomies" is enabled.

### 6.2.4 (2023.12.08) - ASE Free and Pro

* **[IMPROVED][Pro] Security >> Email Address Obfuscator**: added custom subject line for the obfuscate shortcode when mailto linking is enabled. Shortcode example added in module description. Props to Paul R. for the suggestion. Also improved mechanism to auto-obfuscate email addresses in post content so it does not interfere with manually added obfuscation shortcode, which contains an email address in it.

* **[IMPROVED][Free][Pro] Content Management >> Content Duplication**: exclude addition of 'Duplicate' link for WooCommerce products as there's already a native 'Duplicate' link from WooCommerce. Props to Claudio P. for reporting the issue.

* **[FIXED][Free][Pro] Log In | Log Out >> Change Login URL**: fixed an issue where custom login URL redirects to 404 when the site has WPML configured to apply the directory pattern for the main site language. Props to Marcellus J. for reporting the issue.

* **[FIXED][Pro] Optimizations >> Image Upload Control**: fixed an issue where if the PNGs being uploaded are 'paletter' images, the resulting WebP files are blank. Props to Marcellus J. for reporting the issue.

* **[FIXED][Free][Pro] Admin Interface >> Enhance List Tables**: fixed PHP deprecated error, "Constant FILTER_SANITIZE_STRING is deprecated", when "Show additional filter(s) for hierarchical, custom taxonomies" is enabled. Thanks to [Alex @justsmilepeople](https://wordpress.org/support/users/justsmilepeople/) for [reporting this](https://wordpress.org/support/topic/filter_sanitize_string-is-deprecated/).

* **[FIXED][Pro] Custom Code >> Code Snippets Manager**: fixed PHP warning in WP subfolder installs due to non-dynamic definition of a folder in /wp-content/uploads/, which is used to store snippet files for execution. Props to Phil K. for discovering the issue and suggesting the fix.

### 6.2.3 (2023.12.07) - ASE Free and Pro

* **[ADDED][Pro] Security >> Limit Login Attempts >> IP Whitelist**: this should also be useful to unblock a user. Props to Vijayanand V. for the suggestion.

* **[ADDED][Pro] Utilities >> Password Protection**: added IP whitelisting and bypass via URL parameter. Useful for your team to easily view a dev site or providing easy access to clients when reviewing a dev site. Please see module description for details. Props to Alf O.F. for the suggestion to add IP whitelisting.

* **[FIXED][Free][Pro] Disable Components >> Disable Smaller Components >> Disable Emoji Support**: fixed PHP fatal error for an edge case where DNS prefetch of emoji URLs does not return a string. Props to [@tomhung](https://wordpress.org/support/users/tomhung/) for [reporting and providing](https://wordpress.org/support/topic/uncaught-typeerror-strpos/) the code fix.

* **[FIXED][Free][Pro] ASE settings page**: fixed an issue where for some screen size, the main settings section is overlapping with the WP side menu. Props to [@dvaer](https://wordpress.org/support/users/dvaer/) for [reporting this](https://wordpress.org/support/topic/wider-admin-menu-causes-overlap-of-ase-interface/) and also providing the CSS fix for it.

### 6.2.2.1 (2023.12.05) - ASE Pro

* **[IMPROVED][Pro] Utilities >> Maintenance Mode**: improve loading of dependencies on media library and color picker assets.

### 6.2.2 (2023.12.05) - ASE Free and Pro

* **[ADDED][Pro] Utilities >> Maintenance Mode**: add an option to use an image as the page background. Props to Vijayanand V. for the suggestion. Using a solid color is also possible now. Please expect to see this module improved further in future releases.

* **[IMPROVED][PRO] Admin Interface >> Clean Up Admin Bar**: changed algorithm to detect newly added admin bar items, e.g. from theme, plugins or snippets, both on the backend and also the frontend. Props to Jan K. for suggesting the improvement and providing tips on how to achieve it.

* **[IMPROVED][Pro] Optimizations >> Image Upload Control**: transparent PNG will now be converted to transparent WebP. Props to Laurent F. for the suggestion.

* **[IMPROVED][Pro] Content Management >> Custom Content Types**: added description on where to find links to create CPTs, custom taxonomies and custom field groups, which are under the Settings menu.

* **[IMPROVED][Free][Pro] Disable Components >> Disable XML-RPC**: improve code to ensure link to xmlrpc script in &lt;head&gt; is properly removed. Props to Dorel Y. for reporting the issue and suggesting a snippet to do that.

* **[FIXED][Pro] Missing 'Configure' link** in plugin action links on the Pro version, which may make it difficult for users to find the settings page for ASE if they've never used the free version before.

### 6.2.1 (2023.12.01) - ASE Free and Pro

* **[ADDED][Pro] Security >> Obfuscate Email Address**: added the option to automatically obfuscate email addresses inside post content. Props to Dorel Y. for the suggestion.

* **[IMPROVED][Free][Pro] Content Management >> Content Order**: for enabled post types, newly created posts will now be placed at the bottom of the order. This removes the need to manually order after post creation. Props to Bengt R. and Darius for identifying the issue with new posts ordering and suggesting the improvement.

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: added Post Parent and Menu Order columns in the Default (columns) section for hierarchical post types or those supporting page attributes.

* **[IMPROVED][Pro] Content Management >> Custom Content Types >> Custom Field Groups**: tidy up the appearance of TinyMCE toolbar in the WYSIWYG custom field input.

* **[IMPROVED][Free][Pro] Disable Components >> Disable Comments**: this should be more reliable now with improved application of the comments_array filter hook for post types where comments are being disabled. Props to Dorel Y. for reporting the issue with a theme used on their site.

* **[GRAMMAR][Free][Pro] Admin Interface - Enhance List Tables**: remove unneeded dots from the end of the list items. Props to Sridhar K. for spotting that.

* **[FIXED][Free][Pro] Optimizations >> Heartbeat Control**: fixed a PHP Warning when wp-cron.php is triggered. Props to Avi R. and Christian G. for reporting the issue.

* **[FIXED][Free][Pro] Admin Interface >> Admin Menu Organizer**: certain plugins, e.g. GigPress and MemberPress, has parent menu items that were not able to be organized by the AMO module. This should now be fixed. Props to Nadja V.M. and Dana S. for reporting the issue.

* **[FIXED][Pro] Content Management >> Custom Content Types >> Custom Field Groups**: fixed a missing JS file error when using the block editor and having a WYSIWYG custom field loaded.

### 6.2.0 (2023.11.27) - ASE Free and Pro

* **[ADDED][Pro] Utilities >> Local User Avatar**: New module to enable usage of any image from WordPress Media Library as user avatars. Props to Florian B. for the suggestion.

* **[IMPROVED][Free][Pro] Security >> Email Address Obfuscator**: improve module description with clearer examples of how to use the shortcode. Props to jh@ for the suggestion.

* **[IMPROVED][Pro] Content Management >> Custom Content Types >> Custom Field Groups**: image field input will now render the 'medium' size image as a preview in the post edit screen, preventing cropping of images that is better shown uncropped, e.g. brand logo. Props to Simon for providing detailed feedback on this.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: prevent duplication of WooCommerce taxonomy terms in the product categories and product tags columns. Props to Philippe G. and Florian B. for reporting the issue.

* **[FIXED][Free][Pro] Optimizations >> Image Upload Control**: fixed an issue where subsequently uploading files with the same filename will result in the last upload overwriting the earlier uploads, i.e. all uploads ended up with the same filename. This includes scenarios when such images are copy-pasted into the block editor. Props to Manu H. reporting the issue in detail and help with ongoing troubleshooting.

* **[FIXED][Pro] Optimizations >> Image Upload Control**: in some scenarios, the default WebP conversion quality of 82 is not automatically applied upon enabling it. It should now be properly applied. Props to Matija S. for spotting the issue.

### 6.1.3 (2023.11.23) - ASE Free and Pro

* **[ADDED][Pro] Admin Interface >> Hide Admin Bar**: added a Pro feature to (also) hide the admin bar on the backend for some user roles, which maybe desirable for certain types of membership sites.

* **[IMPROVED][Free][Pro] Utilities >> View Admin as Role**: added a floating button to the right-bottom corner of wp-admin pages when viewing as a non-admin user role. In addition to the switcher on the admin bar, this should make it easier to switch back to the administrator role.

* **[IMPROVED][Free][Pro] Utilities >> View Admin as Role**: add a warning for sites that has Ninja Firewall active to uncheck "Block attempts to gain administrative privileges" when this module is enabled to prevent being locked out of the admin account. Props to C de Groot for reporting the issue and suggesting to add a warning.

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: the "Manage Columns" button will now be visible only to administrators. Thanks to Claudio P. for reporting the issue where non-admins can see it and clicking on it produces error messages.

### 6.1.2 (2023.11.21) - ASE Pro

* **[ADDED][Pro] Custom Code >> Code Snippets Manager**: added a description field to each code snippet and a column for it in the list view. Props to Simon (and another user whom I can't figure out who, sorry!) for suggesting this.

* **[IMPROVED][Pro] Custom Code >> Code Snippets Manager**: when Content Order is enabled for code snippets, the 'Order' submenu item will properly be positioned at the end.

* **[IMPROVED][Pro] Custom Code >> Code Snippets Manager**: reduce FOUC (Flash of Unstyled Content) on the add new & edit screens of code snippets.

* **[FIXED][Pro] Admin Interface >> Admin Menu Organizer**: fixed an issue where for certain group of submenu items are not shown when this module is enabled and/or when saving changes with the module enabled. For example, this happened with WooCommerce >> Analytics and FakerPress submenu items. Props to @ubuntuproductions for reporting the issue.

### 6.1.1 (2023.11.17) - ASE Pro

* **[FIXED]Pro] Content Management >> Content Order**: fixed PHP warning for newly added option to use custom order on the frontend. Props to Thomas B. for reporting.

### 6.1.0 (2023.11.17) - ASE Free and Pro

* **[ADDED][Pro] Content Management >> Content Order**: added option to use custom order on frontend query and display of enabled post types. This is done via the pre_get_posts hook for post type archive pages and on secondary queries.

* **[ADDED][Pro] Optimizations >> Image Upload Control**: you can now set the WebP conversion quality, between 1 to 100. Default has been increased from 80 to 82. Props to Istvan for suggesting the improvement after seeing artifacts on blurry parts of certain images when converted to WebP with the default quality settings.

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: newly registered custom taxonomies will now immediately show up in the Admin Columns Manager screen. Previously, you'll need to reset the columns first. Props to Simon for reporting the issue when adding a custom taxonomy with Advanced Custom Fields (ACF), leading to the improvement made.

* **[IMPROVED][Free][Pro] Admin Interface >> Admin Menu Organizer**: styling of WooCommerce separator has been improved to match the other separators.

* **[FIXED][Pro] Admin Interface >> Admin Menu Organizer**: fixed a bug where opting to always hide a menu item does not properlly work. Also added special treatment for Yoast SEO menu item that can not be always hidden due to how it is showing a special menu item for editors. Props to Chris for reporting the issue and helping with troubleshooting.

* **[FIXED][Free][Pro] Security >> Email Address Obfuscator**: further fix for Firefox browser, to correctly show the human-readable email address to site visitors. Props to Mark G. for reporting the issue (again!).

* **[FIXED][Free][Pro] Content Management >> Content Duplication**: fixed an issue with CSS variables used in the block editor not being properly duplicated, thus breaking the style of the duplicate post. Props to Tim H. for reporting the issue when working with blocks from GenerateBlocks. Props to Sami M. for reporting the issue.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: fixed an issue where saving changes resulted in an error in the admin-ajax.php call when 5G / 6G firewall rules is enabled in All in One Security plugin. Props to Joko Z. for reporting the issue.

* **[FIXED][Pro] Admin Interface >> Hide Admin Notices**: fixed an issue where "an unexpected network error has occurred!" notice was shown in the admin notices panel despite no apparent network error occurring. Props to Sebastian A. for reporting this.

* **[FIXED][Pro] Custom Code >> Code Snippets Manager**: fixed an issue where last modified date time is inaccurate. Props to Greg F. for reporting the issue and helping with troubleshooting.

### 6.0.8.2 (2023.11.13) - ASE Pro

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: Fix issues with Rank Math SEO admin columns. Ensure they are properly manageable with ASE while retaining bulk-editability, and prevent duplicate values from being shown. Props to Benjamin P. for reporting the issue with detail and screenshots.

### 6.0.8.1 (2023.11.13) - ASE Free and Pro

* **[IMPROVED][Pro] Content Management >> Media Categories**: frontend CSS previously (also) loaded for public visitors will only be shown to logged-in users when they work with the media library in page builders.

* **[IMPROVED][Free][Pro] Content Management >> External Permalinks**: completely remove jQuery dependency so it no longer loads on the frontend. Previously the frontend JS was improved to no longer use jQuery, but the dependency was still there. Props to David M. for reporting the issue as he was working on his site using Bricks builder and wanted to optimize page load time.

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: Further improvement to the handling of custom columns from plugins, prompted by report from Paul R. of an issue with columns from WooCommerce Product Retailers plugin.


### 6.0.8 (2023.11.12) - ASE Pro

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: Improved handling for custom columns from plugins, e.g. SEO plugins like Yoast SEO, All in One SEO (AIOS), Rank Math SEO and SEOPress. There's now a dedicated "Extra" section on the admin columns management page/UI. There's also a "Reset Columns" button that will allow for restoring columns to the initial state and re-listing available default, extra and custom field columns to choose from. Props to Benjamin P. and Tim for reporting issues with Rank Math and SEOPress which prompted the improvement.

* **[FIX][Pro] Admin Interface >> Admin Menu Organizer**: Fixed an issue where submenu items could not be re-ordered. Props to Bruze Z. for reporting the issue with WooCommerce Products submenu items.

### 6.0.7 (2023.11.10) - ASE Free and Pro

* **[FIX][Free][Pro] Disable Components >> Disable Smaller Components >> Disable Dashicons CSS and JS**: Fix for scenarios where a custom login URL is set with another plugin, e.g. All in One Security (AIOS), and disabling Dashicons will mess up the styling of that login page. Thanks to Aleš for reporting the issue.

* **[FIX][Free][Pro] Security >> Obfuscate Author Slugs**: Fixed PHP warning "Undefined array key SERVER_ADDR" in certain scenarios where server IP is not detectable via that PHP server global. Thanks to Marco M. for reporting.

* **[FIX][Free][Pro] Log In | Log Out >> Change Login URL**: Fix for password-protected pages getting redirected to the /not_found/ 404 page even after entering the correct password. Props to [@vanektomas](https://wordpress.org/support/users/vanektomas/) and [@netzzjd](https://wordpress.org/support/users/netzzjd/) for [reporting this](https://wordpress.org/support/topic/bug-in-change-login-url-if-its-enabled-and-set/).

* **[FIX][Free][Pro] Utilities >> Display System Summary**: Fixed PHP fatal error when mysqli_connect() fails to connect to the database. Thanks to Diaz X. and Simon for reporting the issue.

* **[FIX][Pro] Admin Interface >> Admin Columns Manager**: Fixed PHP Warning "non-numeric value encountered". Props to Elon R. for reporting the issue in detail.

### 6.0.6 (2023.11.05) - ASE Free and Pro

* **[ADDED][Pro] Admin Interface >> Admin Columns Manager**: Horizontal scrolling on the posts list table is automatically enabled when there are columns using custom width in pixels that have the combined width greater than the width of the list table. This is especially useful for post types with many custom fields and you'd like to show many / most of them in the list table.

* **[IMPROVED][Pro] Admin Interface >> Admin Columns Manager**: Ensure custom columns are shown correctly in the posts list table after performing Quick Edit on a post. Props to Benjamin P. for reporting the issue.

* **[FIXED][Pro] Admin Interface >> Admin Columns Manager**: Fixed several PHP Warnings when custom fields have no value assigned yet for a post.

* **[FIXED][Free][Pro] Admin Interface >> Hide Admin Notices**: Connection error notices were shown immediately after enabling this module, regardless of whether there was an actual connection error. It will now be properly hidden and only shown as needed. Props to Sebastian A. for reporting the issue.

* **[FIXED][Pro] Content Management >> Custom Content Types**: The WYSIWYG custom field will now correctly insert the media when using the Add Media button/UI.

* **[FIXED][Free][Pro] Security >> Email Address Obfuscator**: Fixed an issue with Firefox browser not correctly showing the human-readable version of the email. Props to Mark G. for reporting the issue with helpful screenshots.

### 6.0.5.1 (2023.10.31) - ASE Free and Pro

* **[FIXED][Free][Pro] Log In | Log Out >> Change Login URL**: Fixed an issue where correct login redirects to /not_found/ after latest changes in v6.0.5. Props to Gustavo F. and Ignacio C. for reporting the issue.

### 6.0.5 (2023.10.30) - ASE Free and Pro

* **[IMPROVED][Free][Pro] Log In | Log Out >> Change Login URL**: Redirection of /wp-login.php and /wp-admin/ to the 404 /not_found/ URL works more reliably now. Props to Gustavo F. for reporting the issue where those URLs were not properly redirecting to /not_found/ on his sites and ongoing help with troubleshooting it.

* **[FIXED][Pro] Admin Interface >> Admin Menu Organizer**: Missing submenu items should now be shown in the admin menu, as well as ASE Pro settings page's sortables. i.e. the module should more reliably show submenu items now. Props to Bruce Z for reporting this for an ACF Pro CPT menu item which had missing submenu items on his site.

* **[FIXED][Pro] Content Management >> Custom Content Types**: Fixed a PHP Warning error. Props to Sam E.B. for reporting.

### 6.0.4 (2023.10.26) - ASE Free and Pro

* **[IMPROVED][Free] Admin Interface >> Hide Admin Notices**: Removed broad-sweeping CSS rules that unintentionally affected notices that were not supposed to be hidden into the admin notices panel. Props to Ingo R. for reporting the issue with MainWP site connection error notice being hidden on page load.

* **[IMPROVED][Free] Disable Components >> Disable Smaller Components >> Disable version number**: will now only remove version number from static assets in the public view (non-logged-in) of pages. Props to Ingo R for reporting that ACSS auto-BEM feature in Bricks was missing it's button in the structure panel, and was caused by version number being removed from static assets.

* **[FIXED][Free][Pro] PHP Deprecation notice in PHP 8.1:** which showed up when `null` was unintentionally passed into `preg_replace()` during the rendering of some ASE setting fields. Props to Stewart R. for reporting the issue.

* **[FIXED][Pro] Admin Interface >> Admin Menu Organizer:** now correctly shows all submenu items. Props to Henry R. for reporting an issue where several of Elementor's 'Templates' menu's submenu items gone missing when this module was enabled.

### 6.0.3 (2023.10.18) - ASE Free and Pro

* **[FIXED][Free] Admin Interface >> Hide Admin Notices**: Plugin description and update success message will now remain visible after plugin update process is completed on the Plugins listing page.
* **[FIXED][Pro] Custom Code >> Code Snippets Manager**: CSS fixes for the activate / deactivate / publish / update box on each snippet.
* **[FIXED][Free] Utilities >> Display System Summary**: CSS fix to ensure system summary is displayed in alignment with other elements in the At a Glance widget

### 6.0.2 (2023.10.17) - ASE Pro

* **[FIXED] Admin Interface >> Admin Columns Manager**: Will now render the value of custom fields added using default WordPress UI/metabox for managing custom fields. This is rendered using [`get_post_meta()`](https://developer.wordpress.org/reference/functions/get_post_meta/). Props to Upekkha for reporting it.

### 6.0.1 (2023.10.13) - ASE Pro

* **[FIXED] Custom Code >> Code Snippets Manager**: PHP Warning: undefined variable. Props to Michael S.P. for reporting it.
* **[FIXED] Admin Interface >> Admin Columns Manager**: PHP Warning: undefined array key 0. Props to Sam for reporting it.
* **[FIXED] Content Management >> Custom Content Types**: PHP Deprecation: creation of dynamic properties. Props to Steven for reporting it.
* **[FIXED] Optimizations >> Image Upload Control**: .jpeg files will now properly be converted to .webp. Props to Julian S for reporting it.

### 6.0.0 (2023.10.11)

* **[ADDED] Log In | Log Out >> Site Identity on Login Page**: Use the site icon and URL to replace the default WordPress logo with link to wordpress.org on the login page. Props to [@cooper08](https://wordpress.org/support/users/cooper08/) and [@julians3](https://wordpress.org/support/users/julians3/) for [suggesting this](https://wordpress.org/support/topic/change-login-wp-logo/) along with the code snippet.

* **[Pro] The Pro version of ASE is here. Lifetime Deal (LTD) is available.** Find out more at [wpase.com](https://www.wpase.com/chnlg-to-web).

### 5.8.1 (2023.10.05)

* **[FIXED] wp_die error message** when updating to v5.8.0. Thanks to [@verysiberian](https://wordpress.org/support/users/verysiberian/) for the prompt and detailed [report](https://wordpress.org/support/topic/wsod-on-all-sites-after-update/) and [@wrlkd](https://wordpress.org/support/users/wrlkd/), [@rockwildaz](https://wordpress.org/support/users/rockwildaz/) and [@amnwtritaly](https://wordpress.org/support/users/amnwtritaly/) for helping out with troubleshooting.

### 5.8.0 (2023.10.05)

* **[ADDED] Disable Components >> Disable Smaller Components >> Disable version number**: You can now hide version number which by default is part of static resource (CSS/JS) URLs in the &lt;head&gt;. Props to [@cooper08](https://wordpress.org/support/users/cooper08/) for [suggesting this](https://wordpress.org/support/topic/disable-smaller-components/) along with the code snippet.

* **[ADDED] Disable Components >> Disable Smaller Components >> Disable jQuery Migrate**: You can now disable the jQuery Migrate script from loading on the frontend. Props to [@blueoaks](https://wordpress.org/support/users/blueoaks/) for [suggesting this](https://wordpress.org/support/topic/feature-request-disable-jquery-migrate/).

* **[CHANGED] Admin Interface >> Hide Admin Notices**: This is now limited to site admins only. Editors, Authors and other user roles will see notices as usual.

* **[FIXED] Admin Interface >> Hide Admin Notices**: Notices under each plugin that has an update in the Plugins list page now shows up normally where it was previously missing/hidden when Hide Admin Notices is active. Thanks to [@venkeyaccent](https://wordpress.org/support/users/venkeyaccent/), [@dmdeck](https://wordpress.org/support/users/dmdeck/) and [@computerbuddha](https://wordpress.org/support/users/computerbuddha/) for reporting this issue [here](https://wordpress.org/support/topic/bug-in-hide-admin-notices/) and [here](https://wordpress.org/support/topic/hide-admin-notices-issue/).

* **[IMPROVED] Admin Interface >> Wider Admin Menu**: Now works when wp-admin is in Right-to-Left (RTL) languages. Thanks to [@mehdimoradi7172](https://wordpress.org/support/users/mehdimoradi7172/) for [reporting the issue](https://wordpress.org/support/topic/wider-admin-menu-rtl-issue/).

* **[IMPROVED] Log In | Log Out >> Change Login URL**: Default lost password URL no longer accessible directly and must now include custom login slug. Props to [@banijadev](https://wordpress.org/support/users/banijadev/) for discovering the issue and [reporting it](https://wordpress.org/support/topic/add-support-hidden-lost-password-path/).

* **[IMPROVED] Content Management >> Media Replacement**: media-replace-frontend.css no longer loads for site visitors. Props to [@cvladan](https://wordpress.org/support/users/cvladan/) for discovering the issue and [reporting it](https://wordpress.org/support/topic/dont-load-media-replace-frontend-css-on-frontend/).

* **[SECURITY] Utilities >> Password Protection**: Patched a bypass vulnerability disclosed responsibly by security researcher Abu Hurayra via Patchstack, regarding the authentication cookie. It now uses wp_hash_password() and wp_check_password().

### 5.7.1 (2023.09.12)

* **[FIXED] Disable Components >> Disable Gutenberg**: Fixed PHP Warnings. Thanks to [@ysintos](https://wordpress.org/support/users/ysintos/) and [@ofmarconi](https://wordpress.org/support/users/ofmarconi/) for reporting this issue [here](https://wordpress.org/support/topic/unable-to-upload-images-after-last-update-5-7-0/) and [here](https://wordpress.org/support/topic/warning-class-disable-components-php-on-line-238/).

* **[FIXED] Custom Code >> Custom Admin / Frontend CSS**: Fix for escaped symbols being output on inlined stylesheets. Thanks to [@d4niwp](https://wordpress.org/support/users/d4niwp/) for [reporting this](https://wordpress.org/support/topic/issue-with-admin-and-frontend-css-selector-is-converted-into/).

### 5.7.0 (2023.08.30)

* **[NEW] Refreshed UI for ASE admin page.** This is largely based off of the UI/X design work generously provided by [@rinodeboer](https://wordpress.org/support/users/rinodeboer/). Thanks so much Rino!

* **[IMPROVED] Admin Interface >> Hide Admin Notices:** Handle additional notices which appears on an admin pages that have been encapsulated inside an additional div, which is sometimes done by plugins that modifies a WP core admin screen.

* **[IMPROVED] Refactored admin-page.js**, the main js file used to build out ASE's admin page. It's tidier and smaller now.

* **[CHANGED] Move Enhance List Table module inside Admin Interface tab** and reorganize modules in that tab for better logical grouping / sequencing.

### 5.6.2 (2023.08.25)

* **[FIXED] JS error in admin-page.js.** Props to [@andyguzman](https://wordpress.org/support/users/andyguzman/) for [reporting it](https://wordpress.org/support/topic/avif-jquery-breaking-the-settings-page/).


### 5.6.1 (2023.08.24)

* **[FIXED] Content Management >> Media Replacement**: Fixed PHP Warnings. Thanks to Marco for reporting this issue.

* **[IMPROVED] Admin Interface >> Hide Admin Notices**: Fixed missing commas in JS file to target certain notice divs. Notices no longer 'flash' briefly now before being hidden inside the notices panel. Thanks to [@cvladan](https://wordpress.org/support/users/cvladan/) for [suggesting the fix](https://wordpress.org/support/topic/conflict-with-another-plugin-suggestion/).

### 5.6.0 (2023.08.14)

* **[ADDED] Utilities >> Display System Summary**: Show quick summary of the system the site is running on to admins, in the "At a Glance" dashboard widget. This includes the web server software, the PHP version, and the database software. Props to Keith for suggesting this.

* **[IMPROVED] Security >> Limit Login Attempts**: Improve SQL query for creating the failed logins log table so it is more compatible with a wider range of DB setups.

### 5.5.2 (2023.08.09)

* **[IMPROVED] Disable Components >> Disable Gutenberg**: default WP post types (wp_template, wp_template_part, wp_global_styles, wp_navigation) are now removed from the Disable Gutenberg settings.

* **[IMPROVED] Admin Interface >> Admin Menu Organizer**: when a hidden menu item is selected, i.e. the admin page for it is being viewed, the menu item will now remain visible along with it's sub-menu items. This makes for a better UX overall, reducing back and forth clicks of the "Show All" toggle. Props to [@tomhung](https://wordpress.org/support/users/tomhung/) and [@dvaer](https://wordpress.org/support/users/dvaer/) for [suggesting this](https://wordpress.org/support/topic/feature-request-show-all-menu-stay-open-when-on-closed-menu-item/).

* **[IMPROVED] Disable Components >> Disable Feeds**: the /feed/ page now will properly return 403 Forbidden response. It was previously showing 500 error when a site is inspected with [Sucuri malware and security checker](https://sitecheck.sucuri.net/). The code changes for this was produced by ChatGTP via [@ofmarconi](https://wordpress.org/support/users/ofmarconi/)'s [prompt](https://wordpress.org/support/topic/sucuri-error-500-disable-feed/#post-16957833) as part of his [detailed report and investigation](https://wordpress.org/support/topic/sucuri-error-500-disable-feed/) on the issue. Thank you!

* **In-Kind Sponsorship**: If anyone has spare license for [GenerateBlocks Pro](https://generateblocks.com/pro/), [Blockstudio](https://blockstudio.dev/), [Lazy Blocks](https://www.lazyblocks.com/), [CubeWP](https://cubewp.com/) and/or [WS Form](https://wsform.com/) that you'd like to donate to my test site for ASE, please [get in touch](https://bowo.io). Thank you!

### 5.5.1 (2023.08.07)

* **[FIXED] Admin Interface >> Admin Menu Organizer:** some plugins register their menu item with a super-late priority, e.g. 10000, so, ASE can not organize them properly. Changes have been made to accommodate such scenario. Props to [@gd4web](https://github.com/gd4web) for [reporting this](https://wordpress.org/support/topic/features-request-and-issue-reporting/) in detail.

* **[FIXED] Content Management >> Content Order:** Fix for PHP errors that appear if a post type has been de-registered while content ordering is still enabled for it in ASE.

* **[FIXED] Security >> Limit Login Attempts:** Fixed an issue for when 'sql_require_primary_key' is set in the DB, e.g. managed MySQL DB in DigitalOccean, ASE would churn out "Attempt to create or modify table without primary key" error. Props to [Greg Mount](https://github.com/gd4web) for [reporting this](https://wordpress.org/support/topic/attempt-to-create-or-modify-table-without-primary-key/) in great detail and suggesting the fix.

* **[IMPROVED] Disable Components >> Disable Comments:** Add filter to prevent anonymous comment via XML-RPC. Props to [@bzosel](https://github.com/bzosel) for [reporting an issue with disabling comment](https://wordpress.org/support/topic/comment-was-added-although-comments-disabled/) that prompted this improvement.

* **[NEW MONTHLY SPONSOR] Thank you @maeonian for being a new monthly sponsor at USD 2 / month!** ASE is now at 6 of 10 of the monthly sponsors goal. It doesn't take much to sponsor ASE for the time and effort savings you may have gained by using it on your sites. You can [be one](https://github.com/sponsors/qriouslad) today!

### 5.5.0 (2023.08.04)

* **[ADDED] Security >> Email Address Obfuscator**. Obfuscate email address to prevent spam bots from harvesting them, but make it readable like a regular email address for human visitors. Props to [@nassukesso](https://github.com/nassukesso) for [suggesting this feature](https://wordpress.org/support/topic/email-address-obfuscating/).

* **[FIXED] In some scenarios, ASE settings page only shows the category tabs while not showing any of the actual modules settings**. Props to John B. for reporting this and facilitating troubleshooting. He also kindly provided licenses to premium plugins on my test site, that will help with ASE development and maintenance in the future. If you have spare licenses for [~~Elementor Pro~~](https://elementor.com/pro/), [~~Spectra Pro~~](https://wpspectra.com/pro/), [~~Bricks~~](https://bricksbuilder.io/), [~~Cwicly~~](https://cwicly.com/) and/or [~~JetEngine~~](https://crocoblock.com/plugins/jetengine/) that you'd like to donate to my test site, please [get in touch](https://bowo.io).

### 5.4.1 (2023.07.31)

* **[Fixed] Security >> Limit Login Attempts**. Fixed an error on sites with older version of MySQL / MariaDB that caused the failure of DB table creation to be used to log failed login attempts. Props to [Ken Sim](https://wordpress.org/support/users/kwsim539/) for [reporting this](https://github.com/qriouslad/admin-site-enhancements/issues/4) in great detail, which made it much easier to fix. Ken has also kindly became a [monthly sponsor](https://github.com/sponsors/qriouslad) of my work. Thanks Ken! I'm now at 5 of my initial goal of [getting 10 monthly sponsors](https://github.com/sponsors/qriouslad). 🙂

* **[IMPROVED] Content Management >> Enhance List Tables >> Show featured image column:** will now replace the default product thumbnail column on WooCommerce product listing page, and no longer show both columns. Props to [@studio84digital](https://wordpress.org/support/users/studio84digital/) for [reporting it](https://wordpress.org/support/topic/show-featured-image-column-woocommerce/) on the [support forum](https://wordpress.org/support/plugin/admin-site-enhancements/).

### 5.4.0 (2023.07.25)

* **[ADDED] Content Management >> Allow Custom Navigation Menu Items to Open in New Tab**. Allow custom navigation menu items to have links that open in new browser tab via target="\_blank" attribute. The rel="noopener noreferrer nofollow" attribute will also be added for enhanced security and SEO benefits. Props to [@tomhung](https://github.com/tomhung) for [suggesting this feature](https://github.com/qriouslad/admin-site-enhancements/issues/4) and providing the [code snippet](https://gitlab.com/-/snippets/2567854) to base it upon.

* Checked compatibility with WordPress v6.3 RC1

### 5.3.2 (2023.07.15)

* **[FIXED] Disable Smaller Components >> Disable the generator meta tag:** fixed PHP fatal error in some scenarios for PHP v8.0+. Props to [@swissspidy](https://github.com/swissspidy) for [reporting it](https://github.com/qriouslad/admin-site-enhancements/issues/4) on Github.

* **[IMPROVED] Content Management >> External Permalinks:** removed jQuery dependency on the front-end in public.js. Replaced script with pure JS version churn out by ChatGPT which was prompted by Marco M.J. So, yes... first AI usage in ASE code. Pretty cool. 🙂

### 5.3.1 (2023.07.14)

* **[FIXED] Admin Interface >> Disable Dashboard Widgets:** fixed PHP warning. Props to [@mohobook](https://wordpress.org/support/users/mohobook/) for [reporting it](https://wordpress.org/support/topic/code-error-media-replacement-not-working/).

* **[IMPROVED] Content Management >> Open All External Links in New Tab:** will now exclude relative URLs which points to internal URLs from being opened in a new tab. Props to [@francismacomber](https://wordpress.org/support/users/francismacomber/) for [reporting it](https://wordpress.org/support/topic/open-all-external-links-in-new-tab-misidentifies-relative-urls-as-external/).

### 5.3.0 (2023.07.08)

* **[IMPROVED] Utilities >> Email Delivery**: You can now send a test email to verify if your custom sender name/email and SMTP configuration work as intended. Props to many users for suggesting this in the support forum [here](https://wordpress.org/support/topic/request-test-email-delivery/), [here](https://wordpress.org/support/topic/test-smtp-email-delivery/), [here](https://wordpress.org/support/topic/smtp-email-delivery/), [here](https://wordpress.org/support/topic/check-smtp-by-sending-an-email/) and [here](https://wordpress.org/support/topic/please-add-a-test-option-for-smtp/).

### 5.2.11 (2023.07.05)

* **[IMPROVED] Utilities >> Password Protection** This will now also work on non-HTTPS sites. Props to Sascha for reporting the issue via the contact form at [bowo.io](https://bowo.io) and providing great detail about what was done to try and troubleshoot it, and later on found the root cause of the issue, which made fixing the issue much simpler.

### 5.2.10 (2023.06.30)

* **[IMPROVED] Utilities >> Email Delivery** Custom sender name / email can now be set and enforced independent of delivery via external SMTP service/account. Props to [@lcwilson18](https://wordpress.org/support/users/lcwilson18/) for [suggesting it](https://wordpress.org/support/topic/smtp-email-delivery/).

### 5.2.9 (2023.06.27)

* **[FIXED] Log In | Log Out >> Redirect After Login** will now correctly redirect to an internal wp-admin page, e.g. wp-admin/edit.php?post_type=page (View All Pages). Props to [@tomhung](https://wordpress.org/support/users/tomhung/) for [reporting it](https://wordpress.org/support/topic/redirect-after-login-wont-redirect-to-internal-page/).

### 5.2.8 (2023.06.26)

* **[IMPROVED] Front-end public.js** will only be loaded if relevant modules that use it are enabled. Props to Martin M. for reporting the issue via the contact form at [bowo.io](https://bowo.io) and Maxime D. via Facebook message.

### 5.2.7 (2023.06.26)

* **[FIXED] Custom Code**: Possible fix for CodeMirror (code editor) JS error caused by jQuery not being explicitly set as a dependency. When this happens, ASE's admin page is rendered blank / useless. Props to [@sarah-haruel](https://wordpress.org/support/users/sarah-haruel/) for [reporting it](https://wordpress.org/support/topic/admin-and-site-enhancements-ase-disabled-when-slideshow-se-activated/).
* **[IMPROVED] Log In | Log Out >> Change Login URL**: Improve module description to avoid confusion with renaming the entire /wp-admin/ as opposed to renaming just the login URL. Props to [Maz Ziebell](https://wordpress.org/support/users/max-ziebell/) for the suggestion.
* **[FIXED] Disable Components >> Disable Gutenberg**: Fixed PHP warning when saving a CPT post where gutenberg has been disabled. Props to [@gregmount](https://wordpress.org/support/users/gregmount/) for [reporting it](https://wordpress.org/support/topic/disable-gutenberg-throws-php-warning/).
* **[ADDED] A dismissible Sponsorship / Rating / Feedback nudge/notice** has been added to ASE's admin page (only), which will appear after every 10 consecutive clicks of the "Save changes" button. This is a way to try and gain additional support for ASE's ongoing development and maintenance. I hope you don't mind. 🙂

### 5.2.6 (2023.06.24)

* **[FIXED] Security >> Limit Login Attempts**: Fixed PHP warning issue. Props to [@tomo55555](https://wordpress.org/support/users/tomo55555/) for [reporting it](https://wordpress.org/support/topic/warning-error-reported/).

### 5.2.5 (2023.06.22)

* **[FIXED] Utilities >> Password Protection**: Fixed the issue that causes the password protection form/page to have no styling after v5.2.4 patch release. Props to [@gregmount](https://wordpress.org/support/users/gregmount/) for [reporting it](https://wordpress.org/support/topic/v5-2-4-breaks-styling-on-password-protection/).

### 5.2.4 (2023.06.21)

* **[FIXED] Disable Components >> Disable Dashicons**: Fixed PHP warning when executing WP CLI commands. Props to [@tomhung](https://wordpress.org/support/users/tomhung/) for [reporting it](https://wordpress.org/support/topic/php-warning-strpos-empty-needle-2/).

* **[RESOLVED] Unable to Save Changes in ASE**: This is caused by plugin conflict which happens when [WP STAGING - Backup Duplicator & Migration](https://wordpress.org/plugins/wp-staging/) is active. Props to [@kilimats](https://wordpress.org/support/users/kilimats/) for [reporting it](https://wordpress.org/support/topic/doesnt-save-14/) and to [@allbutone](https://wordpress.org/support/users/allbutone/) for liasing with the developer of WP Staging to [fix things on their end](https://wordpress.org/support/topic/plugin-conflict-prevents-saving/).

### 5.2.3 (2023.06.18)

* **[FIXED] Optimizations >> Image Upload Control**: fixed a small code error that prevented image resizing from actually happening. Props to [@matija80](https://wordpress.org/support/users/matija80/) for [reporting it](https://wordpress.org/support/topic/image-upload-control-resizing-doesnt-work/).

* **[FIXED] Admin Interface >> Disable Dashboard Widgets**: fixed "array offset on value of type bool" error. Props to [@grizdev](https://wordpress.org/support/users/grizdev/) for [reporting it](https://wordpress.org/support/topic/disable-dashboard-widgets-produces-array-offset-on-value-of-type-bool-error/).

* **[FIXED] Admin Interface >> Wider Admin Menu**: fixed an issue where wider admin menu pushed WooCommerce page header content to shift right and be cut-off from the screen. Props to [@malaga16](https://wordpress.org/support/users/malaga16/) for [reporting it](https://wordpress.org/support/topic/admin-menu-sidebar-custom-with-problem/).

* **[FIXED] Utilities >> Password Protection**: fixed PHP Warning. Props to [@gregmount](https://wordpress.org/support/users/gregmount/) for [reporting it](https://wordpress.org/support/topic/php-warning-on-password-protected-login/).

### 5.2.2 (2023.06.14)

* **[FIXED] Security >> Limit Login Attempts**: fixed redirection loop that happens when custom login URL is enabled and user has reached the allowed failed login attempts count. Props to [@allbutone](https://wordpress.org/support/users/allbutone/) for [reporting it](https://wordpress.org/support/topic/error-limit-login-attempts/) and narrowing down the steps to replicate the issue reliably, which helps with troubleshooting.

### 5.2.1 (2023.06.13)

* **[FIXED] Disable Components >> Disable Comments**: Fixed an issue where a JS alert saying "Are you sure you want to do this? The comment changes you made will be lost." popups when trying to update a page / post / post type where commenting has been disabled. Props to [@dagaloni](https://wordpress.org/support/users/dagaloni/) for [thoroughly reporting it](https://wordpress.org/support/topic/are-you-sure-you-want-to-to-this-popup-for-updateting-page/) with screenshots and steps to replicate the issue.

* **[FIXED] Utilities >> View Admin as Role**: Provide a simple and secure method to regain administrator access when something goes wrong while switching to non-administrator role. This occasionally happens when the login session ended and you're being logged out by the system. Instruction on how to regain administrator access has been added to the settings section for the module. Props to [@cebuss](https://wordpress.org/support/users/cebuss/) for [thoroughly reporting it](https://wordpress.org/support/topic/feature-request-avoid-locking-people-out-of-their-site/) and providing a way to regain access via direct changes to the database.

* **[SECURITY] Utilities >> View Admin as Role**: Make sure only users performing role switching can access the role switcher admin bar menu. This ensures that non-administrator users logging in normally won't see the menu and prevent them from performing role switching.

### 5.2.0 (2023.06.10)

* **[ADDED] Admin Interface >> Wider Admin Menu**. Give the admin menu more room to better accommodate wider items. Props to [@dvaer](https://profiles.wordpress.org/dvaer/) and [@kilimats](https://wordpress.org/support/users/kilimats/) for [suggesting this](https://wordpress.org/support/topic/feature-request-wider-admin-menu/).

### 5.1.0 (2023.06.04)

* **[ADDED] Disable Components >> Disable Block-Based Widgets Settings Screen**. Restores the classic widgets settings screen when using a classic (non-block) theme. Props to [@ruralinfo](https://profiles.wordpress.org/ruralinfo/) and [@dvaer](https://profiles.wordpress.org/dvaer/) for [suggesting this](https://wordpress.org/support/topic/feature-request-disable-gutenberg-for-widget-page/).
* **[FIXED] UTILITIES >> SMTP Email Delivery**: fixed an issue where 'from' email is using WP default wordpress@site.com instead of the email defined in module settings. Props to [@scarlywebs](https://wordpress.org/support/users/scarlywebs/) for [reporting it](https://wordpress.org/support/topic/smtp-from-not-working/) and the continued help in testing.
* **[ENHANCED] UTILITIES >> SMTP Email Delivery**: added option to force the usage of the FROM name/email defined in module settings. [Suggested](https://wordpress.org/support/topic/smtp-from-not-working/#post-16762588) by [@scarlywebs](https://wordpress.org/support/users/scarlywebs/).
* **[FIXED]** Custom, right-side footer text now only shows on the plugin's main settings page.

### 5.0.2.4 (2023.06.01)

* **[FIXED] Utilities >> SMTP Email Delivery**: fixed an issue with encoding / decoding of stored password that prevented proper functioning of this module. **Please re-enter your password and save changes**.
* **[FIXED] Utilities >> Password Protection**: fixed an issue with encoding / decoding of stored password that prevented proper functioning of this module. **Please re-enter your password and save changes**.

### 5.0.2.3 (2023.05.25)

* **[FIXED] Log In | Log Out >> Change Login URL**: URLs that start with the custom login slug no longer redirects to the login page. Props to [@netzzjd](https://profiles.wordpress.org/netzzjd/) for [reporting it](https://wordpress.org/support/topic/change-login-url-redirects/).

### 5.0.2.2 (2023.05.24)

* **[FIXED] Log In | Log Out >> Change Login URL**: URLs for lost password flow, i.e. password reset flow, are now accessible when this module is enabled. It will no longer redirect to the not_found (404) page. Props to [@scarlywebs](https://wordpress.org/support/users/scarlywebs/) for [reporting it](https://wordpress.org/support/topic/password-reset-url-link/) and help with testing all the way.

### 5.0.2 (2023.05.09)

* **[FIXED] Utilities >> View Admin as Role**: is now only accessible / usable for administrators.
* **[ENHANCE] Admin Interface >> Admin Menu Organizer**: improved description and UI for better clarity.
* **[POLISH] General code polish** to resolve several PHP warnings.

### 5.0.1 (2023.04.29)

* **[FIXED] WordPress Newsboard link in footer** showing up on all wp-admin pages. Now will only show up on the plugin's main page.

### 5.0.0 (2023.04.29)

* **[ADDED] Content Management >> Content Order**. Enable custom ordering of various content types. Useful for displaying them in the desired order in wp-admin and on the frontend. e.g. projects, services, FAQs, team members, etc.
* **[FIXED] Utilities >> Password Protection**: Fix PHP Warning error. Props to [@cvladan](https://github.com/cvladan) for [reporting it](https://wordpress.org/support/topic/minor-php-warning/).
* **[CHANGED] Content Management >> Page and Post Duplication** module has been renamed to "Content Duplication".
* **[ADDED] Translate link** on plugin page's header and in the .org plugin description page. Please [consider helping to translate](https://translate.wordpress.org/projects/wp-plugins/admin-site-enhancements/) the plugin description (a.k.a. README). There are about 100 strings/sentences in it. This will help expand the reach of Admin and Site Enhancements into people who read/speak your language. Special props to [Angelika Reisiger
](https://profiles.wordpress.org/la-geek/), General Translation Editor for Deutsch (German) #de_DE, for tackling translation of the plugin description into [German](https://translate.wordpress.org/locale/de/default/wp-plugins/admin-site-enhancements/) and [German (formal)](https://translate.wordpress.org/locale/de/formal/wp-plugins/admin-site-enhancements/).
* **[ADDED] Link to [WordPress Newsboard](https://bowo.io/asenha-wpn-dsc)** in plugin page's footer. This is another thing I created back in 2014 and is still working to aggregate the latest news, articles, tutorials, podcasts and videos from 100+ WordPress-centric sources.

### 4.9.3 (2023.04.22)

* **[FIXED] Disable Components >> Disable REST API**: fix for error when trying to save edits in Gutenberg editor when Disable REST API module is turned on. Props to [@alexgraphicd](https://profiles.wordpress.org/alexgraphicd/) for [reporting it](https://wordpress.org/support/topic/cant-save-edits-to-posts-or-pages-when-rest-api-is-disabled/).

### 4.9.2 (2023.04.22)

* **[FIXED] Disable Components >> Disable Comments**: fix for certain scenarios where comment form is still shown despite commenting being disabled on the post type. Props to [@crazyserb](https://profiles.wordpress.org/crazyserb/) for [reporting it](https://wordpress.org/support/topic/disable-comments-functionality-doesnt-work-either/), providng temporary admin access, as well as screenshots to help with troubleshooting!

### 4.9.1 (2023.04.19)

* **[ENHANCEMENT] Admin Interface >> Hide Admin Notices**. LearnDash pages was showing up notices below header. This fix will now move them into the hidden, toggleable notices panel.

### 4.9.0 (2023.04.16)

* **[ENHANCEMENT] Content Management >> External Permalinks**. The rel="noopener noreferrer nofollow" attribute will now be added to external permalinks for enhanced security and SEO benefits. 
* **[ADDED] Content Management >> Open All External Links in New Tab**. Force all links to external sites to open in new browser tab via target="\_blank" attribute. The rel="noopener noreferrer nofollow" attribute will also be added for enhanced security and SEO benefits.

### 4.8.3 (2023.04.15)

* **[FIXED] Content Management >> Content Duplication**: fixed isses when duplicating pages built with Oxygen and Bricks builder. May also solve similar issues with other page builders. Please try this on your page builder installation and report in the forum if you're still experiencing issues.

### 4.8.2 (2023.04.03)

* **[FIXED] Admin Interface >> Admin Menu Organizer**: fix for some menu items missing / being hidden in the menu item sortables. Props to [@chrisplaneta](https://profiles.wordpress.org/chrisplaneta/) for [reporting](https://wordpress.org/support/topic/full-of-useful-features-works-without-problems/#post-16620272).

### 4.8.1 (2023.04.03)

* **[FIXED] Admin Interface >> Hide Admin Notices**: now hides notices on some plugins' pages where an extra div exist above the notice divs.
* **[FIXED] Log In | Log Out >> Change Login URL**: fix an issue where sometimes successful login would redirect to /not_found/ URL.
* **[ENHANCEMENT] Content Management >> Media Replacement**: added browser cache busting via jQuery to ensure the new image is shown on the media/attachment edit page after performing replacement. Elsewhere on wp-admin and on the front end, a hard reload is still needed to flush out browser cache for the old image.

### 4.8.0 (2023.04.02)

* **[ADDED] Utilities >> Multiple User Roles**: Enable assignment of multiple roles during user account creation and editing. This maybe useful for working with roles not defined in WordPress core, e.g. from e-commerce or LMS plugins.


### 4.7.4 (2023.03.05)

* **[ADDED] Content Management >> Enhance List Tables >> Show ID in Action Rows**: on the list tables for pages, all post types, all taxonomies, media, users and comments. Props to [@cvladan](https://github.com/cvladan) for the [feature suggestion](https://github.com/qriouslad/admin-site-enhancements/issues/2).

### 4.7.3 (2023.03.03)

* **[FIXED] Optimizations >> Image Upload Control**: "Unexpected response" error in the media uploader when uploading non-JPG files.

### 4.7.2 (2023.02.25)

* **[FIXED] Disable Components >> Disable Smaller Components >> Disable Emojis** now properly disables emojis in admin pages.

### 4.7.1 (2023.02.24)

* **[CHANGED] Utilities >> Maintenance Mode**. When maintenance mode is enabled, an admin bar icon is shown as an indicator.
* **[CHANGED] Utilities >> Password Protection**. Change background color of admin bar status icon from green to red, to better indicate that the site is (currently) inaccessible to the general public.

### 4.7.0 (2023.02.24)

* **[ADDED] Utilities >> Maintenance Mode**. Show a customizable maintenance page on the frontend while performing a brief maintenance to your site. Logged-in administrators can still view the site as usual.
* **[CHANGED] Disable Components >> Disable Smaller Components >> Disable Dashicons CSS and JS** now excludes the login page. This ensures the login page CSS styles is correctly loaded.
* **[CHANGED] Utilities >> SMTP Email Delivery**. Slight improvement to copy/description for improved clarity.

### 4.6.0 (2023.02.22)

* **[ADDED] Utilities >> SMTP Email Delivery**. Use external SMTP service to ensure notification and transactional emails from your site are being delivered to inboxes.

### 4.5.0 (2023.02.17)

* **[ADDED] Admin Interface >> Clean Up Admin Bar >> Remove the Help tab and drawer**.
* **[ADDED] Disable Components >> Disable Smaller Components**. Prevent smaller components from running or loading. Make the site more secure and load slightly faster. This includes disabling the generator &lt;meta&gt; tag, the Windows Live Writer (WLW) manifest &lt;link&gt; tag, the Really Simple Discovery (RSD) &lt;link&gt; tag and the WordPress shortlink &lt;link&gt; tag in &lt;head&gt;, as well as disabling dashicons CSS and JS files for site visitors, and emoji support for pages, posts and custom post types.

### 4.4.0 (2023.02.05)

* **[ADDED] Custom Code >> Custom Body Class**. Add custom &lt;body&gt; class(es) on the singular view of some or all public post types. Compatible with classes already added using [Custom Body Class plugin](https://wordpress.org/plugins/wp-custom-body-class).

### 4.3.1 (2023.02.05)

* **[CHANGED] Content Management >> Media Replace** option is no longer shown on the grid view of the media library. It will only show up on the attachment edit page which can be reached from both the list view via "Edit or Replace Media" link and the grid view media frame's "Edit more details" link.

### 4.3.0 (2023.01.30)

* **[ADDED] Optimizations >> Image Upload Control**. Resize newly uploaded, large images to a smaller dimension and delete originally uploaded files. BMPs and non-transparent PNGs will be converted to JPGs and resized.
* **[CHANGED] Revisions Control** is now under the Optimizations category.
* **[CHANGED] View Admin as Role** is now under the Utilities category.
* **[CHANGED] Improve title of features** to be shorter and more consistent across categories.

### 4.2.2 (2023.01.24)

* **[FIXED] Admin Interface >> Disable Dashboard Widgets**. Fixed a fatal error issue when there's a widget with priority 'high'. Props to [@samirhp](https://profiles.wordpress.org/samirhp/) for [reporting it](https://wordpress.org/support/topic/error-with-new-update-6/).

### 4.2.1 (2023.01.23)

* **[FIXED] Admin Interface >> Disable Dashboard Widgets**. Fixed missing dashboard widgets list when first trying to activate the feature. Also fixes PHP fatal error when trying to view the dashboard when the feature is activated under such scenario. Props to [@samirhp](https://profiles.wordpress.org/samirhp/) for [reporting it](https://wordpress.org/support/topic/error-with-new-update-6/).

### 4.2.0 (2023.01.23)

* **[ADDED] Admin Interface >> Disable Dashboard Widgets**. Clean up and speed up the dashboard by completely disabling some or all widgets. Disabled widgets won't load any assets nor show up under Screen Options.

### 4.1.0 (2023.01.16)

* **[ADDED] Utilities >> Enable Password Protection**. Password-protect the entire site to hide the content from public view and search engine bots / crawlers. Logged-in administrators can still access normally.

### 4.0.1 (2023.01.12)

* **[FIXED] Content Management >> Enable SVG Upload**: Fixed a bug where uploaded, non-SVG media files does not have metadata generated when Enable SVG Upload is enabled.

### 4.0.0 (2023.01.04)

* **[ADDED] Disable Components >> Disable All Updates**. Completely disable core, theme and plugin updates and auto-updates. Will also disable update checks, notices and emails.

### 3.9.2 (2023.01.03)

* **[FIXED] Custom Code >> Manage robots.txt**. Fixed ["Undefined variable" issue](https://wordpress.org/support/topic/undefine-variable-field_option_value/) reported by [kwbrayton](https://profiles.wordpress.org/kwbrayton/). Also make this feature work on scenarios where an actual robots.txt file exists, not just the virtual one created by default, by WordPress. In such case, the robots.txt file will be renamed to robots_txt_backup_{timestamp}.txt when this feature is enabled. Vice versa, when the feature is disabled, the backup file will be renamed back to robots.txt, so it will be in use again as it were.

### 3.9.1 (2022.12.29)

* **[FIXED] Content Management >> Enable External Permalinks**. Fixed an issue where default WordPress permalink for a post loads white, empty screen when no external permalink has been set for the post.

### 3.9.0 (2022.12.29)

* **[ADDED] Content Management >> Enable External Permalinks**. Enable pages, posts and/or custom post types to have permalinks that point to external URLs. Compatible with links added using [Page Links To](https://wordpress.org/plugins/page-links-to/).

### 3.8.0 (2022.12.27)

* **[ADDED] Optimizations >> Enable Heartbeat Control**. Modify the interval of the WordPress heartbeat API or disable it on admin pages, post creation/edit screens and/or the frontend. This will help reduce CPU load on the server.

### 3.7.0 (2022.12.25)

* **[ADDED] Content Management >> Enable Revisions Control**. Prevent bloating the database by limiting the number of revisions to keep for some or all post types supporting revisions.

### 3.6.1 (2022.12.22)

* **[CHANGED] Custom Code >> Manage robots.txt**. Fixed an issue where code editor was not rendered properly upon clicking the Custom Code tab.

### 3.6.0 (2022.12.22)

* **[CHANGED] Added "Log In | Log Out" and "Custom Code" categories**. Recategorizes relevant features under these new categories.
* **[ADDED] Log In | Log Out >> Enable Last Login Column**. Log when users on the site last logged in and display the date and time in the users list table.

### 3.5.0 (2022.12.19)

* **[ADDED] Utilities >> Manage robots.txt**. Easily edit and validate your robots.txt content.

### 3.4.0 (2022.12.16)

* **[ADDED] Utilities >> Enable Log In/Out Menu**. Enable log in, log out and dynamic log in/out menu item for addition to any menu. Depending on the user's logged-in status, the menu item will show up, disappear or change.

### 3.3.0 (2022.12.12)

* **[ADDED] Utilities >> Insert &lt;head&gt;, &lt;body&gt; and &lt;footer&gt; Code**. Easily insert &lt;meta&gt;, &lt;link&gt;, &lt;script&gt; and &lt;style&gt; tags, Google Analytics, Tag Manager, AdSense, Ads Conversion and Optimize code, Facebook, TikTok and Twitter pixels, etc.
* **[CHANGED] Utilities >> Manage ads.txt and app-ads.txt** is now a single settings field.

### 3.2.0 (2022.12.12)

* **[ADDED] Utilities >> Manage ads.txt and app-ads.txt**: Easily edit and validate your ads.txt and app-ads.txt content. Please backup existing ads.txt and app-ads.txt content and remove those files before copying the original content into the ads.txt and app-ads.txt manager in the Utilities tab. If no ads.txt / app-ads.txt files exist in the root directory of your WordPress installation, you can immediately add content for both files.

### 3.1.0 (2022.12.11)

* **[ADDED] Content Management >> Enable Auto-Publishing of Posts with Missed Schedule**: Trigger publishing of scheduled posts of all types marked with "missed schedule", anytime the site is visited. Uses Transients API to reduce load to the DB on busy sites. Will only query the DB once every 15 minutes (at most).

### 3.0.6 (2022.12.08)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Enqueue jQuery UI widget.min.js for WP version less than 5.6.0. This ensures the feature works for those WP versions.

### 3.0.5 (2022.12.08)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Enqueue the minified version of jQuery UI scripts, as older versions of WP do not have the unminified version.

### 3.0.4 (2022.12.07)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Substantially lower priorities of actions that handles rendering of custom menu order, custom menu title and hiding of menu items. This is so that menu items added late by active plugins and theme are handled properly.

### 3.0.3 (2022.12.07)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Make sure menu item sortables reflect custom menu order saved in options, especially when this feature is re-enabled. Remove all HTMl tags and content inside them from menu item titles in the sortables.

### 3.0.2 (2022.12.07)

* **[CHANGED] Hide stubborn notices** appearing inside the admin page header (via CSS).

### 3.0.1 (2022.12.01)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Make sure newer menu items added by newly installed plugins or activated theme are showing up towards the end of the admin menu and the menu item sortables. Comments and updates counters are now hidden. Sortables for menu separators have been faded out to make actual menu items stand out more.
* **[CHANGED] Disable Components >> Disable Comments**: Ensure trackbacks metabox is also hidden on edit screens of post types where commenting is disabled.

### 3.0.0 (2022.11.30)

* **[ADDED] Disable Components >> Disable Feeds**: Disable all RSS, Atom and RDF feeds. This includes feeds for posts, categories, tags, comments, authors and search. Also removes traces of feed URLs from &lt;head&gt;.


### 2.9.0 (2022.11.30)

* **[ADDED] Disable Components >> Disable REST API**: Disable REST API access for non-authenticated users and remove URL traces from \<head\>, HTTP headers and WP RSD endpoint.

### 2.8.3 (2022.11.29)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Fix for missing Show All toggle in certain scenarios.

### 2.8.2 (2022.11.29)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Allow customizing menu item title, for menu items added by plugins or the active theme, i.e. not from WordPress core.

### 2.8.1 (2022.11.28)

* **[CHANGED] Admin Interface >> Admin Menu Organizer**: Fixed the issue when menu items are no longer shown upon saving. This happened when Admin Menu Organizer was enabled and directly saved without making any changes to the menu order first. 

### 2.8.0 (2022.11.28)

* **[ADDED] Disable Components >> Disable Gutenberg**: Disable the Gutenberg block editor for some or all applicable post types. Optionally disable frontend block styles / CSS files for the selected post types.

### 2.7.0 (2022.11.27)

* **[ADDED] Disable Components >> Disable Comments**: Disable comments for some or all public post types. When disabled, existing comments will also be hidden on the frontend.
* **[CHANGED] Security >> Limit Login Attempts**: Improved styling of empty datatable, i.e. when there is no failed login attempts logged.
* **[CHANGED] Settings tab position** is now saved to browser cookie, so it persists upon saving changes or revisiting it from elsewhere in wp-admin.
* **[CHANGED] Some code refactoring** to make logical separation and categorization of features clearer. CodeMirror and DataTables assets loading are also moved

### 2.6.0 (2022.11.19)

* **[ADDED] Content Management >> Enable SVG Upload**: Allow some or all user roles to upload SVG files, which will then be sanitized to keep things secure.

### 2.5.0 (2022.11.18)

* **[ADDED] Security >> Limit Login Attempts**: Prevent brute force attacks by limiting the number of failed login attempts allowed per IP address.
* **[CHANGED] Security >> Custom Login URL** feature has been made compatible with Limit Login Attempts feature.
* **[CHANGED] Security >> Change Login URL** now works with the interim login modal window, the one that pops up when user is logged out in the background.
* **[CHANGED] Security >> Change Login URL** adds another process to ensure user is redirected to the dashboard after successful login. It was redirecting to the /not_found/ (404) page in rare occasions as [reported by @vdrover](https://wordpress.org/support/topic/change-login-url-redirects-to-not-found/).
* **[CHANGED] Utilities >> Redirect After Login** will correctly override default login redirection by Change Login URL.
* **[CHANGED] Improve code comments throughout**.

### 2.4.0 (2022.11.10)

* **[ADDED] Utilities >> Enable Custom Frontend CSS**: Add custom CSS on all frontend pages for all user roles.

### 2.3.0 (2022.11.09)

* **[ADDED] Utilities >> Enable Custom Admin CSS**: Add custom CSS on all admin pages for all user roles.

### 2.2.0 (2022.11.09)

* **[ADDED] Security >> Disable XML-RPC**: Protect your site from brute force, DOS and DDOS attacks via XML-RPC. Also disables trackbacks and pingbacks.
* **[ADDED] Expand/collapse toggle** for feature settings that takes up longer vertical space, e.g. Admin Menu Customizer.
* **[CHANGED] Refactor code around plugin settings page**. Now uses separate classes for sections and fields registration, for sanitization of field values and for rendering the fields on the admin page.

### 2.1.0 (2022.11.08)

* **[ADDED] Security >> Obfuscate Author Slugs**: Obfuscate publicly exposed author page URLs that shows the user slugs / usernames, e.g. _sitename.com/author/username1/_ into _sitename.com/author/a6r5b8ytu9gp34bv/_, and output 404 errors for the original URLs. Also obfuscates in _/wp-json/wp/v2/users/_ REST API endpoint. Props to [pull request](https://github.com/qriouslad/admin-site-enhancements/pull/1) from [Wahyu Arief @wahyuief](https://github.com/wahyuief) and [functions](https://plugins.trac.wordpress.org/browser/smart-user-slug-hider/tags/4.0.2/inc/class-smart-user-slug-hider.php) from [Smart User Slug Hider
](https://wordpress.org/plugins/smart-user-slug-hider/).

### 2.0.0 (2022.11.06)

* **[ADDED] Admin Interface >> Admin Menu Organizer**: Customize the order of the admin menu and optionally hide some items.

### 1.9.0 (2022.11.03)

* **[ADDED] Admin Interface >> Hide or Modify Elements**: Easily simplify or customize various admin UI elements, starting with the admin bar.
* **[CHANGED] Content Management >> Enhance List Tables**: this combines previously separate features related to list tables for various post types.

### 1.8.0 (2022.11.03)

* **[ADDED] Admin Interface >> View Admin as Role**: View admin pages and the site (logged-in) as one of the non-administrator user roles.

### 1.7.0 (2022.10.31)

* **[ADDED] Utilities >> Redirect 404 to Homepage**: Perform 301 (permanent) redirect to the homepage for all 404 (not found) pages.

### 1.6.0 (2022.10.31)

* **[ADDED] Utilities >> Redirect After Logout**: Set custom redirect URL for all or some user roles after logout.

### 1.5.0 (2022.10.30)

* **[ADDED] Utilities >> Redirect After Login**: Set custom redirect URL for all or some user roles after login.

### 1.4.0 (2022.10.30)

* **[ADDED] Security >> Change Login URL**: allow for setting a custom login URL to improve site security.

### 1.3.0 (2022.10.29)

* **[ADDED] Admin Interface >> Hide Admin Bar**: Hide it on the front end for all or some user roles.

### 1.2.0 (2022.10.28)

* **[ADDED] Admin Interface >> Hide Admin Notices**: Clean up admin pages by moving notices into a separate panel easily accessible via the admin bar.

### 1.1.0 (2022.10.22)

* **[ADDED] Content Management >> Enable Media Replacement**: Enable easy replacement of any type of media file with a new one while retaining the existing media ID and file name.

### 1.0.0 (2022.10.17)

* Initial stable release. 