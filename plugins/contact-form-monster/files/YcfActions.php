<?php
CLass YcfActions {

	public function __construct() {

		add_action('admin_enqueue_scripts', array(new ycfStyles(),'registerStyles'));
		
	}
}