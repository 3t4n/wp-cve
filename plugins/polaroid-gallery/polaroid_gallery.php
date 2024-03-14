<?php
/*
Plugin Name: Polaroid Gallery
Plugin URI: http://www.mikkonen.info/polaroid_gallery/
Description: Used to overlay images as polaroid pictures on the current page or post and uses WordPress Media Library.
Version: 2.2
Author: Jani Mikkonen
Author URI: http://www.mikkonen.info
Contributors: tashemi
Donate link: http://goo.gl/0gvUvm
License: Unlicense
TextDomain: polaroid-gallery
DomainPath: /languages
*/

/*
This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>
*/

include_once(dirname(__FILE__) . '/wp_thumbnails_helper.php');

/** plugin init **/
function polaroid_gallery_init() {
	load_plugin_textdomain('polaroid-gallery', false, dirname(plugin_basename(__FILE__)) . '/languages' );

}
add_action('init', 'polaroid_gallery_init');

/** admin code **/
function polaroid_gallery_options_init() {
	register_setting('polaroid_gallery_options', 'image_size');
	register_setting('polaroid_gallery_options', 'ignore_columns');
	register_setting('polaroid_gallery_options', 'custom_text');
	register_setting('polaroid_gallery_options', 'custom_text_value');
	register_setting('polaroid_gallery_options', 'thumbnail_caption');
	register_setting('polaroid_gallery_options', 'thumbnail_option');
	register_setting('polaroid_gallery_options', 'image_option');
	register_setting('polaroid_gallery_options', 'scratches');
	register_setting('polaroid_gallery_options', 'show_in_pages');
}

function polaroid_gallery_options_add_page() {
	add_options_page('Polaroid Gallery Options', 'Polaroid Gallery', 'manage_options', 'polaroid_gallery_options', 'polaroid_gallery_options_do_page');
}

add_action('admin_init', 'polaroid_gallery_options_init');
add_action('admin_menu', 'polaroid_gallery_options_add_page');

