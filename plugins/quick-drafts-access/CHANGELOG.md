# Changelog

## 2.3.1 _(2023-04-29)_
* Change: Note compatibility through WP 6.3+
* Change: Update copyright date (2023)
* Fix: Fix typo for link to DEVELOPER-DOCS.md in README.md
* Fix: Fix typo in readme.txt
* New: Add a possible TODO item

## 2.3 _(2021-09-22)_
* New: Add DEVELOPER-DOCS.md and move hooks documentation into it
* Change: Only support public post types, but also exclude 'attachment'
* Change: Improve and tweak developer documentation and code examples
* Change: Note compatibility through WP 5.8+
* Unit tests:
    * New: Add `setUp()`, namely to actually register post types
    * Change: Test support for actual post types
    * Change: Change `c2c_quick_drafts_access_post_types()` to actual use the post types sent to it
    * Change: Restructure unit test directories
        * Change: Move `bin/` into `tests/`
        * Change: Move `tests/` into `tests/phpunit/`
        * Change: Move unit test file into `tests/phpunit/tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0
* New: Add a possible TODO item

## 2.2.4 _(2021-03-27)_
* Fix: Fix plugin name defined in README.md
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)

## 2.2.3 _(2020-07-12)_

### Highlights:

This minor release fixes the post type support check to prevent display of dropdown for unsupported post types, adds a TODO.md file, updates a few URLs to be HTTPS, tweaks formatting of output markup, and notes compatibility through WP 5.4+.

### Details:

* Fix: Properly check post type support to prevent display of dropdown for unsupported post types
* New: Add TODO.md and move existing TODO list from top of main plugin file into it (and add items to it)
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Change: Remove extraneous inline space in output of 'option' tag
* Change: Remove extraneous space after output of 'select' tag
* Change: Remove extraneous inline spaces in code
* Unit tests:
    * New: Add tests for `filter_drafts_by_author()`
    * Change: Remove unnecessary unregistering of hooks and thusly `tearDown()`
    * Change: More specificly check hook priorities rather than existence when seeing if a hook has been registered
    * Change: Remove duplicate test `test_hooks_action_plugins_loaded()`
    * Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests

## 2.2.2 _(2019-12-15)_
* New: Unit tests: Add test to verify plugin hooks `plugins_loaded` action to initialize itself
* Change: Note compatibility through WP 5.3+
* Change: Update copyright date (2020)

## 2.2.1 _(2019-06-24)_
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * Change: Tweak unit test function names
    * Fix: Update `test_get_post_types()` to account for 'wp_block' post type
* Change: Note compatibility through WP 5.2+
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Split paragraph in README.md's "Support" section into two
* New: Add screenshot for draft author filter dropdown

## 2.2 _(2019-02-25)_
* New: Add dropdown on draft listing of posts to filter which author's drafts to list
* New: Extract functionality for getting filtered list of post types into `get_post_types()`
* New: Add a few more unit tests
* New: Add inline documentation for hooks
* Change: Initialize plugin on 'plugins_loaded' action instead of on load
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS
* Change: Remove 'Domain Path' header setting

## 2.1.1 _(2018-05-08)_
* Change: Cast result of various filters as boolean values
* New: Add README.md
* Change: Add GitHub link to readme
* Change: Unit tests: Minor whitespace tweaks to bootstrap
* Change: Modify formatting of hook names in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Note compatibility through WP 4.9+
* Change: Update copyright date (2018)

## 2.1 _(2017-01-24)_
* Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable.
* Change: Enable more error output for unit tests.
* Change: Note compatibility through WP 4.7+.
* Change: Remove support for WordPress older than 4.6 (should still work for earlier versions going back to WP 3.1)
* Change: Minor readme.txt improvements.
* Change: Update copyright date (2017).

## 2.0.2 _(2015-12-17)_
* Change: Add support for language packs:
    * Don't load textdomain from file
    * Remove .pot file and /lang subdirectory
* Change: Note compatibility through WP 4.4+.
* Change: Explicitly declare methods in unit tests as public.
* Change: Update copyright date (2016).
* Add: Create empty index.php to prevent files from being listed if web server has enabled directory listings.

## 2.0.1 _(2015-09-04)_
* Hardening: Escape the URLs used for the menu links.
* Bugfix: Actually load the textdomain for translations.
* Change: Note compatibility through WP 4.3+.

## 2.0 _(2015-02-23)_
* Change 'Drafts' menu link text to 'All Drafts'
* Add 'My Drafts' menu link that links directly to current user's drafts
* Add filter `c2c_quick_drafts_access_show_all_drafts_menu_link`
* Add filter `c2c_quick_drafts_access_show_my_drafts_menu_link`
* Add extra arg to `c2c_quick_drafts_access_show_if_empty` filter with value of 'all' or 'my' to allow fine-grained control
* Build query args via `add_query_args()` rather than as a string
* Skip handling a post type if it doesn't look like a post type object
* Cast result of `c2c_quick_drafts_access_post_types` filter as array
* Remove `is_admin()` check that prevented class use outside of admin
* Add meager unit tests
* Add full localization support
* Add `version()` to return version number of the plugin
* Explicitly declare functions public and static
* Add documentation blocks for functions
* Add full inline code documentation
* Reformat plugin header
* Add 'Domain Path' directive to top of main plugin file
* Note compatibility through WP 4.1+
* Update copyright date (2015)
* Minor code reformatting (bracing, spacing)
* Change documentation links to wp.org to be https
* Update banner and screenshot images
* Add plugin icon
* Add .pot

## 1.1.4 _(2013-12-19)_
* Minor documentation tweaks
* Note compatibility through WP 3.8+
* Update copyright date (2014)
* Change donate link
* Update banner image to reflect WP 3.8 admin refresh
* Update screenshots to reflect WP 3.8 admin refresh

## 1.1.3
* Add check to prevent execution of code if file is directly accessed
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Move screenshots into repo's assets directory

## 1.1.2
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Add banner image for plugin page
* Remove ending PHP close tag
* Note compatibility through WP 3.4+
* Update copyright date (2012)

## 1.1.1
* Note compatibility through WP 3.3+
* Update screenshots

## 1.1
* Improve internationalization support
* Note compatibility through WP 3.2+
* Drop compatibility with versions of WP older than 3.1
* Minor code refactoring and formatting changes
* Fix plugin homepage and author links in description in readme.txt

## 1.0.2
* Add link to plugin homepage to description in readme.txt

## 1.0.1
* Note compatibility with WP 3.1+
* Update copyright date (2011)
* Add Upgrade Notice section to readme.txt

## 1.0
* Initial release
