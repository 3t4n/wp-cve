=== Conditional Tags Shortcode ===
Contributors: shazdeh
Plugin Name: Conditional Tags Shortcode
Tags: shortcode, context, conditional, conditional-tags, page
Requires at least: 3.1
Tested up to: 5.4.1
Stable tag: 0.2

A shortcode to display content based on context.

== Description ==

With this shortcode you can take control of where the content is displayed.

= Usage =
You can use all the <a href="http://codex.wordpress.org/Conditional_Tags">conditional tags</a> WordPress provides. Checkout examples below.

Show text only on the homepage:
<code>[if is_front_page] The text [/if]</code>

Show text only on the About page of the site:
<code>[if is_page="about"] The text [/if]</code>

Show only on the category archive view:
<code>[if is_category] The text [/if]</code>

You can add "not_" before the conditional tag to reverse the logic, example:
Show on all pages of the site except the homepage:
<code>[if not_is_front_page] The text [/if]</code>

= OR =

Using multiple parameters, the content is displayed when either of the conditions are met ("OR" comparison), for example, show text on both category and tag archive pages:
<code>[if is_category is_tag] The text [/if]</code>

= AND =

To set multiple conditions you can nest the shortcode, for example show text only on homepage AND if the user is logged in:
<code>[if is_user_logged_in][if is_front_page] The text [/if][/if]</code>

Show a link to wordpress.org site, only on single post pages and only on mobile devices:
<code>[if wp_is_mobile][if is_single] <a href="http://wordpress.org/">WordPress</a> [/if][/if]</code>

= has_term_{taxonomy} =

You can use this to check if the current post in the loops belongs to a custom term in the desired taxonomy. See: https://codex.wordpress.org/Function_Reference/has_term

Example, check if current post has the "jazz" term in the "genre" taxonomy:
<code>[if has_term_genre="jazz"] The text [/if]</code>

== Installation ==

1. Upload the `menu-item-visibility` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!