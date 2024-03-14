<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://fresh-d.biz/wocommerce-remove-background.html
 * @since      1.0.0
 *
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/admin
 */


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/admin
 * @author     Fresh-d <info@fresh-d.biz>
 */
class Remove_BG_Admin
{
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name = 'wc-remove-bg';
	
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version = '1.0.0';
	
	private $processed_images = 0;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
	{
		
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wc-remove-bg-adminstyles', plugin_dir_url( __FILE__ ) . 'css/style.css');
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-remove-bg-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'wp-color-picker' );
		
	}
	
	public function add_menu_remove_bg()
	{
		add_menu_page( __( 'Remove BG', 'wc-remove-bg' ), __( 'Remove BG', 'wc-remove-bg' ), 'edit_pages', 'wc_remove_bg', array(
			$this,
			'show_remove_bg'
		), 'dashicons-format-image' );
	}
	
	public function show_remove_bg()
	{
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/plugin-wc-remove-bg-display-admin.php';
	}
	
	public function Remove_BG_processing()
	{
		global $wpdb;

		if(  wp_verify_nonce( sanitize_text_field($_POST['_nonce']), 'update-options' ) && user_can(intval($_POST['schk']), 'edit_pages') ) {
		 
			$process = sanitize_text_field($_POST['process']);
			$sql     = "SELECT * FROM `" . $wpdb->prefix . "wc_remove_bg`";
			$res     = $wpdb->get_results( $sql );
			if ( $process == 'start_queue' || $process == 'save' ) {
				update_option( 'RemoveBG_ApiKey', sanitize_text_field(trim($_POST['RemoveBG_ApiKey'])) );
				update_option( 'RemoveBG_products', sanitize_text_field($_POST['RemoveBG_products']) );
				update_option( 'RemoveBG_products_IDs', sanitize_text_field($_POST['RemoveBG_products_IDs']) );
				update_option( 'RemoveBG_thumbnail', intval($_POST['RemoveBG_thumbnail']) );
				update_option( 'RemoveBG_gallery', intval($_POST['RemoveBG_gallery']) );
				update_option( 'RemoveBG_Background', sanitize_text_field($_POST['RemoveBG_Background']) );
				update_option( 'RemoveBG_Background_Color', sanitize_hex_color($_POST['RemoveBG_Background_Color']) );
				update_option( 'RemoveBG_Preserve_Resize', sanitize_text_field($_POST['RemoveBG_Preserve_Resize']) );
				update_option( 'RemoveBG_Include_Processed', intval($_POST['RemoveBG_Include_Processed']) );
			}
			if ( empty( get_option( 'RemoveBG_ApiKey' ) ) ) {
				echo json_encode( array(
					'hasErrors' => true,
					'error_msg' => __( 'Missing API Key', 'wc-remove-bg' )
				) );
				$this->start_log();
				$this->add_log( null, [ 'errors_msg' => [ [ 'title' => __( 'Missing API Key', 'wc-remove-bg' ) ] ] ] );
			} elseif ( $process == 'start_queue' ) {
				//start
				$wpdb->insert( $wpdb->prefix . "wc_remove_bg",
					array(
						'date_start' => date( 'Y-m-d H:i:s' ),
						'status'     => 's'
					),
					array(
						'%s',
						'%s',
					)
				);
				$remove_bg_id = $wpdb->insert_id;
				
				try {
					$img_thumbnail = ( 1 == get_option( 'RemoveBG_thumbnail' ) ) ? true : false;
					$img_gallery   = ( 1 == get_option( 'RemoveBG_gallery' ) ) ? true : false;
					$this->start_log();
					if ( 'all' == get_option( 'RemoveBG_products' ) ) {
						$posts      = get_posts( array(
							'post_type'   => 'product',
							'fields'      => 'ids',
							'numberposts' => - 1
						) );
						$postImages = [];
						foreach ( $posts as $post ) {
							$postImages = $this->getListOfPostPictures( $post, $postImages, $img_thumbnail, $img_gallery );
						}
						if ( ! empty( $postImages ) ) {
							
							$background_image = '';
							if ( ! empty( sanitize_text_field($_POST['RemoveBG_Background']) ) ) {
								if ( sanitize_text_field($_POST['RemoveBG_Background']) == 'image' ) {
									require_once ABSPATH . 'wp-admin/includes/image.php';
									require_once ABSPATH . 'wp-admin/includes/file.php';
									require_once ABSPATH . 'wp-admin/includes/media.php';
									wp_delete_attachment( get_option( 'RemoveBG_Background_Image' ), true );
									$attachment_id = media_handle_upload( 'RemoveBG_file', 0 );
									update_option( 'RemoveBG_Background_Image', $attachment_id );
									$background_image = wp_get_attachment_image_url( $attachment_id, 'medium' );
								}
							}
							
							echo json_encode( array(
								'hasErrors'        => false,
								'processing'       => false,
								'remove_bg'        => $remove_bg_id,
								'data'             => json_encode( $postImages ),
								'background_image' => $background_image,
								'status'           => 'r',
							) );
							
						} else {
							echo json_encode( array(
								'hasErrors'  => true,
								'processing' => false,
								'status'     => 'e',
								'error_msg'  => __( 'No images to process', 'wc-remove-bg' )
							) );
							$wpdb->update( $wpdb->prefix . "wc_remove_bg",
								array(
									'date_end' => date( 'Y-m-d H:i:s' ),
									'status'   => 'e'
								),
								array(
									'ID' => $remove_bg_id
								),
								array(
									'%s',
									'%s'
								),
								array(
									'%d'
								)
							);
							$this->add_log( null, [ 'errors_msg' => [ [ 'title' => __( 'No images to process', 'wc-remove-bg' ) ] ] ] );
							if ( intval($_POST['RemoveBG_LastImage']) != 1 ) {
								$this->end_log();
							}
							$this->deleteBackgroundImage();
						}
					} else {
						$IDs        = explode( ',', preg_replace( '/\s+/', '', get_option( 'RemoveBG_products_IDs' ) ) );
						$postImages = array();
						foreach ( $IDs as $ID ) {
							if ( is_numeric( $ID ) ) {
								$postImages = $this->getListOfPostPictures( $ID, $postImages, $img_thumbnail, $img_gallery );
							} else {
								$mIDs = explode( '-', $ID );
								for ( $i = (int) $mIDs[0]; $i <= (int) $mIDs[1]; $i ++ ) {
									$postImages = $this->getListOfPostPictures( $i, $postImages, $img_thumbnail, $img_gallery );
								}
							}
						}
						if ( ! empty( $postImages ) ) {
							
							$background_image = '';
							if ( ! empty( sanitize_text_field($_POST['RemoveBG_Background']) ) ) {
								if ( sanitize_text_field($_POST['RemoveBG_Background']) == 'image' ) {
									require_once ABSPATH . 'wp-admin/includes/image.php';
									require_once ABSPATH . 'wp-admin/includes/file.php';
									require_once ABSPATH . 'wp-admin/includes/media.php';
									wp_delete_attachment( get_option( 'RemoveBG_Background_Image' ), true );
									$attachment_id = media_handle_upload( 'RemoveBG_file', 0 );
									update_option( 'RemoveBG_Background_Image', $attachment_id );
									$background_image = wp_get_attachment_image_url( $attachment_id, 'medium' );
								}
							}
							
							echo json_encode( array(
								'hasErrors'        => false,
								'processing'       => false,
								'remove_bg'        => $remove_bg_id,
								'data'             => json_encode( $postImages ),
								'background_image' => $background_image,
								'status'           => 'r',
							) );
						} else {
							echo json_encode( array(
								'hasErrors'  => true,
								'processing' => false,
								'status'     => 'e',
								'error_msg'  => __( 'No images to process', 'wc-remove-bg' )
							) );
							$wpdb->update( $wpdb->prefix . "wc_remove_bg",
								array(
									'date_end' => date( 'Y-m-d H:i:s' ),
									'status'   => 'e'
								),
								array(
									'ID' => $remove_bg_id
								),
								array(
									'%s',
									'%s'
								),
								array(
									'%d'
								)
							);
							$this->add_log( null, [ 'errors_msg' => [ [ 'title' => __( 'No images to process', 'wc-remove-bg' ) ] ] ] );
							if ( intval($_POST['RemoveBG_LastImage']) != 1 ) {
								$this->end_log();
							}
							$this->deleteBackgroundImage();
						}
					}
					
				} catch ( Exception $e ) {
					
					$wpdb->update( $wpdb->prefix . "wc_remove_bg",
						array(
							'date_end'  => date( 'Y-m-d H:i:s' ),
							'status'    => 'e',
							'error_msg' => $e->getMessage()
						),
						array(
							'ID' => $remove_bg_id
						),
						array(
							'%s',
							'%s',
							'%s'
						),
						array(
							'%d'
						)
					);
					echo json_encode( array(
						'hasErrors'  => true,
						'processing' => false,
						'status'     => 'e',
						'error_msg'  => __( 'Background removal error: ', 'wc-remove-bg' ) . $e->getMessage()
					) );
					
					$this->deleteBackgroundImage();
					
					return false;
				}
			} elseif ( $process == 'processing_queue' ) {
				if ( ! empty( intval($_POST['RemoveBG_NextPost'] ) ) ) {
					$post_id       = get_post( intval($_POST['RemoveBG_NextPost']) );
					$image_id      = trim( intval($_POST['RemoveBG_NextImage']) );
					$img_thumbnail = trim( sanitize_text_field($_POST['RemoveBG_NextImageThumb']) );
					$img_gallery   = trim( sanitize_text_field($_POST['RemoveBG_NextImageGallery']) );
					$remove_bg_id  = trim( intval($_POST['RemoveBG_ID']) );
					if ( ! empty( $post_id ) and ! empty( $image_id ) ) {
						try {
							$Remove_BG_api_key = get_option( 'RemoveBG_ApiKey' );
							update_option( 'RemoveBG_Include_Processed', intval($_POST['RemoveBG_Include_Processed']) );
							$wpdb->update( $wpdb->prefix . "wc_remove_bg",
								array(
									'date_end' => date( 'Y-m-d H:i:s' ),
									'status'   => 'r'
								),
								array(
									'ID' => $remove_bg_id
								),
								array(
									'%s',
									'%s'
								),
								array(
									'%d'
								)
							);
							$image_processing = $this->image_processing( $post_id->ID, $image_id, $Remove_BG_api_key, $img_thumbnail, $img_gallery, $remove_bg_id );
							if ( $image_processing === true ) {
								$this->process_log();
								echo json_encode( array(
									'hasErrors'   => false,
									'processing'  => false,
									'status'      => 'r',
									'success_msg' => sprintf( _n( 'Processed %d images of %d', 'Processed %d images of %d', intval($_POST['RemoveBG_CountProcessImage']), intval($_POST['RemoveBG_AllCountImage']) ), number_format_i18n( intval($_POST['RemoveBG_CountProcessImage']) ), number_format_i18n( intval($_POST['RemoveBG_AllCountImage']) ) )
								) );
							} else {
								echo json_encode( array(
									'hasErrors'  => true,
									'processing' => false,
									'status'     => 'e',
									'error_msg'  => __( 'Error: ' . $image_processing, 'wc-remove-bg' )
								) );
								$wpdb->update( $wpdb->prefix . "wc_remove_bg",
									array(
										'date_end' => date( 'Y-m-d H:i:s' ),
										'status'   => 'e'
									),
									array(
										'ID' => $remove_bg_id
									),
									array(
										'%s',
										'%s'
									),
									array(
										'%d'
									)
								);
								if ( intval($_POST['RemoveBG_LastImage']) != 1 ) {
									$this->end_log();
								}
							}
						} catch ( Exception $e ) {
							
							$wpdb->update( $wpdb->prefix . "wc_remove_bg",
								array(
									'date_end'  => date( 'Y-m-d H:i:s' ),
									'status'    => 'e',
									'error_msg' => $e->getMessage()
								),
								array(
									'ID' => $remove_bg_id
								),
								array(
									'%s',
									'%s',
									'%s'
								),
								array(
									'%d'
								)
							);
							echo json_encode( array(
								'hasErrors'  => true,
								'processing' => false,
								'status'     => 'e',
								'error_msg'  => __( 'Background removal error: ', 'wc-remove-bg' ) . $e->getMessage()
							) );
							$this->deleteBackgroundImage();
							if ( intval($_POST['RemoveBG_LastImage']) != 1 ) {
								$this->end_log();
							}
							
							return false;
						}
					}
				}
				if ( ! empty( intval($_POST['RemoveBG_LastImage']) ) ) {
					if ( intval($_POST['RemoveBG_LastImage']) == 1 ) {
						$this->end_log();
						$wpdb->update( $wpdb->prefix . "wc_remove_bg",
							array(
								'date_end' => date( 'Y-m-d H:i:s' ),
								'status'   => 'f',
							),
							array(
								'ID' => $remove_bg_id
							),
							array(
								'%s',
								'%s'
							),
							array(
								'%d'
							)
						);
						$this->deleteBackgroundImage();
					}
				}
			}
		}else{
			echo json_encode( array(
				'hasErrors'  => true,
				'processing' => false,
				'status'     => 'e',
				'error_msg'  => __( 'Security check failed', 'wc-remove-bg' )
			) );
			
		}
		wp_die();
	}
	
	public function deleteBackgroundImage(){
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		wp_delete_attachment( get_option('RemoveBG_Background_Image'), true );
	}
	
	public function getListOfPostPictures( $post, $postImages, $img_thumbnail, $img_gallery )
	{
		if ( $img_thumbnail ) {
			if ( $img_id = get_post_thumbnail_id( $post ) ) {
				if(file_exists(get_attached_file( $img_id )) ){
					$postImages[] = array( 'id' => $post, 'image' => $img_id, 'thumb' => 'yes', 'gallery' => 'no' );
				}
			}
		}
		if ( $img_gallery ) {
			$galleryIds = get_post_meta( $post, '_product_image_gallery' );
			if ( isset( $galleryIds[0] ) && ! empty( $galleryIds[0] ) ) {
				$imgArr = explode( ',', $galleryIds[0] );
				if ( count( $imgArr ) > 0 ) {
					foreach ( $imgArr as $img_id ) {
						if(file_exists(get_attached_file( $img_id )) ){
							$postImages[] = array( 'id' => $post, 'image' => $img_id, 'thumb' => 'no', 'gallery' => 'yes' );
						}
					}
				}
			}
		}
		
		return $postImages;
	}
	
	public function wc_Remove_BG_admin_notice()
	{
		if ( $_GET['page'] == 'wc_remove_bg' ) {
			include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/plugin-wc-remove-bg-display-admin-notices.php';
		}
	}
	
	protected function restore_processing( $post_id, $img_id, $old_img_id )
	{
		if ( $img_current_id = get_post_thumbnail_id( $post_id ) ) {
			if ( $img_id == $img_current_id ) {
				set_post_thumbnail( $post_id, $old_img_id );
				if(wp_delete_attachment( $img_id ) == false){
					echo json_encode( array(
						'hasErrors' => true,
						'msg'       => __( 'File not deleted. Permission issue or file existence', 'wc-remove-bg' )
					) );
					wp_die();
				}
			}
		}
		$galleryIds = get_post_meta( $post_id, '_product_image_gallery' );
		if ( isset( $galleryIds[0] ) && ! empty( $galleryIds[0] ) ) {
			$imgArr = explode( ',', $galleryIds[0] );
			if ( count( $imgArr ) ) {
				foreach ( $imgArr as $key => $img_current_id ) {
					if ( $img_current_id == $img_id ) {
						$imgArr[ $key ] = $old_img_id;
						if(wp_delete_attachment( $img_id ) == false){
							echo json_encode( array(
								'hasErrors' => true,
								'msg'       => __( 'File not deleted. Permission issue or file existence', 'wc-remove-bg' )
							) );
							wp_die();
						}
					}
				}
			}
			if ( count( $imgArr ) ) {
				update_post_meta( $post_id, '_product_image_gallery', implode( ',', $imgArr ) );
			}
		}
	}
	
	protected function image_processing( $post_id, $image_id, $Remove_BG_api_key, $img_thumbnail, $img_gallery, $remove_bg_id )
	{
		$image_info = $this->backupImages( $image_id );
		if(is_array($image_info)){
			if ( get_option( 'RemoveBG_Include_Processed' ) or ( $image_info['insert'] == true ) ) {
				$new_img_id = $this->remove_bg( $image_id, $post_id, $image_info['full_path'], $image_info['insert'], $Remove_BG_api_key );
				if ( is_array( $new_img_id ) == false and $new_img_id > 0 ) {
					if ( $img_thumbnail == 'yes' ) {
						set_post_thumbnail( $post_id, $new_img_id );
					}
					if ( $img_gallery == 'yes' ) {
						$galleryIds = get_post_meta( $post_id, '_product_image_gallery' );
						if ( isset( $galleryIds[0] ) && ! empty( $galleryIds[0] ) ) {
							$imgArr = explode( ',', $galleryIds[0] );
							if ( count( $imgArr ) ) {
								$attach_id = $new_img_id;
								$newImgArr = array_map( function ( $v ) use ( $image_id, $attach_id ) {
									return $v == $image_id ? $attach_id : $v;
								}, $imgArr );
								update_post_meta( $post_id, '_product_image_gallery', implode( ',', $newImgArr ) );
							}
						}
					}
				} else {
					if ( $this->error_handling( $new_img_id, $remove_bg_id ) ) {
					} else {
						$this->add_log( $post_id, $new_img_id );
					}
					$errors_msg = '';
					if ( ! empty( $new_img_id['errors_msg'] ) ) {
						foreach ( $new_img_id['errors_msg'] as $err ) {
							$errors_msg .= $err['title'] . ' ';
						}
					}
					return $errors_msg;
				}
			}else{
				$this->process_skipped_log();
			}
		}else{
			return $image_info;
		}
		
		return true;
	}
	
	protected function backupImages( $img_id )
	{
		global $wpdb;
		
		try{
			$full_path = get_attached_file( $img_id );
			$file = pathinfo( $full_path );
			
			$sql = "SELECT * FROM `" . $wpdb->prefix . "wc_remove_bg_backup` WHERE attach_id='$img_id'";
			$res = $wpdb->get_results( $sql );
			if ( empty( $res[0] ) ) {
				
				$posNobg = strripos( $file['filename'], '_nobg' );
				if ( $posNobg === false ) {
					$newFilePath = $file['dirname'] . '/' . $file['filename'] . rand( 11111, 99999 ) . '_nobg.png';
				} else {
					$str         = substr( $file['filename'], 0, - 10 );
					$newFilePath = $file['dirname'] . '/' .$str . rand( 11111, 99999 ) . '_nobg.png';
				}
				
				if(!$fp = fopen( $newFilePath, "wb" ) ) { return __( 'Check uploads folder permissions', 'wc-remove-bg' ); };
			
				if(fwrite( $fp, file_get_contents( $full_path ) ) === FALSE){
					return __( 'Check uploads folder permissions', 'wc-remove-bg' );
				}
				if(fclose( $fp ) === FALSE){
					return __( 'Check available disk space', 'wc-remove-bg' );	
				}
				
				return [ 'insert' => true, 'full_path' => $newFilePath ];
			}
			
			return [ 'img_id' => $img_id, 'insert' => false, 'full_path' => $full_path ];

		} catch (Exception $e) {
    		return $e->getMessage();
		}
	}
	
	protected function remove_bg( $old_img, $post_id, $filePATHFull, $newProccessRemoveBg, $Remove_BG_api_key )
	{
		global $wpdb;
		try{
			
			$wp_upload_dir = wp_upload_dir();
			
			$fileData = pathinfo( $filePATHFull );

			$tmp_dir  = $wp_upload_dir['basedir'] . '/remove-bg-tmp-dir';
			if ( ! file_exists( $tmp_dir ) ) {
				mkdir( $tmp_dir, 0755, true );
			}
			$tmp_file_name = '/f' . time() . '.' . $fileData['extension'];
			$tmp_img       = $tmp_dir . $tmp_file_name;

			file_put_contents( $tmp_img, file_get_contents( $filePATHFull ) );
			if(file_exists($tmp_img)  === FALSE or filesize($tmp_img) == 0){
				$er['errors_msg'][0]['title'] = __( 'Check available disk space or check uploads (or remove-bg-tmp-dir) folder permissions', 'wc-remove-bg' );
				return $er;
			}
			
			$body = [];
			$body['image_file_b64'] = 'data:image/png;base64,'.base64_encode(file_get_contents($tmp_img));
			if ( get_option( 'RemoveBG_Background' ) == 'color' ) {
				$body['bg_color'] = get_option( 'RemoveBG_Background_Color' );
			}
			if ( get_option( 'RemoveBG_Background' ) == 'image' ) {
				$filePATHFull_bg       = wp_get_attachment_url( get_option('RemoveBG_Background_Image') );
				$body['bg_image_url'] = $filePATHFull_bg;
			}
            $body['size'] = get_option('RemoveBG_Preserve_Resize');
			
			$argRemotePost = [
				'body' => $body,
				'headers' => [ 'X-Api-Key' => $Remove_BG_api_key ]
			];
			
			$response = wp_remote_post(  'https://api.remove.bg/v1.0/removebg', $argRemotePost );
			
			unlink( $tmp_img );
			
			if ( $this->isJSON($response['body']) ) {
			    $errorResponseArray = json_decode($response['body']);
			    $errorResponseSelected = [];
			    if(!empty($errorResponseArray->errors)){
			        foreach ($errorResponseArray->errors as $error){
                        $errorResponseSelected[] = ['title' => $error->title];
                    }
                }
                
				return array(
					'errors'     => true,
					'code'       => 400,
					'img_id'     => $old_img,
					'errors_msg' => $errorResponseSelected
				);
			}
			
			if(!$fp = fopen( $filePATHFull, "wb" ) ) { 
				$er['errors_msg'][0]['title'] = __( 'Check uploads folder permissions', 'wc-remove-bg' );
				return $er;
			}
		
			if(fwrite( $fp, $response['body'] ) === FALSE){
				$er['errors_msg'][0]['title'] = __( 'Check uploads folder permissions', 'wc-remove-bg' );
				return $er;
			}
			if(fclose( $fp ) === FALSE){
				$er['errors_msg'][0]['title'] = __( 'Check available disk space', 'wc-remove-bg' );
				return $er;
			}
			
			if ( $newProccessRemoveBg ) {
				$attachment = array(
					'guid'           => $filePATHFull,
					'post_mime_type' => mime_content_type( $filePATHFull ),
					'post_title'     => pathinfo( $filePATHFull )['filename'],
					'post_content'   => '',
					'post_status'    => 'inherit'
				);
				
				$attach_id = wp_insert_attachment( $attachment, $filePATHFull );
				
				$wpdb->insert( $wpdb->prefix . "wc_remove_bg_backup",
					array(
						'attach_id'     => $attach_id,
						'old_attach_id' => $old_img,
						'backup_date'   => date( 'Y-m-d H:i:s' ),
						'post_id'       => $post_id
					),
					array(
						'%d',
						'%d',
						'%s',
						'%d'
					)
				);
			} else {
				$attach_id = $old_img;
			}
			$metadata = wp_generate_attachment_metadata( $attach_id, $filePATHFull );
			wp_update_attachment_metadata( $attach_id, $metadata );
			
			$this->processed_images ++;
			
			return $attach_id;

		} catch (Exception $e) {
			$er['errors_msg'][0]['title'] = $e->getMessage();
    		return $er;
		}
	}
	
    function isJSON($string) {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }
	
	public function Remove_BG_Restore_Backup()
	{
		global $wpdb;
		if(  wp_verify_nonce( sanitize_text_field($_POST['_nonce']), 'update-options' )  && user_can(intval($_POST['schk']), 'edit_pages') ) {
			$sql = "SELECT * FROM `" . $wpdb->prefix . "wc_remove_bg` WHERE status='s' or status='r' ";
			$res = $wpdb->get_results( $sql );
			if ( empty( $res[0] ) ) {
				//buckup list
				$sql_backup = "SELECT * FROM `" . $wpdb->prefix . "wc_remove_bg_backup`";
				$res_backup = $wpdb->get_results( $sql_backup );
				if ( ! empty( $res_backup ) ) {
					foreach ( $res_backup as $item ) {
						$this->restore_processing( $item->post_id, $item->attach_id, $item->old_attach_id );
					}
					//clear table
					$sql = "TRUNCATE `" . $wpdb->prefix . "wc_remove_bg_backup`";
					$wpdb->query( $sql );
					echo json_encode( array(
						'hasErrors' => false,
						'msg'       => __( 'Pictures restored!', 'wc-remove-bg' )
					) );
				} else {
					echo json_encode( array(
						'hasErrors' => true,
						'msg'       => __( 'The recovery list is empty!', 'wc-remove-bg' )
					) );
				}
			} else {
				echo json_encode( array(
					'hasErrors' => true,
					'msg'       => __( 'Restore is not possible until processing is complete', 'wc-remove-bg' )
				) );
			}
		}else{
			echo json_encode( array(
				'hasErrors'  => true,
				'processing' => false,
				'status'     => 'e',
				'error_msg'  => __( 'One-time password is not correct. Refresh the page and try again.', 'wc-remove-bg' )
			) );
		}
		wp_die();
	}
	
	public function Delete_backup()
	{
		global $wpdb;
		if(  wp_verify_nonce( sanitize_text_field($_POST['_nonce']), 'update-options' )  && user_can(intval($_POST['schk']), 'edit_pages')) {
			try {
				$sql = "SELECT * FROM " . $wpdb->prefix . "wc_remove_bg_backup";
				$res = $wpdb->get_results( $sql );
				if ( ! empty( $res ) ) {
					foreach ( $res as $item ) {
						if ( wp_delete_attachment( $item->old_attach_id, true ) == false ) {
							echo json_encode( array(
								'hasErrors' => true,
								'msg'       => __( 'Write error. Check uploads folder permissions', 'wc-remove-bg' )
							) );
							wp_die();
						}
					}
					//clear table
					$sql = "TRUNCATE `" . $wpdb->prefix . "wc_remove_bg_backup`";
					$wpdb->query( $sql );
					echo json_encode( array(
						'hasErrors' => false,
						'msg'       => __( 'Successfully deleted', 'wc-remove-bg' )
					) );
				} else {
					echo json_encode( array(
						'hasErrors' => true,
						'msg'       => __( 'No pictures to delete!', 'wc-remove-bg' )
					) );
				}
				
			} catch ( Exception $e ) {
				echo json_encode( array(
					'hasErrors' => true,
					'msg'       => __( 'Delete error: ', 'wc-remove-bg' ) . $e->getMessage()
				) );
			}
		}else{
			echo json_encode( array(
				'hasErrors'  => true,
				'processing' => false,
				'status'     => 'e',
				'error_msg'  => __( 'One-time password is not correct. Refresh the page and try again.', 'wc-remove-bg' )
			) );
		}
		wp_die();
	}
		
	public function User_Aborted()
	{
		global $wpdb;
		if(  wp_verify_nonce( sanitize_text_field($_POST['_nonce']), 'update-options' )  && user_can(intval($_POST['schk']), 'edit_pages')) {
			$remove_bg_id = trim( intval($_POST['RemoveBG_ID']) );
			try {
				$wpdb->update( $wpdb->prefix . "wc_remove_bg",
					array(
						'date_end'  => date( 'Y-m-d H:i:s' ),
						'status'    => 'f',
						'error_msg' => __( 'User aborted', 'wc-remove-bg' )
					),
					array(
						'ID' => $remove_bg_id
					),
					array(
						'%s',
						'%s',
						'%s'
					),
					array(
						'%d'
					)
				);
				echo json_encode( array(
					'hasErrors' => false,
					'msg'       => __( 'Process aborted by user successfully', 'wc-remove-bg' )
				) );
				
			} catch ( Exception $e ) {
				echo json_encode( array(
					'hasErrors' => true,
					'msg'       => __( 'The process was interrupted but caused an error: ', 'wc-remove-bg' ) . $e->getMessage()
				) );
			}
		}else{
			echo json_encode( array(
				'hasErrors'  => true,
				'processing' => false,
				'status'     => 'e',
				'error_msg'  => __( 'One-time password is not correct. Refresh the page and try again.', 'wc-remove-bg' )
			) );
		}
		wp_die();
	}
	
	public function Preview_BG_Images()
	{
		if(  wp_verify_nonce( sanitize_text_field($_POST['_nonce']), 'update-options' ) ) {
			update_option( 'RemoveBG_ApiKey', sanitize_text_field(trim($_POST['RemoveBG_ApiKey'])) );
			update_option( 'RemoveBG_Background', sanitize_text_field($_POST['RemoveBG_Background']) );
			update_option( 'RemoveBG_Background_Color', sanitize_text_field($_POST['RemoveBG_Background_Color']) );
			if ( empty( get_option( 'RemoveBG_ApiKey' ) ) ) {
				echo json_encode( array(
					'hasErrors' => true,
					'msg'       => __( 'Missing API Key', 'wc-remove-bg' )
				) );
				$this->start_log();
				$this->add_log( null, [ 'errors_msg' => [ [ 'title' => __( 'Missing API Key', 'wc-remove-bg' ) ] ] ] );
				wp_die();
			}
			
			$background_image = '';
			$attachment_id    = 0;
			if ( ! empty( sanitize_text_field($_POST['RemoveBG_Background']) ) ) {
				if ( sanitize_text_field($_POST['RemoveBG_Background']) == 'image' ) {
					require_once ABSPATH . 'wp-admin/includes/image.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/media.php';
					wp_delete_attachment( get_option( 'RemoveBG_Background_Image' ), true );
					$attachment_id = media_handle_upload( 'RemoveBG_file', 0 );
					if ( isset( $attachment_id->errors ) ) {
						echo json_encode( array(
							'hasErrors' => true,
							'msg'       => __( 'Write error. Check uploads folder permissions and/or available disk space', 'wc-remove-bg' )
						) );
						wp_die();
					}
					update_option( 'RemoveBG_Background_Image', $attachment_id );
				}
			}
			
			try {
				$Remove_BG_api_key = get_option( 'RemoveBG_ApiKey' );
				$full_path         = $body = '';
				$post_id           = trim( intval($_POST['post_id']) );
				if ( ! empty( $post_id ) ) {
					$thumb_id = get_post_thumbnail_id( $post_id );
					if ( $thumb_id > 0 ) {
						$full_path = wp_get_attachment_url( $thumb_id );
						$body = [];
						$body['image_url'] = $full_path;
						if ( get_option( 'RemoveBG_Background' ) == 'color' ) {
							$body['bg_color'] = get_option( 'RemoveBG_Background_Color' );
						}
						if ( get_option( 'RemoveBG_Background' ) == 'image' ) {
							$filePATHFull_bg       = wp_get_attachment_url( $attachment_id );
							$body['bg_image_url'] = $filePATHFull_bg;
						}
						$body['size'] = 'preview';
						
						$argRemotePost = [
							'body' => $body,
							'headers' => [ 'X-Api-Key' => $Remove_BG_api_key ]
						];
						
						$response = wp_remote_post(  'https://api.remove.bg/v1.0/removebg', $argRemotePost );
						
						if ( is_wp_error( $response ) ) {
							$error_message = $response->get_error_message();
							
							echo json_encode( array(
								'hasErrors' => true,
								'msg'       => ! empty( $error_message ) ? $error_message : __( 'Background removal error: ', 'wc-remove-bg' ).$error_message
							) );
						} else {
							echo json_encode( array(
								'hasErrors'   => false,
								'file_before' => 'data:image/png;base64,' . base64_encode( file_get_contents( $full_path ) ),
								'file_after'  => 'data:image/png;base64,' . base64_encode( $response['body'] )
							) );
						}
					} else {
						echo json_encode( array(
							'hasErrors' => true,
							'msg'       => __( 'Item has no thumbnail', 'wc-remove-bg' )
						) );
					}
				} else {
					echo json_encode( array(
						'hasErrors' => true,
						'msg'       => __( 'Enter post id', 'wc-remove-bg' )
					) );
				}
				
				
			} catch ( Exception $e ) {
				echo json_encode( array(
					'hasErrors' => true,
					'msg'       => __( 'Delete error: ', 'wc-remove-bg' ) . $e->getMessage()
				) );
			}
			
			if ( ! empty( sanitize_text_field($_POST['RemoveBG_Background']) ) ) {
				if ( sanitize_text_field($_POST['RemoveBG_Background']) == 'image' ) {
					$this->deleteBackgroundImage();
				}
			}
		}else{
			echo json_encode( array(
				'hasErrors'  => true,
				'processing' => false,
				'status'     => 'e',
				'error_msg'  => __( 'One-time password is not correct. Refresh the page and try again.', 'wc-remove-bg' )
			) );
		}
		wp_die();
	}
	
	protected function error_handling( $param, $remove_bg_id )
	{
		global $wpdb;
		if ( $param['code'] != '400' ) {
			$errors_msg = '';
			foreach ( $param['errors_msg'] as $err ) {
				$errors_msg .= $err['title'] . ' ';
			}
			$wpdb->update( $wpdb->prefix . "wc_remove_bg",
				array(
					'date_end'  => date( 'Y-m-d H:i:s' ),
					'status'    => 'e',
					'error_msg' => $errors_msg
				),
				array(
					'ID' => $remove_bg_id
				),
				array(
					'%s',
					'%s',
					'%s'
				),
				array(
					'%d'
				)
			);
			$wp_upload_dir = wp_upload_dir();
			$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
			if ( ! file_exists( $log_file_dir ) ) {
				mkdir( $log_file_dir, 0755, true );
			}
			$row = "[" . date( 'Y-m-d H:i:s' ) . "] " . __( 'Background removal error: ', 'wc-remove-bg' ) . $errors_msg . "\n";
			$fp  = fopen( $log_file_dir . '/log.txt', "a" );
			fwrite( $fp, $row );
			fclose( $fp );
			
			return true;
		}
		
		return false;
	}
	
	protected function start_log()
	{
		$wp_upload_dir = wp_upload_dir();
		$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
		if ( ! file_exists( $log_file_dir ) ) {
			mkdir( $log_file_dir, 0755, true );
		}
		$RemoveBG_ApiKey = get_option( 'RemoveBG_ApiKey' );
		if ( ! empty( $RemoveBG_ApiKey ) ) {
			$RemoveBG_ApiKey = substr_replace( $RemoveBG_ApiKey, '******************', 3, 18 );
		}
		$row = "Start at: " . date( 'Y-m-d H:i:s' ) . "\n";
		$row .= "With parameters:\n";
		$row .= 'RemoveBG Api key: ' . "\n";
		$row .= $RemoveBG_ApiKey . "\n";
		$row .= "\n";
		$row .= 'Choose target products: ';
		$row .= "\n";
		if ( 'all' == get_option( 'RemoveBG_products' ) ) {
			$row .= 'Remove background from all products';
			$row .= "\n";
		} elseif ( 'specified' == get_option( 'RemoveBG_products' ) ) {
			$row .= 'Remove background only from specified products';
			$row .= "\n";
			$row .= '(' . get_option( 'RemoveBG_products_IDs' ) . ')';
			$row .= "\n";
		}
		$row .= "\n";
		$row .= 'Choose target images: ';
		$row .= "\n";
		if ( get_option( 'RemoveBG_thumbnail' ) ) {
			$row .= 'Main image';
			$row .= "\n";
		}
		if ( get_option( 'RemoveBG_gallery' ) ) {
			$row .= 'Product gallery';
			$row .= "\n";
		}
		$row .= "\n";
		if ( get_option( 'RemoveBG_Include_Processed' ) ) {
			$row .= 'Include processed images';
			$row .= "\n";
		}
		$row .= "\n";
		$row .= "Resize bigger images proportionally:\n";
		$row .= get_option( 'RemoveBG_Preserve_Resize' ) . "\n";
		$row .= "\n";
		$row .= "Make new background:\n";
		$row .= get_option( 'RemoveBG_Background' ) . "\n";
		if ( get_option( 'RemoveBG_Background' ) == 'color' ) {
			$row .= "color: " . get_option( 'RemoveBG_Background_Color' );
			$row .= "\n";
		} elseif ( get_option( 'RemoveBG_Background' ) == 'image' ) {
			$row .= "color: " . wp_get_attachment_image_url( get_option('RemoveBG_Background_Image'), 'medium' );
			$row .= "\n";
		}
		$row .= "\n";
		
		$fp = fopen( $log_file_dir . '/log.txt', "w+" );
		fwrite( $fp, $row );
		fclose( $fp );
	}
	
	protected function process_log()
	{
		$wp_upload_dir = wp_upload_dir();
		$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
		if ( ! file_exists( $log_file_dir ) ) {
			mkdir( $log_file_dir, 0755, true );
		}
		$row = "Processed " . intval($_POST['RemoveBG_CountProcessImage']) . " images of " . intval($_POST['RemoveBG_AllCountImage']) . "\n";
		$fp  = fopen( $log_file_dir . '/log.txt', "a" );
		fwrite( $fp, $row );
		fclose( $fp );
	}
	
	protected function process_skipped_log()
	{
		$wp_upload_dir = wp_upload_dir();
		$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
		if ( ! file_exists( $log_file_dir ) ) {
			mkdir( $log_file_dir, 0755, true );
		}
		$row = "Image ".intval($_POST['RemoveBG_CountProcessImage'])." already processed. Skipping." . "\n";
		$fp  = fopen( $log_file_dir . '/log.txt', "a" );
		fwrite( $fp, $row );
		fclose( $fp );
	}
	
	protected function bucked_log()
	{
		$wp_upload_dir = wp_upload_dir();
		$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
		if ( ! file_exists( $log_file_dir ) ) {
			mkdir( $log_file_dir, 0755, true );
		}
		$row = "Backed up " . intval($_POST['RemoveBG_CountProcessImage']) . " images\n";
		$fp  = fopen( $log_file_dir . '/log.txt', "a" );
		fwrite( $fp, $row );
		fclose( $fp );
	}
	
	protected function end_log()
	{
		$wp_upload_dir = wp_upload_dir();
		$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
		if ( ! file_exists( $log_file_dir ) ) {
			mkdir( $log_file_dir, 0755, true );
		}
		$row = "End at: " . date( 'Y-m-d H:i:s' ) . "\n";
		$fp  = fopen( $log_file_dir . '/log.txt', "a" );
		fwrite( $fp, $row );
		fclose( $fp );
	}
	
	protected function add_log( $post_id = null, $param )
	{
		$wp_upload_dir = wp_upload_dir();
		$log_file_dir  = $wp_upload_dir['basedir'] . '/remove-bg-log';
		if ( ! file_exists( $log_file_dir ) ) {
			mkdir( $log_file_dir, 0755, true );
		}
		$file = null;
		$errors_msg = '';
		foreach ( $param['errors_msg'] as $err ) {
			$errors_msg .= $err['title'] . ' ';
		}
		if(!empty($param['img_id'])) {
			$file = $filePATHFull = get_attached_file( $param['img_id'] );
		}
		if(!empty($post_id)) {
			$row = "[" . date( 'Y-m-d H:i:s' ) . "] Product ID: $post_id;  File: $file; $errors_msg\n";
		}else{
			$row = "[" . date( 'Y-m-d H:i:s' ) . "] ".__( 'Background removal error: ', 'wc-remove-bg' ).$errors_msg;
		}
		$fp   = fopen( $log_file_dir . '/log.txt', "a" );
		fwrite( $fp, $row );
		fclose( $fp );
	}
	
	//not used in this version
	protected function add_bg_color( $filename, $hex )
	{
		$size       = getimagesize( $filename );
		$dest_image = imagecreatetruecolor( $size[0], $size[1] );
		imagesavealpha( $dest_image, true );
		$trans_background = imagecolorallocatealpha( $dest_image, 0, 0, 0, 127 );
		imagefill( $dest_image, 0, 0, $trans_background );
		$im = @imagecreatetruecolor( $size[0], $size[1] );
		list( $r, $g, $b ) = sscanf( $hex, "#%02x%02x%02x" );
		$background_color = imagecolorallocate( $im, $r, $g, $b );
		imagefill( $im, 0, 0, $background_color );
		$orige = imagecreatefrompng( $filename );
		imagecopy( $dest_image, $im, 0, 0, 0, 0, $size[0], $size[1] );
		imagecopy( $dest_image, $orige, 0, 0, 0, 0, $size[0], $size[1] );
		header( 'Content-Type: image/png' );
		imagepng( $dest_image, $filename );
		imagedestroy( $orige );
		imagedestroy( $im );
		imagedestroy( $dest_image );
	}
	
	//not used in this version
	protected function add_bg_image( $filename, $filenamebg, $scale )
	{
		$p          = mime_content_type( $filenamebg );
		$fn         = str_replace( '/', 'createfrom', $p );
		$size       = getimagesize( $filename );
		$sizebg     = getimagesize( $filenamebg );
		$dest_image = imagecreatetruecolor( $size[0], $size[1] );
		imagesavealpha( $dest_image, true );
		$trans_background = imagecolorallocatealpha( $dest_image, 0, 0, 0, 127 );
		imagefill( $dest_image, 0, 0, $trans_background );
		$im = $fn( $filenamebg );
		if ( $scale ) {
			if ( $sizebg[0] > $sizebg[1] ) {
				$new_h = $size[1];
				$new_w = ( $sizebg[0] / $sizebg[1] ) * $size[0];
			} else {
				$new_h = ( $sizebg[1] / $sizebg[0] ) * $size[1];//$sizebg[1];
				$new_w = $size[0];
			}
			$w  = $new_w;
			$h  = $new_h;
			$im = imagescale( $im, $new_w, $new_h );
			imagecopy( $dest_image, $im, 0, 0, $w / 2 - $size[0] / 2, $h / 2 - $size[1] / 2, $size[0], $size[1] );
		} else {
			$w = $sizebg[0];
			$h = $sizebg[1];
			if ( $w < $size[0] || $h < $size[1] ) {
				$dest_image_bg = imagecreatetruecolor( $size[0], $size[1] );
				imagesavealpha( $dest_image_bg, true );
				$trans_background = imagecolorallocatealpha( $dest_image_bg, 0, 0, 0, 127 );
				imagefill( $dest_image_bg, 0, 0, $trans_background );
				$dh = 0;
				while ( $dh < $size[1] ) {
					$dw = 0;
					while ( $dw < $size[0] ) {
						imagecopy( $dest_image_bg, $im, $dw, $dh, 0, 0, $size[0], $size[1] );
						$dw += $w;
					}
					$dh += $h;
				}
				$im = $dest_image_bg;
			}
			imagecopy( $dest_image, $im, 0, 0, 0, 0, $size[0], $size[1] );
		}
		$orige = imagecreatefrompng( $filename );
		imagecopy( $dest_image, $orige, 0, 0, 0, 0, $size[0], $size[1] );
		header( 'Content-Type: image/png' );
		imagepng( $dest_image, $filename );
		imagedestroy( $orige );
		imagedestroy( $im );
		imagedestroy( $dest_image );
	}
}
