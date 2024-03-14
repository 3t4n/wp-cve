<?php
/**
 * Declare class Import
 *
 * @package Import
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;
use LassoLite\Classes\Amazon_Api as Lasso_Amazon_Api;
use LassoLite\Classes\Affiliate_Link as Lasso_Affiliate_Link;
use LassoLite\Classes\Cache_Per_Process as Lasso_Cache_Per_Process;
use LassoLite\Classes\Setting_Enum;
use LassoLite\Classes\Helper as Lasso_Helper;
use LassoLite\Classes\Lasso_DB;
use LassoLite\Classes\Setting as Lasso_Setting;

use LassoLite\Models\Model;
use LassoLite\Models\Revert;

use stdClass;

/**
 * Import
 */
class Import {
	const OBJECT_KEY                   = 'lasso_import';
	const PRETTY_LINK_CATEGORY_SLUG    = 'pretty-link-category';
	const PRETTY_LINK_TAG_SLUG         = 'pretty-link-tag';
	const THIRSTY_LINK_CATEGORY_SLUG   = 'thirstylink-category';
	const AFFILIATE_URLS_CATEGORY_SLUG = 'affiliate_url_category';

	/**
	 * Revert a single post from Lasso to original plugin
	 *
	 * @param int    $import_id     Post id.
	 * @param string $import_source Type of post (AAWP, earnist,...).
	 * @param string $post_type Type of post (aawp, earnist,...). Default to empty.
	 */
	public function process_single_link_revert( $import_id, $import_source, $post_type = '' ) {
		$lasso_db = new Lasso_DB();

		if ( '' === $import_id || '' === $import_source ) {
			return false;
		}

		if ( 'AAWP' === $import_source && 'aawp_list' === $post_type ) {
			$aawp_list       = $lasso_db->get_aawp_list( $import_id );
			$aawp_amazon_ids = $aawp_list->product_asins ?? '';
			$aawp_amazon_ids = '' !== $aawp_amazon_ids ? explode( ',', $aawp_amazon_ids ) : array();

			foreach ( $aawp_amazon_ids as $amazon_id ) {
				$url_detail = $lasso_db->get_url_details_by_product_id( $amazon_id, Amazon_Api::PRODUCT_TYPE );
				if ( $url_detail ) {
					$import_id = $url_detail->lasso_id;
					wp_update_post(
						array(
							'ID'          => $import_id,
							'post_status' => 'trash',
						)
					);
					$status = $lasso_db->process_revert( $import_id, false );
				}
			}
		} elseif ( isset( $import_source ) && in_array( $import_source, array( 'AAWP', 'AmaLinks Pro', 'EasyAzon' ), true ) ) {
			wp_update_post(
				array(
					'ID'          => $import_id,
					'post_status' => 'trash',
				)
			);
			$status = $lasso_db->process_revert( $import_id, false );
		} else {
			$status = $lasso_db->process_revert( $import_id );
			delete_post_meta( $import_id, 'affiliate_link_type' );
		}

		// ? clear cache after reverting
		Lasso_Cache_Per_Process::get_instance()->un_set( self::OBJECT_KEY . '_' . $import_id );
		Lasso_Cache_Per_Process::get_instance()->un_set( 'wp_post_' . $import_id );

		return $status;
	}

