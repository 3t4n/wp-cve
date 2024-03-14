<?php ob_start();
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//define all variables the needed alot
include 'the_globals.php';
$postimage = '';
$dont_show_image = '';
$rpwpluginsurl = plugins_url( '', __FILE__ );


?>
<?php
	$rpw_related_posts_settings = rpw_read_options();
	$limit = (stripslashes($rpw_related_posts_settings['rpw_posts_limit']));
	$searches = get_rpw_searches(rpw_get_taglist());
	$counter = 1;
	if($searches){
	echo '<div id="related_posts_rpw"><ul>';
	foreach($searches as $search) {
		$categorys = get_the_category($search->ID);	//Fetch categories of the plugin
		$p_in_c = false;	// Variable to check if post exists in a particular category
		$title = get_the_title($search->ID);
		$title = rpw_title_shorter($title,10);

		//---------------------------------
		echo '<li style="background-image: none">';
		if($rpw_related_posts_settings['rpw_show_thumbs'] == 'Yes'){
			$out_post_thumbnail = '<div class="related_posts_rpw_main_image"><a href="'.get_permalink($search->ID).'" rel="related" title="'.$title.'">';
		if ((function_exists('get_the_post_thumbnail')) && (has_post_thumbnail($search->ID))) {

			$imgdata = wp_get_attachment_image_src( get_post_thumbnail_id(), 'rpw-thumb' ); //change rpw-thumb to whatever size you are using

			$imgwidth = '';
			if ($imgdata && is_array($imgdata) && count($imgdata) >= 2) {
				$imgwidth = $imgdata[1]; // Thumbnail's width

			}		
				$wanted_width = $rpw_related_posts_settings['rpw_thumbw']; //change this to your liking

			//echo "<p>imgwidth:". $imgwidth. "wanted:" .$wanted_width. "</p>";
			if ( $imgwidth == $wanted_width ) {
				$out_post_thumbnail .= get_the_post_thumbnail( $search->ID, 'rpw-thumb', array('title' => $title,'alt' => $title,'class' => 'rpw_image'));

			} else {


				$out_post_thumbnail .= get_the_post_thumbnail( $search->ID, 'thumbnail', array('title' => $title,'alt' => $title,'class' => 'rpw_image'));
			}

		} else {
				$postimage = get_post_meta($search->ID, 'image' , true);
				$dont_show_image = 'No';
				if ($postimage!='') {
					preg_match_all($reg_exp, get_post($search->ID)->post_content, $matches);

					// Check if $matches is an array and if it has the necessary indexes
					if (is_array($matches) && isset($matches[1]) && isset($matches[1][0])) {
						$postimage = $matches[1][0]; // this gives the first image only
				
						preg_match_all($new_reg_exp, get_post($search->ID)->post_content, $matches2);
				
						// Check if $matches2 is an array and if it has the necessary indexes
						if (is_array($matches2) && isset($matches2[1]) && isset($matches2[1][0])) {
							$new_img_src =$rpwpluginsurl.'/images/noimage.png';
						}
							// Process the image or do something with $new_img_src here
					}
				}
				
			$site_url = site_url();
			$postimage = str_replace("../",$site_url."/",$postimage);
			$out_post_thumbnail .= '<img src="' . $rpwpluginsurl . '/images/noimage.png" title="' . $title . '" class="rpw_image wp-post-image" style="width:100%;height:66.73%;max-width:1470px;" " />';

			if ($postimage !='') {$dont_show_image = 'Yes';}
			}//of line 27
			//$out_post_thumbnail .= '<span id="entry-meta-span" class="entry-meta-span">'. get_the_time('M j, Y',$search->ID) .'</span>';
			$out_post_thumbnail .= '</a></div>';
			if ($dont_show_image == 'Yes') $out_post_thumbnail = '';
		}else{//for line 19 if
			$out_post_thumbnail = '';
		}
		echo $out_post_thumbnail;
		$rpw_Style = $rpw_related_posts_settings['rpw_Style'];
		if($rpw_Style != "Just_Thumbs" && $rpw_Style != "CSS-Just_Thumbs"){
			echo '<div class="related_posts_rpw_main_content">';
			echo '<p><a href="'; echo get_permalink($search->ID); echo '" rel="related" title="'; the_title(); echo '">'; echo $title; echo '</a></p>';
			$rpw_show_excerpt_temp = $rpw_related_posts_settings['rpw_show_excerpt'];
			if ($rpw_show_excerpt_temp == 'Yes'){echo "<p>". rpw_excerpt($search->ID,$rpw_related_posts_settings['rpw_excerpt_length']) . "</p>";}
			echo "</div>";
		}
		echo "</li>";
		global $search_counter ;
		if ($search_counter == $limit) break;	// End loop when related posts limit is reached
	} //end of foreach loop
	echo '</ul></div>';
	}//end of searches if statement
	else{
		echo '<p>No related posts!</p>';
	}
?><?php
$out = ob_get_clean();
return $out;
?>