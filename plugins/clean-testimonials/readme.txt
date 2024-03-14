=== Clean Testimonials ===
Contributors: hello@lukerollans.me, Plugify
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=hello%40plugify%2eio&lc=GB&item_name=Plugin%20Development%20Donation&currency_code=USD
Tags: testimonials,testimonial,recommendation,recommend,testimony,reference,referral,widget,reviews,review
Requires at least: 2.5
Tested up to: 3.8.1
Stable tag: 1.5.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Testimonials to your WordPress website. Simple, easy, quick and clean.

== Description ==

= Have a feature request? =
Sweet! Feature requsts on Github: https://github.com/lukerollans/clean-testimonials/issues?labels=enhancement

= Want to contribute? =
Clean Testimonials on Github: http://github.com/lukerollans/clean-testimonials

Clean Testimonials enables you to easily and quickly add Testimonials to your WordPress website. The plugin integrates seamlessly with your existing WordPress admin area, so you will feel right at home.

There are a few simple ways to manage your testimonials:

1. Easy to use shortcodes when editing content in the WordPress admin area.
2. The built-in Testimonial Widget to display a specific or random testimonial
3. PHP code if you are a developer (see FAQ).

This plugin is in constant development. If you have any feature requests or questions, please feel free to submit them via the support forum.

= Features =

* Creates a "Testimonials" link in your WordPress admin area which allows you or your visitors to submit Testimonials.
* Leverages the simplicity of WordPress shortcodes, allowing you to easily display your Testimonials wherever you like (see FAQ for individual shortcodes).
* Categorize Testimonials any way you see fit and display those categories wherever you like.
* Creates a Testimonial Widget which allows you to display your testimonials in sidebar or widgetized areas.
* Display random testimonials using either a shortcode or the built-in widget.
* Ships with CAPTCHA support, but you can turn it off if you like
* Allows your visitors to upload a thumbnail with their testimonial.
* Allows your visitors to choose whether their contact details are displayed with their testimonials.
* Allows powerful customizations for developers.
* Setup for translation, so if you're a translator, get translating!
* Supports **WP-Paginate** if installed.

**Please Note:** Although any output Clean Testimonials generates is well structured, no styling is shipped out of the box. This means it is up to your theme to decide how the output will be styled.

If you have found this plugin useful, consider taking a moment to rate it, or perhaps even a small donation.

== Installation ==

1. Upload the `clean-testimonials` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= Can testimonials be submitted by my visitors automatically? =

Yes. On top of your ability to add and edit testimonials in the WordPress admin area, you can also use a shortcode to display a form on any page you like. This form will create a new testimonial when a user fills it out. The shortcode to do this is [testimonial-submission-form]

When a user submits a testimonial, it will default to "Draft" status. You will need to publish any testimonials this way before they will display on your site. Just in case someone writes anything naughty!

= How do I display all of my testimonials? =

To display all testimonials with pagination, use the [testimonials] shortcode.

= How do I display a testimonial? =

To display a single testimonial you can use the [testimonial id="xyz"] shortcode, where "xyz" is the ID of the testimonial you wish to display.
You can copy and paste the testimonial shortcode complete with ID from your Testimonials admin page in WordPress.

= How can I display a category of testimonials? =

To display a category of testimonials (with pagination!), you can use the [testimonials category="xyz"] shortcode. where "xyz" is the ID of the testimonial category you wish to display.
You can also display testimonials from multiple categories by using [testimonials category="xyz,abc"] where "xyz" is the first ID and "abc" is the second. You can pass in as many ID's as you like, just separate them with commas.

You can copy and paste this shortcode complete with ID from the Testimonials -> Categories admin page in WordPress.

= Can I display a random testimonial? =

Yes, you can use the Testimonial Widget and specify the "random" option, or you can use the [testimonial id="random"] shortcode.

= Can I change the number of testimonials shown per page? =

Yes. Specify the "per_page" attribute when using the [testimonials] shortcode. EG, [testimonials category="10" per_page="5"].

= Available filters =
The following filters are available in Clean Testimonials so you can customise it to your liking.

1. new_testimonial_notification - Filter whether an email notification should be sent to the administrator when a new testimonial is submitted.
2. new_testimonial_email - Filter which email address which the new testimonial notification email is sent to. Defaults to administrator email.
3. new_testimonial_confirmation_message - Filter the text which is displayed when a new testimonial is received.
4. new_testimonial_failure_message - Filter the text which is displayed when a testimonial submission fails.
5. ct_disable_captcha - Return true to disable captcha on the testimonial submission form

