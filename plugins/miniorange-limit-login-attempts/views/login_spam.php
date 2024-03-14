<?php
global $mollaUtility,$mo_lla_dirName;
$setup_dirName = $mo_lla_dirName.'views'.DIRECTORY_SEPARATOR.'link_tracer.php';
include $setup_dirName;
?>
<div class="molla-nav-tab-wrapper">
  <div>
  		<div class="molla-logo-container"><div class="molla-limit-login-logo"></div></div>
  </div>
  <button class="molla-nav-tab tablinks <?php echo ($active_sub_tab == 'login_sec' ? 'nav-tab-active' : '') ?>" onclick="openTab(event,'security_login')" id="login_sec"><span class="dashicons dashicons-privacy"></span>Login Security</button>
  <button class="molla-nav-tab tablinks <?php echo ($active_sub_tab == 'block_list' ? 'nav-tab-active' : '') ?>" onclick="openTab(event, 'block_list')" id="BlockWhiteTab"><span class="dashicons dashicons-location"></span>IP Security</button>
  <button class="molla-nav-tab tablinks <?php echo ($active_sub_tab == 'settings' ? 'nav-tab-active' : '') ?>" onclick="openTab(event, 'settings')" id="settingsTab"><span class="dashicons dashicons-shield"></span>Firewall</button>
  <button class="molla-nav-tab tablinks <?php echo ($active_sub_tab == 'rate_limiting' ? 'nav-tab-active' : '') ?>" onclick="openTab(event, 'rate_limiting')" id="RateLimitTab"><span class="dashicons dashicons-admin-tools"></span>Rate Limiting </button>
  <button class="molla-nav-tab tablinks <?php echo ($active_sub_tab == 'content_spam' ? 'nav-tab-active' : '') ?>" onclick="openTab(event, 'content_spam')" id="spam_content"><span class="dashicons dashicons-format-gallery"></span>Content & Spam</button>
 
</div>

<div id="lla_message"></div>
<div class="molla-feature-heading-div">
	<div class="molla-feature-nav">
		<a id="molla-feature-nav-link" href="admin.php?page=mo_lla_login_and_spam">Login Security</a> > <strong id="molla_sub_feature_nav" class="molla_sub_feature_nav">Brute force protection</strong>
	</div>
	<div id="molla-feature-name" class="molla-feature-name">
		Login security
	</div>
	<div class="molla-feature-info">
		Enable security features to improve your site security.
	</div>
</div>

<div class="tabcontent molla_tab_content" id="security_login">
	<div class="mo_lla_divided_layout">
		<div class="molla-sub-container">
					<?php include_once $mo_lla_dirName . 'controllers'.DIRECTORY_SEPARATOR.'login-security.php'; ?>
		</div>
	</div>
</div>
<div id="block_list" class="tabcontent molla_tab_content"> 
 	<div class="mo_lla_divided_layout">
		<div class="molla-sub-container">
			<?php include_once $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'ip-blocking.php'; ?>
		</div>	
	</div>
</div>
<div id="country_blocking" class="tabcontent">
 
</div>

<div id="rate_limiting" class="tabcontent molla_tab_content">
	<div class="mo_lla_divided_layout">
		<div class="molla-sub-container">
			<?php include_once $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'rate-limiting.php'; ?>
		</div>	
	</div>
</div>


<div id="settings" class="tabcontent molla_tab_content">
	<div class="mo_lla_divided_layout">
		<div class="molla-sub-container">
			<?php include_once $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'waf.php'; ?>
		</div>	
	</div>
</div>	


<div class="tabcontent molla_tab_content" id="content_spam">
	<div class="mo_lla_divided_layout">
		<div class="molla-sub-container">
					<?php include_once $mo_lla_dirName . 'controllers'.DIRECTORY_SEPARATOR.'content-protection.php'; ?>
		</div>
	</div>
</div>

