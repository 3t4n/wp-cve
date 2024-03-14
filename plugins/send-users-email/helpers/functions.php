<?php

if ( ! function_exists( 'sue_get_roles' ) ) {
	function sue_get_roles( $remove_roles = [] ) {
		global $wp_roles;

		$all_roles = $wp_roles->roles;
		$rolesArr  = [];
		foreach ( $all_roles as $role_Slug => $role_detail ) {
			$rolesArr[ $role_Slug ] = $role_detail['name'];
		}

		// Remove un-necessary roles
		foreach ( $remove_roles as $remove_role ) {
			unset( $rolesArr[ $remove_role ] );
		}

		ksort( $rolesArr );

		return $rolesArr;
	}
}

if ( ! function_exists( 'sue_get_selected_roles' ) ) {
	function sue_get_selected_roles() {
		$options        = get_option( 'sue_send_users_email' );
		$selected_roles = $options['email_send_roles'] ?? '';

		$selected_roles   = explode( ',', $selected_roles );
		$selected_roles[] = 'administrator';

		return $selected_roles;
	}
}

if ( ! function_exists( 'sue_add_email_capability_to_roles' ) ) {
	function sue_add_email_capability_to_roles( $new_roles ) {
		$all_roles = sue_get_roles( [ 'administrator' ] );
		$new_roles = explode( ',', $new_roles );

		// First remove capability from all roles except administrator
		foreach ( $all_roles as $role_slug => $name ) {
			$role = get_role( $role_slug );
			if ( $role ) {
				$role->remove_cap( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY );
			}
		}

		// Now add capability to new roles
		foreach ( $new_roles as $new_role ) {
			$role = get_role( $new_role );
			if ( $role ) {
				$role->add_cap( SEND_USERS_EMAIL_SEND_MAIL_CAPABILITY );
			}
		}
	}
}

if ( ! function_exists( 'sue_get_plugin_url' ) ) {
	function sue_get_plugin_url( $file = '' ) {
		return SEND_USERS_EMAIL_PLUGIN_BASE_URL . $file;
	}
}

if ( ! function_exists( 'sue_get_asset_url' ) ) {
	function sue_get_asset_url( $file = '' ) {
		return SEND_USERS_EMAIL_PLUGIN_BASE_URL . '/assets/' . $file;
	}
}

if ( ! function_exists( 'sue_get_date_range_interval' ) ) {
	function sue_get_past_dates_range_interval( $interval_days = 1 ) {

		$dates = [];

		for ( $i = 0; $i < $interval_days; $i ++ ) {
			$endDate = new \DateTime( "-$i days" );
			$dates[] = $endDate->format( 'Y-m-d' );
		}

		return array_reverse( $dates );
	}
}

if ( ! function_exists( 'sue_get_email_theme_scheme' ) ) {
	function sue_get_email_theme_scheme() {

		return [
			'default',
			'blue',
			'green',
			'pink',
			'purple',
			'red',
			'yellow',
		];
	}
}

if ( ! function_exists( 'sue_remove_caption_shortcode' ) ) {
	function sue_remove_caption_shortcode( $content ) {

		return preg_replace( '%(\[caption\b[^\]]*\](.*?)(\[\/caption]))%', '$2', $content );
	}
}

if ( ! function_exists( 'sue_log_path' ) ) {
	function sue_log_path( $filename = null ) {
		$dirDetails  = wp_upload_dir();
		$uploads_dir = trailingslashit( $dirDetails['basedir'] );

		// check if directory exists and if not create it
		if ( ! file_exists( $uploads_dir . DIRECTORY_SEPARATOR . 'send-users-email' ) ) {
			mkdir( $uploads_dir . DIRECTORY_SEPARATOR . 'send-users-email', 0755 );
		}

		// Create an index file to prevent directory browsing
		$file = $uploads_dir . DIRECTORY_SEPARATOR . 'send-users-email' . DIRECTORY_SEPARATOR . 'index.php';
		if ( ! file_exists( $file ) ) {
			file_put_contents( $file, '' );
		}

		$path = $uploads_dir . DIRECTORY_SEPARATOR . 'send-users-email' . DIRECTORY_SEPARATOR;
		if ( $filename ) {
			$path .= $filename;
		}

		return $path;
	}
}

if ( ! function_exists( 'sue_bytes_to_mb' ) ) {
	function sue_bytes_to_mb( $size = 0 ) {
		return number_format( $size / ( 1024 * 1024 ), 2 );
	}
}

if ( ! function_exists( 'sue_log_wp_mail_failed_error' ) ) {
	function sue_log_wp_mail_failed_error( $message ) {
		$errorLogFileName = sue_get_error_log_filename();
		if ( ! $errorLogFileName ) {
			$errorLogFileName = strtolower( wp_generate_password( 8, false ) . '-error.log' );
			$handle           = fopen( sue_log_path( $errorLogFileName ), 'w' );
			fclose( $handle );
		}
		file_put_contents( sue_log_path( $errorLogFileName ), $message, FILE_APPEND );
	}
}

if ( ! function_exists( 'sue_log_sent_emails' ) ) {
	function sue_log_sent_emails( $user_email, $email_subject, $email_body, $via = 'user' ) {
		$filename = 'email-log-' . date( 'Y-m-d' ) . '.log';
		$file     = sue_log_path( $filename );
		if ( ! file_exists( $file ) ) {
			$handle = fopen( $file, 'w' );
			fclose( $handle );
		}

		$message = '[' . date( 'Y-m-d h:i:s' ) . ']';
		$message .= ' EMAIL SENT';
		$message .= ' | ADDRESS: ' . sue_obscure_text( $user_email, 3 );
		$message .= ' | SUBJECT: ' . trim( preg_replace( '/\s+/', ' ', $email_subject ) );
		$message .= ' | VIA: ' . $via;
		$message .= ' | CONTENT: ' . preg_replace( "/[\r\n]+/", "\n", strip_tags( $email_body ) );

		file_put_contents( $file, $message, FILE_APPEND );
	}
}

if ( ! function_exists( 'sue_remove_non_email_log_filename' ) ) {
	function sue_remove_non_email_log_filename( $fileLists ) {
		// Remove other file names except email log files from array
		foreach ( $fileLists as $key => $fileList ) {
			// remove any other non log email file
			if ( strpos( $fileList, 'email-log' ) !== 0 || substr( $fileList, - 4 ) != '.log' ) {
				unset( $fileLists[ $key ] );
			}
		}

		return $fileLists;
	}
}

if ( ! function_exists( 'sue_get_error_log_filename' ) ) {
	function sue_get_error_log_filename() {
		$logFolderFiles = scandir( sue_log_path() );
		foreach ( $logFolderFiles as $key => $log_folder_file ) {
			if ( strpos( $log_folder_file, '-error.log' ) !== false ) {
				return $log_folder_file;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'sue_obscure_text' ) ) {
	function sue_obscure_text( $string, $frequency = 2 ) {
		$length = strlen( $string );
		for ( $i = 0; $i < $length; $i ++ ) {
			if ( $i != 0 && $i % $frequency == 0 ) {
				$string = substr_replace( $string, '*', $i, 1 );
			}
		}

		return $string;
	}
}