<?php

namespace ZPOS\Admin;

class Woocommerce
{
	public function __construct()
	{
		new Woocommerce\Products();
		new Woocommerce\Categories();
	}
}
