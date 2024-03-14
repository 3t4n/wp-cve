<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

use Dev4Press\v43\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'gd-topic-polls';

	public $version = '2.3';
	public $build = 130;
	public $edition = 'lite';
	public $status = 'stable';
	public $updated = '2023.11.01';
	public $released = '2017.04.17';

	public $is_bbpress_plugin = true;
}
