=== AccessibilityPlus: Boost Accessibility & SEO Score for Lighthouse ===
Contributors: easywpstuff
Tags: pagespeed, lighthouse, accessibility, core web vitals
Requires at least: 5.0
Tested up to: 6.4.1
Requires PHP: 5.6
Stable tag: 1.2.4
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Improve page speed insight or lighthouse accessibility scores by fixing recommended issues.

== Description ==
A WordPress plugin that helps improve your website's pagespeed insight or lighthouse accessibility & SEO scores by fixing recommended features. Some of the issues that this plugin addresses include:

- Form elements do not have associated labels
- Links do not have a discernible name
- Buttons do not have an accessible name
- [user-scalable="no"] is used in the &lt;meta name="viewport"&gt; element or the [maximum-scale] attribute is less than 5
- button, link, and menuitem elements do not have accessible names.
- &lt;frame&gt; or &lt;iframe&gt; elements do not have a title.
- ARIA progressbar elements do not have accessible names.
- Links are not crawlable.
- Image elements do not have [alt] attributes
- Some elements have a [tabindex] value greater than 0
== Features ==

- Easy to use interface
- Fixes common issues that can negatively impact pagespeed insight or lighthouse accessibility scores
- Improves the user experience of your website

== Installation ==
1. Unzip the plugin archive on your computer
2. Upload directory to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the options in the plugin settings according to your site requirement.
5. That's it! The plugin will do the rest.

== Frequently Asked Questions ==

**Does this plugin improve performance score?**
No, this plugin will not improve performance score on pagespeed insight or lighthouse

**Does this plugin improve accessibility score?**
Yes, this plugin gives you the option to select the recommended fix to improve lighthouse accessibility score

**can i enable all options?**
Yes, but it would be better to enable those options that's required for your website.


== Screenshots ==
1. [Screenshot of plugin in action](assets/screenshot-1.png)

== Changelog ==
= 1.2.4 =
* Fixed `button, link, and menuitem elements do not have accessible names.`
= 1.2.3 =
* Added Some elements have a [tabindex] value greater than 0
* Improve `Link don't have a discernible name`
= 1.2.2 =
* Improved DOM
= 1.2.1 =
* Minor Fixes
= 1.2 =
* Speedup DOM and Fixed Fatal error
= 1.1.3 =
* Minore Improvement
= 1.1.2 =
* Fixed svg conflict
= 1.1.1 =
* Improved `Links are not crawlable`
= 1.1.0 =
* Fixed fatal error and other issues. 
= 1.0.5 =
* added option for Image elements do not have [alt] attributes
= 1.0.4 =
* Added options for ARIA progressbar elements do not have accessible names.
* Links are not crawlable
= 1.0.3 =
* Minore issues
= 1.0.2 =
* Improved
= 1.0.1 =
* Fixed Minor issues
= 1.0.0 =
* Initial release.