# Changelog

## 3.8.1 _(2021-04-14)_
* Fix: Update plugin framework to 061 to fix a bug preventing settings from getting saved

## 3.8 _(2021-04-10)_

### Highlights:

This minor release updates updates the plugin framework, restructures the unit test file structure, and notes compatibility through WP 5.7+.

### Details:

* Change: Update plugin framework to 060
    * 060:
    * Rename class from `c2c_{PluginName}_Plugin_051` to `c2c_Plugin_060`
    * Move string translation handling into inheriting class making the plugin framework code plugin-agnostic
        * Add abstract function `get_c2c_string()` as a getter for translated strings
        * Replace all existing string usage with calls to `get_c2c_string()`
    * Handle WordPress's deprecation of the use of the term "whitelist"
        * Change: Rename `whitelist_options()` to `allowed_options()`
        * Change: Use `add_allowed_options()` instead of deprecated `add_option_whitelist()` for WP 5.5+
        * Change: Hook `allowed_options` filter instead of deprecated `whitelist_options` for WP 5.5+
    * New: Add initial unit tests (currently just covering `is_wp_version_cmp()` and `get_c2c_string()`)
    * Add `is_wp_version_cmp()` as a utility to compare current WP version against a given WP version
    * Refactor `contextual_help()` to be easier to read, and correct function docblocks
    * Don't translate urlencoded donation email body text
    * Add inline comments for translators to clarify purpose of placeholders
    * Change PHP package name (make it singular)
    * Tweak inline function description
    * Note compatibility through WP 5.7+
    * Update copyright date (2021)
    * 051:
    * Allow setting integer input value to include commas
    * Use `number_format_i18n()` to format integer value within input field
    * Update link to coffee2code.com to be HTTPS
    * Update `readme_url()` to refer to plugin's readme.txt on plugins.svn.wordpress.org
    * Remove defunct line of code
* Change: Move translation of all parent class strings into main plugin file
* Change: Escape markup class attributes before output (for hardening)
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

## 3.7 _(2020-06-30)_

### Highlights:

This minor release updates its plugin framework, adds a TODO.md file, updates a few URLs to be HTTPS, expands unit testing, updates compatibility to be WP 4.9 through 5.4+, and minor behind-the-scenes tweaks.

### Details:

* New: Add HTML5 compliance by omitting `type` attribute for `style` tag when the theme supports 'html5'
* Change: Update plugin framework to 050
    * Allow a hash entry to literally have '0' as a value without being entirely omitted when saved
    * Output donation markup using `printf()` rather than using string concatenation
    * Update copyright date (2020)
    * Note compatibility through WP 5.4+
    * Drop compatibility with version of WP older than 4.9
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add items to it)
* Change: Note compatibility through WP 5.4+
* Change: Drop compatibility for version of WP older than 4.9
* Change: Update links to coffee2code.com to be HTTPS
* Unit tests:
    * New: Add tests for `add_css()`
    * New: Add test for setting name
    * Change: Store plugin instance in test object to simplify referencing it
    * Change: Update test for default hooks
    * Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests (and delete commented-out code)

## 3.6.1 _(2020-01-03)_
* Change: Unit tests: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)

## 3.6 _(2019-04-22)_

### Highlights:

This release is a minor update that verifies compatibility through WordPress 5.1+, drops compatibility with versions of WordPress older than 4.7, and makes minor behind-the-scenes improvements.

### Details:

