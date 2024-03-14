<?php
namespace ZPOS\Structure;
use ZPOS\Admin\Woocommerce\Products;
trait ProductResponse
{
	public function prepare_object_for_response($object, $request)
	{
		$context = !empty($request['context']) ? $request['context'] : 'view';
		$data = $this->get_product_data($object, $context);

		// Add variations to variable products.
		if ($object->is_type('variable') && $object->has_child()) {
			$data['variations'] = $object->get_children();
		}

		// Add grouped products data.
		if ($object->is_type('grouped') && $object->has_child()) {
			$data['grouped_products'] = $object->get_children();
		}

		//$data = $this->add_additional_fields_to_object($data, $request);
		$data = $this->filter_response_by_context($data, $context);
		$response = rest_ensure_response($data);

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WC_Data $object Object data.
		 * @param WP_REST_Request $request Request object.
		 */
		return apply_filters(
			"woocommerce_rest_prepare_{$this->post_type}_object",
			$response,
			$object,
			$request
		);
	}

	public function get_product_data($product, $context = 'view')
	{
		$product_id = $product->get_id();
		$result = parent::get_product_data($product, $context = 'view');
		$result = $this->adjust_with_barcode_fields($result, $product_id);
		$result['stylization'] = Products::get_stylization($product);
		$result['in_stock'] = $product->is_in_stock();
		$result['stock_status'] = $product->get_stock_status();
		$result['taxonomies'] = apply_filters('zpos_get_product_taxonomies', [], $product_id);
		unset($result['permalink']);
		unset($result['date_created']);
		unset($result['date_created_gmt']);
		unset($result['date_modified']);
		unset($result['date_modified_gmt']);
		unset($result['catalog_visibility']);
		unset($result['description']);
		unset($result['short_description']);
		unset($result['regular_price']);
		unset($result['sale_price']);
		unset($result['date_on_sale_from']);
		unset($result['date_on_sale_from_gmt']);
		unset($result['date_on_sale_to']);
		unset($result['date_on_sale_to_gmt']);
		unset($result['price_html']);
		unset($result['purchasable']);
		unset($result['total_sales']);
		unset($result['virtual']);
		unset($result['downloadable']);
		unset($result['downloads']);
		unset($result['download_limit']);
		unset($result['download_expiry']);
		unset($result['external_url']);
		unset($result['button_text']);
		unset($result['backorders']);
		unset($result['backorders_allowed']);
		unset($result['backordered']);
		unset($result['sold_individually']);
		unset($result['weight']);
		unset($result['dimensions']);
		unset($result['reviews_allowed']);
		unset($result['average_rating']);
		unset($result['rating_count']);
		unset($result['related_ids']);
		unset($result['upsell_ids']);
		unset($result['cross_sell_ids']);
		unset($result['purchase_note']);
		unset($result['default_attributes']);
		unset($result['grouped_products']);
		return $result;
	}

	private function get_barcode($product_id, $barcode_field_name, $variation_barcode_field_name)
	{
		$variation_barcode = get_post_meta($product_id, $variation_barcode_field_name, true);
		return $variation_barcode
			? $variation_barcode
			: get_post_meta($product_id, $barcode_field_name, true);
	}

	private function adjust_with_barcode_fields($value, $product_id)
	{
		$value[Products::PRODUCT_BARCODE_NAME] = $this->get_barcode(
			$product_id,
			Products::PRODUCT_BARCODE_NAME,
			Products::PRODUCT_VARIATION_BARCODE_NAME
		);
		$value[Products::PRODUCT_BARCODE_SECONDARY_NAME] = $this->get_barcode(
			$product_id,
			Products::PRODUCT_BARCODE_SECONDARY_NAME,
			Products::PRODUCT_VARIATION_BARCODE_SECONDARY_NAME
		);
		$value[Products::PRODUCT_BARCODE_ALTERNATIVE_NAME] = $this->get_barcode(
			$product_id,
			Products::PRODUCT_BARCODE_ALTERNATIVE_NAME,
			Products::PRODUCT_VARIATION_BARCODE_ALTERNATIVE_NAME
		);

		return $value;
	}

