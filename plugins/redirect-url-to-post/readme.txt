=== Random Post Plugin - Redirect URL to Post ===
Contributors: camthor
Donate link: https://www.paypal.com/donate/?hosted_button_id=ZNEH34AN2TLUS
Tags: redirect, random, recent, random post, filter
Requires at least: 4.9
Tested up to: 6.3.1
Stable tag: 0.23.0
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Automatically redirect to your latest, oldest, random, or other post through a custom URL

== Description ==

Redirect your visitors to

* a random post
* your last (latest) post
* your first (oldest) post
* the previous or next post
* or a combination of conditions, for example a random post among your last 10 posts, or the latest post from at least 3 months ago, or the latest post of a specific author.

<a href="https://chattymango.com/redirect-url-to-post/?pk_campaign=rutp&pk_kwd=readme" target="_blank">Features</a> | <a href="https://documentation.chattymango.com/documentation/redirect-url-to-post/getting-started-redirect-url-to-post/examples-wordpress-redirects-to-posts/?pk_campaign=rutp&pk_kwd=readme" target="_blank">Examples</a> | <a href="https://documentation.chattymango.com/documentation/redirect-url-to-post/?pk_campaign=rutp&pk_kwd=readme" target="_blank">Documentation</a> | <a href="https://chattymango.com/?redirect_to=latest&cat=9&pk_campaign=rutp&pk_kwd=readme" target="_blank">Latest Development Blog Post</a>

= A magic URL takes you to the right post =

This plugin provides a URL (link) that takes you directly to a post in *single-post view*. This post is determined by the query parameter **?redirect_to=** and optional others.

While the URL remains the same, its target changes depending on the available posts at the time when somebody enters it. That means you can use the same static URL in a menu, with a button or in a newsletter and the plugin finds out the correct target.

Simply enter the URL of your WordPress site into your browser and add **?redirect_to=...** to the end.


Possible values for **redirect_to** are:

* **last** or **latest** – The URL will redirect to the last (latest) post.
* **first** or **oldest** – The URL will redirect to the first (oldest) post.
* **random** – The URL will redirect to a random post.
* **prev** or **previous** – The URL will redirect to the previous post (by date).
* **next** – The URL will redirect to the next post (by date).
* **custom** – The post will be determined according to the mandatory parameter orderby and the optional parameter order.

You can also limit the scope of considered posts by additional filter parameters, such as **&s=searchaword** or **&cat=2**, or use an **offset** to go to the second latest or to the post before the previous. The parameter **random** combined with **count** and **bias** lets you pick the latest (or oldest) posts with a different probability than the rest.

= Caching =

