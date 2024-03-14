<?php
/*
Plugin Name: Auto Featured Image from Title
Version: 2.3
Description: Automatically generates an image from the post title and sets it as the featured image
Author: Chris Huff
Author URI: http://designsbychris.com
Plugin URI: http://designsbychris.com/auto-featured-image-from-title
License: GPLv2 or later
*/

// Set up a few global variables
global $afift_images_path;
$afift_images_path = plugin_dir_path( __FILE__ ) . 'images/';

global $afift_fonts_path;
$afift_fonts_path = plugin_dir_path( __FILE__ ) . 'fonts/';

    // Set all the options if they don't exist yet
    add_option('auto_image_pages',"yes");
    add_option('auto_image_posts',"yes");
	add_option('auto_image_content_length',250);
	add_option('auto_image_remove_linebreaks',"yes");
	add_option('auto_image_default_disable',"");
    add_option('auto_image_write_text',"yes");
    add_option('auto_image_text',"title");
    add_option('auto_image_width',640);
    add_option('auto_image_height',360);
    add_option('auto_image_bg_image',"sunset.jpg");
    add_option('auto_image_bg_color',"#b5b5b5");
    add_option('auto_image_fontface',"chunkfive.ttf");
    add_option('auto_image_fontsize',30);
    add_option('auto_image_text_color',"#fff76d");
    add_option('auto_image_shadow',"yes");
    add_option('auto_image_shadow_color',"#000000");
    add_option('auto_image_set_first',"");

    if(get_option('auto_image_fontface')=='Windsong.ttf'){
        update_option('auto_image_fontface', 'AguafinaScript.ttf');
        }
    if(get_option('auto_image_fontface')=='CaviarDreams.ttf'){
        update_option('auto_image_fontface', 'Railway.ttf');
        }

