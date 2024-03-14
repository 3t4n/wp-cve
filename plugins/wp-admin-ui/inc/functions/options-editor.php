<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Editor
//=================================================================================================

//Full TinyMCE
if (array_key_exists( 'enable_full_tinymce', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_full_tinymce() {
		$wpui_admin_editor_full_tinymce_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_full_tinymce_option ) ) {
			foreach ($wpui_admin_editor_full_tinymce_option as $key => $wpui_admin_editor_full_tinymce_value)
				$options[$key] = $wpui_admin_editor_full_tinymce_value;
			 if (isset($wpui_admin_editor_full_tinymce_option['wpui_admin_editor_full_tinymce'])) { 
			 	return $wpui_admin_editor_full_tinymce_option['wpui_admin_editor_full_tinymce'];
			 }
		}
	};

	if (wpui_admin_editor_full_tinymce() == '1') {
		add_filter( 'tiny_mce_before_init', 'wpui_full_tinymce_editor' );
		function wpui_full_tinymce_editor( $in ) {
			$in['wordpress_adv_hidden'] = FALSE;
			return $in;
		}
	}
}

//Font size
if (array_key_exists( 'font_size_select', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_font_size() {
		$wpui_admin_editor_font_size_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_font_size_option ) ) {
			foreach ($wpui_admin_editor_font_size_option as $key => $wpui_admin_editor_font_size_value)
				$options[$key] = $wpui_admin_editor_font_size_value;
			 if (isset($wpui_admin_editor_font_size_option['wpui_admin_editor_font_size'])) { 
			 	return $wpui_admin_editor_font_size_option['wpui_admin_editor_font_size'];
			 }
		}
	};

	if (wpui_admin_editor_font_size() == '1') {
		function wpui_font_size_select( $buttons ) {
			array_unshift( $buttons, 'fontsizeselect' );
			return $buttons;
		}
		add_filter( 'mce_buttons_2', 'wpui_font_size_select' );
	}
}

//Font Family
if (array_key_exists( 'font_family_select', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_font_family() {
		$wpui_admin_editor_font_family_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_font_family_option ) ) {
			foreach ($wpui_admin_editor_font_family_option as $key => $wpui_admin_editor_font_family_value)
				$options[$key] = $wpui_admin_editor_font_family_value;
			 if (isset($wpui_admin_editor_font_family_option['wpui_admin_editor_font_family'])) { 
			 	return $wpui_admin_editor_font_family_option['wpui_admin_editor_font_family'];
			 }
		}
	};

	if (wpui_admin_editor_font_family() == '1') {
		function wpui_font_family_select( $buttons ) {
			array_unshift( $buttons, 'fontselect' );
			return $buttons;
		}
		add_filter( 'mce_buttons_2', 'wpui_font_family_select' );
	}
}

//Custom Fonts
if (array_key_exists( 'custom_fonts_select', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_custom_fonts() {
		$wpui_admin_editor_custom_fonts_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_custom_fonts_option ) ) {
			foreach ($wpui_admin_editor_custom_fonts_option as $key => $wpui_admin_editor_custom_fonts_value)
				$options[$key] = $wpui_admin_editor_custom_fonts_value;
			 if (isset($wpui_admin_editor_custom_fonts_option['wpui_admin_editor_custom_fonts'])) { 
			 	return $wpui_admin_editor_custom_fonts_option['wpui_admin_editor_custom_fonts'];
			 }
		}
	};

	if (wpui_admin_editor_custom_fonts() == '1') {
		function wpui_custom_fonts( $initArray ) {
		    $initArray['font_formats'] = 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
		        return $initArray;
		}
		add_filter( 'tiny_mce_before_init', 'wpui_custom_fonts' );
	}
}

//Formats
if (array_key_exists( 'formats_select', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_formats_select() {
		$wpui_admin_editor_formats_select_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_formats_select_option ) ) {
			foreach ($wpui_admin_editor_formats_select_option as $key => $wpui_admin_editor_formats_select_value)
				$options[$key] = $wpui_admin_editor_formats_select_value;
			 if (isset($wpui_admin_editor_formats_select_option['wpui_admin_editor_formats_select'])) { 
			 	return $wpui_admin_editor_formats_select_option['wpui_admin_editor_formats_select'];
			 }
		}
	};

	if (wpui_admin_editor_formats_select() == '1') {
		function wpui_formats_select( $buttons ) {
			array_push( $buttons, 'styleselect' );
			return $buttons;
		}
		add_filter( 'mce_buttons', 'wpui_formats_select' );
	}
}

