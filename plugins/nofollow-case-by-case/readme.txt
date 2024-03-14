=== Nofollow Case by Case ===
Contributors: fob
Donate link: none
Tags: comments, nofollow, dofollow, links, administration
Requires at least: 2.0
Tested up to: 3.8
Stable tag: 1.5.6
License: Compatible to GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html


"Dofollow" but Nofollow Case by Case allows you to selectively apply nofollow to your comments as well.


== Description ==

For unmodified links Nofollow Case by Case works like a (do)follow plugin for WordPress. It simply removes rel="nofollow" from your comment links.

Whenever you get the feeling that one of your comment links might be able to harm your own website reputation, if you think about SEO and found a link that looks a bit like spam but should not be deleted, you can replace nofollow to every single comment link at any time. If you want to replace nofollow for a link like that you simply add /dontfollow at the end of the link. You can do this for author links in comments as well as for the links you find in the text.

= This is what the plugin will do automatically: =

**1. Clean up some code:**
It tries to remove all variants of rel="nofollow", target="blank" and rel="external" from comment author links and other links in comments first. A link will become a "real link" no matter if it is a pingback, a trackback or a "real comment".

**2. Fix semantical incorrectness:**
The plugin now applies rel="external" - but ONLY for external links!

**3. jQuery functions of Nofollow Case by Case:**

a) The plugin detects rel="external" in comments and opens those links in a new window. 
No need for target="_blank" which would not validate in XMTML. jQuery replacement does. 

b) The plugin tries to correct author urls that could not be fully replaced before.
This has something to do with template development. Find more information on this within the FAQ.


= Add on =

