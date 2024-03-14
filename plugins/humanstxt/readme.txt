=== Humans TXT ===
Contributors: tillkruess
Donate link: https://www.paypal.me/tillkruss
Tags: Humans TXT, HumansTXT, humans.txt, human, humans, author, authors, contributor, contributors, credit, credits, robot, robots, robots.txt
Requires at least: 3.0
Tested up to: 5.2
Stable tag: 1.3.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Credit the people behind your website in your humans.txt file. Easy to edit, directly within WordPress.


== Description ==

Credit the people behind your website in your **humans.txt** file. Easy to edit, directly within WordPress.

* Use **variables** like a _last-updated_ date, active plugins and [many others...](http://wordpress.org/extend/plugins/humanstxt/other_notes/#Variables)
* Use the `[humanstxt]` shortcode to display your _humans.txt_ on your site
* Add an author link tag to your site's `<head>` tag
* Allow non-admins to edit the _humans.txt_
* Customize everything with custom [filters, actions and pluggable functions](http://wordpress.org/extend/plugins/humanstxt/other_notes/#Plugin-Actions-and-Filters)
* Restore previously saved revisions of your _humans.txt_

More information on the Humans TXT can be found on the [official Humans TXT website](http://humanstxt.org/).

== Installation ==

For detailed installation instructions, please read the [standard installation procedure for WordPress plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

1. Upload the `/humanstxt/` directory and its contents to `/wp-content/plugins/`.
2. Login to your WordPress installation and activate the plugin through the _Plugins_ menu.
3. Activate the plugin and edit your humans.txt file in the _Settings_ menu under _Humans TXT_.

**Please note:** This plugin does not modify or create a physical `humans.txt` file on your server, it serves it dynamically. If your site root contains a physical `humans.txt` file, this file will be shown to the visitor and the one from this plugin will be ignored. To use this plugin, please delete your physical `humans.txt` (but don't forget to migrate/backup it's contents).


== Frequently Asked Questions ==

= Error: The site root already contains a physical humans.txt file. =

If your site root contains a physical `humans.txt` file, this physical file will be shown to the visitor and the one from this plugin will be ignored. To use this plugin, please delete the physical `humans.txt` file on your server (but don't forget to migrate/backup it's contents).

= Error: Please update your permalink structure to something other than the default. =

The plugin will only work, if WordPress is using "Pretty Permalinks". You can activate them in WordPress in the _Settings_ menu under _Permalinks_. Read more about [using permalinks](http://codex.wordpress.org/Using_Permalinks).

= Error: The content has been imported, but the original file could not be renamed. =

The content of the physical `humans.txt` file on your server has been imported, however the original file could not be renamed/moved. To use this plugin, please delete the physical `humans.txt` file on your server (but don't forget to migrate/backup it's contents).

= Error: Import failed. =

The physical `humans.txt` file on your server could not be imported and renamed. To use this plugin, please delete the physical `humans.txt` file on your server (but don't forget to migrate/backup it's contents).

= Why isn't the humans.txt file on my server modified? =

This plugin does not modify or create a physical `humans.txt` file on your server, it serves it dynamically. If your site root contains a physical `humans.txt` file, this file will be shown to the visitor and the one from this plugin will be ignored. To use this plugin, please delete the physical `humans.txt` file on your server (but don't forget to migrate/backup it's contents).

= Where is the humans.txt file located? =

Theoretically in the root of your site, **however** this plugin doesn't create a physical `humans.txt` file on your server, it serves it on the fly.


== Screenshots ==

1. Plugin options page.
2. Plugin revisions page.
3. Shortcode result using `pre` attribute. (Theme: Twenty Eleven)
4. Default shortcode result. (Theme: Twenty Eleven)


== Changelog ==

= 1.3.1 =

* Fix deprecated warning

= 1.3.0 =

* Fix deprecated warning

= 1.2.9 =

* Removed bundled translations (use [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/humanstxt))

= 1.2.8 =

* Removed “suggest variable” button
* Use `<h1>` for admin UI headlines

= 1.2.7 =

* Fixed issue that caused translated variables not to work
* Updated Turkish and Persian translation
* Removed MP6 related styles

= 1.2.6 =

* WordPress 3.8 compatibility adjustments

= 1.2.5 =

* Added MP6 compatibility

= 1.2.4 =

* Fixed deprecated function calls

= 1.2.3 =

* Removed limitation to only work on non-sub-directory installations
* Added Chinese translation

= 1.2.2 =

* WordPress 3.5 compatibility adjustments
* Added Swedish translation

= 1.2.1 =

* WordPress 3.4 compatibility adjustments
* Updated Spanish, Persian, Danish translation
* Added Portuguese translation

= 1.2 =

* Added preview button for humans.txt editor
* Added simple import functionality if a physical humans.txt file exists
* Added Croatian translation
* Variables are now grouped into "WordPress", "Server" and "Themes & Plugins"
* Revision can now be compared to each other
* Improved contextual help menu
* Several options page improvements
* Several internal minor code improvements

= 1.1.3 =

* Prevented PHP notice during plugin activation
* Prevented browser caching issue with CSS/JavaScript file
* Renamed `humanstxt_shortcode()` to `_humanstxt_shortcode()` to prevent plugin conflicts

= 1.1.2 =

* Added Romanian translation
* Removed all PHP shorthand tags to increase system compatibility

= 1.1.1 =

* Added Arabic, Japanese, Korean, Persian and Spanish translations
* Made admin interface compatible with right-to-left (RTL) languages

= 1.1 =

* Added revisions functionality for the `humans.txt`
* Added translation for several languages
* Added variable for active authors and their contact details
* Plugin is now compatible with WordPress 3.0 and higher
* Replacing the translated and the english variable name in the `humans.txt`
* Moved variable callback functions into `callbacks.php`
* Removed Subscriber from the roles list
* `HUMANSTXT_IS_ROOTINSTALL` constant overrides the result of `humanstxt_is_rootinstall()`
* `HUMANSTXT_METABOX` constant can be used to hide the plugin rating box
* `humanstxt_shortcode()` is now pluggable (primarily to prevent plugin conflicts)
* Several other code and interface improvements...

= 1.0.5 =

* Added new variables for the site/blog title, description and encoding
* Added option to allow non-admins to edit the humans.txt file
* Added shortcode usage to options page
* Improved variable preview tooltip and options page styling
* Improved loading of plugin options
* Improved editor auto-grow in Internet Explorer

= 1.0.4 =

* Added `[humanstxt]` shortcode with several attributes
* Added new variables for the number of published posts and pages
* Minor changes to admin interface text, layout and scripts
* Added few shortcut functions like: `humanstxt()` and `humanstxt_authortag()`
* Added filter for result of `$wp-language$` variable callback function

= 1.0.3 =

* Adjusted admin UI metabox styling for WP 3.2
* Improved warning messages and notices

= 1.0.2 =

* Improved text editor functionality
* `$wp-language$` supports now WPML/SitePress, qTranslate and xili-language
* Fixed unwanted injection of author tag

= 1.0.1 =

* Added warning message if WordPress version is older than 3.1
* Prevented potential issue with `$wp-theme-author$` variable
* Prevented potential issue with preview of variable-callback result
* Improved textarea auto-grow functionality
* Improved Internet Explorer 6+7 support
* Added filter for `humanstxt_content()` result
* Revised plugin warning messages

= 1.0 =

* Initial release


== Upgrade Notice ==

= 1.2.7 =

This version fixes an issue that caused translated variables not to work.

= 1.2.6 =

This version adds WordPress 3.8 compatibility.

= 1.2.5 =

This version introduces MP6 compatibility.

= 1.2.4 =

This version fixes several deprecated function calls.

= 1.2.3 =

Removed limitation to only work on non-sub-directory installations.

= 1.2.2 =

This version ensures WordPress 3.5 compatibility.

= 1.2.1 =

Improved WordPress 3.4 compatibility and added/updated several translations.

= 1.2 =

This version introduces several new features and improvements.

= 1.1.3 =

PHP error, browser caching and plugin conflict preventions.

= 1.1.2 =

This version resolves some compatibility issues with IIS and nginx.

= 1.1.1 =

This version ensures right-to-left language support and contains several new translations.

= 1.1 =

This version introduces revisions support, several translations and other major improvements.

= 1.0.5 =

This version introduces user-role support, new variables and other minor improvements.

= 1.0.4 =

This version introduces a shortcode, new variables and minor interface improvements.

= 1.0.3 =

This version ensures WordPress 3.2 compatibility and contains minor fixes and improvements.

= 1.0.2 =

This version contains minor fixes and improvements.

= 1.0.1 =

This version contains several fixes and improvements.


== Variables ==

* `$wp-title$` - Name (title) of site/blog
* `$wp-tagline$` - Tagline (description) of site/blog
* `$wp-posts$` - Number of published posts
* `$wp-pages$` - Number of published pages
* `$wp-lastupdate$` - Date of last modified post or page
* `$wp-authors$` - Active authors and their contact details
* `$wp-language$` - WordPress language(s)
* `$wp-plugins$` - Activated WordPress plugins
* `$wp-charset$` - Encoding used for pages and feeds
* `$wp-version$` - Installed WordPress version
* `$php-version$` - Running PHP parser version
* `$wp-theme$` - Summary of the active WordPress theme
* `$wp-theme-name$` - Name of the active theme
* `$wp-theme-version$` - Version number of the active theme
* `$wp-theme-author$` - Author name of the active theme
* `$wp-theme-author-link$` - Author link of the active theme


== Shortcode Usage ==

The default shortcode `[humanstxt]` will display the contents of the virtual humans.txt file. URLs, email addresses and Twitter account names are converted into clickable links. Plain email addresses are encoded for spam protection. The output will be wrapped with a `<p>` tag and can be styled via the `humanstxt` CSS class.

You can turn off the "clickable links" functionality: `[humanstxt clickable="0"]`

You can also toggle the clickable links individually: `[humanstxt urls="1" emails="0" twitter="1"]`

To display the humans.txt as preformatted text, use the `pre` attribute: `<pre>[humanstxt pre="1"]</pre>`

To display the untouched humans.txt, use the `plain` attribute: `[humanstxt plain="1"]`

You can omit the wrapping with the `<p>` tag: `[humanstxt wrap="0"]`

You can set a CSS id for the wrapping `<p>` tag: `[humanstxt id="my-humans-txt"]`

You can turn off the encoding of email addresses and common text entities: `[humanstxt filter="0"]`


== Useful Functions ==

**humanstxt()**
Echos the content of the virtual humans.txt file. Use `get_humanstxt()` to get the contents as a _string_.

**is_humans()**
Determines if the current request is for the virtual humans.txt file.


== Pluggable Functions ==

All callback functions of the default variables can be overridden. The callback functions are located in [humanstxt/callbacks.php](http://plugins.trac.wordpress.org/browser/humanstxt/trunk/callbacks.php).


== Plugin Constants ==

**HUMANSTXT_METABOX**
Define as `false` to disable the "rate this plugin" box on the options page.


== Plugin Actions and Filters ==

= Actions =

**do_humans**
Runs when the current request is for the *humans.txt* file, right after the `template_redirect` action.

**do_humanstxt**
Runs right before the *humans.txt* is printed to the screen.

= Filters =

**humans_txt**
Applied to the final content of the virtual humans.txt file.

**humans_authortag**
Applied to the author link tag.

**humanstxt_content**
Applied to the humans.txt content. Applied prior to the `humans_txt` filter.

**humanstxt_variables**
Applied to the array of content-variables. See `humanstxt_variables()` for details.

**humanstxt_max_revisions**
Applied to the maximum number of stored revisions. If set to `0`, revisions will be disabled. Default is `50`.

**humanstxt_shortcode_output**
Applied to the final `[humanstxt]` shortcode output.

**humanstxt_shortcode_content**
Applied to the un-wrapped shortcode output.

**humanstxt_shortcode_headline_replacement**
Applied to replacement string for matched standard headlines: `/* Title */`. See `humanstxt_shortcode()` for details.

**humanstxt_shortcode_twitter_replacement**
Applied to replacement string for matched twitter account names. See `humanstxt_shortcode()` for details.

**humanstxt_separator**
Applied to the global text separator. Default is a comma followed by a space.

**humanstxt_plugins_separator**
Use to override the global text separator (see `humanstxt_separator` filter) for the list of active WordPress plugins.

**humanstxt_languages_separator**
Use to override the global text separator (see `humanstxt_separator` filter), for the current WordPress language(s).

**humanstxt_postcount**
Applied to the number of published posts: `$wp-posts$`.

**humanstxt_pagecount**
Applied to the number of published pages: `$wp-pages$`.

**humanstxt_wptheme**
Applied to the summary of the active WordPress theme: `$wp-theme$`.

**humanstxt_plugins**
Applied to the list of active WordPress plugins: `$wp-plugins$`.

**humanstxt_languages**
Applied to current WordPress language(s): `$wp-language$`.

**humanstxt_lastupdate**
Applied to returned date of the `$wp-lastupdate$` variable.

**humanstxt_lastupdate_format**
Applied to the used date-format of the `$wp-lastupdate$` variable. Default is `Y/m/d`. Read more about [date and time formatting](http://codex.wordpress.org/Formatting_Date_and_Time).

**humanstxt_authors**
Applied to the list of active authors: `$wp-authors$`.

**humanstxt_authors_format**
Applied to the format used for the author list `$wp-authors$` variable. Please see `humanstxt_callback_wpauthors()` in [humanstxt/callbacks.php](http://plugins.trac.wordpress.org/browser/humanstxt/trunk/callbacks.php) for details.
