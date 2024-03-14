<?php

/**
 * @method bool|mixed|void get_option($option, $default = false)
 * @method bool update_option($option, $value, $autoload = null)
 * @method WP_Post|array|null get_post($post = null, $output = OBJECT, $filter = 'raw')
 * @method int|WP_Error wp_insert_post(array $postarr, bool $wp_error = false, bool $fire_after_hooks = true)
 * @method int|WP_Error wp_update_post(array|object $postarr = array(), bool $wp_error = false, bool $fire_after_hooks = true)
 * @method array|bool|false|WP_Post|null wp_trash_post($post_id = 0)
 * @method array|int|string get_post_field($field, $post = null, $context = 'display')
 * @method mixed get_post_meta($post_id, $key = '', $single = false)
 * @method bool|int update_post_meta($post_id, $meta_key, $meta_value, $prev_value = '')
 * @method array|false|WP_Error wp_set_post_terms(int $post_id = 0, string|array $tags = '', string $taxonomy = 'post_tag', bool $append = false)
 * @method bool|true|void add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
 * @method bool|true|void add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
 * @method mixed|void apply_filters($tag, $value, ...$args)
 * @method bool is_writable($filename)
 * @method wp_send_json($response, $status_code = null)
 * @method bool|int version_compare($version1, $version2, $operator = null)
 * @method bool is_checkout()
 * @method bool is_product()
 * @method bool|false|string wp_get_attachment_image_url($attachment_id, $size = 'thumbnail', $icon = false)
 * @method array wc_get_product_attachment_props($attachment_id = null, $product = false)
 * @method bool wc_post_content_has_shortcode($tag = '')
 * @method wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
 * @method bool taxonomy_exists(string $taxonomy)
 * @method WP_Taxonomy|WP_Error register_taxonomy(string $taxonomy, array|string $object_type, array|string $args = array())
 * @method string sanitize_title(string $title, string $fallback_title = '', string $context = 'save')
 * @method mixed term_exists(int|string $term, string $taxonomy = '', int $parent = null)
 * @method array|WP_Error wp_insert_term(string $term, string $taxonomy, array|string $args = array())
 * @method WP_Term|array|false get_term_by(string $field, string|int $value, string $taxonomy = '', string $output = OBJECT, string $filter = 'raw')
 * @method array|WP_Error wp_get_post_terms(int $post_id = 0, string|string[] $taxonomy = 'post_tag', array $args = array())
 * @method array|WP_Error wp_set_object_terms(int $object_id, string|int|array $terms, string $taxonomy, bool $append = false)
 * @method bool set_transient(string $transient, mixed $value, int $expiration = 0)
 * @method object|false get_category_by_slug(string $slug)
 *
 * @method string get_woocommerce_currency()
 * @method int wc_get_product_id_by_sku($sku)
 * @method bool|false|WC_Product|null wc_get_product($the_product = false, $deprecated = array())
 * @method bool|int|null wc_update_product_stock($product, $stock_quantity = null, $operation = 'set', $updating = false)
 * @method string wc_get_product_category_list($product_id, $sep = ', ', $before = '', $after = '')
 * @method string wc_price($price, $args = array())
 * @method wc_clear_notices()
 * @method wc_add_notice($message, $notice_type = 'success', $data = array())
 * @method array|stdClass wc_get_products(array $args)
 * @method string wc_attribute_label($name, $product = '')
 * @method array wc_get_attribute_taxonomies()
 * @method string wc_attribute_taxonomy_name(string $attribute_name)
 * @method string wc_sanitize_taxonomy_name(string $taxonomy)
 */
class WC_Payever_WP_Wrapper {

	/**
	 * @param string $method
	 * @param array $args
	 * @return false|mixed|null
	 */
	public function __call( $method, $args ) {
		return function_exists( $method ) ? call_user_func_array( $method, $args ) : null;
	}
}