You can try this [NFCBC SEO Plugin ADD-on](http://wordpress.org/extend/plugins/nfcbc-seo-plugin-add-on/ "NFCBC SEO Plugin Add-on") for easier comment management. The plugin is an old one using javascript but still works. I currently do not have the time to rebuild it. A new version should later support Ajax and nonces and on the other hand will not support old versions of WordPress anymore. 

= Alternative Tool =

[NFCBC SEO Light](http://wordpress.org/extend/plugins/nfcbc-seo-light/ "NFCBC SEO Light") could be used alternatively. It suggests "follow" or "nofollow" or "no link" automatically, depending on comments length. This plugin might be updated soon. I personally prefer Nofollow Case by Case.

= Need more information? =

I have updated the [FAQ for Nofollow Case by Case](http://wordpress.org/extend/plugins/nofollow-case-by-case/faq/ "FAQ for Nofollow Case by Case") and the German description can be found at the old place as well: 

* [Deutsche Plugin-Beschreibung auf fob-marketing.de](http://www.fob-marketing.de/marketing-seo-blog/wordpress-nofollow-seo-plugin-nofollow-case-by-case.html "Nofollow Case by Case SEO Plugin")
* [Deutsche FAQ auf fob-marketing.de](http://www.fob-marketing.de/marketing-seo-blog/antworten-zu-nofollow-case-by-case-und-relexternal-nofollow.html "FAQ bei fob marketing")


== Installation ==

Simply search for this plugin from the plugins section of your blog admin, use automatic install and activate Nofollow Case by Case.

OR

1. Download the plugin here.
2. Unzip the plugin folder.
3. Put the complete folder into your plugin folder of WordPress (e.g. into the `/wp-content/plugins/` directory). 
4. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. This picture shall give you an idea about Nofollow Case by Case. "Type" is what you do in your WordPress Admin within the comment moderation section. "Get" is what  will happen on your website (front end) after saving the comment. If you do nothing you will have a dofollow plugin without any nofollow links in comments.

== Changelog ==

= 1.5.6 =
* Plugin compatibility check for WordPress 3.8 (RC2).

= 1.5.5 =
* Bug fix for WordPress versions between 3.5 and 3.6 (3.5.1 and 3.5.2).

= 1.5.4 =
* Added support for WordPress 3.6 (Sorry for being late with this. I have been informed about issues with line breakes in WP 3.6 today. Fixed it asap.)

= 1.5.3 =
* Added support for avatar links of the Hybrid Theme Framework

= 1.5.2 =
* This is mainly a performance update. I changed the callback (regex) for two reasons: 
* Allow upper and lower case letters in URL replacements (for better compatibility with upper case configurations) and 
* allow optimization of the included callback function used to replace rel="external" (because str_replace seems to do the job up to two times faster than preg_replace here).
* Pushed back rel="external" to the end of link anchors by the way and cleaned up some more code. 
* Included deeper support for the mix of single and double quotes in link replacements, depending on WordPress functions used. The plugin now fills in rel='external' for single quoted links (if it is an external link) and rel="external" for links that originally were prepared with double quotes.

= 1.5.1 =
* Minimalistic update for people who might have rel='nofollow external' in use.

= 1.5 =
* Applied a central callback function for solid output cleaning.
* Plugin now strips /dontfollow even from visable links within links, if a http URL like "www.xyz.com/dontfollow" has been put directly into the comment text.
* Provided a fallback for outdated versions of WordPress (version number < 3.4)

= 1.4 =
* Stronger testing, bugfix and better filtering
* Renewed function for replacement of comment_author_link and comment_author_url_link
* Plugin now removes /dontfollow even from visible links loaded by comment_author_url_link function

= 1.3 =
* New code.
* Separately filter and explain input and output.
* Do not replace the standard make clickable function of WordPress anymore.
* Find and replace results instead.
* Try to support more themes with stronger template cleaning function.
(Search and replace more alternatives of rel="external", rel="nofollow", target = "_blank", ...)
* Use str_replace if there is no need for preg_match.
* Add JavaScript/jQuery fix for urls that can not be modified (see FAQ).
* Add JavaScript/jQuery for XHTML valid new window option (use rel="external" instead of target="_blank").
* Use scripts after page load (restricted to posts with comments)
* And last but not least: Semantically make sure that WordPress can distinguish between internal and external comments!
* Update FAQ
* Update Plugin description
* Update license to GPL2.

= 1.2.0.1 =
* Small fix.

= 1.2 =
* Introduce target="_blank" option.

= 1.1 =
* Validate with new version of WordPress.

= 1.0 =
* First version of Nofollow Case by Case.


== Frequently Asked Questions ==

= What is Nofollow Case by Case good for? =
Links have a value. With Nofollow Case by Case you can decide yourself where the value goes. A nofollow link normally costs you as much as a follow link. So why should you kill the value of your links?

= Why should I make use of the Nofollow Case by Case SEO Plugin? = 
If you work with WordPress you normally have no chance to decide yourself if a link should be a real link or not. All your comment links and comment author links are deactivated for search engines by default. But for good reasons you might want to punish only those links that really seem to be spam. To make it easier for you the plugin comes with follow links by default. So you only have to do anything if a link does not look very well for you.

= What is the difference between Nofollow Case by Case and a normal Dofollow Plugin ? =
A Standard Dofollow Plugin normally brings you the opposite of the WordPress included functions. It simply removes all nofollow links and does not give you any possibility to do this case by case. 

= Why should I use a mix of follow and nofollow? =
The internet is a network giving you a opportunity to vote for everything you like. In my opinion you should do that. But links are only helpful for people if you make use of real links. On the other hand you should be very careful what you vote for. Voting with a link is a recommendation for search engines for a better rank of the page you linked to within the search engine results. If search engines recognize that you vote for a lot of spam pages you run into the risk to lose your own trust rank. This is why you better should make use of nofollow "case by case". 

**Do you want other Blogs to link to your website?**
If you automatically flag their links like spam it will be very unattractive for other bloggers to send you a (trackback) link. If they find a better ressource they might want to link to that one instead of yours. In this case you could be out of the game. With comment links it is still the same. Do you want to punish all your friends? Instead of that you should better make use of Nofollow Case by Case. ;-) 

= Why do people call Nofollow Case by Case a SEO Tool? = 
If you find comments within your blog that obviously come from a spammer who only wanted to grab a link for better search engine results you might want to do one of the following: 

- Delete this comment if it is a bad one (e.g. hardcore spam, viagra...). 
- Flag it with nofollow if someone added a bad link to a good comment. 
- Choose follow (do nothing) if you like the comment as well as the link. 

Following these rules you will help yourself, help your friends and help the search engines being able to provide better search engine results for everyone. Providing good content and sorting out bad links should be the best you can do even for the reputation of your own website. Have fun! 

= Could I also add nofollow to trackback spam? =
Oh yes. You can! If someone links you with nofollow you can modify his trackback link and do the same for him.

= Does Nofollow Case by Case make changes to the database? =
The plugin has pre save filters for new comments (input) and other filters that work on the fly (to validate already existing output). At this stage the plugin does not create any database entries itselt. If you modify links with /dontfollow these links will be stored at the same place where the commenter left his link. If I decide to create an options page for this plugin there will also be a plugin remove function for those options. Until now I did not want to blow up the database. For that reason we also just change links instead of saving additional link information for every single comment.

= How can I check out if the plugin works for me? =
After activating Nofollow Case by Case visit a post with comments and have a look into the source code of that page. From now on all your comment links should be nofollow free. So you should not find any rel="nofollow" or rel="external nofollow" anymore. 

Now visit your admin page and edit one of the comments or simply reply to an existing comment for testing purposes:

Edit /dontfollow to a comment author link and insert one or two links into the comment. One of them should be modified with /dontfollow, too. Modified links look like this: "http://www.example.com/dontfollow". If you want to leave a slash for the original link you can edit //dontfollow to the link. 

Visit your post and make sure that every link works fine. Have a look into the source code again. Your modified links should now be nofollow links, /dontfollow should have been removed everywhere on the fly to make things work. All the other links in comments should be left as follow links where rel="nofollow" has been removed on the fly. 

If you are a professional developer you might want to have a look at all places of the website where comment links are used and verify that replacement works properly on every template used. If you comment out the jQuery script of Nofollow Case by Case you should normally not find any /dontfollow links left in the frontend. Otherwise you might want to install another Wordpress function for the output of that link or enhance the reach of the included jQuery fix. Currently it is loaded only on posts that have comments to verify the output, for example if avatars are used with additional links that can not be found and replaced from the plugin itself because the avatar function includes just a URL instead of a link. You might prefer to use "get_comment_author_link" or "get_comment_author_url_link" instead of "get_comment_author_url" or provide a hook that can be added to Nofollow Case by Case filters. From a SEO point of view you might also think about lowering the number of outgoing links if there are too many of them in place for each single comment. 

= Something seems not to work as expected. Why not? =
The primary job of Nofollow Case by Case is to find and replace all variants of rel="nofollow". For this to work we need a proper link that can be analyzed and replaced. WordPress has several functions included. Some deliver a URL only, other functions deliver a complete link including an anchor, a link text and so on. 

Theme developers and plugin authors can choose from all these functions to include some links into the system whereever this makes sense. Nofollow Case by Case is normally not able to modify a single URL. This can not be modified by PHP directly because it hooks into other functions somewhere else (later). Those functions can be modified but not the original input, not the "comment author url" itself. Whenever possible you should use a standard function like "get_comment_author_link" to receive a complete link in your template that later can easily be modified with standard functions of Nofollow Case by Case. But since version 1.3 Nofollow Case by Case comes with jQuery functions that should fix accidental links like "www.example.com/dontfollow" in a second process (after page load) so that every accidental link should be fixed automatically before a user can click on it. You can play with "nfcbc_external_links_script" at the end of the plugin to either allow a script insert in archives or completely deactivate the script to see where exactly you have issues. A plugin might have a hook that can be used to solve the problem. Let me know if you have issues. 

= How can I remove Nofollow Case by Case? =
You can simply activate, deactivate or delete it in your plugin management section.

= How can I remove link modifications? =
If you deactivate or uninstall Nofollow Case by Case you will automatically fall back on WordPress plus theme and plugin developer replacements for nofollow. The  backfill mix (existing variants of "rel=external", "rel="nofollow" and target="_blank" options will immediately be back, even for your own comments. WordPress will filter follow comments from the database automatically and add nofollow where it does not exist on the fly. 

You only will have to remove your own excecptions by yourself. You can do this one by one in your WordPress admin, you can try a comment moderation tool like "NFCBC SEO Plugin add-on" or simply find and replace all /dontfollow entries in the database directly. The most comfortable tool I know for this job is the plugin "[Search and Replace](http://wordpress.org/extend/plugins/search-and-replace/ "Search and Replace")" by Frank Bültge. Simply install it, select all comment moderation fields, search for /dontfollow and let the job be done in seconds. BUT BE CAREFUL. There will be no undo option. If you are not sure you should better think twice before you decide to switch back to a "nofollow blog".

= Do you provide any other help for search engine optimization ? =
Yes, I do. I am a consultant and a developer for Marketing and SEO things. If you speak a bit German you can find some more information within my own [blog](http://www.fob-marketing.de/marketing-seo-blog/ "fob marketing seo blog"). There has also been a little discussion about [nofollow](http://www.fob-marketing.de/marketing-seo-blog/herzlichen-glueckwunsch-nofollow.html "nofollow") some time ago.

= Do you make use of Nofollow Case by Case yourself? = 
Yes, I do. ;-)


== Contact ==

For further information please send me an [email](http://www.fob-marketing.de/fob/ueber-fob-marketing/fob-marketing-kontakt/ "Send an email to Oliver Bockelmann").
