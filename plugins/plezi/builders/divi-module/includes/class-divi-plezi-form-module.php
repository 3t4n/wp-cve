<?php
class Divi_Plezi_Form_Module extends DiviExtension {
	public $gettext_domain = 'plezi-for-wordpress';
	public $name = 'plezi-form';
	public $version = '1.0.0';

	public function __construct( $name = 'plezi-form', $args = array() ) {
		$this->plugin_dir = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}
}

new Divi_Plezi_Form_Module();
