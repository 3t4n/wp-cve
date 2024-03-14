=== WP Post to PDF Enhanced ===

Contributors: LewisR, qlstudio
Donate Link: http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/
Tags: pdf, post, posts, post to pdf, tcpdf, printable, content, convert, stand-alone, stand alone, acrobat
Requires at least: 2.7
Tested up to: 4.2
Stable tag: 1.1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Post to PDF Enhanced renders posts & pages as downloadable PDFs for archiving and/or printing.

== Description ==

WP Post to PDF Enhanced is based on the original WP post to PDF. It renders posts & pages as downloadable PDFs for archiving and/or printing.
Configuration options are available for the presentation and placement of the PDF link/icon in the article, custom header image, included/excluded posts/pages, fonts for various sections (header, footer, article, etc.), caching of previously-rendered PDFs, and much more.
It is possible to limit access to PDFs to registered users or present the link/icon to all visitors.
WP Post to PDF Enhanced is completely self-contained, and does not rely on any third party to render PDFs; does not require any additional plugins, either.
For detailed documentation visit [the support page] http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/ .

== Installation ==

1. Upload to the "wp-post-to-pdf-enhanced" directory to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure plugin

== Frequently Asked Questions ==

= I've been using the original WP Post to PDF plugin. How do I migrate my settings to WP Post to PDF Enhanced? =

Presently, there is no automated way of migrating optioins from one plugin to the other.

If you have database access, before installing WP Post to PDF Enhanced, create a new row in the `wp_options` table. Assign a unique `option_id` value, set the `option_name` to `wpptopdfenh`, and copy the contents of the `wpptopdf option_value` field to the new `wpptopdfenh option_value` field. You may need to edit any path references referencing `/wp-post-to-pdf` to `/wp-post-to-pdf-enhanced`.

If you do not have database access, note all of the options you have set for WP Post to PDF before disabling/uninstalling it, so that you may re-enter them upon activating WP Post to PDF Enhanced.

Be sure to copy any custom images from the `/wp-post-to-pdf` paths to `/wp-post-to-pdf-enhanced` paths (e.g., `wp-content/uploads/wp-post-to-pdf-logo.png -> wp-content/uploads/wp-post-to-pdf-enhanced-logo.png`).

Finally, if you have used any manual placement tags for the PDF icon in your theme(s), you'll need to edit these manually (e.g., `<?php if (function_exists("wpptopdf_display_icon")) echo wpptopdfenh_display_icon();?>` needs to be edited to read `<?php if (function_exists("wpptopdfenh_display_icon")) echo wpptopdfenh_display_icon();?>`).

= Okay, I've tried WP Post to PDF Enhanced, but now I want to go back to WP Post to PDF. How do I do that? =

None of your previous option settings in your WordPress database have been altered or removed. If you have moved any files from the original locations (instead of copying them), you'll need to move them back, and as per the last item above, if you have used any manual placement tags in your theme(s), you'll need to revert those changes.

= I use the XYZ plugin, and I see that my content is not rendering as expected in the PDF. What's wrong? =

WP Post to PDF Enhanced is a wrapper for the TCPDF library, which does all the heavy lifting. TCPDF does an admirable job of converting HTML to PDF, but it is very particular about the quality of the HTML being handed to it. Likely, there is a missing HTML tag which may be acceptable to most browsers (i.e., does not generate an error, and renders fine on-screen), but which is not entirely according to spec. The first suggestion is to check your code in the raw HTML editor in WordPress (not the visual editor). Look for odd things such as paragraph tags in the middle of table cells (common issue), missing alignment tags, etc.

= My PDF is truncated/broken/missing pieces! What did you do?! =

See the previous entry. Missing graphics and such are common symptoms of non-standard HTML preceding the image, causing TCPDF to simply stop processing the input data.

= When I try to download my PDF, I get a 0-length file. What could be causing this? =

Other plugins can cause difficulty for WP Post to PDF Enhanced. This is particularly true for plugins which filter output to the browser. A known conflict is with NextGen Gallery. Try adding `add_filter( 'run_ngg_resource_manager', '__return_false' );` to your theme's functions.php as a workaround. The same technique may be useful for other plugins (though some detective work will be necessary to determine which function needs to be filtered).

= Non-English characters (Cyrillic, etc.) are shown as "?" in the PDF. What's wrong? =

The default fonts used are Helvetica, which is a core (built into most PDF viewers) font, but not a Unicode font. This avoids downloading the entire font package. However, if you find that your text is not rendering, try a DejaVu font first, before reporting this as a bug. You may set this in the options panel.

= Where do I go to report a problem? =

You may either use the WordPress Plugin page for WP Post to PDF Enhanced, or the official support page on my blog: http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/ .

== Screenshots ==

1. Main Options page
2. Include/Exclude Content Types, Posts, Pages; Caching
3. Icon/Link Presentation options (where the PDF icon should appear)
4. General options (accommodate shortcodes from other plugins, what to include in the PDF header area, etc.)
5. Header options (show on all pages, set logo, set header margin)
6. Body options (featured image inclusion, custom css, custom bullet image, image scaling, etc.)
7. Footer options (custom footer, size and positioning, border, alignment, footer margin)
8. Typography (header, footer, body fonts and sizes)
9. Page Size & Units
10. Sample WordPress page; note PDF icon (top left positioning selected)
11. Sample PDF (minimal header options; default fonts and sizes)

== Changelog ==

= 1.1.1 =

* Changed perms to 0777 when creating directories, as clearing cache led to unwritable cache space.
* Corrected appearance of "Author :" when option set to None (the heading should be suppressed).

= 1.1.0 =