//Shortlink
if (array_key_exists( 'remove_get_shortlink', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_get_shortlink() {
		$wpui_admin_editor_get_shortlink_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_get_shortlink_option ) ) {
			foreach ($wpui_admin_editor_get_shortlink_option as $key => $wpui_admin_editor_get_shortlink_value)
				$options[$key] = $wpui_admin_editor_get_shortlink_value;
			 if (isset($wpui_admin_editor_get_shortlink_option['wpui_admin_editor_get_shortlink'])) { 
			 	return $wpui_admin_editor_get_shortlink_option['wpui_admin_editor_get_shortlink'];
			 }
		}
	};

	if (wpui_admin_editor_get_shortlink() == '1') {
		add_filter( 'pre_get_shortlink', '__return_empty_string' );
	}
}

//New Document button
if (array_key_exists( 'new_doc_btn', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_btn_newdocument() {
		$wpui_admin_editor_btn_newdocument_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_btn_newdocument_option ) ) {
			foreach ($wpui_admin_editor_btn_newdocument_option as $key => $wpui_admin_editor_btn_newdocument_value)
				$options[$key] = $wpui_admin_editor_btn_newdocument_value;
			 if (isset($wpui_admin_editor_btn_newdocument_option['wpui_admin_editor_btn_newdocument'])) { 
			 	return $wpui_admin_editor_btn_newdocument_option['wpui_admin_editor_btn_newdocument'];
			 }
		}
	};
}

//Cut button
if (array_key_exists( 'cut_btn', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_btn_cut() {
		$wpui_admin_editor_btn_cut_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_btn_cut_option ) ) {
			foreach ($wpui_admin_editor_btn_cut_option as $key => $wpui_admin_editor_btn_cut_value)
				$options[$key] = $wpui_admin_editor_btn_cut_value;
			 if (isset($wpui_admin_editor_btn_cut_option['wpui_admin_editor_btn_cut'])) { 
			 	return $wpui_admin_editor_btn_cut_option['wpui_admin_editor_btn_cut'];
			 }
		}
	};
}

//Copy button
if (array_key_exists( 'copy_btn', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_btn_copy() {
		$wpui_admin_editor_btn_copy_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_btn_copy_option ) ) {
			foreach ($wpui_admin_editor_btn_copy_option as $key => $wpui_admin_editor_btn_copy_value)
				$options[$key] = $wpui_admin_editor_btn_copy_value;
			 if (isset($wpui_admin_editor_btn_copy_option['wpui_admin_editor_btn_copy'])) { 
			 	return $wpui_admin_editor_btn_copy_option['wpui_admin_editor_btn_copy'];
			 }
		}
	};
}

//Paste button
if (array_key_exists( 'paste_btn', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_btn_paste() {
		$wpui_admin_editor_btn_paste_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_btn_paste_option ) ) {
			foreach ($wpui_admin_editor_btn_paste_option as $key => $wpui_admin_editor_btn_paste_value)
				$options[$key] = $wpui_admin_editor_btn_paste_value;
			 if (isset($wpui_admin_editor_btn_paste_option['wpui_admin_editor_btn_paste'])) { 
			 	return $wpui_admin_editor_btn_paste_option['wpui_admin_editor_btn_paste'];
			 }
		}
	};
}

//Backcolor button
if (array_key_exists( 'backcolor_btn', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_btn_backcolor() {
		$wpui_admin_editor_btn_backcolor_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_btn_backcolor_option ) ) {
			foreach ($wpui_admin_editor_btn_backcolor_option as $key => $wpui_admin_editor_btn_backcolor_value)
				$options[$key] = $wpui_admin_editor_btn_backcolor_value;
			 if (isset($wpui_admin_editor_btn_backcolor_option['wpui_admin_editor_btn_backcolor'])) { 
			 	return $wpui_admin_editor_btn_backcolor_option['wpui_admin_editor_btn_backcolor'];
			 }
		}
	};

	function wpui_add_more_buttons_tinymce($buttons) {
		if (wpui_admin_editor_btn_newdocument() == '1') {
			$buttons[] = 'newdocument';
		}
		if (wpui_admin_editor_btn_cut() == '1') {
			$buttons[] = 'cut';
		}
		if (wpui_admin_editor_btn_copy() == '1') {
			$buttons[] = 'copy';
		}
		if (wpui_admin_editor_btn_paste() == '1') {
			$buttons[] = 'paste';
		}
		if (wpui_admin_editor_btn_backcolor() == '1') {
			$buttons[] = 'backcolor';
		}
		return $buttons;
	}
	add_filter("mce_buttons", "wpui_add_more_buttons_tinymce");
}