// This is where the magic happens!
function auto_featured_image_from_title ($post_id) {

    // Frequently used variables
    global $afift_images_path;
    global $afift_fonts_path;
    $post = get_post( $post_id );

    // Don't run if the post doesn't even have an ID yet
    if (!isset($post->ID) )
    return;

    // Check to see if the post already has a featured image
    if ( '' != get_the_post_thumbnail($post->ID) )
    return;

    // If this is just a revision, don't generate an image
    if ( wp_is_post_revision($post->ID) )
    return;

    // If post is in the trash, don't generate an image
    $post_status = get_post_status($post->ID);
    if($post_status == 'trash')
    return;

    // If the post meta says not to, guess what, don't generate an image
    $disable_post = get_post_meta($post->ID, 'afift-disable', true);
	if($disable_post == "yes")
	return;

    // Try to prevent the script from timing out or running out of memory
    set_time_limit(0);
    wp_cache_flush();

    // Get options from database
    $auto_image_pages = get_option('auto_image_pages');
    $auto_image_posts = get_option('auto_image_posts');
    $auto_image_default_disable = get_option('auto_image_default_disable');
    $auto_image_text = get_option('auto_image_text');
    $auto_image_content_length = get_option('auto_image_content_length');
	$auto_image_remove_linebreaks = get_option('auto_image_remove_linebreaks');
    $auto_image_write_text = get_option('auto_image_write_text');
    $auto_image_width = get_option('auto_image_width');
    $auto_image_height = get_option('auto_image_height');
    $auto_image_bg_image = get_option('auto_image_bg_image');
    $auto_image_bg_color = get_option('auto_image_bg_color');
    $auto_image_fontface = get_option('auto_image_fontface');
    $auto_image_fontsize = get_option('auto_image_fontsize');
    $auto_image_text_color = get_option('auto_image_text_color');
    $auto_image_shadow = get_option('auto_image_shadow');
    $auto_image_shadow_color = get_option('auto_image_shadow_color');
    $auto_image_set_first = get_option('auto_image_set_first');

    // Only run on pages and posts if the option is set to yes
    if (($post->post_type =='page') && ($auto_image_pages == 'no'))
    return;
    if (($post->post_type =='post') && ($auto_image_posts == 'no'))
    return;

    // Make sure the post text has been given to the post
    $auto_image_post_title = html_entity_decode(strip_tags(get_the_title($post->ID)),ENT_QUOTES,'UTF-8');

    if($auto_image_text=='content'){
		$auto_image_post_content = html_entity_decode(strip_tags($post->post_content));
	  	$auto_image_post_text = $auto_image_post_content;
        }
    elseif($auto_image_text=='excerpt'){
        $auto_image_post_excerpt = html_entity_decode(get_the_excerpt());
	    $auto_image_post_text = $auto_image_post_excerpt;
        }
    else {
        $auto_image_post_text = $auto_image_post_title;
        }
    if (( $auto_image_post_text == '' ) || ( $auto_image_post_text == 'Auto Draft' ))
    return;

    if(strlen($auto_image_post_text) > $auto_image_content_length){
        $auto_image_post_text = substr($auto_image_post_text, 0, $auto_image_content_length);
        $auto_image_post_text .= '...';
        }
  
    // Separate hexidecimal colors into red, green, and blue strings
    if(!function_exists('afift_hex2rgbcolors')){
        function afift_hex2rgbcolors($c){
            $c = str_replace("#", "", $c);
            if(strlen($c) == 3){
                $r = hexdec( $c[0] . $c[1] );
                $g = hexdec( $c[1] . $c[1] );
                $b = hexdec( $c[2] . $c[1] );
                }
            elseif (strlen($c) == 6 ){
                $r = hexdec( $c[0] . $c[2] );
                $g = hexdec( $c[2] . $c[2] );
                $b = hexdec( $c[4] . $c[2] );
                }
            else{
                $r = 'ff';
                $g = 'ff';
                $b = '00';
                }
            return Array("red" => $r, "green" => $g, "blue" => $b);
            }
        }

    $bg = afift_hex2rgbcolors($auto_image_bg_color);
    $text = afift_hex2rgbcolors($auto_image_text_color);
    $shadow = afift_hex2rgbcolors($auto_image_shadow_color);

    // Set the background image
    $backgroundimg = $afift_images_path . $auto_image_bg_image;

    // Start generating the image
    if($auto_image_bg_image=='blank.jpg'){
        $new_featured_img = imagecreatetruecolor($auto_image_width, $auto_image_height);
        $background_color = imagecolorallocate( $new_featured_img, $bg["red"], $bg["green"], $bg["blue"]);
        imagefill($new_featured_img, 0, 0, $background_color);
        }
    else {
        $new_featured_img = imagecreatefromjpeg($backgroundimg);

        $width = imagesx($new_featured_img);
        $height = imagesy($new_featured_img);

        $original_aspect = $width / $height;
        $auto_image_aspect = $auto_image_width / $auto_image_height;

        if ( $original_aspect >= $auto_image_aspect ){
            // If original image is wider than new generated image (in aspect ratio sense)
            $new_height = $auto_image_height;
            $new_width = $width / ($height / $auto_image_height);
            }
        else {
            // If new generated image is wider than original image
            $new_width = $auto_image_width;
            $new_height = $height / ($width / $auto_image_width);
            }

        $auto_image = imagecreatetruecolor( $auto_image_width, $auto_image_height );

        // Resize and crop
        imagecopyresampled(
            $auto_image,
            $new_featured_img,
            // Center the image horizontally
            0 - ($new_width - $auto_image_width) / 2,
            // Center the image vertically
            0 - ($new_height - $auto_image_height) / 2,
            0, 0,
            $new_width, $new_height,
            $width, $height);
        $new_featured_img = $auto_image;
        }

    if($auto_image_write_text=='yes'){
        $text_color = imagecolorallocate( $new_featured_img, $text["red"], $text["green"], $text["blue"]);
        $shadow_color = imagecolorallocate( $new_featured_img, $shadow["red"], $shadow["green"], $shadow["blue"]);
        $font = $afift_fonts_path . $auto_image_fontface;

		$auto_image_top_padding = 20;
		$auto_image_bottom_padding = 20;
		$auto_image_left_padding = 20;
		$auto_image_right_padding = 20;

        $auto_image_top_bottom_padding = $auto_image_top_padding + $auto_image_bottom_padding;
        $auto_image_left_right_padding = $auto_image_left_padding + $auto_image_right_padding;

        $auto_image_transformed_post_text = $auto_image_post_text;

        $auto_image_transformed_post_text = str_replace('  ', ' ', $auto_image_transformed_post_text);
        $auto_image_transformed_post_text = str_replace('&#160;',' ',$auto_image_transformed_post_text);
        $auto_image_transformed_post_text = str_replace(' ',' ',$auto_image_transformed_post_text);
  		$auto_image_transformed_post_text = str_replace("\r", '', $auto_image_transformed_post_text);
  		$auto_image_transformed_post_text = str_replace('&#13;', '', $auto_image_transformed_post_text);

		if($auto_image_remove_linebreaks == 'yes'){
			$auto_image_transformed_post_text = str_replace("\n", ' ', $auto_image_transformed_post_text);
			}
		else{
			$auto_image_transformed_post_text = str_replace("\n", ' #10;', $auto_image_transformed_post_text);
			}

        $words = explode(" ", $auto_image_transformed_post_text);

        $auto_image_fontsize = $auto_image_fontsize + 3;

        do {
            $auto_image_fontsize = $auto_image_fontsize - 3;

		    // Unset variables if this is a subsequent attempt at writing the text
            if(isset($auto_image_text_x)){
                unset($auto_image_text_x);
                unset($auto_image_text_xx);
                unset($auto_image_text_y);
                unset($row);
                }

            // Position the text (the whole string)
            $auto_image_text_array = imagettfbbox($auto_image_fontsize, 0, $font, $auto_image_transformed_post_text);

            $auto_image_text_x[] = ($auto_image_width - $auto_image_text_array[2]) / 2;
            $auto_image_text_xx[] = $auto_image_text_array[2];
            $auto_image_text_y[] = abs($auto_image_text_array[5]);

		    $string = '';
            $tmp_string = '';
		    $before_break = '';
		    $after_break = '';

            $auto_image_text_array['height'] = abs($auto_image_text_array[7]) - abs($auto_image_text_array[1]);
            if($auto_image_text_array[3] > 0) {
                $auto_image_text_array['height'] = abs($auto_image_text_array[7] - $auto_image_text_array[1]) - 1;
                }
            $lineheight = $auto_image_text_array['height'] + 10;

            $ny = 0;
			for($i = 0; $i < count($words) || $before_break != ''; $i++) {

			    if($before_break != ''){
				    $tmp_string = $after_break;
				    $before_break = '';
                    }

			    // Add a word to the tmp string
		  		if($i>=count($words)){
				    $words[$i] = '';
				    }
                $tmp_string .= $words[$i]." ";

			    // Remove a line break if it begins the string
                if(substr($tmp_string, 0, 4) == '#10;'){
                    $tmp_string = substr($tmp_string, 4);
                    }

                // Check width of the last string to see if it fits within image
                $dim = imagettfbbox($auto_image_fontsize, 0, $font, rtrim($tmp_string));

                // Check to see if there is a line break in the tmp string
                $before_break = strstr($tmp_string, '#10;', true);
                $after_break = strstr($tmp_string, '#10;');

				if($dim[4] < ($auto_image_width-$auto_image_left_right_padding)) {
                    // If it fits, save it as a row
			        if($before_break != ''){
				        $string = rtrim($before_break);
                        $row[$ny] = rtrim($before_break);

                        $auto_image_text_array = imagettfbbox($auto_image_fontsize, 0, $font, rtrim($string));

		                $auto_image_text_x[$ny] = ($auto_image_width - $auto_image_text_array[2]) / 2;
		                $auto_image_text_xx[$ny] = $auto_image_text_array[2];
                        $auto_image_text_y[$ny+1] = $auto_image_text_y[$ny] + $lineheight;
                        $ny++;
                        }
			        else{
                        $string = rtrim($tmp_string);
                        $row[$ny] = rtrim($tmp_string);
					    }
					}
				else {
                    $tmp_string = '';
		            $before_break = '';
		            $after_break = '';
                    
				    // If it doesn't fit, get the width of the whole string
                    $auto_image_text_array = imagettfbbox($auto_image_fontsize, 0, $font, rtrim($string));

	                $auto_image_text_x[$ny] = ($auto_image_width - $auto_image_text_array[2]) / 2;
	                $auto_image_text_xx[$ny] = $auto_image_text_array[2];

				    $row[$ny] = $string;
				    $string = '';
                    $auto_image_text_y[$ny+1] = $auto_image_text_y[$ny] + $lineheight;
				    $i--;
                    $ny++;
 	                }
			    }

            $auto_image_text_array = imagettfbbox($auto_image_fontsize, 0, $font, $string);

            $auto_image_text_x[$ny] = ($auto_image_width - $auto_image_text_array[2]) / 2;
            $auto_image_text_xx[$ny] = $auto_image_text_array[2];

            $rowsoftext = count($row);
            $bottom_of_text = ($lineheight*$rowsoftext)-10;
            $longest_row_x = min($auto_image_text_x);
			$longest_row_xx = max($auto_image_text_xx);
            } while (($bottom_of_text > ($auto_image_height - $auto_image_top_bottom_padding)) || ($longest_row_xx > ($auto_image_width - $auto_image_left_right_padding)));

        $offset = ($auto_image_height - $auto_image_top_bottom_padding - $bottom_of_text)/2 + $auto_image_top_padding;

		for($i = 0; $i < $rowsoftext; $i++) {
	        $auto_image_text_x[$i] = $auto_image_text_x[$i] + $auto_image_left_padding - $auto_image_right_padding;
		    }

		$i = 0;
        $row = apply_filters('afift_pro_before_write_rows', $row);
        $auto_image_text_x = apply_filters('afift_pro_before_write_rows', $auto_image_text_x);
        while ($i < $rowsoftext){
            if($auto_image_shadow=='yes'){
                imagettftext($new_featured_img, $auto_image_fontsize, 0, $auto_image_text_x[$i]+2, $auto_image_text_y[$i]+$offset+2, $shadow_color, $font, rtrim($row[$i]));
                }
            imagettftext($new_featured_img, $auto_image_fontsize, 0, $auto_image_text_x[$i], $auto_image_text_y[$i]+$offset, $text_color, $font, rtrim($row[$i]));
            $i++;
            }
	    }
	  
    if($auto_image_post_title == ''){
        $auto_image_post_title = 'image';
	    }
  
    // Save the image
    $regex = array('/[^\p{L}\p{N}\s]/u', '/\s/');
    $repl  = array('', '-');
    $post_slug = preg_replace($regex, $repl, $auto_image_post_title);
    $upload_dir = wp_upload_dir();
    $slug_n = '';
    while(file_exists($upload_dir['path'] . '/' . $post_slug . $slug_n . '.png')){ 
        if($slug_n == ''){ 
            $slug_n = 1; 
            } 
        else { 
            $slug_n++; 
            } 
        } 
    $newimg = $upload_dir['path'] . '/' . $post_slug . $slug_n . '.png';
    imagepng( $new_featured_img, $newimg );
    if($auto_image_write_text=='yes'){
        imagecolordeallocate( $new_featured_img, $text_color );
        imagecolordeallocate( $new_featured_img, $shadow_color );
	    }
    if($auto_image_bg_image=='blank.jpg'){
        imagecolordeallocate( $new_featured_img, $background_color );
        }

    // Process the image into the Media Library
    $newimg_url = $upload_dir['url'] . '/' . $post_slug . $slug_n . '.png';
    $attachment = array(
        'guid'           => $newimg_url, 
        'post_mime_type' => 'image/png',
        'post_title'     => $auto_image_post_title,
        'post_excerpt'   => $auto_image_post_text,
        'post_content'   => $auto_image_post_text,
        'post_status'    => 'inherit'
        );
    $attach_id = wp_insert_attachment( $attachment, $newimg, $post->ID );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $newimg );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    update_post_meta( $attach_id, '_wp_attachment_image_alt', wp_slash($auto_image_post_title) );

    // Set the image as the featured image
    set_post_thumbnail( $post->ID, $attach_id );
    }

