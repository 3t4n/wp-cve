<?php
global $mollaUtility,$mo_lla_dirName;

$setup_dirName = $mo_lla_dirName.'views'.DIRECTORY_SEPARATOR.'link_tracer.php';
 include $setup_dirName;
 echo '';

echo'<div class="mo_lla_divided_layout">
		<div class="mo_lla_setting_layout">';

echo' <h2>IP Address Range Blocking <span class="lla_premium_class"> [Premium Feature] </span><a href='.esc_html($two_factor_premium_doc['IP Address Range Blocking']).' target="_blank"><span class="dashicons dashicons-external"></span></a></h2>
	You can block range of IP addresses here  ( Examples: 192.168.0.100 - 192.168.0.190 )
	<form name="f" method="post" action="" id="iprangeblockingform" >
	<input type="hidden" name="option" value="mo_lla_block_ip_range" />
	<table id="iprangeblockingtable">';
				
	for($i = 1 ; $i <= $range_count ; $i++)
echo'	<tr><td style="width:300px"><input style="padding:0px 10px"  class="mo_lla_table_textbox" type="text"    name="range_'.esc_attr($i).'"value="'.esc_attr(get_option("mo_lla_iprange_range_".$i)).'"  placeholder=" e.g 192.168.0.100 - 192.168.0.190" /></td></tr>';

echo'	</table>
		<a style="cursor:pointer" id="add_range">Add More Range</a><br><br>
		</form>
		<hr>
		<h3>User Session Timeout <span style = "color: red;">[PREMIUM FEATURES ]</span> </h3>
		<table>
		<th><td>
		<div class="mo_lla_subheading">This sets the timeout for the Logout of a Wordpress User.</div>
		<input type="number" name="logout_time" required id ="logout_time" /> Number of Days</td></th>
		<th><td>
		<input type="button" name="saveUserSession" id="saveUserSession" value="Save" class="button button-primary button-large mo_lla_button1" disabled />
		</td></th>
            <td>
                <input type="button" name="resetUserSession" id="resetUserSession" value="Reset" class="button button-primary button-large mo_lla_button1"disabled/>
            </td>
		</table>
		<br>
		<hr>
		<table style="width:100%">
		<tr><th align="left">
		<h3>Real time IP blocking  <span style = "color: red;">[PREMIUM FEATURES ]</span>:
			<br>
			<p><i class="mo_lla_not_bold">Blocking those malicious IPs Which has been detected by miniOrange WAF. This feature contains a list of malicious IPs which is mantained in real time. By enabling this option if any attack has been detected on miniOrange WAF on others wbsite then that IP will be blocked from your site also.</i></p>
  		</th><th align="right">
  		<label class="mo_lla_switch">
		 <input type=checkbox id="RealTimeIP" name="RealTimeIP" disabled/>
		 <span class="mo_lla_slider mo_lla_round"></span>
		</label>
		</tr></th>
		 </h3>
		 </table>
		<hr>
		<h3>htaccess level blocking<span class="lla_premium_class"> [Premium Feature] </span></h3>
		<p>It help you secure your website from unintended user with htaccess website security protection which blocks user request on server(apache) level before reaching your WordPress and saves lots of load on server.</p>
        <br>
		<hr>
		<h3>Browser Blocking<span class="lla_premium_class"> [Premium Feature] </span> <a href='.esc_html($two_factor_premium_doc['Browser Blocking']).' target="_blank"><span class="dashicons dashicons-external"></span></a></h3>
			<form id="mo_lla_enable_user_agent_blocking" method="post" action="">
				<input type="hidden" name="option" value="mo_lla_enable_user_agent_blocking">
				<b style="padding-right:10px;">Enable Browser Blocking</b>
				
			</form><br>
			<div style="margin-bottom:10px">Select browsers below to block</div>
			<form id="mo_lla_browser_blocking" method="post" action="">
				<input type="hidden" name="option" value="mo_lla_browser_blocking">
				<table style="width:100%">
				<tr>
				<td width="33%"><input type="checkbox" name="mo_lla_block_chrome" '.esc_html($block_chrome).' > Google Chrome '.($current_browser=='chrome' ? (Mo_lla_MoWpnsConstants::CURRENT_BROWSER) : "").'</td>
				<td width="33%"><input type="checkbox" name="mo_lla_block_firefox" '.esc_attr($block_firefox).' > Firefox '.($current_browser=='firefox' ? (Mo_lla_MoWpnsConstants::CURRENT_BROWSER) : "").'</td>
				<td width="33%"><input type="checkbox" name="mo_lla_block_ie" '.esc_html($block_ie).' > Internet Explorer '.($current_browser=='ie' ? (Mo_lla_MoWpnsConstants::CURRENT_BROWSER) : "").'</td>
				</tr>
				<tr>
				<td width="33%"><input type="checkbox" name="mo_lla_block_safari" '.esc_html($block_safari).' > Safari '.($current_browser=='safari' ? (Mo_lla_MoWpnsConstants::CURRENT_BROWSER) : "").'</td>
				<td width="33%"><input type="checkbox" name="mo_lla_block_opera" '.esc_attr($block_opera).' > Opera '.($current_browser=='opera' ?(Mo_lla_MoWpnsConstants::CURRENT_BROWSER) : "").'</td>
				<td width="33%"><input type="checkbox" name="mo_lla_block_edge" '.esc_attr($block_edge).' > Microsoft Edge '.($current_browser=='edge' ? (Mo_lla_MoWpnsConstants::CURRENT_BROWSER) : "").'</td>
				</tr>
				</table>
				<br>
			</form>
			<br>
			<hr>
			<h3>Block HTTP Referer\'s <span class="lla_premium_class"> [Premium Feature] </span></h3>
			An "HTTP Referer" is anything online that drives visitors to your website which includes search engines, weblogs link lists, emails etc.<br>Examples : google.com
			<form name="f" method="post" action="" id="referrerblockingform" >
			<input type="hidden" name="option" value="mo_lla_block_referrer" />
			<table id="referrerblockingtable">';

			$count=1;
			foreach($referrers as $referrer)
			{
			    echo '<tr><td style="width:300px"><input style="padding:0px 10px" class="mo_lla_table_textbox" type="text" name="referrer_'.esc_html($count).'"value="'.esc_html($referrer).'"  placeholder=" e.g  google.com" /></td></tr>';
						$count++;
			}

