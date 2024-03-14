<?php
/**
 * Declare class Affiliate_Link
 *
 * @package Enum
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Lasso_DB;
use LassoLite\Classes\Meta_Enum;
use LassoLite\Classes\Setting;

/**
 * Affiliate_Link
 */
class Affiliate_Link {
	const DEFAULT_AMAZON_NAME = 'Amazon';
	/**
	 * Edit detail page
	 *
	 * @var string $edit_details_page
	 */
	public static $edit_details_page = 'surl-url-details';

	/**
	 * Get Lasso post detail
	 *
	 * @param int  $post_id   Lasso post id.
	 * @param bool $is_detail Is detail page. Default to false.
	 */
	public static function get_lasso_url( $post_id, $is_detail = false ) {
		$post_id             = intval( $post_id );
		$post                = get_post( $post_id );
		$post_type           = get_post_type( $post );
		$post_status         = get_post_status( $post );
		$lasso_lite_settings = Setting::get_settings();

		// ? default data
		$edit_link                           = '';
		$link_type                           = '';
		$name                                = $is_detail ? '' : 'The Link Title Goes Here';
		$slug                                = '';
		$guid                                = '';
		$permalink                           = '#';
		$public_link                         = '#';
		$image_src                           = Constant::DEFAULT_THUMBNAIL;
		$image_src_default                   = 1;
		$thumbnail_id                        = '';
		$target_url                          = '';
		$open_new_tab                        = true;
		$enable_nofollow                     = true;
		$enable_sponsored                    = true;
		$description                         = '';
		$price                               = '';
		$display_last_updated                = '';
		$display_primary_button_text_default = $lasso_lite_settings['primary_button_text'];
		$show_disclosure                     = $lasso_lite_settings['show_disclosure'] ?? true;
		$disclosure_text                     = $lasso_lite_settings['disclosure_text'] ?? '';
		$badge_text                          = '';
		$is_amazon_page                      = false;
		$display_primary_button_text         = $display_primary_button_text_default;
		$display_show_price                  = $lasso_lite_settings['show_price'] ?? true;

		// ? real data
		if ( $post_id > 0 && SIMPLE_URLS_SLUG === $post_type && 'publish' === $post_status && $post ) {
			$name                        = $post->post_title;
			$target_url                  = get_post_meta( $post_id, '_surl_redirect', true );
			$slug                        = $post->post_name;
			$permalink                   = get_permalink( $post_id );
			$image_src                   = get_post_meta( $post_id, Meta_Enum::LASSO_LITE_CUSTOM_THUMBNAIL, true );
			$display_primary_button_text = get_post_meta( $post_id, Meta_Enum::BUY_BTN_TEXT, true );
			$open_new_tab                = get_post_meta( $post_id, Meta_Enum::OPEN_NEW_TAB, true );
			$enable_nofollow             = get_post_meta( $post_id, Meta_Enum::ENABLE_NOFOLLOW, true );
			$enable_sponsored            = get_post_meta( $post_id, Meta_Enum::ENABLE_SPONSORED, true );
			$thumbnail_id                = get_post_meta( $post_id, Meta_Enum::THUMBNAIL_ID, true );
			$description                 = get_post_meta( $post_id, Meta_Enum::DESCRIPTION, true );
			$description                 = str_replace( '<p></p>', '', $description );
			$price                       = get_post_meta( $post_id, Meta_Enum::PRICE, true );
			$display_show_price          = get_post_meta( $post_id, Meta_Enum::SHOW_PRICE, true );
			$badge_text                  = get_post_meta( $post_id, Meta_Enum::BADGE_TEXT, true );
			$show_disclosure             = get_post_meta( $post_id, Meta_Enum::SHOW_DISCLOSURE, true );
			$edit_link                   = self::affiliate_edit_link( $post_id );
			$is_amazon_page              = Amazon_Api::is_amazon_url( $target_url );

			if ( '' === $image_src ) {
				$image_src = Constant::DEFAULT_THUMBNAIL;
			}

			if ( '' === $display_primary_button_text ) {
				$display_primary_button_text = $display_primary_button_text_default;
			}

			if ( '' === $open_new_tab ) {
				$open_new_tab = true;
			}

			if ( '' === $enable_nofollow ) {
				$enable_nofollow = true;
			}

			if ( '' === $display_show_price ) {
				$display_show_price = true;
			}

			if ( '' === $show_disclosure ) {
				$show_disclosure = $lasso_lite_settings['show_disclosure'] ?? true;
			}

			if ( $is_amazon_page ) {
				$product_id = Amazon_Api::get_product_id_by_url( $target_url );
				if ( $product_id ) {
					$lasso_amazon_api = new Amazon_Api();
					$product          = $lasso_amazon_api->get_amazon_product_from_db( $product_id );

					if ( $product ) {
						$price                = $product['latest_price'];
						$display_last_updated = gmdate( 'm/d/Y h:i a T', strtotime( $product['last_updated'] ) );
					}
				}

				$target_url  = Amazon_Api::get_amazon_product_url( $target_url );
				$public_link = $target_url;
			} else {
				$public_link = $permalink;
			}
		}

		$url_detail_checkbox_open_new_tab     = $open_new_tab ? 'checked' : '';
		$url_detail_checkbox_enable_nofollow  = $enable_nofollow ? 'checked' : '';
		$url_detail_checkbox_show_price       = $display_show_price ? 'checked' : '';
		$url_detail_checkbox_enable_sponsored = $enable_sponsored ? 'checked' : '';
		$url_detail_checkbox_show_disclosure  = $show_disclosure ? 'checked' : '';

		$rel = $enable_nofollow ? 'nofollow' : '';
		$rel = $open_new_tab ? trim( $rel . ' noopener' ) : $rel;
		$rel = $enable_sponsored ? trim( $rel . ' sponsored' ) : $rel;

		$html_attribute_rel    = 'rel="' . $rel . '"';
		$html_attribute_target = $open_new_tab ? '_blank' : '_self';

		$category = wp_get_post_terms( $post_id, Constant::LASSO_CATEGORY, array( 'fields' => 'ids' ) );
		$category = is_array( $category ) ? $category : array();

		$lasso_lite_url = (object) array(
			'id'                  => $post_id,
			'edit_link'           => $edit_link,
			'link_type'           => $link_type,
			'name'                => trim( $name ),
			'slug'                => $slug,
			'guid'                => $guid,
			'permalink'           => $permalink,
			'public_link'         => Amazon_Api::get_amazon_product_url( $public_link ),
			'image_src'           => trim( $image_src ),
			'image_src_default'   => $image_src_default,
			'thumbnail_id'        => $thumbnail_id,
			'target_url'          => $target_url,
			'open_new_tab'        => $open_new_tab,
			'enable_nofollow'     => $enable_nofollow,
			'enable_sponsored'    => $enable_sponsored,
			'description'         => $description,
			'category'            => $category,
			'show_disclosure'     => $show_disclosure,
			'is_amazon_page'      => $is_amazon_page,
			'price'               => $price,
			'display'             => (object) array(
				'primary_button_text'         => $display_primary_button_text,
				'primary_button_text_default' => $display_primary_button_text_default, // phpcs:ignore: use for placeholder
				'theme'                       => Enum::THEME_CACTUS,
				'show_price'                  => $display_show_price,
				'badge_text'                  => $badge_text,
				'disclosure_text'             => $disclosure_text,
				'last_updated'                => $display_last_updated,
			),
			'url_detail_checkbox' => (object) array(
				'open_new_tab'     => $url_detail_checkbox_open_new_tab,
				'enable_nofollow'  => $url_detail_checkbox_enable_nofollow,
				'enable_sponsored' => $url_detail_checkbox_enable_sponsored,
				'show_disclosure'  => $url_detail_checkbox_show_disclosure,
				'show_price'       => $url_detail_checkbox_show_price,
			),
			'html_attribute'      => (object) array(
				'rel'    => $html_attribute_rel,
				'target' => $html_attribute_target,
			),
		);

		return $lasso_lite_url;
	}

