=== Sortable Word Count Reloaded ===
Contributors: apasionados
Donate link: https://apasionados.es/
Author URI: https://apasionados.es/
Tags: admin, column, word count, posts, pages, sortable, count, words
Requires at least: 4.0.1
Tested up to: 6.3
Requires PHP: 5.6
Stable tag: 1.0.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds a sortable column to the posts and pages admin list with the word count of each page/post.

== Description ==

Adds a sortable column to the posts and pages admin list with the word count of each page/post.

With this plugin you can see the word count for the posts and pages in the list view and sort them.

This plugin is an enhanced version of the plugin [Sortable Word Count](https://wordpress.org/plugins/sortable-word-count/). We decided to create this reloaded version of the plugin, because we wanted to be able to translate it and change the word count function so that it gets the correct word count by filtering comments and other page builder code.

= What can I do with this plugin? =

The plugin adds a sortable column to the posts and pages admin list with the word count of each page/post.

= System requirements =

PHP version 5.6 or greater.

= Sortable Word Count Reloaded Plugin in your Language! =
This first release is avaliable in English and Spanish. In the "languages" folder we have included the necessary files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](https://apasionados.es/contacto/index.php?desde=wordpress-org-sortable-word-count-reloaded-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [Columna palabras ordenable | WordPress Plugin](https://apasionados.es/blog/).

== Screenshots ==

1. Plugin in action.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel. There are no settings.

== Frequently Asked Questions ==

= Why did you make this plugin?  =
We love the "Sortable Word Count" plugin but it didn't have some of the funcionality we wanted so we decided to create this reloaded version of the plugin. This plugin can be translated and the word count function has been changed so that it gets the correct word count by filtering comments and other page builder code.

= Do you remove the page builder code from the word code? =
This is one of the reasons why we released this reloaded version of the plugin. We exclude the Gutenberg code and the page builder (Elementor, Beaver Builder, Visual Composer, etc) code from the word count.

= Does Sortable Word Count Reloaded make changes to the database? =
Yes. The plugin adds an option to the database: 'apa_swcr_option_word_count'. This option is deleted when the plugin is deactivated.

= How can I check out if the plugin works for me? =
Install and activate. Go to the posts or page list in the adminitration. There you will find a new column with the word count.

= How can I remove Sortable Word Count Reloaded ? =
You can simply activate, deactivate or delete it in your plugin management section. If you delete the plugin through FTP the option 'apa_swcr_option_word_count' is not deleted.

= Are there any known incompatibilities? =
Please don't use it with *WordPress MultiSite*, as it has not been tested.

= Which PHP version do I need? =
This plugin has been tested and works with PHP versions 5.6 and greater. WordPress itself [recommends using PHP version 7.3 or greater](https://wordpress.org/about/requirements/). If you're using a PHP version lower than 5.6 please upgrade your PHP version or contact your Server administrator.

= Are there any server requirements? =
Yes. The plugin requires a PHP version 5.6 or higher and we recommend using PHP version 7.3 or higher. The plugin has been tested with PHP up to 7.3.

= Do you make use of Sortable Word Count Reloaded yourself? = 
Of course we do. That's why we created it. ;-)

== Changelog ==

= 1.0.3 (19/mar/2019) =
* First official release.

== Upgrade Notice ==

= 1.0.3 =
First release.

== Contact ==

For further information please send us an [email](https://apasionados.es/contacto/index.php?desde=wordpress-org-sortable-word-count-reloaded).