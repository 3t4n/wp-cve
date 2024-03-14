<?php
namespace LaStudioKitThemeBuilder\Modules;

use Elementor\Core\Base\Module as Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class Modules_Manager {

	private $modules = [];

	public function __construct() {

		$modules = [
			'theme-builder',
            'woocommerce',
            'dynamic-tags',
            'screenshots',
            'popup',
            'woofilters',
            'nested-elements',
			'edynamic-tags',
		];

		$forceActivateModules = [
			'woocommerce',
			'woofilters',
            'nested-elements',
            'edynamic-tags'
		];

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = '\LaStudioKitThemeBuilder\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
            if( ( $class_name::is_active() && ( !lastudio_kit()->has_elementor_pro() || in_array($module_name, $forceActivateModules) ) ) ){
                $this->modules[ $module_name ] = $class_name::instance();
            }
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}
			return null;
		}

		return $this->modules;
	}
}