add_action( 'wp_insert_post', 'auto_featured_image_from_title', 99999 );

// Saves values from check boxes when saving a post
function afift_meta_box($object){
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="afift-disable">
			  <input name="afift-disable" type="hidden" value="no">
			  <input name="afift-disable" type="checkbox" value="yes"<?php
                if((!is_array(get_post_custom_keys($object->ID))) || (is_array(get_post_custom_keys($object->ID)) && (!in_array('afift-disable', get_post_custom_keys($object->ID))))){
				    if(get_option('auto_image_default_disable') == 'yes'){
					    echo ' checked';
					    }
				    }
                else{
                    $checkbox_value = get_post_meta($object->ID, "afift-disable", true);
                    if($checkbox_value == "yes"){
					    echo ' checked';
				        }
				    }
			    ?>>Disable for this post</label>
            <?php
            $afift_disable_set_first = get_option('auto_image_set_first');
            if($afift_disable_set_first == 'yes'){ ?>

            <br />
            <label for="afift-disable-set-first"><input name="afift-disable-set-first" type="checkbox" value="yes"<?php
                $checkbox_value = get_post_meta($object->ID, "afift-disable-set-first", true);
                if($checkbox_value == "yes"){
                    echo ' checked';
				    } ?>>Disable setting from first image for this post</label><?php
			    } ?>
        </div>
    <?php  
}

