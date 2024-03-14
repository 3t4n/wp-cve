=== Gallery Image Captions (GIC) ===
Contributors: mlchaves
Donate link: https://ko-fi.com/marklchaves
Tags: gallery, shortcode, filter, html, css, images, captions
Requires at least: 5.3.2
Tested up to: 6.1.1
Stable tag: 1.4.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Gallery Image Captions (GIC) allows you to customise WordPress gallery image captions. 

== Description ==

With **GIC**, you can display the title, caption, and description image attributes. You can also change/filter the rendering HTML to whatever you want.

After installing and activating GIC, write your filter and add the WordPress [Gallery shortcode](https://codex.wordpress.org/Gallery_Shortcode) to your page.

If you've been _dreaming_ of writing a filter to customise the gallery image captions, then this plugin is for you.

[Visit the live demo page.](https://streetphotography.blog/gallery-image-captions-demo/)

= Motivation =

The default WordPress gallery shortcode will only display the **caption** from the media's attachment metadata. Sometimes it's nice to display more like the title&mdash;even the description.

The **GIC plugin** overrides the WordPress gallery shortcode function to create a [hook](https://developer.wordpress.org/plugins/hooks/). With this _hook_ you can do a little bit more than just displaying the caption.

Some premium themes hide the caption completely. This leaves photography lovers like me scratching their head and spending precious time cobbling together makeshift caption blocks.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/PLUGIN-NAME` directory, or install the plugin through the WordPress **Plugins** page directly.
1. Activate the plugin through the **Plugins** page in WordPress.

== Usage ==

= Custom Filter For Displaying Captions =

The **crux** of this plugin is the ability to filter the gallery image caption. The `galimgcaps_gallery_image_caption` hook makes this possible. 

For the usage examples below, this is the filter used.

`
/**
 * Custom Filter for Gallery Image Captions
 *
 * Note: Avoid altering captiontag, selector, and itemtag.
 */
function mlc_gallery_image_caption($attachment_id, $captiontag, $selector, $itemtag) {

    $id = $attachment_id;

    // Grab the meta from the GIC plugin.
    $my_image_meta = galimgcaps_get_image_meta($id);
    
    /**
     * Here's where to customise the caption content.
     * 
     * This example uses the meta title, caption, and description. 
     * 
     * You can display any value from the $my_image_meta array. 
     * You can add your own HTML too.
     */
    return "<{$captiontag} class='wp-caption-text gallery-caption' id='{$selector}-{$id}'>" .
            "Title: " . $my_image_meta['title'] . "<br>" .
            "Caption: " . $my_image_meta['caption'] . "<br>". 
            "Description: ". $my_image_meta['description'] . 
        "</{$captiontag}></{$itemtag}>";

}
add_filter('galimgcaps_gallery_image_caption', 'mlc_gallery_image_caption', 10, 4);
`

Feel free to use this filter code as a starter template. After activating the GIC plugin, add the code above to your child theme's `functions.php` file. Rename the function and tweak the return string to suit your needs.

= New Filter To Get Custom Fields =

`
/**
 * New GIC 1.4.0 filter for custom meta fields.
 */
function gic_add_custom_fields( $image_meta, $attachment ) {
	
	// This is how you add a custom fields to the array that
	// GIC uses to display captions.
	$image_meta['credit_text'] = $attachment->credit_text;
    $image_meta['credit_link'] = $attachment->credit_link;

	return $image_meta;
}
add_filter( 'galimgcaps_image_meta', 'gic_add_custom_fields', 10, 2 );
`

To use these two custom fields, your `galimgcaps_gallery_image_caption` would look something like this.

`
function mlc_gallery_image_caption($attachment_id, $captiontag, $selector, $itemtag) {

    $id = $attachment_id;

    // Grab the meta from the GIC plugin.
    $my_image_meta = galimgcaps_get_image_meta($id);

    // If there's credit, give it where it's due complete with link.
    $credit = $my_image_meta['description'] ? 
        "<br><strong>Credit</strong>: <a style='display: inline;' href='" . 
        $my_image_meta['credit_link'] . 
        "'>" . $my_image_meta['credit_text'] . "</a>" . "<br>" : 
        '';

    /**
     * With GIC 1.4.0 you can also add custom media attachment fields
     * to your captions.
     */
    return "<{$captiontag} class='wp-caption-text gallery-caption' id='{$selector}-{$id}'>" .
            "<strong>Caption</strong>: " . $my_image_meta['caption'] . "<br>" . 
		    $credit .
        	"</{$captiontag}></{$itemtag}>";

}
add_filter('galimgcaps_gallery_image_caption', 'mlc_gallery_image_caption', 10, 4);
`

**Since v1.2.0**, GIC automatically adds an **Image ID** column to your WordPress Media Library. This is to help you add the image IDs to your GIC shortcodes. 

[See where GIC automatically adds an Image ID column to your WordPress Media Library.](https://ps.w.org/gallery-image-captions/assets/screenshot-11.png)

**New in v1.4.0**, GIC support custom media attachment fields.

== Usage Example 1 ==

= Shortcode =

For starters, let's use a 

`<p></p>` 

tag for the caption tag.

`[gallery size="full" columns="1" link="file" ids="114" captiontag="p"]`

= Styling =

Let's override the generated styles with our own style for one particular image.

`
/* Targeting a Specific Image */

/* Add some padding all around. */
#gallery-1 .gallery-item, 
#gallery-1 .gallery-item p {
    padding: 1%;
}

/* Add some moody background with typewriter font. */
#gallery-1 .gallery-item {
    color: whitesmoke;
    background-color: black;
    font-size: 1.25rem;
    font-family: Courier, monospace;
    text-align: left !important;
}
`

== Usage Example 2 ==

= Shortcode =

**A 2 column x 1 row gallery with large size images using an H4 for the caption.**

`[gallery size="large" columns="2" link="file" ids="109,106" captiontag="h4"]`

**A 3 column x 1 row gallery with medium size images using a blockquote for the caption.**

`[gallery size="medium" columns="3" link="file" ids="109,106,108" captiontag="blockquote"]`

Did you notice that we are using 

`<blockquote></blockquote>` 

in the second shortcode? Let's give it try just for _kicks_.

= Styling =

`
/* 1. Style the H4 Used in the Caption Example */
h4 {
	color: #777777 !important;
	font-size: 1.2rem !important;
	font-family: Helvetica, Arial, sans-serif !important;
}

/* 2. Help Align the Blockquote */
#gallery-3 .gallery-caption {
    margin-left: 40px !important;
}
`
== Frequently Asked Questions ==

= What media metadata can I insert into my captions? =

Here's the list of metadata with their array index you can insert into your captions.

* Alternative Text ['alt']
* Title ['title']
* Caption  ['caption']
* Description ['description']
* Attachment URL ['href']
* Image URL ['src']

Starting with version 1.4.0, you can pull **custom** media attachment fields right into your captions!

= How do I get the file (post) IDs for the shortcode?

As of v1.2.0, you can hit the list icon in your Media Library and a [sortable column of image IDs](https://ps.w.org/gallery-image-captions/assets/screenshot-11.png) will display on the far right. This is much easier and faster than manually (see below) looking them up if you have a lot of images for your gallery.

You can also manually find the image post IDs by selecting the image in the Media Library and hovering over the **Edit** link. You'll need to visually pick out the `post=85` for example in the URL preview.

== Screenshots ==

1. WordPress Gallery Before GIC
2. WordPress Gallery Before GIC
3. Displaying title, caption, and description with moody styling using GIC
4. More styling examples using GIC: centre justified text and even using blockquote styling
5. With GIC, you can even insert links to the image file and attachment Page!
6. Write media queries to control how to display captions for different devices
7. Responsive for mobile displays
8. Another example of displaying title, caption, and description with moody styling using GIC
9. Washington Post style captions using GIC
10. Vogue style captions using GIC
11. Media Library Image ID column to help with writing GIC shortcodes. Since in v1.2.0.
12. Custom field support. New in v1.4.0.
13. Media attachment details with 2 custom fields for photographer credits.

== Changelog ==

= 1.4.0 =
* Fixed an issue where the gallery layout is messed up if GIC is active but there's no GIC filter yet.
- Added a new `galimgcaps_image_meta` filter to support custom media attachment fields.

= 1.3.0 = 
* Changes for WordPress 5.6.

= 1.2.0 =
* Added an [Image ID column](https://ps.w.org/gallery-image-captions/assets/screenshot-11.png) to the Media Library to help find the image file IDs for the GIC shortcodes.

= 1.1.0 = 
* Slight refactoring and documentation updates

= 1.0.1 =
* Readme documentation updates. New author URI in source PHP file.

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.4.0 =
You can now display any custom fields you've got in your gallery image captions.

= 1.2.0 = 
Look for the Image ID column in you Media Library's list view.

= 1.0.1 =
Minor release only. No code changes. Feel free to upgrade for Readme documentation updates and the new author URI in PHP source file. Also tested on PHP version 7.2.18.

== Responsive CSS Example ==

I recommend adding the following media queries if you use galleries with more than one image. The two media queries below will stack 2x1 and 3x1 galleries into a 1 column x n rows or 2 column x n rows  as needed.

`
/* Media Queries for Responsive Galleries */

/**
 * Styling based on article "How To: Style Your WordPress Gallery"
 * by Par Nicolas.
 * 
 * https://theme.fm/how-to-style-your-wordpress-gallery/
 */

/* Mobile Portrait Breakpoint - 1 column */
@media only screen and (max-width: 719.998px) {
    .gallery-columns-2 .gallery-item,
	.gallery-columns-3 .gallery-item { 
	 width: 100% !important; 
  }
}

/* Mobile Landscape and Tablet Breakpoints - 2 columns */
@media only screen and (min-width: 720px) and (max-width: 1024px) {
  .gallery-columns-3 .gallery-item { 
	 width: 50% !important; 
  }
}
`
