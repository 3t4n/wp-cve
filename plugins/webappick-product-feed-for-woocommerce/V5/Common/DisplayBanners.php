<?php
namespace CTXFeed\V5\Common;
class DisplayBanners {

	/**
	 * Lifetime Banner should be shown
	 *
	 * @return boolean
	 *
	 * @since 6.2
	 *
	 */

	  public static function get_slugname(){

		$plugins_all = get_plugins() ;
		$plugin_slug = explode('/',dirname( plugin_basename(__FILE__ ) ) );

		foreach ( $plugins_all as $key=>$value) {

			if ( $plugin_slug[0] == explode('/',$key )[0] ) {
				$slug = explode('/',$key )[0];
			}

		}
		return $slug;
	}

	public static function life_time_banner_should_shown()
	{

//		$slug = "webappick-product-feed-for-woocommerce-pro";
		$slug = self :: get_slugname();

		$lifeTimeProductIds = array( 63687, 63686, 63685, 106128, 106132, 106133 );

		$key = md5( $slug );

		$option_key = 'WebAppick_' . $key . '_manage_license';

		$license_data = get_option( $option_key );

		if ( !empty( $license_data ) and is_array( $license_data ) ) {

			if (strtolower( $license_data['status'] ) === 'active' && isset( $license_data['product_id'] ) && in_array( $license_data['product_id'], $lifeTimeProductIds ) ) {

				$isActive = true;

			} else {

				$isActive = false;

			}

		} else {

			$isActive = false;

		}

		return $isActive;
	}

}
