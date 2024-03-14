<?php
  function sktdonation_getshortcode(){
?>
<div id="skt-donations-tab-7" class="skt-donations-tab-content <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab7' ) ) { ?> skt-donations-current <?php } ?>">
	<ul class="skt-donations-shortcodes">
		<h3 class="skt-mtop"><?php esc_attr_e('Form Fields','skt-donation');?></h3>
		<li><strong><?php esc_attr_e('Shortcode','skt-donation');?></strong></li>
		<li><code><?php echo esc_attr('[skt-donation]');?></code></li>   
	</ul>
</div>
<div id="skt-donations-tab-8" class="skt-donations-tab-content <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab8' ) ) { ?> skt-donations-current <?php } ?>">
	<div class="skt_donation_manage_email_setting">
		<label><?php esc_attr_e('Users Receive Email From / Enter Email ID','skt-donation');?></label>
		<input type="text" name="skt_donation_skt_email_address" value="<?php echo esc_attr( get_option('skt_donation_skt_email_address') ); ?>">
		<label><?php esc_attr_e('Subject','skt-donation');?></label>
		<input type="text" name="skt_donation_skt_email_subject" value="<?php echo esc_attr( get_option('skt_donation_skt_email_subject') ); ?>">
		<label><?php esc_attr_e('Message','skt-donation');?></label>
		<textarea class="sktmesgearea" rows="15" cols="50" name="skt_donation_skt_email_message"><?php echo esc_attr( get_option('skt_donation_skt_email_message') ); ?></textarea>
	</div>
</div>
<?php } 
	$sktdonation_getshortcode = sktdonation_getshortcode();
?>