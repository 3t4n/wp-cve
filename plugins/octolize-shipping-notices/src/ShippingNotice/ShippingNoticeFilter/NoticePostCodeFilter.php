<?php
/**
 * Class NoticePostCodeFilter
 */

namespace Octolize\Shipping\Notices\ShippingNotice\ShippingNoticeFilter;

use Octolize\Shipping\Notices\Model\ShippingNotice;
use stdClass;

/**
 * Shipping Notice Finder.
 */
class NoticePostCodeFilter implements NoticeFilter {

	/**
	 * @var string
	 */
	private $post_code;

	/**
	 * @var string
	 */
	private $country_code;

	/**
	 * @param string $country_code .
	 * @param string $post_code    .
	 */
	public function __construct( string $country_code, string $post_code ) {
		$this->post_code    = $post_code;
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
				return empty( $notice->get_post_codes() ) || $this->is_post_code_matched( $notice->get_post_codes(), $this->post_code, $this->country_code );
			}
		);
	}

	/**
	 * @param string[] $post_codes   .
	 * @param string   $post_code    .
	 * @param string   $country_code .
	 *
	 * @return bool
	 */
	private function is_post_code_matched( array $post_codes, string $post_code, string $country_code ): bool {
		$matches = wc_postcode_location_matcher(
			$post_code,
			$this->get_post_codes( $post_codes ),
			'id',
			'post_code',
			$country_code
		);

		return ! empty( $matches );
	}

	/**
	 * @param string[] $post_codes .
	 *
	 * @return stdClass[]
	 */
	private function get_post_codes( array $post_codes ): array {
		$post_codes_data = [];

		foreach ( $post_codes as $post_code ) {
			$post_codes_data[] = (object) [
				'id'        => $post_code,
				'post_code' => $post_code,
			];
		}

		return $post_codes_data;
	}
}
