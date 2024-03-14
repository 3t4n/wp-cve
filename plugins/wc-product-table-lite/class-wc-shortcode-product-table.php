<?php
/**
 * Product table shortcode class.
 */

if (!defined('ABSPATH')) {
	exit;
}

class WC_Shortcode_Product_Table extends WC_Shortcode_Products
{

	public $id;
	public $caching = false;
	public $transient_name;
	public $search_keyword = array('title' => '', 'content' => '');
	public $only_loop = false;
	public $disable_nav = false;
	public $query_args = array();
	public $attributes = array();
	public $search_ids = false;

	public function __construct($attributes = array(), $type = 'product_table')
	{
		$this->type = $type;
		$this->attributes = $this->parse_attributes($attributes);

	}

	public function get_transient_name()
	{
		if (!$this->transient_name) {

			// inital query
			$initial_query =& $GLOBALS['wcpt_table_data']['query'];

			// user query
			$user_query = array();
			foreach ($_GET as $key => $val) {
				if (FALSE !== strpos($key, $this->id . '_')) {
					$user_query[$key] = $val;
				}
			}

			// user role
			$user = wp_get_current_user();
			$user_roles = (array) $user->roles;

			// merge all 3
			$combined = array_merge($initial_query, $user_query, $user_roles);
			sort($combined);

			$this->transient_name = 'wcpt_' . $this->id . '_cache_' . md5(wp_json_encode($combined)) . WC_Cache_Helper::get_transient_version('product_loop');
		}

		return $this->transient_name;

	}

	public function get_cache()
	{
		if (!$this->caching) {
			return false;
		}
		return get_transient($this->get_transient_name());
	}

	public function set_cache($markup)
	{
		if (!$this->caching) {
			return;
		}
		set_transient($this->get_transient_name(), $markup, DAY_IN_SECONDS);
	}

	protected function get_products()
	{
		$data = wcpt_get_table_data();

		if (
			!empty($this->query_args['p']) &&
			!empty($data['query']['sc_attrs']['ids'])
		) {
			$this->query_args['post__in'] = array_map('trim', explode(',', $data['query']['sc_attrs']['ids']));
			unset($this->query_args['p']);
		}

		$this->query_args = apply_filters('wcpt_query_args', $this->query_args);

		if (!empty($data['query']['sc_attrs']['_return_query_args'])) {
			return $this->query_args;
		}

		$GLOBALS['wcpt_query_args'] = $this->query_args;

		$products = new WP_Query($this->query_args);

		WC()->query->remove_ordering_args();

		// wcpt_console_log($products);

		return apply_filters('wcpt_products', $products);
	}

	public function get_query_args()
	{
		return $this->product_loop('nav_markup');
		return $query_args;
	}

	public function get_nav_markup()
	{
		return $this->product_loop('nav_markup');
	}

	public function get_content()
	{
		return $this->product_loop();
	}

	private function lazy_load()
	{
		// prep vars
		$data = wcpt_get_table_data();
		$tpl = plugin_dir_path(__FILE__) . 'templates/';

		// lazy load?
		if (
			!wp_doing_ajax() &&
			!empty($data['query']) &&
			!empty($data['query']['sc_attrs']) &&
			!empty($data['query']['sc_attrs']['lazy_load'])
		) {
			ob_start();
			include($tpl . 'lazy-load.php');
			return ob_get_clean();

		}

		return false;
	}

	private function ensure_device()
	{
		$table_data = wcpt_get_table_data();
		$table_id = $table_data['id'];

		if (
			!empty($_GET[$table_id . '_device']) &&
			in_array(
				$_GET[$table_id . '_device'],
				array('laptop', 'tablet', 'phone')
			)
		) {
			return;
		}

		// mobile detect library
		if (!class_exists('Mobile_Detect')) {
			require(WCPT_PLUGIN_PATH . 'vendor/Mobile_Detect.php');
		}
		$mobile_detect = new Mobile_Detect;

		// device
		$tablet_device = (method_exists($mobile_detect, 'isTablet') && $mobile_detect->isTablet());
		$phone_device = ($mobile_detect->isMobile() && !$tablet_device);
		$laptop_device = !$tablet_device && !$phone_device;

		// assign $_GET a device
		$requested_device = 'laptop';

		if ($tablet_device) {
			if (!wcpt_get_device_columns_2('tablet')) {
				$requested_device = 'laptop';
			}
		}

		if ($phone_device) {
			$requested_device = 'phone';

			if (!wcpt_get_device_columns_2('phone')) {
				$requested_device = 'tablet';

				if (!wcpt_get_device_columns_2('tablet')) {
					$requested_device = 'laptop';
				}
			}
		}

		$_GET[$table_id . '_device'] = $requested_device;
	}

