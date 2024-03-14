<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wfacp_izimodal_default iziModal " id="modal-edit-field" aria-hidden="false" aria-labelledby="modal-edit-field" role="dialog" style="background: rgb(239, 239, 239); z-index: 999; border-radius: 8px; overflow: hidden; max-width: 640px;min-height:350px;"">
<div id="edit-field-form" class="wfacp_product_swicther_field_wrap">
    <div class="iziModal-header iziModal-noSubtitle" style="background: rgb(109, 190, 69); padding-right: 40px;">
        <h2 class="iziModal-header-title"><?php _e( 'Edit Field', 'woofunnels-aero-checkout' ); ?></h2>
        <div class="iziModal-header-buttons">
            <a href="javascript:void(0)" class="iziModal-button iziModal-button-close" data-izimodal-close=""></a>
        </div>
    </div>

    <div class="iziModal-wrap" style="min-height: 390px;">
        <div class="iziModal-content" style="padding: 0px;">
            <div class="sections">
                <form data-bwf-action="add_field" data-bwf-action="add_field" v-on:submit.prevent="onSubmit">
                    <div class="wfacp_vue_forms">
                        <div class="wfacp_without_form_generator " v-if="current_field_id=='address'">
							<?php
							$this->get_address_field_html( 'billing' );
							?>
                        </div>
                        <div class="wfacp_without_form_generator " v-else-if="current_field_id=='shipping-address'">
							<?php $this->get_address_field_html( 'shipping' ); ?>
                        </div>
                        <div class="" v-else="">
                            <div class="wfacp_edit_field_wrap">
                                <p class="subtitle_wrap" v-if="''!==model_sub_title">{{edit_model_field_label}}<span>{{model_sub_title}}</span></p>
                            </div>
                            <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
                        </div>
						<fieldset>
							<div class="bwf_form_submit wfacp_swl_btn">
								<input data-izimodal-close="" type="button" class="iziModal-button-close wf_cancel_btn wfacp_btn" value="<?php esc_html_e( 'Cancel', 'woofunnels-aero-checkout' ); ?>"/>
								<input type="submit" class="wfacp_btn wfacp_btn_primary wfacp_update_field_btn" value="<?php _e( 'Update', 'woofunnels-aero-checkout' ); ?>"/>
							</div>
						</fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<div class="wfacp_overlay"></div>

<!-- edit Field modal end-->

<!-- add product modal start-->
<div class="wfacp_izimodal_default" id="modal-add-section">
    <div class="sections">
        <form id="add-section-form" data-bwf-action="add_section" v-on:submit.prevent="onSubmit" v-on:keyup.enter="onSubmit">
            <div class="wfacp_vue_forms">
                <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
            </div>
			<fieldset>
				<div class="bwf_form_submit wfacp_swl_btn">
					<button data-iziModal-close class="wf_cancel_btn wfacp_btn" value="cancel"><?php esc_html_e( 'Cancel', 'woofunnels-aero-checkout' ); ?></button>
					<button type="submit" class="wfacp_btn wfacp_btn_primary">{{btn_name}}</button>
				</div>
			</fieldset>
        </form>
    </div>
</div>
<!-- add product modal end-->