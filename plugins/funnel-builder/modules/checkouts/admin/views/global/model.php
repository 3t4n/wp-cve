<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wfacp_success_modal" style="display: none" id="modal-saved-data-success" data-iziModal-icon="icon-home"></div>
<div class="wfacp_izimodal_default" style="display: none" id="modal-checkout-page">
    <div class="sections">
        <form class="wfacp_add_funnel" data-bwf-action="add_new_funnel" id="add-new-form" v-on:submit.prevent="onSubmit">
            <div class="wfacp_vue_forms" id="part-add-funnel">
                <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
            </div>
			<?php
			if ( WFACP_Common::get_id() > 0 ) {
				?>
                <div class="wfacp_checkout_url_disabled_url">
                    <input type="text" disabled v-bind:value="model.base_url+model.post_name"/>
                </div>
			<?php } ?>

			<fieldset>
				<div class="bwf_form_submit wfacp_swl_btn">
					<button data-iziModal-close class="wf_cancel_btn wfacp_btn" value="cancel"><?php esc_html_e( 'Cancel', 'woofunnels-aero-checkout' ); ?></button>
					<button type="submit" class="wfacp_btn wfacp_btn_primary" value="add_new">{{btn_name}}</button>
				</div>
				<div class="wfacp_form_response"></div>
			</fieldset>
        </form>
        <div class="wfacp-funnel-create-success-wrap">
            <div class="wfacp-funnel-create-success-logo">
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                    <span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                </div>
            </div>
            <div class="wfacp-funnel-create-message"><?php _e( 'Page Created Successfully. Launching  Editor...', 'woofunnels-aero-checkout' ); ?></div>
        </div>
    </div>
</div>