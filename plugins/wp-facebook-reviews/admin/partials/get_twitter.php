<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://ljapps.com
 * @since      1.0.0
 *
 * @package    WP_Review_Pro
 * @subpackage WP_Review_Pro/admin/partials
 */
 
 //add thickbox
 add_thickbox();

     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
		
	$dbmsg = "";
	$html="";
	$currentgetappform= new stdClass();
	$currentgetappform->id="";
	$currentgetappform->title="";
	$currentgetappform->site_type="";
	$currentgetappform->query="";
	$currentgetappform->endpoint="";
	//$currentgetappform->cron="";
	//$currentgetappform->blocks="100";
	$currentgetappform->profile_img="";
	$currentgetappform->categories="";
	$currentgetappform->posts="";
	
	//db function variables
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpfb_gettwitter_forms';
	
	//form deleting and updating here---------------------------
	if(isset($_GET['taction'])){
		if(isset($_GET['tid'])){
			$tid = htmlentities($_GET['tid']);
			$tid = intval($tid);
			//for deleting
			if($_GET['taction'] == "del" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tdel_');
				//delete
				$wpdb->delete( $table_name, array( 'id' => $tid ), array( '%d' ) );
			}
			//for updating
			if($_GET['taction'] == "edit" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tedit_');
				//get form array
				$currentgetappform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
			}
			//for copying
			if($_GET['taction'] == "copy" && $_GET['tid'] > 0){
				//security
				check_admin_referer( 'tcopy_');
				//get form array
				$currentgetappform = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$tid );
				//add new template
				$array = (array) $currentgetappform;
				$array['title'] = $array['title'].'_copy';
				
				unset($array['id']);
				//print_r($array);
				//remove the id so it can be generated.
				$wpdb->insert( $table_name, $array );
				//$wpdb->show_errors();
				//$wpdb->print_error();
			}
		}
		
	}
	//------------------------------------------
	

	//form posting here--------------------------------
	//for twitter save keys
	$keystatus['ack'] = '';
	$keystatus['msg'] ='';
	$keystatus['html'] ='';
	//namespace for twitterclass
	use Abraham\TwitterOAuth\TwitterOAuth;
	if (isset($_POST['wprevpro_savecookie'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_cookie');
		$wprevpro_twitter_api_key = sanitize_text_field($_POST['wprevpro_twitter_api_key']);
		$wprevpro_twitter_api_key = trim($wprevpro_twitter_api_key);
		update_option( 'wprevfb_twitterapi_key', $wprevpro_twitter_api_key );
		
		$wprevpro_twitter_api_key_secret = sanitize_text_field($_POST['wprevpro_twitter_api_key_secret']);
		$wprevpro_twitter_api_key_secret = trim($wprevpro_twitter_api_key_secret);
		update_option( 'wprevfb_twitterapi_key_secret', $wprevpro_twitter_api_key_secret );
		
		$wprevpro_twitter_api_token = sanitize_text_field($_POST['wprevpro_twitter_api_token']);
		$wprevpro_twitter_api_token = trim($wprevpro_twitter_api_token);
		update_option( 'wprevfb_twitterapi_token', $wprevpro_twitter_api_token );
		
		$wprevpro_twitter_api_token_secret = sanitize_text_field($_POST['wprevpro_twitter_api_token_secret']);
		$wprevpro_twitter_api_token_secret = trim($wprevpro_twitter_api_token_secret);
		update_option( 'wprevfb_twitterapi_token_secret', $wprevpro_twitter_api_token_secret );
		
		$justsavedkeys = true;
	} else {
		$justsavedkeys = false;
	}
	
	//check if keys are good they are already good.
	//check twitter keys
	$wprevpro_twitter_api_key = get_option('wprevfb_twitterapi_key');
	$wprevpro_twitter_api_key_secret = get_option('wprevfb_twitterapi_key_secret');
	$wprevpro_twitter_api_token = get_option('wprevfb_twitterapi_token');
	$wprevpro_twitter_api_token_secret = get_option('wprevfb_twitterapi_token_secret');
	
	//testing=============
	//$connection = new TwitterOAuth($wprevpro_twitter_api_key, $wprevpro_twitter_api_key_secret, $wprevpro_twitter_api_token, //$wprevpro_twitter_api_token_secret);
	//$statuses = $connection->get("search/tweets", ["q" => "twitterapi"]);
	
	//print_r($statuses);
	
	if($wprevpro_twitter_api_key!=''){
		$keysinput = true;
		$connection = new TwitterOAuth($wprevpro_twitter_api_key, $wprevpro_twitter_api_key_secret, $wprevpro_twitter_api_token, $wprevpro_twitter_api_token_secret);
		$content = $connection->get("account/verify_credentials");
		
		if ($connection->getLastHttpCode() == 200) {
			// get account worked, these keys work
			$keystatus['ack'] = 'success';
			$keystatus['msg'] ='';
			$keystatus['html'] ='<div style="color:green;">Success! These keys work.</div>';
		} else {
			// Handle error case
			$keystatus['ack'] = 'error';
			$temperrormessage = (array)$connection->getLastBody();
			$temperrormessage = json_encode($temperrormessage);
			$keystatus['msg'] = $temperrormessage;
			$keystatus['html'] = '<div style="color:red;">Oops! These keys do not work. '.$temperrormessage.'</div>';
		}
		
		//test premium sandbox search
		//$connection = new TwitterOAuth($wprevpro_twitter_api_key, $wprevpro_twitter_api_key_secret, $wprevpro_twitter_api_token, $wprevpro_twitter_api_token_secret);

		//$statuses = $connection->get("search/tweets", ["q" => '"Yellowhammer Brewing" OR "Yellowhammer Brewery" OR @YellowhammerAle',"count" => '100']);
		//$statuses = $connection->get("tweets/search/30day/dev", ["query" => 'LocalbyFlywheel -from:LocalbyFlywheel',"maxResults" => '100']);
		
//print_r( $statuses);
		
		
	} else {
		$keysinput = false;
	}
	
	//show or hide key form based on this above
	$keyformhideshow = 'wprevpro_hide';	//hide by default
	$keystatushtml = '';
	if($keysinput){		//only doing this if keys are input, else we are just showing form.
		if(!$justsavedkeys){
			if($keystatus['ack']=='success'){
				//saved keys earlier and they work, no need to show form
				$keyformhideshow = 'wprevpro_hide';
			} else if($keystatus['ack']=='error'){
				//saved keys earlier, but they didn't work
				$keyformhideshow = '';
				$keystatushtml = $keystatus['html'];
			}
		}
		if($justsavedkeys){		//just saved the keys
			if($keystatus['ack']=='success'){
				//saved keys and they work show form with success msg
				$keyformhideshow = '';
				$keystatushtml = $keystatus['html'];
			} else if($keystatus['ack']=='error'){
				//just saved keys, but they didn't work
				$keyformhideshow = '';
				$keystatushtml = $keystatus['html'];
			}
		}
	}
	
	
	

	//check to see if form has been posted.
	//if template id present then update database if not then insert as new.

	if (isset($_POST['wprevpro_submittemplatebtn'])){
		//verify nonce wp_nonce_field( 'wprevpro_save_template');
		check_admin_referer( 'wprevpro_save_template');
		//get form submission values and then save or update
		$t_id = sanitize_text_field($_POST['edittid']);
		$title = sanitize_text_field($_POST['wprevpro_template_title']);
		$site_type = "Twitter";
		$query = sanitize_text_field($_POST['wprevpro_query']);
		
		$endpoint = sanitize_text_field($_POST['wprevpro_endpoint']);

		//$cron = sanitize_text_field($_POST['wprevpro_cron_setting']);
		//$blocks = sanitize_text_field($_POST['wprevpro_blocks']);
		//$blocks = intval($blocks);
		$blocks=100;
		
		//$last_name = sanitize_text_field($_POST['wprevpro_last_name']);
		$profile_img = sanitize_text_field($_POST['wprevpro_profile_img']);

		$timenow = time();
		
		
		//+++++++++need to sql escape using prepare+++++++++++++++++++
		//+++++++++++++++++++++++++++++++++++++++++++++++++++++
		//insert or update
			$data = array( 
				'title' => "$title",
				'site_type' => "$site_type",
				'created_time_stamp' => "$timenow",
				'query' => "$query",
				'endpoint' => "$endpoint",
				'blocks' => "$blocks",
				'profile_img' => "$profile_img",
				'categories' => "",
				'posts' => "",
				);
				//print_r($data);
			$format = array( 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				); 

		if($t_id==""){
			//print_r($data);
			//insert
			$insertrow = $wpdb->insert( $table_name, $data, $format );
			if(!$insertrow){
			//$wpdb->show_errors();
			//$wpdb->print_error();
			$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible">'.__('<p><strong>Oops! This form could not be inserted in to the database.</br> -'.$wpdb->show_errors().' -'.$wpdb->print_error().' </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-fb-reviews').'</div>';
			}
			//die();
		} else {
			//update
			//print_r($data);
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $t_id ), $format, array( '%d' ));
			//$wpdb->show_errors();
			//$wpdb->print_error();
			if($updatetempquery>0){
				$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="updated settings-error notice is-dismissible">'.__('<p><strong>Form Updated!</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-fb-reviews').'</div>';
			} else {
				$dbmsg = $dbmsg.'<div id="setting-error-wprevpro_message" class="error settings-error notice is-dismissible">'.__('<p><strong>Oops! The Form could not be updated in the database. </strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>', 'wp-fb-reviews').'</div>';
			}
		}
		
		
	}

	//Get list of all current forms--------------------------
	$currentforms = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
	//-------------------------------------------------------

	