//Insert Media
if (array_key_exists( 'remove_insert_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_insert() {
		$wpui_admin_editor_media_insert_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_insert_option ) ) {
			foreach ($wpui_admin_editor_media_insert_option as $key => $wpui_admin_editor_media_insert_value)
				$options[$key] = $wpui_admin_editor_media_insert_value;
			 if (isset($wpui_admin_editor_media_insert_option['wpui_admin_editor_media_insert'])) { 
			 	return $wpui_admin_editor_media_insert_option['wpui_admin_editor_media_insert'];
			 }
		}
	}
}

//Upload Files
if (array_key_exists( 'remove_upload_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_upload() {
		$wpui_admin_editor_media_upload_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_upload_option ) ) {
			foreach ($wpui_admin_editor_media_upload_option as $key => $wpui_admin_editor_media_upload_value)
				$options[$key] = $wpui_admin_editor_media_upload_value;
			 if (isset($wpui_admin_editor_media_upload_option['wpui_admin_editor_media_upload'])) { 
			 	return $wpui_admin_editor_media_upload_option['wpui_admin_editor_media_upload'];
			 }
		}
	}
}

//Media Library
if (array_key_exists( 'remove_library_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_library() {
		$wpui_admin_editor_media_library_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_library_option ) ) {
			foreach ($wpui_admin_editor_media_library_option as $key => $wpui_admin_editor_media_library_value)
				$options[$key] = $wpui_admin_editor_media_library_value;
			 if (isset($wpui_admin_editor_media_library_option['wpui_admin_editor_media_library'])) { 
			 	return $wpui_admin_editor_media_library_option['wpui_admin_editor_media_library'];
			 }
		}
	}
}

//Media Gallery
if (array_key_exists( 'remove_gallery_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_gallery() {
		$wpui_admin_editor_media_gallery_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_gallery_option ) ) {
			foreach ($wpui_admin_editor_media_gallery_option as $key => $wpui_admin_editor_media_gallery_value)
				$options[$key] = $wpui_admin_editor_media_gallery_value;
			 if (isset($wpui_admin_editor_media_gallery_option['wpui_admin_editor_media_gallery'])) { 
			 	return $wpui_admin_editor_media_gallery_option['wpui_admin_editor_media_gallery'];
			 }
		}
	}
}

//Media Playlist
if (array_key_exists( 'remove_playlist_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_playlist() {
		$wpui_admin_editor_media_playlist_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_playlist_option ) ) {
			foreach ($wpui_admin_editor_media_playlist_option as $key => $wpui_admin_editor_media_playlist_value)
				$options[$key] = $wpui_admin_editor_media_playlist_value;
			 if (isset($wpui_admin_editor_media_playlist_option['wpui_admin_editor_media_playlist'])) { 
			 	return $wpui_admin_editor_media_playlist_option['wpui_admin_editor_media_playlist'];
			 }
		}
	}
}

//Featured img
if (array_key_exists( 'remove_set_featured_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_featured_img() {
		$wpui_admin_editor_media_featured_img_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_featured_img_option ) ) {
			foreach ($wpui_admin_editor_media_featured_img_option as $key => $wpui_admin_editor_media_featured_img_value)
				$options[$key] = $wpui_admin_editor_media_featured_img_value;
			 if (isset($wpui_admin_editor_media_featured_img_option['wpui_admin_editor_media_featured_img'])) { 
			 	return $wpui_admin_editor_media_featured_img_option['wpui_admin_editor_media_featured_img'];
			 }
		}
	}
}

