<?php

class LitCommerce_PermalinkSettings implements LitCommerce_Automation
{
	public function getName()
	{
		return __('Make sure proper permalink structure settings', 'litcommerce');
	}

	public function runStep()
	{
		$currentStructure = get_option('permalink_structure');

		if (!empty($currentStructure)) {
			return new LitCommerce_Result_Object(true);
		}

		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure('/%postname%/');

		return new LitCommerce_Result_Object(true);
	}
}
