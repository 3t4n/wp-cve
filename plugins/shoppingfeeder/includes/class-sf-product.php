<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require_once 'class-sf-resource.php';

class SF_Product extends SF_Resource
{
	protected $_categories = array();
	protected $_processed_categories = array();

	public function __construct()
	{
		//load all categories to lookup later
		$product_categories = array();

		$terms = get_terms( 'product_cat', array( 'hide_empty' => false, 'fields' => 'ids' ) );

		foreach ( $terms as $term_id ) {

			$product_categories[$term_id] = current( $this->get_product_category( $term_id ) );
		}

		$this->_categories = $product_categories;
	}

	public function get_full_category_path( $category_id ) {
		$category = $this->_categories[$category_id];
		$parent_id = $category['parent'];
		$path = $category['name'];
		while ( $parent_id != 0 )
		{
			$category = $this->_categories[$parent_id];
			$path = $category['name'] . ' > ' . $path;
			$parent_id = $category['parent'];
		}
		return $path;
	}

	/**
	 * Get the product category for the given ID
	 *
	 * @since 2.2
	 * @param string $id product category term ID
	 * @param string|null $fields fields to limit response to
	 * @return array
	 */
	public function get_product_category( $id ) {

		$id = absint( $id );

		$term = get_term( $id, 'product_cat' );

		$product_category = array(
			'id'          => intval( $term->term_id ),
			'name'        => $term->name,
			'slug'        => $term->slug,
			'parent'      => $term->parent,
			'description' => $term->description,
			'count'       => intval( $term->count ),
		);

		return array( $product_category, $id, $term, $this );
	}

	public function get_deepest_category($categoryIds) {
		$longestCategoryPathCount = 0;
		$longestCategoryPathId = '';

		//Create a compressed key using the input array and use that as a reference for category combinations that have already been checked
		$categoryKeys = json_encode($categoryIds);
		$categoryKeysCompressed = base64_encode($categoryKeys);

		if(array_key_exists($categoryKeysCompressed, $this->_processed_categories) && !is_null($this->_processed_categories[$categoryKeysCompressed])) {
			return $this->_processed_categories[$categoryKeysCompressed];
		}

		if(!is_null($categoryIds) && !empty($categoryIds)) {
			foreach ($categoryIds as $categoryId) {
				$categoryPathCount = 1;
				$category = $this->_categories[$categoryId];
				$parent_id = $category['parent'];
				while ($parent_id != 0) {
					$category = $this->_categories[$parent_id];
					$parent_id = $category['parent'];
					$categoryPathCount++;
				}
				if ($categoryPathCount > $longestCategoryPathCount) {
					$longestCategoryPathCount = $categoryPathCount;
					$longestCategoryPathId = $categoryId;
				}
			}
		}
		$this->_processed_categories[$categoryKeysCompressed] = $longestCategoryPathId;
		return $longestCategoryPathId;
	}

	public function get_products( $page = null, $num_per_page = 1000, $price_currency = null, $price_currency_rate = null, $allow_variants = true, $overrideCurrencyConversion = false, $product_id = null )
	{
		// set base query arguments
		$query_args = array(
			'fields'      => 'ids',
			'post_type'   => 'product',
			'post_status' => 'publish',
			'orderby'     => 'ids',
			'order'       => 'ASC',
			'meta_query'  => array(),
		);

		if (!is_null($product_id))
		{
			$query_args['post__in'] = array($product_id);
		}

		if ( ! empty( $args['type'] ) ) {

			$types = explode( ',', $args['type'] );

			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $types,
				),
			);

