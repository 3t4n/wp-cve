=== PostLists ===
Contributors: reneade
Donate link: http://www.rene-ade.de/stichwoerter/spenden
Tags: post, posts, page, categories, category, navigation, archive, placeholder, placeholders, lists, list, postlists
Stable tag: trunk
Requires at least: 2.2
Tested up to: 2.99999

This WordPress plugin provides placeholders for configurable dynamic lists of posts. The placeholders can be used in posts, pages or widgets, and will get replaced to the list of posts. You can define the placeholders yourself and configure the list that should replace it.

== Description ==

This plugin is exactly what you need if you like to include a list of posts (latest posts, most commented posts, future posts, ...) in a page, post or widget very easy! This WordPress plugin provides placeholders for configurable dynamic lists of posts. The placeholders can be used in posts, pages or widgets, and will get replaced to the list of posts. Of course, it is also possible to use the lists directly in your template files. You can define the placeholders yourself and configure the list that should replace it.

You can define multiple placeholders like %MYPLACEHOLDER%. 
For each placeholder you can configure the list that replaces the placeholder if the page/widget/post gets viewed. 
You can decide to list posts of a specific category, author or tag. You can configure how to order them or what status the post has to have for example. Of course you can set the maximum number of posts to display.
You can configure the html output for before and for after the list, customize the html string for each entry of the list, and set a string to display if there are no posts.
In these strings you can use placeholders. For example: %count%, %numberposts%, %counter%, %postid%, %posttitle%, %posturl%, %author%, %date%, %time%, %modifieddate%, %modifiedtime%, ... (case sensitive!)

Plugin Website: http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html
Comments are welcome! And of course, I also like presents of my Amazon-Wishlist or paypal donations to finance my website. :-)

== Installation ==

1. Upload the folder 'postlists' with all files to '/wp-content/plugins' on your webserver
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Manage" and then to the new submenu "PostLists" to manage your placeholders and lists

Add a list of posts to a post, page or widget:
  - Type one or more of your placeholders in the content of a post, page or textwidget (exactly the way you wrote it)
  - View the page/post/widget: the placeholder you typed in, will be replaced to the list you configured
Add a list of posts to a template file:
  - Add the following code (replace MYPLACEHOLDER to the placeholder of the list to insert) at the position you like to have the list of posts:
    `
    <?php 
      if( function_exists("pl_postlist") ) 
        pl_postlist("MYPLACEHOLDER"); 
    ?> 
    `

Examples:
After first activation of the plugin some examples have been created. To see PostLists working just create a new post and add one of the following example placeholders:
  %MYPOSTS%
  <!--MYPOSTSCOMMENT-->
  MYCATEGORYPOSTS
  MYMOSTCOMMENTEDPOSTS
  MYLATESTPOSTS24
  MYLATESTMODIFIEDPOSTS
  MYFUTUREPOSTS
  MYLATESTPOST     
Now save and view your post to see it working...
This example placeholders can be removed in the postlists admin menu.

== Update ==

0. Activate "Keep lists and settings also if plugin gets deactivated" if you dont want to loose your lists settings
1. Deactivate the PostLists-Plugin
2. Remove the existing folder 'postlists' with all files from your 'wp-content/plugins' folder on your webserver
3. Upload the folder 'postlists' with all files to '/wp-content/plugins' on your webserver
4. Activate the plugin "PostLists" through the 'Plugins' menu in WordPress

== WordPressVersions ==

This Plugin works fine with WordPress Version 2.3 and all higher Versions.

If you use a lower WordPress-Version or WordPress-Mu, please read this:
http://www.rene-ade.de/inhalte/wordpress-plugin-postlists-wordpress-versionen.html

== Extensions ==

You can download PostLists-Extensions here to add more functionality:
http://www.rene-ade.de/inhalte/wordpress-plugin-postlists-extensions.html

== Screenshots ==

Just visit the plugin website http://www.rene-ade.de/inhalte/wordpress-plugin-postlists.html
