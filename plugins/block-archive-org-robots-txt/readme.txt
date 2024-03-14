=== Block Archive.org via WordPress robots.txt ===
Contributors: apasionados
Donate link: https://apasionados.es/
Author URI: https://apasionados.es/
Tags: robots, robots.txt, crawler, robot, bot, seo
Requires at least: 4.0.1
Tested up to: 6.3
Requires PHP: 5.5
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Blocks the archive.org bots through the WordPress virtual robots.txt file.

== Description ==

This plugin adds lines to the virtual robots.txt file (that WordPress creates automagically if the file is not present physically on the server) to block the archive.org bots.

> Please be aware of what this plugin does exactly: If you activate the plugin it will add some lines to the robots.txt file to tell the [Archive.org bots](https://archive.org/) **not to crawl and index** your page. This means that they won't store a backup of your site and you won't be able to browse the different versions of the website through time.

> Please keep in mind that if you activate this plugin and your site is deleted from the archive.org index, you can't undo this. If you remove the plugin and the block of the archive.org bot, they'll start again crawling and indexing the website from that moment, but all the older information is lost. **Be carefull and think if this is what you want to do!**. If you mess it nobody will not be able to help you (neither we or archive.org).

**Please only activate this plugin if you know what you're doing**.

= What can I do with this plugin? =

This plugin adds the necessary lines to the virtual robots.txt file that WordPress creates automagically to block the archive.org bots.

= What ideas is this plugin based on? =

None. We needed a fast solution to add these lines to the robots.txt file and this was the solution we coded.

= System requirements =

PHP version 5.5 or greater.

= Block archive.org bots robots.txt Plugin in your Language! =
The first release is avaliable in English and Spanish. In the "languages" folder we have included the necessary files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please use the [native WordPress Translation](https://translate.wordpress.org/projects/wp-plugins/block-archive-org-robots-txt/) functionality.

New to Translating a plugin? First read through the [Translator Handbook](https://make.wordpress.org/polyglots/handbook/tools/glotpress-translate-wordpress-org/), then select your locale at [Translating WordPress](https://translate.wordpress.org/) and finally go to the [translation page for this plugin](https://translate.wordpress.org/projects/wp-plugins/block-archive-org-robots-txt/) to translate it.

= Further Reading =
You can access the description of the plugin in Spanish at: [Block archive.org bots robots.txt en espa&ntilde;ol](https://apasionados.es/blog/bloquear-el-bot-de-archive-org-a-traves-de-robots-txt-wordpress-plugin-7752/).

== Screenshots ==

1. Lines that the plugin adds to the virtual robots.txt file that WordPress creates.
2. Archive.org notification when a site blocks their bots.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel. There are no settings.

== Frequently Asked Questions ==

= Why did you make this plugin?  =

We created this plugin to be able to append the lines to block the archive.org bots via robots.txt without having to upload a robots.txt file.

= Does Block archive.org bots robots.txt make changes to the database? =
No. The plugin doesn't write any options or settings to the database.

= How can I check out if the plugin works for me? =
Install and activate. Have a look at the content of the robots.txt file in root of the domain.

= How can I remove Block archive.org bots robots.txt? =
You can simply activate, deactivate or delete it in your plugin management section. There are no options stored in the database so you can delete it also via FTP and everything will be removed.

= What happens to my website on archive.org =
If you activate the plugin it will add some lines to the robots.txt file to tell the [Archive.org bots](https://archive.org/) **not to crawl and index** your page. This means that they won't store a backup of your site and you won't be able to browse the different versions of the website through time. If your site is present on Archive.org and you activate this plugin, be aware that the Archive.org bots won't be able to access the site and in the end will delete it from their index. If this is not what you want to do, **do not activate this plugin**. 

= What happens if there is a physical robots.txt file on the server? =
**This plugin makes changes to the virtual robots.txt file generated automagically by WordPress and doesn't work with a physical robots.txt file**. In order to use this plugin you need to remove the physical robots.txt file from your server. Please delete the robots.txt file via FTP or Server Panel before using this plugin. **We check this on activation**. If we find a physical robots.txt file the plugin can't be activated until the file is removed. Please keep in mind that we only check it on plugin activation and after activation we don't check it any more; so if you upload a robots.txt file to the root of the domain once the plugin is activated, the plugin will have no effects but you will not receive a warning.

= What happens if WordPress is installed in a subdirectory? =
**WordPress must be installed in top-level directory of the web server.** Please note that the robots.txt must be in the top-level directory of your web server. If WordPress is installed in a subdirectory this plugin will not be effective because the robots.txt file generated by WordPress in the subdirectory will be ignored by the bots. Please note that we don\'t check this. You can read more about the robots.txt standard here [robotstxt.org: How to create a /robots.txt file and Where to put it](http://www.robotstxt.org/robotstxt.html).

= Are there any known incompatibilities? =
Please don't use it with *WordPress MultiSite*, as it has not been tested.

The plugin has similar functionality as the [Virtual Robots.txt](https://wordpress.org/plugins/pc-robotstxt/) and the [Better Robots.txt – Index, Rank & SEO booster](https://wordpress.org/plugins/better-robots-txt/) plugin; **both are not compatible with our plugin** as they remove all the virtual WordPress robots.txt content and create their own. The directives our plugin creates are not added to the robots.txt file these plugins generate as they don't use the standard functions of WordPress to append information to them.

= What does this plugin block exactly? =
* `User-agent: ia_archiver`
* `Disallow: /`
* `User-agent: archive.org_bot`
* `Disallow: /`
* `User-agent: ia_archiver-web.archive.org`
* `Disallow: /`

= Are there any server requirements? =
Yes. The plugin requires a PHP version 5.5 or higher and we recommend using PHP version 7.4 or higher. The plugin has been tested with PHP up to 7.4. When releasing this plugin [Wordpress recommends PHP 7.4 or higher](https://wordpress.org/about/requirements/).

= Do you make use of Block archive.org bots robots.txt yourself? = 
Of course we do. That's why we created it. ;-)

== Changelog ==

= 1.2.0 (04/04/2023) =
* Added Data Sanitization/Escaping according to WordPress Developer Guidelines

= 1.0.1 (25/08/2018) =
* Made some minor tweaks.

= 1.0.0 (08/08/2018) =
* First official release.

== Upgrade Notice ==

= 1.2.0 =
UPDATED: Added Data Sanitization/Escaping

== Contact ==

For further information please send us an [email](https://apasionados.es/contacto/index.php?desde=wordpress-org-block-archive-org-robots-txt).