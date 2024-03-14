<?php
namespace Shop_Ready\extension\shopajax;

define( 'SHOP_READY_AJAX_FILTER_MODULE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOP_READY_AJAX_FILTER_MODULE_URL', plugin_dir_url( __FILE__ ) );

use Shop_Ready\base\Register_Service;
use Shop_Ready\base\Module;
final class Service 
{
	
	use Register_Service;
	use Module;
	public static $ext_name = 'ajax_filter';

	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
		
		return [

			Assets\Enqueue::class,	
			Grid\Filter::class,	
			
		];
		
		return [];
	}

	private static function instantiate( $class )
	{
		$service = new $class();
		return $service;
	}
	
}