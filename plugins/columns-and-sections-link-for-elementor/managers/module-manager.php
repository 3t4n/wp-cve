<?php
namespace ECSL\Managers;

use ECSL\Classes\Helper;

class Module_Manager {
	protected $modules = [];
	public function __construct() {
		$helper        = new Helper();
		$this->modules = $helper->get_link_module();

		foreach ( $this->modules as $key => $module_name ) {
			if ( $module_name['enabled'] == 'true' || trim( $module_name['enabled'] ) === '' || $module_name['enabled'] === null ) {
				$class_name                            = str_replace( '-', ' ', $key );
				$class_name                            = str_replace( ' ', '', ucwords( $class_name ) );
				$class_name                            = 'ECSL\\Modules\\' . $class_name . '\Module';
				$this->modules[ $module_name['name'] ] = $class_name::instance();
			}
		}
	}
}
