<?php

namespace WpifyWoo\Modules\XmlFeedHeureka;

use WC_Product_Variable;
use WpifyWoo\Abstracts\AbstractFeed;

class Feed extends AbstractFeed {
	/** @var XmlFeedHeurekaModule $module */
	private $module;
	private $heureka_categories;
	private $delivery_methods = [];

	/**
	 * @param array $products
	 *
	 * @return array
	 */
	public function data( array $products ): array {
		$this->module                 = $this->plugin->get_module( XmlFeedHeurekaModule::class );
		$heureka_categories_languages = $this->module->get_setting( 'categories_languages', true );
		if ( is_array( $heureka_categories_languages ) && ! empty( $heureka_categories_languages[0] ) ) {
			$lang = $heureka_categories_languages[0];
		} else {
			$lang = '';
		}

		$categories_lang          = apply_filters( 'wpify_woo_feed_heureka_categories_lang', $lang );
		$this->heureka_categories = $this->module->get_heureka_categories( $categories_lang );
		$this->delivery_methods   = $this->module->get_setting( 'delivery_methods' );

		$items                = array();
		$exclude_out_of_stock = $this->module->get_setting( 'exclude_outofstock' );

		// Filter the data to assure only one ID exists for every item in feed
		// This is because the page attribute in Woo returns the same product sometimes
		$tmp_data = $this->get_tmp_data();
		$ids      = array_column( $tmp_data, 'ITEM_ID' );

		foreach ( $products as $product ) {
			if ( $exclude_out_of_stock && ! ( $product->is_in_stock() && ! $product->is_on_backorder() ) ) {
				continue;
			}

			if ( apply_filters( 'wpify_woo_xml_heureka_skip_product', false, $product ) ) {
				continue;
			}

			if ( $product->is_type( 'simple' ) ) {
				if ( ! $product->get_price() ) {
					continue;
				}

				if ( in_array( $this->get_item_id( $product ), $ids ) ) {
					continue;
				}

				$items[ '__custom:SHOPITEM:' . rand() ] = $this->get_data( $product );
			} elseif ( $product->is_type( 'variable' ) ) {
				/** @var $product WC_Product_Variable */
				foreach ( $product->get_available_variations() as $variation ) {
					$var = wc_get_product( $variation['variation_id'] );
					if ( ! $var->get_price() ) {
						continue;
					}

					if ( $exclude_out_of_stock && ! ( $var->is_in_stock() && ! $var->is_on_backorder() ) ) {
						continue;
					}

					if ( in_array( $this->get_item_id( $var ), $ids ) ) {
						continue;
					}

					$items[ '__custom:SHOPITEM:' . rand() ] = $this->get_data( $var, $product );
				}
			}
		}

		return $items;
	}

	public function get_item_id( $product ) {
		$item_id = '';

		if ( $this->module->get_setting( 'item_id_custom_field' ) ) {
			$item_id = $product->get_meta( $this->module->get_setting( 'item_id_custom_field' ), true );
		}

		if ( ! $item_id ) {
			$item_id = $product->get_id();
		}

		return $item_id;
	}

