<?php
  function sktdonation_frondendbackground(){
?>
<div id="skt-donations-tab-6" class="skt-donations-tab-content <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab6' ) ) { ?> skt-donations-current <?php } ?>">
	<div class="skt-donations-radio">
		<span><?php esc_attr_e('Button Background Color :','skt-donation'); ?></span>
	    <label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_fend_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_fend_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Button Background Hover Color :','skt-donation'); ?></span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_fend_hover_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_fend_hover_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Tab Menu Background Color :','skt-donation'); ?></span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_fend_menu_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_fend_menu_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Tab Hover Menu Background Color :','skt-donation'); ?></span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_fend_menu_hover_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_fend_menu_hover_backgroundcolor') ); ?>" />
		</label></br></br>
		<span><?php esc_attr_e('Background Color For Form','skt-donation'); ?> </span>
		<label class="skt_radio_inline">
	    	<input type="text" name="skt_donation_fend_form_backgroundcolor" class="color-field" value="<?php echo esc_attr( get_option('skt_donation_fend_form_backgroundcolor') ); ?>" />
		</label></br></br>
	</div>
</div>
<?php }
	$sktdonation_frondendbackground = sktdonation_frondendbackground();
?>