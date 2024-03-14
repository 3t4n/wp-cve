<div class="wrap">
	<h2><?php echo  __( 'metaps Payment Setting', 'woo-paydesign' );?></h2>
	<div class="jp4wc-settings metabox-holder">
		<div class="jp4wc-sidebar">
			<div class="jp4wc-credits">
				<h3 class="hndle"><?php echo __( 'metaps PAYMENT for WooCommerce', 'woo-paydesign' ) . ' ' . M4WC_VERSION;?></h3>
				<div class="inside">
					<?php $this->jp4wc_framework->jp4wc_support_notice('https://support.artws.info/forums/forum/wordpress-official/woo-paydesign/');?>
					<hr />
					<?php $this->jp4wc_framework->jp4wc_update_notice();?>
					<hr />
					<?php $this->jp4wc_framework->jp4wc_community_info();?>
					<?php if ( ! get_option( 'wc4jp_admin_footer_text_rated' ) ) :?>
					<hr />
					<h4 class="inner"><?php echo __( 'Do you like this plugin?', 'woo-paydesign' );?></h4>
					<p class="inner"><a href="https://wordpress.org/support/plugin/woo-paydesign/reviews/#postform" target="_blank" title="' . __( 'Rate it 5', 'woo-paydesign' ) . '"><?php echo __( 'Rate it 5', 'woo-paydesign' )?> </a><?php echo __( 'on WordPress.org', 'woocommerce-for-japan' ); ?><br />
					</p>
					<?php endif;?>
					<hr />
                    <p class="wc-paygent-link inner"><?php echo __( 'Created by', 'woo-paydesign' );?>
                        <a href="https://wc.artws.info/?utm_source=jp4wc-settings&utm_medium=link&utm_campaign=created-by" target="_blank" title="Artisan Workshop"><img src="<?php echo WC_METAPS_PLUGIN_URL;?>assets/images/woo-logo.png" title="Artsain Workshop" alt="Artsain Workshop" class="jp4wc-logo" /></a><br />
                    </p>
				</div>
			</div>
		</div>
		<form id="jp4wc-setting-form" method="post" action="">
			<?php wp_nonce_field( 'my-nonce-key','wc-paydesign-setting');?>
			<div id="main-sortables" class="meta-box-sortables ui-sortable">
<?php
	//Display Setting Screen
	settings_fields( 'jp4wc_paydesign_options' );
	$this->jp4wc_framework->do_settings_sections( 'jp4wc_paydesign_options' );
?>
			<p class="submit">
<?php
	submit_button( '', 'primary', 'save_jp4wc_paydesign_options', false );
?>
			</p>
			</div>
		</form>
		<div class="clear"></div>
	</div>
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function ($) {
		// close postboxes that should be closed
		$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
		// postboxes setup
		postboxes.add_postbox_toggles('jp4wc_paydesign_options');
	});
	//]]>
	</script>
</div>
