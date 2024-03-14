<?php

namespace ZPOS\API;

use const ZPOS\REST_NAMESPACE;

class TaxClasses extends \WC_REST_Tax_Classes_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function get_items_permissions_check($request)
	{
		if (current_user_can('read_woocommerce_pos_setting')) {
			return true;
		}

		return parent::get_items_permissions_check($request);
	}
}
