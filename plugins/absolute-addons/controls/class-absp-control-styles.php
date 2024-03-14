<?php
/**
 *
 */

namespace AbsoluteAddons\Controls;

use AbsoluteAddons\Plugin;
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Select2
 *
 * Class Absp_Control_Styles
 * @package AbsoluteAddons\Controls
 */
class Absp_Control_Styles extends Base_Data_Control {

	const TYPE = 'absp_styles';

	public function get_type() {
		return self::TYPE;
	}

	protected function get_default_settings() {
		return [
			'widget'         => '',
			'options'        => [],
			'disabled'       => [],
			'pro_data'       => [],
			'class_name'     => '',
			'multiple'       => false,
			'select2options' => [], // Select2 library options
			'lockedOptions'  => [], // The lockedOptions array can be passed option keys, passed option keys will be non-deletable.
		];
	}

	public function enqueue() {


		Plugin::enqueue_script( 'sweetalert2', 'assets/dist/js/libraries/sweetalert2.all', [], ABSOLUTE_ADDONS_VERSION, true );
		Plugin::enqueue_script( 'absp_select2-control', '/assets/dist/js/absp-styles', [ 'sweetalert2' ], ABSOLUTE_ADDONS_VERSION, true );
		wp_localize_script( 'absp_select2-control', 'AbspSelect2', [
			'title'      => __( 'Meet the <span>Pro</span> Feature', 'absolute-addons' ),
			'body'       => __( 'Get Pro and experience all of our exciting features and widgets. You will also get world class support from our dedicated team, 24/7.', 'absolute-addons' ),
			'promo_logo' => absp_plugin_url( 'assets/images/absolute-addons-promo.svg', false ),
			'logo_alt'   => __( 'Absolute Addons Promo', 'absolute-addons' ),
			'crown'      => absp_plugin_url( 'assets/images/crown.svg', false ),
			'link_label' => __( 'Upgrade to Pro', 'absolute-addons' ),
		] );
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select data-widget="{{ widget }}" id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-select2 {{ data.class_name }}" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						var disabled = ( -1 !== data.disabled.indexOf( option_value ) ) ? 'disabled' : '';
						var pro_data = ( -1 !== data.pro_data.indexOf( option_value ) ) ? 'data-pro=true' : '';
					#>
					<option {{ selected }} {{ disabled }} {{ pro_data }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}

// End of file class-absp-control-select2.php.
