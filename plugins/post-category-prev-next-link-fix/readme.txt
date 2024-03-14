=== Post Category Prev-Next Link Fix ===
Contributors: Ketanambaliya
Tags: 404, not found, prev-next, category pagination
Requires at least: 3
Tested up to: 6.4.2 
Requires PHP: 5 
Stable tag: 1
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Fixes the bug in WordPress 6.4.2 in category listing page pagination. When you are using permalink structure as %category%/%postname% , second page URL of category listing page will be category-name/page/2 which WordPress identify \\\"page\\\" as post name. And will return 404 error page. This plug-in will fix the issue. This also fix the next and previous buttons bug in while using custom permalink structure. This is also remove category word from the url without using categorybase.

== Installation ==
1. Upload `post-category-prev-next-fix` to the `/wp-content/plugins/` directory
2. Activate the plugin through the \\\'Plugins\\\' menu in WordPress
3. You are done

== Frequently Asked Questions ==
= Do I need to do some settings change in WordPress backend? =
No, this is just a bug fixing. You have nothing to do with it