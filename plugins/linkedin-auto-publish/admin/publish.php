<?php
if( !defined('ABSPATH') ){ exit();}
/*add_action('publish_post', 'xyz_lnap_link_publish');
add_action('publish_page', 'xyz_lnap_link_publish');
$xyz_lnap_future_to_publish=get_option('xyz_lnap_future_to_publish');

if($xyz_lnap_future_to_publish==1)
	add_action('future_to_publish', 'xyz_link_lnap_future_to_publish');

function xyz_link_lnap_future_to_publish($post){
	$postid =$post->ID;
	xyz_lnap_link_publish($postid);
}*/
add_action(  'transition_post_status',  'xyz_link_lnap_future_to_publish', 10, 3 );

function xyz_link_lnap_future_to_publish($new_status, $old_status, $post){

	if (isset($_GET['_locale']) && empty($_POST))
		return ;
	if(!isset($GLOBALS['lnap_dup_publish']))
		$GLOBALS['lnap_dup_publish']=array();

	$postid =$post->ID;
	$get_post_meta=get_post_meta($postid,"xyz_lnap",true);
	$get_post_meta_future_data=get_post_meta($postid,"xyz_lnap_future_to_publish",true);
	$lnpost_permission=get_option('xyz_lnap_lnpost_permission');
	if(isset($_POST['xyz_lnap_lnpost_permission']))
	{
		$lnpost_permission=$_POST['xyz_lnap_lnpost_permission'];
		if ( (isset($_POST['xyz_lnap_lnpost_permission']) && isset($_POST['xyz_lnap_ln_shareprivate'])) )
		{
			$futToPubDataArray=array( 'post_ln_permission'	=>	$lnpost_permission,
					'xyz_lnap_ln_shareprivate'	=>	$_POST['xyz_lnap_ln_shareprivate'],
					'xyz_lnap_lnpost_method'	=>	$_POST['xyz_lnap_lnpost_method'],
					'xyz_lnap_lnmessage'	=>	$_POST['xyz_lnap_lnmessage']);
			update_post_meta($postid, "xyz_lnap_future_to_publish", $futToPubDataArray);
		}
	}
	else
	{
		if ($lnpost_permission == 1) {
			if($new_status == 'publish')
			{
				if ($get_post_meta == 1 ) {
					return;
				}
			}
			else return;
		}
	}
	if($lnpost_permission == 1)
	{
		if($new_status == 'publish')
		{
		if(!in_array($postid,$GLOBALS['lnap_dup_publish'])) {
		    $GLOBALS['lnap_dup_publish'][]=$postid;
		    xyz_lnap_link_publish($postid);
		}
		}
	}

	}

/*$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');
$carr=explode(',', $xyz_lnap_include_customposttypes);
foreach ($carr  as $cstyps ) {
	add_action('publish_'.$cstyps, 'xyz_lnap_link_publish');

}*/

