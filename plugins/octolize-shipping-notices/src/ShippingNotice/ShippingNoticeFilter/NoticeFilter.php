<?php
/**
 * Interface NoticeFilter
 */

namespace Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter;

use Octolize\Shipping\Notices\Model\ShippingNotice;

/**
 * Notice Filter
 */
interface NoticeFilter {
	/**
	 * @param ShippingNotice[] $notices .
	 *
	 * @return ShippingNotice[]
	 */
	public function get_filtered_notices( array $notices ): array;
}
