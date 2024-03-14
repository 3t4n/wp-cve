<?php
	global $wpdb,$mollaUtility,$Mo_lla_wpnsDbQueries;
	$dir =dirname(__FILE__);
	$dir = str_replace('\\', "/", $dir);
	$sqlInjectionFile 	= $dir.'/signature/APSQLI.php';
	$xssFile			= $dir.'/signature/APXSS.php';
	$lfiFile 			= $dir.'/signature/APLFI.php';
	$configfilepath 	= explode('wp-content', $dir);
	$configfile 		= $configfilepath[0].'/wp-includes/mo-waf-config.php';
	$missingFile		= 0;
	if(file_exists($configfile))
	{
		include($configfile);
	}
	else
	{
		 $missingFile	= 1;
	}
	include_once($sqlInjectionFile);
	include_once($xssFile);
	include_once($lfiFile);
	$ipaddress = '';
	$blocking_type = get_option('mo_lla_time_of_blocking_type');
	$ipaddress 	= $mollaUtility->get_client_ip();	
	$results 	= $Mo_lla_wpnsDbQueries->get_ip_blocked_count($ipaddress,$blocking_type);

	if($results!=0)
    {
    	$results1	= $Mo_lla_wpnsDbQueries->get_whitelisted_ip_count($ipaddress);
    	if($results1==0)
    	{
    	    header('HTTP/1.1 403 Forbidden');
	        $error_message = "Number of failed login attempts exceeded.";
		    include_once(dirname(dirname(__FILE__)).'/views/error/403.php');
    	}
    }
	$dir_name =  dirname(__FILE__);
	$dir_name1 = explode('wp-content', $dir_name);
	$dir_name = $dir_name1[0];
	$filepath = str_replace('\\', '/', $dir_name1[0]);
	$fileName = $filepath.'/wp-includes/mo-waf-config.php';
	if($missingFile==1)
	{
	   	if(!file_exists($fileName))
		{
			$file 		= fopen($fileName, "a+");
			$string 	= "<?php".PHP_EOL;
			$string	.= '$SQL = '.get_option("SQLInjection").';'.PHP_EOL;
			$string .= '$XSS = '.get_option("XSSAttack").';'.PHP_EOL;
			$string .= '$RFI = '.get_option("RFIAttack").';'.PHP_EOL;
			$string .= '$LFI = '.get_option("LFIAttack").';'.PHP_EOL;
			$string .= '$RCE = '.get_option("RCEAttack").';'.PHP_EOL;
			$string .= '$RateLimiting = '.get_option("Rate_limiting").';'.PHP_EOL;
			$string .= '$RequestsPMin = '.get_option("Rate_request").';'.PHP_EOL;
			if(get_option('actionRateL') == 0)
				$string .= '$actionRateL = "ThrottleIP";'.PHP_EOL;
			else
				$string .= '$actionRateL = "BlockIP";'.PHP_EOL;
			$string .= '?>'.PHP_EOL;
			fwrite($file, $string);
			fclose($file);		
		 }	
	}
	include_once($fileName);
	
    if(isset($RateLimiting)&&$RateLimiting == 1)
    {

    	$time 		= 60;
    	$reqLimit	= $RequestsPMin;
	    $query = "delete from ".$wpdb->base_prefix."wpns_ip_rate_details where time<".(time()-$time);
	    $results = $wpdb->get_results($query);

	    $query = "insert into ".$wpdb->base_prefix."wpns_ip_rate_details values('".$ipaddress."',".time().");";
		$results = $wpdb->get_results($query);
	   
	    $query = "select count(*) as count from ".$wpdb->base_prefix."wpns_ip_rate_details where ip='".$ipaddress."';";
		$results = $wpdb->get_results($query);
	    if($results[0]->count>=$reqLimit)
	    {
	    	$action = $actionRateL;
			if($action == 'ThrottleIP')
			{			
				$query 			= "select time from ".$wpdb->base_prefix."wpns_attack_logs where ip ='".$ipaddress."' ORDER BY time DESC LIMIT 1;";
			    $results 		= $wpdb->get_results($query);
			    $current_time 	= time();
			    if($results[0]->time < $current_time-60)
			    {
			    	$query 			= "insert into ".$wpdb->base_prefix."wpns_attack_logs values('".$ipaddress."','Rate Limit',".time().",'RLE');";
	    			$results 		= $wpdb->get_results($query);
			    }
	    		header('HTTP/1.1 403 Forbidden');
	    		$error_message = "Number of failed login attempts exceeded.";
				include_once(dirname(dirname(__FILE__)).'/views/error/403.php');
	    	}
	    	else
	    	{
	    		$query 			= "select time from ".$wpdb->base_prefix."wpns_attack_logs where ip ='".$ipaddress."' ORDER BY time DESC LIMIT 1;";
			    $results 		= $wpdb->get_results($query);
			    $current_time 	= time();
			    if($results[0]->time < $current_time-60)
			    {
			    	$query 			= "insert into ".$wpdb->base_prefix."wpns_attack_logs values('".$ipaddress."','Rate Limit',".time().",'RLE');";
	    			$results 		= $wpdb->get_results($query);
			    }
			    $query 		= 'select * from '.$wpdb->base_prefix.'wpns_whitelisted_ips where ip_address="'.$ipaddress.'";';
				$results1	= $wpdb->get_results($query);
		    	if(sizeof($results1)!=0)
		    	{
		    		//IP whitelisted 
		    	}
		    	else
		    	{
		    		$query ="insert into ".$wpdb->base_prefix."wpns_blocked_ips values(NULL,'".$ipaddress."','Rate limit exceed',NULL,".current_time( 'timestamp' ).");";
		    		$results =$wpdb->get_results($query);
	    		}
	    		header('HTTP/1.1 403 Forbidden');
	    		$error_message = "Number of failed login attempts exceeded.";
				include_once(dirname(dirname(__FILE__)).'/views/error/403.php');
	    	}
	 	}
    }
    $attack = array();
    if(isset($SQL) && $SQL==1)
    {
    	array_push($attack,"SQL");
    }
    if(isset($XSS) && $XSS==1)
    {
    	array_push($attack,"XSS");
    }
    if(isset($LFI)&& $LFI==1)
    {
    	array_push($attack,"LFI");
    }
    
    $attackC 		= $attack;
    $ParanoiaLevel 	= 1;
    $annomalyS 		= 0;
    $SQLScore		= 0;
    $XSSScore		= 0;
    $limitAttack 	= get_option('limitAttack');

   
    foreach ($attackC as $key1 => $value1) {
    	for($lev=1;$lev<=$ParanoiaLevel;$lev++)
    	{
    		if(isset($regex[$value1][$lev]))
		    {	
		    	for($i=0;$i<sizeof($regex[$value1][$lev]);$i++)
			    {
			    	foreach ($_REQUEST as $key => $value) {
						if($regex[$value1][$lev][$i] != "")
				    	{	
							if(strpos($regex[$value1][$lev][$i], '/') == false)
					    	{	
					    		if(is_string($value))
						    	{
						    		
					    		if(preg_match('/'.$regex[$value1][$lev][$i].'/', $value))
						    	{	
						    		$scoreValue = 0;
						    	
						    		$annomalyMS = $regex[$value1][$lev][$i];
						    		if(strcmp($annomalyMS,"CRITICAL"))
						    		{
						    			$scoreValue = 5;
						    		}

						    		elseif(strcmp($annomalyMS,"WARNING"))
						    		{
						    			$scoreValue = 3;
						    		}
						    		elseif(strcmp($annomalyMS,"ERROR"))
						    		{
						    			$scoreValue = 4;
						    		}
						    		elseif(strcmp($annomalyMS,"NOTICE"))
						    		{
						    			$scoreValue =2;
						    		}
						    		if($value1 == "SQL")
						    		{
						    			$SQLScore += $scoreValue;
						    		
						    		}
						    		elseif ($value1 == "XSS")
						    		{
						    			$XSSScore += $scoreValue;
						    		}
						    		else
						    		{
						    			$annomalyS += $scoreValue;
						    		}
						    		if($annomalyS>=5 || $SQLScore>=10 || $XSSScore >=10)
						    		{
						    			$value = htmlspecialchars($value);
						    			$query = 'insert into '.$wpdb->base_prefix.'wpns_attack_logs values ("'.$ipaddress.'","'.$value1.'",'.time().',"'.$value.'");';
						    			$results = $wpdb->get_results($query);
						    			$query = "select count(*) as count from ".$wpdb->base_prefix."wpns_attack_logs where ip='".$ipaddress."' and input != 'RLE';";
						    			$results = $wpdb->get_results($query);
						    			if($results[0]->count>$limitAttack)
						    			{
						    				$query 		= 'select * from '.$wpdb->base_prefix.'wpns_whitelisted_ips where ip_address="'.$ipaddress.'";';
											$results	= $wpdb->get_results($query);
									    	if(sizeof($results)!=0)
									    	{
									    		//IP whitelisted 
									    	}
									    	else
									    	{
						    					$query ="insert into ".$wpdb->base_prefix."wpns_blocked_ips values(NULL,'".$ipaddress."','attack limit exceed',NULL,".current_time( 'timestamp' ).");";
	    										$results =$wpdb->get_results($query);
	    									}
	  						    		}
						    			header('HTTP/1.1 403 Forbidden');
	    								$error_message = "Number of failed login attempts exceeded.";
										include_once(dirname(dirname(__FILE__)).'/views/error/403.php');
						    		}
						    		
						    		}
						    	}
					    	}
					    	else if (strpos($regex[$value1][$lev][$i], '#') == false) {
					    		if(is_string($value))
						    	{
						    		
					    		if(preg_match('#'.$regex[$value1][$lev][$i].'#', $value))
						    	{
						    		$scoreValue = 0;
						    		$annomalyMS = $regex[$value1][$lev][$i];
						    		if(strcmp($annomalyMS,"CRITICAL"))
						    		{
						    			$scoreValue = 5;
						    		}

						    		elseif(strcmp($annomalyMS,"WARNING"))
						    		{
						    			$scoreValue = 3;
						    		}
						    		elseif(strcmp($annomalyMS,"ERROR"))
						    		{
						    			$scoreValue = 4;
						    		}
						    		elseif(strcmp($annomalyMS,"NOTICE"))
						    		{
						    			$scoreValue =2;
						    		}


						    		if($value1 == "SQL")
						    		{
						    			$SQLScore += $scoreValue;
						    		
						    		}
						    		elseif ($value1 == "XSS")
						    		{
						    			$XSSScore += $scoreValue;
						    		}
						    		else
						    		{
						    			$annomalyS += $scoreValue;
						    		}
						    		if($annomalyS>=5 || $SQLScore>=10 || $XSSScore >=10)
						    		{
						    			$value = htmlspecialchars($value);
						    			$query = 'insert into '.$wpdb->base_prefix.'wpns_attack_logs values ("'.$ipaddress.'","'.$value1.'",'.time().',"'.$value.'");';
						    			$results = $wpdb->get_results($query);
						    			$query = "select count(*) as count from ".$wpdb->base_prefix."wpns_attack_logs where ip='".$ipaddress."' and input != 'RLE';";
						    			$results = $wpdb->get_results($query);

						    			if($results[0]->count>$limitAttack)
						    			{
						    				$query 		= 'select * from '.$wpdb->base_prefix.'wpns_whitelisted_ips where ip_address="'.$ipaddress.'";';
											$results	= $wpdb->get_results($query);
									    	if(sizeof($results)!=0)
									    	{
									    		//IP whitelisted 
									    	}
									    	else
									    	{
						    					$query ="insert into ".$wpdb->base_prefix."wpns_blocked_ips values(NULL,'".$ipaddress."','attack limit exceed',NULL,".current_time( 'timestamp' ).");";
	    										$results =$wpdb->get_results($query);
	    									}
	  						    		}
						    			header('HTTP/1.1 403 Forbidden');
	    								$error_message = "Number of failed login attempts exceeded.";
										include_once(dirname(dirname(__FILE__)).'/views/error/403.php');
						    		}
						    		}
						    	}
						    }

						    elseif (strpos($regex[$value1][$lev][$i], '@') == false) {
						    	if(is_string($value))
						    	{
						    		
						    	if(preg_match('@'.$regex[$value1][$lev][$i].'@', $value))
						    	{
						    		$scoreValue = 0;
						    		$annomalyMS = $regex[$value1][$lev][$i];
						    		if(strcmp($annomalyMS,"CRITICAL"))
						    		{
						    			$scoreValue = 5;
						    		}

						    		elseif(strcmp($annomalyMS,"WARNING"))
						    		{
						    			$scoreValue = 3;
						    		}
						    		elseif(strcmp($annomalyMS,"ERROR"))
						    		{
						    			$scoreValue = 4;
						    		}
						    		elseif(strcmp($annomalyMS,"NOTICE"))
						    		{
						    			$scoreValue =2;
						    		}


						    		if($value1 == "SQL")
						    		{
						    			$SQLScore += $scoreValue;
						    		
						    		}
						    		elseif ($value1 == "XSS")
						    		{
						    			$XSSScore += $scoreValue;
						    		}
						    		else
						    		{
						    			$annomalyS += $scoreValue;
						    		}
						    		if($annomalyS>=5 || $SQLScore>=10 || $XSSScore >=10)
						    		{	
						    			$value = htmlspecialchars($value);
						    			$query = 'insert into '.$wpdb->base_prefix.'wpns_attack_logs values ("'.$ipaddress.'","'.$value1.'",'.time().',"'.$value.'");';
						    			$results = $wpdb->get_results($query);
						    			$query = "select count(*) as count from ".$wpdb->base_prefix."wpns_attack_logs where ip='".$ipaddress."' and input != 'RLE';";
						    			$results = $wpdb->get_results($query);

						    			if($results[0]->count>$limitAttack)
						    			{
						    				$query 		= 'select * from '.$wpdb->base_prefix.'wpns_whitelisted_ips where ip_address="'.$ipaddress.'";';
											$results	= $wpdb->get_results($query);
									    	if(sizeof($results)!=0)
									    	{
									    		//IP whitelisted 
									    	}
									    	else
									    	{
						    					$query ="insert into ".$wpdb->base_prefix."wpns_blocked_ips values(NULL,'".$ipaddress."','attack limit exceed',NULL,".current_time( 'timestamp' ).");";
	    										$results =$wpdb->get_results($query);
	    									}
	  						    		}
						    			header('HTTP/1.1 403 Forbidden');
	    								$error_message = "Number of failed login attempts exceeded.";
										include_once(dirname(dirname(__FILE__)).'/views/error/403.php');
						    		}
						    	}
						    	}

						    }
					    	
					    }
				    }
				    
				}
			}
		
		}
     }


		
	

?>