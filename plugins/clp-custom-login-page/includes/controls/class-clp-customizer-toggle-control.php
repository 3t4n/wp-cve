<?php

// thanks to @Soderlind
// https://github.com/soderlind/class-customizer-toggle-control

class CLP_Customizer_Toggle_Control extends \WP_Customize_Control {
	
	public $type = 'toggle';

	/**
	 * Render the control's content.
	 * @version 1.0.0
	 */
	public function render_content() {
		?>
		<label class="customize-toogle-label">
			<div style="display:flex;flex-direction: row; justify-content: center; align-items: center;">
				<span class="customize-control-title" style="flex: 2 0 0;"><?php echo esc_html( $this->label ); ?></span>
				<input id="cb<?php echo $this->instance_number; ?>" 
					type="checkbox" 
					class="tgl tgl-<?php echo $this->type; ?>" 
					value="<?php echo esc_attr( $this->value() ); ?>"
					<?php
					$this->link();
					?>
				 />
				<label for="cb<?php echo $this->instance_number; ?>" class="tgl-btn"></label>
			</div>
			<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>
		</label>
		<?php
	}

}