//Insert URL
if (array_key_exists( 'remove_insert_url_media_modal', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_media_insert_url() {
		$wpui_admin_editor_media_insert_url_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_media_insert_url_option ) ) {
			foreach ($wpui_admin_editor_media_insert_url_option as $key => $wpui_admin_editor_media_insert_url_value)
				$options[$key] = $wpui_admin_editor_media_insert_url_value;
			 if (isset($wpui_admin_editor_media_insert_url_option['wpui_admin_editor_media_insert_url'])) { 
			 	return $wpui_admin_editor_media_insert_url_option['wpui_admin_editor_media_insert_url'];
			 }
		}
	}
}

add_filter( 'media_view_strings', 'wpui_custom_media_uploader' );

function wpui_custom_media_uploader( $strings ) {
	if (function_exists('wpui_admin_editor_media_insert') && wpui_admin_editor_media_insert() == '1') {
		unset( $strings['insertMediaTitle'] ); //Insert Media
	}
	if (function_exists('wpui_admin_editor_media_upload') && wpui_admin_editor_media_upload() == '1') {
		unset( $strings['uploadFilesTitle'] ); //Upload Files
	}
	if (function_exists('wpui_admin_editor_media_library') && wpui_admin_editor_media_library() == '1') {
		unset( $strings['mediaLibraryTitle'] ); //Media Library
	}
	if (function_exists('wpui_admin_editor_media_gallery') && wpui_admin_editor_media_gallery() == '1') {
		unset( $strings['createGalleryTitle'] ); //Create Gallery
	}
	if (function_exists('wpui_admin_editor_media_playlist') && wpui_admin_editor_media_playlist() == '1') {
		unset( $strings['createPlaylistTitle'] ); //Create Playlist
	}
	if (function_exists('wpui_admin_editor_media_featured_img') && wpui_admin_editor_media_featured_img() == '1') {
		unset( $strings['setFeaturedImageTitle'] ); //Set Featured Image
	}
	if (function_exists('wpui_admin_editor_media_insert_url') && wpui_admin_editor_media_insert_url() == '1') {
		unset( $strings['insertFromUrlTitle'] ); //Insert from URL
	}
	return $strings;
}


//P Quicktags
if (array_key_exists( 'p_quicktags', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_quicktags_p() {
		$wpui_admin_editor_quicktags_p_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_quicktags_p_option ) ) {
			foreach ($wpui_admin_editor_quicktags_p_option as $key => $wpui_admin_editor_quicktags_p_value)
				$options[$key] = $wpui_admin_editor_quicktags_p_value;
			if (isset($wpui_admin_editor_quicktags_p_option['wpui_admin_editor_quicktags_p'])) {
				return $wpui_admin_editor_quicktags_p_option['wpui_admin_editor_quicktags_p'];
			}
		}
	};
}

//HR Quicktags
if (array_key_exists( 'hr_quicktags', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_quicktags_hr() {
		$wpui_admin_editor_quicktags_hr_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_quicktags_hr_option ) ) {
			foreach ($wpui_admin_editor_quicktags_hr_option as $key => $wpui_admin_editor_quicktags_hr_value)
				$options[$key] = $wpui_admin_editor_quicktags_hr_value;
			if (isset($wpui_admin_editor_quicktags_hr_option['wpui_admin_editor_quicktags_hr'])) {
				return $wpui_admin_editor_quicktags_hr_option['wpui_admin_editor_quicktags_hr'];
			}
		}
	};
}

//PRE Quicktags
if (array_key_exists( 'pre_quicktags', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_quicktags_pre() {
		$wpui_admin_editor_quicktags_pre_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_quicktags_pre_option ) ) {
			foreach ($wpui_admin_editor_quicktags_pre_option as $key => $wpui_admin_editor_quicktags_pre_value)
				$options[$key] = $wpui_admin_editor_quicktags_pre_value;
			if (isset($wpui_admin_editor_quicktags_pre_option['wpui_admin_editor_quicktags_pre'])) {
				return $wpui_admin_editor_quicktags_pre_option['wpui_admin_editor_quicktags_pre'];
			}
		}
	};

	function wpui_admin_editor_quicktags() {
		if ( wp_script_is( 'quicktags' ) ) {
			?>
		<script type="text/javascript">
		<?php if (wpui_admin_editor_quicktags_p() == '1') { ?>
		QTags.addButton( 'eg_paragraph', 'p', '<p>', '</p>', 'p', 'Paragraph tag', 1 );
		<?php } ?>
		<?php if (wpui_admin_editor_quicktags_hr() == '1') { ?>
		QTags.addButton( 'eg_hr', 'hr', '<hr />', '', 'h', 'Horizontal rule line', 201 );
		<?php } ?>
		<?php if (wpui_admin_editor_quicktags_pre() == '1') { ?>
		QTags.addButton( 'eg_pre', 'pre', '<pre>', '</pre>', 'q', 'Preformatted text', 111 );
		<?php } ?>
		</script>
		<?php
		}
	}
	if ((wpui_admin_editor_quicktags_p() == '1') || (wpui_admin_editor_quicktags_hr() == '1') || (wpui_admin_editor_quicktags_pre() == '1') ) {
		add_action( 'admin_print_footer_scripts', 'wpui_admin_editor_quicktags' );
	}
}