	protected function product_loop($return = false)
	{

		// prep vars
		$this->id = $table_id = $this->attributes['id'];
		$data = wcpt_get_table_data();
		$tpl = plugin_dir_path(__FILE__) . 'templates/';
		$this->ensure_device();
		$GLOBALS['wcpt_row_rand'] = rand(0, 100000);

		// lazy load
		if ($lazy_load_mkp = $this->lazy_load()) {
			return $lazy_load_mkp; // ... done
		}

		ob_start(); // for entire shortcode content

		// initial query data from editor
		$GLOBALS['wcpt_user_filters'] = array();

		// -- add filter: out of stock
		$exclude_outofstock = false;
		if (!empty($data['query_v2'])) { // qv2
			if (!empty($data['query_v2']['stockStatus']) && count($data['query_v2']['stockStatus']) && !in_array('outofstock', $data['query_v2']['stockStatus'])) {
				$exclude_outofstock = true;
			}

		} else { // old query editor
			$exclude_outofstock = !empty($data['query']['hide_out_of_stock_items']) || (get_option('woocommerce_hide_out_of_stock_items', 'no') == 'yes');
		}

		$GLOBALS['wcpt_user_filters'][] = array(
			'filter' => 'availability',
			'operator' => $exclude_outofstock ? 'NOT IN' : 'ALSO',
		);

		// -- add filter: orderby
		$orderby = array(
			'filter' => 'orderby',
			'orderby' => !empty($data['query']['orderby']) ? $data['query']['orderby'] : 'date',
			'order' => !empty($data['query']['order']) ? $data['query']['order'] : 'DESC',
			'meta_key' => !empty($data['query']['meta_key']) && in_array($data['query']['orderby'], array('meta_value_num', 'meta_value')) ? $data['query']['meta_key'] : '',
		);

		if (!empty($data['query']['orderby'])) {

			$keys = array();

			// -- -- category
			if ($data['query']['orderby'] == 'category') {
				$keys = array_merge(
					$keys,
					array(
						'orderby_focus_category',
						'orderby_ignore_category',
					)
				);
			}

			// -- -- attribute
			if (in_array($data['query']['orderby'], array('attribute', 'attribute_num'))) {
				$keys = array_merge(
					$keys,
					array(
						'orderby_attribute',
						'orderby_focus_attribute_term',
						'orderby_ignore_attribute_term',
						'orderby_attribute_include_all',
					)
				);
			}

			// -- -- taxonomy
			if ($data['query']['orderby'] == 'taxonomy') {
				$keys = array_merge(
					$keys,
					array(
						'orderby_taxonomy',
						'orderby_focus_taxonomy_term',
						'orderby_ignore_taxonomy_term',
						'orderby_taxonomy_include_all',
					)
				);
			}

			foreach ($keys as $key) {
				if (!empty($data['query'][$key])) {
					$orderby[$key] = $data['query'][$key];
				}
			}

		}

		$GLOBALS['wcpt_user_filters'][] = $orderby;

		// pre-selected filters
		$sc_attrs = $data['query']['sc_attrs'];
		//-- category
		if (
			!empty($sc_attrs['category']) &&
			strtolower(trim($sc_attrs['category'])) !== '_all'
		) {
			$category = array();

			foreach (array_map('trim', explode(',', $sc_attrs['category'])) as $category_slug) {
				$tt_id = get_terms(
					array(
						'taxonomy' => 'product_cat',
						'fields' => 'tt_ids',
						'slug' => $category_slug,
						'hide_empty' => false,
					)
				);
				if ($tt_id) {
					$category[] = $tt_id[0];
				} else {
					$category[] = 0;
				}
			}

			// $category = get_terms(array(
			// 	'taxonomy' => 'product_cat',
			// 	'fields' => 'tt_ids',
			// 	'slug'=> explode( ',', $sc_attrs['category'] ),
			// 	'hide_empty' => false,
			// ));

			$filter_info = array(
				'filter' => 'category',
				'taxonomy' => 'product_cat',
				'values' => $category,
				'operator' => 'IN',
				'clear_label' => '',
				'clear_labels_2' => '',
			);

			wcpt_update_user_filters($filter_info, false);

		}

		//-- search - orderby relevance
		if (wcpt_maybe_apply_sortby_relevance()) {
			$filter_info = array(
				'filter' => 'orderby',
				'orderby' => 'relevance',
				'order' => 'DESC',
			);
			wcpt_update_user_filters($filter_info, true);
		}

		// operate on data before remaining user filters are applied
		do_action('wcpt_before_apply_user_filters', $data);

		// reset search count
		$GLOBALS['wcpt_search_count'] = 0;

		$GLOBALS['wcpt_nav_later'] = array(); // collects nav elm with placeholders to be processed afterwards
		add_filter('wcpt_navigation', array($this, 'nav_later'));

		if (
			!$this->only_loop &&
			!$this->disable_nav
		) {
			$nav = wcpt_parse_navigation();
		}

		// parse
		$this->query_args = $this->parse_query_args();

		// return query args, do not print
		if (!empty($sc_attrs['_return_query_args'])) {
			ob_end_clean();
			return json_encode($this->get_products());
		}

		// search
		if (is_search()) {
			$products = $GLOBALS['wp_query'];
			if (!$products->found_posts) {
				do_action('woocommerce_no_products_found');

				return;
			}
		}

		// inherit from main query
		if ($this->only_loop) {
			$products = $GLOBALS['wp_query'];

			// product variation query
		} else if (
			!empty($data['query']['sc_attrs']['product_variations']) &&
			function_exists('wcpt_product_variations_query')
		) {
			$products = wcpt_product_variations_query($this->query_args);

			// regular query
		} else {
			$products = $this->get_products();

		}

		$this->remove_ordering_args();

		remove_filter('posts_where', array($this, 'search'));

		$GLOBALS['wcpt_products'] = $products;

		// begin container 

		include($tpl . 'container-open.php');

		if (
			function_exists('wc_print_notices') &&
			WC()->session
		) {
			wc_print_notices();
		}

		wcpt_print_styles();

		do_action('wcpt_before_loop', $this->attributes);

		// print navigation
		if (!$this->only_loop) {
			echo apply_filters('wcpt_navigation', $nav);
		}

		if ($cache = $this->get_cache()) {
			echo $cache;

		} else {
			ob_start();

			foreach (array('laptop' => wcpt_get_device_columns_2('laptop'), 'tablet' => wcpt_get_device_columns_2('tablet'), 'phone' => wcpt_get_device_columns_2('phone'), ) as $device => $columns) {

				$columns = apply_filters('wcpt_device_columns', $columns, $device);

				// another device requested -- just return loader icon
				if (
					!empty($_GET[$table_id . '_device']) &&
					$_GET[$table_id . '_device'] != $device
				) {
					// "loading device view" screen
					include($tpl . 'scroll-wrap-outer-open.php');
					include($tpl . 'scroll-wrap-open.php');
					wcpt_icon('loader', 'wcpt-device-view-loading-icon');
					include($tpl . 'scroll-wrap-close.php');
					include($tpl . 'scroll-wrap-outer-close.php');

					continue;
				}

				if ($products->have_posts()) {

					do_action("woocommerce_shortcode_before_{$this->type}_loop", $this->attributes);

					include($tpl . 'scroll-wrap-outer-open.php');
					include($tpl . 'scroll-wrap-open.php');
					include($tpl . 'table-open.php');

					// column headings row
					include($tpl . 'heading-row.php');

					// product rows
					while ($products->have_posts()) {
						$products->the_post();

						$GLOBALS['wcpt_row_rand']++;

						// Set custom product visibility when querying hidden products.
						add_action('woocommerce_product_is_visible', array($this, 'set_product_as_visible'));

						global $product;
						$product = apply_filters('wcpt_product', $product);

						if (!empty($data['query']['sc_attrs']['enable_visibility_rules'])) {
							if (!$product->is_visible()) {
								continue;
							}
						}

						ob_start();

						include($tpl . 'row-open.php');
						do_action('wcpt_after_row_open');

						if (!empty($columns)) {
							foreach ($columns as $column_index => $column) {

								wcpt_parse_style_2($column['cell']);

								include($tpl . 'cell-open.php');

								ob_start();
								echo apply_filters(
									'wcpt_cell_value',
									trim(wcpt_parse_2($column['cell']['template'], $product)),
									$column_index,
									$column,
									$device
								);
								if ($cell_val = ob_get_clean()) {
									include($tpl . 'cell-value-open.php');
									echo $cell_val;
									include($tpl . 'cell-value-close.php');
								}

								include($tpl . 'cell-close.php');

							}
						}

						do_action('wcpt_before_row_close');
						include($tpl . 'row-close.php');

						$row_markup = apply_filters('wcpt_row', ob_get_clean());

						echo $row_markup;

						// Restore product visibility.
						remove_action('woocommerce_product_is_visible', array($this, 'set_product_as_visible'));
					}

					woocommerce_reset_loop();
					wp_reset_postdata();

					include($tpl . 'table-close.php');
					include($tpl . 'scroll-wrap-close.php');
					include($tpl . 'scroll-wrap-outer-close.php');

					// pagination
					if (
						!empty($this->attributes['paginate']) &&
						!$this->only_loop
					) {
						if (!empty($data['query']['sc_attrs'][$_GET[$table_id . '_device'] . '_infinite_scroll'])) {
							include($tpl . 'infinite-scroll-dots.php');

						} else {
							include($tpl . 'pagination.php');

						}
					}

					include($tpl . 'loading-screen.php');

				} else {

					ob_start();
					include $tpl . 'no-results.php';
					$no_results_markup = ob_get_clean();
					echo apply_filters('wcpt_no_results', $no_results_markup);

				}

			}

			wcpt_item_styles();

			do_action('wcpt_container_close');

			$markup = ob_get_clean();
			echo $markup;

			$this->set_cache($markup);

		}

		// update cart info
		if (wp_doing_ajax()) {
			?>
			<script type="text/javascript">
				if (typeof wcpt_update_cart_items !== 'undefined') {
					wcpt_update_cart_items(<?php echo json_encode(WC()->cart->get_cart()); ?>);
				}
			</script>
			<?php
		}

		// edit table link
		if (current_user_can('edit_others_wc_product_tables')) {
			?>
			<div class="wcpt-edit-wrapper">
				<a class="wcpt-edit" target="_blank" href="<?php echo get_edit_post_link($table_id); ?>">Edit table</a>
			</div>
			<?php
		}

		include($tpl . 'container-close.php');

		return ob_get_clean();

	}

