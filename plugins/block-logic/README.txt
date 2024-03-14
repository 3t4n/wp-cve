=== Block Logic - Full Gutenberg Block Display Control ===
Contributors: landwire
Tags: conditional, conditional logic, visibility, block, conditions
Requires at least: 5.0
Tested up to: 6.3.1
Stable tag: 1.0.8
Requires PHP: 5.6

Block Logic adds a "Block Logic" field to the block editor, that lets you show or hide any block based on conditions.

== Description ==
Block Logic adds a "Block Logic" field to the "Advanced" section of the block editor (i.e Gutenberg), that lets you show or hide any block based on conditions. You can use WordPress' [Conditional Tags](http://codex.wordpress.org/Conditional_Tags) or any general PHP code.

= Show or hide blocks based on =
* User role
* User login status
* Post status
* Date and time
* The result of a custom PHP function

= Features =
* Show or hide any block using conditions
* Combine conditions with “and” or “or” operators. See FAQ Writing Logic Code
* Full flexibility: use any condition you want

= Limitations =
Does not work with the Classic Block, Widget Block or Widget Area Block ['core/freeform', 'core/legacy-widget', 'core/widget-area'], as the those blocks do not support block attributes. Does also not work with the HTML Block ['core/html'] inside the Widget Screen, as this one also does not support block attributes there.

= Configuration =
Just activate the plugin. The "Block Logic" textbox will then appear in the "Advanced" section of the Gutenberg editor.

== Frequently Asked Questions ==

= Writing Logic Code =
Make good use of [WP's own conditional tags](http://codex.wordpress.org/Conditional_Tags). You can vary and combine code using:

* `!` (not) to **reverse** the logic, eg `!is_home()` is TRUE when this is NOT the home page.
* `||` (or) to **combine** conditions. `X OR Y` is TRUE when either X is true or Y is true.
* `&&` (and) to make conditions **more specific**. `X AND Y` is TRUE when both X is true and Y is true.
* `is_home()` -- just the main blog page
* `!is_page('about')` -- everywhere EXCEPT this specific WP 'page'
* `is_user_logged_in()` -- shown when a user is logged in
* `is_category(array(5,9,10,11))` -- category page of one of the given category IDs
* `is_single() && in_category('baked-goods')` -- single post that's in the category with this slug
* `current_user_can('level_10')` -- admin only blocks
* `strpos($_SERVER['HTTP_REFERER'], "google.com")!=false` -- blocks to show when clicked through from a google search
* `is_category() && in_array($cat, get_term_children( 5, 'category'))` -- category page that's a descendent of category 5
* `global $post; return (in_array(77,get_post_ancestors($post)));` -- WP page that is a child of page 77
* `global $post; return (is_page('home') || ($post->post_parent=="13"));` -- home page OR the page that's a child of page 13

Note the extra ';' on the end where there is an explicit 'return'.

= The 'block_logic_eval_override' filter =
Before the Block Logic code is evaluated for each block, the text of the Block Logic code is passed through this filter. If the filter returns a BOOLEAN result, this is used instead to determine if the block is visible. Return TRUE for visible.

= With great power comes great responsibility =
The block logic you introduce is EVAL'd directly. Anyone who has access to use the Gutenberg Editor will have the right to add any code, including malicious and possibly destructive functions. There is an optional filter 'block_logic_eval_override' which you can use to bypass the EVAL with your own code if needed.

* I'm getting "PHP Parse error… … eval()'d code on line 1"

You have a PHP syntax error in one of your block's "Block Logic" fields. Review them for errors.

== Screenshots ==

1. The 'Block logic' field at work in the block editor.

== Changelog ==

= 1.0.8 =
* recompiled assets to remove console.log

= 1.0.7 =
* added logic indicator to mark blocks that have logic applied

= 1.0.6 =
* added check for Classic Block, Widget Block or Widget Area Block ['core/freeform', 'core/legacy-widget', 'core/widget-area'], as those do not support block attributes
* added limitations to plugin description
* updated dev dependencies

= 1.0.5 =
* added check for Classic Block and disabled display of settings there

= 1.0.0 =
* Initial Release of the plugin

== Upgrade Notice ==

Nothing to consider.
