<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Oxygen_WFFN_Optin_Form_Widget
 */
if ( ! class_exists( 'Oxygen_WFFN_Optin_Form_Widget' ) ) {

	#[AllowDynamicProperties]

  class Oxygen_WFFN_Optin_Form_Widget extends WFFN_Optin_HTML_Block_Oxy {
		public $slug = 'wffn_optin_oxy_form';
		public $form_sub_headings = [];
		protected $id = 'wffn_optin_oxy_checkout_form';

		/**
		 * Oxygen_WFFN_Optin_Form_Widget constructor.
		 */
		public function __construct() {
			$this->name = __( 'Optin Form', 'woofunnels-aero-checkout' );
			add_filter( 'body_class', [ $this, 'add_body_class' ] );


			parent::__construct();
		}


		public function name() {
			return $this->name;
		}

		public function add_body_class( $classes ) {
			$classes[] = 'wfacp_oxygen_template';


			return $classes;
		}

		/**
		 * @param $template WFACP_Template_Common;
		 */
		public function setup_controls() {

			$this->register_form_fields();
			$this->register_form_styles();
			$this->button_settings();
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

			$this->add_heading( $tab_id, __( 'Typography', 'funnel-builder' ) );

			$this->custom_typography( $tab_id, 'button_text_typo', '.bwfac_form_sec #wffn_custom_optin_submit .bwf_heading', __( 'Title Typography', 'funnel-builder' ) );


			$this->add_heading( $tab_id, __( 'Color', 'funnel-builder' ) );

			$this->add_color( $tab_id, 'button_text_color', '.bwfac_form_sec #wffn_custom_optin_submit .bwf_heading', __( 'Text', 'funnel-builder' ) );
			$this->add_background_color( $tab_id, 'button_bg_color', '.bwfac_form_sec #wffn_custom_optin_submit', '#FBA506' );
			$this->add_color( $tab_id, 'button_text_color_hover', '.bwfac_form_sec #wffn_custom_optin_submit:hover .bwf_heading', __( 'Text Hover', 'funnel-builder' ) );
			$this->add_background_color( $tab_id, 'button_bg_color_hover', '.bwfac_form_sec #wffn_custom_optin_submit:hover', '#FBA506', __( 'Background Hover', 'funnel-builder' ) );


			$this->add_heading( $tab_id, __( 'Advanced', 'funnel-builder' ) );
			$this->add_width( $tab_id, 'button_width', '.bwfac_form_sec #wffn_custom_optin_submit' );

			$this->add_text_alignments( $tab_id, $key . '_button_alignment', '.wffn-custom-optin-from #bwf-custom-button-wrap', __( 'Alignment', 'funnel-builder' ) );
			$this->add_text_alignments( $tab_id, $key . '_button_text_alignment', '.wffn-custom-optin-from #bwf-custom-button-wrap span', __( 'Text Alignment', 'funnel-builder' ) );

			$this->add_heading( $tab_id, __( 'Spacing', 'funnel-builder' ) );
			$this->add_margin( $tab_id, 'button_text_padding', '.bwfac_form_sec #wffn_custom_optin_submit' );
			$this->add_padding( $tab_id, 'button_text_margin', '.bwfac_form_sec #wffn_custom_optin_submit' );

			$this->add_border( $tab_id, 'bwf_button_border', '.bwfac_form_sec #wffn_custom_optin_submit' );
			$this->add_box_shadow( $tab_id, 'bwf_button_shadow', '.bwfac_form_sec #wffn_custom_optin_submit' );

			$this->add_typography( $tab_id, 'button_subheading_text_typo', '.bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading', 'Sub Title Typography' );

			if ( $tab_heading === 'Popup Inline Button' ) {
				$this->add_typography( $tab_id, 'popup_footer_text_typography', '.bwf_pp_cont .bwf_pp_footer', 'Text After Button Typography' );
			}

		}


		public function defaultCSS() {


			$defaultCSS = "
				body .bwfac_form_sec .wffn-optin-input, 
				body .bwfac_form_sec .wffn-optin-input::placeholder{
					color: #3F3F3F;
				}
                .oxy-optin-form{
                    width: 100%
				}
				.wffn-custom-optin-from .bwfac_form_sec.submit_button{
			        clear:both;
				}
				.bwfac_form_sec.wffn-sm-100{
					float:left;
				}
                .wfop_section.single_step:after{
                    clear: both;
                }
                .wfop_section.single_step:after,
                .wfop_section.single_step:before{
                    content: '';
                    display: block;
                }				
				body .bwfac_form_sec .wffn-optin-input{
					font-size: 16px;
					font-weight: 400;
					background-color: #ffffff;
					border:2px solid #d8d8d8;
					border-radius: 0px 0px 0px 0px;
					padding: 12px 15px;
				}			
				body .bwfac_form_sec{
				    padding-right: calc(10px / 2);
				    padding-left: calc(10px / 2);
				    margin-bottom: 10px;
				}
				body .bwfac_form_sec .wfop_input_cont {
				    margin-top: 0px;
				}
				body .bwfac_form_sec #wffn_custom_optin_submit {
				    background-color: #FBA506;
				    padding: 15px 15px 15px 15px;
				    margin: 15px 0px 25px 0px;
				    border: 2px solid #E69500;
				    border-radius: 0px 0px 0px 0px;
				    width:100%;
				}				
				body .bwfac_form_sec #wffn_custom_optin_submit .bwf_heading,
				body .bwfac_form_sec #wffn_custom_optin_submit .bwf_subheading {
				    color: #ffffff;
				}				
				body .bwfac_form_sec #wffn_custom_optin_submit:hover {
				    background-color: #E69500;
				}
				body .wffn-optin-form .wffn-custom-optin-from .bwfac_form_sec label {
				    display:block;width:auto;
				}";

			return $defaultCSS;

		}


	}

	new Oxygen_WFFN_Optin_Form_Widget;
}