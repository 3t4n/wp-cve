<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class PL_Customizer_Control_Range_Slider extends WP_Customize_Control {

	public $type = 'pluglab-range-slider';

	public function to_json() {
		if ( ! empty( $this->setting->default ) ) {
			$this->json['default'] = $this->setting->default;
		} else {
			$this->json['default'] = false;
		}
		parent::to_json();
	}

	public function enqueue() {
		wp_enqueue_script( 'pluglab-range-slider', PL_PLUGIN_INC_URL . 'customizer/js/customizer-range-control/range-control.js', array( 'jquery' ), '', true );

		wp_enqueue_style( 'pluglab-range-slider', PL_PLUGIN_INC_URL . 'customizer/css/customizer-range-control/range-control.css', array(), '' );
	}

	public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php
			endif;
			if ( ! empty( $this->description ) ) :
				?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div id="<?php echo esc_attr( $this->id ); ?>">
				<div class="pluglab-range-slider">
					<input class="pluglab-range-slider-range" type="range" value="<?php echo esc_attr( $this->value() ); ?>" 
																							 <?php
																								$this->input_attrs();
																								$this->link();
																								?>
		 />
					<input class="pluglab-range-slider-value" type="number" value="<?php echo esc_attr( $this->value() ); ?>" 
																							  <?php
																								$this->input_attrs();
																								$this->link();
																								?>
			 />
						<?php if ( ! empty( $this->setting->default ) ) : ?>
						<span class="pluglab-range-reset-slider" title="<?php _e( 'Reset', 'pluglab' ); ?>"><span class="dashicons dashicons-image-rotate"></span></span>
		<?php endif; ?>
				</div>
			</div>
		</label>
		<?php
	}

}
