<?php
namespace QuadLayers\IGG;

/**
 * Helpers
 */
class Helpers {

	/**
	 * Personal client id
	 *
	 * @var integer
	 */
	protected static $personal_client_id = 504270170253170;
	/**
	 * Personal QuadLayers redirect URL
	 *
	 * @var string
	 */
	public static $personal_redirect_url = 'https://socialfeed.quadlayers.com/instagram.php';

	/**
	 * Business client id
	 *
	 * @var integer
	 */
	protected static $business_client_id = 834353156975525;
	/**
	 * Business QuadLayers redirect URL
	 *
	 * @var string
	 */
	public static $business_redirect_uri = 'https://socialfeed.quadlayers.com/facebook.php';

	/**
	 * Function to get personal access_token_link
	 *
	 * @return string
	 */
	public static function get_personal_access_token_link() {
		$state                 = admin_url( 'admin.php' );
		$scope                 = 'user_profile,user_media';
		$personal_client_id    = self::$personal_client_id;
		$personal_redirect_url = self::$personal_redirect_url;
		return "https://www.instagram.com/oauth/authorize?app_id={$personal_client_id}&redirect_uri={$personal_redirect_url}&response_type=code&scope={$scope}&state={$state}";
	}

	/**
	 * Function to get business access_token_link
	 *
	 * @return string
	 */
	public static function get_business_access_token_link() {
		$state                 = admin_url( 'admin.php' );
		$scope                 = 'pages_show_list,instagram_basic,instagram_manage_comments,instagram_manage_insights,pages_read_engagement,business_management';
		$business_client_id    = self::$business_client_id;
		$business_redirect_uri = self::$business_redirect_uri;
		return "https://www.facebook.com/dialog/oauth?client_id={$business_client_id}&redirect_uri={$business_redirect_uri}&response_type=code&scope={$scope}&state={$state}";
	}

	/**
	 * Function to reduce array
	 *
	 * @param array    $array Array to be reduced.
	 * @param callable $callback Function to pass as callback.
	 * @param [type]   $carry Acumulator.
	 * @return [type]  $carry
	 */
	public static function array_reduce( array $array, callable $callback, $carry = null ) {
		foreach ( $array as $key => $value ) {
			$carry = $callback( $carry, $key, $value, $array );
		}
		return $carry;
	}
}