* Change: Initialize plugin on `plugins_loaded` action instead of on load
* Change: Update plugin framework to 049
    * 049:
    * Correct last arg in call to `add_settings_field()` to be an array
    * Wrap help text for settings in `label` instead of `p`
    * Only use `label` for help text for checkboxes, otherwise use `p`
    * Ensure a `textarea` displays as a block to prevent orphaning of subsequent help text
    * Note compatibility through WP 5.1+
    * Update copyright date (2019)
    * 048:
    * When resetting options, delete the option rather than setting it with default values
    * Prevent double "Settings reset" admin notice upon settings reset
    * 047:
    * Don't save default setting values to database on install
    * Change "Cheatin', huh?" error messages to "Something went wrong.", consistent with WP core
    * Note compatibility through WP 4.9+
    * Drop compatibility with version of WP older than 4.7
    * 046:
    * Fix `reset_options()` to reference instance variable `$options`.
    * Note compatibility through WP 4.7+.
    * Update copyright date (2017)
    * 045:
    * Ensure `reset_options()` resets values saved in the database.
* New: Add README.md file
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add inline documentation for hook
* New: Add GitHub link to readme
* Unit tests:
    * New: Add unit test for defaults for settings
    * Change: Improve tests for settings handling
    * Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
    * Change: Enable more error output for unit tests
* Change: Store setting name in constant
* Change: Downplay modern-day effectiveness of the techniques and improve overall explanations
* Change: Improve documentation for hook within readme.txt
* Change: Note compatibility through WP 5.1+
* Change: Drop compatibility with version of WP older than 4.7
* Change: Convert last remaing instances of "e-mail" to "email"
* Change: Rename readme.txt section from 'Filters' to 'Hooks' and provide a better section intro
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Update installation instruction to prefer built-in installer over .zip file
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

## 3.5.1 _(2016-06-13)_
* Change: Update plugin framework to 044:
    * 044
    * Add `reset_caches()` to clear caches and memoized data. Use it in `reset_options()` and `verify_config()`.
    * Add `verify_options()` with logic extracted from `verify_config()` for initializing default option attributes.
    * Add  `add_option()` to add a new option to the plugin's configuration.
    * Add filter `sanitized_option_names` to allow modifying the list of whitelisted option names.
    * Change: Refactor `get_option_names()`.
    * 043
    * Disregard invalid lines supplied as part of hash option value.
    * 042
    * Update `disable_update_check()` to check for HTTP and HTTPS for plugin update check API URL.
    * Translate "Donate" in footer message.
    * Note compatibility through WP 4.5.
* Change: Use "email" instead of "e-mail" in plugin's name, description, and documentation.
* Bugfix: Add appropriate spacing so v3.5's changelog entry gets properly parsed. Props szepeviktor.

## 3.5 _(2016-04-04)_

### Highlights:

This release adds support for language packs and has some minor behind-the-scenes changes.

### Details:

* Change: Update plugin framework to 041:
    * Change class name to `c2c_ObfuscateEmail_Plugin_041` to be plugin-specific.
    * Set textdomain using a string instead of a variable.
    * Don't load textdomain from file.
    * Change admin page header from 'h2' to 'h1' tag.
    * Add `c2c_plugin_version()`.
    * Formatting improvements to inline docs.
* Change: Add support for language packs:
    * Set textdomain using a string instead of a variable.
    * Remove .pot file and /lang subdirectory.
    * Remove 'Domain Path' from plugin header.
* New: Add LICENSE file.
* New: Add empty index.php to prevent files from being listed if web server has enabled directory listings.
* Change: Minor code reformatting.
* Change: Note compatibility through WP 4.5+.
* Change: Dropped compatibility with version of WP older than 4.1.
* Change: Update copyright date (2016).

## 3.4 _(2015-04-22)_
* Enhancement: Prevent email obfuscation from occurring in the admin area
* Update: Add more unit tests
* Update: Note compatibility through WP 4.2+
* Update: Minor code formatting changes (spacing)

## 3.3 _(2015-03-02)_
* Fix when using multi-character AT or DOT replacements in conjunction with text direction technique when not encoding everything
* Use full path when requiring plugin framework file
* Add unit tests
* Update plugin framework to 039
* Explicitly declare `activation()` and `uninstall()` static
* Explicitly declare all function public
* Reformat plugin header
* Minor code reformatting (spacing, bracing)
* Change documentation links to wp.org to be https
* Minor documentation improvements and spacing changes throughout
* Note compatibility through WP 4.1+
* Drop compatibility with version of WP older than 3.6
* Update copyright date (2015)
* Add plugin icon
* Change donate link
* Update screenshot
* Regenerate .pot

