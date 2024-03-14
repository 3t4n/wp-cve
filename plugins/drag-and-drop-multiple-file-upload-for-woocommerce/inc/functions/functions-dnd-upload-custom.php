<?php

	/**
	* @Description : Custom Ajax Functions
	* @Package : Drag & Drop Multiple File Upload - WooCommerce
	* @Author : CodeDropz
	*/

	if ( ! defined( 'ABSPATH' ) || ! defined('DNDMFU_WC') ) {
		exit;
	}

	/**
	* Get Directory
	* @return : array
	*/

	function dndmfu_wc_dir( $dir = 'basedir' ) {
		$instance = DNDMFU_WC_INIT();
		if( isset( $instance->wp_upload_dir[ $dir ] ) ) {
			return $instance->wp_upload_dir[ $dir ];
		}
		return $instance;
	}

	/**
	* Get dir setup
	*/

	function dndmfu_wc_dir_setup( $directory = null, $create = false ) {

		// Create dir
		if( $create ) {
			if( ! is_dir( $directory ) ) {
				wp_mkdir_p( $directory );
			}
			if( file_exists( $directory ) ) {
				return $directory;
			}
		}

		// Get current IDR
		return $directory;
	}

	/**
	* Setup file type pattern for validation
	*/

	function dndmfu_wc_filetypes( $types ) {
		$file_type_pattern = '';

		$allowed_file_types = array();
		$file_types = explode( '|', $types );

		foreach ( $file_types as $file_type ) {
			$file_type = trim( $file_type, '.' );
			$file_type = str_replace( array( '.', '+', '*', '?' ), array( '\.', '\+', '\*', '\?' ), $file_type );
			$allowed_file_types[] = $file_type;
		}

		$allowed_file_types = array_unique( $allowed_file_types );
		$file_type_pattern = implode( '|', $allowed_file_types );

		$file_type_pattern = trim( $file_type_pattern, '|' );
		$file_type_pattern = '(' . $file_type_pattern . ')';
		$file_type_pattern = '/\.' . $file_type_pattern . '$/i';

		return $file_type_pattern;
	}

	/**
	* Check and remove script file
	*/

	function dndmfu_wc_antiscript_file_name( $filename ) {
		$filename = wp_basename( $filename );
		$parts = explode( '.', $filename );

		if ( count( $parts ) < 2 ) {
			return $filename;
		}

		$script_pattern = '/^(php|phtml|pl|py|rb|cgi|asp|aspx)\d?$/i';

		$filename = array_shift( $parts );
		$extension = array_pop( $parts );

		foreach ( (array) $parts as $part ) {
			if ( preg_match( $script_pattern, $part ) ) {
				$filename .= '.' . $part . '_';
			} else {
				$filename .= '.' . $part;
			}
		}

		if ( preg_match( $script_pattern, $extension ) ) {
			$filename .= '.' . $extension . '_.txt';
		} else {
			$filename .= '.' . $extension;
		}

		return $filename;
	}

	/**
	* Filter - Add more validation for file extension
	*/

	function dndmfu_wc_validate_type( $extension, $supported_types = '' ) {

		$valid = false;
		$extension = preg_replace( '/[^A-Za-z0-9,|]/', '', $extension );

		// Get allowed file types (from Wordpress)
        $allowed_mimes = get_allowed_mime_types();

		// Search in $allowe_mimes extension and match
		foreach( $allowed_mimes as $mime => $type ) {
			if ( strpos( $mime, $extension ) !== false ) {
				$valid = true;
                break;
			}
		}

		// If pass on first validation - check extension if exists in allowed types
		if( $valid === true && $supported_types) {
			$extensions = explode('|', strtolower( $supported_types ) );
			if( in_array( $extension, $extensions ) ) {
				$valid = true;
			}
		}

		return $valid;
	}

	/**
	* Get Files
	* @return : array / html
	*/

	function dndmfu_wc_get_files( $files, $raw = false ) {

		if( ! $files )
			return;

		// Get options from main class
		$dir = dndmfu_wc_dir('baseurl');
		$files_upload = array();

		if( is_array( $files ) ) {
			foreach( $files as $file ) {
				if( $raw === false ) {
					$files_upload[] = '<a href="'. esc_url( trailingslashit( $dir ). wp_basename( $file ) ) .'">'. esc_html( wp_basename( $file ) ).'</a>';
				}else {
					$files_upload[] = trailingslashit( $dir ) . wp_basename( $file );
				}
			}
		}

		return $files_upload;
	}

	/**
	* Delete Files
	* @param : $file_path - basedir
	*/

	function dndmfu_wc_delete_file( $file_path ) {

		// There's no reason to proceed if - null
		if( ! $file_path ) {
			return;
		}

		// Get file info
		$file = pathinfo( $file_path );
		$dirname = trailingslashit( wp_normalize_path( $file['dirname'] ) );

		// Check and validate file type if it's safe to delete...
		$safe_to_delete = dndmfu_wc_validate_type( $file['extension'] );

		// @bolean - true if validated
		if( $safe_to_delete ) {

			// Delete parent file
			wp_delete_file( $file_path );

		}
	}

	/**
	* Schedule Delete Files - from /tmp_uploads
	*/

	function dndmfu_wc_auto_remove_files( $dir_path = null, $seconds = 3600, $max = 60 ) {
		if ( is_admin() || 'POST' != $_SERVER['REQUEST_METHOD'] || is_robots() || is_feed() || is_trackback() ) {
			return;
		}

		// Setup dirctory path
		$path = dndmfu_wc_dir( false );

		// Get directory
		$dir = ( ! $dir_path  ? trailingslashit( $path->wp_upload_dir['basedir'] .'/'. $path->_options['tmp_folder'] ) : trailingslashit( $dir_path ) );

		// Make sure dir is readable or writable
		if ( ! is_dir( $dir ) || ! is_readable( $dir ) || ! wp_is_writable( $dir ) ) {
			return;
		}

		// allow theme/plugins to change time before deletion... ( default : 1 hour )
		$seconds = apply_filters( 'dndmfu_wc_time_before_auto_deletion', absint( $seconds ) );

		$max = absint( $max );
		$count = 0;

		if ( $handle = @opendir( $dir ) ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				if ( $file == "." || $file == ".." ) {
					continue;
				}

				// Setup dir and filename
				$file_path = $dir . $file;

				// Check if current path is directory (recursive)
				if( is_dir( $file_path ) ) {
					dndmfu_wc_auto_remove_files( $file_path );
					continue;
				}

				// Get file time of files OLD files.
				$mtime = @filemtime( $file_path );
				if ( $mtime && time() < $mtime + $seconds ) { // less than $seconds old (if time >= modified = then_delete_files) (past)
					continue;
				}

				// @desscription : Make sure it's inside our upload basedir (directory)
				// @example : "c:/xampp/htdocs/wp/wp-content/uploads/wc_drag-n-drop_uploads/file.jpg", "c:/xampp/htdocs/wp/wp-content/uploads/wc_drag-n-drop_uploads/"
				$is_path_in_content_dir = strpos( $file_path, wp_normalize_path( realpath( $path->wp_upload_dir['basedir'] ) ) );

				// Delete files from dir ( don't delete .htaccess file )
				if( 0 === $is_path_in_content_dir ) {
					dndmfu_wc_delete_file( $file_path );
				}

				$count += 1;

				if ( $max <= $count ) {
					break;
				}
			}
			@closedir( $handle );
		}

		// Remove empty dir except - /tmp_uploads
		if( false === strpos( $dir, $path->_options['tmp_folder'] ) ) {
			@rmdir( $dir );
		}
	}

	/**
	* Setup media file on json response after the successfull upload.
	*/

	function dndmfu_wc_media_json_response( $path, $file_name ) {

		$media_files = array(
			'path'		=>	$path,
			'file'		=>	$file_name
		);

		return $media_files;
	}

    /**
	* Get current language
	*/

    function dndmfu_wc_lang() {
        $lang = null;

        // Polylang & WPML compatiblity
        if( function_exists('pll_current_language') ) {
            $lang = pll_current_language();
        }elseif( class_exists('SitePress') ) {
            $lang = ICL_LANGUAGE_CODE;
        }

        // If english / default lang leave empty.
        if( $lang ) {
            $lang = ( $lang == 'en' ? '' : '-'.$lang );
        }

        return apply_filters('dndmfu_wc_lang', $lang );
    }