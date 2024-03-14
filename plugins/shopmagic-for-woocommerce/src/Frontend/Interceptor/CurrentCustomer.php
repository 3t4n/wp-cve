<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Frontend\Interceptor;

use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\Psr\Log\NullLogger;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerProvider;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\Guest\GuestDataAccess;
use WPDesk\ShopMagic\Customer\Guest\GuestFactory;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Helper\RestRequestUtil;
use WPDesk\ShopMagic\Helper\WooCommerceCookies;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use function WC;

final class CurrentCustomer implements Hookable, CustomerProvider, Conditional {
	use LoggerAwareTrait;

	/** @var string */
	private const SESSION_TRACKING_DATA_KEY = 'shopmagic_customer_token';
	/** @var string */
	private const META = 'meta';
	/** @var string */
	private const USER_ID = 'user_id';
	/** @var string */
	private const EMAIL = 'email';

	/** @var GuestDataAccess */
	private $guest_manager;

	/** @var GuestFactory */
	private $guest_factory;

	/** @var string */
	private $cookie_name;

	/** @var int */
	private $days_to_expire_cookie;

	/** @var array{user_id?: numeric-string, hash: string, email?: string, meta: string[]} */
	private $tracking_data = [];

	/** @var CustomerRepository */
	private $customer_repository;

	public function __construct(
		CustomerRepository $customer_repository,
		GuestDataAccess $guest_manager,
		GuestFactory $guest_factory,
		?LoggerInterface $logger = null
	) {
		$this->customer_repository = $customer_repository;
		$this->guest_manager       = $guest_manager;
		$this->guest_factory       = $guest_factory;
		$this->logger              = $logger ?? new NullLogger();

		if ( ! self::is_needed() ) {
			return;
		}

		$this->cookie_name =
			/**
			 * Cookie name for customer tracking. Used to properly identify the same guest users.
			 *
			 * @param string $cookie_name Current cookie name. shopmagic_visitor_HASH by default.
			 *
			 * @return string New cookie name.
			 * @sice 2.17
			 */
			apply_filters( 'shopmagic/core/customer_interceptor/cookie_name', 'shopmagic_visitor_' . COOKIEHASH );
		$this->days_to_expire_cookie =
			/**
			 * The expiration time for customer tracking cookie.
			 *
			 * @param int $cookie_expiry Current cookie expiration. 365 by default.
			 *
			 * @return int New expiration time.
			 * @sice 2.17
			 * @see  shopmagic/core/customer_interceptor/cookie_name
			 */
			apply_filters( 'shopmagic/core/customer_interceptor/cookie_expiry', 365 );

		$this->tracking_data = $this->get_decoded_tracking_data();
		$this->refresh_tracking_user_id();
	}

	public static function is_needed(): bool {
		$enabled = WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) &&
					filter_var( GeneralSettings::get_option( 'enable_session_tracking', true ), \FILTER_VALIDATE_BOOLEAN );

		if ( is_admin() && ! wp_doing_ajax() ) {
			$request_valid = false;
		} elseif ( RestRequestUtil::is_rest_request() ) {
			$request_valid = false;
		} elseif ( wp_doing_cron() ) {
			$request_valid = false;
		} else {
			$request_valid = true;
		}

