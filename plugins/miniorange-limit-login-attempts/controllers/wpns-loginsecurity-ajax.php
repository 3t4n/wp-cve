<?php
class Mo_lla_wpns_ajax
{
	function __construct(){
		//add comment here
		add_action( 'admin_init'  , array( $this, 'mo_login_security_ajax' ) );
	}

	function mo_login_security_ajax(){
		add_action( 'wp_ajax_lla_login_security', array($this,'lla_login_security') );
        
        if (get_site_option('mo_lla_plugin_redirect')) {
            delete_site_option('mo_lla_plugin_redirect');
            wp_redirect(admin_url() .'admin.php?page=mo_lla_login_and_spam');
            exit;
        }
	}
		function lla_login_security(){
			switch(sanitize_text_field($_POST['lla_loginsecurity_ajax']))
			{
				case "lla_bruteforce_form":
					$this->lla_handle_bf_configuration_form();	break;
				case "lla_save_captcha":
					$this->lla_captcha_settings();break;
				case 'lla_ManualIPBlock_form':
					$this->lla_handle_IP_blocking();break;
				case 'lla_WhitelistIP_form':
					$this->lla_whitelist_ip(); break;
				case 'lla_waf_settings_form':
					$this->lla_waf_settings_form(); break;
                case 'lla_userSession_form_reset':
                    $this->lla_userSession_form_reset(); break;
				case 'lla_waf_rate_limiting_form':
					$this->lla_waf_rate_limiting_form(); break;	
				case 'lla_ip_lookup':
					$this->lla_ip_lookup(); 	break;	
				case 'lla_userSession_form':
					$this->lla_userSession_form(); break;	
				case 'lla_inactive_user_logout_form':
					$this->lla_inactive_user_logout_form(); break;
				case 'lla_xmlrpc_form':
					$this->lla_xmlrpc_form(); break;
                case 'mo_lla_black_friday_remove':   
                    $this->mo_lla_blackfriday_remove(); break;
			}
		}

        function mo_lla_blackfriday_remove(){
            $nonce = sanitize_text_field( $_POST['nonce']);
            if (! wp_verify_nonce($nonce, 'mo_lla-remove-offer-banner')) {
               wp_send_json('ERROR');
               return;
           }else{
                update_site_option('mo_lla_remove_offer_banner',true);
                wp_send_json('SUCCESS');
           }
    
        }
		function lla_handle_bf_configuration_form(){

	   		$nonce = sanitize_text_field($_POST['nonce']);
	   		if ( !wp_verify_nonce( $nonce, 'lla-brute-force' ) ){
	   			wp_send_json('ERROR');
	   			return;
	   		}
	   		$brute_force        = sanitize_text_field($_POST['bf_enabled/disabled']);
			$login_attempts 	= sanitize_text_field($_POST['allwed_login_attempts']);
			$blocking_type  	= sanitize_text_field($_POST['time_of_blocking_type']);
			$blocking_value 	= isset($_POST['time_of_blocking_val'])	 ? sanitize_text_field($_POST['time_of_blocking_val'])	: false;
			$show_login_attempts= sanitize_text_field($_POST['show_remaining_attempts']);
			if($show_login_attempts == 'true'){$show_login_attempts = "on";} else if($show_login_attempts == 'false') { $show_login_attempts = "";}
			if($brute_force == 'on' && $login_attempts == "" ){
				wp_send_json('empty');
				return;
			}  
            $brute_force_option=false;
            $brute_force=="true" ? $brute_force_option=true : $brute_force_option=false;
	  		update_option( 'mo_lla_enable_brute_force' 	    , $brute_force_option 	  );
			update_option( 'mo_lla_allwed_login_attempts'	, $login_attempts 		  );
			update_option( 'mo_lla_time_of_blocking_type'	, $blocking_type 		  );
			update_option( 'mo_lla_time_of_blocking_val' 	, $blocking_value   	  );
			update_option( 'mo_lla_show_remaining_attempts' , $show_login_attempts    );  
			wp_send_json($brute_force);
			
		}
        function lla_handle_IP_blocking()
        {

            global $mo_lla_dirName;
            if(!wp_verify_nonce(sanitize_text_field($_POST['nonce']),'manualIPBlockingNonce'))
            {
                echo "NonceDidNotMatch";
                exit;
            }
            else
            {
                include_once($mo_lla_dirName.'controllers'.DIRECTORY_SEPARATOR.'ip-blocking.php');
            }
        }
        function lla_whitelist_ip()
        {
            global $mo_lla_dirName;
            if(!wp_verify_nonce(sanitize_text_field($_POST['nonce']),'IPWhiteListingNonce'))
            {
                echo "NonceDidNotMatch";
                exit;
            }
            else
            {
                include_once($mo_lla_dirName.'controllers'.DIRECTORY_SEPARATOR.'ip-blocking.php');
            }
        }
        function lla_userSession_form()
	{
		if(!wp_verify_nonce(sanitize_text_field($_POST['nonce']),'UserSessiontimeoutNonce'))
		{
			echo "NonceDidNotMatch";
			exit;
		}
		else
		{
			$time  	= sanitize_text_field($_POST['time']);
			$timef 	= floatval($time);			
			$time 	= intval($time);
			
			if(is_numeric($time) && $timef == $time && $time>0)
			{
				update_option('mo_lla_logout_time',$time);
				echo "SavedSessionSettings";
				exit;
			}
			else
			{
				echo "FormatDidNotmatch";
				exit;
			}

		}
	}

