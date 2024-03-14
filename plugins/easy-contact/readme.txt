=== Easy Contact ===
CONTRIBUTORS: plaintxt.org
DONATE LINK: http://www.plaintxt.org/about/#donate
TAGS: email, contact, form, contact form, shortcode, sandbox, css, semantics, extensible xhtml, valid xhtml
REQUIRES AT LEAST: 2.5
TESTED UP TO: 2.6.1
STABLE TAG: 0.1.2

Easy Contact is a simple, highly extensible XHTML contact form featuring spam-reduction measures, GUI customization, and shortcode-based insertion.

== Description ==

Easy Contact is a simple contact form that utilizes the [Sandbox](http://www.plaintxt.org/themes/sandbox/ "Sandbox theme for WordPress") design patterns to create a highly semantic, XHTML-based contact form you can insert using `[easy-contact]` on any page or post.

Easy Contact features spam-reduction measures, GUI-based customization, carbon copying option, and more. Emails include tracked referrer information, including keywords for search-based landings, user agent, and IP.

Easy Contact is for WordPress 2.6.x and features:

* Out-of-the-box function using shortcode without editing any files
* Options menu for complete customization of form content
* Spam reduction measures, i.e., math- and/or challenge-based question
* Secure plugin operation and highly semantic XHTML
* Use of the Sandbox comment form design patterns
* An included starter CSS file with corresponding images
* Tracked information, e.g., user agent, user referrers, IP

Based partially on the classic [WP Contact Form](http://wordpress.org/extend/plugins/wp-contact-form/ "WP Contact Form for WordPress"), Easy Contact includes newer WordPress features (e.g., shortcode) and greatly improved form security. Easy Contact is just another contact form, except built with clean XHTML and improved security.

== Installation ==

This plugin is installed just like any other WordPress plugin. More [detailed installation instructions](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins "Installing Plugins - WordPress Codex") are available on the WordPress Codex.

1. Download Easy Contact
2. Extract the `/easy-contact/` folder from the archive
3. Upload this folder to `../wp-contents/plugins/`
4. Activate the plugin in *Dashboard > Plugins*
5. Customize from the *Settings > Contact* options menu
6. Use the shortcode `[easy-contact]` on any page/post
7. Enjoy. And then consider donating

In other words, just upload the `/easy-contact/` folder and its contents to your plugins folder.

== Use ==

After activating this plugin, simply use the shortcode `[easy-contact]` wherever you want the Easy Contact form. This shortcode takes no attributes.

`[easy-contact]`


You will also want to customize the Easy Contact plugin from the *Settings > Contact* options menu. Here you can set the email address to receive submissions, text for legends, labels, and prompts, turn on spam reduction measure(s), etc.

Included with Easy Contact is a example style sheet file with images. Easy Contact features dynamic classes, so when an input field is returned to the user for an error-related issue, the input is given the class `error`, etc.

If you are using the Sandbox or a Sandbox-based theme template, you probably won't need much (if any) CSS customization, as Easy Contact shares the same design patterns as the Sandbox comment form.

And so on. A very simple plugin.

== License ==

Easy Contact, a plugin for WordPress, (C) 2008 by Scott Allan Wallick, is licensed under the [GNU General Public License](http://www.gnu.org/licenses/gpl.html "GNU General Public License").

Easy Contact is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Easy Contact is distributed in the hope that it will be useful, but **without any warranty**; without even the implied warranty of **merchantability** or **fitness for a particular purpose**. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Extended Options. If not, see [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/ "GNU General Public Licenses").

== Frequently Asked Questions ==

= What does this plugin do? =

The Easy Contact plugin generates a basic XHTML form for collecting 'contact' emails without providing your email explicitly. And that's about it.

= I need spam prevention. Does this plugin have that? =

Yes. Sort of. This plugin has spam reduction measures. There are a couple accessible challenge questions (simple math and a challenge question you may write) as well as internal measures that ensure form data is being submitted legitimately and not from some spam robot of death.

= Awesome. So no spam! =

You're insane. It's spam *prevention*. So less spam, yes.

= Will my contact form be all jacked up on AJAX? =

No. This is an extremely tidy and semantically rich XHTML form. No JavaScript is included (or needed) with this plugin.

= But I want a form plugin that fades and jumps and is draggable and magical. =

Then you should consider the [cform II](http://wordpress.org/extend/plugins/cforms/ "cforms II - contact forms") plugin by Oliver Seidel, which does all sorts of stuff that is beyond the scope of this humble plugin.

= So you're suggesting I use a different plugin for my contact form? =

Not at all. If you want a static contact form that is secure, simple XHTML, which you can customize using CSS based on dynamic class selectors, which won't increase page loads or require complicated CSS customizations, and doesn't have me sticking links back to myself all over the place, then this is your plugin.

= Sounds good. I'm using the old WP Contact Form or another derivative thereof. Can I use both? =

Yes. If you have old database entries for the old WP Contact Form, the Easy Contact options menu will give you the option to delete those entires. Almost everyone at one point has tried that plugin. This plugin uses shortcode and stores info in different places, so you're cool.

= I'd like to modify this plugin in some way unavailable through its options menu. Will you help me? =

No. I apologize as I am unable to help with modifications with any of my plugins.

= Well, thanks for nothing. =

Not even thanks for the plugin?

= Sure. Thanks for the plugin. =

You're welcome. Enjoy.

= I don't like shortcodes. Isn't there an HTML comment that will insert the form? =

No. The benefit of using an HTML comment to insert the form is that when the plugin is deactivated, no junk code is left visible; however, I believe that most users will employ this plugin on a contact page and, if disabling this plugin, will simply replace it with the new form plugin code. So the plugin can be twice as effecient by only looking for one thing instead of two.

= I have no idea what your last answer means. =

Don't worry about it.

= How do I use the sample CSS that is included with the plugin? =

Well, any way you want to. Any [Sandbox](http://www.plaintxt.org/themes/sandbox/ "Sandbox theme for WordPress") or Sandbox-based theme template should handle this form pretty well right out of the box, because it uses the same design patterns as the theme does for its comment form.

The sample CSS includes some nice features, like styles for the default response messages and error input fields. You could just add the following to your current theme's style.css file:

`@import url('../../plugins/easy-contact/sample/econtact-basic.css');`


You may have to adjust the the URL above to locate the `econtact-basic.css` file included with the plugin. But that's an easy way to see what the included style sheet does for you.

= I deactivated and then reactivated this plugin. Where did my settings go? =

They were deleted for ever and ever and ever. Upon deactivating, this plugins deletes all its stored data from your WordPress database. Tidy? Yes. A surprise just now? Definitely.

= One last question. Can I use `[easy-contact]` in multiple pages/posts/places? =

Yes. The shortcode will, however, produce exactly the same form based on the options set in the Easy Contact options menu. But you can use the shortcode in as many places as you like.

== Screenshots ==

1. Easy Contact options menu allows almost complete customization of email details and form content.
