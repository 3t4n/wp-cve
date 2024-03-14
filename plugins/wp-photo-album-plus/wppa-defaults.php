<?php
/* wppa-defaults.php
* Package: wp-photo-album-plus
*
* Contains default settings
* Version: 8.6.02.001
*
*/

// Set default option values in global $wppa_defaults
// With $force = true, all non default options will be removed from wp_options table,
// being the equivalent to resetting to default, except: revision, rating_max and filesystem
// Changin those three requires conversion
function wppa_set_defaults( $force = false ) {
global $wppa_defaults;

	// Test for too early
	if ( ! defined('WPPA_UPLOAD') ) {
		wppa_log( 'err', 'WPPA_UPLOAD not defined in wppa_set_defaults().' );
	}

	$npd = '
<a onClick="jQuery(\'.wppa-dtl\').css(\'display\', \'block\'); jQuery(\'.wppa-more\').css(\'display\', \'none\'); wppaOvlResize();">
<div class="wppa-more">
Camera info
</div>
</a>
<a onClick="jQuery(\'.wppa-dtl\').css(\'display\', \'none\'); jQuery(\'.wppa-more\').css(\'display\', \'block\'); wppaOvlResize();">
<div class="wppa-dtl" style="display:none">
Hide Camera info
</div>
</a>
<div class="wppa-dtl" style="display:none;">
<br>
<table style="margin:0; border:none">
<tr><td class="wppa-label" >Date Time</td><td class="wppa-value" >E#0132</td></tr>
<tr><td class="wppa-label" >Camera</td><td class="wppa-value" >E#0110</td></tr>
<tr><td class="wppa-label" >Focal length</td><td class="wppa-value" >E#920A</td></tr>
<tr><td class="wppa-label" >F-Stop</td><td class="wppa-value" >E#829D</td></tr>
<tr><td class="wppa-label" >ISO Speed Rating</td><td class="wppa-value" >E#8827</td></tr>
<tr><td class="wppa-label" >Exposure program</td><td class="wppa-value" >E#8822</td></tr>
<tr><td class="wppa-label" >Metering mode</td><td class="wppa-value" >E#9207</td></tr>
<tr><td class="wppa-label" >Flash</td><td class="wppa-value" >E#9209</td></tr>
</table>
</div>';

	// Nice scroll options
	$nso = "cursorwidth:'8px',
cursoropacitymin:0.4,
cursorcolor:'#777777',
cursorborder:'none',
cursorborderradius:'6px',
autohidemode:'leave',
nativeparentscrolling:false,
preservenativescrolling:false,
bouncescroll:false,
smoothscroll:true,
cursorborder:'2px solid transparent',
horizrailenabled:false,";

	// Site name for emails
	$site = home_url();
	$site = str_replace( 'https://www.', '', $site );
	$site = str_replace( 'http://www.', '', $site );
	$site = str_replace( 'https://', '', $site );
	$site = str_replace( 'http://', '', $site );
	$spos = strpos( $site, '/' );
	if ( $spos  > '2' ) {
		$site = substr( $site, 0, $spos );
	}

	$wppa_defaults = array ( 	'wppa_revision' 		=> '100',
								'wppa_prevrev'			=> '100',
								'wppa_max_users' 		=> '2500',


						// Table I: Sizes
						// A System
						'wppa_colwidth' 				=> 'auto',	// 1
						'wppa_initial_colwidth' 		=> '640',
						'wppa_resize_to'				=> '-1',		// 3
						'wppa_bwidth' 					=> '1',		// 5
						'wppa_bradius' 					=> '6',		// 6
						'wppa_box_spacing'				=> '8',		// 7
						'wppa_pagelinks_max' 			=> '7',
						'wppa_max_filename_length' 		=> '0',
						'wppa_max_photoname_length' 	=> '0',
						'wppa_sticky_header_size' 		=> '0',

						// B Fullsize
						'wppa_fullsize' 				=> '640',	// 1
						'wppa_maxheight' 				=> '480',	// 2
						'wppa_enlarge' 					=> 'no',	// 3
						'wppa_fullimage_border_width' 	=> '',		// 4
						'wppa_numbar_max'				=> '10',	// 5
						'wppa_share_size'				=> '32',
						'wppa_mini_treshold'			=> '500',
						'wppa_slideshow_pagesize'		=> '0',
						'wppa_film_thumbsize' 			=> '100',	// 9
						'wppa_slideonly_max' 			=> '0',

						// C Thumbnails
						'wppa_thumbsize' 				=> '100',		// 1
						'wppa_thumbsize_alt'			=> '130',		// 1a
						'wppa_thumb_aspect'				=> '0:0:none',	// 2
						'wppa_tf_width' 				=> '100',		// 3
						'wppa_tf_width_alt'				=> '130',		// 3a
						'wppa_tf_height' 				=> '150',		// 4
						'wppa_tf_height_alt'			=> '180',		// 4a
						'wppa_tn_margin' 				=> '4',			// 5
						'wppa_thumb_auto' 				=> 'yes',		// 6
						'wppa_thumb_page_size' 			=> '0',			// 7
						'wppa_popupsize' 				=> '150',		// 8
						'wppa_use_thumbs_if_fit'		=> 'yes',		// 9
						'wppa_area_size' 				=> '0', 		// 10
						'wppa_area_size_slide' 			=> '0',
						'wppa_slide_portrait_only' 		=> 'no',
						'wppa_area_size_audio' 			=> '0',
						'wppa_audioonly_name' 			=> 'no',
						'wppa_audioonly_desc' 			=> 'no',
						'wppa_audioonly_duration' 		=> 'no',
						'wppa_audioonly_posterpos' 		=> 'right',
						'wppa_audioonly_itemdesc' 		=> 'no',
						'wppa_nicescroll' 				=> 'no', 		// 11

						// D Covers
						'wppa_max_cover_width'				=> '1024',	// 1
						'wppa_cover_minheight' 				=> '0',		// 2
						'wppa_head_and_text_frame_height' 	=> '0', 	// 3
						'wppa_text_frame_height'			=> '54',	// 4
						'wppa_coverphoto_responsive' 		=> 'no',
						'wppa_smallsize' 					=> '150',	// 5
						'wppa_smallsize_percentage' 		=> '30',
						'wppa_smallsize_multi'				=> '100',	// 6
						'wppa_smallsize_multi_percentage' 	=> '20',
						'wppa_coversize_is_height'			=> 'no',	// 7
						'wppa_album_page_size' 				=> '0',		// 8
						'wppa_cover_spacing' 				=> '8', 	// 9

						// E Rating & comments
						'wppa_rating_max'				=> '5',		// 1
						'wppa_rating_prec'				=> '2',		// 2
						'wppa_gravatar_size'			=> '40',	// 3
						'wppa_ratspacing'				=> '30',

						// F Widgets
						'wppa_topten_count' 			=> '10',	// 1
						'wppa_topten_non_zero' 			=> 'no',
						'wppa_topten_size' 				=> '86',	// 2
						'wppa_comten_count'				=> '10',	// 3
						'wppa_comten_size'				=> '86',	// 4
						'wppa_featen_count'				=> '10',
						'wppa_featen_size'				=> '86',
						'wppa_thumbnail_widget_count'	=> '10',	// 5
						'wppa_thumbnail_widget_size'	=> '86',	// 6
						'wppa_lasten_count'				=> '10',	// 7
						'wppa_lasten_size' 				=> '86',	// 8
						'wppa_album_widget_count'		=> '10',
						'wppa_album_widget_size'		=> '86',
						'wppa_related_count'			=> '10',
						'wppa_tagcloud_min'				=> '8',
						'wppa_tagcloud_max' 			=> '24',
						'wppa_tagcloud_formula' 		=> 'linear',

						// G Overlay
						'wppa_ovl_txt_lines'			=> 'auto',	// 1
						'wppa_magnifier'				=> 'magnifier-small.png',	// 2
						'wppa_ovl_border_width' 		=> '8',
						'wppa_ovl_border_radius' 		=> '12',

						// H Video
						'wppa_video_width'				=> '640',
						'wppa_video_height' 			=> '480',

						// J Icon sizes
						'wppa_nav_icon_size' 			=> 'default',
						'wppa_nav_icon_size_slide' 		=> 'default',
						'wppa_icon_size_rating' 		=> 'default',
						'wppa_nav_icon_size_panorama' 	=> '32',
						'wppa_nav_icon_size_lightbox' 	=> '32',
						'wppa_nav_icon_size_global_fs' 	=> '32',

						// Table II: Visibility
						// A Breadcrumb
						'wppa_show_bread_posts' 			=> 'yes',	// 1a
						'wppa_show_bread_pages'				=> 'yes',	// 1b
						'wppa_bc_on_search'					=> 'yes',	// 2
						'wppa_bc_on_topten'					=> 'yes',	// 3
						'wppa_bc_on_lasten'					=> 'yes',	// 3
						'wppa_bc_on_comten'					=> 'yes',	// 3
						'wppa_bc_on_featen'					=> 'yes',
						'wppa_bc_on_tag'					=> 'yes',	// 3
						'wppa_bc_on_related'				=> 'yes',
						'wppa_show_home' 					=> 'yes',	// 4
						'wppa_home_text' 					=> __( 'Home', 'wp-photo-album-plus' ),
						'wppa_show_page' 					=> 'yes',	// 4
						'wppa_show_pname' 					=> 'yes',
						'wppa_bc_separator' 				=> 'raquo',	// 5
						'wppa_bc_txt' 						=> htmlspecialchars('<span style="color:red; font_size:24px;">&bull;</span>'),	// 6
						'wppa_bc_url' 						=> wppa_get_imgdir().'arrow.gif',	// 7
						'wppa_pagelink_pos'					=> 'bottom',	// 8
						'wppa_bc_slide_thumblink'			=> 'no',

						// B Slideshow
						'wppa_navigation_type' 				=> 'icons', 	// 0
						'wppa_show_startstop_navigation' 	=> 'yes',		// 1
						'wppa_show_startstop_filmonly' 		=> 'no',
						'wppa_show_renew_filmonly' 			=> 'no',
						'wppa_show_browse_navigation' 		=> 'yes',		// 2
						'wppa_filmstrip' 					=> 'yes',		// 3
						'wppa_film_show_glue' 				=> 'yes',		// 4
						'wppa_show_full_name' 				=> 'yes',		// 5
						'wppa_show_full_owner'				=> 'no', 		// 5.1
						'wppa_show_full_desc' 				=> 'yes',		// 6
						'wppa_hide_when_empty'				=> 'no',		// 6.1
						'wppa_rating_on' 					=> 'yes',		// 7
						'wppa_dislike_mail_every'			=> '5', 		// 7.1
						'wppa_dislike_set_pending'			=> '0',
						'wppa_dislike_delete'				=> '0',
						'wppa_dislike_show_count'			=> 'yes',		// 7.2
						'wppa_rating_display_type'			=> 'graphic',	// 8
						'wppa_show_avg_rating'				=> 'yes',		// 9
						'wppa_show_avg_mine_2' 				=> 'no',
						'wppa_show_comments' 				=> 'yes',		// 10
						'wppa_comment_gravatar'				=> 'monsterid',		// 11
						'wppa_comment_gravatar_url'			=> 'http://',	// 12
						'wppa_show_bbb'						=> 'no',		// 13
						'wppa_show_ubb' 					=> 'no',
						'wppa_show_start_stop_icons' 		=> 'no',
						'wppa_custom_on' 					=> 'no',		// 14
						'wppa_custom_content' 				=> '<div style="color:red; font-size:24px; font-weight:bold; text-align:center;">Hello world!</div><div style="text-align:center">You can change this text in Basic settings -> Slideshow -> I -> Item 26</div>',	// 15
						'wppa_show_slideshownumbar'  		=> 'no',		// 16
						'wppa_show_iptc'					=> 'no',		// 17
						'wppa_show_iptc_open'				=> 'no',
						'wppa_show_exif'					=> 'no',		// 18
						'wppa_show_exif_open'				=> 'no',
						'wppa_share_on'						=> 'no',
						'wppa_share_hide_when_running'		=> 'yes',
						'wppa_sm_void_pages' 				=> '0',
						'wppa_share_on_widget'				=> 'no',
						'wppa_share_on_thumbs'				=> 'no',
						'wppa_share_on_lightbox' 			=> 'no',
						'wppa_share_on_mphoto' 				=> 'no',
						'wppa_share_qr'						=> 'no',
						'wppa_share_facebook'				=> 'yes',
						'wppa_share_twitter'				=> 'yes',
						'wppa_twitter_black' 				=> 'no',
						'wppa_twitter_account' 				=> '',
						'wppa_share_pinterest'				=> 'yes',
						'wppa_pinterest_black' 				=> 'no',
						'wppa_share_linkedin'				=> 'yes',
						'wppa_linkedin_black' 				=> 'no',
						'wppa_facebook_comments'			=> 'yes',
						'wppa_facebook_like'				=> 'yes',
						'wppa_fb_display' 					=> 'standard',
						'wppa_facebook_admin_id'			=> '',
						'wppa_facebook_app_id'				=> '',
						'wppa_load_facebook_sdk'			=> 'yes',
						'wppa_share_single_image'			=> 'yes',
						'wppa_easing_slide' 				=> 'swing',
						'wppa_easing_lightbox' 				=> 'swing',
						'wppa_easing_popup' 				=> 'swing',

						// Filmstrip
						'wppa_film_type' 					=> 'normal', // 'normal' or 'canvas'
						'wppa_film_aspect' 					=> '1.5',
						'wppa_film_arrows' 					=> 'yes',

						// C Thumbnails
						'wppa_thumb_text_name' 				=> 'yes',	// 1
						'wppa_thumb_text_owner'				=> 'no',	// 1.1
						'wppa_thumb_text_desc' 				=> 'yes',	// 2
						'wppa_thumb_text_rating' 			=> 'yes',	// 3
						'wppa_thumb_text_comcount' 			=> 'no',
						'wppa_thumb_text_comcount_note_role' => '',
						'wppa_thumb_text_viewcount'			=> 'no',
						'wppa_thumb_text_virt_album' 		=> 'yes',
						'wppa_thumb_video' 					=> 'no',
						'wppa_thumb_audio' 					=> 'yes',
						'wppa_popup_text_name' 				=> 'yes',	// 4
						'wppa_popup_text_owner' 			=> 'no',
						'wppa_popup_text_desc' 				=> 'yes',	// 5
						'wppa_popup_text_desc_strip'		=> 'no',	// 5.1
						'wppa_popup_text_rating' 			=> 'yes',	// 6
						'wppa_popup_text_ncomments'			=> 'yes', 	//
						'wppa_show_rating_count'			=> 'no',	// 7
						'wppa_albdesc_on_thumbarea'			=> 'none',
						'wppa_albname_on_thumbarea'			=> 'none',
						'wppa_show_empty_thumblist' 		=> 'no',

						'wppa_edit_thumb' 					=> 'yes',	// II-D17
						'wppa_upload_link_thumbs' 			=> 'bottom',

						'wppa_grid_video' 					=> 'no',

						// D Covers
						'wppa_show_cover_text' 				=> 'yes',	// 1
						'wppa_enable_slideshow' 			=> 'yes',	// 2
						'wppa_show_slideshowbrowselink' 	=> 'yes',	// 3
						'wppa_show_viewlink'				=> 'yes',	// 4
						'wppa_show_treecount'				=> '-none-',
						'wppa_show_cats' 					=> 'no',
						'wppa_skip_empty_albums'			=> 'yes',
						'wppa_count_on_title' 				=> '-none-',
						'wppa_viewcount_on_cover' 			=> '-none-',
						'wppa_albumid_on_cover' 			=> '-none-',


						// E Widgets
						'wppa_show_bbb_widget'				=> 'no',	// 1
						'wppa_show_ubb_widget'				=> 'no',	// 1
						'wppa_ubb_color' 					=> '',
						'wppa_show_albwidget_tooltip'		=> 'yes',

						// F Overlay
						'wppa_ovl_theme'					=> 'black',
						'wppa_ovl_bgcolor'					=> 'black',
						'wppa_ovl_name'				=> 'yes',
						'wppa_ovl_desc'				=> 'yes',
						'wppa_ovl_show_counter'				=> 'yes',
						'wppa_ovl_add_owner' 				=> 'no',
						'wppa_ovl_show_startstop' 			=> 'yes',
						'wppa_show_zoomin'					=> 'yes',
						'wppa_ovl_rating' 					=> 'no',
						'wppa_owner_on_new_line' 			=> 'no',

						// H Frontend upload
						'wppa_user_upload_on'			=> 'no',
						'wppa_email_on'					=> 'yes',
						'wppa_user_upload_video_on' 	=> 'no',
						'wppa_user_upload_audio_on' 	=> 'no',
						'wppa_user_opload_roles' 		=> '',
						'wppa_user_create_roles' 		=> '',
						'wppa_copyright_on'				=> 'yes',		// 19
						'wppa_copyright_notice'			=> '<span style="color:red">' . __('Warning: Do not upload copyrighted material!', 'wp-photo-album-plus') . '</span>',	// 20
						'wppa_watermark_user'			=> 'no',
						'wppa_name_user' 				=> 'yes',
						'wppa_name_user_mandatory' 		=> 'no',
						'wppa_apply_newphoto_desc_user'	=> 'no',
						'wppa_desc_user' 				=> 'yes',
						'wppa_desc_user_mandatory' 		=> 'no',
						'wppa_fe_custom_fields' 		=> 'no',
						'wppa_fe_upload_tags' 			=> 'no',
						'wppa_up_tagselbox_on_1' 		=> 'yes',		// 18
						'wppa_up_tagselbox_multi_1' 	=> 'yes',
						'wppa_up_tagselbox_title_1' 	=> __( 'Select tags:' , 'wp-photo-album-plus' ),
						'wppa_up_tagselbox_content_1' 	=> '',
						'wppa_up_tagselbox_on_2' 		=> 'no',
						'wppa_up_tagselbox_multi_2' 	=> 'yes',
						'wppa_up_tagselbox_title_2' 	=> __( 'Select tags:' , 'wp-photo-album-plus' ),
						'wppa_up_tagselbox_content_2' 	=> '',
						'wppa_up_tagselbox_on_3' 		=> 'no',
						'wppa_up_tagselbox_multi_3' 	=> 'yes',
						'wppa_up_tagselbox_title_3' 	=> __( 'Select tags:' , 'wp-photo-album-plus' ),
						'wppa_up_tagselbox_content_3' 	=> '',
						'wppa_up_tag_input_on' 			=> 'yes',
						'wppa_up_tag_input_title' 		=> __( 'Enter new tags:' , 'wp-photo-album-plus' ),
						'wppa_up_tagbox_new' 			=> '',
						'wppa_up_tag_preview' 			=> 'yes',
						'wppa_camera_connect' 			=> 'yes',
						'wppa_blog_it' 					=> '-none-',
						'wppa_blog_it_moderate' 		=> 'yes',
						'wppa_blog_it_shortcode' 		=> '[wppa type="mphoto" photo="#id"]',

						// J Custom datafields
						'wppa_album_custom_fields' 		=> 'no',
						'wppa_album_custom_caption_0' 	=> '',
						'wppa_album_custom_visible_0' 	=> 'no',
						'wppa_album_custom_edit_0' 		=> 'no',
						'wppa_album_custom_caption_1' 	=> '',
						'wppa_album_custom_visible_1' 	=> 'no',
						'wppa_album_custom_edit_1' 		=> 'no',
						'wppa_album_custom_caption_2' 	=> '',
						'wppa_album_custom_visible_2' 	=> 'no',
						'wppa_album_custom_edit_2' 		=> 'no',
						'wppa_album_custom_caption_3' 	=> '',
						'wppa_album_custom_visible_3' 	=> 'no',
						'wppa_album_custom_edit_3' 		=> 'no',
						'wppa_album_custom_caption_4' 	=> '',
						'wppa_album_custom_visible_4' 	=> 'no',
						'wppa_album_custom_edit_4' 		=> 'no',
						'wppa_album_custom_caption_5' 	=> '',
						'wppa_album_custom_visible_5' 	=> 'no',
						'wppa_album_custom_edit_5' 		=> 'no',
						'wppa_album_custom_caption_6' 	=> '',
						'wppa_album_custom_visible_6' 	=> 'no',
						'wppa_album_custom_edit_6' 		=> 'no',
						'wppa_album_custom_caption_7' 	=> '',
						'wppa_album_custom_visible_7' 	=> 'no',
						'wppa_album_custom_edit_7' 		=> 'no',
						'wppa_album_custom_caption_8' 	=> '',
						'wppa_album_custom_visible_8' 	=> 'no',
						'wppa_album_custom_edit_8' 		=> 'no',
						'wppa_album_custom_caption_9' 	=> '',
						'wppa_album_custom_visible_9' 	=> 'no',
						'wppa_album_custom_edit_9' 		=> 'no',

						'wppa_custom_fields' 			=> 'no',
						'wppa_custom_caption_0' 		=> '',
						'wppa_custom_visible_0' 		=> 'no',
						'wppa_custom_edit_0' 			=> 'no',
						'wppa_custom_default_0' 		=> '',
						'wppa_custom_caption_1' 		=> '',
						'wppa_custom_visible_1' 		=> 'no',
						'wppa_custom_edit_1' 			=> 'no',
						'wppa_custom_default_1' 		=> '',
						'wppa_custom_caption_2' 		=> '',
						'wppa_custom_visible_2' 		=> 'no',
						'wppa_custom_edit_2' 			=> 'no',
						'wppa_custom_default_2' 		=> '',
						'wppa_custom_caption_3' 		=> '',
						'wppa_custom_visible_3' 		=> 'no',
						'wppa_custom_edit_3' 			=> 'no',
						'wppa_custom_default_3' 		=> '',
						'wppa_custom_caption_4' 		=> '',
						'wppa_custom_visible_4' 		=> 'no',
						'wppa_custom_edit_4' 			=> 'no',
						'wppa_custom_default_4' 		=> '',
						'wppa_custom_caption_5' 		=> '',
						'wppa_custom_visible_5' 		=> 'no',
						'wppa_custom_edit_5' 			=> 'no',
						'wppa_custom_default_5' 		=> '',
						'wppa_custom_caption_6' 		=> '',
						'wppa_custom_visible_6' 		=> 'no',
						'wppa_custom_edit_6' 			=> 'no',
						'wppa_custom_default_6' 		=> '',
						'wppa_custom_caption_7' 		=> '',
						'wppa_custom_visible_7' 		=> 'no',
						'wppa_custom_edit_7' 			=> 'no',
						'wppa_custom_default_7' 		=> '',
						'wppa_custom_caption_8' 		=> '',
						'wppa_custom_visible_8' 		=> 'no',
						'wppa_custom_edit_8' 			=> 'no',
						'wppa_custom_default_8' 		=> '',
						'wppa_custom_caption_9' 		=> '',
						'wppa_custom_visible_9' 		=> 'no',
						'wppa_custom_edit_9' 			=> 'no',
						'wppa_custom_default_9' 		=> '',


						'wppa_close_text' 				=> 'Close',	// frontend upload/edit etc

						'wppa_icon_corner_style' 		=> 'medium',
						'wppa_spinner_shape' 			=> 'default',
						'wppa_show_dashboard_widgets' 	=> 'all',
						'wppa_audio_icon' 				=> 'Music-Note-1.svg',
						'wppa_video_icon' 				=> 'Film-Clapper.svg',
						'wppa_document_icon' 			=> 'Document-File.svg',
						'wppa_icon_size_multimedia' 	=> 'M',
						'wppa_multimedia_icon_upload' 	=> '',

						// Table III: Backgrounds
						'wppa_bgcolor' 					=> '#eeeeee',
						'wppa_bcolor' 					=> '#cccccc',

						'wppa_bgcolor_img'				=> '#eeeeee',
						'wppa_bcolor_img'				=> '',
						'wppa_bgcolor_fullimg' 			=> '#cccccc',
						'wppa_bcolor_fullimg' 			=> '#777777',
						'wppa_bgcolor_cus'				=> '#dddddd',
						'wppa_bcolor_cus'				=> '#bbbbbb',
						'wppa_bgcolor_numbar'			=> '#cccccc',
						'wppa_bcolor_numbar'			=> '#cccccc',
						'wppa_bgcolor_numbar_active'	=> '#333333',
						'wppa_bcolor_numbar_active'	 	=> '#333333',

						'wppa_bgcolor_thumbnail' 		=> '#000000',

						'wppa_bgcolor_modal' 			=> '#ffffff',
						'wppa_bcolor_modal' 			=> '#ffffff',
						'wppa_svg_color' 				=> '#666666',
						'wppa_svg_bg_color' 			=> 'transparent',
						'wppa_ovl_svg_color' 			=> '#999999',
						'wppa_ovl_svg_bg_color' 		=> 'transparent',
						'wppa_fs_svg_color' 			=> '#999999',
						'wppa_fs_svg_bg_color' 			=> 'transparent',

						'wppa_mobile_ignore_sa' 		=> 'no',

						// Table IV: Behaviour
						// A System
						'wppa_ajax_scroll' 				=> 'yes',
						'wppa_non_ajax_scroll' 			=> 'no',
						'wppa_ajax_render_modal' 		=> 'no',
						'wppa_use_short_qargs' 			=> 'yes',
						'wppa_use_pretty_links'			=> 'classic',
						'wppa_update_addressline'		=> 'yes',
						'wppa_ajax_method' 				=> 'normal',
						'wppa_ajax_home' 				=> 'no',
						'wppa_track_viewcounts'			=> 'yes',
						'wppa_track_clickcounts' 		=> 'no',
						'wppa_auto_page'				=> 'no',
						'wppa_auto_page_type'			=> 'photo',
						'wppa_auto_page_links'			=> 'bottom',
						'wppa_use_custom_style_file' 	=> 'no',
						'wppa_enable_pdf' 				=> 'yes', 	// IV-A30
						'wppa_use_custom_theme_file' 	=> 'no',
						'wppa_cre_uploads_htaccess' 	=> 'remove',
						'wppa_relative_urls' 			=> 'no',
						'wppa_lazy' 					=> 'all',

						'wppa_thumbs_first' 			=> 'no',
						'wppa_login_links' 				=> 'yes',
						'wppa_enable_video' 			=> 'yes',
						'wppa_enable_audio' 			=> 'yes',
						'wppa_enable_stereo' 			=> 'no',
						'wppa_enable_panorama' 			=> 'no',

						'wppa_capitalize_tags' 			=> 'yes',
						'wppa_enable_admins_choice' 	=> 'no',
						'wppa_admins_choice' 			=> 'none',
						'wppa_admins_choice_meonly' 	=> 'no',
						'wppa_admins_choice_action' 	=> 'zip',
						'wppa_choice_is_tag' 			=> 'no',
						'wppa_owner_to_name' 			=> 'no',

						'wppa_nicescroll_window' 		=> 'no',
						'wppa_nicescroll_opts' 			=> $nso,
						'wppa_response_speed' 			=> '0',
						'wppa_request_info' 			=> 'no',
						'wppa_request_info_text' 		=> __('Please specify your question', 'wp-photo-album-plus' ),
						'wppa_album_use_gallery' 		=> 'no',
						'wppa_zoom_on' 					=> 'no',
						'wppa_fs_policy' 				=> 'lightbox',
						'wppa_cache_overrule' 			=> 'default',
						'wppa_cache_maxfiles' 			=> '50',
						'wppa_qr_max' 					=> '50',
						'wppa_delay_overrule' 			=> 'default',

						// B Full size and Slideshow
						'wppa_fullvalign' 				=> 'center',
						'wppa_fullvalign_slideonly' 	=> 'fit',
						'wppa_fullhalign' 				=> 'center',
						'wppa_start_slide' 				=> 'run',
						'wppa_start_slideonly'			=> 'yes',
						'wppa_start_slide_video' 		=> 'no',
						'wppa_start_slide_audio' 		=> 'no',
						'wppa_animation_type'			=> 'fadeover',
						'wppa_slideshow_timeout'		=> '2500',
						'wppa_animation_speed' 			=> '800',
						'wppa_slide_pause'				=> 'no',
						'wppa_slide_wrap'				=> 'yes',
						'wppa_fulldesc_align'			=> 'center',
						'wppa_clean_pbr'				=> 'yes',
						'wppa_wpautop_on_desc'			=> 'nil',
						'wppa_auto_open_comments'		=> 'yes',
						'wppa_film_hover_goto'			=> 'no',
						'wppa_slide_swipe'				=> 'no',
						'wppa_filmonly_continuous' 		=> 'no',
						'wppa_filmonly_random' 			=> 'no',
						'wppa_no_animate_on_mobile'		=> 'no',

						// C Thumbnail
						'wppa_list_photos_by' 			=> '0',
						'wppa_thumbtype' 				=> 'default',
						'wppa_thumbphoto_left' 			=> 'no',
						'wppa_valign' 					=> 'center',
						'wppa_use_thumb_opacity' 		=> 'yes',
						'wppa_thumb_opacity' 			=> '95',
						'wppa_thumb_popup' 				=> 'all',
						'wppa_align_thumbtext' 			=> 'no',
						'wppa_wpautop_on_thumb_desc' 	=> 'nil',

						// D Albums and covers
						'wppa_list_albums_by' 			=> '0',
						'wppa_main_photo' 				=> '-9',
						'wppa_main_photo_random_once' 	=> 'no',
						'wppa_main_photo_reset' 		=> 'no',
						'wppa_coverphoto_pos'			=> 'right',
						'wppa_use_cover_opacity' 		=> 'yes',
						'wppa_cover_opacity' 			=> '85',
						'wppa_cover_type'				=> 'default',
						'wppa_imgfact_count'			=> '10',
						'wppa_cats_inherit' 			=> 'no',
						'wppa_wpautop_on_album_desc' 	=> 'nil',
						'wppa_cover_use_thumb' 			=> 'no',

						// E Rating
						'wppa_rating_change' 			=> 'yes',
						'wppa_rating_multi' 			=> 'no',
						'wppa_rating_dayly' 			=> '0',
						'wppa_allow_owner_votes' 		=> 'yes',
						'wppa_vote_needs_comment' 		=> 'no',
						'wppa_dislike_value'			=> '-5',
						'wppa_next_on_callback'			=> 'no',
						'wppa_star_opacity'				=> '20',
						'wppa_vote_button_text'			=> __('Vote for me!', 'wp-photo-album-plus' ),
						'wppa_voted_button_text'		=> __('Voted for me', 'wp-photo-album-plus' ),
						'wppa_vote_thumb'				=> 'no',
						'wppa_medal_bronze_when'		=> '5',
						'wppa_medal_silver_when'		=> '10',
						'wppa_medal_gold_when'			=> '15',
						'wppa_medal_color' 				=> '2',
						'wppa_medal_position' 			=> 'botright',
						'wppa_topten_sortby' 			=> 'mean_rating',
						'wppa_contest_sortby' 			=> 'average',
						'wppa_contest_number' 			=> 'none',
						'wppa_contest_max' 				=> '10',
						'wppa_contest_comment_policy' 	=> 'all',

						// F Comments
						'wppa_comment_view_login' 		=> 'no',
						'wppa_comments_desc'			=> 'no',
						'wppa_moderate_comment'			=> '-none-',
						'wppa_comment_email_required'	=> 'required',
						'wppa_commentprevious' 		=> 'no',
						'wppa_commentapproved' 		=> 'no',
						'wppa_subscribenotify' 		=> 'no',
						'wppa_email_from_site' 		=> str_replace('&#039;', '', get_bloginfo('name') ),
						'wppa_email_from_email' 	=> 'noreply@' . $site,
						'wppa_commentnotify_added'		=> 'yes',
						'wppa_comten_alt_display'		=> 'no',
						'wppa_comten_alt_thumbsize'		=> '75',
						'wppa_comment_smiley_picker'	=> 'no',
						'wppa_comment_clickable' 		=> 'no',
						'wppa_comment_need_vote' 		=> 'no',
						'wppa_user_comment_roles' 		=> '',

						// G Overlay
						'wppa_ovl_opacity'				=> '80',
						'wppa_ovl_onclick'				=> 'none',
						'wppa_ovl_browse_on_click' 		=> 'no',
						'wppa_ovl_anim'					=> '300',
						'wppa_ovl_slide'				=> '5000',
						'wppa_lightbox_global'			=> 'no',
						'wppa_lightbox_global_set'		=> 'no',
						'wppa_lb_hres' 					=> 'yes',
						'wppa_ovl_video_start' 			=> 'yes',
						'wppa_ovl_audio_start' 			=> 'yes',

						'wppa_ovl_big_browse' 			=> 'no',
						'wppa_ovl_small_browse' 		=> 'no',

						// H Panorama
						'wppa_panorama_control' 			=> 'all',
						'wppa_panorama_manual' 				=> 'all',
						'wppa_panorama_autorun' 			=> 'none',
						'wppa_panorama_autorun_speed' 		=> '3',
						'wppa_panorama_wheel_sensitivity' 	=> '3',
						'wppa_panorama_fov' 				=> '75',
						'wppa_panorama_max' 				=> '6000',

						// Table V: Fonts
						'wppa_fontfamily_title' 	=> '',
						'wppa_fontsize_title' 		=> '',
						'wppa_fontcolor_title' 		=> '',
						'wppa_fontweight_title'		=> 'bold',
						'wppa_fontfamily_fulldesc' 	=> '',
						'wppa_fontsize_fulldesc' 	=> '',
						'wppa_fontcolor_fulldesc' 	=> '',
						'wppa_fontweight_fulldesc'	=> 'normal',
						'wppa_fontfamily_fulltitle' => '',
						'wppa_fontsize_fulltitle' 	=> '',
						'wppa_fontcolor_fulltitle' 	=> '',
						'wppa_fontweight_fulltitle'	=> 'normal',
						'wppa_fontfamily_nav' 		=> '',
						'wppa_fontsize_nav' 		=> '',
						'wppa_fontcolor_nav' 		=> '',
						'wppa_fontweight_nav'		=> 'normal',
						'wppa_fontfamily_thumb' 	=> '',
						'wppa_fontsize_thumb' 		=> '',
						'wppa_fontcolor_thumb' 		=> '',
						'wppa_fontweight_thumb'		=> 'normal',
						'wppa_fontfamily_box' 		=> '',
						'wppa_fontsize_box' 		=> '',
						'wppa_fontcolor_box' 		=> '',
						'wppa_fontweight_box'		=> 'normal',
						'wppa_fontfamily_numbar' 	=> '',
						'wppa_fontsize_numbar' 		=> '',
						'wppa_fontcolor_numbar' 	=> '#777777',
						'wppa_fontweight_numbar'	=> 'normal',
						'wppa_fontfamily_numbar_active' 	=> '',
						'wppa_fontsize_numbar_active' 		=> '',
						'wppa_fontcolor_numbar_active' 	=> '#777777',
						'wppa_fontweight_numbar_active'	=> 'bold',
						'wppa_fontfamily_lightbox'	=> '',
						'wppa_fontsize_lightbox'	=> '10',
						'wppa_fontcolor_lightbox'	=> '',
						'wppa_fontweight_lightbox'	=> 'bold',
						'wppa_fontsize_widget_thumb'	=> '9',
						'wppa_font_calendar_by' 		=> 'small',
						'wppa_font_calendar_by_bold' 	=> 'no',

						// Table VI: Links
						'wppa_grid_linktype' 				=> 'photo',
						'wppa_grid_linkpage' 				=> '0',
						'wppa_grid_blank'					=> 'no',
						'wppa_grid_overrule'				=> 'no',

						'wppa_sphoto_linktype' 				=> 'photo',
						'wppa_sphoto_linkpage' 				=> '0',
						'wppa_sphoto_blank'					=> 'no',
						'wppa_sphoto_overrule'				=> 'no',

						'wppa_mphoto_linktype' 				=> 'photo',
						'wppa_mphoto_linkpage' 				=> '0',
						'wppa_mphoto_blank'					=> 'no',
						'wppa_mphoto_overrule'				=> 'no',

						'wppa_xphoto_linktype' 				=> 'photo',
						'wppa_xphoto_linkpage' 				=> '0',
						'wppa_xphoto_blank'					=> 'no',
						'wppa_xphoto_overrule'				=> 'no',

						'wppa_thumb_linktype' 				=> 'photo',
						'wppa_thumb_linkpage' 				=> '0',
						'wppa_thumb_blank'					=> 'no',
						'wppa_thumb_overrule'				=> 'no',

						'wppa_topten_widget_linktype' 		=> 'photo',
						'wppa_topten_widget_linkpage' 		=> '0',
						'wppa_topten_blank'					=> 'no',
						'wppa_topten_overrule'				=> 'no',

						'wppa_topten_widget_album_linkpage' => '0',

						'wppa_featen_widget_linktype' 		=> 'photo',
						'wppa_featen_widget_linkpage' 		=> '0',
						'wppa_featen_blank'					=> 'no',
						'wppa_featen_overrule'				=> 'no',

						'wppa_slideonly_widget_linktype' 	=> 'widget',
						'wppa_slideonly_widget_linkpage' 	=> '0',
						'wppa_sswidget_blank'				=> 'no',
						'wppa_sswidget_overrule'			=> 'no',

						'wppa_potd_linktype' 				=> 'single',
						'wppa_potd_linkpage' 				=> '0',
						'wppa_potd_blank'					=> 'no',
						'wppa_potdwidget_overrule'			=> 'no',

						'wppa_coverimg_linktype' 			=> 'same',
						'wppa_coverimg_linkpage' 			=> '0',
						'wppa_coverimg_blank'				=> 'no',
						'wppa_coverimg_overrule'			=> 'no',

						'wppa_comment_widget_linktype'		=> 'photo',
						'wppa_comment_widget_linkpage'		=> '0',
						'wppa_comment_blank'				=> 'no',
						'wppa_comment_overrule'				=> 'no',

						'wppa_slideshow_linktype'			=> 'none',
						'wppa_slideshow_linkpage'			=> '0',
						'wppa_slideshow_blank'				=> 'no',
						'wppa_slideshow_overrule'			=> 'no',

						'wppa_thumbnail_widget_linktype'	=> 'photo',
						'wppa_thumbnail_widget_linkpage'	=> '0',
						'wppa_thumbnail_widget_overrule'	=> 'no',
						'wppa_thumbnail_widget_blank'		=> 'no',

						'wppa_film_linktype'				=> 'slideshow',
						'wppa_film_blank' 					=> 'no',
						'wppa_film_overrule' 				=> 'no',

						'wppa_lasten_widget_linktype' 		=> 'photo',
						'wppa_lasten_widget_linkpage' 		=> '0',
						'wppa_lasten_blank'					=> 'no',
						'wppa_lasten_overrule'				=> 'no',

						'wppa_art_monkey_on' 				=> 'no',
						'wppa_art_monkey_types' 			=> 'photo',
						'wppa_art_monkey_source'			=> 'no',
						'wppa_art_monkey_display'			=> 'button',
						'wppa_art_monkey_single' 			=> 'no',
						'wppa_art_monkey_mxsingle' 			=> 'no',
						'wppa_art_monkey_slide' 			=> 'none',
						'wppa_art_monkey_thumb' 			=> 'no',
						'wppa_art_monkey_lightbox' 			=> 'no',

						'wppa_allow_download_album' 		=> 'no',
						'wppa_download_album_source' 		=> 'yes',

						'wppa_album_widget_linktype'		=> 'content',
						'wppa_album_widget_linkpage'		=> '0',
						'wppa_album_widget_blank'			=> 'no',
						'wppa_album_widget_overrule' 		=> 'no',

						'wppa_tagcloud_linktype'			=> 'album',
						'wppa_tagcloud_linkpage'			=> '0',
						'wppa_tagcloud_blank'				=> 'no',

						'wppa_multitag_linktype'			=> 'album',
						'wppa_multitag_linkpage'			=> '0',
						'wppa_multitag_blank'				=> 'no',

						'wppa_super_view_linkpage'			=> '0',

						'wppa_upldr_widget_linkpage' 		=> '0',

						'wppa_bestof_widget_linkpage'		=> '0',

						'wppa_supersearch_linkpage' 		=> '0',

						'wppa_show_empty_search' 			=> 'yes',

						'wppa_album_navigator_widget_linktype' 	=> 'thumbs',
						'wppa_album_navigator_widget_linkpage' 	=> '0',
						'wppa_album_navigator_widget_blank'		=> 'no',
						'wppa_album_navigator_widget_overrule' 	=> 'no',

						'wppa_widget_sm_linktype' 			=> 'landing',
						'wppa_widget_sm_linkpage' 			=> '0',
						'wppa_widget_sm_linkpage_oc' 		=> '1',
						'wppa_tagcloud_linkpage_oc' 		=> '1',
						'wppa_multitag_linkpage_oc' 		=> '1',

						'wppa_cover_sublinks' 				=> 'none',
						'wppa_cover_sublinks_display' 		=> 'none',
						'wppa_real_calendar_linktype' 		=> 'lightbox',
						'wppa_fe_albid_edit' 				=> 'no',

						// Table VII: Security
						// B
						'wppa_user_album_edit_on' 		=> 'no',
						'wppa_upload_moderate'			=> 'no',
						'wppa_fe_upload_private' 		=> 'no',
						'wppa_photoapproved' 			=> 'no',
						'wppa_upload_edit'				=> '-none-',
						'wppa_upload_edit_users' 		=> 'admin',
						'wppa_upload_edit_period' 		=> '0',
						'wppa_upload_edit_theme_css' 	=> 'no',
						'wppa_fe_edit_name' 			=> 'yes',
						'wppa_fe_edit_desc' 			=> 'yes',
						'wppa_fe_edit_tags' 			=> 'yes',
						'wppa_fe_edit_button' 			=> __( 'Edit', 'wp-photo-album-plus' ),
						'wppa_fe_edit_caption' 			=> __( 'Edit photo information', 'wp-photo-album-plus' ),
						'wppa_upload_delete' 			=> 'no',
						'wppa_upload_delete_period' 	=> '0',
						'wppa_owner_moderate_comment' 	=> 'no',
						'wppa_upload_one_only'			=> 'no',
						'wppa_memcheck'					=> 'yes',
						'wppa_memcheck_copy' 			=> 'yes',
						'wppa_comment_captcha'			=> 'none',
						'wppa_spam_maxage'				=> 'none',
						'wppa_user_create_on'			=> 'no',
						'wppa_user_create_max_level' 	=> '99',
						'wppa_user_create_captcha' 		=> 'yes', 	// VII-B3
						'wppa_user_destroy_on' 			=> 'no',
						'wppa_upload_frontend_minsize' 	=> '0',
						'wppa_upload_frontend_maxsize' 	=> '0',
						'wppa_void_dups' 				=> 'no',
						'wppa_fe_alert' 				=> 'all',
						'wppa_fe_upload_max_albums' 	=> '500', 	// VII-B13

						'wppa_editor_upload_limit_count'		=> '0',
						'wppa_editor_upload_limit_time'			=> '0',
						'wppa_author_upload_limit_count'		=> '0',
						'wppa_author_upload_limit_time'			=> '0',
						'wppa_contributor_upload_limit_count'	=> '0',
						'wppa_contributor_upload_limit_time'	=> '0',
						'wppa_subscriber_upload_limit_count'	=> '0',
						'wppa_subscriber_upload_limit_time'		=> '0',

						'wppa_role_limit_per_album' 			=> 'no',

						'wppa_clear_vanished_user' 	=> 'no',

						'wppa_blacklist_user' 		=> '',
						'wppa_un_blacklist_user' 	=> '',
						'wppa_photo_owner_change' 	=> 'no',
						'wppa_superuser_user' 		=> '',
						'wppa_un_superuser_user' 	=> '',
						'wppa_no_rightclick' 		=> 'no',

						// Table VIII: Actions
						// A Harmless
						'wppa_maint_ignore_cron' 				=> 'no',	// 0.2
						'wppa_setup' 							=> '', 		// 1
						'wppa_backup_filename' 		=> '',
						'wppa_backup' 				=> '',
						'wppa_load_skin' 			=> '',
						'wppa_skinfile' 			=> '',
						'wppa_regen_thumbs' 				=> '',
						'wppa_regen_thumbs_skip_one' 	=> '',
						'wppa_rerate'				=> '',
						'wppa_cleanup'				=> '',
						'wppa_recup'				=> '',
						'wppa_format_exif' 			=> '',
						'wppa_file_system'			=> 'flat',
						'wppa_remake' 				=> '',
						'wppa_remake_orientation_only' 	=> 'no',
						'wppa_remake_missing_only' 	=> 'no',
						'wppa_remake_skip_one'		=> '',
						'wppa_errorlog_purge' 		=> '',
						'wppa_comp_sizes' 			=> '',
						'wppa_crypt_photos' 		=> '',
						'wppa_crypt_photos_every' 	=> '0',
						'wppa_crypt_albums' 		=> '',
						'wppa_crypt_albums_every' 	=> '0',
						'wppa_create_o1_files' 				=> '',
						'wppa_create_o1_files_skip_one' 	=> '',
						'wppa_owner_to_name_proc' 			=> '',

						// B Irreversable
						'wppa_rating_clear' 				=> 'no',
						'wppa_viewcount_clear' 				=> 'no',
						'wppa_iptc_clear'					=> '',
						'wppa_exif_clear'					=> '',
						'wppa_apply_default_photoname_all' 	=> '',
						'wppa_apply_new_photodesc_all'		=> '',
						'wppa_remake_index_albums'			=> '',		// 8.1
						'wppa_remake_index_albums_ad_inf' 	=> 'no',	// 8.1
						'wppa_remake_index_photos'			=> '',		// 8.2
						'wppa_remake_index_photos_ad_inf' 	=> 'no',	// 8.2
						'wppa_cleanup_index' 				=> '',		// 8.3
						'wppa_cleanup_index_ad_inf'			=> 'no', 	// 8.3
						'wppa_list_index'			=> '',
						'wppa_list_index_display_start' 	=> '',
						'wppa_list_comments_by' 	=> 'name',
						'wppa_append_text'			=> '',
						'wppa_append_to_photodesc' 	=> '',
						'wppa_remove_text'			=> '',
						'wppa_remove_from_photodesc'	=> '',
						'wppa_remove_empty_albums'	=> '',
						'wppa_watermark_all' 		=> '',
						'wppa_create_all_autopages' => '',
						'wppa_delete_all_autopages' => '',
						'wppa_readd_file_extensions' => '',
						'wppa_all_ext_to_lower' 	=> '',
						'wppa_renew_slugs_albums' 	=> '',
						'wppa_renew_slugs_photos' 	=> '',
						'wppa_zero_numbers' 		=> '5',
						'wppa_leading_zeros' 		=> '',
						'wppa_add_gpx_tag' 			=> '',
						'wppa_optimize_ewww' 		=> '',
						'wppa_optimize_ewww_skip_one' 	=> '',
						'wppa_tag_to_edit' 			=> '',
						'wppa_new_tag_value' 		=> '',
						'wppa_edit_tag' 			=> '',
						'wppa_sync_cloud' 			=> '',
						'wppa_sanitize_tags' 		=> '',
						'wppa_sanitize_cats' 		=> '',
						'wppa_move_all_photos' 		=> '',
						'wppa_move_all_photos_from' => '',
						'wppa_move_all_photos_to' 	=> '',
						'wppa_photos_hyphens_to_spaces' 	=> '',
						'wppa_png_to_jpg' 			=> '',
						'wppa_fix_mp4_meta' 		=> '',
						'wppa_fix_userids' 			=> '',
						'wppa_fix_custom_tags' 		=> '',
						'wppa_covert_usertags'		=> '',




						'wppa_custom_photo_proc' 					=> '',		// 99
						'wppa_custom_photo_proc_keep_last' 	=> 'no',
						'wppa_test_proc_ad_inf' 			=> 'no',	// 99
						'wppa_custom_album_proc' 						=> '', 		// 99
						'wppa_clear_vanished_user_photos' 	=> '',
						'wppa_clear_vanished_user_albums' 	=> '',


						// Table IX: Miscellaneous
						// A System
						'wppa_allow_foreign_shortcodes_general' => 'no',
						'wppa_allow_foreign_shortcodes' 		=> 'no',		// 7
						'wppa_allow_foreign_shortcodes_thumbs' 	=> 'no',
						'wppa_meta_page'				=> 'yes',		// 9
						'wppa_meta_all'					=> 'yes',		// 10
						'wppa_alt_type'					=> 'fullname',
						'wppa_photo_admin_max_albums' 	=> '500',
						'wppa_jpeg_quality'				=> '95',
						'wppa_geo_edit' 				=> 'no',
						'wppa_adminbarmenu_admin'		=> 'yes',
						'wppa_adminbarmenu_frontend'	=> 'yes',
						'wppa_enable_shortcode_wppa_set' => 'no',

						'wppa_og_tags_on'				=> 'yes',
						'wppa_add_shortcode_to_post'	=> 'no',
						'wppa_shortcode_to_add'			=> '[wppa type="album" album="#related,desc"]',
						'wppa_import_preview' 			=> 'yes',
						'wppa_use_audiostub' 			=> 'yes',
						'wppa_audiostub_upload' 		=> '',
						'wppa_documentstub_upload' 		=> '',
						'wppa_audiostub' 				=> '',
						'wppa_import_root' 				=> ABSPATH . 'wp-content',
						'wppa_allow_import_source' 		=> 'no',
						'wppa_import_all' 				=> 'no',
						'wppa_import_auto' 				=> 'no',
						'wppa_enable_generator' 		=> 'yes',

						// Logging
						'wppa_enable_ext_logging' 		=> 'no',
						'wppa_log_errors' 				=> 'yes',
						'wppa_log_errors_stack' 		=> 'yes',
						'wppa_log_errors_url' 			=> 'yes',
						'wppa_log_warnings' 			=> 'yes',
						'wppa_log_warnings_stack' 		=> 'yes',
						'wppa_log_warnings_url' 		=> 'yes',
						'wppa_log_cron' 				=> 'no',
						'wppa_log_cron_stack' 			=> 'no',
						'wppa_log_cron_url' 			=> 'no',
						'wppa_log_ajax' 				=> 'no',
						'wppa_log_ajax_stack' 			=> 'no',
						'wppa_log_ajax_url' 			=> 'no',
						'wppa_log_comments' 			=> 'no',
						'wppa_log_comments_stack' 		=> 'no',
						'wppa_log_comments_url' 		=> 'no',
						'wppa_log_fso' 					=> 'no',
						'wppa_log_fso_stack' 			=> 'no',
						'wppa_log_fso_url' 				=> 'no',
						'wppa_log_debug' 				=> 'no',
						'wppa_log_debug_stack' 			=> 'no',
						'wppa_log_debug_url' 			=> 'no',
						'wppa_log_database' 			=> 'no',
						'wppa_log_database_stack' 		=> 'no',
						'wppa_log_database_url' 		=> 'no',
						'wppa_log_email' 				=> 'no',
						'wppa_log_email_stack' 			=> 'no',
						'wppa_log_email_url' 			=> 'no',
						'wppa_log_tim'					=> 'no',
						'wppa_log_tim_stack'			=> 'no',
						'wppa_log_tim_url'				=> 'no',
						'wppa_log_idx' 					=> 'no',
						'wppa_log_idx_stack' 			=> 'no',
						'wppa_log_idx_url' 				=> 'no',
						'wppa_log_obs' 					=> 'no',
						'wppa_log_obs_stack' 			=> 'no',
						'wppa_log_obs_url' 				=> 'no',
						'wppa_log_cli' 					=> 'no',
						'wppa_log_cli_stack' 			=> 'no',
						'wppa_log_cli_url' 				=> 'no',
						'wppa_log_upl' 					=> 'no',
						'wppa_log_upl_stack' 			=> 'no',
						'wppa_log_upl_url' 				=> 'no',
						'wppa_log_misc' 				=> 'no',
						'wppa_log_misc_stack' 			=> 'no',
						'wppa_log_misc_url' 			=> 'no',
						'wppa_logfile_on_menu' 			=> 'no',


						'wppa_moderate_bulk' 			=> 'no', 	// B20
						'wppa_use_wp_editor' 			=> 'no',
						'wppa_admin_theme_css' 			=> 'no',
						'wppa_admin_inline_css' 		=> '',
						'wppa_admin_extra_css' 			=> '',
						'wppa_generator_max' 			=> '100', 	// B21
						'wppa_retry_mails' 				=> '2', 	// M100
						'wppa_minimum_tags' 			=> '', 		// A11
						'wppa_predef_tags_only' 		=> 'no',

						'wppa_login_url' 				=> site_url( 'wp-login.php', 'login' ), 	// A
						'wppa_cache_root' 				=> 'cache',
						'wppa_direct_comment' 			=> 'no',
						'wppa_extended_resize_count' 	=> '0',
						'wppa_extended_resize_delay'	=> '1000',
						'wppa_load_nicescroller' 		=> 'no',
						'wppa_nice_mobile'				=> 'no',
						'wppa_csv_sep' 					=> ',',

						// IX D New
						'wppa_max_album_newtime'		=> '0',		// 1
						'wppa_max_photo_newtime'		=> '0',		// 2
						'wppa_max_album_modtime'		=> '0',		// 1
						'wppa_max_photo_modtime'		=> '0',		// 2
						'wppa_show_first' 				=> 'no',
						'wppa_pup_is_aup' 				=> 'no',
						'wppa_new_mod_label_is_text' 	=> 'yes',
						'wppa_lasten_limit_new' 		=> 'no',
						'wppa_lasten_use_modified' 		=> 'no',
						'wppa_new_label_text' 			=> __('NEW', 'wp-photo-album-plus' ),
						'wppa_new_label_color' 			=> 'orange',
						'wppa_mod_label_text' 			=> __('MODIFIED', 'wp-photo-album-plus' ),
						'wppa_mod_label_color' 			=> 'green',
						'wppa_first_label_text' 		=> __('FIRST', 'wp-photo-album-plus' ),
						'wppa_first_label_color' 		=> 'blue',
						'wppa_new_label_url' 			=> wppa_get_imgdir('new.png'),
						'wppa_mod_label_url' 			=> wppa_get_imgdir('mod.png'),
						'wppa_first_label_url' 			=> wppa_get_imgdir('first.png'),
						'wppa_apply_newphoto_desc'		=> 'no',	// IX-D3
						'wppa_newphoto_description'		=> $npd,	// IX-D5
						'wppa_compress_newdesc' 		=> 'no',
						'wppa_newphoto_owner' 			=> '', 		// IX-D5.1
						'wppa_upload_limit_count'		=> '0',		// IX-D6a
						'wppa_upload_limit_time'		=> '0',		// IX-D6b
						'wppa_grant_an_album'			=> 'no',
						'wppa_grant_name'				=> 'display',
						/* translators: Keep $user untranslated. It is a placeholder for user name or id */
						'wppa_grant_desc' 				=> __( 'Default photo album for $user', 'wp-photo-album-plus' ),
						'wppa_grant_parent_sel_method' 	=> 'selectionbox',
						'wppa_grant_parent'				=> '-1',
						'wppa_grant_cats' 				=> '',
						'wppa_grant_tags' 				=> '',
						'wppa_grant_restrict' 			=> 'no',
						'wppa_ipc025_to_tags' 			=> 'no',
						'wppa_default_parent' 			=> '0',
						'wppa_default_parent_always' 	=> 'no',

						'wppa_max_albums'				=> '0',
						'wppa_alt_is_restricted'		=> 'no',
						'wppa_link_is_restricted'		=> 'no',
						'wppa_covertype_is_restricted'	=> 'no',
						'wppa_porder_restricted'		=> 'no',
						'wppa_reup_is_restricted' 		=> 'yes',
						'wppa_newtags_is_restricted' 	=> 'no',
						'wppa_admin_separate' 			=> 'no',
						'wppa_download_album_is_restricted' 	=> 'no',

						'wppa_newphoto_name_method' 	=> 'filename',
						'wppa_default_coverimage_name' 	=> 'Coverphoto',

						'wppa_copy_timestamp'			=> 'no',
						'wppa_copy_owner' 				=> 'no',
						'wppa_copy_custom' 				=> 'no',
						'wppa_frontend_album_public' 	=> 'no',
						'wppa_backend_album_public' 	=> 'no',
						'wppa_default_album_linktype' 	=> 'content',
						'wppa_sanitize_import' 			=> 'yes',
						'wppa_remove_accents' 			=> 'yes',
						'wppa_status_new' 				=> 'publish',
						'wppa_posttitle_owner' 			=> '--- postauthor ---',

						// E Search
						'wppa_search_linkpage' 			=> '0',		// 1
						'wppa_search_oc' 				=> '1',
						'wppa_excl_sep' 				=> 'no',	// 2
						'wppa_search_desc' 				=> 'yes',
						'wppa_search_tags'				=> 'no',
						'wppa_search_cats'				=> 'no',
						'wppa_search_comments' 			=> 'no',
						'wppa_photos_only'				=> 'no',	// 3
						'wppa_max_search_photos'		=> '250',
						'wppa_max_search_albums'		=> '25',
						'wppa_tags_or_only'				=> 'no',
						'wppa_tags_not_on' 				=> 'no',
						'wppa_wild_front'				=> 'no',
						'wppa_search_display_type' 		=> 'content',
						'wppa_ss_name_max' 				=> '0',
						'wppa_ss_text_max' 				=> '0',
						'wppa_search_toptext' 			=> '',
						'wppa_search_in_section' 		=> __( 'Search in current section', 'wp-photo-album-plus' ),
						'wppa_search_in_results' 		=> __( 'Search in current results', 'wp-photo-album-plus' ),
						'wppa_search_min_length' 		=> '2', 	// 18
						'wppa_search_user_void' 		=> 'times,views,wp-content,wp,content,wppa-pl,wppa,pl',
						'wppa_search_numbers_void' 		=> 'no',
						'wppa_index_ignore_slash' 		=> 'no',
						'wppa_search_catbox' 			=> 'no',
						'wppa_search_selboxes' 			=> '0',
						'wppa_search_caption_0' 		=> '',
						'wppa_search_selbox_0' 			=> '',
						'wppa_search_caption_1' 		=> '',
						'wppa_search_selbox_1' 			=> '',
						'wppa_search_caption_2' 		=> '',
						'wppa_search_selbox_2' 			=> '',
						'wppa_extended_duplicate_remove' => 'no',
						'wppa_search_placeholder' 		=> __( 'Search photos &hellip;', 'wp-photo-album-plus' ),
						'wppa_search_form_method' 		=> 'post',
						'wppa_use_wppa_search_form' 	=> 'no',

						// F Watermark
						'wppa_watermark_on'				=> 'no',
						'wppa_watermark_file'			=> 'specimen.png',
						'wppa_watermark_pos'			=> 'cencen',
						'wppa_textual_watermark_type'	=> 'tvstyle',
						'wppa_textual_watermark_text' 	=> "Copyright (c) 2014 w#site \n w#filename (w#owner)",
						'wppa_textual_watermark_font' 	=> 'system',
						'wppa_textual_watermark_size'	=> '10',
						'wppa_watermark_fgcol_text' 	=> '#000000',
						'wppa_watermark_bgcol_text' 	=> '#ffffff',
						'wppa_watermark_upload'			=> '',
						'wppa_watermark_opacity'		=> '20',
						'wppa_watermark_opacity_text' 	=> '80',
						'wppa_watermark_thumbs' 		=> 'no',
						'wppa_watermark_preview'		=> '',
						'wppa_watermark_size' 			=> '0',
						'wppa_watermark_margin' 		=> '0',

						// G Slide order
						'wppa_slide_order'				=> '0,1,2,3,4,5,6,7,8,9,10',
						'wppa_slide_order_split'		=> '0,1,2,3,4,5,6,7,8,9,10,11',
						'wppa_swap_namedesc' 			=> 'no',
						'wppa_split_namedesc'			=> 'no',

						// H Source file management and import/upload
						'wppa_keep_source'				=> 'yes',
						'wppa_source_dir'				=> WPPA_ABSPATH.WPPA_UPLOAD.'/wppa-source',
						'wppa_keep_sync'				=> 'yes',
						'wppa_remake_add'				=> 'yes',
						'wppa_save_iptc'				=> 'yes',
						'wppa_save_exif'				=> 'yes',
						'wppa_save_gpx' 				=> 'yes',
						'wppa_chgsrc_is_restricted'		=> 'no',
						'wppa_ext_status_restricted' 	=> 'no',
						'wppa_desc_is_restricted' 		=> 'no',
						'wppa_newpag_create'			=> 'no',
						'wppa_newpag_content'			=> '[wppa type="cover" album="w#album" align="center"]',
						'wppa_newpag_type'				=> 'page',
						'wppa_newpag_status'			=> 'publish',
						'wppa_pl_dirname' 				=> 'wppa-pl',
						'wppa_import_parent_check' 		=> 'yes',
						'wppa_keep_import_files' 		=> 'no',

						// J Other plugins
						'wppa_cp_points_comment'		=> '0',
						'wppa_cp_points_comment_appr' 	=> '0',
						'wppa_cp_points_rating'			=> '0',
						'wppa_cp_points_upload'			=> '0',
						'wppa_use_scabn'				=> 'no',
						'wppa_use_CMTooltipGlossary' 	=> 'no',
						'wppa_photo_on_bbpress' 		=> 'no',
						'wppa_domain_link_buddypress' 	=> 'no',
						'wppa_uses_pla' 				=> 'no',

						// K External services
						'wppa_cdn_service'						=> '',
						'wppa_cdn_cloud_name'					=> '',
						'wppa_cdn_api_key'						=> '',
						'wppa_cdn_api_secret'					=> '',
						'wppa_cdn_service_update'				=> 'no',
						'wppa_delete_all_from_cloudinary' 		=> '',
						'wppa_delete_derived_from_cloudinary' 	=> '',
						'wppa_max_cloud_life' 					=> '0',
						'wppa_gpx_implementation' 				=> 'wppa-plus-embedded',
						'wppa_map_height' 						=> '300',
						'wppa_map_apikey' 						=> '',
						'wppa_gpx_shortcode'					=> '[map style="width: auto; height:300px; margin:0; " marker="yes" lat="w#lat" lon="w#lon"]',
						'wppa_geo_zoom' 						=> '10',
						'wppa_fotomoto_on'						=> 'no',
						'wppa_fotomoto_hide_when_running'		=> 'no',
						'wppa_fotomoto_min_width' 				=> '400',
						'wppa_image_magick' 					=> '',
						'wppa_image_magick_ratio' 				=> 'NaN',

						// L photo shortcode
						'wppa_photo_shortcode_enabled' 			=> 'yes',
						'wppa_photo_shortcode_type' 			=> 'mphoto',
						'wppa_photo_shortcode_size' 			=> '350',
						'wppa_photo_shortcode_align' 			=> 'center',
						'wppa_photo_shortcode_fe_type' 			=> '-none-',
						'wppa_photo_shortcode_random_albums' 	=> '-2',
						'wppa_photo_shortcode_random_fixed' 	=> 'no',
						'wppa_photo_shortcode_random_fixed_html' => 'no',

						// M Mails
						'wppa_newalbumnotify' 			=> 'no',
						'wppa_feuploadnotify' 			=> 'no',
						'wppa_beuploadnotify' 			=> 'no',
						'wppa_show_email_thumbs' 		=> 'yes',
						'wppa_commentnotify'			=> 'no',
						'wppa_commentnotify_limit' 		=> 'no',
						'wppa_moderatephoto'			=> 'no',
						'wppa_moderatecomment' 			=> 'no',
						'wppa_void_admin_email' 			=> 'no',
						'wppa_mailinglist_policy' 			=> 'opt-in',
						'wppa_mailinglist_callback_url' 	=> '',


						// Photo of the day widget
						'wppa_potd_align' 			=> 'center',
						'wppa_potd_linkurl'			=> '',
						'wppa_potd_linktitle' 		=> '',
						'wppa_potd_subtitle'		=> 'none',
						'wppa_potd_counter' 		=> 'no',
						'wppa_potd_counter_link' 	=> 'thumbs',
						'wppa_potd_album_type' 		=> 'physical',
						'wppa_potd_album'			=> 'all',	// All albums
						'wppa_potd_include_subs' 	=> 'no',
						'wppa_potd_status_filter'	=> 'none',
						'wppa_potd_inverse' 		=> 'no',
						'wppa_potd_method'			=> '4', 	// Change every
						'wppa_potd_period'			=> '24',	// Day
						'wppa_potd_offset' 			=> '0',
						'wppa_potd_photo'			=> '',
						'wppa_potd_preview' 		=> 'no',
						'wppa_potd_log' 			=> 'no',
						'wppa_potd_log_max' 		=> '5',


						'wppa_widget_width'			=> '200',	// Do we use this somewhere still?

						// Topten widget
						'wppa_toptenwidgettitle'	=> __('Top Ten Photos', 'wp-photo-album-plus' ),

						// Thumbnail widget
						'wppa_thumbnailwidgettitle'	=> __('Thumbnail Photos', 'wp-photo-album-plus' ),

						// Search widget
						'wppa_searchwidgettitle'	=> __('Search photos', 'wp-photo-album-plus' ),

						// Comment admin
						'wppa_comadmin_show' 		=> 'all',
						'wppa_comadmin_order' 		=> 'timestamp',

						// QR code settings
						'wppa_qr_size'				=> '200',
						'wppa_qr_color'				=> '#000000',
						'wppa_qr_bgcolor'			=> '#FFFFFF',
						'wppa_qr_cache' 			=> 'no',

						'wppa_dismiss_admin_notice_scripts_are_obsolete' => 'no',

						'wppa_heartbeat' 			=> '0',

						'wppa_use_wp_upload_dir_locations' => 'no',

						'wppa_scrollend_delay' 		=> '200',
						'wppa_scrollend_delay_mob' 	=> '1',
						'wppa_resizeend_delay' 		=> '200',
						'wppa_resizeend_delay_mob' 	=> '1',
						'wppa_pre_cache_albums' 	=> '20',
						'wppa_pre_cache_photos' 	=> '100',
						'wppa_show_scgens' 			=> '',
//						'wppa_force_local_js' 		=> 'no',

						'wppa_opt_menu_search' 			=> 'yes',
						'wppa_opt_menu_doc'				=> 'yes',
						'wppa_opt_menu_edit_tags'		=> 'yes',
						'wppa_opt_menu_edit_sequence' 	=> 'yes',
						'wppa_opt_menu_edit_email' 		=> 'yes',

						);

	if ( $force ) {
		array_walk( $wppa_defaults, 'wppa_set_default' );
	}

	return true;
}
function wppa_set_default( $value, $key ) {
	$void_these = array(
		'wppa_revision',
		'wppa_rating_max',
		'wppa_file_system'
		);

	if ( ! in_array( $key, $void_these ) ) {
		delete_option( $key );
	}
}