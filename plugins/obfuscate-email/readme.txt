=== Obfuscate Email ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: email, obfuscate, obfuscation, security, spam, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.9
Tested up to: 5.7
Stable tag: 3.8.1

Obfuscate email addresses to deter email-harvesting spammers.

== Description ==

Obfuscate email addresses to deter email-harvesting spammers, with a focus on retaining the appearance and functionality of email hyperlinks.

"Obfuscation" simply means that techniques are employed to modify email address strings that appear on your site in such a way that bots scraping your site are unable to identify those addresses; however, at the same time those emails addresses should still look and work correctly for visitors, as much as possible.

The plugin allows for use of one or more (or all!) of three techniques for email protection that have proven themselves in the past. While techniques abound for email obfuscation, the three techniques included provide the best balance of email address protection with minimal impact on visitors. You can decide on a technique by technique basis which ones you'd like to employ as some have potential drawbacks. The plugin's settings page allows you select which techniques to use.

Ultimately, your best bet would be to not publicly expose an email address and to offer a contact form as an alternative means of contact. Or you can just accept that email addresses will get scraped and spammed, and rely on an email service that is good at filtering out spam. But this plugin is here for you if you want to employ the most reasonable means of making email harvesting difficult for your site.

See Filters section for `c2c_obfuscate_email_filters` for complete list of filters that are processed.

Please read the Details section of this documentation to learn more about the techniques employed.


== Details ==

