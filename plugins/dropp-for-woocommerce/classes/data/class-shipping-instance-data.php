<?php

namespace Dropp\Data;

use Dropp\Shipping_Method\Shipping_Method;
use WC_Shipping_Zone;

class Shipping_Instance_Data
{
	public function __construct(public Shipping_Method $shipping_method, public WC_Shipping_Zone $zone)
	{
	}
}