	public function get_item_schema()
	{
		$weight_unit = get_option('woocommerce_weight_unit');
		$dimension_unit = get_option('woocommerce_dimension_unit');
		$schema = [
			'$schema' => 'http://json-schema.org/draft-04/schema#',
			'title' => $this->post_type,
			'type' => 'object',
			'properties' => [
				'id' => [
					'description' => __('Unique identifier for the resource.', 'woocommerce'),
					'type' => 'integer',
					'context' => ['view', 'edit'],
					'readonly' => true,
				],
				'name' => [
					'description' => __('Product name.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
				],
				'slug' => [
					'description' => __('Product slug.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
				],
				'type' => [
					'description' => __('Product type.', 'woocommerce'),
					'type' => 'string',
					'default' => 'simple',
					'enum' => array_keys(wc_get_product_types()),
					'context' => ['view', 'edit'],
				],
				'status' => [
					'description' => __('Product status (post status).', 'woocommerce'),
					'type' => 'string',
					'default' => 'publish',
					'enum' => array_keys(get_post_statuses()),
					'context' => ['view', 'edit'],
				],
				'featured' => [
					'description' => __('Featured product.', 'woocommerce'),
					'type' => 'boolean',
					'default' => false,
					'context' => ['view', 'edit'],
				],
				'sku' => [
					'description' => __('Unique identifier.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
				],
				'price' => [
					'description' => __('Current product price.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
					'readonly' => true,
				],

				'on_sale' => [
					'description' => __('Shows if the product is on sale.', 'woocommerce'),
					'type' => 'boolean',
					'context' => ['view', 'edit'],
					'readonly' => true,
				],
				'tax_status' => [
					'description' => __('Tax status.', 'woocommerce'),
					'type' => 'string',
					'default' => 'taxable',
					'enum' => ['taxable', 'shipping', 'none'],
					'context' => ['view', 'edit'],
				],
				'tax_class' => [
					'description' => __('Tax class.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
				],
				'manage_stock' => [
					'description' => __('Stock management at product level.', 'woocommerce'),
					'type' => 'boolean',
					'default' => false,
					'context' => ['view', 'edit'],
				],
				'stock_quantity' => [
					'description' => __('Stock quantity.', 'woocommerce'),
					'type' => 'integer',
					'context' => ['view', 'edit'],
				],
				'in_stock' => [
					'description' => __(
						'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.',
						'woocommerce'
					),
					'type' => 'boolean',
					'default' => true,
					'context' => ['view', 'edit'],
				],
				'shipping_required' => [
					'description' => __('Shows if the product need to be shipped.', 'woocommerce'),
					'type' => 'boolean',
					'context' => ['view', 'edit'],
					'readonly' => true,
				],
				'shipping_taxable' => [
					'description' => __(
						'Shows whether or not the product shipping is taxable.',
						'woocommerce'
					),
					'type' => 'boolean',
					'context' => ['view', 'edit'],
					'readonly' => true,
				],
				'shipping_class' => [
					'description' => __('Shipping class slug.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
				],
				'shipping_class_id' => [
					'description' => __('Shipping class ID.', 'woocommerce'),
					'type' => 'integer',
					'context' => ['view', 'edit'],
					'readonly' => true,
				],
				'parent_id' => [
					'description' => __('Product parent ID.', 'woocommerce'),
					'type' => 'integer',
					'context' => ['view', 'edit'],
				],
				'purchase_note' => [
					'description' => __('Optional note to send the customer after purchase.', 'woocommerce'),
					'type' => 'string',
					'context' => ['view', 'edit'],
				],
				'categories' => [
					'description' => __('List of categories.', 'woocommerce'),
					'type' => 'array',
					'context' => ['view', 'edit'],
					'items' => [
						'type' => 'object',
						'properties' => [
							'id' => [
								'description' => __('Category ID.', 'woocommerce'),
								'type' => 'integer',
								'context' => ['view', 'edit'],
							],
							'name' => [
								'description' => __('Category name.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'slug' => [
								'description' => __('Category slug.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
						],
					],
				],
				'tags' => [
					'description' => __('List of tags.', 'woocommerce'),
					'type' => 'array',
					'context' => ['view', 'edit'],
					'items' => [
						'type' => 'object',
						'properties' => [
							'id' => [
								'description' => __('Tag ID.', 'woocommerce'),
								'type' => 'integer',
								'context' => ['view', 'edit'],
							],
							'name' => [
								'description' => __('Tag name.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'slug' => [
								'description' => __('Tag slug.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
						],
					],
				],
				'images' => [
					'description' => __('List of images.', 'woocommerce'),
					'type' => 'array',
					'context' => ['view', 'edit'],
					'items' => [
						'type' => 'object',
						'properties' => [
							'id' => [
								'description' => __('Image ID.', 'woocommerce'),
								'type' => 'integer',
								'context' => ['view', 'edit'],
							],
							'date_created' => [
								'description' => __(
									"The date the image was created, in the site's timezone.",
									'woocommerce'
								),
								'type' => 'date-time',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'date_created_gmt' => [
								'description' => __('The date the image was created, as GMT.', 'woocommerce'),
								'type' => 'date-time',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'date_modified' => [
								'description' => __(
									"The date the image was last modified, in the site's timezone.",
									'woocommerce'
								),
								'type' => 'date-time',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'date_modified_gmt' => [
								'description' => __('The date the image was last modified, as GMT.', 'woocommerce'),
								'type' => 'date-time',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'src' => [
								'description' => __('Image URL.', 'woocommerce'),
								'type' => 'string',
								'format' => 'uri',
								'context' => ['view', 'edit'],
							],
							'name' => [
								'description' => __('Image name.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
							],
							'alt' => [
								'description' => __('Image alternative text.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
							],
							'position' => [
								'description' => __(
									'Image position. 0 means that the image is featured.',
									'woocommerce'
								),
								'type' => 'integer',
								'context' => ['view', 'edit'],
							],
						],
					],
				],
				'attributes' => [
					'description' => __('List of attributes.', 'woocommerce'),
					'type' => 'array',
					'context' => ['view', 'edit'],
					'items' => [
						'type' => 'object',
						'properties' => [
							'id' => [
								'description' => __('Attribute ID.', 'woocommerce'),
								'type' => 'integer',
								'context' => ['view', 'edit'],
							],
							'name' => [
								'description' => __('Attribute name.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
							],
							'position' => [
								'description' => __('Attribute position.', 'woocommerce'),
								'type' => 'integer',
								'context' => ['view', 'edit'],
							],
							'visible' => [
								'description' => __(
									"Define if the attribute is visible on the \"Additional information\" tab in the product's page.",
									'woocommerce'
								),
								'type' => 'boolean',
								'default' => false,
								'context' => ['view', 'edit'],
							],
							'variation' => [
								'description' => __(
									'Define if the attribute can be used as variation.',
									'woocommerce'
								),
								'type' => 'boolean',
								'default' => false,
								'context' => ['view', 'edit'],
							],
							'options' => [
								'description' => __(
									'List of available term names of the attribute.',
									'woocommerce'
								),
								'type' => 'array',
								'context' => ['view', 'edit'],
								'items' => [
									'type' => 'string',
								],
							],
						],
					],
				],
				'variations' => [
					'description' => __('List of variations IDs.', 'woocommerce'),
					'type' => 'array',
					'context' => ['view', 'edit'],
					'items' => [
						'type' => 'integer',
					],
					'readonly' => true,
				],
				'meta_data' => [
					'description' => __('Meta data.', 'woocommerce'),
					'type' => 'array',
					'context' => ['view', 'edit'],
					'items' => [
						'type' => 'object',
						'properties' => [
							'id' => [
								'description' => __('Meta ID.', 'woocommerce'),
								'type' => 'integer',
								'context' => ['view', 'edit'],
								'readonly' => true,
							],
							'key' => [
								'description' => __('Meta key.', 'woocommerce'),
								'type' => 'string',
								'context' => ['view', 'edit'],
							],
							'value' => [
								'description' => __('Meta value.', 'woocommerce'),
								'type' => 'mixed',
								'context' => ['view', 'edit'],
							],
						],
					],
				],
			],
		];

		return $this->add_additional_fields_schema($schema);
	}
}
