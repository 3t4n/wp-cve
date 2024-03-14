<?php
/**
 * Author: Vitaly Kukin
 * Date: 13.02.2018
 * Time: 9:35
 */

namespace dm;


class dmUploadImages {
	
	private $product;
	
	public function upload( $post_id, $product ) {
		
		if( ! $post_id )
			return [
				'error' => __( 'Post ID not found', 'dm' )
			];
		
		$this->product = $this->getProduct( $post_id );
		
		$this->uploadSku( $post_id );
		$this->uploadGallery( $post_id );
		$this->uploadImage( $post_id );
		
		return [
			'post_id' => $post_id,
			'product' => $product,
			'id'      => null,
			'url'     => null,
			'success' => '<strong>' . get_the_title( $post_id ) . '</strong> has been imported',
		];
	}
	
	/**
	 * uploadImagesReview
	 *
	 * @param integer $post_id
	 */
	public function uploadImagesReview( $post_id ) {
		
		$comments = get_comments( [ 'post_id' => $post_id ] );
		
		foreach( $comments as $comment ) {
			$this->uploadReviewImage( $comment->comment_ID, $post_id );
		}
	}
	
	private function uploadReviewImage( $commentId, $post_id ) {
		
		$images = get_comment_meta( $commentId, 'images' );
		
		if( count( $images ) == 0 ) {
			return false;
		}
		
		$urls = unserialize( array_shift( $images ) );
		
		if( ! is_array( $urls ) || empty( $urls ) ) {
			return false;
		}
		
		foreach( $urls as $key => $url ) {
			
			$attach = $this->attachmentImage( $post_id, $url );
			
			if( $attach !== false ) {
				$urls[ $key ] = $attach[ 'id' ];
			}
		}
		
		return update_comment_meta( $commentId, 'images', $urls );
	}
	
	/**
	 * @param integer $post_id
	 * @param string $url
	 * @param bool $isThumb
	 *
	 * @return bool|false|int
	 */
	public function uploadImage( $post_id, $url, $isThumb = false ) {
		
		$loadImg = $this->attachmentImage( $post_id, $url );
		if ( $loadImg && $loadImg[ 'url' ] ) {
			
			if( $isThumb )
				set_post_thumbnail( $post_id, $loadImg[ 'id' ] );
			
			return $loadImg;
		}
		
		return false;
	}
	
	/**
	 * @param $post_id
	 * @param $url
	 * @param string $size
	 * @param string $name
	 *
	 * @return array|bool
	 */
	private function attachmentImage( $post_id, $url, $size = 'full', $name = '' ) {
		
		$url = $this->getFullUrlImg($url);
		
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png|webp)\b/i', $url, $matches );
		if ( ! $matches ) {
			return false;
		}
		
		$p = strrpos( $url, "." );
		$r = substr( $url, $p );
		
		if ( $name != '' ) {
			$filename = $post_id . '-' . sanitize_title( $name ) . $r;
		} else {
			$filename = $post_id . '-' . sanitize_title( md5( $url ) ) . $r;
			$name     = basename( $filename );
		}
		
		$attach_id = $this->getAttachImageId( basename( $filename ) );
		
