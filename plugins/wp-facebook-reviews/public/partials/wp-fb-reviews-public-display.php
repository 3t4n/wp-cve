<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/public/partials
 */

 	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_post_templates';
	
 //use the template id to find template in db, echo error if we can't find it or just don't display anything
 	//Get the form--------------------------
	$tid = htmlentities($a['tid']);
	$tid = intval($tid);
	$currentform = $wpdb->get_results("SELECT * FROM $table_name WHERE id = ".$tid);

	
	//check to make sure template found
	if(isset($currentform[0])){
		
		//use values from currentform to get reviews from db
		$table_name = $wpdb->prefix . 'wpfb_reviews';
		
		if($currentform[0]->hide_no_text=="yes"){
			$rlength = 1;
		} else {
			$rlength = 0;
		}
		
		if($currentform[0]->display_order=="random"){
			$sorttable = "RAND() ";
			$sortdir = "";
		} else {
			$sorttable = "created_time_stamp ";
			$sortdir = "DESC";
		}
		$rtype = "Facebook";
		if($currentform[0]->rtype=='["fb"]'){
			$rtype = "Facebook";
		}
		if($currentform[0]->rtype=='["google"]'){
			$rtype = "Google";
		}

		$reviewsperpage= $currentform[0]->display_num*$currentform[0]->display_num_rows;
		$tablelimit = $reviewsperpage;
		//change limit for slider
		if($currentform[0]->createslider == "yes"){
			$tablelimit = $tablelimit*$currentform[0]->numslides;
		}
		

			$totalreviews = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM ".$table_name."
				WHERE id>%d AND review_length >= %d AND (type = %s OR type = %s)
				ORDER BY ".$sorttable." ".$sortdir." 
				LIMIT ".$tablelimit." ", "0","$rlength","$rtype","Twitter")
			);

			
			//print_r($totalreviews);
			//echo "<br><br>";
			
	//only continue if some reviews found
	$makingslideshow=false;
	$animateheight = 'true';
	if(count($totalreviews)>0){

		//if creating a slider than we need to split into chunks for each slider
		//if($currentform[0]->createslider == "yes"){
			//print_r(array_chunk($totalreviews, $reviewsperpage));
			$totalreviewschunked = array_chunk($totalreviews, $reviewsperpage);
		//}
		//loop through each chunk

		

			//add styles from template misc here
			$template_misc_array = json_decode($currentform[0]->template_misc, true);
			
			if(is_array($template_misc_array)){
				$misc_style ="";
				//hide stars and/or date
				if($template_misc_array['showstars']=="no"){
					$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprevpro_star_imgs_T'.$currentform[0]->style.' {display: none;}';
				}
				if($template_misc_array['showdate']=="no"){
					$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_showdate_T'.$currentform[0]->style.' {display: none;}';
				}
				
				
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_preview_bradius_T'.$currentform[0]->style.' {border-radius: '.$template_misc_array['bradius'].'px;}';
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_preview_bg1_T'.$currentform[0]->style.' {background:'.$template_misc_array['bgcolor1'].';}';
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_preview_bg2_T'.$currentform[0]->style.' {background:'.$template_misc_array['bgcolor2'].';}';
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_preview_tcolor1_T'.$currentform[0]->style.' {color:'.$template_misc_array['tcolor1'].';}';
				$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_preview_tcolor2_T'.$currentform[0]->style.' {color:'.$template_misc_array['tcolor2'].';}';
				
				//style specific mods 	div > p
				if($currentform[0]->style=="1"){
					$misc_style = $misc_style . '#wprev-slider-'.$currentform[0]->id.' .wprev_preview_bg1_T'.$currentform[0]->style.'::after{ border-top: 30px solid '.$template_misc_array['bgcolor1'].'; }';
				}
				
				//------------------------

				echo "<style>".$misc_style."</style>";
				
			}
			//--------------------------
			

			//print out user style added
			echo "<style>".$currentform[0]->template_css."</style>";
		
		
		
		//if making slide show then add it here
		if($currentform[0]->createslider == "yes"){
			//make sure we have enough to create a show here
			if($totalreviews>$reviewsperpage){
				$makingslideshow = true;
				$rtltag = '';
				if ( is_rtl() ) {
				$rtltag = 'dir="rtl"';
				$animateheight = 'false';
				}
				echo '<div class="wprev-slider" '.$rtltag.' id="wprev-slider-'.$currentform[0]->id.'"><ul>';
			}
		} else {
			echo '<div class="wprev-no-slider" id="wprev-slider-'.$currentform[0]->id.'"><ul>';
		}

		$loopnum = 1;
		foreach ( $totalreviewschunked as $reviewschunked ){
			//echo "loop1";
			$totalreviewstemp = $reviewschunked;
			
			//need to break $totalreviewstemp up based on how many rows, create an multi array containing them
			if($currentform[0]->display_num_rows>1 && count($totalreviewstemp)>$currentform[0]->display_num){
				//count of reviews total is greater than display per row then we need to break in to multiple rows
				for ($row = 0; $row < $currentform[0]->display_num_rows; $row++) {
					$n=1;
					foreach ( $totalreviewstemp as $tempreview ){
						//echo "<br>".$tempreview->reviewer_name;
						//echo $n."-".$row."-".$currentform[0]->display_num;
						if($n>($row*$currentform[0]->display_num) && $n<=(($row+1)*$currentform[0]->display_num)){
							$rowarray[$row][$n]=$tempreview;
						}
						$n++;
					}
				}
			} else {
				//everything on one row so just put in multi array
				$rowarray[0]=$totalreviewstemp;
			}

			 
			//if making slide show
			if($makingslideshow){
				if($loopnum==1){
					echo '<li>';
				} else {
					echo '<li class="wprevnextslide">';
				}
			}
		 
				//include the correct tid here
				if($currentform[0]->style=="1"){
				$iswidget=false;
					include(plugin_dir_path( __FILE__ ) . '/template_style_1.php');
				//require_once plugin_dir_path( __FILE__ ) . '/template_style_1.php';
				}
			
			//if making slide show then end loop here
			if($makingslideshow){
					echo '</li>';
			}
			$loopnum++;
		
		}	//end loop chunks
		//if making slide show then end it
		if($makingslideshow){
				echo '</ul></div>';
				/*echo "<script type='text/javascript'>
						var jqtimesran = 0;
						function wprs_defer(method) {
								if (window.jQuery) {
									if(jQuery.fn.wprs_unslider){
										method();
									} else if(jqtimesran<10) {
										jqtimesran = jqtimesran +1;
										setTimeout(function() { wprs_defer(method) }, 500);
										console.log('waiting for wppro_rev_slider js...');									
									}
								} else {
									setTimeout(function() { wprs_defer(method) }, 100);
									console.log('waiting for jquery..');
								}
							}
						wprs_defer(function () {
							jQuery(document).ready(function($) {
								var fbslider = $('#wprev-slider-".$currentform[0]->id."').wprs_unslider(
									{
									autoplay:false,
									speed: '700',
									delay: '5000',
									animation: 'horizontal',
									arrows: true,
									animateHeight: ".$animateheight.",
									activeClass: 'wprs_unslider-active',
									}
								);
								$('.wprs_unslider-nav').show();
							});
						});
						</script>";
						*/
		} else {
		echo '</div>';
		}
	 
	}
}
?>

