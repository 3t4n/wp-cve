=== Get Post Content Shortcode ===
Contributors: webdeveric
Tags: post content, shortcode
Requires at least: 3.6.0
Tested up to: 4.5.0
Stable tag: 0.4.0

This plugin provides a shortcode to get the content of a post based on ID number.

== Description ==

This plugin provides a shortcode to get the content of a post based on ID number.
By default, the content will be passed through `wpautop()` and `do_shortcode()` unless you tell it not to by using attributes or filters as shown below.

= Examples =

`[post-content id="42"]`
This gets the content of post 42.

`[post-content id="42" autop="false"]`
This gets the content of post 42 and does not call wpautop on the content.

`[post-content id="42" shortcode="false"]`
This gets the content of post 42 and does not call do_shortcode on the content.

`[post-content id="42" autop="false" shortcode="false"]`
This gets the content of post 42 and does not call wpautop or do_shortcode on the content.

`[post-content id="42" status="publish,future"]`
This gets the content of post 42 only if the post_status is "publish" or "future".

`[post-content id="42" field="excerpt"]`
This gets the excerpt of post 42.

**Note:**
The containing post may still have wpautop called on it's content.

= Attributes =

1. **id** - integer

   The post ID

1. **autop** - boolean - default: true

   The following values equal true: true, 1, yes. All other values equal false.

1. **shortcode** - boolean - default: true

   The following values equal true: true, 1, yes. All other values equal false.

1. **status** - text - default: publish

   Any default or custom WordPress status value (publish, draft, future, etc.).

   The default value will be used if the status is not registered with WordPress.

1. **field** - text - default: post_content

   The name of the database column you want to retrieve.

   This default value will be used if the column name is not in the array of allowed field names.

= Filters =

**You can modify the fields that are allowed to be retrieved with this filter.**

`add_filter('post-content-allowed-fields', function($allowed_fields) {
    // Do your filtering here.
    return $allowed_fields;
});`

**You can specify the default shortcode attribute values.**

`add_filter('post-content-default-attributes', function ($default_attributes) {
    // Your code here.
    return $default_attributes;
});`

**You can filter attributes per shortcode usage**

`add_filter('shortcode_atts_post-content', function ($out, $pairs, $attributes) {
    // Your code here.
    return $out;
}, 10, 3);`

== Installation ==

1. Upload `get-post-content-shortcode` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[post-content id="SOME OTHER POST ID"]` in your content.

== Changelog ==

= 0.4.0 =
* Added a filter to allow you to specify the default values for the shortcode attributes.

= 0.3.2 =
* Added `field` attribute so you can specify what content to return.

= 0.3.1 =
* By default, this shortcode will only get content of published posts, unless you specify the status attribute.

= 0.3.0 =
* I updated the code to temporarily switch to the other post so that shortcodes in the other post will work as expected.

= 0.2.0 =
* I updated the code to use the `get_post_field` function instead of `get_post`.

= 0.1.0 =
* Initial build
