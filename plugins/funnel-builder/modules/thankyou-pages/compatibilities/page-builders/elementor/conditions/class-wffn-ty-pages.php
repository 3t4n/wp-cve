<?php

namespace ElementorPro\Modules\ThemeBuilder\Conditions;

use ElementorPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

#[AllowDynamicProperties]

  class WFFN_TY_Pages extends Post {

	public function get_label() {
		return 'FunnelKit Thank You Page';
	}


	public function register_sub_conditions() {
	}


}
