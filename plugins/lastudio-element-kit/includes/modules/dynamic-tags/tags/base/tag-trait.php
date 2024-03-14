<?php

namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait Tag_Trait {

	public function is_editable() {
        return true;
	}
}
