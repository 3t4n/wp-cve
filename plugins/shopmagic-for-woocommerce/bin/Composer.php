<?php

namespace WPDesk\Composer;

use ShopMagicVendor\DI\ContainerBuilder;

class Composer {

	public static function compile() {
		$cacheDir = __DIR__ . '/../cache';
		if ( file_exists( $cacheDir ) ) {
			foreach ( scandir( $cacheDir ) as $file ) {
				$filename = "$cacheDir/$file";
				if ( is_dir( $filename ) ) {
				} elseif ( is_file( $filename ) ) {
					unlink( $filename );
				}
			}
			rmdir( $cacheDir );
		}
		require __DIR__ . '/../vendor_prefixed/php-di/php-di/src/functions.php';
		require __DIR__ . '/../functions.php';
		$builder = new ContainerBuilder();
		$builder->addDefinitions(
			__DIR__ . '/../config/services.inc.php'
		);
		$builder->enableCompilation( $cacheDir );
		$builder->useAutowiring( true );
		$builder->build();
	}

}
