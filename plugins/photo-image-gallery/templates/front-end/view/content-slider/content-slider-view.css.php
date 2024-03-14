<style>
* {outline:none;}
#g-main-slider_<?php echo $galleryID; ?> {background:#<?php echo get_option("uxgallery_ht_view5_slider_background_color");?>;}
.entry-content a{
	border-bottom: none;
}
#g-main-slider_<?php echo $galleryID; ?> div.slider-content {
	position:relative;
	width:100%;
	padding:0 0 0 0;
	position:relative;
	background:#<?php echo get_option("uxgallery_ht_view5_slider_background_color");?>;
}
[class$="-arrow"] {
	background-image:url(<?php echo UXGALLERY_IMAGES_URL.'/admin_images/arrow.'.get_option("uxgallery_ht_view5_icons_style").'.png';?>) !important;
}
.ls-select-box {
	background:url(<?php echo UXGALLERY_IMAGES_URL.'/admin_images/menu.'.get_option("uxgallery_ht_view5_icons_style").'.png';?>) right center no-repeat #<?php echo get_option("uxgallery_ht_view5_slider_background_color");?>;
}
#g-main-slider_<?php echo $galleryID; ?>-nav-select {
	color:#<?php echo get_option("uxgallery_ht_view5_title_font_color");?>;
}
#g-main-slider_<?php echo $galleryID; ?> div.slider-content .slider-content-wrapper {
	position:relative;
	width:100%;
	padding:0;
	display:block;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?> {
	position:relative;
	width:<?php echo get_option("uxgallery_ht_view5_main_image_width");?>px;
	display:inline-block;
	padding:0 10px 0 0;
	float:left;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?> a{
	display: inline-block;
	width: 100%;
    float: left;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?> img.main-image {
	position:relative;
	width:100%;
	height:auto;
	display:block;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?> .play-icon {
	position:absolute;
	top:0;
	left:0;
	width:100%;
	height:100%;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?>  .play-icon.youtube-icon {background:url(<?php echo UXGALLERY_IMAGES_URL.'/admin_images/play.youtube.png' ;?>) center center no-repeat;}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?>  .play-icon.vimeo-icon {background:url(<?php echo UXGALLERY_IMAGES_URL.'/admin_images/play.vimeo.png'; ?>) center center no-repeat;}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block {
	display:inline-block;
	width: calc(100% - <?php echo get_option("uxgallery_ht_view5_main_image_width") + 10; ?>px);
    min-width: 210px;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block > div {
	padding-bottom:10px;
	margin-top:10px;
<?php if(get_option('uxgallery_ht_view5_show_separator_lines')=="on") {?>
	background:url('<?php echo  UXGALLERY_IMAGES_URL.'/admin_images/divider.line.png'; ?>') center bottom repeat-x;
<?php } ?>
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block > div:last-child {background:none;}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .title {
	position:relative;
	display:block;
	margin:-10px 0 0 0;
	font-size:<?php echo get_option("uxgallery_ht_view5_title_font_size");?>px !important;
	line-height:<?php echo get_option("uxgallery_ht_view5_title_font_size");?>px !important;
	color:#<?php echo get_option("uxgallery_ht_view5_title_font_color");?>;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description {
	clear:both;
	position:relative;
	font-weight:normal;
	text-align:justify;
	font-size:<?php echo get_option("uxgallery_ht_view5_description_font_size");?>px !important;
	line-height:<?php echo get_option("uxgallery_ht_view5_description_font_size");?>px !important;
	color:#<?php echo get_option("uxgallery_ht_view5_description_color");?>;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description h1,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description h2,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description h3,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description h4,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description h5,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description h6,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description p,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description strong,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description span {
	padding:2px !important;
	margin:0 !important;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description ul,
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block .description li {
	padding:2px 0 2px 5px;
	margin:0 0 0 8px;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block {
	position:relative;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block a,#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block a:link,#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block a:visited{
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#<?php echo get_option("uxgallery_ht_view5_linkbutton_background_color");?>;
	color:#<?php echo get_option("uxgallery_ht_view5_linkbutton_color");?>;
	font-size:<?php echo get_option("uxgallery_ht_view5_linkbutton_font_size");?>px;
	text-decoration:none;
}
#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block a:hover,#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block a:focus,#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .button-block a:active {
	background:#<?php echo get_option("uxgallery_ht_view5_linkbutton_background_hover_color");?>;
	color:#<?php echo get_option("uxgallery_ht_view5_linkbutton_font_hover_color");?>;
}
@media only screen and (min-width:500px) {
	#g-main-slider_<?php echo $galleryID; ?>-nav-ul {
		visibility:hidden !important;
		height:1px;
	}
}
@media only screen and (max-width:500px) {
	#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .image-block_<?php echo $galleryID; ?>,#g-main-slider_<?php echo $galleryID; ?> .slider-content-wrapper .right-block {
		width:100%;
		display:block;
		float:none;
		clear:both;
	}
}
<?php
switch ($like_dislike) {
	case "dislike":
?>
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>{
	color: #<?php echo get_option('uxgallery_ht_contentsl_likedislike_font_color'); ?>;
	position: relative;
	z-index: 99999999999999999999999999;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.uxgallery_like_wrapper ,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.uxgallery_dislike_wrapper {
	position:relative;
	background:<?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_contentsl_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_contentsl_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: inline-block;
	border-radius: 3px;
	cursor: pointer;
	font-size:0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.uxgallery_like_wrapper{
	margin: 3px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.uxgallery_dislike_wrapper{
	margin: 3px 3px 3px 0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_like{
	font-size:0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_like .ux_like_thumb {
	display:block;
	float:left;
	padding:0px 4px 0px 18px;
	font-size: 12px;
	font-weight: 700;
	position:relative;
	height: 23px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_like_count,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_dislike_count{
	display:block;
	float:left;
	padding:4px 4px 4px 4px;
	font-size: 12px;
	font-weight: 700;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_dislike{
	font-size:0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_dislike .ux_dislike_thumb {
	display:block;
	float:left;
	padding:0px 4px 0px 18px;
	font-size: 12px;
	font-weight: 700;
	position:relative;
	height: 23px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_like .ux_like_thumb:hover:after,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>	.ux_dislike .ux_dislike_thumb:hover:after {
	opacity:1;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_up{
	font-size: 17px;
	position:absolute;
	top: 5px;
	left: 4px;
	color:#<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_color'); ?>;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .dislike_thumb_down{
	font-size: 17px;
	position:absolute;
	top: 4px;
	left: 4px;
	color:#<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_color'); ?>;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_hide{
	display: none !important;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active{
	color: #<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_active_color'); ?> !important;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active{
	color: #<?php echo get_option('uxgallery_ht_contentsl_active_font_color'); ?> !important;
}
@media screen and (min-width: 768px){
	.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like {
		color: #<?php echo get_option('uxgallery_ht_contentsl_active_font_color'); ?> !important;
	}
	.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .like_thumb_up {
		color: #<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_active_color'); ?> !important;
	}
	.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .ux_dislike {
		color: #<?php echo get_option('uxgallery_ht_contentsl_active_font_color'); ?> !important;
	}
	.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .dislike_thumb_down {
		color: #<?php echo get_option('uxgallery_ht_contentsl_likedislike_thumb_active_color'); ?> !important;
	}
}
/*/////Like/Dislike Styles END/////like/dislike///////*/
<?php break;
		case 'heart';
?>
/*/////Like/Dislike Styles BEGIN//////Heart//////*/
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?>{
	position: absolute;
	top: 0;
	right: 15px;
	z-index: 99;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper  {
	position:relative;
	background:<?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_contentsl_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_contentsl_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: inline-block;
	border-radius: 8px;
	font-size:0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover{
	background: #D6D4D4;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper{
	margin: 3px;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like{
	font-size:0;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
	display: block;
	float: left;
<?php $heartCount='';
if(get_option('uxgallery_ht_contentsl_rating_count')=='off'){
	$heartCount="transparent";
}else{
	$heartCount='#'.get_option('uxgallery_ht_contentsl_likedislike_font_color');
}
?>
	color:<?php echo $heartCount.';'; ?>
	width: 38px;
	height: 38px;
	padding:8px 0;
	font-size: 12px;
	text-align: center;
	font-weight: 700;
	position: relative;
	cursor: pointer;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:after {
	color:#fff;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count{
	display:none;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:hover:after {
	opacity:1;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like  .likeheart{
	font-size: 32px;
	color:#<?php echo get_option('uxgallery_ht_contentsl_heart_likedislike_thumb_color'); ?>;
	position: absolute;
	top: 4px;
	left: 3px;
	transition:0.3s ease;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active{
	color: #<?php echo get_option('uxgallery_ht_contentsl_heart_likedislike_thumb_active_color'); ?> !important;
}
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active{
<?php if(get_option('uxgallery_ht_contentsl_rating_count')!='off'):?>
	color: #<?php echo get_option('uxgallery_ht_contentsl_active_font_color'); ?> !important;
<?php endif; ?>
}
@media screen and (min-width: 768px){
	.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like_thumb {
	<?php if(get_option('uxgallery_ht_contentsl_rating_count')!='off'):?>
		color: #<?php echo get_option('uxgallery_ht_contentsl_active_font_color'); ?> !important;
	<?php endif; ?>
	}
	.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .likeheart {
		color: #<?php echo get_option('uxgallery_ht_contentsl_heart_likedislike_thumb_active_color'); ?> !important;
	}
}
/*/////Like/Dislike Styles END//////Heart//////*/
<?php
		break;
		}?>
</style>