	/**
	 * Import a single post into Lasso
	 *
	 * @param int    $import_id  Post id.
	 * @param string $post_type  Type of post (earnist, thirstylink, affiliate_url, aawp,...).
	 * @param string $post_title Title.
	 * @param string $import_permalink Import permalink.
	 */
	public function process_single_link_data( $import_id, $post_type, $post_title = '', $import_permalink = '' ) {
		if ( 'pretty-link' === $post_type ) {
			$import_data = $this->get_pretty_link_data( $import_id );
		} elseif ( 'thirstylink' === $post_type ) {
			$import_data = $this->get_thirsty_affiliates_data( $import_id );
		} elseif ( 'earnist' === $post_type ) {
			$import_data = $this->get_earnist_data( $import_id );
		} elseif ( 'affiliate_url' === $post_type ) {
			$import_data = $this->get_affiliate_urls_data( $import_id );
		} elseif ( 'aawp' === $post_type ) {
			$import_data = $this->get_aawp_data( $import_id );
		} elseif ( 'aawp_list' === $post_type ) {
			return $this->import_aawp_list_data( $import_id );
		} elseif ( 'easyazon' === $post_type ) {
			$import_data = $this->get_easyazon_data( $import_id );
		} elseif ( 'amalinkspro' === $post_type ) {
			$import_data = $this->get_amalinkspro_data( $import_id, $post_title, $import_permalink );
		} elseif ( 'easy_affiliate_link' === $post_type ) {
			$import_data = $this->get_easy_affiliate_link_data( $import_id, $post_title, $import_permalink );
		} elseif ( Setting_Enum::LASSO_PRO_SLUG === $post_type ) {
			$import_data = $this->get_lasso_pro_data( $import_id );
		}
		$import_data['post_type'] = $post_type;

		// ? Make a Lasso Link
		list($status, $import_data) = $this->import_into_lasso( $import_data, $post_type );

		// ? Return if status is false
		if ( ! $status ) {
			return array( $status, $import_data );
		}

		$lasso_url = Lasso_Affiliate_Link::get_lasso_url( $import_data['post']->ID );
		$lasso_id  = $lasso_url->id ?? 0;

		// ? Flip all old links to Lasso
		if ( $lasso_id > 0 ) {
			$import_data['new_url'] = $lasso_url->public_link ?? '';
		} elseif ( Constant::LASSO_AMAZON_PRODUCT_TYPE !== $lasso_url->link_type ) {
			$import_post_name = $import_data['post']->post_name ?? '';
			if ( strpos( $import_post_name, '/' ) ) {
				$start_pos = strpos( $import_post_name, '/' ) + 1;
			} else {
				$start_pos = 0;
			}

			$post_name              = substr( $import_post_name, $start_pos );
			$import_data['new_url'] = get_home_url() . '/' . $post_name . '/';
		} else {
			$import_data['new_url'] = $import_data['amazon_product']['product']['url'] ?? '';
		}

		return array( $status, $import_data );
	}