<script type="text/javascript">
		jQuery('.molla-support-div').addClass('molla-support-closed');
		jQuery('#molla-slide-support').removeClass('dashicons-arrow-right-alt2');
		jQuery('#molla-slide-support').addClass('molla-support-icon');

		document.getElementById('AttackTypes').style.display = "none";
		document.getElementById('htaccessChange').style.display="none";	
		document.getElementById('rateLFD').style.display="none";
		jQuery('#resultsIPLookup').empty();
		var Rate_request 	= "<?php echo esc_html(get_option('Rate_request'));?>";
		var Rate_limiting 	= "<?php echo esc_html(get_option('Rate_limiting'));?>";
		var actionValue		= "<?php echo esc_html(get_option('actionRateL'));?>";
		var WAFEnabled 		= "<?php echo esc_html(get_option('WAFEnabled'));?>";
		if(WAFEnabled == '1')
		{
			if(Rate_limiting == '1')
			{

				jQuery('#rateL').prop("checked",true);
				jQuery('#req').val(Rate_request);
				if(actionValue == 0)
				{
					jQuery('#action').val('ThrottleIP');
				}
				else
				{
					jQuery('#action').val('BlockIP');
				}
				document.getElementById('rateLFD').style.display="block";
					
			}WAFEnabled
		}
		jQuery('#rateL').click(function(){
			var rateL 	= 	jQuery("input[name='rateL']:checked").val();
			document.getElementById('rateLFD').style.display="none";	
			var Rate_request 	= "<?php echo esc_html(get_option('Rate_request'));?>";
			var nonce = '<?php echo wp_create_nonce("RateLimitingNonce");?>';
			var actionValue		= "<?php echo esc_html(get_option('actionRateL'));?>";

			jQuery('#req').val(Rate_request);
			if(actionValue == 0)
			{
				jQuery('#action').val('ThrottleIP');
			}
			else
			{
				jQuery('#action').val('BlockIP');
			}
			if(Rate_request !='')
			{	

				var data = {
				'action'					          : 'lla_login_security',
				'lla_loginsecurity_ajax' 	          :'lla_waf_rate_limiting_form',
				'Requests'					          :  Rate_request,
				'nonce'						          :  nonce,
				'rateCheck'					          :  rateL,
				'actionOnLimitE'			          :  actionValue
				};
				jQuery.post(ajaxurl, data, function(response) { 
					var response = response.replace(/\s+/g,' ').trim();
					if(response == 'RateEnabled')
					{
						document.getElementById('rateLFD').style.display="block";
						window.onload = nav_popup('Rate Limiting is Enabled');
					}
					else if(response == 'Ratedisabled')
					{
						window.onload = nav_popup('Rate Limiting is disabled.');
					}
					else if(response == 'WAFNotEnabled')
					{
						window.onload = nav_popup('Enable WAF to use Rate Limiting');
						document.getElementById('rateLFD').style.display="none";
					}
					else if(response == 'NonceDidNotMatch')
					{
						window.onload = nav_popup('Nonce verification failed');
						document.getElementById('rateLFD').style.display="none";
					}
					else
					{
						window.onload = nav_popup('<b>ERROR</b> : An unknown error has occured');
					}
		
				});
			}
			
			
		});
		jQuery('#LookupIP').click(function(){
			jQuery('#resultsIPLookup').empty();
			var ipAddress 	= jQuery('#ipAddresslookup').val();
			if(!ipAddress || !ipAddress.match(/^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/))
            {
                jQuery("#resultsIPLookup").empty();
                window.onload = nav_popup('IP is empty or Invalid');
                exit;
            }

			var nonce 		= '<?php echo wp_create_nonce("IPLookUPNonce");?>';
			jQuery("#resultsIPLookup").empty();
			jQuery("#resultsIPLookup").append("<img src='<?php echo esc_url($img_loader_url);?>'>");
			jQuery("#resultsIPLookup").slideDown(400);
			var data = {
				'action'					: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_ip_lookup',
				'nonce'						:  nonce,
				'IP'						:  ipAddress
				};
				jQuery.post(ajaxurl, data, function(response) {
					if(response == 'INVALID_IP_FORMAT')
					{
						jQuery("#resultsIPLookup").empty();
						window.onload = nav_popup('IP did not match required format');
					}
					else if(response == 'INVALID_IP')
					{
						jQuery("#resultsIPLookup").empty();
						window.onload = nav_popup('IP entered is invalid');
					}
					else if(response == 'IP_NOT_FOUND'){
						jQuery("#resultsIPLookup").empty();
						window.onload = nav_popup('IP details not found');
					}
					else if(response.geoplugin_status == 404)
					{
						jQuery("#resultsIPLookup").empty();
						window.onload = nav_popup('IP details not found');
					}
					else if (response.geoplugin_status == 200 ||response.geoplugin_status == 206) {
						   jQuery('#resultsIPLookup').empty();
				           jQuery('#resultsIPLookup').append(response.ipDetails);
				    }
					
				});
		});
		jQuery('#saveRateL').click(function(){
			var req  	= 	jQuery('#req').val();
			var rateL 	= 	jQuery("input[name='rateL']:checked").val();
			var Action 	= 	jQuery("#action").val();
			var nonce = '<?php echo wp_create_nonce("RateLimitingNonce");?>';
			if(req !='' && rateL !='' && Action !='')
			{
				var data = {
				'action'										: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_waf_rate_limiting_form',
				'Requests'									:  req,
				'nonce'											:  nonce,
				'rateCheck'									:  rateL,
				'actionOnLimitE'						:  Action
				};
				jQuery.post(ajaxurl, data, function(response) {
					var response = response.replace(/\s+/g,' ').trim();
					if(response == 'RateEnabled')
					{
						window.onload = nav_popup('Rate Limiting is Saved',true);
					}
					else if(response == 'Ratedisabled')
					{
						window.onload = nav_popup('Rate Limiting is disabled',true);
					}
					else
					{
						window.onload = nav_popup('<b>ERROR</b> : An unknown error has occured');
					}
		
				});
			}
		
		});	

		var WAF 			= "<?php echo esc_html(get_option('WAF'));?>";
		var wafE 			= "<?php echo esc_html(get_option('WAFEnabled'));?>";
		var SQL 			= "<?php echo esc_html(get_option('SQLInjection'));?>";
		var XSS 			= "<?php echo esc_html(get_option('XSSAttack'));?>";
		var LFI 			= "<?php echo esc_html(get_option('LFIAttack'));?>";
		var RFI 			= "<?php echo esc_html(get_option('RFIAttack'));?>";
		var RCE 			= "<?php echo esc_html(get_option('RCEAttack'));?>";
		var limitAttack 	= "<?php echo esc_html(get_option('limitAttack'));?>"



		if(wafE=='1')
		{	
			document.getElementById('AttackTypes').style.display="block";
	
			if(WAF == 'PluginLevel')
			{
				jQuery('#pluginWAF').prop("checked",true);
			}
			if(SQL == '1')
			{
				jQuery('#SQL').prop("checked",true);	
			}
			if(XSS == '1')
			{
				jQuery('#XSS').prop("checked",true);	
			}
			if(LFI == '1')
			{
				jQuery('#LFI').prop("checked",true);	
			}
			if(RFI == '1')
			{
				jQuery('#RFI').prop("checked",true);	
			}
			if(RCE == '1')
			{
				jQuery('#RCE').prop("checked",true);
			}
			if(limitAttack >1)
			{
				jQuery('#limitAttack').val(limitAttack);
			}
		}
		
		jQuery('#SQL').click(function(){
			var SQL = jQuery("input[name='SQL']:checked").val();
			var nonce = '<?php echo wp_create_nonce("WAFsettingNonce");?>';
			if(SQL != '')
			{
				var data = {
				'action'					: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_waf_settings_form',
				'optionValue' 				: 'SQL',
				'SQL'						:  SQL,
				'nonce'						:  nonce
				};
				jQuery.post(ajaxurl, data, function(response) {
						var response = response.replace(/\s+/g,' ').trim();
						if(response == 'SQLenable')
						{
							window.onload = nav_popup('SQL Injection protection is enabled',true);
						}
						else
						{
							window.onload = nav_popup('SQL Injection protection is disabled',true);
						}
			
				});
							
			}


		});


		jQuery('#saveLimitAttacks').click(function(){
			var limitAttack = jQuery("#limitAttack").val();
			var nonce = '<?php echo wp_create_nonce("WAFsettingNonce");?>';
			if(limitAttack != '')
			{
				var data = {
				'action'					: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_waf_settings_form',
				'optionValue' 				: 'limitAttack',
				'limitAttack'				:  limitAttack,
				'nonce'						:  nonce
				};
				jQuery.post(ajaxurl, data, function(response) {
						var response = response.replace(/\s+/g,' ').trim();
						if(response == 'limitSaved')
						{
							window.onload = nav_popup('Limit of attacks has been saved',true);
						}
						else
						{
							window.onload = nav_popup('An Error occured while saving the settings');
						}
			
				});
						
			}


		});

		

		jQuery('#XSS').click(function(){
			var XSS = jQuery("input[name='XSS']:checked").val();
			var nonce = '<?php echo wp_create_nonce("WAFsettingNonce");?>';
			if(XSS != '')
			{
				var data = {
				'action'					: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_waf_settings_form',
				'optionValue' 				: 'XSS',
				'XSS'						:  XSS,
				'nonce'						:  nonce
				};
				jQuery.post(ajaxurl, data, function(response) {
						var response = response.replace(/\s+/g,' ').trim();
						if(response == 'XSSenable')
						{
							window.onload = nav_popup('XSS detection is enabled');
						}
						else
						{
							window.onload = nav_popup('XSS detection is disabled');
						}
			
				});
							
			}
			

		});
		jQuery('#LFI').click(function(){
			var LFI = jQuery("input[name='LFI']:checked").val();
			var nonce = '<?php echo wp_create_nonce("WAFsettingNonce");?>';
			if(LFI != '')
			{
				var data = {
				'action'					: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_waf_settings_form',
				'optionValue' 				: 'LFI',
				'LFI'						:  LFI,
				'nonce'						:  nonce
				};
				jQuery.post(ajaxurl, data, function(response) {
						var response = response.replace(/\s+/g,' ').trim();
						if(response == 'LFIenable')
						{
							window.onload = nav_popup('LFI detection is enabled',true);
						}
						else
						{
							window.onload = nav_popup('LFI detection is disabled',true);
						}
			
				});
							
			}
			
			


		
		});
		
		
		jQuery('#pluginWAF').click(function(){
			var pluginWAF = jQuery("input[name='pluginWAF']:checked").val();
			var nonce = '<?php echo wp_create_nonce("WAFsettingNonce");?>';
			if(pluginWAF != '')
			{

				var data = {
				'action'					: 'lla_login_security',
				'lla_loginsecurity_ajax' 	: 'lla_waf_settings_form',
				'optionValue' 				: 'WAF',
				'pluginWAF'					:  pluginWAF,
				'nonce'						:  nonce
				};
				jQuery.post(ajaxurl, data, function(response) {
						var response = response.replace(/\s+/g,' ').trim();
						if(response == "PWAFenabled")
						{
							document.getElementById('AttackTypes').style.display="block";
							var SQL ="<?php echo esc_html(get_option('SQLInjection'));?>";
							var XSS ="<?php echo esc_html(get_option('XSSAttack'));?>";
							var LFI ="<?php echo esc_html(get_option('LFIAttack'));?>";
							var RFI ="<?php echo esc_html(get_option('RFIAttack'));?>";
							var RCE ="<?php echo esc_html(get_option('RCEAttack'));?>";
							var limitAttack 	= "<?php echo esc_html(get_option('limitAttack'));?>"

							if(SQL == '1')
							{
								jQuery('#SQL').prop("checked",true);	
							}
							if(XSS == '1')
							{
								jQuery('#XSS').prop("checked",true);	
							}
							if(LFI == '1')
							{
								jQuery('#LFI').prop("checked",true);	
							}
							if(RFI == '1')
							{
								jQuery('#RFI').prop("checked",true);	
							}
							if(RCE == '1')
							{
								jQuery('#RCE').prop("checked",true);	
							}
							if(limitAttack >1)
							{	
								jQuery('#limitAttack').val(limitAttack);
							}
							window.onload = nav_popup('WAF  is enabled on Plugin level',true);
							
						}
						else
						{
							window.onload = nav_popup('WAF is disabled on plugin level',true);
							document.getElementById('AttackTypes').style.display="none";
						}
			
				});				
			}

		});
		
		jQuery('#cnclDH').click(function(){
			var pluginWAF = jQuery("input[name='pluginWAF']:checked").val();
			document.getElementById("htaccessChange").style.display = "none";
			if(pluginWAF == 'on')
			{
				jQuery('#pluginWAF').prop("checked",true);
				document.getElementById('AttackTypes').style.display = "block";	
			}		
			window.onload = nav_popup('WAF activation canceled');

		});
		
