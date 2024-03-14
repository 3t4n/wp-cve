<?php
defined('ABSPATH') or die('Nope nope nope...');
?>
<style>
#wpcontent {
    padding-left: 0px !important; 
}
.error a{
	background-color: #4776e6;
	color: #fff;
	padding: 5px 10px;
	border-radius: 5px;
	text-decoration: none;
}

.tcenter{
    text-align: center
}

.maintitle{
    height: auto;
}

.anticontainer{
	width: auto;
	clear: both;
	padding: 1%;
	background-color: #f3f6ff;
}

.antititle{
    display: inline-flex;
    align-items: center;
    position: relative;
}

.antititle h3{
    padding-left: 10px;
}

.anticontent{
    margin-left: auto;
    margin-right: auto;
    max-width: 750px;
}
.maincounts{
	text-align: left;
	line-height: 25px;
	font-size: 16px;
	background: #fff;
	border: 1px solid #d6e2ed;
	margin: 25px 0;
}

.maincounts .cbtn{
    margin: 30px;
    text-align: center;
}
.setsubhead{
    border-bottom: 1px solid #d6e2ed;
    padding: 16px 15px;
    font-weight: 700;
    font-size: 14px;
    text-align: left;
}
.setmatter{
    width: auto;
    padding:15px;
    font-size: 14px;
    font-weight: 500;
}
.setmatter span{
    font-weight: 700;
}

.setmatter a{
    font-weight: 700;
    text-decoration: none;
}
input[type=text]{
    width: 100%;
    min-height: 30px;
    padding: 9px 15px;
    font-size: 14px;
    border: 1px solid #b7c9d9;
    -webkit-box-shadow: none;
    box-shadow: none;
    border-radius: 3px;
    padding-left: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
}

textarea{
    border: 1px solid #b7c9d9;
    border-radius: 3px;
    font-size: 14px;
    padding: 9px 15px;
    width: 100%;
    -webkit-box-shadow: none;
    box-shadow: none;
    margin: 0;
    color: #444;
    line-height: 2;
    min-height: 30px;
    overflow: hidden;
}
.checkcontainer{
    width: 100%;
    height: 50px;
    position: relative;
}
.checklabel{
    width: auto;
    float: left;
    position: relative;
}

.checks{
    width: 50%;
    float: right;
    position: absolute;
    left: 30%;
}

.checks img{
    padding-left: 10px;
}

.textsecondary{
    font-size: 17px;
    font-weight: 500;
    color: #4c6577;
    margin-top: 25px;
    margin-bottom: 10px;
    padding: 0 10px;
}

.textprimary{
    font-size: 18px;
    font-weight: 700;
    color: #393f4c;
    margin-bottom: 25px;
    padding: 0 10px;
}

.antibtn{
    background: #5cc0a5;
    border-color: #40a88d;
    color: #fff;
    font-size: 17px;
    font-weight: 700;
    padding: 15px 25px;
    line-height: 1;
    border: solid;
    border-width: 1px 1px 2px;
    border-radius: 3px;
    cursor: pointer;
    display: inline-block;
    text-decoration: none;
}

.antibtn:hover{
    color: #fff;
    background: #5cc0b8;
    text-decoration: none;
}

.ttip{
    width: 20px;
    height: 20px;
    position: absolute;
    left: 220px;
    top: 0px;
}

.ttip .tooltip {
	  visibility: hidden;
	  width: 200px;
	  height:auto;
	  background-color: #052c88;
	  color: #fff;
	  text-align: center;
	  border-radius: 6px;
	  padding: 5px 5px;
	  bottom:30px;
	  right: -10px;
	  font-weight: 400;
	  line-height:1.2;
	  position: absolute;
	  z-index: 1;
}

.tooltip a{
    color: #ffc81f;
    font-weight: 400;
}

.ttip .tooltip:before {
	  content: '';
	  display: block;
	  width: 0;
	  height: 0;
	  position: absolute;
	  border-left: 10px solid transparent;
	  border-right: 10px solid transparent;
	  border-top: 10px solid #052c88;
	  bottom: -9px;
	  right: 10px;
}

.ttip:hover .tooltip {
	visibility: visible;
}

@supports(-webkit-appearance: none) or (-moz-appearance: none) {
	input[type='checkbox']{
		-webkit-appearance: none;
		-moz-appearance: none;
	}
}

input[type=checkbox] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

