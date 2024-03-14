<?php

namespace WpifyWoo\Modules\PacketaShipping;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;

class ShippingMethodsGenerator extends AbstractComponent {
	public function generate_classes( $api_key ) {
		$url      = sprintf( 'https://www.zasilkovna.cz/api/v4/%s/branch.json?lang=cs', $api_key );
		$carriers = json_decode( wp_remote_retrieve_body( wp_remote_get( $url ) ) );
		$result   = array();

		foreach ( $carriers->carriers as $carrier ) {
			if ( $carrier->pickupPoints === 'false' && $carrier->id != '16629' ) {

				$replaces = array(
					'(18:00-22:00)' => '',
					' ' => '',
					',' => '',
					'ů' => 'u',
				);

				$class_name = str_replace(array_keys($replaces), array_values($replaces), $this->sanitize_filename($carrier->labelName));
				$template = $this->get_shipping_method_template($class_name, $carrier->id, $carrier->labelName);
				$path = sprintf('%s/ShippingMethods/%s.php', __DIR__, $class_name);

				if (!file_exists($path)) {
					file_put_contents($path, $template);
				}

				$result[] = [
					'id' => $carrier->id,
					'class' => $class_name,
					'label' => $carrier->labelName,
				];
			}
		}

		file_put_contents( __DIR__ . '/carriers.json', json_encode( $result ) );
	}

	function sanitize_filename( $field ) {
		$letters = [
			0  => "a à á â ä æ ã å ā",
			1  => "c ç ć č",
			2  => "e é è ê ë ę ė ē ě",
			3  => "i ī į í ì ï î",
			4  => "l ł",
			5  => "n ñ ń ň",
			6  => "o ō ø œ õ ó ò ö ô",
			7  => "s ß ś š Š š",
			8  => "u ū ú ù ü û",
			9  => "w ŵ",
			10 => "y ŷ ÿ ý",
			11 => "z ź ž ż",
			12 => "d ď",
		];

		foreach ( $letters as &$values ) {
			$newValue = substr( $values, 0, 1 );
			$values   = substr( $values, 2, strlen( $values ) );
			$values   = explode( " ", $values );

			foreach ( $values as &$oldValue ) {
				while ( strpos( $field, $oldValue ) !== false ) {
					$field = preg_replace( "/" . $oldValue . '/', $newValue, $field, 1 );
				}
			}
		}

		return $field;
	}

	public function get_shipping_method_template( $class_name, $id, $label ) {
		return sprintf( '<?php

namespace WpifyWoo\Modules\PacketaShipping\ShippingMethods;

use WC_Shipping_Flat_Rate;

if ( ! class_exists( "WpifyWoo\Modules\PacketaShipping\ShippingMethods\PacketaShippingMethod%1$s" ) ) {
	class %1$s extends WC_Shipping_Flat_Rate {
		/**
		 * Constructor.
		 *
		 * @param int $instance_id Shipping method instance ID.
		 */
		public function __construct( $instance_id = 0 ) {
			$this->id           = "packeta_%2$s";
			$this->instance_id  = absint( $instance_id );
			$this->method_title = __( "%3$s", "wpify-woo" );
			$this->supports     = array(
				"shipping-zones",
				"instance-settings",
				"instance-settings-modal",
			);

			$this->init();

			add_action( "woocommerce_update_options_shipping_" . $this->id, array( $this, "process_admin_options" ) );
		}
	}
}
', $class_name, $id, $label );
	}
}
