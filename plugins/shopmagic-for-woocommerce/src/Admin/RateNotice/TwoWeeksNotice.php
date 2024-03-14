<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\RateNotice;

use ShopMagicVendor\WPDesk\Notice\Notice;
use ShopMagicVendor\WPDesk\Notice\PermanentDismissibleNotice;
use ShopMagicVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer;
use ShopMagicVendor\WPDesk\ShowDecision\ShouldShowStrategy;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Validator\FullyConfiguredValidator;


/**
 * Two weeks notice defined in issue #90.
 */
final class TwoWeeksNotice {
	/**
	 * @var string
	 */
	public const NOTICE_NAME = 'shopmagic_two_week_rate_notice';

	/**
	 * @var string
	 */
	public const CLOSE_TEMPORARY_NOTICE = 'close-temporary-notice-date';

	/**
	 * @var string
	 */
	public const OPTION_NAME_WITH_ACTIVATED_DATE_FROM_HELPER = 'plugin_activation_shopmagic-for-woocommerce/shopMagic.php';

	/**
	 * @var string
	 */
	public const PERSISTENT_KEY_NEVER_SHOW_AGAIN = 'two-weeks-permanent';

	/**
	 * @var string
	 */
	public const PERSISTENT_KEY_LAST_TIME_HIDDEN = 'two-weeks-last-date';

	/** @var string */
	private $assets_url;

	/** @var \ShopMagicVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer */
	private $persistence;

	/** @var Automation[] */
	private $automations = [];

	/**
	 * Current time.
	 *
	 * @var int
	 */
	private $now;

	/** @var ShouldShowStrategy */
	private $show_strategy;

	/**
	 * @param string             $assets_url
	 * @param Automation[]       $automations      Required to check if at least one fully
	 *                                             configured automation exists.
	 * @param ShouldShowStrategy $show_strategy    Show on the pages in which Beacon is visible.
	 * @param int|null           $now              Current time in unix.
	 */
	public function __construct(
		$assets_url,
		array $automations,
		ShouldShowStrategy $show_strategy,
		$now = null
	) {
		$this->assets_url = $assets_url;
		if ( $now === null ) {
			$this->now = time();
		} else {
			$this->now = $now;
		}

		$this->automations   = $automations;
		$this->show_strategy = $show_strategy;
		$this->persistence   = new WordpressOptionsContainer( 'shopmagic-notice' );
	}

	public function hooks(): void {
		add_action(
			'admin_enqueue_scripts',
			function (): void {
				wp_enqueue_script( 'shopmagic-rate-notice', $this->assets_url . '/js/two-weeks-notice.js', [], SHOPMAGIC_VERSION, true );
			}
		);
		add_action(
			'wp_ajax_shopmagic_close_temporary',
			function (): void {
				if ( $this->persistence->has( 'two-weeks-last-date' ) ) {
					$this->persistence->set( self::PERSISTENT_KEY_NEVER_SHOW_AGAIN, true );
				} else {
					$this->persistence->set( self::PERSISTENT_KEY_LAST_TIME_HIDDEN, time() );
				}
			}
		);
	}

	/**
	 * Action links
	 *
	 * @return string[]
	 */
	private function action_links(): array {
		$actions   = [];
		$actions[] = sprintf(
			'<a target="_blank" href="%1$s">%2$s</a>',
			esc_url( 'https://wpde.sk/sm-rate' ),
			esc_html__( 'Ok, you deserved it', 'shopmagic-for-woocommerce' )
		);
		$actions[] = sprintf(
			'<a data-type="date" class="sm-close-temporary-notice" data-source="%1$s" href="#">%2$s</a>',
			self::CLOSE_TEMPORARY_NOTICE,
			esc_html__( 'Nope, maybe later', 'shopmagic-for-woocommerce' )
		);
		$actions[] = sprintf(
			'<a class="close-rate-notice notice-dismiss-link" data-source="already-did" href="#">%s</a>',
			esc_html__( 'I already did', 'shopmagic-for-woocommerce' )
		);

		return $actions;
	}

	/**
	 * Should show message
	 */
	public function should_show_message(): bool {
		if ( time() > strtotime( '2020-04-22' ) ) {
			if ( $this->persistence->has( self::PERSISTENT_KEY_NEVER_SHOW_AGAIN ) ) {
				return false;
			}

			if ( $this->show_strategy->shouldDisplay() ) {
				/** @var string $activation_date */
				$activation_date = get_option( self::OPTION_NAME_WITH_ACTIVATED_DATE_FROM_HELPER );
				$two_weeks       = 60 * 60 * 24 * 7 * 2;

				if ( ! empty( $activation_date ) && strtotime( $activation_date ) + $two_weeks < $this->now ) {
					if ( $this->persistence->has( self::PERSISTENT_KEY_LAST_TIME_HIDDEN ) ) {
						$last_close = (int) $this->persistence->get( self::PERSISTENT_KEY_LAST_TIME_HIDDEN );

						return ! empty( $last_close ) && $last_close + $two_weeks < $this->now && $this->is_fully_configured_automation_exists();
					}

					return $this->is_fully_configured_automation_exists();
				}
			}
		}

		return false;
	}

	private function is_fully_configured_automation_exists(): bool {
		// FIXME This should be injected in constructor.
		$validator = new FullyConfiguredValidator();

		return ! empty(
			array_filter(
				$this->automations,
				static function ( Automation $automation ) use ( $validator ): bool {
					return $validator->valid(
						new DataLayer( [ Automation::class => $automation ] )
					);
				}
			)
		);
	}

	/**
	 * Show admin notice
	 */
	public function show_message(): void {
		new PermanentDismissibleNotice(
			$this->get_message(),
			self::NOTICE_NAME,
			Notice::NOTICE_TYPE_INFO,
			10,
			[
				'class' => self::NOTICE_NAME,
				'id'    => self::NOTICE_NAME,
			]
		);
	}

	/**
	 * Get rate message
	 */
	private function get_message(): string {
		$message = __(
			"Awesome, you've been using ShopMagic for more than 2 weeks. Could you please do me a BIG favor and give it a 5-star rating on WordPress.org? ~ Mac @ ShopMagic Team",
			'shopmagic-for-woocommerce'
		);
		$message .= '<br/>';
		$message .= implode( ' | ', $this->action_links() );

		return $message;
	}

}