			unset( $args['type'] );
		}

		//$query_args['is_paged'] = true;
		$args['page'] = $page;
		$args['limit'] = $num_per_page;

		$query_args = $this->merge_query_args( $query_args, $args );

		$query = new WP_Query( $query_args );

		$products = array();

		foreach ( $query->posts as $product_id ) {

			list( $product_data, $variations) = $this->get_product( $product_id, null, $price_currency, $price_currency_rate, $allow_variants, $overrideCurrencyConversion);

			//only use the base product if we don't have variations
			if (!isset($variations) || is_null($variations) || (is_array($variations) && empty($variations)))
			{
				$products[] = $product_data;
			}

			if ( $allow_variants ) {
				if ( !is_null( $variations ) ) {
					foreach ( $variations as $variation ) {
						$base_product = $product_data;

						//remove the extra images as they don't apply to the variant

						//edit 04-08-2021 Allow extra images from the base product to go to the variations
						/*
						unset( $base_product['extra_images'] );
						*/

						//unset empty values from variant so that we get them from base product in the array_merge coming up
						foreach ($variation as $key => $value)
						{
							if (empty($value))
							{
								unset($variation[$key]);
							}
						}

						//if we've only got a placeholder image, then just unset it
						if ( isset( $variation['image'][0]['image_url'] ) && $variation['image'][0]['is_placeholder'] === true) {
							unset( $variation['image_url'] );
							unset( $variation['image'] );
						}

						//bring in the variant data
						$variant = array_merge( $base_product, $variation );

						$includedAttributes = array();

						//create the title from attributes
						if ( !empty( $variant['attributes'] ) ) {
							foreach ( $variant['attributes'] as $attribute ) {
								if ( !empty( $attribute['option'] ) ) {
									$variant['title'] .= ' '.$attribute['option'];
								}
								if(!empty( $attribute['name'] ) ) {
									$includedAttributes[$attribute['name']] = $attribute['option'];
								}
							}
						}

						if ( isset( $variant['image'][0]['image_url'] )) {
							$variant['image_url'] = $variant['image'][0]['image_url'];
							unset( $variant['image'] );
						}

						//Trickle attributes down from base product to variants
						foreach($base_product['attributes'] as $key => $value) {
							if(empty($variant['attributes'][$key])) {
//                            	$attrName = ucwords( str_replace( 'attribute_', '', str_replace( 'pa_', '', $key ) ) );
								$variant['attributes'][$key] = $value;
							}
						}
						$products[] = $variant;
					}
				}
			}
		}

		return $products;

	}

	/**
	 * Get the product for the given ID
	 *
	 * @since 2.1
	 * @param int $id the product ID
	 * @param string $fields
	 * @return array
	 */
	public function get_product( $id, $fields = null, $price_currency = null, $price_currency_rate = null, $allow_variants = true, $overrideCurrencyConversion = false ) {

		$product = wc_get_product( $id );

		// add data that applies to every product type
		$product_data = $this->get_product_data( $product, $price_currency, $price_currency_rate, $overrideCurrencyConversion );
		if ( $product->is_type( 'variable' ) )
		{
//	        $prices = $product->get_variation_prices();
//	        $lowest = reset( $prices['price'] );
//	        $highest = end( $prices['price'] );
//            $product_data['sale_price'] = $product->min_variation_price;
			$product_data['sale_price'] = $product->get_variation_sale_price( 'min', false );
			$product_data['price'] = $product->get_variation_regular_price( 'min', false );
		}

		$variations = null;

		if ( $allow_variants ) {
			// add variations to variable products
			if ( $product->is_type( 'variable' ) && $product->has_child() ) {

				$variations = $this->get_variation_data( $product, $price_currency, $price_currency_rate );
			}

			// add the parent product data to an individual variation
			if ( $product->is_type( 'variation' ) ) {

				//$product_data['parent'] = $this->get_product_data( $product->parent );
			}
		}

		return array( $product_data, $variations );
	}

	/**
	 * Get standard product data that applies to every product type
	 *
	 * @since 2.1
	 * @param WC_Product $product
	 * @return array
	 */
	private function get_product_data( $product, $price_currency = null, $price_currency_rate = null, $overrideCurrencyConversion = false )
	{
		$brand = $manufacturer = $mpn = $gtin = '';
		$attributes = $this->get_attributes( $product );
		$useful_attributes = array();
		foreach ( $attributes as $attribute )
		{
			$attribute_label = $attribute['name'];
			$value = current( $attribute['options'] );

			$useful_attributes[$attribute_label] = $value;

			if ( preg_match('/^manufacturer$/i', $attribute_label ) ) {
				$manufacturer = $value;
			}

			if ( preg_match('/^brand$/i', $attribute_label ) ) {
				$brand = $value;
			}

			if ( preg_match('/^(mpn|model|model number)$/i', $attribute_label ) ) {
				$mpn = $value;
			}

			if ( preg_match( '/^(gtin|ean|upc)$/i', $attribute_label ) ) {
				$gtin = $value;
			}
		}

		//Support for Advanced Custom Fields
		if ( function_exists( "get_field_objects") && function_exists( "acf_get_field_group") )
		{
			$field_objects = get_field_objects( $product->get_id() );

			if ( !empty( $field_objects ) )
			{
				foreach ($field_objects as $field_object)
				{
					$field_key = $field_object['key'];
					$field_parent_id = $field_object['parent'];
					$group = acf_get_field_group($field_parent_id);
					if ( !empty($group) )
					{
						$group_key = $group['key'];
					}

					if ( !empty($field_key) && !empty($group_key) )
					{
						$useful_attributes[$group_key . '::' . $field_key] = $field_object['value'];
					}
				}
			}
		}

		//last chance to fetch data from post_meta for model number
		if ( !isset( $mpn ) || empty( $mpn ) )
		{
			$mpn = get_post_meta( $product->get_id(), '_model_number', true );
			if ( empty( $mpn ) )
			{
				$mpn = get_post_meta( $product->get_id(), '_model_no', true );
			}
			$useful_attributes['meta_mpn'] = $mpn;
		}

		//last chance to fetch data from post_meta for upc
		if ( !isset( $gtin ) || empty( $gtin ) )
		{
			$gtin = get_post_meta( $product->get_id(), '_gtin', true );
			if ( empty( $gtin ) )
			{
				$gtin = get_post_meta( $product->get_id(), '_upc', true );
			}
			$useful_attributes['meta_gtin'] = $gtin;
		}

		//support for WCmp
		if ( !isset( $gtin ) || empty( $gtin ) )
		{
			if (function_exists( "get_wcmp_vendor_settings") )
			{
				if( get_wcmp_vendor_settings('is_gtin_enable', 'general') == 'Enable' )
				{
					$gtin = get_post_meta( $product->get_id(), '_wcmp_gtin_code', true);
					if ( empty( $gtin ) )
					{
						$gtin = null;
					}
					$useful_attributes['wcmp_gtin'] = $gtin;
				}
			}
		}

		//WPM support
		if ( !isset( $gtin ) || empty( $gtin ) )
		{
			if ( function_exists( 'wpm_get_code_gtin_by_product') )
			{
				$gtin = wpm_get_code_gtin_by_product($product);
				if ( empty( $gtin ) )
				{
					$gtin = null;
				}
				$useful_attributes['wpm_gtin'] = $gtin;
			}
		}

		//EAN for Woocommerce support
		if ( !isset( $gtin ) || empty( $gtin ) )
		{
			if ( 'yes' === get_option( 'alg_wc_ean_plugin_enabled', 'yes' ) )
			{
				$gtin = get_post_meta($product->get_id(), get_option( 'alg_wc_ean_meta_key', '_alg_ean' ), true);

				if (empty($gtin))
				{
					$gtin = null;
				}
				$useful_attributes['ean_for_woocommerce_gtin'] = $gtin;
			}
		}

		//WooCommerce Brands
		if ( empty( $brand ) )
		{
			//if WooCommerce brands is being used
			if ( defined( 'WC_BRANDS_VERSION' ) )
			{
				$terms = get_the_terms( $product->get_id(), 'product_brand' );
				if ( is_array( $terms ) ) {
					$brands = [];
					foreach ( $terms as $term ) {
						$brands[] = $term->name;
						if ( empty( $term->parent ) ) {
							$brand = $term->name;
						}
					}

					if( empty( $brand ) && !empty( $brands[0] ) )
					{
						$brand = $brands[0];
					}
				}
			}
		}

		//Perfect Brands
		//https://github.com/franmastromarino/perfect-woocommerce-brands/
		if ( empty( $brand ) )
		{
			if ( defined( 'PWB_PLUGIN_NAME' ) )
			{
				$brandList = wp_get_object_terms( $product->get_id(), 'pwb-brand' );

				if ( ! empty( $brandList ) ) {
					$brands = [];
					foreach ( $brandList as $term ) {
						$brands[] = $term->name;
					}

					if( ! empty($brands[0] ) )
					{
						$brand = $brands[0];
					}
				}
			}
		}

		//support for WPSSO
		if ( ( class_exists( 'WpssoWcmdSearch' ) ) )
		{
			if ( !isset( $gtin ) || empty( $gtin ) )
			{
				$gtin = get_post_meta($product->get_id(), '_wpsso_product_gtin', true);
				if (empty($gtin))
				{
					$gtin = get_post_meta($product->get_id(), '_wpsso_product_gtin14', true);
					if (empty($gtin))
					{
						$gtin = get_post_meta($product->get_id(), '_wpsso_product_gtin13', true);
						if (empty($gtin))
						{
							$gtin = get_post_meta($product->get_id(), '_wpsso_product_gtin12', true);
							if (empty($gtin))
							{
								$gtin = get_post_meta($product->get_id(), '_wpsso_product_isbn', true);
								if (empty($gtin))
								{
									$gtin = null;
								}
							}
						}
					}
				}
				$useful_attributes['wpsso_gtin'] = $gtin;
			}

			if ( !isset( $mpn ) || empty( $mpn ) )
			{

				$mpn = get_post_meta($product->get_id(), '_wpsso_product_mfr_part_no', true);
				if (empty($mpn))
				{
					$mpn = null;
				}
				$useful_attributes['wpsso_mpn'] = $mpn;
			}
		}

		//last chance check if there's the Google PlA plugin
		if (empty($gtin))
		{
			if ( defined('WC_GLA_VERSION') )
			{
				try
				{
					$attribute_manager = new \Automattic\WooCommerce\GoogleListingsAndAds\Product\Attributes\AttributeManager();

					//can only get the GTIN for a non-variant
					if (!$product->is_type( 'variable' ))
					{
						$gtin_value = $attribute_manager->get_value($product, \Automattic\WooCommerce\GoogleListingsAndAds\Product\Attributes\GTIN::get_id());

						if (!empty($gtin_value))
						{
							$gtin = $gtin_value;
						}
					}
				} catch (Exception $e)
				{
					//do nothing if this doesn't work
				}
			}
		}

		$title = $product->get_title();
		$image_url = wp_get_attachment_url( get_post_thumbnail_id( $product->is_type( 'variation' ) ? $product->variation_id : $product->get_id() ) );
		$image_modified_time = null;
		$all_images = $this->get_images( $product );
		if ( count( $all_images ) > 0 )
		{
			foreach ( $all_images as $image_info )
			{
				if ( isset( $image_info['image_url'] ) && $image_info['image_url'] == $image_url && isset( $image_info['image_modified_time'] ) )
				{
					$image_modified_time = $image_info['image_modified_time'];
				}
			}
		}

		$sale_price_effective_date = '';
		if  ($to = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) )
		{
			$from = get_post_meta( $product->get_id(), '_sale_price_dates_from', true );
			$sale_price_effective_date = ShoppingFeeder::format_datetime( $from ) . '/' . ShoppingFeeder::format_datetime( $to );
		}

		//add the sale price, if for some reason this is not currently on sale
		$useful_attributes['is_on_sale'] = $product->is_on_sale();
		$useful_attributes['current_sale_price'] = $product->get_sale_price() ? $product->get_sale_price() : 0;

		if($overrideCurrencyConversion) {
			$price = $product->get_regular_price("");
			$sale_price = ( $product->is_on_sale() ) ? ( $product->get_sale_price("") ? $product->get_sale_price("") : 0 ) : 0;
		} else {
			$price = $product->get_regular_price();
			$sale_price = ( $product->is_on_sale() ) ? ( $product->get_sale_price() ? $product->get_sale_price() : 0 ) : 0;
		}

		if ( !is_null( $price_currency ) && !is_null( $price_currency_rate ) )
		{
			if (!empty($price) && intval($price) > 0)
			{
				$price = $price * $price_currency_rate;
			}
			if (!empty($sale_price) && intval($sale_price) > 0)
			{
				$sale_price = $sale_price * $price_currency_rate;
			}
		}

		//support for COGS
		$cogs = null;
		if (function_exists( 'alg_wc_cog' ) )
		{
			$cogs = alg_wc_cog()->core->products->get_product_cost( $product->get_id(), ['convert_to_number' => true]);
			if ($cogs > 0)
			{
				$cogs = wc_format_decimal( $cogs, 2 );
			}
			else
			{
				$cogs = null;
			}
		}

		return array(
			'internal_id'        => (int) $product->get_id(),
			'category'           => $this->get_full_category_path( $this->get_deepest_category( wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'ids' ) ) ) ),
			'title'              => $title,
			'brand'              => !empty( $brand ) ? $brand : ( !empty( $manufacturer ) ? $manufacturer : '' ),
			'manufacturer'       => !empty( $manufacturer ) ? $manufacturer : ( !empty( $brand ) ? $brand : '' ),
			'mpn'                => !empty( $mpn ) ? $mpn : $product->get_sku(),
			'description'        => wpautop( do_shortcode( $product->get_description() ) ),
			'short_description'  => wpautop( do_shortcode( $product->get_short_description() ) ),
			'weight'             => $product->get_weight() ? wc_format_decimal( $product->get_weight(), 2 ) : null,
			'sku'                => $product->get_sku(),
			'gtin'               => $gtin,
			'price'              => wc_format_decimal( $price, 2 ),
			'sale_price'         => wc_format_decimal( $sale_price, 2 ),
			'sale_price_effective_date'         => $sale_price_effective_date,
			'cost_price'         => $cogs,

			'tags'               => wc_get_object_terms( $product->get_id(), 'product_tag', 'name' ),

			'delivery_cost'      => '',
			'tax'                => '',

			'url'                => $product->get_permalink(),
			'image'              => $all_images,
			'image_url'          => $image_url,
			'image_modified_time'=> $image_modified_time,
			'extra_images'       => $all_images,
			'availability'       => $product->is_in_stock() ? 'in stock' : 'out of stock',
			//            'availability_date'  => $product->is_in_stock() ? 'in stock' : 'out of stock',
			'quantity'           => (int) $product->get_stock_quantity(),
			'condition'          => 'new',
			'attributes'         => $useful_attributes,
			'dimensions'         => array(
				'length' => $product->get_length(),
				'width'  => $product->get_width(),
				'height' => $product->get_height(),
				'unit'   => get_option( 'woocommerce_dimension_unit' ),
			),

			'internal_update_time'         => ShoppingFeeder::format_datetime( $product->get_date_modified(), true ),


			//            'created_at'         => ShoppingFeeder::format_datetime( $product->get_post_data()->post_date_gmt ),
			//            'type'               => $product->product_type,
			//            'status'             => $product->get_post_data()->post_status,
			//            'downloadable'       => $product->is_downloadable(),
			//            'virtual'            => $product->is_virtual(),
			//            'price_html'         => $product->get_price_html(),
			//            'taxable'            => $product->is_taxable(),
			//            'tax_status'         => $product->get_tax_status(),
			//            'tax_class'          => $product->get_tax_class(),
			//            'managing_stock'     => $product->managing_stock(),
			//            'backorders_allowed' => $product->backorders_allowed(),
			//            'backordered'        => $product->is_on_backorder(),
			//            'sold_individually'  => $product->is_sold_individually(),
			//            'purchaseable'       => $product->is_purchasable(),
			//            'featured'           => $product->is_featured(),
			//            'visible'            => $product->is_visible(),
			//            'catalog_visibility' => $product->visibility,
			//            'shipping_required'  => $product->needs_shipping(),
			//            'shipping_taxable'   => $product->is_shipping_taxable(),
			//            'shipping_class'     => $product->get_shipping_class(),
			//            'shipping_class_id'  => ( 0 !== $product->get_shipping_class_id() ) ? $product->get_shipping_class_id() : null,
			//            'categories'         => wp_get_post_terms( $product->id, 'product_cat', array( 'fields' => 'names' ) ),
			//            'tags'               => wp_get_post_terms( $product->id, 'product_tag', array( 'fields' => 'names' ) ),
			//            'variations'         => array(),
			//            'parent'             => array(),
			//            'attributes'         => $attributes,
		);
	}

	/**
	 * Get an individual variation's data
	 *
	 * @since 2.1
	 * @param WC_Product $product
	 * @return array
	 */
	private function get_variation_data( $product, $price_currency = null, $price_currency_rate = null, $overrideCurrencyConversion = false ) {

		$variations = array();

		foreach ( $product->get_children() as $child_id ) {

			/** @var WC_Product_Variation $variation */
			$variation = wc_get_product( $child_id );

			if ( $variation === false || ! $variation->exists() ) {
				continue;
			}

			$brand = $manufacturer = $mpn = $gtin = '';
			$attributes = $this->get_attributes( $variation );
			$useful_attributes = array();
			foreach ( $attributes as $attribute_label => $value )
			{
				$useful_attributes[$attribute_label] = $value;

				if ( preg_match('/^manufacturer$/i', $attribute_label ) ) {
					$manufacturer = $value;
				}

				if ( preg_match('/^brand$/i', $attribute_label ) ) {
					$brand = $value;
				}

				if ( preg_match('/^(mpn|model|model number)$/i', $attribute_label ) ) {
					$mpn = $value;
				}

				if ( preg_match( '/^(gtin|ean|upc)$/i', $attribute_label ) ) {
					$gtin = $value;
				}
			}

			//Support for Advanced Custom Fields
			if ( function_exists( "get_field_objects") && function_exists( "acf_get_field_group") )
			{
				$field_objects = get_field_objects( $variation->get_id() );

				if ( !empty( $field_objects ) )
				{
					foreach ($field_objects as $field_object)
					{
						$field_key = $field_object['key'];
						$field_parent_id = $field_object['parent'];
						$group = acf_get_field_group($field_parent_id);
						if ( !empty($group) )
						{
							$group_key = $group['key'];
						}

						if ( !empty($field_key) && !empty($group_key) )
						{
							$useful_attributes[$group_key . '::' . $field_key] = $field_object['value'];
						}
					}
				}
			}

			//last chance to fetch data from post_meta for model number
			if ( !isset( $mpn ) || empty( $mpn ) )
			{
				$mpn = get_post_meta( $variation->get_id(), '_model_number', true );
				if ( empty( $mpn ) )
				{
					$mpn = get_post_meta( $variation->get_id(), '_model_no', true );
				}
				$useful_attributes['meta_mpn'] = $mpn;
			}

			//last chance to fetch data from post_meta for upc
			if ( !isset( $gtin ) || empty( $gtin ) )
			{
				$gtin = get_post_meta( $variation->get_id(), '_gtin', true );
				if ( empty( $gtin ) )
				{
					$gtin = get_post_meta( $variation->get_id(), '_upc', true );
				}
				$useful_attributes['meta_gtin'] = $gtin;
			}

			//support for WCmp
			if ( !isset( $gtin ) || empty( $gtin ) )
			{
				if (function_exists( "get_wcmp_vendor_settings") )
				{
					if( get_wcmp_vendor_settings('is_gtin_enable', 'general') == 'Enable' )
					{
						$gtin = get_post_meta( $variation->get_id(), '_wcmp_gtin_code', true);
						if ( empty( $gtin ) )
						{
							$gtin = null;
						}
						$useful_attributes['wcmp_gtin'] = $gtin;
					}
				}
			}

			//WPM support
			if ( !isset( $gtin ) || empty( $gtin ) )
			{
				if ( function_exists( 'wpm_get_code_gtin_by_product') )
				{
					$gtin = wpm_get_code_gtin_by_product($variation);
					if ( empty( $gtin ) )
					{
						$gtin = null;
					}
					$useful_attributes['wpm_gtin'] = $gtin;
				}
			}

			//EAN for Woocommerce support
			if ( !isset( $gtin ) || empty( $gtin ) )
			{
				if ( 'yes' === get_option( 'alg_wc_ean_plugin_enabled', 'yes' ) )
				{
					$gtin = get_post_meta($variation->get_id(), get_option( 'alg_wc_ean_meta_key', '_alg_ean' ), true);

					if (empty($gtin))
					{
						$gtin = null;
					}
					$useful_attributes['ean_for_woocommerce_gtin'] = $gtin;
				}
			}

			//WooCommerce Brands
			if ( empty( $brand ) )
			{
				//if WooCommerce brands is being used
				if (defined( 'WC_BRANDS_VERSION' ))
				{
					$terms = get_the_terms( $product->get_id(), 'product_brand' );
					if ( is_array( $terms ) ) {
						$brands = [];
						foreach ( $terms as $term ) {
							$brands[] = $term->name;
							if ( empty( $term->parent ) ) {
								$brand = $term->name;
							}
						}

						if(empty($brand) && !empty($brands[0]))
						{
							$brand = $brands[0];
						}
					}
				}
			}

			//Perfect Brands
			//https://github.com/franmastromarino/perfect-woocommerce-brands/
			if ( empty( $brand ) )
			{
				if (defined( 'PWB_PLUGIN_NAME' ))
				{
					$brandList = wp_get_object_terms( $product->get_id(), 'pwb-brand' );

					if ( ! empty( $brandList ) ) {
						$brands = [];
						foreach ( $brandList as $term ) {
							$brands[] = $term->name;
						}

						if(!empty($brands[0]))
						{
							$brand = $brands[0];
						}
					}
				}
			}

			//support for WPSSO
			if ( ( class_exists( 'WpssoWcmdSearch' ) ) )
			{
				if ( !isset( $gtin ) || empty( $gtin ) )
				{
					$gtin = get_post_meta($variation->get_id(), '_wpsso_product_gtin', true);
					if (empty($gtin))
					{
						$gtin = get_post_meta($variation->get_id(), '_wpsso_product_gtin14', true);
						if (empty($gtin))
						{
							$gtin = get_post_meta($variation->get_id(), '_wpsso_product_gtin13', true);
							if (empty($gtin))
							{
								$gtin = get_post_meta($variation->get_id(), '_wpsso_product_gtin12', true);
								if (empty($gtin))
								{
									$gtin = get_post_meta($variation->get_id(), '_wpsso_product_isbn', true);
									if (empty($gtin))
									{
										$gtin = null;
									}
								}
							}
						}
					}
					$useful_attributes['wpsso_gtin'] = $gtin;
				}

				if ( !isset( $mpn ) || empty( $mpn ) )
				{

					$mpn = get_post_meta($variation->get_id(), '_wpsso_product_mfr_part_no', true);
					if (empty($mpn))
					{
						$mpn = null;
					}
					$useful_attributes['meta_mpn'] = $mpn;
				}
			}

			//last chance check if there's the Google PlA plugin
			if (empty($gtin))
			{
				if ( defined('WC_GLA_VERSION') )
				{
					try
					{
						$attribute_manager = new \Automattic\WooCommerce\GoogleListingsAndAds\Product\Attributes\AttributeManager();

						//can only get the GTIN for a non-variant
						if (!$variation->is_type( 'variable' ))
						{
							$gtin_value = $attribute_manager->get_value($variation, \Automattic\WooCommerce\GoogleListingsAndAds\Product\Attributes\GTIN::get_id());

							if (!empty($gtin_value))
							{
								$gtin = $gtin_value;
							}
						}
					} catch (Exception $e)
					{
						//do nothing if this doesn't work
					}
				}
			}

			$all_images = $this->get_images( $variation );
			$image_url = null;
			$image_modified_time = null;

			if ( isset( $all_images[0] ) )
			{
				$image_info = $all_images[0];
				$image_url = @$image_info['image_url'];
				$image_modified_time = @$image_info['image_modified_time'];
			}

			$sale_price_effective_date = '';
			if  ($to = get_post_meta( $variation->get_id(), '_sale_price_dates_to', true ) )
			{
				$from = get_post_meta( $variation->get_id(), '_sale_price_dates_from', true );
				$sale_price_effective_date = ShoppingFeeder::format_datetime( $from ) . '/' . ShoppingFeeder::format_datetime( $to );
			}

			//add the sale price, if for some reason this is not currently on sale
			$useful_attributes['is_on_sale'] = $variation->is_on_sale();
			$useful_attributes['current_sale_price'] = $variation->get_sale_price() ? $variation->get_sale_price() : 0;

			if($overrideCurrencyConversion) {
				$price = $variation->get_price("");
				$regular_price = $variation->get_regular_price("");
				$sale_price = $variation->get_sale_price("") ? $variation->get_sale_price("") : null;
			} else {
				$price = $variation->get_price();
				$regular_price = $variation->get_regular_price();
				$sale_price = $variation->get_sale_price() ? $variation->get_sale_price() : null;
			}

			if ( !is_null( $price_currency ) && !is_null( $price_currency_rate ) )
			{
				if (!empty($price) && intval($price) > 0)
				{
					$price = $price * $price_currency_rate;
				}
				if (!empty($sale_price) && intval($sale_price) > 0)
				{
					$sale_price = $sale_price * $price_currency_rate;
				}
				if (!empty($regular_price) && intval($regular_price) > 0)
				{
					$regular_price = $regular_price * $price_currency_rate;
				}
			}

			//force $price to $regular_price if we have a differing $sale_price
			if ( !is_null( $sale_price ) && $regular_price > $sale_price && $price == $sale_price )
			{
				$price = $regular_price;
			}

			//support for COGS
			$cogs = null;
			if (function_exists( 'alg_wc_cog' ) )
			{
				$cogs = alg_wc_cog()->core->products->get_product_cost( $variation->get_id(), ['convert_to_number' => true]);
				if ($cogs > 0)
				{
					$cogs = wc_format_decimal( $cogs, 2 );
				}
				else
				{
					$cogs = null;
				}
			}

			$variations[] = array(
				'internal_variant_id'   => $variation->get_id(),


				//                'created_at'        => ShoppingFeeder::format_datetime( $variation->get_post_data()->post_date_gmt ),
				'internal_update_time'        => ShoppingFeeder::format_datetime( $variation->get_date_modified(), true ),
				//                'downloadable'      => $variation->is_downloadable(),
				//                'virtual'           => $variation->is_virtual(),
				'title'             => $variation->get_title(),
				'description'       => $variation->get_description(),
				'url'               => $variation->get_permalink(),
				'sku'               => $variation->get_sku(),
				'mpn'               => $mpn,
				'gtin'              => $gtin,
				'brand'              => !empty( $brand ) ? $brand : ( !empty( $manufacturer ) ? $manufacturer : '' ),
				'manufacturer'       => !empty( $manufacturer ) ? $manufacturer : ( !empty( $brand ) ? $brand : '' ),
				'price'             => wc_format_decimal( $price, 2 ),
				'regular_price'     => wc_format_decimal( $regular_price, 2 ),
				'sale_price'        => wc_format_decimal( $sale_price, 2 ),
				'sale_price_effective_date'         => $sale_price_effective_date,
				'cost_price'         => $cogs,
				'taxable'           => $variation->is_taxable(),
				'tax_status'        => $variation->get_tax_status(),
				'tax_class'         => $variation->get_tax_class(),
				'quantity'          => (int ) $variation->get_stock_quantity(),
				'availability'       => $variation->is_in_stock() ? 'in stock' : 'out of stock',
				'in_stock'          => $variation->is_in_stock(),
				//                'backordered'       => $variation->is_on_backorder(),
				//                'purchaseable'      => $variation->is_purchasable(),
				'visible'           => $variation->variation_is_visible(),
				'on_sale'           => $variation->is_on_sale(),
				'weight'            => $variation->get_weight() ? wc_format_decimal( $variation->get_weight(), 2 ) : null,
				'dimensions'        => array(
					'length' => $variation->get_length(),
					'width'  => $variation->get_width(),
					'height' => $variation->get_height(),
					'unit'   => get_option( 'woocommerce_dimension_unit' ),
				),
				'shipping_class'    => $variation->get_shipping_class(),
				'shipping_class_id' => ( 0 !== $variation->get_shipping_class_id() ) ? $variation->get_shipping_class_id() : null,
				'image'             => $all_images,
				'image_url'          => $image_url,
				'image_modified_time'=> $image_modified_time,
				'attributes'        => $useful_attributes,
			);
		}
		//print_r($variations);

		return $variations;
	}

	/*
	* Get the images for a product or product variation
	*
	* @since 2.1
	* @param WC_Product|WC_Product_Variation $product
	* @return array
	*/
	private function get_images( $product ) {

		$images = $attachment_ids = array();

		if ( $product->is_type( 'variation' ) ) {

			if ( has_post_thumbnail( $product->get_id() ) ) {

				// add variation image if set
				$attachment_ids[] = get_post_thumbnail_id( $product->get_id() );

			} elseif ( has_post_thumbnail( $product->get_id() ) ) {

				// otherwise use the parent product featured image if set
				$attachment_ids[] = get_post_thumbnail_id( $product->get_id() );
			}

		} else {

			// add featured image
			if ( has_post_thumbnail( $product->get_id() ) ) {
				$attachment_ids[] = get_post_thumbnail_id( $product->get_id() );
			}

			// add gallery images
			$attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );
		}

		// build image data
		foreach ( $attachment_ids as $position => $attachment_id ) {

			$attachment_post = get_post( $attachment_id );

			if ( is_null( $attachment_post ) ) {
				continue;
			}

			$attachment = wp_get_attachment_image_src( $attachment_id, 'full' );

			if ( ! is_array( $attachment ) ) {
				continue;
			}

			$images[] = array(
				'image_modified_time' => ShoppingFeeder::format_datetime( $attachment_post->post_modified_gmt ),
				'image_url'        => current( $attachment ),
				'is_placeholder' => false
			);
		}

		// set a placeholder image if the product has no images set
		if ( empty( $images ) ) {

			$images[] = array(
				'image_modified_time' => ShoppingFeeder::format_datetime( time() ),
				'image_url'        => wc_placeholder_img_src(),
				'is_placeholder' => true
			);
		}

		return $images;
	}

	/**
	 * Get the attributes for a product or product variation
	 *
	 * @since 2.1
	 * @param WC_Product|WC_Product_Variation $product
	 * @return array
	 */
	private function get_attributes( $product ) {

		$attributes = array();

		if ( $product->is_type( 'variation' ) ) {

			// variation attributes
			foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {

				// taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
				$attrName = ucwords( str_replace( 'attribute_', '', str_replace( 'pa_', '', $attribute_name ) ) );
				$attributes[$attrName] = $attribute;
			}

		} else {

			foreach ( $product->get_attributes() as $attribute ) {

				// taxonomy-based attributes are comma-separated, others are pipe (|) separated
				if ( $attribute['is_taxonomy'] ) {
					$options = explode( ',', $product->get_attribute( $attribute['name'] ) );
				} else {
					$options = explode( '|', $product->get_attribute( $attribute['name'] ) );
				}

//	            $attrName = ucwords( str_replace( 'pa_', '', $attribute['name'] ) );
//	            $attributes[$attrName] = array_map( 'trim', $options );
				$attributes[] = array(
					'name'      => ucwords( str_replace( 'pa_', '', $attribute['name'] ) ),
					'position'  => $attribute['position'],
					'visible'   => (bool) $attribute['is_visible'],
					'variation' => (bool) $attribute['is_variation'],
					'options'   => array_map( 'trim', $options ),
				);
			}
		}

		$shippingclassSlug = $product->get_shipping_class();

		if(!is_null($shippingclassSlug) && !empty($shippingclassSlug)) {
			$shippingclassName = get_term_by('slug', $shippingclassSlug, 'product_shipping_class');
			$attributes[] = array(
				'name'   => 'shipping_class',
				'options' => (array)$shippingclassName->name
			);
		}

		return $attributes;
	}
}
