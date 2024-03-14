<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Customer\Guest\GuestInOrderContextTrait;
use WPDesk\ShopMagic\Workflow\Queue\Queue;

/**
 * On plugin activation allows to sweep through existing orders and extract guest data.
 * This class needs to keep in sync with previous GuestBackgroundConverter which was deleted
 * along with commit which introduced BackgroundOrderInterceptor. For any historical references
 * simply seek for commit after 4fadb0f7
 */
class BackgroundOrderInterceptor implements HookProvider, LoggerAwareInterface {
	use HookTrait;
	use LoggerAwareTrait;
	use GuestInOrderContextTrait;

	private const HOOK_CONVERT_ORDER_PAGE      = 'shopmagic/core/guest/convert_order_page';
	private const CONVERSION_MUTEX_OPTION_NAME = 'shopmagic_guest_conversion';
	private const LIMIT                        = 10;

	/** @var GuestInterceptor */
	private $interceptor;

	/** @var Queue */
	private $queue;

	/** @var bool */
	private $initialized = false;

	public function __construct(
		GuestInterceptor $interceptor,
		Queue $queue
	) {
		$this->interceptor = $interceptor;
		$this->queue       = $queue;
	}

	public function hooks(): void {
		$this->initialized = true;
		$this->add_action( self::HOOK_CONVERT_ORDER_PAGE, [ $this, 'convert_orders' ] );
	}

	/**
	 * @param int $page
	 *
	 * @return \WC_Abstract_Order[]
	 * @throws \Exception
	 */
	private function get_orders( int $page ): array {
		$query  = new \WC_Order_Query(
			[
				'limit'    => self::LIMIT,
				'page'     => $page,
				'paginate' => true,
				'order'    => 'ASC',
				'orderby'  => 'date',
			]
		);
		/** @var \stdClass $orders */
		$orders = $query->get_orders();

		return $orders->orders;
	}

	private function convert_orders( int $page ): void {
		foreach ( $this->get_orders( $page ) as $order ) {
			if ( ! $this->order_has_guest( $order ) ) {
				continue;
			}

			// We need WC_Order methods to query customer data
			if ( ! $order instanceof \WC_Order ) {
				continue;
			}

			$this->logger->debug(
				'Trying to extract guest from order #{id}',
				[ 'id' => $order->get_id() ]
			);
			try {
				$guest = $this->interceptor->intercept( $order );
				$this->touch_order( $order, $guest->get_raw_id() );

				$this->logger->debug(
					'Successfully extracted guest #{guest} from order #{order_id}',
					[
						'guest'    => $guest->get_id(),
						'order_id' => $order->get_id(),
					]
				);
			} catch ( \InvalidArgumentException|InterceptionFailure $e ) {
				$this->logger->error(
					'Guest extraction for order #{order_id} was impossible. Reason: {reason}',
					[
						'reason'   => $e->getMessage(),
						'order_id' => $order->get_id(),
					]
				);
			} catch ( \Throwable $e ) {
				$this->logger->error(
					'Guest extraction for order #{order_id} failed. Direct reason: {reason}',
					[
						'reason'   => $e->getMessage(),
						'order_id' => $order->get_id(),
					]
				);
			}
		}

		if ( count( $this->get_orders( $page ) ) > 0 ) {
			$this->queue->add(
				self::HOOK_CONVERT_ORDER_PAGE,
				[ 'page' => ++ $page ]
			);
		}
	}

	/**
	 * Extract guests from orders if run first time.
	 */
	public function start_guest_extraction_if_needed(): void {
		if ( ! $this->initialized ) {
			$this->logger->warning(
				'`WPDesk\ShopMagic\Customer\Guest\Interceptor\BackgroundOrderInterceptor` class must be initialized first.'
			);

			return;
		}

		$time = microtime( true );
		if ( ! get_option( self::CONVERSION_MUTEX_OPTION_NAME ) ) {
			update_option( self::CONVERSION_MUTEX_OPTION_NAME, $time, true );
			if ( get_option( self::CONVERSION_MUTEX_OPTION_NAME ) === $time ) {
				$this->queue->add(
					self::HOOK_CONVERT_ORDER_PAGE,
					[ 'page' => 0 ]
				);
			}
		}
	}

}
