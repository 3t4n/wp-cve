=== DoFollow Case by Case ===
Contributors: apasionados, netconsulting
Donate link: https://apasionados.es/
Tags: dofollow, nofollow, rel nofollow, comment, comments, link, links, seo, shortcode
Requires at least: 4.0
Tested up to: 6.4
Stable tag: 3.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

DoFollow Case by Case allows you to selectively apply dofollow to comments, either case by case or through a white-list of commenter’s emails.

== Description ==

**This WordPress plugin gives you the possibility to remove the "nofollow" attribute from your wordpress blog's comments**: from the author's links and/or from the comments text links. This can be done either case by case (editing each comment) or through a white-list of commenters emails, whose comments will allways be dofollow.

And don't forget:

> Don't use this plugin if you are using another plugin with similar funcionality. Please read the information about it in the [FAQ section](https://wordpress.org/plugins/dofollow-case-by-case/faq/ "FAQ section").

= What can I do with this plugin? =

This plugin allows you to set links in comments to be dofollow instead of nofollow. When editing a comment, now you have the option to remove the rel="nofollow" attributes from the links contained in them.
To make it easier, you can also setup commenters emails whose links in comments should always be dofollow and you can even set their Author URL when commenting to be dofollow.
On the other side you can also define URLs that when contained in a comment are always dofollow, so that you can setup links to your own sites to be always dofollow.

In order to add commenter's emails or URLs to the white list, please go to **DoFollow > DoFollow**.

**DoFollow > White List Email**: The Email White List contains a list of emails of commenters, whose links in comments are allways dofollow. And you can also choose to make the Author URL dofollow. By default the Author URL is not followed.
Here you can add for example the email addresses of your staff and collaborators.

**DoFollow > White List URL**: The URL White List contains a list of URLs that when linked to in a comment, are always dofollow, nevertheless who links to them.
Here you can setup for example links from your sites or from other sites.


= What ideas is this plugin based on? =

