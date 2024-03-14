<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\HookEmitter;

use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Emits periodic hooks for tasks that should but not have to run at a given time.
 */
final class CronHeartbeat implements Hookable {
	/** @var int */
	private const TOO_MUCH_INTERVAL = 30;
	/** @var string */
	private const OPTION_NAME_LAST_RUN = 'shopmagic_cron_last_run';
	/** @var string */
	private const INTERVAL = 'interval';
	/** @var string */
	private const DISPLAY = 'display';

	/**
	 * @return array<string, array<string, mixed>>
	 */
	private function get_workers(): array {
		return [
			'shopmagic/core/cron/one_minute'      => [
				self::INTERVAL => 60,
				self::DISPLAY  => __( 'Every minute', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/two_minutes'     => [
				self::INTERVAL => 2 * 60,
				self::DISPLAY  => __( 'Every two minutes', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/five_minutes'    => [
				self::INTERVAL => 5 * 60,
				self::DISPLAY  => __( 'Every five minutes', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/fifteen_minutes' => [
				self::INTERVAL => 15 * 60,
				self::DISPLAY  => __( 'Every fifteen minutes', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/thirty_minutes'  => [
				self::INTERVAL => 30 * 60,
				self::DISPLAY  => __( 'Every thirty minutes', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/hourly'          => [
				self::INTERVAL => 60 * 60,
				self::DISPLAY  => __( 'Every hour', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/four_hours'      => [
				self::INTERVAL => 4 * 60 * 60,
				self::DISPLAY  => __( 'Every for hours', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/daily'           => [
				self::INTERVAL => 86400,
				self::DISPLAY  => __( 'Every day', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/two_days'        => [
				self::INTERVAL => 2 * 86400,
				self::DISPLAY  => __( 'Every two days', 'shopmagic-for-woocommerce' ),
			],
			'shopmagic/core/cron/weekly'          => [
				self::INTERVAL => 7 * 86400,
				self::DISPLAY  => __( 'Every week', 'shopmagic-for-woocommerce' ),
			],
		];
	}

	public function hooks(): void {
		add_filter(
			'cron_schedules',
			function ( array $schedules ): array {
				return $this->prepare_schedules( $schedules );
			},
			100
		);
		foreach ( array_keys( $this->get_workers() ) as $hook ) {
			add_action(
				$hook,
				function () {
					$this->prevent_from_run_too_much();
				},
				1
			);
		}

		add_action(
			'admin_init',
			function () {
				$this->add_cron_events();
			}
		);
	}

	/**
	 * Add cron events.
	 *
	 * @internal
	 */
	public function add_cron_events(): void {
		foreach ( array_keys( $this->get_workers() ) as $hook ) {
			if ( ! wp_next_scheduled( $hook ) ) {
				wp_schedule_event( time(), $hook, $hook );
			}
		}
	}

	/**
	 * Prepare cron schedules to use in wp_schedule_event.
	 *
	 * @param mixed[] $schedules
	 *
	 * @return mixed[]
	 * @internal
	 */
	public function prepare_schedules( array $schedules ): array {
		return array_merge( $schedules, $this->get_workers() );
	}


	/**
	 * Prevents workers from working if they have done so in the past 30 seconds
	 *
	 * @internal
	 */
	public function prevent_from_run_too_much(): void {
		$action = current_action();

		if ( $this->is_worker_locked( $action ) ) {
			remove_all_actions( $action ); // prevent actions from running.

			return;
		}

		@set_time_limit( 300 );

		$this->update_last_run( $action );
	}

	/**
	 * Prevent cron events to run too frequent. Allow only one at a time.
	 */
	private function is_worker_locked( string $action ): bool {
		$time_unlocked = $this->get_last_run( $action )
		                      ->modify( '+' . self::TOO_MUCH_INTERVAL . ' seconds' );

		return $time_unlocked->getTimestamp() > time();
	}

	private function update_last_run( string $action ): void {
		$last_runs = $this->cron_run_last_time();

		if ( $last_runs === [] ) {
			$last_runs = [];
		}

		$last_runs[ $action ] = time();

		update_option( self::OPTION_NAME_LAST_RUN, $last_runs, false );
	}

	/**
	 * @return int[] Keys are action names and values are unix timestamp.
	 */
	private function cron_run_last_time(): array {
		return get_option( self::OPTION_NAME_LAST_RUN, [] );
	}

	private function get_last_run( string $action ): \DateTimeImmutable {
		$last_runs = $this->cron_run_last_time();
		if ( isset( $last_runs[ $action ] ) ) {
			return ( new \DateTimeImmutable() )->setTimestamp( $last_runs[ $action ] );
		}

		return new \DateTimeImmutable( '-100 years' );
	}
}
