<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Optin_HTML_Block_Oxy
 */
if ( ! class_exists( 'WFFN_Optin_HTML_Block_Oxy' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Optin_HTML_Block_Oxy extends WFFN_OXY_Field {

		protected function html( $settings, $defaults, $content ) {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			$settings['button_border_size'] = 0;
			$wrapper_class                  = '';
			$show_labels                    = ( isset( $settings['show_labels'] ) && $settings['show_labels'] === 'on' );
			$input_size                     = isset( $settings['input_size'] ) ? $settings['input_size'] : '12px';
			$wrapper_class                  .= $show_labels ? '' : ' wfop_hide_label';

			$optinPageId    = WFOPP_Core()->optin_pages->get_optin_id();
			$optin_fields   = WFOPP_Core()->optin_pages->form_builder->get_optin_layout( $optinPageId );
			$optin_settings = WFOPP_Core()->optin_pages->get_optin_form_integration_option( $optinPageId );

			foreach ( $optin_fields as $step_slug => $optinFields ) {
				foreach ( $optinFields as $key => $optin_field ) {
					$optin_fields[ $step_slug ][ $key ]['width'] = $settings[ $optin_field['InputName'] ];
				}
			}

			$custom_form = WFOPP_Core()->form_controllers->get_integration_object( 'form' );
			if ( $custom_form instanceof WFFN_Optin_Form_Controller_Custom_Form ) {
				$custom_form->_output_form( $wrapper_class, $optin_fields, $optinPageId, $optin_settings, 'inline', $settings );
			}

			if( isset($_REQUEST['action']) && $_REQUEST['action'] === 'oxy_render_oxy-optin-form') {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			?>
            <script>
                jQuery(document).trigger('wffn_reload_phone_field');
            </script>
			<?php

			}
			if ( ! empty( $input_size ) ) {
				?>
                <style>
                    .bwfac_forms_outer[data-field-size="small"] .bwfac_form_sec input:not(.wfop_submit_btn) {
                        padding: <?php echo $input_size;//phpcs:ignore ?> 15px;
                    }
                </style>
				<?php
			}


		}


		protected function register_form_fields() {
			$tab_id = $this->add_tab( __( 'Layout', 'funnel-builder' ) );

			$optinPageId = WFOPP_Core()->optin_pages->get_id();
			$get_fields  = [];
			if ( $optinPageId > 0 ) {
				$get_fields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optinPageId );
			}
			foreach ( is_array( $get_fields ) ? $get_fields : [] as $field ) {

				$default = isset( $field['width'] ) ? $field['width'] : 'wffn-sm-100';
				$this->add_select( $tab_id, $field['InputName'], esc_html__( $field['label'] ), $this->get_class_options(), $default );
			}

			do_action( 'wffn_additional_controls', $this );

		}

		protected function button_settings( $tab_heading = '' ) {
			if ( empty( $tab_heading ) ) {
				$tab_heading = __( 'Button', 'funnel-builder' );
			}
			$key = "wfacp_inline_key";
			// Button Controls
			$tab_id = $this->add_tab( $tab_heading );
			$this->add_heading( $tab_id, __( 'Text', 'funnel-builder' ) );
			$this->add_text( $tab_id, 'button_text', __( 'Title', 'funnel-builder' ), __( 'Send Me My Free Guide', 'funnel-builder' ), [], '', __( 'Enter the Button Text', 'funnel-builder' ) );
			$this->add_text( $tab_id, 'subtitle', 'Sub Title', '', [], '', __( 'Enter subtitle', 'funnel-builder' ) );
			$this->add_text( $tab_id, 'button_submitting_text', 'Submitting Text', __( 'Submitting...', 'funnel-builder' ) );

			if ( $tab_heading === 'Popup Inline Button' ) {
				$this->add_text( $tab_id, 'popup_footer_text', __( 'Text After Button', 'funnel-builder' ), __( 'Your Information is 100% Secure', 'funnel-builder' ) );
			}


			$this->add_heading( $tab_id, __( 'Color', 'funnel-builder' ) );

			$this->add_color( $tab_id, 'button_text_color', '.bwfac_form_sec #wffn_custom_optin_submit,.bwfac_form_sec #wffn_custom_optin_submit span', __( 'Text', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'button_bg_color', '.bwfac_form_sec #wffn_custom_optin_submit', '#FBA506' );

			$this->add_color( $tab_id, 'button_text_hover', '.bwfac_form_sec #wffn_custom_optin_submit:hover span', __( 'Text Hover', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'button_bg_hover', '.bwfac_form_sec #wffn_custom_optin_submit:hover', '#E69500', __( 'Background Hover', 'funnel-builder' ) );

			$this->add_heading( $tab_id, __( 'Advanced', 'funnel-builder' ) );
			$this->add_width( $tab_id, 'button_width', '.bwfac_form_sec #wffn_custom_optin_submit' );

			$this->add_text_alignments( $tab_id, $key . '_button_alignment', '.wffn-custom-optin-from #bwf-custom-button-wrap', __( 'Alignment', 'funnel-builder' ) );
			$this->add_text_alignments( $tab_id, $key . '_button_text_alignment', '.wffn-custom-optin-from #bwf-custom-button-wrap span.bwf-text-wrapper', __( 'Text Alignment', 'funnel-builder' ) );

			$this->add_heading( $tab_id, __( 'Spacing', 'funnel-builder' ) );
			$this->add_margin( $tab_id, 'button_text_padding', '.bwfac_form_sec #wffn_custom_optin_submit' );
			$this->add_padding( $tab_id, 'button_text_margin', '.bwfac_form_sec #wffn_custom_optin_submit' );

			$this->add_border( $tab_id, 'bwf_button_border', '.bwfac_form_sec #wffn_custom_optin_submit' );
			$this->add_box_shadow( $tab_id, 'bwf_button_shadow', '.bwfac_form_sec #wffn_custom_optin_submit' );
			$this->add_typography( $tab_id, 'button_text_typo', '.bwfac_form_sec #wffn_custom_optin_submit .bwf_heading', 'Title Typography' );
			$this->add_typography( $tab_id, 'button_subheading_text_typo', '.bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading', 'Sub Title Typography' );
			$this->add_heading( $tab_id, __( 'Color', 'funnel-builder' ) );

			$this->add_color( $tab_id, 'button_sub_text_color', 'bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading', __( 'Text', 'funnel-builder' ), '#fff' );

			$this->add_color( $tab_id, 'button_sub_text_hover', 'bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading:hover', __( 'Text Hover', 'funnel-builder' ), '#fff' );
			$this->add_background_color( $tab_id, 'button_bg_hover', '.bwfac_form_sec #wffn_custom_optin_submit:hover', '#E69500', __( 'Background Hover', 'funnel-builder' ) );

			if ( $tab_heading === 'Popup Inline Button' ) {
				$this->add_typography( $tab_id, 'popup_footer_text_typography', '.bwf_pp_cont .bwf_pp_footer', 'Text After Button Typography' );
			}

		}

		protected function register_form_styles() {
			$tab_id = $this->add_tab( __( 'Label', 'funnel-builder' ) );
			$this->add_switcher( $tab_id, 'show_labels', __( 'Show Label', 'funnel-builder' ), 'on' );


			$this->add_heading( $tab_id, __( 'Spacing', 'funnel-builder' ) );
			$this->add_margin( $tab_id, 'label_margin', '.bwfac_form_sec > label, .bwfac_form_sec .wfop_input_cont > label' );


			$this->add_heading( $tab_id, __( 'Color', 'funnel-builder' ) );
			$this->add_color( $tab_id, 'mark_required_color', '.bwfac_form_sec > label > span, .bwfac_form_sec .wfop_input_cont > label > span', __( 'Asterisk Color', 'funnel-builder' ), '#7A7A7A' );
			$this->add_typography( $tab_id, 'label_typography', '.bwfac_form_sec > label, .bwfac_form_sec .wfop_input_cont > label' );


			$tab_id = $this->add_tab( __( 'Input', 'funnel-builder' ) );


			$this->add_heading( $tab_id, __( 'Color', 'funnel-builder' ) );
			$this->add_background_color( $tab_id, 'field_background_color', '.bwfac_form_sec .wffn-optin-input', '#fff', __( 'Background', 'funnel-builder' ) );


			$this->add_heading( $tab_id, __( 'Spacing', 'funnel-builder' ) );
			$this->add_margin( $tab_id, $this->slug . '_column_gap_margin', '.wffn-custom-optin-from .wfop_section .bwfac_form_sec', __( 'Rows', 'funnel-builder' ) );
			$this->add_padding( $tab_id, $this->slug . '_column_gap_padding', '.wffn-custom-optin-from .wfop_section .bwfac_form_sec', __( 'Columns', 'funnel-builder' ) );

			$this->add_heading( $tab_id, __( 'Advanced', 'funnel-builder' ) );

			$this->add_select( $tab_id, 'input_size', __( 'Field Size', 'funnel-builder' ), self::get_input_fields_sizes(), '12px' );

			$field_typography = [
				'.bwfac_form_sec .wffn-optin-input::placeholder',
				'.bwfac_form_sec .wffn-optin-input',
			];

			$this->add_typography( $tab_id, 'field_typography', implode( ',', $field_typography ), __( 'Field Typography' ) );
			$this->add_border( $tab_id, 'field_border', '.bwfac_form_sec .wffn-optin-input' );


		}

		public static function get_input_fields_sizes() {
			return [
				'6px'  => __( 'Small', 'funnel-builder' ),
				'9px'  => __( 'Medium', 'funnel-builder' ),
				'12px' => __( 'Large', 'funnel-builder' ),
				'15px' => __( 'Extra Large', 'funnel-builder' ),
			];
		}

		protected function get_post_type() {
			return WFOPP_Core()->optin_pages->get_post_type_slug();
		}

		protected function setup_data( $post = null ) {

			if ( isset( $_GET['ct_builder'] ) && isset( $_GET['oxy_wffn_optin_id'] ) && ! isset( $_GET['oxygen_iframe'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return false;
			}

			parent::setup_data( $post );

			if ( is_null( $post ) ) {
				return false;
			}
			WFOPP_Core()->optin_pages->set_id( $post->ID );
			WFOPP_Core()->optin_pages->setup_options();

			return true;
		}

		protected function get_module_post( $post ) {
			if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['selected_type'] ) && 'wffn_op_save_design' === $_REQUEST['action'] && 'oxy' === $_REQUEST['selected_type'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post = ! empty( absint( $_REQUEST['wfop_id'] ) ) ? get_post( absint( $_REQUEST['wfop_id'] ) ) : $post; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			return $post;
		}

	}
}