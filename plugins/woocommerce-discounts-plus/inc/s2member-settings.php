<?php if ( ! defined( 'ABSPATH' ) ) exit; 


function wdp_s2member_settings() {
// Check the user capabilities
	if ( !current_user_can( 'administrator' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', "wcdp" ) );
	}
	
	global $s2_pro, $wdp_pro, $wdp_s2member, $wcdp_data;


	
		
	if ( 
		!empty($_POST) 
		&&
		(! isset( $_POST['wdp_nonce_action_field'] ) 
		|| ! wp_verify_nonce( $_POST['wdp_nonce_action_field'], 'wdp_nonce_action' ) 
		)
	) {
	
	   print __('Sorry, your nonce did not verify.', "wcdp");
	   exit;
	
	} else {
	
	   // process form data
	
		//pre($wp_roles);
		//pre($options);
		$s2_roles = wdp_s2_roles();
		if(
			isset($_POST['wdp_s2member'])
		){
			update_option('wdp_s2member', wdp_sanitize_arr_data($_POST['wdp_s2member']));
		}
		
		if(
				isset($_POST['s2_role'])
			&&
				isset($_POST['s2_role_discount'])
			&&
				array_key_exists($_POST['s2_role'], $s2_roles)
		){
			update_option('wdp_'.$_POST['s2_role'], wdp_sanitize_arr_data($_POST['s2_role_discount']));
			
		}	
		
	}
	
?>

<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php echo esc_html($wcdp_data['Name'].($wdp_pro?' (Pro)':'').'+ s2Member'.($s2_pro?' (Pro)':'').' '.__('Settings', "wcdp" )); ?></h2>
	<?php if ( isset( $_POST['wdp_fields_submitted'] ) && $_POST['wdp_fields_submitted'] == 'submitted' ) { ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', "wcdp" ); ?></strong></p></div>
	<?php } ?>
	<div id="content">
		<form method="post" action="" id="wdp_settings">
			<?php wp_nonce_field( 'wdp_nonce_action', 'wdp_nonce_action_field' ); ?>
			<input type="hidden" name="wdp_fields_submitted" value="submitted">
			<div id="poststuff">
				<div style="float:left; width:100%;">
					<div class="postbox">
						<h3><?php _e( 'Discounts Criteria', "wcdp" ); ?></h3>
						<div class="inside wdp-settings">
							
<div class="s2_roles_guide">
<ul class="s2_roles_and_criteria">
<li>
<input name="wdp_s2member" type="checkbox" <?php echo (get_option('wdp_s2member')?'checked="checked"':''); ?> value="1" /> <span><strong><?php _e('Enable', "wcdp"); ?></strong> (<?php echo __('It will overwrite', "wcdp"); ?> <a href="admin.php?page=wc-settings&tab=plus_discount"><?php _e('default discount criteria', "wcdp")?></a>)</span>
</li>
<li>
<label><?php _e('Membership Level', "wcdp"); ?>:</label>
<?php if(!empty($s2_roles)){ ?>
<select name="s2_role">
<option value=""><?php _e('Select a role to define discount criteria', "wcdp"); ?></option>
<?php foreach($s2_roles as $key=>$val){ ?>
<?php
$fp = get_option('wdp_'.$key);
$caption = $val.($fp!=''?' ('.(get_option( 'woocommerce_discount_type', '' )=='flat'?wdp_get_formatted_price($fp):$fp.'%').')':'');
?>
<option value="<?php echo esc_attr($key); ?>" data-val="<?php echo esc_attr($fp); ?>"><?php echo esc_html($caption); ?></option>
<?php } ?>
</select>
<?php } ?>
</li>
<li>
<label><?php _e('Discount Criteria', "wcdp"); ?>:</label>                           
<input type="text" name="s2_role_discount" value="" /> <a title=<?php _e("Click here to change", "wcdp"); ?> href="admin.php?page=wc-settings&tab=plus_discount" target="_blank">(<?php echo (get_option( 'woocommerce_discount_type', '' )=='flat'?get_woocommerce_currency_symbol().'<span class="s2_role_discount_type"></span>':'<span class="s2_role_discount_type"></span>%');?>)</a>
</li>
<li>
<label>&nbsp;</label>
<input class="button button-primary button-large" type="submit" value=<?php _e("Save Changes", "wcdp"); ?> />
</li>
</ul>
<div class="s2_roles_guide_right">
<strong><?php _e('How it works?', "wcdp"); ?></strong><br />
<ol>
	<li><a href="admin.php?page=ws-plugin--s2member-gen-ops"><?php _e('Create membership levels', "wcdp"); ?></a> <?php _e('with', "wcdp"); ?> s2member > <?php _e('Membership Levels/Labels', "wcdp"); ?>. &nbsp;|&nbsp;<a href="http://s2member.com/kb-article/video-custom-capabilities-for-wordpress/" target="_blank"><?php _e('Video Tutorial', "wcdp"); ?></a></li>
<li><?php _e('If membership levels are created already so you will have them in "Membership Level" dropdown here (on this page, see left).', "wcdp")?> <?php _e('Now select', "wcdp")?> <a href="admin.php?page=wc-settings&tab=plus_discount"><?php _e('Discount Type', "wcdp")?></a> <?php _e('either fixed price discount or percentage discount', "wcdp")?>.</li>   
<li><?php _e('Now you will notice that these membership levels are appearing with either your selected', "wcdp");?> <a href="admin.php?page=wc-settings" target="_blank">currency symbol <?php echo get_woocommerce_currency_symbol(); ?></a> <?php _e('or')?> <?php _e('percentage', "wcdp")?> % <?php _e('symbol', "wcdp")?>. <?php _e('Select membership level and define discount criteria.', "wcdp");?></li> 
<li><?php _e('Save changes and try discounts in your cart according to the membership level of logged in user.', "wcdp"); ?> <?php _e('For more support', "wcdp");?>, <a href="<?php echo esc_url($wdp_pro?'https://wordpress.org/support/plugin/woocommerce-discounts-plus':'https://androidbubbles.com/contact'); ?>" target="_blank"><?php _e('contact us', "wcdp");?></a>.</li>
</ol>
<div class="video_tutorials">
<strong><?php _e('Video Tutorials', "wcdp"); ?>:</strong>
<ol>
<li>
<strong><?php _e('Overview', "wcdp"); ?>:</strong>
<iframe src="https://www.youtube.com/embed/8j7gRzoHZdc" frameborder="0" allowfullscreen></iframe>
</li>
<li>
<strong><?php _e('Discounts', "wcdp"); ?> <?php _e('with', "wcdp");?> s2member</strong>
<iframe src="https://www.youtube.com/embed/plIK2MTgB5E" frameborder="0" allowfullscreen></iframe>
</li>
</ol>
</div>
</div>
</div>

						</div>
					</div>
				</div>
				
			</div>
		</form>
	</div>
</div>
<?php }