= Available actions =
The following actions are available in Clean Testimonials so you can customise it to your liking.
1. ct_before_render_testimonial - Fires before a testimonial is rendered. Passes in $testimonial and $context
1. ct_after_render_testimonial - Fires after a testimonial is rendered. Passes in $testimonial and $context

= I am a developer, what can you tell me? =

The following information might be handy for you to know.

1. Testimonials operate via a custom post type which is simply named "testimonial".
2. Testimonials are grouped in a custom taxonomy named "testimonial_category".
3. The Testimonial widget class name is "Testimonial_Widget" and of course extends WP_Widget.

== Screenshots ==

1. An overview of the Testimonials menu
2. Editing or creating a Testimonial in the backend
3. Managing Testimonial categories
4. Example use of the [testimonials] shortcode. This will display all testimonials with pagination
5. Example use of the [testimonial-submission-form] shortcode. This shortcode will turn this page into a Testimonial submission page for your users

== Changelog ==

= 1.5.2.1 =
* Removed developer output from [testimonials] shortcode

= 1.5.1 =
* Added translation support.
* Added filter "ct_disable_captcha" to allow disabling captcha.
* Multiple categories can now be passed in a shortcode. EG, [testimonials category="34,87,33" per_page="25"].
* Added a one donation donation/rating request. Enjoying this free plugin? Please show some one time love!
* Updated PayPal donation address in readme

= 1.5 =
* Added ct_pre_render_testimonial, ct_render_testimonial and ct_after_render_testimonial hooks.
* ct_pre_render_testimonial is a filter which allows a developer to completely override how a testimonial looks.
* ct_render_testimonial is a filter which allows a developer to filter the default testimonial styling before Clean Testimonials outputs it.
* ct_after_render_testimonial is an action which triggers every time a testimonial is rendered, allowing you to perform your own actions afterwards.
* Added 'context' parameter to each testimonial render (including the above hooks). This allows a developer further flexibility in how a testimonial should be rendered.
* Minor tweak to the testimonial cycle code
* The testimonial cycle AJAX request now takes 'context' and 'word_limit' as a parameter

Props to @ericdaams for this release!

= 1.4.2 =
* Simple maintenance release. No functionality change.

= 1.4.1 =
* Fixed bug causing testimonial widget to display a PHP warning when no random categories were selected

= 1.4 =
* Introduced new filters to customize testimonial submission behaviour. See FAQ.
* Added filter to customize CAPTCHA theme. EG, red (default), white, blackglass or clean. See FAQ.
* Added ability to specify ordering in the [testimonials] shortcode. EG, [testimonials category="5" order="date" orderby="desc"]
* Added shortcode attribute to allow basic AJAX powered random testimonial cycling

= 1.3.5 =
* Bug fixes. Woops!

= 1.3.4 =
* An email notification is now sent to the site owner when a new testimonial is submitted
* The Testimonial Widget "random" option now allows you to specify source categories if you wish
* Added "word_limit" attribute to [testimonial] and [testimonials] shortcodes, allowing you to truncate long testimonials if you wish

= 1.3.3 =
* Fixed bug causing testimonial submission form to appear incorrectly

= 1.3.2 =
* Fixed bug causing Testimonials submitted via the template submission form to be saved as incomplete

= 1.3.1 =
* Added CAPTCHA to testimonial submission form

= 1.3 =
* Added "per_page" attribute to [testimonials] shortcode which allows you to specify how many testimonials to display per page. EG, [testimonials category="25" per_page="5"]. Default is 2
* Fixed small PHP warning which could be generated when testimonial pagination is rendered

= 1.2.5 =
* When displaying a testimonial, the comma separating the client name and company name will no longer be displayed if the company name is not set

= 1.2.4 =
* Fixed output positioning of shortcode data

= 1.2.3 =
* Small security updates

= 1.2.2 =
* Fixed bug causing Featured Images (thumbnails) to not appear on the Testimonial edit page
* Added Testimonial Widget
* Changed [testimonial] shortcode to accept "random" as an ID which, in turn, outputs a random testimonial

= 1.2 =
* Enhancements and bug fixes

= 1.0 =
* Initial release of plugin

== Upgrade notice ==

No upgrade notice necessary
