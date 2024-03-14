<?php

	namespace TABS_RES_PLUGINS\Extension\WooCommerce;

	/**
	 * Description of Tabs Rest API
	 *
	 * @author $biplob018
	 */
	class Build_Api
	{

		/**
		 * Define $wpdb
		 *
		 * @since 3.3.0
		 */
		public $database;
		public $request;
		public $rawdata;
		public $styleid;
		public $childid;
		public $customize;

		/**
		 * Constructor of plugin class
		 *
		 * @since 3.3.0
		 */
		public function __construct ()
		{

			$this->build_api();
			$this->get_admin();
		}

		// instance container
		private static $instance = null;

		public static function instance ()
		{
			if (self::$instance == null) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function fixed_data ($agr)
		{
			return hex2bin($agr);
		}

		public function build_api ()
		{
			add_action('rest_api_init', function () {
				register_rest_route(untrailingslashit('responsivewootabsultimate/v1/'), '/(?P<action>\w+)/', [
				  'methods' => ['GET', 'POST'],
				  'callback' => [$this, 'api_action'],
				  'permission_callback' => [$this, 'get_permissions_check'],
				]);
			});
		}

		public function get_permissions_check ($request)
		{
			$transient = get_transient('responsive_vc_tabs_permission_role');
			if (false === $transient) {
				$user_role = get_option('responsive_vc_tabs_permission');
				$role_object = get_role($user_role);
				$first_key = '';
				if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
					reset($role_object->capabilities);
					$first_key = key($role_object->capabilities);
				} else {
					$first_key = 'manage_options';
				}
				$transient = 'responsive_vc_tabs_permission_role';
				set_transient($transient, $first_key, 2 * HOUR_IN_SECONDS);
				return current_user_can($first_key);
			}
			return current_user_can($transient);
		}

		public function api_action ($request)
		{
			$this->request = $request;

			$wpnonce = $request['_wpnonce'];
			if (!wp_verify_nonce($wpnonce, 'wp_rest')) :
				return new \WP_REST_Request('Invalid URL', 422);
			endif;

			$action_class = strtolower($request->get_method()) . '_' . sanitize_key($request['action']);
			if (method_exists($this, $action_class)) :
				return $this->{$action_class}();

			endif;
			return 'Silence is Golden';
		}

		public function array_replace ($arr = [], $search = '', $replace = '')
		{
			array_walk($arr, function (&$v) use ($search, $replace) {
				$v = str_replace($search, $replace, $v);
			});
			return $arr;
		}

		/**
		 * Generate safe path
		 * @since v1.0.0
		 */
		public function safe_path ($path)
		{

			$path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
			return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
		}

		public function get_woo_product_name ()
		{


			$query_args = [
			  'post_type' => ['product'],
			  'post_status' => 'publish',
			  'order_by' => 'date',
			  'order' => 'DSC',
			  'posts_per_page' => 15,
			  'post_type' => 'any'
			];
			if (isset($this->request['ids'])) {
				$ids = explode(',', $this->request['ids']);
				$query_args['post__in'] = $ids;
			}
			if (isset($this->request['qu'])) {
				$query_args['s'] = $this->request['qu'];
			}

			$query = new \WP_Query($query_args);
			$options = [];
			if ($query->have_posts()) :
				while ($query->have_posts()) {
					$query->the_post();
					$options[] = ['id' => get_the_ID(), 'text' => get_the_title()];
				}
			endif;

			return ['results' => $options];
			wp_reset_postdata();
		}

		public function get_woo_author_name ()
		{

			if (isset($this->request['ids'])) {

				$ids = explode(',', $this->request['ids']);
				$arg = ['include' => $ids];
				$users = get_users($arg);
			} else {
				$users = get_users();
			}


			$options = [];
			foreach ($users as $user) {
				$options[] = ['id' => $user->ID, 'text' => $user->display_name];
			}
			return ['results' => $options];
		}

		public function get_woo_cat_name ()
		{

			$taxonomy = "product_cat";
			$query_args = [
			  'orderby' => 'slug',
			  'hide_empty' => false
			];

			if (isset($this->request['ids'])) {
				$ids = explode(',', $this->request['ids']);
				$query_args['include'] = $ids;
			}

			$terms = get_terms(
			  $taxonomy,
			  $query_args,
			);

			$options = [];
			foreach ($terms as $trm) {
				$options[] = [
				  'id' => $trm->term_id,
				  'text' => $trm->name
				];
			};
			return ['results' => $options];
		}

		public function get_woo_tag_name ()
		{

			$taxonomy = "product_tag";
			$query_args = [
			  'orderby' => 'slug',
			  'hide_empty' => false
			];
			if (isset($this->request['ids'])) {
				$ids = explode(',', $this->request['ids']);
				$query_args['include'] = $ids;
			}
			$terms = get_terms(
			  $taxonomy,
			  $query_args,
			);

			$options = [];
			foreach ($terms as $trm) {
				$options[] = [
				  'id' => $trm->term_id,
				  'text' => $trm->name
				];
			};
			return ['results' => $options];
		}

		public function get_tabsupdate ()
		{

			$id = $this->request['id'];

			$title = $this->request['title'];
			$priority = $this->request['priority'];
			$activation = isset($this->request['activation']) ? $this->request['activation'] : '';
			$condition = $this->request['condition'];

			$singular_id = $condition == 'singular' ? $this->request['singular_id'] : '';
			$archive = $condition == 'archive' ? $this->request['archive'] : '';
			$products_author = $products_cat = $products_tags = '';

			if ($condition == 'archive' && $archive == 'products_cat'):
				$products_cat = $this->request['archive_cat_id'];
			elseif ($condition == 'archive' && $archive == 'products_tags'):
				$products_tags = $this->request['archive_tags_id'];
			endif;

			$post_data = [
			  'post_title' => $title,
			  'post_status' => 'publish',
			  'post_type' => 'responsive_woo_tabs',
			];

			$post = get_post($id);

			if ($post == null) {
				$post_data['post_author'] = $this->request['post_author'];
				$id = wp_insert_post($post_data);
			} else {
				$post_data['ID'] = $id;
				wp_update_post($post_data);
			}

			update_post_meta($id, 'responsive_woo_tabs_activation', $activation);
			update_post_meta($id, 'responsive_woo_tabs_priority', $priority);
			update_post_meta($id, 'responsive_woo_tabs_condition', $condition);
			update_post_meta($id, 'responsive_woo_tabs_singular_id', $singular_id);
			update_post_meta($id, 'responsive_woo_tabs_archive', $archive);
			update_post_meta($id, 'responsive_woo_tabs_products_cat', $products_cat);
			update_post_meta($id, 'responsive_woo_tabs_products_tags', $products_tags);

			$url = get_admin_url() . '/post.php?post=' . $id . '&action=edit';

			return $url;
			exit;
		}

		public function get_woo_tabs_single_data ()
		{


			$id = $this->request['id'];

			$post = get_post($id);
			if ($post != null) {
				return [
				  'title' => $post->post_title,
				  'status' => $post->post_status,
				  'activation' => get_post_meta($post->ID, 'responsive_woo_tabs_activation', true),
				  'priority' => get_post_meta($post->ID, 'responsive_woo_tabs_priority', true),
				  'condition' => get_post_meta($post->ID, 'responsive_woo_tabs_condition', true),
				  'singular_id' => get_post_meta($post->ID, 'responsive_woo_tabs_singular_id', true),
				  'archive' => get_post_meta($post->ID, 'responsive_woo_tabs_archive', true),
				  'products_cat' => get_post_meta($post->ID, 'responsive_woo_tabs_products_cat', true),
				  'products_tags' => get_post_meta($post->ID, 'responsive_woo_tabs_products_tags', true),
				];
			}
			return true;
		}

	}
