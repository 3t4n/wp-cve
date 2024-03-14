=== Improved Simpler CSS ===
Contributors: enej, ctlt-dev, oltdev
Tags: css, wpmu, appearance, themes, custom css, edit css, live edit css, revisions css, custom post type
Requires at least: 3.0
Tested up to: 3.6-beta

Add the ability to add css to your existing style sheet. 

== Description ==

An easy way to modify the css globally on your site. 

As an admin you are able to edit the css from the front end. 



== Installation ==
1. Default WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Why isn't the code showing up on the blog? =
Remember that this plugin depends on standard WordPress hooks to operate. If the
active theme does not have `wp_head()` in its code, this plugin is ineffective.
*Remedy:* add the code `<?php wp_head(); ?>` to the theme files in the `<head>` section.

= Why can't I add JavaScript to the blog's code? =
This plugin will only operate for Cascading Style Sheets code. The custom CSS is escaped
and outputted within a set of `<style>` tags, preventing bots from abusing the functionality
to inject malicious code. Allowing users to inject JavaScript into the blog's header
is a security vulnerability, thus this plugin does not permit it.


== Screenshots ==
1. The menu item as it appears under the Appearance menu.
2. Live Edit CSS
3. OLD Edit Screen 
4. Revisions Screen 

== Changelog ==

= 2.0.2 =
Minor bug fixes

= 2 = 
Completely rewritten 


= 1.0 = 
* custom css stored in post tabe as a content type
* custom css revisions are enabled
* improved custom css  