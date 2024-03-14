<?php
/**
 * Class ProFeaturesSettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

/**
 *.
 */
class ProFeaturesSettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const OPTION_PRO_FEATURES = 'pro_features';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::OPTION_PRO_FEATURES, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		$link = get_user_locale() === 'pl_PL' ? 'https://octol.io/ups-pro-features-pl' : 'https://octol.io/ups-pro-features';

		return [
			'title'             => __( 'PRO Features', 'flexible-shipping-ups' ),
			'type'              => 'checkbox',
			'default'           => '',
			'value'             => '',
			'class'             => 'js--pro-feature-enable js--pro-feature-enable-main',
			'label'             =>
				__( 'Show the options available in the PRO version', 'flexible-shipping-ups' ) . '<br /><br />' .
				'<a target="_blank" href="' . esc_url( $link ) . '">' . __( 'Learn more about PRO version →', 'flexible-shipping-ups' ) . '</a>',
			'desc_tip'          => __( 'Tick this checkbox to display the features coming with the plugin\'s PRO version.', 'flexible-shipping-ups' ),
		];
	}
}
