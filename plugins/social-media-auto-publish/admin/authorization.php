<?php
if( !defined('ABSPATH') ){ exit();}
$app_id = get_option('xyz_smap_application_id');
$app_secret = get_option('xyz_smap_application_secret');
$redirecturl=admin_url('admin.php?page=social-media-auto-publish-settings&auth=1');
 	if(is_ssl()===false)
		$redirecturl=preg_replace("/^http:/i", "https:", $redirecturl);
$my_url=urlencode($redirecturl);
if(isset($_POST) && (isset($_POST['fb_auth']) || isset($_POST['lnauth'])) )
{
	ob_clean();
}
if ( xyz_smap_is_session_started() === FALSE ) session_start();
$code="";
if(isset($_REQUEST['code']))
$code = $_REQUEST["code"];

if(isset($_POST['fb_auth']))
{
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_fb_auth_form_nonce' ))
	{
		wp_nonce_ays( 'xyz_smap_fb_auth_form_nonce' );
		exit();
	}
	
		$xyz_smap_session_state = md5(uniqid(rand(), TRUE));
		setcookie("xyz_smap_session_state",$xyz_smap_session_state,"0","/");
		
		$dialog_url = "https://www.facebook.com/".XYZ_SMAP_FB_API_VERSION."/dialog/oauth?client_id="
		. $app_id . "&redirect_uri=" . $my_url . "&state="
		. $xyz_smap_session_state . "&scope=email,public_profile,pages_read_engagement,pages_show_list,pages_manage_posts";
		
		header("Location: " . $dialog_url);
}