## 3.2
* Disable text direction technique by default (doesn't change existing setting value)
* Update plugin framework to 036
* Better singleton implementation:
    * Add `instance()` static method for returning/creating singleton instance
    * Made static variable 'instance' private
    * Made constructor protected
    * Made class final
    * Additional related changes in plugin framework (protected constructor, erroring `__clone()` and `__wakeup()`)
* Add checks to prevent execution of code if file is directly accessed
* Regenerate .pot
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Discontinue use of PHP4-style constructor
* Discontinue use of explicit pass-by-reference for objects
* Remove ending PHP close tag
* Minor documentation improvements
* Minor code reformatting (spacing)
* Note compatibility through WP 3.6+
* Update copyright date (2013)
* Move screenshots into repo's assets directory
* Add banner

## 3.1
* Fix bug where display:none technique was ignored if text direction technique was not active
* Fix bug where display:none and text direction techniques were erroneously applied to email addresses in tag attributes when mid-string
* Update plugin framework to 034
* Note compatibility through WP 3.3+
* Drop compatibility with versions of WP older than 3.1
* Change parent constructor invocation
* Create 'lang' subdirectory and move .pot file into it
* Regenerate .pot
* Add 'Domain Path' directive to top of main plugin file
* Add link to plugin directory page to readme.txt
* Tweak installation instructions in readme.txt
* Changed description
* Documentation changes
* Update screenshots for WP 3.3
* Update copyright date (2012)

## 3.0
* Significant rewrite of entire plugin
* Use plugin framework v026, which among other things adds support for:
    * Reset of options to default values
    * Better sanitization of input values
    * Offload core/basic functionality to generic plugin framework
    * Additional hooks for various stages/places of plugin operation
    * Easier localization support
* Apply obfuscation to additional filters: link_description, link_notes, bloginfo, nav_menu_description, term_description, the_title, list_cats, widget_text
* Deprecate function `c2c_email_obfuscator()`
* Change `c2c_obfuscate_email()`
    * Second argument is now an array of arguments to override plugin settings
    * All previously existing arguments (except first) have been deprecated
* Add filter `c2c_obfuscate_email_filters`
* Full localization support
* Fix to properly register activation and uninstall hooks
* Save a static version of itself in class variable $instance
* Rename class from `ObfuscateEmail` to `c2c_ObfuscateEmail`
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.1+ and WP 3.2+
* Drop compatibility with versions of WP older than 3.0
* Explicitly declare all class functions public
* Add PHPDoc documentation
* Add package info to top of plugin file
* Add 'Text Domain' header tag
* Add Template Tags, Filters, Changelog, and Upgrade Notice sections to readme.txt
* Add screenshot
* Update copyright date (2011)
* Add .pot file

## 2.0
* Overhauled and added a bunch of new code
* Encapsulated a majority of functionality in a class
* Added admin options page for the plugin, under Options -> Obfuscate E-mail (or in WP 2.5: Settings &rarr; Obfuscate E-mail) so that default e-mail obfuscation can be easily configured via WP admin
* Packaged plugin into its own directory, now including a readme.txt and screenshots
* Maintained (though renamed) existing primary functions (which have remained non-classed) for others to use directly
* Added options to define replacements for `@` and `.` characters in e-mails for partial obfuscation
* Filter 'get_the_excerpt' instead of 'the_excerpt'
* Additionally filter `the_author_email` and `get_comment_author_email`
* trim() before returning instead of `substr()`
* Tweaked description, installation instructions, and examples
* Updated copyright date and version to 2.0
* Added readme.txt and screenshot image to distribution zip
* Tested compatibility with WP 2.3+ and 2.5

## 0.9
* Initial release