The email obfuscation techniques included in this plugin were chosen for their effectiveness and general applicability with minimal impact on users. I urge you to read about an [experiment](https://web.archive.org/web/20180908103745/http://techblog.tilllate.com/2008/07/20/ten-methods-to-obfuscate-e-mail-addresses-compared/) performed by Silvan Mühlemann in which he protected email addresses using nine different techniques. He ensured the page containing those email addresses got indexed by Google and then waited 1.5 years. During that time he measured the amount of spam received to each of the email addresses. (Note: this experiment came out a few years after this plugin was originally created, but at this point was conducted over 10 years ago. Its conclusions may not apply as strongly today.)

Three techniques stood out as having received *zero* spam emails during that time. Two of those three techniques are included in this plugin. The fourth of his techniques is also included even though it did get a very small amount of spam -- the technique was still very effective and more importantly does not rely on users to have CSS or JavaScript enabled.

The techniques are as follows. Two are enabled by default. Weigh the requirements against what you're comfortable requiring of visitors in order for them to see and make use of email addresses you post on your site.

(For all the examples below, assume you have the link `<a href="mailto:person@example.com">person@example.com</a>` in your post.)

= Using CSS display:none =

* *How does it work?* Garbage text, wrapped in span tags, is inserted into any displayed email addresses. Using CSS, the text gets hidden so that visitors see the email addresses as intended. Email scrapers don't typically utilize a CSS engine to help determine how text would look onscreen.

* *Uses CSS?* Yes, which means if a visitor does not have CSS enabled, the emails will appear with extra text in them.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* Yes (unless they have CSS disabled).

* *Does this protect email addresses appearing in mailto: links and within HTML tag attributes?* No.

* *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

* *Example*

    `<a href="mailto:person@example.com">person@<span class="displaynone">null</span>example.com</a>`

= Replacing the `@` and `.` characters =

* *How does it work?*  The `@` and `.` characters are replaced with alternative strings, such as `AT` and `DOT`, respectively. The exact replacements are configurable on the plugin's settings page. By default, if you don't specify custom replacements, the plugin will use entity substitution (`@` becomes `&#064;` and `.` becomes `&#046;`).

* *Uses CSS?* No.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* No, though it should (hopefully) be clear to the user what they need to replace.

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* Yes, though if you specify custom replacement strings visitors clicking on a mailto link will have to modify the email address that shows up in their mail program.

* *How effective is this?* In the aforementioned experiment, almost no spam emails were received when using just this technique. As a bonus, this technique does not require the support of any particular client-side techniques (CSS or JavaScript).

* *Examples*
    * Custom AT and DOT replacements
        * `<a href="mailto:personATexampleDOTcom">personATexampleDOTcom</a>`
        * `<a href="mailto:person@DELETETHISexample.com">person@DELETETHISexample.com</a>`
    * Everything encoded (aka hexadecimal HTML entity substitution)
`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;">&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;</a>`

= Changing text direction with CSS (not enabled by default) =

* *How does it work?* The email addresses are sent reversed in the markup. Using CSS, the text gets reversed so that visitors see the email addresses as intended. Email scrapers don't recognize the emails in their reversed form and don't typically utilize a CSS engine to help determine how text would look onscreen.

* *Uses CSS?* Yes, which means if a visitor does not have CSS enabled, the emails will appear backwards to them.

* *Uses JavasScript?* No.

* *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* No, text copied in such a manner will be reversed. However, a right-click -> "copy link/email address" will work properly for linked email addresses.

* *Does this protect emails appearing in mailto: links and within HTML tag attributes?* No.

* *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.

* *Example*:

    `<a href="mailto:person@example.com"><span class="codedirection">moc.elpmaxe@nosrep</span></a>`

= How it looks =

If all techniques are enabled at once, the resulting obfuscation of the example link above is (for the full effect, view this in the page's source or the [readme.txt]() file directly):

`<a href="mailto:&#x70;&#x65;&#x72;&#x73;&#x6f;&#x6e;&#x40;&#x65;&#x78;&#x61;&#x6d;&#x70;&#x6c;&#x65;&#x2e;&#x63;&#x6f;&#x6d;"><span class="codedirection">&#x6d;&#x6f;&#x63;&#x2e;&#x65;&#x6c;&#x70;&#x6d;&#x61;&#x78;&#x65;<span class="displaynone">null</span>&#x40;&#x6e;&#x6f;&#x73;&#x72;&#x65;&#x70;</span></a>`


However, in your browser it would appear to you as it does prior to obfuscation, and the link for the email would still work. Theoretically, however, spammers would have a somewhat more difficult time harvesting the emails you display or link to in your posts.

NOTE: (Only when using the custom replacement feature will visitors need to modify the email address for use in their email program.)

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/obfuscate-email/) | [Plugin Directory Page](https://wordpress.org/plugins/obfuscate-email/) | [GitHub](https://github.com/coffee2code/obfuscate-email/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Whether installing or updating, whether this plugin or any other, it is always advisable to back-up your data before starting
1. Install via the built-in WordPress plugin installer. Or download and unzip `obfuscate-email.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Go to `Settings` -> `Obfuscate Email` admin options page (which you can also get to via the Settings link next to the plugin on the Manage Plugins page) and optionally customize the settings.


== Frequently Asked Questions ==

= So it'll be impossible for spammers to harvest my site for email addresses? =

Of course nothing is guaranteed. By its very definition, "obfuscate" means "to make obscure or unclear", and that's all it's really doing. It's some degree of basic protection, which is oftentimes better than nothing. Similarly, a locked door is only some measure of deterrent for a would-be intruder and not absolute security.

Your best bet would be to not publicly expose an email address. You could provide a contact form as an alternative means of contact. Or you can just accept that email addresses will get scraped and spammed, and rely on an email service that is good at filtering out spam. But this plugin is here for you if you want to employ the most reasonable means of making email harvesting difficult for your site.

= Aren't there better methods of email obfuscation? =

Nothing short of not actually displaying email addresses can guarantee that email addresses can't get harvested. Some methods are more aggressive and therefore have compatibility and/or usability issues. This plugin can be very compatible and usable by most visitors to your site, but also has allowances for greater protection with minimal impact (though how minimal is for you to judge).

= Does this plugin make use of JavaScript as other email obfuscators do?

No. This makes this plugin's implementation of obfuscation more compatible and usable by more visitors. This choice does leave out JavaScript-based approaches that some argue are effective in their own way (techniques such as ROT13 transformation, JS insertion/contruction of the email address, among others).

= Can I apply more than one of the available techniques at the same time for even greater protection? =

Yes, all techniques can be activated at once (and multiple ones are by default).

= Will obfuscated links meet WCAG/508 or other accessibility standards? =

No. Any technique used to obfuscate email address has some measure of drawbacks in terms of accessibility and/or usability. The documentation for the techniques provided by the plugin are clear about the nature of their individual drawbacks.

= Does this plugin modify the post content in the database? =

No. The plugin filters post content on-the-fly. Emails will remain unchanged in the database.

= Why don't I see any obfuscation when viewing the source for the page (or a selection) via my browser's inspector? =

The web browser's inspector tool will process certain techniques (such as HTML hexadecimal substitution) before showing the source in the inspector. You should "View Source" to see the raw markup sent to the browser.

= Does this plugin include unit tests? =

Yes.


== Screenshots ==

1. A screenshot of the plugin's admin options page.


== Template Tags ==

The plugin provides one optional template tag for use in your theme templates.

= Functions =

* `function c2c_obfuscate_email( $text, $args = array() )`

= Arguments =

* `$text`
Required argument. The text and/or HTML that contains email addresses that you want to be obfuscated.

* `$args`
Optional argument. An array of configuration options, each element of which will override the plugin's corresponding default setting.
    * encode_everything (boolean) : Encode all characters in the email address using hexadecimal HTML entity substitution?
    * use_text_direction (boolean) : Utilize CSS text direction technique?
    * use_display_none (boolean) : Utilize CSS display:none technique?
    * at_replace (string) : String to use in place of `@` in email addresses (used only if encode_everything is false)
    * dot_replace (string) : String to use in place of `.` in email addresses (used only if encode_everything is false)

= Examples =

* Basic usage. Obfuscate email addresses in $text according to current plugin settings.

`<?php echo c2c_obfuscate_email( $text ); ?>`

* Override all plugin default settings when obfuscating email addresses in $text and just use text direction technique.

`<?php echo c2c_obfuscate_email( $text, array(
  array('use_text_direction' => true, 'use_display_none' => false, 'encode_everything' => false, 'at_replace' => '', 'dot_replace' => '')
) ); ?>`


== Hooks ==

The plugin exposes one filter for hooking. Typically, code making use of filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain).

**c2c_obfuscate_email_filters (filter)**

The 'c2c_obfuscate_email_filters' filter allows you to customize what filters get processed for email obfuscation. The following filters are all filtered by default:

* link_description
* link_notes
* bloginfo
* nav_menu_description
* term_description
* the_title
* the_content
* get_the_excerpt
* comment_text
* list_cats
* widget_text
* the_author_email
* get_comment_author_email

Arguments:

* array $filters : the default array of filters

Example:

`
/**
 * Also obfuscate emails appearing in custom field values.
 *
 * @param array $filters Filters that get filtered to obfuscate email addresses.
 * @return array
 */
function change_c2c_obfuscate_email_filters( $filters ) {
	$filters[] = 'the_meta';
	return $filters;
}
add_filter( 'c2c_obfuscate_email_filters', 'change_c2c_obfuscate_email_filters' );
`


== Changelog ==

= 3.8.1 (2021-04-14) =
* Fix: Update plugin framework to 061 to fix a bug preventing settings from getting saved

= 3.8 (2021-04-10) =
Highlights:

* This minor release updates updates the plugin framework, restructures the unit test file structure, and notes compatibility through WP 5.7+.

Details:

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

= 3.7 (2020-06-30) =
Highlights:

* This minor release updates its plugin framework, omits `type` attribute for `style` tag when theme supports 'html5', adds a TODO.md file, updates a few URLs to be HTTPS, expands unit testing, and updates compatibility to be WP 4.9 through 5.4+.

Details:

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

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/obfuscate-email/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 3.8.1 =
Recommended bugfix release: fixed a bug preventing settings from getting saved; updated plugin framework to v061

= 3.8 =
Minor update: updated the plugin framework, restructured the unit test file structure, noted compatibility through WP 5.7+, and updated copyright date (2021).

= 3.7 =
Minor update: updated plugin framework, added a TODO.md file, updated a few URLs to be HTTPS, expanded unit testing, updated compatibility to be WP 4.9 through 5.4+, and minor behind-the-scenes tweaks.

= 3.6.1 =
Trivial update: modernized unit tests, noted compatibility through WP 5.3+, and updated copyright date (2020)

= 3.6 =
Minor update: tweaked plugin initialization, updated plugin framework to version 049, noted compatibility through WP 5.1+, dropped compatibility with versions of WP older than 4.7, created CHANGELOG.md to store historical changelog outside of readme.txt, and updated copyright date (2019)

= 3.5.1 =
Minor update: updated plugin framework to 044; fixed display of changelog; used "email" instead of "e-mail" everywhere

= 3.5 =
Minor update: improved support for localization; minor widget code changes; verified compatibility through WP 4.5; dropped compatibility with WP older than 4.1; updated copyright date (2016)

= 3.4 =
Minor update: prevented email obfuscation from occurring in the admin area; noted compatibility through WP 4.2+

= 3.3 =
Minor update: minor fix; added unit tests; updated plugin framework to 039; noted compatibility through WP 4.1+; updated copyright date (2015); added plugin icon

= 3.2 =
Recommended update. Highlights: disabled text direction technique by default; updated plugin framework; noted compatibility through WP 3.6+; and more.

= 3.1 =
Recommended update. Fixed a number of bugs; noted WP 3.3 compatibility; dropped support for versions of WP older than 3.1; updated plugin framework; and more.

= 3.0 =
Recommended update. Major rewrite. Everything has changed and been improved. You want this. Compatible with WP 3.0 through 3.2+.
