<style>
.ux_slideshow_image_wrap_gallery_<?php echo $galleryID; ?> {
    height:<?php echo $sliderheight; ?>px;
    width:<?php  echo $sliderwidth; ?>px;
    max-width: calc(100% - 2*<?php echo get_option('uxgallery_slider_slideshow_border_size'); ?>px);
    position:relative;
    display: block;
    text-align: center;
    border:<?php echo get_option('uxgallery_slider_slideshow_border_size'); ?>px #<?php echo get_option('uxgallery_slider_slideshow_border_color'); ?> solid;
    box-sizing: content-box;
    clear:both;
<?php if($sliderposition=="left"){ $position='float:left;';}elseif($sliderposition=="right"){$position='float:right;';}else{$position='float:none; margin: 0 auto;';} ?>
<?php echo $position;  ?>
}
.ux_slideshow_image_wrap_gallery_<?php echo $galleryID; ?> * {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
}
.ux_slideshow_image_gallery_<?php echo $galleryID; ?> {
<?php if(get_option('uxgallery_slider_crop_image') == "resize"){?>
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
<?php } else{?>
     height: auto;
    top: 50%;
    left: 50%;
    transform: translate( -50%, -50% );
<?php }?>
    max-width: 100%;
    max-height: 100%;
    position: absolute;
}
.ux_slider_gallery_<?php echo $galleryID; ?> li iframe{
    width: 100%;
    height: 100%;
}
.ux_slider_gallery_<?php echo $galleryID; ?> li #thevideo{
    width: 100%;
    height: 100%;
}
 .ux_slider_gallery_<?php echo $galleryID; ?> li .thumb_wrapper{
     width: 100%;
     height: 100%;
 }