jQuery('#RLPage').click(function(){
	document.getElementById("RateLimitTab").click();
	window.onload = nav_popup();
});

jQuery('#SettingPage').click(function(){
	document.getElementById("settingsTab").click();
	window.onload = nav_popup();
});
jQuery('#IPBlockingWhitelistPage').click(function(){
	document.getElementById("BlockWhiteTab").click();
	window.onload = nav_popup();
});
jQuery('#RTBPage').click(function(){
	document.getElementById("RealTimeTab").click();
	window.onload = nav_popup();
});
	
function waf_function(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";

  localStorage.setItem("lastTab",cityName);
  evt.currentTarget.className += " active";
}

	
	var tab = localStorage.getItem("lastTab");
	if(tab == "waf_dash")
	{
		document.getElementById("defaultOpen").click();
	}
	else if(tab == "settings")
	{
		document.getElementById("settingsTab").click();	
	}

	else if(tab == "block_list")
	{
		document.getElementById("BlockWhiteTab").click();	
	}
	
	else if(tab == "real_time")
	{
		document.getElementById("RealTimeTab").click();	
	}
	
	else if(tab == "rate_limiting")
	{
		document.getElementById("RateLimitTab").click();	
	}
	else 
	{
		// document.getElementById("defaultOpen").click();	
	}
	

