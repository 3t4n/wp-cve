<?php
class wps_visitor_counter extends WP_Widget{

	function __construct(){
		$paramitter=array(
		'description' => __('Display Visitor Counter and Statistics Traffic in shortcode and widget', 'wps-visitor-counter'), //plugin description
		'name' => 'WPS - Visitor Counter'  //title of plugin
		);

		parent::__construct('wps_visitor_counter', '', $paramitter);
	}
	
	public function widget($args, $instance){
		echo wps_add_visitor_counter();
	}
}