<?= '<style>' ?>

#uxgallery_popup_list_<?php echo $galleryID; ?> .dispFloat{
    display: inline-block;
    float: left;
}
.element_<?php echo $galleryID; ?> {
	width: 100%;
	max-width: <?php echo get_option('uxgallery_ht_view2_element_width')+2*get_option('uxgallery_ht_view2_element_border_width'); ?>px;
	height: <?php echo get_option('uxgallery_ht_view2_element_height')+45+2*get_option('uxgallery_ht_view2_element_border_width'); ?>px;
	margin: 0 10px 10px 0;
	background: #<?php echo get_option('uxgallery_ht_view2_element_background_color'); ?>;
	border: <?php echo get_option('uxgallery_ht_view2_element_border_width'); ?>px solid #<?php echo get_option('uxgallery_ht_view2_element_border_color'); ?>;
	outline: none;
	box-sizing: border-box;
}
.element_<?php echo $galleryID; ?>.no-title{
	height: <?php echo get_option('uxgallery_ht_view2_element_height')+2*get_option('uxgallery_ht_view2_element_border_width'); ?>px;
}
.element_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> {
<?php if(get_option('uxgallery_image_natural_size_contentpopup')=='resize'){?> position: relative;
	width: 100%;
<?php }elseif(get_option('uxgallery_image_natural_size_contentpopup')=='natural'){?> position: relative;
	width: 100%;
	overflow: hidden;
	height: <?php echo get_option('uxgallery_ht_view2_element_height'); ?>px !important;
<?php }?>
}

.element_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> img {
<?php if(get_option('uxgallery_image_natural_size_contentpopup')=='resize'){?> width: 100% !important;
	height: <?php echo get_option('uxgallery_ht_view2_element_height'); ?>px !important;
	display: block;
	border-radius: 0 !important;
	box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
<?php }elseif(get_option('uxgallery_image_natural_size_contentpopup')=='natural'){?> display: block;
	max-width: none !important;
	border-radius: 0 !important;
	box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
<?php }?>
}

.element_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> .gallery-image-overlay {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_view2_element_overlay_color'),2));
				$titleopacity=get_option("uxgallery_ht_view2_element_overlay_transparency")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: none;
}

.element_<?php echo $galleryID; ?>:hover .image-block_<?php echo $galleryID; ?> .gallery-image-overlay {
	display: block;
}

.element_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> .gallery-image-overlay a {
	position: absolute;
	top: 0;
	left: 0;
	display: block;
	width: 100%;
	height: 100%;
	box-shadow: none !important;
	background: url('<?php echo  UXGALLERY_IMAGES_URL.'/admin_images/zoom.'.get_option("uxgallery_ht_view2_zoombutton_style").'.png'; ?>') center center no-repeat;
}

.element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> {
	position: relative;
	height: 30px;
	margin: 0;
	padding: 15px 0 15px 0;
	-webkit-box-shadow: inset 0 1px 0 rgba(0, 0, 0, .1);
	box-shadow: inset 0 1px 0 rgba(0, 0, 0, .1);
}

.element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> h3 {
	position: relative;
	margin: 0 !important;
	padding: 0 1% 5px 1% !important;
	width: 98%;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
	font-weight: normal;
	font-size: <?php echo get_option("uxgallery_ht_view2_element_title_font_size");?>px !important;
	line-height: <?php echo get_option("uxgallery_ht_view2_popup_title_font_size");?>px !important;
	color: #<?php echo get_option("uxgallery_ht_view2_element_title_font_color");?>;
}

.element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> .button-block {
	position: absolute;
	right: 0;
	top: 0;
	display: none;
	vertical-align: middle;
	padding: 10px 10px 4px 10px;

	border-left: 1px solid rgba(0, 0, 0, .05);
}

.element_<?php echo $galleryID; ?>:hover .title-block_<?php echo $galleryID; ?> .button-block {
	display: block;
}

