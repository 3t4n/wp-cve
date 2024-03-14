<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//Media Library
//=================================================================================================

//Image quality
if (array_key_exists( 'jpg_quality', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_jpeg_quality() {
        $wpui_library_jpeg_quality_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_jpeg_quality_option ) ) {
            foreach ($wpui_library_jpeg_quality_option as $key => $wpui_library_jpeg_quality_value)
                $options[$key] = $wpui_library_jpeg_quality_value;
             if (isset($wpui_library_jpeg_quality_option['wpui_library_jpeg_quality'])) { 
                return $wpui_library_jpeg_quality_option['wpui_library_jpeg_quality'];
             }
        }
    };

    if (wpui_library_jpeg_quality() !='') {
        add_filter('jpeg_quality', function($arg){return wpui_library_jpeg_quality();}, 9999);
        add_filter( 'wp_editor_set_quality', function($arg){return wpui_library_jpeg_quality();}, 9999 );
    }
}

//Clean filenames
if (array_key_exists( 'clean_filenames', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_clean_filename() {
        $wpui_library_clean_filename_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_clean_filename_option ) ) {
            foreach ($wpui_library_clean_filename_option as $key => $wpui_library_clean_filename_value)
                $options[$key] = $wpui_library_clean_filename_value;
             if (isset($wpui_library_clean_filename_option['wpui_library_clean_filename'])) { 
                return $wpui_library_clean_filename_option['wpui_library_clean_filename'];
             }
        }
    };

    if (wpui_library_clean_filename() =='1') {
        function wpui_library_clean_filenames($filename) {
			
			/* @author 	Mickael Gris */
			/* Force the file name in UTF-8 (encoding Windows / OS X / Linux) */
			$filename = mb_convert_encoding($filename, "UTF-8");

			$char_not_clean = array('/À/','/Á/','/Â/','/Ã/','/Ä/','/Å/','/Ç/','/È/','/É/','/Ê/','/Ë/','/Ì/','/Í/','/Î/','/Ï/','/Ò/','/Ó/','/Ô/','/Õ/','/Ö/','/Ù/','/Ú/','/Û/','/Ü/','/Ý/','/à/','/á/','/â/','/ã/','/ä/','/å/','/ç/','/è/','/é/','/ê/','/ë/','/ì/','/í/','/î/','/ï/','/ð/','/ò/','/ó/','/ô/','/õ/','/ö/','/ù/','/ú/','/û/','/ü/','/ý/','/ÿ/', '/©/');
			$clean = array('a','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y','copy');

			$friendly_filename = preg_replace($char_not_clean, $clean, $filename);


			/* After replacement, we destroy the last residues */
			$friendly_filename = utf8_decode($friendly_filename);
			$friendly_filename = preg_replace('/\?/', '', $friendly_filename);


			/* Lowercase */
			$friendly_filename = strtolower($friendly_filename);

			return $friendly_filename;
		}
		add_filter('sanitize_file_name', 'wpui_library_clean_filenames', 10);
    }
}

//SVG mimes type
if (array_key_exists( 'svg_mimes_type', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_svg_file() {
        $wpui_library_svg_file_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_svg_file_option ) ) {
            foreach ($wpui_library_svg_file_option as $key => $wpui_library_svg_file_value)
                $options[$key] = $wpui_library_svg_file_value;
             if (isset($wpui_library_svg_file_option['wpui_library_svg'])) { 
                return $wpui_library_svg_file_option['wpui_library_svg'];
             }
        }
    };

    if (wpui_library_svg_file() !='') {
        function wpui_library_svg_file_upload($mimes) {
			$mimes['svg'] = 'image/svg+xml';
			return $mimes;
		}
		add_filter('upload_mimes', 'wpui_library_svg_file_upload');
    }
}

//Add URL col
if (array_key_exists( 'url_col', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_url_col() {
        $wpui_library_url_col_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_url_col_option ) ) {
            foreach ($wpui_library_url_col_option as $key => $wpui_library_url_col_value)
                $options[$key] = $wpui_library_url_col_value;
             if (isset($wpui_library_url_col_option['wpui_library_url_col'])) { 
                return $wpui_library_url_col_option['wpui_library_url_col'];
             }
        }
    };

    if (wpui_library_url_col() !='') {
		function wpui_library_add_url_col( $cols ) {
		    $cols["wpui_media_url"] = "URL";
		    return $cols;
		}
		function wpui_library_add_url_col_display( $column_name, $id ) {
		        if ( $column_name == "wpui_media_url" ) {
		        	echo '<input type="text" width="100%" onclick="jQuery(this).select();" value="'. wp_get_attachment_url( $id ). '" />';
		        }
		}
		add_filter( 'manage_media_columns', 'wpui_library_add_url_col' );
		add_action( 'manage_media_custom_column', 'wpui_library_add_url_col_display', 999, 2 );
    }
}

