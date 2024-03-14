<?php
 
class DirectoryPress_Elementor_Widget_Settings {
	
	
	public function __construct( $postid, $widget_type ) {
		
		
		$this->postid 		= $postid;
		//$this->widget_id 	= $widget_id;
		$this->widget_type 	= $widget_type;
		$this->widget 		= null;

		$this->parse();
		
	}
	
	
	public function elementor(){
		
		return 	\Elementor\Plugin::$instance;
		
	}
	
	public function get_settings () {
		if(!is_null($this->widget)){
			$widget = $this->elementor()->elements_manager->create_element_instance( $this->widget );
		
			return $widget->get_settings_for_display();
		
		}else{
			return false;
		}
	}
	
	private function parse() {
		
		$data = $this->read_data();
		
		$this->parse_options($data);
		
	}
	
	private function read_data () {

		return $this->elementor()->documents->get( $this->postid )->get_elements_data();
		
	}
	
	private function parse_options($data) {
		
		if(!is_array($data) || empty($data)){
			return;
		}		
		
		foreach ( $data as $item ) {
			
			if(empty($item)){
				continue;
			}
			
			if ( 'section' === $item['elType'] || 'column' === $item['elType']) {
				
				$this->parse_options($item['elements']);
				
			} else {
				
				$this->parse_options_simple($item);
			}
		}
	}
	
	private function parse_options_simple($item) {

		if (array_key_exists('widgetType', $item) && $item['widgetType'] === $this->widget_type) {
			$this->widget = $item;
		}
	}
}