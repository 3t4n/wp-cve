<?php
/**
 * Class NoticeStatusFilter
 */

namespace Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter;

use Octolize\Shipping\Notices\Model\ShippingNotice;

/**
 * Shipping Notice Finder.
 */
class NoticeStatusFilter implements NoticeFilter {
	/**
	 * @param ShippingNotice[] $notices .
	 *
	 * @return ShippingNotice[]
	 */
	public function get_filtered_notices( array $notices ): array {
		return array_filter(
			$notices,
			function ( ShippingNotice $notice ) {
				return $notice->is_enabled();
			}
		);
	}
}