//Add Dimensions col
if (array_key_exists( 'dimensions_col', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_dimensions_col() {
        $wpui_library_dimensions_col_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_dimensions_col_option ) ) {
            foreach ($wpui_library_dimensions_col_option as $key => $wpui_library_dimensions_col_value)
                $options[$key] = $wpui_library_dimensions_col_value;
             if (isset($wpui_library_dimensions_col_option['wpui_library_dimensions_col'])) { 
                return $wpui_library_dimensions_col_option['wpui_library_dimensions_col'];
             }
        }
    };

    if (wpui_library_dimensions_col() !='') {
		function wpui_media_col_dimensions( $cols ) {
	        $cols["wpui_dimensions"] = "Width x Height";
	        return $cols;
		}
		function wpui_media_col_dimensions_display( $column_name, $id ) {
		    $meta = wp_get_attachment_metadata($id);
		    if ( $column_name == "wpui_dimensions" ) {
				if(isset($meta['width']))
				echo $meta['width'].' x '.$meta['height'];
			}
		}
		add_filter( 'manage_media_columns', 'wpui_media_col_dimensions' );
		add_action( 'manage_media_custom_column', 'wpui_media_col_dimensions_display', 999, 2 );
    }
}

//Add EXIF col
if (array_key_exists( 'exif_col', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_exif_col() {
        $wpui_library_exif_col_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_exif_col_option ) ) {
            foreach ($wpui_library_exif_col_option as $key => $wpui_library_exif_col_value)
                $options[$key] = $wpui_library_exif_col_value;
             if (isset($wpui_library_exif_col_option['wpui_library_exif_col'])) { 
                return $wpui_library_exif_col_option['wpui_library_exif_col'];
             }
        }
    };

    if (wpui_library_exif_col() !='') {
		function wpui_media_col_exif($defaults){
		    $defaults['wpui_exif'] = __('EXIF', 'wp-admin-ui');
		    return $defaults;
		}
		function wpui_shutter_speed($meta) {
			if ((1 / $meta['image_meta']['shutter_speed']) > 1) {
		    	if ((number_format((1 / $meta['image_meta']['shutter_speed']), 1)) == 1.3
		     	or number_format((1 / $meta['image_meta']['shutter_speed']), 1) == 1.5
		     	or number_format((1 / $meta['image_meta']['shutter_speed']), 1) == 1.6
		     	or number_format((1 / $meta['image_meta']['shutter_speed']), 1) == 2.5){
		        	return "1/" . number_format((1 / $meta['image_meta']['shutter_speed']), 1, '.', '') . __(' second', 'wp-admin-ui');
		     	}
		     	else {
		       		return "1/" . number_format((1 / $meta['image_meta']['shutter_speed']), 0, '.', '') . __(' second', 'wp-admin-ui');
		     	}
		  	}
		  	else {
		    	return $meta['image_meta']['shutter_speed'] . __(' seconds', 'wp-admin-ui');
		   	}
		}
		function wpui_media_col_exif_display($column_name, $id){
	        if($column_name === 'wpui_exif'){
	           	$meta = wp_get_attachment_metadata($id);
	           	if($meta !='') {
	        		if($meta['image_meta']['camera'] != ''){
			        	echo "CR:  ".$meta['image_meta']['credit']."<hr />";
			           	echo "CAM:  ".$meta['image_meta']['camera']."<hr />";
			           	echo "FL:  ".$meta['image_meta']['focal_length']."<hr />";
			           	echo "AP:  ".$meta['image_meta']['aperture']."<hr />";
			           	echo "ISO:  ".$meta['image_meta']['iso']."<hr />";
			           	echo "SS:  ".wpui_shutter_speed($meta)."<hr />";
			           	echo "TS:  ".date( "d-M-Y H:i:s", $meta['image_meta']['created_timestamp'] )."<hr />";
			           	echo "C:  ".$meta['image_meta']['copyright'];
			        }
		        }
	    	}
		}
		add_filter('manage_media_columns', 'wpui_media_col_exif', 1, 999);
		add_action('manage_media_custom_column', 'wpui_media_col_exif_display', 1, 999);
    }
}

//Add ID col
if (array_key_exists( 'id_col', wpui_get_roles_cap($wpui_user_role))) {
    function wpui_library_id_col() {
        $wpui_library_id_col_option = get_option("wpui_library_option_name");
        if ( ! empty ( $wpui_library_id_col_option ) ) {
            foreach ($wpui_library_id_col_option as $key => $wpui_library_id_col_value)
                $options[$key] = $wpui_library_id_col_value;
             if (isset($wpui_library_id_col_option['wpui_library_id_col'])) { 
                return $wpui_library_id_col_option['wpui_library_id_col'];
             }
        }
    };

    if (wpui_library_id_col() !='') {
		function wpui_media_col_attachment_id($defaults){
		    $defaults['wpui_media_id'] = __('ID');
		    return $defaults;
		}
		function wpui_media_custom_col_attachment_id($column_name, $id){
		        if($column_name === 'wpui_media_id'){
		        echo $id;
		    }
		}
		add_filter('manage_media_columns', 'wpui_media_col_attachment_id', 1);
		add_action('manage_media_custom_column', 'wpui_media_custom_col_attachment_id', 1, 2);
    }
}

