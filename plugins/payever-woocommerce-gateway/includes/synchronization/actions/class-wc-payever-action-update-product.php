<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Synchronization_Action_Handler_UpdateProduct' ) ) {
	return;
}

use Payever\Sdk\Core\Base\ResponseInterface;
use Payever\Sdk\Core\Http\MessageEntity\GetCurrenciesResultEntity;
use Payever\Sdk\Core\Http\ResponseEntity\GetCurrenciesResponse;
use Payever\Sdk\Products\Enum\ProductTypeEnum;
use Payever\Sdk\Products\Http\RequestEntity\ProductRequestEntity;
use Payever\Sdk\ThirdParty\Action\ActionHandlerInterface;
use Payever\Sdk\ThirdParty\Action\ActionPayload;
use Payever\Sdk\ThirdParty\Action\ActionResult;
use Payever\Sdk\ThirdParty\Enum\ActionEnum;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Synchronization_Action_Handler_UpdateProduct implements ActionHandlerInterface, LoggerAwareInterface {

	use WC_Payever_Helper_Wrapper_Trait;
	use WC_Payever_Payments_Api_Client_Trait;
	use WC_Payever_Product_Uuid_Trait;
	use WC_Payever_Wpdb_Trait;
	use WC_Payever_WP_Wrapper_Trait;

	/** @var LoggerInterface */
	protected $logger;

	/** @var ActionResult */
	private $action_result;

	/** @var bool */
	private $skip_fs = false;

	/** @var GetCurrenciesResultEntity[] $currencies */
	private $currencies;

	/**
	 * @inheritdoc
	 */
	public function getSupportedAction() {
		return ActionEnum::ACTION_UPDATE_PRODUCT;
	}

	/**
	 * @inheritDoc
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param bool $skip_fs
	 * @return $this
	 * @internal
	 */
	public function set_skip_fs( $skip_fs ) {
		$this->skip_fs = $skip_fs;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function handle( ActionPayload $action_payload, ActionResult $action_result ) {
		$this->action_result = $action_result;

		/** @var ProductRequestEntity $product_entity */
		$product_entity = $action_payload->getPayloadEntity();

		$this->process_product_entity( $product_entity );
	}

	/**
	 * @param ProductRequestEntity $product_entity
	 */
	private function process_product_entity( ProductRequestEntity $product_entity ) {
		if ( ! $product_entity->getSku() ) {
			$this->action_result->incrementSkipped();
			$this->action_result->addError(
				sprintf(
					'Product [title=%s] [uuid=%s] has empty SKU',
					$product_entity->getTitle(),
					$product_entity->getUuid()
				)
			);

			return;
		}

		if ( $product_entity->isVariant() ) {
			$this->action_result->incrementSkipped();
			$this->action_result->addError(
				sprintf(
					'Product SKU=%s is variant. This integration only supports full product payload',
					$product_entity->getSku()
				)
			);

			return;
		}

		try {
			if ( $this->is_action_stalled( $product_entity ) ) {
				return;
			}
			$wc_product = $this->process_product_import( $product_entity );
			$this->process_product_categories( $wc_product, $product_entity );
		} catch ( \Exception $exception ) {
			$this->action_result->incrementSkipped();
			$this->action_result->addException( $exception );
		}
	}

	/**
	 * @param ProductRequestEntity $request_entity
	 * @return bool
	 */
	private function is_action_stalled( ProductRequestEntity $request_entity ) {
		$post_id = $this->get_wp_wrapper()->wc_get_product_id_by_sku( $request_entity->getSku() );
		if ( ! $post_id ) {
			return false;
		}
		$product = $this->get_wp_wrapper()->wc_get_product( $post_id );
		if ( ! $product ) {
			return false;
		}
		$updated_at_from_request = $request_entity->getUpdatedAt();
		$updated_at = null;
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$updated_at = $product->get_date_modified();
		}
		$result = $updated_at_from_request instanceof \DateTime && $updated_at instanceof \DateTime &&
			$updated_at_from_request->getTimestamp() < $updated_at->getTimestamp();
		if ( $result ) {
			$this->action_result->incrementSkipped();
			$message = sprintf(
				'Skip processing stalled action: %s <= %s',
				$updated_at_from_request->format( \DATE_RFC3339_EXTENDED ),
				$updated_at->format( \DATE_RFC3339_EXTENDED )
			);
			$this->action_result->addError( $message );
			$this->logger->info( $message );
		}

		return $result;
	}

	/**
	 * Attributes import
	 *
	 * @param $product_id
	 * @param $attributes
	 *
	 * @return array
	 */
	private function import_product_attributes( $product_id, $attributes ) {
		$wpdb = $this->get_wpdb();

		$product_attributes   = array();
		$attribute_taxonomies = $this->get_wp_wrapper()->wc_get_attribute_taxonomies();
		foreach ( $attributes as $key => $terms ) {
			$taxonomy = $this->get_wp_wrapper()->wc_attribute_taxonomy_name( $key );
			if ( ! $this->get_wp_wrapper()->taxonomy_exists( $taxonomy ) ) {
				$this->get_wp_wrapper()->register_taxonomy(
					$taxonomy,
					array( 'product', 'product_variation' ),
					array(
						'hierarchical' => true,
						'show_ui'      => false,
						'query_var'    => true,
						'rewrite'      => false,
					)
				);

				$attr_label   = ucfirst( $key );
				$attr_name    = $this->get_wp_wrapper()->wc_sanitize_taxonomy_name( $key );
				$attr_label   = empty( $attr_label ) ? ucfirst( $attr_name ) : $attr_label;
				$attribute_id = $this->get_attribute_id_by_name( $attr_name );

				if ( empty( $attribute_id ) ) {
					$args = array(
						'attribute_id'      => null,
						'attribute_name'    => $attr_name,
						'attribute_label'   => $attr_label,
						'attribute_type'    => 'select',
						'attribute_orderby' => 'menu_order',
						'attribute_public'  => 0,
					);

					$wpdb->insert( "{$wpdb->prefix}woocommerce_attribute_taxonomies", $args );
					//delete_transient( 'wc_attribute_taxonomies' );
					$attribute_id                                  = $this->get_attribute_id_by_name( $attr_name );
					$args['attribute_id']                          = $attribute_id;
					$attribute_taxonomies[ 'id:' . $attribute_id ] = (object) $args;
					$this->get_wp_wrapper()->set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );
				}
			}

			$product_attributes[ $taxonomy ] = array(
				'name'         => $taxonomy,
				'value'        => implode( ' | ', $terms ),
				'position'     => '',
				'is_visible'   => 1,
				'is_variation' => 1,
				'is_taxonomy'  => 1,
			);

			foreach ( $terms as $value ) {
				$term_name = ucfirst( $value );
				$term_slug = $this->get_wp_wrapper()->sanitize_title( $value );

				if ( ! $this->get_wp_wrapper()->term_exists( $value, $taxonomy ) ) {
					$this->get_wp_wrapper()->wp_insert_term( $term_name, $taxonomy, array( 'slug' => $term_slug ) );
				}

				$this->get_wp_wrapper()->wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
			}
		}

		return $product_attributes;
	}

	/**
	 * Product variations import
	 *
	 * @param $product_id
	 * @param ProductRequestEntity[] $variants
	 * @param $entity_currency
	 */
	private function import_product_variations( $product_id, $variants, $entity_currency ) {
		$product = $this->get_wp_wrapper()->wc_get_product( $product_id );
		foreach ( $variants as $variant ) {
			$variation_sku = $variant->getSku();
			$variation_id  = $this->get_helper_wrapper()->get_product_variation_id_by_sku( $variation_sku );
			if ( ! $variation_id ) {
				$variation_post = array(
					'post_title'   => $variant->getTitle(),
					'post_content' => $variant->getDescription(),
					'post_name'    => $variation_sku,
					'post_status'  => $variant->getActive() ? 'publish' : 'pending',
					'post_parent'  => $product_id,
					'post_type'    => 'product_variation',
					'guid'         => $product->get_permalink(),
				);
				$variation_id   = $this->get_wp_wrapper()->wp_insert_post( $variation_post );
			}
			foreach ( $variant->getOptions() as $option ) {
				$taxonomy        = $this->get_wp_wrapper()->wc_attribute_taxonomy_name( $option->getName() );
				$term_slug       = $this->get_wp_wrapper()->get_term_by( 'name', $option->getValue(), $taxonomy )->slug;
				$post_term_names = $this->get_wp_wrapper()->wp_get_post_terms( $product_id, $taxonomy, array( 'fields' => 'names' ) );
				if ( ! in_array( $option->getValue(), $post_term_names ) ) {
					$this->get_wp_wrapper()->wp_set_post_terms( $product_id, $option->getValue(), $taxonomy, true );
				}

				$this->get_wp_wrapper()->update_post_meta( $variation_id, 'attribute_' . $taxonomy, $term_slug );
			}

			$this->save_product_data( $variation_id, $variant, $entity_currency );
		}
	}

	/**
	 * Processes entity price
	 *
	 * @param $entity_price
	 * @param $entity_currency
	 *
	 * @return float|int
	 */
	private function process_entity_price( $entity_price, $entity_currency ) {
		$default_currency = $this->get_wp_wrapper()->get_woocommerce_currency();
		if ( $entity_currency && $entity_currency !== $default_currency ) {
			if ( null === $this->currencies ) {
				/** @var ResponseInterface $response */
				$response = $this->get_payments_api_client()->getCurrenciesRequest();
				/** @var GetCurrenciesResponse $responseEntity */
				$responseEntity = $response->getResponseEntity();
				$this->currencies = $responseEntity->getResult();
			}

			if ( 'EUR' !== $entity_currency ) {
				/**
				 * payever's base currency is EUR, so we can only convert from EUR
				 */
				$entity_price = $entity_price / $this->currencies[ $entity_currency ]->getRate();
			}

			if ( 'EUR' === $default_currency ) {
				return round( $entity_price, 2 );
			}

			/**
			 * non-EUR <> non-EUR conversion
			 */
			return round( $entity_price * $this->currencies[ $default_currency ]->getRate(), 2 );
		}

		return $entity_price;
	}

	/**
	 * Processes product categories
	 *
	 * @param WC_Product $wc_product
	 * @param ProductRequestEntity $product_entity
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	private function process_product_categories( WC_Product $wc_product, ProductRequestEntity $product_entity ) {
		$payever_categories = $product_entity->getCategories();
		$payever_categories = array_filter( (array) $payever_categories );

		$product_category_ids = array();
		foreach ( $payever_categories as $category ) {
			$wc_category = $this->get_wp_wrapper()->get_category_by_slug( $category->getSlug() );
			if ( $wc_category ) {
				$product_category_ids[] = $wc_category->term_id;
			} else {
				$wc_category = $this->get_wp_wrapper()->wp_insert_term(
					$category->getTitle(),
					'product_cat',
					array(
						'slug' => $category->getSlug(),
					)
				);

				if ( is_wp_error( $wc_category ) ) {
					$term_id = $wc_category->error_data['term_exists'] ?: null;
				} else {
					$term_id = $wc_category['term_id'];
				}
				$product_category_ids[] = $term_id;
			}
		}
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$wc_product->set_category_ids( $product_category_ids );
			$wc_product->save();
		} else {
			$wpdb = $this->get_wpdb();
			foreach ( $product_category_ids as $term_id ) {
				$data = array(
					'term_taxonomy_id' => $term_id,
					'object_id'        => $wc_product->id,
				);
				$wpdb->delete( $wpdb->term_relationships, $data );
				$wpdb->insert( $wpdb->term_relationships, $data );
			}
		}
	}

	/**
	 * Processes product import
	 *
	 * @param ProductRequestEntity $product_entity
	 *
	 * @return WC_Product
	 */
	private function process_product_import( ProductRequestEntity $product_entity ) {
		$is_new_product = false;
		$post_id = $this->get_product_uuid_manager()->findByUuid( $product_entity->getUuid() );
		if ( ! $post_id || ! $this->get_wp_wrapper()->get_post( $post_id ) ) {
			$post_id = $this->get_wp_wrapper()->wc_get_product_id_by_sku( $product_entity->getSku() );
		}
		if ( ! $post_id ) {
			$post_id = $this->create_new_product( $product_entity );
			$is_new_product = true;
		}

		$this->save_product_data( $post_id, $product_entity );
		$this->save_product_attributes( $product_entity, $post_id );

		$is_new_product
			? $this->action_result->incrementUpdated()
			: $this->action_result->incrementCreated();

		return $this->get_wp_wrapper()->wc_get_product( $post_id );
	}

	/**
	 * @param $product_entity
	 * @param $post_id
	 * @return $this
	 */
	private function save_product_attributes( $product_entity, $post_id ) {
		$variants = $product_entity->getVariants();

		if ( $variants && count( $variants ) ) {
			//for variable products
			$this->get_wp_wrapper()->wp_set_object_terms( $post_id, 'variable', 'product_type' );
			$attributes = array();

			foreach ( $variants as $variant ) {
				foreach ( $variant->getOptions() as $option ) {
					$attributes[ $option->getName() ][] = $option->getValue();
				}
			}
			$product_attributes = $this->import_product_attributes( $post_id, $attributes );
			$this->get_wp_wrapper()->update_post_meta( $post_id, '_product_attributes', $product_attributes );
			$this->import_product_variations( $post_id, $variants, $product_entity->getCurrency() );

			return $this;
		}
		$this->get_wp_wrapper()->wp_set_object_terms( $post_id, 'simple', 'product_type' );

		return $this;
	}

	/**
	 * @param $product_entity
	 * @return int|WP_Error
	 */
	private function create_new_product( $product_entity ) {
		$post = array(
			'post_author'  => 1,
			'post_content' => $product_entity->getDescription(),
			'post_status'  => $product_entity->getActive() ? 'publish' : 'pending',
			'post_title'   => $product_entity->getTitle(),
			'post_parent'  => '',
			'post_type'    => 'product',
		);

		$post_id = $this->get_wp_wrapper()->wp_insert_post( $post );
		$this->get_product_uuid_manager()->add_item(
			array(
				'product_id' => $post_id,
				'uuid'       => $product_entity->getUuid(),
			)
		);

		return $post_id;
	}

	/**
	 * Saves product data
	 *
	 * @param $product_id
	 * @param ProductRequestEntity $product_entity
	 * @param null $entity_currency
	 */
	private function save_product_data( $product_id, ProductRequestEntity $product_entity, $entity_currency = null ) {
		if ( ! $entity_currency ) {
			$entity_currency = $product_entity->getCurrency();
		}
		$this->get_wp_wrapper()->wp_update_post(
			array(
				'ID'           => $product_id,
				'post_title'   => $product_entity->getTitle(),
				'post_content' => $product_entity->getDescription(),
			)
		);

		$this
			->save_product_meta( $product_entity, $entity_currency, $product_id )
			->update_product_terms( $product_entity, $product_id );

		$featured    = true;
		$gallery_ids = array();
		foreach ( $product_entity->getImagesUrl() as $image_url ) {
			$attachment_id = $this->upload_image( $image_url );
			if ( $attachment_id ) {
				if ( $featured ) {
					$this->get_wp_wrapper()->update_post_meta( $product_id, '_thumbnail_id', $attachment_id );
					$featured = false;
					continue;
				}
				$gallery_ids[] = $attachment_id;
			}
		}

		$this->get_wp_wrapper()->update_post_meta( $product_id, '_product_image_gallery', implode( ',', $gallery_ids ) );
	}

	private function update_product_terms( $product_entity, $product_id ) {
		if ( ! $product_entity->isVariant() && $this->get_wp_wrapper()->taxonomy_exists( 'product_visibility' ) ) {
			$visibility_terms = $product_entity->getActive() ? array() : array( 'exclude-from-catalog', 'exclude-from-search' );
			$this->get_wp_wrapper()->wp_set_object_terms( $product_id, $visibility_terms, 'product_visibility' );
		}
		if ( ! $product_entity->isVariant() && $this->get_wp_wrapper()->taxonomy_exists( 'product_type' ) ) {
			$this->get_wp_wrapper()->wp_set_object_terms(
				$product_id,
				count( $product_entity->getVariants() ) ? 'variable' : 'simple',
				'product_type'
			);
		}

		return $this;
	}

	private function save_product_meta( $product_entity, $entity_currency, $product_id ) {
		$price      = $this->process_entity_price( $product_entity->getPrice(), $entity_currency );
		$sale_price = $product_entity->getOnSales()
			? $this->process_entity_price( $product_entity->getSalePrice(), $entity_currency )
			: '';
		$meta_data = array(
			'_visibility'            => $product_entity->getActive() || $product_entity->isVariant() ? 'visible' : 'hidden',
			'_downloadable'          => 'no',
			'_virtual'               => 'no',
			'_regular_price'         => $price,
			'_sale_price'            => $sale_price,
			'_featured'              => 'no',
			'_weight'                => '',
			'_length'                => '',
			'_width'                 => '',
			'_height'                => '',
			'_sku'                   => $product_entity->getSku() ?: str_replace( ' ', '_', strtolower( $product_entity->getTitle() ) ),
			'_sale_price_dates_from' => '',
			'_sale_price_dates_to'   => '',
			'_price'                 => $price,
			'_backorders'            => 'no',
		);
		if ( ProductTypeEnum::TYPE_DIGITAL === $product_entity->getType() ) {
			$meta_data['_downloadable'] = 'yes';
			$meta_data['_virtual'] = 'yes';
		}
		if ( $product_entity->getShipping() ) {
			$meta_data['_weight'] = $product_entity->getShipping()->getWeight();
			$meta_data['_length'] = $product_entity->getShipping()->getLength();
			$meta_data['_width'] = $product_entity->getShipping()->getWidth();
			$meta_data['_height'] = $product_entity->getShipping()->getHeight();
		}
		foreach ( $meta_data as $meta_key => $meta_value ) {
			$this->get_wp_wrapper()->update_post_meta( $product_id, $meta_key, $meta_value );
		}

		return $this;
	}

	/**
	 * Uploads image
	 *
	 * @param $image_url
	 *
	 * @return int
	 * @codeCoverageIgnore
	 */
	private function upload_image( $image_url ) {
		if ( $this->skip_fs ) {
			return 0;
		}
		$upload_dir      = wp_upload_dir();
		$file_name       = basename( $image_url );
		$upload_file     = $upload_dir['path'] . '/' . $file_name;
		$upload_file_url = $upload_dir['url'] . '/' . $file_name;

		$attachID = attachment_url_to_postid( $upload_file_url );

		if ( $attachID ) {
			return $attachID;
		}

		if ( $this->download_image( $upload_file, $image_url ) ) {
			$wp_filetype = wp_check_filetype( $file_name, null );
			$attachment  = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => $file_name,
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attachID = wp_insert_attachment( $attachment, $upload_file );
			if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
				$relativeFilename = str_replace( '/', DIRECTORY_SEPARATOR, 'wp-admin/includes/image.php' );
				include_once ABSPATH . $relativeFilename;
			}
			$image_post = get_post( $attachID );
			$full_path  = get_attached_file( $image_post->ID );
			$meta_data  = wp_generate_attachment_metadata( $attachID, $full_path );

			wp_update_attachment_metadata( $attachID, $meta_data );
		}

		return $attachID;
	}

	/**
	 * Downloads image
	 *
	 * @param $local_path
	 * @param $url
	 *
	 * @return bool
	 * @codeCoverageIgnore
	 */
	private function download_image( $local_path, $url ) {
		if ( is_file( $local_path ) && ! is_writable( $local_path ) ) {
			return false;
		}
		if ( ! is_writable( dirname( $local_path ) ) ) {
			return false;
		}

		try {
			$this->get_payments_api_client()->getHttpClient()->download( $url, $local_path );
		} catch ( \Exception $exception ) {
			return false;
		} catch ( \Error $error ) {
			return false;
		}

		return true;
	}

	/**
	 * Gets attribute id by name
	 *
	 * @param $name
	 *
	 * @return int
	 */
	private function get_attribute_id_by_name( $name ) {
		$wpdb = $this->get_wpdb();
		$attribute_id = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT attribute_id FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name LIKE %s",
				$name
			)
		);

		return reset( $attribute_id );
	}
}
