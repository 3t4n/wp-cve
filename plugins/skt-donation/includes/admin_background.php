<?php
	function sktdonation_adminbackground(){
?>
<div id="skt-donations-tab-5" class="skt-donations-tab-content <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab5' ) ) { ?> skt-donations-current <?php } ?>">
	<div class="skt-donations-radio">
		<span><?php esc_attr_e('Button Background Color :','skt-donation'); ?></span>
	    <label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_admin_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_admin_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Button Background Hover Color :','skt-donation'); ?></span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_admin_hover_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_admin_hover_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Tab Menu Background Color :','skt-donation'); ?></span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_admin_menu_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_admin_menu_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Pagination Background Color :','skt-donation'); ?></span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_admin_page_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_admin_page_backgroundcolor') ); ?>" />
		</label></br></br>
	</div>
</div>
<?php
	}
	$sktdonation_adminbackground = sktdonation_adminbackground();
?>