function polaroid_gallery_options_do_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Polaroid Gallery Options', 'polaroid-gallery') ?></h2>
		<form method="post" action="options.php">
			<?php 
			settings_fields('polaroid_gallery_options');
			$image_size			= get_option('image_size', 'large'); 
			$ignore_columns		= get_option('ignore_columns', 'no'); 
			$custom_text		= get_option('custom_text', 'no');
			$custom_text_value	= get_option('custom_text_value', 'Image');
			$thumbnail_caption	= get_option('thumbnail_caption', 'show'); 
			$thumbnail_option	= get_option('thumbnail_option', 'none'); 
			$image_option		= get_option('image_option', 'title3');
			$scratches			= get_option('scratches', 'yes');
			$show_in_pages		= get_option('show_in_pages', 'yes');
			?>
			<h3><?php _e('Gallery Settings', 'polaroid-gallery'); ?></h3>
			<p><?php _e('Choose the image size to display when user clicks the thumbnail. Images will be scaled to fit the screen if they are too large.', 'polaroid-gallery'); ?></p>
			<p><?php _e('You can adjust the image sizes via Settings -> Media.', 'polaroid-gallery'); ?></p>
			<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Image sizes', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Size', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("Medium", 'polaroid-gallery'); ?>'><input type='radio' name='image_size' value='medium' <?php checked('medium', $image_size); ?>/> <?php _e("Medium", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Large", 'polaroid-gallery'); ?>'><input type='radio' name='image_size' value='large' <?php checked('large', $image_size); ?>/> <?php _e("Large", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Full size", 'polaroid-gallery'); ?>'><input type='radio' name='image_size' value='full' <?php checked('full', $image_size); ?>/> <?php _e("Full size", 'polaroid-gallery'); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Run gallery everywhere', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Load gallery in list of posts', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("No", 'polaroid-gallery'); ?>'><input type='radio' name='show_in_pages' value='no' <?php checked('no', $show_in_pages); ?>/> <?php _e("No (it is good for blogs where gallery is used only in posts)", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Yes", 'polaroid-gallery'); ?>'><input type='radio' name='show_in_pages' value='yes' <?php checked('yes', $show_in_pages); ?>/> <?php _e("Yes (it is good for web sites where gallery is used on custom pages and it is good for blogs where posts are displayed full (no excerpt) in the list of posts)", 'polaroid-gallery'); ?></label><br />
 					</fieldset>
 				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Ignore Gallery columns', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Ignore Gallery columns', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("No", 'polaroid-gallery'); ?>'><input type='radio' name='ignore_columns' value='no' <?php checked('no', $ignore_columns); ?>/> <?php _e("No", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Yes", 'polaroid-gallery'); ?>'><input type='radio' name='ignore_columns' value='yes' <?php checked('yes', $ignore_columns); ?>/> <?php _e("Yes (good for fluid layouts)", 'polaroid-gallery'); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Add scratches to thumbnails', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Add scratches to thumbnails', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("No", 'polaroid-gallery'); ?>'><input type='radio' name='scratches' value='no' <?php checked('no', $scratches); ?>/> <?php _e("No", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Yes", 'polaroid-gallery'); ?>'><input type='radio' name='scratches' value='yes' <?php checked('yes', $scratches); ?>/> <?php _e("Yes", 'polaroid-gallery'); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Custom text for "Image"', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Custom text for "Image"', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("No", 'polaroid-gallery'); ?>'><input type='radio' name='custom_text' value='no' <?php checked('no', $custom_text); ?>/> <?php _e("No (localized default text)", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Yes", 'polaroid-gallery'); ?>'><input type='radio' name='custom_text' value='yes' <?php checked('yes', $custom_text); ?>/> <?php _e("Yes", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Text", 'polaroid-gallery'); ?>'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Text", 'polaroid-gallery'); ?>: <input type='text' name='custom_text_value' value='<?php print $custom_text_value; ?>' /></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Thumbnail text visibility', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Thumbnail text visibility', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("Always visible", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_caption' value='show' <?php checked('show', $thumbnail_caption); ?>/> <?php _e("Always visible", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Visible with mouseover", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_caption' value='hide' <?php checked('hide', $thumbnail_caption); ?>/> <?php _e("Visible with mouseover", 'polaroid-gallery'); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Thumbnail text settings', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Thumbnail text settings', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("None", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_option' value='none' <?php checked('none', $thumbnail_option); ?>/> <?php _e("None", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Caption text", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_option' value='caption' <?php checked('caption', $thumbnail_option); ?>/> <?php _e("Caption text", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Image #", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_option' value='image1' <?php checked('image1', $thumbnail_option); ?>/> <?php _e("Image #", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Image #/#", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_option' value='image2' <?php checked('image2', $thumbnail_option); ?>/> <?php _e("Image #/#", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("#", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_option' value='number1' <?php checked('number1', $thumbnail_option); ?>/> <?php _e("#", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("#/#", 'polaroid-gallery'); ?>'><input type='radio' name='thumbnail_option' value='number2' <?php checked('number2', $thumbnail_option); ?>/> <?php _e("#/#", 'polaroid-gallery'); ?></label><br />
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Image text settings', 'polaroid-gallery') ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php _e('Image text settings', 'polaroid-gallery') ?></span></legend>
						<label title='<?php _e("None", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='none' <?php checked('none', $image_option); ?>/> <?php _e("None", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Title text", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='title1' <?php checked('title1', $image_option); ?>/> <?php _e("Title text", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("#  Title text", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='title2' <?php checked('title2', $image_option); ?>/> <?php _e("#  Title text", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("#/#  Title text", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='title3' <?php checked('title3', $image_option); ?>/> <?php _e("#/#  Title text", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Image #  Title text", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='title4' <?php checked('title4', $image_option); ?>/> <?php _e("Image #  Title text", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Image #/#  Title text", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='title5' <?php checked('title5', $image_option); ?>/> <?php _e("Image #/#  Title text", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Image #", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='image1' <?php checked('image1', $image_option); ?>/> <?php _e("Image #", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("Image #/#", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='image2' <?php checked('image2', $image_option); ?>/> <?php _e("Image #/#", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("#", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='number1' <?php checked('number1', $image_option); ?>/> <?php _e("#", 'polaroid-gallery'); ?></label><br />
						<label title='<?php _e("#/#", 'polaroid-gallery'); ?>'><input type='radio' name='image_option' value='number2' <?php checked('number2', $image_option); ?>/> <?php _e("#/#", 'polaroid-gallery'); ?></label><br />
					</fieldset>
				</td>
			</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'polaroid-gallery') ?>" />
			</p>
		</form>
	</div>
	<?php
}

/** plugin code **/
function polaroid_gallery_enqueue() {
	if (isTimeToLoadGallery()) {
		global $wp_styles;
		$polaroid_gallery_plugin_prefix = plugin_dir_url(__FILE__);

		// detect PC user 
		if (!isMobileDetected()){
			// add javascript to footer
			wp_enqueue_script('jquery.easing-1.3', ('//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'), array('jquery'), false, true);
			wp_enqueue_script('jquery.mousewheel-3.0.4', ('//cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.mousewheel-3.0.4.pack.js'), array('jquery'), false, true);
			wp_enqueue_script('jquery.fancybox-1.3.4', ('//cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js'), array('jquery'), false, true);
			wp_enqueue_script('polaroid_gallery-2.1', ($polaroid_gallery_plugin_prefix.'js/polaroid_gallery-2.1.min.js'), array('jquery'), false, true);

			// add css to head
			wp_enqueue_style('polaroid_gallery_fancybox', ($polaroid_gallery_plugin_prefix . 'css/jquery.fancybox-1.3.4.min.css'));
			wp_enqueue_style('polaroid_gallery_style-2.1', ($polaroid_gallery_plugin_prefix . 'css/polaroid_gallery.min.css'));

			//add font to footer
			wp_enqueue_style('gocha-hand', "//fonts.googleapis.com/css?family=Gochi+Hand");

			// add IE css to head
			wp_enqueue_style('polaroid_gallery_ie_style-2.1', ($polaroid_gallery_plugin_prefix . 'css/jquery.fancybox-old-ie.css'));
			$wp_styles->add_data('polaroid_gallery_ie_style-2.1', 'conditional', 'lte IE 8');
		}else
		{
			// detect mobile user
			wp_enqueue_style('polaroid_gallery_style-2.1', ($polaroid_gallery_plugin_prefix . 'css/polaroid_gallery_mobile.min.css'));
		}

		// add localized javascript to head
		$custom_text		= get_option('custom_text', 'no');
		$custom_text_value	= get_option('custom_text_value', 'Image');
		$thumbnail_option	= get_option('thumbnail_option', 'none');
		$image_option		= get_option('image_option', 'title3');
		$scratches			= get_option('scratches', 'yes');
		$text2image 		= __('Image', 'polaroid-gallery');

		if($custom_text == 'yes') {
			$text2image = $custom_text_value;
		}

		$params = array(
			'text2image' => $text2image,
			'thumbnail' => $thumbnail_option,
			'image' => $image_option,
			'scratches' => $scratches,
		);
		wp_localize_script('polaroid_gallery-2.1', 'polaroid_gallery', $params);
	}
}

function polaroid_gallery_shortcode($output, $attr) {
	if (!isTimeToLoadGallery()) return $output;
	if (isset($attr['usedefault']) && $attr['usedefault']=="true") return $output;
	global $post, $wp_locale;
	
	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}
	
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));
	
	$image_size			= get_option('image_size', 'large'); 
	$ignore_columns		= get_option('ignore_columns', 'no'); 
	$thumbnail_caption	= get_option('thumbnail_caption', 'show');
	
	$id = intval($id);
	if ( 'RAND' == $order ) {
		$orderby = 'none';
	}
	
	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}
	
	if ( empty($attachments) ) {
		return '';
	}
	
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}
	
	// detect PC user 
	if (!isMobileDetected()){
		$output = makeGalleryForPCandTablets($attachments, $thumbnail_caption, $id, $image_size, $ignore_columns, $columns);
	}else
	{
		$output = makeGalleryForPhones($attachments, $thumbnail_caption, $id, $image_size);
	}

	return $output;	
}

