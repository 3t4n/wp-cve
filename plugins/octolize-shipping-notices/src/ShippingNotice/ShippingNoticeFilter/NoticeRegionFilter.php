<?php
/**
 * Class NoticeRegionFilter
 */

namespace Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter;

use Octolize\Shipping\Notices\Model\Continent;
use Octolize\Shipping\Notices\Model\Country;
use Octolize\Shipping\Notices\Model\Region;
use Octolize\Shipping\Notices\Model\ShippingNotice;
use Octolize\Shipping\Notices\Model\State;
use Octolize\Shipping\Notices\Model\World;

/**
 * Shipping Notice Finder.
 */
class NoticeRegionFilter implements NoticeFilter {

	/**
	 * @var string
	 */
	private $state_code;

	/**
	 * @var string
	 */
	private $country_code;

	/**
	 * @param string $country_code .
	 * @param string $state_code   .
	 */
	public function __construct( string $country_code, string $state_code ) {
		$this->state_code   = $state_code;
		$this->country_code = $country_code;
	}

	/**
	 * @param ShippingNotice[] $notices .
	 *
	 * @return ShippingNotice[]
	 */
	public function get_filtered_notices( array $notices ): array {
		return array_filter(
			$notices,
			function ( ShippingNotice $notice ) {
				return $this->is_notice_matched( $notice->get_regions(), $this->country_code, $this->state_code );
			}
		);
	}

	/**
	 * @param Region[] $regions      .
	 * @param string   $country_code .
	 * @param string   $state_code   .
	 *
	 * @return bool
	 */
	private function is_notice_matched( array $regions, string $country_code, string $state_code ): bool {
		$regions = array_filter(
			$regions,
			function ( Region $region ) use ( $country_code, $state_code ) {
				return $this->is_region_matched( $region, $country_code, $state_code );
			}
		);

		return ! empty( $regions );
	}

	/**
	 * @param Region $region
	 * @param string $country_code
	 * @param string $state_code
	 *
	 * @return bool
	 */
	private function is_region_matched( Region $region, string $country_code, string $state_code ): bool {
		if ( $region instanceof Continent ) {
			$countries = array_filter(
				$region->get_countries(),
				// @phpstan-ignore-next-line
				static function ( Country $country ) use ( $country_code ): bool {
					return $country->get_code() === $country_code;
				}
			);

			return ! empty( $countries );
		}

		if ( $region instanceof Country ) {
			return $region->get_code() === $country_code;
		}

		if ( $region instanceof State ) {
			return $region->get_code() === $state_code;
		}

		return $region instanceof World;
	}
}
