<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_COMPLEMENTARY_CONTROLS' ) ) {
	class CF7_ADDONS_COMPLEMENTARY_CONTROLS extends CF7_ADDONS_PARENT {

		protected $slug        = 'complementary-controls';
		protected $title       = 'Complementary Controls';
		protected $image       = 'complementary-controls.addon/image.png';
		protected $description = 'List of complementary controls to use with CF7 forms.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/complementary-controls-addon';
		protected $free        = true;
		private $settings;
		private $controls;
		public function __construct() {
			 $this->controls = array(
				 'print-form-button'      => array( 'file' => 'print-form-button.php' ),
				 'select2-drop-down-menu' => array( 'file' => 'select2-drop-down-menu.php' ),
				 'copy-to-clipboard'      => array( 'file' => 'copy-to-clipboard.php' ),
				 'data-table'             => array( 'file' => 'data-table.php' ),
			 );

			 $container_obj = $this;
			 foreach ( $this->controls as $control_key => $control ) {
				 $slug = $control_key;
				 $path = dirname( __FILE__ ) . '/complementary-controls.addon/controls/' . $control['file'];
				 if ( file_exists( $path ) ) {
					 include $path;
				 }
			 }
			 // add_action('wpcf7_contact_form', [$this, 'wpcf7_contact_form']);
		} // End __construct

		/*** WPCF7 HOOKS ***/

		public function wpcf7_contact_form( $cf7_obj ) {
			$cf7_id   = $cf7_obj->id();
			$settings = $this->get_settings( $cf7_id );

			if (
				! empty( $settings ) &&
				! empty( $settings['enabled'] ) &&
				isset( $settings['user_exists'] ) &&
				'stop' == $settings['user_exists'] &&
				! empty( $settings['field']['user_email'] )
			) {
				add_filter(
					'wpcf7_validate_email',
					function( $result, $tag ) use ( $settings ) {
						return $this->wpcf7_validate( $result, $tag, $settings );
					},
					20,
					2
				);
			}
		} // End form_shortcode

		public function wpcf7_validate( $result, $tag, $settings ) {
			if ( $tag->name == $settings['field']['user_email'] ) {
				$email = isset( $_POST[ $settings['field']['user_email'] ] ) ? sanitize_email( wp_unslash( $_POST[ $settings['field']['user_email'] ] ) ) : '';
				if ( empty( $email ) || email_exists( $email ) ) {
					$result->invalidate( $tag, $settings['error_message'] );
				}
			}
			return $result;
		} // End wpcf7_validate

		/*** END WPCF7 HOOKS ***/

		public function set_control_obj( $slug, $control_obj ) {
			if ( isset( $this->controls[ $slug ] ) ) {
				$this->controls[ $slug ]['obj'] = $control_obj;
			}
		} // End set_control_obj

		public function is_control_active( $slug ) {
			$settings = $this->get_settings();
			return ! isset( $settings[ $slug ] ) || ! empty( $settings[ $slug ] );
		} // End is_control_active

		public function settings_interface() {
			if (
				isset( $_REQUEST['cf7-datasource-addon-nonce'] ) &&
				wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cf7-datasource-addon-nonce'] ) ), 'cf7-datasource-complementary-controls-addon' )
			) {
				$settings = array();
				foreach ( $this->controls as $control_key => $control ) {
					$settings[ $control_key ] = ! empty( $_REQUEST[ $control_key ] ) ? true : false;
				}
				$this->set_settings( $settings );
			}

			wp_enqueue_style( 'cf7-complementary-controls', plugins_url( '/complementary-controls.addon/styles.css', __FILE__ ), array(), CF7_DATASOURCE::$version );

			print '<h2>' . esc_html( $this->get_title() ) . ' - Add on <a href="' . esc_attr( $this->get_help_url() ) . '" target="_blank" style="font-weight:normal;">(' . esc_html__( 'Additional details', 'cf7-datasource' ) . ')</a></h2>

            <form action="admin.php?page=cf7-datasource-addons&cf7-data-source-addon=' . esc_attr( $this->get_slug() ) . '" method="post">';

			wp_nonce_field( 'cf7-datasource-complementary-controls-addon', 'cf7-datasource-addon-nonce' );

			print '<ul class="cf7-addons">';

			foreach ( $this->controls as $control_key => $control ) {
				if ( isset( $control['obj'] ) ) {
					$control_obj = $control['obj'];
				} else {
					continue;
				}

				$control_enabled = $this->is_control_active( $control_key );
				$btn_class       = 'button-primary';
				$btn_text        = __( 'Enable Control', 'cf7-datasource' );

				if ( $control_enabled ) {
					$btn_class = 'button-secondary';
					$btn_text  = __( 'Disable Control', 'cf7-datasource' );
				}

				$image_url = $control_obj->get_image_url();
				print ( $control_enabled ? '<input type="hidden" name="' . esc_attr( $control_key ) . '" value="1" />' : '' ) // phpcs:ignore WordPress.Security.EscapeOutput
				. '
                    <li class="cf7-complementary-control">
                        <div class="cf7-complementary-control-details">
                            <div class="cf7-complementary-control-description">
                                <h2>' . esc_html( $control_obj->get_title() ) . '</h2>
                                <p>' . wp_kses_post( $control_obj->get_description() ) . '</p>
                            </div>
                            <div class="cf7-complementary-control-img-wrap">' . ( ! empty( $image_url ) ? '<img src="' . esc_attr( $image_url ) . '">' : '' ) . '</div>
                            <div style="border-top:1px solid #dcdcde;padding-top:10px; text-align:right;margin-left:-20px;margin-right:-20px;">
                            <button data-cf7-control="' . esc_attr( $control_key ) . '" class="' . esc_attr( $btn_class ) . '">' . esc_html( $btn_text ) . '</button>
                            </div>
                        </div>
                    </li>
                ';
			}
			print '</ul></form>';
			?>
			<script>
			jQuery(function(){
				var $ = jQuery;
				$(document).on('click', '[data-cf7-control]', function(){
					var slug  = $(this).attr('data-cf7-control'),
						field = $('[name="'+slug+'"]');
					if(field.length) field.remove();
					else $(this).after('<input type="hidden" name="'+slug+'" value="1" />');
				});
			});
			</script>
			<?php
		} // End settings_interface
	} // End class CF7_ADDONS_COMPLEMENTARY_CONTROLS
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_COMPLEMENTARY_CONTROLS() );
