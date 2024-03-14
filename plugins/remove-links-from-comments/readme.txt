=== Remove links from comments ===
Contributors: Fivera
Donate link: 
Tags: comment author, get_comment_author_link, author link,remove link,remove url,remove hyperlink
Requires at least: 2.0.2
Tested up to: 4.7.0
Stable tag: 1.1

This simple Plugin removes the website field in your comments and also hides all the hyperlinks from comment author user names.

== Description ==


I will start with saying that the external links from our websites pass some of our own juice to the website that they link to. That is not a bad thing at all, i love my juice being passed to websites like WordPress.org or to the websites that have similar content like mine e.g. : websites of the plugin authors that i review and promote on fivera.net or a cool online tool that i use like Pingdom or codepen.io

But i certainly dont want my juice to go to *exterminate rats with this great new poison* website, only because the author has made a decent comment on one of my articles.

So I have created this simple yet effective plugin to keep the external links to the minimum.
[Here](http://fivera.net/remove-links-comments-free-wordpress-plugin/)’s the page with more details about it.

== Installation ==

1. Upload the `remove-links-from-comments` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What does this plugin do? =
Remove links from comments plugin does only  two things.

1. It removes the Website field from the comments - modifies comment_form_default_fields 

2. It hides the the hyperlink under comment author box - modifies the behavior of get_author_comment_link

So all the future comments will not have the website field at all and you don't have to worry about the comments that are already on your website as it will automatically hide them showing only the comment authors name as a plain text.


= Where can I see the change? =

Anywhere comment author names are displayed on your website.
== Screenshots ==

1. Website field removed
2. Link on Author removed


== Changelog ==

= 1.0 =
* First build

= 1.1 =
* new version to refresh it with wp repository

== Upgrade Notice ==

= 1.0 =
There is only one version available at this time

=1.1.=
* tested with the newest verion of the wordpress 4.7