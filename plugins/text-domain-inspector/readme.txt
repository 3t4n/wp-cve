=== Text Domain Inspector ===
Contributors: laugei
Tags: text-domain, text, domain, inspector, translation
Requires at least: 4.6
Tested up to: 6.2.2
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Text Domain Inspector is a plugin that helps to inspect text domains of translatable strings

== Description ==
Have you ever been frustrated when trying to find the text domain of translatable string you want to translate? This task can be challenging and it can take a lot of your time even if you have code reading skills.

This plugin aims to solve this problem by allowing website administrator to inspect text domains of translatable strings directly in the browser.

How to use:
* Press "Inspect Text Domains" button in admin menu bar;
* Red dots will appear next to translatable strings;
* Hover the red dot to view the text domain;
* Open source code in the browser to view text domains in HTML attributes (ctrl+u (Windows) / cmd+opt+u (Mac));

Works in:
* HTML documents;
* HTML fragments;
* HTML attributes;
* Plain text;
* Dynamically loaded content (through AJAX);
* JSON;

== Installation ==
Upload this plugin to your website and activate it.

You're done!

== Screenshots ==
1. In the browser.
2. HTML attributes which are visible in the browser.
3. HTML attributes in the source code.
4. JSON AJAX response.


== Changelog ==
### 1.1

* Added support for i18n
* Text domain is now appended as a query param if string is a URL (fixes the error for WP >= 5.9)

### 1.0

* Initial version.
