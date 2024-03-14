<?php global $wdp_premium_link, $wdp_url; ?>
<div class="category">

    <form method="post" action="">

        <?php wp_nonce_field( 'wcdp_cc', 'wcdp_cc_field' ); ?>

       <!-- <div class="row mt-3">
            <div class="col-md-12 ">
                <a href="<?php /*echo admin_url(); */?>admin.php?page=wc-settings&tab=plus_discount" class="button primary float-md-right float-left"  target="_blank"><?php /*_e('Default settings', "wcdp"); */?></a>
            </div>
        </div>-->

        <div class="row mt-4">
            <div class="col-md-3">
                <label for="wcdp_pricing_scale_text"><?php _e( 'Pricing Scale Text:', "wcdp" ); ?></label>
            </div>

            <div class="col-md-5">
                <input id="wcdp_pricing_scale_text" class="form-control" type="text" name="wcdp_pricing_scale_text" value="<?php echo esc_attr(get_option('wcdp_pricing_scale_text')); ?>" placeholder="<?php _e( 'Pricing Scale:', "wcdp" ); ?>" />
                
                
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <label for="wcdp_criteria_no"><?php _e( 'Number of Criteria:', "wcdp" ); ?></label>
            </div>

            <div class="col-md-5">
                <input type="text" id="wcdp_criteria_no" name="wcdp_criteria_no" class="form-control" value="<?php echo esc_attr($wdpp_obj->opts-1); ?>" />
                <span style="font-size: 14px; color: lightslategray" class="mt-1">(<?php _e( 'Restrict functional criteria, define as many as you want.', "wcdp" ); ?>)</span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <label for="wcdp_cats"><?php _e( 'Product Categories: (Optional)', "wcdp" ); ?></label>
            </div>

            <div class="col-md-5">
                <select name="wcdp_cats[]" id="wcdp_cats" size="7" class="form-control" style="height:200px; max-width: 100%">

		            <?php if(!empty($categories)){  ?>

                        <option <?php selected(empty($wcdp_cats)); ?> value=""><?php _e( 'Select and define', "wcdp" ); ?></option>

			            <?php foreach($categories as $cat){ ?>

                            <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected(in_array($cat->term_id, $wcdp_cats)); ?>><?php echo esc_html($cat->name); ?></option>

			            <?php } ?>

		            <?php } ?>



                </select>
                
                <a style="display:block; margin:10px 0 0 0" title="<?php _e( 'How multiple categories based discount work?', "wcdp" ); ?>" href="<?php echo esc_url($wdp_url); ?>/images/multiple-categories.png" target="_blank"><img style="height:130px" src="<?php echo esc_url($wdp_url); ?>/images/multiple-categories.png" /></a>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-md-3">
                <label for="wcdp_define_discount"><?php _e( 'Define discount criteria', "wcdp" ); ?></label>
            </div>

            <div class="col-md-5 wcdp_cc">

	            <?php if(!$wdp_pro): ?>
                    <div class="alert alert-warning text-center">
			            <a href="<?php echo esc_url($wdp_premium_link); ?>" target="_blank"><?php _e( 'Go Premium for this Feature', "wcdp" ); ?></a>
                    </div>
                    <div>
                        <a href="<?php echo esc_url($wdp_premium_link); ?>" target="_blank"><img src="<?php echo esc_attr($wdp_url); ?>/images/pro-features.gif" /></a>
                    </div>
	            <?php endif; ?>
                
                

            </div>

        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <p class="submit"><input type="submit" name="Submit" class="btn btn-primary" value="<?php _e( 'Save Changes', "wcdp" ); ?>" /></p>
            </div>
        </div>

    </form>

</div>