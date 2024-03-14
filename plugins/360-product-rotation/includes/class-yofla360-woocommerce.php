<?php

if (!defined("ABSPATH")) {
	exit;
}

/**
 * Class YoFLA360Woocommerce
 *
 * Adds tab to product settings to specify an (uploaded) 360 view to replace
 * main product image.
 *
 * Inspired by WebRotate 360 Woocommerce plugin structure, thanks!
 * (WebRotate 360, in return, got inspired by my 360 player & software)
 *
 */
class YoFLA360Woocommerce
{

	public static $thumbCounter = 1;

	/**
	 * The currently displayed product
	 */
	protected $product;

	/**
	 * True, if woocommerce version 2.x is installed
	 */
	protected $is_woocommerce_2x;

	/**
	 * True, if product has a 360 view assigned (simple or variable product)
	 */
	protected $is_360_view;

	/**
	 * True, if product has variations
	 */
	protected $is_variable_product;

	/**
	 * Array with ids and names of the product variants
	 */
	protected $product_variants;

	/**
	 * YoFLA360Woocommerce constructor.
	 *
	 */
	public function __construct()
	{

		//init class variables
		$this->_init();

		//admin interface
		if (is_admin()) {
			add_action("woocommerce_product_write_panel_tabs", array($this, "yofla_360_tab_options_tab"));
			add_action("woocommerce_product_data_panels", array($this, "yofla_360_tab_options"));
			add_action("woocommerce_process_product_meta", array($this, "yofla_360_save_product"));
			add_action("admin_enqueue_scripts", array($this, "yofla_360_admin_scripts"));
		} else {

			// apply yofla_360_replace_product_image filter on product images
			if ($this->is_woocommerce_2x) {
				//for wocommerce 2.x
				add_filter("woocommerce_single_product_image_html", array(
					$this,
					"yofla_360_replace_product_image"
				));
			} else {
				//for wocommerce 1.x, 3.x, ...

				// add product image
				add_filter("woocommerce_single_product_image_thumbnail_html", array(
					$this,
					"yofla_360_replace_product_image"
				));

				// css
				add_filter("woocommerce_single_product_image_gallery_classes", array(
					$this,
					"yofla_360_add_gallery_styles"
				));
			}

			//woocommerce specific javascript and styles
			add_action('wp_enqueue_scripts', array($this, 'yofla_360_frontend_scripts'));
		}
	}

	/**
	 * Includes javascript and css files for variable products functionality
	 *
	 */
	public function yofla_360_frontend_scripts()
	{

		if (false == $this->_is_360_view()) {
			return;
		}

		$js_client_file = 'y360-woocommerce-client.js';

		// register javascript
		wp_register_script('yofla_360_woocommerce_client', YOFLA_360_PLUGIN_URL . 'js/' . $js_client_file, [
			'jquery',
			'woocommerce'
		], '1.0.1');

		// add javascript
		wp_enqueue_script('yofla_360_woocommerce_client');

		// add styles
		wp_enqueue_style('yofla_360_woocommerce_styles', YOFLA_360_PLUGIN_URL . 'styles/y360-woocommerce-styles.css');


        if(isset(get_option('yofla_360_options')['woocommerce_360thumb_url'])){
            $thumbUrl = get_option('yofla_360_options')['woocommerce_360thumb_url'];
            if (isset($thumbUrl) && strlen($thumbUrl) > 5) {
                if (function_exists('wp_add_inline_script')) {
                    $script = 'window.Yofla360_woocommerce_360thumb_url = "' . $thumbUrl . '";';
                    wp_add_inline_script('yofla_360_woocommerce_client', $script);
                }
            }
        }
	}

	/**
	 * Includes javascript and css files for admin zone
	 *
	 */
	public function yofla_360_admin_scripts()
	{
		wp_enqueue_style('yofla_360_woocommerce_admin_styles', YOFLA_360_PLUGIN_URL . 'styles/y360-woocommerce-admin-styles.css');
	}


	/**
	 * HTML of the 360 Product Tab Tab
	 *
	 */
	public function yofla_360_tab_options_tab()
	{
		echo '<li class="custom_tab"> <a href="#yofla360_tab_data" id="yofla360_tab">&nbsp; 360&deg; View</a></li>';
	}


