<?php

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

/**
 * Api Class
 */
if ( ! class_exists( 'Api' ) ) {
	class Api {

		/**
		 * All API Classes
		 *
		 * @var array
		 */
		protected array $classes;

		/**
		 * Initialize
		 */
		public function __construct() {
			$this->classes = [
				Api\Cart::class,
				Api\Search::class,
				Admin\Api::class,
				Api\Post::class,
				Api\Product::class,
				Api\User::class,
				Api\Comment::class,
				Api\Review::class,
				Api\Setting::class,
				Api\Customer::class,
				Api\Country::class,
				Api\Delivery::class,
				Api\Captcha::class,
				Api\Checkout::class,

				Plugin\WooCommerceBooking::class,
			];

			if ( class_exists( 'WooCommerce' ) ) {
				$this->classes[] = AdvancedRestApi\Product::class;
			}

			if ( defined( 'WCFMapi_TEXT_DOMAIN' ) ) {
				$this->classes[] = AdvancedRestApi\Order::class;
			}

			add_action( 'rest_api_init', array( $this, 'init_api' ) );

			$auth = new Api\Auth();
			add_action( 'rest_api_init', array( $auth, 'register_routes' ) );
			add_filter( 'determine_current_user', array( $auth, 'cookie_determine_current_user' ) );
		}

		/**
		 * Register APIs
		 *
		 * @return void
		 */
		public function init_api() {
			foreach ( $this->classes as $class ) {
				$object = new $class();
				$object->register_routes();
			}

			/**
			 * Register vendor API
			 * @since 1.0.13
			 */
			$vendor = false;

			if ( Utils::vendorActive() == 'dokan' ) {
				$vendor = Vendor\DokanStore::class;
			} else if ( Utils::vendorActive() == 'wcfm' ) {
				$vendor = Vendor\WCFMStore::class;
			} else if ( Utils::vendorActive() == 'wcmp' ) {
				$vendor = Vendor\WCMpStore::class;
			}

			if ( $vendor ) {
				$object = new $vendor();
				$object->register_routes();
			}

			/**
			 * Register Learning API
			 */

			$learning = false;

			/**
			 * Enable support if Master Study active
			 */
			if ( class_exists( 'STM_LMS_Reviews' ) ) {
				$learning = Lms\MasterStudy\Main::class;
			}

			if ( $learning ) {
				new $learning();
			}
		}
	}
}