.element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> a, .element .title-block_<?php echo $galleryID; ?> a:link, .element .title-block_<?php echo $galleryID; ?> a:visited,
.element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> a:hover, .element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> a:focus, .element_<?php echo $galleryID; ?> .title-block_<?php echo $galleryID; ?> a:active {
	position: relative;
	display: block;
	vertical-align: middle;
	padding: 3px 10px 3px 10px;
	border-radius: 3px;
	font-size: <?php echo get_option("uxgallery_ht_view2_element_linkbutton_font_size");?>px;
	background: #<?php echo get_option("uxgallery_ht_view2_element_linkbutton_background_color");?>;
	color: #<?php echo get_option("uxgallery_ht_view2_element_linkbutton_color");?>;
	text-decoration: none !important;
}

.load_more5 {
	margin: 10px 0;
	position: relative;
	text-align: <?php if(get_option('uxgallery_video_ht_view1_loadmore_position') == 'left') {echo 'left';}
			elseif (get_option('uxgallery_video_ht_view1_loadmore_position') == 'center') { echo 'center'; }
			elseif(get_option('uxgallery_video_ht_view1_loadmore_position') == 'right') { echo 'right'; }?>;
	width: 100%;
}

.load_more_button5 {
	border-radius: 10px;
	display: inline-block;
	padding: 5px 15px;
	font-size: <?php echo get_option('uxgallery_video_ht_view1_loadmore_fontsize'); ?>px !important;;
	color: <?php echo '#'.get_option('uxgallery_video_ht_view1_loadmore_font_color'); ?> !important;;
	background: <?php echo '#'.get_option('uxgallery_video_ht_view1_button_color'); ?> !important;
	cursor: pointer;
}

.load_more_button5:hover {
	color: <?php echo '#'.get_option('uxgallery_video_ht_view1_loadmore_font_color_hover'); ?> !important;
	background: <?php echo '#'.get_option('uxgallery_video_ht_view1_button_color_hover'); ?> !important;
}

.loading5 {
	display: none;
}

.paginate5 {
	font-size: <?php echo get_option('uxgallery_video_ht_view1_paginator_fontsize'); ?>px !important;
	color: <?php echo '#'.get_option('uxgallery_video_ht_view1_paginator_color'); ?> !important;
	text-align: <?php echo get_option('uxgallery_video_ht_view1_paginator_position'); ?>;
	margin-top: 15px;
}

.paginate5 a {
	border-bottom: none !important;
}

.icon-style5 {
	font-size: <?php echo get_option('uxgallery_video_ht_view1_paginator_icon_size'); ?>px !important;
	color: <?php echo '#'.get_option('uxgallery_video_ht_view1_paginator_icon_color'); ?> !important;
}

.clear {
	clear: both;
}

/*#####POPUP#####*/
#uxgallery_popup_list_<?php echo $galleryID; ?> {
	position: fixed;
	display: table;
	width: 80%;
	top: 7%;
	left: 7%;
	margin: 0 !important;
	padding: 0 !important;
	list-style: none;
	z-index: 100000000;
	display: none;
	height: 85%;
}

