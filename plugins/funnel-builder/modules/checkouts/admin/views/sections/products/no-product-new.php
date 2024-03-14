<div class="wfacp_welcome_card" v-if="isEmpty()">

    <div class="bwf-zero-state">
        <div class="bwf-zero-state-wrap">
            <div class="bwf-zero-sec bwf-zero-sec-icon bwf-pb-gap">
                <img src="<?php echo esc_url( plugin_dir_url( WFACP_PLUGIN_FILE ) . 'admin/assets/img/zero-state/funnel.svg'); ?>"/>
            </div>
            <div class="bwf-zero-sec bwf-zero-sec-content bwf-h2 bwf-pb-10">
                <div><?php _e( 'Add a product to the checkout page', 'woofunnels-aero-checkout' ) ?></div>
            </div>
            <div class="bwf-zero-sec bwf-zero-sec-content bwf-pb-gap">
                <div class="bwf-h4-1"><?php _e( 'You can sell as many product as you want. If you intend to create global checkout for your storefront skip this step.', 'woofunnels-aero-checkout' ) ?></div>
                <div class="wf_funnel_clear_10"></div>
            </div>
            <div class="bwf-zero-sec bwf-zero-sec-buttons">
                <a href="#" class="wfacp_btn wfacp_btn_primary wfacp_modal_open" data-izimodal-open="#modal-add-product">
                        <?php _e( 'Add Product', 'woofunnels-aero-checkout' ) ?>
                </a>
            </div>
        </div>
    </div>
</div>