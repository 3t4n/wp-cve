<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\HookEmitter;

use ShopMagicVendor\WPDesk\Forms\Field\CheckboxField;
use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;

/**
 * Removes outcomes from database.
 */
final class OutcomeCleaner extends RecurringCleaner implements Conditional {
	public static function is_needed(): bool {
		return \filter_var( GeneralSettings::get_option( GeneralSettings::OUTCOMES_PURGE ), \FILTER_VALIDATE_BOOLEAN );
	}

	protected function get_items_to_clean(): iterable {
		$cut_time = ( new \DateTimeImmutable( 'now', wp_timezone() ) )
			->modify( RecurringCleaner::DEFAULT_EXPIRATION_TIME );

		return $this->persister->get_repository()->find_by(
			[
				[
					'field'     => 'updated',
					'condition' => '<=',
					'value'     => WordPressFormatHelper::datetime_as_mysql( $cut_time ),
				],
			]
		);
	}
}
