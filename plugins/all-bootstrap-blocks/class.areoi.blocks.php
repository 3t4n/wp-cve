<?php

class AREOI_Blocks
{
	private static $initiated = false;

	public static function init() {

		if ( !self::$initiated ) {

			add_filter( 'block_categories_all', [ 'AREOI_Blocks', 'add_block_categories' ], 10, 2 );

			require AREOI__PLUGIN_DIR . '/blocks/index.php';
		}
	}

	public static function add_block_categories( $categories, $post ) 
	{
		$new_category = [
			'slug' => 'areoi-headers-and-footers',
			'title' => __( 'Bootstrap Headers and Footers (Beta)', AREOI__TEXT_DOMAIN ),
			'icon'	=> ''
		];
		$new_categories[] = $new_category;

		$new_category = [
			'slug' => 'areoi-layout',
			'title' => __( 'Bootstrap Layout', AREOI__TEXT_DOMAIN ),
			'icon'	=> ''
		];
		$new_categories[] = $new_category;

		$new_category = [
			'slug' => 'areoi-components',
			'title' => __( 'Bootstrap Components', AREOI__TEXT_DOMAIN ),
			'icon'	=> ''
		];
		$new_categories[] = $new_category;

		$new_category = [
			'slug' => 'areoi-strips',
			'title' => __( 'Bootstrap Strips (Beta)', AREOI__TEXT_DOMAIN ),
			'icon'	=> ''
		];
		$strips = array( $new_category );

		$asset_file = include( AREOI__PLUGIN_DIR . 'build/index.asset.php');
		wp_register_script(
		   'areoi-blocks',
		   AREOI__PLUGIN_URI . 'build/index.js',
		    $asset_file['dependencies'],
		    $asset_file['version']
		);
		
		return array_merge( $new_categories, $categories, $strips );
	}
}