		if ( empty( $attach_id ) ) {
			$uploaddir  = wp_upload_dir();
			$uploadfile = $uploaddir[ 'path' ] . '/' . $filename;
			
			if ( ! file_exists( $uploadfile ) ) {
				$contents = $this->file_get_contents( $url );
				if($contents == false)
					return false;
				
				$fp = @fopen( $uploadfile, 'x' );
				if ( $fp ) {
					
					fwrite( $fp, $contents );
					fclose( $fp );
				}
			}
			
			
			if ( !file_exists( $uploadfile ) ) {
				return false;
			}
			
			if ( filesize( $uploadfile ) == 0 ) {
				unlink($uploadfile);
				return false;
			}
			
			$wp_filetype = wp_check_filetype( basename( $filename ), null );
			
			$attachment_data = [
				'post_mime_type' => $wp_filetype[ 'type' ],
				'post_title'     => $name,
				'post_content'   => '',
				'post_status'    => 'inherit'
			];
			
			$attach_id    = wp_insert_attachment( $attachment_data, $uploadfile, $post_id );
			$imagenew     = get_post( $attach_id );
			$fullsizepath = get_attached_file( $imagenew->ID );
			
			if(!function_exists('wp_generate_attachment_metadata')){
				include( ABSPATH . 'wp-admin/includes/image.php' );
			}
			
			$attach_data  = \wp_generate_attachment_metadata( $attach_id, $fullsizepath );
			\wp_update_attachment_metadata( $attach_id, $attach_data );
		}
		
		
		return [
			'url' => $this->getImageById( $attach_id, $size ),
			'id'  => $attach_id
		];
	}
	
	private function file_get_contents( $file ) {
		
		if(substr($file, 0,2) == '//'){
			$file = 'http:'. $file;
		}
		
		$response = wp_remote_get( $file, [
			'timeout'   => 15,
			'sslverify' => false
		] );
		
		if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ){
			return $response['body'];
		}
		
		return false;
	}
	
	private function getImageById( $id, $size = 'thumbnail' ) {
		
		$img = wp_get_attachment_image_src( $id, $size, false );
		
		if ( $img ) {
			return $img[ 0 ];
		}
		
		return false;
	}
	
	private function getAttachImageId( $image_url ) {
		
		global $wpdb;
		
		$attachment = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_title LIKE '%s';", '%' . $wpdb->esc_like( $image_url ) . '%'
			)
		);
		
		return isset( $attachment[ 0 ] ) ? $attachment[ 0 ] : false;
	}
	
	private function getFullUrlImg( $url ) {
		
		$url = preg_replace( '/(_\d+x\d+\.jpe?g)/', '', $url );
		
		return $url;
	}
	
	protected function getProduct( $post_id ) {
		
		global $wpdb;
		
		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT pr.*, pm.*, am.*, pr.post_id
                 FROM {$wpdb->ads_products} pr
                 	INNER JOIN {$wpdb->posts} posts ON pr.post_id = posts.ID
                    LEFT JOIN {$wpdb->ads_products_meta} pm ON pm.post_id = pr.post_id
                    LEFT JOIN {$wpdb->prefix}ads_ali_meta am ON am.post_id = pm.post_id
                 WHERE pr.post_id = %d", $post_id
			)
		);
		
	}
	
	public function uploadImagesWoo( $product ) {
		
		$info = $this->getProductInfo( $product[ 'post_id' ] );
		
		$imported = false;
		
		if( isset( $product[ 'images' ] ) && count( $product[ 'images' ] ) ) {
			
			$images = $product[ 'images' ];
			
			foreach( $images as &$image ) {
				
				if( $image[ 'id' ] > 0 ) continue;
				
				if( $imported ) {
					
					$product[ 'images' ] = $images;
					
					update_option( 'me_task_upload_images_' . $product[ 'post_id' ], $product );
					\set_transient( 'me_has_task_upload_images', $product[ 'post_id' ], 30 );
					
					$percent = $this->countPercent( $images );
					
					return [
						'post_id'        => (int) $product[ 'post_id' ],
						'product'        => $product[ 'product' ],
						'images'         => $images,
						'uploadedImages' => isset( $product[ 'uploadedImages' ] ) ? $product[ 'uploadedImages' ] : [],
						'message'        => __( 'Upload images. Progress', 'dm' ) . ': ' . $percent . '%',
						'percent'        => floatval( $percent )
					];
				}
				
				if( $check_id = $this->checkImages( $image[ 'url' ], $images ) ) {
					$image[ 'id' ] = $check_id;
				} elseif( isset( $product[ 'uploadedImages' ] ) &&
				          $check_id = $this->checkUploadedImages( $image[ 'url' ], $product[ 'uploadedImages' ] ) ) {
					$image[ 'id' ] = $check_id;
				}
				
				switch( $image[ 'type' ] ) {
					
					case 'thumb':
						
						if( $image[ 'id' ] > 0 ) {
							set_post_thumbnail( $image[ 'post_id' ], $image[ 'id' ] );
						} else {
							$uploaded = $this->attachmentImage( $image[ 'post_id' ], $image[ 'url' ] );
							$image[ 'id' ] = $uploaded[ 'id' ];
							set_post_thumbnail( $image[ 'post_id' ], $image[ 'id' ] );
						}
						
						break;
					
					case 'gallery':

						$gallery = get_post_meta( $image[ 'post_id' ], '_product_image_gallery', true );
						
						$gallery = ! empty( $gallery ) ? explode( ',', $gallery ) : [];
						
						$uploaded      = $this->attachmentImage( $image[ 'post_id' ], $image[ 'url' ] );
						
						$image[ 'id' ] = $uploaded[ 'id' ];
						$gallery[]     = $image[ 'id' ];
						
						update_post_meta( $image[ 'post_id' ], '_product_image_gallery', implode(',', $gallery) );

						break;
					
					case 'variation':
						
						global $wpdb;
						
						$var_ids = implode( ',', $this->getVariablesID( $product[ 'post_id' ] ) );
						
						$post_id = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT post_id FROM {$wpdb->postmeta} WHERE post_id IN ({$var_ids}) AND meta_key = 'adswSKU' AND meta_value = %s",
								$image[ 'post_id' ]
							)
						);
						
						if( ! empty( $post_id ) ) {
							
							if( empty( $image[ 'id' ] ) ) {
								$uploaded       = $this->attachmentImage( $post_id, $image[ 'url' ] );
								$image[ 'id' ]  = $uploaded[ 'id' ];
							}
							
							update_post_meta( $post_id, '_thumbnail_id', $image[ 'id' ] );
						}
						
						break;
				}
				
				$imported = true;
			}
		}
		
		\delete_option( 'me_task_upload_images_' . $product[ 'post_id' ] );
		
		return [
			'post_id' => $product[ 'post_id' ],
			'product' => $product[ 'product' ],
			'id'      => $info->product_id,
			'url'     => $info->productUrl,
			'success' => '<strong>' . get_the_title( $product[ 'post_id' ] ) . '</strong> has been imported',
			
		];
	}
	
	public function uploadImages( $product ) {
		
		$info = $this->getProduct( $product[ 'post_id' ] );
		
		$imported = false;
		
		if( isset( $product[ 'images' ] ) && count( $product[ 'images' ] ) ) {
			
			$images = $product[ 'images' ];
			
			foreach( $images as &$image ) {
				
				if( $image[ 'id' ] > 0 ) continue;
				
				if( $imported ) {
					
					$product[ 'images' ] = $images;
					
					update_option( 'me_task_upload_images_' . $product[ 'post_id' ], $product );
					\set_transient( 'me_has_task_upload_images', $product[ 'post_id' ], 30 );
					
					$percent = $this->countPercent( $images );
					
					return [
						'post_id'        => $product[ 'post_id' ],
						'product'        => $product[ 'product' ],
						'images'         => $images,
						'uploadedImages' => isset( $product[ 'uploadedImages' ] ) ? $product[ 'uploadedImages' ] : [],
						'message'        => __( 'Upload images. Progress', 'dm' ) . ': ' . $percent . '%',
						'percent'        => $percent,
					];
				}
				
				if( $check_id = $this->checkImages( $image[ 'url' ], $images ) ) {
					$image[ 'id' ] = $check_id;
				} elseif( isset( $product[ 'uploadedImages' ] ) &&
				          $check_id = $this->checkUploadedImages( $image[ 'url' ], $product[ 'uploadedImages' ] ) ) {
					$image[ 'id' ] = $check_id;
				}
				
				switch( $image[ 'type' ] ) {
					
					case 'thumb':
						
						if( $image[ 'id' ] > 0 ) {
							set_post_thumbnail( $image[ 'post_id' ], $image[ 'id' ] );
						} else {
							$uploaded = $this->attachmentImage( $image[ 'post_id' ], $image[ 'url' ] );
							$image[ 'id' ] = $uploaded[ 'id' ];
							set_post_thumbnail( $image[ 'post_id' ], $image[ 'id' ] );
						}
						
						global $wpdb;
						
						$wpdb->update(
							$wpdb->prefix . 'ads_products' ,
							[ 'imageUrl' => get_the_post_thumbnail_url( $image[ 'post_id' ], 'ads-medium' ) ],
							[ 'post_id'  => $product[ 'post_id' ] ]
						);
						
						break;
					
					case 'gallery':
						
						global $wpdb;
						
						$gallery = @unserialize( $info->gallery );
						$gallery = $gallery && ! empty( $gallery ) ? $gallery : [];
						
						$uploaded      = $this->attachmentImage( $image[ 'post_id' ], $image[ 'url' ] );
						$image[ 'id' ] = $uploaded[ 'id' ];
						$gallery[]     = $image[ 'id' ];
						
						$wpdb->update(
							$wpdb->prefix . 'ads_products_meta' ,
							[ 'gallery' => serialize( $gallery ) ],
							[ 'post_id' => $product[ 'post_id' ] ]
						);
						
						break;
					
					case 'variation':
						
						global $wpdb;
						
						if( empty( $image[ 'id' ] ) ) {
							$uploaded      = $this->attachmentImage( $product[ 'product' ], $image[ 'url' ] );
							$image[ 'id' ] = $uploaded[ 'id' ];
						}
						
						$variables = @unserialize( $info->sku );
						
						if( $variables ) {
							
							foreach( $variables as &$variable ) {
								
								if( $variable[ 'img' ] == $image[ 'url' ] )
									$variable[ 'img' ] = $image[ 'id' ];
							}
							
							$wpdb->update(
								$wpdb->prefix . 'ads_products_meta' ,
								[ 'sku' => serialize( $variables ) ],
								[ 'post_id' => $product[ 'post_id' ] ]
							);
						}
						
						break;
				}
				
				$imported = true;
			}
		}
		
		\delete_option( 'me_task_upload_images_' . $product[ 'post_id' ] );
		
		return [
			'post_id' => $product[ 'post_id' ],
			'product' => $product[ 'product' ],
			'id'      => $info->product_id,
			'url'     => $info->productUrl,
			'success' => '<strong>' . get_the_title( $product[ 'post_id' ] ) . '</strong> has been imported',
		];
	}
	
	public function getVariablesID( $post_id ) {
		
		global $wpdb;
		
		$result = $wpdb->get_results(
			$wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_parent = %d AND post_type = 'product_variation'", $post_id )
		);
		
		$foo = [];
		if( $result ) foreach ( $result as $res )
			$foo[] = $res->ID;
		
		return $foo;
	}
	
	public function checkImages( $url, $images ) {
		
		foreach( $images as $image ) {
			
			if( $image[ 'url' ] == $url && ! empty( $image[ 'id' ] ) )
				return $image[ 'id' ];
		}
		
		return false;
	}
	
	protected function checkUploadedImages( $url, $images ) {
		
		if( sizeof( $images ) > 0 ) foreach( $images as $image ) {
			
			if( $image[ 'url' ] == $url && ! empty( $image[ 'id '] ) )
				return $image[ 'id '];
		}
		
		return false;
	}
	
	protected function countPercent( $images ) {
		
		$count = count( $images );
		$i     = 0;
		
		foreach( $images as $image )
			if( ! empty( $image[ 'id'] ) )
				$i++;
		
		return $i > 0 ? dm_floatvalue( 100 * $i  / $count ) : 0;
	}
	
	public function getProductInfo( $id ) {
		
		global $wpdb;
		
		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adsw_ali_meta WHERE post_id = %d LIMIT 1", $id )
		);
	}
}