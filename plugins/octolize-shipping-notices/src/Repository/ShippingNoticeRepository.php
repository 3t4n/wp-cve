<?php
/**
 * Class ShippingNoticeRepository
 */

namespace Octolize\Shipping\Notices\Repository;

use Octolize\Shipping\Notices\Model\Region;
use Octolize\Shipping\Notices\Model\ShippingNotice;
use Octolize\Shipping\Notices\CustomPostType;
use Octolize\Shipping\Notices\ShippingNotice\RegionFactory;
use Octolize\Shipping\Notices\ShippingNotice\SingleNoticeOption;
use Octolize\Shipping\Notices\WooCommerceSettings\SingleSectionSettingsFields;
use WP_Query;

/**
 * Shipping notices repository.
 */
class ShippingNoticeRepository {

	/**
	 * @var SingleNoticeOption
	 */
	private $single_notice_option;

	/**
	 * @var RegionFactory
	 */
	private $region_factory;

	/**
	 * @param SingleNoticeOption $single_notice_option .
	 */
	public function __construct( SingleNoticeOption $single_notice_option, RegionFactory $region_factory ) {
		$this->region_factory       = $region_factory;
		$this->single_notice_option = $single_notice_option;
	}

	/**
	 * @return ShippingNotice[]
	 */
	public function get_all(): array {
		$notices = [];

		foreach ( $this->get_notice_ids( $this->get_args() ) as $notice_id ) {
			$notice_options = $this->single_notice_option->get_single_notice_options( $notice_id );

			$notices[ $notice_id ] = $this->get_single_shipping_notice(
				$notice_id,
				( $notice_options[ SingleSectionSettingsFields::ENABLED_FIELD ] ?? 'no' ) === 'yes',
				$notice_options[ SingleSectionSettingsFields::TITLE_FIELD ] ?? __( 'Shipping Notice Name', 'octolize-shipping-notices' ),
				$this->region_factory->get_regions( (array) ( $notice_options[ SingleSectionSettingsFields::REGIONS_FIELD ] ?? [] ) ),
				$notice_options[ SingleSectionSettingsFields::MESSAGE_FIELD ] ?? '',
				wp_parse_list( $notice_options[ SingleSectionSettingsFields::LOCATIONS_FIELD ] ?? [] ),
				wp_parse_list( $notice_options[ SingleSectionSettingsFields::POST_CODES_FIELD ] ?? [] )
			);
		}

		return $notices;
	}

	/**
	 * @param int      $id         .
	 * @param bool     $enabled    .
	 * @param string   $title      .
	 * @param Region[] $regions    .
	 * @param string   $message    .
	 * @param string[] $locations  .
	 * @param string[] $post_codes .
	 *
	 * @return ShippingNotice
	 * @codeCoverageIgnore
	 */
	protected function get_single_shipping_notice( int $id, bool $enabled, string $title, array $regions, string $message, array $locations, array $post_codes ): ShippingNotice {
		return new ShippingNotice( $id, $enabled, $title, $regions, $message, $locations, $post_codes );
	}

	/**
	 * @param array<string, string|bool> $args .
	 *
	 * @return int[]
	 * @codeCoverageIgnore
	 */
	protected function get_notice_ids( array $args ): array {
		// @phpstan-ignore-next-line
		return ( new WP_Query( $args ) )->posts;
	}

	/**
	 * @return array<string, string|bool>
	 */
	private function get_args(): array {
		return [
			'post_type'   => CustomPostType::POST_TYPE,
			'nopaging'    => true,
			'post_status' => 'any',
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
			'fields'      => 'ids',
		];
	}
}