	/**
	 * Import post data from other plugins into Lasso
	 *
	 * @param array  $import_data Array contains post data.
	 * @param string $post_type   Type of post (post, page, surl, simple_url,...).
	 */
	private function import_into_lasso( $import_data, $post_type ) {
		$lasso_db             = new Lasso_DB();
		$lasso_affiliate_link = new Lasso_Affiliate_Link();

		$lasso_settings = Setting::get_settings();

		// ? Make sure slug is correct
		$post_id = $import_data['post']->ID ?? '';
		$slug    = $import_data['post']->post_name ?? '';
		$slug    = Lasso_Helper::lasso_unique_post_name( $post_id, $slug );
		$title   = $import_data['post']->post_title ?? '';
		if ( 'pretty-link' === $post_type ) {
			$slug    = $import_data['pretty_link_data']->slug ?? $slug;
			$post_id = $import_data['pretty_link_data']->link_cpt_id ?? $post_id;
			$title   = $import_data['pretty_link_data']->name ?? $title;

			$slug = trim( $slug, '/' );
			if ( false !== strpos( $slug, '/' ) ) {
				$tmp  = explode( '/', $slug );
				$slug = end( $tmp );
			}
		}

		// ? prepare data for saving Lasso post
		$default_btn_txt = Lasso_Setting::get_setting( 'primary_button_text', 'Buy Now' );
		$affiliate_link  = array(
			'affiliate_name'   => $title,
			'affiliate_url'    => $import_data['redirect_url'] ?? '',
			'post_name'        => $slug,
			'price'            => $import_data['price'] ?? '',
			'description'      => $import_data['description'] ?? '',
			'buy_btn_text'     => $import_data['button_text'] ?? $default_btn_txt,

			'open_new_tab'     => $import_data['open_new_tab'] ?? $lasso_settings['open_new_tab'] ?? 1,
			'enable_nofollow'  => $import_data['enable_nofollow'] ?? $lasso_settings['enable_nofollow'] ?? 1,
			'enable_sponsored' => $import_data['enable_sponsored'] ?? $lasso_settings['enable_sponsored'] ?? 1,
			'show_disclosure'  => $import_data['show_disclosure'] ?? $lasso_settings['show_disclosure'] ?? 1,
			'is_opportunity'   => $import_data['is_opportunity'] ?? 1,
			'badge_text'       => $import_data['badge_text'] ?? '',
			'thumbnail'        => $import_data['thumbnail'] ?? '',
		);

		$cat_ids = $import_data['cat_ids'] ?? array();
		if ( count( $cat_ids ) > 0 ) {
			$affiliate_link['categories'] = $cat_ids;
		}

		$data['post_id']      = $post_id;
		$data['settings']     = $affiliate_link;
		$data['thumbnail_id'] = $import_data['thumbnail_id'][0] ?? '';
		$data['old_uri']      = $import_data['old_uri'] ?? '';
		$data['is_importing'] = true;

		// ? Use defined "affiliate name" as Lasso Post's name
		if ( in_array( $post_type, array( Setting_Enum::EASY_AFFILIATE_LINK_SLUG, Setting_Enum::LASSO_PRO_SLUG ), true ) ) {
			$data['use_defined_affiliate_name'] = 1;
		}

		// ? Flip post type and log import
		$import_data['post'] = $import_data['post'] ?? new stdClass();
		$import_data['post'] = is_object( $import_data['post'] ) ? $import_data['post'] : new stdClass();

		// ? Check affiliate_name and affiliate_url and old_uri before function save_lasso_url
		if ( empty( $affiliate_link['affiliate_name'] ) || empty( $affiliate_link['affiliate_url'] ) || empty( $data['old_uri'] ) ) {
			return array( false, $import_data );
		}

		// ? Fix key in Lite
		$data['settings']['surl_redirect'] = $data['settings']['affiliate_url'] ?? '';

		$post_id                 = $lasso_affiliate_link->save_lasso_url( $data );
		$import_data['post']->ID = $post_id;

		$post      = get_post( $post_id );
		$post_name = $post->post_name ?? '';
		$slug      = ! empty( $post_name ) && empty( $slug ) ? $post_name : $slug;

		$old_uri = $import_data['old_uri'] ?? '';

		$status = $lasso_db->process_import( $post_id, $slug, $old_uri, $post_type );

		// ? clear cache after importing
		if ( $status ) {
			Lasso_Cache_Per_Process::get_instance()->un_set( self::OBJECT_KEY . '_' . $post_id );
			Lasso_Cache_Per_Process::get_instance()->un_set( 'wp_post_' . $post_id );
		}

		return array( $status, $import_data );
	}

	/**
	 * Get post data of Pretty Link plugin
	 *
	 * @param int $import_id Post id.
	 */
	private function get_pretty_link_data( $import_id ) {
		$lasso_db = new Lasso_DB();

		$post             = get_post( $import_id );
		$pretty_link_data = $lasso_db->get_pretty_link_by_id( $import_id );

		if ( ! $pretty_link_data ) {
			return array();
		}

		$post->post_name      = $pretty_link_data->slug;
		$redirect_url         = $pretty_link_data->url;
		$cat_names            = $this->get_post_category_names( $import_id, self::PRETTY_LINK_CATEGORY_SLUG );
		$tag_names            = $this->get_post_category_names( $import_id, self::PRETTY_LINK_TAG_SLUG );
		$final_category_names = array_merge( $cat_names, $tag_names );
		$final_category_names = array_unique( $final_category_names );

		$data = array(
			'post'             => $post,
			'pretty_link_data' => $pretty_link_data,
			'redirect_url'     => $redirect_url,
			'cat_ids'          => $final_category_names,
			'old_uri'          => $this->get_import_permalink( $import_id, $post->post_name, $post->post_type ),
			'thumbnail_id'     => array( '' ),
			'description'      => $pretty_link_data->description,
			'enable_nofollow'  => $pretty_link_data->nofollow ?? '0',
			'enable_sponsored' => $pretty_link_data->sponsored ?? '0',
		);

		return $data;
	}

