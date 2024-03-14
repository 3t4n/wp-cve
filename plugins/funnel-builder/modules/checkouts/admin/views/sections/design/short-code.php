<div class="wfacp-short-code-wrapper">
    <div class="wfacp_fsetting_table_head wfacp-scodes-head wfacp_shotcode_tab_wrap">
        <div class="wfacp_clear_20"></div>
        <div class="wfacp-fsetting-header"><?php _e( 'Checkout Form', 'woofunnels-aero-checkout' ); ?></div>
        <div class="wfacp_clear_20"></div>
    </div>

    <!-----  NEW ADDED ACC TO DESIGN  ------->
    <div class=" wfacp_global_settings_wrap wfacp_page_col2_wrap wfacp_shortcodes_designs">
        <div class="wfacp_page_left_wrap" id="wfacp_global_setting_vue">
            <div class="wfacp_loader" style="display: none;"><span class="spinner"></span></div>
            <div class="wfacp-product-tabs-view-vertical wfacp-product-widget-tabs">
                <div class="wfacp-product-tabs-wrapper wfacp-tab-center">
                    <div class="wfacp_embed_form_tab wfacp-tab-title wfacp-tab-desktop-title wfacp-active" data-tab="1" role="tab" aria-controls="wfacp-shortcode-fieldset">
						<?php _e( 'Shortcode', 'wordpress' ) ?>
                    </div>
                </div>
                <div class="wfacp-product-widget-container">
                    <div class="wfacp-product-tabs wfacp-tabs-style-line" role="tablist">
                        <div class="wfacp-product-tabs-content-wrapper">
                            <div class="wfacp_global_setting_inner">
                                <div class="wfacp_vue_forms">
                                    <div class="vue-form-generator">                                        <!---->
                                        <fieldset class="wfacp_embed_fieldset wfacp-activeTab wfacp-shortcode-fieldset" style="display: block;">
                                            <legend><?php _e( 'Form Shortcode', 'wordpress' ) ?></legend>
                                            <div class="wfacp-scodes-row ">
                                                <div class="wfacp-scodes-value">
													<?php
													$wfacp_id = WFACP_Common::get_id();
													$url      = admin_url( 'post.php?post=' . $wfacp_id . '&action=edit' );
													$link     = "<a href='$url'>WordPress Editor</a>";
													?>
                                                    <div class="wfacp-scodes-value-in">
                                                        <div class="wfacp_description">
                                                            <input type="text" value="[wfacp_forms]" style="width:100%;" readonly>
                                                        </div>
                                                        <a href="javascript:void(0)" class="wfacp_copy_text"><svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg><?php _e( 'Copy' ); ?></a>
                                                    </div>
                                                    <p class="hint"><?php _e( 'Use this shortcode to embed the checkout form on this page. Switch to ' . $link . '.', 'woofunnels-aero-checkout' ) ?></p>
                                                </div>
                                            </div>

	                                        <?php
	                                        $should_show_shortcode_with_id = filter_input(INPUT_GET,'funnel_id', FILTER_SANITIZE_NUMBER_INT);
	                                        if ( ! $should_show_shortcode_with_id ) {
		                                        ?>
												<legend><?php _e( 'Embed Form Shortcode', 'woofunnel-aero-checkout' ) ?></legend>
												<div class="wfacp-scodes-row">
												<div class="wfacp-scodes-value">
													<div class="wfacp-scodes-value-in">
														<div class="wfacp_description">
															<input type="text" value="[wfacp_forms id='<?php echo $wfacp_id ?>']" style="width:100%;" readonly>
														</div>
														<a href="javascript:void(0)" class="wfacp_copy_text"><svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20"><path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path></svg><?php _e( 'Copy' ); ?></a>
													</div>
													<p class="hint"><?php _e( 'Use this shortcode to embed the checkout form on other page(s).', 'woofunnels-aero-checkout' ) ?></p>
												</div>
												</div><?php } ?>

                                        </fieldset>
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
<div class="wfacp_success_modal" style="display: none" id="modal-section-success_shortcodes6456" data-iziModal-icon="icon-home"></div>