function xyz_lnap_link_publish($post_ID) {

	$_POST_CPY=$_POST;
	$_POST=stripslashes_deep($_POST);
	$xyz_lnap_ln_shareprivate=$xyz_lnap_lnpost_method=$lmessagetopost='';
	$get_post_meta_future_data=get_post_meta($post_ID,"xyz_lnap_future_to_publish",true);
	$lnpost_permission=get_option('xyz_lnap_lnpost_permission');
	if(isset($_POST['xyz_lnap_lnpost_permission']))
		$lnpost_permission=$_POST['xyz_lnap_lnpost_permission'];
	elseif(!empty($get_post_meta_future_data) && get_option('xyz_lnap_default_selection_edit')==2 )///select values from post meta
	{
		$lnpost_permission=$get_post_meta_future_data['post_ln_permission'];
		$xyz_lnap_ln_shareprivate=$get_post_meta_future_data['xyz_lnap_ln_shareprivate'];
		$xyz_lnap_lnpost_method=$get_post_meta_future_data['xyz_lnap_lnpost_method'];
		$lmessagetopost=$get_post_meta_future_data['xyz_lnap_lnmessage'];
	}

	if ($lnpost_permission != 1) {
		$_POST=$_POST_CPY;
		return ;
	} else if(( (isset($_POST['_inline_edit'])) || (isset($_REQUEST['bulk_edit'])) ) && (get_option('xyz_lnap_default_selection_edit') == 0))
	{
		$_POST=$_POST_CPY;
		return;
	}

	global $current_user;
	wp_get_current_user();

	////////////linkedin////////////

	$lnappikey=get_option('xyz_lnap_lnapikey');
	$lnapisecret=get_option('xyz_lnap_lnapisecret');
	if ($lmessagetopost=='')
	$lmessagetopost=get_option('xyz_lnap_lnmessage');
	if ($xyz_lnap_lnpost_method=='')
	$xyz_lnap_lnpost_method=get_option('xyz_lnap_lnpost_method');
	if(isset($_POST['xyz_lnap_lnmessage']))
		$lmessagetopost=$_POST['xyz_lnap_lnmessage'];
	if (isset($_POST['xyz_lnap_lnpost_method']))
		$xyz_lnap_lnpost_method=$_POST['xyz_lnap_lnpost_method'];
if ($xyz_lnap_ln_shareprivate=='')
  $xyz_lnap_ln_shareprivate=get_option('xyz_lnap_ln_shareprivate');
  if(isset($_POST['xyz_lnap_ln_shareprivate']))
  $xyz_lnap_ln_shareprivate=intval($_POST['xyz_lnap_ln_shareprivate']);

  // $xyz_lnap_ln_sharingmethod=get_option('xyz_lnap_ln_sharingmethod');
  // if(isset($_POST['xyz_lnap_ln_sharingmethod']))
  // 	$xyz_lnap_ln_sharingmethod=intval($_POST['xyz_lnap_ln_sharingmethod']);

  $lnaf=get_option('xyz_lnap_lnaf');

	////////////////////////
	$postpp= get_post($post_ID);global $wpdb;
	$entries0 = $wpdb->get_results($wpdb->prepare( 'SELECT user_nicename,display_name FROM '.$wpdb->base_prefix.'users WHERE ID=%d',$postpp->post_author));
	foreach( $entries0 as $entry ) {
		$user_nicename=$entry->user_nicename;
		$user_displayname=$entry->display_name;}

	if ($postpp->post_status == 'publish')
	{
		$posttype=$postpp->post_type;
		$ln_publish_status=array();

		if ($posttype=="page")
		{

			$xyz_lnap_include_pages=get_option('xyz_lnap_include_pages');
			if($xyz_lnap_include_pages==0)
			{$_POST=$_POST_CPY;return;}
		}

		else if($posttype=="post")
		{
			$xyz_lnap_include_posts=get_option('xyz_lnap_include_posts');
			if($xyz_lnap_include_posts==0)
			{
				$_POST=$_POST_CPY;return;
			}

			$xyz_lnap_include_categories=get_option('xyz_lnap_include_categories');
			if($xyz_lnap_include_categories!="All")
			{
				$carr1=explode(',',$xyz_lnap_include_categories);

				$defaults = array('fields' => 'ids');
				$carr2=wp_get_post_categories( $post_ID, $defaults );
				$retflag=1;
				foreach ($carr2 as $key=>$catg_ids)
				{
					if(in_array($catg_ids, $carr1))
						$retflag=0;
				}


				if($retflag==1)
				{$_POST=$_POST_CPY;return;}
			}
		}
		/*$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');
		 $carr=explode(',', $xyz_lnap_include_customposttypes);
		foreach ($carr  as $cstyps ) {
		add_action('publish_'.$cstyps, 'xyz_lnap_link_publish');

		}*/
		////
		else
		{

			$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');
			if($xyz_lnap_include_customposttypes!='')
			{

				$carr=explode(',', $xyz_lnap_include_customposttypes);

				if(!in_array($posttype, $carr))
				{
				$_POST=$_POST_CPY;return;
				}

			}
			else
			{
				$_POST=$_POST_CPY;return;
			}

		}
		$get_post_meta=get_post_meta($post_ID,"xyz_lnap",true);
		if($get_post_meta!=1)
			add_post_meta($post_ID, "xyz_lnap", "1");

		include_once ABSPATH.'wp-admin/includes/plugin.php';

		$pluginName = 'bitly/bitly.php';

		if (is_plugin_active($pluginName)) {
			remove_all_filters('post_link');
		}
		$link = get_permalink($postpp->ID);


		$xyz_lnap_apply_filters=get_option('xyz_lnap_apply_filters');
		$ar2=explode(",",$xyz_lnap_apply_filters);
		$con_flag=$exc_flag=$tit_flag=0;
		if(isset($ar2))
		{
			if(in_array(1, $ar2)) $con_flag=1;
			if(in_array(2, $ar2)) $exc_flag=1;
			if(in_array(3, $ar2)) $tit_flag=1;
		}

		$content = $postpp->post_content;
		$breaks = array("<br />","<br>","<br/>");
		$content = str_ireplace($breaks, "\r\n", $content);
		if($con_flag==1)
			$content = apply_filters('the_content', $content);
		$content = html_entity_decode($content, ENT_QUOTES, get_bloginfo('charset'));
		$excerpt = $postpp->post_excerpt;
		if($exc_flag==1)
			$excerpt = apply_filters('the_excerpt', $excerpt);
		$excerpt = html_entity_decode($excerpt, ENT_QUOTES, get_bloginfo('charset'));
		$content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
		$content=  preg_replace("/\\[caption.*?\\].*?\\[.caption\\]/is", '', $content);
		$content = preg_replace('/\[.+?\]/', '', $content);
		$excerpt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $excerpt);

		if($excerpt=="")
		{
			if($content!="")
			{
				$content1=$content;
				$content1=strip_tags($content1);
				$content1=strip_shortcodes($content1);

				$excerpt=implode(' ', array_slice(explode(' ', $content1), 0, 50));
			}
		}
		else
		{
			$excerpt=strip_tags($excerpt);
			$excerpt=strip_shortcodes($excerpt);
		}
		$description = $content;

		$description_org=$description;
		$attachmenturl=xyz_lnap_getimage($post_ID, $postpp->post_content);
		if(!empty($attachmenturl))
			$image_found=1;
		else
			$image_found=0;

		$name = $postpp->post_title;
		$caption = html_entity_decode(get_bloginfo('title'), ENT_QUOTES, get_bloginfo('charset'));

		if($tit_flag==1)
			$name = apply_filters('the_title', $name);

		$name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
		$name=strip_tags($name);
		$name=strip_shortcodes($name);

		$description=strip_tags($description);
		$description=strip_shortcodes($description);
	   	$description=str_replace("&nbsp;","",$description);
		$excerpt=str_replace("&nbsp;","",$excerpt);

		if((($lnappikey!="" && $lnapisecret!=""&& get_option('xyz_lnap_ln_api_permission')!=2)|| get_option('xyz_lnap_ln_api_permission')==2) &&  $lnpost_permission==1 && $lnaf==0 && (( get_option('xyz_lnap_ln_share_post_company')!='')|| get_option('xyz_lnap_lnshare_to_profile')==1))
		{
			$ln_api_count=0;$api_exceed_err_ln=0;$remaining_api_count=0;
			$contentln=array();

			$description_li=xyz_lnap_string_limit($description, 100);
// 			$caption_li=xyz_lnap_string_limit($caption, 200);
			$name_li=xyz_lnap_string_limit($name, 200);
			$message1=str_replace('{POST_TITLE}', $name, $lmessagetopost);
			$message2=str_replace('{BLOG_TITLE}', $caption,$message1);
			$message3=str_replace('{PERMALINK}', $link, $message2);
			$message4=str_replace('{POST_EXCERPT}', $excerpt, $message3);
			$message5=str_replace('{POST_CONTENT}', $description, $message4);
			$message5=str_replace('{USER_NICENAME}', $user_nicename, $message5);
			$message5=str_replace('{USER_DISPLAY_NAME}', $user_displayname, $message5);
			$publish_time=get_the_time(get_option('date_format'),$post_ID );
			$message5=str_replace('{POST_PUBLISH_DATE}', $publish_time, $message5);
			$message5=str_replace('{POST_ID}', $post_ID, $message5);
			$message5=str_replace("&nbsp;","",$message5);

			$sanitizedDescription = preg_replace_callback('/([\(\)\{\}\[\]])|([@*<>\\\\\_~])/m', function ($matches) {
			    return '\\'.$matches[0];
				}, $message5); //added for escaping special characters not supported by linkedin versioned api
		$message5=$sanitizedDescription;
		$message5=xyz_lnap_string_limit($message5, 3000);
		$xyz_lnap_application_lnarray=get_option('xyz_lnap_application_lnarray');
		$xyz_lnap_ln_api_permission=get_option('xyz_lnap_ln_api_permission');
		$xyz_lnap_smapsoln_sec_key=get_option('xyz_lnap_secret_key');
		$xyz_lnap_smapsoln_userid=get_option('xyz_lnap_smapsoln_userid');
		$xyz_lnap_xyzscripts_userid=get_option('xyz_lnap_xyzscripts_user_id');

		$ln_publish_status=array();$image_upload_err='';
		if ($xyz_lnap_ln_api_permission!=2)
		{
		$ln_acc_tok_arr=json_decode($xyz_lnap_application_lnarray);
		$xyz_lnap_application_lnarray=$ln_acc_tok_arr->access_token;

		$ObjLinkedin = new LNAPLinkedInOAuth2($xyz_lnap_application_lnarray);//print_r($ObjLinkedin);die;
		}
		elseif ($xyz_lnap_ln_api_permission==2){
			$xyz_lnap_token_fetch=1;
			$post_details=array('xyz_smap_userid'=>$xyz_lnap_smapsoln_userid,
					'xyz_smap_xyzscripts_userid'=>$xyz_lnap_xyzscripts_userid,
					'xyz_smap_token_fetch'=>$xyz_lnap_token_fetch
			);
			$url=XYZ_SMAP_SOLUTION_LN_PUBLISH_URL.'api/v2/publish.php';
			$result=xyz_lnap_post_to_smap_api($post_details,$url,$xyz_lnap_smapsoln_sec_key);
			$result=json_decode($result);
			if(!empty($result))
			{
				if (isset($result->status))
				{
					if($result->status==0)
					{
						$err=$result->msg;
						$ln_publish_status["new"]="<span style=\"color:red\">".$err."</span><br/><span style=\"color:#21759B\">No. of api calls used: ".$ln_api_count."</span>";
					}
					elseif ($result->status==1 && isset($result->access_token))
					{
						$xyz_lnap_application_lnarray=$result->access_token;
						$ObjLinkedin = new LNAPLinkedInOAuth2($xyz_lnap_application_lnarray);
						$remaining_api_count_ln=$result->ln_api_count;
					}
				}
			}
			//////////////////////////////////////////////////
		}
		$message5 = preg_replace('/(\n\s*){2,}/', "\n\n", $message5);
			$contentln['author'] ='urn:li:person:'.get_option('xyz_lnap_lnappscoped_userid');
			$contentln['lifecycleState'] ='PUBLISHED';
			$contentln['commentary']=$message5;
			$contentln['visibility']='PUBLIC';//new
			// $contentlnauthor ='urn:li:person:'.get_option('xyz_lnap_lnappscoped_userid');
				$ln_text=array('text'=>$message5);
			$ln_title=array('text'=>$name_li);
			 $contentln['distribution']=array('feedDistribution'=>'MAIN_FEED','targetEntities'=>[],'thirdPartyDistributionChannels'=>[]);
			// $contentln['commentary']='commentary text msgss to ln';
				if ($xyz_lnap_lnpost_method==1 || (empty($attachmenturl) && $xyz_lnap_lnpost_method==3))//if simple text message
			{
				if($xyz_lnap_ln_api_permission==2)
				{
					$required_api_count_ln=1;
					if (($remaining_api_count_ln-$required_api_count_ln)<1)
					{
						$api_exceed_err_ln=1;goto api_exceed_err_ln;
					}
				}
				//$distribution=array('feedDistribution'=>'MAIN_FEED','targetEntities'=>[],'thirdPartyDistributionChannels'=>[]);
				$contentln['commentary']=$message5;
				// $contentln['distribution']=$distribution;
			}
			elseif ($xyz_lnap_lnpost_method==2)//link share
			{
				//$distribution=array('feedDistribution'=>'MAIN_FEED','targetEntities'=>[],'thirdPartyDistributionChannels'=>[]);
				$contentln['commentary']=$message5;
				// $contentln['distribution']=$distribution;
				// if (!empty($attachmenturl))
				if (!empty($attachmenturl) && get_option('xyz_lnap_lnshare_to_profile')==1)
			{
				if($xyz_lnap_ln_api_permission==2)
				{
						$required_api_count_ln=3;//Change it as 4 if it counts check_status_linkedin_asset() step
					if (($remaining_api_count_ln-$required_api_count_ln)<1)
					{
						$api_exceed_err_ln=1;goto api_exceed_err_ln;
					}
				}
					$registerupload1['initializeUploadRequest']=array('owner'=>'urn:li:person:'.get_option('xyz_lnap_lnappscoped_userid'));
					$arrResponse = $ObjLinkedin->getImagePostResponses($registerupload1);//print_r(json_encode($arrResponse));die;
					$ln_api_count++;
					$uploadUrl='';
					if (isset($arrResponse['value']['uploadUrl']) && isset($arrResponse['value']['image']))
				{
						$uploadUrl=$arrResponse['value']['uploadUrl'];
						$image_parameter=$arrResponse['value']['image'];
						$image_param= substr($image_parameter,13);
				}
					if ($uploadUrl!='')
					{
						$arrResponse = $ObjLinkedin->getUploadUrlResponses($uploadUrl,$attachmenturl,array());
						$ln_api_count++;
					}
					$contentln['content']=array('article'=>array('source'=>$link,'thumbnail'=>$image_parameter,'title'=>$name_li,'description'=>$description_li));
					$status_check=$ObjLinkedin->check_status_linkedin_asset('https://api.linkedin.com/rest/assets/'.$image_param);
					$ln_api_count++;
				}
				else if(empty($attachmenturl))
				{
					if($xyz_lnap_ln_api_permission==2)
					{
						$required_api_count_ln=1;
						if (($remaining_api_count_ln-$required_api_count_ln)<1)
						{
							$api_exceed_err_ln=1;goto api_exceed_err_ln;
						}
					}
					$contentln['content']=array('article'=>array('source'=>$link,'title'=>$name_li,'description'=>$description_li));
				}
				update_post_meta($post_ID, "xyz_lnap_insert_og", "1");
			}
		$ln_publish_status["new"]='';
			if (get_option('xyz_lnap_lnshare_to_profile')==1)
			{
				if ($xyz_lnap_lnpost_method==3)//Text message with image
			{
 				$image_upload_flag=0;
				if(!empty($attachmenturl))
				{
					if($xyz_lnap_ln_api_permission==2)
					{
						$required_api_count_ln=4;//crosscheck count
						if (($remaining_api_count_ln-$required_api_count_ln)<1)
						{
							$api_exceed_err_ln=1;goto api_exceed_err_ln;
						}
					}
					//if($xyz_lnap_ln_api_permission!=2)
					//{
					$registerupload1['initializeUploadRequest']=array('owner'=>'urn:li:person:'.get_option('xyz_lnap_lnappscoped_userid'));
					$arrResponse = $ObjLinkedin->getImagePostResponses($registerupload1);
						$ln_api_count++;
						$urn_li_digitalmediaAsset=$uploadUrl='';
					if (isset($arrResponse['value']['uploadUrl']) && isset($arrResponse['value']['image']))
						{
						$uploadUrl=$arrResponse['value']['uploadUrl'];
						$image_parameter=$arrResponse['value']['image'];
						$image_param= substr($image_parameter,13);
						}
						if ($uploadUrl!='')
						{
							$arrResponse = $ObjLinkedin->getUploadUrlResponses($uploadUrl,$attachmenturl,array());
							$ln_api_count++;
						$cont=array('media'=>array('title'=>$name_li,'id'=>$image_parameter));
						$contentln['commentary']=$message5;
						$contentln['content']=$cont;
						$status_check=$ObjLinkedin->check_status_linkedin_asset('https://api.linkedin.com/rest/assets/'.$image_param);
							$ln_api_count++;
							$upload_status_arr=$status_check['recipes'][0];
							if (isset($upload_status_arr['status']) && ($upload_status_arr['status'] =="AVAILABLE" || $upload_status_arr['status'] =="PROCESSING"))
							{
								$image_upload_flag=1;
							}
							else
							{
								$ln_image_status='';
								if (isset($upload_status_arr['status']))
									$ln_image_status="-upload status:".$upload_status_arr['status'];
									$image_upload_err.='<br/><span style="color:red">Image upload failed '.$ln_image_status.'</span>';
							}
						}
						else {
								$image_upload_err.='<br/><span style="color:red">Image Upload Failed</span>';
						}
				}
			}
				if($xyz_lnap_ln_shareprivate==1)
			{
				$contentln['Visibility']='CONNECTIONS';
				}
				else
				{
				$contentln['visibility']='PUBLIC';
				}
				try{
				$response2 = $ObjLinkedin->shareStatus($contentln);
				$ln_api_count++;
				////////////////////////////////

				if (isset($response2) && !empty($response2)){
					$response_array = explode("\n",$response2);//print_r($response_array);die;
					$post_id_response='';$error_message='';
					foreach ($response_array as $key => $value)
					{
						$splited_array= explode(":",$value,2);
						if(strcasecmp($splited_array[0],'x-restli-id')==0){//If success it contains response header x-restli-id that contains the Post ID
								$post_id_response=trim($splited_array[1]);
								break;
				}
					 else if(stripos($value,"code")>0 && stripos($value,"status")>0)// If error then a response message will be retured
				{
							$error_message_array=json_decode($value);
							$error_message=$error_message_array->message;
							break;
				}

					}
					if(empty($error_message) && empty($post_id_response))
					{
						list($headers, $body) = explode("\r\n\r\n", $response2, 2);

						// Parse the headers into an associative array
						$headerLines = explode("\r\n", $headers);
						$headersArray = [];

						foreach ($headerLines as $line) {
								$parts = explode(': ', $line, 2);
								if (count($parts) === 2) {
										$headerName = $parts[0];
										$headerValue = $parts[1];
										$headersArray[$headerName] = $headerValue;
								}
						}

						// Decode the JSON body into an associative array
						$bodyArray = json_decode($body, true);

						$message="Not Available";	$status = '0:';
						if(isset($bodyArray['message']))
						$message = $bodyArray['message'];
						if(isset($bodyArray['message']))
						$status = $bodyArray['status'];
						$ln_publish_status["new"].=	"<span style=\"color:red\"> Profile:".$status.$message.".</span><br/>";

					}
				}

				if (isset($post_id_response) && !empty($post_id_response)){
					$linkedin_post="www.linkedin.com/feed/update/".$post_id_response;
					// $linkedin_post="https://www.linkedin.com/feed/update/urn:li:share:".$image_param;
					$post_link='<br/><span style="color:#21759B;text-decoration:underline;"><a  href="https://'.$linkedin_post.'">View Post</a></span>';
					$ln_publish_status["new"].="<span style=\"color:green\">profile:Success.</span>".$post_link;
				}
				else if(isset($error_message) && !empty($error_message))
				{
						$ln_publish_status["new"].="<span style=\"color:red\">profile: ".$error_message.".</span>";
				}
				if ($image_upload_err!='')
					$ln_publish_status["new"].=$image_upload_err;

				}
				catch(Exception $e)
				{
				$ln_publish_status["new"].=$e->getMessage();
				}
			}
			////////////////////////////////////////////////////////////////////////////////////////////////
			$xyz_lnap_ln_company_id1=$ln_publish_status_comp=array();$ln_publish_status_comp["new"]='';
			if(get_option('xyz_lnap_ln_share_post_company')!='')//company
				$xyz_lnap_ln_company_id1=explode(",",get_option('xyz_lnap_ln_share_post_company'));
			if (!empty($xyz_lnap_ln_company_id1)){
				foreach ($xyz_lnap_ln_company_id1 as $xyz_lnap_ln_company_id)
				{
							$contentln['lifecycleState'] ='PUBLISHED';
							$contentln['author'] ='urn:li:organization:'.$xyz_lnap_ln_company_id;
							$contentln['visibility']='PUBLIC';
							$contentln['commentary']=$message5;
							if($xyz_lnap_lnpost_method==2 )
							{
							if (!empty($attachmenturl))
							{
								if($xyz_lnap_ln_api_permission==2)
								{
									$required_api_count_ln=3;
									if (($remaining_api_count_ln-$required_api_count_ln)<1)
									{
										$api_exceed_err_ln=1;goto api_exceed_err_ln;
									}
								}
								$registerupload1['initializeUploadRequest']=array('owner'=>'urn:li:organization:'.$xyz_lnap_ln_company_id);
								$arrResponse = $ObjLinkedin->getImagePostResponses($registerupload1);//print_r(json_encode($arrResponse));die;
								$ln_api_count++;
								$uploadUrl='';
								if (isset($arrResponse['value']['uploadUrl']) && isset($arrResponse['value']['image']))
								{
									$uploadUrl=$arrResponse['value']['uploadUrl'];
									$image_parameter=$arrResponse['value']['image'];
									$image_param= substr($image_parameter,13);
								}
								if ($uploadUrl!='')
								{
									$arrResponse = $ObjLinkedin->getUploadUrlResponses($uploadUrl,$attachmenturl,array());
									$ln_api_count++;
								}
								$contentln['content']=array('article'=>array('source'=>$link,'thumbnail'=>$image_parameter,'title'=>$name_li,'description'=>$description_li));
								$status_check=$ObjLinkedin->check_status_linkedin_asset('https://api.linkedin.com/rest/assets/'.$image_param);
								$ln_api_count++;
							}
							else if(empty($attachmenturl))
										{
											if($xyz_lnap_ln_api_permission==2)
											{
												$required_api_count_ln=1;
												if (($remaining_api_count_ln-$required_api_count_ln)<1)
												{
													$api_exceed_err_ln=1;goto api_exceed_err_ln;
												}
											}
											$contentln['content']=array('article'=>array('source'=>$link,'title'=>$name_li,'description'=>$description_li));
										}
										update_post_meta($post_ID, "xyz_lnap_insert_og", "1");
									}

							if ($xyz_lnap_lnpost_method==3)//Text with Image
							{
								$image_upload_flag=0;
						if(!empty($attachmenturl))
						{
							if($xyz_lnap_ln_api_permission==2)
							{
										$required_api_count_ln=1;
								if (($remaining_api_count_ln-$required_api_count_ln)<1)
								{
									$api_exceed_err_ln=1;goto api_exceed_err_ln;
								}
							}
									$registerupload1['initializeUploadRequest']=array('owner'=>'urn:li:organization:'.$xyz_lnap_ln_company_id);
									$arrResponse = $ObjLinkedin->getImagePostResponses($registerupload1);
								$ln_api_count++;
									if (isset($arrResponse['value']['uploadUrl']) && isset($arrResponse['value']['image']))
								{
										$uploadUrl=$arrResponse['value']['uploadUrl'];
										$image_parameter=$arrResponse['value']['image'];
										$image_param= substr($image_parameter,13);
								}
								if ($uploadUrl!='')
								{
									$arrResponse = $ObjLinkedin->getUploadUrlResponses($uploadUrl,$attachmenturl,array());
									$ln_api_count++;
										// $distribution=array('feedDistribution'=>'MAIN_FEED','targetEntities'=>[],'thirdPartyDistributionChannels'=>[]);
										$cont=array('media'=>array('title'=>$name_li,'id'=>$image_parameter));
										$contentln['commentary']=$message5;
										// $contentln['distribution']=$distribution;
										$contentln['content']=$cont;
										$status_check=$ObjLinkedin->check_status_linkedin_asset('https://api.linkedin.com/rest/assets/'.$image_param);
									$ln_api_count++;
									$upload_status_arr=$status_check['recipes'][0];
									if (isset($upload_status_arr['status']) && ( $upload_status_arr['status'] =="AVAILABLE" || $upload_status_arr['status'] =="PROCESSING"))
									{
										$image_upload_flag=1;
									}
									else
									{
										$ln_image_status='';
										if (isset($upload_status_arr['status']))
											$ln_image_status="-upload status:".$upload_status_arr['status'];
											$image_upload_err.='<br/><span style="color:red">Image upload failed '.$ln_image_status.'</span>';
									}
								}
								else {
									$image_upload_err.='<br/><span style="color:red">Image Upload Failed</span>';
						}
					}
					}
					try
						{

							$response2 = $ObjLinkedin->shareStatus($contentln);
							$ln_api_count++;
							if (isset($response2) && !empty($response2)){
								$response_array = explode("\n",$response2);//print_r($response_array);die;
								$post_id_response='';$error_message='';
								foreach ($response_array as $key => $value)
								{
									$splited_array= explode(":",$value,2);
									if(strcasecmp($splited_array[0],'x-restli-id')==0){//If success it contains response header x-restli-id that contains the Post ID
											$post_id_response=trim($splited_array[1]);
											break;
							}
								 else if(stripos($value,"code")>0 && stripos($value,"status")>0)// If error then a response message will be retured
							{
										$error_message_array=json_decode($value);
										$error_message=$error_message_array->message;
										break;
							}

								}
								if(empty($error_message) && empty($post_id_response))
								{
									list($headers, $body) = explode("\r\n\r\n", $response2, 2);

									// Parse the headers into an associative array
									$headerLines = explode("\r\n", $headers);
									$headersArray = [];

									foreach ($headerLines as $line) {
									    $parts = explode(': ', $line, 2);
									    if (count($parts) === 2) {
									        $headerName = $parts[0];
									        $headerValue = $parts[1];
									        $headersArray[$headerName] = $headerValue;
									    }
									}

									// Decode the JSON body into an associative array
									$bodyArray = json_decode($body, true);

									$message="Not Available";	$status = '0:';
									if(isset($bodyArray['message']))
									$message = $bodyArray['message'];
									if(isset($bodyArray['message']))
									$status = $bodyArray['status'];
									$ln_publish_status_comp["new"].=	"<br/><span style=\"color:red\"> company/".$xyz_lnap_ln_company_id.":".$status.$message.".</span><br/>";

								}
							}
							if (isset($post_id_response) && !empty($post_id_response)){
								$linkedin_post="www.linkedin.com/feed/update/".$post_id_response;
								// $linkedin_post="https://www.linkedin.com/feed/update/urn:li:share:".$image_param;
								$post_link='<br/><span style="color:#21759B;text-decoration:underline;"><a  href="https://'.$linkedin_post.'">View Post</a></span>';
								$ln_publish_status_comp["new"].="<br/><span style=\"color:green\">company/".$xyz_lnap_ln_company_id." :Success.</span>".$post_link;
							}
							else if(isset($error_message) && !empty($error_message))
							{
									$ln_publish_status_comp["new"].="<br/><span style=\"color:red\">company/".$xyz_lnap_ln_company_id.": ".$error_message.".</span>";
							}
							if ($image_upload_err!='')
								$ln_publish_status_comp["new"].=$image_upload_err;
					}
					catch(Exception $e)
					{
						$ln_publish_status_comp["new"].="<br/><span style=\"color:red\">company/".$xyz_lnap_ln_company_id.":".$e->getMessage().".</span><br/>";
					}
					//}
				}
			}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////

			$ln_publish_status_insert='';
			if(!empty($ln_publish_status['new']))
				$ln_publish_status_insert.=$ln_publish_status['new'];
				if(isset($ln_publish_status_comp["new"]))
					$ln_publish_status_insert.=$ln_publish_status_comp["new"];


			if($xyz_lnap_ln_api_permission==2)
			$ln_publish_status_insert.="<span style=\"color:#21759B\">No. of api calls used: ".$ln_api_count."</span>";
		api_exceed_err_ln:
		if($api_exceed_err_ln==1){
			$ln_publish_status_insert.="<span style=\"color:red\"> Daily API count limit exceeded,only '.$remaining_api_count.' api calls left.</span>";//1;
			}
			$ln_publish_status_insert=serialize($ln_publish_status_insert);
		if($xyz_lnap_ln_api_permission==2)
			{
			$xyz_lnap_token_fetch=0;
			$post_details=array('xyz_smap_userid'=>$xyz_lnap_smapsoln_userid,
					'xyz_smap_xyzscripts_userid'=>$xyz_lnap_xyzscripts_userid,
					'xyz_smap_token_fetch'=>$xyz_lnap_token_fetch,
						'ln_response_from_plugin'=>$ln_publish_status_insert,
						'ln_api_count_from_plugin'=>$ln_api_count
				);
				$url=XYZ_SMAP_SOLUTION_LN_PUBLISH_URL.'api/v2/publish.php';
			$result=xyz_lnap_post_to_smap_api($post_details,$url,$xyz_lnap_smapsoln_sec_key);
			}

		$time=time();
		$post_ln_options=array(
				'postid'	=>	$post_ID,
				'acc_type'	=>	"Linkedin",
				'publishtime'	=>	$time,
				'status'	=>	$ln_publish_status_insert
		);

		$update_opt_array=array();

		$arr_retrive=(get_option('xyz_lnap_post_logs'));

		$update_opt_array[0]=isset($arr_retrive[0]) ? $arr_retrive[0] : '';
		$update_opt_array[1]=isset($arr_retrive[1]) ? $arr_retrive[1] : '';
		$update_opt_array[2]=isset($arr_retrive[2]) ? $arr_retrive[2] : '';
		$update_opt_array[3]=isset($arr_retrive[3]) ? $arr_retrive[3] : '';
		$update_opt_array[4]=isset($arr_retrive[4]) ? $arr_retrive[4] : '';
		$update_opt_array[5]=isset($arr_retrive[5]) ? $arr_retrive[5] : '';
		$update_opt_array[6]=isset($arr_retrive[6]) ? $arr_retrive[6] : '';
		$update_opt_array[7]=isset($arr_retrive[7]) ? $arr_retrive[7] : '';
		$update_opt_array[8]=isset($arr_retrive[8]) ? $arr_retrive[8] : '';
		$update_opt_array[9]=isset($arr_retrive[9]) ? $arr_retrive[9] : '';

		array_shift($update_opt_array);
		array_push($update_opt_array,$post_ln_options);
		update_option('xyz_lnap_post_logs', $update_opt_array);


		}
	}

	$_POST=$_POST_CPY;
}

?>