#uxgallery_popup_list_<?php echo $galleryID; ?>.active {
	display: table;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element {
	position: relative;
	display: none;
	width: 100%;
	padding: 40px 0 20px 0;
	min-height: 100%;
	position: relative;
	background: #<?php echo get_option("uxgallery_ht_view2_popup_background_color");?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element.active {
	display: block;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> {
	position: absolute;
	width: 100%;
	height: 40px;
	top: 0;
	left: 0;
	z-index: 2001;
	background: url('<?php echo  UXGALLERY_IMAGES_URL.'/admin_images/divider.line.png'; ?>') center bottom repeat-x;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .close, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .close:link, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .close:visited {
	position: relative;
	float: right;
	width: 40px;
	height: 40px;
	display: block;
	background: url('<?php echo  UXGALLERY_IMAGES_URL.'/admin_images/close.popup.'.get_option("uxgallery_ht_view2_popup_closebutton_style").'.png'; ?>') center center no-repeat;
	border-left: 1px solid #ccc;
	opacity: .65;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .close:hover, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .close:focus, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .close:active {
	opacity: 1;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element .popup-wrapper_<?php echo $galleryID; ?> {
	position: relative;
	width: 98%;
	height: 98%;
	padding: 2% 0% 0% 2%;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> {
	width: 55%;
<?php if(get_option('uxgallery_ht_view2_popup_full_width') == 'off') { echo "height:100%;"; }
	else { echo "height:100%;"; }?> position: relative;
	float: left;
	margin-right: 2%;
	border-right: 1px solid #ccc;
	min-width: 200px;
	min-height: 100%;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> img {
<?php
	if(get_option('uxgallery_ht_view2_popup_full_width') == 'off') { echo "max-width:100% !important; max-height:100% !important;margin: 0 auto !important; position:relative !important; display:block;"; }
	else { echo "width:100% !important;"; }
?> display: block;
	padding: 0 !important;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> iframe {
	width: 100% !important;
	height: 100%;
	display: block;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block {
	width: 42.8%;
	height: 100%;
	position: relative;
	float: left;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element .popup-wrapper_<?php echo $galleryID; ?> .right-block > div {
	padding-top: 10px;
	padding-right: 4%;
	margin-bottom: 10px;
<?php if(get_option('uxgallery_ht_view2_show_separator_lines')=="on") {?> background: url('<?php echo  UXGALLERY_IMAGES_URL.'/admin_images/divider.line.png'; ?>') center top repeat-x;
<?php } ?>
}

#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element .popup-wrapper_<?php echo $galleryID; ?> .right-block > div:last-child {
	background: none;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .title {
	position: relative;
	display: block;
	margin: 0 0 10px 0 !important;
	font-size: <?php echo get_option("uxgallery_ht_view2_popup_title_font_size");?>px !important;
	line-height: <?php echo get_option("uxgallery_ht_view2_popup_title_font_size");?>px !important;
	color: #<?php echo get_option("uxgallery_ht_view2_popup_title_font_color");?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description {
	clear: both;
	position: relative;
	font-weight: normal;
	text-align: justify;
	font-size: <?php echo get_option("uxgallery_ht_view2_description_font_size");?>px !important;
	color: #<?php echo get_option("uxgallery_ht_view2_description_color");?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description h1,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description h2,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description h3,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description h4,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description h5,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description h6,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description p,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description strong,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description span {
	padding: 2px !important;
	margin: 0 !important;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description ul,
#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block .description li {
	padding: 2px 0 2px 5px;
	margin: 0 0 0 8px;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block ul.thumbs-list {
	list-style: none;
	display: table;
	position: relative;
	clear: both;
	width: 100%;
	margin: 0 auto;
	padding: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block ul.thumbs-list li {
	display: block;
	float: left;
	width: <?php echo get_option("uxgallery_ht_view2_thumbs_width");?>px;
	height: <?php echo get_option("uxgallery_ht_view2_thumbs_height");?>px;
	margin: 0 2% 5px 1% !important;
	opacity: 0.45;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block ul.thumbs-list li.active, #uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block ul.thumbs-list li:hover {
	opacity: 1;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block ul.thumbs-list li a {
	display: block;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block ul.thumbs-list li img {
	width: <?php echo get_option("uxgallery_ht_view2_thumbs_width");?>px !important;
	height: <?php echo get_option("uxgallery_ht_view2_thumbs_height");?>px !important;
}

/**/
#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .left-change, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .right-change {
	width: 40px;
	height: 39px;
	font-size: 25px;
	display: inline-block;
	text-align: center;
	border: 1px solid #eee;
	border-bottom: none;
	border-top: none;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .right-change {
	positio: relative;
	margin-left: -6px;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .right-change:hover, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .left-change:hover {
	background: #ddd;
	border-color: #ccc;
	color: #000 !important;
	cursor: pointer;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .right-change a, #uxgallery_popup_list_<?php echo $galleryID; ?> .heading-navigation_<?php echo $galleryID; ?> .left-change a {
	position: absolute;
	top: 50%;
	transform: translate(-50%, -50%);
	color: #777;
	text-decoration: none;
	width: 12px;
	height: 24px;
	line-height: 1;
	display: inline-block;
}

/**/
.popup-element .button-block {
	position: relative;
}

.popup-element .button-block a, .popup-element .button-block a:link, .popup-element .button-block a:visited {
	position: relative;
	display: inline-block;
	padding: 6px 12px;
	background: #<?php echo get_option("uxgallery_ht_view2_popup_linkbutton_background_color");?>;
	color: #<?php echo get_option("uxgallery_ht_view2_popup_linkbutton_color");?>;
	font-size: <?php echo get_option("uxgallery_ht_view2_popup_linkbutton_font_size");?>px;
	text-decoration: none;
}

.popup-element .button-block a:hover, .popup-element .button-block a:focus, .popup-element .button-block a:active {
	background: #<?php echo get_option("uxgallery_ht_view2_popup_linkbutton_background_hover_color");?>;
	color: #<?php echo get_option("uxgallery_ht_view2_popup_linkbutton_font_hover_color");?>;
}

#uxgallery-popup-overlay-image {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 199;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_view2_popup_overlay_color'),2));
				$titleopacity=get_option("uxgallery_ht_view2_popup_overlay_transparency_color")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>
}

@media only screen and (max-width: 767px) {
	#uxgallery_popup_list_<?php echo $galleryID; ?> {
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: auto !important;
		left: 0;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element {
		margin: 0;
		height: auto !important;
		position: absolute;
		left: 0;
		top: 0;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> li.popup-element .popup-wrapper_<?php echo $galleryID; ?> {
		height: auto !important;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .image-block_<?php echo $galleryID; ?> {
		width: 100%;
		float: none;
		clear: both;
		margin-right: 0;
		border-right: 0;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> .popup-wrapper_<?php echo $galleryID; ?> .right-block {
		width: 100%;
		float: none;
		clear: both;
		margin-right: 0;
		border-right: 0;
	}

	#uxgallery-popup-overlay-image_<?php echo $galleryID; ?> {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 199;
	}
}

<?php switch ($like_dislike) {
case "dislike":
?>
/*/////Like/Dislike Styles BEGIN//////Dislike//////*/
#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
    float: right;
    color: #<?php echo get_option('uxgallery_ht_popup_likedislike_font_color'); ?>;
    position: absolute;
    top: 0;
    right: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper,
#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
	position: relative;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_popup_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_popup_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: inline-block;
	border-radius: 3px;
	font-size: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
	margin: 3px;
	cursor: pointer;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
	margin: 3px 3px 3px 0;
	cursor: pointer;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like {
	font-size: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
	display: block;
	float: left;
	padding: 0px 4px 0px 18px;
	font-size: 12px;
	font-weight: 700;
	position: relative;
	height: 23px;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count,
#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike_count {
	display: block;
	float: left;
	padding: 4px 4px 4px 4px;
	font-size: 12px;
	font-weight: 700;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike {
	font-size: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike .ux_dislike_thumb {
	display: block;
	float: left;
	padding: 0px 4px 0px 18px;
	font-size: 12px;
	font-weight: 700;
	position: relative;
	height: 23px;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_up {
	font-size: 17px;
	position: absolute;
	top: 5px;
	left: 4px;
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_color'); ?>;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .dislike_thumb_down {
	font-size: 17px;
	position: absolute;
	top: 4px;
	left: 4px;
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_color'); ?>;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_hide {
	display: none !important;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active {
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?> !important;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active {
	color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
}

@media screen and (min-width: 768px) {
	#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like {
		color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
	}

	#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .like_thumb_up {
		color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?> !important;
	}

	#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .ux_dislike {
		color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
	}

	#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .dislike_thumb_down {
		color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?> !important;
	}
}

/*///////////////////POPUP////////////////*/
#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_font_color'); ?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper,
#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
	position: relative;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_popup_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_popup_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: inline-block;
	border-radius: 3px;
	font-size: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
	margin: 3px;
	cursor: pointer;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper {
	margin: 3px 3px 3px 0;
	cursor: pointer;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like {
	font-size: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
	display: block;
	float: left;
	padding: 4px 4px 4px 18px;
	font-size: 12px;
	font-weight: 700;
	position: relative;
	height: 28px;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count,
#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike_count {
	display: block;
	float: left;
	padding: 4px 4px 4px 4px;
	font-size: 12px;
	font-weight: 700;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike {
	font-size: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_dislike .ux_dislike_thumb {
	display: block;
	float: left;
	padding: 4px 4px 4px 18px;
	font-size: 12px;
	font-weight: 700;
	position: relative;
	height: 28px;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_up {
	font-size: 17px;
	position: absolute;
	top: 5px;
	left: 4px;
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_color'); ?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .dislike_thumb_down {
	font-size: 17px;
	position: absolute;
	top: 4px;
	left: 4px;
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_color'); ?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_hide {
	display: none !important;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active {
	color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?> !important;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active {
	color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
}

@media screen and (min-width: 768px) {
	#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like {
		color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .like_thumb_up {
		color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?> !important;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .ux_dislike {
		color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_dislike_wrapper:hover .dislike_thumb_down {
		color: #<?php echo get_option('uxgallery_ht_popup_likedislike_thumb_active_color'); ?> !important;
	}
}

<?php break; ?>
/*///////////////////POPUP////////////////*/
/*/////Like/Dislike Styles END//////Dislike//////*/
<?php case "heart":
?>
/*/////Like/Dislike Styles BEGIN//////Heart//////*/
#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
    float: right;
    position: absolute;
    top: 0;
    right: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
	position: relative;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_popup_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_popup_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: inline-block;
	border-radius: 8px;
	font-size: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover {
	background: #<?php echo get_option('uxgallery_ht_popup_heart_hov_bg_color'); ?>;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
	margin: 3px;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like {
	font-size: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
	display: block;
	float: left;
<?php $heartCount='';
if(get_option('uxgallery_ht_popup_rating_count')=='off'){
	$heartCount="transparent";
}else{
	$heartCount='#'.get_option('uxgallery_ht_popup_likedislike_font_color');
}
?> color: <?php echo $heartCount.';'; ?> width: 38px;
	height: 38px;
	padding: 8px 0;
	font-size: 12px;
	text-align: center;
	font-weight: 700;
	position: relative;
	cursor: pointer;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:after {
	color: #fff;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count {
	display: none;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:hover:after {
	opacity: 1;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .likeheart {
	font-size: 32px;
	color: #<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_color'); ?>;
	position: absolute;
	top: 4px;
	left: 3px;
	transition: 0.3s ease;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active {
	color: #<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_active_color'); ?> !important;
}

#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active {
<?php if(get_option('uxgallery_ht_popup_rating_count')!='off'):?> color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
<?php endif; ?>
}

@media screen and (min-width: 768px) {
	#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like_thumb {
	<?php if(get_option('uxgallery_ht_popup_rating_count')!='off'):?> color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
	<?php endif; ?>
	}

	#uxgallery_content_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .likeheart {
		color: #<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_active_color'); ?> !important;
	}
}

/*///////////////POPUP//////////////////*/
#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> {
	position: absolute;
	top: 0;
	right: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
	position: relative;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split(get_option('uxgallery_ht_popup_likedislike_bg'),2));
				$titleopacity=get_option("uxgallery_ht_popup_likedislike_bg_trans")/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
	display: inline-block;
	border-radius: 8px;
	font-size: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover {
	background: #<?php echo get_option('uxgallery_ht_popup_heart_hov_bg_color'); ?>;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper {
	margin: 3px;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like {
	font-size: 0;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb {
	display: block;
	float: left;
<?php $heartCount='';
if(get_option('uxgallery_ht_popup_rating_count')=='off'){
	$heartCount="transparent";
}else{
	$heartCount='#'.get_option('uxgallery_ht_popup_likedislike_font_color');
}
?> color: <?php echo $heartCount.';'; ?> width: 38px;
	height: 38px;
	padding: 8px 0;
	font-size: 12px;
	text-align: center;
	font-weight: 700;
	position: relative;
	cursor: pointer;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:after {
	color: #fff;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like_count {
	display: none;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .ux_like_thumb:hover:after {
	opacity: 1;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .ux_like .likeheart {
	font-size: 32px;
	color: #<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_color'); ?>;
	position: absolute;
	top: 4px;
	left: 3px;
	transition: 0.3s ease;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_thumb_active {
	color: #<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_active_color'); ?> !important;
}

#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .like_font_active {
<?php if(get_option('uxgallery_ht_popup_rating_count')!='off'):?> color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
<?php endif; ?>
}

@media screen and (min-width: 768px) {
	#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .ux_like_thumb {
	<?php if(get_option('uxgallery_ht_popup_rating_count')!='off'):?> color: #<?php echo get_option('uxgallery_ht_popup_active_font_color'); ?> !important;
	<?php endif; ?>
	}

	#uxgallery_popup_list_<?php echo $galleryID; ?> .uxgallery_like_cont_<?php echo $galleryID.$pID; ?> .uxgallery_like_wrapper:hover .likeheart {
		color: #<?php echo get_option('uxgallery_ht_popup_heart_likedislike_thumb_active_color'); ?> !important;
	}
}

/*/////Like/Dislike Styles END//////Heart//////*/

<?php break;
}?>

<?php
  $cat_style = get_option('uxgallery_album_popup_category_style');
  $count_style = get_option('uxgallery_album_popup_count_style');

  $image_width = get_option('uxgallery_ht_view2_element_width');
  $image_height = get_option('uxgallery_ht_view2_element_height');
  $image_border = get_option('uxgallery_ht_view2_element_border_width');
  $image_border_color = get_option('uxgallery_ht_view2_element_border_color');
  $image_behavior = get_option('uxgallery_image_natural_size_contentpopup');

  $title_font_size = get_option('uxgallery_ht_view2_element_title_font_size');

  $img_hover_dark_text_color = get_option('uxgallery_album_popup_dark_text_color');
  $img_hover_blur_text_color = get_option('uxgallery_album_popup_blur_text_color');
  $img_hover_scale_bg = get_option('uxgallery_album_popup_scale_color');
  $img_hover_scale_opacity =  get_option('uxgallery_album_popup_scale_opacity');
  $img_hover_scale_text_color = get_option('uxgallery_album_popup_scale_text_color');
  $img_hover_bottom_bg = get_option('uxgallery_album_popup_bottom_color');
  $img_hover_bottom_text_color = get_option('uxgallery_album_popup_bottom_text_color');
  $img_hover_elastic_text_color = get_option('uxgallery_album_popup_elastic_text_color');
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

.mosaicflow__column {
    float: left;
    width: <?= $image_width ?>px !important;
    margin-right: 10px;
}

/*=========  hover style ===========*/

.view {
    color: #fff;
    margin: 0 10px 10px 0;
    overflow: hidden;
    position: relative;
    text-align: center;
    float: left;
    cursor: default;
    width: <?= $image_width ?>px !important;
    height: <?= $image_height ?>px !important;
    border: <?= $image_border."px" ?> solid #<?= $image_border_color ?>;
}

.view-wrapper {
    height: 100%;
}

.view img {
    display: block;
    position: relative;
    transition: all 0.2s linear;
    height: 100%;
<?php if($image_behavior == 'natural'){ ?> width: auto;
<?php }else{ ?> width: 100%;
<?php }?> max-width: 100%;
    margin: 0 auto;
}

.mask-text h2 {
    font-size: <?= $title_font_size ?>px !important;
    margin-top: 20px !important;
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
    margin: 20px 40px 0px 40px;
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

#envirabox-thumbs ul {
    width: 20000px !important;
}

<?php if(get_option('uxgallery_album_popup_window_thumbnails') == 'off') {?>
#envirabox-thumbs {
    display: none;
}

<?php }
if(get_option('uxgallery_album_popup_window_controls_on_top') == 'off'){ ?>
#envirabox-buttons {
    display: none;
}

<?php }
if(get_option('uxgallery_album_popup_window_controls') == 'off'){ ?>
.envirabox-close, .envirabox-prev, .envirabox-next {
    display: none;
}

<?php } ?>

<?= '</style>' ?>