	/**
	 * HTML of the 360 Product Tab Content
	 *
	 */
	public function yofla_360_tab_options()
	{
		echo '<style type="text/css">a#yofla360_tab:before{content: "\f111" !important;}</style>
            <div id="yofla360_tab_data" class="panel woocommerce_options_panel">
            <div class="options_group">
            ';

		//list of 360 views
		$products_list = $this->_get_products_list();
		$upload_url = admin_url('upload.php?page=yofla-360-media');

		//no product uploaded yet
		if (sizeof($products_list) == 1) {
			echo '<span style="padding: 10px;">No 360&deg; views found. Do you wish to <a href="' . $upload_url . '">add one</a> now?</span>';
		} //a product is uploaded
		else {

			if ($this->_is_variable_product()) {

				$variants = $this->_get_product_variants();

				foreach ($variants as $variant) {

					$variant_name = $variant["name"];
					$variant_id = $variant["id"];
					$variant_value = $this->_get_variant_value($variant_id);

					echo "<strong>$variant_name</strong>";

					woocommerce_wp_select(array(
						"id" => "_y360path_variant_" . $variant_id,
						"name" => "_y360path_variants[$variant_id]",
						"label" => "Please choose a 360&deg; view",
						"placeholder" => "",
						"value" => "$variant_value",
						"desc_tip" => true,
						"description" => sprintf("Upload new 360 views in Media page."),
						"options" => $products_list
					));
				}
			} else {
				woocommerce_wp_select(array(
					"id" => "_y360path",
					"name" => "_y360path",
					"label" => "Please choose a 360&deg; view",
					"placeholder" => "",
					"desc_tip" => true,
					"description" => sprintf("Upload new 360 views in Media page."),
					"options" => $products_list
				));
				woocommerce_wp_text_input(array(
					"id" => "_y360path_url",
					"name" => "_y360path_url",
					"label" => "OR choose absolute url of the product",
					"placeholder" => "",
					"desc_tip" => true,
					"description" => sprintf("Absolute url path to folder with 360 view"),
				));
			}
		}

		echo "</div></div>";
	}

	/**
	 * Saving path parameter when product is saved/updated
	 *
	 * @param $post_id
	 */
	public function yofla_360_save_product($post_id)
	{
		if (isset($_POST["_y360path"])) {
			update_post_meta($post_id, "_y360path", sanitize_text_field($_POST["_y360path"]));
		}
		if (isset($_POST["_y360path_url"])) {
			update_post_meta($post_id, "_y360path_url", $_POST["_y360path_url"]);
		}
		if (isset ($_POST["_y360path_variants"])) {
			update_post_meta($post_id, "_y360path_variants", $_POST["_y360path_variants"]);
		}
	}


	/**
	 * Callback function for the filter that modifies the output for the product image
	 *
	 * @param $content
	 * @return string
	 */
	public function yofla_360_replace_product_image($content)
	{
		global $post;
		$y360_options = get_option( 'yofla_360_options' ); //read options stored in WP options database

		$replaceMethod = 'default'; // [default|htmlvar] htmlvar: 360 view code is injected using JS

		$is_woocommerce_alternate_embedding = !empty($y360_options['woocommerce_alternate_embedding']);
		if ($is_woocommerce_alternate_embedding) {
			$replaceMethod = 'htmlvar';
		}

		if (false == $this->_is_360_view()) {
			// if is NOT a 360 view
			return $content;
		} else {
			if ($this->_is_variable_product()) {
				/**
				 * Variable Product
				 */
				$new_content = $this->_get_360player_variableProduct_code($replaceMethod);
			} else {
				/**
				 * Simple Product
				 */
				$_y360path = (get_post_meta($post->ID, "_y360path", true));
				$_y360path_url = (get_post_meta($post->ID, "_y360path_url", true));
				$path = ($_y360path_url) ? $_y360path_url : $_y360path;
				$new_content = $this->_get_360player_embed_code($path, $replaceMethod);
			}
		}

		/**
		 *
		 */
		if (self::$thumbCounter == 1) {
			self::$thumbCounter++;
			return $new_content . $content;
			return $content;
		} else {
			return $content;
		}
	}

	/**
	 * Adds styles to enclosing product-gallery div
	 *
	 * @param $prevStyles
	 * @return array
	 */
	public function yofla_360_add_gallery_styles($prevStyles)
	{
		if (false == $this->_is_360_view()) {
			$prevStyles;
		}
		return array_merge($prevStyles, ["yofla_360_has_360_view"]);
	}

	/**
	 * Initializes the class variables
	 */
	private function _init()
	{
		$this->is_woocommerce_2x = substr($this->_get_woo_version_number(), 0, 1) == '2';
	}

