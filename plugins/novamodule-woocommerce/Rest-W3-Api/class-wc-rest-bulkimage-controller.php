<?php
/**
 * REST Controller
 *
 * This class extend `WC_REST_Controller`
 *
 * It's required to follow "Controller Classes" guide before extending this class:
 * <https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/>
 *
 * @class   WC_REST_Bulkimage_Controller
 * @package NovaModule\RestApi
 * @see     https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/image.php';
if ( ! function_exists( 'wc_rest_check_post_permissions' ) ) {
	require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-rest-functions.php';
}

/**
 * REST API Bulk Images controller class.
 *
 * @package NovaModule\RestApi
 * @extends WC_REST_Controller
 */
class WC_REST_Bulkimage_Controller extends WC_REST_Controller {


	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'bulkimages';

	/**
	 * Register routes.
	 *
	 * @since 3.5.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'edit_bulkimage' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
			)
		);
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( ! wc_rest_check_post_permissions( 'product', 'create' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_edit', __( 'Sorry, you are not allowed to edit this resource.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}
		return true;
	}

	/**
	 * Update the batch of Items with the Images.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|Array
	 */
	public function edit_bulkimage( WP_REST_Request $request ) {
		$wp_rest_server = rest_get_server();
		$data           = $request->get_params();
		$data_count     = count( $data );
		$response       = array();
		for ( $i = 0; isset( $data[ $i ] ) && $i < $data_count; $i++ ) {

			$each_row = $data[ $i ];
			$sku      = '';
			$post_id  = '';
			if ( isset( $each_row['id'] ) && $each_row['id'] ) {
				$post_id = $each_row['id'];
			}
			if ( isset( $each_row['sku'] ) && $each_row['sku'] ) {
				$sku = $each_row['sku'];
			}
			if ( ! $post_id && $sku ) {
				$post_id = $this->getIdBySku( $sku );
			} elseif ( $post_id && ! $this->checkIfProductExists( $post_id ) && $sku ) {
				$post_id = $this->getIdBySku( $sku );
			}
			if ( '' === $post_id || null === $post_id || ! $post_id ) {
				$response[] = array(
					'id'    => null,
					'error' => array(
						'code'    => 422,
						'message' => "Invalid Data, either id or sku doesn't exists " . wp_json_encode( $each_row ),
						'data'    => wp_json_encode( $each_row ),
					),
				);
				continue;
			}

			if ( $post_id && '' !== $post_id ) {

				$product        = wc_get_product( (int) $post_id );
				$each_row['id'] = (int) $post_id;
				if ( ! $product ) {
					$response[] = array(
						'id'    => null,
						'error' => array(
							'code'    => 422,
							'message' => "Invalid Data, either id or sku doesn't exists " . wp_json_encode( $each_row ),
							'data'    => wp_json_encode( $each_row ),
						),
					);
					continue;
				}
				if ( 'variation' === $product->get_type() ) {
					if ( $product->get_parent_id() && ! ( isset( $each_row['product_id'] ) && '' !== $each_row['product_id'] && $each_row['product_id'] ) ) {
						$each_row['product_id'] = $product->get_parent_id();
					}

					if ( ! ( isset( $each_row['product_id'] ) && '' !== $each_row['product_id'] && $each_row['product_id'] ) && isset( $each_row['variantParentSku'] ) && '' !== $each_row['variantParentSku'] ) {
						$parent_id_details = $this->getParentIdBySku( $each_row['variantParentSku'] );
						if ( isset( $parent_id_details['success'] ) && true === $parent_id_details['success'] && $parent_id_details['post_id'] && '' !== $parent_id_details['post_id'] ) {
							$each_row['product_id'] = $parent_id_details['post_id'];
						}
					}

					if ( '' === $each_row['product_id'] || null === $each_row['product_id'] || ! $each_row['product_id'] ) {
						$response[] = array(
							'id'    => $each_row['id'],
							'error' => array(
								'code'    => 422,
								'message' => 'Invalid Data format, variant parent is missing (' . $each_row['id'] . ')' . wp_json_encode( $each_row ),
								'data'    => wp_json_encode( $each_row ),
							),
						);
						continue;
					}

					$parent = wc_get_product( $each_row['product_id'] );
					if ( ! $parent ) {
						$response[] = array(
							'id'    => $each_row['id'],
							'error' => array(
								'code'    => 422,
								'message' => 'Variation cannot be imported: Missing parent ID or parent does not exist yet (' . $each_row['id'] . ')' . wp_json_encode( $each_row ),
								'data'    => wp_json_encode( $each_row ),
							),
						);
						continue;
					}
					if ( $parent->is_type( 'variation' ) ) {
						$response[] = array(
							'id'    => $each_row['id'],
							'error' => array(
								'code'    => 422,
								'message' => 'Variation cannot be imported: Parent product cannot be a product variation (' . $each_row['id'] . ')' . wp_json_encode( $each_row ),
								'data'    => wp_json_encode( $each_row ),
							),
						);
						continue;
					}
					$variantimage = null;
					$variantimage = $each_row['image'];
					$error_data   = '';
					if ( ! empty( $variantimage ) ) {
						$image_flag    = true;
						$variant_img   = null;
						$attachment_id = isset( $variantimage['id'] ) ? absint( $variantimage['id'] ) : 0;
						if ( 0 === $attachment_id && isset( $variantimage['src'] ) ) {
							$variantimage_name = isset( $variantimage['name'] ) ? $variantimage['name'] : '';
							if ( ! $variantimage_name ) {
								$variantimage_name = $post_id;
							}

							$gimage = $this->generate_image( $variantimage['src'], $variantimage_name );

							if ( $gimage ) {
								$variant_img = $gimage;
							} else {
								$image_flag = false;
								$error_data = wp_json_encode( $variantimage );
							}
						}
						if ( $image_flag ) {
							if ( isset( $variant_img, $variant_img['file'], $variant_img['filename'] ) ) {
								$id = null;
								$id = $this->create_attachement( $variant_img['file'], $variant_img['filename'], $post_id );
								if ( $id ) {
									$variantimage['id'] = $id;
									unset( $variantimage['src'] );
								} else {
									$image_flag = false;
									$error_data = wp_json_encode( $variantimage );
									break;
								}
							}
						}
					}
					if ( $image_flag && ! empty( $variantimage ) ) {
						if ( isset( $each_row['image'] ) ) {
							$each_row['image'] = $variantimage;
						}
						$_item = new WP_REST_Request( 'PUT' );
						$_item->set_body_params( $each_row );
						$variations_controler = new WC_REST_Product_Variations_Controller();
						$_response            = $variations_controler->update_item( $_item );
						unset( $variantimage );
					} else {
						if ( '' === $error_data ) {
							$error_data = wp_json_encode( $each_row );
						}
						$response[] = array(
							'id'    => $post_id,
							'error' => array(
								'code'    => 422,
								'message' => 'Invalid Image data ' . $error_data,
								'data'    => $error_data,
							),
						);
						continue;
					}
				} else {
					$images = $each_row['images'];
					$images = is_array( $images ) ? array_filter( $images ) : array();
					if ( ! empty( $images ) ) {
						$gallery       = array();
						$upload_urls   = array();
						$image_flags   = true;
						$image_url     = '';
						$error_data    = '';
						$image         = null;
						$attachmentids = array();
						$image_count   = count( $images );
						for ( $j = 0; isset( $images[ $j ] ) && $j < $image_count; $j++ ) {
							$image         = $images[ $j ];
							$attachment_id = isset( $image['id'] ) ? absint( $image['id'] ) : 0;
							$gimage        = null;

							if ( 0 === $attachment_id && isset( $image['src'] ) ) {
								$image_name = isset( $image['name'] ) ? $image['name'] : '';
								if ( ! $image_name ) {
									$image_name = $post_id;
								}
								$gimage = $this->generate_image( $image['src'], $image_name );
								if ( $gimage ) {
									$gallery[ $j ] = $gimage;
									$upload_urls[] = $gimage;
								} else {
									$image_flags = false;
									$image_url   = $image['src'];
									$error_data  = wp_json_encode( $image );
									break;
								}
							}
						}
						if ( false === $image_flags && is_array( $upload_urls ) && count( $upload_urls ) > 0 ) {
							foreach ( $upload_urls as $key => $upload_url ) {
								if ( isset( $upload_url, $upload_url['file'], $upload_url['filename'] ) ) {
									if ( file_exists( $upload_url['file'] ) && ! is_dir( $upload_url['file'] ) ) {
										@unlink( $upload_url['file'] );
									}
								}
							}
						}
						if ( $image_flags ) {
							foreach ( $gallery as $key => $each_image ) {
								if ( isset( $each_image['file'], $each_image['filename'] ) ) {
									if ( isset( $images[ $key ] ) ) {
										$id = null;
										$id = $this->create_attachement( $each_image['file'], $each_image['filename'], $post_id );
										if ( $id ) {
											$images[ $key ]['id'] = $id;
											unset( $images[ $key ]['src'] );
											$attachmentids[] = $id;
										} else {
											$image_flags = false;
											$error_data  = wp_json_encode( $images[ $key ] );
											break;
										}
									} elseif ( file_exists( $upload_url['file'] ) && ! is_dir( $upload_url['file'] ) ) {
										$image_flags = false;
										$error_data  = $upload_url['image_url'];
										break;
									}
								}
							}
						}
						if ( false === $image_flags ) {

							if ( is_array( $upload_urls ) && count( $upload_urls ) > 0 ) {
								foreach ( $upload_urls as $key => $upload_url ) {
									if ( isset( $upload_url, $upload_url['file'], $upload_url['filename'] ) ) {
										if ( file_exists( $upload_url['file'] ) && ! is_dir( $upload_url['file'] ) ) {
											@unlink( $upload_url['file'] );
										}
									}
								}
							}
							if ( is_array( $attachmentids ) && count( $attachmentids ) > 0 ) {
								foreach ( $attachmentids as $key => $attachmentid ) {
									wp_delete_attachment( $attachmentid, $force_delete = false );
								}
							}
						}
					}

					if ( $image_flags && ! empty( $images ) ) {
						if ( isset( $each_row['images'] ) ) {
							$each_row['images'] = $images;
						}
						$_item = new WP_REST_Request( 'PUT' );
						$_item->set_body_params( $each_row );
						$products_controller = new WC_REST_Products_Controller();
						$_response           = $products_controller->update_item( $_item );
					} else {
						if ( '' === $error_data ) {
							$error_data = wp_json_encode( $each_row );
						}
						$response[] = array(
							'id'    => $post_id,
							'error' => array(
								'code'    => 422,
								'message' => 'Invalid Image data ' . $error_data,
								'data'    => $error_data,
							),
						);
						continue;
					}
				}
				if ( is_wp_error( $_response ) ) {
					$response[] = array(
						'id'    => $_item['id'],
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message() . 'Error: ' . wp_json_encode( $each_row ),
							'data'    => $_response->get_error_data() . 'Error: ' . wp_json_encode( $each_row ),
						),
					);
				} else {
					$response[] = $wp_rest_server->response_to_data( $_response, '' );
				}
			} else {
				$response[] = array(
					'id'    => null,
					'error' => array(
						'code'    => 422,
						'message' => "Invalid Data, either id or sku doesn't exists " . wp_json_encode( $each_row ),
						'data'    => wp_json_encode( $each_row ),
					),
				);
				continue;
			}
		}

