<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wfacp_welcome_wrap" v-if="isEmpty()">
    <div class="wfacp_welcome_wrap_in">
        <div class="wfacp_first_product">
            <div class="wfacp_welc_head">
                <div class="wfacp_welc_icon"><img src="<?php echo WFACP_PLUGIN_URL; ?>/admin/assets/img/clap.png" alt="" title=""/></div>
                <div class="wfacp_welc_title"> <?php _e( 'Add Product', 'woofunnels-aero-checkout' ); ?>
                </div>
            </div>

            <div class="wfacp_welc_text">
                <p><?php _e( 'To generate a checkout page add a product.<br>Tip: You can also mark this page as a Global Checkout when page is ready!', 'woofunnels-aero-checkout' ); ?></p>
            </div>
            <button type="button" class="wfacp_step wfacp_button_add wfacp_button_inline wfacp_modal_open wfacp_welc_btn" data-izimodal-open="#modal-add-product" data-iziModal-title="Create New Funnel Step" data-izimodal-transitionin="fadeInDown">
				<?php _e( 'Add A Product', 'woofunnels-aero-checkout' ); ?>
            </button>
        </div>
    </div>
</div>




