<?php

class CF7_COMPLEMENTARY_CTRL {

	protected $slug;
	protected $container_obj;

	public function __construct( $container_obj, $slug ) {
		$this->container_obj = $container_obj;
		$this->slug          = $slug;
		$container_obj->set_control_obj( $slug, $this );
	} // End __construct

	public function get_title() {
		return ! empty( $this->title ) ? $this->title : '';
	}

	public function get_description() {
		 return ! empty( $this->description ) ? $this->description : '';
	}

	public function get_image_url() {
		return ! empty( $this->image_url ) ? $image = plugins_url( $this->image_url, __FILE__ ) : '';
	}
} // End CF7_COMPLEMENTARY_CTRL
