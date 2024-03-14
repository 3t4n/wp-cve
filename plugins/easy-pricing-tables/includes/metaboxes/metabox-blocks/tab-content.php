<div id="dh_ptp_tabs_1" class="dh_ptp_tab">
	<?php if ( defined( 'PTP_EDD_INTEGRATION' ) ) {
			echo '<div class="ptp-dialog" id="ptp-edd-dialog" style="display: none;" title="' . __('Easy Digital Downloads', 'easy-pricing-tables') . '">';
				dh_ptp_print_downloads();
			echo '</div>';
		}
	?>
	
	<?php if ( defined( 'PTP_WOO_INTEGRATION' ) ) {
			echo '<div class="ptp-dialog" id="ptp-woo-dialog" style="display: none;" title="' . __('WooCommerce Products', 'easy-pricing-tables') . '">';
				dh_ptp_print_woo_products();
			echo '</div>';
		}
	?>
	
	<?php if ( defined( 'PTP_SPP_INTEGRATION' ) ) {
			echo '<div class="ptp-dialog" id="ptp-spp-dialog" style="display: none;" title="' . __('Checkout Forms', 'easy-pricing-tables') . '">';
				dh_ptp_print_spp_products();
			echo '</div>';
		}
	?>	

    <a href="#" style="float:right;" id="ptp-new-column" class="docopy-column button button-large ptp-icon-plus"><?php _e('New Column', 'easy-pricing-tables'); ?></a>
    <?php if ( defined ( 'PTP_SCP_INTEGRATION' ) ) { ?>
		<?php $mb->the_field('scp-enabled'); ?>
		<div id="ptp-scp-switch">
			<h4 style="display:inline;"><?php _e('Simple Pay Pro Checkout', 'easy-pricing-tables')?></h4>
			<label class="switch">
				<input class="switch-input" id="ept-scp-enable-toggle" type="checkbox" name="<?php esc_attr( $mb->the_name() ); ?>" value="enabled" <?php $mb->the_checkbox_state('enabled') ?>/>
				<span class="switch-label" data-on="On" data-off="Off"></span>
				<span class="switch-handle"></span>
			</label>
		</div>
	<?php } ?>
	
	<h4><?php _e('Pricing Table Columns', 'easy-pricing-tables'); ?></h4>
	
	<div style="clear: both; margin-bottom:10px;"></div>

    <div id="all-columns-wrap">
        <div class="tt-ptp-column zero">
			<?php if ( defined ( 'PTP_WOO_INTEGRATION' ) OR defined( 'PTP_EDD_INTEGRATION' ) OR defined( 'PTP_SPP_INTEGRATION' ) ) { ?>
				<div class="ptp-settings-spacer" style='height: 45px;'>&nbsp;</div>
			<?php } ?>	
			<?php if ( defined ( 'PTP_SCP_INTEGRATION' ) ) { ?>
				<ul class="ptp-settings ptp-scp-sub-explanation">
				<?php if ( defined ( 'PTP_SCP_SUBS_ACTIVE' ) ) { ?>
					<li class="explaination-desc ptp-scp-plan-id-desc">
					<strong><?php _e('Stripe Plan ID', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Stripe Plan ID', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
					<?php _e('Enter the corresponding Stripe Plan ID if using subscriptions.', 'easy-pricing-tables'); ?>
																	   "></i><br><span class='ptp-optional-text'><?php _e('(Optional)', 'easy-pricing-tables') ?></span>
					</li>
				<?php } ?>
				
				</ul>
			<?php } ?>
				
            <div class="ptp-title explaination-title">
                <strong><?php _e('Name', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Product or Plan Name', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                <?php _e('Enter your product name or plan name here.', 'easy-pricing-tables'); ?>
                                                                      &lt;br/&gt;&lt;br/&gt; 
                                                                      &lt;strong&gt;<?php _e('Best practice:', 'easy-pricing-tables'); ?>&lt;/strong&gt;
                                                                      &lt;br/&gt;
                                                                      <?php echo htmlspecialchars(__('Avoid generic names such as <em>Bronze</em>, <em>Silver</em> and <em>Gold</em>.<br/>', 'easy-pricing-tables')); ?>
                                                                      <?php echo htmlspecialchars(__('Instead, choose aspirational names like <em>Personal</em>, <em>Small Business</em> and <em>Enterprise</em>.<br/><br/>', 'easy-pricing-tables')); ?>
                                                                      <?php echo htmlspecialchars(__('Many people choose plans based on names, not features: A corporate buyer might choose <em>Enterprise</em> even though <em>Personal</em> might be sufficient for his use-case.', 'easy-pricing-tables')); ?>
                                                                      "></i>
            </div>
            <ul class="ptp-settings explaination-settings">
				
                <li class="explaination-desc planprice">
                    <strong><?php _e('Pricing', 'easy-pricing-tables'); ?></strong> <span style="display: inline;" class='ptp-optional-text ptp-optional-price-label-toggle'><?php _e('(Optional)', 'easy-pricing-tables') ?></span><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Pricing', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('Enter your pricing options here.', 'easy-pricing-tables'); ?>
                                                                        "></i>
                </li>
                <li class="ptp-design4-content explaination-desc">
                    <strong><?php _e('Description', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Optional Description', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('Additional note on front part of pricing circle.', 'easy-pricing-tables'); ?>
                                                                            "></i><span style="display: inline;" class='ptp-optional-text ptp-optional-description-label-toggle'><?php _e('(Optional)', 'easy-pricing-tables') ?></span>
                </li>
                <li class="ptp-comparison-content explaination-desc ptp-billing-timeframe-content">
                    <strong><?php _e('Bill Cycle', 'easy-pricing-tables'); ?> </strong><span style="display: inline;" class='ptp-optional-text ptp-optional-timeframe-label-toggle'><?php _e('(Optional)', 'easy-pricing-tables') ?></span><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Bill Cycle', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('Specify a period for billing.', 'easy-pricing-tables'); ?>
                                                                                  "></i>
                </li>
                <li class="features explaination-desc">
                    <strong><?php _e('Features', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Features', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('Enter your features here (one per line).', 'easy-pricing-tables'); ?>
                                                                              &lt;br/&gt;&lt;br/&gt; 
                                                                              &lt;strong&gt;<?php _e('Best practice:', 'easy-pricing-tables'); ?>&lt;/strong&gt;
                                                                              &lt;br/&gt;
                                                                              <?php _e("Don't overwhelm users with too much content. Long pricing tables are confusing and difficult to read.", 'easy-pricing-tables'); ?>
                                                                              "></i>
                </li>
                <li class="ptp-comparison-content explaination-desc ptp-comparison-table">
                    <?php $mb->the_field('comparison-table-features'); ?>
                    <?php
                    $placeholder = array(
                        str_pad(__('# of Website', 'easy-pricing-tables'), 50),
                        str_pad(__('# of Visits', 'easy-pricing-tables'), 50),
                        str_pad(__('Data Transfer', 'easy-pricing-tables'), 50),
                        str_pad(__('Storage', 'easy-pricing-tables'), 50),
                    );
                    ?>
                    <textarea name="<?php esc_attr( $mb->the_name() ); ?>" class="form-control comparison1-textarea" rows="7" placeholder="<?php echo implode('', $placeholder); ?>"><?php echo $mb->the_value(); ?></textarea><br/>
                </li>
                <li class="ptp-standard-content explaination-desc ptp-button-text-desc" style="margin-top:15px">
                    <strong><?php _e('Button Text', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Call To Action Text', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('Enter your call to action text here.', 'easy-pricing-tables'); ?>
                                                                            &lt;br/&gt;&lt;br/&gt; 
                                                                            &lt;strong&gt;<?php _e('Best practice:', 'easy-pricing-tables'); ?>&lt;/strong&gt;
                                                                            &lt;br/&gt;
                                                                            <?php _e('Here are some of the highest converting variations:', 'easy-pricing-tables'); ?>&lt;br/&gt; 
                                                                            * <?php _e('Add To Cart', 'easy-pricing-tables'); ?>&lt;br/&gt; 
                                                                            * <?php _e('Sign Up', 'easy-pricing-tables'); ?>&lt;br/&gt; 
                                                                            * <?php _e('Sign Up Free', 'easy-pricing-tables'); ?>&lt;br/&gt; 
                                                                            * <?php _e('Start Free Trial', 'easy-pricing-tables'); ?>"></i>
                </li>
                <li class="explaination-desc ptp-button-url-desc">
                    <strong><?php _e('Button URL', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Call To Action URL', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('Enter your call to action URL here. This is usually either a payment link (e.g. PayPal) or a page where users can create an account.', 'easy-pricing-tables'); ?>
                                                                           "></i>
                </li>
				<li class="explaination-desc ptp-button-mode-desc" style="display: none;">
                    <strong><?php _e('Button Action', 'easy-pricing-tables'); ?></strong><i class="ptp-icon-help-circled" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Button Click Mode', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
                    <?php _e('You can toggle between using WP Simple Pay Pro for Stripe to checkout instantly with Stripe when the call to action button is clicked, or turn this feature off and use a custom URL.', 'easy-pricing-tables'); ?>
                                                                           "></i>
                </li>
				
				
            </ul>
        </div>

        <?php
       /*
        * check if this pricing table is already exist will return number of column as user did setting
        * otherwise if not exist yet, will initialize 2 columns  
        */
//        $checkIsExistMeta = get_post_meta(get_the_ID(), $id, TRUE);
        if ($meta) {
            $options = array();
               
        } else {
            $options = array('length' => 2);
        }

         /**
         * the loop used to display our tables
         */
        while ($mb->have_fields_and_multi('column', $options)):
            ?>
            <?php $mb->the_group_open(); ?>
            <div class="tt-ptp-column">
				<div class='ptp-integrations'>
					<?php if ( defined ( 'PTP_EDD_INTEGRATION' ) ) {
						echo '<button type="button" class="ptp-edd-button button-secondary" data-trigger="hover" data-html="true" data-placement="top" data-content="' . __('Click to get data from Easy Digital Downloads', 'easy-pricing-tables') . '">'; 
							echo '<img style="margin-top: 5px;" src="' . PTP_PLUGIN_PATH_FOR_SUBDIRS . '/includes/integrations/edd/edd.png' . '">';
						echo '</button>';
						}
					?>
					
					<?php if ( defined ( 'PTP_WOO_INTEGRATION' ) ) {
						echo '<button type="button" class="ptp-woo-button button-secondary" data-trigger="hover" data-html="true" data-placement="top" data-content="' . __('Click to get data from WooCommerce', 'easy-pricing-tables') . '">'; 
							echo '<img style="margin-top: 5px; max-width: 16px;" src="' . PTP_PLUGIN_PATH_FOR_SUBDIRS . '/includes/integrations/woocommerce/woo.png' . '">';
						echo '</button>';
						}
					?>
					<?php if ( defined ( 'PTP_SPP_INTEGRATION' ) ) {
						echo '<button type="button" class="ptp-spp-button button-secondary" data-trigger="hover" data-html="true" data-placement="top" data-content="' . __('Click to get data from Simple Pay Pro', 'easy-pricing-tables') . '">'; 
							echo '<img style="margin-top: 5px; max-width: 16px;" src="' . PTP_PLUGIN_PATH_FOR_SUBDIRS . '/includes/integrations/simplepay3/stripe.ico' . '">';
						echo '</button>';
						}
					?>
				</div>
				
				<?php if ( defined ( 'PTP_SCP_SUBS_ACTIVE' ) ) { ?>
					<div class='ptp-integrations scp-subscriptions-integration'>
					<ul class="plan-settings stripe-settings">
							<li class="ptp-stripe-plan-id-input-wrapper">
								<?php $mb->the_field('stripeplanid'); ?>
								<?php wp_nonce_field( 'dh-ptp-get-stripe-plan-id', 'dh-ptp-get-stripe-plan-id-nonce' ); ?>
								<input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. my-plan', 'easy-pricing-tables'); ?>" value="<?php $mb->the_value(); ?>" class="form-control dh-php-stripe-plan-id">
								<span style='display: none;' class='ptp-stripe-icon dashicons dashicons-image-rotate ptp-spin'></span>
							</li>
					</ul>
					</div>
				<?php } ?>
				
                <div class="ptp-title plan-title">

    <?php $mb->the_field('feature'); ?>
                    <input type="hidden" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php if (!is_null($metabox->get_the_value())) {
        $mb->the_value();
    } elseif (!$mb->is_last()) {
        echo "unfeatured";
    } ?>" class="form-control" />

                    <a onClick="buttonHandler(this)" class="button button-small feature-button <?php if ($mb->get_the_value() == "featured") {
        echo "ptp-icon-star";
    } else {
        echo "ptp-icon-star-empty";
    } ?>" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Feature This Column', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="<?php _e("Click this button to feature this column. A featured column appears bigger and includes the wording 'Most Popular'. You can only feature one column per table.", 'easy-pricing-tables'); ?>"><?php _e('Feature', 'easy-pricing-tables'); ?></a>
                    <button class="button button-small dodelete ptp-icon-trash" id="delete-button" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Delete This Column', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="<?php _e('Click this button to delete this column.', 'easy-pricing-tables'); ?>"><?php _e('Delete', 'easy-pricing-tables'); ?></button>

                        <?php $mb->the_field('planname'); ?>
                    <input id="plan-name" type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. Small Business', 'easy-pricing-tables'); ?>" value="<?php echo $mb->the_value(); ?>" class="form-control">
                </div>

                <ul class="ptp-settings plan-settings">
                    <li>
    <?php $mb->the_field('planprice'); ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. $49/mo', 'easy-pricing-tables'); ?>" value="<?php echo esc_attr( $mb->the_value() ); ?>" class="form-control planprice">
                    </li>
                    <li class="ptp-design4-content">
    <?php $mb->the_field('optionaldescription'); ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. Per Month', 'easy-pricing-tables'); ?>" value="<?php echo esc_attr( $mb->the_value() ); ?>" class="form-control">
                    </li>
                    <li class="ptp-comparison-content ptp-billing-timeframe-content">
    <?php $mb->the_field('billingtimeframe'); ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. Monthly', 'easy-pricing-tables'); ?>" value="<?php echo esc_attr( $mb->the_value() ); ?>" class="form-control ptp-interval-input">
                    </li>
                    <li class="ptp-comparison-content ptp-billing-timeframe-content-spacer">
                        <div style="height: 27px;">&nbsp;</div>
                    </li>
                    <li class="features">
                        <?php $mb->the_field('planfeatures'); ?>
                        <?php
                        $placeholder = array(
                            str_pad(__('e.g. 1 Website', 'easy-pricing-tables'), 40),
                            str_pad(__('30,000 Monthly Visits', 'easy-pricing-tables'), 40),
                            str_pad(__('Unlimited Data Transfer', 'easy-pricing-tables'), 40),
                            str_pad(__('5GB Storage', 'easy-pricing-tables'), 40),
                        );
                        ?>
                        <textarea name="<?php esc_attr( $mb->the_name() ); ?>" class="form-control" rows="7" placeholder="<?php echo implode('', $placeholder); ?>"><?php echo empty( $mb->the_value() ) ? '' : esc_textarea( $mb->the_value() ); ?></textarea>
                    </li>

                    <li class="ptp-standard-content">
                        <?php $mb->the_field('buttontext'); ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. Add to Cart', 'easy-pricing-tables'); ?>" value="<?php esc_attr( $mb->the_value() ); ?>" class="form-control ptp-buy-button-text-input">
                    </li>

                  	<?php $mb->the_field('buttonurl'); ?>
					<?php if ( defined ( 'PTP_SCP_INTEGRATION' ) ) { ?>
						<p class='ptp-scp-url-toggle' data-on='<?php _e('Stripe Description', 'easy-pricing-tables')?>' data-off='<?php _e('Button URL', 'easy-pricing-tables')?>'></p><i class="ptp-icon-help-circled ptp-url-tooltip ptp-buy-button-tooltip-on" style="display:none" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Stripe Description', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
						<?php _e('Description shown in checkout form.  Defaults to Plan Name.  Disable this setting to allow a custom URL.', 'easy-pricing-tables'); ?>"></i><i class="ptp-icon-help-circled ptp-url-tooltip ptp-buy-button-tooltip-off" style="display:none" data-trigger="hover" data-html="true" data-placement="right" data-original-title="&lt;strong&gt;<?php _e('Call To Action URL', 'easy-pricing-tables'); ?>&lt;/strong&gt;" data-content="
						<?php _e('Enter your call to action URL here. This is usually either a payment link (e.g. PayPal) or a page where users can create an account.', 'easy-pricing-tables'); ?>
                                                                           "></i>
					<?php } ?>
	
                    <input type="text" placeholder="<?php _e('e.g. http://example.com/buy', 'easy-pricing-tables'); ?>" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $mb->the_value() ); ?>" class="form-control ptp-buy-button-input">
					
					<?php if ( defined ( 'PTP_SCP_INTEGRATION' ) ) { ?>
						
						<?php $mb->the_field('stripedesc'); ?>
						<input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" placeholder="<?php _e('e.g. My Product - Small Business', 'easy-pricing-tables'); ?>" value="<?php esc_attr( $mb->the_value() ); ?>" class="form-control ptp-scp-desc-input">
				
					<?php } ?>
                    
                </ul>
            </div>
    <?php $mb->the_group_close(); ?>
<?php endwhile; ?>

    </div>
    <div style="clear:both;"></div>
    <p class="ptp-comparison-content"><?php _e('<b>Note:</b> Use shortcodes [check] and [times] to use yes and no icons.', 'easy-pricing-tables'); ?></p>
</div>
<script>
    jQuery( function( $ ) {
        $( '.wpa_group.wpa_group-column' ).each( function( index ) {
            $( this ).attr( 'id', 'ept-table-column[' + ( index ) + ']' );
        } );
    } );
</script>