	/**
	 * Get post data of Thirsty Affiliate plugin
	 *
	 * @param int $import_id Post id.
	 */
	private function get_thirsty_affiliates_data( $import_id ) {
		$post         = get_post( $import_id );
		$redirect_url = get_post_meta( $import_id, '_ta_destination_url', true );
		$rel_tag      = get_post_meta( $import_id, '_ta_rel_tags', true ); // ? get rel tag for Thirsty Affiliates
		$cat_ids      = $this->get_post_category_names( $import_id, self::THIRSTY_LINK_CATEGORY_SLUG );

		$data = array(
			'post'             => $post,
			'redirect_url'     => $redirect_url,
			'cat_ids'          => $cat_ids,
			'old_uri'          => $this->get_import_permalink( $import_id, $post->post_name, $post->post_type ),
			'thumbnail_id'     => get_post_meta( $import_id, '_ta_image_ids', true ),
			'description'      => '',
			'enable_sponsored' => ( false === strpos( strtolower( $rel_tag ), 'sponsored' ) ) ? Lasso_Helper::cast_to_boolean( Setting::get_setting( 'enable_sponsored' ) ) : 1,
		);

		return $data;
	}

	/**
	 * Get post data of Earnist plugin
	 *
	 * @param int $import_id Post id.
	 */
	private function get_earnist_data( $import_id ) {
		$post                = get_post( $import_id );
		$redirect_url        = get_post_meta( $import_id, '_earn_url', true );
		$cat_ids             = $this->get_categories_id_of_post( $import_id );
		$earnist_description = get_post_meta( $import_id, '_earn_description', true );
		$earnist_price       = get_post_meta( $import_id, '_earn_price', true );
		$earnist_button_text = get_post_meta( $import_id, '_earn_button_text', true );

		$earnist_image_id  = '';
		$earnist_image_url = '';
		$post_thumbnail    = get_post_thumbnail_id( $import_id );
		if ( ! empty( $post_thumbnail ) ) {
			$earnist_image_id = $post_thumbnail;
		} else {
			$earnist_image_url = get_post_meta( $import_id, '_earn_image_url', true );
			if ( 0 === strpos( $earnist_image_url, '/wp-content/' ) ) {
				$earnist_image_url = get_home_url() . $earnist_image_url;
			}
		}

		$data = array(
			'post'          => $post,
			'redirect_url'  => $redirect_url,
			'cat_ids'       => $cat_ids,
			'old_uri'       => $this->get_import_permalink( $import_id, $post->post_name, $post->post_type ),
			'thumbnail_id'  => array( $earnist_image_id ),
			'thumbnail_url' => $earnist_image_url,
			'description'   => $earnist_description,
			'price'         => $earnist_price,
			'button_text'   => $earnist_button_text,
		);

		return $data;
	}

	/**
	 * Get post data of Affiliate Url plugin
	 *
	 * @param int $import_id Post id.
	 */
	private function get_affiliate_urls_data( $import_id ) {
		$post         = get_post( $import_id );
		$cat_ids      = $this->get_post_category_names( $import_id, self::AFFILIATE_URLS_CATEGORY_SLUG );
		$redirect_url = get_post_meta( $import_id, '_affiliate_url_redirect', true );

		$data = array(
			'post'         => $post,
			'redirect_url' => $redirect_url,
			'cat_ids'      => $cat_ids,
			'old_uri'      => $this->get_import_permalink( $import_id, $post->post_name, $post->post_type ),
			'thumbnail_id' => array( get_post_thumbnail_id( $import_id ) ),
			'description'  => get_the_excerpt( $import_id ),
		);

		return $data;
	}

	/**
	 * Import amazon product from other plugins into Lasso
	 *
	 * @param string $amazon_id Amazon product id.
	 */
	private function import_aawp_amazon_product_into_lasso( $amazon_id ) {
		$lasso_db         = new Lasso_DB();
		$lasso_amazon_api = new Lasso_Amazon_Api();

		$product_data = array();

		$product = $lasso_db->get_aawp_product( $amazon_id );
		if ( $product ) {
			$base_url     = $lasso_amazon_api->get_amazon_product_url( $product->url, false );
			$product_data = array(
				'product_id'      => $product->asin,
				'title'           => $product->title,
				'price'           => Lasso_Amazon_Api::format_price( $product->price, $product->currency ),
				'default_url'     => $base_url,
				'url'             => $product->url,
				'image'           => $product->image_url,
				'is_prime'        => $product->is_prime,
				'currency'        => $product->currency,
				'features'        => $product->features,
				'savings_amount'  => $product->savings,
				'savings_percent' => $product->savings_percentage,
				'savings_basis'   => $product->savings_basis,
				'rating'          => $product->rating,
				'reviews'         => $product->reviews,
				'is_manual'       => 1,
			);

			$lasso_amazon_api->update_amazon_product_in_db( $product_data, $product->date_updated );
		}

		return $product_data;
	}