	public function order_by_asc_popularity_post_clauses($args)
	{
		global $wpdb;
		$args['orderby'] = "$wpdb->postmeta.meta_value+0 ASC, $wpdb->posts.post_date DESC";
		return $args;
	}

	/**
	 * Remove ordering queries.
	 */
	public function remove_ordering_args()
	{
		remove_filter('posts_clauses', array($this, 'order_by_asc_popularity_post_clauses'));
		remove_filter('get_meta_sql', array($this, 'cast_decimal_precision'));
	}

	public function parse_query_args()
	{
		$query_args_essential = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows' => false,
			'orderby' => 'price',
			'order' => 'DESC',
		);

		$query_args = array_merge($query_args_essential, $this->query_args);

		if (empty($query_args['tax_query'])) {
			$query_args['tax_query'] = array();
		}

		// Visibility.
		if (empty($this->attributes['include_hidden'])) {
			// $this->set_visibility_query_args( $query_args );

			if (!is_array($query_args['tax_query'])) {
				$query_args['tax_query'] = array(
					'relation' => 'AND',
				);
			}

			$product_visibility_terms = wc_get_product_visibility_term_ids();

			if (
				!empty($this->attributes['_archive']) &&
				$this->attributes['_archive'] == 'search'
			) {
				$product_visibility_not_in = $product_visibility_terms['exclude-from-search'];
			} else {
				$product_visibility_not_in = $product_visibility_terms['exclude-from-catalog'];
			}

			if (!empty($product_visibility_not_in)) {
				$query_args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field' => 'term_taxonomy_id',
					'terms' => $product_visibility_not_in,
					'operator' => 'NOT IN',
				);
			}

		}

		// SKUs.
		$this->set_skus_query_args($query_args);

		// IDs.
		$this->set_ids_query_args($query_args);

		// Set specific types query args.
		$method = "set_{$this->type}_query_args";
		if (method_exists($this, $method)) {
			$this->{$method}($query_args);
		}

		// Attributes.
		$this->set_attributes_query_args($query_args);

		// Tags.
		$this->set_tags_query_args($query_args);

		// apply user filters
		$table_id = $this->attributes['id'];
		$data =& $GLOBALS['wcpt_table_data'];

		//-- flags
		$user_set_cats = false;

		//-- iterate user nav filters
		if (!empty($GLOBALS['wcpt_user_filters'])) {

			foreach ($GLOBALS['wcpt_user_filters'] as &$filter_info) {

				// results per page
				if ($filter_info['filter'] == 'results_per_page') {
					$this->attributes['limit'] = $filter_info['results'];
				}

				// category
				if (
					$filter_info['filter'] == 'category' &&
					!empty($filter_info['values'])
				) {
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field' => 'term_taxonomy_id',
						'terms' => $filter_info['values'],
						'operator' => $filter_info['operator'],
					);

					$user_set_cats = true;
				}

				// taxonomy
				if ($filter_info['filter'] == 'taxonomy') {
					$query_args['tax_query'][] = array(
						'taxonomy' => $filter_info['taxonomy'],
						'field' => 'term_taxonomy_id',
						'terms' => $filter_info['values'],
						'operator' => $filter_info['operator'],
					);
				}

				// attribute
				if ($filter_info['filter'] == 'attribute') {
					$query_args['tax_query'][] = array(
						'taxonomy' => $filter_info['taxonomy'],
						'field' => 'term_taxonomy_id',
						'terms' => $filter_info['values'],
						'operator' => !empty($filter_info['operator']) ? $filter_info['operator'] : 'IN',
					);
				}

				// rating
				if ($filter_info['filter'] == 'rating') {
					$query_args['meta_query'][] = array(
						'key' => '_wc_average_rating',
						'value' => array((int) $filter_info['values'][0], 5),
						'compare' => 'BETWEEN',
						'type' => 'NUMERIC',
					);
				}

				// hide out of stock items
				if (
					$filter_info['filter'] == 'availability' &&
					in_array(strtoupper($filter_info['operator']), array('NOT IN', 'IN')) // ignore ALSO
				) {
					$product_visibility_terms = wc_get_product_visibility_term_ids();

					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field' => 'term_taxonomy_id',
						'terms' => array($product_visibility_terms['outofstock']),
						'operator' => $filter_info['operator'],
					);
				}

				// custom field
				if ($filter_info['filter'] == 'custom_field') {

					if ($filter_info['compare'] == 'BETWEEN') {
						$arr = array(
							'key' => $filter_info['meta_key'],
							'value' => array($filter_info['min'], $filter_info['max']),
							'compare' => 'BETWEEN',
							'type' => $filter_info['type'],
						);

						// if( $arr['type'] == 'DECIMAL' ){
						// 	add_filter( 'get_meta_sql', array($this, 'cast_decimal_precision') );
						// }

						if (!$filter_info['max']) {
							$arr['compare'] = '>=';
							$arr['value'] = $filter_info['min'];
						}

						if (!$filter_info['min']) {
							$arr['compare'] = '<=';
							$arr['value'] = $filter_info['max'];
						}

						$query_args['meta_query'][] = $arr;

					} else if (in_array($filter_info['compare'], array('IN', 'NOT IN'))) {
						$query_args['meta_query'][] = array(
							'key' => $filter_info['meta_key'],
							'value' => $filter_info['values'],
							'compare' => $filter_info['compare'],
						);

					} else if ($filter_info['compare'] === 'LIKE') {
						$arr = [
							"relation" => "OR",
						];
						foreach ($filter_info['values'] as $val) {
							$arr[] = array(
								'key' => $filter_info['meta_key'],
								'value' => $val,
								'compare' => "LIKE",
							);
						}

						$query_args['meta_query'][] = $arr;


					} else if (in_array($filter_info['compare'], array('EXISTS', 'NOT EXISTS', ))) {
						$query_args['meta_query'][] = array(
							'key' => $filter_info['meta_key'],
							'compare' => $filter_info['compare'],
						);

					}

				}

				// orderby
				if ($filter_info['filter'] == 'orderby') {

					// order by a column
					if (!empty($_GET[$data['id'] . '_' . 'orderby']) && substr($_GET[$data['id'] . '_' . 'orderby'], 0, 7) == 'column_') {
						$col_index = substr($_GET[$data['id'] . '_' . 'orderby'], 7);
						$device = $_GET[$data['id'] . '_' . 'device'];
						if (!in_array($device, array('laptop', 'tablet', 'phone'))) {
							$device = 'laptop';
						}
						$order = strtolower($_GET[$data['id'] . '_' . 'order']);
						if (!in_array($order, array('asc', 'desc'))) {
							$order = 'asc';
						}

						if ($column_sorting = wcpt_get_column_sorting_info($col_index, $device)) {

							if ($column_sorting['orderby'] == 'price' && $order == 'desc') {
								$filter_info['orderby'] = 'price-desc';

							} else {
								$filter_info['orderby'] = $column_sorting['orderby'];

							}

							$filter_info['order'] = $order;
							$filter_info['meta_key'] = $column_sorting['meta_key'];

							if (in_array($column_sorting['orderby'], array('attribute', 'attribute_num'))) {
								$filter_info['orderby_attribute'] = !empty($column_sorting['orderby_attribute']) ? $column_sorting['orderby_attribute'] : false;
								$filter_info['orderby_focus_attribute_term'] = !empty($column_sorting['orderby_focus_attribute_term']) ? $column_sorting['orderby_focus_attribute_term'] : false;
								$filter_info['orderby_ignore_attribute_term'] = !empty($column_sorting['orderby_ignore_attribute_term']) ? $column_sorting['orderby_ignore_attribute_term'] : false;
								$filter_info['orderby_attribute_include_all'] = !empty($column_sorting['orderby_attribute_include_all']);
							}

							if ($column_sorting['orderby'] == 'taxonomy') {
								$filter_info['orderby_taxonomy'] = !empty($column_sorting['orderby_taxonomy']) ? $column_sorting['orderby_taxonomy'] : false;
								$filter_info['orderby_focus_taxonomy_term'] = !empty($column_sorting['orderby_focus_taxonomy_term']) ? $column_sorting['orderby_focus_taxonomy_term'] : false;
								$filter_info['orderby_ignore_taxonomy_term'] = !empty($column_sorting['orderby_ignore_taxonomy_term']) ? $column_sorting['orderby_ignore_taxonomy_term'] : false;
								$filter_info['orderby_taxonomy_include_all'] = !empty($column_sorting['orderby_taxonomy_include_all']);
							}

						}

					}

					if ($filter_info['orderby'] == 'sku') {
						$filter_info['orderby'] = 'meta_value';
						$filter_info['meta_key'] = '_sku';

					} else if ($filter_info['orderby'] == 'sku_num') {
						$filter_info['orderby'] = 'meta_value_num';
						$filter_info['meta_key'] = '_sku';

					}

					$query_args['orderby'] = $filter_info['orderby'];
					$query_args['order'] = $filter_info['order'];

					if (!empty($filter_info['meta_key'])) {
						$query_args['meta_key'] = $filter_info['meta_key'];
					}

					if ($filter_info['orderby'] == 'relevance') {
						$query_args['orderby'] = 'post__in';
					}

				}

				// price range
				if (
					$filter_info['filter'] == 'price_range' &&
					(
						!empty($filter_info['min_price']) ||
						!empty($filter_info['max_price'])
					)
				) {

					if (
						isset($filter_info['min_price']) &&
						!$filter_info['min_price']
					) {
						unset($filter_info['min_price']);
					}

					if (
						isset($filter_info['max_price']) &&
						!$filter_info['max_price']
					) {
						unset($filter_info['max_price']);
					}

					$current_min_price = isset($filter_info['min_price']) ? floatval($filter_info['min_price']) : 0;
					$current_max_price = isset($filter_info['max_price']) ? floatval($filter_info['max_price']) : PHP_INT_MAX;

					$meta_query = apply_filters(
						'woocommerce_get_min_max_price_meta_query',
						array(
							'price_filter' => true,
							'key' => '_price',
							'value' => array($current_min_price, $current_max_price),
							'compare' => 'BETWEEN',
							'type' => 'DECIMAL(10,' . wc_get_price_decimals() . ')',
						),
						$filter_info
					);

					$meta_query['price_filter'] = true;
					$query_args['meta_query']['price_filter'] = $meta_query;

				}

				// on sale
				if ($filter_info['filter'] == 'on_sale') {
					$query_args['post__in'] = wc_get_product_ids_on_sale();

				}

				// search
				if ($filter_info['filter'] == 'search') {
					foreach ($filter_info['searches'] as $search) {
						wcpt_search($search, $query_args['post__in']);
					}
				}

				// date picker
				if ($filter_info['filter'] == 'date_picker') {

					// date query
					if ($filter_info['date_query']) {
						$_date_query = array();

						if ($filter_info['values'][0]) {
							$_date_query['after'] = $filter_info['values'][0] . ' 00:00:00';
						}

						if ($filter_info['values'][1]) {
							$_date_query['before'] = $filter_info['values'][1] . ' 23:59:59';
						}

						$query_args['date_query'][] = $_date_query;

						$query_args['date_query']['inclusive'] = true;

						// meta query
					} else {

						$_meta_query = array(
							'key' => $filter_info['meta_key'],
							'type' => strtoupper($filter_info['type']),
						);

						// values
						$start_date = $filter_info['values'][0] ? $filter_info['values'][0] . ' 00:00:00' : false;
						$end_date = $filter_info['values'][1] ? $filter_info['values'][1] . ' 23:59:59' : false;

						// maybe convert to timestamp
						if ($filter_info['type'] == 'numeric') {
							// start date timestamp
							if (!empty($start_date)) {
								$_datetime = new DateTime($start_date);
								$start_date = $_datetime->getTimestamp();
							}

							// end date timestamp
							if (!empty($end_date)) {
								$_datetime = new DateTime($end_date);
								$end_date = $_datetime->getTimestamp();
							}
						}


						// comparison
						// -- both dates provided
						if (
							!empty($start_date) &&
							!empty($end_date)
						) {
							$_meta_query['value'] = array($start_date, $end_date);
							$_meta_query['compare'] = 'BETWEEN';

							// -- only start date provided						
						} else if (!empty($start_date)) {
							$_meta_query['value'] = $start_date;
							$_meta_query['compare'] = '>=';

							// -- only end date provided						
						} else if (!empty($end_date)) {
							$_meta_query['value'] = $end_date;
							$_meta_query['compare'] = '<=';

						}

						$query_args['meta_query'][] = $_meta_query;

					}
				}

			}
		}

		// orderby
		// if( 
		//   ! empty( $_GET[ $table_id . '_orderby' ] ) &&
		//   $_GET[ $table_id . '_orderby' ] === 'relevance'
		// ){
		//   $query_args['orderby'] = 'post__in';
		// }

		// Categories are essential for the query.
		// Esure they are there regardless of whether user set them or not

		// if( ! $user_set_cats ){
		// 	$terms = array();
		// 	if( empty( $data['query']['category'] ) ){
		// 		$terms = get_terms(
		// 			array(
		// 				'taxonomy' => 'product_cat',
		// 				'fields' => 'tt_ids',
		// 				'hide_empty' => false,
		// 			)
		// 		);
		// 	}else{
		// 		$terms = explode( ',', $data['query']['category'] );
		// 	}

		// 	$query_args['tax_query'][] = array(
		// 		'taxonomy' 	=> 'product_cat',
		// 		'field'    	=> 'term_taxonomy_id',
		// 		'terms'			=> $terms,
		// 	);
		// }

		// ensure category is applied 
		if (
			!$user_set_cats &&
			!empty($this->attributes['category'])
		) {
			$terms = explode(',', $this->attributes['category']);

			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field' => 'term_taxonomy_id',
				'terms' => $terms,
			);
		}

		// force excludes regardless of what user chose
		// if( ! empty( $data['query']['sc_attrs']['exclude_category'] ) ){
		// 	$query_args['tax_query'][] = array(
		// 		'taxonomy' 	=> 'product_cat',
		// 		'field'    	=> 'name',
		// 		'terms'			=> explode( ',', $data['query']['sc_attrs']['exclude_category'] ),
		// 		'operator' => 'NOT IN',
		// 	);
		// }

		if ($query_args['orderby'] == 'price-desc') {
			$query_args['orderby'] = 'price';
			$query_args['order'] = 'DESC';

		} else if ($query_args['orderby'] == 'price') {
			$query_args['orderby'] = 'price';
			$query_args['order'] = 'ASC';

		} else if ($query_args['orderby'] == 'rating') {
			$query_args['order'] = 'DESC';

		}

		$ordering_args = WC()->query->get_catalog_ordering_args($query_args['orderby'], $query_args['order']);
		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order'] = $ordering_args['order'];

		if ($ordering_args['meta_key']) {
			$query_args['meta_key'] = $ordering_args['meta_key'];
		}

		$query_args['posts_per_page'] = intval($this->attributes['limit']);
		if (1 < $this->attributes['page']) {
			$query_args['paged'] = absint($this->attributes['page']);
		}

		if (!empty($this->attributes['offset'])) {
			$query_args['offset'] = $this->attributes['offset'];
		}

		// apply pagination
		if (
			!empty($_REQUEST[$table_id . '_paged']) &&
			!empty($data['query']['paginate'])
		) {
			$query_args['paged'] = (int) $_REQUEST[$table_id . '_paged'];
		}

		// parse additional query args string
		if (!empty($this->attributes['additional_query_args'])) {
			$query_args = wp_parse_args($this->attributes['additional_query_args'], $query_args);
		}

		if (empty($query_args['meta_query'])) { // fix WOOF error
			$query_args['meta_query'] = array();
		}

		$query_args = apply_filters('woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type);

		// Always query only IDs.
		// $query_args['fields'] = 'ids';

		return $query_args;
	}

	protected function parse_attributes($attributes)
	{
		$attributes = apply_filters('wcpt_before_parse_attributes', $attributes);

		$table_data = $GLOBALS['wcpt_table_data'];

		if (empty($GLOBALS['wcpt_table_data']['query']['sc_attrs'])) {
			$GLOBALS['wcpt_table_data']['query']['sc_attrs'] = array();
		}

		$GLOBALS['wcpt_table_data']['query']['sc_attrs'] = array_merge($GLOBALS['wcpt_table_data']['query']['sc_attrs'], $attributes);

		// don't want woocommerce shortcode to process these its own way
		foreach (array('attribute', 'custom_field') as $key) {
			if (!empty($attributes[$key])) {
				unset($attributes[$key]);
			}
		}

		foreach (array('name', 'id') as $key) {
			if (!empty($GLOBALS['wcpt_table_data']['query']['sc_attrs'][$key])) {
				unset($GLOBALS['wcpt_table_data']['query']['sc_attrs'][$key]);
			}
		}

		if (!defined('WCPT_PRO')) {
			foreach ($GLOBALS['wcpt_table_data']['query']['sc_attrs'] as $key => $val) {
				if (stristr($key, 'freeze')) {
					unset($GLOBALS['wcpt_table_data']['query']['sc_attrs'][$key]);
				}
			}
		}

		$attributes = $this->parse_legacy_attributes($attributes);

		$attributes = shortcode_atts(
			array(
				'limit' => '-1',      // Results limit.
				'columns' => '3',       // Number of columns.
				'rows' => '',        // Number of rows. If defined, limit will be ignored.
				'orderby' => '',   		 // menu_order, title, date, rand, price, popularity, rating, or id.
				'order' => '',     	 // ASC or DESC.
				'meta_key' => '',     	 // meta key to order by
				'ids' => '',        // Comma separated IDs.
				'skus' => '',        // Comma separated SKUs.
				'category' => '',        // Comma separated category slugs.
				'nav_category' => '',
				'nav_category_id' => '',
				'cat_operator' => 'IN',      // Operator to compare categories. Possible values are 'IN', 'NOT IN', 'AND'.
				'attribute' => '',        // Single attribute slug.
				'terms' => '',        // Comma separated term slugs.
				'terms_operator' => 'IN',      // Operator to compare terms. Possible values are 'IN', 'NOT IN', 'AND'.
				'tag' => '',        // Comma separated tag slugs.
				'tag_operator' => 'IN',      // Comma separated tag slugs.
				'class' => '',        		 // HTML class.
				'html_class' => '',        // HTML class.
				'page' => 1,         // Page for pagination.
				'paginate' => false,     // Should results be paginated.
				'cache' => false,      // Should shortcode output be cached.

				'exclude_category' => '',       // Comma separated category slugs.
				'include_hidden' => false,    // Hidden from shop / search.
				'offset' => 0,      	 // Post offset.
				'id' => 0,				 // Table id.
				'_archive' => false,
				'_only_loop' => false,
				'_disable_nav' => false,
			),
			$attributes,
			$this->type
		);

		// only render loop based on gloal query
		// hide navigation and pagination
		if (
			$attributes['_only_loop'] &&
			in_array(trim(strtolower($attributes['_only_loop'])), array('true', '1', 'yes'))
		) {
			$this->only_loop = true;
		}

		if ($attributes['_disable_nav']) {
			$this->disable_navigation = true;
		}

		// cache
		$this->caching = !!$attributes['cache'];

		$query =& $GLOBALS['wcpt_table_data']['query'];

		// category
		if ($attributes['category'] == '_all') {
			$query['category'] = array();

		}

		// nav_category - category names
		if (!empty($attributes['nav_category'])) {
			// modify the original set of categories
			$query['category'] = get_terms(
				array(
					'taxonomy' => 'product_cat',
					'fields' => 'tt_ids',
					'slug' => array_map('trim', explode(',', $attributes['nav_category'])),
					'hide_empty' => false,
				)
			);
		}

		// tags
		// if (!empty($GLOBALS['wcpt_table_data']['query']['sc_attrs']['tags'])) {
		// 	$attributes['tag'] = $GLOBALS['wcpt_table_data']['query']['sc_attrs']['tags'];
		// }

		// empty query category for archive
		if (!empty($attributes['_archive'])) {
			$query['category'] = array();
		}

		// nav_category_id - category term taxonomy ids
		if (!empty($attributes['nav_category_id'])) {
			// modify the original set of categories
			$query['category'] = array_map('trim', explode(',', $attributes['nav_category_id']));
		}

		// ids
		if (!empty($attributes['ids'])) {
			$query['ids'] = $attributes['ids'];
		}

		// set_ids_query_args requires ","		
		if (!empty($query['ids']) && is_array($query['ids'])) {
			$query['ids'] = implode(',', $query['ids']);
		}

		// skus
		if (!empty($attributes['skus'])) {
			$query['skus'] = $attributes['skus'];
		}

		// orderby
		if (!empty($attributes['orderby'])) {
			$orderby = trim(strtolower($attributes['orderby']));

			if ($orderby == 'custom_field') {
				$orderby = 'meta_value';
			} else if ($orderby == 'custom_field_num') {
				$orderby = 'meta_value_num';
			}

			$lite_arr = array(
				'title',
				'date',
				'menu_order',
				'rating',
				'price',
				'price-desc',
				'popularity',
				'rand',
			);

			$pro_arr = array(
				'ids',
				'skus',
				'title',
				'date',
				'menu_order',
				'rating',
				'price',
				'price-desc',
				'popularity',
				'rand',
				'meta_value_num',
				'meta_value',
				'id',
				'sku',
				'sku_num',
				'post_content',
			);

			$arr = defined('WCPT_PRO') ? $pro_arr : $lite_arr;

			if (in_array($orderby, $arr)) {
				$query['orderby'] = $attributes['orderby'];
			}
		}

		// order
		if (!empty($attributes['order'])) {
			$order = trim(strtoupper($attributes['order']));
			if ($order == 'ASCENDING') {
				$order = 'ASC';
			} else if ($order == 'DESCENDING') {
				$order = 'DESC';
			}

			if (
				in_array(
					$order,
					array(
						'ASC',
						'DESC',
					)
				)
			) {
				$query['order'] = $order;
			}
		}

		// meta key
		if (defined('WCPT_PRO')) {
			if (!empty($attributes['custom_field'])) {
				$attributes['meta_key'] = trim($attributes['custom_field']);
			}

			if (!empty($attributes['meta_key'])) {
				$attributes['meta_key'] = trim($attributes['meta_key']);
			}
		}

		// skus
		if (!empty($attributes['skus'])) {
			$query['skus'] = $attributes['skus'];
		}

		// limit
		if (!empty($query['sc_attrs']['limit'])) {
			$query['limit'] = (int) $GLOBALS['wcpt_table_data']['query']['sc_attrs']['limit'];
		}

		if (!empty($query['category']) && is_array($query['category'])) {
			$query['category'] = array_map('intval', array_unique($query['category']));
			// cats need to be comma separated string
			$query['category'] = implode(',', $query['category']);
		}

		if (!empty($query['original_category']) && is_array($query['original_category'])) {
			$query['original_category'] = array_map('intval', array_unique($query['original_category']));
			// cats need to be comma separated string
			$query['original_category'] = implode(',', $query['original_category']);
		}

		// paginate
		if (gettype($attributes['paginate']) == 'string') {
			if (
				in_array(
					strtolower($attributes['paginate']),
					array('no', 'false', 'disable', 'disabled')
				)
			) {
				$query['paginate'] = false;
			} else if (
				in_array(
					strtolower($attributes['paginate']),
					array('yes', 'true', 'enable', 'enabled')
				)
			) {
				$query['paginate'] = true;
			}
		}

		// offset
		if (isset($GLOBALS['wcpt_table_data']['query']['sc_attrs']['offset'])) {
			$attributes['offset'] = (int) $attributes['offset'];
			$attributes['paginate'] = false;
		}

		// merge
		return apply_filters('wcpt_parse_attributes', array_merge($attributes, $query));
	}

	public function nav_later($nav)
	{
		$GLOBALS['wcpt_nav_later_flag'] = true;
		foreach ($GLOBALS['wcpt_nav_later'] as $elm) {
			extract($elm);
			if (!isset($product)) {
				$product = false;
			}
			$markup = wcpt_parse_ctx_2($element, $elm_tpl, $elm_type, $product);
			$nav = str_replace($placeholder, $markup, $nav);
		}
		$GLOBALS['wcpt_nav_later_flag'] = false;

		return $nav;
	}

	public function cast_decimal_precision($array)
	{
		$array['where'] = str_replace('DECIMAL', 'DECIMAL(10,3)', $array['where']);
		return $array;
	}
}