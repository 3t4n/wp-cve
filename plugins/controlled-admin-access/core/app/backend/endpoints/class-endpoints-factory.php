<?php

namespace WPRuby_CAA\Core\App\Backend\Endpoints;


class Endpoints_Factory {

	public static function boot()
	{
		foreach (self::available_endpoints() as $endpoint)
		{
			if (class_exists($endpoint)) {
				new $endpoint();
			}
		}
	}

	/**
	 * @param string $endpoint
	 *
	 * @return array|string
	 */
	public static function get_endpoints_nonce ($endpoint = '') {

		if ($endpoint !== '') {
			if (class_exists($endpoint)) {
				/** @var Abstract_Endpoint $endpoint */
				$endpointClass = new $endpoint();
				return wp_create_nonce( $endpointClass->action() );
			}
		}

		$nonce = [];
		foreach (self::available_endpoints() as $endpoint) {
			/** @var Abstract_Endpoint $endpoint */
			$endpointClass = new $endpoint();
			$nonce[$endpointClass->action()] = wp_create_nonce( $endpointClass->action() );
		}

		return $nonce;
	}

	/**
	 * @return string[]
	 */
	public static function available_endpoints()
	{
		return [
			Create_Update_User_Endpoint::class,
			Delete_User_Endpoint::class,
			Get_Users_Endpoint::class,
			Get_User_Endpoint::class,
			Get_Menu_Endpoint::class,
			Activate_User_Endpoint::class,
			Deactivate_User_Endpoint::class,
			Bulk_Actions_Endpoint::class,
			Update_Settings_Endpoint::class,
			Get_Settings_Endpoint::class,
			Reset_User_Endpoint::class,
		];
	}

}
