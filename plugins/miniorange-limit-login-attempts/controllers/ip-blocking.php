<?php 
	
	global $mollaUtility,$mo_lla_dirName;
	$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
	if(current_user_can( 'manage_options' )  && isset($_POST['option']))
	{
		switch(sanitize_text_field($_POST['option']))
		{
			case "mo_lla_manual_block_ip":
				lla_handle_manual_block_ip(sanitize_text_field($_POST['IP']));			break;
			case "mo_lla_unblock_ip":
				lla_handle_unblock_ip(sanitize_text_field($_POST['id']));			break;
			case "mo_lla_whitelist_ip":
				lla_handle_whitelist_ip(sanitize_text_field($_POST['IP']));				break;
			case "mo_lla_remove_whitelist":
				lla_handle_remove_whitelist(sanitize_text_field($_POST['id']));	break;
		}
	}

	$blockedips 		= $mo_lla_handler->get_blocked_ips();
	$whitelisted_ips 	= $mo_lla_handler->get_whitelisted_ips();
	$img_loader_url		= plugins_url('miniorange-limit-login-attempts/includes/images/loader.gif');
	$page_url			= "";
	$license_url		= add_query_arg( array('page' => 'upgrade'), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	// Function to handle Manual Block IP form submit
	function lla_handle_manual_block_ip($ip)
	{
		global $mollaUtility;	
		if( $mollaUtility->check_empty_or_null( $ip) )
		{
			echo("empty IP");
			exit;
		} 
		if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',$ip))
		{
			echo("INVALID_IP_FORMAT");
			exit;
		}
		else
		{
			$ipAddress 		=  $ip ;
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$isWhitelisted 	= $mo_lla_config->is_whitelisted($ipAddress); 
			if($isWhitelisted==0)
			{
				if($mo_lla_config->is_ip_blocked_manual_ip($ipAddress)){
					echo("already blocked");	
					exit;
				} else{
					$mo_lla_config->block_ip($ipAddress, Mo_lla_MoWpnsConstants::BLOCKED_BY_ADMIN, true);	
					?>
					<table id="blockedips_table1" class="display">
				<thead><tr><th>IP Address&emsp;&emsp;</th><th>Reason&emsp;&emsp;</th><th>Blocked Until&emsp;&emsp;</th><th>Blocked Date&emsp;&emsp;</th><th>Action&emsp;&emsp;</th></tr></thead>
				<tbody>
				<?php					
								$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
								$blockedips 		= $mo_lla_handler->get_blocked_ips();
								$whitelisted_ips 	= $mo_lla_handler->get_whitelisted_ips();
								global $mo_lla_dirName;
								foreach($blockedips as $blockedip)
								{
					echo 			"<tr class='mo_lla_not_bold'><td>".esc_html($blockedip->ip_address)."</td><td>".esc_html($blockedip->reason)."</td><td>";
									if(empty($blockedip->blocked_for_time)) 
					echo 				"<span class=redtext>Permanently</span>"; 
									else 
					echo 				date("M j, Y, g:i:s a",esc_html($blockedip->blocked_for_time));
					echo 			"</td><td>".esc_html(date("M j, Y, g:i:s a",$blockedip->created_timestamp))."</td><td><a  onclick=unblockip('".esc_attr($blockedip->id)."')>Unblock IP</a></td></tr>";
								} 
					?>
					</tbody>
					</table>
					<script type="text/javascript">
						jQuery("#blockedips_table1").DataTable({
						"order": [[ 3, "desc" ]]
						});
					</script>
					<?php
					exit;
				}
			}
			else
			{
				echo("IP_IN_WHITELISTED");
				exit;
			}
		}
	}


	// Function to handle Manual Block IP form submit
	function lla_handle_unblock_ip($entryID)
	{
		global $mollaUtility;
		
		if( $mollaUtility->check_empty_or_null($entryID))
		{
			echo("UNKNOWN_ERROR");
			exit;
		}
		else
		{
			$entryid 		= $entryID;
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$mo_lla_config->unblock_ip_entry($entryid);
					?>
				<table id="blockedips_table1" class="display">
				<thead><tr><th>IP Address&emsp;&emsp;</th><th>Reason&emsp;&emsp;</th><th>Blocked Until&emsp;&emsp;</th><th>Blocked Date&emsp;&emsp;</th><th>Action&emsp;&emsp;</th></tr></thead>
				<tbody>
<?php					
				$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
				$blockedips 		= $mo_lla_handler->get_blocked_ips();
				$whitelisted_ips 	= $mo_lla_handler->get_whitelisted_ips();
				global $mo_lla_dirName;
				foreach($blockedips as $blockedip)
				{
	echo 			"<tr class='mo_lla_not_bold'><td>".esc_html($blockedip->ip_address)."</td><td>".esc_html($blockedip->reason)."</td><td>";
					if(empty($blockedip->blocked_for_time)) 
	echo 				"<span class=redtext>Permanently</span>"; 
					else 
	echo 				date("M j, Y, g:i:s a",esc_html($blockedip->blocked_for_time));
	echo 			"</td><td>".esc_html(date("M j, Y, g:i:s a",$blockedip->created_timestamp))."</td><td><a onclick=unblockip('".esc_attr($blockedip->id)."')>Unblock IP</a></td></tr>";
				} 
	?>
					</tbody>
					</table>
					<script type="text/javascript">
						jQuery("#blockedips_table1").DataTable({
						"order": [[ 3, "desc" ]]
						});
					</script>
					<?php
			
			exit;
		}
	}


	// Function to handle Whitelist IP form submit
	function lla_handle_whitelist_ip($ip)
	{
		global $mollaUtility;
		if( $mollaUtility->check_empty_or_null($ip))
		{
			echo("EMPTY IP");
			exit;
		}
		if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',$ip))
		{			//change message
				echo("INVALID_IP");
				exit;
		}
		else
		{
			$ipAddress = sanitize_text_field($ip);
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			if($mo_lla_config->is_whitelisted($ipAddress))
			{
				echo("IP_ALREADY_WHITELISTED");
				exit;
			}
			else
			{
				$mo_lla_config->whitelist_ip($ip);
				$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
				$whitelisted_ips 	= $mo_lla_handler->get_whitelisted_ips();
					
			?>
				<table id="whitelistedips_table1" class="display">
				<thead><tr><th >IP Address</th><th >Whitelisted Date</th><th >Remove from Whitelist</th></tr></thead>
				<tbody>
				<?php
					foreach($whitelisted_ips as $whitelisted_ip)
					{
						echo "<tr class='mo_lla_not_bold'><td>".esc_html($whitelisted_ip->ip_address)."</td><td>".esc_html(date("M j, Y, g:i:s a",$whitelisted_ip->created_timestamp))."</td><td><a  onclick=removefromwhitelist('".esc_html($whitelisted_ip->id)."')>Remove</a></td></tr>";
					} 	
				?>
				</tbody>
				</table>
			<script type="text/javascript">
				jQuery("#whitelistedips_table1").DataTable({
				"order": [[ 1, "desc" ]]
				});
			</script>

	<?php
			exit;
			}
		}
	}
	// Function to handle remove whitelisted IP form submit
	function lla_handle_remove_whitelist($entryID)
	{
		global $mollaUtility;
		if( $mollaUtility->check_empty_or_null($entryID))
		{
			echo("UNKNOWN_ERROR");
			exit;
		}
		else
		{
			$entryid = sanitize_text_field($entryID);
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$mo_lla_config->remove_whitelist_entry($entryid);
			$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
			$whitelisted_ips 	= $mo_lla_handler->get_whitelisted_ips();		
			?>
				<table id="whitelistedips_table1" class="display">
				<thead><tr><th >IP Address</th><th >Whitelisted Date</th><th >Remove from Whitelist</th></tr></thead>
				<tbody>
			<?php
					foreach($whitelisted_ips as $whitelisted_ip)
					{
						echo "<tr class='mo_lla_not_bold'><td>".esc_html($whitelisted_ip->ip_address)."</td><td>".esc_html(date("M j, Y, g:i:s a",$whitelisted_ip->created_timestamp))."</td><td><a onclick=removefromwhitelist('".esc_html($whitelisted_ip->id)."')>Remove</a></td></tr>";
					} 
			?>
				</tbody>
				</table>
			<script type="text/javascript">
				jQuery("#whitelistedips_table1").DataTable({
				"order": [[ 1, "desc" ]]
				});
			</script>

		<?php
			exit;
		}
	}

	