// Saves values from check boxes when saving a post
function add_afift_meta_box(){
    add_meta_box("afift-disable", "Auto Featured Image", "afift_meta_box", "post", "side", "low", "high");
    add_meta_box("afift-disable", "Auto Featured Image", "afift_meta_box", "page", "side", "low", "high");
    }
add_action("add_meta_boxes", "add_afift_meta_box");

function save_afift_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $meta_box_checkbox_value = "";
    $meta_box_set_first_checkbox_value = "";

    if(isset($_POST["afift-disable"]))
    {
        $meta_box_checkbox_value = $_POST["afift-disable"];
    }   
    update_post_meta($post_id, "afift-disable", $meta_box_checkbox_value);
	  
    if(isset($_POST["afift-disable-set-first"]))
    {
        $meta_box_set_first_checkbox_value = $_POST["afift-disable-set-first"];
    }   
    update_post_meta($post_id, "afift-disable-set-first", $meta_box_set_first_checkbox_value);
}

add_action("save_post", "save_afift_meta_box", 10, 3);

// Set the first image in the post as the featured image, if there isn't one yet
function afift_set_first_image($post_id) {

    $post = get_post( $post_id );

    // Make sure the featured image isn't set yet
    if (get_the_post_thumbnail($post->ID) != '')
    return;

    // Make sure the option to do this is set to yes
    $auto_image_set_first = get_option('auto_image_set_first');
    if($auto_image_set_first != 'yes')
	return;

    // Make sure the option isn't disabled in post meta
    $disable_set_first = get_post_meta($post->ID, 'afift-disable-set-first', true);
    if($disable_set_first == "yes"){
	    return;
        }
  
    // Only run on pages and posts if the option is set to yes
    $auto_image_pages = get_option('auto_image_pages');
    $auto_image_posts = get_option('auto_image_posts');
    if (($post->post_type =='page') && ($auto_image_pages == 'no'))
    return;
    if (($post->post_type =='post') && ($auto_image_posts == 'no'))
    return;

    // Use the first image in the post, if there is one
    $first_img = '';
    $backgroundimg = '';
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if($output != ''){
        $backgroundimg = $matches [1] [0];
        }

	if ( !$backgroundimg || strlen( $backgroundimg ) == 0 )
		return false;

    $auto_image_width = get_option('auto_image_width');
    $auto_image_height = get_option('auto_image_height');
  
    $ext = strtolower(pathinfo($backgroundimg, PATHINFO_EXTENSION));
    if($ext == 'png'){
        $new_featured_img = imagecreatefrompng($backgroundimg);
        }
    elseif($ext == 'gif'){
        $new_featured_img = imagecreatefromgif($backgroundimg);
        }
    else{
        $new_featured_img = imagecreatefromjpeg($backgroundimg);
        }
    $width = imagesx($new_featured_img);
    $height = imagesy($new_featured_img);

    $original_aspect = $width / $height;
    $auto_image_aspect = $auto_image_width / $auto_image_height;

    if ( $original_aspect >= $auto_image_aspect ){
        // If original image is wider than new generated image (in aspect ratio sense)
        $new_height = $auto_image_height;
        $new_width = $width / ($height / $auto_image_height);
        }
    else {
        // If new generated image is wider than original image
        $new_width = $auto_image_width;
        $new_height = $height / ($width / $auto_image_width);
        }

    $auto_image = imagecreatetruecolor( $auto_image_width, $auto_image_height );

    // Resize and crop
    imagecopyresampled(
        $auto_image,
        $new_featured_img,
        // Center the image horizontally
        0 - ($new_width - $auto_image_width) / 2,
        // Center the image vertically
        0 - ($new_height - $auto_image_height) / 2,
        0, 0,
        $new_width, $new_height,
        $width, $height);
    $new_featured_img = $auto_image;

    $auto_image_post_title = html_entity_decode(strip_tags(get_the_title($post->ID)),ENT_QUOTES,'UTF-8');
    if($auto_image_post_title == ''){
        $auto_image_post_title = 'image';
	    }
	  
    // Save the image
    $attachment_array = array(
        'title'          => $auto_image_post_title,
        'alt'            => $auto_image_post_title,
        'caption'        => $auto_image_post_title,
        'description'    => $auto_image_post_title,
        'filename'       => $auto_image_post_title,
        'filename_spaces' => '-'
        );
    $attachment_array = apply_filters('afift_pro_before_save_image', $attachment_array, $post_id);
    $regex = array('/[^\p{L}\p{N}\s]/u', '/\s/');
    $repl  = array('', $attachment_array['filename_spaces']);
    $post_slug = strtolower(preg_replace($regex, $repl, $attachment_array['filename']));
    $upload_dir = wp_upload_dir();
    $slug_n = ''; 
    while(file_exists($upload_dir['path'] . '/' . $post_slug . $slug_n . '.jpg')){ 
        if($slug_n == ''){ 
            $slug_n = 1; 
            } 
        else { 
            $slug_n++; 
            } 
        } 
    $newimg = $upload_dir['path'] . '/' . $post_slug . $slug_n . '.jpg';
    imagejpeg( $new_featured_img, $newimg, 100 );

    // Process the image into the Media Library
    $newimg_url = $upload_dir['url'] . '/' . $post_slug . $slug_n . '.jpg';
    $attachment = array(
        'guid'           => $newimg_url, 
        'post_mime_type' => 'image/jpeg',
        'post_title'     => $attachment_array['title'],
        'post_excerpt'   => $attachment_array['caption'],
        'post_content'   => $attachment_array['description'],
        'post_status'    => 'inherit'
        );
    $attach_id = wp_insert_attachment( $attachment, $newimg, $post->ID );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $newimg );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    update_post_meta( $attach_id, '_wp_attachment_image_alt', wp_slash($attachment_array['alt']) );

    // Set the image as the featured image
    set_post_thumbnail( $post->ID, $attach_id );
    }

