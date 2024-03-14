<?php
/**
 * Template for displaying the slider field
 *
 * @var array $field The field.
 * @package YITH\Search\Views
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

list ( $field_id, $class, $name, $value,
	/**
	 * Array of option containing min and max value
	 * This is deprecated since 3.5 | use 'min' and 'max' instead.
	 */
	$option,
	$min, $max, $step, $custom_attributes, $data ) = yith_plugin_fw_extract( $field, 'id', 'class', 'name', 'value', 'option', 'min', 'max', 'step', 'custom_attributes', 'data' );

// Handle the deprecated attribute 'option': use 'min' and 'max' instead.
if ( ! isset( $min ) && isset( $option, $option['min'] ) ) {
	$min = $option['min'];
}

if ( ! isset( $max ) && isset( $option, $option['max'] ) ) {
	$max = $option['max'];
}

$min  = isset( $min ) ? $min : 0;
$max  = isset( $max ) ? $max : 100;
$step = isset( $step ) ? $step : 1;
?>
<div class="yith-plugin-fw-slider-container <?php echo ! empty( $class ) ? esc_attr( $class ) : ''; ?>">
	<div class="ywcas-slider-wrapper">
		<div class="ui-slider">
			<div id="<?php echo esc_attr( $field_id ); ?>-div"
				 class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"
				 data-step="<?php echo esc_attr( $step ); ?>"
				 data-min="<?php echo esc_attr( $min ); ?>"
				 data-max="<?php echo esc_attr( $max ); ?>"
				 data-val="<?php echo esc_attr( $value ); ?>"

				<?php yith_plugin_fw_html_attributes_to_string( $custom_attributes, true ); ?>
				<?php yith_plugin_fw_html_data_to_string( $data, true ); ?>
			>
				<input id="<?php echo esc_attr( $field_id ); ?>"
					   type="hidden"
					   name="<?php echo esc_attr( $name ); ?>"
					   value="<?php echo esc_attr( $value ); ?>"
				/>
			</div>

		</div>
		<input id="<?php echo esc_attr( $field_id ); ?>-preview"
			   type="text"
			   name="<?php echo esc_attr( $name ); ?>-preview"
			   value="<?php echo esc_attr( $value ); ?>"
			   disabled
		/>
		<span>%</span>
	</div>

</div>