/**
	 * Registers additional links for the Polaroid Gallery plugin on the WP plugin configuration page
	 *
	 * Registers the links if the $file param equals to the sitemap plugin
	 * @param $links Array An array with the existing links
	 * @param $file string The file to compare to
	 * @return string[]
*/
function polaroid_gallery_register_plugin_links($links, $file) {
	$base = basename(__FILE__);
	if(basename($file) == $base) {
		$links[] = '<a href="options-general.php?page=polaroid_gallery_options">' . __('Settings', 'polaroid-gallery') . '</a>';
		$links[] = '<a href="http://wordpress.org/plugins/polaroid-gallery/faq/">' . __('FAQ', 'polaroid-gallery') . '</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/polaroid-gallery">' . __('Support', 'polaroid-gallery') . '</a>';
		$links[] = '<a href="http://goo.gl/0gvUvm">' . __('Donate', 'polaroid-gallery') . '</a>';
	}
	return $links;
}

function makeGalleryForPCandTablets($attachments, $thumbnail_caption, $id, $image_size, $ignore_columns, $columns){
	$gallery_width = "auto";
	// columns - items per column
	$columns = intval($columns);
	if( $ignore_columns == 'yes' ) {
		$columns = 0;
	}else {
		// to calc width for automaticall centering gallery
		// padding is item padding defined in css
		$padding = 20;
		// image_width is thumbnail width defined in wp settings
		$image_width = get_image_width("thumbnail");
		$gallery_width = (($image_width + $padding) * $columns)."px";
	}	

	$output = "<div class='polaroid-gallery galleryid-{$id}' style='width:".$gallery_width.";'>";
	
	$images_counter = 0;
	
	$galleryGroupId = rand();
	
	$caption_class = '';
	if($thumbnail_caption == 'show') {
		$caption_class = ' showcaption';
	}

	foreach ( $attachments as $id => $attachment ) {
		$image = wp_get_attachment_image_src($id, $size=$image_size, $icon = false); 
		$thumb = wp_get_attachment_image_src($id, $size='thumbnail', $icon = false); 
		$title = wptexturize(trim($attachment->post_title));
		/*if ($title == "")
		{
			$title = wptexturize(trim($attachment->post_excerpt));
		}*/
		$alt = wptexturize(trim($attachment->post_excerpt));
		
		$output .= '
			<a href="'. $image[0] .'" title="'. $title .'" rel="polaroid_'. $galleryGroupId .'" class="polaroid-gallery-item'. $caption_class .'"><span class="polaroid-gallery-image" title="'. $alt .'" style="background-image: url('. $thumb[0] .'); width: '. $thumb[1] .'px; height: '. $thumb[2] .'px;"></span></a>';
		
		if ( $columns > 0 && ++$images_counter % $columns == 0 ){
			$output .= '
			<br style="clear: both;" />';
		}
	}
	if ( $columns > 0 && $images_counter % $columns != 0 ) {
		$output .= '
			<br style="clear: both;" />';
	}
	if( $ignore_columns == 'yes' ) {
		$output .= '
			<br style="clear: both;" />';
	}
	$output .= "</div>\n";
	
	return $output;
}

