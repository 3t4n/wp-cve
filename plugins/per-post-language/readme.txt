=== Per Post Language ===
Author: Fahad Alduraibi
Author URI: http://www.fadvisor.net/blog/
Contributors: fduraibi
Tags: ppl,multilingual,multilanguage,language,languages,translation,post,posts,page,pages,single,locale,rtl,ltr
Requires at least: 4.0.0
Tested up to: 4.5.2
Stable tag: 1.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin allows the user to set the blog language per post or page while having a default blog language.

== Description ==

**Do you write posts in different languages?  
Would you like your blog theme language to be the same as the language in which your wrote your post or page?**

With this plugin you can set the language of your blog per post and also per page, that is if your blog is in English and you wrote a post or a page in Spanish or Arabic you can set the language of your blog to match that post's or page's language, so when your visitors open that post or page they will see your blog in that language. You don't have to translate your theme and plugins if they come with translations and even it will change the direction of the view to RTL (Right-to-Left) if your language is RTL based.

* The plugin does not provide any translations, it only changes the themes and plugins translation language if they provide there own translations. 
* The available languages are only the languages that WordPress already recognizes and supports.
* The plugin only works for posts and pages, the front page will show in the blog default language.

== Installation ==

1. Search for the plugin from within the blog plugin admin page, or manually upload the plugin folder to the `/wp-content/plugins/` directory.
2. Install and Activate the plugin.
3. Go to Settings -> "Per Post Language" and add your languages.

To use it simply go to the post edit page and select the language from the "Post Language" box.

IMPORTANT: If you use server caching or have a caching plugin, you might need to delete all caches after installing or updating this plugin.

== Frequently Asked Questions ==

= Q: I installed this plugin but my theme or plugin still shows in the default blog language, why? =
Check if your theme or plugin has support for your language. Also if switching to RTL language make sure your theme has RTL support. (All WordPress default themes has support for LTR & RTL and translations for many languages)
   
= Q: I use a plugin which has support for my language but that plugin still shows in the default language? =
Some plugins are developed to load the translations very early and before the post is loaded so they only see the default language. Contact the plugin developers or create a support ticket and ask them to delay the loading of the translations, also send them this link: http://geertdedeckere.be/article/loading-wordpress-language-files-the-right-way

== Screenshots ==

1. The settings page of the plugin where you add needed languages.
2. The "Post Language" box is used to set the post language from the post edit page. 
3. The home page shows in the blog default language.
4. Example of a post in Dutch (Deutsch)
5. Example of a post in Azerbaijan (گؤنئی آذربایجان)
6. Example of a post in Arabic (عربي)
7. Example of a post in Brazilian Portuguese (Português do Brasil)
8. Example of a post in Spanish (Español)

== Changelog ==

= 1.3 =
* Set the language direction of the editing page for posts and pages if your language is RTL, that can be done by setting the direction of your language from settings page. The direction only effect the title and the post body, the rest follows the main default blog language direction.

= 1.2 =
* Now you can set the language for Pages also.
* Language translation fix to be compatible with WordPress translation service
* A visual notifications for language download status.

= 1.1 =
* Bug fix, make the plugin react only on posts and not pages.

= 1.0 =
* Initial release.
