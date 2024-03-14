<?php

	namespace TABS_RES_PLUGINS\Extension\WooCommerce;

	use TABS_RES_PLUGINS\Extension\WooCommerce\Build_Api;

	/**
	 * Description of WooCommerce
	 *
	 * @author biplo
	 */
	class WooCommerce extends Build_Api
	{

		use \TABS_RES_PLUGINS\Extension\WooCommerce\Inc\Admin\Editor;
		use \TABS_RES_PLUGINS\Extension\WooCommerce\Inc\MetaData;
		use \TABS_RES_PLUGINS\Extension\WooCommerce\Product\Meta;
		use \TABS_RES_PLUGINS\Extension\WooCommerce\Product\Data;

		public function get_admin ()
		{

			/*
			 * Global Tab content Panels
			 */


			add_action('admin_footer', [$this, 'Editor']);
			add_filter('manage_responsive_woo_tabs_posts_columns', [$this, 'column']);
			add_action('manage_responsive_woo_tabs_posts_custom_column', [$this, 'column_data'], 10, 2);

			add_action('save_post', [$this, 'save_icon_meta_box_content']);
			add_action('save_post', [$this, 'save_callback_meta_box_content']);

			add_action('admin_menu', [$this, 'register_menu']);
			add_action('admin_menu', [$this, 'customize_menu']);

			/*
			 * Add Tabs Panel into WooCommerce Postbox
			 */
			add_filter('woocommerce_product_data_tabs', [$this, 'add_postbox_tabs']);
			add_action('admin_head', [$this, 'responsive_tabs_css_icon']);
			/*
			 * Enqueue our JS / CSS files
			 */
// 
			add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts_and_styles'], 10, 1);
			/*
			 * Product Tab content Panels
			 */
			add_action('woocommerce_product_data_panels', [$this, 'add_product_panels']);
			add_action('woocommerce_process_product_meta', [$this, 'product_meta_fields_save']);
			add_action('woocommerce_init', [$this, 'init']);
			add_filter('woocommerce_product_tabs', [$this, 'add_product_tabs']);
		}

		public function init ()
		{

			if ($this->use_the_content_filter()) :
				add_filter('responsive_woo_tab_content_filter', [$this, 'content_filter'], 10, 1);
			endif;
			add_filter('responsive_woo_tab_product_tabs_content', 'do_shortcode');
			// Allow the use of shortcodes within the tab content
			// Add our custom product tabs section to the product page
			add_filter('woocommerce_product_tabs', [$this, 'add_custom_product_tabs']);
			// Add our custom product tabs layoouts to the product page
			$this->reorder_default_tabs();
			$settigs = get_option('responsive_tabs_woocommerce_default');
			if ($settigs > 0) :
				add_filter('woocommerce_locate_template', [$this, 'woo_template'], 1, 3);
			endif;
		}

		public function register_menu ()
		{

			$user_role = get_option('responsive_vc_tabs_permission');
			$role_object = get_role($user_role);
			$first_key = '';
			if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
				reset($role_object->capabilities);
				$first_key = key($role_object->capabilities);
			} else {
				$first_key = 'manage_options';
			}
			add_submenu_page('edit.php?post_type=responsive_woo_tabs', 'Responsive Tabs WooCommerce Settings', 'Settings', $first_key, 'responsive_woo_tabs-welcome', [$this, 'woo_extension']);
		}


		public function customize_menu ()
		{
			remove_submenu_page('edit.php?post_type=responsive_woo_tabs', 'post-new.php?post_type=responsive_woo_tabs');
		}

		public function woo_extension ()
		{
			new \TABS_RES_PLUGINS\Page\WooExtension();
		}


	}