	/**
	 * @param $product \WC_Product
	 */
	public function get_data( $product, $parent_product = null ) {
		$feed_product_name = $product->get_meta( '_wpify_woo_heureka_product_name', true ) ?: $product->get_name();
		$feed_product      = $product->get_meta( '_wpify_woo_heureka_product', true ) ?: $product->get_name();

		$data = array(
			'ITEM_ID'       => $this->get_item_id( $product ),
			'PRODUCTNAME'   => array( '_cdata' => $feed_product_name ),
			'PRODUCT'       => array( '_cdata' => $feed_product ),
			'DESCRIPTION'   => array( '_cdata' => $this->get_description( $product, $parent_product ) ),
			'URL'           => array( '_cdata' => $product->get_permalink() ),
			'PRICE_VAT'     => wc_get_price_including_tax( $product ),
			'DELIVERY_DATE' => $product->is_in_stock() && ! $product->is_on_backorder() ? $this->module->get_setting( 'delivery' ) : $this->module->get_setting( 'delivery_out_of_stock' ),
		);

		$ean = $this->get_ean( $product );
		if ( $ean ) {
			$data['EAN'] = $ean;
		}

		$image_id = $product->get_image_id();
		if ( ! $image_id && $parent_product ) {
			$image_id = $parent_product->get_image_id();
		}

		if ( $image_id ) {
			$data['IMGURL'] = array( '_cdata' => wp_get_attachment_url( $image_id ) );
		}

		$heureka_category = $this->get_heureka_category( $product, $parent_product );
		if ( $heureka_category ) {
			$data['CATEGORYTEXT'] = array( '_cdata' => $heureka_category );
		}

		$counter = 0;

		foreach ( $product->get_attributes() as $tax => $attribute ) {
			$data[ '__custom:PARAM:' . $counter ] = array(
				'PARAM_NAME' => array( '_cdata' => wc_attribute_label( $tax ) ),
				'VAL'        => array( '_cdata' => $product->get_attribute( $tax ) ),
			);
			$counter ++;
		}

		if ( ! empty( $this->delivery_methods ) && is_array( $this->delivery_methods ) ) {
			foreach ( $this->delivery_methods as $key => $method ) {
				$data[ '__custom:DELIVERY:' . $key ] = array(
					'DELIVERY_ID'    => $method['method'],
					'DELIVERY_PRICE' => $method['price'],
				);

				if ( ! empty( $method['price_cod'] ) ) {
					$data[ '__custom:DELIVERY:' . $key ]['DELIVERY_PRICE_COD'] = $method['price_cod'];
				}
			}
		}

		if ( $parent_product ) {
			$data['ITEMGROUP_ID'] = $parent_product->get_id();
		}

		return apply_filters( 'wpify_woo_xml_feed_heureka_item_data', $data, $product, $parent_product );
	}

	public function get_description( $product, $parent_product ) {
		$description = $product->get_description();
		if ( ! $description && $parent_product ) {
			$description = $parent_product->get_description();
		}

		return $description;
	}

	public function get_ean( $product ) {
		if ( $this->module->get_setting( 'ean_custom_field' ) ) {
			$ean = $product->get_meta( $this->module->get_setting( 'ean_custom_field' ), true );
		} else {
			$ean = substr( $product->get_sku(), 0, 14 );
		}

		return $ean;
	}

	public function get_heureka_category( $product, $parent_product = null ) {
		if ( $parent_product ) {
			$product = $parent_product;
		}
		$category        = null;
		$custom_category = $product->get_meta( '_wpify_woo_heureka_category', true );
		if ( $custom_category ) {
			return $custom_category;
		}
		foreach ( $product->get_category_ids() as $id ) {
			$cat_id = $this->module->get_setting( 'heureka_category_' . $id );
			if ( ! $cat_id && function_exists( 'icl_object_id' ) ) {
				$default_lang = apply_filters('wpml_default_language', NULL );
				$cat_id = $this->module->get_setting( 'heureka_category_' . apply_filters( 'wpml_object_id', $id, 'product_cat', true, $default_lang ) );
			}
			if ( $cat_id ) {
				$category = $this->heureka_categories[ $cat_id ]['category_fullname'] ?: $this->heureka_categories[ $cat_id ]['name'];
				break;
			}
		}

		return $category;
	}

	public function get_root_name() {
		return 'SHOP';
	}

	public function feed_name() {
		return 'heureka';
	}

	/**
	 * @return mixed
	 */
	public function get_module() {
		return $this->module;
	}

	public function get_delivery_methods() {
		$methods = [];
		if ( ! empty( $this->delivery_methods ) && is_array( $this->delivery_methods ) ) {
			foreach ( $this->delivery_methods as $key => $method ) {
				$methods[ '__custom:DELIVERY:' . $key ] = array(
					'DELIVERY_ID'        => $method['method'],
					'DELIVERY_PRICE'     => $method['price'],
					'DELIVERY_PRICE_COD' => $method['price_cod'],
				);
			}
		}

		return $methods;
	}
}