	/**
	 * Add a new Lasso link
	 *
	 * @param string $link Link. Default to empty.
	 * @return void|string
	 */
	public function add_a_new_link( $link = '' ) {
		$post = Helper::POST();
		$link = trim( $link ?? '' );
		$link = esc_url_raw( $link );
		$url  = trim( $link != '' ? $link : ( $post['link'] ?? '' ) ); // phpcs:ignore
		$url  = esc_url_raw( $url );

		$is_ajax_request = wp_doing_ajax() && '' === $link;

		if ( '' === $url ) {
			if ( $is_ajax_request ) {
				wp_send_json_error( 'No data to save.' );
			} else {
				return 'No data to save.';
			}
		}

		$lasso_amazon_api = new Amazon_Api();

		$url            = Helper::add_https( $url );
		$url            = Helper::format_url_before_requesting( $url );
		$url            = Amazon_Api::get_redirect_url( $url );
		$is_amazon_link = Amazon_Api::is_amazon_url( $url );
		$get_final_url  = $url;
		$permalink      = Helper::get_title_by_url( $url );
		$title          = $is_amazon_link ? self::DEFAULT_AMAZON_NAME : $permalink;
		$default_title  = $title;
		$image          = Constant::DEFAULT_THUMBNAIL;

		// ? Check whether product is existing
		$lasso_post_id = self::get_lasso_lite_post_id_by_url( $url );

		if ( $lasso_post_id > 0 ) {
			wp_update_post(
				array(
					'ID'          => $lasso_post_id,
					'post_status' => 'publish',
				)
			);
			if ( $is_ajax_request ) {
				wp_send_json_success(
					array(
						'success'      => true,
						'is_duplicate' => true,
						'post_id'      => $lasso_post_id,
					)
				);
			} else {
				return $lasso_post_id;
			}
		}

		$url        = Amazon_Api::get_amazon_product_url( $url, true, false );
		$product_id = Amazon_Api::get_product_id_by_url( $get_final_url );

		if ( $is_amazon_link && $product_id ) {
			$product = $lasso_amazon_api->get_amazon_product_from_db( $product_id );

			if ( $product ) {
				$lasso_amazon_api->update_amazon_product_in_db(
					array(
						'product_id'      => $product['amazon_id'],
						'title'           => $product['default_product_name'],
						'price'           => $product['latest_price'],
						'default_url'     => $product['base_url'],
						'url'             => $url,
						'image'           => $product['default_image'],
						'quantity'        => '0' === $product['out_of_stock'] ? 200 : 0,  // ? Manual checks won't show out of stock for now. TODO: Add BLS to out of stock checks.
						'is_manual'       => $product['is_manual'],
						'is_prime'        => $product['is_prime'],
						'features'        => $product['features'],
						'currency'        => $product['currency'],
						'savings_amount'  => $product['savings_amount'],
						'savings_percent' => $product['savings_percent'],
						'savings_basis'   => $product['savings_basis'],
					)
				);
			}

			if ( ! $product ) {
				$product_info = $lasso_amazon_api->fetch_product_info( $product_id, true, false, $get_final_url );
				if ( ! empty( $product_info ) ) {
					$product = $product_info['product'];

					if ( 'NotFound' === $product_info['error_code'] ) {
						$res['status_code']              = 404;
						$product['default_product_name'] = self::DEFAULT_AMAZON_NAME;
						$product['default_image']        = Constant::DEFAULT_THUMBNAIL;
						$product['monetized_url']        = $url;
					} else {
						$product['default_product_name'] = $product['title'];
						$product['default_image']        = $product['image'];
						$product['monetized_url']        = $product['url'];
					}

					$title = $product['default_product_name'];
					$image = $product['default_image'];
				}
			}

			$url   = Amazon_Api::get_amazon_product_url( $get_final_url, true, false );
			$title = $product['default_product_name'] ?? $title;
			$image = $product['default_image'] ?? $image;
		}

		if ( ! $title ) {
			$title = Helper::get_title_by_url( $url );
		}

		$affiliate_link = array(
			'is_amazon'       => $is_amazon_link,
			'affiliate_name'  => $title,
			'surl_redirect'   => trim( $url ),
			'affiliate_desc'  => '',
			'permalink'       => sanitize_title( $title ),
			'is_opportunity'  => 1,
			'buy_btn_text'    => '',
			'second_btn_text' => '',
			'price'           => '',
			'badge_text'      => '',
			'second_btn_url'  => '',
			'thumbnail'       => $image,
			'description'     => '',
		);

		$data['settings'] = $affiliate_link;
		$post_id          = $this->save_lasso_url( $data );

		if ( '' !== $link ) {
			return $post_id;
		}

		wp_send_json_success(
			array(
				'success' => true,
				'url'     => $url,
				'title'   => $title,
				'post_id' => $post_id,
			)
		);
	}

