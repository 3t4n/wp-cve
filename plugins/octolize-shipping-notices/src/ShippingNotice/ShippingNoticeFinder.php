<?php
/**
 * Class ShippingNoticeFinder
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Exception;
use Octolize\Shipping\Notices\Model\ShippingNotice;
use Octolize\Shipping\Notices\Repository\ShippingNoticeRepository;
use Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter\NoticeFilter;
use Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter\NoticeLocationFilter;
use Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter\NoticePostCodeFilter;
use Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter\NoticeRegionFilter;
use Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter\NoticeStatusFilter;

/**
 * Shipping Notice Finder.
 */
class ShippingNoticeFinder {

	/**
	 * @var ShippingNoticeRepository
	 */
	private $shipping_notice_repository;

	/**
	 * @param ShippingNoticeRepository $shipping_notice_repository
	 */
	public function __construct( ShippingNoticeRepository $shipping_notice_repository ) {
		$this->shipping_notice_repository = $shipping_notice_repository;
	}

	/**
	 * @param string $country_code .
	 * @param string $state_code   .
	 * @param string $location     .
	 * @param string $post_code    .
	 *
	 * @return ShippingNotice
	 * @throws Exception
	 */
	public function find_message( string $country_code, string $state_code, string $post_code, string $location ): ShippingNotice {
		$notices = $this->shipping_notice_repository->get_all();

		foreach ( $this->get_filters( $location, $country_code, $state_code, $post_code ) as $filter ) {
			$notices = $filter->get_filtered_notices( $notices );
		}

		/** @var ShippingNotice[] $notices */
		$notices = array_values( $notices );

		if ( ! empty( $notices ) ) {
			return $notices[0];
		}

		throw new Exception( __( 'Shipping notice not found', 'octolize-shipping-notices' ) );
	}

	/**
	 * @param string $location     .
	 * @param string $country_code .
	 * @param string $state_code   .
	 * @param string $post_code    .
	 *
	 * @return NoticeFilter[]
	 * @codeCoverageIgnore
	 */
	protected function get_filters( string $location, string $country_code, string $state_code, string $post_code ): array {
		return [
			new NoticeStatusFilter(),
			new NoticeLocationFilter( $location ),
			new NoticeRegionFilter( $country_code, $state_code ),
			new NoticePostCodeFilter( $country_code, $post_code ),
		];
	}
}