.ux_slider_gallery_<?php echo $galleryID; ?> li .thumb_wrapper img{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}
#ux_slideshow_left_gallery_<?php echo $galleryID; ?>,
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    cursor: pointer;
    display:none;
    display: block;
    height: 100%;
    outline: medium none;
    position: absolute;
    /*z-index: 10130;*/
    z-index: 13;
    bottom:25px;
    top:50%;
}
#ux_slideshow_left-ico_gallery_<?php echo $galleryID; ?>,
#ux_slideshow_right-ico_gallery_<?php echo $galleryID; ?> {
    z-index: 13;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    cursor: pointer;
    display: table;
    left: -9999px;
    line-height: 0;
    margin-top: -15px;
    position: absolute;
    top: 50%;
}
#ux_slideshow_left-ico_gallery_<?php echo $galleryID; ?>:hover,
#ux_slideshow_right-ico_gallery_<?php echo $galleryID; ?>:hover {
    cursor: pointer;
}
.ux_slideshow_image_container_gallery_<?php echo $galleryID; ?> {
    display: table;
    position: relative;
    top: 0;
    left: 0;
    text-align: center;
    vertical-align: middle;
    width:100%;
    height: 100%;
}
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> {
    text-decoration: none;
    position: absolute;
    z-index: 12;
    display: inline-block;
<?php  if(get_option('uxgallery_slider_title_has_margin')=='on'){
        $slider_title_width=(get_option('uxgallery_slider_title_width')-6);
        $slider_title_height=(get_option('uxgallery_slider_title_height')-6);
        $slider_title_margin="3";
    }else{
        $slider_title_width=(get_option('uxgallery_slider_title_width'));
        $slider_title_height=(get_option('uxgallery_slider_title_height'));
        $slider_title_margin="0";
    }  ?>
    width:<?php echo $slider_title_width; ?>%;
    /*height:<?php echo $slider_title_height; ?>%;*/
<?php 
    if($slideshow_title_position[0]=="left"){echo 'left:'.$slider_title_margin.'%;';}
    elseif($slideshow_title_position[0]=="center"){echo 'left:50%;transform:translateX(-50%);';}
    elseif($slideshow_title_position[0]=="right"){echo 'right:'.$slider_title_margin.'%;';}
    if($slideshow_title_position[1]=="top"){echo 'top:'.$slider_title_margin.'%;';}
    elseif($slideshow_title_position[1]=="middle"){echo 'top:50%;transform:translateY(-50%);';}
    elseif($slideshow_title_position[1]=="bottom"){echo 'bottom:'.$slider_title_margin.'%;';}
                if($slideshow_title_position[0]=="center" && $slideshow_title_position[1]=="middle") {echo "transform:translate(-50%, -50%);"; }
 ?>
    padding:2%;
    text-align:<?php echo get_option('uxgallery_slider_title_text_align'); ?>;
    font-weight:bold;
    color:#<?php echo get_option('uxgallery_slider_title_color'); ?>;
    background:<?php 			
				list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_slider_title_background_color'),2));
				$titleopacity=get_option("uxgallery_slider_title_background_transparency")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
		?>;
    border-style:solid;
    font-size:<?php echo get_option('uxgallery_slider_title_font_size'); ?>px;
    border-width:<?php echo get_option('uxgallery_slider_title_border_size'); ?>px;
    border-color:#<?php echo get_option('uxgallery_slider_title_border_color'); ?>;
    border-radius:<?php echo get_option('uxgallery_slider_title_border_radius'); ?>px;
}
.ux_slideshow_description_text_gallery_<?php echo $galleryID; ?> {
    text-decoration: none;
    position: absolute;
    z-index: 11;
    border-style:solid;
    display: inline-block;
<?php  if(get_option('uxgallery_slider_description_has_margin')=='on'){
        $slider_description_width=(get_option('uxgallery_slider_description_width')-6);
        $slider_description_height=(get_option('uxgallery_slider_description_height')-6);
        $slider_description_margin="3";
    }else{
        $slider_description_width=(get_option('uxgallery_slider_description_width'));
        $slider_descriptione_height=(get_option('uxgallery_slider_description_height'));
        $slider_description_margin="0";
    }  ?>
    width:<?php echo $slider_description_width; ?>%;
    /*height:<?php echo $slider_description_height; ?>%;*/
<?php 
    if($slideshow_description_position[0]=="left"){echo 'left:'.$slider_description_margin.'%;';}
    elseif($slideshow_description_position[0]=="center"){echo 'left:50%;transform:translateX(-50%);';}
    elseif($slideshow_description_position[0]=="right"){echo 'right:'.$slider_description_margin.'%;';}
    if($slideshow_description_position[1]=="top"){echo 'top:'.$slider_description_margin.'%;';}
    elseif($slideshow_description_position[1]=="middle"){echo 'top:50%;transform:translateY(-50%);';}
    elseif($slideshow_description_position[1]=="bottom"){echo 'bottom:'.$slider_description_margin.'%;';}
                if($slideshow_description_position[0]=="center" && $slideshow_description_position[1]=="middle") {echo "transform:translate(-50%, -50%);"; }
 ?>
    padding:3%;
    text-align:<?php echo get_option('uxgallery_slider_description_text_align'); ?>;
    color:#<?php echo get_option('uxgallery_slider_description_color'); ?>;
    background:<?php 
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_slider_description_background_color'),2));
			$descriptionopacity=get_option("uxgallery_slider_description_background_transparency")/100;
			echo 'rgba('.$r.','.$g.','.$b.','.$descriptionopacity.') !important';
		?>;
    border-style:solid;
    font-size:<?php echo get_option('uxgallery_slider_description_font_size'); ?>px;
    border-width:<?php echo get_option('uxgallery_slider_description_border_size'); ?>px;
    border-color:#<?php echo get_option('uxgallery_slider_description_border_color'); ?>;
    border-radius:<?php echo get_option('uxgallery_slider_description_border_radius'); ?>px;
}
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?>.none, .ux_slideshow_description_text_gallery_<?php echo $galleryID; ?>.none,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?>.hidden, .ux_slideshow_description_text_gallery_<?php echo $galleryID; ?>.hidden {display:none;}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>.none{display:none !important;}
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h1, .ux_slideshow_description_text_gallery_<?php echo $galleryID; ?> h1,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h2, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h2,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h3, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h3,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h4, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> h4,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> p, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> p,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> strong,  .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> strong,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> span, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> span,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> ul, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> ul,
.ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> li, .ux_slideshow_title_text_gallery_<?php echo $galleryID; ?> li {
    padding:2px;
    margin: 0;
}
.ux_slide_container_gallery_<?php echo $galleryID; ?> {
    display: table-cell;
    margin: 0 auto;
    position: relative;
    vertical-align: middle;
    width:100%;
    height:100%;
    _width: inherit;
    _height: inherit;
}
.ux_slide_bg_gallery_<?php echo $galleryID; ?> {
    margin: 0 auto;
    width:100%;
    height:100%;
    _width: inherit;
    _height: inherit;
}
.ux_slider_gallery_<?php echo $galleryID; ?> {
    width:100%;
    height:100%;
    display:table;
    padding: 0;
    margin: 0;
}
.ux_slideshow_image_item_gallery_<?php echo $galleryID; ?> {
    width:100%;
    height:100%;
    display: table-cell;
    filter: Alpha(opacity=100);
    opacity: 1;
    position: absolute;
    top: 0;
    left: 0;
    vertical-align: middle;
    z-index: 2;
    margin: 0 !important;
    padding: 0;
    overflow:hidden;
    border-radius: <?php echo get_option('uxgallery_slider_slideshow_border_radius'); ?>px !important;
}
.ux_slideshow_image_second_item_gallery_<?php echo $galleryID; ?> {
    width:100%;
    height:100%;
    display: table-cell;
    filter: Alpha(opacity=0);
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    vertical-align: middle;
    z-index: 1;
    overflow:hidden;
    margin: 0 !important;
    padding: 0;
    border-radius: <?php echo get_option('uxgallery_slider_slideshow_border_radius'); ?>px !important;
}
.ux_grid_gallery_<?php echo $galleryID; ?> {
    display: none;
    height: 100%;
    overflow: hidden;
    position: absolute;
    width: 100%;
}
.ux_gridlet_gallery_<?php echo $galleryID; ?> {
    opacity: 1;
    filter: Alpha(opacity=100);
    position: absolute;
}
.ux_slideshow_dots_container_gallery_<?php echo $galleryID; ?> {
    display: table;
    position: absolute;
    width:100% !important;
    height:100% !important;
}
.ux_slideshow_dots_thumbnails_gallery_<?php echo $galleryID; ?> {
    margin: 0 auto;
    overflow: hidden;
    position: absolute;
    width:100%;
    height:60px;
}
.ux_slideshow_dots_gallery_<?php echo $galleryID; ?> {
    display: inline-block;
    position: relative;
    cursor: pointer;
    box-shadow: 1px 1px 1px rgba(0,0,0,0.1) inset, 1px 1px 1px rgba(255,255,255,0.1);
    width:10px;
    height: 10px;
    border-radius: 10px;
    background: #00f;
    margin: 10px;
    overflow: hidden;
    z-index: 17;
}
.ux_slideshow_dots_active_gallery_<?php echo $galleryID; ?> {
    opacity: 1;
    background:#0f0;
    filter: Alpha(opacity=100);
}
.ux_slideshow_dots_deactive_gallery_<?php echo $galleryID; ?> {
}
.ux_slideshow_image_item1_gallery_<?php echo $galleryID; ?> {
    display: table;
    width: inherit;
    height: inherit;
}
.ux_slideshow_image_item2_gallery_<?php echo $galleryID; ?> {
    display: table-cell;
    vertical-align: middle;
    text-align: center;
}
.ux_slideshow_image_item2_gallery_<?php echo $galleryID; ?> a {
    display:block;
    vertical-align:middle;
    width:100%;
    height:100%;
}
.ux_slideshow_image_wrap_gallery_<?php echo $galleryID; ?> {
    background:#<?php echo get_option('uxgallery_slider_slider_background_color'); ?>;
}
.ux_slideshow_dots_thumbnails_gallery_<?php echo $galleryID; ?> {
<?php if(get_option('uxgallery_slider_dots_position')=="bottom"){?>
    bottom: 0;
<?php }else if(get_option('uxgallery_slider_dots_position')=="none"){?>
    display:none;
<?php
}else{ ?>
    top: 0; <?php } ?>
}
.ux_slideshow_dots_gallery_<?php echo $galleryID; ?> {
    background:#<?php echo get_option('uxgallery_slider_dots_color'); ?>;
}
.ux_slideshow_dots_active_gallery_<?php echo $galleryID; ?> {
    background:#<?php echo get_option('uxgallery_slider_active_dot_color'); ?>;
}
<?php	switch ($like_dislike) {
case "dislike":
    ?>
/*/////Like/Dislike Styles BEGIN/////like/dislike///////*/
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>{
    position: absolute;
    top: 0;
    left: 0;
    z-index: 9999;
    color:#<?php echo get_option('uxgallery_ht_slider_likedislike_font_color'); ?>;
    display: none;
}
.ux_slide_container_gallery_<?php echo $galleryID; ?>:hover .uxgallery_like_cont_<?php echo $galleryID.$pID; ?>{
    display: table;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper ,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
    position:relative;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_slider_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_slider_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
    display: inline-block;
    border-radius: 3px;
    font-size: 0;
    cursor: pointer;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper{
    margin: 3px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper{
    margin: 3px 3px 3px 0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like{
    font-size: 0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
    display:block;
    float:left;
    padding:4px 4px 4px 18px;
    font-size: 12px;
    font-weight: 700;
    position:relative;
    height: 28px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike_count{
    display:block;
    float:left;
    padding:4px 4px 4px 4px;
    font-size: 12px;
    font-weight: 700;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike{
    font-size: 0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike .ux_dislike_thumb {
    display:block;
    float:left;
    padding:4px 4px 4px 18px;
    font-size: 12px;
    font-weight: 700;
    position:relative;
    height: 28px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_up{
    font-size: 17px;
    position:absolute;
    top: 5px;
    left: 4px;
    color:#<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_color'); ?>;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .dislike_thumb_down{
    font-size: 17px;
    position:absolute;
    top: 4px;
    left: 4px;
    color:#<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_color'); ?>;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_hide{
    display: none !important;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active{
    color: #<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_active_color'); ?> !important;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active{
    color: #<?php echo get_option('uxgallery_ht_slider_active_font_color'); ?> !important;
}
@media screen and (min-width: 768px){
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like {
        color: #<?php echo get_option('uxgallery_ht_slider_active_font_color'); ?> !important;
    }
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .like_thumb_up {
        color: #<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_active_color'); ?> !important;
    }
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .ux_dislike {
        color: #<?php echo get_option('uxgallery_ht_slider_active_font_color'); ?> !important;
    }
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .dislike_thumb_down {
        color: #<?php echo get_option('uxgallery_ht_slider_likedislike_thumb_active_color'); ?> !important;
    }
}
/*/////Like/Dislike Styles END////like/dislike////////*/
<?php
		break;
	case "heart":
        ?>
/*/////Like/Dislike Styles BEGIN//////Heart//////*/
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>{
    position: absolute;
    top: 0;
    left: 0;
    z-index: 99;
    display: none;
}
.ux_slide_container_gallery_<?php echo $galleryID; ?>:hover .uxgallery_like_cont_<?php echo $galleryID.$pID; ?>{
    display: block;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper  {
    position:relative;
    background:<?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_slider_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_slider_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
    display: inline-block;
    border-radius: 8px;
    font-size: 0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover{
    background: #D6D4D4;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper{
    margin: 3px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like{
    font-size: 0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
    display: block;
    float: left;
<?php $heartCount='';
if(get_option('uxgallery_ht_slider_rating_count')=='off'){
    $heartCount="transparent";
}else{
    $heartCount='#'.get_option('uxgallery_ht_slider_likedislike_font_color');
}
?>;
    color:<?php echo $heartCount; ?>;
    width: 38px;
    height: 38px;
    padding:8px 0;
    font-size: 12px;
    text-align: center;
    font-weight: 700;
    position: relative;
    cursor: pointer;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count{
    display:none;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like  .likeheart{
    font-size: 32px;
    color: #<?php echo get_option('uxgallery_ht_slider_heart_likedislike_thumb_color'); ?>;
    position: absolute;
    top: 4px;
    left: 3px;
    transition:0.3s ease;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active{
    color: #<?php echo get_option('uxgallery_ht_slider_heart_likedislike_thumb_active_color'); ?> !important;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active{
<?php if(get_option('uxgallery_ht_slider_rating_count')!='off'):?>
    color: #<?php echo get_option('uxgallery_ht_slider_active_font_color'); ?> !important;
<?php endif; ?>
}
@media screen and (min-width: 768px){
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like_thumb {
    <?php if(get_option('uxgallery_ht_slider_rating_count')!='off'):?>
        color: #<?php echo get_option('uxgallery_ht_slider_active_font_color'); ?> !important;
    <?php endif; ?>
    }
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .likeheart {
        color: #<?php echo get_option('uxgallery_ht_slider_heart_likedislike_thumb_active_color'); ?> !important;
    }
}
.youtube-icon {background:url(<?php echo UXGALLERY_IMAGES_URL.'/admin_images/play.youtube.png'; ?>) center center no-repeat;}
.vimeo-icon {background:url(<?php echo UXGALLERY_IMAGES_URL.'/admin_images/play.vimeo.png'; ?>) center center no-repeat;}
.playbutton{
    width: 100%;
    height: 100%;
    position: absolute;
}
/*/////Like/Dislike Styles END//////Heart//////*/
<?php
break;
}
$arrowfolder=UXGALLERY_IMAGES_URL.'/front_images/arrows';
switch (get_option('uxgallery_slider_navigation_type')) {
    case 1:
        ?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-21px;
    height:43px;
    width:29px;
    background:url(<?php echo $arrowfolder;?>/arrows.simple.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-21px;
    height:43px;
    width:29px;
    background:url(<?php echo $arrowfolder;?>/arrows.simple.png) right top no-repeat;
}
<?php
break;
case 2:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-25px;
    height:50px;
    width:50px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-25px;
    height:50px;
    width:50px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) right top no-repeat;
}
#ux_slideshow_left_gallery_<?php echo $galleryID; ?>:hover {
    background-position:left -50px;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?>:hover {
    background-position:right -50px;
}
<?php
break;
case 3:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-22px;
    height:44px;
    width:44px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-22px;
    height:44px;
    width:44px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) right top no-repeat;
}
#ux_slideshow_left_gallery_<?php echo $galleryID; ?>:hover {
    background-position:left -44px;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?>:hover {
    background-position:right -44px;
}
<?php
break;
case 4:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-33px;
    height:65px;
    width:59px;
    background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-33px;
    height:65px;
    width:59px;
    background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) right top no-repeat;
}
#ux_slideshow_left_gallery_<?php echo $galleryID; ?>:hover {
    background-position:left -66px;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?>:hover {
    background-position:right -66px;
}
<?php
break;
case 5:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-18px;
    height:37px;
    width:40px;
    background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-18px;
    height:37px;
    width:40px;
    background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) right top no-repeat;
}
<?php
break;
case 6:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-25px;
    height:50px;
    width:50px;
    background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-25px;
    height:50px;
    width:50px;
    background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) right top no-repeat;
}
#ux_slideshow_left_gallery_<?php echo $galleryID; ?>:hover {
    background-position:left -50px;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?>:hover {
    background-position:right -50px;
}
<?php
break;
case 7:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    right: 0;
    margin-top:-19px;
    height:38px;
    width:38px;
    background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-19px;
    height:38px;
    width:38px;
    background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) right top no-repeat;
}
<?php
break;
case 8:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-22px;
    height:45px;
    width:45px;
    background:url(<?php echo $arrowfolder;?>/arrows.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-22px;
    height:45px;
    width:45px;
    background:url(<?php echo $arrowfolder;?>/arrows.png) right top no-repeat;
}
<?php
break;
case 9:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-22px;
    height:45px;
    width:45px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-22px;
    height:45px;
    width:45px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) right top no-repeat;
}
<?php
break;
case 10:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-24px;
    height:48px;
    width:48px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-24px;
    height:48px;
    width:48px;
    background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) right top no-repeat;
}
#ux_slideshow_left_gallery_<?php echo $galleryID; ?>:hover {
    background-position:left -48px;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?>:hover {
    background-position:right -48px;
}
<?php
break;
case 11:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-29px;
    height:58px;
    width:55px;
    background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-29px;
    height:58px;
    width:55px;
    background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) right top no-repeat;
}
<?php
break;
case 12:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-37px;
    height:74px;
    width:74px;
    background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-37px;
    height:74px;
    width:74px;
    background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) right top no-repeat;
}
<?php
break;
case 13:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-16px;
    height:33px;
    width:33px;
    background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-16px;
    height:33px;
    width:33px;
    background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) right top no-repeat;
}
<?php
break;
case 14:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-51px;
    height:102px;
    width:52px;
    background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-51px;
    height:102px;
    width:52px;
    background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) right top no-repeat;
}
<?php
break;
case 15:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left: 0;
    margin-top:-19px;
    height:39px;
    width:70px;
    background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right: 0;
    margin-top:-19px;
    height:39px;
    width:70px;
    background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) right top no-repeat;
}
<?php
break;
case 16:
?>
#ux_slideshow_left_gallery_<?php echo $galleryID; ?> {
    left:-21px;
    margin-top:-20px;
    height:40px;
    width:37px;
    background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) left  top no-repeat;
}
#ux_slideshow_right_gallery_<?php echo $galleryID; ?> {
    right:-21px;
    margin-top:-20px;
    height:40px;
    width:37px;
    background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) right top no-repeat;
}
<?php
break;
}
?>
.thumb_image{
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left:0;
}
.entry-content a{
    border-bottom: none !important;
}
</style>