//PDF Filter
if (array_key_exists( 'pdf_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_pdf() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_pdf'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_pdf'];
			 }
		}
	};
}

//ZIP Filter
if (array_key_exists( 'zip_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_zip() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_zip'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_zip'];
			 }
		}
	};
}

//RAR Filter
if (array_key_exists( 'rar_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_rar() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_rar'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_rar'];
			 }
		}
	};
}

//7Z Filter
if (array_key_exists( '7z_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_7z() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_7z'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_7z'];
			 }
		}
	};
}

//TAR Filter
if (array_key_exists( 'tar_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_tar() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_tar'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_tar'];
			 }
		}
	};
}

//SWF Filter
if (array_key_exists( 'swf_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_swf() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_swf'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_swf'];
			 }
		}
	};
}

//DOC Filter
if (array_key_exists( 'doc_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_doc() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_doc'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_doc'];
			 }
		}
	};
}

//DOCX Filter
if (array_key_exists( 'docx_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_docx() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_docx'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_docx'];
			 }
		}
	};
}

//PPT Filter
if (array_key_exists( 'ppt_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_ppt() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_ppt'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_ppt'];
			 }
		}
	};
}

//PPTX Filter
if (array_key_exists( 'pptx_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_pptx() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_pptx'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_pptx'];
			 }
		}
	};
}

//XLS Filter
if (array_key_exists( 'xls_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_xls() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_xls'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_xls'];
			 }
		}
	};
}

//XLSX Filter
if (array_key_exists( 'xlsx_filter', wpui_get_roles_cap($wpui_user_role))) {
	function wpui_library_filters_xlsx() {
		$wpui_library_filters_option = get_option("wpui_library_option_name");
		if ( ! empty ( $wpui_library_filters_option ) ) {
			foreach ($wpui_library_filters_option as $key => $wpui_library_filters_value)
				$options[$key] = $wpui_library_filters_value;
			 if (isset($wpui_library_filters_option['wpui_library_filters_xlsx'])) { 
			 	return $wpui_library_filters_option['wpui_library_filters_xlsx'];
			 }
		}
	};

	function wpui_get_allowed_mime_types( $post_mime_types ) {
		if (wpui_library_filters_pdf() == '1') {
	    	$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_zip() == '1') {
	    	$post_mime_types['application/zip'] = array( __( 'ZIPs' ), __( 'Manage ZIPs' ), _n_noop( 'ZIP <span class="count">(%s)</span>', 'ZIPs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_rar() == '1') {
	    	$post_mime_types['application/rar'] = array( __( 'RARs' ), __( 'Manage RARs' ), _n_noop( 'RAR <span class="count">(%s)</span>', 'RARs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_7z() == '1') {
	    	$post_mime_types['application/x-7z-compressed'] = array( __( '7Zs' ), __( 'Manage 7Zs' ), _n_noop( '7Z <span class="count">(%s)</span>', '7Zs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_tar() == '1') {
	    	$post_mime_types['application/x-tar'] = array( __( 'TARs' ), __( 'Manage TARs' ), _n_noop( 'TAR <span class="count">(%s)</span>', 'TARs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_swf() == '1') {
	    	$post_mime_types['application/x-shockwave-flash'] = array( __( 'SWFs' ), __( 'Manage SWFs' ), _n_noop( 'SWF <span class="count">(%s)</span>', 'SWFs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_doc() == '1') {
	    	$post_mime_types['application/msword'] = array( __( 'DOCs' ), __( 'Manage DOCs' ), _n_noop( 'DOC <span class="count">(%s)</span>', 'DOCs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_docx() == '1') {
	    	$post_mime_types['application/vnd.openxmlformats-officedocument.wordprocessingml.document'] = array( __( 'DOCXs' ), __( 'Manage DOCXs' ), _n_noop( 'DOCX <span class="count">(%s)</span>', 'DOCXs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_ppt() == '1') {
	    	$post_mime_types['application/vnd.ms-powerpoint'] = array( __( 'PPTs' ), __( 'Manage PPTs' ), _n_noop( 'PPT <span class="count">(%s)</span>', 'PPTs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_pptx() == '1') {
	    	$post_mime_types['application/vnd.openxmlformats-officedocument.presentationml.presentation'] = array( __( 'PPTXs' ), __( 'Manage PPTXs' ), _n_noop( 'PPTX <span class="count">(%s)</span>', 'PPTXs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_xls() == '1') {
	    	$post_mime_types['application/vnd.ms-excel'] = array( __( 'XLSs' ), __( 'Manage XLSs' ), _n_noop( 'XLS <span class="count">(%s)</span>', 'XLSs <span class="count">(%s)</span>' ) );
	    }
	    if (wpui_library_filters_xlsx() == '1') {
	    	$post_mime_types['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'] = array( __( 'XLSXs' ), __( 'Manage XLSXs' ), _n_noop( 'XLSX <span class="count">(%s)</span>', 'XLSXs <span class="count">(%s)</span>' ) );
	    }
	    return $post_mime_types;
	}

	add_filter( 'post_mime_types', 'wpui_get_allowed_mime_types', 999 );
}