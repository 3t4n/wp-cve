<?php

namespace SiteSEO\Models;

if ( ! defined('ABSPATH')) {
	exit;
}

interface GetJsonFromFile {
	public function getJson();

	public function getArrayJson();
}
