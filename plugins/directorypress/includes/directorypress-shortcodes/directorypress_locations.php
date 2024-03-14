<?php 

class directorypress_locations_handler extends directorypress_public {

	public function init($args = array()) {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		parent::init($args);

		$shortcode_atts = array_merge(array(
				'custom_home' => 0,
				'directorytype' => 0,
				'location_style' => 'default',
				'parent' => 0,
				'depth' => 1,
				'columns' => 1,
				'count' => 0,
				'hide_empty' => 0,
				'sublocations' => 0,
				'locations' => array(),
				'icons' => 1,
				'location_bg' => '',
				'location_bg_image' => '',
				'gradientbg1' => '',
				'gradientbg2' => '',
				'opacity1' => '',
				'opacity2' => '',
				'gradient_angle' => '',
				'location_width' => 30,
				'location_height' => '',
				'location_padding' => 15,
		), $args);
		$this->args = $shortcode_atts;

		
		if (isset($this->args['locations']) && !is_array($this->args['locations'])) {
			if ($locations = array_filter(explode(',', $this->args['locations']), 'trim')) {
				$this->args['locations'] = $locations;
			}
		}
		apply_filters('directorypress_locations_handler_construct', $this);

	}
		
	public function display() {
		global $directorypress_object;
		
		$this->args['max_subterms'] = $this->args['sublocations'];
		$this->args['exact_terms'] = $this->args['locations'];
		
		ob_start();

		$terms = new DirectoryPress_Location_Terms($this->args);
		$terms->display();

		$output = ob_get_clean();
		//$terms->getDynamicStyles();
		return $output;
	}
}

?>