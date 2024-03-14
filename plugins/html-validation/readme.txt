=== HTML Validation ===
Contributors: seshelby
Donate link: https://www.alumnionlineservices.com/
Tags: html validation, markup validation, code validation, html validator, accessibility, validated
Requires at least: 4.6
Tested up to: 6.4
Requires PHP: 5.5
Stable tag: 1.0.13
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
The HTML Validation Plugin runs in the background, identifies and reports HTML validation errors on your website. Once activated, the HTML Validation plugin uses Wordpress cron to scan your website content in the background. A progress bar on the report screen indicates scan progress. HTML Validation is provided by [Validator.nu](https://about.validator.nu/). Please refer to the provided [privacy policy and terms of use](https://about.validator.nu/#tos). Posts may also be scanned using the Validate HTML link provided on the "All Posts" screen. 

The HTML Validation Pro extension adds options to automatically correct many HTML Validation issues. This one of a kind plugin could save you hundreds of hours of work finding and correcting HTML validation issues. [Visit our website to learn more and add the Pro Extension](https://www.alumnionlineservices.com/php-scripts/html-validation/#proext) 

== Installation ==
1. Upload the html-validation folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

== Screenshots ==
1. Not Available

== Changelog ==
- 1.0.13
1. Corrected validate link not working when linkid is not present in link table
1. Corrected pagination not working when validate is passed through the url

- 1.0.12
1. corrected recheck not working when validate html link is used to start a validation checks
1. corrected unescaped output values

- 1.0.11
1. added function to delete tables when a multisite blog is removed

- 1.0.10
1. corrected php warnings on settings page
1. increased database field sizes on id fields
1. corrected bug in duplicate error ignore feature
1. added notice when processing ignore or recheck on error screen
1. added ada issue notice for empty option tags

= 1.0.9 =
1. corrected ignored content list not being updated on settings page

= 1.0.8 =
1. corrected filtered error results resetting when changing pages
2. corrected filtered error results resetting when rechecking a page

= 1.0.7 =
1. changed default cron frequency

= 1.0.6 =
1. added indicator for minumum settings to be reviewed and adjusted

= 1.0.5 =
1. added support for filtering ada errors in report
1. updated link inventory to include term id to support ada compliance scans
1. corrected bug resulting in multiple cron hooks being scheduled for initial scan

= 1.0.4 =
1. corrected bug resulting in term errors being purged
1. reset scan flag on purge to trigger faster validation checks on initial scan
1. improved input validation

= 1.0.3 =
1. removed refresh action on error report when page focus is lost

= 1.0.2 =
1. corrected recheck not working

= 1.0.1 =
1. corrected fatal error on report screen

= 1.0 =
1. Initial Deployment