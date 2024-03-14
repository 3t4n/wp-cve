<?php

// displays the campaign_monitor opt-in form
function pmc_mc_form($redirect, $list_id, $message) {
	global $pmc_options, $post;
	if(strlen(trim($message)) <= 0) {
		$message = __('You have been successfully subscribed', 'pmc');	
	}
	if(strlen(trim($redirect)) <= 0) {
		if (is_singular()) :
			$redirect =  get_permalink($post->ID);
		else :
			$redirect = 'http';
			if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $redirect .= "s";
			$redirect .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") $redirect .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			else $redirect .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		endif;	
	}
	ob_start(); 
		if(isset($_GET['submitted']) && $_GET['submitted'] == '1') {
			echo '<p>' . $message . '</p>';
		} else {			
			if(strlen(trim($pmc_options['api_key'])) > 0 ) { ?>
<style>
 		
#optin {
	background: #393939 url(http://mahfuzar.info/wp-content/uploads/2014/09/dark.png) repeat-x top;
	border: 3px solid #111;
	color: #fff;
	padding: 20px 15px;
	text-align: center;
}
	#optin input {
		border: 1px solid #111;
		font-size: 15px;
		margin-bottom: 10px;
		padding: 8px 10px;
		border-radius: 3px;
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		box-shadow: 0 2px 2px #111;
		-moz-box-shadow: 0 2px 2px #111;
		-webkit-box-shadow: 0 2px 2px #111;
		width: 80%;
	}
		#optin input.email { background: #fff url(http://mahfuzar.info/wp-content/uploads/2014/09/email.png) no-repeat 10px center; padding-left: 35px }


		#optin input.name { background: #fff url(http://mahfuzar.info/wp-content/uploads/2014/09/name.png) no-repeat 10px center; padding-left: 35px }
		#optin input[type="submit"] {
			background: #960e17 url(http://mahfuzar.info/wp-content/uploads/2014/09/red.png) repeat-x top;
			border: 1px solid #111;
			color: #fff;
			cursor: pointer;
			font-size: 18px;
			font-weight: bold;
			padding: 8px 0;
			text-shadow: -1px -1px #3a060a;
			width: 100%
		}
			#optin input[type="submit"]:hover { color: #ffa5a5 }
</style>
			<div id="optin">
			<form id="pmc_mailchimp" action="" method="post">
			
				<?php if( !isset($pmc_options['disable_names'])) { ?>
					<div>
						
						<input name="pmc_fname" class="name" id="pmc_fname" type="text" value="Enter your name" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
					</div>
					
				<?php } ?>			
				<div>
				 
					<input name="pmc_email" id="pmc_email" class="required email" type="text" value="Enter your email" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
				</div>
				<div>
					<input type="hidden" name="redirect" value="<?php echo $redirect; ?>"/>
					<input type="hidden" name="action" value="pmc_signup"/>
					<input type="hidden" name="pmc_list_id" value="<?php echo $list_id; ?>"/>
					<div id="mce-responses" class="clear">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
		</div>
					<div class="clear">
			<input type="submit" value="<?php _e('Sign Up', 'pmc'); ?>" name="subscribe" id="mc-embedded-subscribe" class="button">
		</div>
				 
				</div>
			</form>
			</div>
			<?php
		}
	}
	return ob_get_clean();
}

function pmc_mc_form_shortcode($atts, $content = null ) {

	global $pmc_options;	
	
	extract( shortcode_atts( array(
		'redirect' => '',
		'list' => 1,
		'message' => __('You have been successfully subscribed.', 'pmc')
	), $atts ) );
	
	if($redirect == '') {
		$redirect = add_query_arg('submitted', 'yes', get_permalink());
	}
	
	$lists = pmc_get_lists();
	$i = 0;
	foreach($lists as $id => $list_name) {
		if($i == ($list-1) ) {
			$list_id = $id;
		}
		$i++;	
	}
	
	return pmc_mc_form($redirect, $list_id, $message);
}
add_shortcode('mailchimp', 'pmc_mc_form_shortcode');

?>