The plugin offers [caching of database results](https://documentation.chattymango.com/documentation/redirect-url-to-post/getting-started-redirect-url-to-post/other-parameters/?pk_campaign=rutp&pk_kwd=readme#Caching "plugin website").

= Settings and Parameters =

There is no settings page in the backend. You configure the plugin entirely through the query parameters in the URL.

Please find more information about parameters and troubleshooting [on the plugin website](https://documentation.chattymango.com/documentation/redirect-url-to-post/?pk_campaign=rutp&pk_kwd=readme "plugin website").


= Examples for URLs =

Note: Replace "http://www.example.com/" with your own website location. Spaces are written as "%20".

http://www.example.com/?redirect_to=latest - **redirects to the latest post**

http://www.example.com/?redirect_to=random&pk_campaign=random - **redirects to a random post and tracks the visit**

http://www.example.com/?redirect_to=prev - **redirects to the previous post**

http://www.example.com/?redirect_to=random&each_once=rewind - **redirects to a random post, avoiding duplicates, and then starts over again**

http://www.example.com/?redirect_to=prev&offset=1 - **redirects to the post before the previous post**

http://www.example.com/?redirect_to=random&count=10 - **redirects to a random post among the 10 latest posts**

http://www.example.com/?redirect_to=random&count=10&bias=80 - **redirects to a random post. The plugin picks one from the latest 10 with a probability of 80% and from the rest with a probability of 20%**

http://www.example.com/?redirect_to=random&count=10&offset=1 - **redirects to a random post among the 10 posts that come after the latest**

http://www.example.com/?redirect_to=random&after=1%20month%20ago - **redirects to a random post among the posts that are not older than 1 month**

http://www.example.com/?redirect_to=latest&exclude=4,7 - **redirects to the latest post, excluding the posts with the IDs 4 and 7**

http://www.example.com/?redirect_to=latest&offset=1 - **redirects to the second latest post**

http://www.example.com/?redirect_to=custom&orderby=comment_count&order=DESC - **redirects to the post with the most comments**

http://www.example.com/?redirect_to=latest&s=iaido&default_redirect_to=12&cache=200 - **redirects to the latest post that contains the word 'iaido' or, if nothing can be found, to the page or post with the ID 12; use a cache with a 200 second lifetime**


= Button =

The plugin also provides a shortcode [redirect_to_post_button] to create a simple button. Some [parameters](https://documentation.chattymango.com/documentation/redirect-url-to-post/getting-started-redirect-url-to-post/redirect-button/?pk_campaign=rutp&pk_kwd=readme#Shortcode_to_create_button "shortcodes") are available.

A button that links to a random post is a great way to increase your visitors' on-site engagement and therefore your **SEO ranking**!

https://www.youtube.com/watch?v=-7kTnkBVpDA

(video by WPBeginner)

**If you find this plugin useful, please give it a [5-star rating](https://wordpress.org/support/plugin/redirect-url-to-post/reviews/?filter=5 "reviews"). Thank you!**

Follow us on [Facebook](https://www.facebook.com/chattymango/) or [Twitter](https://twitter.com/ChattyMango).

= Check out my other plugins =

* [Tag Groups](https://wordpress.org/plugins/tag-groups/)

== Installation ==

1. Find the plugin in the list at the admin backend and click to install it. Or, upload the ZIP file through the admin backend. Or, upload the unzipped redirect-url-to-post folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the ‘Plugins’ menu in WordPress.

After the first activation you will find a screen with some examples of URLs for your blog.

== Frequently Asked Questions ==

= 1. How to find out the cause if something doesn't work?

The first thing you should try is to add `&rutpdebug` (or `&rutpdebug=2` for advanced users) to the URL. This will show you more details about what is happening in the background. At the end you will see the URL of the resulting post without actually going there.

= 2. What if more than one post match the criteria (e.g. two have the same comment_count)? =

There can be only *one* winner. The post that would be first in the list (as determined by WP) beats all others.

= 3. The random parameter redirects always to the same post =

You probably use a caching plugin or service that also caches query strings. Try adding an exception for the string "redirect_to=". If you use Cloudflare, you can try their [Page Rules]( https://chattymango.freshdesk.com/solution/articles/5000750256-the-redirect-to-random-parameter-always-redirects-to-the-same-post "Cloudflare").

= 4. Which URL can serve as the base? =

Obviously only URLs of the WordPress blog where this plugin is installed.

= 5. The post cannot be found but I'm sure that it's there and that it's public =

The most common reason is that this post belongs to a special post type. Try the parameter "post_type", for example "post_type=product".

= 6. Can I help translate? =

Thank you! Please [continue here](https://translate.wordpress.org/projects/wp-plugins/redirect-url-to-post).

== Screenshots ==

== Changelog ==

### 0.23.0 ###

= Features =

* The cache is automatically cleared when a post is updated
* We don't reveal the database table prefix in debugging

### 0.22.0 ###

= Features =

* Option to delete the cache (for a particular set of parameters) with `cache=-1` (here too, the constant `CHATTY_MANGO_RUTP_CACHE` takes priority)

= Other =

* Added the actual database request to the debugging output

### 0.21.0 ###

= Features =

* Paramter post_type can also be a comma-separated list

### 0.20.0 ###

= Features =

* New parameters: author__in, author__not_in, category__and, category__in, category__not_in, post__in, post__not_in, post_name__in, post_parent, post_parent__in, post_parent__not_in, tag__and, tag__in, tag__not_in, tag_slug__and, tag_slug__in. [More information](https://developer.wordpress.org/reference/classes/wp_query/#parameters). Wherever WordPress requires an array, you submit the elements as comma-separated list: `...&tag__in=12,15`.

### 0.19.1 ###

= Other =

* Added more debug messages for `&rutpdebug`

### 0.19.0 ###

= Features =

* Define own URL parameters that will be passed through by adding to your wp-config.php `define( 'CHATTY_MANGO_RUTP_PASS_THROUGH', 'own1,own2' );` (comma-separated list) to allow ...&own1=foo&own2=bar

### 0.18.3 ###

= Bug Fixes =

* Reduced memory usage when not required to retrieve post objects from database

### 0.18.2 ###

= Bug Fixes =

* Fixing parameter ignore_sticky_posts

### 0.18.1 ###

= Bug Fixes =

* Fixing bug introduced in last version (random with bias not working)

### 0.18.0 ###

= Features =

* New parameter `lock` to redirect a visitor always to the same post for a specified time. Use it with a time in seconds: `...&lock=86400`. Particularly useful with random redirects. This works only in the same browser and if the visitor accepts and keeps cookies.

= Other =

* Cookies (for `each_once` and `lock`) have now by default a domain-wide validity. Switch to separate cookies per directory with the parameter `directory_cookie`.
* Please [help translate](https://translate.wordpress.org/projects/wp-plugins/redirect-url-to-post)!


= Older Versions =

The complete changelog is available [here](https://chattymango.com/changelog/redirect-url-to-post/?pk_campaign=rutp&pk_kwd=readme).

== Upgrade Notice ==
