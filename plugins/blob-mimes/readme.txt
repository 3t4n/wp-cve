=== Lord of the Files: Enhanced Upload Security ===
Contributors: blobfolio
Donate link: https://blobfolio.com/donate.html
Tags: mime, SVG, file validation, security plugin, wordpress security, malware, exploit, security, sanitizing, sanitization, file detection, upload security, secure, file uploads, infection, block hackers, protection
Requires at least: 5.2
Tested up to: 6.4
Requires PHP: 7.3
Stable tag: trunk
License: WTFPL
License URI: http://www.wtfpl.net/

This plugin expands file-related security and sanity around the upload process.

== Description ==

WordPress relies mostly on name-based validation when deciding whether or not to allow a particular file, leaving the door open for various kinds of attacks.

Lord of the Files adds to this content-based validation and sanitizing, making sure that files are what they say they are and safe for inclusion on your site.

The main features include:

 * Robust *real* filetype detection;
 * Full MIME alias mapping;
 * SVG sanitization (if SVG uploads have been independently allowed);
 * File upload validation debugger;
 * Fixes issues related to [#40175](https://core.trac.wordpress.org/ticket/40175) that have been present since WordPress `4.7.1`.
 * Fixes ambiguous media extensions [#40921](https://core.trac.wordpress.org/ticket/40921)

== Requirements ==

 * WordPress 5.2 or later.
 * PHP 7.3 or later.
 * `dom` PHP extension.
 * `fileinfo` PHP extension.
 * `mbstring` PHP extension.
 * `xml` PHP extension.

Please note: it is **not safe** to run WordPress atop a version of PHP that has reached its [End of Life](http://php.net/supported-versions.php). Future releases of this plugin might, out of necessity, drop support for old, unmaintained versions of PHP. To ensure you continue to receive plugin updates, bug fixes, and new features, just make sure PHP is kept up-to-date. :)

== Frequently Asked Questions ==

= Does this require any theme or config changes? =

This plugin is intended to be an activate-and-forget sort of affair for most users. All features are enabled by default.

But if you're a developer or system administrator, you might take a peek at `Tools > File Validation Reference` for a list of public filters you can hook into to change things up, and `Settings > File Settings` for global configuration overrides.

= This has mostly helped but I am still having trouble with one file... =

While this plugin extends MIME alias handling more than 20-fold(!), we are still busy tracking down all the edge cases.

Please go to `Tools > Debug File Validation` and post the output from that page into a new support ticket for this plugin.

We'll gladly see if we can cook up a fix or workaround!

= Does this plugin enable SVG support? =

No. This plugin does not modify your site's allowed upload types (see e.g. [upload_mimes](https://codex.wordpress.org/Plugin_API/Filter_Reference/upload_mimes) for that). However if SVGs are otherwise enabled for your site, this plugin will _sanitize_ them at the upload stage to make sure they do not contain any dangerous exploits.

There are a number of SVG-related filters that can be used to modify the sanitization behavior. Take a look at `Tools > File Validation Reference` for more information.

If you find the filters too aggressive, add `const LOTF_NO_SANITIZE_SVGS = true;` to your `wp-config.php` to disable the extra sanitizing.

== Screenshots ==

1. Example output from `Tools > Debug File Validation`.
2. The plugin includes a settings wizard under `Settings > File Settings`.

== Installation ==

Nothing fancy!  You can use the built-in installer on the Plugins page or extract and upload the `blob-mimes` folder to your plugins directory via FTP.

To install this plugin as [Must-Use](https://wordpress.org/support/article/must-use-plugins/), download, extract, and upload the `blob-mimes` folder to your `mu-plugins` directory and follow the third example listed under [Caveats](https://wordpress.org/support/article/must-use-plugins/#caveats); the main file for this plugin is `blob-mimes/index.php`.

Please note: MU Plugins are removed from the usual update-checking process, so you will need to handle all future updates manually.

== Privacy Policy ==

This plugin does not make use of or collect any "Personal Data".

== Changelog ==

= 1.3.18 =
* [Misc] Update MIME database.

= 1.3.17 =
* [Misc] Update MIME database.

= 1.3.16 =
* [Fix] Add workaround for MS Project files.

= 1.3.15 =
* [Misc] Update MIME database.

= 1.3.14 =
* [Misc] Update MIME database.

== Upgrade Notice ==

= 1.3.18 =
This release updates the MIME database.

= 1.3.17 =
This release updates the MIME database.

= 1.3.16 =
This release adds a workaround for MS Project files.

= 1.3.15 =
This release updates the MIME database.

= 1.3.14 =
This release updates the MIME database.