//PRE Quicktags
if (array_key_exists( 'formatting_shortcuts', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_formatting_shortcuts() {
		$wpui_admin_editor_formatting_shortcuts_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_formatting_shortcuts_option ) ) {
			foreach ($wpui_admin_editor_formatting_shortcuts_option as $key => $wpui_admin_editor_formatting_shortcuts_value)
				$options[$key] = $wpui_admin_editor_formatting_shortcuts_value;
			if (isset($wpui_admin_editor_formatting_shortcuts_option['wpui_admin_editor_formatting_shortcuts'])) {
				return $wpui_admin_editor_formatting_shortcuts_option['wpui_admin_editor_formatting_shortcuts'];
			}
		}
	};

	if (wpui_admin_editor_formatting_shortcuts() =='1') {
		function wpui_editor_disable_formatting_shortcuts( $opt ) {
			if ( isset( $opt['plugins'] ) && $opt['plugins'] ) {
				$opt['plugins'] = explode( ',', $opt['plugins'] );
				$opt['plugins'] = array_diff( $opt['plugins'] , array( 'wptextpattern' ) );
				$opt['plugins'] = implode( ',', $opt['plugins'] );
			}
			return $opt;
		}
		add_filter( 'tiny_mce_before_init', 'wpui_editor_disable_formatting_shortcuts', 999 );
	}
}

//Default Image Alignment
if (array_key_exists( 'img_default_align', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_img_def_align() {
		$wpui_admin_editor_img_def_align_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_img_def_align_option ) ) {
			foreach ($wpui_admin_editor_img_def_align_option as $key => $wpui_admin_editor_img_def_align_value)
				$options[$key] = $wpui_admin_editor_img_def_align_value;
			if (isset($wpui_admin_editor_img_def_align_option['wpui_admin_editor_img_def_align'])) {
				return $wpui_admin_editor_img_def_align_option['wpui_admin_editor_img_def_align'];
			}
		}
	};

	if (wpui_admin_editor_img_def_align() !='') {
	    update_option( 'image_default_align', wpui_admin_editor_img_def_align());
	}
}

//Default Image Link Type
if (array_key_exists( 'img_defaut_link_type', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_img_def_link() {
		$wpui_admin_editor_img_def_link_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_img_def_link_option ) ) {
			foreach ($wpui_admin_editor_img_def_link_option as $key => $wpui_admin_editor_img_def_link_value)
				$options[$key] = $wpui_admin_editor_img_def_link_value;
			if (isset($wpui_admin_editor_img_def_link_option['wpui_admin_editor_img_def_link'])) {
				return $wpui_admin_editor_img_def_link_option['wpui_admin_editor_img_def_link'];
			}
		}
	};

	if (wpui_admin_editor_img_def_link() !='') {
        update_option( 'image_default_link_type', wpui_admin_editor_img_def_link());
	}
}

//Default Image Size
if (array_key_exists( 'img_default_size', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_admin_editor_img_def_size() {
		$wpui_admin_editor_img_def_size_option = get_option("wpui_editor_option_name");
		if ( ! empty ( $wpui_admin_editor_img_def_size_option ) ) {
			foreach ($wpui_admin_editor_img_def_size_option as $key => $wpui_admin_editor_img_def_size_value)
				$options[$key] = $wpui_admin_editor_img_def_size_value;
			if (isset($wpui_admin_editor_img_def_size_option['wpui_admin_editor_img_def_size'])) {
				return $wpui_admin_editor_img_def_size_option['wpui_admin_editor_img_def_size'];
			}
		}
	};

	if (wpui_admin_editor_img_def_size() !='') {//check
	    update_option( 'image_default_size', wpui_admin_editor_img_def_size());
	}
}
