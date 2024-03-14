<?php
class CLP_Customizer_Range_Value_Control extends \WP_Customize_Control {
	public $type = 'range-value';
	public $default = '';

	/**
	 * Render Control
	*
	* @since  1.0.0
	* @access public
	* @return void
	*/
	public function render_content() { ?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="range-slider">
				<div class="reset__default" data-default="<?php echo esc_attr( $this->default ); ?>"></div>
				<span  style="width:100%; display: flex;align-items: center;"><input class="range-slider__range" type="range" value="<?php echo esc_attr( $this->value() ); ?>"
					<?php
					$this->input_attrs();
					$this->link();
					?>
				>
				<span class="range-slider__value">0</span></span>
			</div>
			<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
		<?php
	}
}