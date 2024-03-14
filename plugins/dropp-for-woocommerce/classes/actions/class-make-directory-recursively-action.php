<?php

namespace Dropp\Actions;

use Dropp\Models\Dropp_Consignment;
use Dropp\Models\Dropp_Location;
use Dropp\Order_Adapter;
use WP_Filesystem_Base;

/**
 * Get Consignment from API
 */
class Make_Directory_Recursively_Action {
	public function __invoke(string $path, int $depth): bool {
		/** @var WP_Filesystem_Base $wp_filesystem */
		global $wp_filesystem;
		if ($depth <= 0) {
			return false;
		}
		$parts = explode('/', $path);
		$root = array_slice($parts, 0, -$depth);
		$local = array_slice($parts, -$depth);
		$dir = implode('/', $root);
		foreach ($local as $basename) {
			$dir .= "/$basename";
			if ($wp_filesystem->is_dir($dir)) {
				continue;
			}
			if (! $wp_filesystem->mkdir($dir)) {
				return false;
			}
		}
		return true;
	}
}