	/**
	 * Save Lasso data into DB
	 *
	 * @param array $data           Lasso data. Default to null.
	 * @param bool  $is_ajax        Is request ajax. Default to false.
	 */
	public function save_lasso_url( $data = null, $is_ajax = false ) {
		$lasso_db         = new Lasso_DB();
		$lasso_amazon_api = new Amazon_Api();

		$warning         = '';
		$is_ajax_request = wp_doing_ajax() && ( '' === $data || null === $data );
		$is_ajax_request = $is_ajax_request || $is_ajax;
		$post            = is_array( $data ) ? $data : $_POST; // phpcs:ignore

		$post                   = wp_unslash( $post ); // phpcs:ignore
		$post_id                = intval( $post['post_id'] ?? 0 );
		$is_update              = $post_id > 0;
		$is_change_primary_link = $post['is_change_primary_link'] ?? false;
		$is_change_primary_link = Helper::cast_to_boolean( $is_change_primary_link );
		$is_change_primary_link = true === $is_change_primary_link;

		$thumbnail_id = $post['thumbnail_id'] ?? 0;
		$post_data    = $post['settings'] ?? array();

		if ( empty( $post_data ) || ! is_array( $post_data ) ) {
			$error_message = 'No data to save.';
			if ( $is_ajax_request ) {
				wp_send_json_error( $error_message );
			} else {
				return $error_message;
			}
		}

		if ( empty( trim( $post_data['surl_redirect'] ?? '' ) ) || empty( trim( $post_data['affiliate_name'] ?? '' ) ) ) {
			$error_message = 'Name and Target URL are required.';
			if ( $is_ajax_request ) {
				wp_send_json_error( $error_message );
			} else {
				return $error_message;
			}
		}

		$lasso_lite_url   = self::get_lasso_url( $post_id );
		$post_title       = $post_data['affiliate_name'];
		$post_name        = $post_data['permalink'] ?? $lasso_lite_url->slug ?? '';
		$surl_redirect    = trim( $post_data['surl_redirect'] ?? '' );
		$buy_btn_text     = $post_data['buy_btn_text'] ?? $lasso_lite_url->display->primary_button_text;
		$open_new_tab     = $post_data['open_new_tab'] ?? $lasso_lite_url->open_new_tab;
		$enable_nofollow  = $post_data['enable_nofollow'] ?? $lasso_lite_url->enable_nofollow;
		$enable_sponsored = $post_data['enable_sponsored'] ?? $lasso_lite_url->enable_sponsored;
		$show_disclosure  = $post_data['show_disclosure'] ?? $lasso_lite_url->show_disclosure;
		$show_price       = $post_data['show_price'] ?? $lasso_lite_url->display->show_price;
		$price            = $post_data['price'] ?? $lasso_lite_url->price;
		$thumbnail        = $post_data['thumbnail'] ?? Constant::DEFAULT_THUMBNAIL;
		$description      = $post_data['description'] ?? $lasso_lite_url->description;
		$term             = isset( $post_data['categories'] ) && is_array( $post_data['categories'] ) ? $post_data['categories'] : array();
		$badge_text       = $post_data['badge_text'] ?? '';
		$term             = array_map(
			function( $val ) {
				$term_id = is_numeric( $val ) ? intval( $val ) : 0;
				$term_id = get_term_by( 'name', $val, Constant::LASSO_CATEGORY )->term_id ?? $term_id;
				// ? Support category name is number and different existed term ids.
				$term_id = $term_id && term_exists( $term_id ) ? $term_id : 0;

				if ( 0 === $term_id && ! empty( $val ) ) { // ? add new category
					$result  = wp_insert_term( $val, Constant::LASSO_CATEGORY );
					$term_id = ( ! is_wp_error( $result ) ) ? $result['term_id'] : 0;
				}

				return $term_id;
			},
			$term
		);

		// ? Check whether product is existing
		$lasso_post_id = self::get_lasso_lite_post_id_by_url( $surl_redirect );
		if ( $lasso_post_id > 0 && $is_update && $is_change_primary_link ) {
			wp_update_post(
				array(
					'ID'          => $lasso_post_id,
					'post_status' => 'publish',
				)
			);
			if ( $is_ajax_request ) {
				wp_send_json_success(
					array(
						'success'      => true,
						'is_duplicate' => true,
						'post_id'      => $lasso_post_id,
					)
				);
			} else {
				return $lasso_post_id;
			}
		}

		$affiliate_homepage = Helper::get_base_domain( $surl_redirect );
		$is_opportunity     = 1;
		$product_id         = Amazon_Api::get_product_id_by_url( $surl_redirect );
		$product_type       = Amazon_Api::is_amazon_url( $surl_redirect ) ? Amazon_Api::PRODUCT_TYPE : '';

		if ( Amazon_Api::PRODUCT_TYPE === $product_type ) {
			$product = $lasso_amazon_api->get_amazon_product_from_db( $product_id );
			if ( ! $product ) {
				$product = $lasso_amazon_api->fetch_product_info( $product_id, true, false, $surl_redirect );
			}

			$is_importing               = $data['is_importing'] ?? false;
			$old_redirect_url           = get_post_meta( $post_id, Meta_Enum::SURL_REDIRECT, true );
			$old_thumbnail              = get_post_meta( $post_id, Meta_Enum::LASSO_LITE_CUSTOM_THUMBNAIL, true );
			$update_title               = 'Amazon' === $post_title;
			$update_thumbnail           = Constant::DEFAULT_THUMBNAIL === $thumbnail || $is_importing;
			$use_defined_affiliate_name = $data['use_defined_affiliate_name'] ?? false;

			if ( Amazon_Api::is_amazon_url( $old_redirect_url ) ) {
				$old_amazon_id    = Amazon_Api::get_product_id_by_url( $old_redirect_url );
				$update_title     = ( $product_id !== $old_amazon_id || $update_title ) && ! $use_defined_affiliate_name;
				$update_thumbnail = strpos( $old_thumbnail, 'media-amazon.' ) !== false || Constant::DEFAULT_THUMBNAIL === $thumbnail;
			} else {
				$update_title = $use_defined_affiliate_name ? false : true;
			}

			$post_title = $update_title ? ( $product['default_product_name'] ?? $post_title ) : $post_title;
			$thumbnail  = $update_thumbnail ? ( $product['default_image'] ?? ( $product['product']['image'] ?? $thumbnail ) ) : $thumbnail;
		}

		$lasso_lite_post = array(
			'post_title'   => $post_title,
			'post_type'    => SIMPLE_URLS_SLUG,
			'post_name'    => $post_name,
			'post_content' => '',
			'post_status'  => 'publish',
			'meta_input'   => array(
				Meta_Enum::SURL_REDIRECT               => $surl_redirect,
				Meta_Enum::LASSO_LITE_CUSTOM_THUMBNAIL => $thumbnail,
				Meta_Enum::OPEN_NEW_TAB                => $open_new_tab,
				Meta_Enum::ENABLE_NOFOLLOW             => $enable_nofollow,
				Meta_Enum::BUY_BTN_TEXT                => $buy_btn_text,
				Meta_Enum::DESCRIPTION                 => $description,
				Meta_Enum::SHOW_PRICE                  => $show_price,
				Meta_Enum::PRICE                       => $price,
				Meta_Enum::ENABLE_SPONSORED            => $enable_sponsored,
				Meta_Enum::SHOW_DISCLOSURE             => $show_disclosure,
				Meta_Enum::BADGE_TEXT                  => $badge_text,
			),
		);

		if ( $is_update ) {
			// ? Check duplicate slug
			$duplicate_post = Helper::the_slug_exists( $post_name, $post_id );

			if ( $duplicate_post ) {
				$warning = 'Permalink <a href="' . get_edit_post_link( $duplicate_post['ID'] ) . '" class="white underline" target="_blank"><strong>' . $duplicate_post['post_name'] . '</strong></a> is being used by <strong>' . $duplicate_post['post_type'] . '</strong>. We updated the permalink to avoid a conflict.';
			}
			// ? END

			$lasso_lite_post['ID'] = $post_id;
			wp_update_post( $lasso_lite_post );
			clean_post_cache( $post_id );
		} else {
			$post_id = wp_insert_post( $lasso_lite_post, true );
		}

		if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
			// ? update categories
			wp_set_object_terms( $post_id, $term, Constant::LASSO_CATEGORY );
			$lasso_db->update_url_details( $post_id, $surl_redirect, $affiliate_homepage, $is_opportunity, $product_id, $product_type );
			// ? update thumbnail
			if ( $thumbnail_id > 0 ) {
				set_post_thumbnail( $post_id, $thumbnail_id );
				$image_url = wp_get_attachment_url( $thumbnail_id );
				update_post_meta( $post_id, Meta_Enum::LASSO_LITE_CUSTOM_THUMBNAIL, $image_url );
			} else {
				delete_post_thumbnail( $post_id );
			}
			$lasso_lite_url = self::get_lasso_url( $post_id );
		}