		return $enabled && $request_valid;
	}

	/**
	 * @return array{user_id?: numeric-string, email?: string, meta: string[]}
	 */
	private function get_decoded_tracking_data(): array {
		$data = json_decode( $this->get_raw_tracking_data(), true );
		if ( ! \is_array( $data ) ) {
			$data = [ self::META => [] ];
		}

		unset( $data['hash'] );

		return $data;
	}

	private function get_raw_tracking_data(): string {
		if ( function_exists( 'WC' ) && WC()->session ) {
			$raw_data = WC()->session->get( self::SESSION_TRACKING_DATA_KEY );
			if ( ! empty( $raw_data ) ) {
				return $raw_data;
			}
		} else {
			$this->logger->warning( 'Failed to retrieve customer tracking data. WC session not found.' );
		}

		if ( $this->is_enabled_cookie() ) {
			$raw_data = WooCommerceCookies::get( $this->cookie_name );
			if ( ! empty( $raw_data ) ) {
				return $raw_data;
			}
		}

		return '';
	}

	private function is_enabled_cookie(): bool {
		/**
		 * Can be used to globally override cookie usage. When disabled no cookies will ever be created
		 * by the ShopMagic plugins.
		 *
		 * @param bool $enabled Current value. True by default.
		 *
		 * @returns bool
		 * @since 2.17
		 */
		return apply_filters( 'shopmagic/core/customer_interceptor/cookies_enabled', true );
	}

	/**
	 * @internal
	 */
	public function refresh_tracking_user_id(): void {
		if ( is_user_logged_in() ) {
			$this->set_user_id( (int) wp_get_current_user()->ID );
		}
	}

	private function set_user_id( int $user_id ): void {
		$this->tracking_data[ self::USER_ID ] = $user_id;
		/**
		 * @ignore Action used to internally sync customer status with other interceptors.
		 */
		do_action( 'shopmagic/core/customer_interceptor/changed', $this->tracking_data );
	}

	public function set_meta( string $meta_name, string $meta_value ): void {
		$this->tracking_data[ self::META ][ $meta_name ] = $meta_value;
	}

	public function is_customer_provided(): bool {
		try {
			return $this->get_customer() instanceof Customer;
		} catch ( CannotProvideCustomerException $e ) {
			return false;
		}
	}

	/**
	 * @return Customer
	 * @throws CannotProvideCustomerException
	 */
	public function get_customer(): Customer {
		if ( is_user_logged_in() ) {
			return $this->customer_repository->fetch_user( new \WP_User( get_current_user_id() ) );
		}

		if ( isset( $this->tracking_data[ self::USER_ID ] ) ) {
			$user = get_user_by( 'id', $this->tracking_data[ self::USER_ID ] );
			if ( $user instanceof \WP_User ) {
				return $this->customer_repository->fetch_user( $user );
			}
		}

		if ( isset( $this->tracking_data[ self::EMAIL ] ) ) {
			try {
				// @todo: save metadata each time we intercept some customer, even known one
				return $this->customer_repository->find_by_email( $this->tracking_data[ self::EMAIL ] );
			} catch ( CustomerNotFound $e ) {
				$guest = $this->guest_factory->from_email( $this->tracking_data[ self::EMAIL ] );
				foreach ( $this->tracking_data[ self::META ] as $meta_key => $meta_value ) {
					$guest->add_meta( $meta_key, $meta_value );
				}

				if ( ( \is_array( $this->tracking_data[ self::META ] ) || $this->tracking_data[ self::META ] instanceof \Countable ? \count( $this->tracking_data[ self::META ] ) : 0 ) > 0 ) {
					$this->guest_manager->save( $guest );
				}

				return $guest;
			}
		}

		throw new CannotProvideCustomerException( 'Customer is not available' );
	}

	public function hooks(): void {
		if ( ! $this->is_enabled_session_tracking() ) {
			return;
		}

		add_action(
			'wp',
			function () {
				$this->remember_tracking_key();
			},
			99
		);
		add_action(
			'shutdown',
			function () {
				$this->remember_tracking_key();
			},
			0
		);
		add_action(
			'set_logged_in_cookie',
			function () {
				$this->refresh_tracking_user_id();
			}
		);
		add_action(
			'comment_post',
			function ( int $comment_id ) {
				$this->capture_from_comment( $comment_id );
			}
		);
		add_action(
			'woocommerce_new_order',
			function ( $order_id, $order = null ) {
				$this->capture_from_order( $order_id, $order );
			},
			10,
			2
		);
		add_action(
			'woocommerce_api_create_order',
			function ( $order_id, $order = null ) {
				$this->capture_from_order( $order_id, $order );
			},
			10,
			2
		);
	}

	private function is_enabled_session_tracking(): bool {
		return filter_var(
			GeneralSettings::get_option( 'enable_session_tracking', true ),
			\FILTER_VALIDATE_BOOLEAN
		);
	}

	/**
	 * @internal
	 */
	public function remember_tracking_key(): void {
		$encoded_tracking_data = $this->encode_tracking_data();
		if ( WC()->session ) {
			WC()->session->set( self::SESSION_TRACKING_DATA_KEY, $encoded_tracking_data );
		}
		if ( $this->can_save_cookie( $this->cookie_name, $encoded_tracking_data ) ) {
			WooCommerceCookies::set(
				$this->cookie_name,
				$encoded_tracking_data,
				time() + $this->days_to_expire_cookie * DAY_IN_SECONDS
			);
		}
	}

	private function encode_tracking_data(): string {
		if ( defined( 'AUTH_SALT' ) ) {
			$salt = AUTH_SALT;
		} else {
			try {
				// We never decode this data yet,
				// then use totally random sequence during write
				$salt = random_bytes( 12 );
			} catch ( \Exception $e ) {
				$salt = '';
			}
		}
		$hash         = md5( json_encode( $this->tracking_data ) . $salt );
		$data         = $this->tracking_data;
		$data['hash'] = $hash;

		return json_encode( $data );
	}

	private function can_save_cookie( string $name, $data ): bool {
		if ( headers_sent() ) {
			return false;
		}

		if ( ! $this->is_enabled_cookie() ) {
			return false;
		}

		if ( empty( $this->tracking_data['email'] ) && empty( $this->tracking_data['user_id'] ) ) {
			return false;
		}

		if ( WooCommerceCookies::is_set( $name ) && WooCommerceCookies::get( $name ) === $data ) {
			return false;
		}

		return true;
	}

	/**
	 * @internal
	 */
	public function capture_from_comment( int $comment_id ): void {
		if ( is_user_logged_in() ) {
			return;
		}

		$comment = get_comment( $comment_id );
		if ( ! $comment ) {
			return;
		}
		if ( $comment->user_id ) {
			return;
		}
		$this->set_user_email( $comment->comment_author_email );
	}

	public function set_user_email( string $email ): void {
		$this->tracking_data[ self::EMAIL ] = $email;
		/**
		 * @ignore Action used to internally sync customer status with other interceptors.
		 */
		do_action( 'shopmagic/core/customer_interceptor/changed', $this->tracking_data );
	}

	public function capture_from_order( $order_id, $order = null ): void {
		if ( is_user_logged_in() ) {
			return;
		}

		if ( ! $order instanceof \WC_Abstract_Order ) {
			$order = wc_get_order( $order_id );
		}

		$this->set_user_email( $order->get_billing_email() );
	}
}