	/**
	 * Get post data of Aawp plugin
	 *
	 * @param int $import_id Post id.
	 */
	private function get_aawp_data( $import_id ) {
		$lasso_amazon_api = new Lasso_Amazon_Api();

		$product_data = $lasso_amazon_api->get_amazon_product_from_db( $import_id );
		if ( ! $product_data ) {
			$product_data = $this->import_aawp_amazon_product_into_lasso( $import_id );
		} else {
			$product_data['url']   = $product_data['monetized_url'] ?? '';
			$product_data['title'] = $product_data['default_product_name'] ?? '';
		}

		$redirect_url     = $product_data['url'] ?? '';
		$post_title       = $product_data['title'] ?? '';
		$post             = new stdClass();
		$post->post_title = $post_title;

		$aawp_options = get_option( 'aawp_output' );
		$button_text  = $aawp_options['button_text'] ?? '';

		$data = array(
			'post'             => $post,
			'redirect_url'     => $redirect_url,
			'old_uri'          => $import_id,
			'description'      => '',
			'enable_sponsored' => 1,
			'button_text'      => $button_text,
		);

		return $data;
	}

	/**
	 * Get Lasso Pro data to import.
	 *
	 * @param int $import_id Lasso Pro post id.
	 * @return array
	 */
	private function get_lasso_pro_data( $import_id ) {
		$target_url = self::get_lasso_pro_target_url( $import_id );
		$amazon_id  = Lasso_Amazon_Api::get_product_id_by_url( $target_url );
		$terms      = get_the_terms( $import_id, 'lasso-cat' );
		$cat_ids    = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'name' ) : null;

		$lasso_amazon_api = new Lasso_Amazon_Api();
		if ( $amazon_id && ! $lasso_amazon_api->get_amazon_product_from_db( $amazon_id ) ) {
			$this->import_lasso_pro_amazon_product_into_lasso_lite( $amazon_id );
		}

		$data = array(
			'post'             => get_post( $import_id ),
			'redirect_url'     => $target_url,
			'cat_ids'          => $cat_ids,
			'old_uri'          => $target_url,
			'button_text'      => get_post_meta( $import_id, 'buy_btn_text', true ),
			'price'            => get_post_meta( $import_id, 'price', true ),
			'thumbnail_id'     => get_post_meta( $import_id, 'lasso_thumbnail_id', true ),
			'thumbnail'        => get_post_meta( $import_id, 'lasso_custom_thumbnail', true ),
			'description'      => get_post_meta( $import_id, 'affiliate_desc', true ),
			'open_new_tab'     => get_post_meta( $import_id, 'open_new_tab', true ),
			'enable_nofollow'  => get_post_meta( $import_id, 'enable_nofollow', true ),
			'enable_sponsored' => get_post_meta( $import_id, 'enable_sponsored', true ),
			'show_disclosure'  => get_post_meta( $import_id, 'show_disclosure', true ),
			'badge_text'       => get_post_meta( $import_id, 'badge_text', true ),
		);

