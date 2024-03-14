<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/public/partials
 */
 //html code for the template style
$plugin_dir = WP_PLUGIN_DIR;
$imgs_url = esc_url( plugins_url( 'imgs/', __FILE__ ) );

//loop if more than one row
for ($x = 0; $x < count($rowarray); $x++) {
	if(	$currentform[0]->template_type=="widget"){
		?>
		<div class="wprevpro_t1_outer_div_widget w3_wprs-row-padding-small wprevprodiv">
		<?php
		} else {
		?>
		<div class="wprevpro_t1_outer_div w3_wprs-row-padding wprevprodiv">
		<?php
	}
	//loop 
	foreach ( $rowarray[$x] as $review ) 
	{
		$typelower = strtolower($review->type);
		
		//check if hiding, showing avatar
		if(isset($template_misc_array['avataropt']) && $template_misc_array['avataropt']=='mystery'){
			$userpic = $imgs_url.$typelower."_mystery_man.png";
		} else if(isset($template_misc_array['avataropt']) && $template_misc_array['avataropt']=='init'){
			$userpic ="https://avatar.oxro.io/avatar.svg?name=".substr($review->reviewer_name, 0, 1);
		} else {
			if($review->type=="Facebook"){
				if($review->userpiclocal!=""){
					$userpic = $review->userpiclocal;
				} else if($review->userpic!=""){
					$userpic = $review->userpic;
				} else {
					$userpic = 'https://graph.facebook.com/v2.2/'.$review->reviewer_id.'/picture?width=60&height=60';
				}
			} else {
				$userpic = $review->userpic;
			}
		}
		$userpichtml = '<img src="'.$userpic.'" alt="'.stripslashes($review->reviewer_name).' Avatar" class="wprevpro_t1_IMG_4" loading="lazy" />';

		if(isset($template_misc_array['avataropt']) && $template_misc_array['avataropt']=='hide'){
			$userpichtml = '';
		}
		
	
		if(!isset($template_misc_array['showicon'])){
			$template_misc_array['showicon']='';
		}
		
		//star number
		if($review->type=="Yelp"){
			//find business url
			$options = get_option('wprevpro_yelp_settings');
			$burl = $options['yelp_business_url'];
			if($burl==""){
				$burl="https://www.yelp.com";
			}
			$starfile = "yelp_stars_".$review->rating.".png";
			$yelp_logo = '<a href="'.$burl.'" target="_blank" rel="nofollow"><img src="'.$imgs_url.'yelp_outline.png" alt="" class="wprevpro_t1_yelp_logo"></a>';
		} else {
			$starfile = "stars_".$review->rating."_yellow.png";
			$yelp_logo ="";
		}
		
		// icon
		if($template_misc_array['showicon']=="no"){
			$yelp_logo = '';
		} else if($template_misc_array['showicon']=="yes"){
			 $yelp_logo = '<img src="'.$imgs_url.''.$typelower.'_small_icon.png" alt="'.$review->type.' Logo" class="wprevpro_t1_site_logo siteicon sitetype_'.$review->type.'">';
		 } else {
			 if($review->type=="Facebook") {
				//facebook logo
				$burl = "https://www.facebook.com/pg/".$review->pageid."/reviews/";
				$yelp_logo = '<a href="'.$burl.'" target="_blank" rel="nofollow"><img src="'.$imgs_url.'facebook_small_icon.png" alt="" class="wprevpro_t1_fb_logo sitetype_'.$review->type.'"></a>';
			} else if($review->type=="Google") {
			 $burl = $review->from_url;
			$yelp_logo = '<a href="'.$burl.'" target="_blank" rel="nofollow noreferrer" class="wprevpro_t1_site_logo_a"><img src="'.$imgs_url.''.$typelower.'_small_icon.png" alt="'.$review->type.' Logo" class="wprevpro_t1_site_logo siteicon sitetype_'.$review->type.'"></a>';
			}
		}
		
		
		
		
		//star alt tag
		$altimgtag = $review->rating.' star review';
		//star html
		if($review->type=="Facebook" || $review->type=="Google"){
			if($review->rating>0){
				$middlehtml='';
				
				//$starhtml = '<img src="'.$imgs_url.$starfile.'" alt="'.$altimgtag.'" class="wprevpro_t1_star_img_file">&nbsp;&nbsp;';
				
				$starhtmlstart ='<span class="starloc1 wprevpro_star_imgs wprevpro_star_imgsloc1">';
				$fullclass = 'wprsp-star';
				$emptyclass = 'wprsp-star-o';
				$userrating = intval($review->rating);
				if($userrating>0){
					$loopleft = 5 - $userrating;
					//loop to build based on rating
					for ($xstar = 1; $xstar <= $userrating; $xstar++) {
						$middlehtml = $middlehtml.'<span class="svgicons svg-'.$fullclass.'"></span>';
					}
					if($review->rating==1.5||$review->rating==2.5||$review->rating==3.5||$review->rating==4.5){
						//add another half only if using star, svgicons svg-wprsp-star
						if($fullclass=='wprsp-star'){
						$middlehtml = $middlehtml.'<span class="svgicons svg-wprsp-star-half"></span>';
						$loopleft--;
						}
					}
					if($loopleft>0){
						for ($ystar = 0; $ystar < $loopleft; $ystar++) {
							$middlehtml = $middlehtml.'<span class="svgicons svg-'.$emptyclass.'"></span>';
						}
					}
				}
				$starhtml=$starhtmlstart.$middlehtml.'</span>';
				
				
			} else if($review->recommendation_type=='positive'){
				$starfile = 'positive-min.png';
				$altimgtag = 'positive review';
				$starhtml = '<img src="'.$imgs_url.$starfile.'" alt="'.$altimgtag.'" class="wprevpro_t1_rec_img_file">&nbsp;&nbsp;';
			} else if($review->recommendation_type=='negative'){
				$starfile = 'negative-min.png';
				$altimgtag = 'negative review';
				$starhtml = '<img src="'.$imgs_url.$starfile.'" alt="'.$altimgtag.'" class="wprevpro_t1_rec_img_file">&nbsp;&nbsp;';
			}
		}
		if($review->type=="Twitter"){
			$starhtml ='';
		}
		
		$reviewtext = "";
		if($review->review_text !=""){
			$reviewtext = $review->review_text;
		}
		
		//if read more is turned on then divide then add read more span links
		if(!isset($currentform[0]->read_more_text)){
			$currentform[0]->read_more_text ='';
		}
		if($currentform[0]->read_more_text==''){
			$currentform[0]->read_more_text = 'read more';
		}
		if(	$currentform[0]->read_more=="yes"){
			$readmorenum = 30;
			if(	isset($currentform[0]->read_more_num) && $currentform[0]->read_more_num!=""){
				$readmorenum= intval($currentform[0]->read_more_num);
			}

			$countwords = mb_substr_count($reviewtext, " ");
			
			if($countwords>$readmorenum){
				//split in to array
				$pieces = explode(" ", $reviewtext);
				//slice the array in to two
				$part1 = array_slice($pieces, 0, $readmorenum);
				$part2 = array_slice($pieces, $readmorenum);
				$reviewtext = implode(" ",$part1)."<a class='wprs_rd_more'>... ".$currentform[0]->read_more_text."</a><span class='wprs_rd_more_text' style='display:none;'> ".implode(" ",$part2)."</span>";
			}
		}
		


		//per a row
		if($currentform[0]->display_num>0){
			$perrow = 12/$currentform[0]->display_num;
		} else {
			$perrow = 4;
		}
		
		
		
		//if this is twitter then add hashtag and @ links, also add div to showcase likes, retweets and replies
		if($review->type=="Twitter"){
			
			//Convert urls to <a> links
			$reviewtext = preg_replace("/([\w]+\:\/\/[\w\-?&;#~=\.\/\@]+[\w\/])/", "<a rel=\"nofollow noreferrer\" target=\"_blank\" href=\"$1\">$1</a>", $reviewtext);

			//Convert hashtags to twitter searches in <a> links
			$reviewtext = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a rel=\"nofollow noreferrer\" target=\"_new\" target=\"_blank\" href=\"https://twitter.com/search?q=$1\">#$1</a>", $reviewtext);

			//Convert attags to twitter profiles in &lt;a&gt; links
			$reviewtext = preg_replace("/@([A-Za-z0-9_\/\.]*)/", "<a rel=\"nofollow noreferrer\" target=\"_blank\" href=\"https://twitter.com/$1\">@$1</a>", $reviewtext);

		}
		
		$date_format = get_option( 'date_format' );
		if(isset($date_format) && $date_format!=''){
			//$datehtml = date($date_format,$review->created_time_stamp);
			$datehtml = date_i18n($date_format,$review->created_time_stamp);
		} else {
			$datehtml = date("n/d/Y",$review->created_time_stamp);
		}
		
		//add verified if needed.
		$verifiedhtml = '';
		if(isset($template_misc_array['verified']) && $template_misc_array['verified']=="yes1"){
			$verifiedhtml = '<span class="verifiedloc1 wprevpro_verified_svg wprevtooltip" data-wprevtooltip="Verified on '.$review->type.'"><span class="svgicons svg-wprsp-verified"></span></span>';
		}
		
		
		
	?>
		<div class="wprevpro_t1_DIV_1<?php if(	$currentform[0]->template_type=="widget"){echo ' marginb10';}?> w3_wprs-col l<?php echo $perrow; ?>">
			<div class="wprevpro_t1_DIV_2 wprev_preview_bg1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?> wprev_preview_bradius_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
				<p class="wprevpro_t1_P_3 wprev_preview_tcolor1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
					<span class="wprevpro_star_imgs_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $starhtml; ?></span><?php echo $verifiedhtml; ?><?php echo stripslashes($reviewtext); ?>
				</p>
				<?php echo $yelp_logo; ?>
			</div><span class="wprevpro_t1_A_8"><?php echo $userpichtml; ?></span> <span class="wprevpro_t1_SPAN_5 wprev_preview_tcolor2_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo stripslashes($review->reviewer_name); ?><br/><span class="wprev_showdate_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $datehtml; ?></span> </span>
		</div>
	<?php
	}
	//end loop
	?>
	</div>
<?php
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