        function lla_userSession_form_reset()
        {

            if(!wp_verify_nonce(sanitize_text_field($_POST['nonce']),'UserSessiontimeoutNonce'))
            {
                echo "NonceDidNotMatch";
                exit;
            }
            else
            {

                    delete_option('mo_lla_logout_time');
                    echo "ResetSessionSettings";
                    exit;
            }
        }

        function lla_ip_lookup()
        {
            
            if(!wp_verify_nonce(sanitize_text_field($_POST['nonce']),'IPLookUPNonce'))
            {
                echo "NonceDidNotMatch";
                exit;
            }
            else
            {
                $ip  = sanitize_text_field($_POST['IP']);
                if(!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/',$ip))
                {
                    echo("INVALID_IP_FORMAT");
                    exit;
                }
                else if(! filter_var($ip, FILTER_VALIDATE_IP)){
                    echo("INVALID_IP");
                    exit;
                }
                $result_json=wp_remote_post("http://www.geoplugin.net/json.gp?ip=".$ip);
                if( !is_wp_error( $result_json ) ) {
                    $result_json=wp_remote_retrieve_body( $result_json);
                }
                $result = json_decode($result_json, true);
                if($result["geoplugin_status"]==429){
                    //returns when user ip is blocked by geoplugin server 
                    echo("IP_NOT_FOUND");
                    exit;
                }
                $hostname 	= gethostbyaddr($result["geoplugin_request"]);
                try{
                    $timeoffset	= timezone_offset_get(new DateTimeZone($result["geoplugin_timezone"]),new DateTime('now'));
                    $timeoffset = $timeoffset/3600;

                }catch(Exception $e){
                    $result["geoplugin_timezone"]="";
                    $timeoffset="";
                }
                $ipLookUpTemplate  = Mo_lla_MoWpnsConstants::IP_LOOKUP_TEMPLATE;
                if($result['geoplugin_request']==$ip) {
                    $ipLookUpTemplate = str_replace("{{status}}", $result["geoplugin_status"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{ip}}", $result["geoplugin_request"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{region}}", $result["geoplugin_region"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{country}}", $result["geoplugin_countryName"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{city}}", $result["geoplugin_city"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{continent}}", $result["geoplugin_continentName"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{latitude}}", $result["geoplugin_latitude"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{longitude}}", $result["geoplugin_longitude"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{timezone}}", $result["geoplugin_timezone"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{curreny_code}}", $result["geoplugin_currencyCode"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{curreny_symbol}}", $result["geoplugin_currencySymbol"], $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{per_dollar_value}}", $result["geoplugin_currencyConverter"],$ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{hostname}}", $hostname, $ipLookUpTemplate);
                    $ipLookUpTemplate = str_replace("{{offset}}", $timeoffset, $ipLookUpTemplate);
                    $result['ipDetails'] = $ipLookUpTemplate;
                }else{
                    $result["ipDetails"]["status"]="ERROR";
                }
                wp_send_json( $result );
            }
        }
        function lla_waf_settings_form()
        {
            $dir_name =  dirname(__FILE__);
            $dir_name1 = explode('wp-content', $dir_name);
            $dir_name = $dir_name1[0];
            $filepath = str_replace('\\', '/', $dir_name1[0]);
            $fileName = $filepath.'/wp-includes/mo-waf-config.php';
            if(!file_exists($fileName))
            {
                $file    = fopen($fileName, "a+");
                $string  = "<?php".PHP_EOL;
                $string .= '$SQL=1;'.PHP_EOL;
                $string .= '$XSS=1;'.PHP_EOL;
                $string .= '$RCE=0;'.PHP_EOL;
                $string .= '$LFI=0;'.PHP_EOL;
                $string .= '$RFI=0;'.PHP_EOL;
                $string .= '$RateLimiting=1;'.PHP_EOL;
                $string .= '$RequestsPMin=240;'.PHP_EOL;
                $string .= '$actionRateL="ThrottleIP";'.PHP_EOL;
                $string .= '?>'.PHP_EOL;

                fwrite($file, $string);
                fclose($file);
            }

            if(!wp_verify_nonce($_POST['nonce'],'WAFsettingNonce'))
            {
                var_dump("NonceDidNotMatch");
                exit;
            }
            else
            {
                switch (sanitize_text_field($_POST['optionValue'])) {
                    case "SQL":
                        $this->savesql();			break;
                    case "XSS":
                        $this->savexss();			break;
                    case "RCE":
                        $this->saverce();			break;
                    case "RFI":
                        $this->saverfi();			break;
                    case "LFI":
                        $this->savelfi();			break;
                    case "WAF":
                        $this->saveWAF();			break;
                    case "limitAttack":
                        $this->limitAttack();		break;
                    default:
                        break;
                }
            }
        }
        function lla_waf_rate_limiting_form()
        {
            if(!wp_verify_nonce(sanitize_text_field($_POST['nonce']),'RateLimitingNonce'))
            {
                echo "NonceDidNotMatch";
                exit;
            }
            else
            {
                if(get_site_option('WAFEnabled') != 1)
                {
                    echo "WAFNotEnabled";
                    exit;
                }
                if(sanitize_text_field($_POST['Requests'])!='')
                {

                    if(is_numeric($_POST['Requests']))
                    {
                    $dir_name  =  dirname(__FILE__);
                    $dir_name1 =  explode('wp-content', $dir_name);
                    $dir_name  = $dir_name1[0];
                    $filepath  = str_replace('\\', '/', $dir_name1[0]);
                    $fileName  = $filepath.'/wp-includes/mo-waf-config.php';
                    $file      = file_get_contents($fileName);
                    $data      = $file;
                    $req       = sanitize_text_field($_POST['Requests']);

                    if($req >1)
                    {
                        update_option('Rate_request',$req);
                        if(isset($_POST['rateCheck']))
                        {
                            if(sanitize_text_field($_POST['rateCheck']) == 'on')
                            {
                                update_option('Rate_limiting','1');
                                echo "RateEnabled";
                                if(strpos($file, 'RateLimiting')!=false)
                                {
                                    $file = str_replace('$RateLimiting=0;', '$RateLimiting=1;', $file);
                                    $data = $file;
                                    file_put_contents($fileName,$file);

                                }
                                else
                                {
                                    $content = explode('?>', $file);
                                    $file = $content[0];
                                    $file .= PHP_EOL;
                                    $file .= '$RateLimiting=1;'.PHP_EOL;
                                    $file .='?>';
                                    file_put_contents($fileName,$file);
                                    $data = $file;
                                }
                            }
                        }
                        else
                        {
                            update_option('Rate_limiting','0');
                            echo "Ratedisabled";
                            if(strpos($file, 'RateLimiting')!=false)
                            {
                                $file = str_replace('$RateLimiting=1;', '$RateLimiting=0;', $file);
                                $data = $file;
                                file_put_contents($fileName,$file);
                            }
                            else
                            {
                                $content = explode('?>', $file);
                                $file = $content[0];
                                $file .= PHP_EOL;
                                $file .= '$RateLimiting=0;'.PHP_EOL;
                                $file .='?>';
                                file_put_contents($fileName,$file);
                                $data = $file;
                            }

                        }

                        $file = $data;
                        if(strpos($file, 'RequestsPMin')!=false)
                        {
                            $content = explode(PHP_EOL, $file);
                            $con = '';
                            $len =  sizeof($content);

                            for($i=0;$i<$len;$i++)
                            {
                                if(strpos($content[$i], 'RequestsPMin')!=false)
                                {
                                    $con.='$RequestsPMin='.$req.';'.PHP_EOL;
                                }
                                else
                                {
                                    $con .= $content[$i].PHP_EOL;
                                }
                            }

                            file_put_contents($fileName,$con);
                            $data = $con;
                        }

                        else
                        {
                            $content = explode('?>', $file);
                            $file = $content[0];
                            $file .= PHP_EOL;
                            $file .= '$RequestsPMin='.$req.';'.PHP_EOL;
                            $file .='?>';
                            file_put_contents($fileName,$file);
                            $data = $file;
                        }

                        if(sanitize_text_field($_POST['actionOnLimitE'])=='BlockIP' || sanitize_text_field($_POST['actionOnLimitE']) == 1)
                        {
                            update_option('actionRateL',1);

                            $file = $data;
                            if(strpos($file, 'actionRateL')!=false)
                            {
                                $content = explode(PHP_EOL, $file);
                                $con = '';
                                foreach ($content as $line => $lineV) {
                                    if(strpos($lineV, 'actionRateL')!=false)
                                    {
                                        $con.='$actionRateL="BlockIP";'.PHP_EOL;
                                    }
                                    else
                                    {
                                        $con .= $lineV.PHP_EOL;
                                    }
                                }
                                file_put_contents($fileName,$con);
                            }
                            else
                            {
                                $content = explode('?>', $file);
                                $file = $content[0];
                                $file .= PHP_EOL;
                                $file .= '$actionRateL="BlockIP";'.PHP_EOL;
                                $file .='?>';
                                file_put_contents($fileName,$file);
                                $file = $data;
                            }
                        }
                        else if(sanitize_text_field($_POST['actionOnLimitE']) =='ThrottleIP' || sanitize_text_field($_POST['actionOnLimitE']) == 0)
                        {

                            $file = $data;
                            update_option('actionRateL',0);
                            if(strpos($file, 'actionRateL')!=false)
                            {
                                $content = explode(PHP_EOL, $file);
                                $con = '';
                                foreach ($content as $line => $lineV) {
                                    if(strpos($lineV, 'actionRateL')!=false)
                                    {
                                        $con.='$actionRateL="ThrottleIP";'.PHP_EOL;
                                    }
                                    else
                                    {
                                        $con .= $lineV.PHP_EOL;
                                    }
                                }
                                file_put_contents($fileName,$con);
                            }
                            else
                            {
                                $content = explode('?>', $file);
                                $file = $content[0];
                                $file .= PHP_EOL;
                                $file .= '$actionRateL="ThrottleIP";'.PHP_EOL;
                                $file .='?>';
                                file_put_contents($fileName,$file);
                            }
                        }

                }
                exit;
            }}
            echo("Error");
            exit;
            }


        }
        private function saveWAF()
        {
            if(isset($_POST['pluginWAF']))
            {
                if(sanitize_text_field($_POST['pluginWAF'])=='on')
                {
                    update_option('WAF','PluginLevel');
                    update_option('WAFEnabled','1');
                    echo("PWAFenabled");exit;
                }
            }
            else
            {
                update_option('WAFEnabled','0');
                update_option('WAF','wafDisable');
                echo("PWAFdisabled");exit;
            }
        }

