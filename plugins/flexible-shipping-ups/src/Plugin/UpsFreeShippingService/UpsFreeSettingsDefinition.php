<?php
/**
 * Class UpsFreeSettingsDefinition
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService;

use UpsFreeVendor\WPDesk\AbstractShipping\Exception\SettingsFieldNotExistsException;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\DatesAndTimesSettingsDefinitionDecorator;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\DeliveryConfirmationSettingsDefinitionDecorator;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\DestinationAddressTypeSettingsDefinitionDecorator;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\EstimatedDeliverySettingsDefinitionDecorator;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\HandlingFeesSettingsDefinitionDecorator;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\PackerPackingMethodSettingsDefinitionDecorator;
use WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field\ProFeaturesSettingsDefinitionDecorator;

class UpsFreeSettingsDefinition extends SettingsDefinition {
	/**
	 * UPS settings definition.
	 *
	 * @var UpsSettingsDefinition
	 */
	private $ups_settings_definition;

	/**
	 * @param SettingsDefinition $ups_settings_definition
	 */
	public function __construct( SettingsDefinition $ups_settings_definition ) {
		$ups_settings_definition = new DestinationAddressTypeSettingsDefinitionDecorator( $ups_settings_definition, 'pickup_type' );
		$ups_settings_definition = new DeliveryConfirmationSettingsDefinitionDecorator( $ups_settings_definition, DestinationAddressTypeSettingsDefinitionDecorator::OPTION_DESTINATION_ADDRESS_TYPE );
		$ups_settings_definition = new HandlingFeesSettingsDefinitionDecorator( $ups_settings_definition, DeliveryConfirmationSettingsDefinitionDecorator::OPTION_DELIVERY_CONFIRMATION );
		$ups_settings_definition = new PackerPackingMethodSettingsDefinitionDecorator( $ups_settings_definition, HandlingFeesSettingsDefinitionDecorator::OPTION_HANDLING_FEES );
		$ups_settings_definition = new DatesAndTimesSettingsDefinitionDecorator( $ups_settings_definition, PackerPackingMethodSettingsDefinitionDecorator::OPTION_PACKAGING_METHOD );
		$ups_settings_definition = new EstimatedDeliverySettingsDefinitionDecorator( $ups_settings_definition, DatesAndTimesSettingsDefinitionDecorator::DATES_AND_TIMES_TITLE );
		$ups_settings_definition = new ProFeaturesSettingsDefinitionDecorator( $ups_settings_definition, EstimatedDeliverySettingsDefinitionDecorator::OPTION_DELIVERY_DATES );

		$this->ups_settings_definition = $ups_settings_definition; // @phpstan-ignore-line
	}

	/**
	 * Get form fields.
	 *
	 * @return array
	 * @throws SettingsFieldNotExistsException .
	 */
	public function get_form_fields(): array {
		return $this->ups_settings_definition->get_form_fields();
	}

	/**
	 * Validate settings.
	 *
	 * @param SettingsValues $settings Settings.
	 *
	 * @return bool
	 */
	public function validate_settings( SettingsValues $settings ): bool {
		return $this->ups_settings_definition->validate_settings( $settings );
	}
}
