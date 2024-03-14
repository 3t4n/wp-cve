<?php 
if ( ! class_exists( 'ARM_members_activity_Lite' ) ) {
	class ARM_members_activity_Lite {

		function __construct() {
			global $wpdb, $ARMemberLite, $arm_slugs;
				add_action( 'arm_record_activity', array( $this, 'arm_add_activity' ), 1 );
				/* Ajax Load More Activities */
				add_action( 'wp_ajax_nopriv_arm_crop_iamge', array( $this, 'arm_crop_image' ) );
				add_action( 'wp_ajax_arm_crop_iamge', array( $this, 'arm_crop_image' ) );

				add_action( 'wp_ajax_arm_upload_front', array( $this, 'arm_upload_front' ), 1 );
				add_action( 'wp_ajax_nopriv_arm_upload_front', array( $this, 'arm_upload_front' ), 1 );

				add_action( 'wp_ajax_arm_upload_cover', array( $this, 'arm_upload_cover' ), 1 );
				add_action( 'wp_ajax_nopriv_arm_upload_cover', array( $this, 'arm_upload_cover' ), 1 );

				add_action( 'wp_ajax_arm_upload_profile', array( $this, 'arm_upload_profile' ), 1 );
				add_action( 'wp_ajax_nopriv_arm_upload_profile', array( $this, 'arm_upload_profile' ), 1 );

				add_action( 'wp_ajax_arm_import_user', array( $this, 'arm_import_user' ), 1 );

				global $check_sorting;
				$check_sorting = 'checksorting';

				global $check_version;
				$check_version = 'checkversion';
				add_action( 'admin_init', array( $this, 'upgrade_data' ) );

				add_action( 'wp_ajax_armlite_deactivate_plugin', array( $this, 'armite_deactivate_plugin_func' ) );
				
				add_action( 'admin_footer', array( $this, 'arm_deactivate_feedback_popup' ), 1 );
		}

		function upgrade_data() {

			global $arm_lite_newdbversion;

			if ( ! isset( $arm_lite_newdbversion ) || $arm_lite_newdbversion == '' ) {
				$arm_lite_newdbversion = get_option( 'armlite_version' );
			}

			if ( version_compare( $arm_lite_newdbversion, '4.0.27', '<' ) ) {
				$path = MEMBERSHIPLITE_VIEWS_DIR . '/upgrade_latest_data.php';
				include $path;
			}

		}
		function checkversion( $case = '' ) {

			return 1;
		}
		function checksorting() {

			return 1;
		}

		function arm_add_activity( $activity = array() ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_social_feature;
			return false;
		}

		function arm_get_remote_post_params( $plugin_info = '' ) {
			global $wpdb;

			$action = '';
			$action = $plugin_info;

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugin_list = get_plugins();
			$site_url    = ARMLITE_HOME_URL;
			$plugins     = array();

			$active_plugins = get_option( 'active_plugins' );

			foreach ( $plugin_list as $key => $plugin ) {
				$is_active = in_array( $key, $active_plugins );

				// filter for only armember ones, may get some others if using our naming convention
				if ( strpos( strtolower( $plugin['Title'] ), 'armember' ) !== false ) {
					$name      = substr( $key, 0, strpos( $key, '/' ) );
					$plugins[] = array(
						'name'      => $name,
						'version'   => $plugin['Version'],
						'is_active' => $is_active,
					);
				}
			}
			$plugins = json_encode( $plugins );

			// get theme info
			$theme            = wp_get_theme();
			$theme_name       = $theme->get( 'Name' );
			$theme_uri        = $theme->get( 'ThemeURI' );
			$theme_version    = $theme->get( 'Version' );
			$theme_author     = $theme->get( 'Author' );
			$theme_author_uri = $theme->get( 'AuthorURI' );

			$im        = is_multisite();
			$sortorder = get_option( 'armSortOrder' );

			$post = array(
				'wp'        => get_bloginfo( 'version' ),
				'php'       => phpversion(),
				'mysql'     => $wpdb->db_version(),
				'plugins'   => $plugins,
				'tn'        => $theme_name,
				'tu'        => $theme_uri,
				'tv'        => $theme_version,
				'ta'        => $theme_author,
				'tau'       => $theme_author_uri,
				'im'        => $im,
				'sortorder' => $sortorder,
			);

			return $post;
		}


		function armgetapiurl() {
			$api_url = 'https://arpluginshop.com/';
			return $api_url;
		}




		function checksite( $str ) {
			update_option( 'arm_wp_get_version', $str );
		}

		function arm_get_activity_by( $field = '', $value = '', $limit = '', $object_type = ARRAY_A ) {
			 global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_subscription_plans;
			$object_type = ! empty( $object_type ) ? $object_type : ARRAY_A;
			$limit       = ( ! empty( $limit ) ) ? ' LIMIT ' . $limit : '';
			$result      = false;
			if ( ! empty( $field ) && $value != '' ) {
				$result = $wpdb->get_results( $wpdb->prepare('SELECT * FROM `' . $ARMemberLite->tbl_arm_activity . "` WHERE `$field`=%s ORDER BY `arm_activity_id` DESC $limit",$value), $object_type );//phpcs:ignore --Reason: $ARMemberLite->tbl_arm_activity is table name. False Positive Alarm.
			}
			return $result;
		}
		function arm_crop_image() {
			global $ARMemberLite;
			$ARMemberLite->arm_check_user_cap( '', '1' );//phpcs:ignore --Reason:Verifying nonce
			$update_meta = isset( $_POST['update_meta'] ) ? sanitize_text_field( $_POST['update_meta'] ) : ''; //phpcs:ignore
			$type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : ''; //phpcs:ignore
			$cord = isset( $_POST['cord'] ) ? sanitize_text_field( $_POST['cord'] ) : ''; //phpcs:ignore
			$user_id = get_current_user_id();

			$arm_src = isset( $_POST['src'] ) ? esc_url_raw( $_POST['src'] ) : ''; //phpcs:ignore
			$arm_rotate = isset( $_POST['rotate'] ) ? $_POST['rotate'] : 'undefined'; //phpcs:ignore

			/*this change need to confirm with multisite*/
			$arm_src = MEMBERSHIPLITE_UPLOAD_URL . '/' . basename( $arm_src );

			$info     = getimagesize( MEMBERSHIPLITE_UPLOAD_DIR . '/' . basename( $arm_src ) );
			$file     = esc_url_raw( $arm_src );
			$file1    = MEMBERSHIPLITE_UPLOAD_DIR . '/' . basename( $arm_src );
			$orgnl_hw = getimagesize( $file1 );
			$orgnl_w  = $orgnl_hw[0];
			$orgnl_h  = $orgnl_hw[1];
			$targ_x1  = 0;
			$targ_y1  = 0;
			$targ_x2  = $orgnl_w;
			$targ_y2  = $orgnl_h;
			$is_crop  = false;
			if ( !empty( $cord ) ) {
				$crop = explode( ',', $cord );
				if ( $crop[2] != 0 && $crop[3] != 0 ) {
					$targ_x1 = intval($crop[0]);
					$targ_y1 = intval($crop[1]);
					$targ_x2 = intval($crop[2]);
					$targ_y2 = intval($crop[3]);
					$is_crop = true;
				}
			}

			if ( $type == 'profile' ) {

				if ( $update_meta != 'no' ) {
					update_user_meta( $user_id, 'avatar', $file );
					do_action( 'arm_upload_bp_avatar', $user_id );
				}

				$thumb_w = 220;
				$thumb_h = 220;
			} elseif ( $type == 'cover' ) {
				$thumb_w = 918;
				$thumb_h = 320;

				if ( $update_meta != 'no' ) {
					update_user_meta( $user_id, 'profile_cover', $file );
					do_action( 'arm_upload_bp_profile_cover', $user_id );
				}
			}
			if ( $arm_rotate != 'undefined' ) {
				if ( $arm_rotate == -90 || $arm_rotate == 270 ) {
					$arm_rotate = 90;
				} elseif ( $arm_rotate == -180 || $arm_rotate == 180 ) {
					$arm_rotate = 180;
				} elseif ( $arm_rotate == -270 || $arm_rotate == 90 ) {
					$arm_rotate = 270;
				}
				$new_targ_x1     = $targ_x1;
				$new_targ_y1     = $targ_y1;
				$fileTemp        = $file1;
				$image_info      = getimagesize( $fileTemp );
				$original_width  = $image_info[0];
				$original_height = $image_info[1];
				$new_width       = abs( $targ_x2 - $new_targ_x1 );
				$new_height      = abs( $targ_y2 - $new_targ_y1 );
				if ( $info['mime'] == 'image/png' ) {
					$source         = imagecreatefrompng( $fileTemp );
					$imageRotate    = imagerotate( $source, $arm_rotate, 0 );
					$rotated_width  = imagesx( $imageRotate );
					$rotated_height = imagesy( $imageRotate );
					$dx             = $rotated_width - $original_width;
					$dy             = $rotated_height - $original_height;
					$crop_x         = 0;
					$crop_y         = 0;
					if ( $is_crop ) {
						$crop_x = $dx / 2 + $new_targ_x1;
						$crop_y = $dy / 2 + $new_targ_y1;
					}
					$new_image = imagecreatetruecolor( $targ_x2, $targ_y2 );
					if ( $is_crop ) {
						imagealphablending( $new_image, false );
						imagesavealpha( $new_image, true );
						imagecopyresampled( $new_image, $imageRotate, 0, 0, $targ_x1, $targ_y1, $targ_x2, $targ_y2, $targ_x2, $targ_y2 );
						$upload = imagepng( $new_image, $fileTemp );
					} else {
						$upload = imagepng( $imageRotate, $fileTemp );
					}
					
					$original_info = getimagesize( $file1 );
					$original_w    = $original_info[0];
					$original_h    = $original_info[1];
					$original_img  = imagecreatefrompng( $file1 );
					$thumb_img     = imagecreatetruecolor( $thumb_w, $thumb_h );
					imagealphablending( $thumb_img, false );
					imagesavealpha( $thumb_img, true );
					imagecopyresampled( $thumb_img, $original_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $original_w, $original_h );
					imagepng( $thumb_img, MEMBERSHIPLITE_UPLOAD_DIR . '/' . basename( $file ) );
				} elseif ( $info['mime'] == 'image/bmp' ) {
					$source         = imagecreatefrombmp( $fileTemp );
					$imageRotate    = imagerotate( $source, $arm_rotate, 0 );
					$rotated_width  = imagesx( $imageRotate );
					$rotated_height = imagesy( $imageRotate );
					$dx             = $rotated_width - $original_width;
					$dy             = $rotated_height - $original_height;
					$crop_x         = 0;
					$crop_y         = 0;
					$targ_x1        = $new_targ_x1;
					if ( $is_crop ) {
						$crop_x = $dx / 2 + $new_targ_x1;
						$crop_y = $dy / 2 + $new_targ_y1;
					}
					$new_image = imagecreatetruecolor( $targ_x2, $targ_y2 );
					if ( $is_crop ) {
						imagecopyresampled( $new_image, $imageRotate, 0, 0, $targ_x1, $targ_y1, $targ_x2, $targ_y2, $targ_x2, $targ_y2 );
						$upload = imagebmp( $new_image, $fileTemp );
					} else {
						$upload = imagebmp( $imageRotate, $fileTemp );
					}

					$original_info = getimagesize( $file1 );
					$original_w    = $original_info[0];
					$original_h    = $original_info[1];
					$original_img  = imagecreatefrombmp( $file1 );
					$thumb_img     = imagecreatetruecolor( $thumb_w, $thumb_h );
					imagecopyresampled( $thumb_img, $original_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $original_w, $original_h );
					imagebmp( $thumb_img, MEMBERSHIPLITE_UPLOAD_DIR . '/' . basename( $file ) );
				} else {
					$source         = imagecreatefromjpeg( $fileTemp );
					$imageRotate    = imagerotate( $source, $arm_rotate, 0 );
					$rotated_width  = imagesx( $imageRotate );
					$rotated_height = imagesy( $imageRotate );
					$dx             = $rotated_width - $original_width;
					$dy             = $rotated_height - $original_height;
					$crop_x         = 0;
					$crop_y         = 0;
					$targ_x1        = $new_targ_x1;
					if ( $is_crop ) {
						$crop_x = $dx / 2 + $new_targ_x1;
						$crop_y = $dy / 2 + $new_targ_y1;
					}
					$new_image = imagecreatetruecolor( $targ_x2, $targ_y2 );
					if ( $is_crop ) {
						imagecopyresampled( $new_image, $imageRotate, 0, 0, $targ_x1, $targ_y1, $targ_x2, $targ_y2, $targ_x2, $targ_y2 );
						$upload = imagejpeg( $new_image, $fileTemp );
					} else {
						$upload = imagejpeg( $imageRotate, $fileTemp );
					}

					$original_info = getimagesize( $file1 );
					$original_w    = $original_info[0];
					$original_h    = $original_info[1];
					$original_img  = imagecreatefromjpeg( $file1 );
					$thumb_img     = imagecreatetruecolor( $thumb_w, $thumb_h );
					imagecopyresampled( $thumb_img, $original_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $original_w, $original_h );
					imagejpeg( $thumb_img, MEMBERSHIPLITE_UPLOAD_DIR . '/' . basename( $file ) );
				}
			}

			if ( $type == 'profile' ) {
				if ( $update_meta != 'no' ) {
					update_user_meta( $user_id, 'avatar', $file );
					do_action( 'arm_after_upload_bp_avatar', $user_id );
				}
			} elseif ( $type == 'cover' ) {
				if ( $update_meta != 'no' ) {
					update_user_meta( $user_id, 'profile_cover', $file );
					do_action( 'arm_after_upload_bp_profile_cover', $user_id );
				}
			}

			echo esc_url( $file );
			die();
		}

		function path_only( $file ) {
			return trailingslashit( dirname( $file ) );
		}

		function arm_allowed_wp_mime_types() {
			$mimes = get_allowed_mime_types();
			ksort( $mimes );
			$mcount       = count( $mimes );
			$third        = ceil( $mcount / 3 );
			$c            = 0;
			$mimes['exe'] = '';
			unset( $mimes['exe'] );

			$allowed_mimes = array();

			foreach ( $mimes as $ext => $type ) {
				if ( strpos( $ext, '|' ) !== false ) {
					$exts = explode( '|', $ext );
					foreach ( $exts as $extension ) {
						if ( $extension != '' ) {
							array_push( $allowed_mimes, $extension );
						}
					}
				} else {
					array_push( $allowed_mimes, $ext );
				}
			}

			return $allowed_mimes;
		}

		function arm_upload_front() {
			global $ARMemberLite;
			$ARMemberLite->arm_check_user_cap( '', '1' ); //phpcs:ignore --Reason:Verifying nonce
			$arm_lite_upload_dir = MEMBERSHIPLITE_UPLOAD_DIR . '/';
			$arm_lite_upload_url = MEMBERSHIPLITE_UPLOAD_URL . '/';

			$file_name = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : false ); //phpcs:ignore
			$response  = '';
			if ( $file_name ) {
				$server_content_length = !empty( $_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : ''; //phpcs:ignore
				$content_length = (int) $server_content_length;
				$file_size_new  = number_format( ( $content_length / 1048576 ), 2, '.', '' );

				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $_FILES['armfileselect'] ); //phpcs:ignore
				if ( $arm_is_valid_file ) {
					$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
					$file_result          = $this->arm_upload_file_function( $_FILES['armfileselect']['tmp_name'], $arm_upload_file_path ); //phpcs:ignore

					$response = $arm_lite_upload_url . $file_name;
				}
				echo $response; //phpcs:ignore
				exit;
			} else {
				$files             = $_FILES['armfileselect']; //phpcs:ignore
				$file_size         = ( isset( $_REQUEST['allow_size'] ) ) ? intval($_REQUEST['allow_size']) : '';
				$file_name         = !empty( $_REQUEST['fname'] ) ? sanitize_text_field($_REQUEST['fname']) : '';
				$file_size_new     = intval($files['size']);
				$file_size_new     = number_format( $file_size_new / 1048576, 2, '.', '' );
				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $files );
				if ( $arm_is_valid_file ) {
					if ( ! empty( $file_size ) && ( $file_size_new > $file_size ) ) {
						$response = "<p class='error_upload_size'>" . esc_html__( 'File size not allowed', 'armember-membership' ) . '</p>';
					} else {
						$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
						$this->arm_upload_file_function( $files['tmp_name'], $arm_upload_file_path );
						$response = $arm_lite_upload_url . $file_name;
						echo "<p class='uploaded'>" . esc_html($arm_lite_upload_url) . esc_html($file_name) . '</p>'; //phpcs:ignore
					}
				}
			}
			exit;
		}

		function arm_upload_cover() {
			global $ARMemberLite;
			$ARMemberLite->arm_check_user_cap( '', '1' ); //phpcs:ignore --Reason:Verifying nonce
			$arm_lite_upload_dir = MEMBERSHIPLITE_UPLOAD_DIR . '/';
			$arm_lite_upload_url = MEMBERSHIPLITE_UPLOAD_URL . '/';

			$file_name = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : false ); //phpcs:ignore
			$response  = '';
			$userID    = get_current_user_id();
			if ( $file_name && ! empty( $userID ) && $userID != 0 ) {
				$arm_content_length = !empty( $_SERVER['CONTENT_LENGTH'] ) ? $_SERVER['CONTENT_LENGTH'] : ''; //phpcs:ignore
				$content_length = (int) $arm_content_length;
				$file_size_new  = number_format( ( $content_length / 1048576 ), 2, '.', '' );

				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $_FILES['armfileselect'] ); //phpcs:ignore
				if ( $arm_is_valid_file ) {

					$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
					$armfile_select_tmp_name = !empty( $_FILES['armfileselect']['tmp_name']) ? $_FILES['armfileselect']['tmp_name'] : ''; //phpcs:ignore
					$this->arm_upload_file_function( sanitize_text_field($armfile_select_tmp_name), $arm_upload_file_path );

					$response = $arm_lite_upload_url . $file_name;
					echo $response; //phpcs:ignore
					exit;
				}
			} else {
				$files         = !empty($_FILES['armfileselect']) ? $_FILES['armfileselect'] : ''; //phpcs:ignore
				$file_size     = ( isset( $_REQUEST['allow_size'] ) ) ? intval($_REQUEST['allow_size']) : '';
				$file_name     = !empty($_REQUEST['fname']) ? sanitize_text_field($_REQUEST['fname']) : '';
				$file_size_new = !empty( $_FILES['armfileselect']['size'] ) ? intval($_FILES['armfileselect']['size']) : '';
				$file_size_new = number_format( $file_size_new / 1048576, 2, '.', '' );

				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $files );
				if ( $arm_is_valid_file ) {
					if ( ! empty( $file_size ) && ( $file_size_new > $file_size ) ) {
						$response = "<p class='error_upload_size'>" . esc_html__( 'File size not allowed', 'armember-membership' ) . '</p>';
					} else {
						$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
						$this->arm_upload_file_function( $files['tmp_name'], $arm_upload_file_path );
						$response = $arm_lite_upload_url . $file_name;
						echo "<p class='uploaded'>" . esc_html($arm_lite_upload_url) . esc_html($file_name) . '</p>'; //phpcs:ignore
					}
				}
			}
			exit;
		}

		function arm_upload_profile() {

			global $ARMemberLite;
			$ARMemberLite->arm_check_user_cap( '', '1' ); //phpcs:ignore --Reason:Verifying nonce

			$arm_lite_upload_dir = MEMBERSHIPLITE_UPLOAD_DIR . '/';
			$arm_lite_upload_url = MEMBERSHIPLITE_UPLOAD_URL . '/';

			$file_name = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ?  $_SERVER['HTTP_X_FILENAME']  : false ); //phpcs:ignore
			$response  = '';
			$userID    = get_current_user_id();
			if ( $file_name && ! empty( $userID ) && $userID != 0 ) {
				// $oldCover = get_user_meta($userID, 'profile_cover', true);
				$armcontent_length = !empty( $_SERVER['CONTENT_LENGTH'] ) ? $_SERVER['CONTENT_LENGTH'] : ''; //phpcs:ignore
				$content_length = (int) $armcontent_length;
				$file_size_new  = number_format( ( $content_length / 1048576 ), 2, '.', '' );

				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $_FILES['armfileselect'] ); //phpcs:ignore
				if ( $arm_is_valid_file ) {
					$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
					$armfileselect_temp_nm = !empty( $_FILES['armfileselect']['tmp_name'] ) ? $_FILES['armfileselect']['tmp_name'] : ''; //phpcs:ignore
					$this->arm_upload_file_function( sanitize_text_field($armfileselect_temp_nm), $arm_upload_file_path );

					$response = $arm_lite_upload_url . $file_name;
					echo $response; //phpcs:ignore
					exit;
				}
			} else {
				$files         = $_FILES['armfileselect']; //phpcs:ignore
				$file_size     = ( isset( $_REQUEST['allow_size'] ) ) ? intval($_REQUEST['allow_size']) : '';
				$file_name     = !empty( $_REQUEST['fname'] ) ? sanitize_text_field($_REQUEST['fname']) : '';
				$file_size_new = intval($files['size']);
				$file_size_new = number_format( $file_size_new / 1048576, 2, '.', '' );

				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $files );
				if ( $arm_is_valid_file ) {
					if ( ! empty( $file_size ) && ( $file_size_new > $file_size ) ) {
						$response = "<p class='error_upload_size'>" . esc_html__( 'File size not allowed', 'armember-membership' ) . '</p>';
					} else {
						$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
						$this->arm_upload_file_function( $files['tmp_name'], $arm_upload_file_path );
						$response = $arm_lite_upload_url . $file_name;
						echo "<p class='uploaded'>" . esc_html($arm_lite_upload_url) . esc_html($file_name) . '</p>'; //phpcs:ignore
					}
				}
			}
			exit;
		}

		function arm_import_user() {
			global $ARMemberLite, $arm_capabilities_global;

			$ARMemberLite->arm_check_user_cap( $arm_capabilities_global['arm_manage_general_settings'], '1' ); //phpcs:ignore --Reason:Verifying nonce
			$arm_lite_upload_dir = MEMBERSHIPLITE_UPLOAD_DIR . '/';
			$arm_lite_upload_url = MEMBERSHIPLITE_UPLOAD_URL . '/';
			$file_name           = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : false ); //phpcs:ignore
			$response            = '';
			$userID              = get_current_user_id();
			if ( $file_name && ! empty( $userID ) && $userID != 0 ) {
				$file_size_new = !empty($_FILES['armfileselect']['size']) ? intval($_FILES['armfileselect']['size']) : '';
				$file_size_new = number_format( $file_size_new / 1048576, 2, '.', '' );

				add_filter( 'upload_mimes', array( $this, 'arm_allow_mime_type' ), 1 );

				$arm_is_valid_file = $this->arm_check_valid_file_ext_data( $file_name, $file_size_new, $_FILES['armfileselect'] ); // phpcs:ignore
				if ( $arm_is_valid_file ) {
					$arm_upload_file_path = $arm_lite_upload_dir . $file_name;
					$arm_file_select_temp_name = !empty($_FILES['armfileselect']['tmp_name']) ? $_FILES['armfileselect']['tmp_name'] : ''; //phpcs:ignore
					$this->arm_upload_file_function( $arm_file_select_temp_name, $arm_upload_file_path );
					$response = $arm_lite_upload_url . $file_name;
					echo $response; //phpcs:ignore
					exit;
				}
			}
			echo $response; //phpcs:ignore
			exit;
		}

		function arm_allow_mime_type( $mime_type_array ) {
			if ( is_array( $mime_type_array ) && ! array_key_exists( 'xml', $mime_type_array ) ) {
				$mime_type_array['xml'] = 'text/xml';
			}
			return $mime_type_array;
		}

		function arm_upload_file_function( $source, $destination ) {
			if ( empty( $source ) || empty( $destination ) ) {
				return false;
			}

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			WP_Filesystem();
			global $wp_filesystem;

			$file_content = $wp_filesystem->get_contents( $source );

			$result = $wp_filesystem->put_contents( $destination, $file_content, 0777 );

			return $result;
		}

		function arm_check_for_invalid_data( $file_content = '' ) {
			if ( '' == $file_content ) {
				return true;
			}

			$arm_valid_pattern = '/(\<\?(php))/';

			if ( preg_match( $arm_valid_pattern, $file_content ) ) {
				return false;
			}

			return true;
		}

		function arm_check_valid_file_ext_data( $file_name, $file_size, $arm_files_arr ) {
			$is_valid_file = 0;
			if ( $file_name && $file_size <= 20 ) {
				$arm_allowed_mimes = $this->arm_allowed_wp_mime_types();
				$denyExts          = array( 'php', 'php3', 'php4', 'php5', 'pl', 'py', 'jsp', 'asp', 'exe', 'cgi' );

				$checkext = explode( '.', $file_name );
				$ext      = strtolower( $checkext[ count( $checkext ) - 1 ] );

				$actual_file_name = $arm_files_arr['name'];
				$actual_checkext  = explode( '.', $actual_file_name );
				$actual_ext       = strtolower( $actual_checkext[ count( $actual_checkext ) - 1 ] );

				if ( ! in_array( $ext, $denyExts ) && in_array( $ext, $arm_allowed_mimes ) && ! in_array( $actual_ext, $denyExts ) && in_array( $actual_ext, $arm_allowed_mimes ) ) {
					if ( ! function_exists( 'WP_Filesystem' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
					}
					WP_Filesystem();
					global $wp_filesystem;
					$file_content = $wp_filesystem->get_contents( $arm_files_arr['tmp_name'] );

					$valid_data = $this->arm_check_for_invalid_data( $file_content );

					if ( ! $valid_data ) {
						echo "<p class='error_upload_size'>" . esc_html__( 'The file could not be uploaded due to security reason as it contains malicious code', 'armember-membership' ) . '</p>';
						header( 'HTTP/1.0 401 Unauthorized' );
						die;
					} else {
						$is_valid_file = 1;
					}
				}
			} else {
				echo "<p class='error_upload_size'>" . esc_html__( 'This file could not be processed due file limit exceeded.', 'armember-membership' ) . '</p>';
				die;
			}
			return $is_valid_file;
		}
		
		function arm_deactivate_feedback_popup() {
			$question_options                      = array();
			$question_options['list_data_options'] = array(
				'setup-difficult'  => esc_html__( 'Set up is too difficult', 'armember-membership' ),
				'docs-improvement' => esc_html__( 'Lack of documentation', 'armember-membership' ),
				'features'         => esc_html__( 'Not the features I wanted', 'armember-membership' ),
				'better-plugin'    => esc_html__( 'Found a better plugin', 'armember-membership' ),
				'incompatibility'  => esc_html__( 'Incompatible with theme or plugin', 'armember-membership' ),
				'bought-premium'   => esc_html__( 'I bought premium version of ARMember', 'armember-membership' ),
				'maintenance'      => esc_html__( 'Other', 'armember-membership' ),
			);

			$html  = '<div class="armlite-deactivate-form-head"><strong>' . esc_html__( 'ARMember Lite - Sorry to see you go', 'armember-membership' ) . '</strong></div>';
			$html .= '<div class="armlite-deactivate-form-body">';

			if ( is_array( $question_options['list_data_options'] ) ) {
				$html .= '<div class="armlite-deactivate-options">';
				$html .= '<p><strong>' . esc_html( esc_html__( 'Before you deactivate the ARMember Lite plugin, would you quickly give us your reason for doing so?', 'armember-membership' ) ) . '</strong></p><p>';

				foreach ( $question_options['list_data_options'] as $key => $option ) {
					$html .= '<input type="radio" name="armlite-deactivate-reason" id="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '"> <label for="' . esc_attr( $key ) . '">' . esc_attr( $option ) . '</label><br>';
				}

				$html .= '</p><label id="armlite-deactivate-details-label" for="armlite-deactivate-reasons"><strong>' . esc_html( esc_html__( 'How could we improve ?', 'armember-membership' ) ) . '</strong></label><textarea name="armlite-deactivate-details" id="armlite-deactivate-details" rows="2" style="width:100%"></textarea>';

				$html .= '</div>';
			}
			$html .= '<hr/>';

			$html .= '</div>';
			$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . esc_html__( 'Submitting form', 'armember-membership' ) . '</p>';
			$html .= '<div class="armlite-deactivate-form-footer"><p>';
			$html .= '<label for="armlite_anonymous" title="'
				. esc_html__( 'If you UNCHECK this then your email address will be sent along with your feedback. This can be used by armlite to get back to you for more info or a solution.', 'armember-membership' )
				. '"><input type="checkbox" name="armlite-deactivate-tracking" id="armlite_anonymous"> ' . esc_html__( 'Send anonymous', 'armember-membership' ) . '</label><br>';
			$html .= '<a id="armlite-deactivate-submit-form" class="button button-primary" href="#"><span>'
				. esc_html__( 'Submit', 'armember-membership' )
				. '&nbsp;and&nbsp;'. esc_html__( 'Deactivate', 'armember-membership' ).'</span></a>';
			$html .= '</p></div>';
			?>
			<div class="armlite-deactivate-form-bg"></div>
			<style type="text/css">
				.armlite-deactivate-form-active .armlite-deactivate-form-bg {background: rgba( 0, 0, 0, .5 );position: fixed;top: 0;left: 0;width: 100%;height: 100%; z-index: 9;}
				.armlite-deactivate-form-wrapper {position: relative;z-index: 999;display: none; }
				.armlite-deactivate-form-active .armlite-deactivate-form-wrapper {display: inline-block;}
				.armlite-deactivate-form {display: none;}
				.armlite-deactivate-form-active .armlite-deactivate-form {position: absolute;bottom: 30px;left: 0;max-width: 500px;min-width: 360px;background: #fff;white-space: normal;}
				.armlite-deactivate-form-head {background: #00b2f0;color: #fff;padding: 8px 18px;}
				.armlite-deactivate-form-body {padding: 8px 18px 0;color: #444;}
				.armlite-deactivate-form-body label[for="armlite-remove-settings"] {font-weight: bold;}
				.deactivating-spinner {display: none;}
				.deactivating-spinner .spinner {float: none;margin: 4px 4px 0 18px;vertical-align: bottom;visibility: visible;}
				.armlite-deactivate-form-footer {padding: 0 18px 8px;}
				.armlite-deactivate-form-footer label[for="armlite_anonymous"] {visibility: hidden;}
				.armlite-deactivate-form-footer p {display: flex;align-items: center;justify-content: space-between;margin: 0;}
				<?php /* #armlite-deactivate-submit-form span {display: none;} */ ?>
				.armlite-deactivate-form.process-response .armlite-deactivate-form-body,.armlite-deactivate-form.process-response .armlite-deactivate-form-footer {position: relative;}
				.armlite-deactivate-form.process-response .armlite-deactivate-form-body:after,.armlite-deactivate-form.process-response .armlite-deactivate-form-footer:after {content: "";display: block;position: absolute;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba( 255, 255, 255, .5 );}
			</style>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					var armlite_deactivateURL = $("#armlite-deactivate-link-<?php echo esc_attr( 'armember-membership' ); ?>")
						armlite_formContainer = $('#armlite-deactivate-form-<?php echo esc_attr( 'armember-membership' ); ?>'),
						armlite_deactivated = true,
						armlite_detailsStrings = {
							'setup-difficult' : '<?php echo esc_html__( 'What was the dificult part?', 'armember-membership' ); ?>',
							'docs-improvement' : '<?php echo esc_html__( 'What can we describe more?', 'armember-membership' ); ?>',
							'features' : '<?php echo esc_html__( 'How could we improve?', 'armember-membership' ); ?>',
							'better-plugin' : '<?php echo esc_html__( 'Can you mention it?', 'armember-membership' ); ?>',
							'incompatibility' : '<?php echo esc_html__( 'With what plugin or theme is incompatible?', 'armember-membership' ); ?>',
							'bought-premium' : '<?php echo esc_html__( 'Please specify experience', 'armember-membership' ); ?>',
							'maintenance' : '<?php echo esc_html__( 'Please specify', 'armember-membership' ); ?>',
						};

					jQuery( armlite_deactivateURL).attr('onclick', "javascript:event.preventDefault();");
					jQuery( armlite_deactivateURL ).on("click", function(){

						function ARMLiteSubmitData(armlite_data, armlite_formContainer)
						{
							armlite_data['action']          = 'armlite_deactivate_plugin';
							armlite_data['security']        = '<?php echo esc_attr(wp_create_nonce( 'armlite_deactivate_plugin' )); ?>'; 
							armlite_data['_wpnonce']        = '<?php echo esc_attr(wp_create_nonce( 'arm_wp_nonce' )); ?>';
							armlite_data['dataType']        = 'json';
							armlite_formContainer.addClass( 'process-response' );
							armlite_formContainer.find(".deactivating-spinner").show();
							jQuery.post(ajaxurl,armlite_data,function(response)
							{
									window.location.href = armlite_url;
							});
						}

						var armlite_url = armlite_deactivateURL.attr( 'href' );
						jQuery('body').toggleClass('armlite-deactivate-form-active');
						armlite_formContainer.show({complete: function(){
							var offset = armlite_formContainer.offset();
							if( offset.top < 50) {
								$(this).parent().css('top', (50 - offset.top) + 'px')
							}
							jQuery('html,body').animate({ scrollTop: Math.max(0, offset.top - 50) });
						}});
						armlite_formContainer.html( '<?php echo $html; //phpcs:ignore ?>');
						armlite_formContainer.on( 'change', 'input[type=radio]', function()
						{
							var armlite_detailsLabel = armlite_formContainer.find( '#armlite-deactivate-details-label strong' );
							var armlite_anonymousLabel = armlite_formContainer.find( 'label[for="armlite_anonymous"]' )[0];
							var armlite_submitSpan = armlite_formContainer.find( '#armlite-deactivate-submit-form span' )[0];
							var armlite_value = armlite_formContainer.find( 'input[name="armlite-deactivate-reason"]:checked' ).val();

							armlite_detailsLabel.text( armlite_detailsStrings[ armlite_value ] );
							armlite_anonymousLabel.style.visibility = "visible";
							armlite_submitSpan.style.display = "inline-block";
							if(armlite_deactivated)
							{
								armlite_deactivated = false;
								jQuery('#armlite-deactivate-submit-form').removeAttr("disabled");
								armlite_formContainer.off('click', '#armlite-deactivate-submit-form');
								armlite_formContainer.on('click', '#armlite-deactivate-submit-form', function(e){
									e.preventDefault();
									var data = {
										armlite_reason: armlite_formContainer.find('input[name="armlite-deactivate-reason"]:checked').val(),
										armlite_details: armlite_formContainer.find('#armlite-deactivate-details').val(),
										armlite_anonymous: armlite_formContainer.find('#armlite_anonymous:checked').length,
									};
									ARMLiteSubmitData(data, armlite_formContainer);
								});
							}
						});
						armlite_formContainer.on('click', '#armlite-deactivate-submit-form', function(e){
							e.preventDefault();
							ARMLiteSubmitData({}, armlite_formContainer);
						});
						$('.armlite-deactivate-form-bg').on('click',function(){
							armlite_formContainer.fadeOut();
							$('body').removeClass('armlite-deactivate-form-active');
						});
					});
				});
			</script>
			<?php
		}

		function armite_deactivate_plugin_func() {
			global $ARMemberLite;
			check_ajax_referer( 'armlite_deactivate_plugin', 'security' );
			$ARMemberLite->arm_check_user_cap('',1); //phpcs:ignore --Reason:Verifying nonce
			if ( ! empty( $_POST['armlite_reason'] ) && isset( $_POST['armlite_details'] ) ) {
				$args                     = array();
				$args['armlite_reason'] = isset($_POST['armlite_reason']) ? sanitize_text_field( $_POST['armlite_reason'] ) : '';
				$args['armlite_details'] = isset($_POST['armlite_details']) ? sanitize_textarea_field( $_POST['armlite_details'] ) : ''; 
				$armlite_anonymous = $args['armlite_anonymous'] = isset($_POST['armlite_anonymous']) ? sanitize_text_field( $_POST['armlite_anonymous'] ) : 0;
				$args['action'] = isset($_POST['action']) ? sanitize_text_field( $_POST['action'] ) : '';
				$args['security'] = isset($_POST['security']) ? sanitize_text_field( $_POST['security'] ) : '';
				$args['dataType'] = isset($_POST['dataType']) ? sanitize_text_field( $_POST['dataType'] ) : '';
				$args['armlite_site_url'] = ARMLITE_HOME_URL;
				if ( !$armlite_anonymous ) {
					$args['arm_lite_site_email'] = get_option( 'admin_email' );
				}
				$url = 'https://www.armemberplugin.com/armember_addons/armlite_feedback.php';

				$response = wp_remote_post(
					$url,
					array(
						'timeout' => 500,
						'body'    => $args,
					)
				);
			}
			echo json_encode(
				array(
					'status' => 'OK',
				)
			);
			die();
		}

	}
}
global $arm_lite_members_activity;
$arm_lite_members_activity = new ARM_members_activity_Lite();