input[type=checkbox]{
    width: 40px;
    height: 20px;
    position: relative;
    -webkit-appearance:none;
    background: #c6c6c6;
    outline: none;
    border: 2px solid #ccc;
    border-radius: 10px;
    transition: 0.5s;

}

input:checked[type=checkbox]{
	background: #039af4;
	content: '';
	border: 2px solid #ccc;
}

input[type=checkbox]:before{
	content: '';
	position: absolute;
	width: 20px;
	height: 20px;
	border-radius: 15px;
	top: -2px;
	left: -2px;
	background: #fff;
	transition: 0.2s;
}

input:checked[type=checkbox]:before{
	left: 20px;
	content: '';
	top: 0;
}

.prespecarea{
    width: auto;
    padding: 0 14%;
    display: flex;
    height: 50px;
}

.prespec{
    width: 50%;
    height: 40px;
    text-align: left;
    display: flex;
    align-items: center;
}
.prespec img{
    padding-right: 10px;
}

.prespec p{

    font-size: 14px;
    font-weight: 700;
    color: #393f4a;
}

.prespec p{
    transform: translateY(10%);
}

.prebtn{
    padding:10px 20px;
    background-color: #4f9fe0;
    font-size: 1.2rem;
    font-weight: 700;
    border-radius: 10px;
    color: #fff;
    text-decoration: none;
}

.savebtn{
    background-color: #3a93dd;
    border-color: #2971a9;
    color: #fff;
    border-radius: 3px;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: 400;
    padding: 10px 20px;
    text-decoration: none;
    margin:10px 0;
    float: right;
}
.savebtn:hover{
    background-color: #65b4f5;
}

.prebtn:hover{
    color: #fff;
    background-color: #65b4f5;
}

.antifooter{
  width:auto;
  height: auto;
  font-size: 16px;
  line-height: 1.5;
  text-align: center;
  margin: 20px;
}

@media(max-width: 420px){
    .savebtn{
        float: left;
    }
    .maintitle{
        height: 110px;
    }
    .prespecarea{
        flex-direction: column;
        height: 100px;
    }
    .prespec{
        width: 100%;
        height: auto;
    }

    .maincounts .cbtn {
    	margin-top: 20%;
    }
    
}

</style>

<script type="text/javascript"> 
	var values_changed = false;
	var submitting = false;
	window.onbeforeunload = function(e) {
    	if(values_changed){
    		if(!submitting){
    			return false;	
    		}    		
    	}
	}
	jQuery("body").on("change","#adev_token, .adev_allow_free_email, .adev_allow_role_business_email, .adev_allow_disposable_email, #adev_whitelist, #adev_blacklist, #adev_domain_whitelist, #adev_domain_blacklist", function(){
		values_changed = true;
	});

	 function is_submit(){
	 	submitting = true;
	 }
	
</script>
<?php if(isset($_GET['settings-updated']) 
	&& get_option('adev_token') != "" 
	&& !get_option('adev_token_expired_message')){ ?>
		<div class="notice notice-success is-dismissible">
        	<p><?php echo esc_html__('You are now subscribed to premium version of the plugin' ,'antideo-email-validator'); ?> </p>
        </div>
    <?php 
} ?>

