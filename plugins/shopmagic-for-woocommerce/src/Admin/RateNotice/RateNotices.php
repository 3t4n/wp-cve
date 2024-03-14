<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin\RateNotice;

/**
 * Manages greetings&marketing notices.
 */
final class RateNotices {

	/** @var \WPDesk\ShopMagic\Admin\RateNotice\TwoWeeksNotice[] */
	private $notices = [];

	/**
	 * @param \WPDesk\ShopMagic\Admin\RateNotice\TwoWeeksNotice[] $notices
	 */
	public function __construct( array $notices ) {
		$this->notices = $notices;
	}

	public function hooks(): void {
		foreach ( $this->notices as $notice ) {
			$notice->hooks();
			add_action(
				'admin_notices',
				static function () use ( $notice ): void {
					if ( $notice->should_show_message() ) {
						$notice->show_message();
					}
				}
			);
		}
	}
}