if(isset($_COOKIE['xyz_smap_session_state']) && isset($_REQUEST['state']) && ($_COOKIE['xyz_smap_session_state'] === $_REQUEST['state'])) {
	
	$token_url = "https://graph.facebook.com/".XYZ_SMAP_FB_API_VERSION."/oauth/access_token?"
	. "client_id=" . $app_id . "&redirect_uri=" . $my_url
	. "&client_secret=" . $app_secret . "&code=" . $code;
	
	$params = null;$access_token="";
	$response = wp_remote_get($token_url,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
	
	if(is_array($response))
	{
		if(isset($response['body']))
		{
                        $params= json_decode($response['body']);
			if(isset($params->access_token))
			$access_token = $params->access_token;

			//parse_str($response['body'], $params);
			//if(isset($params['access_token']))
			//$access_token = $params['access_token'];
		}
	}
	
	if($access_token!="")
	{
		update_option('xyz_smap_fb_token',$access_token);
		update_option('xyz_smap_af',0);
		
		
		$offset=0;$limit=100;$data=array();
		//$fbid=get_option('xyz_smap_fb_id');
		do
		{
			$result1="";$pagearray1="";
			$pp=wp_remote_get("https://graph.facebook.com/".XYZ_SMAP_FB_API_VERSION."/me/accounts?access_token=$access_token&limit=$limit&offset=$offset",array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
			if(is_array($pp))
			{
				$result1=$pp['body'];
				$pagearray1 = json_decode($result1);
				if(is_array($pagearray1->data))
					$data = array_merge($data, $pagearray1->data);
			}
			else
				break;
			$offset += $limit;
// 			if(!is_array($pagearray1->paging))
// 				break;
// 		}while(array_key_exists("next", $pagearray1->paging));
		}while(isset($pagearray1->paging->next));
		
		
		$count=0;
		if (!empty($data))
		$count=count($data);
			
		$smap_pages_ids1=get_option('xyz_smap_pages_ids');
		$smap_pages_ids0=array();$newpgs="";
		if($smap_pages_ids1!="")
			$smap_pages_ids0=explode(",",$smap_pages_ids1);
		
		$smap_pages_ids=array();$profile_flg=0;
		if (!empty($smap_pages_ids0)){
		for($i=0;$i<count($smap_pages_ids0);$i++)
		{
		if($smap_pages_ids0[$i]!="-1")
			$smap_pages_ids[$i]=trim(substr($smap_pages_ids0[$i],0,strpos($smap_pages_ids0[$i],"-")));
			else{
			$smap_pages_ids[$i]=$smap_pages_ids0[$i];$profile_flg=1;
			}
		}}
		
		for($i=0;$i<$count;$i++)
		{
		if(in_array($data[$i]->id, $smap_pages_ids))
			$newpgs.=$data[$i]->id."-".$data[$i]->access_token.",";
		}
					$newpgs=rtrim($newpgs,",");
	 				if($profile_flg==1)
					{
						if($newpgs!="")
						$newpgs=$newpgs.",-1";
           			 	else
           				$newpgs=-1;
					}
		update_option('xyz_smap_pages_ids',$newpgs);
		
		$url = 'https://graph.facebook.com/'.XYZ_SMAP_FB_API_VERSION.'/me?access_token='.$access_token;
		$contentget=wp_remote_get($url,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));$page_id='';
		if(is_array($contentget))
		{
			$result1=$contentget['body'];
			$pagearray = json_decode($result1);
			$page_id=$pagearray->id;
		}
		update_option('xyz_smap_fb_numericid',$page_id);
		
		header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&auth=1'));
	}
	else {
		
		$xyz_smap_af=get_option('xyz_smap_af');
		
		if($xyz_smap_af==1){
			header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&msg=3'));
			exit();
		}
	}
}
else {
	
	//header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&msg=2'));
	//exit();
}






$ig_app_id = get_option('xyz_smap_igapplication_id');
$ig_app_secret = get_option('xyz_smap_igapplication_secret');
if(isset($_POST['ig_auth']))
{
    if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_ig_auth_form_nonce' ))
    {
        wp_nonce_ays( 'xyz_smap_ig_auth_form_nonce' );
        exit();
    }
    $xyz_smap_ig_session_state = md5(uniqid(rand(), TRUE));
    setcookie("xyz_smap_ig_session_state",$xyz_smap_ig_session_state,"0","/");
    $dialog_url = "https://www.facebook.com/".XYZ_SMAP_FB_API_VERSION."/dialog/oauth?client_id="
        . $ig_app_id . "&redirect_uri=" . $my_url . "&state="
            . $xyz_smap_ig_session_state . "&scope=instagram_basic,pages_read_engagement,instagram_content_publish";
            header("Location: " . $dialog_url);
}
if(isset($_COOKIE['xyz_smap_ig_session_state']) && isset($_REQUEST['state']) && ($_COOKIE['xyz_smap_ig_session_state'] === $_REQUEST['state'])) 
{
    $token_url = "https://graph.facebook.com/".XYZ_SMAP_FB_API_VERSION."/oauth/access_token?"
        . "client_id=" . $ig_app_id . "&redirect_uri=" . $my_url
        . "&client_secret=" . $ig_app_secret . "&code=" . $code;
        
    $params = null;$access_token="";
    $response = wp_remote_get($token_url,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
    
    if(is_array($response))
    {
        if(isset($response['body']))
        {
            $params= json_decode($response['body']);
            if(isset($params->access_token))
                $access_token = $params->access_token;
        }
    }
    if($access_token!="")
    {
        update_option('xyz_smap_ig_token',$access_token);
        update_option('xyz_smap_ig_af',0);
        $offset=0;$limit=100;$data=array();
        do
        {
            $result1="";$pagearray1="";
            $pp=wp_remote_get("https://graph.facebook.com/".XYZ_SMAP_IG_API_VERSION."/me/accounts?access_token=$access_token&limit=$limit&offset=$offset",array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
            if(is_array($pp))
            {
                $result1=$pp['body'];
                $pagearray1 = json_decode($result1);
                if(is_array($pagearray1->data))
                    $data = array_merge($data, $pagearray1->data);
            }
            else
                break;
                $offset += $limit;
            }while(isset($pagearray1->paging->next));
            $count=0;
            if (!empty($data))
                $count=count($data);
            $newpgs="";
            if($count>0)
            {
                $smap_pages_ids1=get_option('xyz_smap_ig_pages_ids');
                $smap_pages_ids0=array();
                if($smap_pages_ids1!="")
                    $smap_pages_ids0=explode(",",$smap_pages_ids1);
                $smap_pages_ids=array();
                for($i=0;$i<count($smap_pages_ids0);$i++)
                {
                    $smap_pages_ids[$i]=trim(substr($smap_pages_ids0[$i],strripos($smap_pages_ids0[$i],"-")+1));
                }
//                     $smap_pages_ids0=array();//check not need
//                     if($smap_pages_ids1!="")//check not need
//                         $smap_pages_ids0=explode(",",$smap_pages_ids1);//check not need
                $business_acc_id_list='';
                for($i=0;$i<$count;$i++)
                {
                    $business_acc_id='';
                    $result=wp_remote_get("https://graph.facebook.com/".XYZ_SMAP_IG_API_VERSION."/".($data[$i]->id)."?fields=instagram_business_account&access_token=".$access_token,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
                    if($result['body']!=NULL)
                    {
                        $business_acc='';
                        $business_acc=json_decode($result['body']);
                        if(isset($business_acc->instagram_business_account) && ($business_acc->instagram_business_account!=NULL))
                        {
                            $business_acc_details=$business_acc->instagram_business_account;
                            $business_acc_id=$business_acc_details->id;
                        }
                        
                        if($business_acc_id!='' && in_array($business_acc_id, $smap_pages_ids))
                            $business_acc_id_list.=$data[$i]->id."-".$data[$i]->access_token."-".$business_acc_id.",";
                    }
                }
                $business_acc_id_list=rtrim($business_acc_id_list,",");
                if($business_acc_id_list!="")
                    update_option('xyz_smap_ig_pages_ids',$business_acc_id_list);
                    header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&auth=1'));
                }
                $url = 'https://graph.facebook.com/'.XYZ_SMAP_IG_API_VERSION.'/me?access_token='.$access_token;
                $contentget=wp_remote_get($url,array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));$page_id='';
                if(is_array($contentget))
                {
                    $result1=$contentget['body'];
                    $pagearray = json_decode($result1);
                    $page_id=$pagearray->id;
                }
                update_option('xyz_smap_ig_numericid',$page_id);
                header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&auth=1&msg=9'));
        }
        else {
            $xyz_smap_ig_af=get_option('xyz_smap_ig_af');
            if($xyz_smap_ig_af==1){
                header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&msg=3'));
                exit();
            }
        }
}
$state=md5(get_home_url());

$redirecturl=urlencode(admin_url('admin.php?page=social-media-auto-publish-settings'));

	$lnappikey=get_option('xyz_smap_lnapikey');
	$lnapisecret=get_option('xyz_smap_lnapisecret');
	$xyz_smap_ln_api_permission=get_option('xyz_smap_ln_api_permission');
  $xyz_smap_ln_signin_method=get_option('xyz_smap_ln_signin_method');
  if($xyz_smap_ln_signin_method==1)
  {
  	$smap_ln_profile_scopes="r_liteprofile";
  	$userid_index="id";
  	$user_data_endpoint ='https://api.linkedin.com/v2/me';
  }
  else
  {
  	$smap_ln_profile_scopes="openid+profile+email";
  	$userid_index="sub";
  	$user_data_endpoint ='https://api.linkedin.com/v2/userinfo';
  }
	if(isset($_POST['lnauth']))
	{
		if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_ln_auth_form_nonce' ))
		{
			wp_nonce_ays( 'xyz_smap_ln_auth_form_nonce' );
			exit();
		}
		if(!isset($_GET['code']))
		{
			if ($xyz_smap_ln_api_permission==0)
			$linkedin_auth_url='https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$lnappikey.'&scope='.$smap_ln_profile_scopes.'+w_member_social&state='.$state.'&redirect_uri='.$redirecturl;
				elseif ($xyz_smap_ln_api_permission==1)
				$linkedin_auth_url='https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$lnappikey.'&redirect_uri='.$redirecturl.'&state='.$state.'&scope='.$smap_ln_profile_scopes.'+w_member_social+w_organization_social+r_organization_social+rw_organization_admin';
			wp_redirect($linkedin_auth_url);
			echo '<script>document.location.href="'.$linkedin_auth_url.'"</script>';
			die;
		
		}
	}
	if( isset($_GET['error']) && isset($_GET['error_description']) )//if any error
	{
		header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&ln_auth_err='.$_GET['error'].':'.$_GET['error_description']));
		exit();
	}
	else if(isset($_GET['code']) && isset($_GET['state']) && $_GET['state']==$state)
	{
		$url = 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&redirect_uri='.$redirecturl.'&client_id='.$lnappikey.'&client_secret='.$lnapisecret.'&code='.$_GET['code'];
		$response = wp_remote_post( $url, array('method' => 'POST',
							'sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));	// Access Token request
		$ln_acc_tok_json=$response['body'];
		$ln_acc_tok_arr=json_decode($ln_acc_tok_json);
		if(isset($ln_acc_tok_arr->access_token))
		{
		$ObjLinkedin = new SMAPLinkedInOAuth2($ln_acc_tok_arr->access_token);
		$userdata=$ObjLinkedin->xyz_smap_fetch_user_data($user_data_endpoint);
    if (isset($userdata[$userid_index])){
      update_option('xyz_smap_lnappscoped_userid', $userdata[$userid_index]);
		}
		update_option('xyz_smap_application_lnarray', $ln_acc_tok_json);
		update_option('xyz_smap_lnaf',0);
		header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&msg=4'));
		exit();
		}
		else if (isset($ln_acc_tok_arr->error)&& isset($ln_acc_tok_arr->error_description))
		{
			header("Location:".admin_url('admin.php?page=social-media-auto-publish-settings&ln_auth_err='.$ln_acc_tok_arr->error.':'.$ln_acc_tok_arr->error_description));
			exit();
		}
	}

