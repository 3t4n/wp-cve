<?php

defined( 'ABSPATH' ) or die();

/* Range Slider Function for customizer */
if ( class_exists( 'WP_Customize_Control' ) ) :
class scoreline_Customizer_Range_Value_Control extends WP_Customize_Control {
	public $type = 'range-value';
	 // Enqueue scripts/styles.
	public function enqueue() {
		wp_enqueue_script( 'customizer-range-value-control', WL_COMPANION_PLUGIN_URL . 'admin/js/customizer-range-value-control.js', array( 'jquery' ), '', true );
		wp_enqueue_style( 'customizer-range-value-control', WL_COMPANION_PLUGIN_URL . 'admin/css/customizer-range-value-control.css', array());
	} 
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php esc_html_e( $this->label,WL_COMPANION_DOMAIN ); ?></span>
			<div class="range-slider"  style="width:100%; display:flex;flex-direction: row;justify-content: flex-start;">
				<span  style="width:100%; flex: 1 0 0; vertical-align: middle;"><input class="range-slider__range" type="range" value="<?php esc_attr_e( $this->value() ,WL_COMPANION_DOMAIN); ?>" <?php $this->input_attrs(); $this->link(); ?>>
				<span class="range-slider__value">0</span></span>
			</div>
			<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php esc_html_e( $this->description ,WL_COMPANION_DOMAIN); ?></span>
			<?php endif; ?>
		</label>
		<?php
	}
}
endif;
?>