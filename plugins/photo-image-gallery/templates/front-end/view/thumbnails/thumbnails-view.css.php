<?= "<style>"?>
section #uxgallery<?php echo $galleryID; ?> {
    padding: <?php echo get_option('uxgallery_thumb_box_padding'); ?>px !important;
    min-width: 100%;
    width: 100%;
    min-height: 100%;
    text-align: center;
    margin: 0;
    margin-bottom: 30px;
    box-shadow:none !important;
<?php if(get_option("uxgallery_thumb_box_has_background") == 'on'){ ?> background-color: #<?php echo get_option("uxgallery_thumb_box_background"); ?>;
<?php } ?> <?php if(get_option("uxgallery_thumb_box_use_shadow") == 'on'){ echo 'box-shadow: 0 0 10px;'; } ?>
}

#uxgallery<?php echo $galleryID; ?> .ux_big_li {
<?php if(get_option("uxgallery_image_natural_size_thumbnail")=='resize'){?> overflow: hidden;
    width: <?php echo get_option("uxgallery_thumb_image_width")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    max-width: <?php echo get_option("uxgallery_thumb_image_width")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    height: <?php echo get_option("uxgallery_thumb_image_height")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    margin: <?php echo get_option("uxgallery_thumb_margin_image"); ?>px <?php echo get_option("uxgallery_thumb_margin_image")-2; ?>px !important;
    border-radius: <?php echo get_option("uxgallery_thumb_image_border_radius"); ?>px;
    padding: 0 !important;
<?php }
elseif(get_option("uxgallery_image_natural_size_thumbnail")=='natural'){
?> overflow: hidden;
    width: <?php echo get_option("uxgallery_thumb_image_width")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    height: <?php echo get_option("uxgallery_thumb_image_height")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    margin: <?php echo get_option("uxgallery_thumb_margin_image"); ?>px !important;
    border-radius: <?php echo get_option("uxgallery_thumb_image_border_radius"); ?>px;
    padding: 0 !important;
<?php }?> border-radius: <?php echo get_option("uxgallery_thumb_image_border_radius"); ?>px;
    border: <?php echo get_option("uxgallery_thumb_image_border_width")."px"; ?> solid #<?php echo get_option("uxgallery_thumb_image_border_color"); ?>;
    box-sizing: border-box;
    max-width: 100%;
    position: relative;
    display: inline-block;
}

#uxgallery<?php echo $galleryID; ?> li img {
<?php if(get_option("uxgallery_image_natural_size_thumbnail")=='resize'){?> width: 100%;
    max-width: <?php echo get_option("uxgallery_thumb_image_width"); ?>px;
    height: <?php echo get_option("uxgallery_thumb_image_height"); ?>px;
    margin: 0 !important;
<?php }
elseif(get_option("uxgallery_image_natural_size_thumbnail")=='natural'){
?> margin: 0 !important;
    max-width: none !important;
<?php }?> border-radius: <?php  if(get_option("uxgallery_thumb_image_border_width") == 0) echo get_option("uxgallery_thumb_image_border_radius"); else echo 0; ?>px;
}

section #uxgallery<?php echo $galleryID; ?> li .overLayer ul li h2,
section #uxgallery<?php echo $galleryID; ?> li .infoLayer ul li h2 {
    font-size: <?php echo get_option("uxgallery_thumb_title_font_size"); ?>px;
    color: #<?php echo get_option("uxgallery_thumb_title_font_color"); ?>;
}

section #uxgallery<?php echo $galleryID; ?> li .infoLayer ul li p {
    color: #<?php echo get_option("uxgallery_thumb_title_font_color"); ?>;
}

section #uxgallery<?php echo $galleryID; ?> li .overLayer,
section #uxgallery<?php echo $galleryID; ?> li .infoLayer {
    -webkit-transition: opacity 0.3s linear;
    -moz-transition: opacity 0.3s linear;
    -ms-transition: opacity 0.3s linear;
    -o-transition: opacity 0.3s linear;
    transition: opacity 0.3s linear;
    width: 100%;
    max-width: <?php echo get_option("uxgallery_thumb_image_width")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    height: <?php echo get_option("uxgallery_thumb_image_height")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    position: absolute;
    text-align: center;
    opacity: 0;
    top: 0;
    left: 0;
    z-index: 4;
    max-height: 100%;
    border-radius: <?php  if(get_option("uxgallery_thumb_image_border_width") == 0) echo get_option("uxgallery_thumb_image_border_radius"); else echo 0; ?>px;
}