        private function savesql()
        {
            if(isset($_POST['SQL']))
            {
                if(sanitize_text_field($_POST['SQL'])=='on')
                {
                    update_option('SQLInjection',1);
                    $dir_name =  dirname(__FILE__);
                    $dir_name1 = explode('wp-content', $dir_name);
                    $dir_name = $dir_name1[0];
                    $filepath = str_replace('\\', '/', $dir_name1[0]);
                    $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                $file = file_get_contents($fileName);
                if(strpos($file, 'SQL')!=false)
                {
                    $file = str_replace('$SQL=0;', '$SQL=1;', $file);
                    file_put_contents($fileName,$file);
                }
                else
                {
                    $content = explode('?>', $file);
                    $file = $content[0];
                    $file .= PHP_EOL;
                    $file .= '$SQL=1;'.PHP_EOL;
                    $file .='?>';
                    file_put_contents($fileName,$file);
                }
                echo("SQLenable");
                exit;

                }
            }
            else
            {
                update_option('SQLInjection',0);

                $dir_name =  dirname(__FILE__);
                $dir_name1 = explode('wp-content', $dir_name);
                $dir_name = $dir_name1[0];
                $filepath = str_replace('\\', '/', $dir_name1[0]);
                $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                $file = file_get_contents($fileName);
                if(strpos($file, '$SQL')!=false)
                {
                    $file = str_replace('$SQL=1;', '$SQL=0;', $file);
                    file_put_contents($fileName,$file);
                }
                else
                {
                    $content = explode('?>', $file);
                    $file = $content[0];
                    $file .= PHP_EOL;
                    $file .= '$SQL=0;'.PHP_EOL;
                    $file .='?>';
                    file_put_contents($fileName,$file);
                }

                echo("SQLdisable");
                exit;

            }

        }
        private function saverce()
        {
            if(isset($_POST['RCE']))
            {
                if(sanitize_text_field($_POST['RCE'])=='on')
                {
                    update_option('RCEAttack',1);

                    $dir_name =  dirname(__FILE__);
                    $dir_name1 = explode('wp-content', $dir_name);
                    $dir_name = $dir_name1[0];
                    $filepath = str_replace('\\', '/', $dir_name1[0]);
                    $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                    $file = file_get_contents($fileName);
                    if(strpos($file, 'RCE')!=false)
                    {
                        $file = str_replace('$RCE=0;', '$RCE=1;', $file);
                        file_put_contents($fileName,$file);
                    }
                    else
                    {
                        $content = explode('?>', $file);
                        $file = $content[0];
                        $file .= PHP_EOL;
                        $file .= '$RCE=1;'.PHP_EOL;
                        $file .='?>';
                        file_put_contents($fileName,$file);
                    }
                    echo("RCEenable");
                    exit;
                }
            }
            else
            {
                update_option('RCEAttack',0);

                $dir_name =  dirname(__FILE__);
                $dir_name1 = explode('wp-content', $dir_name);
                $dir_name = $dir_name1[0];
                $filepath = str_replace('\\', '/', $dir_name1[0]);
                $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                $file = file_get_contents($fileName);
                if(strpos($file, '$RCE')!=false)
                {
                    $file = str_replace('$RCE=1;', '$RCE=0;', $file);
                    file_put_contents($fileName,$file);
                }
                else
                {
                    $content = explode('?>', $file);
                    $file = $content[0];
                    $file .= PHP_EOL;
                    $file .= '$RCE=0;'.PHP_EOL;
                    $file .='?>';
                    file_put_contents($fileName,$file);
                }
                echo("RCEdisable");
                exit;

            }

        }
        private function savexss()
        {
            if(isset($_POST['XSS']))
            {
                if(sanitize_text_field($_POST['XSS'])=='on')
                {
                    update_option('XSSAttack',1);
                    $dir_name =  dirname(__FILE__);
                    $dir_name1 = explode('wp-content', $dir_name);
                    $dir_name = $dir_name1[0];
                    $filepath = str_replace('\\', '/', $dir_name1[0]);
                    $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                    $file = file_get_contents($fileName);
                    if(strpos($file, 'XSS')!=false)
                    {
                        $file = str_replace('$XSS=0;', '$XSS=1;', $file);
                        file_put_contents($fileName,$file);
                    }
                    else
                    {
                        $content = explode('?>', $file);
                        $file = $content[0];
                        $file .= PHP_EOL;
                        $file .= '$XSS=1;'.PHP_EOL;
                        $file .='?>';
                        file_put_contents($fileName,$file);
                    }
                    echo("XSSenable");
                    exit;
                }
            }
            else
            {
                update_option('XSSAttack',0);
                $dir_name =  dirname(__FILE__);
                $dir_name1 = explode('wp-content', $dir_name);
                $dir_name = $dir_name1[0];
                $filepath = str_replace('\\', '/', $dir_name1[0]);
                $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                $file = file_get_contents($fileName);
                if(strpos($file, '$XSS')!=false)
                {
                    $file = str_replace('$XSS=1;', '$XSS=0;', $file);
                    file_put_contents($fileName,$file);
                }
                else
                {
                    $content = explode('?>', $file);
                    $file = $content[0];
                    $file .= PHP_EOL;
                    $file .= '$XSS=0;'.PHP_EOL;
                    $file .='?>';
                    file_put_contents($fileName,$file);
                }
                echo("XSSdisable");
                exit;
            }

        }
        private function savelfi()
        {
            if(isset($_POST['LFI']))
            {
            

                if(sanitize_text_field($_POST['LFI'])=='on')
                {
                    update_option('LFIAttack',1);
                    $dir_name =  dirname(__FILE__);
                    $dir_name1 = explode('wp-content', $dir_name);
                    $dir_name = $dir_name1[0];
                    $filepath = str_replace('\\', '/', $dir_name1[0]);
                    $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                    $file = file_get_contents($fileName);
                    if(strpos($file, 'LFI')!=false)
                    {
                        $file = str_replace("LFI=0;", "LFI=1;", $file);
                        file_put_contents($fileName,$file);
                    }
                    else
                    {
                        $content = explode('?>', $file);
                        $file = $content[0];
                        $file .= PHP_EOL;
                        $file .= '$LFI=1;'.PHP_EOL;
                        $file .='?>';
                        file_put_contents($fileName,$file);
                    }
                    $file = file_get_contents($fileName);

                    echo("LFIenable");
                    exit;
                }
            }
            else
            {
                update_option('LFIAttack',0);
                $dir_name =  dirname(__FILE__);
                $dir_name1 = explode('wp-content', $dir_name);
                $dir_name = $dir_name1[0];
                $filepath = str_replace('\\', '/', $dir_name1[0]);
                $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                $file = file_get_contents($fileName);
                if(strpos($file, '$LFI')!=false)
                {
                    $file = str_replace('$LFI=1;', '$LFI=0;', $file);
                    file_put_contents($fileName,$file);
                }
                else
                {
                    $content = explode('?>', $file);
                    $file = $content[0];
                    $file .= PHP_EOL;
                    $file .= '$LFI=0;'.PHP_EOL;
                    $file .='?>';
                    file_put_contents($fileName,$file);
                }
                echo("LFIdisable");
                exit;
            }

        }
        private function saverfi()
        {
            if(isset($_POST['RFI']))
            {
                if(sanitize_text_field($_POST['RFI'])=='on')
                {
                    update_option('RFIAttack',1);
                    $dir_name =  dirname(__FILE__);
                    $dir_name1 = explode('wp-content', $dir_name);
                    $dir_name = $dir_name1[0];
                    $filepath = str_replace('\\', '/', $dir_name1[0]);
                    $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                    $file = file_get_contents($fileName);
                    if(strpos($file, 'RFI')!=false)
                    {
                        $file = str_replace('$RFI=0;', '$RFI=1;', $file);
                        file_put_contents($fileName,$file);
                    }
                    else
                    {
                        $content = explode('?>', $file);
                        $file = $content[0];
                        $file .= PHP_EOL;
                        $file .= '$RFI=1;'.PHP_EOL;
                        $file .='?>';
                        file_put_contents($fileName,$file);
                    }
                    echo("RFIenable");
                    exit;
                }
            }
            else
            {
                update_option('RFIAttack',0);
                $dir_name =  dirname(__FILE__);
                $dir_name1 = explode('wp-content', $dir_name);
                $dir_name = $dir_name1[0];
                $filepath = str_replace('\\', '/', $dir_name1[0]);
                $fileName = $filepath.'/wp-includes/mo-waf-config.php';

                $file = file_get_contents($fileName);
                if(strpos($file, '$RFI')!=false)
                {
                    $file = str_replace('$RFI=1;', '$RFI=0;', $file);
                    file_put_contents($fileName,$file);
                }
                else
                {
                    $content = explode('?>', $file);
                    $file = $content[0];
                    $file .= PHP_EOL;
                    $file .= '$RFI=0;'.PHP_EOL;
                    $file .='?>';
                    file_put_contents($fileName,$file);
                }
                echo("RFIdisable");
                exit;
            }

        }
        private function limitAttack()
        {
            if(isset($_POST['limitAttack']))
            {
                $value = sanitize_text_field($_POST['limitAttack']);
                if($value>1)
                {
                    update_option('limitAttack',$value);
                    echo "limitSaved";
                    exit;
                }
                else
                {
                    echo "limitIsLT1";
                    exit;
                }

            }
        }
	function lla_captcha_settings(){


        $nonce=sanitize_text_field($_POST['nonce']);
            if ( ! wp_verify_nonce( $nonce, 'lla-captcha' ) ){
                wp_send_json('ERROR');
                return;
            }
        $site_key = sanitize_text_field($_POST['site_key']);
        $secret_key = sanitize_text_field($_POST['secret_key']);
        $enable_captcha = sanitize_text_field($_POST['enable_captcha']);
        $login_form_captcha = sanitize_text_field($_POST['login_form']);
        $reg_form_captcha = sanitize_text_field($_POST['registeration_form']);

        if((isset($_POST['version'])))
        {
            $mo2f_g_version = sanitize_text_field($_POST['version']);
        }

        $enable_captcha = ($enable_captcha == 'true') ? $enable_captcha = "on" : $enable_captcha = "";

        $login_form_captcha = ($login_form_captcha == 'true') ? $login_form_captcha = "on" : $login_form_captcha = "";

        $reg_form_captcha = ($reg_form_captcha == 'true') ? $reg_form_captcha = "on" : $reg_form_captcha = "";

        if(($site_key == "" || $secret_key == "") && $enable_captcha == 'on'){
            wp_send_json('empty');
            return;
        }


        if((($login_form_captcha == "on") || ($enable_captcha=="on")) && $mo2f_g_version==""){
         wp_send_json('version_select');
         return;
         }
        if($mo2f_g_version=='reCAPTCHA_v2')
        {
            update_option( 'mo_lla_recaptcha_site_key'			 		, $site_key     );
            update_option( 'mo_lla_recaptcha_secret_key'				, $secret_key   );
        }
        if($mo2f_g_version=='reCAPTCHA_v3')
        {

            update_option( 'mo_lla_recaptcha_site_key_v3'			 	    , $site_key     );
            update_option( 'mo_lla_recaptcha_secret_key_v3'				, $secret_key   );
        }

        update_option( 'mo_lla_activate_recaptcha'			 		,  $enable_captcha );
        update_option( 'mo_lla_recaptcha_version'			 		,  $mo2f_g_version );


        if($enable_captcha == "on"){
                update_option( 'mo_lla_activate_recaptcha_for_login'	, $login_form_captcha );
                update_option( 'mo_lla_activate_recaptcha_for_woocommerce_login', $login_form_captcha );
                update_option('mo_lla_activate_recaptcha_for_registration', $reg_form_captcha   );
                update_option( 'mo_lla_activate_recaptcha_for_woocommerce_registration',$reg_form_captcha   );
                update_site_option('recaptcha_notification_option',1);
                wp_send_json('true');
            }
            else if($enable_captcha == ""){
                update_option( 'mo_lla_activate_recaptcha_for_login'	, '' );
                update_option( 'mo_lla_activate_recaptcha_for_woocommerce_login', '' );
                update_option('mo_lla_activate_recaptcha_for_registration', ''   );
                update_option( 'mo_lla_activate_recaptcha_for_woocommerce_registration','' );
                wp_send_json('false');
            }

    }

