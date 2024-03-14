<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck;

use ShopMagicVendor\WPDesk\Forms\Sanitizer;
use ShopMagicVendor\WPDesk\Forms\Validator;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;


/**
 * Field to use in events that want to recheck status before run.
 */
final class DefferedCheckField extends CheckboxField {
	/**
	 * @var string
	 */
	public const NAME = 'check_defer';

	public function __construct() {
		$this
			->set_name( self::NAME )
			->set_label( __( 'Recheck order status before run', 'shopmagic-for-woocommerce' ) )
			->set_description(
				__(
					"Useful for delayed automations. Ensures the status hasn't changed since initial event.",
					'shopmagic-for-woocommerce'
				)
			);
	}

	public function get_sanitizer(): Sanitizer {
		return new class implements Sanitizer {

			public function sanitize( $value ) {
				if ($value === 'yes') return true;
				if ($value === 'no') return false;
				return (bool) $value;
			}
		};
	}

	public function get_validator(): Validator {
		$validator      = parent::get_validator();
		$fast_validator = new class implements Validator {

			public function is_valid( $value ): bool {
				return in_array( $value, [ 'yes', 'no', 1, 0, true, false ] );
			}

			public function get_messages(): array {
				return [];
			}
		};
		if ( $validator instanceof Validator\ChainValidator ) {
			$validator->attach( $fast_validator );
		}

		return $fast_validator;
	}
}
