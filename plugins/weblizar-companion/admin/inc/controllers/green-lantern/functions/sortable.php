<?php 

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'green_lantern_Custom_sortable_Control' ) ) :
	class green_lantern_Custom_sortable_Control extends WP_Customize_Control {
		public $type = 'home-sortable';

		/*Enqueue resources for the control*/
		public function enqueue() {

			wp_enqueue_style( 'customizer-repeater-admin-stylesheet', WL_COMPANION_PLUGIN_URL. '/admin/inc/controllers/green-lantern/customizer_js_css/css/green-lantern-admin-style.css');

			wp_enqueue_script( 'customizer-repeater-script', WL_COMPANION_PLUGIN_URL . '/admin/inc/controllers/green-lantern/customizer_js_css/js/green-lantern-customizer_repeater.js', array(
				'jquery',
				'jquery-ui-draggable'
			), '', true );

		}

		public function render_content() {
			if ( empty( $this->choices ) ) {
				return;
			}
			$values = json_decode( $this->value() );
			$name   = $this->id;
			?>

            <span class="customize-control-title">
				<?php esc_attr_e( $this->label ,WL_COMPANION_DOMAIN); ?>
			</span>

			<?php if ( ! empty( $this->description ) ): ?>
                <span class="description customize-control-description"><?php esc_html_e( $this->description,WL_COMPANION_DOMAIN ); ?></span>
			<?php endif; ?>

            <div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
				<?php
				if ( ! empty( $values ) ) {
					foreach ( $values as $value ) { ?>
                        <div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable ui-sortable-handle">
                            <div class="customizer-repeater-customize-control-title">
								<?php esc_attr_e( $this->choices[ $value ] ,WL_COMPANION_DOMAIN); ?>
                            </div>
                            <input type="hidden" class="section-id" value="<?php esc_attr_e( $value ,WL_COMPANION_DOMAIN); ?>">
                        </div>
					<?php } ?>

				<?php } else {
					foreach ( $this->choices as $value => $label ): ?>
                        <div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable ui-sortable-handle">
                            <div class="customizer-repeater-customize-control-title">
								<?php esc_attr_e( $label ,WL_COMPANION_DOMAIN); ?>
                            </div>
                            <input type="hidden" class="section-id" value="<?php esc_attr_e( $value ,WL_COMPANION_DOMAIN); ?>">
                        </div>
					<?php endforeach;
				}
				if ( ! empty( $value ) ) { ?>
                    <input type="hidden"
                           id="customizer-repeater-<?php esc_attr_e( $this->id ,WL_COMPANION_DOMAIN); ?>-colector" <?php esc_url( $this->link() ); ?>
                           class="customizer-repeater-colector"
                           value="<?php echo esc_textarea( json_encode( $value ) ); ?>"/>
					<?php
				} else { ?>
                    <input type="hidden"
                           id="customizer-repeater-<?php esc_attr_e( $this->id ,WL_COMPANION_DOMAIN); ?>-colector" <?php esc_url( $this->link() ); ?>
                           class="customizer-repeater-colector"/>
					<?php
				} ?>
            </div>
		<?php
		}
	}
endif;

function sanitize_json_string( $json ) {
	$sanitized_value = array();
	foreach ( json_decode( $json, true ) as $value ) {
		$sanitized_value[] = esc_attr( $value );
	}

	return json_encode( $sanitized_value );
}