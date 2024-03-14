<?php
namespace Wpmet\Gutenova;
defined('ABSPATH') || exit;

class Helper{

	  public $block_path_base;
	  public $block_path;
	  public $block_url;
	  public $block_name;
	  public $block_key;
	  public $block_root_path;
	  public $block_root_url;
	  public $is_editor;

    public function __construct($block_dir_base, $block_name, $block_key, $block_root_path, $block_root_url){
        $this->block_path_base = $block_dir_base;
        $this->block_path = $block_root_path . $block_dir_base;
        $this->block_url = $block_root_url . $block_dir_base;
        $this->block_name = $block_name;
        $this->block_key = $block_key;
        $this->block_root_path = $block_root_path;
        $this->block_root_url = $block_root_url;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- It's handled from wordpress
        $this->is_editor = (isset($_GET['is-editor']) && sanitize_text_field(wp_unslash($_GET['is-editor'])) == '1');
    }

	public static function add_product_in_cart_if_no_cart_found() {

		if(empty(WC()->cart->cart_contents)) {

			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
			WC()->customer = new \WC_Customer(get_current_user_id(), true);
			WC()->cart     = new \WC_Cart();

			if(WC()->cart->is_empty()) {
				$demo_products = get_posts(
					[
						'post_type'   => 'product',
						'numberposts' => 1,
						'post_status' => 'publish',
						'fields'      => 'ids',
						'orderby'     => 'ID',
						'order'       => 'DESC',
						'tax_query'   => [
							[
								'taxonomy' => 'product_type',
								'field'    => 'slug',
								'terms'    => 'simple',
							],
						],
						'meta_query'  => [
							'relation' => 'AND',
							[
								'key'     => '_sale_price',
								'value'   => 0,
								'compare' => '>',
								'type'    => 'numeric',
							],
							[
								'key'   => '_stock_status',
								'value' => 'instock',
							],
						],
					]
				);

				if(!empty($demo_products)) {
					foreach($demo_products as $id) {
						WC()->cart->add_to_cart($id);
					}
				}
			}
		}
	}

}