?>

<div class="">
<h1></h1>
<div class="wrap" id="wp_rev_maindiv">

	<img class="wprev_headerimg" src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png?id='.$this->_token; ?>">
	
<?php 
include("tabmenu.php");

//query args for export and import
$url_tempdownload = admin_url( 'admin-post.php?action=print_reviewfunnel.csv' );

	
?>
<div class="welcomecontainer wpfbr_margin10 w3-row-padding w3-section w3-stretch">

<div class="w3-col w3-container ">
<div class="welcomediv w3-white w3-border w3-border-light-gray2 w3-round-small">

<div class="w3-col wpfbr_margin10">
<div class="wprevpro_margin10">
	<a id="wprevpro_addnewtemplate" keycheck="<?php echo $keystatus['ack']; ?>" class="button dashicons-before dashicons-plus-alt"><?php _e('Add New Twitter Source', 'wp-fb-reviews'); ?></a>
	<a id="wprevpro_addnewapikey" class="button dashicons-before dashicons-plus-alt"><?php _e('Enter/Modify API Keys', 'wp-fb-reviews'); ?></a>

</div>
<div class='bordereddiv'><?php _e('Use this page to search for and download tweets about your business/product/service. Click the <b>"Add New Twitter Source"</b> button to get started. If you need to search for tweets older than 7 days, or you get API Key errors, then you will need to use the <b>"Enter/Modify API Keys"</b> button to enter your own keys.', 'wp-fb-reviews'); ?> </div>

