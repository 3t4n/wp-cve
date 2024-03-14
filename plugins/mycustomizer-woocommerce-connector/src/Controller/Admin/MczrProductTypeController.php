<?php

namespace MyCustomizer\WooCommerce\Connector\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrFlashMessage;
use MyCustomizer\WooCommerce\Connector\Types\WC_Product_Mczr;

MczrAccess::isAuthorized();

class MczrProductTypeController {

	public function __construct() {
		$this->factory = MczrFactory::getInstance();
		$this->request = Request::createFromGlobals();
		$this->flash   = new MczrFlashMessage();
	}

	public function init() {
	}

	public function create( $startingPointId, $startingPointName, $price ) {
		$product_post = array(
			'post_title'  => $startingPointName,
			'post_status' => 'publish',
			'post_type'   => 'product',
		);

		// Creating the product variation
		$productPostId = wp_insert_post( $product_post );

		wp_set_object_terms( $productPostId, 'mczr', 'product_type' );
		update_post_meta( $productPostId, 'mczrStartingPoint', $startingPointId );
		update_post_meta( $productPostId, '_price', $price );

		return $productPostId;
	}

	public function update( $startingPointId, $startingPointName = null, $price = null ) {
		$product_query = new \WP_Query(
			array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'     => 'mczrStartingPoint',
						'value'   => $startingPointId,
						'compare' => 'IS',
					),
				),
			)
		);


		if ( $product_query->have_posts() ) {
			$postId = $product_query->post->ID;
			$data = array(
				'ID'         => $postId,
			);

			if ( isset($startingPointName) && $startingPointName != null ) {
				$data['post_title'] = $startingPointName;
				update_post_meta( $postId, 'post_title', $startingPointName );
			}

			if ( isset($price) && $price != null ) {
				$data['_price'] = $price;
				update_post_meta( $postId, '_price', $price );
			}

			return wp_update_post( $data );
		}

		return false;
	}

	public function delete( $startingPointId ) {
		$products = new \WP_Query(
			array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'     => 'mczrStartingPoint',
						'value'   => $startingPointId,
						'compare' => 'IS',
					),
				),
			)
		);

		if ( $products->have_posts() ) {
			$productId = $products->post->ID;
			$product   = wc_get_product( $productId );
			$product->delete( true );
			return true;
		}

		return false;
	}

	public function attachProductThumbnail( $post_id, $url ) {
		$image_url  = $url;
		$url_array  = explode( '/', $url );
		$image_name = $url_array[ count( $url_array ) - 1 ];
		$image_data = wp_remote_retrieve_body( wp_remote_get( $image_url ) );

		$upload_dir       = wp_upload_dir();
		$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
		$filename         = basename( $unique_file_name ) . '.png';
		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		file_put_contents( $file, $image_data );

		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		$attach_id   = wp_insert_attachment( $attachment, $file, $post_id );
		include_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		set_post_thumbnail( $post_id, $attach_id );
	}

	public function processAndSaveTabMetaField() {
		$productId = ( $this->request->get( 'post_ID' ) );
		$product   = new WC_Product_Mczr( $productId );
		$errors    = false;
		$validator = MczrFactory::getInstance()->getValidator();

		if ( ! $product->is_type( 'mczr' ) ) {
			return;
		}

		$mczrStartingPoint = $this->request->get( 'mczrStartingPoint' );

		try {
			$syncCodeDecoded = json_decode( base64_decode( $mczrStartingPoint ) );

			if ( ! isset( $syncCodeDecoded->startingPointId ) ) {
				throw new \Exception( 'Invalid Sync Code' );
			}

			$mczrStartingPoint = $syncCodeDecoded->startingPointId;
		} catch ( \Exception $err ) {
			error_log( $err->getMessage());
		}

		$validate = array(
			'mczrStartingPoint' => $validator->validate(
				trim( $mczrStartingPoint ),
				array(
					new Regex(
						array(
							'pattern' => '#^[a-zA-Z0-9-_]+$#i',
							'message' => sprintf( 'MyCustomizer starting point could not be saved. Please check against value : no space, only alphanumeric characters with dash or underscore. ([a-zA-Z0-9\-\_]+)' ),
						)
					),
					new NotBlank(),
				)
			),
			'mczrQuantityMin'   => $validator->validate( trim( $this->request->get( 'mczrQuantityMin' ) ), array( new Regex( '#^[0-9]+$#i' ) ) ),
			'mczrQuantityMax'   => $validator->validate( trim( $this->request->get( 'mczrQuantityMax' ) ), array( new Regex( '#^[0-9]+$#i' ) ) ),
			'mczrQuantityStep'  => $validator->validate( trim( $this->request->get( 'mczrQuantityStep' ) ), array( new Regex( '#^[0-9]+$#i' ) ) ),
			'mczrQuantityStart' => $validator->validate( trim( $this->request->get( 'mczrQuantityStart' ) ), array( new Regex( '#^[0-9]+$#i' ) ) ),
		);

		// Check all fields for errors
		foreach ( $validate as $name => $violation ) {
			if ( ! empty( (string) $violation ) ) {
				$errors = true;
				$this->flash->add( MczrFlashMessage::TYPE_ERROR, sprintf( $violation ) );
			} else {
				$value = $this->request->get( $name );

				if ( 'mczrStartingPoint' === $name ) {
					$value = $mczrStartingPoint;
				}

				update_post_meta( $productId, $name, $value );
			}
		}

		if ( $errors ) {
			return;
		}

		// Success
		$this->flash->add( MczrFlashMessage::TYPE_SUCCESS, sprintf( 'Your product is now set as customizable with id %s', \htmlentities( $mczrStartingPoint ) ) );
		return;
	}
}
