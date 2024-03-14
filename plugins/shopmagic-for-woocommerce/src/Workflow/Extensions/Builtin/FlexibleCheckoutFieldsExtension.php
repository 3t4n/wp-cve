<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Extensions\Builtin;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\FCF\Free\Integration\Integrator;
use WPDesk\ShopMagic\Integration\FlexibleCheckoutFields\Placeholder\OrderCheckoutField;
use WPDesk\ShopMagic\Workflow\Extensions\AbstractExtension;

final class FlexibleCheckoutFieldsExtension extends AbstractExtension {

	/** @var \WPDesk\FCF\Free\Integration\Integrator */
	private $integrator;

	/** @var LoggerInterface */
	private $logger;

	public function __construct( Integrator $integrator, LoggerInterface $logger ) {
		$this->integrator = $integrator;
		$this->logger     = $logger;
	}

	/**
	 * @return \WPDesk\ShopMagic\Integration\FlexibleCheckoutFields\Placeholder\OrderCheckoutField[]
	 */
	public function get_placeholders(): array {
		return [
			new OrderCheckoutField( $this->integrator, $this->logger ),
		];
	}

}