section #uxgallery<?php echo $galleryID; ?> li a {
    position: absolute;
    display: block;
    width: 100%;
    max-width: <?php echo get_option("uxgallery_thumb_image_width")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    height: <?php echo get_option("uxgallery_thumb_image_height")+2*get_option("uxgallery_thumb_image_border_width"); ?>px;
    top: 0;
    left: 0;
    z-index: 6;
    max-height: 100%;
}

li#fullPreview {
    list-style: none;
}

.load_more3 {
    margin: 10px 0;
    position: relative;
    text-align: <?php if(get_option('uxgallery_video_ht_view7_loadmore_position') == 'left') {echo 'left';}
					elseif (get_option('uxgallery_video_ht_view7_loadmore_position') == 'center') { echo 'center'; }
					elseif(get_option('uxgallery_video_ht_view7_loadmore_position') == 'right') { echo 'right'; }?>;
    width: 100%;
}

.load_more_button3 {
    border-radius: 10px;
    display: inline-block;
    padding: 5px 15px;
    font-size: <?php echo get_option('uxgallery_video_ht_view7_loadmore_fontsize'); ?>px !important;;
    color: <?php echo '#'.get_option('uxgallery_video_ht_view7_loadmore_font_color'); ?> !important;;
    background: <?php echo '#'.get_option('uxgallery_video_ht_view7_button_color'); ?> !important;
    cursor: pointer;
}

.load_more_button3:hover {
    color: <?php echo '#'.get_option('uxgallery_video_ht_view7_loadmore_font_color_hover'); ?> !important;
    background: <?php echo '#'.get_option('uxgallery_video_ht_view7_button_color_hover'); ?> !important;
}

.loading3 {
    display: none;
}

.paginate3 {
    font-size: <?php echo get_option('uxgallery_video_ht_view7_paginator_fontsize'); ?>px !important;
    color: <?php echo '#'.get_option('uxgallery_video_ht_view7_paginator_color'); ?> !important;
    text-align: <?php echo get_option('uxgallery_video_ht_view7_paginator_position'); ?>;
}

.paginate3 a {
    border-bottom: none !important;
}

.icon-style3 {
    font-size: <?php echo get_option('uxgallery_video_ht_view7_paginator_icon_size'); ?>px !important;
    color: <?php echo '#'.get_option('uxgallery_video_ht_view7_paginator_icon_color'); ?> !important;
}

.clear {
    clear: both;
}

section #uxgallery<?php echo $galleryID; ?> li:hover .overLayer {
    -webkit-transition: opacity 0.3s linear;
    -moz-transition: opacity 0.3s linear;
    -ms-transition: opacity 0.3s linear;
    -o-transition: opacity 0.3s linear;
    transition: opacity 0.3s linear;
    opacity: <?php echo (get_option("uxgallery_thumb_title_background_transparency")/100)+0.001; ?>;
    display: block;
    background: #<?php echo get_option("uxgallery_thumb_title_background_color"); ?>;
    /*border-radius:



















<?php  if(get_option("uxgallery_thumb_image_border_width") == 0) echo get_option("uxgallery_thumb_image_border_radius"); else echo 0; ?>                    px;*/
}

section #uxgallery<?php echo $galleryID; ?> li:hover .infoLayer {
    -webkit-transition: opacity 0.3s linear;
    -moz-transition: opacity 0.3s linear;
    -ms-transition: opacity 0.3s linear;
    -o-transition: opacity 0.3s linear;
    transition: opacity 0.3s linear;
    opacity: 1;
    display: block;
}

section #uxgallery<?php echo $galleryID; ?> p {
    text-align: center;
}

<?php
switch ($like_dislike) {
    case "dislike":
?>
/*/////Like/Dislike Styles END//////like/dislike//////*/
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 3;
    color: #<?php echo get_option('uxgallery_ht_thumb_likedislike_font_color'); ?>;
    display: block;
}