jQuery('#BlockIP').click(function(){

	var ip 	= jQuery('#molla_ManuallyBlockIP').val();

	var nonce = '<?php echo wp_create_nonce("manualIPBlockingNonce");?>';
	if(ip != '')
	{
		var data = {
		'action'										: 'lla_login_security',
		'lla_loginsecurity_ajax' 						: 'lla_ManualIPBlock_form', 
		'IP'											:  ip,
		'nonce'											:  nonce,
		'option'										: 'mo_lla_manual_block_ip'
		};
		jQuery.post(ajaxurl, data, function(response) {
				var response = response.replace(/\s+/g,' ').trim();
				jQuery('#lla_message').empty();
				if(response == 'empty IP')
				{
					window.onload = nav_popup('IP can not be blank');
				}
				else if(response == 'already blocked')
				{
					window.onload = nav_popup('IP is already blocked');
				}
				else if(response == "INVALID_IP_FORMAT")
				{
					window.onload = nav_popup('IP does not match required format');

				}
				else if(response == "IP_IN_WHITELISTED")
				{
					window.onload = nav_popup('IP is whitelisted can not be blocked');

				}
				else
				{
					refreshblocktable(response);
					window.onload = nav_popup('IP Blocked Sucessfully');
				}
		
		});
					
	}

});



