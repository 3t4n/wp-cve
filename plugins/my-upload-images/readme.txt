=== My Upload Images ===
Contributors: fishpie
Donate link: http://web.contempo.jp/donate?mui
Tags: media uploader, upload, image, custom field, cms
Plugin URI: http://web.contempo.jp/weblog/tips/p617
Requires at least: 4.0
Tested up to: 4.7.3
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create metabox with media uploader. It allows to upload and sort images in any post_type. 



== Description ==
This plugin create the metabox with the media uploader into any post types. In the metabox, You can drag images into any order you like. The IDs and the order of images will put on record in the customfield of your posts as array. 
= Attention =
Available only for WordPress 4.0+. 


== Screenshots ==
1. Select post types you’d like to display metabox.
2. Just upload and sort images.



== Installation ==
1. Copy the ‘my-upload-images’ folder into your plugins folder.
2. Activate the plugin via the ‘Plugins‘ admin page. The plugin requires the setup of selecting post_types which you want to add metabox.

= Example usage =
The image IDs are stored in [‘my_upload_images’] custom field. When to output the IDs into your template file, write codes like below.

Output images and links.
<code>&lt;?php
$my_upload_images = get_post_meta( $post-&gt;ID, 'my_upload_images', true );
if ( $my_upload_images ): foreach( $my_upload_images as $img_id ):
 $full_src = wp_get_attachment_image_src ($img_id,'fullsize');
 if ( !$full_src ) continue;
 echo 
 '&lt;a href="'.$full_src[0].'"&gt;'.wp_get_attachment_image ($img_id,'thumbnail').'&lt;/a&gt;'."\n";
endforeach; endif; 
?&gt;</code>

Output images and links with attributes.
<code>&lt;?php
$my_upload_images = get_post_meta( $post-&gt;ID, 'my_upload_images', true );
$slider = '';
if ( $my_upload_images ): 
 foreach( $my_upload_images as $img_id ):
 $full_src = wp_get_attachment_image_src ($img_id,'fullsize');
 if ( !$full_src ) continue;
 $file = get_post( $img_id );
 $img_title = $file-&gt;post_title; // title
 $img_caption = $file-&gt;post_excerpt; // caption
 $img_desc = $file-&gt;post_content; // desctiprion
 $img_alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true ); // alt
 $thumb_src = wp_get_attachment_image_src ($img_id,'thumbnail');
 $slider .= 
 "\t".'&lt;li&gt;'."\n".
 "\t\t".'&lt;a href="'.$full_src[0].'"'.( $img_title ? ' title="'.esc_attr( $img_title ).'"' : '' ).'&gt;'."\n".
 "\t\t\t".'&lt;img src="'.$thumb_src[0].'" width="'.$thumb_src[1].'" height="'.$thumb_src[2].'"'.( $img_alt ? ' alt="'.esc_attr( $img_alt ).'"' : '' ).'/&gt;'."\n".
 ( $img_title ? "\t\t\t".'&lt;div class="title"&gt;'.$img_title.'&lt;/div&gt;'."\n" : '' ).
 ( $img_desc ? "\t\t\t".'&lt;div class="caption"&gt;'.wpautop( $img_caption ).'&lt;/div&gt;'."\n" : '' ).
 "\t\t".'&lt;/a&gt;'."\n".
 "\t".'&lt;/li&gt;'."\n";
 endforeach; 
 echo '&lt;ul class="slider"&gt;'."\n".$slider.'&lt;/ul&gt;'."\n";
endif; 
?&gt;</code>


= Attention =
The custom field doesn’t have multiple values, it just has become an array in a single value. When you call them with ‘get_post_meta’ function, do not set the third parameter to ‘false’.



== Changelog ==
= 1.4.1 =
14.Mar.2017. Fixed behavior of custom wp.media.

= 1.4.0 =
25.Feb.2017. Add image metadata editor. Fix TypeError of wp.media.js.

= 1.3.9 =
29.Dec.2016. Fix a conflict of edit_form_after_title.

= 1.3.8 =
12.May.2016. Add limit max number of registerable images. Fix display on preview. Change Text Domain.

= 1.3.7 =
16.Nov.2015. Add edit buttons on images. Rename functions to avoid name conflict. Some bug fixes, accessibility improvements and translation updates. 

= 1.3.6 =
18.Oct.2015. Bug fix.

= 1.3.5 =
11.Oct.2015. Bug fix.

= 1.3.4 =
12.Sep.2015. Add caption on thumbnail. Unite multiple option values into an array.

= 1.3.3 =
07.June.2015. Add selector of position of metabox.

= 1.3.2 =
10.May.2015. Auto generate post thumbnail by plugin.

= 1.3.1 =
15.Jan.2015. Fixed Javascript.

= 1.3 =
10.Jan.2015. First public version Release.

= 1.0 =
25.Apr.2014. Initial Release.



