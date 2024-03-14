<?php

global $directorypress_locations_widget_params;
$directorypress_locations_widget_params = array(
		array(
				'type' => 'directorytype',
				'param_name' => 'directorytype',
				'heading' => __("Locations links will redirect to selected directorytype", "DIRECTORYPRESS"),
		),
		array(
				'type' => 'textfield',
				'param_name' => 'parent',
				'heading' => __('Parent location', 'DIRECTORYPRESS'),
				'description' => __('ID of parent location (default 0 – this will build locations tree starting from the parent as root).', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'depth',
				'value' => array('1', '2'),
				'heading' => __('locations nesting package', 'DIRECTORYPRESS'),
				'description' => __('The max depth of locations tree. When set to 1 – only root locations will be listed.', 'DIRECTORYPRESS'),
			),
		array(
				'type' => 'textfield',
				'param_name' => 'sublocations',
				'heading' => __('Show sub-locations items number', 'DIRECTORYPRESS'),
				'description' => __('This is the number of sublocations those will be displayed in the table, when location item includes more than this number "View all sublocations ->" link appears at the bottom.', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'depth', 'value' => '2'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'count',
				'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
				'heading' => __('Show location listings count?', 'DIRECTORYPRESS'),
				'description' => __('Whether to show number of listings assigned with current location.', 'DIRECTORYPRESS'),
		),
		array(
				'type' => 'dropdown',
				'param_name' => 'hide_empty',
				'value' => array(__('No', 'DIRECTORYPRESS') => '0', __('Yes', 'DIRECTORYPRESS') => '1'),
				'heading' => __('Hide empty locations?', 'DIRECTORYPRESS'),
		),
		/* array(
				'type' => 'dropdown',
				'param_name' => 'icons',
				'value' => array(__('Yes', 'DIRECTORYPRESS') => '1', __('No', 'DIRECTORYPRESS') => '0'),
				'heading' => __('Show locations icons', 'DIRECTORYPRESS'),
		), */
		/* array(
				'type' => 'locationsfield',
				'param_name' => 'locations',
				'heading' => __('locations', 'DIRECTORYPRESS'),
				'dependency' => array('element' => 'custom_home', 'value' => '0'),
		), */
		array(
				'type' => 'checkbox',
				'param_name' => 'visibility',
				'heading' => __("Show only on directorytype pages", "DIRECTORYPRESS"),
				'value' => 1,
				'description' => __("Otherwise it will load plugin's files on all pages.", "DIRECTORYPRESS"),
		),
);

class directorypress_locations_widget extends directorypress_widget {

	public function __construct() {
		global $directorypress_object, $directorypress_locations_widget_params;

		parent::__construct(
				'directorypress_locations_widget',
				__('DIRECTORYPRESS - Locations', 'DIRECTORYPRESS')
		);

		$this->convertParams($directorypress_locations_widget_params);
	}
	
	public function render_widget($instance, $args) {
		global $directorypress_object;
		
		// when visibility enabled - show only on directorytype pages
		if (empty($instance['visibility']) || !empty($directorypress_object->public_handlers)) {
			$instance['menu'] = 0;
			$instance['columns'] = 1;
			
			$title = apply_filters('widget_title', $instance['title']);
	
			echo wp_kses_post($args['before_widget']);
				if (!empty($title)) {
					echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
				}
				if ($instance['style'] == 1){
					echo '<div class="directorypress-widget directorypress-locations-widget clearfix">';
				}else{
					echo '<div class="directorypress-widget directorypress-locations-widget style2 clearfix">';	
				}
					$directorypress_handler = new directorypress_locations_handler();
					$directorypress_handler->init($instance);
					echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			echo wp_kses_post($args['after_widget']);
		}
	}
}
?>