function makeGalleryForPhones($attachments, $thumbnail_caption, $id, $image_size){
	$output = "<div class='polaroid-gallery galleryid-{$id}'>";
	
	$caption_class = '';
	if($thumbnail_caption == 'show') {
		$caption_class = ' showcaption';
	}

	foreach ( $attachments as $id => $attachment ) {
		$image = wp_get_attachment_image_src($id, $size=$image_size, $icon = false); 
		$title = wptexturize(trim($attachment->post_title));
		if ($title == "")
		{
			$title = wptexturize(trim($attachment->post_excerpt));
		}
		$alt = wptexturize(trim($attachment->post_excerpt));
		
		$output .= '
			<a class="polaroid-gallery-item'. $caption_class .'">
				<img id="polaroid-gallery-image" src='. $image[0] .' style="width:'.$image[1].'px;" title="'. $alt .'" />
				<span class="polaroid-gallery-text">'.$title.'
				</span>
			</a>';

			$output .= '
			<br style="clear: both;" />';

	}

	$output .= "</div>\n";
	
	return $output;
}

function isMobileDetected() {
	if (!class_exists("Mobile_Detect")) {
		require_once plugin_dir_path(__FILE__) . 'Mobile_Detect.php';
	}
	$detect = new Mobile_Detect;
	// detect PC user 
	return ($detect->isMobile() AND !$detect->isTablet());
}

function isTimeToLoadGallery(){
	$show_in_pages = (get_option('show_in_pages', 'yes') === 'yes');
	return (!is_admin() AND ($show_in_pages OR is_single() OR is_feed()));
}

add_action('wp_enqueue_scripts', 'polaroid_gallery_enqueue');
//Shortcode
add_filter('post_gallery', 'polaroid_gallery_shortcode', 10, 2);
//Additional links on the plugin page
add_filter('plugin_row_meta', 'polaroid_gallery_register_plugin_links', 10, 2);
?>