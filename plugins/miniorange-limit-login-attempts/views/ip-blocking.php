<div class="molla-sub-tab-header">
			<div id="molla-mip-block" class="molla-sub-tab molla-sub-tab-active" onclick="molla_switch_ip_tabs(this)">IP BLOCKING</div>
			<div id="molla-ipwhite" class="molla-sub-tab" onclick="molla_switch_ip_tabs(this)">IP WHITELISTING</div>
			<div id="molla-iplookup" class="molla-sub-tab" onclick="molla_switch_ip_tabs(this)">IP LOOKUP</div>
</div>
<div id="molla-mip-block-div" class="mo-lla-sub-tabs mo-lla-sub-tabs-active">
			<h2>Manual IP Blocking <a href='<?php echo esc_html($two_factor_premium_doc['Manual IP Blocking']);?>' target="_blank"><span class="dashicons dashicons-external"></span></a></h2>
			<h4 class="mo_lla_setting_layout_inside">Manually block an IP address here:&emsp;&emsp;
					<input type="text" name="ManuallyBlockIP" id="molla_ManuallyBlockIP" required placeholder='IP address'pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}" style="width: 35%; height: 41px" />&emsp;&emsp;
					<input type="button" name="BlockIP" id="BlockIP" value="Manual Block IP" class="button button-primary button-large" />
			</h4>
			<h3 class="mo_lla_setting_layout_inside"><b>Blocked IP's</b></h3>
			<h4 class="mo_lla_setting_layout_inside">&emsp;&emsp;&emsp;
				<div id="blockIPtable">
					<table id="blockedips_table" class="display">
					<thead><tr><th>IP Address&emsp;&emsp;</th><th>Reason&emsp;&emsp;</th><th>Blocked Until&emsp;&emsp;</th><th>Blocked Date&emsp;&emsp;</th><th>Action&emsp;&emsp;</th></tr></thead>
					<tbody>
						<?php			
							$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
							$blockedips 		= $mo_lla_handler->get_blocked_ips();
							$whitelisted_ips 	= $mo_lla_handler->get_whitelisted_ips();
							
							$disabled = '';
							global $mo_lla_dirName;
							foreach($blockedips as $blockedip)
							{
								echo 	"<tr class='mo_lla_not_bold'><td>".esc_html($blockedip->ip_address)."</td><td>".esc_html($blockedip->reason)."</td><td>";
												if(empty($blockedip->blocked_for_time)) 
								echo 				"<span class=redtext>Permanently</span>"; 
												else 
								echo 				esc_html(date("M j, Y, g:i:s a",$blockedip->blocked_for_time));
								echo 			"</td><td>".esc_html(date("M j, Y, g:i:s a",$blockedip->created_timestamp))."</td><td><a ".esc_html($disabled)." onclick=unblockip('".esc_attr($blockedip->id)."')>Unblock IP</a></td></tr>";
							} 
						?>
					</tbody>
					</table>
				</div>	
			</h4>
</div>
		
<div id="molla-ipwhite-div" class="mo-lla-sub-tabs">
			<h2>IP Whitelisting  <a href='<?php echo esc_html($two_factor_premium_doc['IP Whitelisting']);?>' target="_blank"><span class="dashicons dashicons-external"></span></a></h2>
			<h4 class="mo_lla_setting_layout_inside">Add new IP address to whitelist:&emsp;&emsp;
				<input type="text" name="IPWhitelist" id="IPWhitelist" required placeholder='IP address'pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}" style="width: 40%; height: 41px"/>&emsp;&emsp;
				<input type="button" name="WhiteListIP" id="WhiteListIP" value="Whitelist IP" class="button button-primary button-large" />
			</h4>
			<h3 class="mo_lla_setting_layout_inside">Whitelist IP's</h3>
			<h4 class="mo_lla_setting_layout_inside">&emsp;&emsp;&emsp;
				<div id="WhiteListIPtable">
					<table id="whitelistedips_table" class="display">
					<thead><tr><th>IP Address</th><th>Whitelisted Date</th><th>Remove from Whitelist</th></tr></thead>
					<tbody>
					<?php
										foreach($whitelisted_ips as $whitelisted_ip)
										{
											echo "<tr class='mo_lla_not_bold'><td>".esc_html($whitelisted_ip->ip_address)."</td><td>".date("M j, Y, g:i:s a",esc_html($whitelisted_ip->created_timestamp))."</td><td><a ".esc_html($disabled)." onclick=removefromwhitelist('".esc_attr($whitelisted_ip->id)."')>Remove</a></td></tr>";
										} 

					echo'			</tbody>
								</table>';
					?>
				</div>
			</h4>
</div>	
				
<div id="molla-iplookup-div" class="mo-lla-sub-tabs">
			<h2>IP LookUp <a href='<?php echo esc_html($two_factor_premium_doc['IP LookUp']);?>' target="_blank"><span class="dashicons dashicons-external"></span></a></h2>
			It will allow you to know the IP location in case of suspicious activity.
			<h4 class="mo_lla_setting_layout_inside">Enter IP address you Want to check:&emsp;&emsp;
				<input type="text" name="ipAddresslookup" id="ipAddresslookup" required placeholder='IP address'pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}" style="width: 40%; height: 41px"/>&emsp;&emsp;
				<input type="button" name="LookupIP" id="LookupIP" value="LookUp IP" class="button button-primary button-large" />
			</h4>
			<div class="ip_lookup_desc" hidden ></div>
			<div id="resultsIPLookup"></div>
</div>

<script>

	
	function molla_switch_ip_tabs(component){
		const tabs = ['molla-mip-block','molla-ipwhite','molla-iplookup'];

		jQuery("#molla_sub_feature_nav").html(jQuery("#"+component.id).text());

	
		tabs.forEach(element => {
			if(component.id==element){
				jQuery('#'+element+'-div').show();
				jQuery('#'+element).addClass('molla-sub-tab-active');

			}
			else{
				jQuery('#'+element+'-div').hide();
				jQuery('#'+element).removeClass('molla-sub-tab-active');

			}
	});

}
</script>