=== Cookie Notice & Consent ===
Contributors: christophrado
Donate link: https://www.paypal.me/christophrado
Tags: cookie, consent, compliance, gdpr, dsgvo
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 1.6.1
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display a cookie notice, collect consent for different categories and output scripts if consent is given.

== Description ==

* **Cookie Notice & Consent** makes it easy for you to collect consent for the usage of cookies.
* It lets you define different cookie categories and display them within a notice banner.
* Ready-made themes for the cookie notice banner makes it easy for non-technical users to get started.
* Users can select which categories to consent with. Consent logs are stored (optional).
* Scripts will be output and executed only after the user has accepted the respective categories.

Cookie Notice & Consent aims to help you comply with local privacy laws like GDPR/DSGVO. It does not offer a one-click solution though. Please set up your website properly and carefully to comply with applicable laws.

= Developer focus =

Please note that **Cookie Notice & Consent** is generally geared towards developers and technically savvy site administrators. Although it does provide pre-made themes, the plugin does not provide separate options for individual design aspects, and does not plan to provide those in the future. It is up to the developer/administrator to add further styling to match the theme using CSS.

This plugin does not add any branding (neither visually nor textually), top-level admin menu items or user-facing indicators. It aims to be usable in client projects by blending in with WordPress core appearance, naming and settings. Settings screens are visible to administrator level users only.

= What this plugin does not provide =

* Beyond the pre-made, as-is themes, this plugin does not offer styling options via the settings screen (please contact your webdev for further design customization)
* This plugin does not provide scanning for, detecting or automatic blocking content like embeds, scripts or cookies

= Shortcodes and functions =

This plugin currently provides the following shortcodes and public functions:

* Shortcode [revoke_cookie_consent]: Outputs a revoke consent button that clears the consent cookie
* Shortcode [cookie_consent_status]: Outputs a formatted string indicating the users consent choice
* Function `is_cookie_consent_set()`: Returns whether the consent has been set by the user
* Function `is_cookie_category_accepted( $category )`: Returns whether the given cookie category has been accepted by the user (valid values: essential, functional, marketing)

= Disclaimer =

**This plugin does not represent legal advice and assumes no liability whatsoever. Please obtain proper advice from real lawyers if in doubt.**

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/cookie-notice-consent` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure your settings using the Settings->Cookies screen

== Frequently Asked Questions ==

= How can I modify the notice banner styling? =

You can choose between pre-defined themes. Apart from those, and the accent color option, further customization requires CSS skills. Applicable CSS classes are best found in the source code. Included styling is done via class names, which makes it easy to overwrite using the parent element's ID.

= Are you planning to add more features? =

Yes. Right now, I'm interested in looking into auto-blocking of embeds. Plans might change though.

= Where's feature X that I need for full compliance in my country? =

If this plugin does not meet your legal requirements, I encourage you not to use it. Please note that this plugin will probably never turn into a one-click solution for regular users. This plugin is geared towards developers or at least technically savvy users.

== Screenshots ==

1. Cookie banner, 'Labs' theme, with category descriptions
2. Cookie banner, 'Sidecar' theme, with category descriptions
3. General Settings screen, tabbed category navigation
4. Design Settings screen, theme selector, with accent color picker
4. Cookie category settings screen, define name, description and scripts
5. Consent logs and basic consent statistics per category

== Changelog ==

= 1.6.1 =
* Fixed: XSS vulnerability (non-escaped settings field values; only Administrator affected; disclosed by Patchstack)
* Tested up to 6.3

= 1.6.0 =
* Note: If you run your own design, please note that this release adds a new element to the notice banner
* Added: Reject button
* Improved: Buttons will now be hidden if their label is empty
* Tested up to 6.2

= 1.5.3 =
* Tested up to 6.1

= 1.5.2 =
* Added: Filter `cookie_notice_consent_cookie_expiration` to change the consent cookie expiration time in days (default: 30)
* Improved: Cookie notice visibility management when a cache plugin is active
* Fixed: WPML not filtering frontend strings because of wrong hook priority
* Fixed: DivisionByZeroError on the statistics admin screen if no logs are present

= 1.5.1 =
* Hotfix: Missing plugin files

= 1.5.0 =
* Tested up to 5.9
* Added: WPML / Polylang support
* Added: Info dialog when revoking consent
* Added: Options for automatically respecting 'Do not track' (DNT) and 'Global Privacy Control' (GPC) privacy signals
* Added: Numerous action hooks in the notice output
* Added: (BETA) Experimental embed blocking (proof of concept, only for editor embeds)

= 1.4.1 =
* Added: Option to set the Revoke Consent button label
* Added: Filter `cookie_notice_consent_print_$category_code_in_head` to output category code in the head (true) rather than footer (false, default)
* Added: Filter `cookie_notice_consent_print_plugin_script_in_head` to output the plugin base script in the head (true) rather than footer (false, default)
* Changed: Renamed filter `cookie_notice_consent_output_script_$category` to `cookie_notice_consent_$category_code` to better reflect its purpose

= 1.4.0 =
* Tested up to 5.8
* Added: Proper cache recognition and handling (via WP_CACHE constant)
* Improved: Consent banner is additionally shown and hidden via JS (better behaviour if caching is in use)
* Improved: Consent UUID is generated by the frontend script if caching is in use
* Improved: Nonce check for logging is skipped if caching is in use
* Fixed: Don't append cache buster URL argument when revoking cookies
* Fixed: Prevent double cache buster URL argument when accepting cookies

= 1.3.0 =
* Added: New 'Low-key' theme, an unobtrusive option that doesn't interrupt users
* Fixed: Number formatting in consent statistics

= 1.2.2 =
* Tested up to 5.7
* Added: Option to automatically purge all cookies site-wide on consent revoke (on by default)
* Improved: Template function `is_cookie_category_set` doesn't expect `category_` prefix anymore
* Improved: Register admin-ajax hooks only when necessary (if consent logging is on)
* Improved: Better UX for slow connections by keeping the loading spinner visible while waiting for reload
* Improved: Code commenting

= 1.2.1 =
* Fixed: Missing style in admin area (single consent view)
* Improved: Theme styles and sizing on smaller screens

= 1.2.0 =
* Added: Themes! Two ready-made cookie notice themes to choose from
* Added: Option to enable/disable consent logging (on by default)
* Added: Option to enable/disable consent log IP anonymization (on by default)
* Added: Option for automatic consent log purging
* Changed: Moved 'Show category description' option to design settings (tiny breaking change, sorry)
* Changed: Show Cookie Consents menu item and Consent Statistics tab only if logging is on
* Changed: Optimized frontend script to reflect optional logging
* Improved: More consistent rendering of default theme/style
* Improved: Markup structure, classes and naming

= 1.1.0 =
* Added: Cookie consent logging via custom post type
* Added: Basic consent statistics per cookie category
* Added: Loading indicator when interacting with the consent banner (helpful for slow connections)
* Changed: Minifying of frontend scripts and styles, added version parameter
* Improved: Code and function formatting and structure

= 1.0.3 =
* Fixed: Additional client-side cookie check to prevent cache issues

= 1.0.2 =
* Fixed: Invalid code output due to sanitizing

= 1.0.1 =
* Fixed: Allow basic HTML in banner text

= 1.0.0 =
* Initial release