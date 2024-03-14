<?php global $wdp_premium_link, $wdp_url; ?>
<div class="error_message">

    <form method="post" action="">

        <?php wp_nonce_field( 'wcdp_cc', 'wcdp_cc_field' ); ?>


	    <?php if(!$wdp_pro): ?>
            <div class="alert alert-warning text-center mt-3">
			    <a href="<?php echo esc_url($wdp_premium_link); ?>" target="_blank"><?php _e( 'Go Premium for this Feature', "wcdp" ); ?></a>
            </div>
            <div>
                <a href="<?php echo esc_url($wdp_premium_link); ?>" target="_blank"><img src="<?php echo esc_url($wdp_url); ?>/images/pro-features.gif" /></a>
            </div>
	    <?php else: ?>

        <div class="row mt-4">

            <div class="col-md-3">
                <label for="wcdp_define_discount"><?php _e( 'Define global discount criteria', "wcdp" ); ?></label>
            </div>

            <div class="col-md-5 wcdp_global_criteria">


            </div>

        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <p class="submit"><input type="submit" name="wcdp_submit_global_criteria" class="btn btn-primary" value="<?php _e( 'Save Changes', "wcdp" ); ?>" /></p>
            </div>
        </div>

        <?php endif; ?>



    </form>

</div>