<?php

namespace ZPOS;

use ZPOS\Model\Gateway;
use ZPOS\Model\Cart;
use ZPOS\Model\Product;

class Model
{
	public function __construct()
	{
		new Gateway();
		new Cart();
		new Product();
	}
}
