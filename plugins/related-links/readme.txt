=== Related Links ===
Contributors: chabis
Donate link: http://wordpress.org/extend/plugins/related-links/
Tags: related, deep, internal, link, post, page, selection
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: trunk

Manually link to existing content or a custom url through a meta box on the writing page.

== Description ==

Related Links gives you the possibility to manually link other posts to your current post. But you can also link pages, media or any custom post-type. And in addition you can use custom urls to link to external files. The plugin adds a Metabox to the writing page with a list of all available content.

Features:

* Shows a list of all available content in a Metabox on the writing page
* Multiple links can be selected
* Link order can be changed through drag and drop
* Custom URLs can be added
* Search field to quickly find a link
* Simple theme integration with an included widget or `related_links()`
* Works with custom post-types
* Settings to enable the post-types that should be shown in the Metabox

== Installation ==

1. Upload the `related-links` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Set in the link types in `Related Links` under the `Settings` menu in WordPress.
4. Place `<?php related_links(); ?>` in your templates.

== Frequently Asked Questions ==

= How do I show the links in my theme? =

Use the `Related Links` widget to show a list of the related links.


= How do I show the links in a specific template? =

Use the `related_links()` function directly in your template files. This will return an unordered list with an `<ul>` wrapper. Use this code for example in your `content.php' template:

`<?php related_links(); ?>`


= How can I modify the output of the link list? =

You need to use the `get_related_links()` function. A simple example that shows a list with all link names and the type of link:

`<?php $related_links = get_related_links(); ?>
<ul>
	<?php foreach ($related_links as $link): ?>
		<li><a href="<?php echo $link['url']; ?>"><?php echo $link['type']; ?>: <?php echo $link['title']; ?></a></li>
	<?php endforeach; ?>
</ul>`


= What are the properties returned by the `get_related_links()` function? =

The `get_related_links()` returns an array containing every related link. when you loop through this array every link consists of another array with the following keys:

* key `id`: equals to `$post->ID` or `null` for custom links
* key `url`: equals to `get_permalink()` or the manually entered url of a custom link
* key `title`: equals to `$post->post_title` or the manually entered title of a custom link
* key `type`: the `$post->post_type` or `null` for custom links


= How do I only show the links for a certain post_type in my theme? =

Set the `$post_type` in `get_related_links($post_type)` to `'post'`, `'page'` or any custom post-type. A simple example that show a list of links:

`<?php $related_links = get_related_links('page'); ?>
<ul>
	<?php foreach ($related_links as $link): ?>
		<li><a href="<?php echo $link['url']; ?>"><?php echo $link['type']; ?>: <?php echo $link['title']; ?></a></li>
	<?php endforeach; ?>
</ul>`


= How do I show the related links of another post (not the current one)? =

Set the `$post_id` in `get_related_links(null, $post_id)` to the id of the post. A simple example that show a list of links:

`<?php $related_links = get_related_links(null, 1); ?>
<ul>
	<?php foreach ($related_links as $link): ?>
		<li><a href="<?php echo $link['url']; ?>"><?php echo $link['type']; ?>: <?php echo $link['title']; ?></a></li>
	<?php endforeach; ?>
</ul>`


= How do I link directly to a media file? =

You need to check the `'type'` and then get with `wp_get_attachment_url()` the attachment url from the `'id'`.

`<?php $related_links = get_related_links(null, 1); ?>
<ul>
<?php foreach ($related_links as $link): ?>
	<?php if ($link['type'] == 'attachment') :
		$url = wp_get_attachment_url($link['id']);
	else :
		$url = $link['url'];
	endif; ?>
	<li><a href="<?php echo $url; ?>"><?php echo $link['title']; ?></a></li>
<?php endforeach; ?>
</ul>`

= How do show the type of a media file? =

You need to check the `'type'` and then get with `wp_get_attachment_url()` the attachment url from the `'id'`.

`<?php $related_links = get_related_links(null, 1); ?>
<ul>
<?php foreach ($related_links as $link): ?>
	<?php 
	if ($link['type'] == 'attachment') :
		$url = wp_get_attachment_url($link['id']);
		$mime = explode('/', get_post_mime_type($link['id']));
		$mime = $mime[sizeof($mime) - 1];			
	else :
		$url = $link['url'];
		$mime = null;
	endif; 
	?>
	<li><a href="<?php echo $url; ?>"><?php echo $link['title']; ?><?php echo isset($mime) ? ' (' . $mime . ')' : ''; ?></a></li>
<?php endforeach; ?>
</ul>`

= How to prevent a PHP error when the plugin is not activated? =

Put the `get_related_links()` function or your whole code into an `if` clause. Like this your theme will still work even if the plugin is deactivated.

`<?php if(function_exists('get_related_links')) : ?>
	<?php $related_links = get_related_links(); ?>
	<ul>
		<?php foreach ($related_links as $link): ?>
			<li><a href="<?php echo $link['url']; ?>"><?php echo $link['type']; ?>: <?php echo $link['title']; ?></a></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>`

== Screenshots ==

1. Related links Metabox on the post page.
3. Settings page.

== Changelog ==

= 1.7.1 =
* Removed an obsolete console log that may cause problems on Internet Explorer 7 and 8.
= 1.7 =
* Added a Widget to show the related links in your widgetized theme areas.
* Fixed a JavaScript error when adding custom links.
* Settings aren't reset after plugin reactivation.
= 1.6 =
* Metabox loads new list items while scrolling. Leads to a better load and search performance when a blog contains many hundred posts. 
* Use placeholder attribute for input fields.
* Use plugins_url() to load stylesheets and javascript.
* Enqueue jQuery UI correctly.
= 1.5.7 =
* Added related_links() function to echo an unordered list of links
* Added media post-type to link to media files (thanks jhned)
= 1.5.6 =
* Fixed a problem where empty post data could lead to a php error
= 1.5.5 =
* Fixed the marking of already selected links in the list (thanks robert_k for the fix)
= 1.5.4 =
* Fixed a bug where get_related_links() always returned custom links when the post_type parameter was set
= 1.5.3 =
* Updated CSS for WordPress 3.3 
= 1.5.2 =
* Updated the way how settings are saved to be compatible with future WordPress versions
= 1.5.1 =
* Fixed a bug where the plugin was also loaded on the taxonomy admin pages
= 1.5 =
* Links order can be changed with drag and drop
* Search field to quickly find a link by name
* External URLs can be added
* Added an `id` property to the get_related_links() function
* New meta data structure but legacy support for older plugin versions is added
* Checking if the post really exists before it is added to the output
* Better list loading through ajax

= 1.0.1 =
* The meta box content list is now scrollable

= 1.0 =
Initial release