.element_<?php echo $galleryID; ?>:hover .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
    display: table;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
    position: relative;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_thumb_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_thumb_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
    display: inline-block;
    border-radius: 3px;
    font-size: 0;
    cursor: pointer;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
    margin: 3px;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
    margin: 3px 3px 3px 0;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like {
    font-size: 0;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
    display: block;
    float: left;
    padding: 0px 4px 0px 18px;
    font-size: 12px;
    font-weight: 700;
    position: relative;
    height: 23px;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count,
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike_count {
    display: block;
    float: left;
    padding: 4px 4px 4px 4px;
    font-size: 12px;
    font-weight: 700;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike {
    font-size: 0;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike .ux_dislike_thumb {
    display: block;
    float: left;
    padding: 0px 4px 0px 18px;
    font-size: 12px;
    font-weight: 700;
    position: relative;
    height: 23px;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_up {
    font-size: 17px;
    position: absolute;
    top: 5px;
    left: 4px;
    color: #<?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_color')?>;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .dislike_thumb_down {
    font-size: 17px;
    position: absolute;
    top: 4px;
    left: 4px;
    color: #<?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_color')?>;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_hide {
    display: none !important;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active {
    color: # <?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_active_color')?> !important;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active {
    color: # <?php echo get_option('uxgallery_ht_thumb_active_font_color')?> !important;
}

@media screen and (min-width: 768px) {
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like {
        color: # <?php echo get_option('uxgallery_ht_thumb_active_font_color')?> !important;
    }

    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .like_thumb_up {
        color: # <?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_active_color')?> !important;
    }

    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .ux_dislike {
        color: # <?php echo get_option('uxgallery_ht_thumb_active_font_color')?> !important;
    }

    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .dislike_thumb_down {
        color: # <?php echo get_option('uxgallery_ht_thumb_likedislike_thumb_active_color')?> !important;
    }
}

/*/////Like/Dislike Styles END//////like/dislike//////*/
<?php break;
    case 'heart':
?>
/*/////Like/Dislike Styles BEGIN//////Heart//////*/
.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
    display: block;
}

.element_<?php echo $galleryID; ?>:hover .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
    display: block;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
    position: relative;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_thumb_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_thumb_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
    display: inline-block;
    border-radius: 8px;
    font-size: 0;
    cursor: pointer;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover {
    background: #D6D4D4;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
    margin: 3px;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like {
    font-size: 0;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
    display: block;
    float: left;
<?php $heartCount='';
if(get_option('uxgallery_ht_thumb_rating_count')=='off'){
    $heartCount="transparent";
}else{
    $heartCount='#'.get_option('uxgallery_ht_thumb_likedislike_font_color');
}
?> color: <?php echo $heartCount.';'; ?> width: 38px;
    height: 26px;
    padding: 10px 0 0 0;
    font-size: 12px;
    text-align: center;
    font-weight: 700;
    position: relative;
    cursor: pointer;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:after {
    color: #fff;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count {
    display: none;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .likeheart {
    font-size: 32px;
    color: #<?php echo get_option('uxgallery_ht_thumb_heart_likedislike_thumb_color')?>;
    position: absolute;
    top: 4px;
    left: 3px;
    transition: 0.3s ease;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active {
    color: <?php echo "#".get_option('uxgallery_ht_thumb_heart_likedislike_thumb_active_color')?> !important;
}

.uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active {
<?php if(get_option('uxgallery_ht_thumb_rating_count')!='off'):?> color: <?php echo "#".get_option('uxgallery_ht_thumb_active_font_color')?> !important;
<?php endif; ?>
}

@media screen and (min-width: 768px) {
    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like_thumb {
    <?php if(get_option('uxgallery_ht_thumb_rating_count')!='off'):?> color: <?php echo "#".get_option('uxgallery_ht_thumb_active_font_color')?> !important;
    <?php endif; ?>
    }

    .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .likeheart {
        color: <?php echo "#".get_option('uxgallery_ht_thumb_heart_likedislike_thumb_active_color')?> !important;
    }
}

/*/////Like/Dislike Styles END//////Heart//////*/
<?php
    break;
    }?>

<?php
$cat_style = get_option('uxgallery_album_thumbnail_category_style');
$count_style = get_option('uxgallery_album_thumbnail_count_style');

$container_bg = get_option('uxgallery_thumb_box_background');
$container_padding =get_option('uxgallery_thumb_box_padding');
$container_shadow = get_option('uxgallery_thumb_box_use_shadow');
$container_has_bg = get_option('uxgallery_thumb_box_has_background');

$title_font_size = get_option('uxgallery_thumb_title_font_size');

$image_width = get_option('uxgallery_thumb_image_width');
$image_height = get_option('uxgallery_thumb_image_height');
$image_border = get_option('uxgallery_thumb_image_border_width');
$image_border_color = get_option('uxgallery_thumb_image_border_color');
$image_border_radius = get_option('uxgallery_thumb_image_border_radius');
$image_margin = get_option('uxgallery_thumb_margin_image');
$image_behavior = get_option('uxgallery_image_natural_size_thumbnail');

$img_hover_dark_text_color = get_option('uxgallery_album_thumbnail_dark_text_color');
$img_hover_blur_text_color = get_option('uxgallery_album_thumbnail_blur_text_color');
$img_hover_scale_bg = get_option('uxgallery_album_thumbnail_scale_color');
$img_hover_scale_opacity =  get_option('uxgallery_album_thumbnail_scale_opacity');
$img_hover_scale_text_color = get_option('uxgallery_album_thumbnail_scale_text_color');
$img_hover_bottom_bg = get_option('uxgallery_album_thumbnail_bottom_color');
$img_hover_bottom_text_color = get_option('uxgallery_album_thumbnail_bottom_text_color');
$img_hover_elastic_text_color = get_option('uxgallery_album_thumbnail_elastic_text_color');
?>

.album_images_count {
    float: right;
    position: absolute;
    top: 3px;
    right: 3px;
}

#hover {
    color: rgba(188, 175, 204, 0.9);
}

h2#testimonials {
    color: #fffae3;
}

div#all {
    width: 100%;
    height: 100%;
}

.album_back_button .album_socials {
    float: right;
    top: -8px
}

.album_back_button {
    margin-bottom: 15px;
}

.album_back_button .uxmodernsl-share-buttons {
    margin: 0px;
    padding: 6px 1px 0px 5px;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 3px;
}

#back_to_albums, #back_to_galleries {
    background: #616161;
    padding: 10px;
    color: #fff;
    border-radius: 3px;
}

#back_to_albums:hover, #back_to_galleries:hover {
    background-color: #000;
}

.view img {
    height: auto;
}

/*=========  container style ===========*/
#album_list, .gallery_images {
<?php if ($container_has_bg == "on"){
    echo "background-color:#".$container_bg.";";
}
if($container_shadow == "on"){
    echo "box-shadow: 0 0 10px;";
}
?> padding: <?= $container_padding?>px;
    text-align: center;
}

/*=========  hover style ===========*/

.view {
    color: #fff;
    margin: <?= $image_margin ?>px;
    overflow: hidden;
    position: relative;
    text-align: center;
    cursor: default;
    width: <?= $image_width ?>px !important;
    height: <?= $image_height ?>px !important;
    height: auto;
    border: <?= $image_border."px" ?> solid #<?= $image_border_color ?>;
    border-radius: <?= $image_border_radius ?>px;
    display: inline-block;
}

.view img {
    display: block;
    position: relative;
    transition: all 0.2s linear;
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
}

.view img, .view-wrapper {
<?php if($image_behavior == "resize"){ ?> height: 100%;
    width: 100%;

<?php }else{ ?> height: 100%;
    width: auto;
<?php } ?>
}

.mask-text h2 {
    font-size: <?= $title_font_size ?>px !important;
}

/* view 1 */
.view-first .text-category, .view-first .mask-text h2, .view-first .mask-text p {
    color: #<?= $img_hover_dark_text_color ?>;
}

/*  view 2 */
.view-second .text-category, .view-second .mask-text h2, .view-second p {
    color: #<?= $img_hover_blur_text_color ?>;
}

.view-second .mask-text h2 {
    border-bottom: 1px solid #<?= $img_hover_blur_text_color ?>;
}

/* view 3*/
.view-third .mask {
    background-color: <?php list($r,$g,$b) = array_map('hexdec',str_split($img_hover_scale_bg, 2));
				$opacity=$img_hover_scale_opacity;
				echo 'rgba('.$r.','.$g.','.$b.','.$opacity.')  !important'; ?>;
    transition: all 0.5s linear;
    opacity: 0;
    color: #<?= $img_hover_scale_text_color ?>
}

.view-third h2 {
    border-bottom: 1px solid #<?= $img_hover_scale_text_color ?>;
    background: transparent;
    margin: 5px 40px 0px 40px;
    transform: scale(0);
    color: #<?= $img_hover_scale_text_color ?>;
    transition: all 0.5s linear;
    opacity: 0;
}

.view-third p {
    color: #<?= $img_hover_scale_text_color ?>;
    opacity: 0;
    transform: scale(0);
    transition: all 0.5s linear;
}

.view-third .text-category {
    color: #<?= $img_hover_scale_text_color ?>;
}

/* view 4 */

.view-forth .mask-bg {
    background: #<?= $img_hover_bottom_bg ?>;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.view-forth .mask-text {
    color: #<?= $img_hover_bottom_text_color ?>;
    position: relative;
    z-index: 500;
    padding: 5px 8px;
}

.view-forth .mask-text h2 {
    margin: 0px;
    font-size: 13px;
    padding: 2px;
    color: #<?= $img_hover_bottom_text_color ?>;
}

.view-forth .mask-text h2:hover {
    cursor: pointer;
}

.view-forth .text-category {
    display: block;
    font-size: 15px;
    color: #<?= $img_hover_bottom_text_color ?>;
}

.view-forth p {
    color: #<?= $img_hover_bottom_text_color ?>;
}

/* view 5 */
.view-fifth .text-category, .view-fifth .text-category *, .view-fifth .mask-text h2, .view-fifth .mask-text p {
    color: #<?=$img_hover_elastic_text_color?>;
}

/*=========  category style ===========*/

.album_categories li span {
    float: left;
    margin: 0 5px 5px 5px;
    display: block;
    text-align: center;
    padding: 7px 16px;
    text-decoration: none;
<?php if($cat_style == 0){ ?> background-color: #43454f;
    color: white;
    border-radius: 3px;
<?php }elseif($cat_style == 1){ ?> background-color: #e9515f;
    border-radius: 7px;
    color: #fff;
<?php }elseif($cat_style == 2){ ?> background-color: #fff;
    border: 2px solid #43454f;
    border-radius: 3px;
    color: #43454f;
<?php }elseif($cat_style == 3){ ?> background-image: url("<?= UXGALLERY_IMAGES_URL."/albums/category/bg_3.png"; ?>");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50%;
    color: #fff;
    border-radius: 4px;
    border: 2px solid #7b2436;
    box-shadow: -1px -2px 4px rgba(4, 4, 4, 0.42);
<?php }elseif($cat_style == 4){ ?> background-image: url("<?= UXGALLERY_IMAGES_URL."/albums/category/bg_4.png"; ?>");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50%;
    color: #fff;
    border-radius: 4px;
    border: 2px solid #78985d;
    border-bottom-color: #758865;
    box-shadow: 0px 1px 4px rgba(4, 4, 4, 0.42);
<?php }elseif($cat_style == 5){ ?> background-color: #ed1b52;
    border-radius: 3px;
    color: #ffffff;
<?php }elseif($cat_style == 6){ ?> background-color: #42cb6f;
    color: #fff;
    border-bottom: 4px solid #3ab75c;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
<?php } ?>
}

.album_categories li span.active, .album_categories li span:hover {
    cursor: pointer;

<?php if($cat_style == 0){ ?> background-color: #2e303c;
<?php }elseif($cat_style == 1){ ?> background-color: #b93642;
<?php }elseif($cat_style == 2){ ?> background-color: #43454f;
    color: #ffffff;
<?php }elseif($cat_style == 3){ ?> color: #fff683;
<?php }elseif($cat_style == 4){ ?> color: #2f4a18;
<?php }elseif($cat_style == 5){ ?> background-color: #ab1b41;
<?php }elseif($cat_style == 6){ ?> background-color: #3ab75c;
<?php } ?>
}

/*=========  count style ===========*/

.album_images_count {
    font-size: 17px !important;
    font-weight: bold !important;
    font-family: sans-serif !important;
    background-repeat: no-repeat !important;
    background-size: contain !important;
    z-index: 2;
    width: 47px;
    height: 47px;
    padding-top: 6px !important;
<?php
    switch($count_style)
    {
    case 0:
       $count = 0;
       $color = "#565656";
       break;
    case 1:
       $count = 1;
       $color = "#565656";
       echo "width: 65px;";
       echo "background-size: contain !important;";
       break;
    case 2:
       $count = 2;
       $color = "#ffffff";
       break;
    case 3:
       $count = 3;
       $color = "#ffffff";
       break;
    case 4:
       $count = 4;
       $color = "#ffffff";
       echo "background-size: contain !important; width: 87px; height: 30px; text-align: right;padding:0px !important; padding-right: 12px !important;font-size:15px !important;";
       break;
    default:
       $count = 3;
       $color = "#ffffff";
       break;
    }

?> background-image: url('<?= UXGALLERY_IMAGES_URL."/albums/count/".$count.".png" ?>') !important;
    color: <?= $color; ?>;
}

.count_image {
    font-size: 9px;
    position: absolute;
<?php if($count_style == 4){
    echo "bottom:4px; right:14px;";
}
else echo "bottom: 18px; left:8px;";
    ?>
}

@media only screen and (max-width: 475px) {
    .view {
        width: auto !important;
        max-width: <?= $image_width ?>px !important;
        height: auto !important;
        max-height: <?= $image_height ?>px !important;
    }
}

<?= '</style>' ?>