<div class="error_message">

    <form method="post" action="">

        <?php wp_nonce_field( 'wcdp_cc', 'wcdp_cc_field' ); ?>

        <div class="inside wcdp_settings">

            <!--<div class="row mt-3">
                <div class="col-md-12">
                    <div class="h5">
	                    <?php /*_e( 'Error Messages:', "wcdp" ); */?>
                    </div>
                </div>
            </div>-->

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="h6 alert alert-dark">
	                    <?php _e( 'Discount Available Conditionally?', "wcdp" ); ?>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <strong><label for="wcdp_error_no_shipping" class="mb-3"><?php _e( 'Discounts With No Shipping > Switch to Shipping', "wcdp" ); ?></label></strong>
                        <textarea class="form-control" rows="3" id="wcdp_error_no_shipping" name="wcdp_dac_error_messages[no_shipping][0]"><?php echo esc_textarea($error_messages['no_shipping'][0]); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="form-group">
                        <strong><label for="wcdp_error_no_shipping_1" class="mb-3"><?php _e( 'Discounts With No Shipping > Switch to Discounts', "wcdp" ); ?></label></strong>
                        <textarea class="form-control" rows="3" id="wcdp_error_no_shipping_1" name="wcdp_dac_error_messages[no_shipping][1]"><?php echo esc_textarea($error_messages['no_shipping'][1]); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="form-group">
                        <strong><label for="wcdp_error_only_shipping" class="mb-3"><?php _e( 'Discounts With Shipping Only > Switch to Shipping', "wcdp" ); ?></label></strong>
                        <textarea class="form-control" rows="3" id="wcdp_error_only_shipping" name="wcdp_dac_error_messages[only_shipping][0]"><?php echo esc_textarea($error_messages['only_shipping'][0]); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-8">
                    <div class="form-group">
                        <strong><label for="wcdp_error_only_shipping_1" class="mb-3"><?php _e( 'Discounts With Shipping Only > Switch to Discounts', "wcdp" ); ?></label></strong>
                        <textarea class="form-control" rows="3" id="wcdp_error_only_shipping_1" name="wcdp_dac_error_messages[only_shipping][1]"><?php echo esc_textarea($error_messages['only_shipping'][1]); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-8">
                    <p class="submit"><input type="submit" name="Submit" class="btn btn-primary" value="<?php _e( 'Save Changes', "wcdp" ); ?>" /></p>
                </div>
            </div>


        </div>

    </form>

</div>