<div id="apikeyformdiv" class="<?php echo $keyformhideshow; ?> wprevpro_margin10 bordered_form" id="login_cookie">
	    <form  action="?page=wpfb-get_twitter" method="post" name="logincookie" enctype="multipart/form-data">
		<table class="">
		<tbody>
			<tr class="wprevpro_row">
				<td scope="row" style="width:50%;">
				<div class="twitter_key_header"><?php _e('Consumer API Keys:', 'wp-fb-reviews'); ?></div>
				<div class="twitter_key_div"><?php _e('API Key:', 'wp-fb-reviews'); ?> <input class="inputrow100per" type="text" name="wprevpro_twitter_api_key" id="wprevpro_twitter_api_key" value="<?php echo esc_html(get_option('wprevfb_twitterapi_key')); ?>"></div>
				<div class="twitter_key_div"><?php _e('API Secret Key:', 'wp-fb-reviews'); ?> <input class="inputrow100per" type="text" name="wprevpro_twitter_api_key_secret" id="wprevpro_twitter_api_key_secret" value="<?php echo esc_html(get_option('wprevfb_twitterapi_key_secret')); ?>"></div>
				</td>
				<td scope="row" style="padding-left:10px;">
				<div class="twitter_key_header"><?php _e('Access token & access token secret:', 'wp-fb-reviews'); ?></div>
				<div class="twitter_key_div"><?php _e('Access Token:', 'wp-fb-reviews'); ?> <input class="inputrow100per" type="text" name="wprevpro_twitter_api_token" id="wprevpro_twitter_api_token" value="<?php echo esc_html(get_option('wprevfb_twitterapi_token')); ?>"></div>
				<div class="twitter_key_div"><?php _e('Access Token Secret:', 'wp-fb-reviews'); ?> <input class="inputrow100per" type="text" name="wprevpro_twitter_api_token_secret" id="wprevpro_twitter_api_token_secret" value="<?php echo esc_html(get_option('wprevfb_twitterapi_token_secret')); ?>"></div>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<td scope="row" style="padding-left:10px;" colspan=2>
				<p class="description">
				<?php _e('Twitter requires API Keys and Access Tokens to access their data. If you do not already have a Twitter Developer account, you\'ll need to create one. After that, you can create a Twitter application to get your keys and token. Instructions can be found on this <a href="https://ljapps.com/how-to-get-your-twitter-api-keys-access-tokens-access-premium-search-api/" target="_blank">page</a>.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			
			</tbody>
			</table>
				<?php 
				//security nonce
				wp_nonce_field( 'wprevpro_save_cookie');
				?>
			<input type="submit" name="wprevpro_savecookie" id="wprevpro_savecookie" class="button button-primary" value="<?php _e('Save', 'wp-fb-reviews'); ?>">
&nbsp;&nbsp;<a href="https://ljapps.com/how-to-get-your-twitter-api-keys-access-tokens-access-premium-search-api/" target="_blank" id="instr" name="instr" class="button-secondary "><?php _e('API Instructions', 'wp-fb-reviews'); ?></a>
<?php echo $keystatushtml; ?>
        </form>
</div>


  <div class="wprevpro_margin10" id="wprevpro_new_template">
<form name="newtemplateform" id="newtemplateform" action="?page=wpfb-get_twitter" method="post">
	<table class="wprevpro_margin10 form-table ">
		<tbody>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Form Title:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<input id="wprevpro_template_title" data-custom="custom" type="text" name="wprevpro_template_title" placeholder="" value="<?php echo esc_html($currentgetappform->title); ?>" required>
					<p class="description">
					<?php _e('Enter a unique name for these tweets. This would normally be the name of business/product/service the tweets are talking about.', 'wp-fb-reviews'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Search Terms (query):', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<input class="yelp_business_url" id="wprevpro_query" data-custom="custom" type="text" name="wprevpro_query" placeholder="" value='<?php echo esc_html($currentgetappform->query); ?>' required>
					<p class="description">
					<?php _e('The search terms to use in the query. 256 characters with 30-Day Sandbox tier, 128 with Full Archive Sandbox tier. See operators <a href="https://developer.twitter.com/en/docs/tweets/rules-and-filtering/overview/operators-by-product" target="_blank">here</a>.', 'wp-fb-reviews'); ?>		</p>
					<p class="description">
					<?php _e('Example: LocalbyFlywheel -from:LocalbyFlywheel -RT', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Search API:', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<select name="wprevpro_endpoint" id="wprevpro_endpoint">
					  <option value="7" <?php if($currentgetappform->endpoint=='7' || $currentgetappform->endpoint==''){echo "selected";} ?>>Standard 7-day endpoint</option>
					  <option value="30" <?php if($currentgetappform->endpoint=='30'){echo "selected";} ?>>30-day endpoint</option>
					  <option value="all" <?php if($currentgetappform->endpoint=='all'){echo "selected";} ?>>Full-archive endpoint </option>
					</select>
					<p class="description">
					<?php _e('This plugin uses the Twitter Premium Search API to look for tweets. <b>If you choose 30 day search or Full archive, you must enter your API Keys above.</b> Twitter gives you 250 free 30-day searches and 50 all-time searches a month. <br><b>Important:</b> You <b>MUST</b> Go <a href="https://developer.twitter.com/en/account/environments" target="_blank">here</a> and click the "Set up dev environment" button. Make sure you use the label "<b>wprevdev</b>" for the "Dev environment label" and select the same app as the keys you used above.', 'wp-fb-reviews'); ?>		</p>
				</td>
			</tr>
			<tr class="wprevpro_row">
				<th scope="row">
					<?php _e('Local Images', 'wp-fb-reviews'); ?>
				</th>
				<td>
					<input type="radio" name="wprevpro_profile_img" value="no" <?php if($currentgetappform->profile_img=='no' || $currentgetappform->profile_img==''){echo "checked";} ?>><?php _e('No', 'wp-fb-reviews'); ?>&nbsp;&nbsp;&nbsp;
					<input type="radio" name="wprevpro_profile_img" value="yes" <?php if($currentgetappform->profile_img=='yes' ){echo "checked";} ?>><?php _e('Yes', 'wp-fb-reviews'); ?>&nbsp;&nbsp;&nbsp;
					<p class="description">
					<?php _e('By default, images are referenced from the original source server. Set this to yes if you would like the plugin to try and save the images locally. This may not always work as the remote site might block the download.', 'wp-fb-reviews'); ?></p>
				</td>
			</tr>


		</tbody>
	</table>
	<?php 
	//security nonce
	wp_nonce_field( 'wprevpro_save_template');
	?>
	<input type="hidden" name="edittid" id="edittid"  value="<?php echo esc_html($currentgetappform->id); ?>">
	<input type="submit" name="wprevpro_submittemplatebtn" id="wprevpro_submittemplatebtn" class="button button-primary" value="<?php _e('Save', 'wp-fb-reviews'); ?>">
	<a id="wprevpro_addnewtemplate_cancel" class="button button-secondary"><?php _e('Cancel', 'wp-fb-reviews'); ?></a>
	</form>
</div>
  

<?php

//display message
echo $dbmsg;
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="40px" class="manage-column">'.__('ID', 'wp-fb-reviews').'</th>
					<th scope="col" class="manage-column">'.__('Title', 'wp-fb-reviews').'</th>
					<th scope="col" class="manage-column">'.__('Query', 'wp-fb-reviews').'</th>
					<th scope="col" width="115px" class="manage-column">'.__('Last Checked', 'wp-fb-reviews').'</th>
					<th scope="col" width="390px" class="manage-column">'.__('Action', 'wp-fb-reviews').'</th>
				</tr>
				</thead>
			<tbody id="appformstable">';
	if(count($currentforms)>0){
	foreach ( $currentforms as $currentform ) 
	{
	//remove query args we just used
	$urltrimmed = remove_query_arg( array('taction', 'id') );
		$tempeditbtn =  add_query_arg(  array(
			'taction' => 'edit',
			'tid' => "$currentform->id",
			),$urltrimmed);
			
		$url_tempeditbtn = wp_nonce_url( $tempeditbtn, 'tedit_');
			
		$tempdelbtn = add_query_arg(  array(
			'taction' => 'del',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
			
		$url_tempdelbtn = wp_nonce_url( $tempdelbtn, 'tdel_');
		
						//for copying
		$tempcopybtn = add_query_arg(  array(
			'taction' => 'copy',
			'tid' => "$currentform->id",
			),$urltrimmed) ;
		$url_tempcopybtn = wp_nonce_url( $tempcopybtn, 'tcopy_');
			
		$lastranon = '';
		if($currentform->last_ran>0){$lastranon = date("M j, Y",$currentform->last_ran);}
		
		//$fposts = addslashes($currentform->posts);
		$fposts = str_replace('"',"'",$currentform->posts);
		//$fcategories = addslashes($currentform->categories);
		$fcategories = str_replace('"',"'",$currentform->categories);
			
		$html .= '<tr id="'.$currentform->id.'">
				<th scope="col" class=" manage-column">'.esc_html($currentform->id).'</th>
				<th scope="col" class=" manage-column" style="min-width: 200px;"><b><span class="titlespan">'.esc_html($currentform->title).'</span></b></th>
				<th scope="col" class="tdquery manage-column">'.esc_html($currentform->query).'</th>
				<th scope="col" class=" manage-column">'.esc_html($lastranon).'</th>
				<th scope="col" class="manage-column" limage="'.esc_url($currentform->profile_img).'" fcats="'.esc_attr($fcategories).'" fposts="'.esc_attr($fposts).'" ftitle="'.esc_attr($currentform->title).'" epoint="'.esc_attr($currentform->endpoint).'" squery="'.esc_attr($currentform->query).'"><a href="'.$url_tempeditbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-generic">'.__('Edit', 'wp-fb-reviews').'</a> <a href="'.$url_tempdelbtn.'" class="rfbtn button button-secondary dashicons-before dashicons-trash">'.__('Delete', 'wp-fb-reviews').'</a> <a href="'.$url_tempcopybtn.'" class="rfbtn button button-secondary dashicons-before dashicons-admin-page">'.__('Copy', 'wp-fb-reviews').'</a> <span class="rfbtn button button-primary dashicons-before dashicons-star-filled retreviewsbtn"> '.__('Get Tweets', 'wp-fb-reviews').'</span></th>
			</tr>';
	}
	} else {
		$html .= '<tr><td colspan="5">'.__('You can create a Review Form to download tweets from Twitter! Once downloaded, they will show up on the Review List page of the plugin and you can display them on your website with a Review Template. Click the "Add New Twitter Source Page" button above to get started.', 'wp-fb-reviews').'</td></tr>';
	}
		$html .= '</tbody></table>';
echo $html;
//echo "<div></br>Coming Soon! You will be able to easily search and download twitter posts!</br></br></div>"; 

?>

<div id="retreivewspopupdiv" style="display:none;">
	<div id="tb_content_query">
	<input id="tb_content_query_input" data-custom="custom" type="text" name="tb_content_query_input" value="">&nbsp;<span class="button button-secondary updatequery"><?php _e('Update', 'wp-fb-reviews'); ?></span>
	</div>
	<div class="downloadrevsbtnspinner"></div>
	<table id="selecttweets" class="wp-list-table widefat striped posts">
	</table>
	<div class="ajaxmessagediv"></div>
	
</div>
					

	<div id="popup_review_list" class="popup-wrapper wprevpro_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
	

</div>
<?php
//echo "<br><br><br>";
//print_r($licensecheckarray);
?>
</div>
</div>
</div>
</div>
</div>
