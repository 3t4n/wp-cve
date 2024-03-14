<?php
/**
 * amigo theme customizer icon picker control
 * 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } 

if ( ! class_exists( 'amigo_Customizer_Icon_Picker_Control' ) && 
	class_exists( 'WP_Customize_Control' ) ) {

	class amigo_Customizer_Icon_Picker_Control extends WP_Customize_Control {		

		public $type = 'amigo-iconpicker';
		public $iconset = array();
		public function to_json() {
			if ( empty( $this->iconset ) ) {
				$this->iconset = 'fa';
			}
			$iconset               = $this->iconset;
			$this->json['iconset'] = $iconset;
			parent::to_json();
		}

		public function enqueue() {

			$path = AMIGO_PLUGIN_DIR_URL . '/lib/customizer-controls/customizer-iconpicker-control';

			wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/fonts/font-awesome/css/font-awesome.min.css' );

			wp_enqueue_script( 'iconpicker-ddslick-min', $path . '/assets/js/jquery.ddslick.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'iconpicker-control', $path . '/assets/js/icon-picker-control.js', array( 'jquery', 'iconpicker-ddslick-min' ), '', true );			
		}

		public function render_content(){ ?>
			<?php 
			if ( empty( $this->choices ) ) {

				$path = AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-iconpicker-control/';

				require_once $path . '/inc/icon-list.php';

				$this->choices = amigo_fontawesome_icon_list();
			}
			?>

			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<select class="amigo-iconpicker-control" id="<?php echo esc_attr( $this->id ); ?>">
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php echo selected( $this->value(), $value, false ); ?> data-iconsrc="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>

		<?php }
	}
} ?>