<div class="notice notice-error quick_adsense_adstxt_adsense_notice is-dismissible" style="padding: 15px;">
	<p><b>Quick Adsense</b> had detected that your ads.txt file does not have all your Google Adsense Publisher IDs.<br />This will severely impact your adsense earnings and your immediate attention is required.</p>
	<p>Your recommended google entries for ads.txt is as given below.<br />You can manually copy this to your ads.txt file or 
		<?php if ( ( ! quick_adsense_get_value( $args, 'is_ajax' ) ) && ( 'toplevel_page_quick-adsense' !== quick_adsense_get_value( $args, 'screen_id' ) ) ) { ?>
			<a href="<?php echo esc_url( admin_url( '/admin.php?page=quick-adsense#quick_adsense_adstxt_adsense_auto_update' ) ); ?>">CLICK HERE</a>
		<?php } else { ?>
			<a href="#" onclick="quick_adsense_adstxt_adsense_auto_update()">CLICK HERE</a>
		<?php } ?>
	&nbsp;to instruct Quick Adsense to try and add the entries automatically.</p>
	<p><code style="display: block; padding: 2px 10px;"><?php echo esc_html( quick_adsense_get_value( $args, 'adstxt_new_adsense_entries' ) ); ?></code></p>
	<p><small><i><b>We recommend you not to dismiss this notice for continued daily ads.txt monitoring.  This notice will stop appearing automatically once Quick Adsense detects correct entries in ads.txt (rechecked daily).</b></i></small></p>
	<div class="clear"></div>
	<?php if ( quick_adsense_get_value( $args, 'is_ajax' ) ) { ?>
		<button type="button" class="notice-dismiss" onclick="javascript:jQuery(this).parent().remove()"><span class="screen-reader-text">Dismiss this notice.</span></button>
	<?php } ?>
</div>