	function lla_xmlrpc_form()
	{
        $disable_xml_rpc_checkbox='';
		if(isset($_POST['disableXMLRPC']))
        $disable_xml_rpc_checkbox=sanitize_text_field($_POST['disableXMLRPC']);                 
		update_option('mo_wpns_disable_xml_rpc',$disable_xml_rpc_checkbox);
		if($disable_xml_rpc_checkbox == 'on')
		{
			add_filter( 'xmlrpc_enabled', '__return_false' );
			echo "DisabledXMLRPC";
			exit;
		}
		else
		{
            add_filter( 'xmlrpc_enabled', '__return_true' );
        	echo "NotDisabledXMLRPC";
        	exit;
        }
	}
	
	function lla_inactive_user_logout_form(){
			$nonce = sanitize_text_field($_POST['nonce']);
	   		if ( ! wp_verify_nonce( $nonce, 'InactiveUserLNonce' ) ){
	   			echo "NonceDidNotMatch";
	   			exit;
	   		}
	   		$loginDuration = isset($_POST['loginDuration'])?sanitize_text_field($_POST['loginDuration']):'';
	   		$adminSession  = isset($_POST['adminSession'])?sanitize_text_field($_POST['adminSession']):'';
	   		$enableIUL     = isset($_POST['enableIUL'])?sanitize_text_field($_POST['enableIUL']):'';

			if($loginDuration != '' && $enableIUL=='on')
			{
				update_option('mo_lla_inactive_user_logout',sanitize_text_field($enableIUL));
				update_option('mo_inactive_allowed_admin_session',sanitize_text_field($adminSession));
				update_option('mo_inactive_logout_duration',sanitize_text_field($loginDuration));
				echo "SettingsSaved";
				exit;
			}
            else if($loginDuration != '' && $enableIUL==''){
                update_option('mo_lla_inactive_user_logout',sanitize_text_field($enableIUL));
				update_option('mo_inactive_allowed_admin_session',sanitize_text_field($adminSession));
				update_option('mo_inactive_logout_duration',sanitize_text_field($loginDuration));
				echo "SettingDisabled";
				exit;
            }
            
			echo "error";
			exit;
	}

	
}
new Mo_lla_wpns_ajax;

?>