echo'	</table><a style="cursor:pointer" id="add_referer">Add More Referer\'s</a><br><br>
		</form>
		<br> <hr>
		  	<table style="width:100%"><tr><th align="left">
			<h3>Rate Limiting for Crawlers<strong style="color: red"><a href="admin.php?page=upgrade"> [Premium Feature] </a></strong>: <br>
			<p><i class="mo_lla_not_bold">Web crawlers crawl your Webstie for increasing ranking in the search engine. But sometimes they can make so many request to the server that the service can get damage.By enabling this feature you can provide limit at which a crawler can visit your site.</i></p></th><th align="right">
	  		<label class="mo_lla_switch">
			<input type=checkbox id="RateLimitCrawler" name="RateLimitCrawler" disabled />
			<span class="mo_lla_slider mo_lla_round"></span>
			</label></tr></th></h3></table>
		<br><hr>
			<table style="width:100%">
			<tr><th align="left">
			<h3>Fake Web Crawler Protection<strong style="color: red"><a href="admin.php?page=upgrade"> [Premium Feature] </a></strong>: <br>
			<p><i class="mo_lla_not_bold">Web Crawlers are used for scaning the Website and indexing it. Google, Bing, etc. are the top crwalers which increase your sites indexing in the seach engine. There are several fake crawlers which can damage your site. By enabling this feature all fake google and bing crawlers will be blocked.  </i></p></th><th align="right">
	  		<label class="mo_lla_switch"><input type=checkbox id="FakeCrawler" name="FakeCrawler" disabled />
			<span class="mo_lla_slider mo_lla_round"></span></label></tr></th></h3></table>
		<br> <hr>
		  	<table style="width:100%">
			<tr><th align="left">
			<h3>BotNet Protection<strong style="color: red"><a href="admin.php?page=upgrade"> [Premium Feature] </a></strong>:<br>
			<p><i class="mo_lla_not_bold"> BotNet is a network of robots or army of robots. The BotNet is used for Distributed denial of service attack. The attacker sends too many requests from multiple IPs to a service so that the legitimate traffic can not get the service. By enabling this your Website will be protected from such kind of attacks.  </i></p>	 
	  		</th><th align="right"><label class="mo_lla_switch">
			<input type=checkbox id="BotNetProtection" name="BotNetProtection" disabled /><span class="mo_lla_slider mo_lla_round"></span></label></tr></th></h3>
			 </table>

		<br> <hr>
		<h2>Country Blocking <span class="lla_premium_class"> [Premium Feature] </span></h2>
		<b>Select countries from below which you want to block.</b><br><br>
		<form name="f" method="post" action="" id="countryblockingform" >
		<input type="hidden" name="option" value="mo_lla_block_countries" />
		<table id="countryblockingtable" style="width:100%">';			
			foreach($country as $key => $value)
				echo '<tr class="one-third"><td><input type="checkbox" name="'.esc_html($key).'"/ >'.esc_html($value).'</td></tr>';

echo'	</table><br>
		</form>
		</div>
	</div>
	<script>		
		jQuery( document ).ready(function() {
			var countrycodes = "'.esc_html($codes).'";
			var countrycodesarray = countrycodes.split(";");
			for (i = 0; i < countrycodesarray.length; i++) { 
				if(countrycodesarray[i]!="")
					$("#countryblockingform :input[name=\'"+countrycodesarray[i]+"\']").prop("checked", true);
			}

			$("#add_range").click(function() {
				var last_index_name = $("#iprangeblockingtable tr:last .mo_lla_table_textbox").attr("name");
				var splittedArray = last_index_name.split("_");
				var last_index = parseInt(splittedArray[splittedArray.length-1])+1;
				var new_row = \'<tr><td><input style="padding:0px 10px" class="mo_lla_table_textbox" type="text" name="range_\'+last_index+\'" value=""   placeholder=" e.g 192.168.0.100 - 192.168.0.190" /></td></tr>\';
				$("#iprangeblockingtable tr:last").after(new_row);
			});

			$("#add_referer").click(function() {
				var last_index_name = $("#referrerblockingtable tr:last .mo_lla_table_textbox").attr("name");
				var splittedArray = last_index_name.split("_");
				var last_index = parseInt(splittedArray[splittedArray.length-1])+1;
				var new_row = \'<tr><td><input style="padding:10px 0px" class="mo_lla_table_textbox" type="text" name="referrer_\'+last_index+\'" value=""   placeholder=" e.g  google.com" /></td></tr>\';
				$("#referrerblockingtable tr:last").after(new_row);
			});
	
		});
	</script>';