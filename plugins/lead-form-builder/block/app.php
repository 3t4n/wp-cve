<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Exit if accessed directly.
if ( ! class_exists( 'Lead_Form_Builder_Blocks' ) ) {
			class Lead_Form_Builder_Blocks {


				function __construct()
				{

					add_action( 'wp_ajax_lead_form_builderr_data', array( $this, 'lead_form_builder') );

				}

				public function lead_form_builder() {
					
					$lfb = New LFB_SAVE_DB;
					if(isset( $_POST['data'] )){
					$formid = intval(json_decode( wp_unslash( $_POST['data'] ))->data);
					$title = sanitize_text_field(  json_decode( wp_unslash( $_POST['data'] ))->title );
					$rander_form = do_shortcode('[lead-form form-id='.$formid.' new_title="'.$title.'"]');

					$fid_new = $lfb->get_single_lead_form($formid);

					if($rander_form==='' && $fid_new){
						$rander_form = do_shortcode('[lead-form form-id='.$fid_new.' new_title="'.$title.'"]');
						$formid = $fid_new;
					}


							wp_send_json_success( array('fid'=>$formid,'lfb_form' => $lfb->lfb_get_all_form_title(),'lfb_rander' => $rander_form));
					} else{
					wp_send_json_success( array('status'=>false) );

					}
				}
		}
}

function lead_form_builder_block_init() {
	New Lead_Form_Builder_Blocks;
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'lead_form_builder_block_init' );


