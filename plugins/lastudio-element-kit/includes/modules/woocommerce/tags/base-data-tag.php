<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Tags;

use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base_Data_Tag extends Data_Tag {

	public function get_group() {
		return Module::WOOCOMMERCE_GROUP;
	}
}