	/**
	 * Returns true, if the product (simple or variable) has a 360 view defined
	 *
	 */
	private function _is_360_view()
	{
		global $post;
		if(!$post) return;

		if (isset($this->is_360_view)) {
			return $this->is_360_view;
		} else {
			if ($this->_is_variable_product()) {
				$is_360_view = sizeof($this->_defined_360_variant_views()) > 0;
			} else {
				$_y360path = (get_post_meta($post->ID, "_y360path", true));
				$_y360path_url = (get_post_meta($post->ID, "_y360path_url", true));
				$is_360_view = !empty($_y360path) || !empty($_y360path_url);
			}
			$this->is_360_view = $is_360_view;
			return $is_360_view;
		}
	}

	/**
	 * Returns the product object of current product
	 *
	 * @return bool|WC_Product
	 */
	private function _get_product_instance()
	{
		if (!isset($this->product)) {
			$_pf = new WC_Product_Factory();
			$this->product = $_pf->get_product(get_the_ID());
		}
		return $this->product;
	}


	/**
	 * HTML output for simple product
	 *
	 * @param $_y360path
	 * @param null $elementId
	 * @return bool|string
	 */
	private function _get_360player_embed_code($_y360path, $method = 'none')
	{
		$viewData = new YoFLA360ViewData();
		$viewData->set_src($_y360path);
		$viewData->width = '100%';
		$viewData->height = '100%'; // null; //set by y360-woocommerce-styles.css

		//iframe embedding by default
		$output = YoFLA360()->Frontend()->get_embed_iframe_html($viewData, null);

		$output = "<div data-yofla360html='" . $_y360path . "' class='woocommerce-product-gallery__image'>" . $output . "</div>";

		//output
		if ($method == 'htmlvar') {
			$htmlVarOutput = '<script> ';
			$htmlVarOutput .= 'var yofla360html = window.yofla360html || {};';
			$htmlVarOutput .= 'yofla360html["' . $_y360path . '"] = `' . htmlentities2($output) . '`;';
			$htmlVarOutput .= '</script> ';
			return $htmlVarOutput;
		} else {
			return $output;
		}

	}

	/**
	 * HTML output for variable product
	 *
	 * @return string
	 */
	private function _get_360player_variableProduct_code($method = 'none')
	{
		global $post;


		$output = '<div class="woocommerce-product-gallery__image y360_variable_products">';

		//list of all variants with
		$_variants = (get_post_meta($post->ID, "_y360path_variants", true));

		if ($_variants && sizeof($_variants) > 0) {
			foreach ($_variants as $postId => $path) {

				$variant_html = '';
				$variant_className = '';

				//360 view for variant product with path defined
				if (!empty($path)) {
					//output 360 player code
					$variant_html = $this->_get_360player_embed_code($path) . "\n";
					$variant_className = 'y360_360View';
				} //no 360 view for variant product is defined
				else {
					if ($post_thumbnail_id = get_post_thumbnail_id($postId)) {
						$full_size_image = wp_get_attachment_image_src($post_thumbnail_id, 'full');
						$image_title = get_post_field('post_excerpt', $post_thumbnail_id);
						$attributes = array(
							'title' => $image_title,
							'data-src' => $full_size_image[0],
							'data-large_image' => $full_size_image[0],
							'data-large_image_width' => $full_size_image[1],
							'data-large_image_height' => $full_size_image[2],
						);
						$variant_html = get_the_post_thumbnail($postId, 'shop_single', $attributes);
						$variant_className = 'y360_ImageView';
					}
				}

				//construct div id
				$classId = 'y360_variant_content_' . $postId;
				$output .= '<div class="y360_variant ' . $variant_className . ' ' . $classId . '">' . PHP_EOL;
				$output .= $variant_html . PHP_EOL;
				$output .= '</div>' . PHP_EOL;
			}//foreach
		}
		$output .= '</div>';

		//output
		if ($method == 'htmlvar') {
			$htmlVarOutput = '<script> ';
			$htmlVarOutput .= 'var yofla360html = window.yofla360html || {};';
			$htmlVarOutput .= 'yofla360html["' . $postId . '"] = `' . htmlentities2($output) . '`;';
			$htmlVarOutput .= '</script> ';
			return $htmlVarOutput;
		} else {
			return $output;
		}

	}

