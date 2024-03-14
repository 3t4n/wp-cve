<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Forms\Field\InputNumberField;
use ShopMagicVendor\WPDesk\Forms\Field\TimepickerField;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;


/**
 * Helper class for before events.
 */
final class BeforeEventHelper {

	/** @var string */
	private $param_days_before;

	/** @var string */
	private $param_checktime;

	public function __construct( string $param_name_days_before, string $param_name_checktime ) {
		$this->param_checktime   = $param_name_checktime;
		$this->param_days_before = $param_name_days_before;
	}

	/** @return \ShopMagicVendor\WPDesk\Forms\Field[] */
	public function get_fields( string $days_before_label ): array {
		$fields = [];

		$fields[] = ( new InputNumberField() )
			->set_label( $days_before_label )
			->set_attribute( 'type', 'number' )
			->set_required()
			->set_name( $this->param_days_before );

		$fields[] = ( new TimepickerField() )
			->set_label( __( 'Time of the day', 'shopmagic-for-woocommerce' ) )
			->set_name( $this->param_checktime )
			->set_description( __( '(24 hour time)', 'shopmagic-for-woocommerce' ) )
			->set_description_tip(
				__(
					'This automation will run daily in the background at the time set in the "Time of the day" field. If no time is set it will run at 4:00 in the morning.',
					'shopmagic-for-woocommerce'
				)
			);

		return $fields;
	}

	/**
	 * Returns time of the day to check for memberships.
	 *
	 * @param array{int, int} $default [ $hour, $minute ]
	 *
	 * @return int Returns seconds from 00:00 today to the given hour. UTC timezone.
	 */
	public function get_checktime_utc( ContainerInterface $fields_data, array $default ): int {
		if ( $fields_data->has( $this->param_checktime ) ) {
			$check_time = json_decode( $fields_data->get( $this->param_checktime ), true );
		} else {
			$check_time = $default;
		}

		if ( empty( $check_time ) ) {
			$check_time = $default;
		}

		return $this->convert_time_to_utc_int( (int) $check_time[0], (int) $check_time[1] );
	}

	private function convert_time_to_utc_int( int $hour, int $minute ): int {
		$datetime_with_hour = ( new \DateTime( 'today midnight', WordPressFormatHelper::get_wp_timezone() ) )->setTime( $hour, $minute );

		$datetime_utc = ( new \DateTime( 'now', new \DateTimeZone( 'UTC' ) ) )->setTimestamp( $datetime_with_hour->getTimestamp() );

		return $datetime_utc->format( 'G' ) * 60 * 60 + $datetime_utc->format( 'i' ) * 60;
	}

	public function get_days_before_end_as_today_date( ContainerInterface $fields_data ): \DateTimeImmutable {
		$days_before = absint( $fields_data->get( $this->param_days_before ) );
		$date        = new \DateTimeImmutable( 'now', WordPressFormatHelper::get_wp_timezone() );

		return $date->modify( sprintf( '+%s days', $days_before ) );
	}

}
