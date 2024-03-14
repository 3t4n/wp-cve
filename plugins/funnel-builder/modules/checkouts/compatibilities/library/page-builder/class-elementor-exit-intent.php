<?php

namespace ElementorPro\Modules\ThemeBuilder\Conditions;

use ElementorPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

#[AllowDynamicProperties] 

  class WFACP_Elementor_exit_intent extends Post {

	public function get_label() {
		return 'FunnelKit Checkout';
	}

	public function register_sub_conditions() {
	}
}