	/**
	 * Returns a list of uploaded products, that can be assigned as a 360 view
	 * for this product.
	 *
	 * Output format is an array for the woocommerce_wp_select function
	 *
	 *
	 * @see yofla_360_tab_options
	 * @see YoFLA360Utils
	 */
	private function _get_products_list()
	{
		$result = array();
		$result[''] = 'No 360&deg; view is assigned';

		$list_raw = YoFLA360()->Utils()->get_yofla360_directories_list(false);

		$lk = get_option('yofla_360_options')['license_key'];
		$list_cloud = null;
		if($lk){
			$list_cloud = YoFLA360()->Utils()-> get_cloud_projects_by_lk($lk);
		}

		if($list_cloud && sizeof($list_cloud) > 0){
			foreach ($list_cloud as $key => $value) {
			    $versionNumber = '';
			    if(isset($value['versionNumber'])){
			     $versionNumber = $value['versionNumber'];
			    }
                if(isset($value['name'])){
                    $name = $value['name'];
                    $src = $value['accountId'].';'.$value['projectId'].';'.$versionNumber;
                    $result[$src] = 'Cloud: '.$name;
                }
			}
		}

		foreach ($list_raw as $key => $value) {
			$result[YOFLA_360_PRODUCTS_FOLDER . '/' . $value['name']] = 'Local: '.$value['name'];
		}
		return $result;
	}

	/**
	 * Returns false if product has no variations.
	 * Returns array of variation ids, if product has variations
	 *
	 * @return bool|mixed
	 */
	private function _is_variable_product()
	{
		if (isset($this->is_variable_product)) {
			return $this->is_variable_product;
		} else {
			$this->is_variable_product = (sizeof($this->_get_product_variants()) > 0);
			return $this->is_variable_product;
		}
	}

	/**
	 * Returns an array with names and ids of the product's variants
	 * Returns an empty array if product has no variations specified
	 *
	 * @return array [["id"=>12,"name"=>"Name"]]
	 */
	private function _get_product_variants()
	{
		if (isset($this->product_variants)) {
			return $this->product_variants;
		} else {

			$product = $this->_get_product_instance();

			if ($product && get_class($product) == 'WC_Product_Variable') {

				$variations = $product->get_available_variations();
				$result = array();

				foreach ($variations as $key => $value) {
					if ($value['variation_is_active']) {

						$id = $value['variation_id'];
						$name = isset($value['name']) ? $value['name'] : null;

						if (!$name) {
							$name = 'Variation: ';
							$variants = [];
							foreach ($value['attributes'] as $attribute_name => $attribute_value) {
								$variants[] = $attribute_value;
							}
							$name .= implode( ', ', $variants);
						}

						$result[] = array("id" => $id, "name" => $name);
					}
				}
			} //no variable product
			else {
				$result = [];
			}

			//save value
			$this->product_variants = $result;

			//return variants
			return $result;
		}
	}

	/**
	 * Returns the selected 360 view for provided variant id
	 *
	 *
	 * @param $variant_id
	 * @return string
	 */
	private function _get_variant_value($variant_id)
	{
		global $post;
		if (empty($post->ID)) {
			return '';
		}

		$_y360path_variants = (get_post_meta($post->ID, "_y360path_variants", true));
		if (isset($_y360path_variants[$variant_id])) {
			return $_y360path_variants[$variant_id];
		} else {
			return '';
		}
	}

	/**
	 * Returns a list of defined 360 views vor variant images
	 *
	 * @return array example: ['50' => 'yofla360/vespa_green']
	 */
	private function _defined_360_variant_views()
	{
		global $post;
		$result = [];

		if (empty($post->ID)) {
			return $result;
		}

		//list of all variants with
		$_variants = (get_post_meta($post->ID, "_y360path_variants", true));

		//filter variants, that have a 360 view defined, add to results
		if ($_variants && sizeof($_variants) > 0) {
			foreach ($_variants as $key => $value) {
				if (!empty($value)) {
					$result[$key] = $value;
				}
			}
		}

		//return
		return $result;
	}

	private function _get_woo_version_number()
	{
		// If get_plugins() isn't available, require it
		if (!function_exists('get_plugins')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		// Create the plugins folder and file variables
		$plugin_folder = get_plugins('/' . 'woocommerce');
		$plugin_file = 'woocommerce.php';

		// If the plugin version number is set, return it
		if (isset($plugin_folder[$plugin_file]['Version'])) {
			return $plugin_folder[$plugin_file]['Version'];

		} else {
			// Otherwise return null
			return NULL;
		}
	}

}//class