* Addressed possible XSS vulnerability as described here: https://blog.sucuri.net/2015/04/security-advisory-xss-vulnerability-affecting-multiple-wordpress-plugins.html.
* Updated to TCPDF 6.2.6, and all included fonts.
* Correct title formatting when option set to apply other plugin output (thanks to doublesharp for the suggestion and the code snippet!).
* Changed options when creating cache directories to set perms to 0755 (thanks to Qobo Ltd for the suggestion and the code snippet!).
* Reorganized admin panels to improve flow and accommodate additional settings.
* Added paper size option based upon sizes available in TCPDF.
* Added custom footer options.
* Added custom bullet image options (still testing image types and consistency).
* Added margin options (previously, these were set in TCPDF options file).
* Improvements (I hope) to image alignment (though still needs work).
* Initial code to set version in db for use in smoother upgrades.
* Initial work to preserve existing option settings and add defaults for newly added ones. (This is still under development and not yet functional.)

= 1.0.5 =

* Fixed issue where we were attempting to save/update phantom post/page when in admin area and save button was pressed. This triggered a "Warning: Creating default object from empty value in /home/{site root}/public_html/wp-content/plugins/wp-post-to-pdf-enhanced/wp-post-to-pdf-enhanced.php on line ###" which could only happen if we were trying to update a cached pdf.
* Applied WP-phptidy cleanup to php (see http://www.2rosenthals.net/wordpress/help/general-help/wp-phptidy-a-tool-to-clean-up-wordpress-plugin-and-theme-code for details).

= 1.0.4 =

* Fixed issue with deprecated get_bloginfo('siteurl') when inserting the blog url in the header.

= 1.0.3 =

* Fixed issue with has_shortcode() function introduced with WP 3.6. We are once again compatible with earlier versions of WP.

= 1.0.2 =

* Various minor variable isset and constant corrections to fix PHP Notices in error log.

= 1.0.1 =

* Implemented basic shortcode ([wpptopdfenh]) to allow placement of PDF icon on demand. Shortcode observes display options for public/non-public and single page only, so excluding the main icon from the page will still allow shortcode to display the icon.
* Implemented shortcode ([wpptopdfenh_break]) to set manual page breaks within PDF.
* Added note to FAQ concerning non-Unicode fonts and non-English characters.
* Added ability to add global css. This is stored in the db.
* Added option to specify the logo image padding/size factor (default is 14).
* Resized input fields and textareas in admin panel.
* Added debug code to keep handy (turned off by default; not settable in admin).
* Partial Fix: Modified some code which was wrapping div tags around all images, forcing text centering (and thus, images within those areas). Images still not respecting alignment, but workaround is to place image in text area and set text alignment (issue #44).
* Fix: When include/exclude dialogs are both set to include, and no entries present, radio button for post/page include/exclude is deselected (issue #43).
* Fix: Corrected Arial -> Helvetica core font mapping (use DejaVu for Unicode); added several missing fonts to admin dropdown (issue #48).
* Fix: When Process Shortcodes is not selected, strip shortcodes from content, so as not to render things like "[shortcode]".
* Fix: Cleaned up some code to resolve undefined index and variable notices, as well as constant WPPT0PDFENH_PATH already defined notice (issue #47).
* Fix: Added PHP version check before sending non-existent html decode constant (ENT_HTML401) to PHP < 5.4, reducing log noise (issue #46).
* Fix: Rewrote code to generate icon. Now, instead of grabbing the url from the permalink, we grab the entire thing, in case there are additional query strings attached (issue #45).
* To-do: Allow different icon for shortcode.
* To-do: Allow arguments for shortcodes (fonts, external css, other options).
* To-do: Implement filter to strip shortcodes (when Process Shortcodes is deselected) /except/ for our shortcodes.
* To-do: Fix (once and for all) image alignment issues.
* To-do: Add option to change formatting of tags & categories from links to plain text.
* To-do: Add option to specify a custom footer.
* To-do: Add option to include custom fields in header.

= 1.0.0 =

* Initial public release; functionally equivalent to WP Post to PDF unofficial version 2.4.0.
* Includes TCPDF 6.0.043, and all included fonts.
* To-do: Allow limiting category list to just the first category; allow for relocating this to footer, left, right, center).
* To-do: Allow limiting tag list to just the first tag; allow for relocating this to footer, left, right, center).
* To-do: Fix HTML prior to rendering when extraneous tags cause annoying truncation of certain PDFs (tables, for example).
* To-do: Fix image positioning to better respect the HTML layout.
* To-do: Add option to move date and/or category to the footer, with left, right, or center alignment.
* To-do: Add option to specify the separator in a list of categories & list of tags.
* To-do: Add option to remove paragraph break between author, categories, tags, date (to format better and waste less vertical space).
* To-do: Allow exception to site-wide image scaling factor via shortcode (and add other shortcodes as overrides for various options set in the admin panel).
* To-do: Allow for custom css definitions to apply to PDF (note that this is highly dependent upon css support in TCPDF class; this to-do list item refers to the ability to enter such css in the admin panel, and not to any specific css support).
* To-do: Add shortcode to set manual page breaks when rendering PDF.

== Upgrade Notice ==

= 1.1.0 =

Addressed possible XSS vulnerability in conjunction with WP upgrade to 4.2 to address the same issue 9as well as other plugins.

= 1.0.5 =

Resolved nasty issue seen by some sites where saving/updating in admin area caused error message to be thrown.

= 1.0.0 =

Initial public release.

== Upgrading from versions before 1.1.0 ==

1. Make a note of all of your current settings, as they may not be preserved.
2. If possible, export the wpptopdfenh options from the wp_options table in the database. This is the best way to preserve your existing settings.
3. Deactivate the existing plugin.
4. Delete the existing plugin.
5. Install the new version.
6. Activate the plugin.
7. Go to Settings | WP Post to PDF Enhanced and verify/re-enter your settings from the previous version.
