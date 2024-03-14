<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder;

use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\DataSharing\DataReceiver;
use WPDesk\ShopMagic\DataSharing\Traits\DataReceiverAsProtectedField;
use WPDesk\ShopMagic\DataSharing\Traits\StandardWooCommerceDataProviderAccessors;
use WPDesk\ShopMagic\Workflow\Components\GroupableNamedComponent;
use WPDesk\ShopMagic\Workflow\Components\Groups;

/**
 * Static function are responsible for the info that is required to establish a contract:
 * what should be prepared for this class to successfully instantiate and will the instance be used.
 * We should avoid changes in these static conditions during runtime. If these conditions needs to change then we
 * should refactor the static part to another class. Now it's here to greatly simplify the extending of the class for external devs.
 * er ins
 * Three responsibilities:
 * - Has info how the placeholder should look in admin panel: name, description, parameters to render
 * - DataReceiver.
 * - Receives data for processing placeholder shortcode and processes it.
 */
abstract class Placeholder implements
	DataReceiver,
	GroupableNamedComponent,
	LoggerAwareInterface {
	use DataReceiverAsProtectedField;
	use StandardWooCommerceDataProviderAccessors;
	use LoggerAwareTrait;
	/**
	 * Shortcode for the placeholder. Have to be unique. Can be in any format but
	 * most placeholder should use groupname.name-of-the-placeholder format.
	 * In form input the groupname.name-of-the-placeholder should looks like {{ groupname.name-of-the-placeholder }}
	 */
	abstract public function get_slug(): string;

	/**
	 * Placeholder is a special case where id is the same as full name.
	 * We maintain this behavior because placeholders aren't stored in database with key-based
	 * search purposes. Additionally, placeholder name is always predictable (doesn't require
	 * translation, etc.), thus it's safe to rely on its name.
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->get_name();
	}

	public function get_name(): string {
		return $this->get_group_slug() . '.' . $this->get_slug();
	}

	public function get_group_slug(): string {
		return Groups::class_to_group( $this->get_required_data_domains() );
	}

	/**
	 * @param scalar|array $values Optionally you can pass variables to dynamically set parameters based on events settings.
	 *
	 * @return \ShopMagicVendor\WPDesk\Forms\Field[]
	 */
	public function get_supported_parameters( $values = null ): array {
		return [];
	}

	/**
	 * Placeholder value to replace the shortcode of given name.
	 *
	 * @param string[] $parameters
	 */
	abstract public function value( array $parameters ): string;

}
