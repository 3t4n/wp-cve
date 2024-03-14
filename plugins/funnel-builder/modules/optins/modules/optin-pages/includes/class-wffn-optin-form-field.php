<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single optin form field(like first_name, last_name, optin_email, optin_phone) to register different form fields
 * Class WFFN_Optin_Form_Field
 */
if ( ! class_exists( 'WFFN_Optin_Form_Field' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Optin_Form_Field implements WFFN_Optin_Form_Field_Interface {

		private static $slug = '';
		public $is_custom_field = false;
		public static $countries_phone_regex = [
			'af' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Afghanistan (‫افغانستان‬‎)', 'code' => '93' ],
			'al' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Albania (Shqipëri)', 'code' => '355' ],
			'bg' => [ 'pattern' => '/^((\+)\d{13}){1}?$/', 'name' => 'Bulgaria (България)', 'code' => '359' ],
			'br' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Brazil (Brasil)', 'code' => '55' ],
			'ca' => [
				'pattern'   => '/^((\+)\d{11}){1}?$/',
				'name'      => 'Canada',
				'code'      => '1',
				"areaCodes" => [
					"204",
					"226",
					"236",
					"249",
					"250",
					"289",
					"306",
					"343",
					"365",
					"387",
					"403",
					"416",
					"418",
					"431",
					"437",
					"438",
					"450",
					"506",
					"514",
					"519",
					"548",
					"579",
					"581",
					"587",
					"604",
					"613",
					"639",
					"647",
					"672",
					"705",
					"709",
					"742",
					"778",
					"780",
					"782",
					"807",
					"819",
					"825",
					"867",
					"873",
					"902",
					"905"
				]
			],
			'hk' => [ 'pattern' => '/^((\+)\d{13}){1}?$/', 'name' => 'Hong Kong (香港)', 'code' => '852' ],
			'il' => [ 'pattern' => '/^((\+)\d{13}){1}?$/', 'name' => 'israel', 'code' => '972' ],
			'in' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'India (भारत)', 'code' => '91' ],
			'it' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Italy (Italia)', 'code' => '39' ],
			'cn' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'China (中国)', 'code' => '86' ],
			'jp' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Japan (日本)', 'code' => '81' ],
			'ae' => [ 'pattern' => '/^((\+)\d{13}){1}?$/', 'name' => 'United Arab Emirates (‫الإمارات العربية المتحدة‬‎)', 'code' => '971' ],
			'gb' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'United Kingdom', 'code' => '44' ],
			'nl' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Netherlands (Nederland)', 'code' => '31' ],
			'fr' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'France', 'code' => '33' ],
			'vn' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Vietnam (Việt Nam)', 'code' => '84' ],
			'si' => [ 'pattern' => '/^((\+)\d{13}){1}?$/', 'name' => 'Slovenia (Slovenija)', 'code' => '386' ],
			'es' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Spain (España)', 'code' => '34' ],
			'ro' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Romania (România)', 'code' => '40' ],
			'mx' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Mexico (México)', 'code' => '52' ],
			'pk' => [ 'pattern' => '/^((\+)\d{12}){1}?$/', 'name' => 'Pakistan (‫پاکستان‬‎)', 'code' => '92' ],
			'us' => [ 'pattern' => '/^((\+)\d{11}){1}?$/', 'name' => 'United States', 'code' => '1' ],
		];

		/**
		 * WFFN_Optin_Form_Field constructor.
		 */
		public function __construct() {
		}

		public function should_register() {
			return true;
		}

		public static function get_slug() {
			return self::$slug;
		}

		public function get_prefix() {
			return WFFN_Optin_Pages::FIELD_PREFIX;
		}

		/**
		 * @return array
		 */
		public function get_field_format() {
			return [];
		}

		/**
		 * @return string
		 * Load custom scripts js file
		 */
		public function load_scripts() {
			return '';
		}

		/**
		 * Load custom style css file
		 */
		public function load_style() {
		}

		public function get_default_value( $field_data ) {
			return $field_data['default'];
		}

		public function get_field_editor_html( $mode = 'new' ) {
			?>
			<div class="wfop_<?php echo esc_attr( $mode ); ?>_fields_wrap" data-type="<?php echo esc_attr( $this::get_slug() ); ?>">
				<div class="wffn_row_billing">
					<div class="wffn_billing_left">
						<label><?php esc_html_e( 'Label', 'funnel-builder' ); ?></label>
					</div>
					<div class="wffn_billing_right">
						<input type="text" onkeyup="window.wfop_design.fieldEdited('<?php echo esc_attr( $mode ) ?>',this.value, 'label','<# print(data.index); #>')" value="<# print(data.field.label); #>" class="form-control wffn_label">
					</div>
				</div>
				<div class="wffn_row_billing">
					<div class="wffn_billing_left">
						<label><?php esc_html_e( 'Placeholder', 'funnel-builder' ); ?></label>
					</div>
					<div class="wffn_billing_right">
						<input type="text" onkeyup="window.wfop_design.fieldEdited('<?php echo esc_attr( $mode ) ?>',this.value, 'placeholder','<# print(data.index); #>')" value="<# print(data.field.placeholder); #>" class="form-control wffn_placeholder">
					</div>
				</div>
				<div class="wffn_row_billing">
					<div class="wffn_billing_left">
						<label for=""><?php esc_html_e( 'Required', 'funnel-builder' ); ?></label>
					</div>
					<div class="wffn_billing_right">
						<input onchange="window.wfop_design.fieldEdited('<?php echo esc_attr( $mode ) ?>',this.checked, 'required','<# print(data.index); #>')" type="checkbox" <#
						print(data.curr.isChecked(data.field.required)); #> class="form-control wffn_required">
					</div>
				</div>
				<div class="wffn_row_billing">
					<div class="wffn_billing_left">
						<label for=""><?php esc_html_e( 'Default', 'funnel-builder' ); ?></label>
					</div>
					<div class="wffn_billing_right">
						<input onkeyup="window.wfop_design.fieldEdited('<?php echo esc_attr( $mode ) ?>',this.value, 'default','<# print(data.index); #>')" type="text" value="<# print(data.field.default); #>" class="form-control">
					</div>
				</div>
				<div class="wffn_row_billing">
					<div class="wffn_billing_left">
						<label><?php esc_html_e( 'Width', 'funnel-builder' ); ?></label>
					</div>
					<div class="wffn_billing_right">
						<select onchange="window.wfop_design.fieldEdited('<?php echo esc_attr( $mode ); ?>',this.value, 'width','<# print(data.index); #>')">
							<option
							<# print(data.curr.isSelected(data.field.width,'wffn-sm-100')); #> value='wffn-sm-100'>100%</option>
							<option
							<# print(data.curr.isSelected(data.field.width,'wffn-sm-50')); #> value='wffn-sm-50'>50%</option>
							<option
							<# print(data.curr.isSelected(data.field.width,'wffn-sm-33')); #> value='wffn-sm-33'>33%</option>
						</select>
					</div>
				</div>
			</div>
			<?php
		}

		public function get_required_class( $field ) {
			if ( true === $field['required'] || 'true' === $field['required'] || 1 === $field['required'] || '1' === $field['required'] ) {
				return 'wfop_required';
			}

			return '';
		}

		public function get_input_class( $field ) {
			$classes = 'wffn-optin-input ';

			$classes .= $this->get_required_class( $field );

			return $classes;
		}

		public function get_sanitized_value( $data, $field ) {
			return isset( $data[ $field['InputName'] ] ) ? wffn_clean( $data[ $field['InputName'] ] ) : '';
		}

		public function get_editor_data() {
			return array(
				'is_custom_field' => $this->is_custom_field,
				'default'         => $this->get_field_format(),
				'title'           => $this->get_title()
			);
		}

		public function __toString() {
		}
	}
}
