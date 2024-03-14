<?php 

class directorypress_categories_handler extends directorypress_public {

	public function init($args = array()) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		parent::init($args);

		$shortcode_atts = array_merge(array(
				'custom_home' => 0,
				'directorytype' => 0,
				'parent' => 0,
				'depth' => 1,
				'columns' => 1,
				'count' => 1,
				'hide_empty' => 0,
				'subcats' => 0,
				'categories' => array(),
				'grid' => 0,
				'grid_view' => 0, // 3 types of view
				'icons' => 1,
				'menu' => 0,
				'icon_type' => 'img',
				'packages' => array(),
				'cat_style' => 'default',
				'cat_icon_type' => 1,
				'scroll' => 0, //cz custom
				'desktop_items' => 3, //cz custom
				'mobile_items' => 1 , //cz custom
				'tab_items' => 2 , //cz custom
				'autoplay' => 'false' , //cz custom
				'loop' => 'false' , //cz custom
				'owl_nav' => 'false' , //cz custom
				'delay' => '1000' , //cz custom
				'autoplay_speed' => '1000' , //cz custom
				'gutter' => '30' , //cz custom
				'slider_arrow_position' => 'absolute',
				'slider_arrow_icon_pre' => '',
				'slider_arrow_icon_next' => '',
				'cat_font_size' => '' , //cz custom
				'cat_font_weight' => '' , //cz custom
				'cat_font_line_height' => '' , //cz custom
				'cat_font_transform' => '' , //cz custom
				'child_cat_font_size' => '' , //cz custom
				'child_cat_font_weight' => '' , //cz custom
				'child_cat_font_line_height' => '' , //cz custom
				'child_cat_font_transform' => '' , //cz custom
				'parent_cat_title_color' => '' , //cz custom
				'parent_cat_title_color_hover' => '' , //cz custom
				'parent_cat_title_bg' => '' , //cz custom
				'parent_cat_title_bg_hover' => '' , //cz custom
				'subcategory_title_color' => '' , //cz custom
				'subcategory_title_color_hover' => '' , //cz custom
				'cat_bg' => '' , //cz custom
				'cat_bg_hover' => '' , //cz custom
				'cat_border_color' => '' , //cz custom
				'cat_border_color_hover' => '' , //cz custom
				'is_widget' => 0,
		), $args);
		$this->args = $shortcode_atts;
		
		if (isset($this->args['categories']) && !is_array($this->args['categories'])) {
			if ($categories = array_filter(explode(',', $this->args['categories']), 'trim')) {
				$this->args['categories'] = $categories;
			}
		}
		$this->scripts();
		apply_filters('directorypress_categories_handler_construct', $this);
		
	}
	public function scripts() {
		
		wp_enqueue_style('directorypress_category');
	}
	public function display() {
		global $directorypress_object;
		$this->args['max_subterms'] = $this->args['subcats'];
		$this->args['exact_terms'] = $this->args['categories'];
		
		ob_start();
		
		$terms = new DirectoryPress_Category_Terms($this->args);
		$terms->display();

		$output = ob_get_clean();

		return $output;

	}
}

?>