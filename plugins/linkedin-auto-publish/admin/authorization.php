<?php
if( !defined('ABSPATH') ){ exit();}
if(isset($_POST) && isset($_POST['lnauth'] ))
{
	ob_clean();
}
if ( xyz_lnap_is_session_started() === FALSE ) session_start();


$state=md5(get_home_url());

$redirecturl=urlencode(admin_url('admin.php?page=linkedin-auto-publish-settings'));

$lnappikey=get_option('xyz_lnap_lnapikey');
$lnapisecret=get_option('xyz_lnap_lnapisecret');
$xyz_lnap_ln_api_permission=get_option('xyz_lnap_ln_api_permission');
$xyz_lnap_ln_signin_method=get_option('xyz_lnap_ln_signin_method');
if($xyz_lnap_ln_signin_method==1)
{
	$lnap_profile_scopes="r_liteprofile";
	$userid_index="id";
	$user_data_endpoint ='https://api.linkedin.com/v2/me';
}
else
{
	$lnap_profile_scopes="openid+profile+email";
	$userid_index="sub";
	$user_data_endpoint ='https://api.linkedin.com/v2/userinfo';
}
if(isset($_POST['lnauth']))
{
	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_lnap_auth_nonce' ) )
			{
				wp_nonce_ays( 'xyz_lnap_auth_nonce' );

				exit();

			}

			if(!isset($_GET['code']))
			{
		if ($xyz_lnap_ln_api_permission==0)
						$linkedin_auth_url='https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$lnappikey.'&scope='.$lnap_profile_scopes.'+w_member_social&state='.$state.'&redirect_uri='.$redirecturl;
		elseif ($xyz_lnap_ln_api_permission==1)
						$linkedin_auth_url='https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$lnappikey.'&redirect_uri='.$redirecturl.'&state='.$state.'&scope='.$lnap_profile_scopes.'+w_member_social+w_organization_social+r_organization_social+rw_organization_admin';
				wp_redirect($linkedin_auth_url);
				echo '<script>document.location.href="'.$linkedin_auth_url.'"</script>';
				die;

			}
}
if( isset($_GET['error']) && isset($_GET['error_description']) )//if any error
{
	header("Location:".admin_url('admin.php?page=linkedin-auto-publish-settings&ln_auth_err='.$_GET['error'].':'.$_GET['error_description']));
	exit();
}
else if(isset($_GET['code']) && isset($_GET['state']) && $_GET['state']==$state)
{


	$url = 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&redirect_uri='.$redirecturl.'&client_id='.$lnappikey.'&client_secret='.$lnapisecret.'&code='.$_GET['code'];
	$response = wp_remote_post( $url, array('method' => 'POST',
			'sslverify'=> (get_option('xyz_lnap_peer_verification')=='1') ? true : false));	// Access Token request
	
	$ln_acc_tok_json=$response['body'];
	$ln_acc_tok_arr=json_decode($ln_acc_tok_json);
	if(isset($ln_acc_tok_arr->access_token))
	{
		$ObjLinkedin = new LNAPLinkedInOAuth2($ln_acc_tok_arr->access_token);
		$userdata=$ObjLinkedin->xyz_lnap_fetch_user_data($user_data_endpoint);
		if (isset($userdata[$userid_index])){
			update_option('xyz_lnap_lnappscoped_userid', $userdata[$userid_index]);
		}
	update_option('xyz_lnap_application_lnarray', $ln_acc_tok_json);
	update_option('xyz_lnap_lnaf',0);

	header("Location:".admin_url('admin.php?page=linkedin-auto-publish-settings&msg=4'));
	exit();
	}
	else if (isset($ln_acc_tok_arr->error)&& isset($ln_acc_tok_arr->error_description))
	{
		header("Location:".admin_url('admin.php?page=linkedin-auto-publish-settings&ln_auth_err='.$ln_acc_tok_arr->error.':'.$ln_acc_tok_arr->error_description));
		exit();
	}
}

?>