		return $data;
	}

	/**
	 * Import Lasso Pro amazon product data to Lite
	 *
	 * @param string $amazon_id Amazon Product id.
	 * @return array|bool|mixed|object|void|null
	 */
	private function import_lasso_pro_amazon_product_into_lasso_lite( $amazon_id ) {
		$lasso_db         = new Lasso_DB();
		$lasso_amazon_api = new Lasso_Amazon_Api();

		$product = $lasso_db->get_lasso_pro_amazon_product( $amazon_id );
		if ( $product ) {
			$product_data = array(
				'product_id'      => $product->amazon_id,
				'title'           => $product->default_product_name,
				'price'           => $product->latest_price,
				'default_url'     => $product->base_url,
				'url'             => $product->url,
				'image'           => $product->image_url,
				'is_prime'        => $product->is_prime,
				'currency'        => $product->currency,
				'features'        => $product->features,
				'savings_amount'  => $product->savings_amount,
				'savings_percent' => $product->savings_percent,
				'savings_basis'   => $product->savings_basis,
				'quantity'        => $product->quantity,
				'rating'          => $product->rating,
				'reviews'         => $product->reviews,
				'is_manual'       => 1,
			);

			$lasso_amazon_api->update_amazon_product_in_db( $product_data, $product->last_updated );
		}

		return $product;
	}

	/**
	 * Get post data of Aawp plugin
	 *
	 * @param int $import_id Post id.
	 */
	private function import_aawp_list_data( $import_id ) {
		$lasso_db         = new Lasso_DB();
		$lasso_amazon_api = new Lasso_Amazon_Api();

		$list = $lasso_db->get_aawp_list( $import_id );
		if ( ! $list ) {
			return array( false, array() );
		}

		$cat_name = $list->keywords;
		$cat      = term_exists( $cat_name, Constant::LASSO_CATEGORY );
		if ( null === $cat || 0 === $cat ) {
			$cat = wp_insert_term( $cat_name, Constant::LASSO_CATEGORY );
		}
		$cat_id = (int) $cat['term_id'];

		$asins = $list->product_asins;
		$asins = explode( ',', $asins );
		foreach ( $asins as $asin ) {
			$product_data = $lasso_amazon_api->get_amazon_product_from_db( $asin );
			if ( ! $product_data ) {
				$this->import_aawp_amazon_product_into_lasso( $asin );
			}

			list($status, $import_data) = $this->process_single_link_data( $asin, 'aawp' );
			$post_id                    = $import_data['post']->ID ?? 0;

			// ? update categories
			wp_set_object_terms( $post_id, array( $cat_id ), Constant::LASSO_CATEGORY );
		}

		return array( true, array() );
	}

	/**
	 * Get post data of EasyAzon plugin
	 *
	 * @param string $import_id Post id.
	 */
	private function get_easyazon_data( $import_id ) {
		$lasso_db         = new Lasso_DB();
		$lasso_amazon_api = new Lasso_Amazon_Api();

		$product        = $lasso_db->get_easyazon_product( $import_id );
		$product_serial = $product->option_value ?? '';
		$product        = maybe_unserialize( $product_serial ); // phpcs:ignore

		// ? insert amazon product data from easyazon into lasso
		$db_product = $lasso_amazon_api->get_amazon_product_from_db( $product['identifier'] );
		if ( ! $db_product ) {
			$store_data = array(
				'product_id'  => $product['identifier'],
				'title'       => $product['title'],
				'price'       => $product['attributes']['ListPrice'] ?? $product['lowest_price_n'] ?? '',
				'default_url' => $product['url'],
				'url'         => $product['url'],
				'image'       => $product['images'][4]['url'] ?? $product['images'][ count( $product['images'] ) - 1 ]['url'],
				'quantity'    => 200,  // Manual checks won't show out of stock for now. TODO: Add BLS to out of stock checks.
				'is_manual'   => 1,
			);
			$lasso_amazon_api->update_amazon_product_in_db( $store_data );
		}

		$redirect_url     = $product['url'] ?? '';
		$post_title       = $product['title'] ?? '';
		$post_title       = $post_title ? $post_title : 'Amazon';
		$post             = new stdClass();
		$post->post_title = $post_title;

		$data = array(
			'post'         => $post,
			'redirect_url' => $redirect_url,
			'old_uri'      => $import_id,
			'description'  => '',
		);

		return $data;
	}

	/**
	 * Get post data of AmaLinks Pro plugin
	 *
	 * @param string $import_id  Post id.
	 * @param string $post_title Post title.
	 * @param string $import_permalink Import permalink.
	 */
	private function get_amalinkspro_data( $import_id, $post_title, $import_permalink ) {
		$post             = new stdClass();
		$post->post_title = $post_title;

		if ( empty( $post->post_title ) ) {
			$lasso_amazon     = new Lasso_Amazon_Api();
			$product          = $lasso_amazon->get_amazon_product_from_db( $import_id );
			$post->post_title = $product['default_product_name'] ?? $post->post_title;
			if ( ! $product && empty( $post->post_title ) ) {
				$result           = $lasso_amazon->fetch_product_info( $import_id, true, false, $import_permalink );
				$post->post_title = $result['product']['title'] ?? $post->post_title;
			}
		}

		$data = array(
			'post'         => $post,
			'redirect_url' => $import_permalink,
			'old_uri'      => $import_id,
			'description'  => '',
		);

		return $data;
	}

	/**
	 * Get post data of Easy Affiliate Links plugin
	 *
	 * @param string $import_id  Post id.
	 * @param string $post_title Post title.
	 * @param string $import_permalink Import permalink.
	 */
	public function get_easy_affiliate_link_data( $import_id, $post_title, $import_permalink ) {
		$post         = get_post( $import_id );
		$redirect_url = get_post_meta( $import_id, 'eafl_url', true );
		$terms        = get_the_terms( $import_id, 'eafl_category' );
		$cat_ids      = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'name' ) : null;

		$default_settings = get_option( 'eafl_settings' );
		$target           = get_post_meta( $import_id, 'eafl_target', true );
		$description      = get_post_meta( $import_id, 'eafl_description', true );
		if ( 'default' === $target ) {
			$target = $default_settings['default_target'] ?? '_blank';
		}
		$nofollow = get_post_meta( $import_id, 'eafl_nofollow', true );
		if ( 'default' === $nofollow ) {
			$nofollow = $default_settings['default_nofollow'] ?? 'nofollow';
		}
		$sponsored = get_post_meta( $import_id, 'eafl_sponsored', true );

		$data = array(
			'post'             => $post,
			'redirect_url'     => $redirect_url,
			'cat_ids'          => $cat_ids,
			'old_uri'          => get_permalink( $import_id ),
			'description'      => $description,
			'open_new_tab'     => '_blank' === $target ? 1 : 0,
			'enable_nofollow'  => 'nofollow' === $nofollow ? 1 : 0,
			'enable_sponsored' => '1' === $sponsored ? 1 : 0,
		);

		return $data;
	}

	/**
	 * Get import permalink
	 *
	 * @param int    $id        Post id.
	 * @param string $post_name Post name.
	 * @param string $post_type Type of post (post, page, pretty-link, surl,...).
	 */
	private function get_import_permalink( $id, $post_name, $post_type ) {
		if ( 'pretty-link' === $post_type ) {
			$prlipro = get_option( 'prlipro_options', array() );
			if ( ! is_array( $prlipro ) ) {
				$prlipro = array();
			}

			$home_url         = get_home_url();
			$base_slug_prefix = $prlipro['base_slug_prefix'] ?? false;

			if ( $base_slug_prefix && '' !== $base_slug_prefix && strpos( $post_name, $base_slug_prefix ) === false ) {
				$post_name        = substr( $post_name, strpos( $post_name, '/' ) );
				$import_permalink = $home_url . '/' . $base_slug_prefix . '/' . $post_name . '/';
			} else {
				$import_permalink = $home_url . '/' . $post_name . '/';
			}
		} else {
			$import_permalink = get_the_permalink( $id );
		}

		return $import_permalink;
	}

	/**
	 * Get all categories of a Lasso post
	 *
	 * @param int $post_id Lasso post id.
	 */
	public function get_categories_id_of_post( $post_id ) {
		$lasso_db = new Lasso_DB();

		$sql     = '
			SELECT `term_taxonomy_id` 
			FROM ' . $lasso_db->term_relationships . ' 
			WHERE `object_id` = %d
		';
		$prepare = Model::prepare( $sql, $post_id );
		$result  = Model::get_results( $prepare, ARRAY_A );

		$cat_ids = array();

		if ( count( $result ) > 0 ) {
			foreach ( $result as $value ) {
				$cat_ids[] = $value['term_taxonomy_id'];
			}
		}

		return $cat_ids;
	}

	/**
	 * Check whether a post was imported into Lasso or not
	 *
	 * @param int $post_id Lasso post id.
	 */
	public static function is_post_imported_into_lasso( $post_id ) {
		$post_id   = intval( $post_id );
		$cache_key = self::OBJECT_KEY . '_' . $post_id;
		$results   = Lasso_Cache_Per_Process::get_instance()->get_cache( $cache_key, null );
		if ( null !== $results ) {
			return $results;
		}

		if ( 0 === $post_id ) {
			Lasso_Cache_Per_Process::get_instance()->set_cache( $cache_key, false );
			return false;
		}

		// ? Cache post by Lasso_Cache_Per_Process
		$post = Lasso_Cache_Per_Process::get_instance()->get_cache( 'wp_post_' . $post_id );
		if ( false === $post ) {
			$post = get_post( $post_id );
			Lasso_Cache_Per_Process::get_instance()->set_cache( 'wp_post_' . $post_id, $post );
		}
		$post_type   = get_post_type( $post );
		$post_status = get_post_status( $post );

		if ( Constant::LASSO_POST_TYPE !== $post_type || 'publish' !== $post_status ) {
			Lasso_Cache_Per_Process::get_instance()->set_cache( $cache_key, false );
			return false;
		}

		$sql     = '
			select DISTINCT plugin
			from ' . ( new Revert() )->get_table_name() . '
			where lasso_id = ' . $post_id . '
		';
		$results = Model::get_results( $sql );

		if ( is_null( $results ) ) {
			Lasso_Cache_Per_Process::get_instance()->set_cache( $cache_key, false );
			return false;
		}

		$results = array_map(
			function( $v ) {
				return $v->plugin;
			},
			$results
		);

		if ( empty( $results ) ) {
			Lasso_Cache_Per_Process::get_instance()->set_cache( $cache_key, false );
			return false;
		}

		// ? Set cache to keep results loaded
		Lasso_Cache_Per_Process::get_instance()->set_cache( $cache_key, $results );
		return $results;
	}

	/**
	 * Get category names by post id
	 *
	 * @param int    $post_id  Post id.
	 * @param string $taxonomy Taxonomy.
	 * @return array
	 */
	public function get_post_category_names( $post_id, $taxonomy ) {
		$result  = array();
		$cat_ids = $this->get_categories_id_of_post( $post_id );

		foreach ( $cat_ids as $key => $cat_id ) {
			$term     = self::get_term_by_taxonomy( $cat_id, $taxonomy );
			$cat_name = $term->name ?? '';
			if ( ! empty( $cat_name ) ) {
				$result[ $key ] = $cat_name;
			}
		}

		return $result;
	}

	/**
	 * Get term by taxonomy id and slug
	 *
	 * @param string $taxonomy_id   Taxonomy id.
	 * @param string $taxonomy_slug Taxonomy slug.
	 */
	public static function get_term_by_taxonomy( $taxonomy_id, $taxonomy_slug ) {
		$sql     = '
			SELECT tt.term_id, name, slug, taxonomy
			FROM ' . Model::get_wp_table_name( 'term_taxonomy' ) . ' AS tt
			LEFT JOIN
				' . Model::get_wp_table_name( 'terms' ) . ' AS t
				ON tt.term_id = t.term_id
			WHERE
				tt.term_taxonomy_id = %d
				AND tt.taxonomy = %s
			LIMIT 1
		';
		$prepare = Model::prepare( $sql, $taxonomy_id, $taxonomy_slug );

		return Model::get_row( $prepare );
	}

	/**
	 * Get Lasso Pro target url
	 *
	 * @param int $lasso_pro_id Lasso Pro post Id.
	 * @return mixed
	 */
	public static function get_lasso_pro_target_url( $lasso_pro_id ) {
		$sql = '
			SELECT redirect_url
			FROM ' . Model::get_wp_table_name( 'lasso_url_details' ) . '
			WHERE lasso_id = %d
		';
		$sql = Model::prepare( $sql, $lasso_pro_id );

		$target_url = Model::get_var( $sql );

		return $target_url;
	}
}
