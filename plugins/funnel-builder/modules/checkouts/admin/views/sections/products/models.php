<?php
defined( 'ABSPATH' ) || exit;
?>
<!-- add product modal start-->
<div class="wfacp_izimodal_default" id="modal-add-product">
    <div class="sections">
        <form id="modal-add-product-form" data-bwf-action="add_product" v-on:submit.prevent="onSubmit">
            <div class="wfacp_vue_forms">
                <fieldset>
                    <div class="form-group ">
                        <div id="product_search">
                            <div class="wfacp_pro_label_wrap wfacp_clearfix">
                                <div class="wfacp_select_pro_wrap"><label><?php _e( 'Select a Product', 'woofunnels-aero-checkout' ); ?></label></div>
                            </div>
                            <multiselect v-model="selectedProducts" id="ajax" label="product" track-by="product" placeholder="Type to search" open-direction="bottom" :options="products" :multiple="<?php echo( 'true' ); ?>" :searchable="true" :loading="isLoading" :internal-search="true" :clear-on-select="false" :close-on-select="true" :options-limit="300" :limit="3" :max-height="600" :show-no-results="true" :hide-selected="true" @search-change="asyncFind">
                                <template slot="clear" slot-scope="props">
                                </template>
                                <span slot="noResult"><?php _e( 'Oops! No elements found. Consider changing the search query.', 'woofunnels-aero-checkout' ); ?></span>
                            </multiselect>
                        </div>
                    </div>
                </fieldset>
				<fieldset>
					<div class="bwf_form_submit wfacp_swl_btn">
						<input data-iziModal-close type="button" class="wf_cancel_btn wfacp_btn" value="<?php esc_html_e( 'Cancel', 'woofunnels-aero-checkout' ); ?>"/>
						<input type="submit" class="wfacp_btn wfacp_btn_primary" value="<?php _e( 'Add Product', 'woofunnels-aero-checkout' ); ?>"/>
					</div>
				</fieldset>
            </div>
        </form>
    </div>
</div>
<!-- add product modal end-->
