=== Hikari Category Permalink ===
Contributors: shidouhikari 
Donate link: http://Hikari.ws/wordpress/#donate
Tags: permalink, permalinks, category, custom, seo, filter, JavaScript, postmeta, metadata
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 1.00.08

For each post, author can choose which category is used in permalink.

== Description ==

Wordpress 3.0 comes with a new filter that lets us customize what is used for each permalink structure tag, other than Wordpress default.

One of these permalink structure tags is <code>%category%</code>. By default, Wordpress always use the category with lowest ID, making <code>%category%</code> impractical for SEO optimization.

**Hikari Category Permalink** allows post authors to choose among each post's categories, which of them is used in that post permalink, giving much more flexibility and power to permalinks.

This plugin is a fork of <a href="http://wordpress.org/extend/plugins/scategory-permalink/">Dmytro's sCategory Permalink</a>. It has all original features and is compatible with original options, while being more stable and simple, and also fixes 2 recurring bugs.


= Features =

* You can choose for each post separately, which category is used in its <code>%category%</code> permalink.
* Posts without a category set to be used in permalink behave as Wordpress default, (which currently is) the one with lowest ID is used
* Posts with a category set have it used
* In post edit page, where you set the post's categories, there's a new feature allowing you to choose which category will be used in permalink
* No rewrite rules tweaks are done, making the plugin much simpler and bug free
* If you already used sCategory Permalink, you can safely deactivate it and replace by **Hikari Category Permalink**, all your category permalinks will be used



== Installation ==

**Hikari Category Permalink** requires at least *Wordpress 3.0* and *PHP5* to work. It has backward compatibily for Wordpress 2.8 and 2.9, but they are deprecated and I don't support them officially. Setting category permalink also requires *JavaScript* enabled.

If you have Dmytro's sCategory Permalink plugin installed, first deactivate it. **Hikari Category Permalink** doesn't conflict with the legacy plugin, but I don't recommend keeping both activated.

Once you are ready to install, you can use the built in installer and upgrader, or you can install the plugin manually.

1. Download the zip file, upload it to your server and extract all its content to your <code>/wp-content/plugins</code> folder. Make sure the plugin has its own folder (for exemple  <code>/wp-content/plugins/hikari-category-permalink/</code>).
2. Activate the plugin through the 'Plugins' menu in WordPress admin page.
3. Go to Settings > Permalink, select "Custom Structure" and set any structure you'd like, using the <code>%category%</code> tag. For an exemple, you can use <code>/%category%/%post_id%/%postname%/</code>.
4. There's no option to be configured. Just try to edit any existing post or create a new one.
5. When you hover mouse in a category in the "Categories box", you'll see a blue "Permalink" text appear on the right of the hovered category, just click on that text to set that category to be used in this post permalink. (You need JavaScript enabled to see the "Permalink" text appear)


= Upgrading =

If you have to upgrade manually, simply delete <code>hikari-category-permalink</code> folder and follow installation steps again.

If you are using Dmytro's sCategory Permalink plugin, just deactivate it and activate Hikari Category Permalink.
And once the upgrade is done, *if you are using Wordpress 3.0 or above*, go to Settings > Permalink and replace legacy <code>%scategory%</code> tag to stantard <code>%category%</code>!

= Uninstalling =

To uninstall just deactivate the plugin, or delete its folder.

There's no wp_options option stored, posts category permalinks are stored as postmeta. Currently there's no automatic way to delete these postmeta if you don't need them anymore, this feature will be added in a future release.



== Frequently Asked Questions ==

= What happens if I don't use the <code>%category%</code> tag in my permalink structure?  =

You still can set category permalinks when creating and editing posts, but their permalinks won't have any category on them. When you decide to use <code>%category%</code> in your permalinks, saved categories will start being used.

= And what happens if I used it, but then remove the tag from my structure?  =

Your posts URLs will of course change, and not have category on them anymore. But postmeta will remain saved and you'll still be able to set them, and when you decide to use the <code>%category%</code> tag again they'll be used.

= What happens for posts that have no category permalink set?  =

