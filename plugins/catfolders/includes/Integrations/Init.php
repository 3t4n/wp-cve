<?php
namespace CatFolders\Integrations;

class Init {
	public function __construct() {
		$this->registerClasses();
	}

	public function registerClasses() {
		$classes = array(
			'PageBuilders',
			'MediaLibraryAssistant',
		);

		foreach ( $classes as $class ) {
			$cl = __NAMESPACE__ . "\\{$class}";
			new $cl();
		}
	}
}
