<?php

global $directorypress_categories_widget_params;
$directorypress_categories_widget_params = array(
		array(
				'type' => 'directorytype',
				'param_name' => 'directorytype',
				'heading' => __("Categories links will redirect to selected directorytype", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'style',
				'value' => array(__('Style 1', 'DIRECTORYPRESS') => '1', __('Style 2', 'DIRECTORYPRESS') => '2'),
				'heading' => __('Style', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'parent',
				'heading' => __('Parent category', 'DIRECTORYPRESS'),
				'description' => __('ID of parent category (default 0 – this will build categories tree starting from the parent as root).', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'depth',
				'value' => array('1', '2'),
				'heading' => __('Categories nested level', 'DIRECTORYPRESS'),
				'description' => __('The max depth of categories tree. When set to 1 – only root categories will be listed.', 'DIRECTORYPRESS'),
			),
		array(
				'type' => 'textfield',
				'param_name' => 'subcats',
				'heading' => __('Show subcategories items number', 'DIRECTORYPRESS'),
				'description' => __('This is the number of subcategories those will be displayed in the table, when category item includes more than this number "View all subcategories ->" link appears at the bottom.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'depth', 'value' => '2'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'count',
				'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
				'heading' => __('Show category listings count?', 'DIRECTORYPRESS'),
				'description' => __('Whether to show number of listings assigned with current category.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'hide_empty',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Hide empty categories?', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'icons',
				'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
				'heading' => __('Show categories icons', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'cat_icon_type',
				'value' => array(__('Font Icons', 'DIRECTORYPRESS') => '1', __('Image icons', 'DIRECTORYPRESS') => '2', __('Svg icons', 'DIRECTORYPRESS') => '3'),
				'heading' => __('Show categories icons', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'categoriesfield',
				'param_name' => 'categories',
				'heading' => __('Categories', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'checkbox',
				'param_name' => 'visibility',
				'heading' => __("Show only on directorytype pages", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => __("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
		),
);

class directorypress_categories_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_categories_widget_params;

		parent::__construct(
				'directorypress_categories_widget',
				__('DIRECTORYPRESS - Categories', 'DIRECTORYPRESS')
		);

		$this->convertParams($directorypress_categories_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object;
		
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			$instance['menu'] = 0;
			$instance['columns'] = 1;
			$instance['is_widget'] = 1;
			
			$title = apply_filters('widget_title', $instance['title']);
			
			echo wp_kses_post($args['before_widget']);
				if (!empty($title)) {
					if ($instance['style'] == 1){
						echo '<div class="directorypress_category_widget_inner">'. wp_kses_post($args['before_title'] . $title . $args['after_title']) .'</div>';
					}else{
						echo '<div class="directorypress_category_widget_inner style2">'. wp_kses_post($args['before_title'] . $title . $args['after_title']) .'</div>';
					}
				}
				echo '<div class="directorypress-widget directorypress-categories-widget">';
					if ($instance['style'] == 1){
						echo '<div class="directorypress_category_widget_inner">';
					}else{
						echo '<div class="directorypress_category_widget_inner style2">';
					}
						$directorypress_handler = new directorypress_categories_handler();
						$directorypress_handler->init($instance);
						echo wp_kses_post($directorypress_handler->display());
					echo '</div>';
				echo '</div>';
			echo wp_kses_post($args['after_widget']);
				
		}
	}
}
?>