Hikari Category Permalink just does nothing and send the permastruct back to Wordpress to deal with it in its default. Wordpress default is use the category with lowest ID.

= I've read that Wordpress is inefficient when only string tags are used in posts permalink, how does your plugin deals with it?  =

It's inefficient because Wordpress generates and parses permalinks dynamically, they aren't stored, and to parse it uses general expressions and database queries. If you only have string tags in posts permalinks, there's no way for Wordpress to distinguish posts from pages, and then it must use slower methods to identify a post.

**Hikari Category Permalink** uses the standard <code>%category%</code> tag and doesn't require any rewrite rules tweaking, all its tweaks are in permalink creation (<code>get_permalink()</code> function) and not in parsing, so your permalink structure has the same weakness it would have if the plugin wasn't being used. I suggest adding an integer tag somewhere in the structure (begining, middle or end, if you use more than 1 string tag I suggest adding an int tag between them). My prefered one is <code>%post_id%</code>, because its meaning is directly related to the post, and you have quick view to a post ID when you can see its permalink.

= Why did you fork Dmytro's sCategory Permalink?  =

sCategory Permalink is a great plugin, it was original, unique, and pionner. For long we were in need of a category-in-permalink solution than the simple the-one-with-lowest-ID Wordpress offers. Dmytro was able to imagine a much better solution and implement it.

But the plugin was bugged, it's in v0.6.2 for a long time, and I was still using v0.3.0, because any version above it was generating 404 for me in any post, while v0.3.0 generates 404 when I use multipaged comments. Dmytro offered the plugin for free and made it almost perfect, but it wasn't being supported anymore and he didn't answer my contact attempts, so I decided to fix it.

I wasn't able to fix the original bugs, but discovered that with a new filter inside <code>get_permalink()</code>, the code with original bugs wouldn't be needed anymore, the plugin would be much simpler to tweak Wordpress and permalink generation would be much more flexible. I opened a ticket in Wordpress trac requesting the new filter, and it was added to 3.0! :D

Since I can't contact Dmytro, I just forked the plugin and enhanced it.

= I'm currently using Dmytro's sCategory Permalink, can I safely upgrade to Hikari Category Permalink? =

Yes, I did that :P

It uses the same postmeta, so all your existing category permalinks will be used, and new ones will use the same name, all simple. And with the advantage of 404 errors being totally gone, since Hikari Category Permalink doesn't touch rewrite rules! :D

And one more thing, once the upgrade is done, *if you are using Wordpress 3.0 or above*, go to Settings > Permalink and replace legacy <code>%scategory%</code> tag to stantard <code>%category%</code>!

= Why your plugin doesn't support Wordpress 2.9?  =

It indeed supports and I've been using in it, even 2.8 may work. But when used in Wordpress *below* 3.0, the legacy <code>%scategory%</code> tag **must** be used, standard <code>%category%</code> **doesn't** work, and therefore 404 error when multipages comments are used may occur. I just hacked my Wordpress core and added the filter that only 3.0 has natively, but I won't explain other people how to do it in 2.9 and won't support any bug on it, I only support Hikari Category Permalink in Wordpress 3.0 and above :)



== Screenshots ==

1. Category metabox when a new post is created
2. Category metabox when categories are already selected, but no permalink set yet
3. Permalink selection feature appears when mouse hovers over it
4. When permalink is selected, category becomes bold


Post permalink by Wordpress default: http://domain.com/ciencia/200/post-name/
Post permalink by **Hikari Category Permalink**: http://domain.com/consciencia/caos/200/post-name/

== Changelog ==

= 1.00.08 =
* It seems Wordpress is executing an action in unexpected places, that's making the plugin delete Category Permalinks from some posts.
* This version is just a temporary measure to assure Category Permalink postmeta isn't delete, at least not by the plugin.
* **It's recommended to update it ASAP, to avoid this bug from happening and deleting your postmeta.** A new version will be released soon with an UI to better handle it. Sorry for the inconvenience.

= 1.00.07 =
* First public release.

== Upgrade Notice ==

= 1.00 and above =
If you have to upgrade manually, simply delete <code>hikari-category-permalink</code> folder and follow installation steps again.