<div class="anticontainer">
<form method="post" action="options.php" onsubmit="is_submit();">
    <?php settings_fields('adev-settings-group'); ?>
    <?php do_settings_sections('adev-settings'); ?>
	
	<div class="anticontent">
		<div class="maintitle">
			<div class="antititle">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/antideomin.png'; ?>" width="50px" /> 
				<h3><?php echo esc_html__('Antideo Email Validator' ,'antideo-email-validator'); ?> </h3>
			</div>
			<button type="subtmit" class="savebtn"><?php echo esc_html__('Save Changes' ,'antideo-email-validator'); ?> </button>
		</div>
		<div class="maincounts"> 
			<div class="setsubhead"> <?php echo esc_html__('License Key' ,'antideo-email-validator'); ?> </div>        
			<div class="setmatter">
				<?php if(!get_option('adev_token')) { ?>
					<?php echo esc_html__('You\'re using','antideo-email-validator'); ?> <span><?php echo esc_html__('the free version','antideo-email-validator'); ?></span><?php echo esc_html__(' - license key is not needed','antideo-email-validator'); ?><br>
					<?php echo esc_html__('To unlock more features consider' ,'antideo-email-validator'); ?>  
					<a target="_blank" href="https://www.antideo.com/wordpress-email-validation-plugin/">
						<?php echo esc_html__('upgrading to Premium.' ,'antideo-email-validator'); ?> 
					</a>
					<br>
					<?php echo esc_html__('Already subscribed? Enter your license key below to unlock premium!' ,'antideo-email-validator'); ?>  
					<a target="_blank" href="https://account.antideo.com/account/">
					<?php echo esc_html__('Retrieve your license key' ,'antideo-email-validator'); ?> 
					</a>
					<br>  
				<?php } else {
					if(get_option('adev_token_expired_message') != ""){
						?>
						<div class="notice notice-error is-dismissible" style="margin:0px 0px 0px 2px; " >
        					<p><?php _e(get_option('adev_token_expired_message'), 'antideo-email-validator' ); ?></p>
    					</div>
					<?php }
				} ?>
				<input type="text" id="adev_token" name="adev_token" value="<?php echo esc_attr(get_option('adev_token')); ?>" placeholder="<?php echo esc_attr( __('Paste your license key here' ,'antideo-email-validator')); ?>" />
			</div>        
		</div>
		<?php if(get_option('adev_token') != "" && !get_option('adev_token_expired_message')){ ?>
			<div class="maincounts">
				<p style="padding: 0px 16px; font-size: 14px; font-weight: bold;"><?php echo esc_html__('You are currently using the Premium Version' ,'antideo-email-validator'); ?> </p>
			</div>
		<?php }?>

		<div class="maincounts">
			<div class="setmatter">
				<div class="checkcontainer">
					<div class="checklabel">
						<input type="checkbox" class="adev_allow_free_email" name="adev_allow_free_email" value="1" <?php !get_option('adev_allow_free_email') or print('checked') ?> /> 
						<?php echo esc_html__('Allow Free Emails' ,'antideo-email-validator'); ?>  
						<div class="ttip">
							<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/infoicon2.png'; ?>" width="15px">
							<span class="tooltip"><?php echo esc_html__('Allow emails from free ESP\'s like yahoo, gmail, AOL etc.' ,'antideo-email-validator'); ?> 
								<a href="https://www.antideo.com/wordpress-email-validation-plugin/" target="blank"><?php echo esc_html__('Know more' ,'antideo-email-validator'); ?> </a>
							</span>
						</div>
					</div>
				</div>
				<div class="checkcontainer">
				    <div class="checklabel">
						<input type="checkbox" class="adev_allow_role_business_email" name="adev_allow_role_business_email" value="1" <?php !get_option('adev_allow_role_business_email') or print('checked') ?> /> 
						<?php echo esc_html__('Allow Generic Emails ' ,'antideo-email-validator'); ?> 
						<div class="ttip">
							<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/infoicon2.png'; ?>" width="15px">
							<span class="tooltip"><?php echo esc_html__('Allow role based emails like info@, admin@,marketing@ etc. ' ,'antideo-email-validator'); ?> 
								<a href="https://www.antideo.com/wordpress-email-validation-plugin/" target="blank"><?php echo esc_html__('Know more' ,'antideo-email-validator'); ?> </a>
							</span>
						</div>
					</div>
				</div>
				<div class="checkcontainer">
				    <div class="checklabel">
						<input type="checkbox" class="adev_allow_disposable_email" name="adev_allow_disposable_email" value="1" <?php !get_option('adev_allow_disposable_email') or print('checked') ?> /> 
						<?php echo esc_html__('Allow Disposable Emails ' ,'antideo-email-validator'); ?> 
						<div class="ttip">
							<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/infoicon2.png'; ?>" width="15px">
							<span class="tooltip"><?php echo esc_html__('Allow disposable or temporary emails.' ,'antideo-email-validator'); ?> 
								<a href="https://www.antideo.com/wordpress-email-validation-plugin/" target="blank"><?php echo esc_html__('Know more' ,'antideo-email-validator'); ?> </a>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="maincounts">
			<div class="setsubhead"><?php echo esc_html__('Local Whitelist' ,'antideo-email-validator'); ?>  (<?php echo count(array_filter(explode("\n", get_option('adev_whitelist')))); ?>)</div>
			<div class="setmatter">
				<textarea rows="3" id="adev_whitelist" name="adev_whitelist" placeholder="<?php echo esc_attr( __('One email per line.' ,'antideo-email-validator')); ?>"><?php echo esc_attr(get_option('adev_whitelist')); ?></textarea>
			</div>
		</div>

		<div class="maincounts">
			<div class="setsubhead"><?php echo esc_html__('Local Blacklist' ,'antideo-email-validator'); ?>  (<?php echo count(array_filter(explode("\n", get_option('adev_blacklist')))); ?>)</div>
			<div class="setmatter">
				<textarea rows="3" id="adev_blacklist" name="adev_blacklist" placeholder="<?php echo esc_attr( __('One email per line.' ,'antideo-email-validator')); ?>"><?php echo esc_attr(get_option('adev_blacklist')); ?></textarea>
			</div>
		</div>

		<div class="maincounts">
			<div class="setsubhead"><?php echo esc_html__('Local Whitelist Domains' ,'antideo-email-validator'); ?>  (<?php echo count(array_filter(explode("\n", get_option('adev_domain_whitelist')))); ?>)</div>
			<div class="setmatter">
				<textarea rows="3" placeholder="<?php echo esc_attr( __('One domain per line.' ,'antideo-email-validator')); ?>" id="adev_domain_whitelist" name="adev_domain_whitelist"><?php echo esc_attr(get_option('adev_domain_whitelist')); ?></textarea>
			</div>
		</div>

		<div class="maincounts">
			<div class="setsubhead"><?php echo esc_html__('Local Blacklist Domains' ,'antideo-email-validator'); ?>  (<?php echo count(array_filter(explode("\n", get_option('adev_domain_blacklist')))); ?>)</div>
			<div class="setmatter">
				<textarea rows="3" placeholder="<?php echo esc_attr( __('One domain per line.' ,'antideo-email-validator')); ?>"  id="adev_domain_blacklist" name="adev_domain_blacklist"><?php echo esc_attr(get_option('adev_domain_blacklist')); ?></textarea>
			</div>
		</div>

		<div class="maincounts">
			<div class="textsecondary tcenter"><?php echo esc_html__('Thank you for being a loyal Antideo user' ,'antideo-email-validator'); ?> </div>
			
			<?php if(!get_option('adev_token')) { ?>
			
			<div class="textprimary tcenter"><?php echo esc_html__('Upgrade to Premium and unlock all the security features' ,'antideo-email-validator'); ?> </div>
			
			<div class="prespecarea">
				<div class="prespec"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/plusicon.png'; ?>" width="14px"><p class=""><?php echo esc_html__('Email Syntax Validation' ,'antideo-email-validator'); ?> </p></div>
				<div class="prespec"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/plusicon.png'; ?>" width="14px"><p class=""><?php echo esc_html__('Block Free Emails' ,'antideo-email-validator'); ?> </p></div>
			</div>

			<div class="prespecarea">
				<div class="prespec"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/plusicon.png'; ?>" width="14px"><p class=""><?php echo esc_html__('Block Generic Emails' ,'antideo-email-validator'); ?> </p></div>
				<div class="prespec"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/plusicon.png'; ?>" width="14px"><p class=""><?php echo esc_html__('Mx Records Validation' ,'antideo-email-validator'); ?> </p></div>
			</div>

			<div class="prespecarea">
				<div class="prespec"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/plusicon.png'; ?>" width="14px"><p class=""> 
					<?php echo esc_html__('Block Disposable Emails' ,'antideo-email-validator'); ?> <br>
					<?php echo esc_html__('(Constantly Updated List)' ,'antideo-email-validator'); ?> </p>
				</div>
				<div class="prespec"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/plusicon.png'; ?>" width="14px"><p class=""> 
					<?php echo esc_html__('Unlimited line items in local' ,'antideo-email-validator'); ?> <br>
					<?php echo esc_html__('Whitelists and Blacklists' ,'antideo-email-validator'); ?> </p>
				</div>				
			</div>

			<div class="cbtn">
				<a target="_blank" href="https://www.antideo.com/wordpress-email-validation-plugin/" class="prebtn"><?php echo esc_html__('Upgrade to Premium' ,'antideo-email-validator'); ?> </a>
			</div>

			<?php } ?>
		</div>
	</div>
</form>   

<div class="antifooter">
	<?php echo esc_html__('Please rate Antideo Email Validator ' ,'antideo-email-validator'); ?> 
  	<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/stars.png'; ?>"><?php echo esc_html__(' on ' ,'antideo-email-validator'); ?>
  	<a target="_blank" href="https://wordpress.org/plugins/antideo-email-validator/" ><?php echo esc_html__('WordPress.org' ,'antideo-email-validator'); ?> </a> 
  	<?php echo esc_html__('to help us spread the word. Thank you from the Antideo team!' ,'antideo-email-validator'); ?> 
</div>

</div>

