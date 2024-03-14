<?php

namespace Shop_Ready\extension\templates\hooks\product;

use Shop_Ready\base\Template_Redirect as Shop_Ready_Template;

/*
* WooCommerece Single Product
*
*/

class Single extends Shop_Ready_Template
{


	public function register()
	{

		$this->set_name('single');

		add_filter('body_class', array($this, 'set_body_class'));
		add_filter('wp_head', array($this, 'push_data'));
		add_filter('wc_get_template_part', array($this, 'get_template'), 120, 3);

		add_action($this->get_action_hook(), array($this, 'dynamic_template'), 10);
		add_action('shop_ready_single_product_notification', array($this, 'single_product_notification'), 100);
		add_action('init', array($this, 'single_product_compatible'), 10, 1);
		add_action('woocommerce_product_thumbnails', array($this, 'woocommerce_product_thumbnails'), 10, 1);
	}
	public function woocommerce_product_thumbnails()
	{
		global $product;
		if (shop_ready_is_elementor_mode()) {
			wc_get_template('single-product/product-thumbnails.php');
		}
	}
	public function single_product_compatible()
	{

		if (isset($_GET['sr_tpl']) && isset($_GET['tpl_type'])) {

			if (is_numeric(shop_ready_get_single_product_key())) {
				$product = wc_get_product(shop_ready_get_single_product_key());
				// $product->set_gallery_image_ids([10982]);
				$GLOBALS['product'] = $product;

				setup_postdata(get_post(shop_ready_get_single_product_key()));
			}
		}
	}
	/**
	 * | is_renderable_template |
	 *
	 * @param  [string] $template
	 * @param  [string] $slug
	 * @param  [string] $name
	 * @return boolean | int
	 */
	public function is_renderable_template($template, $slug, $name)
	{

		if (is_product()) {
			return $name === 'single-product' && $slug === 'content';
		}

		return false;
	}

	/**
	 * | Default Notification |
	 * | Customize style from editor site settings |
	 *
	 * @since 1.0
	 * @return void
	 */
	public function single_product_notification()
	{

		echo wp_kses_post('<div class="elementor-section elementor-section-boxed">');

		echo wp_kses_post('<div class="elementor-container elementor-column-gap-default ">');
		echo wp_kses_post('<div class="woocommerce-product-page-notice-wrapper width:100% ">');
		wc_print_notices();
		echo wp_kses_post('</div>');
		echo wp_kses_post('</div>');

		echo wp_kses_post('</div>');
	}



	/**
	 * | set_body_class |
	 *
	 * @author     <quomodosoft.com>
	 * @since   File available since Release 1.0
	 * @param  [string] $classes
	 * @return array | []
	 */
	public function set_body_class($classes)
	{

		if (is_product()) {

			return array_merge($classes, array('shopready-elementor-addon', 'woo-ready-' . $this->name));
		}

		return $classes;
	}

	public function push_data()
	{

		if (!is_product()) {
			return;
		}

?>

<script type="text/javascript">
var wready_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
var wready_product_id = '<?php echo esc_html(get_the_id()); ?>';
</script>

<?php
	}
}