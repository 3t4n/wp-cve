<?php
/**
 * Class NoticeLocationFilter
 */

namespace Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter;

use Octolize\Shipping\Notices\Model\ShippingNotice;

/**
 * Filters by location.
 */
class NoticeLocationFilter implements NoticeFilter {

	/**
	 * @var string
	 */
	private $location;

	/**
	 * @param string $location .
	 */
	public function __construct( string $location ) {
		$this->location = $location;
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
				return in_array( $this->location, $notice->get_locations(), true );
			}
		);
	}
}