add_action( 'wp_insert_post', 'afift_set_first_image', 999999 );

// Load the color picker on the admin page
function afift_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'auto_featured_image', plugins_url('colorpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }

add_action( 'admin_enqueue_scripts', 'afift_enqueue_color_picker' );

// Set up the admin page
function afift_settings() {

    //create new top-level menu
    add_options_page('Auto Featured Image', 'Auto Featured Image', 'manage_options', 'auto-featured-image-from-title.php', 'afift_settings_page');
    add_filter( "plugin_action_links", "afift_settings_link", 10, 2 );
    //call register settings function
    add_action( 'admin_init', 'register_auto_featured_image' );
}

function afift_settings_link($links, $file) {
    static $this_plugin;
        if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
        if ($file == $this_plugin){
    $afift_settings_link = '<a href="options-general.php?page=auto-featured-image-from-title.php">'.__("Settings", "auto-featured-image-from-title").'</a>';
        array_unshift($links, $afift_settings_link);
        }
    return $links;
    }

function register_auto_featured_image() {
    //register our settings
    register_setting( 'auto_featured_image_group', 'auto_image_pages' );
    register_setting( 'auto_featured_image_group', 'auto_image_posts' );
    register_setting( 'auto_featured_image_group', 'auto_image_default_disable' );
    register_setting( 'auto_featured_image_group', 'auto_image_write_text' );
    register_setting( 'auto_featured_image_group', 'auto_image_text' );
    register_setting( 'auto_featured_image_group', 'auto_image_content_length' );
    register_setting( 'auto_featured_image_group', 'auto_image_remove_linebreaks' );
    register_setting( 'auto_featured_image_group', 'auto_image_width' );
    register_setting( 'auto_featured_image_group', 'auto_image_height' );
    register_setting( 'auto_featured_image_group', 'auto_image_bg_color' );
    register_setting( 'auto_featured_image_group', 'auto_image_bg_image' );
    register_setting( 'auto_featured_image_group', 'auto_image_fontface' );
    register_setting( 'auto_featured_image_group', 'auto_image_fontsize' );
    register_setting( 'auto_featured_image_group', 'auto_image_text_color' );
    register_setting( 'auto_featured_image_group', 'auto_image_shadow' );
    register_setting( 'auto_featured_image_group', 'auto_image_shadow_color' );
    register_setting( 'auto_featured_image_group', 'auto_image_set_first' );
}

add_action('admin_menu', 'afift_settings');

function afift_css_head() {
    if ((isset($_GET['page'])) && ($_GET['page'] == 'auto-featured-image-from-title.php')){ ?>
        <style type="text/css">
        #afift {margin-right:300px;}
        #afift_settings, #afift_info {background-color:#fff;border:#ccc 1px solid; padding:15px;}
        #afift_settings {float:left;width:100%;}
        #afift_info {float:right;margin-right:-280px;width:200px;}
        #afift_info ul {list-style-type:disc;margin-left:30px;}
        #afift_settings label {display:table;}
        #afift_settings .bg_group {text-align:center;padding:10px;width:240px;float:left;}
        .showfonts {position:relative;color:#00f;}
        .showfonts span {display:none;}
        .showfonts span img {display:block;}
        .showfonts:hover span {display:block;position:absolute;top:0px;left:50px;backgroun />d-color:#fff;border:#aaa 1px solid;padding:5px;z-index:9999;} 
        #afift h2 {clear:both;}
        #afift input[type=submit] {clear:both;display:block;margin-bottom:30px;}
		.clear {clear:both;}
		.left {float:left;}
        </style>
        <?php }
    }

add_action('admin_head', 'afift_css_head');

function afift_settings_page() {

    global $afift_images_path;
    global $afift_fonts_path;

?>

<div id="afift">

<h2>Auto Featured Image From Title Settings</h2>

<div id="afift_settings">

<form method="post" action="options.php">
    <?php settings_fields( 'auto_featured_image_group' ); ?>
        <p><label for="auto_image_pages">Auto Generate Images for Pages:</label>
        <select name="auto_image_pages" id="auto_image_pages">
            <option value='yes'<?php if((get_option('auto_image_pages'))=='yes'){ echo " selected";} ?>>Yes</option>
            <option value='no'<?php if((get_option('auto_image_pages'))=='no'){ echo " selected";} ?>>No</option>
        </select></p>
        <p><label for="auto_image_posts">Auto Generate Images for Posts:</label>
        <select name="auto_image_posts" id="auto_image_posts">
            <option value='yes'<?php if((get_option('auto_image_posts'))=='yes'){ echo " selected";} ?>>Yes</option>
            <option value='no'<?php if((get_option('auto_image_posts'))=='no'){ echo " selected";} ?>>No</option>
        </select></p>

        <p><input type="checkbox" name="auto_image_default_disable" class="left" value="yes"<?php if(get_option("auto_image_default_disable")=='yes'){ echo ' checked="checked"';} ?> /><label for="auto_image_default_disable">Disable auto image generation by default</label></p>

        <p><label for="auto_image_width">Image Width:</label>
        <input name="auto_image_width" type="text" size="5" id="auto_image_width" value="<?php form_option('auto_image_width'); ?>" /></p>
        <p><label for="auto_image_height">Image Height:</label>
        <input name="auto_image_height" type="text" size="5" id="auto_image_height" value="<?php form_option('auto_image_height'); ?>" /></p>

        <p><label for="auto_image_write_text">Write Text onto Generated Image:</label>
        <select name="auto_image_write_text" id="auto_image_write_text">
            <option value='yes'<?php if((get_option('auto_image_write_text'))=='yes'){ echo " selected";} ?>>Yes</option>
            <option value='no'<?php if((get_option('auto_image_write_text'))=='no'){ echo " selected";} ?>>No</option>
        </select></p>
  
        <p><label for="auto_image_text">Text to Write onto Generated Images:</label>
        <select name="auto_image_text" id="auto_image_text">
            <option value='title'<?php if((get_option('auto_image_text'))=='title'){ echo " selected";} ?>>Post Title</option>
            <option value='excerpt'<?php if((get_option('auto_image_text'))=='excerpt'){ echo " selected";} ?>>Post Excerpt</option>
            <option value='content'<?php if((get_option('auto_image_text'))=='content'){ echo " selected";} ?>>Post Content</option>
        </select></p>

  	    <div id="content_length_div">
            <p><label for="auto_image_content_length">Maximum Text Length:</label>
            <input name="auto_image_content_length" type="text" size="10" id="auto_image_content_length" value="<?php form_option('auto_image_content_length'); ?>" /></p>
        </div>

        <p><label for="auto_image_remove_linebreaks">Remove Linebreaks:</label>
        <select name="auto_image_remove_linebreaks" id="auto_image_remove_linebreaks">
            <option value='yes'<?php if((get_option('auto_image_remove_linebreaks'))=='yes'){ echo " selected";} ?>>Yes</option>
            <option value='no'<?php if((get_option('auto_image_remove_linebreaks'))=='no'){ echo " selected";} ?>>No</option>
        </select></p>
  
        <p><label for="auto_image_text_color">Text Color:</label>
        <input name="auto_image_text_color" type="text" value="<?php form_option('auto_image_text_color'); ?>" class="my-color-field" /></p>

        <p><label for="auto_image_fontface">Font: <small class="showfonts">Show fonts<span><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/AguafinaScript.ttf.jpg"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/ChunkFive.ttf.jpg"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/LindenHill.ttf.jpg"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/Raleway.ttf.jpg"></span></small></label>
        <select name="auto_image_fontface" id="auto_image_fontface">
            <option value='AguafinaScript.ttf'<?php if((get_option('auto_image_fontface'))=='AguafinaScript.ttf'){ echo " selected";} ?>>Aguafina Script</option>
            <option value='chunkfive.ttf'<?php if((get_option('auto_image_fontface'))=='chunkfive.ttf'){ echo " selected";} ?>>Chunk Five</option>
            <option value='LindenHill.ttf'<?php if((get_option('auto_image_fontface'))=='LindenHill.ttf'){ echo " selected";} ?>>Linden Hill</option>
            <option value='Raleway.ttf'<?php if((get_option('auto_image_fontface'))=='Raleway.ttf'){ echo " selected";} ?>>Raleway</option>
        </select></p>
        <p><label for="auto_image_fontsize">Font Size (in pixels):</label>
        <input name="auto_image_fontsize" type="text" size="5" id="auto_image_fontsize" value="<?php form_option('auto_image_fontsize'); ?>" /></p>
        <p><label for="auto_image_shadow">Apply Text Shadow:</label>
        <select name="auto_image_shadow" id="auto_image_shadow">
            <option value='yes'<?php if((get_option('auto_image_shadow'))=='yes'){ echo " selected";} ?>>Yes</option>
            <option value='no'<?php if((get_option('auto_image_shadow'))=='no'){ echo " selected";} ?>>No</option>
        </select></p>
    <p><label for="auto_image_shadow_color">Shadow Color:</label>
        <input name="auto_image_shadow_color" type="text" value="<?php form_option('auto_image_shadow_color'); ?>" class="my-color-field" /></p>
    <p><label for="auto_image_bg_color">Background Color:</label>
        <input name="auto_image_bg_color" type="text" value="<?php form_option('auto_image_bg_color'); ?>" class="my-color-field" /></p>
    <p><label for="auto_image_bg_image">Background Image:</label>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/sunset.jpg.240x120.jpg" width="240" height="120" alt="Sunset" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="sunset.jpg"){echo ' checked="checked"';} ?> value="sunset.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/flower.jpg.240x120.jpg" width="240" height="120" alt="Flower" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="flower.jpg"){echo ' checked="checked"';} ?> value="flower.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/rose.jpg.240x120.jpg" width="240" height="120" alt="Rose" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="rose.jpg"){echo ' checked="checked"';} ?> value="rose.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/book.jpg.240x120.jpg" width="240" height="120" alt="Book" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="book.jpg"){echo ' checked="checked"';} ?> value="book.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/grunge.jpg.240x120.jpg" width="240" height="120" alt="Grunge" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="grunge.jpg"){echo ' checked="checked"';} ?> value="grunge.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/bokeh.jpg.240x120.jpg" width="240" height="120" alt="Bokeh" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="bokeh.jpg"){echo ' checked="checked"';} ?> value="bokeh.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/grass-hill.jpg.240x120.jpg" width="240" height="120" alt="Grass Hill" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="grass-hill.jpg"){echo ' checked="checked"';} ?> value="grass-hill.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/clouds.jpg.240x120.jpg" width="240" height="120" alt="Clouds" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="clouds.jpg"){echo ' checked="checked"';} ?> value="clouds.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/wood.jpg.240x120.jpg" width="240" height="120" alt="Wood" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="wood.jpg"){echo ' checked="checked"';} ?> value="wood.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/bricks.jpg.240x120.jpg" width="240" height="120" alt="Bricks" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="bricks.jpg"){echo ' checked="checked"';} ?> value="bricks.jpg" /></span>
        <span class="bg_group"><img src="<?php echo plugins_url() ?>/auto-featured-image-from-title/images/blank.jpg.240x120.jpg" width="240" height="120" alt="Blank" /><br /><input type="radio" id="auto_image_bg_image" name="auto_image_bg_image"<?php if(get_option('auto_image_bg_image')=="blank.jpg"){echo ' checked="checked"';} ?> value="blank.jpg" /></span>
    </p>
    <p class="clear"><input type="checkbox" class="left" name="auto_image_set_first" value="yes"<?php if(get_option("auto_image_set_first")=='yes'){ echo ' checked="checked"';} ?> /><label for="auto_image_set_first">Set the first image in a post as the featured image</label></p>
    <p><input type="submit" value="Save Changes" /></p>

</form>

</div>

<div id="afift_info">

    <strong><a href="http://designsbychris.com/auto-featured-image-from-title/">Purchase the PRO version</a>!</strong>
    <p><strong>Use discount code LITE2PRO for 30% off!</strong></p>
    <p><strong><?php if( (date('Y')<2017) && (date('n')<9)){ ?>
	  Or, take this <a href="https://www.murvey.com/s?56db1e54c28de3cd049f90b2">limited-time survey</a> to receive 70% off, the biggest discount we'll ever offer! <em>Survey closes August 31, 2016.</em>
	  <?php } ?></strong></p>
	<p>The PRO version allows you to customize the featured image even more! The PRO version includes many more features and options, including the ability to upload your own fonts and background images, or use a random Flickr photo for your featured image. <a href="http://designsbychris.com/auto-featured-image-from-title/">Check it out</a>!</p>

    <hr />
    <strong>Rate it!</strong>
    <p>If you've found this plugin useful, please <a href="https://wordpress.org/support/view/plugin-reviews/auto-featured-image-from-title#postform">give it a rating and review</a>! This helps others know it's a good plugin, and makes me feel all warm and fuzzy, so that I will keep it updated and working great in the future.</p>

    <hr />
    <strong>Font Licenses:</strong><br />
    <small>
	  <a href="http://www.fontspace.com/sudtipos/aguafina-script">Aguafina Script</a> | <a href="http://www.fontspace.com/sudtipos/aguafina-script">license</a><br />
	  <a href="https://www.fontsquirrel.com/fonts/ChunkFive">ChunkFive</a> | <a href="http://www.fontsquirrel.com/license/ChunkFive">license</a><br />
	  <a href="https://www.theleagueofmoveabletype.com/linden-hill">Linden Hill</a> | <a href="https://www.theleagueofmoveabletype.com/linden-hill">license</a><br />
	  <a href="https://www.fontsquirrel.com/fonts/raleway">Raleway</a> | <a href="https://www.fontsquirrel.com/license/raleway">license</a><br />
  	</small><br />
    <strong>Image Licenses:</strong><br />
    <small>
	  <a href="https://unsplash.com/photos/8fMwyZPxqtg">Flower</a> | 
	  <a href="https://unsplash.com/photos/yZ_2RjtKXUU">Rose</a> | 
	  <a href="https://unsplash.com/photos/Ez5V2THOpDo">Clouds</a><br />
	  <a href="https://unsplash.com/photos/ss0vA9RUCV4">Grass Hill</a> | 
	  <a href="https://unsplash.com/photos/FEGsRHANjRg">Sunset</a> | 
	  <a href="https://unsplash.com/photos/VLdaxYyXJvw">Bokeh</a><br />
	  <a href="https://unsplash.com/photos/082dCXNKfxU">Book</a> | 
	  <a href="https://pixabay.com/ro/c%C4%83r%C4%83mizi-perete-pietre-structura-459299/">Bricks</a> | 
	  <a href="https://freestocktextures.com/texture/grunge-wall-plaster,571.html">Grunge</a><br />
	  <a href="https://unsplash.com/photos/h0Vxgz5tyXA">Wood</a>
	</small>
</div>

<?php }

// Display a notice that can be dismissed
function afift_lite_admin_notice() {
    global $current_user;
    $user_id = $current_user->ID;

    // Check that the user hasn't already clicked to ignore the message
    if ( !is_multisite() ) {
        if ( !get_user_meta($user_id, 'afift_lite2pro_ignore_notice4') ) {
            $url = add_query_arg(array('afift_lite2pro_ignore_notice4'=>'0',));
            echo '<div class="updated"><p>';
            echo '<a href="' . $url . '" style="float:right;" rel="nofollow">Hide Notice</a>            <strong>Thanks for using Auto Featured Image from Title LITE!</strong><br />'; ?>
            Use discount code LITE2PRO to get 30&#37; off the <a href="http://designsbychris.com/auto-featured-image-from-title">PRO version</a>!<br />
            <?php if( (date('Y')<2017) && (date('n')<9)){ ?>
            Or, take this <a href="https://www.murvey.com/s?56db1e54c28de3cd049f90b2">limited-time survey</a> to <strong>receive 70% off</strong>, the greatest discount we'll ever offer! <em>Survey closes August 31, 2016.</em><br />
            <?php }else{ ?>
            <?php } echo '</p></div>';
            }
        }
    }

add_action('admin_notices', 'afift_lite_admin_notice');

function afift_lite_notice_ignore() {
    global $current_user;
    $user_id = $current_user->ID;

    // If user clicks to ignore the notice, add that to their user meta
    if ( isset($_GET['afift_lite2pro_ignore_notice4']) && '0' == $_GET['afift_lite2pro_ignore_notice4'] ) {
        add_user_meta($user_id, 'afift_lite2pro_ignore_notice4', 'true', true);
        }
    }
add_action('admin_init', 'afift_lite_notice_ignore');

?>