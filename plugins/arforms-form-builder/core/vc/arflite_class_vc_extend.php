<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ARFormslite_VCExtendArp {

	protected static $instance = null;
	var $is_arforms_vdextend   = 0;

	public function __construct() {
		add_action( 'init', array( $this, 'ARFLiteintegrateWithVC' ) );
		add_action( 'init', array( $this, 'ArfLiteCallmyFunction' ) );
	}

	public function ARFLiteintegrateWithVC() {
		if ( function_exists( 'vc_map' ) ) {
			global $arfliteversion, $arflitemainhelper;

			if ( version_compare( WPB_VC_VERSION, '4.3.4', '>=' ) ) {

				if ( isset( $_REQUEST['vc_action'] ) && ! empty( $_REQUEST['vc_action'] ) ) {

					wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), $arfliteversion );
					wp_enqueue_script( 'bootstrap' );

					wp_register_script( 'jqbootstrapvalidation', ARFLITEURL . '/bootstrap/js/jqBootstrapValidation.js', array( 'jquery' ), $arfliteversion );
					wp_enqueue_script( 'jqbootstrapvalidation' );

					wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arfliteversion );
					wp_enqueue_script( 'arflite-font-awesome' );

					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_script( 'wp-color-picker' );
				}
			}

			vc_map(
				array(
					'name'              => __( 'ARForms Lite', 'arforms-form-builder' ),
					'description'       => __( 'Exclusive Wordpress Form Builder Plugin', 'arforms-form-builder' ),
					'base'              => 'ARFormslite',
					'category'          => __( 'Content', 'arforms-form-builder' ),
					'class'             => '',
					'controls'          => 'full',
					'admin_enqueue_css' => array( ARFLITEURL . '/core/vc/arformslite_vc.css' ),
					'front_enqueue_css' => ARFLITEURL . '/core/vc/arformslite_vc.css',
					'front_enqueue_js'  => ARFLITEURL . '/core/vc/arformslite_vc.js',
					'icon'              => 'arforms_vc_icon',
					'params'            => array(
						array(
							'type'        => 'ARFormslite_Shortode',
							'heading'     => false,
							'param_name'  => 'id',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),

					),
				)
			);
		}
	}

	public function ArfLiteCallmyFunction() {
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'ARFormslite_Shortode', array( $this, 'arformslite_param_html' ), ARFLITEURL . '/core/vc/arformslite_vc.js' );
		}
	}

	public function arformslite_param_html( $settings, $value ) {

		global $arflitemainhelper, $arfliteformhelper;

		echo '<input  id="Arf_param_id" type="hidden" name="id" value="" class="wpb_vc_param_value" />';

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_arfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_arforms_vdextend == 0 ) {
			$this->is_arforms_vdextend = 1;
			?>
			<div class='arfinsertform_modal_container arf_popup_content_container arfinsertform_vc_modal_container'>

				<div class="main_div_container vc_main_div_container">
					<div class="select_form arfmarginb20">
						<label><?php echo esc_html__( 'Select a form to insert into page', 'arforms-form-builder' ); ?>&nbsp;<span class="newmodal_required vc_newmodal_required">*</span></label>
						<div class="selectbox">
							<?php $arfliteformhelper->arflite_forms_dropdown_new( 'arfaddformid_vc_popup', '', 'Select form' ); ?>

						</div>
					</div>
					<input type="hidden" id="arf_shortcode_type" value="normal" name="shortcode_type"  class="wpb_vc_param_value" />
				</div>

			</div>
			<?php
		}
	}

}
?>
