<?php
		$admin_url = admin_url();
		$url = explode('/wp-admin/', $admin_url);
		$url = $url[0].'/htaccess';
		$nameDownload = "Backup.htaccess";
?>
    <div class="molla-sub-tab-header">
			<div id="molla-waf" class="molla-sub-tab molla-sub-tab-active" >WEB APPLICATION FIREWALL</div>
    </div>
		<div class="mo-lla-sub-tabs mo-lla-sub-tabs-active">
		<table style="width:100%">
			<tr><th align="left">
			<h3>Website firewall on plugin level:
				<a href='<?php echo esc_html($two_factor_premium_doc['Plugin level waf']);?>' target="_blank">
				<span class="	dashicons dashicons-external"></span></a>
				<p><i class="mo_lla_not_bold">This will activate WAF after the WordPress load. This will block illegitimate requests after making connection to WordPress. This will check Every Request in plugin itself.</i></p>
			</th><th align="right">
			<label class='mo_lla_switch'>
			<input type=checkbox id='pluginWAF' name='pluginWAF' />
			<span class='mo_lla_slider mo_lla_round'></span>
			</label>
			</tr></th>
			</h3>
			</table>
			<div id ='htaccessChange' name ='htaccessChange'>
			<p><i class="mo_lla_not_bold"> This feature will make changes to .htaccess file, Please confirm before the changes<br>
				if you have any issue after this change please use the downloaded version as backup.
				Rename the file as '.htaccess' [without name just extension] and use it as backup.  
				</i></p> 

		</div>
		</div>	
		<div name = 'AttackTypes' id ='AttackTypes'>
		<div class="mo_lla_setting_layout">
		
			<table style="width:100%">
				<tr>
					<th align="left"> <h1>Vulnerabilities</h1></th>

					<th align="right"><h1>Enable/disable</h1></th>
					
				</tr>
			</table>
			<hr color = "#2271b1"/>
		<table style="width:100%">
		<tr>

		<th align="left"><h2>	SQL Injection Protection <strong class="molla_basic_feature">[Basic Level Protection] </strong>
			
			<p><i class="mo_lla_not_bold">SQL Injection attacks are used for attack on database. This option will block all illegal requests which tries to access your database. <a href="admin.php?page=upgrade"><strong style="color: #0081E3">Advance Signatures</strong></a></i></p>
		</th>  
		<th align="right">
			<label class='mo_lla_switch'>
			<input type="checkbox" name="SQL" id="SQL"/>
		 	<span class='mo_lla_slider mo_lla_round'></span>
			</label>
		</th>

		</h2>

	</tr>
		<tr>
		<th align="left"><h2>Cross Site scripting Protection <strong class="molla_basic_feature">[Basic Level Protection] </strong>
			<br>
			<p><i class="mo_lla_not_bold">cross site scripting is used for script attacks. This will block illegal scripting on website. <a href="admin.php?page=upgrade"><strong style="color: #0081E3">Advance Signatures</strong></a></i></p>
		</th>
		<th align="right">
			<label class='mo_lla_switch'>
			<input type="checkbox" name="XSS" id="XSS"/>
		 	<span class='mo_lla_slider mo_lla_round'></span>
			</label>
			</th>
		</h2></tr>
			<tr>
		<th align="left"><h2>Local File Inclusion Protection <strong class="molla_basic_feature">[Basic Level Protection] </strong>
				<br>
			<p><i class="mo_lla_not_bold">Local File inclusion is used for making changes to the local files of the server. This option will block Local File Inclusion. <a href="admin.php?page=upgrade"><strong style="color: #0081E3">Advance Signatures</strong></a></i></p>
		</th>
		<th align="right">
			<label class='mo_lla_switch'>
			<input type="checkbox" name="LFI" id="LFI"/>
		 	<span class='mo_lla_slider mo_lla_round'></span>
			</label>
		</th>
		</h2></tr></table>
		
	</div>
	<div class="mo_lla_setting_layout">
		<table style="width: 100%"><tr>
			<th align="left"><h2>Block After <strong style="color: #0081E3">[Recommended : 10] </strong>:
			<p><i class="mo_lla_not_bold">Option for blocking the IP if the limit of the attacks has been exceeds.</i></p></th>  
		  <th align="right"><input type ="number" name ="limitAttack" id = "limitAttack" required min="5"/></th>
		  <th><h2 align="left"> attacks</h2></th>
		  <th align="right"><input type="button" name="saveLimitAttacks" id="saveLimitAttacks" value="Save" class="button button-primary button-large mo_llan_button1" /></th></h2></tr>
		</table>
	</div>
    </div>
