<?php 
/**
 * Amigo theme customizer title control
 *
 *
 * 
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Amigo_Separator' ) && 
	class_exists( 'WP_Customize_Control' ) ) {

	class Amigo_Separator extends WP_Customize_Control {

		public $type = 'amigo-separator';
		public $label = '';		

		public function enqueue() {

			$path = AMIGO_PLUGIN_DIR_URL.'/lib/customizer-controls/customizer-separator-control/assets/';

			wp_enqueue_style( 'aqwa-separator', $path . 'css/customizer.css', null );
		}			

		public function render_content() {
			?>			
			<div class="amigo-separator">
				<h3 style="text-align: center;"><?php echo esc_html( $this->label ) ?></h3>
			</div>		
			<?php 
		}		

	}
}