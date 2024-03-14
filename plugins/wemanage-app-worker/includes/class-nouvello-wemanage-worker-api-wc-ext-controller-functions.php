<?php

/**
 * Nouvello Extented WC API Controller Functions Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


if (!class_exists('Nouvello_Wemanage_Worker_Api_WC_Ext_Controller_functions')) :

	/**
	 * Extented WC API Controller Class.
	 *
	 * @since 1.0
	 */
	final class Nouvello_Wemanage_Worker_Api_WC_Ext_Controller_Functions
	{

		/**
		 * Constructor
		 */
		public function __construct()
		{
			// modify exisiting wc endpoint - product ->  return with additional data.
			add_filter('woocommerce_rest_prepare_product_object', array($this, 'custom_change_product_response'), 20, 3);
			add_filter('woocommerce_rest_prepare_product_variation_object', array($this, 'custom_change_product_response'), 20, 3);
			// modify exisiting wc endpoint - order ->  return with additional data.
			add_filter('woocommerce_rest_prepare_shop_order_object', array($this, 'custom_change_order_response'), 20, 3);

			// sales report.
			add_filter('woocommerce_rest_prepare_report_sales', array($this, 'custom_change_report_sales_response'), 20, 3);
			// top sellers.
			add_filter('woocommerce_rest_prepare_report_top_sellers', array($this, 'custom_change_report_top_sellers_response'), 20, 3);
		}

		/**
		 * Get example data.
		 *
		 * @param  [type] $data [description].
		 * @return array        Returned data
		 */
		public function get_example_data($data)
		{
			return array('example_data' => 'Returned Example Data');
		}

		/**
		 * Nouvello wp_get_categories_hierarchy.
		 *
		 * @return array        Returned data
		 */
		public function nouvello_wp_get_categories_hierarchy()
		{
			$args = array(
				'taxonomy' => 'product_cat',
				'hide_empty' => 0,
				'parent' => 0,
			);

			$terms = $this->helper_get_child_terms(get_terms($args));
			return $terms;
		}

		/**
		 * Helper function of nouvello_wp_get_categories_hierarchy()
		 *
		 * @param  [type] $items [description].
		 * @return [type]        [description]
		 */
		public function helper_get_child_terms($items)
		{
			foreach ($items as $item) {
				$item->children = get_terms(
					'product_cat',
					array(
						'child_of' => $item->term_id,
						'hide_empty' => 0,
					)
				);
				$item->child_count = count($item->children);
				if ($item->children) {
					$this->helper_get_child_terms($item->children);
				}
			}
			return $items;
		}

		/**
		 * [nouvello_get_category_html_and_tags_array description]
		 *
		 * @param  [type] $data [description] - params sent via a GET or POST request.
		 * @param  [type] $args [description] - any other arguments.
		 * @return [type]       [description]
		 */
		public function nouvello_get_category_html_and_tags_obj($data, $args = '')
		{
			return array(
				'category_list' => $this->nouvello_wp_get_categories_hierarchy_html($data, $args), // categories with checkboxes markup.
				'category_select' => $this->helper_add_new_category_select_markup(), // add new category markup.
				'all_tags' => get_terms( // an object of all tag terms registered with wc.
					array(
						'taxonomy' => 'product_tag',
						'hide_empty' => false,
					)
				),
			);
		}

		/**
		 * Same as the function above, but gets a few extra details along with the request.
		 *
		 * @param  [type] $data [description] - params sent via a GET or POST request.
		 * @param  [type] $args [description] - any other arguments.
		 * @return [type]       [description]
		 */
		public function nouvello_get_category_html_and_tags_obj_w_attr_terms_and_currency($data, $args = '')
		{

			$return = array(
				'category_list' => $this->nouvello_wp_get_categories_hierarchy_html($data, $args), // categories with checkboxes markup.
				'category_select' => $this->helper_add_new_category_select_markup(), // add new category markup.
				'all_tags' => get_terms( // an object of all tag terms registered with wc.
					array(
						'taxonomy' => 'product_tag',
						'hide_empty' => false,
					)
				),
				'nouvello_all_attributes_with_terms' => $this->nouvello_get_all_attributes_with_terms(''), // do not pass data.
				'nouvello_wc_currency_symbol' => get_woocommerce_currency_symbol(),
			);

			// additional data - get products price range.
			if ('' != $data) { // we have $data sent via GET or POST.
				$params = $this->get_passed_api_params($data);
				if (isset($data) && isset($data[0]) && isset($data[0]['additional_data']) && 'products_price_range' == $data[0]['additional_data']) {
					$return['products_price_range'] = $this->get_products_price_range();
				}
			}
			return $return;
		}

		/**
		 * [nouvello_wp_get_categories_hierarchy_html description]
		 *
		 * @param  [type] $data [description] - params sent via a GET or POST request.
		 * @param  [type] $args [description] - any other arguments.
		 * @return [type]       [description]
		 */
		public function nouvello_wp_get_categories_hierarchy_html($data, $args = '')
		{

			if ('' != $data) { // we have $data sent via GET or POST.
				$params = $this->get_passed_api_params($data);
			} else if ('' == $data && '' != $args) { // we have $args instead of passed $data from a GET or a POST.
				$params = $args;
			}

			$categories = $this->helper_get_categories_checkboxes_markup(0, $params['product_categories']);
			// get the categories starting with parents (level 0 ).
			$html = '<div class="categorydiv">
					<div id="product_cat-all" class="tabs-panel">
						<input type="hidden" name="tax_input[product_cat][]" value="0">			
						<ul id="product_catchecklist" data-wp-lists="list:product_cat" class="categorychecklist form-no-clear">';
			$html .= $categories;
			$html .= '</ul></div></div>';

			return $html;
		}

		/**
		 * [helper_get_categories_checkboxes_markup description]
		 *
		 * @param  [type] $parent               [description].
		 * @param  string $product_category_ids [description].
		 * @return [type]                       [description]
		 */
		public function helper_get_categories_checkboxes_markup($parent, $product_category_ids = '')
		{

			$html = '';
			$categories = get_categories(
				array(
					'taxonomy' => 'product_cat',
					'hide_empty' => false,
					'parent' => $parent,
				)
			);

			$checked = '';

			if ($categories) {
				foreach ($categories as $category) {
					$name = $category->name;
					if ($product_category_ids) {
						if (in_array($category->cat_ID, $product_category_ids)) {
							$checked = ' checked="checked" ';
						} else {
							$checked = '';
						}
					}

					// HERE THE RECURSION IS USED TO GET SUBCATEGORIES TREE.
					$children = $this->helper_get_categories_checkboxes_markup($category->cat_ID, $product_category_ids);
					if ($children) {
						// category has children, use expandable style.
						$html .= '<li data-cat-name="' . $name . '" data-cat-id="' . $category->cat_ID . '" data-parent-cat-id="' . $category->parent . '">
												<label class="selectit">
													<input value="' . $category->cat_ID . '" type="checkbox" name="tax_input[product_cat][]"' . $checked . '>'
							. $name .
							'</label>
												<ul class="children">' . $children . '</ul>
											</li>';
					} else {
						// category hasn't any children, use endpoint style.
						$html .= '<li data-cat-name="' . $name . '" data-cat-id="' . $category->cat_ID . '" data-parent-cat-id="' . $category->parent . '">
												<label class="selectit">
													<input value="' . $category->cat_ID . '" type="checkbox" name="tax_input[product_cat][]"' . $checked . '>'
							. $name .
							'</label>
											</li>';
					}
				}
			}
			return $html;
		}

		/**
		 * [helper_add_new_category_select_markup description]
		 *
		 * @return [type] [description]
		 */
		public function helper_add_new_category_select_markup()
		{
			$parent_dropdown_args = array(
				'taxonomy'         => 'product_cat',
				'hide_empty'       => 0,
				'id'               => 'newproduct_cat_parent',
				'name'             => '',
				'orderby'          => 'name',
				'hierarchical'     => 1,
				'show_option_none' => '&mdash; Parent category &mdash;',
				'class' => 'form-select form-select-solid form-select-lg fw-bold',
			);
			ob_start();
			wp_dropdown_categories($parent_dropdown_args);
			return ob_get_clean();
		}

		/**
		 * [get_categories_simple_html_list]
		 *
		 * @param  [type] $parent [description].
		 * @return [type]         [description]
		 */
		public function get_categories_simple_html_list($parent)
		{
			$html = '';
			$categories = get_categories(
				array(
					'taxonomy' => 'product_cat',
					'parent' => $parent,
				)
			);
			$data['parents'] = $categories;
			if ($categories) {
				foreach ($categories as $category) {
					$name = $category->name;
					// HERE THE RECURSION IS USED TO GET SUBCATEGORIES TREE.
					$children = $this->get_categories_simple_html_list($category->cat_ID);
					if ($children) {
						// category has children, use expandable style.
						$html .= '<li>' . $name . '<ul>' . $children . '</ul></li>';
					} else {
						// category hasn't any children, use endpoint style.
						$html .= '<li>
													<a href="' . get_category_link($category->cat_ID) . '">' . $name .
							'<span class=\"float-right\">(' . $category->count . ')</span>
													</a>
												</li>';
					}
				}
			}
			return $html;
		}

		/**
		 * Helper function to get the passed api params.
		 *
		 * @param  [type] $data [description].
		 * @return [type]       [description]
		 */
		public function get_passed_api_params($data)
		{
			$api_params = array();
			$params = $data->get_params();
			if (isset($params) && is_array($params) && !empty($params)) {
				unset($params['oauth_consumer_key']);
				unset($params['oauth_nonce']);
				unset($params['oauth_signature']);
				unset($params['oauth_signature_method']);
				unset($params['oauth_timestamp']);
				foreach ($params as $param => $value) {
					$api_params[$param] = $data->get_param($param);
				}
			}

			return $api_params;
		}



		/**
		 * Get all the attributes registered in WooCommerce - regardless of a specific product.
		 *
		 * @param  [type] $data [description].
		 * @return [type]       [description]
		 */
		public function nouvello_get_all_attributes_with_terms($data)
		{

			$product_attributes   = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			foreach ($attribute_taxonomies as $attribute) {
				$product_attributes[] = array(
					'id'           => intval($attribute->attribute_id),
					'name'         => $attribute->attribute_label,
					'slug'         => wc_attribute_taxonomy_name($attribute->attribute_name),
					'type'         => $attribute->attribute_type,
					'order_by'     => $attribute->attribute_orderby,
					'has_archives' => (bool) $attribute->attribute_public,
					'terms'        => get_terms(
						wc_attribute_taxonomy_name($attribute->attribute_name),
						'orderby=name&hide_empty=0'
					),
				);
			}

			return $product_attributes;
		}

		/**
		 * Modify WC get product API request to return additional data.
		 *
		 * @param  [type] $response [description].
		 * @param  [type] $object   [description].
		 * @param  [type] $request  [description].
		 * @return [type]           [description].
		 */
		public function custom_change_product_response($response, $object, $request)
		{

			// $request is silent to improve security. but values are there.

			$append_variations = !empty($request['append_variations']) ? true : false;
			if ($append_variations) {
				$variations = $response->data['variations'];
				$variations_res = array();
				$variations_array = array();
				if (!empty($variations) && is_array($variations)) {
					foreach ($variations as $variation) {
						$variation_id = $variation;
						$variation = new WC_Product_Variation($variation_id);
						$variations_res['id'] = $variation_id;
						$variations_res['on_sale'] = $variation->is_on_sale();
						$variations_res['regular_price'] = (float) $variation->get_regular_price();
						$variations_res['sale_price'] = (float) $variation->get_sale_price();
						$variations_res['sku'] = $variation->get_sku();
						$variations_res['quantity'] = $variation->get_stock_quantity();
						$variations_res['image_id'] = $variation->get_image_id();
						$variations_res['image_url'] = wp_get_attachment_url($variations_res['image_id']);
						if (null == $variations_res['quantity']) {
							$variations_res['quantity'] = '';
						}
						$variations_res['stock'] = $variation->get_stock_quantity();

						$attributes = array();
						// variation attributes.
						foreach ($variation->get_variation_attributes() as $attribute_name => $attribute) {
							// taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`.
							$attributes[] = array(
								'id'     => wc_attribute_taxonomy_id_by_name(wc_attribute_label(str_replace('attribute_', '', $attribute_name), $variation)),
								'name'   => wc_attribute_label(str_replace('attribute_', '', $attribute_name), $variation),
								'slug'   => str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name)),
								'attr_type' => (strpos(str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name)), 'pa_') !== false) ? 'global' : 'custom',
								'term_name' => get_term_by('slug', $attribute, str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name)))->name,
								'term_slug' => $attribute,
							);

							$attributes_list[] = wc_attribute_label(str_replace('attribute_', '', $attribute_name), $variation);
							$terms_list[] = get_term_by('slug', $attribute, str_replace('attribute_', '', wc_attribute_taxonomy_slug($attribute_name)))->name;
						}
						$variations_res['menu_order'] = $variation->menu_order;
						$variations_res['attributes'] = $attributes;
						$variations_array[] = $variations_res;
					}

					$response->data['nouvello_product_variations'] = $variations_array;
				}
			}

			// additional data - attributes & terms, currency symbol.
			$append_additional_data = !empty($request['append_additional_data']) ? true : false;
			if ($append_additional_data) {
				// additional data.
				$response->data['nouvello_all_attributes_with_terms'] = $this->nouvello_get_all_attributes_with_terms(''); // do not pass data.
				$response->data['nouvello_this_product_variation_attributes_and_terms'] = $this->nouvello_get_this_product_variation_attributes_and_terms($variations_array);
				$response->data['nouvello_wc_currency_symbol'] = get_woocommerce_currency_symbol();
			}

			// append currency symbol.
			$append_currency_symbol = !empty($request['append_currency_symbol']) ? true : false;
			if ($append_currency_symbol) {
				$response->data['nouvello_wc_currency_symbol'] = get_woocommerce_currency_symbol();
			}

			// additional data - categories and tags.
			$append_category_html_and_tags_obj_with_selected = !empty($request['append_category_html_and_tags_obj_with_selected']) ? true : false;
			if ($append_category_html_and_tags_obj_with_selected) {
				// build the array product's selected categories.
				$args = array();
				if (isset($response->data['categories'])) {
					foreach ($response->data['categories'] as $category) {
						$args['product_categories'][] = $category['id'];
					}
					$response->data['category_html_and_tags_obj'] = $this->nouvello_get_category_html_and_tags_obj('', $args);
				}
			}

			// additional data - each variation regular & sale price.
			$append_variation_prices = !empty($request['append_variation_prices']) ? true : false;
			if ($append_variation_prices) {
				$variations = $response->data['variations'];
				$variations_res = array();
				$variations_array = array();
				if (!empty($variations) && is_array($variations)) {
					foreach ($variations as $variation) {
						$variation_id = $variation;
						$variation = new WC_Product_Variation($variation_id);
						$variations_res['regular_price'] = (float) $variation->get_regular_price();
						$variations_res['sale_price'] = (float) $variation->get_sale_price();
						$variations_array[$variation_id] = $variations_res;
					}
				}
				$response->data['variation_prices'] = $variations_array;
			}

			global $_wp_additional_image_sizes;

			if (empty($response->data)) {
				return $response;
			}

			foreach ($response->data['images'] as $key => $image) {
				$image_urls = array();
				// foreach ( $_wp_additional_image_sizes as $size => $value ) {
				// $image_info = wp_get_attachment_image_src( $image['id'], $size );
				// $response->data['images'][ $key ][ $size ] = $image_info[0];
				// } // to get all sizes.
				$image_info = wp_get_attachment_image_src($image['id'], 'thumbnail');
				$response->data['images'][$key]['src'] = $image_info[0]; // get just the thumbnail.
			}

			$requested_fields = !empty($request['requested_fields']) ? true : false;
			if ($requested_fields) {
				return $this->products_api_call_response_service($response, $request['requested_fields']);
			}

			$return_minimal_info = !empty($request['return_minimal_info']) ? true : false;
			if ($return_minimal_info) {
				return $this->products_api_call_remove_fields($response);
			}

			return $response; // default - returns all fields.
		}




		/**
		 * [products_api_call_response_service description]
		 *
		 * @param  [type] $response         [description].
		 * @param  [type] $requested_fields [description].
		 * @return [type]                   [description]
		 */
		public function products_api_call_response_service($response, $requested_fields)
		{
			// returning only requested fields.
			foreach ($response->data as $key => $value) {
				if (!in_array($key, $requested_fields)) {
					unset($response->data[$key]);
				}

				// remove unneeded info from images array.
				if (in_array('images', $requested_fields)) {
					foreach ($response->data['images'] as $image_key => $image) {
						unset($response->data['images'][$image_key]['date_created']);
						unset($response->data['images'][$image_key]['date_created_gmt']);
						unset($response->data['images'][$image_key]['date_modified']);
						unset($response->data['images'][$image_key]['date_modified_gmt']);
						unset($response->data['images'][$image_key]['name']);
					}
				}
			}
			return $response;
		}


		/**
		 * [products_api_call_remove_fields]
		 *
		 * @param  [type] $response         [description].
		 * @return [type]                   [description]
		 */
		public function products_api_call_remove_fields($response)
		{

			// removing the specified fields to improve response time.
			$api_fields = array(
				// 'id',
				// 'name',
				// 'slug',
				// 'permalink',
				// 'date_created',
				'date_created_gmt',
				'date_modified',
				'date_modified_gmt',
				// 'type',
				// 'status',
				'featured',
				'catalog_visibility',
				'description',
				'short_description',
				// 'sku',
				// 'price',
				// 'regular_price',
				// 'sale_price',
				'date_on_sale_from',
				'date_on_sale_from_gmt',
				'date_on_sale_to',
				'date_on_sale_to_gmt',
				// 'on_sale',
				'purchasable',
				'total_sales',
				'virtual',
				'downloadable',
				'downloads',
				'download_limit',
				'download_expiry',
				'external_url',
				'button_text',
				'tax_status',
				'tax_class',
				// 'manage_stock',
				// 'stock_quantity',
				'backorders',
				'backorders_allowed',
				'backordered',
				'low_stock_amount',
				'sold_individually',
				'weight',
				'dimensions',
				'shipping_required',
				'shipping_taxable',
				'shipping_class',
				'shipping_class_id',
				'reviews_allowed',
				'average_rating',
				'rating_count',
				'upsell_ids',
				'cross_sell_ids',
				'parent_id',
				'purchase_note',
				// 'categories',
				// 'tags',
				// 'images',
				'attributes',
				'default_attributes',
				// 'variations',
				'grouped_products',
				'menu_order',
				// 'price_html',
				'related_ids',
				'meta_data',
				// 'stock_status',
				// 'variation_prices',
				'_links',
			);

			foreach ($api_fields as $field) {
				unset($response->data[$field]);
			}

			// remove unneeded info from images array.
			foreach ($response->data['images'] as $image_key => $image) {
				unset($response->data['images'][$image_key]['date_created']);
				unset($response->data['images'][$image_key]['date_created_gmt']);
				unset($response->data['images'][$image_key]['date_modified']);
				unset($response->data['images'][$image_key]['date_modified_gmt']);
				unset($response->data['images'][$image_key]['name']);
			}

			return $response;
		}


		/**
		 * Modify WC get Order API request to return additional data.
		 *
		 * @param  [type] $response [description].
		 * @param  [type] $object   [description].
		 * @param  [type] $request  [description].
		 * @return [type]           [description].
		 */
		public function custom_change_order_response($response, $object, $request)
		{
			// $request is silent to improve security. but values are there.

			// additional data - store information.
			$append_store_info = !empty($request['append_store_info']) ? true : false;
			if ($append_store_info) {

				// Website name.
				$website_name = get_bloginfo('name');

				// The main address pieces.
				$store_address     = get_option('woocommerce_store_address');
				$store_address_2   = get_option('woocommerce_store_address_2');
				$store_city        = get_option('woocommerce_store_city');
				$store_postcode    = get_option('woocommerce_store_postcode');

				// The country/state.
				$store_raw_country = get_option('woocommerce_default_country');

				// Split the country/state.
				$split_country = explode(':', $store_raw_country);

				// Country and state separated.
				$store_country = $split_country[0];
				$store_state   = $split_country[1];

				// Full country name.
				$store_base_country = WC()->countries->countries[$store_country];

				// Logo.
				$store_logo = '';
				$logo = get_theme_mod('custom_logo');
				if (isset($logo)) {
					$logo_image = wp_get_attachment_image_src($logo, 'medium');
					if (isset($logo_image) && isset($logo_image[0])) {
						$store_logo = $logo_image[0];
					}
				}

				$info_array = array(
					'name' => $website_name,
					'address' => $store_address,
					'address_2' => $store_address_2,
					'city' => $store_city,
					'state' => $store_state,
					'postcode' => $store_postcode,
					'country' => $store_country,
					'base_country' => $store_base_country,
					'logo' => $store_logo,
				);

				// Logo.
				$logo = get_theme_mod('custom_logo');
				$logo_image = wp_get_attachment_image_src($logo, 'medium');

				$response->data['store_info'] = $info_array;
			}

			// add order item thumbnails to api response.
			if (!empty($response->data) && !empty($response->data['line_items'])) {
				foreach ($response->data['line_items'] as $key => $item) {
					$item_meta = get_post_meta($item['product_id']);
					if ($item_meta) {
						$item_img = wp_get_attachment_image_src($item_meta['_thumbnail_id'][0], 'thumbnail');
						if ($item_img) {
							$response->data['line_items'][$key]['thumbnail'] = $item_img[0];
						}
					}
				}
			}


			// utm attribution
			$append_utm_attribution = !empty($request['append_utm_attribution']) ? true : false;
			if ($append_utm_attribution && class_exists('Nouvello_WeManage_Utm_Woocommerce')) {
				$order_id = $response->data['id'];
				$attribution = Nouvello_WeManage_Utm_Woocommerce::get_conversion_attribution($order_id);

				$gclid = !empty($attribution['gclid_visit']['value']) ? $attribution['gclid_visit']['value'] : '';
				$fbclid = !empty($attribution['fbclid_visit']['value']) ? $attribution['fbclid_visit']['value'] : '';
				$msclkid = !empty($attribution['msclkid_visit']['value']) ? $attribution['msclkid_visit']['value'] : '';

				$html = '';
				$click_id = '';
				$click_id_src = '';
				$count_margin = 0;

				if (!empty($gclid)) :
					$html .= ($count_margin ? '<div style="margin-top:6px">' : '<div>') . Nouvello_WeManage_Utm_Html::get_clid_tag_html('gclid') . '</div>';
					$click_id = !empty($attribution['gclid_value']['value']) ? $attribution['gclid_value']['value'] : '';
					$click_id_src = 'Google';
					$count_margin++;
				endif;

				if (!empty($fbclid)) :
					$html .= ($count_margin ? '<div style="margin-top:6px">' : '<div>') . Nouvello_WeManage_Utm_Html::get_clid_tag_html('fbclid') . '</div>';
					$click_id = !empty($attribution['fbclid_value']['value']) ? $attribution['fbclid_value']['value'] : '';
					$click_id_src = 'Facebook';
					$count_margin++;
				endif;

				if (!empty($msclkid)) :
					$html .= ($count_margin ? '<div style="margin-top:6px">' : '<div>') . Nouvello_WeManage_Utm_Html::get_clid_tag_html('msclkid') . '</div>';
					$click_id = !empty($attribution['msclid_value']['value']) ? $attribution['msclid_value']['value'] : '';
					$click_id_src = 'Microsoft';
					$count_margin++;
				endif;

				$response->data['utm_attribution'] = $attribution;
				$response->data['utm_click_id'] = $click_id;
				$response->data['utm_click_id_src'] = $click_id_src;
				$response->data['utm_src_html'] = $html;
			}

			return $response;
		}

		/**
		 * Returns a list of all attributes and their terms using for a specific product (in all it's variations combined).
		 *
		 * @param  [type] $variations_array [description].
		 * @return [type]                   [description].
		 */
		public function nouvello_get_this_product_variation_attributes_and_terms($variations_array)
		{
			$seen_attributes = array();
			$seen_terms = array();
			foreach ($variations_array as $variation) {

				foreach ($variation['attributes'] as $attribute) {

					if (!in_array($attribute['name'], $seen_attributes)) {
						$seen_attributes[] = $attribute['name'];
						$seen_terms[$attribute['name']]['attr_type'] = $attribute['attr_type'];
						$seen_terms[$attribute['name']]['id'] = $attribute['id'];
						$seen_terms[$attribute['name']]['options'] = array();
					}
					if ('global' == $attribute['attr_type']) {
						if (!in_array($attribute['term_name'], $seen_terms[$attribute['name']]['options'])) {
							$seen_terms[$attribute['name']]['options'][] = $attribute['term_name'];
						}
					} else if ('custom' == $attribute['attr_type']) {
						if (!in_array($attribute['term_slug'], $seen_terms[$attribute['name']]['options'])) {
							$seen_terms[$attribute['name']]['options'][] = $attribute['term_slug'];
						}
					}
				}
			}

			$final_array = array();
			$index = 0;

			foreach ($seen_terms as $attribute_name => $attribute) {
				$final_array[$index] = new stdClass();
				$final_array[$index]->id = $attribute['id'];
				$final_array[$index]->name = $attribute_name;
				$final_array[$index]->attr_type = $attribute['attr_type'];
				// $final_array[ $index ]->variation_array = $variations_array;
				$final_array[$index]->options = $attribute['options'];
				$index++;
			}

			return $final_array;
		}


		/**
		 * [nouvello_get_shop_stats description]
		 *
		 * @return [type] [description]
		 */
		public function nouvello_get_shop_stats()
		{
			$data = array();
			$data['sales'] = $this->count_sales();
			$data['customers_count'] = $this->count_customers();
			$data['products_count'] = $this->count_products();
			return $data;
		}

		/**
		 * Helper function to get sale stats.
		 *
		 * @return [type] [description]
		 */
		public function count_sales()
		{
			global $wpdb;

			$data = array();

			$post_status = implode('", "', array('wc-completed', 'wc-processing', 'wc-on-hold'));

			$query = 'SELECT SUM(meta.meta_value) AS total_sales, COUNT(posts.ID) AS total_orders FROM ' . $wpdb->prefix . 'posts AS posts LEFT JOIN ' . $wpdb->prefix . 'postmeta AS meta ON posts.ID = meta.post_id WHERE meta.meta_key = "_order_total" AND posts.post_type = "shop_order" AND posts.post_status IN ( "' . $post_status . '" )';
			// @codingStandardsIgnoreStart
			$select_order_details = $wpdb->get_results($query);
			// @codingStandardsIgnoreEnd
			if (isset($select_order_details) && isset($select_order_details[0])) {
				$data = array(
					'total_sales' => $select_order_details[0]->total_sales,
					'total_orders' => $select_order_details[0]->total_orders,
					'currency' => get_woocommerce_currency_symbol(),
				);
			}

			return $data;
		}


		/**
		 * Helper function to count customers.
		 *
		 * @return [type] [description]
		 */
		public function count_customers()
		{
			global $wpdb;
			return $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'wc_customer_lookup');
		}

		/**
		 * Helper function to get total products count.
		 *
		 * @return [type] [description]
		 */
		public function count_products()
		{
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);
			$products = new WP_Query($args);
			return $products->found_posts;
		}


		/**
		 * Goes through all products and finds the lowest and highest price.
		 *
		 * @return [type] [description]
		 */
		public function get_products_price_range()
		{
			global $wpdb;
			$meta_query = new \WP_Meta_Query(array());
			$tax_query  = new \WP_Tax_Query(array());

			$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
			$tax_query_sql  = $tax_query->get_sql($wpdb->posts, 'ID');

			$sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
			$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
			$sql .= " 	WHERE {$wpdb->posts}.post_type IN ('product')
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('_price')
					AND price_meta.meta_value > '' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

			$prices = $wpdb->get_row($sql); // WPCS: unprepared SQL ok.

			$return = array(
				'min' => floor($prices->min_price),
				'max' => ceil($prices->max_price),
			);

			return $return;
		}


		/**
		 * [nouvello_email_order description]
		 *
		 * @param  [type] $data [description].
		 * @return [type]           [description]
		 */
		public function nouvello_email_order($data)
		{
			$api_params = array();
			$params = $data->get_params();
			$order_id = $params['order_id'];

			add_filter('woocommerce_new_order_email_allows_resend', '__return_true');
			// Get the WC_Email_New_Order object.
			$email_new_order = WC()->mailer()->get_emails()['WC_Email_New_Order'];
			// Sending the new Order email notification for an $order_id (order ID).
			$status = $email_new_order->trigger($order_id);
			return json_encode($status);
		}

		/**
		 * Get the woocommerce shop info: name, address, log etc.
		 *
		 * @param  [type] $data [description].
		 * @return [type]       [description]
		 */
		public function nouvello_get_shop_info($data)
		{

			// Website name.
			$website_name = get_bloginfo('name');

			// The main address pieces.
			$store_address     = get_option('woocommerce_store_address');
			$store_address_2   = get_option('woocommerce_store_address_2');
			$store_city        = get_option('woocommerce_store_city');
			$store_postcode    = get_option('woocommerce_store_postcode');

			// The country/state.
			$store_raw_country = get_option('woocommerce_default_country');

			// Split the country/state.
			$split_country = explode(':', $store_raw_country);

			// Country and state separated.
			$store_country = $split_country[0];
			$store_state   = $split_country[1];

			// Full country name.
			$store_base_country = WC()->countries->countries[$store_country];

			// Logo.
			$store_logo = '';
			$logo = get_theme_mod('custom_logo');
			if (isset($logo)) {
				$logo_image = wp_get_attachment_image_src($logo, 'medium');
				if (isset($logo_image) && isset($logo_image[0])) {
					$store_logo = $logo_image[0];
				}
			}

			$info_array = array(
				'name' => $website_name,
				'address' => $store_address,
				'address_2' => $store_address_2,
				'city' => $store_city,
				'state' => $store_state,
				'postcode' => $store_postcode,
				'country' => $store_country,
				'base_country' => $store_base_country,
				'logo' => $store_logo,
			);

			// Logo.
			$logo = get_theme_mod('custom_logo');
			$logo_image = wp_get_attachment_image_src($logo, 'medium');

			return $info_array;
		}


		/**
		 * [nouvello_upload_csv_file description]
		 *
		 * @param  [type] $data [description].
		 */
		public function nouvello_upload_csv_file($data)
		{

			$body = $data->get_body();
			$body_array = json_decode($body, true);
			$body_json_data = $body_array[0]['json_data'];
			$body_array_data = json_decode($body_json_data, true);

			$csv_data = $body_array_data['file_data'];
			$csv_nm = $body_array_data['file_nm'];
			$csv_label = $body_array_data['csv_label'];

			$ext = end(explode('.', $csv_nm));
			$fn_name = time() . '.' . $ext;
			$wp_upload_dir = wp_upload_dir();
			$filepath_nm = $wp_upload_dir['path'] . $fn_name;

			$f = fopen($filepath_nm, 'a');
			fputcsv($f, $csv_label);

			foreach ($csv_data as $csv_inner) :
				fputcsv($f, $csv_inner);
			endforeach;

			fclose($f);

			$result = array(
				'file_path' => $filepath_nm,
				'file_nm' => $csv_nm,
			);

			return json_encode($result);
		}


		/**
		 * [nouvello_import_csv_data description]
		 *
		 * @param  [type] $data [description].
		 */
		public function nouvello_import_csv_data($data)
		{
			global $wpdb;

			$user_id = get_current_user_id();
			wp_set_current_user($user_id); // set the current wp user.
			wp_set_auth_cookie($user_id);

			$body = $data->get_body();
			$body_array = json_decode($body, true);
			// build post array.
			foreach ($body_array[0]['json_data'] as $key => $value) {
				$post_array[$key] = $value;
			}

			include_once WC_ABSPATH . 'includes/admin/importers/class-wc-product-csv-importer-controller.php';
			include_once WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php';

			$file   = wc_clean(wp_unslash($post_array['file'])); // PHPCS: input var ok.
			$params = array(
				'delimiter'       => !empty($post_array['delimiter']) ? wc_clean(wp_unslash($post_array['delimiter'])) : ',', // PHPCS: input var ok.
				'start_pos'       => isset($post_array['position']) ? absint($post_array['position']) : 0, // PHPCS: input var ok.
				'mapping'         => isset($post_array['mapping']) ? (array) wc_clean(wp_unslash($post_array['mapping'])) : array(), // PHPCS: input var ok.
				'update_existing' => isset($post_array['update_existing']) ? (bool) $post_array['update_existing'] : false, // PHPCS: input var ok.
				'lines'           => apply_filters('woocommerce_product_import_batch_size', 30),
				'parse'           => true,
			);

			// Log failures.
			if (0 !== $params['start_pos']) {
				$error_log = array_filter((array) get_user_option('product_import_error_log'));
			} else {
				$error_log = array();
			}

			$importer         = WC_Product_CSV_Importer_Controller::get_importer($file, $params);
			$results          = $importer->import();
			$percent_complete = $importer->get_percent_complete();
			$error_log        = array_merge($error_log, $results['failed'], $results['skipped']);

			update_user_option($user_id, 'product_import_error_log', $error_log);

			if (100 === $percent_complete) {
				// @codingStandardsIgnoreStart.
				$wpdb->delete($wpdb->postmeta, array('meta_key' => '_original_id'));
				$wpdb->delete($wpdb->posts, array(
					'post_type'   => 'product',
					'post_status' => 'importing',
				));
				$wpdb->delete($wpdb->posts, array(
					'post_type'   => 'product_variation',
					'post_status' => 'importing',
				));
				// @codingStandardsIgnoreEnd.

				// Clean up orphaned data.
				$wpdb->query(
					"
						DELETE {$wpdb->posts}.* FROM {$wpdb->posts}
						LEFT JOIN {$wpdb->posts} wp ON wp.ID = {$wpdb->posts}.post_parent
						WHERE wp.ID IS NULL AND {$wpdb->posts}.post_type = 'product_variation'
					"
				);
				$wpdb->query(
					"
						DELETE {$wpdb->postmeta}.* FROM {$wpdb->postmeta}
						LEFT JOIN {$wpdb->posts} wp ON wp.ID = {$wpdb->postmeta}.post_id
						WHERE wp.ID IS NULL
					"
				);
				// @codingStandardsIgnoreStart.
				$wpdb->query("
							DELETE tr.* FROM {$wpdb->term_relationships} tr
							LEFT JOIN {$wpdb->posts} wp ON wp.ID = tr.object_id
							LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
							WHERE wp.ID IS NULL
							AND tt.taxonomy IN ( '" . implode("','", array_map('esc_sql', get_object_taxonomies('product'))) . "' )
					");
				// @codingStandardsIgnoreEnd.

				// Send success.
				wp_send_json_success(
					array(
						'code' => 200,
						'position'   => 'done',
						'percentage' => 100,
						'url'        => add_query_arg(array('_wpnonce' => wp_create_nonce('woocommerce-csv-importer')), admin_url('edit.php?post_type=product&page=product_importer&step=done')),
						'imported'   => count($results['imported']),
						'failed'     => count($results['failed']),
						'updated'    => count($results['updated']),
						'skipped'    => count($results['skipped']),
					)
				);
			} else {
				wp_send_json_success(
					array(
						'code' => 202,
						'position'   => $importer->get_file_position(),
						'percentage' => $percent_complete,
						'imported'   => count($results['imported']),
						'failed'     => count($results['failed']),
						'updated'    => count($results['updated']),
						'skipped'    => count($results['skipped']),
					)
				);
			}
			die();
		}



		/**
		 * [custom_change_report_sales_response]
		 *
		 * @param  [type] $response   [description].
		 * @param  [type] $object     [description].
		 * @param  [type] $request    [description].
		 * @return [type]             [description]
		 */
		public function custom_change_report_sales_response_v1($response, $object, $request)
		{
			if (!empty($response->data)) {
				$response->data['currency_symbol'] = get_woocommerce_currency_symbol();
			}

			if (!empty($response->data['totals'])) {
				foreach ($response->data['totals'] as $key => $value) {
					$date = new DateTime($key);
					$period = $request['period'];
					if ('week' == $period) {
						$response->data['totals'][$key]['clean_key'] = $date->format('D');
					} else if ('month' == $period || 'last_month' == $period) {
						$response->data['totals'][$key]['clean_key'] = intval($date->format('d'));
					} else if ('year' == $period) {
						$response->data['totals'][$key]['clean_key'] = $date->format('M');
					} else if (isset($request['date_min']) && isset($request['date_max'])) {

						$query_args = array(
							'fields' => 'ids',
							'post_type' => 'shop_order',
							'post_status' => array_keys(wc_get_order_statuses()),
							'posts_per_page' => -1,
							'date_query' => array(
								array(
									'before' => $request['date_max'],
									'after'  => $request['date_min'],
									'inclusive' => true,
								),
							),
						);

						$query = new WP_Query($query_args);
						$orders = array();
						if ($query->have_posts()) :
							$index = 0;
							while ($query->have_posts()) :
								$query->the_post();
								$orders[$index]['id'] = get_the_ID();
								$orders[$index]['time'] = get_the_time('g a');
								// order total.
								$order = wc_get_order(get_the_ID());
								$order_total = $order->get_total();
								$orders[$index]['total'] = $order_total;
								// .
								$index++;
							endwhile;
						endif;

						$response->data['totals'][$key]['orders'] = array_reverse($orders);
					} else {
						$response->data['totals'][$key]['else'] = 'ELSE';
					}
				}
			}

			return $response;
		}



		/**
		 * [custom_change_report_sales_response]
		 *
		 * @param  [type] $response   [description].
		 * @param  [type] $object     [description].
		 * @param  [type] $request    [description].
		 * @return [type]             [description]
		 */
		public function custom_change_report_sales_response($response, $object, $request)
		{

			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$starttime = $mtime;

			if (!empty($response->data)) {
				$response->data['currency_symbol'] = get_woocommerce_currency_symbol();
			}

			if (!empty($response->data['totals'])) {
				foreach ($response->data['totals'] as $key => $value) {
					$date = new DateTime($key);
					$period = $request['period'];
					if ('week' == $period) { // not used - we use date range instead.
						$response->data['totals'][$key]['clean_key'] = $date->format('D');
					} else if ('month' == $period || 'last_month' == $period) { // not used - we use date range instead.
						$response->data['totals'][$key]['clean_key'] = intval($date->format('d'));
					} else if ('year' == $period) { // used intead of date range.
						$response->data['totals'][$key]['clean_key'] = $date->format('M');

						// get views & conversions from start of year.

						$current_year = gmdate('Y');
						$last_year = $current_year - 1;
						$start_of_year = $last_year . '-01-01';

						$datetime = new DateTime();
						$today = $datetime->format('Y-m-d'); // now.

						global $wpdb;
						$table_name = $wpdb->prefix . 'nouvello_visitor_counter';
						// @codingStandardsIgnoreStart
						$total_views = $wpdb->get_results($wpdb->prepare('SELECT SUM(`views`) AS "views" FROM `' . $table_name . '` WHERE `date` BETWEEN %s AND %s', $start_of_year, $today));
						// @codingStandardsIgnoreEnd
						if (isset($total_views) && isset($total_views[0]) && isset($total_views[0]->views)) {
							$response->data['total_views'] = $total_views[0]->views;
						} else {
							$response->data['total_views'] = 0;
						}
						// .
						$total_orders = $response->data['total_orders'];
						$total_views = $response->data['total_views'];
						// count views for conversion rates.
						if (isset($total_views) && 0 != $total_orders && 0 != $total_views) {
							$conversion_rate = $total_orders / $total_views * 100;
							$response->data['conversion'] = number_format($conversion_rate, 2, '.', '');
						} else {
							$response->data['conversion'] = 0;
						}

						// if we wish to avoid total order higher then total views (to avoid conversion rates higher the 100%).
						if ($response->data['total_orders'] > $response->data['total_views']) {
							$response->data['total_views'] = $response->data['total_orders'];
							$response->data['conversion'] = 100;
						}
					} else if (isset($request['date_min']) && isset($request['date_max'])) {

						$response->data['totals'][$key]['day_name'] = $date->format('D');
						$response->data['totals'][$key]['day_num'] = intval($date->format('d'));
						$response->data['totals'][$key]['month_name'] = $date->format('M');

						$query_args = array(
							'fields' => 'ids',
							'post_type' => 'shop_order',
							'post_status' => array_keys(wc_get_order_statuses()),
							'posts_per_page' => -1,
							'date_query' => array(
								array(
									'before' => $request['date_max'],
									'after'  => $request['date_min'],
									'inclusive' => true,
								),
							),
						);

						$query = new WP_Query($query_args);
						$orders = array();
						if ($query->have_posts()) :
							$index = 0;
							$order_total_visitors = 0;
							$order_total_views = 0;
							while ($query->have_posts()) :
								$query->the_post();
								$orders[$index]['id'] = get_the_ID();
								$orders[$index]['time'] = get_the_time('g a');
								// order total.
								$order = wc_get_order(get_the_ID());
								$order_total = $order->get_total();
								$orders[$index]['total'] = $order_total;

								// to calculate conversions we saved visitor (unique) and views in the order meta data once order is completed.
								// we retrieve the data here and count the visitor and views and add it to the final array for each retrieval.
								// order meta data (product visit counters ).
								$order_vistor_counter = $order->get_meta('order_vistors_counter');
								// $orders[ $index ]['counters'] = $order_vistor_counter; // we dont want to append this to the response to keep it short. enable this to debug the counters.

								// loop though products in each order and increment $order_total_visitors and $order_total_views by the value of the product visitors and views.
								if (isset($order_vistor_counter) && !empty($order_vistor_counter)) {
									if (isset($order_vistor_counter['products']) && !empty($order_vistor_counter['products'])) {
										foreach ($order_vistor_counter['products'] as $product_counter) {
											$order_total_visitors += $product_counter['visitor'];
											$order_total_views += $product_counter['views'];
										}
									}
								}
								// .
								$index++;
							endwhile;
						endif;

						$response->data['totals'][$key]['orders'] = array_reverse($orders);
						// append counts.
						if (isset($order_total_visitors)) {
							$response->data['total_visitors'] = $order_total_visitors;
						}
						if (isset($order_total_views)) {
							$response->data['total_views'] = $order_total_views;
						}

						/*
						// count unique vists for conversion reates.
						// if ( isset( $order_total_visitors ) && isset( $order_total_views ) && 0 != $response->data['total_orders'] && 0 != $response->data['total_visitors'] ) {
						// 	$conversion_rate = $response->data['total_orders'] / $response->data['total_visitors'] * 100;
						// 	$response->data['conversion'] = number_format( $conversion_rate, 2, '.', '' );
						// } else {
						// 	$response->data['conversion'] = 0;
						// }
						*/

						// count views for conversion rates.
						if (isset($order_total_visitors) && isset($order_total_views) && 0 != $response->data['total_orders'] && 0 != $response->data['total_views']) {
							$conversion_rate = $response->data['total_orders'] / $response->data['total_views'] * 100;
							$response->data['conversion'] = number_format($conversion_rate, 2, '.', '');
						} else {
							$response->data['conversion'] = 0;
						}

						// if we wish to avoid total order higher then total views (to avoid conversion rates higher the 100%).
						if ($response->data['total_orders'] > $response->data['total_views']) {
							$response->data['total_views'] = $response->data['total_orders'];
							$response->data['conversion'] = 100;
						}
					} // end foreach
				} // end if totals
			}
			$mtime = microtime();
			$mtime = explode(' ', $mtime);
			$mtime = $mtime[1] + $mtime[0];
			$endtime = $mtime;
			$totaltime = ($endtime - $starttime);

			$response->data['execution_time'] = $totaltime . ' seconds';

			return $response;
		}


		/**
		 * [custom_change_report_top_sellers_response description]
		 *
		 * @param  [type] $response   [description].
		 * @param  [type] $object     [description].
		 * @param  [type] $request    [description].
		 * @return [type]             [description]
		 */
		public function custom_change_report_top_sellers_response($response, $object, $request)
		{

			if (!empty($response->data) && isset($response->data['product_id'])) {
				// add product thumbnail to api response.
				$product = wc_get_product($response->data['product_id']);
				$response->data['sku'] = $product->get_sku();
				$item_meta = get_post_meta($response->data['product_id']);
				if ($item_meta) {
					$item_img = wp_get_attachment_image_src($item_meta['_thumbnail_id'][0], 'thumbnail');
					if ($item_img) {
						$response->data['thumbnail'] = $item_img[0];
					}
				}
				// .

				// add product views and conversions.
				$date = new DateTime($key);
				$period = $request['period'];
				if (isset($request['date_min']) && isset($request['date_max'])) {
					$start = $request['date_min'];
					$end = $request['date_max'];
				} else if ('year' == $period) {
					// get views & conversions from start of year.
					$current_year = gmdate('Y');
					$last_year = $current_year - 1;
					$start_of_year = $last_year . '-01-01';

					$datetime = new DateTime();
					$today = $datetime->format('Y-m-d'); // now.

					$start = $start_of_year;
					$end = $today;
				}

				global $wpdb;
				$table_name = $wpdb->prefix . 'nouvello_visitor_counter';
				// @codingStandardsIgnoreStart
				$total_views = $wpdb->get_results($wpdb->prepare('SELECT SUM(`views`) AS "views" FROM `' . $table_name . '` WHERE `post_id` = %d AND `date` BETWEEN %s AND %s', $response->data['product_id'], $start, $end));
				// @codingStandardsIgnoreEnd
				if (isset($total_views) && isset($total_views[0]) && isset($total_views[0]->views)) {
					$response->data['total_views'] = $total_views[0]->views;
				} else {
					$response->data['total_views'] = 0;
				}
				// .
				$total_orders = $response->data['quantity'];
				$total_views = $response->data['total_views'];
				// count views for conversion rates.
				if (isset($total_views) && 0 != $total_orders && 0 != $total_views) {
					$conversion_rate = $total_orders / $total_views * 100;
					$response->data['conversion'] = number_format($conversion_rate, 2, '.', '');
				} else {
					$response->data['conversion'] = 0;
				}
				// .
				// if we wish to avoid total order higher then total views (to avoid conversion rates higher the 100%).
				if ($total_orders > $response->data['total_views']) {
					$response->data['total_views'] = $total_orders;
					$response->data['conversion'] = 100;
				}
			}

			return $response;
		}
	} // end of class

endif; // end if class exist.