We were looking for a plugin like [Nofollow Case by Case](http://wordpress.org/plugins/nofollow-case-by-case/ "Nofollow Case by Case") but that worked the other way round. Instead of removing the re="nofollow" from all comments links and have the possibilty to add the rel="nofollow" case by case, we wanted to leave the rel="nofollow" and all comments and have the possibility to remove them only from some comments.

And the last plugin we liked is [Smart DoFollow](http://wordpress.org/plugins/smart-dofollow/ "Smart DoFollow") which lets you automatically give DoFollow links to authors of comments that are longer than a given number of chars. This is intersting, but very dangerous as today all comment spam is quite long and has many characters.

= DoFollow Case by Case Plugin in your Language! =
This first release is avaliable in English and Spanish. In the i18n we have included the necessarry files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](http://apasionados.es/contacto/index.php?desde=wordpress-org-dofollow-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [DoFollow Case by Case en castellano](http://apasionados.es/blog/dofollow-case-by-case-1676/).


== Installation ==

1. Upload the `dofollow-case-by-case` folder to the `/wp-content/plugins/` directory
1. Activate the DoFollow Case by Case plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the `DoFollow` menu that appears in your admin menu

Please note that the plugin should not be used together with other plugins with similar funcionalities like: [Nofollow Case by Case](http://wordpress.org/plugins/nofollow-case-by-case/ "Nofollow Case by Case"),[Nofollow Case by Case](http://wordpress.org/plugins/nofollow-case-by-case/ "Nofollow Case by Case"), [Ultimate Nofollow](http://wordpress.org/plugins/nofollow/ "Ultimate Nofollow"), [Nofollow Free](http://wordpress.org/plugins/nofollow-free/ "Nofollow Free") or [Smart DoFollow](http://wordpress.org/plugins/smart-dofollow/ "Smart DoFollow").


== Frequently Asked Questions ==

= What is Dofollow Case by Case good for? =
Links have a value. With Dofollow Case by Case you can decide yourself where the value goes.

= Why should I make use of the Dofollow Case by Case SEO Plugin? = 
If you work with WordPress you normally have no chance to decide yourself if a link should be a real link or not. All your comment links and comment author links are deactivated for search engines by default. But for good reasons you might want to punish only those links that really seem to be spam. To make it easier for you the plugin comes with nofollow links by default but you can follow the links that are valuable.

= Does Nofollow Case by Case make changes to the database? =
Yes. It creates a new table: "nodofollow".
* If you activate the plugin and the table doesn't exist, the plugin creates it.
* If you desactivate the plugin, the table isn't deleted.
* If you delete the plugin, the table is deleted.

= How can I check out if the plugin works for me? =
After activating Dofollow Case by Case visit a post with comments and have a look into the source code of that page. All your comments should have rel="nofollow" or rel="external nofollow" attributes. 

Now visit your admin page and edit one of the comments for testing purposes: Check the dofollow checkbox.

Visit your post and make sure that every link works fine. Have a look into the source code again. Your modified comment links should now be follow links. All the other links in comments should be left as nofollow links. 

= Something seems not to work as expected. Why not? =
The primary job of Dofollow Case by Case is to find and replace variants of rel="nofollow". For this to work we need a proper link that can be analyzed and replaced. WordPress has several functions included. Some deliver a URL only, other functions deliver a complete link including an anchor, a link text and so on. 

Theme developers and plugin authors can choose from all these functions to include some links into the system whereever this makes sense. Dofollow Case by Case is normally not able to modify a single URL. This can not be modified by PHP directly because it hooks into other functions somewhere else (later). Those functions can be modified but not the original input, not the "comment author url" itself. Whenever possible you should use a standard function like "get_comment_author_link" to receive a complete link in your template that later can easily be modified with standard functions of Dofollow Case by Case. Let us know if you have issues. 

= How can I remove Dofollow Case by Case? =
You can simply activate, deactivate or delete it in your plugin management section.

= Are there any known incompatibilities? =
**The plugin should not be used together with other plugins with similar funcionalities** like: [Nofollow Case by Case](http://wordpress.org/plugins/nofollow-case-by-case/ "Nofollow Case by Case"),[Nofollow Case by Case](http://wordpress.org/plugins/nofollow-case-by-case/ "Nofollow Case by Case"), [Ultimate Nofollow](http://wordpress.org/plugins/nofollow/ "Ultimate Nofollow"), [Nofollow Free](http://wordpress.org/plugins/nofollow-free/ "Nofollow Free") or [Smart DoFollow](http://wordpress.org/plugins/smart-dofollow/ "Smart DoFollow"). This list is not complete because there are many plugins with similiar functionalities in the [plugin directory](https://wordpress.org/plugins/search.php?type=term&q=nofollow&=Search+Plugins "plugin directory").

If you find incompatibilities with other plugins (that do not have similar funcionalitites as this one), please let us know.

= Can I update WordPress without problems while using your plugin? = 
> **This plugin changes core WordPress functionality** (i.e. modifies raw JS in js/wplink.js) in a way that is not modular and **may break after major WordPress updates**.

> Please do not update WordPress to a major version until the plugin has been tested with it and the "tested up to" tag has been updated. **You can always apply the security and other minor updates**.

= Do you make use of Dofollow Case by Case yourself? = 
Of course we do. ;-)

== Screenshots ==

1. The "DoFollow Case by Case" main screen. Here you can add email addresses of commenters and URLs to the white lists.
2. Email White List. Here you can edit the list of Email Addresses of the commenters whose comments are allways dofollow.
3. URL White List. Here you can edit the list of URLs that are allways dofollow.
4. Edit comment. Where you can edit a comment and make the links of the comment dofollow.
5. Insert link pop-up. Pop-up window for link insertion in a post or page where we added the option to make it nofollow.
6. Shortcode for making a link of a post or page nofollow.


== Changelog ==

= 3.5.1 (12dec2023) =
* Solved warnings in PHP 8.x - Trying to access array offset on value of type null in lines 557, 589, 592 & 595.

= 3.5.0 (22sep2023) =
* Security update. Cross Site Request Forgery (CSRF) discovered by Skalucy and notified by patchstack.com.

= 3.4.2 (01sep2023) =
* Removed functionality to mark a link in a post or page as nofollow as this is included in Gutenberg by default. Removed shortcode to mark a link as nofollow.

= 3.4.1 =
* Updated the information regarding text domain and language folder.

= 3.4 =
* Updates to make the plugin compatible with PHP 7.0.x. Thanks to Geoffrey for pointing out the problem and the solution.

= 3.3 =
* Updated all CSS styles of the plugin to make them completly independent of the default WordPress styles. In WordPress 4.4 there were some problems with the default styles; now they are solved.

= 3.2 =
* Upgraded menu and submenu declarations. The deprecated level argument has been replaced by 'capacity'. Thanks to Geoffrey for pointing this out.

= 3.1 =
* Fixed compatibility issue with the insert "rel=nofollow" link in some WordPress 4.3 installations (update of the Javascript library js/wplink.js).

= 3.0 =
* Added compatibility to WordPress 4.2.1 by updating the insert link Javascript library wplink.js.

= 2.9 =
* Minor changes of the CSS file related to problems with postbox style.

= 2.8 =
* Added compatibility to WordPress 3.9.1 (JavasScript error: ReferenceError: tinyMCEPopup is not defined) by updating the insert link Javascript library wplink.js and the main plugin file.

= 2.7 =
* Added compatibility to all Genesis Framework 2 themes, that use the comment_text function, instead of the get_comment_text function.

= 2.6 =
* Following the request of ParkerWest we have changed the behaviour of the plugin when an AUTHOR doesn't include a URL when commenting. Now if the AUTHOR of a comment doesn't include a URL when commenting, the AUTHOR name is not linked any more to the comment and in this case the AUTHOR name has no link.

= 2.5 =
* Change of function name: "pagination" to "pagination_limit" to prevent conflicts with some themes. These themes use the "pagination" function for the blog pagination and when our plugin was installed, it didn't show pagination at all.

= 2.4 =
* Upload of missing images from administration layout.

= 2.3 =
* Upload of missing images from administration layout.
* Style updates to plugin administration layout.

= 2.2 =
* Spanish translation update.

= 2.1 =
* Style updates to plugin administration layout.
* Better organization of the administration layout.
* New plugin administration header.
* New button layout.
* Better pagination within the plugin administration.

= 2.0 =
* Security update. Added security to database calls.
* Changed database calls of functions: pagination and pagination_href.
* Added security to functions: listWhiteDofollow, cont_config_sub_NDF_email, con_config_sub_NDF_url, nox_inner_custom_box, update_comment, remove_dofollow_author, remove_dofollow_comment.

= 1.3 =
* Maintenance update to syncronice Subversion repository so that WordPress shows updated readme.txt file. Sorry for the inconvenience.
* Added the possibility to access the plugins settings from the WordPress plugin screen.
* Added tested compatibility up to WordPress 3.6.1.

= 1.2. =
* Updated to 1.2 because we didn't change version in plugin main file. Sorry for the inconvenience.
* Updated and shortened description in the plugin that show on the plugin repository in the heading.

= 1.1 =
* Update of readme.txt file, changing spanish characters in link to post in Spanish.
* Added tested compatibility up to WordPress 3.6.

= 1.0 =
* First stable release.

= 0.5 =
* Beta release.

== Upgrade Notice ==

= 3.5.1 =
UPDATE: Solved warnings in PHP 8.x

== Contact ==

For further information please send us an [email](http://apasionados.es/contacto/index.php?desde=wordpress-org-dofollow-contact).