		return $response;
	}
	/**
	 * Get the product Id based on the sku.
	 *
	 * @param String $sku passed to function to get the Id.
	 * @return post Id
	 */
	private function getIdBySku( $sku ) {
		global $wpdb;
		$post_id       = '';
		$postmetatable = $wpdb->prefix . 'postmeta';
		$posttable     = $wpdb->prefix . 'posts';
		$results       = $wpdb->get_results( $wpdb->prepare( 'SELECT post_id from ' . $wpdb->prefix . 'postmeta as a INNER JOIN ' . $wpdb->prefix . 'posts as b ON a.post_id = b.id WHERE meta_key = %s AND meta_value=%s ORDER BY a.post_id DESC', array( '_sku', $sku ) ) );

		foreach ( $results as $key => $value ) {
			$post_id = $value->post_id;
			return $post_id;
		}

		return $post_id;
	}
	/**
	 * Check if product exists based on the id
	 *
	 * @param Integer $post_ID passed to check the Item with that product Exists.
	 * @return post Id|boolean
	 */
	private function checkIfProductExists( $post_ID = '' ) {
		if ( ! $post_ID ) {
			return false;
		}
		global $wpdb;

		$post_id   = false;
		$posttable = $wpdb->prefix . 'posts';
		$post_ID   = (int) $post_ID;
		$results   = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->prefix . "posts WHERE post_type IN ('product','product_variation') AND ID =%d", array( $post_ID ) ) );

		foreach ( $results as $key => $value ) {
			$post_id = $value->ID;
			return $post_id;
		}
		return $post_id;
	}
	/**
	 * Get the Image from URL
	 *
	 * @param String $url is passed to data from the server.
	 * @return imageData |boolean
	 */
	private function grab_image( $url ) {
		$headers[]  = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/jpg, image/png, image/bmp, image/tiff, image/x-png, image/pjpeg';
		$headers[]  = 'Connection: Keep-Alive';
		$headers[]  = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$user_agent = 'php';
		$ch         = curl_init( $url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		/*curl_setopt( $ch, CURLOPT_BINARYTRANSFER, 1 );*/
		curl_setopt( $ch, CURLOPT_USERAGENT, $user_agent );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		$raw = curl_exec( $ch );
		if ( ! curl_errno( $ch ) ) {
			$info = curl_getinfo( $ch );
			curl_close( $ch );
			if ( $info['http_code'] && 200 === (int) $info['http_code'] && preg_match( '!^image/!', $info['content_type'] ) ) {
				return array(
					'info' => $info,
					'raw'  => $raw,
				);
			}
		} else {
			curl_close( $ch );
		}
		return false;
	}
	/**
	 * Create Image and store in folder
	 *
	 * @param String $image_url image url to pull the data.
	 * @param String $filename file name to be set for generated file.
	 * @return uploadedImageData |boolean
	 */
	private function generate_image( $image_url, $filename = '' ) {
		$image_data = $this->grab_image( $image_url );
		if ( $image_data && isset( $image_data['info'], $image_data['raw'] ) ) {
			$info      = $image_data['info'];
			$image_raw = $image_data['raw'];
			$ext       = '.jpg';
			$mime      = $info['content_type'];
			if ( '' === $mime ) {
				$mime = 'image/jpeg';
			}
			$mimeext = array(
				'image/jpg'     => '.jpg',
				'image/png'     => '.png',
				'image/gif'     => '.gif',
				'image/bmp'     => '.bmp',
				'image/jpeg'    => '.jpeg',
				'image/tiff'    => '.tif',
				'image/svg+xml' => '.svg',
				'image/pjpeg'   => '.jpeg',
				'image/x-png'   => '.png',
			);

			if ( isset( $mimeext[ $mime ] ) && '' !== $mimeext[ $mime ] ) {
				$ext = $mimeext[ $mime ];
			}

			if ( '' === $filename ) {
				$filename = time() . wp_rand( 1000, 10000 ) . '-wcimg' . $ext;
			} else {
				$filename = time() . wp_rand( 1000, 10000 ) . '-' . $filename . $ext;
			}

			$upload_dir = wp_upload_dir();

			if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			try {
				file_put_contents( $file, $image_raw );
			} catch ( Exception $e ) {
				$img_savefile = fopen( $file, 'w' );
				fwrite( $img_savefile, $image_raw );
				fclose( $img_savefile );
			}
			return array(
				'file'      => $file,
				'filename'  => $filename,
				'image_url' => $image_url,
			);
		}
		return false;
	}
	/**
	 * Create attachment
	 *
	 * @param String  $file Image file link.
	 * @param String  $filename Image file Name.
	 * @param Integer $post_id product Id.
	 * @return uploadedImageData |boolean
	 */
	private function create_attachement( $file, $filename, $post_id ) {
		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		$attach_id   = wp_insert_attachment( $attachment, $file, $post_id );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		return $attach_id;

	}
	/**
	 * Get the parent Id based on the parent Sku
	 *
	 * @param String $sku to get the variant parent Item based on Sku.
	 * @return parentId|boolean
	 */
	private function getParentIdBySku( $sku ) {
		global $wpdb;
		$post_id           = '';
		$postmetatable     = $wpdb->prefix . 'postmeta';
		$posttable         = $wpdb->prefix . 'posts';
		$results           = $wpdb->get_results( $wpdb->prepare( 'SELECT post_id from ' . $wpdb->prefix . 'postmeta as a INNER JOIN ' . $wpdb->prefix . 'posts' . " as b ON a.post_id = b.id WHERE meta_key = '_sku' AND meta_value=%s AND b.post_type IN ('product') ORDER BY a.post_id DESC", array( $sku ) ) );
		$result            = array();
		$result['success'] = false;
		if ( 1 === count( $results ) ) {
			$result['success'] = true;
			foreach ( $results as $key => $value ) {
				$post_id           = $value->post_id;
				$result['post_id'] = $post_id;
				return $result;
			}
		} elseif ( count( $results ) > 1 ) {
			$message = 'Multiple Items has same sku, ID List with same skus - ';
			foreach ( $results as $key => $value ) {
				$post_id  = $value->post_id;
				$message .= $post_id . ', ';
			}
			$message         = trim( $message, ',' );
			$result['error'] = $message;
		} elseif ( 0 === count( $results ) ) {
			$message         = 'No item with sku ' . $sku . ' exists in woo-commerce';
			$result['error'] = $message;
		}
		return $result;
	}
}