jQuery('#WhiteListIP').click(function(){

	var ip 	= jQuery('#IPWhitelist').val();

	var nonce = '<?php echo wp_create_nonce("IPWhiteListingNonce");?>';
	if(ip != '')
	{
		var data = {
		'action'					: 'lla_login_security',
		'lla_loginsecurity_ajax' 	: 'lla_WhitelistIP_form', 
		'IP'						:  ip,
		'nonce'						:  nonce,
		'option'					: 'mo_lla_whitelist_ip'
		};
		jQuery.post(ajaxurl, data, function(response) {
				jQuery("#lla_message").empty();
				var response = response.replace(/\s+/g,' ').trim();
				if(response == 'EMPTY IP')
				{
					window.onload = nav_popup('IP can not be empty');

				}
				else if(response == 'INVALID_IP')
				{
					window.onload = nav_popup('IP does not match required format.');
	
				}
				else if(response == 'IP_ALREADY_WHITELISTED')
				{
					window.onload = nav_popup('IP is already whitelisted');
	
				}
				else
				{	
					refreshWhiteListTable(response);	
					window.onload = nav_popup('IP whitelisted successfully',true);

				}
		});
					
	}

});

jQuery("#blockedips_table").DataTable({
				"order": [[ 3, "desc" ]]
			});