		if ( $is_ajax_request ) {
			$get_display_html = $post['get_display_html'] ?? false;
			$display_html     = '';
			if ( 'true' === $get_display_html ) {
				$shortcode    = '[lasso id="' . $lasso_lite_url->id . '" rel="' . $lasso_lite_url->slug . '"]';
				$display_html = do_shortcode( $shortcode );
			}

			wp_send_json_success(
				array(
					'success'      => 1,
					'warning'      => $warning,
					'post'         => $lasso_lite_url,
					'display_html' => $display_html,
				)
			);
		}

		return $post_id;
	}

	/**
	 * Get edit url of post
	 *
	 * @param int $post_id The post id.
	 * @return string      Edit url of the post id.
	 */
	public static function affiliate_edit_link( $post_id = 0 ) {
		$query = array(
			'post_type' => SIMPLE_URLS_SLUG,
			'page'      => self::$edit_details_page,
			'post_id'   => $post_id,
		);

		return add_query_arg(
			$query,
			admin_url( 'edit.php' )
		);
	}

	/**
	 * Get amazon product if by Lasso post id
	 *
	 * @param int $lasso_id Lasso post id.
	 */
	public static function get_amazon_id( $lasso_id ) {
		$lasso_db           = new Lasso_DB();
		$lasso_post_details = $lasso_db->get_url_details( $lasso_id );
		$details_product_id = $lasso_post_details->product_id ?? '';

		$amazon_product_id = get_post_meta( $lasso_id, 'amazon_product_id', true );

		return '' === $details_product_id ? $amazon_product_id : $details_product_id;
	}

	/**
	 * Get Lasso Lite post id by url
	 *
	 * @param string $url URL.
	 * @param int    $default_id Default id. Default to 0.
	 */
	public static function get_lasso_lite_post_id_by_url( $url, $default_id = 0 ) {
		$lasso_lite_db = new Lasso_DB();

		// ? Get post id from url
		$lasso_lite_id = $default_id;
		$url           = trim( $url, '/' );
		$url           = str_replace( '&amp;', '&', $url );
		$parse         = wp_parse_url( $url );
		$path          = '';

		if ( strpos( $url, home_url() ) !== false && isset( $parse['path'] ) ) {
			$path = $parse['path'];
			$path = trim( $path, '/' );

			$explode = explode( '/', $path );
			$slug    = end( $explode );
			$lasso   = get_page_by_path( $slug, OBJECT, Constant::LASSO_POST_TYPE );

			if ( $lasso ) {
				$lasso_lite_id = Constant::LASSO_POST_TYPE === get_post_type( $lasso->ID ) ? $lasso->ID : $lasso_lite_id;
			}
		}

		if ( 0 === $lasso_lite_id ) {
			$lasso_post    = $lasso_lite_db->get_lasso_by_uri( $path ); // ? by redirect url
			$lasso_lite_id = $lasso_post->ID ?? $lasso_lite_id;
		}

		if ( 0 === $lasso_lite_id ) {
			$detail        = $lasso_lite_db->get_url_details_by_url( $url ); // ? by redirect url
			$lasso_lite_id = $detail->lasso_id ?? $lasso_lite_id;
		}

		if ( 0 === $lasso_lite_id && Amazon_Api::is_amazon_url( $url ) ) {
			$amazon_product_id = Amazon_Api::get_product_id_by_url( $url );
			$product_exist     = $lasso_lite_db->check_amazon_product_exist( $amazon_product_id );
			$lasso_post_id     = $lasso_lite_db->get_lasso_id_by_product_id_and_type( $product_exist['amazon_id'] ?? '' );
			$lasso_lite_id     = $lasso_post_id ? $lasso_post_id : $default_id;
		}

		if ( 0 === $lasso_lite_id ) {
			$lasso_lite_id = Lasso_DB::get_lasso_lite_id_by_url_from_post_meta( $url );
		}

		$tmp_url = Helper::get_final_url_from_url_param( $url );
		$tmp_url = $tmp_url ? $tmp_url : $url;
		$k       = Amazon_Api::is_amazon_search_page( $tmp_url );
		if ( 0 === $lasso_lite_id && $k ) {
			$k             = rawurlencode( $k );
			$detail        = $lasso_lite_db->get_url_details_by_url( '%/s?k=' . $k . '%' ); // ? by redirect url
			$lasso_lite_id = $detail->lasso_id ?? $lasso_lite_id;
		}

		return intval( $lasso_lite_id );
	}
}
