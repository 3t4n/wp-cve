<div id="wfacp_optimization_container" class="wfacp_inner_setting_wrap">
    <div class="wfacp_p20_noside wfacp_box_size clearfix">
        <div class="wfacp_wrap_inner wfacp_wrap_inner_offers" style="margin-left: 0px;">
            <div class="wfacp_wrap_r">
                <div class="wfacp-product-tabs-view-vertical wfacp-product-widget-tabs">
                    <div class="wfacp-product-tabs-wrapper wfacp-tab-center ">
                        <div data-tab="3" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Express Checkout Buttons', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="11" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Inline Field Validation', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="12" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Collapsible Optional Field', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="10" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Enhanced Phone Field', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="1" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Google Address Autocompletion', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="2" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Auto Apply Coupons', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="4" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Multistep Field Preview', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="5" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Preferred Countries', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="6" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Time Checkout Expiry', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="7" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Prefill Form for Abandoned Users', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="8" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Auto fill State from Zip Code and Country', 'woofunnels-aero-checkout' ) ?></div>
                        <div data-tab="9" role="tab" class="wfacp-tab-title wfacp-tab-desktop-title"><?php _e( 'Generate URL to populate checkout', 'woofunnels-aero-checkout' ) ?></div>
                    </div>
                    <div class="wfacp-product-widget-container wfacp_optimise_global_setting">
                        <div class="wfacp-product-tabs wfacp-tabs-style-line" role="tablist">
                            <div class="wfacp-product-tabs-content-wrapper">
                                <div class="wfacp_global_setting_inner">
                                    <div class="wfacp_global_container">
                                        <form @change="changed()">
                                            <div class="wfacp_settings_sections">
                                                <vue-form-generator ref="update_optimize_ref" :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
                                            </div>
                                            <div class="bwf_ajax_save_buttons bwf_form_submit">
                                                <button v-on:click.self="save()" type="button" class="wfacp_save_btn_style" style="margin-bottom: 10px;"><?php _e( 'Save Changes', 'woofunnels-aero-checkout' ); ?></button>
                                                <span class="wfacp_spinner spinner"></span>
                                            </div>
                                            <br/>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>