jQuery("#whitelistedips_table").DataTable({
				"order": [[ 1, "desc" ]]
			});
function unblockip(id) {
  var nonce = '<?php echo wp_create_nonce("manualIPBlockingNonce");?>';
	if(id != '')
	{
		var data = {
		'action'					: 'lla_login_security',
		'lla_loginsecurity_ajax' 	: 'lla_ManualIPBlock_form', 
		'id'						:  id,
		'nonce'						:  nonce,
		'option'					: 'mo_lla_unblock_ip'
		};
		jQuery.post(ajaxurl, data, function(response) {
			var response = response.replace(/\s+/g,' ').trim();

			if(response=="UNKNOWN_ERROR")
			{	
				window.onload = nav_popup('Unknow Error occured while unblocking IP');
			}
			else
			{
				refreshblocktable(response);
				window.onload = nav_popup('IP UnBlocked Sucessfully',true);
			}
		});
					
	}
}
function removefromwhitelist(id)
{
	var nonce = '<?php echo wp_create_nonce("IPWhiteListingNonce");?>';
	if(id != '')
	{
		var data = {
		'action'					: 'lla_login_security',
		'lla_loginsecurity_ajax' 	: 'lla_WhitelistIP_form', 
		'id'						:  id,
		'nonce'						:  nonce,
		'option'					: 'mo_lla_remove_whitelist'
		};
		jQuery.post(ajaxurl, data, function(response) {
				var response = response.replace(/\s+/g,' ').trim();
				if(response == 'UNKNOWN_ERROR')
				{
					window.onload = nav_popup('Unknow Error occured while removing IP from Whitelist');
				}
				else	
				{
					refreshWhiteListTable(response);	
					window.onload = nav_popup('IP removed from Whitelist');		
				}
		});
					
	}
}

function refreshblocktable(html)
{
	 jQuery('#blockIPtable').html(html);

}

function refreshWhiteListTable(html)
{
	 
	 jQuery('#WhiteListIPtable').html(html);	
}
</script>


<script>

	const currentTab='<?php echo $active_tab; ?>';

	

	document.getElementById("security_login").style.display = "block";
	document.getElementById("content_spam").style.display = "none";
	// document.getElementById("login_sec").className += " nav-tab-active";

	function openTab(evt, tabname){
		var i, tablinks, tabcontent;
		tabcontent = document.getElementsByClassName("tabcontent");
  			for (i = 0; i < tabcontent.length; i++) {
    		tabcontent[i].style.display = "none";
  		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" nav-tab-active", "");
		}
		document.getElementById(tabname).style.display = "block";
		
		localStorage.setItem("tablast", tabname);
  		evt.currentTarget.className += " nav-tab-active";
		
		jQuery("#molla-feature-nav-link").html(jQuery('#'+evt.target.id).html());
		jQuery("#molla").html("");
		jQuery("#molla-feature-name").html(jQuery("#molla-feature-nav-link").text());
	}
	jQuery("#"+currentTab).click();
	var tab = localStorage.getItem("tablast");
	if(tab == "security_login"){
		document.getElementById("login_sec").click();
	}
	else if(tab == "content_spam"){
		document.getElementById("spam_content").click();
	}
	else{
		document.getElementById("login_sec").click();
	}
</script>