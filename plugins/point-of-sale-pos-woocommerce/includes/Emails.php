<?php

namespace ZPOS;

use ZPOS\Emails\Receipt;

class Emails
{
	public function __construct()
	{
		add_filter('woocommerce_email_classes', [$this, 'apply_email_classes']);
	}

	public function apply_email_classes($classes)
	{
		$classes['zpos_receipt'] = new Receipt();

		return $classes;
	}
}
