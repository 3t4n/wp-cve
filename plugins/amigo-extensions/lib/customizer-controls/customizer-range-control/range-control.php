<?php
/**
 * Amigo theme customizer range control
 *
 *
 * 
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Amigo Range Control Base Class
	 *
	 *
	 */

	class Amigo_Extensions_Range_Control extends WP_Customize_Control {

		/**
		* The type of control being rendered
		*/
		public $type = 'amigo-extension-range-control';


		/**
		* The type of control settings
		*/
		public function to_json() {
			if ( ! empty( $this->setting->default ) ) {
				$this->json['default'] = $this->setting->default;
			} else {
				$this->json['default'] = false;
			}
			parent::to_json();
		}

		/**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {

			/**
		 	* range control assets dir path
		 	*/
		 	$path = AMIGO_PLUGIN_DIR_URL.'/lib/customizer-controls/customizer-range-control/assets/';

			// enqueue css
		 	wp_enqueue_style( 'amigo-extension-range-slider', $path . 'css/range-control.css' );

			// enqueue js
		 	wp_enqueue_script( 'amigo-extension-range-slider', $path . 'js/range-control.js', array( 'jquery' ), '', true );
		 	
		 }

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<div id="<?php echo esc_attr( $this->id ); ?>">
					<div class="amigo-extension-range-control">
						<input class="range-of-amigo-extension-range-control" type="range" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->input_attrs(); $this->link(); ?> />
						<input class="value-of-amigo-extension-range-control" type="number" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->input_attrs(); $this->link(); ?> />
						<?php if ( ! empty( $this->setting->default ) ) : ?>
							<span class="reset-amigo-extension-range-control" title="<?php _e( 'Reset', 'amigo-extensions' ); ?>"><span class="dashicons dashicons-image-rotate"></span></span>
						<?php endif;?>
					</div>
				</div>
			</label>
		<?php }

	}

}